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

    public static function get_name() {
        $desc = "Profile_Size_Statistic";
        return $desc;
    }

    public static function get_tool() {
        $desc = "Dialog";
        return $desc;
    }
    
    function get_statistics() {
        session_load_from_tool("dialog");
		$params = array("profile"=>$this->input);
        $errors = "";
		$mi_connectors=get_proxys_by_assoc_id(get_settings_value_from_tool('talk_to_this_assoc_id', "dialog"));
		$msg=mi_command("profile_get_size", $params, $mi_connectors[0], $errors);
		$profile_size = $msg["Profile"]["count"];
        return $profile_size;
    }
}