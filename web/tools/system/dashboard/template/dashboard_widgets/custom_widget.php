<?php
require_once("template/widget/widget.php");

class custom_widget extends widget
{
    public $text;
	public static $ignore = 1;
    function __construct($text, $name, $sizeX, $sizeY, $title=null) {
        parent::__construct($name, $sizeX, $sizeY, $title);
        $this->text = $text;
    }

    function get_html() {
        return '<li type="custom" title='.$this->title.' id='.$this->id.'><header>'.$this->title.'<a href=\'dashboard.php?action=edit_widget&widget_name='.$this->name.'\' onclick="lockPanel()" style="position:relative; left:60px; top:2px; content: url(\'../../../images/sett.png\');"></a></header><div>'.$this->text.'</div></li>';
    }

    function get_name() {
        return "Custom widget";
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY());
    }

    public static function new_form() { 
        form_generate_input_text("Title", "", "widget_title", null, null, 20,null);
        form_generate_input_text("Content", "", "widget_content", null, null, 20,null);
        form_generate_input_text("ID", "", "widget_id", null, null, 20,null);
        form_generate_input_text("SizeX", "", "widget_sizex", null, null, 20,null);
        form_generate_input_text("SizeY", "", "widget_sizey", null, null, 20,null);
    }

}

?>