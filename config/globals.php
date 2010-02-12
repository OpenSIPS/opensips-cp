<?php
// Global configuration parameters used by more then one module


// parameter used for the aliases tables if there are more than 
// the standard dbaliases table. The defined array has as key the
// label and as value the table name.For defining more than one
// attribute/value pair, complete the list with identical elements
// separated by comma.

$config->table_aliases = array("DBaliases"=>"dbaliases");


// The permissions parameter is also used by more the one module,
// when setting the modules permissions for a certain admin.
// This array has 2 values that will remain unchanged: read-only 
// and read-write.

$config->permissions = array("read-only","read-write");

// Password can be saved in plain text mode by setting 
// $config->admin_passwd_mode to 1 or chyphered mode, by setting it to 0
$config->admin_passwd_mode=1;

?>
