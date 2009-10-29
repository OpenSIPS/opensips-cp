<?php
/*
 * $Id$
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

 require("../config/access.inc.php");
 session_start();
 
 if (isset($_POST['username'])) $username = $_POST['username'];
  else $username = "";
 if (isset($_POST['password'])) $password = $_POST['password'];
  else $password = "";
 if ($username=="" || $password=="") {
                                      header("Location:index.php?err=1");
                                      exit();
                                     }
 
 $login_ok = false;
 while (list($key,$value) = each($admin))
 {
  if ($admin[$key]['0'] == $username && $admin[$key]['1'] == $password) {
                                                                         $_SESSION['user_login'] = $username;
                                                                         $_SESSION['user_tabs'] = $admin[$key]['2'];
                                                                         if ($admin[$key]['2']=="*") $_SESSION['user_priv'] = "*";
                                                                          else $_SESSION['user_priv'] = $admin[$key]['3'];
                                                                         $login_ok = true;
                                                                        }
  else $login_ok = false;
  if ($login_ok == true) {
                          $handle = fopen($config->accesss_log_file, "a");
                          $log = "[OK]  [".date("d-m-Y")." ".date("H:i:s")."] '$username' from '".$_SERVER['REMOTE_ADDR']."'\n";
                          fwrite($handle, $log);
                          fclose($handle);
                          header("Location:main.php");
                          exit();
                         }
 }
 
 $handle = fopen($config->accesss_log_file, "a");
 $log = "[NOK] [".date("d-m-Y")." ".date("H:i:s")."] '$username' / '$password' from '".$_SERVER['REMOTE_ADDR']."'\n";
 fwrite($handle, $log);
 fclose($handle);
 header("Location:index.php?err=1");
 exit();
?>