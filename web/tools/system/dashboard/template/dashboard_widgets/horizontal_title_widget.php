<?php
require_once("template/widget/widget.php");

class horizontal_title_widget extends widget
{
    public $text;
	public static $ignore = 1;

    function __construct($title) {
        parent::__construct($title, 5, 1, $title);
        $this->text = $text;
    }

    function get_html() {
        return '<li type="horizontalTitle" title='.$this->title.' id='.$this->id.'><div>'.$this->title.'</div></li>';
    }

    function get_name() {
        return "Horizontal title widget";
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY());
    }

    public static function new_form() {
        if (!$params['widget_title'])
            $params['widget_title'] = "Title"; 
        form_generate_input_text("Title", "", "widget_title", null, null, 20,null);
        form_generate_input_text("ID", "", "widget_id", null, null, 20,null);
    }
}
?>