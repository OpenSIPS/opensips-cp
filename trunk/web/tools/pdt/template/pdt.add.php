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

<?php
if ($config->sdomain) {
	db_connect();
	$result=mysql_query("SELECT * FROM ".$config->table_domains." WHERE 1 ORDER BY domain ASC") or die(mysql_error());
	$sdomain_input='<select name="sdomain" class="newPdt">';
	while($row=mysql_fetch_array($result))
	if ($row['domain']==$sdomain) $sdomain_input.='<option value="'.$row['domain'].'" selected>'.$row['domain'].'</option>';
	else $sdomain_input.='<option value="'.$row['domain'].'">'.$row['domain'].'</option>';
	$sdomain_input.='</select>';
	db_close();
}
?>
<form action="<?=$page_name?>?action=add_verify" method="post">
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="pdtTitle">Add new Prefix 2 Domain</td>
 </tr>
<?php
if (isset($form_error)) {
	echo(' <tr align="center">');
	echo('  <td class="rowOdd" colspan="2"><div class="formError">'.$form_error.'</div></td>');
	echo(' </tr>');
}
?>
 <tr>
  <td class="rowOdd"><b>Prefix:</b></td>
  <td class="rowOdd" width="250"><?=$config->start_string.$config->start_prefix?><input type="text" name="prefix" value="<?=$prefix?>" maxlength="30" class="newPdt"></td>
 </tr>
<?php
if ($config->sdomain)
{
 ?> 
 <tr>
  <td class="rowOdd"><b>source Domain:</b></td>
  <td class="rowOdd"><?=$sdomain_input?></td>
 </tr>
 <?php
}
?>
 <tr>
  <td class="rowOdd"><b>to Domain:</b></td>
  <td class="rowOdd"><input type="text" name="domain" value="<?=$domain?>" maxlength="255" class="newPdt"></td>
 </tr>
 <tr>
  <td class="rowOdd" colspan="2" align="center"><input type="submit" name="add" value="Add" class="Button"></td>
 </tr>
 <tr>
  <td class="pdtTitle" colspan="2"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<?=$back_link?>