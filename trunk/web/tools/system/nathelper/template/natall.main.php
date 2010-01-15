<!--
 /*
 * $Id: natall.main.php 28 2009-04-01 15:27:03Z iulia_bublea $
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

<form action="<?=$page_name?>?action=refresh" method="post">
<?
	$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
	for ($i=0;$i<count($mi_connectors);$i++){

                $comm_type=params($mi_connectors[$i]);
                mi_command('nh_show_rtpp',$errors,$status);
                print_r($errors);
                $status = trim($status);
                //preg_match_all('/[0-9a-z\:\_a-z0-9\:0-9]+[\:][\:]\s+[a-z\=0-9]+/',$status,$matches);
                preg_match_all('/.+[\:][\:]\s+[a-z\=0-9]+/',$status,$matches);
}
?>
</form>
<form action="<?=$page_name?>?action=refresh" method="post">
<table width="85%" cellspacing="2" cellpadding="2" border="0">
 
 <tr height="10">
  <td colspan="3" class="searchRecord" align="right"><input type="submit" name="refresh" value="Refresh RTP Proxy" class="searchButton">&nbsp;&nbsp;&nbsp;</td>
 </tr>
 <tr height="10">
  <td colspan="2" class="searchTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<br>

<table width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">

  <td class="nathelperTitle">RTPproxy Sock</td>
  <td class="nathelperTitle">Setid</td>
  <td class="nathelperTitle">Index</td>
  <td class="nathelperTitle">Status</td>
  <td class="nathelperTitle">Weight</td>
  <td class="nathelperTitle">Recheck Ticks</td>

<?
$data_no=count($matches[0]);
if ($data_no==0) echo('<tr><td colspan="6" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
else {

	for ($i=0; $i<count($matches[0]);$i=$i+5) {
                if ($i%2==1) $row_style="rowOdd";
                else $row_style="rowEven";
		$sock=explode("::",$matches[0][$i]);
		$setid=explode("=",$sock[1]);
		$index=explode("::",$matches[0][$i+1]);
		$status=explode("::",$matches[0][$i+2]);
		if ( $status[1]==0 ) {
		        $toggle_button = "enabled";

		} else if ( $status[1] > 0 ){
		        $toggle_button = "disabled";
		}

		$weight=explode("::",$matches[0][$i+3]);
		$ticks=explode("::",$matches[0][$i+4]);
?>
 	
 <tr align=center>
  <td class="<?=$row_style?>">&nbsp;<?=$sock[0]?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$setid[1]?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$index[1]?></td>
  <td class="<?=$row_style?>">&nbsp;<div align="center">
	<form action="<?=$page_name?>?action=toggle&toggle_button=<?=$toggle_button?>&sock=<?=$sock[0]?>" method="post">
	<? if ( $toggle_button == "enabled" ) {

        	echo '<input type="submit" name="toggle" value="'.$toggle_button.'" class="formButton" style="background-color: #00ff00; ">';
		
	} else
	if  ( $toggle_button == "disabled" )
	{

        	echo '<input type="submit" name="toggle" value="'.$toggle_button.'" class="formButton" style="background-color: #ff0000; ">';
	}
	?>
        </form>
	</div>
  </td>
  <td class="<?=$row_style?>">&nbsp;<?=$weight[1]?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$ticks[1]?></td>
 </tr>
<?
  } 	
 }
?>

 <tr>
 <td colspan="6" class="nathelperTitle" align="right">Total Records: <?php print count($matches[0])?>&nbsp;</td>
 <tr>
</table>
