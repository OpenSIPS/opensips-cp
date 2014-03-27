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
                if( ($key = array_search("list_admins", $available_tabs))!==false) {
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

function params($box_val){

	global $xmlrpc_host;
	global $xmlrpc_port;
	global $fifo_file;
	global $udp_host;
	global $udp_port;

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
	}

	return $comm_type;
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

function permission($option,$i,$disabled) {
	global $config;
	require("../../../../config/globals.php");
	$permissions = $config->permissions;
	if ($disabled=='disabled') {
		?>
		<select disabled="disabled" name="permission<?php print "_$i";?>" id="permission" size="1" style="width: 175px" class="dataSelect" >
		<?php
	} else {
	?>
		<select name="permission<?php print "_$i";?>" id="permission" size="1" style="width: 175px" class="dataSelect" >
	<?php
	}

	if (!empty($option)) {
             echo('<option value="'.$option. '" selected > '.$option.'</option>');			
	}	
		
	foreach ($permissions as $key) {
		if ($key==$option){
			continue;
		} else {
		
             		echo('<option value="'.$key. '" > '.$key.'</option>');			
		}
	}
	?>
	</select>
	<?php
}

?>
