<?php
 /*
 * Copyright (C) 2011 OpenSIPS Project
 *
 * This file is part of opensips-cp, a free Web Control Panel Application for 
 * OpenSIPS SIP server.
 *
 * opensips-cp is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * opensips-cp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

 if (is_null($panel_id)) {
     ?> 
     <h1> DASHBOARD 0.1</h1>
     <?php 
 } else { ?>
<form action="<?=$page_name?>?action=add_widget&panel_id=<?=$panel_id?>" method="post">
 <?php if (!$_SESSION['read_only']) echo('<input type="submit" name="add_new" value="Add New Widget" class="formButton add-new-btn">') ?>
</form>


<head>
    <title>Demo &raquo; dashboard </title>
<link href="../../../style_tools.css" type="text/css" rel="StyleSheet">
    <link rel="stylesheet" type="text/css" href="css/demo.css">
    <link rel="stylesheet" type="text/css" href="css/jquery.gridster.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="jquery.gridster.min.js" type="text/javascript" charset="utf-8"></script>

</head>

<body>
<h1></h1>

<div class="gridster"  >
    <ul>
        <li data-row="1" data-col="1" data-sizex="1" data-sizey="1">
            <header>|||</header>Default1</li>
        <li data-row="1" data-col="2"  data-sizex="1" data-sizey="1" class="grafik" id = "ugabuga">
            <header>|||</header>Default2</li>
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
    var action = "<?=$action?>";
    var widget_info = "<?=$widget_info?>";
    gridster = $(".gridster > ul").gridster({
            widget_margins: [7, 7],
            widget_base_dimensions: [100, 100],
            shift_widgets_up: false,
            shift_larger_widgets_down: false,
            max_cols: 7,
            max_rows: 7,
            collision: {
                wait_for_mouseup: true
            },
            resize: {
                enabled: true
            }
        }).data('gridster');

  //      $.each(widgets, function (i, widget) {
  //          gridster.add_widget.apply(gridster, widget)
  //      });
         
         if (action == "add_widget_verify") {
            var wi = <?php echo json_encode($_POST); ?>;
            addWidget(gridster,wi['widget_title'], wi['widget_content'], wi['widget_id'], Number(wi['widget_sizex']), Number(wi['widget_sizey']));
         }
</script>

</body>




<?php  
if ($_SESSION['config']['panels'][$panel_id]['content'] != null) {
    ?>
    <script>
            console.log(<?php echo json_encode($_SESSION['config']['panels'][$panel_id]['content']); ?>);
    </script>
    <?php
}
} ?>