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


function write2fifo($fifo_cmd, &$errors, &$status){

	global $config;

	/* check fifo file */
	if (!file_exists($config->fifo_server)) {
		$errors[]="No FIFO file to SIP Server"; return;
	}

	/* open fifo now */
	$fifo_handle=fopen($config->fifo_server, "w");
	if (!$fifo_handle) {
		$errors[]="sorry -- cannot open write fifo";	return;
	}

	/* create fifo for replies */
	@system("mkfifo -m 666 ".$config->reply_fifo_path);

	/* add command separator */
	$fifo_cmd=$fifo_cmd."\n";
//	$fifo_cmd=$fifo_cmd."\n";
	/* write fifo command */
	if (fwrite($fifo_handle, $fifo_cmd)==-1) {
		@unlink($config->reply_fifo_path);
		@fclose($fifo_handle);
		$errors[]="sorry -- fifo writing error"; return;
	}
	@fclose($fifo_handle);

	/* read output now */
	@$fp = fopen($config->reply_fifo_path, "r");
	if (!$fp) {
		@unlink($config->reply_fifo_path);
		$errors[]="sorry -- reply fifo opening error"; return;
	}

	stream_set_timeout($fp, 5);
	$status=fgetS($fp, 256);
	$info = stream_get_meta_data($fp);
	
	if ($info['timed_out']) {
		fclose($fp);
		$errors[]= 'Read from FIFO file to SIP server timed out'; return;
	}
	
	if (!$status) {
		fclose($fp);
	   @unlink($config->reply_fifo_path);
		$errors[]="sorry -- reply fifo reading error"; return;
	}
	
	$rd=fread($fp, 8192);
	fclose($fp);
	@unlink($config->reply_fifo_path);
	
	return $rd;
}



function write2fifo_new($command, &$errors, &$status){

	global $config;
	global $fifo_file ; 	

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
						
						$fifo_cmd=":".$cmd.":".$config->reply_fifo_filename."\n";

			} else {
					/* command with args */
					foreach($command_args as $key=>$val){
               			 $arg_list.=$val."\n";
		}
		
		
			$fifo_cmd=":".$cmd.":".$config->reply_fifo_filename."\n".$arg_list;

		}

	
	/* check fifo file */
	if (!file_exists($fifo_file)) {
		$errors[]="No FIFO file to SIP Server"; return;
	}

	/* open fifo now */
	$fifo_handle=fopen($fifo_file, "w");
	if (!$fifo_handle) {
		$errors[]="sorry -- cannot open write fifo";	return;
	}

	/* create fifo for replies */
	@system("mkfifo -m 666 ".$config->reply_fifo_path);

	/* add command separator */
	$fifo_cmd=$fifo_cmd."\n";

	/* write fifo command */

	if (fwrite($fifo_handle, $fifo_cmd)==-1) {
		@unlink($config->reply_fifo_path);
		@fclose($fifo_handle);
		$errors[]="sorry -- fifo writing error"; return;
	}

	@fclose($fifo_handle);

	/* read output now */

	@$fp = fopen($config->reply_fifo_path, "r");
	if (!$fp) {
		@unlink($config->reply_fifo_path);
		$errors[]="sorry -- reply fifo opening error"; return;
	}

	stream_set_timeout($fp, 5);
	$status=fgetS($fp, 256);
	$info = stream_get_meta_data($fp);
	
	if ($info['timed_out']) {
		fclose($fp);
		$errors[]= 'Read from FIFO file to SIP server timed out'; return;
	}
	
	if (!$status) {
		fclose($fp);
	   @unlink($config->reply_fifo_path);
		$errors[]="sorry -- reply fifo reading error"; return;
	}
	
	$rd=fread($fp, 8192);
	fclose($fp);
	@unlink($config->reply_fifo_path);
	
	return $rd;
}


function xml_do_call($xmlrpc_host,$xmlrpc_port,$request,&$errors,&$status) {
    
   $fp = @fsockopen($xmlrpc_host, $xmlrpc_port, $errno, $errstr);
    if (!$fp) {
    echo "sorry -- cannot connect to xmlrpc server" . "<BR>";
    echo  $errno ." - ". $errstr ;
    exit;
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


function write2xmlrpc($command,&$errors,&$status){

	global $xmlrpc_host ; 
	global $xmlrpc_port ; 

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

	$xml=(substr($response,strpos($response,"\r\n\r\n")+4));

	$str=xmlrpc_decode($xml);

	for ($j=0;$j<count($matches[0]);$j++){
		$temp = substr($matches[0][$j],8);
		$str = substr($temp,0,-9);		
	}

	$status = $str ; 

	if (is_array($str)) {

		$errors[] = "write2xmlrpc(): some error occured \n" . "ErrorCode: " . $str['faultCode'] ."\n".$str['faultString']."\n" ;; 

	}

	return  $str ;

}

function mi_command($command,&$errors,&$status){

    global $comm_type ; 
    global $xmlrpc_host ; 
    global $xmlrpc_port ; 
    global $fifo_file ; 

    $buf="";
    if (strtolower($comm_type)=="fifo"){
    
    $buf=write2fifo_new($command, $errors, $status);
    }

    if (strtolower($comm_type)=="xmlrpc"){
    
    $buf=write2xmlrpc($command,$errors,$status);
    
    }
    return $buf ; 

}

?>
