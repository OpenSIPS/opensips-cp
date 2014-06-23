<?php
/*
* $Id: functions.inc.php 210 2010-03-08 18:09:33Z bogdan_iancu $
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
                if( ($key = array_search("alias_management", $available_tabs))!==false) {
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

function print_domains($type,$value)
{

	global $config;

        require("../../../../config/tools/system/domains/local.inc.php");
        require("../../../../config/db.inc.php");
        require("../../../../config/tools/system/domains/db.inc.php");
        require("db_connect.php");

        $table_domains=$config->table_domains;

        $sql="select domain from $table_domains";
        $result = $link->queryAll($sql);
        if(PEAR::isError($result)) {
                die('Failed to issue query, error message : ' . $result->getMessage());
        }
        foreach ($result as $k=>$v) {
                $options[]=array("label"=>$v['domain'],"value"=>$v['domain']);
        }

        if ($value=='ANY') {
                array_unshift($options,array("label"=>"ANY","value"=>"ANY"));
                $value='';
        }
        $start_index = 0;
        $end_index = sizeof($options);

?>
	<select name=<?=$type?> id=<?=$type?> size="1" style="width: 190px" class="dataSelect">
	 <?php
           if ($value!=NULL) {
             echo('<option value="'.$value. '" selected > '.$value.'</option>');
             $temp = $value;
             $value = '';
           }
	  for ($i=$start_index;$i<$end_index;$i++)
	  {
           if ($options[$i]['value'] == $temp) {
                continue;
	   } else { 	
	     echo('<option value="'.$options[$i]['value']. '"> '.$options[$i]['value'].'</option>');
	   }
	  }
	 ?>
	 </select>
	<?php
}

function print_groups($type,$value){
	require("../../../../config/tools/users/acl_management/local.inc.php");
	?>
	<select name=<?=$type?> id=<?=$type?> size="1" style="width: 190px" class="dataSelect">
	<?php
		           if ($value!=NULL) 
		        	      echo('<option value="'.$value. '" selected > '.$value.'</option>');
		           else
				   		 echo('<option value="ANY" selected >ANY</option>');
	foreach ($config->grps as $grp){
	
					if (strcmp($grp,$value)==0) 
						continue;
					else
						echo("<option value=".$grp."> ".$grp."</option>");						

	 }
	 ?>
	 </select>
	 <?php
}

function print_aliasType($value)
{
        global $config;
        require("../../../../config/globals.php");
        foreach ($config->table_aliases as $k=>$v) {
                $options[]=array("label"=>$k,"value"=>$v);
        }
        if ($value=='ANY') {
                array_unshift($options,array("label"=>"ANY","value"=>"ANY"));
                $value=0;
        }
        $start_index = 0;
        $end_index = sizeof($options);
?>
        <select name="alias_type" id="alias_type" size="1" style="width: 175px" class="dataSelect">
         <?php
           if ($value!=NULL) {
             echo('<option value="'.$value. '" selected > '.$value.'</option>');
             $temp = $value;
             $value = '';
           }

          for ($i=$start_index;$i<$end_index;$i++)
          {
           if ($options[$i]['value'] == $temp) {
                continue;
           } else {
             echo('<option value="'.$options[$i]['label']. '"> '.$options[$i]['label'].'</option>');
           }
          }
         ?>
         </select>
        <?php

}

function get_time_zones(){
    global $config;

    @$fp=fopen($config->zonetab_file, "r");
    if (!$fp) {$errors[]="Cannot open zone.tab file"; return array();}
    $out=array();

    while (!feof($fp)){
       $line=FgetS($fp, 512);
       if (substr($line,0,1)=="#") continue; //skip comments
       if (!$line) continue; //skip blank lines

       $line_a=explode("\t", $line);

       $line_a[2]=trim($line_a[2]);
       if ($line_a[2]) $out[]=$line_a[2];
    }

    fclose($fp);
    sort($out);
    return $out;
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
