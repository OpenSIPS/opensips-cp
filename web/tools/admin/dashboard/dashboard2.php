<!doctype html>
<html>
<head>
    <title>Demo &raquo; dashboard </title>
<link href="../../../style_tools.css" type="text/css" rel="StyleSheet">
    <link rel="stylesheet" type="text/css" href="css/demo.css">
    <link rel="stylesheet" type="text/css" href="css/jquery.gridster.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="jquery.gridster.min.js" type="text/javascript" charset="utf-8"></script>

</head>

<body>

<button onclick="move()" id="button">:)</button>
<div id = "hodoronctronc">
<?php show_graph("load:load", 0); ?>
</div>
<h1>dashboard</h1>



<div class="gridster">
    <ul>
        <li data-row="1" data-col="1" data-sizex="1" data-sizey="1">
            <header>LALALA</header>0</li>
        <li data-row="1" data-col="5"  data-sizex="4" data-sizey="4" class="grafik" id = "ugabuga">
            <header>|||</header>5</li>
    </ul>
</div>
<style type="text/css">

    .gridster li header {
        background: #999;
        display: block;
        font-size: 20px;
        line-height: normal;
        padding: 4px 0 6px;
        margin-bottom: 20px;
        cursor: move;
    }

</style>

<script type="text/javascript" id="code">
    var gridster;

    $(function () {

        gridster = $(".gridster > ul").gridster({
            widget_margins: [5, 5],
            widget_base_dimensions: [165, 90],
            max_cols: 9,
            resize: {
                enabled: true
            }
        }).data('gridster');

  //      $.each(widgets, function (i, widget) {
  //          gridster.add_widget.apply(gridster, widget)
  //      });

    });

move();
function move() {
    var newParent = document.getElementById('ugabuga');
    var oldParent = document.getElementById('hodoronctronc');
    console.log(oldParent);
  while (oldParent.childNodes.length > 0) {
    newParent.appendChild(oldParent.childNodes[0]);
  }
}
</script>

</body>
</html>

<?php
session_start();
show_boxes_widgets();

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

function consoole_log( $data ){
	echo '<script>';
	echo 'console.log('. json_encode( $data ) .')';
	echo '</script>';
  } //  DE_STERS
function show_boxes_widgets() {
    require("../../../../config/boxes.global.inc.php");
    consoole_log($_SESSION);
    foreach($_SESSION['boxes'] as $box) {
        consoole_log("ee");
            ?>
        <script type="text/javascript" id="code">
            var widget = ['<li><header>|||</header><?=$box['mi_conn']?></li>', 1, 1];
            var gridster = $(".gridster ul").gridster({
            widget_margins: [5, 5],
            widget_base_dimensions: [165, 90],
            max_cols: 9,
            resize: {
                enabled: true
            }
        }).data('gridster');
            gridster.add_widget.apply(gridster, widget)
            </script>
            <?php
    }
}
?>