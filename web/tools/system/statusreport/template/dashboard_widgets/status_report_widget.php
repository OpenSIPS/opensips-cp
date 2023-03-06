<?php
require_once(__DIR__."/../../../../system/dashboard/template/widget/widget.php");

class status_report_widget extends widget
{
  public $mi_group;
  public $mi_id;
  public $status;
  public $widget_box;

  function __construct($array) {
    if (isset($array['widget_refresh']) && $array['widget_refresh'] != '')
      $r = intval($array['widget_refresh']) * 1000;
    else
      $r = 60000; # one minute is the default
    parent::__construct($array['panel_id'], $array['widget_name'], 2,2, $array['widget_name'], $r);
    $this->set_status(widget::STATUS_OK);
    $this->widget_box = self::get_box($array);
    if ($this->widget_box == null) {
      $this->set_status(widget::STATUS_CRIT);
      return;
    }
    $group_identifier = explode("/", $array["widget_identifier"]);
    $this->mi_group = $group_identifier[0];
    $this->mi_id = $group_identifier[1];
    $this->set_params();
  }

  function get_name() {
    return "Status widget";
  }

  function display_test() {
    echo('
      <table style="table-layout: fixed;
        width: 160px; height:20px; margin: auto; margin-left: 10px; font-weight: bolder;" cellspacing="2" cellpadding="1" border="0">
    ');
    echo('<tr><td class="rowOdd" style="text-align: center">'.$this->mi_group.' / '.$this->mi_id.'</td></tr>');
    echo('<tr><td class="rowOdd" style="text-align: center; font-weight: 900; color:'.($this->status['Readiness']?"green":"red").'">'.$this->status['Details'].'<span style="color:black;"> ('.$this->status['Status'].')</span></td></tr>');
    echo('</table>');
  }

  function set_params() {
    require_once("../../../common/mi_comm.php");
    $group = $this->mi_group;
    $identifier = $this->mi_id;
    $params = [];
    $params["group"] = $group;
    if ($identifier)
      $params["identifier"] = $identifier;
    $stat_res = mi_command("sr_get_status", $params, $this->widget_box['mi_conn'], $errors);
    $this->status = $stat_res;
    if (!$this->status['Readiness'])
      $this->set_status(widget::STATUS_CRIT);
    if (!isset($this->status['Details']) || $this->status['Details'] == '')
      $this->status['Details'] = 'n/a';
  }

  public static function box_selection($identifiers_list, $init) {
    $ilist = json_encode($identifiers_list);
    echo ('
            <script>
            var ilist = '.$ilist.';
            var init = '.$init.'
            var box_select = document.getElementById("widget_box");
            box_select.addEventListener("change", function(){update_options();}, false);
            function update_options() {
                var box_select = document.getElementById("widget_box");
                var selected_box = box_select.value;
                var identifier_select = document.getElementById("widget_identifier"); 
                identifier_select.options.length = 0;
                if (ilist[selected_box])  {
                    ilist[selected_box].forEach(element => {
                        var opt = document.createElement("option");
                        opt.value = element;
                        opt.textContent = element;
                        identifier_select.appendChild(opt);
                    });
                }
            } if (init == 1) update_options();
            </script>
    ');
  }

  static function get_identifiers_options() {
    foreach($_SESSION['boxes'] as $id => $box) {
      $stat_res = mi_command("sr_list_identifiers", array(), $box['mi_conn'], $errors);
      foreach($stat_res as $group) {
        foreach($group["Identifiers"] as $identifier)
          $identifiers[$box['id']][] = $group["Group"]."/".$identifier;
      }
    }
    return $identifiers;
  }

  function echo_content() {
    $this->display_test();
  }

  public static function new_form($params = null) {
    $boxes_info = self::get_boxes();
    if (is_null($params))
      $init = 1;
    else $init = 0;
    if (!$params['widget_name'])
      $params['widget_name'] = "Status";
    $identifiers_list = self::get_identifiers_options();
    $options = (!$init)?$identifiers_list[$params['widget_box']]:$identifiers_list[0];
    form_generate_input_text("Name", "", "widget_name", "n", $params['widget_name'], 20,null);
    form_generate_select("Box", "", "widget_box", null,  $params['widget_box'], $boxes_info[0], $boxes_info[1]);
    form_generate_select("Group/ID", "", "widget_identifier", null, $params['widget_identifier'], $options);
    form_generate_input_text("Refresh period", "Period (in seconds) when the widget should update", "widget_refresh", "y", $params['widget_refresh'], 20, '^([0-9]\+)$');
    self::box_selection($identifiers_list, $init);
  }

  static function get_description() {
    return "Display the Status of a given OpenSIPS SR indentifier.";
  }
}
// vim:set sw=2 ts=2 et ft=php fdm=marker:
?>
