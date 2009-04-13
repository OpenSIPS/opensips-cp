<?php

/*
 * $Id:$
 * Copyright (C) 2008 Voice Sistem SRL
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

$box_id=0;

// mi host:port pair || fifo_file
$boxes[$box_id]['mi']['conn']="192.168.0.1:8080";

// monit host:port
$boxes[$box_id]['monit']['conn']="192.168.0.1:2812";
$boxes[$box_id]['monit']['user']="admin";
$boxes[$box_id]['monit']['pass']="pass";
$boxes[$box_id]['monit']['has_ssl']=1;


// description (appears in mi , monit )
$boxes[$box_id]['desc']="Primary SIP server";

 
$boxes[$box_id]['assoc_id']=1;

// enable local smonitor charts on this box : 0=disabled 1=enabled
// (cron)
$boxes[$box_id]['smonitor']['charts']=1;


/*==============================================================
$box_id=1;

// mi host:port pair || fifo_file 
$boxes[$box_id]['mi']['conn']="192.168.0.2:8080";


// monit host:port
$boxes[$box_id]['monit']['conn']="192.168.0.2:2812";
$boxes[$box_id]['monit']['user']="admin";
$boxes[$box_id]['monit']['pass']="pass";
$boxes[$box_id]['monit']['has_ssl']=1;


// description (appears in mi , monit )
$boxes[$box_id]['desc']="Secondary SIP server";


$boxes[$box_id]['assoc_id']=1;

// enable local smonitor charts on this box : 0=disabled 1=enabled
// (cron)
$boxes[$box_id]['smonitor']['charts']=1;
*/

?>
