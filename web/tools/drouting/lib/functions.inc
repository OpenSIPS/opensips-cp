<?php
/*
 * $Id: functions.inc,v 1.1.1.1 2006-08-30 10:43:07 bogdan Exp $
 */
 
######################
# Database Functions #
######################

function db_connect()
{
 global $config;
 
 $link = @mysql_connect($config->db_host, $config->db_user, $config->db_pass);

 if (!$link) {
              die("Could not connect to MySQL Server: " . mysql_error());
              exit();
             }

 $selected = @mysql_select_db($config->db_name, $link);
 if (!$selected) {
                  die("Could not select '$config->db_name' database." . mysql_error());
                  exit();
                 } 
}

function db_close()
{
 mysql_close();
}

##########################
# End Database Functions #
##########################


function get_priv()
{
 if ($_SESSION['user_tabs']=="*") $_SESSION['read_only'] = false;
 else {
       $available_tabs = explode(",", $_SESSION['user_tabs']);
       $available_priv = explode(",", $_SESSION['user_priv']);
       $key = array_search("drouting", $available_tabs);
       if ($available_priv[$key]=="read-only") $_SESSION['read_only'] = true;
       if ($available_priv[$key]=="read-write") $_SESSION['read_only'] = false;
      }
 return;
}

?>