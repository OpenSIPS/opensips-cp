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
$boxes[$box_id]['mi']['conn']="192.168.2.134:8080";

// monit host:port
$boxes[$box_id]['monit']['conn']="192.168.2.134:2812";
$boxes[$box_id]['monit']['user']="admin";
$boxes[$box_id]['monit']['pass']="chile";
$boxes[$box_id]['monit']['has_ssl']=1;

// scontroller ip:port 
$boxes[$box_id]['scontroller']['server_name']="Sip Server";
$boxes[$box_id]['scontroller']['conn']="192.168.2.134:22";
$boxes[$box_id]['scontroller']['user']="root";
$boxes[$box_id]['scontroller']['file1']="/etc/init.d/vrrpd1";
$boxes[$box_id]['scontroller']['file2']="/etc/init.d/vrrpd2";
$boxes[$box_id]['scontroller']['comm']= array(
			array("Interfaces Configuration", "ifconfig"));


// description (appears in mi , monit )
$boxes[$box_id]['desc']="Primary SIP server";

// system id , for scontroller - if 2 hosts are asigned the same `assoc_id` they are in the same `system`
// if assoc_id==0 then box is not for scontroller - doesnt belong to a `system`.  
$boxes[$box_id]['assoc_id']=1;


/*==============================================================*/
$box_id=1;

// mi host:port pair || fifo_file 
$boxes[$box_id]['mi']['conn']="192.168.2.101:8080";

// monit host:port
$boxes[$box_id]['monit']['conn']="192.168.2.101:2812";
$boxes[$box_id]['monit']['user']="admin";
$boxes[$box_id]['monit']['pass']="chile";
$boxes[$box_id]['monit']['has_ssl']=1;

// scontroller user@ip:port 
$boxes[$box_id]['scontroller']['server_name']="SIP server";
$boxes[$box_id]['scontroller']['conn']="192.168.2.101:22";
$boxes[$box_id]['scontroller']['user']="root";
$boxes[$box_id]['scontroller']['file1']="/etc/init.d/vrrpd1";
$boxes[$box_id]['scontroller']['file2']="/etc/init.d/vrrpd2";
$boxes[$box_id]['scontroller']['comm']= array(
			array("Interfaces Configuration", "ifconfig"));


// description (appears in mi , monit )
$boxes[$box_id]['desc']="Secondary SIP server";
// system id , for scontroller - if 2 hosts are asigned the same `assoc_id` they are in the same `system`
// if assoc_id==0 then box is not for scontroller - doesnt belong to a `system`.  
$boxes[$box_id]['assoc_id']=1;


/*==============================================================*/
/*	SYSTEMS	 , for scontroller			       */

$_system_id=0;
$systems[$_system_id]['name']="SIP Servers";
$systems[$_system_id]['desc']="OpenSIPS SIP server cluster";
//boxes with this assoc_id are assigned to this system 
$systems[$_system_id]['assoc_id']=1;
// 1= sip proxies pair , 2 = databases , 3 = media servers , etc..   
$systems[$_system_id]['system_type_id']=1;
// high availability : 1 = master/slave , 2= master/master
$systems[$_system_id]['ha']=2 ;
$systems[$_system_id]['vip1']='192.168.2.200' ;
// if ha == 1 then vip2 is ignored
$systems[$_system_id]['vip2']='192.168.2.201' ;


?>
