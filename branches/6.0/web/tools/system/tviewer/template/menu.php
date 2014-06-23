<!--
 * $Id: menu.php,v 1.1.1.1 2006-08-31 13:35:13 bogdan Exp $
 -->

<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
    <td align="right" style="color:#0969b5">
        <b><?php print "System / Table Viewer / ".$_SESSION['permission'];?></b>
    </td>
</tr>

  <tr>
    <td align="center" valign="middle">
      <div class="menuItems">
        <?php
         $first_item = true;
         if (!isset($config->menu_item)) echo('<font class="menuItemSelect">&nbsp;</font>');
         else
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
<br>
