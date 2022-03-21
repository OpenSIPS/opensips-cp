<?php
require_once(__DIR__."/../../../../admin/dashboard/template/widget/widget.php");

class cdr_widget extends widget
{
    public $cdr_entries;

    function __construct($name, $sizeX, $sizeY, $color, $title=null) {
        parent::__construct($name, $sizeX, $sizeY);
        $this->set_cdr_entries();
        $this->color = $color;
    }

    function get_html() {
        return '<li type="cdr" style="background-color: '.$this->color.';" title="'.$this->title.'" id="'.$this->id.'"><div>There are '.$this->cdr_entries.' CDR Viewer entries</div></li>';
    }

    function get_name() {
        return "CDR widget";
    }

    function set_cdr_entries() {
        require(__DIR__."/../../lib/db_connect.php");
        $cdr_table = get_settings_value_from_tool("cdr_table", "cdrviewer");
        $sql = "select count(*) as no from ".$cdr_table;
        $stm = $link->prepare($sql);
        $stm->execute();
        $row = $stm->fetchAll(PDO::FETCH_ASSOC);
        $this->cdr_entries = $row[0]['no'];
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY());
    }

    public static function new_form() {  
        form_generate_input_text("Name", "", "widget_name", null, null, 20,null);
        form_generate_input_text("ID", "", "widget_id", null, null, 20,null);
        form_generate_input_text("SizeX", "", "widget_sizex", null, null, 20,null);
        form_generate_input_text("SizeY", "", "widget_sizey", null, null, 20,null);
        form_generate_input_text("Color", "", "widget_color", null, null, 20,null);
    }

}

?>