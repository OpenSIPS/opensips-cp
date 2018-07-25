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



function print_object($obj_name, $start_value, $end_value, $select_value, $disabled)
{
 $width = 56 + (preg_match("/year/",$obj_name)?20:0);
?>
 <select name="<?=$obj_name?>" id="<?=$obj_name?>" style="width:<?=$width?>px!important" size="1" class="dataSelect" <?=$disabled?>>
 <?php
 for ($i=$start_value;$i<=$end_value;$i++)
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

function print_start_date_time($datetime)
{
	$obj_name="start";
	if ($datetime=="") {
		$status="disabled";
		$a=1;
		$b=1;
		$c=date("Y");
		$d=0;
		$e=0;
		$f=0;
	}
	else {
		$status="";
		$a=substr($datetime,8,2);
		$b=substr($datetime,5,2);
		$c=substr($datetime,0,4);
		$d=substr($datetime,11,2);
		$e=substr($datetime,14,2);
		$f=substr($datetime,17,2);;
	}
	print_object($obj_name."_year",date("Y")-5,date("Y")+5,$c,$status); echo("<b>-</b>");
	print_object($obj_name."_month",1,12,$b,$status); echo("<b>-</b>");
	print_object($obj_name."_day",1,31,$a,$status); echo("&nbsp;&nbsp;");
	print_object($obj_name."_hour",0,23,$d,$status); echo("<b>:</b>");
	print_object($obj_name."_minute",0,59,$e,$status); echo("<b>:</b>");
	print_object($obj_name."_second",0,59,$f,$status);
}

function print_end_date_time($datetime)
{
	$obj_name="end";
	if ($datetime=="") {
		$status="disabled";
		$a=date("d");
		$b=date("m");
		$c=date("Y");
		$d=23;
		$e=59;
		$f=59;
	}
	else {
		$status="";
		$a=substr($datetime,8,2);
		$b=substr($datetime,5,2);
		$c=substr($datetime,0,4);
		$d=substr($datetime,11,2);
		$e=substr($datetime,14,2);
		$f=substr($datetime,17,2);;
	}
	print_object($obj_name."_year",date("Y")-5,date("Y")+5,$c,$status); echo("<b>-</b>");
	print_object($obj_name."_month",1,12,$b,$status); echo("<b>-</b>");
	print_object($obj_name."_day",1,31,$a,$status); echo("&nbsp;&nbsp;");
	print_object($obj_name."_hour",0,23,$d,$status); echo("<b>:</b>");
	print_object($obj_name."_minute",0,59,$e,$status); echo("<b>:</b>");
	print_object($obj_name."_second",0,59,$f,$status);
}


function get_field($string){
	global $show_field ;

	reset($show_field);

	$x = count($show_field);

	for($i = 0;$i < $x; $i++) {

		if ($show_field[$i][key($show_field[$i])] == $string )

		return key($show_field[$i]);

	}

}

function cdr_export($start_time,  $end_time ) {

	global $config ;
	global $export_csv;
	global $cdr_repository_path ;
	global $cdr_set_field_names;
	global $delay;


	$cdr_table = $config->cdr_table ;

	$sql = "select * " ;

	$sql.=" from ".$cdr_table . " where  ";

	$sql.=" unix_timestamp( ? ) - ".$delay."  <= unix_timestamp(time)  and  ";

	$sql.="unix_timestamp(time) <= unix_timestamp( ? ) - ".$delay   ;

	$sql .= " order by time desc " ;

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

		for ($i = 0 ; $i < count($export_csv)  ; $i++) {


			$line .= $export_csv[$i][key($export_csv[$i])];

			if  ($i < count($export_csv) - 1 )

			$line .= $field_separator ;

		}
		$line .=  $line_terminator ;

		fwrite($f , $line , strlen($line));

	}
	for ($j=0;count($result)>$j;$j++) {
		$line = "";

		for ($i = 0 ; $i < count($export_csv)  ; $i++) {


			$line .= $result[$j][key($export_csv[$i])];

			if  ($i < count($export_csv) - 1 )

			$line .= $field_separator ;

		}


		$line .=  $line_terminator ;

		fwrite($f , $line , strlen($line));
	}


	fclose($f);

}

function cdr_put_to_download($start_time , $end_time , $sql_search , $outfile){

	global $config ;
	global $export_csv;
	global $cdr_set_field_names;
	global $link;

	$cdr_table = $config->cdr_table ;

	$sql = "select * " ;
	$sql_vals=array();

	$sql.=" from ".$cdr_table . " where (1=1) ";


	if (($start_time !="")) {
		$sql.=" and unix_timestamp( ? )  <= unix_timestamp(time)";
		array_push( $sql_vals, $start_time);
	}


	if (($end_time !="")){
		$sql.=" and unix_timestamp(time) <= unix_timestamp( ? )"   ;
		array_push( $sql_vals, $end_time);
	}


	if ($sql_search!="") $sql.=  $sql_search  ;

	$sql .= " order by time desc " ;
	
	$stm = $link->prepare($sql);
	if ($stm === false)
		die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
	$stm->execute( $sql_vals );
	$result = $stm->fetchAll(PDO::FETCH_ASSOC);

	$num_rows = count($result);


	$f = fopen($outfile, "w");

	$field_separator = ",";
	$line_terminator = "\n" ;

	if ($cdr_set_field_names == 1 ) {

		for ($i = 0 ; $i < count($export_csv)  ; $i++) {


			$line .= $export_csv[$i][key($export_csv[$i])];

			if  ($i < count($export_csv) - 1 )

			$line .= $field_separator ;

		}
		$line .=  $line_terminator ;

		fwrite($f , $line , strlen($line));

	}
	for ($j=0;count($result)>$j;$j++) {
		if ( function_exists("process_cdr_line_for_export") )
			process_cdr_line_for_export( $result[$j] );
		$line = "";

		for ($i = 0 ; $i < count($export_csv)  ; $i++) {


			$line .= $result[$j][key($export_csv[$i])];

			if  ($i < count($export_csv) - 1 )

			$line .= $field_separator ;

		}


		$line .=  $line_terminator ;

		fwrite($f , $line , strlen($line));
	}


	fclose($f);


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
