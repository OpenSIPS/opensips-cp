<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>CodePen - Packery - jQuery UI Draggable</title>
<link href="../../../style_tools.css" type="text/css" rel="StyleSheet">
  <meta name="viewport" content="width=device-width, initial-scale=1"><link rel='stylesheet' href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css'><link rel="stylesheet" href="./css/style.css">

</head>
<body>
<!-- partial:index.partial.html -->
<h1>marele dashboard</h1>

<div class="grid">
  <div class="grid-item grid-item--width2 rotund"></div>
  <div class="grid-item grid-item--height2 rotund"></div>
  <div class="grid-item rotund"></div>
  <div class="grid-item rotund"></div>
  <div class="grid-item grid-item--width2 grid-item--height2 grafik grafikrotund"><?php show_graph("load:load",0); ?></div>
  <div class="grid-item "></div>
  <div class="grid-item grid-item--width2"></div>
  <div class="grid-item grid-item--height2"></div>
  <div class="grid-item"></div>
  <div class="grid-item grid-item--width2 rotund"></div>
  <div class="grid-item grid-item--height2"></div>
  <div class="grid-item"></div>
  <div class="grid-item"></div>
  <div class="grid-item grid-item--width2 grid-item--height2"><?php show_boxes_tool(); ?></div>
  <div class="grid-item"></div>
  <div class="grid-item grid-item--width2"></div>
  <div class="grid-item grid-item--height2"></div>
  <div class="grid-item"></div>
</div>
<!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js'></script>
<script src='https://unpkg.com/packery@2/dist/packery.pkgd.js'></script>
<script>
  // external js: packery.pkgd.js, jquery-ui-draggable.js

// initialize Packery
var $grid = $('.grid').packery({
  itemSelector: '.grid-item',
  // columnWidth helps with drop positioning
  columnWidth: 100
});

// make all items draggable
var $items = $grid.find('.grid-item').draggable();
// bind drag events to Packery
$grid.packery( 'bindUIDraggableEvents', $items );
</script>
</body>
</html>
<?php
session_start();

function show_graph($stat,$box_id){
  require("../../../common/cfg_comm.php");
  session_load_from_tool("smonitor", 0);
	global $config;
	global $gauge_arr;
	$var = $stat;
	require("../../../../config/tools/system/smonitor/db.inc.php");
	require("../../../../config/db.inc.php");
	require("db_connect.php");

	$_SESSION['charting_url'] = $url;
	$_SESSION['full_stat'] = $var;
	$_SESSION['stat'] = str_replace(':', '', $stat);
	$_SESSION[str_replace(':', '', $stat)] = $row;
	$_SESSION['sampling_time'] = get_settings_value_from_tool("sampling_time", "smonitor", $box_id);
	$_SESSION['chart_size'] = get_settings_value_from_tool("chart_size", "smonitor",$box_id);
	$_SESSION['box_id_graph'] = $box_id;
	$_SESSION['chart_history'] = get_settings_value_from_tool("chart_history", "smonitor",$box_id);
	$_SESSION['tmonitoring'] = get_settings_value_from_tool("table_monitoring", "smonitor",$box_id);

		$_SESSION['normal'] = 1;
	

	require("lib/d3js.php");

}

function show_boxes_tool() {
  echo ('<iframe src="../boxes_config/index.php" height="290" width="650" title="description"></iframe>');
}


?>