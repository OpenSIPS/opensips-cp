<?php
require_once(__DIR__."/../../../../admin/dashboard/template/widget/widget.php");

class cdr_widget extends widget
{
    public $cdr_entries;

    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_name'], $array['widget_sizex'], $array['widget_sizey']);
        $this->set_cdr_entries();
        $this->color = $array['widget_color'];
    }

    function get_html() {  
        $menu = "";
        $color = "";

        if ($this->has_menu == "yes") 
            $menu = '<header><a href=\'dashboard.php?action=edit_widget&panel_id='.$this->panel_id.'&widget_id='.$this->id.'\' onclick="lockPanel()" style=" top:2px; content: url(\'../../../images/sett.png\');"></a></header>';
        
        if ($this->color)
            $color = 'style="background-color: '.$this->color.';"';

        return '<li type="cdr" '.$color.' style="background-color: '.$this->color.';" title="'.$this->title.'" id="'.$this->id.'">'.$menu.'<div>There are '.$this->cdr_entries.' CDR Viewer entries</div></li>';
    }

    function get_name() {
        return "CDR widget";
    }
    function display_test() {
        echo ('<iframe width="500" height="400" id="Megatest" src="./../../system/cdrviewer/index.php" title="description"></iframe>');
    }

    function display_test3() {
        echo ('<!DOCTYPE html>
        <html>
        <head>
        <style> 
        .flex-container {
          display: -webkit-flex;
          display: flex;  
          -webkit-flex-flow: row wrap;
          flex-flow: row wrap;
          font-weight: bold;
          text-align: center;
        }
        
        .flex-container > * {
          padding: 10px;
          flex: 1 100%;
        }
        
        .main {
          text-align: left;
          background: cornflowerblue;
        }
        
        .header {background: coral;}
        .footer {background: lightgreen;}
        .aside {background: moccasin;}
        
        @media all and (min-width: 768px) {
          .aside { flex: 1 auto; }
          .main    { flex: 3 0px; }
          .aside { order: 1; } 
          .main    { order: 2; }
          .footer  { order: 4; }
        }
        </style>
        </head>
        <body>
        
        <div class="flex-container">
          <header class="header">Header</header>
          <aside class="aside">Aside</aside>
          <article class="main">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sed ex turpis. Cras luctus nibh lectus, in ullamcorper ex tempor eleifend. Nulla bibendum, eros a consequat vestibulum, orci massa fermentum quam, sed commodo nunc ex vitae nisl. Aliquam ullamcorper interdum est nec tincidunt.</p>
          </article>
          <footer class="footer">Footer</footer>
        </div>
        
        </body>
        </html>
        
        ');
    }

    function display_test2() {
        echo ('
        <form action="/action_page.php" method="get" id="form1">
        <label for="fname">First name:</label>
        <input type="text" id="fname" name="fname"><br><br>
        <label for="fname">First name:</label>
        <input type="text" id="fname" name="fname"><br><br>
        <label for="fname">First name:</label>
        <input type="text" id="fname" name="fname"><br><br>
        <label for="fname">First name:</label>
        <input type="text" id="fname" name="fname"><br><br>
<label for="lname">Last name:</label>
<input type="text" id="lname" name="lname">
</form>

<button type="submit" form="form1" value="Submit">Submit</button>
        ');
    }

    function echo_content() {
        echo ('<div id="'.$this->id.'_old">');
        $this->display_test3();
        echo('</div>');
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

    public static function new_form($params = null) {  
        form_generate_input_text("Name", "", "widget_name", null, $params['widget_name'], 20,null);
        form_generate_input_text("ID", "", "widget_id", null, $params['widget_id'], 20,null);
        form_generate_input_text("SizeX", "", "widget_sizex", null, $params['widget_sizex'], 20,null);
        form_generate_input_text("SizeY", "", "widget_sizey", null, $params['widget_sizey'], 20,null);
        form_generate_input_text("Color", "", "widget_color", null, $params['widget_color'], 20,null);
    }

}

?>