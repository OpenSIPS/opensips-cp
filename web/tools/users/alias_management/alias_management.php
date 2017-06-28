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

require("template/header.php");
require("lib/".$page_id.".main.js");
require("../../../../config/globals.php");
require("../../../../config/tools/users/alias_management/local.inc.php");
include("lib/db_connect.php");
foreach ($config->table_aliases as $key=>$value) {
	$options[]=array("label"=>$key,"value"=>$value);
}

$current_page="current_page_alias_management";
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
				
                $alias_username = $_POST['alias_username'];
                $alias_domain = $_POST['alias_domain'];
                $alias_type = $_POST['alias_type'];
                $username = $_POST['username'];
                $domain = $_POST['domain'];

                
				for($i=0; $i<count($options);$i++){
					if ($alias_type == $options[$i]['label']) 
						$table = $options[$i]['value']; 
				}						
                                $sql = "INSERT INTO ".$table."
                                (alias_username, alias_domain, username, domain) VALUES
                                ('".$alias_username."','".$alias_domain."','". $username."','".$domain."')";
                                $resultset = $link->prepare($sql);
                                $resultset->execute();
                                $resultset->free();
                                $info="The new record was added";
                        //}
                        $link->disconnect();
                  print "New Alias added!";
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
                $user_table = $_GET['table'];
                $alias_username=$_POST['alias_username'];
                $alias_domain=$_POST['alias_domain'];
                $username = $_POST['username'];
                $domain= $_POST['domain'];

                if ($alias_username=="" || $alias_domain=="" || $username=="" || $domain==""){
                        $errors = "Invalid data, the entry was not modified in the database";
                }
                if ($errors=="") {
	                $sql = "SELECT * FROM ".$user_table." WHERE alias_username='" .$alias_username. "' AND alias_domain='".$alias_domain. "' AND id!=".$id;
                        $resultset = $link->queryAll($sql);
               	        if(PEAR::isError($resultset)) {
                       	        die('Failed to issue query, error message : ' . $resultset->getMessage());
			}

                        $sql = "UPDATE ".$user_table." SET alias_username='".$alias_username."', alias_domain = '".$alias_domain.
                        "', username='".$username."', domain ='".$domain."' WHERE id=".$id;
                        $resultset = $link->prepare($sql);
                        $resultset->execute();
                        $resultset->free();
                        $info="The alias was modified";
           
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
			$_SESSION['username']=$_GET['username'];
			$_SESSION['alias_domain']=$_GET['domain'];
		}

        $_SESSION['alias_id']=$_POST['alias_id'];

        $_SESSION[$current_page]=1;
        extract($_POST);
        if ($show_all=="Show All") {
			if (isset($_SESSION['fromusrmgmt']))
				$_SESSION['fromusrmgmt']=0;
				$_SESSION['username']="";
                $_SESSION['alias_username']="";
                $_SESSION['alias_domain']="";
                $_SESSION['alias_type']="";
        } else if($search=="Search"){
				$_SESSION['username']=$_POST['username'];
                $_SESSION['alias_username']=$_POST['alias_username'];
                $_SESSION['alias_domain']=$_POST['alias_domain'];
                $_SESSION['alias_type']=$_POST['alias_type'];
        } else if($_SESSION['read_only']){

                $errors= "User with Read-Only Rights";

        }else if($delete=="Delete Alias"){
                $sql_query = "";
                if( $_POST['alias_username'] != " " ) {
                        $alias_username = $_POST['alias_username'];
                        $sql_query .= " AND alias_username like '%".$alias_username."%'";
                }
                if( ($_POST['alias_domain'] == "ANY") ||($_POST['alias_domain'] == " ") ) {
			$sql_query .= " AND alias_domain like '%'";
	        } else {
			$alias_domain = $_POST['alias_domain'];
                        $sql_query .= " AND alias_domain like '%".$alias_domain . "%'";
		}

                if($_POST['alias_type'] != "ANY" ) {
			for ($i=0;count($options)>$i;$i++){
	                        if($_POST['alias_type']==$options[$i]['label'])
	                        $table =  $options[$i]['value'];
			}

	                $sql = "SELECT * FROM ".$table.
        	        " WHERE (1=1) " . $sql_query;
                	$resultset = $link->queryAll($sql);
	                if(PEAR::isError($resultset)) {
        	                die('Failed to issue query, error message : ' . $resultset->getMessage());
                	}
	                if (count($resultset)==0) {
        	                $errors="No such Alias";
                	        $_SESSION['alias_username']="";
                        	$_SESSION['alias_domain']="";

	                }else{

        	                $sql = "DELETE FROM ".$table." WHERE (1=1) " . $sql_query;
                	        $link->exec($sql);
                	}		
		} else {
			for($i=0;$i<count($options);$i++){
				$table = $options[$i]['value'];
	                        $sql = "SELECT * FROM ".$table.
        	                " WHERE (1=1) " . $sql_query;
                	        $resultset = $link->queryAll($sql);
                        	if(PEAR::isError($resultset)) {
                                	die('yyFailed to issue query, error message : ' . $resultset->getMessage());
                        	}
	                        if (count($resultset)==0) {
        	                        $errors="No such Alias";
                	                $_SESSION['alias_username']="";
                        	        $_SESSION['alias_domain']="";
        	                }else{
	
                	                $sql = "DELETE FROM ".$table." WHERE (1=1) " . $sql_query;
                        	        $link->exec($sql);

				}
		}
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
