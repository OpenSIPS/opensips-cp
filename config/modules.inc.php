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


### List with all the available modules - you can enable and disable module from here

$config_modules 	= array (
	"admin"                 => array (
		"enabled"       => true,
		"settings"      => true,
		"name"          => "Admin",
		"modules"       => array (
			"list_admins"	=> array (
				"enabled"	=> true,
				"name"		=> "Admins"
			),
		),
	),
	"users"			=> array (
		"enabled" 	=> true,
		"name" 		=> "Users",
		"icon"		=> "images/icon-user.svg",
		"modules"	=> array (
			"acl_management"	=> array (
				"enabled"		=> true,
				"name"			=> "ACL Management"
			),
			"alias_management"	=> array (
				"enabled"		=> true,
				"name"			=> "Alias Management"
			),
			"user_management"	=> array (
				"enabled"		=> true,
				"name"			=> "User Management"
			),
		)
	),
	"system"		=> array (
		"enabled"	=> true,
		"name"		=> "System",
		"icon"		=> "images/icon-system.svg",
		"modules"	=> array (
			"callcenter"		=> array (
				"enabled"		=> true,
				"name"			=> "Callcenter"
			),
			"cdrviewer"			=> array (
				"enabled"		=> true,
				"name"			=> "CDR Viewer"
			),
			"dialog"			=> array (
				"enabled"		=> true,
				"name"			=> "Dialog"
			),
			"dialplan"			=> array (
				"enabled"		=> true,
				"name"			=> "Dialplan"
			),
			"dispatcher"		=> array (
				"enabled"		=> true,
				"name"			=> "Dispatcher"
			),
			"domains"			=> array (
				"enabled"		=> true,
				"name"			=> "Domains"
			),
			"drouting"			=> array (
				"enabled"		=> true,
				"name"			=> "Dynamic Routing"
			),
			"clusterer"			=> array (
				"enabled"		=> true,
				"name"			=> "Clusterer"
			),
			"loadbalancer"		=> array (
				"enabled"		=> true,
				"name"			=> "Load Balancer"
			),
			"mi"				=> array (
				"enabled"		=> true,
				"name"			=> "MI Commands"
			),
			"monit"				=> array (
				"enabled"		=> true,
				"name"			=> "Monit"
			),
			"rtpproxy"			=> array (
				"enabled"		=> true,
				"name"			=> "RTPProxy"
			),
			"rtpengine"			=> array (
				"enabled"		=> true,
				"name"			=> "RTPEngine"
			),
			"permissions"		=> array (
				"enabled"		=> true,
				"name"			=> "Permissions"
			),
			"sip_trunk"			=> array (
				"enabled"		=> true,
				"name"			=> "SIP Trunk"
			),
			"siptrace"			=> array (
				"enabled"		=> true,
				"name"			=> "SIP Trace"
			),
			"smonitor"			=> array (
				"enabled"		=> true,
				"name"			=> "Statistics Monitor"
			),
			"homer"				=> array (
				"enabled"		=> true,
				"name"			=> "Homer"
			),
			"tls_mgm"			=> array (
				"enabled"		=> true,
				"name"			=> "TLS Management"
			),
			"tviewer"			=> array (
				"enabled"		=> true,
				"name"			=> "TViewer"
			),
		)
	),
);




?>
