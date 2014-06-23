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


function params($box_val){

	global $xmlrpc_host;
	global $xmlrpc_port;
	global $fifo_file;
	global $udp_host;
	global $udp_port;
	global $json_url;

	$a=explode(":",$box_val);

	switch ($a[0]) {
		case "udp":
			$comm_type="udp";
			$udp_host = $a[1];
			$udp_port = $a[2];
			break;
		case "xmlrpc":
			$comm_type="xmlrpc";
			$xmlrpc_host = $a[1];
			$xmlrpc_port = $a[2];
			break;
		case "fifo":
			$comm_type="fifo";
			$fifo_file = $a[1];
			break;
		case "json":
			$comm_type="json";
			$json_url = substr($box_val,5);
			break;
	}

	return $comm_type;
}

function get_proxys_by_assoc_id($my_assoc_id){

	$global= '../../../../config/boxes.global.inc.php';
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
function get_priv() {

        $modules = get_modules();

        foreach($modules['Admin'] as $key=>$value) {
                $all_tools[$key] = $value;
        }
        foreach($modules['Users'] as $key=>$value) {
                $all_tools[$key] = $value;
        }
        foreach($modules['System'] as $key=>$value) {
                $all_tools[$key] = $value;
        }

        if($_SESSION['user_tabs']=="*") {
                foreach ($all_tools as $lable=>$val) {
                        $available_tabs[]=$lable;
                }
        } else {
                $available_tabs=explode(",",$_SESSION['user_tabs']);
        }

        if ($_SESSION['user_priv']=="*") {
                $_SESSION['read_only'] = false;
		$_SESSION['permission'] = "Read-Write";
        } else {
                $available_privs=explode(",",$_SESSION['user_priv']);
                if( ($key = array_search("siptrace", $available_tabs))!==false) {
                        if ($available_privs[$key]=="read-only"){
                                $_SESSION['read_only'] = true;
				$_SESSION['permission'] = "Read-Only";
                        }
                        if ($available_privs[$key]=="read-write"){
                                $_SESSION['read_only'] = false;
				$_SESSION['permission'] = "Read-Write";
                        }

                }
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

function get_modules() {
         $modules=array();
         $mod = array();
         if ($handle=opendir('../../../tools/admin/'))
         {
          while (false!==($file=readdir($handle)))
           if (($file!=".") && ($file!="..") && ($file!="CVS")  && ($file!=".svn"))
           {
            $modules[$file]=trim(file_get_contents("../../../tools/admin/".$file."/tool.name"));
           }
         closedir($handle);
         $mod['Admin'] = $modules;
        }

         $modules=array();
         if ($handle=opendir('../../../tools/users/'))
         {
          while (false!==($file=readdir($handle)))
           if (($file!=".") && ($file!="..") && ($file!="CVS")  && ($file!=".svn"))
           {
            $modules[$file]=trim(file_get_contents("../../../tools/users/".$file."/tool.name"));
           }
          closedir($handle);
          $mod['Users'] = $modules;
         }

         $modules=array();
         if ($handle=opendir('../../../tools/system/'))
         {
          while (false!==($file=readdir($handle)))
           if (($file!=".") && ($file!="..") && ($file!="CVS")  && ($file!=".svn"))
           {
            $modules[$file]=trim(file_get_contents("../../../tools/system/".$file."/tool.name"));
           }
          closedir($handle);
          $mod['System'] = $modules;
          }
     return $mod;
}

?>
