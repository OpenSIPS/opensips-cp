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
 
 <a href=# onclick="lockPanel()" style="position:relative; left:110px; top:2px; content: url('../../../images/share/inactive.png');"></a>
<form action="<?=$page_name?>?action=add_widget&panel_id=<?=$panel_id?>" method="post">
 <?php if (!$_SESSION['read_only']) echo('<input type="submit" name="add_new" value="Add New Widget" class="formButton add-new-btn">') ?>
</form>


<head>
<link href="../../../style_tools.css" type="text/css" rel="StyleSheet">
    <link rel="stylesheet" type="text/css" href="css/demo.css">
    <link rel="stylesheet" type="text/css" href="css/jquery.gridster.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="jquery.gridster.min.js" type="text/javascript" charset="utf-8"></script>

</head>

<body>

<div class="gridster"  >
    <ul>
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
            widget_base_dimensions: [90, 90],
            shift_widgets_up: false,
            shift_larger_widgets_down: false,
            collision: {
                wait_for_mouseup: true
            },
            resize: {
                enabled: true
            },
            serialize_params: function($w, wgd) {console.log(wgd);
                    return { 
                           id: $w.attr('id'),
                           col: wgd.col,
                           row: wgd.row, 
                           size_x: wgd.size_x, 
                           size_y: wgd.size_y 
                    };
            },
            draggable: { 
                stop: function (e, ui, $widget) {
                    var positions = gridster.serialize();
                    positions.push (<?=$panel_id?>);
                    store_dashboard(positions);
                }
            }
        }).data('gridster');

  //      $.each(widgets, function (i, widget) {
  //          gridster.add_widget.apply(gridster, widget)
  //      });
         
         if (action == "add_widget_verify") { 
            var wi = <?php echo json_encode($widget_array); ?>;
            addWidget(gridster,wi[0], Number(wi[1]), Number(wi[2]));
            //move( "ugabuga", "hodoronc");
         }
</script>

</body>



<?php

/*

if ($_SESSION['config']['panels'][$panel_id]['widgets']['positions'] != null) {
    //consoole_log($_SESSION['config']['panels']);
    ?>
    <script>
        var e;
        var stored_widgets = JSON.parse(<?php echo json_encode($_SESSION['config']['panels'][$panel_id]['widgets']['positions']); ?>);
            
            //console.log(stored_widgets);
            stored_widgets.forEach(element => 
            {
                //   move( "chart_".concat(element.id), element.id);
                    gridster.add_widget("<li/>", 2, 2, element.col, element.row);
                
            });
    </script>
    
    <?php
}
*/
if ($_SESSION['config']['panels'][$panel_id]['content'] != null) {
 foreach ($_SESSION['config']['panels'][$panel_id]['widgets'] as $widget)
 { 
    $widget_content = json_decode($widget['content'], true);
    $widget_id = $widget_content['widget_id'];
    $widget_positions = json_decode($widget['positions'], true);
    $new_widget = new $widget_content['widget_type']($widget_content);
    $new_widget->set_id($widget_content['widget_id']);
    $widget_array = $new_widget->get_as_array();
    $_SESSION['test_dashboard'] = $widget_id;
    $new_widget->echo_content();
     ?>
<script>
    var widget_content = <?php echo json_encode($widget_array); ?>;
    var widget_type = <?php echo json_encode($widget_content['widget_type']);?>;
    var widget_positions = <?php echo json_encode($widget_positions); ?>;
    
    var col, row;
    var sizeX = Number(widget_content[1]);
    var sizeY = Number(widget_content[2]);
    if (widget_positions) {
        col = widget_positions.col;
        row = widget_positions.row;
        sizeX= widget_positions.size_x;
        sizeY = widget_positions.size_y;
    }
    addWidget(gridster,widget_content[0], sizeX, sizeY, col, row);
    move(widget_positions.id.concat("_old"), widget_positions.id);
</script>
     <?php
 }
}
} ?>