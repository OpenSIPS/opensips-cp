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
 
 require("../config/local.inc.php");
?>

<html>

<head>
 <title><?=$login_page_title?></title>
 <link href='style.css' type='text/css' rel='StyleSheet'>
</head>

<body>
<center>
<img src="images/logo.jpg"><br>
<form action="login.php" method="post">
<br><br>
<table cellspacing="0" cellpadding="0" border="0" bgcolor="white">
  <tr>
   <td width="8"><img height="25" alt="" src="images/boxtopleft.gif" width="8" border="0"></td>
   <td background="images/boxtop.gif" align="center" class="loginTitle"><?=$login_title?></td>
   <td width="8"><img height="25" alt="" src="images/boxtopright.gif" width="8" border="0"></td>
  </tr>
  <tr>
   <td background="images/boxleft.jpg">&nbsp;</td>
   <td>
    <img height="20" alt="" src="images/spacer.gif" width="5" border="0"><br>
    <table cellspacing="0" cellpadding="1" width="350" border="0">
     <tbody>
      <tr>
       <td colspan="3" align="center"><img src="images/login_header.gif" width="75" height="26"><br><img height="10" alt="" src="images/spacer.gif" width="5" border="0"><br></td>
      </tr>
      <tr>
       <td colspan="3"><hr></td>
      </tr>
      <tr>
       <td width="40" align="right" valign="middle"><img height="10" src="images/arrow.gif" width="5" border="0">&nbsp;</td>
       <td width="90" align="right"><b class="loginLabel"><?php echo($login_user) ?>&nbsp;</b></td>
       <td><input type="text" name="name" class="loginInput" autofocus></td>
      </tr>
      <tr>
       <td width="40" align="right" valign="middle"><img height="10" src="images/arrow.gif" width="5" border="0">&nbsp;</td>
       <td width="90" align="right"><b class="loginLabel"><?php echo($login_pass) ?>&nbsp;</b></td>
       <td><input type="password" name="password" class="loginInput" autocomplete="off"></td>
      </tr>
      <tr>
       <td colspan="3"><hr></td>
      </tr>
      <tr>
       <td colspan="3"><br><input type="image" src="images/go.gif" align="right" alt="Login" name="acces" value="x"></td>
      </tr>
     </tbody>
    </table>
   </td>
   <td background="images/boxright.jpg">&nbsp;</td>
  </tr>
  <tr valign="top" align="left">
   <td><img height="8" alt="" src="images/boxbottomleft.gif" width="8" border="0"></td>
   <td width="143" background="images/boxbottom.gif"><img height="8" alt="" src="images/boxbottom.gif" width="9" border="0"></td>
   <td><img height="8" alt="" src="images/boxbottomright.gif" width="8" border="0"></td>
  </tr>
</table>
</form>
<?php
 if (isset($_GET['err'])) {
 if ($_GET['err']==1) echo('<div class="loginError">'.$login_err.'</div>');
 if ($_GET['err']==2) echo('<div class="loginError">'.$session_err.'</div>');
 if ($_GET['err']==3) echo('<div class="loginError">Account Blocked</div>');
 }
?>
</center>
</body>

</html>
