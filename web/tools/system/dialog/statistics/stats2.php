<?php
require_once(__DIR__."/../../smonitor/template/stat_class.php");

class stats2 extends custom_statistic
{
    
    function __construct($input) {
        parent::__construct("stats2", "dialog_stats2", $input);
    }
    
    function get_statistics() {
        return array(0,1,5);
    }
}