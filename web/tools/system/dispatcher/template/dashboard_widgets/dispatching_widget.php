<?php
require_once(__DIR__."/../../../../system/dashboard/template/widget/widget.php");

class dispatching_widget extends widget
{
  public $active = 0;
  public $inactive = 0;
  public $probing = 0;
  public $box_id;
  public $widget_box;

  function __construct($array) {
    if (isset($array['widget_refresh']) && $array['widget_refresh'] != '')
      $r = intval($array['widget_refresh']) * 1000;
    else
      $r = 60000; # one minute is the default
    parent::__construct($array['panel_id'], $array['widget_name'], 2,2, $array['widget_name'],$r);
    $this->box_id = $array['widget_box'];
    $this->set_status(widget::STATUS_OK);
    if (isset($array['widget_partition']) && $array['widget_partition'] != '')
      $this->partition = $array['widget_partition'];
    else
      $this->partition = null;
    if (isset($array['widget_set']) && $array['widget_set'] != '')
      $this->set = $array['widget_set'];
    else
      $this->set = null;
    $this->widget_box = self::get_box($array);
    if ($this->widget_box == null)
      $this->set_status(widget::STATUS_CRIT);
    else
      $this->update();
    $this->status_report_id = ($this->partition == null?"default":$this->partition) . ';events';
  }


  function get_name() {
    return "Dispatching destinations";
  }
  function display_test() {
    echo ('
      <table style="table-layout: fixed;
        width: 180px; height:20px; margin: auto; margin-left: 30px; font-weight: bolder;" cellspacing="2" cellpadding="2" border="0">
      ');
    echo ('
      <tr><td class="rowEven">Available: </td><td><span style="color:green; font-weight: 900;">'.$this->active.'</span></td></tr>');
    if ($this->inactive >0) {
      echo ('<tr><td class="rowEven">Inactive: </td><td><span style="color:red; font-weight: 900;">'.$this->inactive.'</span></td></tr>');
    }
    if ($this->probing > 0)
      echo ('<tr><td class="rowEven">Probing: </td><td><span style="color:orange; font-weight: 900;">'.$this->probing.'</span></td>
      </tr>');
    echo('</table>');

  }

  function count_destinations($set) {
    foreach($set['Destinations'] as $destination) {
      switch ($destination['state']) {
      case "Active":
        $this->active ++;
        break;
      case "Inactive":
        $this->inactive ++;
        break;
      case "probing":
        $this->probing ++;
        break;
      default:
        error_log("Bug: ".$destination['state']);
      }
    }
  }

  function update() {
    require_once("../../../common/mi_comm.php");
    if ($this->partition == null) {
      $stat_res = mi_command("ds_list", array(), $this->widget_box['mi_conn'], $errors);

      foreach($stat_res["PARTITIONS"] as $key => $partition)
        foreach($partition["SETS"] as $key => $set)
          $this->count_destinations($set);
    } else {
      $stat_res = mi_command("ds_list", array("partition"=>$this->partition), $this->widget_box['mi_conn'], $errors);
      if ($this->set != null) {
        foreach($stat_res["PARTITIONS"][0]["SETS"] as $key => $set)
          if ($set["id"] == $this->set)
            $this->count_destinations($set);
      } else {
        foreach($stat_res["PARTITIONS"][0]["SETS"] as $key => $set)
          $this->count_destinations($set);
      }
    }
    if (count($errors) != 0 || $this->inactive > 0)
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
    $parititons = mi_command("ds_list", null, $mi_box['mi_conn'], $errors);
    if (count($errors) != 0) {
      error_log(print_r($errors, true));
      return array();
    }
    $ret = array();
    foreach ($parititons['PARTITIONS'] as $part) {
      $ret[$part["name"]] = array();
      foreach ($part['SETS'] as $set)
        $ret[$part["name"]][] = $set["id"];
       sort($ret[$part["name"]]);
    }
    return $ret;
  }

  public static function ds_box_selection($partition, $set) {
        echo ('
            <script>
            var box_select = document.getElementById("widget_box");
            box_select.addEventListener("change", function(){update_partitions();}, false);
            var part_select = document.getElementById("widget_partition");
            part_select.addEventListener("change", function(){update_set();}, false);
            var partitions = {};
            var init_partition = "'.$partition.'";
            var init_set = "'.$set.'";
            function update_partitions() {
                var box_select = document.getElementById("widget_box");
                var selected_box = box_select.value;
                var part_select = document.getElementById("widget_partition"); 
                part_select.options.length = 1;
                part_select.selectedIndex = 0;
                fetch_widget_info("dispatching_widget", "fetch_box_parts", "widget_box="+selected_box)
                .then(data => {
                  partitions = data;
		              Object.keys(data).forEach(part => {
                    var opt = document.createElement("option");
                    opt.value = part;
                    opt.textContent = part;
                    part_select.appendChild(opt);
                    if (init_partition == part)
                      part_select.selectedIndex = part_select.options.length - 1;
		              });
                  init_partition = "";
                  update_set();
		            });
            };
            function update_set() {
              var set_select = document.getElementById("widget_set"); 
              var part_select = document.getElementById("widget_partition"); 
              var partition = part_select.value;
              set_select.options.length = 1;
              set_select.selectedIndex = 0;
              if (partition in partitions) {
                partitions[partition].forEach(set => {
                  var opt = document.createElement("option");
                  opt.value = set;
                  opt.textContent = set;
                  set_select.appendChild(opt);
                  if (init_set == set)
                    set_select.selectedIndex = set_select.options.length - 1;
                });
              }
              init_set = "";
            }
            update_partitions();
            </script>
        ');
  }


  public static function new_form($params = null) {
    $boxes_info = self::get_boxes();
    if (!isset($params['widget_name']))
      $params['widget_name'] = "Dispatching";
    if (!isset($params['widget_box']))
      $params['widget_box'] = $boxes_info[0][0];
    if (!isset($params['widget_partition']))
      $params['widget_partition'] = "";
    if (!isset($params['widget_set']))
      $params['widget_set'] = "";
    form_generate_input_text("Name", null, "widget_name", null, $params['widget_name'], 20,null);
    form_generate_select("Box", null, "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
    form_generate_select("Partition", "Partition to track destinations for; Empty means all partitions are considered", "widget_partition", null, $params['widget_partition'], array(), null, true);
    form_generate_select("Set", "Set within partition to track destinations for; Empty means all sets within partition are considered", "widget_set", null, $params['widget_set'], array(), null, true);
    form_generate_input_text("Refresh period", "Period (in seconds) when the widget should update", "widget_refresh", "y", $params['widget_refresh'], 20, '^([0-9]\+)$');
    self::ds_box_selection($params['widget_partition'], $params['widget_set']);
  }

  static function get_description() {
    return "Shows the number of available destinations vs probing/inactive ones. The information is fetched via MI from a certain OpenSIPS/Box.";
  }
}

// vim:set sw=2 ts=2 et ft=php fdm=marker:
?>
