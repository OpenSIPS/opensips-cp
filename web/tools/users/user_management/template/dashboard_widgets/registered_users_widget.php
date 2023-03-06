<?php
require_once(__DIR__."/../../../../system/dashboard/template/widget/widget.php");

class registered_users_widget extends widget
{
    public $chart;
	public $widget_box_id;
	public $widget_box;
	
    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_title'], 2, 3, $array['widget_title']);
        $this->color = 'rgb(198,226,213)';
        $this->widget_box_id = $array['widget_box'];
		foreach ($_SESSION['boxes'] as $box) {
			if ($box['id'] == $this->widget_box_id)
				$this->widget_box = $box;
		}
    }


    function get_name() {
        return "Users widget";
    }

    public static function get_total_subs() {
        require_once(__DIR__."/../../lib/functions.inc.php");
	return get_total_users();
    }


    function echo_content() {
        $wi = $this->id;
        $_SESSION['ru_widget_id'] = $wi;
        $total_subs = self::get_total_subs();
        $reg_subs = mi_command("get_statistics", array("statistics" => array("location-users")), $this->widget_box['mi_conn'], $errors);
        $reg_contacts = mi_command("get_statistics", array("statistics" => array("location-contacts")), $this->widget_box['mi_conn'], $errors);
	$elems['reg_subs'] = $reg_subs['usrloc:location-users'];
	$elems['total_subs'] = $total_subs;
	$elems['reg_contacts'] = $reg_contacts['usrloc:location-contacts'];
        $_SESSION['pie_elements'] = $elems;
        require(__DIR__."/../../../../../common/charting/d3js_pie.php");
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY(), $this->get_id());
    }
  
    public static function new_form($params = null) {
        if (!$params['widget_title'])
            $params['widget_title'] = "Users";
		$boxes_info = self::get_boxes();
	if (!isset($params['widget_box']))
		$params['widget_box'] = $boxes_info[0];
        form_generate_input_text("Title", "", "widget_title", "n", $params['widget_title'], 20,null);
        form_generate_select("Box", "", "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
    }

	
	static function get_description() {
		return "
Displays information on users, like total number of provisioned users versus the number of registered users and the number of registered contacts";
	}

}
