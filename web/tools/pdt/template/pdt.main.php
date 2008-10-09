<?php
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

?>

<form action="<?=$page_name?>?action=search" method="post">
<?php
$sql_search="";
$search_prefix=$_SESSION['pdt_search_prefix'];
if ($search_prefix!="") {
	$pos=strpos($search_prefix,"*");
	if ($pos===false) $sql_search.=" AND prefix='".$config->start_prefix.$search_prefix."'";
	else $sql_search.=" AND prefix like '".$config->start_prefix.str_replace("*","%",$search_prefix)."'";
}
$search_domain=$_SESSION['pdt_search_domain'];
if ($search_domain!="") {
	$pos=strpos($search_domain,"*");
	if ($pos===false) $sql_search.=" AND domain='".$search_domain."'";
	else $sql_search.=" AND domain like '".str_replace("*","%",$search_domain)."'";
}
if ($config->sdomain) {
	$search_sdomain=$_SESSION['pdt_search_sdomain'];
	if ($search_sdomain!="") $sql_search.=" AND sdomain='".$search_sdomain."'";
	db_connect();
	$result=mysql_query("SELECT * FROM ".$config->table_domains." WHERE 1 ORDER BY domain ASC") or die(mysql_error());
	$sdomain_input='<select name="search_sdomain" class="searchInput">';
	$sdomain_input.='<option value=""></option>';
	while($row=mysql_fetch_array($result))
	if ($row['domain']==$search_sdomain) $sdomain_input.='<option value="'.$row['domain'].'" selected>'.$row['domain'].'</option>';
	else $sdomain_input.='<option value="'.$row['domain'].'">'.$row['domain'].'</option>';
	$sdomain_input.='</select>';
	db_close();
}
?>
<table width="300" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="searchTitle">Search Prefix 2 Domain by</td>
 </tr>
 <tr>
  <td class="searchRecord">Prefix :</td>
  <td class="searchRecord" width="200"><?=$config->start_string.$config->start_prefix?><input type="text" name="search_prefix" value="<?=$search_prefix?>" maxlength="32" class="searchInput"></td>
 </tr>
<?php
if ($config->sdomain)
{
 ?>
 <tr>
  <td class="searchRecord">source Domain :</td>
  <td class="searchRecord" width="200"><?=$sdomain_input?></td>
 </tr>
 <?php
}
?>
 <tr>
  <td class="searchRecord">to Domain :</td>
  <td class="searchRecord" width="200"><input type="text" name="search_domain" value="<?=$search_domain?>" maxlength="255" class="searchInput"></td>
 </tr>
 <tr>
  <td colspan="2" class="searchRecord" align="center"><input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;<input type="submit" name="show_all" value="Show All" class="searchButton"></td>
 </tr>
 <tr>
  <td colspan="2" class="searchTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>

<form action="<?=$page_name?>?action=add" method="post">
 <?php if (!$_read_only) echo('<input type="submit" name="add_new" value="Add New" class="Button">') ?>
</form>

<table width="450" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td align="center" class="pdtTitle">Prefix</td>
<?php
if ($config->sdomain) echo('<td align="center" class="pdtTitle">source Domain</td>');
?>
  <td align="center" class="pdtTitle">to Domain</td>
  <td align="center" class="pdtTitle">Edit</td>
  <td align="center" class="pdtTitle">Delete</td>
 </tr>
<?php
db_connect();
if ($sql_search=="") $sql_command="SELECT * FROM ".$table." WHERE 1 ORDER BY prefix ASC";
else $sql_command="SELECT * FROM ".$table." WHERE 1 ".$sql_search." ORDER BY prefix ASC";
$result=mysql_query($sql_command) or die(mysql_error());
$data_no=mysql_num_rows($result);
if ($data_no==0) echo('<tr><td class="rowEven" colspan="5" align="center"><br>'.$no_result.'<br><br></td></tr>');
else
{
	$page=$_SESSION[$current_page];
	$page_no=ceil($data_no/10);
	if ($page>$page_no) {
		$page=$page_no;
		$_SESSION[$current_page]=$page;
	}
	$start_limit=($page-1)*10;
	$sql_command.=" LIMIT ".$start_limit.", 10";
	$result=mysql_query($sql_command) or die(mysql_error());
	$index_row=0;
	while($row=mysql_fetch_array($result))
	{
		$index_row++;
		if ($index_row%2==1) $row_style="rowOdd";
		else $row_style="rowEven";
		if ($config->sdomain) $edit_link='<a href="'.$page_name.'?action=edit&prefix='.$row['prefix'].'&sdomain='.$row['sdomain'].'"><img src="images/edit.gif" border="0"></a>';
		else $edit_link='<a href="'.$page_name.'?action=edit&prefix='.$row['prefix'].'"><img src="images/edit.gif" border="0"></a>';
		if ($config->sdomain) $delete_link='<a href="'.$page_name.'?action=delete&prefix='.$row['prefix'].'&sdomain='.$row['sdomain'].'" onclick="return confirmDelete(\''.$config->start_string.$row['prefix'].'@'.$row['sdomain'].'\')" ><img src="images/trash.gif" border="0"></a>';
		else $delete_link='<a href="'.$page_name.'?action=delete&prefix='.$row['prefix'].'" onclick="return confirmDelete(\''.$config->start_string.$row['prefix'].'\')" ><img src="images/trash.gif" border="0"></a>';
		if ($_read_only) $edit_link=$delete_link='<i>n/a</i>';
  ?>
  <tr>
   <td class="<?=$row_style?>"><?=$config->start_string?><?=$row['prefix']?></td>
<?php
if ($config->sdomain) echo('<td class="'.$row_style.'">'.$row['sdomain'].'</td>');
?>
   <td class="<?=$row_style?>"><?=$row['domain']?></td>
   <td class="<?=$row_style?>" align="center"><?=$edit_link?></td>
   <td class="<?=$row_style?>" align="center"><?=$delete_link?></td>
  </tr>
  <?php
	}
}
db_close();
?>
 <tr>
  <td colspan="5" class="pdtTitle">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
     <tr>
      <td align="left">
       &nbsp;Page:
       <?php
       for($i=1;$i<=$page_no;$i++)
       if ($i==$page) echo('<font class="pageActive">'.$i.'</font>&nbsp;');
       else echo('<a href="'.$page_name.'?page='.$i.'&'.$sess.'" class="pageList">'.$i.'</a>&nbsp;');
       ?>
      </td>
      <td align="right">Total Records: <?=$data_no?>&nbsp;</td>
     </tr>
    </table>
  </td>
 </tr>
</table>
<br>

<form action="<?=$page_name?>?action=add" method="post">
 <?php if (!$_read_only) echo('<input type="submit" name="add_new" value="Add New" class="Button">') ?>
</form>