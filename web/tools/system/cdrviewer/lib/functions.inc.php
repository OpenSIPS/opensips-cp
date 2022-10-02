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



function print_object($obj_name, $start_value, $end_value, $select_value, $disabled, $disabler, $extra)
{
 $width = 56 + (preg_match("/year/",$obj_name)?20:0);
 echo('<select name="'.$obj_name.'" id="'.$obj_name.'" style="width:'.$width.'px!important" size="1" class="dataSelect" '.($disabled&&(!$disabler)?"disabled":"").$extra.'>');

 if ($disabler) {
	echo('<option value="none" '.($disabled?"selected":"").'>----</option>');
	if ($disabled) $select_value="";
 }
 for ($i=$end_value;$i>=$start_value;$i--)
 {
 	if ($i<10) $value="0".$i;
 	else $value=$i;
 	if ($value==$select_value) $selected="selected";
 	else $selected="";
 	echo('<option value="'.$value.'" '.$selected.'>'.$value.'</option>');
 }
 ?>
 </select>
<?php
}

function print_cdr_filter_date_time($datetime,$obj_name)
{
	if ($datetime=="") {
		$disabled=true;
		$a=date("d");
		$b=date("m");
		$c=date("Y");
		$d=date("H");
		$e=date("i");
		$f=date("s");
	}
	else {
		$disabled=false;
		$a=substr($datetime,8,2);
		$b=substr($datetime,5,2);
		$c=substr($datetime,0,4);
		$d=substr($datetime,11,2);
		$e=substr($datetime,14,2);
		$f=substr($datetime,17,2);;
	}
	print_object($obj_name."_year",date("Y")-5,date("Y"),$c,$disabled,true, ' onChange="changeState(\''.$obj_name.'\')"' ); echo("<b>-</b>");
	
	print_object($obj_name."_month",1,12,$b,$disabled,false,NULL); echo("<b>-</b>");
	print_object($obj_name."_day",1,31,$a,$disabled,false,NULL); echo("&nbsp;&nbsp;");
	print_object($obj_name."_hour",0,23,$d,$disabled,false,NULL); echo("<b>:</b>");
	print_object($obj_name."_minute",0,59,$e,$disabled,false,NULL); echo("<b>:</b>");
	print_object($obj_name."_second",0,59,$f,$disabled,false,NULL);
	echo("<script>changeState('".$obj_name."')</script>");
}


function get_field($string){
	global $show_field ;

	reset($show_field);

	foreach ($show_field as $key => $value) {

		if ($value == $string )

		return $key;

	}

}

function cdr_export($start_time,  $end_time ) {

	global $config ;
	$export_csv = get_settings_value("export_csv");
	global $cdr_repository_path ;
	global $cdr_set_field_names;
	global $cdr_export_time_limit;
	global $delay;


	$cdr_table = get_settings_value("cdr_table");

	$sql = "select * " ;

	$sql.=" from ".$cdr_table . " where  ";

	$sql.=" DATE_SUB( ?, INTERVAL ".$delay." SECOND) <= time  and  ";

	$sql.="time <= DATE_SUB( ?, INTERVAL ".$delay." SECOND)"   ;

	$sql .= " order by time desc " ;

	if (isset($cdr_export_time_limit))
		set_time_limit ($cdr_export_time_limit);

	$stm = $link->prepare($sql);
	if ($stm === false)
		die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
	$stm->execute( array($start_time,$end_time) );
	$result = $stm->fetchAll(PDO::FETCH_ASSOC);


	$num_rows = count($result);

	echo $num_rows ;


	$start_time_name=explode(" ",$start_time);

	$end_time_name=explode(" ",$end_time);

	$ts_start = strtotime($start_time_name[0]." ".$start_time_name[1]) - $delay ;

	$from_unix_timestamp_start=strftime("%Y-%m-%d_%H:%M:%S",$ts_start);

	$ts_end=strtotime($end_time_name[0]." ".$end_time_name[1]) - $delay ;

	$from_unix_timestamp_end=strftime("%Y-%m-%d_%H:%M:%S",$ts_end);

	$date=$from_unix_timestamp_start."__".$from_unix_timestamp_end ;

	$outfile='cdr-'.$date.'.csv';

	$f = fopen($cdr_repository_path.'/'.$outfile, "w");


	$field_separator = ",";
	$line_terminator = "\n" ;

	if ($cdr_set_field_names == 1 ) {

		$i = 0;
		foreach ($export_csv as $key=>$value) {
			$line .= $value;
			if ($i < count($export_csv) - 1)
				$line .= $field_separator;
			$i++;
		}
		
		$line .=  $line_terminator ;

		fwrite($f , $line , strlen($line));

	}
	for ($j=0;count($result)>$j;$j++) {
		$line = "";

		$i = 0;
		foreach ($export_csv as $key=>$value) {
			$line .= $result[$j][$key];
			if ($i < count($export_csv) - 1)
				$line .= $field_separator;
			$i++;
		}

		$line .=  $line_terminator ;

		fwrite($f , $line , strlen($line));
	}


	fclose($f);

}

