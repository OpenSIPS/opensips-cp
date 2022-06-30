<?php
require_once(__DIR__."/../../../../admin/dashboard/template/widget/widget.php");

class status_report_widget extends widget
{
    public $mi_group;
	public $mi_id;
	public $status;

    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_name'], 2,3, $array['widget_name']);
		$this->mi_group = $array['widget_group'];
		$this->mi_id = $array['widget_identifier'];
		$this->set_params();
        $this->color = "rgb(225, 229, 195)";
    }


    function get_name() {
        return "Status report widget";
    }
    function display_test() {
		echo ('<span style= "font-size:13px;">Status report for '.$this->mi_group.' '.$this->mi_id.':</span><br><br>
		<table class="ttable" style="table-layout: fixed;
		width: 140px; height:15px; margin: auto;" cellspacing="0" cellpadding="0" border="0">
		');
		foreach($this->status as $key => $value) {
			echo('<tr><td class="rowEven">'.$key.': '.$value.'</td></tr>');
		}
		echo('</table>');
	}

	function set_params() {
		$group = $this->mi_group;
		$identifier = $this->mi_id;
		$params = [];
		$params["group"] = $group;
		if ($identifier)
			$params["identifier"] = $identifier;
		$stat_res = mi_command("sr_get_status", array("group" => $group), $_SESSION['boxes'][0]['mi_conn'], $errors);
		$this-> status = $stat_res;
	}


    function echo_content() {
       $this->display_test();
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY());
    }

    public static function new_form($params = null) {  
        form_generate_input_text("Name", "", "widget_name", "n", $params['widget_name'], 20,null);
        form_generate_input_text("Group", "", "widget_group", "n", $params['widget_group'], 20,null);
        form_generate_input_text("Identifier", "", "widget_identifier", "y", $params['widget_identifier'], 20,null);
    }

}

?>