<?php
/*
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


$path_to_cdrviewer="/var/www/html/opensips-cp/web/tools/system/cdrviewer";
chdir($path_to_smonitor);
require("../../../../config/db.inc.php");
require("../../../../web/common/cfg_comm.php");
session_load_from_tool("cdrviewer");
require("../../../../config/tools/system/cdrviewer/db.inc.php");
require("lib/functions.inc.php");
require("lib/db_connect.php");



error_reporting(E_ALL & ~E_NOTICE );

$max_limit = "0.002";  //  gigs 
$min_limit = "0.001"; 

$max_limit = $max_limit * 1024 * 1024  ; // kilo 

$min_limit = $min_limit * 1024 * 1024  ;


function directory_size ($dir){
	
	$size=exec("du -shk $dir");
	
	return $size ; 

}


function	remove_oldest_csv($dir) {
	
	
	$exten = array ("csv" , "CSV") ;
	
	
	if ($handle=opendir($dir))
	{
		while (false!==($file=readdir($handle)))
		if (($file!=".") && ($file!="..") )
		{


			$a=explode(".",$file);
			$x=count($a);

			if (in_array($a[$x-1] , $exten))
			{


				$file_list[]= $file;

			}


		}


		closedir($handle);			
	
	}

		// what is the oldest file in the directory ? 

		sort($file_list);

		reset($file_list);

		unlink($dir."/".$file_list[0]);
		
		print_r($file_list) ; 	
		
}



// how to pass arguments :
// 2008-02-21 00:00:00 2008-02-22 23:59:59
$start_time_day = $argv[1] ;

$start_time_hour = $argv[2] ;

$end_time_day = $argv[3] ;

$end_time_hour = $argv[4] ;




$nr_args = count($argv) ;

if ( $nr_args == 2 ){
	// get timestamp file from command line 
	
	$timestamp_file = $argv[1] ;

	if (!is_file($timestamp_file)) die ("cannot find the file specified : ".$timestamp_file."\n");

	$handle = fopen($timestamp_file,"a+");

	$contents = fread($handle, filesize($timestamp_file));
	$contents = trim($contents);
	// is that a date that we have ?
	// expecting format: yyyy-mm-dd hh:mm:ss

	$start_time_ar = explode(" ",$contents);

	$start_time_day = $start_time_ar[0] ;

	$start_time_hour = $start_time_ar[1] ;

	$start_time = $start_time_day." ".$start_time_hour;

	$end_time =  date("Y-m-d H:i:s");
	
	fclose($handle);

	
} else {

	$start_time = $start_time_day ." ".$start_time_hour;
	$end_time = $end_time_day." ". $end_time_hour  ;

}


$sz = directory_size($cdr_repository_path);

	
if ( (int)$sz  > (int)$max_limit ){

echo $min_limit."\n";
echo $sz."\n"; ;
echo $max_limit."\n";;
	
		
while ( (int)$sz  > (int)$min_limit ) {

	
		remove_oldest_csv($cdr_repository_path);

		$sz = directory_size($cdr_repository_path);
}
	
}



$ret = cdr_export($start_time , $end_time );

if ($nr_args == 2 ){

	if ( $ret==0 ) {

		// write the new file

		if (!$handle = fopen($timestamp_file, 'w')) {
	         die ("Cannot open file ($timestamp_file)");
         }

    	// Write $end_time to our opened file.

    	
    	if (fwrite($handle,$end_time ) === FALSE) {
       		 die ("Cannot write to file ($timestamp_file)");       
        }

		
    fclose($handle) ; 
    

	} else {

		// leave the old file 	, write to stdout we have a problem

		echo "Did not write new timestamp file \n" ;
	}
}

?>
