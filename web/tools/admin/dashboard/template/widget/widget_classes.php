<?php
require_once("widget.php");

class chart_widget extends widget
{
    public $chart;
    function __construct($chart, $name, $title=null) {
        parent::__construct($name, 4, 2, $title);
        $this->chart = $chart;
    }

    function get_html() {
        return '<li type="chart" id='.$this->id.'></li>';
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY(), $this->get_id());
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
        return '<li type="custom" title='.$this->title.' id='.$this->id.'><header>'.$this->title.'<a href=\'dashboard.php?action=edit_widget&widget_name='.$this->name.'\' onclick="lockPanel()" style="position:relative; left:60px; top:2px; content: url(\'../../../images/sett.png\');"></a></header><div>'.$this->text.'</div></li>';
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY());
    }

}

?>