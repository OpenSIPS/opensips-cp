<!--
 * $Id: menu.php 40 2009-04-13 14:59:22Z iulia_bublea $
 -->

<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td align="right" style="color:#0969b5">
        <b><?php print "System / Dynamic Routing / ".$_SESSION['permission']; ?></b>
    </td>
  </tr>
  <tr>
    <td align="center" valign="middle">
      <div class="menuItems">
        <?php
         $first_item = true; 
         while (list($key,$value) = each($config->menu_item))
		 if (!(($config->menu_item[$key]["0"]=="groups.php") && !($config->table_groups))) 
          {
           if (!$first_item) echo('&nbsp;&nbsp;|&nbsp;&nbsp;');
           if ($page_name!=$config->menu_item[$key]["0"]) echo('<a href="'.$config->menu_item[$key]["0"].'" class="menuItem">'.$config->menu_item[$key]["1"].'</a>');
            else echo('<a href="'.$config->menu_item[$key]["0"].'" class="menuItemSelect">'.$config->menu_item[$key]["1"].'</a>');
           $first_item = false;
          }
        ?>
      </div>
    </td> 
  </tr>
</table>
<hr width="100%" color="#000000">
<div align="right">
 <?php if ((!$_read_only) && ($page_name!="settings.php")) echo('<button type="button" class="formButton" onClick="window.open(\'apply_changes.php\',\'apply\',\'width=300,height=100\')">Apply Changes to Server</button><br>') ?>
</div>
<br>
