<?php
/*
* $Id: alias_management.php 210 2010-03-08 18:09:33Z bogdan_iancu $
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

require("template/header.php");
require("lib/".$page_id.".main.js");
require("../../../../config/globals.php");
require("../../../../config/tools/users/acl_management/local.inc.php");
include("lib/db_connect.php");
$table=$config->table_acls;

$current_page="current_page_acl_management";
if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";


if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;
#################
# start add new #
#################

if ($action=="add")
{
        extract($_POST);
        if(!$_SESSION['read_only'])
        {
                require("template/".$page_id.".add.php");
                require("template/footer.php");
                exit();
        }else {
                $errors= "User with Read-Only Rights";
        }

}

#################
# end add new   #
#################

####################
# the form comes already verified here  #
####################
if ($action=="add_verified")
{
        if(!$_SESSION['read_only']){
				
                $acl_username = $_POST['username'];
                $acl_domain = $_POST['domain'];
                $acl_grp = $_POST['acl_grp'];

                
                                $sql = 'INSERT INTO '.$table.'
                                (username, domain, grp, last_modified) VALUES
                                ("'.$acl_username.'","'.$acl_domain.'","'. $acl_grp.'","NOW()")';
                                $resultset = $link->prepare($sql);
                                $resultset->execute();
                                $resultset->free();
                                $info="The new record was added";
                        $link->disconnect();
                  print "New ACL added!";
		}
        
              
       
				

				
        else
			print "User with Read-Only Rights";
}


##################
# end add verify #
##################


#################
# start edit    #
#################
if ($action=="edit")
{

        if(!$_SESSION['read_only']){

                extract($_POST);

                require("template/".$page_id.".edit.php");
                require("template/footer.php");
                exit();
        }else{
                $errors= "User with Read-Only Rights";
        }
}
#############
# end edit      #
#############


#################
# start modify  #
#################
if ($action=="modify")
{

        $info="";
        $errors="";

        if(!$_SESSION['read_only']){

                $id = $_GET['id'];
                $acl_username=$_POST['username'];
                $acl_domain=$_POST['domain'];
                $acl_grp = $_POST['acl_grp'];

                if ($acl_username=="" || $acl_domain=="" || $acl_grp==""){
                        $errors = "Invalid data, the entry was not modified in the database";
                }
				
				$sql_command = "select * from subscriber where username = '".$acl_username."'";
				$resultset = $link->queryAll($sql_command);
				if(PEAR::isError($resultset)) {
				    die('Failed to issue query, error message : ' . $resultset->getMessage());
				}

				if (count($resultset)<1) {
					$errors="This user does not exist !!!";
				}
                if ($errors=="") {
	                $sql = "SELECT * FROM ".$table." WHERE username='" .$acl_username. "' AND domain='".$acl_domain. "' AND grp='".$acl_grp."' AND id!=".$id;
                        $resultset = $link->queryAll($sql);
               	        if(PEAR::isError($resultset)) {
                       	        die('Failed to issue query, error message : ' . $resultset->getMessage());
				}

                        $sql = "UPDATE ".$table." SET username='".$acl_username."', domain = '".$acl_domain.
                        "', grp='".$acl_grp."' WHERE id=".$id;
                        $resultset = $link->prepare($sql);
                        $resultset->execute();
                        $resultset->free();
                        $info="The ACL was modified";
           
                        $link->disconnect();
                }
        }else{

                $errors= "User with Read-Only Rights";
        }

}
#################
# end modify    #
#################



################
# start search #
################
if ($action=="dp_act")
{
		if (isset($_GET['fromusrmgmt'])) {
		
			$fromusrmgmt=$_GET['fromusrmgmt'];
			
			$_SESSION['fromusrmgmt']=1;
			$_SESSION['acl_username']=$_GET['username'];
	        $_SESSION['acl_domain']=$_GET['domain'];
		}

        $_SESSION['acl_id']=$_POST['acl_id'];

        $_SESSION[$current_page]=1;
        extract($_POST);
        if ($show_all=="Show All") {
                $_SESSION['acl_username']="";
                $_SESSION['acl_domain']="";
                $_SESSION['acl_grp']="";
        } else if($search=="Search"){
                $_SESSION['acl_username']=$_POST['acl_username'];
                $_SESSION['acl_domain']=$_POST['acl_domain'];
                $_SESSION['acl_grp']=$_POST['acl_grp'];
        } else if($_SESSION['read_only']){

                $errors= "User with Read-Only Rights";

        }else if($delete=="Delete ACL"){
                $sql_query = "";
                if( $_POST['acl_username'] != "" ) {
                        $acl_username = $_POST['acl_username'];
                        $sql_query .= " AND username like '%".$acl_username."%'";
                }
                if( ($_POST['acl_domain'] == "ANY") ||($_POST['acl_domain'] == "") ) {
			$sql_query .= " AND acl_domain like '%'";
	        } else {
			$acl_domain = $_POST['acl_domain'];
                        $sql_query .= " AND domain like '%".$acl_domain . "%'";
		}

				if ($_POST['acl_grp']=="ANY"){
					$sql_query .= " AND grp like '%'";
				}
				else{
					$acl_grp = $_POST['acl_grp'];
					$sql_query .= "AND grp ='".$acl_grp."'";
				}
                if($_POST['acl_grp'] != "ANY" ) {

	                $sql = "SELECT * FROM ".$table.
        	        " WHERE (1=1) " . $sql_query;
                	$resultset = $link->queryAll($sql);
	                if(PEAR::isError($resultset)) {
        	                die('Failed to issue query, error message : ' . $resultset->getMessage());
                	}
	                if (count($resultset)==0) {
        	                $errors="No such ACL";
                	        $_SESSION['acl_username']="";
                        	$_SESSION['acl_domain']="";
							$_SESSION['acl_grp']="";

	                }else{

        	                $sql = "DELETE FROM ".$table." WHERE (1=1) " . $sql_query;
                	        $link->exec($sql);
                	}		
		} else {
//			for($i=0;$i<count($options);$i++){
	                        $sql = "SELECT * FROM ".$table." WHERE (1=1) ".$sql_query;
                	        $resultset = $link->queryAll($sql);
                        	if(PEAR::isError($resultset)) {
                                	die('Failed to issue query, error message : '.$resultset->getMessage());
                        	}
	                        if (count($resultset)==0) {
        	                        $errors="No such ACL";
                	                $_SESSION['acl_username']="";
                        	        $_SESSION['acl_domain']="";
        	                }else{
	
                	                $sql = "DELETE FROM ".$table." WHERE (1=1) " . $sql_query;
                        	        $link->exec($sql);

				}
		//}
                $link->disconnect();
        }
	}
}
##############
# end search #
##############

################
# start delete #
################
if ($action=="delete")
{
        if(!$_SESSION['read_only']){

                $id=$_GET['id'];
		$table=$_GET['table'];

                $sql = "DELETE FROM ".$table." WHERE id=".$id;
                $link->exec($sql);
                $link->disconnect();
        }else{

                $errors= "User with Read-Only Rights";
        }
}
##############
# end delete #
##############


##############
# start main #
##############

require("template/".$page_id.".main.php");
if($errors)
echo('!!! ');echo($errors);
require("template/footer.php");
exit();

##############
# end main   #
##############
?>
