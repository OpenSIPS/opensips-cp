<?php
require_once(__DIR__."/../../../../system/dashboard/template/widget/widget.php");

class clusterer_shtags_widget extends widget
{
  public $active = 0;
  public $inactive = 0;
  public $probing = 0;
  public $system;
  public $clusters;

  const tag_state_color = array("black", "green", "orange", "red");

  function __construct($array) {
    $r = intval($array['widget_refresh']) * 1000;
    parent::__construct($array['panel_id'], $array['widget_name'], 3,3, $array['widget_name'],$r);
    $this->set_status(widget::STATUS_OK);
    if (isset($array['widget_cluster']) && $array['widget_cluster'] != '')
      $this->cluster = $array['widget_cluster'];
    else
      $this->cluster = null;
    $this->system = self::get_system($array);
    $this->system_id = $array['widget_system'];
    $this->boxes = array();
    $this->shtags = array();
    $this->count = 0;
    if ($this->system == null)
      $this->set_status(widget::STATUS_CRIT);
    else
      $this->update();
  }

  public static function get_system($params) {
    if (!isset($params['widget_system']))
      return null;
    if (!isset($_SESSION['systems'][$params['widget_system']]))
      return null;
    return $_SESSION['systems'][$params['widget_system']];
  }


  function get_name() {
    return "Clusterer Sharing Tags";
  }

  function display_test() {
    echo ('
        <table style="table-layout: fixed;
          width: '.($this->sizeX * 90).'px; height:20px; text-align: center; margin: auto; margin-left: 10px; font-weight: bolder;" cellspacing="2" cellpadding="2" border="0">'
    );
    echo ('
        <tr>
          <td style="width: 20%; text-shadow: 0.5px 0.5px;">Shtag</td>');
    # we need to split the width equally within the boxes
    $width = 80 / count($this->boxes);
    foreach ($this->boxes as $box => $shtags) {
      echo ('<td style="width: '.$width.'%; text-shadow: 0.5px 0.5px;">'.$box.'</td>');
    }
    echo ('
        </tr>');
    foreach ($this->shtags as $shtag=>$state) {
      echo ('<tr><td>'.$shtag.'</td>');
      foreach ($this->boxes as $box=>$shtags) {
        if ($state) {
          /* everything ok, active should be green, orange unknown */
          if (!isset($shtags[$shtag]))
            $c = 2;
          else if ($shtags[$shtag] == 'active')
            $c = 1;
          else
            $c = 0;
        } else {
          if (!isset($shtags[$shtag]))
            $c = 2;
          else
            $c = 3;
        }
        echo('<td><span style="color:'.clusterer_shtags_widget::tag_state_color[$c].'";>'.$shtags[$shtag].'</span></td>');
      }
      echo ('</tr>');
    }
    echo('</table>');
    # 3 table rows fit one widget row
    $this->sizeY = max((count($this->shtags) + 1)/2 + 1, 2);
  }

  function update() {
    require_once("../../../common/mi_comm.php");
    $status = widget::STATUS_OK;
    foreach ($_SESSION['boxes'] as $box) {
      if ($box['assoc_id'] != $this->system_id)
        continue;
      $tags = array();
      $errors = array();
      $clusters_res = mi_command("clusterer_list_shtags", null, $box['mi_conn'], $errors);
      if (count($errors) != 0) {
        error_log(print_r($errors, true));
        $status = widget::STATUS_CRIT;
      } else {
        foreach ($clusters_res as $shtag) {
          if ($this->cluster != null && $shtag['Cluster'] != $this->cluster)
            continue;
          $name = $shtag['Tag'].'/'.$shtag['Cluster'];
          if (!in_array($name, $this->shtags))
            $this->shtags[$name] = false;
          $tags[$name] = $shtag['State'];
        }
      }
      $this->boxes[$box['name']] = $tags;
    }
    /* verify state */
    foreach ($this->shtags as $shtag=>$state) {
      $active = 0;
      foreach ($this->boxes as $box=>$shtags) {
        if ($shtags[$shtag] == "active")
          $active++;
      }
      if ($active == 1)
        $this->shtags[$shtag] = true;
      if (!$this->shtags[$shtag])
        $status = widget::STATUS_CRIT;
    }
    $this->set_status($status);
  }

  function echo_content() {
    $this->display_test();
  }

  public static function fetch_system_clusters($params) {
    $errors = [];
    $system = self::get_system($params);
    if ($system == null)
      return array();
    require_once("../../../common/mi_comm.php");
    $ret = array();
    foreach ($_SESSION['boxes'] as $box) {
      if ($box['assoc_id'] != $params['widget_system'])
        continue;
      $errors = array();
      $clusters_ret = mi_command("clusterer_list", null, $box['mi_conn'], $errors);
      if (count($errors) == 0) {
        foreach ($clusters_ret['Clusters'] as $cluster) {
          if (!in_array($cluster['cluster_id'], $ret))
            $ret[] = $cluster['cluster_id'];
        }
      } else {
        error_log(print_r($errors, true));
      }
    }
    sort($ret);
    return $ret;
  }

  public static function cluster_system_selection($cluster) {
    echo ('
            <script>
            var sys_select = document.getElementById("widget_system");
            sys_select.addEventListener("change", function(){update_clusters();}, false);
            var init_cluster = "'.$cluster.'";
            function update_clusters() {
                var sys_select = document.getElementById("widget_system");
                var selected_sys = sys_select.value;
                var cluster_select = document.getElementById("widget_cluster"); 
                cluster_select.options.length = 1;
                cluster_select.selectedIndex = 0;
                fetch_widget_info("clusterer_shtags_widget", "fetch_system_clusters", "widget_system="+selected_sys)
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
    $systems = array();
    foreach ($_SESSION['systems'] as $id=>$system) {
      $systems[$id] = $system['name'];
    }
    if (!isset($params['widget_name']))
      $params['widget_name'] = "Cluster shtags";
    if (!isset($params['widget_system']))
      $params['widget_system'] = key($systems);
    if (!isset($params['widget_cluster']))
      $params['widget_cluster'] = "";
    if (!isset($params['widget_refresh']))
      $params['widget_refresh'] = 60;
    form_generate_input_text("Name", null, "widget_name", null, $params['widget_name'], 20,null);
    form_generate_select("System", null, "widget_system", null,  $params['widget_system'], array_keys($systems), array_values($systems));
    form_generate_select("Cluster", "Cluster to filter sharing tags; if empty, all sharing tags withn all clusters are consudered", "widget_cluster", null, $params['widget_cluster'], array(), null, true);
    form_generate_input_text("Refresh period", "Period (in seconds) when the widget should update", "widget_refresh", "n", $params['widget_refresh'], 20, '^([0-9]\+)$');
    self::cluster_system_selection($params['widget_cluster']);
  }

  static function get_description() {
    return "Shows the status of the sharing tags (active or not) from a certain cluster. The information is provided from the perspective of the node/Box the CP is connecting to via MI";
  }
}

// vim:set sw=2 ts=2 et ft=php fdm=marker:
?>
