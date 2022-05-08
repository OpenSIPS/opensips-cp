<?php
require_once(__DIR__."/../../smonitor/template/stat_class.php");

class stats2 extends custom_statistic
{
    
    function __construct($input) {
        parent::__construct("stats2_name", "dialog_stats2", "Dialog", $input);
    }
    
    public static function get_description() {
        $desc = "this is the description 2";
        return $desc;
    }

    function get_statistics() {
        return array(0,1,5);
    }
}