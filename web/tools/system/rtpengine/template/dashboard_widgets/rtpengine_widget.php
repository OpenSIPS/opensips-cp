<?php
require_once(__DIR__."/../../../../system/dashboard/template/widget/widget.php");

class rtpengine_widget extends widget
{
  public $active = 0;
  public $inactive = 0;
  public $probing = 0;
  public $widget_box;

  function __construct($array) {
    $r = intval($array['widget_refresh']) * 1000;
    parent::__construct($array['panel_id'], $array['widget_name'], 2,2, $array['widget_name'],$r);
    $this->set_status(widget::STATUS_OK);
    if (isset($array['widget_set']) && $array['widget_set'] != '')
      $this->set = $array['widget_set'];
    else
      $this->set = null;
    $this->widget_box = self::get_box($array);
    if ($this->widget_box == null)
      $this->set_status(widget::STATUS_CRIT);
    else
      $this->update();
  }


  function get_name() {
    return "RTPEngine Servers";
  }
  function display_test() {
    echo ('
      <table style="table-layout: fixed;
        width: 180px; height:20px; margin: auto; margin-left: 30px; font-weight: bolder;" cellspacing="2" cellpadding="2" border="0">
      ');
    echo ('
      <tr><td class="rowEven">Available: </td><td><span style="color:green; font-weight: 900;">'.$this->active.'</span></td></tr>');
    if ($this->probing > 0)
      echo ('<tr><td class="rowEven">Probing: </td><td><span style="color:orange; font-weight: 900;">'.$this->probing.'</span></td>
      </tr>');
    if ($this->inactive >0) {
      echo ('<tr><td class="rowEven">Inactive: </td><td><span style="color:red; font-weight: 900;">'.$this->inactive.'</span></td></tr>');
    }
    echo('</table>');

  }

  /* returns true if at least one node is available */
  function count_rtpproxies($set) {
    $ret = false;
    foreach($set as $node) {
      if ($node['disabled'] == 0) {
        $this->active ++;
        $ret = true;
      } else if ($node['recheck_ticks'] != 4294967295) {
        $this->probing ++;
      } else {
        $this->inactive ++;
      }
    }
    return $ret;
  }

  function update() {
    require_once("../../../common/mi_comm.php");
    $stat_res = mi_command("rtpengine_show", array(), $this->widget_box['mi_conn'], $errors);
    if (count($errors) != 0) {
      error_log(print_r($errors, true));
      $this->set_status(widget::STATUS_CRIT);
      return;
    }
    foreach($stat_res as $set) {
      if ($this->set == null || $this->set == $set['Set']) {
        if (!$this->count_rtpproxies($set['Nodes']))
          $this->set_status(widget::STATUS_CRIT);
      }
    }
    if ($this->get_status() != widget::STATUS_CRIT && $this->probing >0)
      $this->set_status(widget::STATUS_WARN);
  }

  function echo_content() {
    $this->display_test();
  }

  public static function fetch_box_sets($params) {
    $errors = [];
    $mi_box = self::get_box($params);
    if ($mi_box == null)
      return array();
    require_once("../../../common/mi_comm.php");
    $parititons = mi_command("rtpengine_show", null, $mi_box['mi_conn'], $errors);
    if (count($errors) != 0) {
      error_log(print_r($errors, true));
      return array();
    }
    $ret = array();
    foreach ($parititons as $set) {
      $ret[] = $set['Set'];
    }
    sort($ret);
    return $ret;
  }

  public static function rtp_box_selection($set) {
        echo ('
            <script>
            var box_select = document.getElementById("widget_box");
            box_select.addEventListener("change", function(){update_sets();}, false);
            var init_set = "'.$set.'";
            function update_sets() {
                var box_select = document.getElementById("widget_box");
                var selected_box = box_select.value;
                var set_select = document.getElementById("widget_set"); 
                set_select.options.length = 1;
                set_select.selectedIndex = 0;
                fetch_widget_info("rtpengine_widget", "fetch_box_sets", "widget_box="+selected_box)
                .then(data => {
		              data.forEach(set => {
                    var opt = document.createElement("option");
                    opt.value = set;
                    opt.textContent = set;
                    set_select.appendChild(opt);
                    if (init_set == set)
                      set_select.selectedIndex = set_select.options.length - 1;
		              });
                  init_set = "";
		            });
            };
            update_sets();
            </script>
        ');
  }


  public static function new_form($params = null) {
    $boxes_info = self::get_boxes();
    if (!isset($params['widget_name']))
      $params['widget_name'] = "RTPEngine";
    if (!isset($params['widget_box']))
      $params['widget_box'] = $boxes_info[0][0];
    if (!isset($params['widget_set']))
      $params['widget_set'] = "";
    if (!isset($params['widget_refresh']))
      $params['widget_refresh'] = 60;
    form_generate_input_text("Name", null, "widget_name", null, $params['widget_name'], 20,null);
    form_generate_select("Box", null, "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
    form_generate_select("Set", "RTPEngine set to track; Empty means all servers are considered", "widget_set", null, $params['widget_set'], array(), null, true);
    form_generate_input_text("Refresh period", "Period (in seconds) when the widget should update", "widget_refresh", "n", $params['widget_refresh'], 20, '^([0-9]\+)$');
    self::rtp_box_selection($params['widget_set']);
  }

  static function get_description() {
    return "Shows the number of available RTPEngine servers vs inactive/disabled ones. The information is fetched via MI form a given OpenSIPS/Box";
  }
}

// vim:set sw=2 ts=2 et ft=php fdm=marker:
?>
