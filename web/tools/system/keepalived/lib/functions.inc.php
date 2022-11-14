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
    foreach($_SESSION['boxes'] as $loaded_box) {
        if ($loaded_box['name'] == $box['box'])
            $default_box = $loaded_box;
    }
    if (!$box['ssh_ip'] && $default_box)
        $box['ssh_ip'] = explode(":", $default_box['mi_conn'])[1];
    if (!$box['ssh_port'])
        $box['ssh_port'] = 22;
    if (!$box['ssh_user'])
        $box['ssh_user'] = "root";
       if (!$box['ssh_pubkey'])
        $box['ssh_pubkey'] = "id_rsa_keepalived.pub";
    if (!$box['ssh_key'])
        $box['ssh_key'] = "id_rsa_keepalived";
    if (!$box['exec'])
        $box['exec'] = "/etc/init.d/keepalived";
    return $box;
}

?>