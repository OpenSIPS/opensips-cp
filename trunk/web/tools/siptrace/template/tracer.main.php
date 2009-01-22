<!--
 /*
 * $Id:$
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
-->

<div id="tooltip" style="background-color:ivory;width: 150px;
 height: 29px;border: solid 1px gray; text-align: center;">
</div>


<? 

if (!isset($toggle_button)) {

	// 	get siptrace status

	$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);

	// get status from the first one only
	$comm_type=params($mi_connectors[0]);

	mi_command("sip_trace" , $errors , $status);
	print_r($errors);
	$status = trim($status);
	if ($status == "on")
	$toggle_button = "disable";

	if ($status == "off")
	$toggle_button = "enable";



}

 ?>
<hr width="100%" color="#000000">
<div align="right">
<form action="<?=$page_name?>?action=toggle&toggle_button=<?=$toggle_button?>" method="post">
<? if  ( $toggle_button == "disable" ) {

	echo '<input type="submit" name="toggle" value="'.$toggle_button.'" class="formButton" style="background-color: #00ff00; ">';

} else
if  ( $toggle_button == "enable" )
{

	echo '<input type="submit" name="toggle" value="'.$toggle_button.'" class="formButton" style="background-color: #ff0000; ">';
}
?>
	</form>
</div>
<form action="<?=$page_name?>?action=search" method="post">
<?php
$sql_search="";
$search_regexp=$_SESSION['tracer_search_regexp'];
if ($search_regexp!="") $sql_search.=" AND msg REGEXP '".$search_regexp."'";
$search_callid=$_SESSION['tracer_search_callid'];
if ($search_callid!="") $sql_search.=" AND callid='".$search_callid."'";
$search_traced_user=$_SESSION['tracer_search_traced_user'];
if ($search_traced_user!="") $sql_search.=" AND traced_user='".$search_traced_user."'";
$search_start=$_SESSION['tracer_search_start'];
if ($search_start!="") $sql_search.=" AND time_stamp>'".$search_start."'";
$search_end=$_SESSION['tracer_search_end'];
if ($search_end!="") $sql_search.=" AND time_stamp<'".$search_end."'";


if (isset($_SESSION['delete']) && (isset($sql_search)) ){


	$_SESSION['tracer_search_regexp']="";
	$_SESSION['tracer_search_callid']="";
	$_SESSION['tracer_search_traced_user']="";
	$_SESSION['tracer_search_start']="";
	$_SESSION['tracer_search_end']="";

}

 ?>
<table width="85%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="searchTitle">Search SIP Traces by</td>
 </tr>
 <tr>
  <td class="searchRecord" width="115">RegExp :</td>
  <td class="searchRecord"><input type="text" name="search_regexp" value="<?=$search_regexp?>" id="search_regexp" maxlength="128" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Call ID :</td>
  <td class="searchRecord"><input type="text" name="search_callid" value="<?=$search_callid?>"  id="search_callid" maxlength="128" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Traced User :</td>
  <td class="searchRecord"><input type="text" name="search_traced_user" value="<?=$search_traced_user?>" id="search_traced_user" maxlength="128" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord"><input type="checkbox" name="set_start" value="set" onChange="changeState('start')" <?php if($search_start!="") echo('checked') ?>>Start Date :</td>
  <td class="searchRecord"><?=print_start_date_time($search_start)?></td>
 </tr>
 <tr>
  <td class="searchRecord"><input type="checkbox" name="set_end" value="set" onChange="changeState('end')" <?php if($search_end!="") echo('checked') ?>>End Date :</td>
  <td class="searchRecord"><?=print_end_date_time($search_end)?></td>
 </tr>
 <tr>
  <td class="searchRecord" colspan="2" align="center"><input type="checkbox" name="set_grouped" value="set" <?php if($_SESSION['grouped_results']) echo('checked') ?>> Group results by Call ID</td>
 </tr>
 

 <tr height="10">
  <td colspan="3" class="searchRecord" align="center"><input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;<input type="submit" name="show_all" value="Show All" class="searchButton">&nbsp;&nbsp;&nbsp;<input type="submit" id="deletebutton" name="delete" value="Delete" class="searchButton" onClick="return confirmDelete()" >  </td>
 </tr>
 <tr height="10">
  <td colspan="2" class="searchTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form><br>

<table width="500" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td class="Title" align="center">Date Time</td>
  <td class="Title" align="center">Method</td>
  <td class="Title" align="center">Address</td>
  <td class="Title" align="center" width="55">Message</td>
  <td class="Title" align="center" width="45">Call</td>
 </tr>
<?php
db_connect();



if (isset($_SESSION['delete']) && (isset($sql_search)) ){

	$sql="delete from ".$table." where 1 ".$sql_search;

	$result=mysql_query($sql) or die(mysql_error());


	unset($_SESSION['delete']);

	unset($sql_search);
}



if ($_SESSION['grouped_results']) {
	if ($sql_search=="") $sql="SELECT DISTINCT callid FROM ".$table." WHERE status='' AND direction='in' ORDER BY id ASC";
	else $sql="SELECT DISTINCT callid FROM ".$table." WHERE status='' AND direction='in'".$sql_search." ORDER BY id ASC";
}
else {
	if ($sql_search=="") $sql="SELECT id FROM ".$table." WHERE 1 ORDER BY id ASC";
	else $sql="SELECT id FROM ".$table." WHERE 1".$sql_search." ORDER BY id ASC";
}
$result=mysql_query($sql) or die(mysql_error());
$data_no=mysql_num_rows($result);
if ($data_no==0) echo('<tr><td colspan="5" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
else
{
	$page=$_SESSION[$current_page];
	$page_no=ceil($data_no/$config->results_per_page);
	if ($page>$page_no) {
		$page=$page_no;
		$_SESSION[$current_page]=$page;
	}
	$start_limit=($page-1)*$config->results_per_page;
	$sql.=" LIMIT ".$start_limit.", ".$config->results_per_page;
	$result=mysql_query($sql) or die(mysql_error());
	while($row=mysql_fetch_array($result))
	{
		if ($_SESSION['grouped_results']) $sql_="SELECT * FROM ".$table." WHERE callid='".$row['callid']."'".$sql_search." ORDER BY id ASC LIMIT 1";
		else $sql_="SELECT * FROM ".$table." WHERE id='".$row['id']."'".$sql_search." ORDER BY id LIMIT 1";
		$result_=mysql_query($sql_) or die(mysql_error());
		$row_=mysql_fetch_array($result_);
		{
			if (($row_['fromip']!="127.0.0.1") && ($row_['fromip']!="255.255.255.255")) $trace_text="from ".$row_['fromip'];
			else $trace_text="to ".get_ip($row_['toip']);
			$details_msg='<a href="details.php?traceid='.$row_['id'].'"><img src="images/trace.png" border="0" onClick="window.open(\'details.php?traceid='.$row_['id'].'&regexp='.$search_regexp.'\',\'info\',\'scrollbars=1,width=550,height=300\');return false;"></a>';
			$matched_trace_id=$row_['id'];
   ?>
   <tr>
   <td class="rowOdd"><?=$row_['time_stamp']?></td>
   <td class="rowOdd"><?=$row_['method']?></td>
   <td class="rowOdd"><?=$trace_text?></td>
   <td class="rowOdd" align="center"><?=$details_msg?></td>
   <td class="rowOdd" align="center"><a href="<?=$page_name.'?id='.$row_['id']?>" class="traceLink"><img src="images/details.gif" border="0"></a></td>
   </tr>
   <?php
   if (in_array($row_['id'],$_SESSION['detailed_callid']))
   {
   	$sql_d="SELECT * FROM ".$table." WHERE callid='".$row_['callid']."' ORDER BY id ASC";
   	$result_d=mysql_query($sql_d) or die(mysql_error());
    ?>
    <tr><td colspan="5" class="rowOdd">
    <table width="480" cellspacing="1" cellpadding="1" border="0" align="right">
     <tr align="center">
      <td class="Title">Date Time</td>
      <td class="Title">Method</td>
      <td class="Title">Status</td>
      <td class="Title">Path</td>
     <td class="Title">Details</td>
     </tr>
     <?php

     $caller="";
     $callee=array();
     $proxy="";

     $seq = 0 ;

     while($row_d=mysql_fetch_array($result_d))

     {

     	if ($row_d['id']==$matched_trace_id) $row_style="rowOdd";

     	else $row_style="rowEven";


     	$direction = $row_d['direction'] ;

     	$method = $row_d['method'];

     	$to_ip=$row_d['toip'];

     	$from_ip=$row_d['fromip'];

     	// a request has no status
     	// a reply has status
     	$status = trim($row_d['status']);

     	$a = explode (":",$to_ip)  ;
     	$b = explode (":",$from_ip);

     	// siptrace - opensips 1.2   (fromip does not have protocol)
     	if ( count($a) > count($b) ) {

     		$to_ip=$a[1].":".$a[2];

     	}


     	// identify proxy
     	if (in_array($from_ip,$proxy_list)) {

     		if ($proxy=="") $proxy=$from_ip;


     	}


     	if (in_array($to_ip,$proxy_list)) {

     		if ($proxy=="") $proxy=$to_ip;

     	}



     	if ($proxy=="")
     	{
     		echo('<tr><td colspan="5" class="rowEven" align="center"><br>Error: Proxy not set in local config ($proxy_list)? <br><br></td></tr>');

     		exit();
     	}



     	if ((( $status=="" )  && ($direction == "in")) || (( $status!="" )  && ($direction == "out"))){


     		if ($direction == "in")  {
     			$caller = $from_ip;
     		}


     		if ($direction == "out")  {
     			$caller = $to_ip;

     		}


     	} else

     	if ((( $status=="" )   && ($direction == "out")) || (( $status!="" )   && ($direction == "in"))) {

     		// XXX

     		if ($direction == "out" ) $tmp_ip = $to_ip ;

     		if ($direction == "in" ) $tmp_ip = $from_ip ;

     		if (isset($tmp_ip) ) {


     			$callee = $tmp_ip ;
     			unset($caller);

     		}


     	}

     	else {

     		echo "bug" ;

     	}


     	if ($from_ip==$proxy) {

     		$left= "proxy" ;
     	}
     	else if ($from_ip==$caller) {

     		$left= "caller" ;

     	}




     	if ($to_ip==$proxy) {

     		$right= "proxy" ;

     	} else if ($to_ip==$callee){

     		$right= "callee" ;

     	}



     	if ($from_ip==$callee){

     		$left="proxy" ;
     		$right="callee" ;

     	}


     	if ($to_ip==$caller){


     		$left="caller" ;
     		$right="proxy" ;

     	}

     	//  exception
     	//		if (( in_array($caller,$proxy_list ) === true ) && ( in_array($callee,$proxy_list ) === true ) )  {
     	if (( in_array($from_ip,$proxy_list ) === true ) && ( in_array($to_ip,$proxy_list ) === true ) )  {

     		//			$path='<img src="images/server.png" alt="SIP Proxy" onmouseover=if(t1)t1.Show(event,\''.$from_ip.'\') onmouseout=if(t1)t1.Hide(event) >';

     		/*
     		if (($row_d['direction']=="in") ) {

     		$path.=' <img src="images/arrow_right.png" alt="to"> ';

     		}


     		if (($row_d['direction']=="out") ) {

     		$path.=' <img src="images/arrow_left.png" alt="to"> ';
     		}

     		*/

     		if ($status=="") {


     			$path='<img src="images/server.png" alt="SIP Proxy" onmouseover=if(t1)t1.Show(event,\''.$from_ip.'\') onmouseout=if(t1)t1.Hide(event) >';

     			$path.=' <img src="images/arrow_right.png" alt="to"> ';

     			$path.='<img src="images/server.png" alt="SIP Proxy" onmouseover=if(t1)t1.Show(event,\''.$to_ip.'\') onmouseout=if(t1)t1.Hide(event) >';
     		}


     		if ($status!="") {


     			$path='<img src="images/server.png" alt="SIP Proxy" onmouseover=if(t1)t1.Show(event,\''.$from_ip.'\') onmouseout=if(t1)t1.Hide(event) >';

     			$path.=' <img src="images/arrow_left.png" alt="to"> ';

     			$path.='<img src="images/server.png" alt="SIP Proxy" onmouseover=if(t1)t1.Show(event,\''.$to_ip.'\') onmouseout=if(t1)t1.Hide(event) >';

     		}

     	} else {


     		if ($row_d['status']=="") $status="&nbsp;";

     		else $status=$row_d['status'];


     		if ($left=="proxy")	 {

     			$path='<img src="images/server.png" alt="SIP Proxy" onmouseover=if(t1)t1.Show(event,\''.$proxy.'\') onmouseout=if(t1)t1.Hide(event) >';

     		} else
     		if ($left=="caller") {


     			$path='<img src="images/caller.png" alt="UA: Callee" onmouseover=if(t1)t1.Show(event,\''.$caller.'\') onmouseout=if(t1)t1.Hide(event) >';
     		}


     		if (($row_d['direction']=="in") && ($right=="proxy") &&  ($left=="caller")  ) {

     			$path.=' <img src="images/arrow_right.png" alt="to"> ';

     		}

     		if (($row_d['direction']=="out")  && ($right=="proxy") && ($left=="caller") )  {

     			$path.=' <img src="images/arrow_left.png" alt="to"> ';
     		}



     		if (($row_d['direction']=="in") && ($left=="proxy") && ($right=="callee")) {

     			$path.=' <img src="images/arrow_left.png" alt="to"> ';
     		}



     		if (($row_d['direction']=="out")  && ($left=="proxy") && ($right=="callee") )  {

     			$path.=' <img src="images/arrow_right.png" alt="to" > ';
     		}


     		if ($right=="proxy")	 {

     			$path.='<img src="images/server.png" alt="SIP Proxy" onmouseover=if(t1)t1.Show(event,\''.$proxy.'\') onmouseout=if(t1)t1.Hide(event) >';

     		} else if ($right=="callee")  {


     			$path.='<img src="images/callee.png" alt="UA: Callee" onmouseover=if(t1)t1.Show(event,\''.$callee.'\') onmouseout=if(t1)t1.Hide(event) >';

     		}

     	}



     	$details='<a href="details.php?traceid='.$row_d['id'].'"><img src="images/trace.png" border="0" onClick="window.open(\'details.php?traceid='.$row_d['id'].'&regexp='.$search_regexp.'\',\'info\',\'scrollbars=1,width=550,height=300\');return false;"></a>';
      ?>
      <tr align="center">
       <td class="<?=$row_style?>"><?=$row_d['time_stamp']?></td>
       <td class="<?=$row_style?>"><?=$row_d['method']?></td>
       <td class="<?=$row_style?>"><?=$status?></td>
       <td class="<?=$row_style?>"><?=$path?></td> 
       <td class="<?=$row_style?>"><?=$details?></td>
      </tr>
      <?php
      $seq ++ ;
     }

     ?>

     <tr>
      <td colspan="6" class="Title" align="center">
       <img src="images/caller.png" alt="UA: Caller"> Caller  
        <img src="images/server.png" alt="UA: Proxy"> Proxy 
        <img src="images/callee.png" alt="UA: Callee"> Callee      
      </td>
     </tr>
    </table>
	
     
     
     <img src="images/spacer.gif" width="100%" height="10"><br>
    </td></tr>
    <?php

   }
		}

	}
}
db_close();
?>
 <tr>
  <td colspan="5" class="Title">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
     <tr>
      <td align="left">
       &nbsp;Page:
       <?php
       if ($data_no==0) echo('<font class="pageActive">0</font>&nbsp;');
       else {
       	$max_pages = $config->results_page_range;
       	// start page
       	if ($page % $max_pages == 0) $start_page = $page - $max_pages + 1;
       	else $start_page = $page - ($page % $max_pages) + 1;
       	// end page
       	$end_page = $start_page + $max_pages - 1;
       	if ($end_page > $page_no) $end_page = $page_no;
       	// back block
       	if ($start_page!=1) echo('&nbsp;<a href="'.$page_name.'?page='.($start_page-$max_pages).'" class="menuItem"><b>&lt;&lt;</b></a>&nbsp;');
       	// current pages
       	for($i=$start_page;$i<=$end_page;$i++)
       	if ($i==$page) echo('<font class="pageActive">'.$i.'</font>&nbsp;');
       	else echo('<a href="'.$page_name.'?page='.$i.'" class="pageList">'.$i.'</a>&nbsp;');
       	// next block
       	if ($end_page!=$page_no) echo('&nbsp;<a href="'.$page_name.'?page='.($start_page+$max_pages).'" class="menuItem"><b>&gt;&gt;</b></a>&nbsp;');
       }
       ?>
      </td>
      <td align="right">Total Records: <?=$data_no?>&nbsp;</td>
     </tr>
    </table>
  </td>
 </tr>
</table>
