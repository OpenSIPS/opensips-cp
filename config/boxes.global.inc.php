<?php
/*
 * Copyright (C) 2011-2021 OpenSIPS Project
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

/*==================== DEFINITION OF BOXES (servers) ========================*/
// each server is a box

$box_id=0;

//  MI connector (via JSON backend):   json:host:port/mi
$boxes[$box_id]['mi']['conn']="json:127.0.0.1:8888/mi";

// monit host:port
$boxes[$box_id]['monit']['conn']="127.0.0.1:2812";
$boxes[$box_id]['monit']['user']="admin";
$boxes[$box_id]['monit']['pass']="admin";
$boxes[$box_id]['monit']['has_ssl']=1;


// description (appears in mi , monit )
$boxes[$box_id]['desc']="SIP server";

 
$boxes[$box_id]['assoc_id']=1;

// enable local smonitor charts on this box : 0=disabled 1=enabled
// (cron)
$boxes[$box_id]['smonitor']['charts']=1;


/*---------------------------------------------------------------------------
$box_id=1;

//  MI connector (via JSON backend):   json:host:port/mi
$boxes[$box_id]['mi']['conn']="json:127.0.0.2:8888/mi";


// monit host:port
$boxes[$box_id]['monit']['conn']="127.0.0.2:2812";
$boxes[$box_id]['monit']['user']="admin";
$boxes[$box_id]['monit']['pass']="pass";
$boxes[$box_id]['monit']['has_ssl']=1;


// description (appears in mi , monit )
$boxes[$box_id]['desc']="Presence server";


$boxes[$box_id]['assoc_id']=2;

// enable local smonitor charts on this box : 0=disabled 1=enabled
// (cron)
$boxes[$box_id]['smonitor']['charts']=1;
*/



/*======================== DEFINITION OF SYSTEMS ========================*/
// each system is a set of servers with the same
// functionality on the platform (like sets of
// SIP servers, sets of Load-Balancers, sets of
// Presence agents, etc)

$_system_id=0;
$systems[$_system_id]['name']="SIP Servers";
$systems[$_system_id]['desc']="OpenSIPS SIP server cluster";
//boxes with this assoc_id are assigned to this system
$systems[$_system_id]['assoc_id']=1;


/*---------------------------------------------------------------------------
$_system_id=1;
$systems[$_system_id]['name']="Presence Servers";
$systems[$_system_id]['desc']="OpenSIPS Presence server cluster";
//boxes with this assoc_id are assigned to this system
$systems[$_system_id]['assoc_id']=2;
*/


?>
