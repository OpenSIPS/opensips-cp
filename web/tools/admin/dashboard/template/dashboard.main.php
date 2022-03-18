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
            serialize_params: function($w, wgd) {
                    if ($w.context.childNodes.length >= 2) 
                        var outer_html = $w.context.childNodes[1].outerHTML;
                    else var outer_html = "";
                    if ($w.attr('type') == "cdr") {
                        outer_html = $w.context.childNodes[0].outerText;
                    }
                    return { 
                           id: $w.attr('id'), 
                           type: $w.attr('type'),
                           title: $w.attr('title'),
                           ohtml: outer_html,
                           col: wgd.col, 
                           row: wgd.row, 
                           size_x: wgd.size_x, 
                           size_y: wgd.size_y 
                    };
            },
            draggable: {
                stop: function (e, ui, $widget) {
                    var positions = gridster.serialize();
                    console.log(positions);
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
if ($_SESSION['config']['panels'][$panel_id]['content'] != null) {
    ?>
    <script>
        var e;
     console.log(e);
            var stored_widgets = JSON.parse(<?php echo json_encode($_SESSION['config']['panels'][$panel_id]['content']); ?>);
            stored_widgets.forEach(element => 
            { console.log(element);
                if (element.type == "chart") {  console.log(element.id);
                   gridster.add_widget('<li id="'.concat(element.id).concat('" type="').concat(element.type).concat('"></li>'), element.size_x, element.size_y, element.col, element.row);
                   move( "chart_".concat(element.id), element.id);
                } else if (element.type == "custom") {
                    gridster.add_widget('<li title='.concat(element.title).concat(' id="').concat(element.id).concat('" type="').concat(element.type).concat('"><header>').concat(element.title).concat('<a href=\'dashboard.php?action=edit_widget&widget_name=nameegg\' onclick="lockPanel()" style="position:relative; left:60px; top:2px; content: url(\'../../../images/sett.png\');"></a></header><div>').concat(element.ohtml).concat('</div></li>'), element.size_x, element.size_y, element.col, element.row)
                } else if (element.type == "horizontalTitle") {
                    gridster.add_widget('<li title='.concat(element.title).concat(' id="').concat(element.id).concat('" type="').concat(element.type).concat('"><div>').concat(element.title).concat('</div></li>'), 5, 1, element.col, element.row);
                }  else if (element.type == "verticalTitle") {
                    gridster.add_widget('<li title='.concat(element.title).concat(' id="').concat(element.id).concat('" type="').concat(element.type).concat('"><div>').concat(element.title).concat('</div></li>'), 1, 5, element.col, element.row);
                }  else if (element.type == "cdr") {
                    gridster.add_widget('<li title='.concat(element.name).concat(' id="').concat(element.id).concat('" type="').concat(element.type).concat('"><div>').concat(element.ohtml).concat('</div></li>'), element.size_x, element.size_y, element.col, element.row);
                }     
            });
    </script>
    <?php
}
} ?>