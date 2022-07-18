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

$sql_search="";
$sql_vals=array();
$search_regexp=$_SESSION['tracer_search_regexp'];
if ($search_regexp!="") {
	$sql_search.=" AND msg REGEXP ?";
	array_push( $sql_vals, $search_regexp);
}
$search_callid=$_SESSION['tracer_search_callid'];
if ($search_callid!="") {
	$sql_search.=" AND callid=?";
	array_push( $sql_vals, $search_callid);
}
$search_traced_user=$_SESSION['tracer_search_traced_user'];
if ($search_traced_user!="") {
	$sql_search.=" AND trace_attrs=?";
	array_push( $sql_vals, $search_traced_user);
}
$search_start=$_SESSION['tracer_search_start'];
if ($search_start!="") {
	$sql_search.=" AND time_stamp>?";
	array_push( $sql_vals, $search_start);
}
$search_end=$_SESSION['tracer_search_end'];
if ($search_end!="") {
	$sql_search.=" AND time_stamp<?";
	array_push( $sql_vals, $search_end);
}


if (isset($_SESSION['delete']) && (isset($sql_search)) ){
	$_SESSION['tracer_search_regexp']="";
	$_SESSION['tracer_search_callid']="";
	$_SESSION['tracer_search_traced_user']="";
	$_SESSION['tracer_search_start']="";
	$_SESSION['tracer_search_end']="";
}
?>

<form action="<?=$page_name?>?action=search" method="post">
<?php csrfguard_generate(); ?>
<table width="70%" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td class="searchRecord" width="115">RegExp </td>
  <td class="searchRecord"><input type="text" name="search_regexp" value="<?=$search_regexp?>" id="search_regexp" maxlength="128" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Call ID </td>
  <td class="searchRecord"><input type="text" name="search_callid" value="<?=$search_callid?>"  id="search_callid" maxlength="128" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Traced User </td>
  <td class="searchRecord"><input type="text" name="search_traced_user" value="<?=$search_traced_user?>" id="search_traced_user" maxlength="128" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord"><input type="checkbox" name="set_start" value="set" onChange="changeState('start')" <?php if($search_start!="") echo('checked') ?>>Start Date </td>
  <td class="searchRecord"><?=print_start_date_time($search_start)?></td>
 </tr>
 <tr>
  <td class="searchRecord"><input type="checkbox" name="set_end" value="set" onChange="changeState('end')" <?php if($search_end!="") echo('checked') ?>>End Date </td>
  <td class="searchRecord"><?=print_end_date_time($search_end)?></td>
 </tr>
 <tr>
  <td class="searchRecord" colspan="2" align="center"><input type="checkbox" name="set_grouped" value="set" <?php if($_SESSION['grouped_results']) echo('checked') ?>> Group results by Call ID</td>
 </tr>
 

 <tr height="10">
  <td colspan="3" class="searchRecord" align="center">
	<input type="submit" name="search" value="Search" class="formButton">&nbsp;&nbsp;&nbsp;
	<input type="submit" name="show_all" value="Show All" class="formButton">&nbsp;&nbsp;&nbsp;
	<?php if(!$_SESSION['read_only']){ ?>
	<input type="submit" id="delete" name="delete" value="Delete Listed" class="formButton" onClick="return confirmDelete()" > &nbsp;&nbsp;&nbsp;
	<?php if  ( $toggle_button == "Disable" ) {
		echo '<input type="submit" name="toggle" value="'.$toggle_button.'" class="formButton">';
	} else if  ( $toggle_button == "Enable" ) {
		echo '<input type="submit" name="toggle" value="'.$toggle_button.'" class="formButton" style="background-color: #f26a60; ">';
        } else if(isset($toggle_button)) { 
		echo '<input type="submit" name="toggle" value="'.$toggle_button.'" class="formButton"  disabled>';
        }
	} ?>
  </td>
 </tr>
</table>
</form>


