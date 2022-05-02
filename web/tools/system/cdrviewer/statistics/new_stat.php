<?php
require_once(__DIR__."/../../smonitor/template/stat_class.php");

class new_stat extends custom_statistic
{
    
    function __construct($input) {
        parent::__construct("stats3_name", "dialog_stats3", "CDRViewer", $input);
    }
    
    function get_statistics() {
        return array(0,1,5);
    }
}