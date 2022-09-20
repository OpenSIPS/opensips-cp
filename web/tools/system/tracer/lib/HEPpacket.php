<?php

class HEPpacket
{		

	public $protocol_ids;
	public $protocol_types;
	public $payloads;
    public $family;
    public $protocol;
    public $src_addr;
    public $dst_addr;
    public $src_port;
    public $dst_port;
    public $data;
    public $correlation;
    public $ts;
    public $tms;
	public $errors;

    public function __construct($payloads) {
        $this->payloads = $payloads;
		$this->family = AF_INET;
		$this->protocol = "UNKNOWN";
		$this->ts = time();
		$this->tms = microtime();
		$this->errors = "";
		$this->protocol_ids = json_decode('{
			"0":"HOPOPTS",
			"1":"ICMP",
			"2":"IGMP",
			"3":"GGP",
			"4":"IPV4",
			"41":"IPV6",
			"6":"TCP",
			"8":"EGP",
			"12":"PUP",
			"17":"UDP",
			"22":"IDP",
			"77":"ND",
			"43":"ROUTING",
			"44":"FRAGMENT",
			"50":"ESP",
			"51":"AH",
			"58":"ICMPV6",
			"59":"NONE",
			"60":"DSTOPTS",
			"103":"PIM",
			"132":"SCTP",
			"255":"RAW",
			"256":"MAX",
			"78":"ICLFXBM",
			"5":"ST",
			"7":"CBT",
			"9":"IGP",
			"27":"RDP",
			"113":"PGM",
			"115":"L2TP"
		 }', true);
		$this->protocol_types = array(
			0x00 => "UNKNOWN",
			0x01 => "SIP",
			0x02 => "XMPP",
			0x03 => "SDP",
			0x04 => "RTP",
			0x05 => "RTCP JSON",
			0x56 => "LOG",
			0x57 => "MI",
			0x58 => "REST",
			0x59 => "NET",
			0x60 => "CONTROL",
		);
    }

    function parse() { 
		$payloads = $this->payloads;
		$length = count($payloads);
		while ($length > 0) { 
			if ($length < 6) {
				$this->errors .= "Payload too small\n";
			}
			$chunk_vendor_id = unpack("n", implode("", array_slice($payloads, 0, 2)))[1];
			$chunk_type_id = unpack("n", implode("", array_slice($payloads, 2, 2)))[1];
			$chunk_len = unpack("n", implode("", array_slice($payloads, 4, 2)))[1];
			if ($chunk_len < 6) {
				$this->errors .- "Chunk too small\n";
			}
			$payload = array_slice($payloads, 6, $chunk_len - 6);
			$payloads = array_slice($payloads, $chunk_len);
			$length -= $chunk_len;
			$this->push_chunk($chunk_vendor_id, $chunk_type_id, $payload);
			
		}
	}

	function push_chunk($vendor, $type_id, $payload) {
		if ($vendor != 0) {
			$this->errors .= "Unknown vendor id\n";
			//throw new Exception('Unknown vendor id\n');
		}
		if ($type_id == 0x0001) {
			if (count($payload) != 1) {
				$this->errors .= "Type 0x0001 and len != 1\n";
			//	throw new Exception('Type 0x0001 and len != 1\n');
			}
			$this->family = ord($payload[0]);
		} else if ($type_id == 0x0002) {
			if (count($payload) != 1) {
				//$this->errors .= "Type 0x0002 and len != 1\n";
				//throw new Exception('Type 0x0002 and len != 1\n');
			}
			if (!(array_key_exists(unpack("C", $payload[0])[1], $this->protocol_ids))) {
				$this->protocol = unpack("C", $payload[0])[1];
			} else {
				$this->protocol = $this->protocol_ids[unpack("C", $payload[0])[1]];
			}
		} else if ($type_id >= 0x0003 && $type_id <= 0x0006) {
			$addr = "";
			if ($type_id <= 0x0004) {
				$expected_payload_len = 4;
				foreach($payload as $index => $byte) {
					$addr .= ord($byte).".";
				}
				$addr = substr($addr, 0, strlen($addr)-1);
			} else {
				$expected_payload_len = 16;
				$addr = bin2hex(join(array_map("chr", array_map("ord", $payload))));
			}
			if (count($payload) != $expected_payload_len) {
				$this->errors .= "Different expected len\n";
			//	throw new Exception ("Different expected len");
			}
			if ($type_id == 0x0003 || $type_id == 0x0005) {
				$this->src_addr = $addr;
			} else $this->dst_addr = $addr;
		} else if ($type_id == 0x0007 || $type_id == 0x0008) {
			if (count($payload) != 2) {
				$this->errors .= "Payload len not 2\n";
			//	throw new Exception("Payload len not 2");
			}
			$port = unpack("n", implode("", $payload))[1];
			if ($type_id == 7) {
				$this->src_port = $port;
			} else $this->dst_port = $port;
		} else if ($type_id == 0x0009 || $type_id == 0x000a) {
			if (count($payload) != 4) {
				$this->errors .= "Payload len not 4\n";
			//	throw new Exception("Payload len not 4");
			}
			$timespec = unpack("N", implode("", $payload))[1];
			if ($type_id == 0x0009) {
				$this->ts = $timespec;
			} else {
				$this->tms = $timespec;
			}
		} else if ($type_id == 0x000b) {
			if (count($payload) != 1) {
				$this->errors .= "Payload len not 1\n";
			//	throw new Exception("Payload len not 1");
			}
			if (!(array_key_exists(unpack("C", $payload[0])[1], $this->protocol_types))) {
				$this->type = unpack("C", $payload[0])[1];
			} else {
				$this->type = $this->protocol_types[unpack("C", $payload[0])[1]];
			}
		} else if ($type_id == 0x000c) {
			$this->errors .= "Capture id is not used now\n";
		} else if ($type_id == 0x000f) {
			$this->data = implode("", array_map(function($in) {
				return chr(unpack("c", $in)[1]);
			}, $payload));
		} else if ($type_id == 0x00011) {
			$this->correlation = implode("", array_map(function($in) {
				return chr(unpack("c", $in)[1]);
			}, $payload));
		} else {
			$this->errors .= "Unhandled payload type\n";
		}

	}
	function get_packet() {
		$text = "";
		$time_str = date('d-m-Y H:i:s', $this->ts);
		$protocol_str = " ".$this->protocol."/".$this->type;
		if ($this->type == "SIP") {
			$ip_str = " ".$this->src_addr.":".$this->src_port." -> ".$this->dst_addr.":".$this->dst_port;
		} else {
			$ip_str = "";
		}
		$data_str = $this->data;
		$text = $time_str.$protocol_str.$ip_str."\n".$data_str;
		return $text;
	}

	function get_meta() {
		$text = "";
		$time_str = date('d-m-Y H:i:s', $this->ts);
		$protocol_str = " ".$this->protocol."/".$this->type;
		if ($this->type == "SIP") {
			$ip_str = " ".$this->src_addr.":".$this->src_port." -> ".$this->dst_addr.":".$this->dst_port;
		} else {
			$ip_str = "";
		}
		$text = $time_str.$protocol_str.$ip_str;
		return $text;		
	}

	function get_data() {
		return $this->data;
	}
}
?>