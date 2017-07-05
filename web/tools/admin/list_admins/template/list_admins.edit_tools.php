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

require_once("../../../common/cfg_comm.php");

 if (isset($form_error)) {
                          echo(' <tr align="center">');
                          echo('  <td colspan="2" class="dataRecord"><div class="formError">'.$form_error.'</div></td>');
                          echo(' </tr>');
                         }
	$id=$_GET['id'];
	
	$sql = "select * from ".$table." where id='".$id."'";
	$resultset = $link->queryAll($sql);
        $index_row=0;
	$link->disconnect();
$permissions=array();
?>
<form action="<?=$page_name?>?action=modify_tools&id=<?=$_GET['id']?>&uname=<?=$_GET['uname']?>" method="post">
<table width="400" cellspacing="2" cellpadding="2" border="0">

  <?php
	$sql = 'select available_tools,permissions from '. $table .' where username="'.$_GET['uname'].'" limit 1';
	$resultset = $link->queryAll($sql);
	if(PEAR::isError($resultset)) {
        	die('Failed to issue query, error message : ' . $resultset->getMessage());
	}
        $modules=get_modules();
	
	foreach($modules['Admin'] as $key=>$value) {
		$all_tools[$key] = $key; 
	}
	foreach($modules['Users'] as $key=>$value) {
		$all_tools[$key] = $key; 
	}
	foreach($modules['System'] as $key=>$value) {
		$all_tools[$key] = $key; 
	}
	if($resultset[0]['available_tools']!="all") {
		$available_tabs=explode(",",$resultset[0]['available_tools']);
		
	} else {
		$available_tabs=$all_tools;
	}
	
	if ($resultset[0]['permissions']!="all") {
		$perms=explode(",",$resultset[0]['permissions']);
		$i=0;
		foreach($available_tabs as $key=>$value) {
			$avail_tabs_perms[$key]=$perms[$i];
			$i++;
		}
		
				
	} else {
		foreach($available_tabs as $key=>$value) {
			$avail_tabs_perms[$key]='read-write';
		}
	}
	$i=0;
	
        foreach ($modules as $key => $value) {
  ?>
	   <tr>
	     <td colspan="3" class="listadminsTitle" align="center">Tools and Permissions for <?php print $key?> Section: </td>
	   </tr>
	
	<?php
	
	foreach ($value as $k=>$v) {
	$i++;
	?>
        <tr>
                <td class="dataRecord" width="25"><b><?php print $v;?></b></td>
			<?php 
if ($_SESSION['read_only']) {
	$disabled="disabled";
} else {
	$disabled='';
}
			if(($resultset[0]['available_tools']=="all") || (in_array($k,$available_tabs))) { 
			
			?>
                		<td class="dataRecord" width="15"><input type="checkbox" name="state[<?php print $k;?>]" onClick="toggle(this,'<?='foo'.$i;?>');" checked class="dataInput" id="<?=$k?>" <?php print $disabled; ?>> </td>
				<td class="dataRecord" width="15" >
				
			<?php
				foreach($available_tabs as $keys=>$values) {
			?>
			<?php
					if ($k==$values) {
					
					?>
						<span id="<?='foo'.$i;?>" style="visibility:visible">
						<?php permission($avail_tabs_perms[$keys],$k,$disabled);?>
						</span>
					<?php
					}
			}
			?>
				</td>
			<?php
			} else {
			?>
	                	<td class="dataRecord" width="15"><input type="checkbox" name="state[<?php print $k; ?>]" onClick="toggle(this,'<?='foo'.$i;?>');" class="dataInput" id="<?=$k?>" <?php print $disabled; ?> ></td>
				<td class="dataRecord" width="15">
					<span id="<?='foo'.$i;?>" style="visibility:hidden">
						<?php permission('',$k,$disabled);?>
					</span>
				</td>
			<?php } ?>
         </tr>
  <?php
     }
  }

if (!$_SESSION['read_only']) {
?>
  <tr>
   <td colspan="3" class="dataRecord" align="center"><input type="submit" name="save" value="Save" class="formButton"></td>
  </tr>
<?php
 }
?>
  <tr height="10">
   <td colspan="3" class="dataTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></td>
  </tr>
  </table>

</form>

<br>
<?=$back_link?>

