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

function inspect_config_monit(){
	global $monit_boxes ;
	global $box_count ;


	$global='../../../../config/boxes.global.inc.php';
	require ($global);

	$b=0;

	foreach ( $boxes as $ar ){

			$boxlist[$b][$ar['monit']['conn']]=$ar['desc'];
			$boxlist[$b]['user']=$ar['monit']['user'];
			$boxlist[$b]['pass']=$ar['monit']['pass'];
			$boxlist[$b]['has_ssl']=$ar['monit']['has_ssl'];
			$b++;
	}


	$box_count=$b ;
	return $boxlist ;	

}

function show_boxes($boxen){

	global $current_box;
	global $page_name ;


	echo ('<table allign=left cellspacing=0 cellpadding=3 border=0 width="20%"><tr><td>');
	echo ('<form action="'.$page_name.'?box_val="'.$box_val.' method="post" name="boxen_select" >');
	echo ('<input type="hidden" name="box_val" class="formInput" method="post" value="">');
	echo ('<select name="box_list" class="formInput" onChange=boxen_select.box_val.value=boxen_select.box_list.value;boxen_select.submit() >');

	if (empty($current_box)){

		$current_box=key($boxen);
		$_SESSION['monit_current_box']=$current_box ;
	}
	foreach ( $boxen as $val )
	if (!empty($val)) {
		echo '<option value="'.key($boxen).'"' ;
		if ((key($boxen))==$current_box) echo ' selected';
		echo '>'.$val.'</option>';
		next($boxen);
	}

	echo ('</select></form>');

	echo ('</td><td><h3>');
	echo $current_box;
	echo ('</h3></td>');
	return $current_box;
}

function show_button(){

	echo ('<td width="10%">');
	echo ('<form>');
	echo ('<input type="button" name="refresh_button" value="Force Refresh" onClick="window.location.href=\'monit.php\'" >');
	echo ('</form>');
	echo ('</td></tr></table>');

}


function prepare_for_select($boxlis){

	$i=0;
	foreach ($boxlis as $arr){
		$newarr[key($boxlis[$i])]=$arr[key($boxlis[$i])];
		$i++;
	}

	return $newarr;
}

function get_monit_page($box,$port,$user,$pass,$file,$action,$has_ssl) {

	// for monit 4.9 (change in monit's httpd )
	if (!strstr($file,"/")) $file="/".$file ;

	if (!empty($action))
	{
		$out  = "GET ".$file."?action=".$action." HTTP/1.1\r\n";
	}
	else
	{
		$out  = "GET ".$file." HTTP/1.1\r\n";
	}

	$out .= "Host: ".$box."\r\n";
	$out .= "Connection: Close\r\n";
	$out .= "Authorization: Basic ".base64_encode($user.":".$pass)."\r\n";
	$out .= "\r\n";

	if ($has_ssl==0){

		if (!$con = @fsockopen($box, $port, $errno, $errstr, 10))

		return 0;

	} else {

		if (!$con = @fsockopen("ssl://".$box, $port, $errno, $errstr, 10))

		return 0;

	}


	fwrite($con, $out);
	$data = '';
	while (!feof($con)) {
		$data .= @fgets($con, 128);
	}
	fclose($con);
	return $data;
}



function get_params_for_this_box($box){

	$global='../../../../config/boxes.global.inc.php';
	require ($global);

	$b=explode(":",$box);
	$box=$b[0];
	$port=$b[1];
	$foo=array();
	for ($i=0;$i<count($boxes);$i++){
		$a=explode(":",$boxes[$i]['monit']['conn']);
		if (trim($a[0])==trim($box) && (trim($port)==trim($a[1]))){
			$foo['host']=$a[0];
			$foo['port']=$a[1];
			$foo['user']=$boxes[$i]['monit']['user'];
			$foo['pass']=$boxes[$i]['monit']['pass'];
			$foo['has_ssl']=$boxes[$i]['monit']['has_ssl'];
			return $foo;
			}
		}
	return "" ;

}


function get_params_for_this_box_global($box){
	
	$global='../../../../config/boxes.global.inc.php';
	require ($global);

	$foo=array();
	for ($i=0;$i<count($boxes);$i++){
		if (trim($boxes[$i]['monit']['conn'])==trim($box)){
			$a=explode(":",$boxes[$i]['monit']['conn']);
			$foo['host']=$a[0];
			$foo['port']=$a[1];
			$foo['user']=$boxes[$i]['monit']['user'];
			$foo['pass']=$boxes[$i]['monit']['pass'];
			$foo['has_ssl']=$boxes[$i]['monit']['has_ssl'];
			return $foo;
		}
	}
	return "" ;

}

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
                if( ($key = array_search("monit", $available_tabs))!==false) {
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


function monit_html_replace($page){
	global $refresh_timeout;
	$monit_request='monit_proxyfy.php?var=';
	$newpage=str_replace("<a href='","<a href='".$monit_request,$page);
	$newpage=str_replace("method=GET","method=POST",$newpage);
	$newpage=str_replace("action=","action=".$monit_request,$newpage);
	$newpage=preg_replace('/<meta HTTP-EQUIV="REFRESH" CONTENT=[0-9]+(?:\.[0-9]+)?>/','<meta HTTP-EQUIV="REFRESH" CONTENT='.$refresh_timeout.'>',$newpage);
	$newpage=str_replace('<img src="_pixel" width="1" height="1" alt="">','',$newpage);
	return $newpage ;

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
