<?php
require_once(__DIR__."/../../../../system/dashboard/template/widget/widget.php");

class gateways_widget extends widget
{
  public $available = 0;
  public $inactive = 0;
  public $probing = 0;
  public $widget_box;

  function __construct($array) {
    if (isset($array['widget_refresh']) && $array['widget_refresh'] != '')
      $r = intval($array['widget_refresh']) * 1000;
    else
      $r = 60000; # one minute is the default
    parent::__construct($array['panel_id'], $array['widget_name'], 2,2, $array['widget_name'], $r);
    $this->set_status(widget::STATUS_OK);
    if (isset($array['widget_partition']) && $array['widget_partition'] != '')
      $this->partition = $array['widget_partition'];
    else
      $this->partition = null;
    $this->widget_box = self::get_box($array);
    if ($this->widget_box == null)
      $this->set_status(widget::STATUS_CRIT);
    else
      $this->set_gateways();
    $this->status_report_id = ($this->partition == null?"Default":$this->partition) . ';events';
  }


  function get_name() {
    return "Dynamic Routing widget";
  }
  function display_test() {
    echo ('
      <table style="table-layout: fixed;
        width: 180px; height:20px; margin: auto; margin-left: 30px; font-weight: bolder;" cellspacing="2" cellpadding="2" border="0">
      ');
    echo ('
      <tr>
      <td class="rowEven">Available: </td><td><span style="color:green; font-weight: 900;">'.$this->available.'</span></td></tr>');
    if ($this->inactive > 0) {
      echo ('<tr><td class="rowEven">Inactive: </td><td><span style="color:red; font-weight: 900;">'.$this->inactive.'</span></td></tr>');
      $this->set_status(widget::STATUS_CRIT);
    }
    if ($this->probing > 0)
      echo ('<tr><td class="rowEven">Probing: </td><td><span style="color:orange; font-weight: 900;">'.$this->probing.'</span></td>
      </tr>');
    echo('</table>');

  }

  function set_gateways() {
    $errors = [];
    require_once("../../../common/mi_comm.php");
    if ($this->partition != null)
      $params = array("partition_name"=>$this->partition);
    else
      $params = null;
    $stat_res = mi_command("dr_gw_status", $params, $this->widget_box['mi_conn'], $errors);
    if (count($errors) != 0) {
      $this->set_status(widget::STATUS_CRIT);
      return;
    }
    foreach($stat_res['Gateways'] as $gateway) {
      switch ($gateway['State']) {
        case "Active":
          $this->available ++;
          break;
        case "Disabled MI":
        case "Inactive":
          $this->inactive ++;
          break;
        case "probing":
          $this->probing ++;
          break;
        default:
          error_log("Bug");
      }
    }
    if ($this->inactive > 0)
      $this->set_status(widget::STATUS_CRIT);
    else if ($this->probing > 0)
      $this->set_status(STATUS_WARN);
  }


  function echo_content() {
    $this->display_test();
  }

  public static function fetch_box_parts($params) {
    $errors = [];
    $mi_box = self::get_box($params);
    if ($mi_box == null)
      return array();
    require_once("../../../common/mi_comm.php");
    $partitions = mi_command("dr_reload_status", null, $mi_box['mi_conn'], $errors);
    if (count($errors) != 0) {
      error_log(print_r($errors, true));
      return array();
    }
    $ret = array();
    if (in_array("Gateways", $partitions)) {
      foreach ($partitions['Partitions'] as $part)
        $ret[] = $part["name"];
    }
    return $ret;
  }


  public static function dr_box_selection($partition) {
        echo ('
            <script>
            var box_select = document.getElementById("widget_box");
            box_select.addEventListener("change", function(){update_partitions();}, false);
            var init_partition = "'.$partition.'";
            function update_partitions() {
                var box_select = document.getElementById("widget_box");
                var selected_box = box_select.value;
                var part_select = document.getElementById("widget_partition"); 
                part_select.options.length = 1;
                part_select.selectedIndex = 0;
                fetch_widget_info("gateways_widget", "fetch_box_parts", "widget_box="+selected_box)
                .then(data => {
		              data.forEach(part => {
                    var opt = document.createElement("option");
                    opt.value = part;
                    opt.textContent = part;
                    part_select.appendChild(opt);
                    if (init_partition == part)
                      part_select.selectedIndex = part_select.options.length - 1;
		              });
                  init_partition = "";
		            });
            };
            update_partitions();
            </script>
        ');
  }


  public static function new_form($params = null) {
    $boxes_info = self::get_boxes();
    if (!$params['widget_name'])
      $params['widget_name'] = "Dynamic Routing";
    if (!isset($params['widget_box']))
      $params['widget_box'] = $boxes_info[0][0];
    if (!isset($params['widget_partition']))
      $params['widget_partition'] = "";
    form_generate_input_text("Name", null, "widget_name", null, $params['widget_name'], 20,null);
    form_generate_select("Box", null, "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
    form_generate_select("Partition", "Partition to track gateways for; Empty means all partitions are considered; if no partitions are available, the instance does not have partitioning enabled.", "widget_partition", null, $params['widget_partition'], array(), null, true);
    form_generate_input_text("Refresh period", "Period (in seconds) when the widget should update", "widget_refresh", "y", $params['widget_refresh'], 20, '^([0-9]\+)$');
    self::dr_box_selection($params['widget_partition']);
  }

  static function get_description() {
    return "Shows the number of available gateways vs probing/inactive ones. The information is fetched via MI from a certain OpenSIPS/Box.";
  }
}

// vim:set sw=2 ts=2 et ft=php fdm=marker:
?>
