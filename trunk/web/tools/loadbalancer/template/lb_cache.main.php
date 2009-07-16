<!--
 /*
 * $Id: lb_cache.main.php 28 2009-04-01 15:27:03Z iulia_bublea $
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
                mi_command('lb_list',$errors,$status);
                print_r($errors);
                $status = trim($status);
		preg_match_all('/Destination\:\:\s+sip\:[a-zA-Z0-9.]+\s+id=\d+\s+group=\d+\s+enabled=(yes|no)\s+auto-re=(on|off)(\s+Resource\:\:\s+[a-zA-Z0-9]+\s+max=\d+\s+load=\d+)*/',$status,$matches);
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

  <td class="loadbalancerTitle">ID</td>
  <td class="loadbalancerTitle">Group ID</td>
  <td class="loadbalancerTitle">Destination URI</td>
  <td class="loadbalancerTitle">Status</td>
  <td class="loadbalancerTitle">Auto</td>
  <td class="loadbalancerTitle">Resources</td>

<?
	for ($i=0; $i<count($matches[0]);$i++) {
                if ($i%2==1) $row_style="rowOdd";
                else $row_style="rowEven";
		$tmp = explode("::",$matches[0][$i]); 
		$temp=explode(" ",$tmp[1]);
		$dst_uri = $temp[1];
		$id = explode("=",$temp[2]);
		$group = explode("=",$temp[3]);
		$status = explode("=",$temp[4]);
		$auto = explode(" ",$temp[5]);
		$auto_re = explode("=",$auto[0]);
		$auto_re[1] = substr($auto_re[1],0,-8);

		if ( $status[1]=="yes" ) {
		        $toggle_button = "enabled";

		} else if ( $status[1] == "no" ){
		        $toggle_button = "disabled";
		}

$resource="";
for ($j=2;count($tmp)>$j;$j++) {
	if (preg_match('/Resource$/',$tmp[$j],$match)!=0 ) $tmp[$j]=substr($tmp[$j],0,-8);
	$resource .= $tmp[$j]."\n";

}
?>
 <tr align=center>
  <td class="<?=$row_style?>">&nbsp;<?=$id[1]?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$group[1]?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$dst_uri?></td>
  <td class="<?=$row_style?>">&nbsp;<div align="center">
	<form action="<?=$page_name?>?action=toggle&toggle_button=<?=$toggle_button?>&id=<?=$id[1]?>" method="post">
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
  <td class="<?=$row_style?>">&nbsp;<?=$auto_re[1]?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$resource?></td>
 </tr>
<?
 }
?>

</table>