<table class="ttable" width="600" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <th class="listTitle" align="center">Date Time</th>
  <th class="listTitle" align="center">Method</th>
  <th class="listTitle" align="center">Address</th>
  <th class="listTitle" align="center" width="55">Message</th>
  <th class="listTitle" align="center" width="45">Call</th>
 </tr>
<?php



if (isset($_SESSION['delete']) && (isset($sql_search)) ){

	$sql="delete from ".$table." where (1=1) ".$sql_search;
	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}
	$stm->execute( $sql_vals );
	
	unset($_SESSION['delete']);

	unset($sql_search);
}



if ($_SESSION['grouped_results']) {
	$sql = "SELECT callid, MAX(id) AS mid FROM ".$table.
				" WHERE status='' AND direction='in'".$sql_search." GROUP BY callid ORDER BY mid DESC";
	$sql_cnt = "SELECT COUNT(DISTINCT(callid)) FROM ".$table." WHERE (1=1) ".$sql_search;
} else {
	$sql = "SELECT id FROM ".$table." WHERE (1=1) ".$sql_search." ORDER BY id DESC";
	$sql_cnt = "SELECT COUNT(*) FROM ".$table." WHERE (1=1) ".$sql_search;
}

$stm = $link->prepare($sql_cnt);
if ($stm===FALSE) {
	die('Failed to issue query ['.$sql_command.'], error message : ' . $link->errorInfo()[2]);
}
$stm->execute( $sql_vals );
$data_no = $stm->fetchColumn(0);

