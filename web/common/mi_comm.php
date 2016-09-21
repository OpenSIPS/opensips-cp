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
	if ($err["error"]) {
		// error is reported
		$errors[] = "Error code ".$err["error"]["code"]." (".$err["error"]["message"].")";
	}
	
	return $response;
}


function write2udp($command,$udp_host,$udp_port,&$errors,&$status){
	global $config;

	$first_space = strpos($command, ' ');
	if ($first_space === false){
		$cmd = ":".trim($command).":\n";
		$args = "";
	}
	else {
		$cmd = ":".substr($command, 0, $first_space).":\n";
		$args = str_replace(" ",":\n" ,substr($command, $first_space+1, strlen($command))).":\n";
	}

	#create the udp socket
	if(!($sock = socket_create(AF_INET, SOCK_DGRAM, 0))){
    	$errorcode = socket_last_error();
	    $errormsg = socket_strerror($errorcode);
		$errors [] = "Couldn't create socket: [$errorcode] $errormsg";
		$status = "500 Error";
		return;
	}
	//Send the message to the server
    if( ! socket_sendto($sock, $cmd.$args , strlen($cmd.$args) , 0 , $udp_host , $udp_port))
    {
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);
		$errors [] = "Could not send data: [$errorcode] $errormsg";
		$status = "500 Could not send data";
		return;
    }
         
    //Now receive reply from server and print it
    if(socket_recv ( $sock , $reply , 2045 , MSG_WAITALL ) === FALSE)
    {
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);
		$errors [] = "Could not receive data: [$errorcode] $errormsg";
		$status = "500 Could not receive data";
		return;
    }

	$first_enter = strpos($reply, PHP_EOL);
    if ($first_enter === false){
        $status = trim($reply);
        $args = "";
    }
    else {
        $status = trim(substr($reply, 0, $first_enter));
        $message = substr($reply, $first_enter+1, strlen($reply));
    }    

	$errors = NULL;
	return $message;
}

function write2fifo($command, $fifo_file, &$errors, &$status){

	global $config;

	$reply_fifo_filename="webfifo_".rand();
	$reply_fifo_path="/tmp/".$reply_fifo_filename;

      $pos=strpos($command," ");
   
        if ($pos===false) {

             $cmd=$command;
             $arg="";
      
         }else {

		        $cmd=trim(substr($command,0,$pos));
	    	        $arg=trim(substr($command,$pos,strlen($command)));

			  $command_args=array();	
		    	  $command_args=explode(" ",$arg);	


        }

	    if ($arg==""){
				/* no args */
				/*if the command contains delimiter ':' , then return , or else bad things happen  */
                  
                  $found_delimiter=strpos($cmd,":");
					 	if ( $found_delimiter === false )
					 	{
								// mkay					 	
					 	} 
					 								 
					 	 else 
					 	 
					 	 { 
			 	 			$err="Bad command. character ':' not permited here.";
							$status=$err;			 	 			
			 	 			return $err ;
					 	 			
				 	 	 }	  
						
						$fifo_cmd=":".$cmd.":".$reply_fifo_filename."\n";

			} else {
					/* command with args */
					foreach($command_args as $key=>$val){
               			 $arg_list.=$val."\n";
		}
		
		
			$fifo_cmd=":".$cmd.":".$reply_fifo_filename."\n".$arg_list;

		}

	
	/* check fifo file */
	if (!file_exists($fifo_file)) {
		$errors[]="Cannot connect to OpenSIPS Server via Management Interface ($fifo_file)"; return;
	}

	/* open fifo now */
	$fifo_handle=fopen($fifo_file, "w");
	if (!$fifo_handle) {
		$errors[]="sorry -- cannot open write fifo";	return;
	}

	/* create fifo for replies */
	@system("mkfifo -m 666 ".$reply_fifo_path);

	/* add command separator */
	$fifo_cmd=$fifo_cmd."\n";

	/* write fifo command */
	if (fwrite($fifo_handle, $fifo_cmd)==-1) {
		@unlink($reply_fifo_path);
		@fclose($fifo_handle);
		$errors[]="sorry -- fifo writing error"; return;
	}

	@fclose($fifo_handle);

	/* read output now */

	@$fp = fopen($reply_fifo_path, "r");
	if (!$fp) {
		@unlink($reply_fifo_path);
		$errors[]="sorry -- reply fifo opening error"; return;
	}

	stream_set_timeout($fp, 20);
	$status=fgetS($fp, 256);
	$info = stream_get_meta_data($fp);
	
	if ($info['timed_out']) {
		fclose($fp);
		$errors[]= 'Read from FIFO file to SIP server timed out'; return;
	}
	
	if (!$status) {
		fclose($fp);
	   @unlink($reply_fifo_path);
		$errors[]="sorry -- reply fifo reading error"; return;
	}
	
	$rd=fread($fp, 8192);
	fclose($fp);
	@unlink($reply_fifo_path);
	return $rd;
}


