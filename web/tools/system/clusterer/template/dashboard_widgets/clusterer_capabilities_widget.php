<?php
require_once(__DIR__."/../../../../system/dashboard/template/widget/widget.php");

class clusterer_capabilities_widget extends widget
{
  public $active = 0;
  public $inactive = 0;
  public $probing = 0;
  public $widget_box;
  public $clusters;

  const cap_state = array(
    array("ok", "green"),
    array("not sync", "red"),
    array("disabled", "orange"),
  );

  function __construct($array) {
    $r = intval($array['widget_refresh']) * 1000;
    parent::__construct($array['panel_id'], $array['widget_name'], 3,3, $array['widget_name'],$r);
    $this->set_status(widget::STATUS_OK);
    if (isset($array['widget_cluster']) && $array['widget_cluster'] != '')
      $this->cluster = $array['widget_cluster'];
    else
      $this->cluster = null;
    $this->widget_box = self::get_box($array);
    $this->clusters = array();
    $this->count = 0;
    if ($this->widget_box == null)
      $this->set_status(widget::STATUS_CRIT);
    else
      $this->update();
  }


  function get_name() {
    return "Clusterer Capabilities";
  }

  function display_test() {
    echo ('
        <table style="table-layout: fixed;
          width: 270px; height:20px; margin: auto; margin-left: 10px; font-weight: bolder;" cellspacing="2" cellpadding="2" border="0">'
    );
    if ($this->cluster) {
      echo ('
          <tr>
            <td style="width: 75%; text-shadow: 0.5px 0.5px;">Capability</td>
            <td style="width: 25%; text-shadow: 0.5px 0.5px;">State</td>
          </tr>
        ');
      foreach ($this->clusters[$this->cluster] as $name => $state) {
        echo ('
          <tr>
            <td>'.$name.'</td>
            <td><span style="color:'.clusterer_capabilities_widget::cap_state[$state][1].';">'.clusterer_capabilities_widget::cap_state[$state][0].'</span></td>
          </tr>');
      }
    } else {
      echo ('
          <tr>
            <td style="width: 20%; text-shadow: 0.5px 0.5px;">Cluster</td>
            <td style="width: 60%; text-shadow: 0.5px 0.5px;">Capability</td>
            <td style="width: 20%; text-shadow: 0.5px 0.5px;">State</td>
          </tr>
        ');
      foreach ($this->clusters as $cluster => $capabilities) {
        foreach ($capabilities as $name => $state) {
          echo ('
            <tr>
              <td>'.$cluster.'</td>
              <td>'.$name.'</td>
              <td><span style="color:'.clusterer_capabilities_widget::cap_state[$state][1].';">'.clusterer_capabilities_widget::cap_state[$state][0].'</span></td>
            </tr>');
        }
      }
    }
    echo('</table>');
    # 3 table rows fit one widget row
    $this->sizeY = ($this->count + 1)/2 + 1;
  }

  function update() {
    require_once("../../../common/mi_comm.php");
    $clusters_res = mi_command("clusterer_list_cap", array(), $this->widget_box['mi_conn'], $errors);
    if (count($errors) != 0) {
      error_log(print_r($errors, true));
      $this->set_status(widget::STATUS_CRIT);
      return;
    }
    $status = widget::STATUS_OK;
    foreach($clusters_res['Clusters'] as $cluster) {
      if ($this->cluster != null && $this->cluster != $cluster['cluster_id'])
        continue;
      $cluster_caps = array();
      foreach($cluster['Capabilities'] as $cap) {
        if ($cap['enabled'] != "yes") {
          $cluster_caps[$cap['name']] = 2;
          if ($status != widget::STATUS_CRIT)
            $status = widget::STATUS_WARN;
        } else if (strtolower($cap['state']) != "ok") {
          $cluster_caps[$cap['name']] = 1;
          $status = widget::STATUS_CRIT;
        } else {
          $cluster_caps[$cap['name']] = 0;
        }
        $this->count++;
      }
      $this->clusters[$cluster['cluster_id']] = $cluster_caps;
      if ($this->cluster != null)
        break;
    }
    $this->set_status($status);
  }

  function echo_content() {
    $this->display_test();
  }

  public static function fetch_box_clusters($params) {
    $errors = [];
    $mi_box = self::get_box($params);
    if ($mi_box == null)
      return array();
    require_once("../../../common/mi_comm.php");
    $clusters_ret = mi_command("clusterer_list_cap", null, $mi_box['mi_conn'], $errors);
    if (count($errors) != 0) {
      error_log(print_r($errors, true));
      return array();
    }
    $ret = array();
    foreach ($clusters_ret['Clusters'] as $cluster) {
      $ret[] = $cluster['cluster_id'];
    }
    sort($ret);
    return $ret;
  }

  public static function cluster_box_selection($cluster) {
    echo ('
            <script>
            var box_select = document.getElementById("widget_box");
            box_select.addEventListener("change", function(){update_clusters();}, false);
            var init_cluster = "'.$cluster.'";
            function update_clusters() {
                var box_select = document.getElementById("widget_box");
                var selected_box = box_select.value;
                var cluster_select = document.getElementById("widget_cluster"); 
                cluster_select.options.length = 1;
                cluster_select.selectedIndex = 0;
                fetch_widget_info("clusterer_capabilities_widget", "fetch_box_clusters", "widget_box="+selected_box)
                .then(data => {
                  data.forEach(cluster => {
                    var opt = document.createElement("option");
                    opt.value = cluster;
                    opt.textContent = cluster;
                    cluster_select.appendChild(opt);
                    if (init_cluster == cluster)
                      cluster_select.selectedIndex = cluster_select.options.length - 1;
                  });
                  init_cluster = "";
                });
            };
            update_clusters();
            </script>
        ');
  }

  public static function new_form($params = null) {
    $boxes_info = self::get_boxes();
    if (!isset($params['widget_name']))
      $params['widget_name'] = "Cluster Capabilities";
    if (!isset($params['widget_box']))
      $params['widget_box'] = $boxes_info[0][0];
    if (!isset($params['widget_cluster']))
      $params['widget_cluster'] = "";
    if (!isset($params['widget_refresh']))
      $params['widget_refresh'] = 60;
    form_generate_input_text("Name", null, "widget_name", null, $params['widget_name'], 20,null);
    form_generate_select("Box", null, "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
    form_generate_select("Cluster", "Cluster to track capabilities for; Empty means all clusters are considered", "widget_cluster", null, $params['widget_cluster'], array(), null, true);
    form_generate_input_text("Refresh period", "Period (in seconds) when the widget should update", "widget_refresh", "n", $params['widget_refresh'], 20, '^([0-9]\+)$');
    self::cluster_box_selection($params['widget_cluster']);
  }

  static function get_description() {
    return "Shows the status of the capabilities (sync'ed or not) from a certain cluster. The information is provided from the perspective of the node/Box the CP is connecting to via MI.";
  }
}

// vim:set sw=2 ts=2 et ft=php fdm=marker:
?>
