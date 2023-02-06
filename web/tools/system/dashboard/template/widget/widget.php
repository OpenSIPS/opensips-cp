<?php

abstract class widget
{
  public static $ignore = 0;
  public $name;
  public $id = null;
  public $sizeX, $sizeY;
  public $title;
  public $color;
  public $has_menu;
  public $panel_id;
  public $_status = 0;
  public $refresh_period = 0;

  const STATUS_UNKNOWN = 0;
  const STATUS_OK = 1;
  const STATUS_WARN = 2;
  const STATUS_CRIT = 3;

  public function __construct($panel_id, $name, $sizeX, $sizeY, $title=null, $refresh=0) {
    $this->panel_id = $panel_id;
    $this->name = $name;
    $this->sizeX = $sizeX;
    $this->sizeY = $sizeY;
    $this->title = $title;
    $this->refresh_period = $refresh;
    $this->color = "rgb(225, 232, 239)";
  }

  function get_sizeX() {
    return $this->sizeX;
  }

  function display_widget($update = null) {
    echo ("<div id=".$this->id."_old>
      <div class='widget_title_bar' style='height: 20px; background-color: #3e5771; position: absolute; top: 0px; left:0px; right:0px; border-radius: 7px 7px 1px 1px;'><span style='color:rgb(203, 235,221); position:relative; top:2px;'>".$this->title."</span>
      <span id='".$this->id."_status_indicator' class='status_indicator'></span>
      </div><hr style='height:10px; visibility:hidden;' />
      ");
    if ($this->refresh_period != 0) {
      $func = $this->refresh();
      if ($func)
        $func = "refresh_widget_json('".$this->id."', ".$func.")";
      else
        $func = "refresh_widget_html('".$this->id."')";
      echo ('<script type="text/javascript">window.setInterval(function() { '.$func.';}, '. $this->refresh_period .');</script>');
    }
    echo("<div class='widget_body'>");
    $this->echo_content();
    echo('</div>');

    echo ("</div>
      <script>refresh_widget_status(".$this->get_status().",'".$this->id."');</script>");
  }

  function get_status() {
    return $this->_status;
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

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY(), $this->get_id());
    }

    function get_html() {
        $menu = '<header class="dashboard_menu dashboard_edit" style="background-color: #3e5771; position: absolute; top: -41px; left:0px; right:0px; border-radius: 25px 25px 0px 0px;  "><a href=\'dashboard.php?action=edit_widget&panel_id='.$this->panel_id.'&widget_id='.$this->id.'\' onclick="lockPanel()" style=" top:2px; content: url(\'../../../images/sett.png\');"></a></header>';
        $color = 'background-color: '.$this->color.'; ';
  $border = 'border-radius: 7px 7px 7px 7px; ';
        return '<li style="'.$color.$border.'" class="dashboard_edit dashboard_edit_body"  id='.$this->id.'>'.$menu.'</li>';
    }

    function refresh() {
      return false;
    }

    function get_data() {
      return false;
    }

    public static function get_boxes() {
        $boxes_names = [];
    $boxes_ids = [];
        foreach ($_SESSION['boxes'] as $box) {
      $boxes_names[] = $box['name']." / ".$_SESSION['systems'][$box['assoc_id']]['name'];
            $boxes_ids[] = $box['id'];
        }
    $boxes_info[0] = $boxes_ids;
    $boxes_info[1] = $boxes_names;
        return $boxes_info;
    }

    function set_id($id) {
        $this->id = $id;
    }

  static function get_description() {
  }

  function set_status($level) {
    $this->_status = $level;
  }
}
// vim:set sw=2 ts=2 et ft=php fdm=marker:
?>
