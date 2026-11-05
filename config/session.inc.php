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

 if (session_status() == PHP_SESSION_NONE)
     session_start();

 if (!isset($_SESSION['user_login'])) {
     echo('<script language="JavaScript">window.open("/cp/index.php?err=2","_parent")</script>');
     exit();
 }

 /*
  * Per-tool authorization gate.
  *
  * Tool privileges used to be enforced only in each tool's index.php, while the
  * state-changing handlers (user_management.php, list_admins.php, ...) checked
  * only that a session was logged in. A logged-in user could therefore post
  * straight to a handler for a tool they were never granted and it would run.
  *
  * Every tool page reaches session.inc.php through template/header.php, so the
  * privilege is enforced here centrally: the tool is resolved from the request
  * path and checked against the user's granted tools on every request, whatever
  * the entry point (handler, nested page or AJAX endpoint).
  */
 (function () {
     $script = $_SERVER['SCRIPT_FILENAME'] ?? '';
     if ($script === '' || ($script = realpath($script)) === false)
         return;
     $tools_root = realpath(__DIR__ . '/../web/tools');
     if ($tools_root === false ||
         strpos($script, $tools_root . DIRECTORY_SEPARATOR) !== 0)
         return; // not a request to a tool page - nothing to gate

     $rel = str_replace(DIRECTORY_SEPARATOR, '/',
         substr($script, strlen($tools_root) + 1));

     // Map each module's filesystem path to its tool key, so a request to any
     // script inside a tool directory (nested or AJAX scripts included) is
     // attributed to the owning tool.
     require(__DIR__ . '/modules.inc.php');
     $paths = array();
     if (isset($config_admin_modules))
         foreach ($config_admin_modules as $key => $attr)
             $paths['admin/' . $key] = $key;
     if (isset($config_modules))
         foreach ($config_modules as $group => $gattr) {
             if (!isset($gattr['modules']))
                 continue;
             foreach ($gattr['modules'] as $key => $mattr)
                 $paths[isset($mattr['path']) ? $mattr['path']
                                              : $group . '/' . $key] = $key;
         }

     $tool = null;
     $best = -1;
     foreach ($paths as $path => $key) {
         if (($rel === $path || strpos($rel, $path . '/') === 0) &&
             strlen($path) > $best) {
             $tool = $key;
             $best = strlen($path);
         }
     }

     $is_super = (($_SESSION['user_tabs'] ?? '') === '*' ||
                  ($_SESSION['user_priv'] ?? '') === '*');

     if ($tool === null) {
         // Scripts under web/tools/admin/ that no listed module claims (e.g.
         // tools_config) are administrative: only superadmins may reach them.
         if (strpos($rel, 'admin/') === 0 && !$is_super) {
             $_SESSION['permission'] = 'No permissions';
             echo('<b>You do not have permissions to access this tool</b>');
             exit();
         }
         return;
     }

     // Superadmins (available_tools/permissions stored as "*") may use any tool.
     if ($is_super) {
         $_SESSION['read_only'] = false;
         $_SESSION['permission'] = 'Admin';
         return;
     }

     $tabs  = explode(',', $_SESSION['user_tabs'] ?? '');
     $privs = explode(',', $_SESSION['user_priv'] ?? '');
     $idx = array_search($tool, $tabs, true);
     if ($idx === false) {
         $_SESSION['permission'] = 'No permissions';
         echo('<b>You do not have permissions to access this tool</b>');
         exit();
     }

     // Keep read_only scoped to the tool actually being used, instead of letting
     // the last tool visited decide it for the whole session.
     $priv = $privs[$idx] ?? 'read-only';
     $_SESSION['read_only'] = ($priv === 'read-only');
     $_SESSION['permission'] = $priv === 'read-only' ? 'Read-Only'
         : ($priv === 'read-write' ? 'Read-Write' : 'Admin');
 })();

 $_read_only = $_SESSION['read_only'] ?? true;
?>
