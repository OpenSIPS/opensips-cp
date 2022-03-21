<?php

abstract class widget
{
    public $name;
    public $id;
    public $sizeX, $sizeY;
    public $title;
    public $color;

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