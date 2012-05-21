<?php
/*
 * $Id$
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

include("db_connect.php");
  extract($_POST);
  $form_valid=true;
  if ($form_valid)
   if ($address=="") {
                      $form_valid=false;
                      $form_error="- invalid <b>Address</b> field -";
                     }
  if ($form_valid)
   if ($strip=="") {
                    $form_valid=false;
                    $form_error="- invalid <b>Strip</b> field -";
                   }
  if ($form_valid)
   if (!is_numeric($strip)) {
                             $form_valid=false;
                             $form_error="- <b>Strip</b> field must be numeric -";
                            }
  if ($form_valid)
   if ($strip<0) {
                  $form_valid=false;
                  $form_error="- <b>Strip</b> field must be a positive number -";
                 }
  if ($form_valid && $action!="modify") {
                    $sql="select * from ".$table." where address='".$address."' and type='".$type."' and strip='".$strip."' and pri_prefix='".$pri_prefix."'";
                    $result=$link->queryAll($sql);
		    if(PEAR::isError($result)) {
                    	die('Failed to issue query, error message : ' . $result->getMessage());
                    }
		    $data_rows=count($result); 	
                    if (($data_rows>0) && ($result[0]['gwid']!=$_GET['id']))
                    {
                     $form_valid=false;
                     $form_error="- this is already a valid gateway -";
                    }


                   }
	if ($form_valid && $action!="modify") {
		$sql="select count(*) from ".$table." where gwid = '".$gwid."'";
		$result=$link->queryOne($sql);
		if(PEAR::isError($result)) {
			die('Failed to issue query, error message : ' . $result->getMessage());
		}
		if ($result > 0) {
			$form_valid=false;
            $form_error="- GWID already exists -";
		}

	}

?>
