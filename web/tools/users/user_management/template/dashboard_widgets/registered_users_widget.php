<?php
require_once(__DIR__."/../../../../admin/dashboard/template/widget/widget.php");

class registered_users_widget extends widget
{
    public $chart;
	public $widget_box;
    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_title'], 4, 3, $array['widget_title']);
        $this->color = 'rgb(242,229,206)';
        $this->widget_box = $array['widget_box'];
    }


    function get_name() {
        return "Registered Users widget";
    }

    public static function get_total_subs() {
        require(__DIR__."/../../lib/db_connect.php");
        $users_table = get_settings_value_from_tool("table_users", "user_management");
        $sql = "select count(*) as no from ".$users_table;
        $stm = $link->prepare($sql);
        $stm->execute();
        $row = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $row[0]['no'];
    }

    function echo_content() {
        $wi = $this->id;
        $_SESSION['ru_widget_id'] = $wi;
        $total_subs = self::get_total_subs();
        $reg_subs = mi_command("get_statistics", array("statistics" => array("location-users")), $_SESSION['boxes'][$this->widget_box]['mi_conn'], $errors);
        $reg_contacts = mi_command("get_statistics", array("statistics" => array("location-contacts")), $_SESSION['boxes'][$this->widget_box]['mi_conn'], $errors);

        $_SESSION['reg_subs'] = $reg_subs;
        $_SESSION['total_subs'] = $total_subs;
        $_SESSION['reg_contacts'] = $reg_contacts;
        echo ("<div id=".$wi."_old>");
        require(__DIR__."/../../../../system/smonitor/lib/d3js_pie.php");
        echo ("</div>");
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY(), $this->get_id());
    }
    
    public static function get_boxes() {
        $boxes_names = [];
        foreach ($_SESSION['boxes'] as $box) {
            $boxes_names[] = $box['id'];
        }
        return $boxes_names;
    }
  
    public static function new_form($params = null) {  
        form_generate_input_text("Title", "", "widget_title", null, $params['widget_title'], 20,null);
        form_generate_select("Box", "", "widget_box", null,  $params['widget_box'], self::get_boxes());
    }
}