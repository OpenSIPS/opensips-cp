<?php
require_once(__DIR__."/../../../../system/dashboard/template/widget/widget.php");

class clusterer_nodes_widget extends widget
{
  public $active = 0;
  public $inactive = 0;
  public $probing = 0;
  public $widget_box;
  public $clusters;

  const node_state_color = array("green", "orange", "red");

  function __construct($array) {
    $r = intval($array['widget_refresh']) * 1000;
    parent::__construct($array['panel_id'], $array['widget_name'], 4,3, $array['widget_name'],$r);
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
    return "Clusterer Nodes";
  }

  function get_desc($info) {
    if ($info['desc'] == $info['id'])
      return $info['desc'];
    return '<div class="tooltip">
      <sup style="font-size: 13px;">'.$info['desc'].'</sup>
      <span style="top:-50px;  pointer-events: none;" class="tooltiptext">Node ID: '.$info['id'].'</span>
      </div>';
  }

  function display_test() {
    echo ('
        <table style="table-layout: fixed;
          width: '.($this->sizeX * 90).'px; height:20px; margin: auto; margin-left: 10px; font-weight: bolder;" cellspacing="2" cellpadding="2" border="0">'
    );
    if ($this->cluster) {
      echo ('
          <tr>
            <td style="width: 80%; text-shadow: 0.5px 0.5px;">Node</td>
            <td style="width: 20%; text-shadow: 0.5px 0.5px;">State</td>
          </tr>
        ');
      foreach ($this->clusters[$this->cluster] as $info) {
        echo ('
          <tr>
            <td>'.$this->get_desc($info).'</td>
            <td><span style="color:'.$info["color"].';">'.$info["info"].'</span></td>
          </tr>');
      }
    } else {
      echo ('
          <tr>
            <td style="width: 20%; text-shadow: 0.5px 0.5px;">Cluster</td>
            <td style="width: 65%; text-shadow: 0.5px 0.5px;">Node</td>
            <td style="width: 15%; text-shadow: 0.5px 0.5px;">State</td>
          </tr>
        ');
      foreach ($this->clusters as $cluster => $details) {
        foreach ($details as $info) {
          echo ('
            <tr>
              <td>'.$cluster.'</td>
              <td>'.$this->get_desc($info).'</td>
              <td><span style="color:'.$info["color"].';">'.$info["info"].'</span></td>
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
    $clusters_res = mi_command("clusterer_list", array(), $this->widget_box['mi_conn'], $errors);
    if (count($errors) != 0) {
      error_log(print_r($errors, true));
      $this->set_status(widget::STATUS_CRIT);
      return;
    }
    $status = widget::STATUS_OK;
    foreach($clusters_res['Clusters'] as $cluster) {
      if ($this->cluster != null && $this->cluster != $cluster['cluster_id'])
        continue;
      $this->clusters[$cluster['cluster_id']] = array();
      foreach($cluster['Nodes'] as $node) {
        if ($node['state'] != "enabled") {
          $state = 1; /* disabled */
          $info = "disabled";
        } else {
          $info = $node['link_state'];
          switch (strtolower($info)) {
          case 'up': $state = 0; break;
          case 'down': $state = 2; break;
          default: $state = 1; break;
          }
        }
        switch($state) {
        case 2:
          $status = widget::STATUS_WARN;
          break;
        case 1:
          if ($status != widget::STATUS_CRIT)
            $status = widget::STATUS_WARN;
          break;
        default:
          break;
        }
        $this->clusters[$cluster['cluster_id']][] = array(
          "id"=>$node['node_id'],
          "desc"=>((isset($node['description']) && $node['description'] != '')?$node['description']:$node['node_id']),
          "info"=>$info,
          "color"=>clusterer_nodes_widget::node_state_color[$state],
        );
        $this->count++;
      }
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
    $clusters_ret = mi_command("clusterer_list_topology", null, $mi_box['mi_conn'], $errors);
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
                fetch_widget_info("clusterer_nodes_widget", "fetch_box_clusters", "widget_box="+selected_box)
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
      $params['widget_name'] = "Cluster Nodes";
    if (!isset($params['widget_box']))
      $params['widget_box'] = $boxes_info[0][0];
    if (!isset($params['widget_cluster']))
      $params['widget_cluster'] = "";
    if (!isset($params['widget_refresh']))
      $params['widget_refresh'] = 60;
    form_generate_input_text("Name", null, "widget_name", null, $params['widget_name'], 20,null);
    form_generate_select("Box", null, "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
    form_generate_select("Cluster", "Cluster to track nodes for", "widget_cluster", null, $params['widget_cluster'], array(), null, true);
    form_generate_input_text("Refresh period", "Period (in seconds) when the widget should update", "widget_refresh", "n", $params['widget_refresh'], 20, '^([0-9]\+)$');
    self::cluster_box_selection($params['widget_cluster']);
  }

  static function get_description() {
    return "Shows the status of the nodes of a given cluster - which nodes are up or down. The status info is provided from the perspective of a node/Box the CP is connecting to via MI";
  }
}

// vim:set sw=2 ts=2 et ft=php fdm=marker:
?>
