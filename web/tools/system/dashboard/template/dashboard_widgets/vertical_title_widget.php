<?php
require_once("template/widget/widget.php");

class vertical_title_widget extends widget
{
    public $text;
	public static $ignore = 1;
    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_title'], 1, 5, $array['widget_title']);
        $this->text = $text;
        $this->color = $array['widget_color'];
        $this->has_menu = $array['widget_menu'];
    }

    function get_html() {
        $menu = "";
        $color = "";

        if ($this->has_menu == "yes") 
            $menu = '<header><a href=\'dashboard.php?action=edit_widget&panel_id='.$this->panel_id.'&widget_id='.$this->id.'\' onclick="lockPanel()" style=" top:2px; content: url(\'../../../images/sett.png\');"></a></header>';
        
        if ($this->color)
            $color = 'style="background-color: '.$this->color.';"';

        return '<li type="verticalTitle" '.$color.' title='.$this->title.' id='.$this->id.'>'.$menu.'<div>'.$this->title.'</div></li>';
    }

    function get_name() {
        return "Vertical title widget";
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY());
    }

    public static function new_form($params = null) {
        if (!$params['widget_title'])
            $params['widget_title'] = "Title";
        form_generate_input_text("Title", "", "widget_title", null, $params['widget_title'], 20,null);
        form_generate_input_text("ID", "", "widget_id", null, $params['widget_id'], 20,null);
        form_generate_input_text("Has menu", "", "widget_menu", null, $params['widget_menu'], 20,null);
        form_generate_input_text("Color", "", "widget_color", null, $params['widget_color'], 20,null);
    }
}

?>