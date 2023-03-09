<?php
/*
 * Copyright (C) 2011 OpenSIPS Project
 *
 * This file is part of opensips-cp, a free Web Control Panel Application for 
 * OpenSIPS SIP server.
 *
 * opensips-cp is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * opensips-cp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

function ssh_conn($host, $port, $user, $pub_key, $prv_key, $command, $pass = null) {
    $connection=ssh2_connect($host, $port);
    if ($connection) {
	if ($pub_key[0] != '/')
		$pub_key = '../../../../config/tools/system/keepalived/'.$pub_key;
	if ($prv_key[0] != '/')
		$prv_key = '../../../../config/tools/system/keepalived/'.$prv_key;
        $auth=ssh2_auth_pubkey_file($connection, $user, $pub_key, $prv_key, $pass);
        if (!$auth) return(false);
    }
    $stream=ssh2_exec($connection,$command);
    if (!$stream) return("ERROR: cannot execute Show State command '$command'");
    stream_set_blocking($stream, true);
    $out="";
    while ($buffer=fgets($stream,4096)) $out.=$buffer;
    fclose($stream);
    $out=trim($out);
    return $out;
}

function set_defaults($box) {
    $default_box = NULL;
    foreach($_SESSION['boxes'] as $loaded_box) {
        if ($loaded_box['name'] == $box['box'])
            $default_box = $loaded_box;
    }
    if (!isset($box['ssh_ip']) && $default_box)
        $box['ssh_ip'] = preg_split("/[:\/]/", $default_box['mi_conn'])[1];
    if (!isset($box['ssh_port']))
        $box['ssh_port'] = 22;
    if (!isset($box['ssh_user']))
        $box['ssh_user'] = "root";
    if (!isset($box['ssh_pubkey']))
        $box['ssh_pubkey'] = get_settings_value("ssh_pubkey");
    if (!isset($box['ssh_key']))
        $box['ssh_key'] = get_settings_value("ssh_key");
    if (!isset($box['check_exec']))
        $box['check_exec'] = get_settings_value("check_exec");
    if (!isset($box['check_pattern']))
        $box['check_pattern'] = get_settings_value("check_pattern");
    return $box;
}

?>
