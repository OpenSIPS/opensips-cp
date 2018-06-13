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

function get_ip($string)
{
 $temp=explode(":",$string);
 $k=sizeof($temp);
 return($temp[$k-2].":".$temp[$k-1]);
}

?>
