<?php
require("template/header.php");
require_once("lib/functions.inc.php");
require("lib/db_connect.php");
require("lib/".$page_id.".main.js");

global $config;

$action="";
if (isset($_GET['action']))
	$action = $_GET['action'];

$current_page="current_page_blacklist";

if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;
$page = $_SESSION[$current_page];

##############
# start add  #
##############

if($action == "add"){
	if(!$_SESSION['read_only']){
		require("template/".$page_id.".add.php");
		require('template/footer.php');
		exit();
	}else{
		$error = "User with Read-Only Rights";
	}
}

##############
# end add    #
##############


#####################
# start add_verify  #
#####################

if($action == "add_verify"){

	$prefix = $_POST['prefix'];
	$description = $_POST['description'];
	$whitelist = isset($_POST['whitelisted']) ? '1' : '0';

	if(empty($prefix)) $error = "You have to specify a prefix !";
	else{
		$sql = "SELECT * FROM globalblacklist WHERE prefix='$prefix'";
		$resultset = $link->query($sql);

		if(PEAR::isError($resultset)) {
			die('Failed to issue query, error message : ' . $resultset->getMessage());
		}

		if ( $resultset->numRows() > 0 ) $error="This prefix has already been entered in the database !";
		else {
			$sql = "INSERT INTO globalblacklist (id, prefix, whitelist, description) VALUES ('', :prefix, :whitelist, :description)";
			$resultset = $link->prepare($sql);

			$resultset->execute(array(
				"prefix"=>$prefix,
				"whitelist"=>$whitelist,
				"description"=>$description
				));
			$resultset->free();
			$log = $prefix . " successuly " . ($whitelist ? "whitelisted" : "blacklisted") . "<hr/>";
		}
	}
}

#####################
# end add_verify    #
#####################


#################
# start delete  #
#################

if($action == "delete"){
	if(!$_SESSION['read_only']){
		$id = $_GET['id'];

		$sql = "SELECT * FROM globalblacklist WHERE id='$id'";
		$resultset = $link->query($sql);

		if(PEAR::isError($resultset)) {
			die('Failed to issue query, error message : ' . $resultset->getMessage());
		}

		if ( $resultset->numRows() == 0 ) $error="This entry doesn't exist !";
		else {
			$resultset->free();

			$sql = "DELETE FROM globalblacklist WHERE id=:id";
			$resultset = $link->prepare($sql);

			$resultset->execute(array(
				"id"=>$id
				));
			$resultset->free();
			$log = "Entry successuly deleted !<hr/>";
		}
	}else{
		$error = "User with Read-Only Rights";
	}
}

#################
# end delete    #
#################


################
# start search #
################
if ($action=="dp_act")
{
	$_SESSION[$current_page]=1;
	extract($_POST);
	if ($show_all=="Show All") {
		$_SESSION['lst_g_prefix']="";
		$_SESSION['lst_g_whitelist']="";
		$_SESSION['lst_g_description']="";
	} else if($search=="Search"){
		$_SESSION['lst_g_prefix']=$_POST['lst_prefix'];
		$_SESSION['lst_g_whitelist']= isset($_POST['lst_whitelist']) ? $_POST['lst_whitelist'] : "";
		$_SESSION['lst_g_description']=$_POST['lst_description'];
	} 
}
##############
# end search #
##############


##############
# start edit #
##############
if ($action=="edit")
{
	if(!$_SESSION['read_only']){
		$id = $_GET['id'];
		$sql = "SELECT * FROM globalblacklist WHERE id='$id'";
		$resultset = $link->query($sql);

		if(PEAR::isError($resultset)) {
			die('Failed to issue query, error message : ' . $resultset->getMessage());
		}

		if ( $resultset->numRows() == 0 ) $error="This entry doesn't exist !";
		else {
			$entry = $resultset->fetchRow();

			$resultset->free();

			require("template/".$page_id.".edit.php");
			require("template/footer.php");
			exit();
		}
	}else{
		$error = "User with Read-Only Rights";
	}
}
##############
# end edit   #
##############


#################
# start modify  #
#################

if($action == "modify"){
	if(!$_SESSION['read_only']){
		$id = $_GET['id'];
		$prefix = $_POST['prefix'];
		$description = $_POST['description'];
		$whitelist = isset($_POST['whitelisted']) ? "1" : "0";

		if(empty($prefix)) $error = "You have to specify a prefix !";
		else{
			$sql = "SELECT * FROM globalblacklist WHERE id='$id'";
			$resultset = $link->query($sql);

			if(PEAR::isError($resultset)) {
				die('Failed to issue query, error message : ' . $resultset->getMessage());
			}

			if ( $resultset->numRows() == 0 ) $error="This entry doesn't exist !";
			else {
				$resultset->free();

				$sql = "UPDATE globalblacklist SET prefix=:prefix, description=:description, whitelist=:whitelist WHERE id=:id";
				$resultset = $link->prepare($sql);

				$resultset->execute(array(
					"id"=>$id,
					"prefix"=>$prefix,
					"description"=>$description,
					"whitelist"=>$whitelist
					));
				$resultset->free();
				$log = $prefix . " successuly " . ($whitelist ? "whitelisted" : "blacklisted") . "<hr/>";
			}
		}
	}else{
		$error = "User with Read-Only Rights";
	}
}

#################
# end modify    #
#################



##############
# start main #
##############

require("template/".$page_id.".main.php");
require("template/footer.php");
exit();

##############
# end main   #
##############
?>

