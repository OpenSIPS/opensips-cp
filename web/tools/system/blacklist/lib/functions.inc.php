<?php

function verif_entries($username, $prefix, $domain, $whitelist){
	require("../../../../config/db.inc.php");
	require("db_connect.php");
	$log = "";
	// Determines if we can insert an entry in the database
	$possible = false;
	$log .= $prefix . " successfully " . ($whitelist ? "whitelisted" : "blacklisted") . " for " . $username  . (($domain != "none") ? "@" . $domain : "") . "<hr/>";
	if($domain == "none") $domain = "";
	$sql = "SELECT * FROM userblacklist WHERE (username='$username' AND prefix='$prefix' AND domain='none') XOR (username='$username' AND prefix='$prefix' AND domain='$domain')";
	$resultset = $link->query($sql);

	if(PEAR::isError($resultset)) {
		die('Failed to issue query, error message : ' . $resultset->getMessage() . '<hr/>' . $sql);
	}

	// If one of the two available options exists, we verify which one is in the database, then we look if we can add the new entry
	if ( $resultset->numRows() == 1 ) {
		$resultset->free();
		$sql = "SELECT * FROM userblacklist WHERE username='$username' AND prefix='$prefix' AND domain='none'";

		$resultset = $link->query($sql);

		if(PEAR::isError($resultset)) {
			die('Failed to issue query, error message : ' . $resultset->getMessage() . '<hr/>' . $sql);
		}

		// If only the first option exists, we can record our entry, only if a domain is specified
		if ( $resultset->numRows() == 1 ) {
			if($domain == ""){
				$log .= "Error : We're sorry, but this entry already exists without a domain. You should modify it or add a domain !<hr/>";
			}else{
				$possible = true;
			}
		// If only the second option exists, we can record our entry only if domain isn't specified
		}else{
			if($domain == ""){
				$possible = true;
			}else{
				$log .= "Error : We're sorry, but this entry already exists with a domain. You should modify it or remove the domain !<hr/>";
			}
			$resultset->free();
		}
	// If the options exists or not in the database
	} else {

		$resultset->free();
		// Only verify first option, because if it exists
		$sql = "SELECT * FROM userblacklist WHERE (username='$username' AND prefix='$prefix' AND domain='none') AND (username='$username' AND prefix='$prefix' AND domain='$domain')";

		$resultset = $link->query($sql);

		if(PEAR::isError($resultset)) {
			die('Failed to issue query, error message : ' . $resultset->getMessage() . '<hr/>' . $sql);
		}

		// If the two options exists, we return an error
		if ( $resultset->numRows() > 0 ) {
			$log .= "Error : This prefix already exists in the database for the couples (username + prefix) and (username + prefix + domain) !<hr/>";
		// If none of the two options exists, we can record our entry in the database
		}else{
			$possible = true;
		}
		$resultset->free();
	}
	return Array($possible, $log);
}

?>
