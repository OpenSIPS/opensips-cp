<?php

abstract class widget
{   
    public $name;
    public $id;
    public $sizeX, $sizeY;
    public $title;

    public function __construct($name, $sizeX, $sizeY, $title=null) {
        $this->name = $name;
        $this->sizeX = $sizeX;
        $this->sizeY = $sizeY;
        $this->title = $title;
    }

    function get_sizeX() {
        return $this->sizeX;
    }

    function get_sizeY() {
        return $this->sizeY;
    }

    function get_title() {
        return $this->title;
    }

    function get_html() {
    }

    function set_id($id) {
        $this->id = $id;
    }

}

class chart_widget extends widget
{
    public $chart;
    function __construct($chart, $name, $title=null) {
        parent::__construct($name, 20, 12, $title);
        $this->chart = $chart;
    }

    function get_html() {
        return '<li id='.$this->id.'><header>'.$this->title.'</header>'.$this->chart.'</li>';
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY());
    }

}

class custom_widget extends widget
{
    public $text;

    function __construct($text, $name, $sizeX, $sizeY, $title=null) {
        parent::__construct($name, $sizeX, $sizeY, $title);
        $this->text = $text;
    }

    function get_html() {
        return '<li id='.$this->id.'><header>'.$this->title.'<a href=\'dashboard.php?action=edit_widget&widget_name='.$this->name.'\' onclick="lockPanel()" style="position:relative; left:60px; top:2px; content: url(\'../../../images/sett.png\');"></a></header>'.$this->text.'</li>';
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY());
    }

}
?>