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

  extract($_POST);
  $form_valid=true;
  if ($form_valid)
   if ($prefix=="") {
                     $form_valid=false;
                     $form_error="- invalid <b>Prefix</b> field -";
                    }
  if ($form_valid)
   if ($domain=="") {
                     $form_valid=false;
                     $form_error="- invalid <b>to Domain</b> field -";
                    }
  if ($form_valid)
   if (!is_numeric($prefix)) {
                              $form_valid=false;
                              $form_error="- <b>Prefix</b> field must be numeric -";
                             }
  if ($form_valid)
   if ($prefix<0) {
                   $form_valid=false;
                   $form_error="- <b>Prefix</b> field must be a positive number -";
                  }
  if ($form_valid) {
                    db_connect();
                    if ($config->sdomain) $sql="SELECT * FROM ".$table." WHERE prefix='".$config->start_prefix.$prefix."' and sdomain='".$sdomain."'";
                     else $sql="SELECT * FROM ".$table." WHERE prefix='".$config->start_prefix.$prefix."'";
                    $result=mysql_query($sql) or die(mysql_error());
                    $data_rows=mysql_num_rows($result);
                    $rows=mysql_fetch_array($result);
                    if (($config->sdomain) && ($data_rows>0) && (($rows['prefix']!=$old_prefix) || ($rows['sdomain']!=$old_sdomain))) $form_valid=false;
                    if ((!$config->sdomain) && ($data_rows>0) && ($rows['prefix']!=$old_prefix)) $form_valid=false;
                    if (!$form_valid) $form_error="- this is already a valid record -";
                    db_close();
                   }

?>
