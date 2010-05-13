<?php
/*
 * $Id$
 * Copyright (C) 2008-2010 Voice Sistem SRL
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
   if ($gwlist=="") {
                     $form_valid=false;
                     $form_error="- invalid <b>Gateway List</b> field -";
                    }
  
  if ($form_valid) {
                    // make $gwlist
                    if (substr($gwlist,strlen($gwlist)-1,1)==";") $gwlist=substr($gwlist,0,strlen($gwlist)-1);
                    if (substr($gwlist,strlen($gwlist)-1,1)==",") $gwlist=substr($gwlist,0,strlen($gwlist)-1);
  }
                    $sql="select * from ".$table." where gwlist='".$gwlist."'";
		    $result = $link->queryAll($sql);
		    if(PEAR::isError($resultset)) {
                                die('Failed to issue query, error message : ' . $resultset->getMessage());
                    }	
                    $data_rows=count($result);
                    if (($data_rows>0) && ($result[0]['id']!=$_GET['id']))
                    {
                     $form_valid=false;
                     $form_error="- this is already a valid rule -";
                    }
                   

?>