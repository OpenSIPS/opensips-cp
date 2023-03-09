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
?>
<head>
<link href="../../../style_tools.css" type="text/css" rel="StyleSheet">
    <link rel="stylesheet" type="text/css" href="css/widget.css">
    <link rel="stylesheet" type="text/css" href="css/jquery.gridster.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="jquery.gridster.min.js" type="text/javascript" charset="utf-8"></script>

</head>
<body>

<?php
 if (is_null($panel_id)) {
     ?> 
     <h1> DASHBOARD 0.1</h1>
     <?php 
 } else { ?>
 
</td>
 </tr>
</table>
 <table style="position:relative;  bottom :50px; " id="panel_buttons">
     <tr><td>
 <form action="<?=$page_name?>?action=add_widget&panel_id=<?=$panel_id?>" method="post">
 <?php csrfguard_generate();
 if (!$_SESSION['read_only']) echo('<input type="submit" name="add_new" value="Add New Widget" class="formButton add-new-btn">') ?>
</form></td><td>
<form action="<?=$page_name?>?action=import_widget&panel_id=<?=$panel_id?>" method="post">
 <?php csrfguard_generate();
 if (!$_SESSION['read_only']) echo('<input type="submit" name="add_new" value="Import New Widget" class="formButton add-new-btn">') ?>
</form></td></tr></table>

</center>

<div class="gridster"  >
    <ul>
    </ul>
</div>

<script type="text/javascript" id="code">
	document.getElementById('lockButton').style.display = 'block'; //button is initially hidden
	// to avoid seeing image loading when it hasnt cached yet. should find workaround

    var gridster;
    var action = "<?=$action?>";
    gridster = $(".gridster > ul").gridster({
            widget_base_dimensions: [87, 40],
            shift_widgets_up: false,
            shift_larger_widgets_down: false,
            collision: {
                wait_for_mouseup: true
            },
            resize: {
                enabled: true
            },
            serialize_params: function($w, wgd) {
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
         
</script>

</body>



<?php

if ($_SESSION['config']['panels'][$panel_id]['content'] != null) {
 foreach ($_SESSION['config']['panels'][$panel_id]['widgets'] as $widget)
 { 	
    $widget_content = json_decode($widget['content'], true);
    $widget_id = $widget_content['widget_id'];
    $widget_positions = json_decode($widget['positions'], true);
    if (!class_exists($widget_content['widget_type']))
	    continue;
    $new_widget = new $widget_content['widget_type']($widget_content);
    $new_widget->set_id($widget_content['widget_id']);
    $widget_array = $new_widget->get_as_array();//this returns info of widget as array
    $new_widget->display_widget();
	/*
	widget_positions is fetched from db. The following lines ignore the db widget sizes,
	to allow sizes to be modified in the widget class. Otherwise you can't tell if you
	should use the class size or the db size. To re-enable widget manual resizing the
	db sizes should be included in the widget constructor, and handled there. TODO
	*/
	$widget_positions['size_y'] = $new_widget->sizeY;
	$widget_positions['size_x'] = $new_widget->sizeX;
	
     ?>
<script>
    var widget_info = <?php echo json_encode($widget_array); ?>;
    var widget_type = <?php echo json_encode($widget_content['widget_type']);?>;
    var widget_positions = <?php echo json_encode($widget_positions); ?>;
    
    var col, row;
    var sizeX = Number(widget_info[1]);
    var sizeY = Number(widget_info[2]);
    if (widget_positions) {
        col = widget_positions.col;
        row = widget_positions.row;
        sizeX= widget_positions.size_x;
        sizeY = widget_positions.size_y;
    } 
    addWidget(gridster,widget_info[0], sizeX, sizeY, col, row);
    move(widget_positions.id.concat("_old"), widget_positions.id);
</script>
     <?php 
 }
 ?> 
 <script>
	lockPanel();
</script>
 <?php
}
} 
?>
