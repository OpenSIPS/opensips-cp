<?php
require_once(__DIR__."/../../../../admin/dashboard/template/widget/widget.php");

class status_report_widget extends widget
{
    public $mi_group;
	public $mi_id;
	public $status;
	public $box_id;
	public $widget_box;

    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_name'], 2,2, $array['widget_name']);
		$this->box_id = $array['widget_box'];
		foreach ($_SESSION['boxes'] as $box) {
			if ($box['id'] == $this->box_id)
				$this->widget_box = $box;
		}
		$this->mi_group = $array['widget_group'];
		$this->mi_id = $array['widget_identifier'];
		$this->set_params();
        $this->color = "rgb(225, 229, 195)";
    }


    function get_name() {
        return "Status report widget";
    }
    function display_test() {
		//echo ('<span style= "font-size:13px;">Status report for '.$this->mi_group.' '.$this->mi_id.':</span><br><br>');
		echo('<table class="ttable" style="table-layout: fixed;
		width: 150px; height:15px; margin: auto;" cellspacing="0" cellpadding="0" border="0">
		');
		echo('<tr><td class="rowEven">Details: '.$this->status['Details'].' ('.$this->status['Status'].')</td></tr>');
		echo('<tr><td class="rowEven">Readiness: '.(($this->status['Readiness'])?"<span style=\"font-weight: 900; color:green;\">True</span>":"<span style=\"font-weight: 900; color:red;\">False</span>").'</td></tr>');
		echo('</table>');
	}

	function set_params() {
		$group = $this->mi_group;
		$identifier = $this->mi_id;
		$params = [];
		$params["group"] = $group;
		if ($identifier)
			$params["identifier"] = $identifier;
		$stat_res = mi_command("sr_get_status", array("group" => $group), $this->widget_box['mi_conn'], $errors);
		$this-> status = $stat_res;
	}


    function echo_content() {
       $this->display_test();
    }

	public static function get_boxes() {
        $boxes_names = [];
        foreach ($_SESSION['boxes'] as $box) {
            $boxes_names[] = $box['id'];
        }
        return $boxes_names;
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY());
    }

    public static function new_form($params = null) { 
		if (!isset($params['widget_identifier']))
			$params['widget_identifier'] = "main"; 
        form_generate_input_text("Name", "", "widget_name", "n", $params['widget_name'], 20,null);
        form_generate_input_text("Group", "", "widget_group", "n", $params['widget_group'], 20,null);
        form_generate_input_text("Identifier", "", "widget_identifier", "n", $params['widget_identifier'], 20,null);
    	form_generate_select("Box", "", "widget_box", null,  $params['widget_box'], self::get_boxes());
	}

}

?>