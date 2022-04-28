<?php
require_once(__DIR__."/../../smonitor/template/stat_class.php");

class stats1 extends custom_statistic
{
    
    function __construct($input) {
        parent::__construct("stats1", "dialog_stats1", $input);
    }
    
    function get_statistics() {
        return array(0,1,3);
    }
}