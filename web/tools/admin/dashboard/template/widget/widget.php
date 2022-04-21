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
    }

    function set_id($id) {
        $this->id = $id;
    }

}
?>