function cdr_put_to_download($start_time , $end_time , $sql_search , $outfile){
 
	global $config ;
	$export_csv = get_settings_value("export_csv");
	global $cdr_set_field_names;
	global $cdr_export_time_limit;
	global $link;

	$cdr_table = get_settings_value("cdr_table");

	$sql = "select * " ;
	$sql_vals=array();

	$sql.=" from ".$cdr_table . " where (1=1) ";


	if (($start_time !="")) {
		$sql.=" and ?  <= time ";
		array_push( $sql_vals, $start_time);
	}


	if (($end_time !="")){
		$sql.=" and time <= ? "   ;
		array_push( $sql_vals, $end_time);
	}


	if ($sql_search!="") $sql.=  $sql_search  ;
	if ($config->db_driver == "mysql")
		$link->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
	if (isset($cdr_export_time_limit))
		set_time_limit ($cdr_export_time_limit);

	$sql .= " order by time desc " ;
	
	$stm = $link->prepare($sql);
	if ($stm === false)
		die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
	$stm->execute( $sql_vals );

	$f = fopen($outfile, "w");

	$field_separator = ",";
	$line_terminator = "\n" ;
	
	if ($cdr_set_field_names == 1 ) {

		$i = 0;
		foreach($export_csv as $key=>$value) {
			$line .= $value;
			if ($i < count($export_csv) - 1)
				$line .= $field_separator;
			$i++;
		}
		
		$line .=  $line_terminator ;

		fwrite($f , $line , strlen($line));
	}

	while ($row = $stm->fetch(PDO::FETCH_ASSOC)) {

		if ( function_exists("process_cdr_line_for_export") )
			process_cdr_line_for_export( $row );
		$line = "";

		$i = 0;
		foreach ($export_csv as $key => $value) {

			$line .= $row[$key];

			if  ($i < count($export_csv) - 1 )

			$line .= $field_separator ;
			$i++;
		}

		$line .=  $line_terminator ;
		fwrite($f , $line , strlen($line));
	}
	fclose($f);
	$stm->closeCursor();

	//	put to download


	$f = fopen($outfile, "rb");
	$content_len = (int) filesize($outfile);
	$content_file = fread($f, $content_len);
	fclose($f);

	$date=date("Y-m-d");
	$output_file = 'cdr-'.$date.'.csv';

	@ob_end_clean();
	@ini_set('zlib.output_compression', 'Off');
	header('Pragma: public');

	header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
	header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
	header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
	header('Content-Transfer-Encoding: none');
	header('Content-Type: application/octetstream; name="' . $output_file . '"'); //This should work for IE & Opera
	header('Content-Type: application/octet-stream; name="' . $output_file . '"'); //This should work for the rest
	header('Content-Disposition: inline; filename="' . $output_file . '"');
	header("Content-length: $content_len");

	echo $content_file;



	if ( unlink ($outfile) === false )
	die("cannot remove temp file ");
}

?>
