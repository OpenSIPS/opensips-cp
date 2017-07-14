<?php
/*
 * $Id$
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

###############################################################################

 
 //database tables
 $config->cdr_table = "acc";

 $config->results_per_page = 30 ;
 
 $config->results_page_range = 10 ;
 
 
 // highlighting 
 $config->from_color="black";	   $config->from_bgcolor="yellow";
 $config->to_color="white";      $config->to_bgcolor="blue";
 $config->callid_color="black";  $config->callid_bgcolor="orange";
 $config->cseq_color="white";	   $config->cseq_bgcolor="navy";
 $config->regexp_color="navy";   $config->regexp_bgcolor="red";
 
 
 // what fields to show  
 $show_field[0]['time'] = "Time" ; 
 $show_field[1]['method'] = "Method" ; 
 $show_field[2]['callid'] = "Sip Call ID" ; 
 $show_field[3]['sip_code'] = "Sip Code" ; 
 $show_field[4]['sip_reason'] = "Sip Reason" ; 
 $show_field[5]['setuptime'] = "Setup Time"; 
 $show_field[6]['duration'] = "Duration"; 
 $show_field[7]['from_tag'] = "Sip From Tag" ; 
 $show_field[8]['to_tag'] = "Sip To Tag";
 
 
 // the tool must be aware of couple of fields 
 $sip_call_id_field_name='callid';
 $cdr_id_field_name='id';


 // what fields to export (cron job)
 $export_csv[0]['id'] = "CDR ID" ;
 $export_csv[1]['time'] = "Call Start Time";
 $export_csv[1]['method'] = "SIP Method" ; 
 $export_csv[2]['callid'] = "Sip Call ID" ; 
 $export_csv[3]['sip_code'] = "Sip Code" ; 
 $export_csv[4]['sip_reason'] = "Sip Reason" ; 
 $export_csv[5]['setuptime'] = "Setup Time"; 
 $export_csv[6]['duration'] = "Duration"; 
 $export_csv[7]['from_tag'] = "Sip From Tag" ; 
 $export_csv[8]['to_tag'] = "Sip To Tag";

 // where to dump the files (cron job)
 $cdr_repository_path = '/var/lib/opensips_cdrs' ; 

 // field description in csv file (cron job) ; 
 //	1 == on ,  0 == off
 $cdr_set_field_names = 1 ;

 $delay=3600 ; 

 /* Optional function that may implement custom processing for the
    CDR fields before being exported. The argument the asoc array
    corresponding to a full CDR (all db fields) */
 //function process_cdr_line_for_export( &$cdr_line ) {
 //}
?>
