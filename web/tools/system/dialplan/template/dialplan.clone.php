<?
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

$dpid=$_POST['dialplan_id'];
?>

<form action="<?=$page_name?>?action=dp_act=<?php echo $_GET['dest_dpid']?>" method="post">
<table width="50%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" height="10" class="dialplanTitle"></td>
 </tr>
 <tr>
  <td class="searchRecord">Dialplan Source ID :</td>
  <td class="searchRecord" width="200"><input type="text" name="src_dpid" value="<?=$dpid?>" maxlength="16" class="searchInput"></td>
 </tr>
 <tr height="10">
  <td class="searchRecord">Dialplan Dest ID :</td>
  <td class="searchRecord" width="200"><input type="text" name="dest_dpid" maxlength="16" class="searchInput"></td>
 </tr>
 <tr height="10">
  <td colspan=2 align="center"><input type="submit" name="clone" value="Clone Dialplan" class="formButton"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="dialplanTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<?=$back_link?>

<?php/*
 $sql = "SELECT * FROM ".$table.
	" WHERE dpid=" .$dpid;
        $resultset = $link->queryAll($sql);
print ($resultset);
        if(PEAR::isError($resulset)) {
        	die('Failed to issue query, error message : ' . $resultset->getMessage());
        }
print $sql;
 if (count($resultset)==0) {
 	$errors="No rules to duplicate";
 } 
	$sql = "INSERT INTO ".$table.
               "(dpid, pr, match_op, match_exp, match_flags, subst_exp,
               repl_exp, attrs) VALUES (".$dest_dpid.", ".
               $resultset[0]['pr'].", ".$resultset[0]['match_op'].
               ", '".$resultset[0]['match_exp']."', ".$resultset[0]['match_flags'].
               ", '" .$resultset[0]['subst_exp']."', '".$resultset[0]['repl_exp'].
               "', '".$resultset[0]['attrs']."')";
print $sql;
               $result = $link->prepare($sql);
               if(PEAR::isError($result)) {
          	     die('Failed to issue query, error message: ' . $result->getMessage());
               }
               $result->execute();
               $result->free();

                                $info="The dialplan was cloned";
*/?>
