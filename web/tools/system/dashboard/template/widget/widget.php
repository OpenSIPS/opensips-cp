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
  public $widget_module = null;
  public $widget_group = null;
  public $widget_name = null;
  public $status_module = "";
  public $status_report = null;
  public $status_report_id = null;
  public $status_actions = null;

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
    $reflector = new \ReflectionClass(get_class($this));
    $this->widget_name = substr(basename($reflector->getFileName()), 0, -4);
    $dir = dirname(dirname(dirname($reflector->getFileName())));
    $this->widget_module = basename($dir);
    $this->widget_group = basename(dirname($dir));
    $this->refresh_period = $refresh;
    $this->color = "rgb(225, 232, 239)";
  }

  function get_sizeX() {
    return $this->sizeX;
  }

  function display_widget($update = null) {
    if ($this->status_module !== null) {
      if ($this->status_module === "")
        $module = $this->widget_module;
      else
        $module = $this->status_module;
    } else {
      $module = null;
    }
    echo ("<div id=".$this->id."_old>
      <div class='widget_title_bar' style='height: 20px; background-color: #3e5771; position: absolute; top: 0px; left:0px; right:0px; border-radius: 7px 7px 1px 1px;'><span style='color:rgb(203, 235,221); position:relative; top:2px;'>".$this->title."</span>
      <span id='".$this->id."_status_indicator' class='status-indicator'>
      ");
    if ($module != null || $this->status_report_id != null || $this->status_actions != null) {
      echo ("<div class='dropdown-content'>");
      if ($module != null) {
        echo ("<a href='../../../tools/".$this->widget_group."/".$module."/index.php'>Go to ".get_tool_name($module, $this->widget_group)."</a>");
      }
      if ($this->status_report_id != null) {
        if ($this->status_report != null)
          $module = $this->status_report;
        echo ("<a href='../../../tools/system/statusreport/index.php?group=".$module."&id=".urlencode($this->status_report_id)."'>Go to Reports</a>");
      }
      if ($this->status_actions != null) {
        foreach ($this->status_actions as $action => $target)
          echo ("<a href='".$target."'>Go to ".$action."</a>");
      }
      echo ("</div>");
    }
    echo("</span>
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

  public static function get_box($params) {
    if (!isset($params['widget_box']))
      return null;
    foreach ($_SESSION['boxes'] as $b) {
      if ($b['id'] == $params['widget_box'])
        return $b;
    }
    return null;
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
