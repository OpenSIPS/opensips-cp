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


function write2json($command, $json_url, &$errors, &$status){
	global $config;

	$first_space = strpos($command, ' ');
	if ($first_space === false){
		$cmd = trim($command);
		$args = "";
	}
	else {
		$cmd = substr($command, 0, $first_space);
		$args = "?params=".str_replace(" ","," ,substr($command, $first_space+1, strlen($command)));
	}
	
	$url = $json_url."/".$cmd.$args;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$response = curl_exec($ch);

	if($response === false){
		$errors[] = curl_error($ch);
		return false;
	}

	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	curl_close($ch);

	//search for errors inside the reply
	$err = json_decode($response,true);
	if (array_key_exists("error", $err) && $err["error"]) {
		// error is reported
		$errors[] = "Error code ".$err["error"]["code"]." (".$err["error"]["message"].")";
	}

	return $response;
}


function mi_command($command, $mi_url, &$errors, &$status){

	/* identify and break down the MI URL */
	$a=explode(":",$mi_url);

	if ($a[0]!="json") {
		$errors[] = "Unknwon/Unsupported type[".$a[0]."] for MI URL <".$mi_url.">";
		return;
	}

	if (strlen($a[1])==0){
		$errors[] = "No URL found in JSON MI URL <".$mi_url.">";
		return;
	}

	$output = write2json( trim($command), substr($mi_url,5)/*URL*/, $errors, $status);

	if ($status && preg_match("/([0-9][0-9][0-9])/",$status,$matches) ) {
		if ( $matches[0] >=300) { 
			$errors[] = "MI command failed with ".$status;
		}
	}

	if ($errors) {
		echo "<font color='red'>".$errors[0]."</font>";
	}

    return $output; 
}

?>
