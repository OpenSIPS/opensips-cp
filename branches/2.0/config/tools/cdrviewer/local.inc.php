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

###############################################################################
# Attention : advanced options !!

 
 //database tables
 $config->cdr_table = "cdrs";

 $config->results_per_page = 10 ;
 
 $config->results_page_range = 10 ;
 
 
 // highlighting 
 $config->from_color="black";	   $config->from_bgcolor="yellow";
 $config->to_color="white";      $config->to_bgcolor="blue";
 $config->callid_color="black";  $config->callid_bgcolor="orange";
 $config->cseq_color="white";	   $config->cseq_bgcolor="navy";
 $config->regexp_color="navy";   $config->regexp_bgcolor="red";
 
 
 // what fields to show  
 $show_field[0]['caller_id'] = "Caller" ; 
 $show_field[1]['callee_id'] = "Callee" ; 
 $show_field[2]['call_start_time'] = "Call Start Time";
 $show_field[3]['duration'] = "Duration"; 
 $show_field[4]['leg_type'] = "Leg Type"; 
 
 
/*  
 $show_field[5]['leg_status'] = "Leg Status";
 $show_field[6]['sip_call_id'] = "Sip Call ID"; 
 $show_field[7]['sip_from_tag'] = "Sip From Tag";
 $show_field[8]['sip_to_tag'] = "Sip To Tag";
 $show_field[9]['cdr_rated'] = "Cdr Rated";
 $show_field[10]['created'] = "Created";
 $show_field[11]['src_local'] = "Src Local";
*/ 

// use this field to link with siptrace module 
  $sip_call_id_field_name='sip_call_id';


 // what fields to export (cron job)
 $export_csv[0]['cdr_id'] = "CDR ID" ;
 $export_csv[1]['call_start_time'] = "Call Start Time";
 $export_csv[2]['duration'] = "Duration"; 
 $export_csv[3]['sip_call_id'] = "SIP callid"; 
 $export_csv[4]['sip_from_tag'] = "SIP fromTag"; 
 $export_csv[5]['sip_to_tag'] = "SIP toTag"; 
 $export_csv[6]['leg_status'] = "LEG status" ;
 $export_csv[7]['leg_type'] = "LEG type" ;
 $export_csv[8]['leg_transition'] = "LEG transition" ;
 $export_csv[9]['caller_id'] = "Caller" ; 
 $export_csv[10]['callee_id'] =  "Callee" ; 
 $export_csv[11]['destination'] = "Destination" ; 

 
/* 
 $export_csv[5]['leg_status'] = "Leg Status"; 
 $export_csv[6]['sip_call_id'] = "Sip Call ID"; 
 $export_csv[7]['sip_from_tag'] = "Sip From Tag";
 $export_csv[8]['sip_to_tag'] = "Sip To Tag";
 $export_csv[9]['cdr_rated'] = "Cdr Rated";
 $export_csv[10]['created'] = "Created";
 $export_csv[11]['src_local'] = "Src Local";
*/ 
 
 // where to dump the files (cron job)
 
 
 // where to dump the files (cron job)
 $cdr_repository_path = '/var/lib/opensips_cdrs' ; 

//  field description in csv file (cron job) ; 
//	1 == on ,  0 == off
 $cdr_set_field_names = 1 ;

 $delay=3600 ; 
 
?>