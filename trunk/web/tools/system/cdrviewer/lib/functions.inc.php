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


########## end mi
function get_priv() {

        $modules = get_modules();

        foreach($modules['Admin'] as $key=>$value) {
                $all_tools[$key] = $value;
        }
        foreach($modules['Users'] as $key=>$value) {
                $all_tools[$key] = $value;
        }
        foreach($modules['System'] as $key=>$value) {
                $all_tools[$key] = $value;
        }

        if($_SESSION['user_tabs']=="*") {
                foreach ($all_tools as $lable=>$val) {
                        $available_tabs[]=$lable;
                }
        } else {
                $available_tabs=explode(",",$_SESSION['user_tabs']);
        }

        if ($_SESSION['user_priv']=="*") {
                $_SESSION['read_only'] = false;
		$_SESSION['permission'] = "Read-Write";
        } else {
                $available_privs=explode(",",$_SESSION['user_priv']);
                if( ($key = array_search("cdrviewer", $available_tabs))!==false) {
                        if ($available_privs[$key]=="read-only"){
                                $_SESSION['read_only'] = true;
				$_SESSION['permission'] = "Read-Only";
                        }
                        if ($available_privs[$key]=="read-write"){
                                $_SESSION['read_only'] = false;
				$_SESSION['permission'] = "Read-Write";
                        }

                }
        }

        return;

}


function print_object($obj_name, $start_value, $end_value, $select_value, $disabled)
{
?>
 <select name="<?=$obj_name?>" id="<?=$obj_name?>" size="1" class="dataSelect" <?=$disabled?>>
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

	$sql.=" unix_timestamp('".$start_time ."') - ".$delay."  <= unix_timestamp(call_start_time)  and  ";

	$sql.="unix_timestamp(call_start_time) <= unix_timestamp('" . $end_time  ."') - ".$delay   ;

	$sql .= " order by call_start_time desc " ;

	$result =$link->queryAll($sql);
	if(PEAR::isError($result))
        	die('Failed to issue query, error message : ' . $result->getMessage());
	


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

	$sql.=" from ".$cdr_table . " where (1=1) ";


	if (($start_time !="")) {

		$sql.=" and unix_timestamp('".$start_time ."')  <= unix_timestamp(call_start_time)";

	}


	if (($end_time !="")){

		$sql.=" and unix_timestamp(call_start_time) <= unix_timestamp('" . $end_time  ."')"   ;

	}


	if ($sql_search!="") $sql.=  $sql_search  ;

	$sql .= " order by call_start_time desc " ;
	
	$result = $link->queryAll($sql);
	if(PEAR::isError($result)) {
        	die('Failed to issue query, error message : ' . $result->getMessage());
	}


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

function get_modules() {
         $modules=array();
         $mod = array();
         if ($handle=opendir('../../../tools/admin/'))
         {
          while (false!==($file=readdir($handle)))
           if (($file!=".") && ($file!="..") && ($file!="CVS")  && ($file!=".svn"))
           {
            $modules[$file]=trim(file_get_contents("../../../tools/admin/".$file."/tool.name"));
           }
         closedir($handle);
         $mod['Admin'] = $modules;
        }

         $modules=array();
         if ($handle=opendir('../../../tools/users/'))
         {
          while (false!==($file=readdir($handle)))
           if (($file!=".") && ($file!="..") && ($file!="CVS")  && ($file!=".svn"))
           {
            $modules[$file]=trim(file_get_contents("../../../tools/users/".$file."/tool.name"));
           }
          closedir($handle);
          $mod['Users'] = $modules;
         }

         $modules=array();
         if ($handle=opendir('../../../tools/system/'))
         {
          while (false!==($file=readdir($handle)))
           if (($file!=".") && ($file!="..") && ($file!="CVS")  && ($file!=".svn"))
           {
            $modules[$file]=trim(file_get_contents("../../../tools/system/".$file."/tool.name"));
           }
          closedir($handle);
          $mod['System'] = $modules;
          }
     return $mod;
}

?>
