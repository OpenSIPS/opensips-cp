<?php
/*
 * Copyright (C) 2022 OpenSIPS Solutions
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
global $table_regex;
global $config;

$config->keepalived = array(
	"title0" => array(
		"type" => "title",
		"title" => "General settings"
	),
	"machines" => array(
		"default" => "",
		"name"	=> "Machines",
		"type"	=> "json",
		"tip" 	  => "VIPs and Machines/Boxes where keepalived is setup",
		"example" => "The format of the machine node is a JSON list, where each element
consists of two nodes:
 * name: the title of the keepalived instance
 * boxes: a list of boxes that are part of the keepalived instance

Each box consists of the following nodes:
 * box: the name of the Box or keepalived node
 * ssh_ip: the IP of the machine; if missing and a known box is used,
   the MI conn_ip is considered
 * ssh_port: the SSH port to connect to; default is 22
 * ssh_user: the SSH user to authenticate with; default is root
 * ssh_pubkey: the SSH public key to authenticate with; if missing, the
   default value is being used; if the file is relative, it is being
   searched in the keepalived's local config directory
 * ssh_key: the SSH private key to authenticate with; if missing, the
   default value is being used; if the file is relative, it is being
   searched in the keepalived's local config directory
 * check_exec: script that is executed to check if the mode of the node;
   the output is compared against the 'check_pattern' value;
   if missing, the Default Check command is being used
 * check_pattern: pattern that is applied on 'check_exec' output
   to check if the node is Primary; if missing, the global Pattern value
   is being used
 * backup_exec: command to run when the machine is put in backup mode;
   if missing, the global default value is considered
 * primary_exec: command to run when the machine is put in primary mode;
   if missing, the global default value is considered

A simple example of a Keepalived instance with two nodes is:
[
  {
    \"name\": \"Virtual IP\",
    \"boxes\": [
      {
        \"box\": \"Primary\",
        \"ssh_ip\": \"10.0.0.1\"
      },
      {
        \"box\": \"Secondary\",
        \"ssh_ip\": \"10.0.0.2\"
      }
    ]
  }
]"
	),
	"ssh_pubkey" => array(
		"default" => "id_rsa.pub",
		"name"	=> "SSH Public Key",
		"type"	=> "text",
		"tip" 	  => "Default public key path"
	),
	"ssh_key" => array(
		"default" => "id_rsa",
		"name"	=> "SSH Private Key",
		"type"	=> "text",
		"tip" 	  => "Default private key path"
	),
	"check_exec" => array(
		"default" => "ip a s",
		"name"	=> "Default Check Command",
		"type"	=> "text",
		"tip" 	  => "Default command to check whether a machine/node is the primary node."
	),
	"check_pattern" => array(
		"default" => "",
		"name"	=> "Pattern to check Primary mode",
		"type"	=> "text",
		"tip" 	  => "A patthen that is applied on the output of Check Command; if matched, the node is considered Primary; otherwise it is a backup node",
	),
	"backup_exec" => array(
		"default" => "",
		"name"	=> "Default Backup Switch Command",
		"type"	=> "text",
		"tip" 	  => "Default command to put a machine/node in backup mode; if defined and not empty, the command is run on all nodes that are to become backup",
	),
	"primary_exec" => array(
		"default" => "",
		"name"	=> "Default Primary Switch Command",
		"type"	=> "text",
		"tip" 	  => "Default command to run when a machine/node is put in primary mode",
	),
);
