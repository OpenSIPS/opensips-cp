<?php
require_once("widget.php");

class custom_widget extends widget
{
    public $text;

    function __construct($text, $name, $sizeX, $sizeY, $title=null) {
        parent::__construct($name, $sizeX, $sizeY, $title);
        $this->text = $text;
    }

    function get_html() {
        return '<li type="custom" title='.$this->title.' id='.$this->id.'><header>'.$this->title.'<a href=\'dashboard.php?action=edit_widget&widget_name='.$this->name.'\' onclick="lockPanel()" style="position:relative; left:60px; top:2px; content: url(\'../../../images/sett.png\');"></a></header><div>'.$this->text.'</div></li>';
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

class horizontal_title_widget extends widget
{
    public $text;

    function __construct($title) {
        parent::__construct($title, 5, 1, $title);
        $this->text = $text;
    }

    function get_html() {
        return '<li type="horizontalTitle" title='.$this->title.' id='.$this->id.'><div>'.$this->title.'</div></li>';
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY());
    }

    public static function new_form() {  
        form_generate_input_text("Title", "", "widget_title", null, null, 20,null);
        form_generate_input_text("ID", "", "widget_id", null, null, 20,null);
    }
}

class vertical_title_widget extends widget
{
    public $text;

    function __construct($title) {
        parent::__construct($title, 1, 5, $title);
        $this->text = $text;
    }

    function get_html() {
        return '<li type="verticalTitle" title='.$this->title.' id='.$this->id.'><div>'.$this->title.'</div></li>';
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY());
    }

    public static function new_form() {  
        form_generate_input_text("Title", "", "widget_title", null, null, 20,null);
        form_generate_input_text("ID", "", "widget_id", null, null, 20,null);
    }
}

?>