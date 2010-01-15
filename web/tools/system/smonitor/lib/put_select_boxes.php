<?php

  session_start(); 

  $xmlrpc_host="";
  $xmlrpc_port="";
  $fifo_file="";

 $current_box=$_SESSION['smon_current_box'];  

 if (empty($current_box))
  		$current_box="";

 $boxlist=array();
 $boxlist=inspect_config_mi();


  if (!empty($_POST['box_val'])) {
  	
  		$current_box=$_POST['box_val'];
  		$_SESSION['smon_current_box']=$current_box ; 
  }

  if (!empty($_SESSION['smon_current_box']) && empty($current_box)) {
  		$current_box=$_SESSION['smon_current_box'];
  }


  $current_box=show_boxes($boxlist,$current_box);
  $_SESSION['smon_current_box']=$current_box;
  
  $comm_type=params($current_box);
  $_SESSION['comm_type']=$comm_type;     
 
?>
