<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\GoogleAuthenticator;

/**
 * @see https://github.com/google/google-authenticator/wiki/Key-Uri-Format
 */
final class GoogleAuthenticator implements GoogleAuthenticatorInterface
{
    /**
     * @var int
     */
    private $passCodeLength;

    /**
     * @var int
     */
    private $secretLength;

    /**
     * @var int
     */
    private $pinModulo;

    /**
     * @var \DateTimeInterface
     */
    private $instanceTime;

    /**
     * @var int
     */
    private $codePeriod;

    /**
     * @var int
     */
    private $periodSize = 30;

    public function __construct(int $passCodeLength = 6, int $secretLength = 10, ?\DateTimeInterface $instanceTime = null, int $codePeriod = 30)
    {
        /*
         * codePeriod is the duration in seconds that the code is valid.
         * periodSize is the length of a period to calculate periods since Unix epoch.
         * periodSize cannot be larger than the codePeriod.
         */

        $this->passCodeLength = $passCodeLength;
        $this->secretLength = $secretLength;
        $this->codePeriod = $codePeriod;
        $this->periodSize = $codePeriod < $this->periodSize ? $codePeriod : $this->periodSize;
        $this->pinModulo = 10 ** $passCodeLength;
        $this->instanceTime = $instanceTime ?? new \DateTimeImmutable();
    }

    /**
     * @param string $secret
     * @param string $code
     * @param int    $discrepancy
     */
    public function checkCode($secret, $code, $discrepancy = 1): bool
    {
        /**
         * Discrepancy is the factor of periodSize ($discrepancy * $periodSize) allowed on either side of the
         * given codePeriod. For example, if a code with codePeriod = 60 is generated at 10:00:00, a discrepancy
         * of 1 will allow a periodSize of 30 seconds on either side of the codePeriod resulting in a valid code
         * from 09:59:30 to 10:00:29.
         *
         * The result of each comparison is stored as a timestamp here instead of using a guard clause
         * (https://refactoring.com/catalog/replaceNestedConditionalWithGuardClauses.html). This is to implement
         * constant time comparison to make side-channel attacks harder. See
         * https://cryptocoding.net/index.php/Coding_rules#Compare_secret_strings_in_constant_time for details.
         * Each comparison uses hash_equals() instead of an operator to implement constant time equality comparison
         * for each code.
         */
        $periods = floor($this->codePeriod / $this->periodSize);

        $result = 0;
        for ($i = -$discrepancy; $i < $periods + $discrepancy; ++$i) {
            $dateTime = new \DateTimeImmutable('@'.($this->instanceTime->getTimestamp() - ($i * $this->periodSize)));
            $result = hash_equals($this->getCode($secret, $dateTime), $code) ? $dateTime->getTimestamp() : $result;
        }

        return $result > 0;
    }

    /**
     * NEXT_MAJOR: add the interface typehint to $time and remove deprecation.
     *
     * @param string                                   $secret
     * @param float|string|int|\DateTimeInterface|null $time
     */
    public function getCode($secret, /* \DateTimeInterface */ $time = null): string
    {
        if (null === $time) {
            $time = $this->instanceTime;
        }

        if ($time instanceof \DateTimeInterface) {
            $timeForCode = floor($time->getTimestamp() / $this->periodSize);
        } else {
            @trigger_error(
                'Passing anything other than null or a DateTimeInterface to $time is deprecated as of 2.0 '.
                'and will not be possible as of 3.0.',
                \E_USER_DEPRECATED
            );
            $timeForCode = $time;
        }

        $base32 = new FixedBitNotation(5, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567', true, true);
        $secret = $base32->decode($secret);

        $timeForCode = str_pad(pack('N', $timeForCode), 8, \chr(0), \STR_PAD_LEFT);

        $hash = hash_hmac('sha1', $timeForCode, $secret, true);
        $offset = \ord(substr($hash, -1));
        $offset &= 0xF;

        $truncatedHash = $this->hashToInt($hash, $offset) & 0x7FFFFFFF;

        return str_pad((string) ($truncatedHash % $this->pinModulo), $this->passCodeLength, '0', \STR_PAD_LEFT);
    }

    /**
     * NEXT_MAJOR: Remove this method.
     *
     * @param string $user
     * @param string $hostname
     * @param string $secret
     *
     * @deprecated deprecated as of 2.1 and will be removed in 3.0. Use Sonata\GoogleAuthenticator\GoogleQrUrl::generate() instead.
     */
    public function getUrl($user, $hostname, $secret): string
    {
        @trigger_error(sprintf(
            'Using %s() is deprecated as of 2.1 and will be removed in 3.0. '.
            'Use Sonata\GoogleAuthenticator\GoogleQrUrl::generate() instead.',
            __METHOD__
        ), \E_USER_DEPRECATED);

        $issuer = \func_get_args()[3] ?? null;
        $accountName = sprintf('%s@%s', $user, $hostname);

        // manually concat the issuer to avoid a change in URL
        $url = GoogleQrUrl::generate($accountName, $secret);

        if ($issuer) {
            $url .= '%26issuer%3D'.$issuer;
        }

        return $url;
    }

    public function generateSecret(): string
    {
        return (new FixedBitNotation(5, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567', true, true))
            ->encode(random_bytes($this->secretLength));
    }

    private function hashToInt(string $bytes, int $start): int
    {
        return unpack('N', substr(substr($bytes, $start), 0, 4))[1];
    }
}

// NEXT_MAJOR: Remove class alias
class_alias('Sonata\GoogleAuthenticator\GoogleAuthenticator', 'Google\Authenticator\GoogleAuthenticator', false);
