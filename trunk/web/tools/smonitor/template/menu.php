<!--
 * $Id: menu.php,v 1.1.1.1 2006-08-30 10:43:17 bogdan Exp $
 -->

<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td align="center" valign="middle">
      <div class="menuItems">
        <?php
         $first_item = true; 
         while (list($key,$value) = each($config->menu_item)) 
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
<?php
 if ($page_id=="rt_stats") echo('<button type="button" class="formButton" onClick="window.location.href=\'rt_stats.php\'">Refresh Statistics Values</button><br>');
 if ($page_id=="charts") echo('<button type="button" class="formButton" onClick="window.location.href=\'charts.php\'">Refresh Statistics Charts</button><br>');
?>
</div>
<br>