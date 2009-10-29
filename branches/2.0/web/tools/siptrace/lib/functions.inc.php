<?php
/*
 * $Id$
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

######################
# Database Functions #
######################

function db_connect()
{
 global $config;

 if (isset($config->db_host_siptrace) && isset($config->db_user_siptrace) && isset($config->db_name_siptrace) ) {
 	$config->db_host = $config->db_host_siptrace;
        $config->db_port = $config->db_port_siptrace;
        $config->db_user = $config->db_user_siptrace;
        $config->db_pass = $config->db_pass_siptrace;
        $config->db_name = $config->db_name_siptrace;
 }
 
 $link = @mysql_connect($config->db_host, $config->db_user, $config->db_pass);
 
 if (!$link) {
              die("Could not connect to MySQL Server: " . mysql_error());
              exit();
             }
 
 $selected = @mysql_select_db($config->db_name, $link);
 if (!$selected) {
                  die("Could not select '$config->db_name' database." . mysql_error());
                  exit();
                 } 
}

function db_close()
{
 mysql_close();
}

##########################
# End Database Functions #
##########################


function params($box_val){

    global $xmlrpc_host; 
    global $xmlrpc_port; 
    global $fifo_file; 

$a=explode(":",$box_val);    
	
if (!empty($a[1]))
    {
	
    	$comm_type="xmlrpc";
	
    	$xmlrpc_host=$a[0];
	
    	$xmlrpc_port=$a[1];
    
    } else {
    
    	$comm_type="fifo";
	
    	$fifo_file=$box_val ;
    }

return $comm_type;
}

function get_proxys_by_assoc_id($my_assoc_id){

	$global= '../../../config/boxes.global.inc.php';
	require($global);
	 
	$mi_connectors=array();
	
	for ($i=0;$i<count($boxes);$i++){
  
		if ($boxes[$i]['assoc_id']==$my_assoc_id){
		
			$mi_connectors[]=$boxes[$i]['mi']['conn'];			

		}		

	}

	return $mi_connectors; 	
}

########## end mi 

function get_priv()
{
 if ($_SESSION['user_tabs']=="*") $_SESSION['read_only'] = false;
 else {
       $available_tabs = explode(",", $_SESSION['user_tabs']);
       $available_priv = explode(",", $_SESSION['user_priv']);
       $key = array_search("trace", $available_tabs);
       if ($available_priv[$key]=="read-only") $_SESSION['read_only'] = true;
       if ($available_priv[$key]=="read-write") $_SESSION['read_only'] = false;
      }
 return;
}

function print_object($obj_name, $start_value, $end_value, $select_value, $disabled)
{
?>
 <select name="<?=$obj_name?>" id="<?=$obj_name?>" size="1" class="dataSelect" <?=$disabled?>>
 <?php
  for ($i=$start_value;$i<=$end_value;$i++)
  {
   if ($i<10) $value="0".$i;
    else $value=$i;
   if ($value==$select_value) $selected="selected";
    else $selected="";
   echo('<option value="'.$value.'" '.$selected.'>'.$value.'</option>');
  }
 ?>
 </select>
<?php
}

function print_start_date_time($datetime)
{
 $obj_name="start";
 if ($datetime=="") {
                     $status="disabled";
                     $a=1;
                     $b=1;
                     $c=date("Y");
                     $d=0;
                     $e=0;
                     $f=0;
                    }
  else {
        $status="";
        $a=substr($datetime,8,2);
        $b=substr($datetime,5,2);
        $c=substr($datetime,0,4);
        $d=substr($datetime,11,2);
        $e=substr($datetime,14,2);
        $f=substr($datetime,17,2);;
       }
 print_object($obj_name."_year",date("Y")-5,date("Y")+5,$c,$status); echo("<b>-</b>");
 print_object($obj_name."_month",1,12,$b,$status); echo("<b>-</b>");
 print_object($obj_name."_day",1,31,$a,$status); echo("&nbsp;&nbsp;");
 print_object($obj_name."_hour",0,23,$d,$status); echo("<b>:</b>");
 print_object($obj_name."_minute",0,59,$e,$status); echo("<b>:</b>");
 print_object($obj_name."_second",0,59,$f,$status);
}

function print_end_date_time($datetime)
{
 $obj_name="end";
 if ($datetime=="") {
                     $status="disabled";
                     $a=date("d");
                     $b=date("m");
                     $c=date("Y");
                     $d=23;
                     $e=59;
                     $f=59;
                    }
  else {
        $status="";
        $a=substr($datetime,8,2);
        $b=substr($datetime,5,2);
        $c=substr($datetime,0,4);
        $d=substr($datetime,11,2);
        $e=substr($datetime,14,2);
        $f=substr($datetime,17,2);;
       }
 print_object($obj_name."_year",date("Y")-5,date("Y")+5,$c,$status); echo("<b>-</b>");
 print_object($obj_name."_month",1,12,$b,$status); echo("<b>-</b>");
 print_object($obj_name."_day",1,31,$a,$status); echo("&nbsp;&nbsp;");
 print_object($obj_name."_hour",0,23,$d,$status); echo("<b>:</b>");
 print_object($obj_name."_minute",0,59,$e,$status); echo("<b>:</b>");
 print_object($obj_name."_second",0,59,$f,$status);
}

function get_ip($string)
{
 $temp=explode(":",$string);
 $k=sizeof($temp);
 return($temp[$k-2].":".$temp[$k-1]);
}

?>
