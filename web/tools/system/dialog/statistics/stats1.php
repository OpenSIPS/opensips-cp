<?php
require_once(__DIR__."/../../smonitor/template/stat_class.php");

class stats1 extends custom_statistic
{
    
    function __construct($input) {
        parent::__construct("stats1_name", "dialog_stats1", "Dialog", $input);
    }
    
    public static function get_description() {
        $desc = "this is the description 1";
        return $desc;
    }
    
    function get_statistics() {
        return array(0,1,3);
    }
}