<?php
require_once(__DIR__."/../../smonitor/template/stat_class.php");

class dialog_stat extends custom_statistic
{
	public $profile;

    function __construct($input) {
        parent::__construct("stats1_name", "dialog_stats1", "Dialog", $input);
		$this->profile = $input['profile_id'];
    }
    
    public static function get_description() {
        $desc = "This class gets the dialog size of a profile";
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
		$params = array("profile"=>$this->profile);
        $errors = "";
		$mi_connectors=get_proxys_by_assoc_id(get_settings_value_from_tool('talk_to_this_assoc_id', "dialog"));
		$msg=mi_command("profile_get_size", $params, $mi_connectors[0], $errors);
		$profile_size = $msg["Profile"]["count"];
        return $profile_size;
    }

    public static function new_form($params = null) {  
		form_generate_input_text("Custom statistic name:", null, "name_id", "y", $params['name_id'], null, null);
		form_generate_input_text("Profile:", null, "profile_id", "y", $params['profile_id'], null, null);
		
	}
}