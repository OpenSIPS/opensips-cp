<?php
require_once(__DIR__."/../../../../system/dashboard/template/widget/widget.php");

class cdr_widget extends widget
{
	public $total_cdrs;

	function __construct($array) {
    if (isset($array['widget_refresh']) && $array['widget_refresh'] != '')
      $r = intval($array['widget_refresh']) * 1000;
    else
      $r = 60000; # one minute is the default
		parent::__construct($array['panel_id'], $array['widget_name'], 2, 3, $array['widget_name'], $r);
		$this->set_cdr_entries();
	}

	function get_name() {
		return "CDR widget";
	}

	function display_test() {
    echo ('
      <table style="table-layout: fixed;
        width: 90%; height:20px; margin: auto; margin-left: 10px; font-weight: bolder;" cellspacing="2" cellpadding="2" border="0">
      ');
    echo ('
      <tr><td class="rowEven" width="55%">Today: </td><td style="text-align: right;">'.$this->today_cdrs.'</td></tr>
      <tr><td class="rowEven">Yesterday: </td><td style="text-align: right;">'.$this->yesterday_cdrs.'</td></tr>
      <tr><td class="rowEven">Last 7 days: </td><td style="text-align: right;">'.$this->last_week_cdrs.'</td></tr>
      <tr><td class="rowEven">Prev 7 days: </td><td style="text-align: right;">'.$this->prev_week_cdrs.'</td></tr>
      <tr><td class="rowEven" width="40%">Total: </td><td style="text-align: right;">'.$this->total_cdrs.'</td></tr>');
    echo('</table>');
	}

	function echo_content() {
		$this->display_test();
	}

	function set_cdr_entries() {
    session_load_from_tool("cdrviewer");
		require(__DIR__."/../../lib/db_connect.php");
		$cdr_table = get_settings_value_from_tool("cdr_table", "cdrviewer");
    $sql = "select count(*) from ".$cdr_table. " union ".
      "select count(*) from ".$cdr_table." where time > curdate() union ".
      "select count(*) from ".$cdr_table." where time > curdate() - interval 1 day and time < now() - interval 1 day union ".
      "select count(*) from ".$cdr_table." where time < NOW() and time > NOW() - interval 1 week union ".
      "select count(*) from ".$cdr_table." where time < NOW() - interval 1 week and time > NOW() - interval 2 week;";
		$stm = $link->prepare($sql);
		$stm->execute();
		$rows = $stm->fetchAll(PDO::FETCH_ASSOC);
		$this->total_cdrs = $rows[0]['count(*)'];
		$this->today_cdrs = $rows[1]['count(*)'];
		$this->yesterday_cdrs = $rows[2]['count(*)'];
		$this->last_week_cdrs = $rows[3]['count(*)'];
		$this->prev_week_cdrs = $rows[4]['count(*)'];
	}

	public static function new_form($params = null) { 
		if (!$params['widget_name'])
			$params['widget_name'] = "CDR";
		form_generate_input_text("Name", "", "widget_name", null, $params['widget_name'], 20,null);
    form_generate_input_text("Refresh period", "Period (in seconds) when the widget should update", "widget_refresh", "y", $params['widget_refresh'], 20, '^([0-9]\+)$');
	}

  static function get_description() {
    return "Shows the various CDR countings (from the acc table) on different time intervals (as history)";
  }

}
// vim:set sw=2 ts=2 et ft=php fdm=marker:
?>