if ($data_no==0) echo('<tr><td colspan="5" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
else
{
	$page=$_SESSION[$current_page];
	$page_no=ceil($data_no/get_settings_value("results_per_page"));
	if ($page>$page_no) {
		$page=$page_no;
		$_SESSION[$current_page]=$page;
	}
	$start_limit=($page-1)*get_settings_value("results_per_page");
        if ($start_limit==0) $sql.=" limit ".get_settings_value("results_per_page");
        else $sql.=" limit ".get_settings_value("results_per_page")." OFFSET " . $start_limit;
	$stm = $link->prepare($sql);
	if ($stm===FALSE) {
		die('Failed to issue query ['.$sql_command.'], error message : ' . $link->errorInfo()[2]);
	}
	$stm->execute( $sql_vals );
	$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
	for($i=0; count($resultset)>$i;$i++)
	{
		if ($_SESSION['grouped_results']) $sql_="SELECT * FROM ".$table." WHERE callid='".$resultset[$i]['callid']."'".$sql_search." ORDER BY id ASC LIMIT 1";
		else $sql_="SELECT * FROM ".$table." WHERE id='".$resultset[$i]['id']."'".$sql_search." ORDER BY id LIMIT 1";
		$stm_ = $link->prepare($sql_);
		if ($stm_===FALSE) {
			die('Failed to issue query ['.$sql_command.'], error message : ' . $link->errorInfo()[2]);
		}
		$stm_->execute( $sql_vals );
		$resultset_ = $stm_->fetchAll(PDO::FETCH_ASSOC);
		if (($resultset_[0]['from_ip']!="127.0.0.1") && ($resultset_[0]['from_ip']!="255.255.255.255")) $trace_text="from ".$resultset_[0]['from_proto'].":".$resultset_[0]['from_ip'].":".$resultset_[0]['from_port'];
		else $trace_text="to ".get_ip($resultset_[0]['toip']);
		$details_msg='<a href="details.php?traceid='.$resultset_[0]['id'].'"><img src="../../../images/share/details.png" border="0" onClick="window.open(\'details.php?traceid='.$resultset_[0]['id'].'&regexp='.$search_regexp.'\',\'info\',\'scrollbars=1,width=550,height=300\');return false;"></a>';
		$matched_trace_id=$resultset_[0]['id'];
   ?>
   <tr>
   <td ><?=$resultset_[0]['time_stamp']?></td>
   <td ><?=$resultset_[0]['method']?></td>
   <td ><?=$trace_text?></td>
   <td  align="center"><?=$details_msg?></td>
   <td  align="center"><a href="<?=$page_name.'?id='.$resultset_[0]['id']?>" class="traceLink"><img src="../../../images/share/info.png" border="0"></a></td>
   </tr>
   <?php
   if (in_array($resultset_[0]['id'],$_SESSION['detailed_callid']))
   {
   	$sql_d="SELECT * FROM ".$table." WHERE callid=? ORDER BY id ASC";
	$stm_d = $link->prepare($sql_d);
	if ($stm_d===FALSE) {
		die('Failed to issue query ['.$sql_command.'], error message : ' . $link->errorInfo()[2]);
	}
	$stm_d->execute( array($resultset_[0]['callid']) );
	$resultset_d = $stm_d->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <tr><td colspan="5" align="center" >
    <table width="650" cellspacing="1" cellpadding="1" border="0" align="right">
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

    for ($j=0;count($resultset_d)>$j;$j++)

     {

     	if ($resultset_d[$j]['id']==$matched_trace_id) $row_style="rowOdd";

     	else $row_style="rowEven";


     	$direction = $resultset_d[$j]['direction'] ;

     	$method = $resultset_d[$j]['method'];

     	$to_ip=$resultset_d[$j]['to_ip'];

     	$from_ip=$resultset_d[$j]['fromip'];

     	// a request has no status
     	// a reply has status
     	$status = trim($resultset_d[$j]['status']);

     	$from_ip = $resultset_d[$j]['from_proto'].":".$resultset_d[$j]['from_ip'].":".$resultset_d[$j]['from_port'];
     	$to_ip = $resultset_d[$j]['to_proto'].":".$resultset_d[$j]['to_ip'].":".$resultset_d[$j]['to_port'];

     	// identify proxy
     	if (in_array($from_ip,get_settings_value('proxy_list'))) {

     		if ($proxy=="") $proxy=$from_ip;


     	}


     	if (in_array($to_ip,get_settings_value('proxy_list'))) {

     		if ($proxy=="") $proxy=$to_ip;

     	}



     	if ($proxy=="")
     	{
     		echo('<tr><td colspan="5" class="rowEven" align="center"><br>Error: Proxy '.$to_ip.'not set in local config ('.get_settings_value('proxy_list').')? <br><br></td></tr>');

     		exit();
     	}

		if ( $ftag_init=="" )
				$ftag_init = $ftag;

		if ($ftag_init==$ftag) {
				// downstream
				$dir = "down";

				if ( $status=="" ) {
				// request
						if ($direction == "in") {
								$left = "caller";
								$right = "proxy";
						} else {
								$left = "proxy";
								$right = "callee";
						}
				} else {
						// reply
						if ($direction == "in") {
								$left = "proxy";
								$right = "callee";
						} else {
								$left = "caller";
								$right = "proxy";
						}
				}
		} else {
				// upstream
				$dir = "up";
				if ( $status=="" ) {
				// request

						if ($direction == "in") {
								$right = "callee";
								$left = "proxy";
						} else {
								$right = "proxy";
								$left = "caller";
						}
				} else {
				// reply
						if ($direction == "in") {
								$right = "proxy";
								$left = "caller";
						} else {
								$right = "callee";
								$left = "proxy";
						}
				}
		}

		if ($direction == "in")  {
			$caller = $from_ip;
		}

		if ($direction == "out")  {
			$caller = $to_ip;
		}

		if ((( $status=="" )   && ($direction == "out")) || (( $status!="" )   && ($direction == "in"))) {
              if ($direction == "out" ) $tmp_ip = $to_ip ;
              if ($direction == "in" ) $tmp_ip = $from_ip ;
              if (isset($tmp_ip) ) {
                      $callee = $tmp_ip ;
                      unset($caller);
              }
      	}
	
	

     	if (( in_array($from_ip,get_settings_value('proxy_list') ) === true ) && ( in_array($to_ip,get_settings_value('proxy_list') ) === true ) )  {

     		if ($status=="") {

				$path='<div class="tooltip"> <img src="images/server.png"/><span class="tooltiptext"><strong>'.$from_ip.'</strong></span></div>';

     			$path.=' <img src="images/arrow_right.png" alt="to"> ';

				$path.='<div class="tooltip"> <img src="images/server.png"/><span class="tooltiptext"><strong>'.$to_ip.'</strong></span></div>';
     		}


     		if ($status!="") {


				$path='<div class="tooltip"> <img src="images/server.png"/><span class="tooltiptext"><strong>'.$from_ip.'</strong></span></div>';

     			$path.=' <img src="images/arrow_left.png" alt="to"> ';

				$path.='<div class="tooltip"> <img src="images/server.png"/><span class="tooltiptext"><strong>'.$to_ip.'</strong></span></div>';

     		}

     	} else {


     		if ($resultset_d[$j]['status']=="") $status="&nbsp;";

     		else $status=$resultset_d[$j]['status'];


     		if ($left=="proxy")	 {
				$path='<div class="tooltip"> <img src="images/server.png"/><span class="tooltiptext"><strong>'.$proxy.'</strong></span></div>';

     		} else
     		if ($left=="caller") {

				$path='<div class="tooltip"> <img src="images/caller.png"/><span class="tooltiptext"><strong>'.$caller.'</strong></span></div>';
     		}


     		if (($resultset_d[$j]['direction']=="in") && ($right=="proxy") &&  ($left=="caller")  ) {

     			$path.=' <img src="images/arrow_right.png" alt="to"> ';

     		}

     		if (($resultset_d[$j]['direction']=="out")  && ($right=="proxy") && ($left=="caller") )  {

     			$path.=' <img src="images/arrow_left.png" alt="to"> ';
     		}



     		if (($resultset_d[$j]['direction']=="in") && ($left=="proxy") && ($right=="callee")) {

     			$path.=' <img src="images/arrow_left.png" alt="to"> ';
     		}



     		if (($resultset_d[$j]['direction']=="out")  && ($left=="proxy") && ($right=="callee") )  {

     			$path.=' <img src="images/arrow_right.png" alt="to" > ';
     		}


     		if ($right=="proxy")	 {

				$path.='<div class="tooltip"> <img src="images/server.png"/><span class="tooltiptext"><strong>'.$proxy.'</strong></span></div>';

     		} else if ($right=="callee")  {

				$path.='<div class="tooltip"> <img src="images/callee.png"/><span class="tooltiptext"><strong>'.$callee.'</strong></span></div>';

     		}

     	}



     	$details='<a href="details.php?traceid='.$resultset_d[$j]['id'].'"><img src="../../../images/share/details.png" border="0" onClick="window.open(\'details.php?traceid='.$resultset_d[$j]['id'].'&regexp='.$search_regexp.'\',\'info\',\'scrollbars=1,width=550,height=300\');return false;"></a>';
      ?>
      <tr align="center">
       <td class="<?=$row_style?>"><?=$resultset_d[$j]['time_stamp']?></td>
       <td class="<?=$row_style?>"><?=$resultset_d[$j]['method']?></td>
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
	
     
     
     <img src="../../../images/share/spacer.gif" width="100%" height="10"><br>
    </td></tr>
    <?php

   }
		}

	}

?>
 <tr>
  <th colspan="5" class="siptraceTitle">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
     <tr>
      <th align="left">
       &nbsp;Page:
       <?php
       if ($data_no==0) echo('<font class="pageActive">0</font>&nbsp;');
       else {
       	$max_pages = get_settings_value("results_page_range");
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
      </th>
      <th align="right">Total Records: <?=$data_no?>&nbsp;</th>
     </tr>
    </table>
  </th>
 </tr>
</table>