function xml_do_call($xmlrpc_host,$xmlrpc_port,$request,&$errors,&$status) {
    
   $fp = @fsockopen($xmlrpc_host, $xmlrpc_port, $errno, $errstr);
    if (!$fp) {
    echo "Cannot connect to OpenSIPS Server via Management Interface ($xmlrpc_host/$xmlrpc_port)" . "<BR>";
    //echo  $errno ." - ". $errstr ;
    return;
    }

   $query = "POST /RPC2 HTTP/1.0\nUser_Agent: opensips-cp\nHost: ".$xmlrpc_host."\nContent-Type: text/xml\nContent-Length: ".strlen($request)."\n\n".$request."\n";
   if (!fputs($fp, $query, strlen($query))) {
     $errors[] = "Write error"; return -1;
   }

   $contents = '';

   while (!feof($fp)) { 	    
     $contents .= fgets($fp);
   }

   fclose($fp);
   return $contents;

}


function write2xmlrpc($command, $xmlrpc_host, $xmlrpc_port, &$errors,&$status){

	// command with arguments 
	$full_command=explode(" ",$command);

	/* extract command , args  */
	$my_command=array_shift($full_command);

	/* args */
	$params=$full_command ; 

	if (!isset($params[0])) {
		$params=NULL ;
	}

	$request = xmlrpc_encode_request($my_command, $params);
    $response = xml_do_call($xmlrpc_host, $xmlrpc_port, $request,$errors,$status);
    $xml_str=(substr($response,strpos($response,"\r\n\r\n")+4));

	//search for errors inside the reply
	$xml = xmlrpc_decode($xml_str);
	if (is_array($xml) && xmlrpc_is_fault($xml)) {
		// error is reported
		$errors[] = "Error code ".$xml["faultCode"]." (".$xml["faultString"].")";
	}

	return $xml;
}


function mi_command($command, $mi_url, &$mi_type, &$errors, &$status){

	/* identify and break down the MI URL */
	$a=explode(":",$mi_url);

	switch ($a[0]) {
		case "udp":
			$mi_type="udp";
			if (strlen($a[1])==0){
				$errors[] = "No host found in UDP MI URL <".$mi_url.">";
				return;
			}
			if (strlen($a[2])==0){
				$errors[] = "No port found in UDP MI URL <".$mi_url.">";
				return;
			}
			$output = write2udp(trim($command),$a[1]/*host*/,$a[2]/*port*/,$errors,$status);
			break;

		case "xmlrpc":
			$mi_type="xmlrpc";
			if (strlen($a[1])==0){
				$errors[] = "No host found in XMLRPC MI URL <".$mi_url.">";
				return;
			}
			if (strlen($a[2])==0){
				$errors[] = "No port found in XMLRPC MI URL <".$mi_url.">";
				return;
			}
			$output = write2xmlrpc(trim($command),$a[1]/*host*/,$a[2]/*port*/,$errors,$status);
			break;

		case "fifo":
			$mi_type="fifo";
			if (strlen($a[1])==0){
				$errors[] = "No file found in FIFO MI URL <".$mi_url.">";
				return;
			}
			$output = write2fifo(trim($command), $a[1] /*fifo filename*/, $errors, $status);
			break;

		case "json":
			$mi_type="json";
			if (strlen($a[1])==0){
				$errors[] = "No URL found in JSON MI URL <".$mi_url.">";
				return;
			}
			$output = write2json(trim($command),substr($mi_url,5)/*URL*/,$errors,$status);
			break;
		default:
			$errors[] = "Unknwon type[".$a[0]."] for MI URL <".$mi_url.">";
			return;
	}

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
