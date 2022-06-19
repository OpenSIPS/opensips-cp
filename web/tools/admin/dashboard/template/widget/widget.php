<?php

abstract class widget
{
    public $name;
    public $id;
    public $sizeX, $sizeY;
    public $title;
    public $color;
    public $has_menu;
    public $panel_id;

    public function __construct($panel_id, $name, $sizeX, $sizeY, $title=null) {
        $this->panel_id = $panel_id;
        $this->name = $name;
        $this->sizeX = $sizeX;
        $this->sizeY = $sizeY;
        $this->title = $title;
    }

    function get_sizeX() {
        return $this->sizeX;
    }

    function echo_content() {
        
    }

    function get_sizeY() {
        return $this->sizeY;
    }

    function get_title() {
        return $this->title;
    }

    function get_id() {
        return $this->id;
    }

    function get_html() {
        $menu = '<header class="dashboard_menu dashboard_edit" style="background-color: #3e5771; position: absolute; top: -43px; left:0px; right:0px; border-radius: 25px 25px 0px 0px;  "><a href=\'dashboard.php?action=edit_widget&panel_id='.$this->panel_id.'&widget_id='.$this->id.'\' onclick="lockPanel()" style=" top:2px; content: url(\'../../../images/sett.png\');"></a></header>';
        $color = 'style="background-color: '.$this->color.';"';
        return '<li class="dashboard_edit" '.$color.' id='.$this->id.'>'.$menu.'</li>';
    }

    function set_id($id) {
        $this->id = $id;
    }

}
?>