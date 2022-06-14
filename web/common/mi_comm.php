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


function write2json($command, $params_array, $json_url, &$errors){
	global $config;

	$args = array( "jsonrpc" => "2.0", "id"=> 1 );
	$args['method'] = trim($command);
	if ( isset($params_array) && !empty($params_array)) 
		$args['params'] = $params_array;
	$data_string = json_encode($args);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $json_url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($data_string))                                                                       
	);
	$response = curl_exec($ch);

	if($response === false){
		$errors[] = curl_error($ch);
		return false;
	}

	$status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

	curl_close($ch);

	if ($status>=300) {
		$errors[] = "MI HTTP request failed with ".$status." reply";
		return NULL;
	}

	//search for errors inside the reply
	$res = json_decode($response,true);
	if ( array_key_exists( "error", $res) ) {
		// error is reported
		$errors = $res["error"];
		return NULL;
	}

	return $res['result'];
}


function mi_command($command, $params_array, $mi_url, &$errors)
{
	if (empty($mi_url)) {
		$errors[] = "Failed to send MI command to an empty-string MI URL!";
		return;
	}

	/* identify and break down the MI URL */
	$a=explode(":",$mi_url);

	if ($a[0]!="json") {
		$errors[] = "Unknown/Unsupported type[".$a[0]."] for MI URL <".$mi_url.">";
		return;
	}

	if (strlen($a[1])==0){
		$errors[] = "No URL found in JSON MI URL <".$mi_url.">";
		return;
	}

	$output = write2json( trim($command), $params_array, substr($mi_url,5)/*URL*/, $errors);

	/* print here only the errors from MI level (bad param, no cmd, etc), but not errors from cmd level */
	if (isset($errors["code"]) && $errors["code"]<0) {
		echo "<font color='red'>"."MI command failed with code ".$errors["code"]." (".$errors["message"].")"."</font>";
	}

	return $output; 
}

?>
