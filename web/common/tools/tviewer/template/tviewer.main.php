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


// load table content

$where="1 = 1";
$qvalues = array();
foreach ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'] as $key => $value) {
	if (isset($_SESSION[$key]))
		if ($_SESSION[$key] != "") {
			if ($value["type"] == "text") {
				$where.=" and ".$key." like ?";
				$qvalues[] = "%".$_SESSION[$key]."%";
			} else {
				$where.=" and ".$key." = ?";
				$qvalues[] = $_SESSION[$key];
			}

		}
}

$query_ct = "select count(".$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_primary_key'].") 
			from ".$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table'];

$total_records = $link->query($query_ct);
if($total_records === false) {
	die('Failed to issue total count query, error message : ' . print_r($link->errorInfo(), true). "[".$query_ct."]");
}


$query_fl =	"select count(".$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_primary_key'].") 
		from ".$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table']."
		where ".$where;

$stm = $link->prepare($query_fl);
if ($stm->execute($qvalues) === false) {
	die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true). "[".$query_fl."]");
}
$filtered_records = $stm->fetchColumn(0);

//determine the colspan
if(!$_SESSION['read_only']){
	$colspan = count($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'])+count($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_columns']);
}else{
	$colspan = count($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs']);
}

unset($resultset);
if ($filtered_records > 0) {
	$res_no=$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['per_page'];
	$page=$_SESSION[$current_page];
	$page_no=ceil($filtered_records/$res_no);
	if ($page>$page_no) {
		$page=$page_no;
		$_SESSION[$current_page]=$page+1;
	}
	$start_limit=($page-1)*$res_no;

	$query = 	"select ".implode(",",array_keys($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs']))." 
		from ".$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table']." 
		where ".$where." 
		order by ".$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_order_by']." 
		LIMIT ".$res_no." 
		OFFSET ".$start_limit;
	
	$stm = $link->prepare($query);
	if($stm->execute($qvalues) === false) {
		die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true). "[".$query."]");
	}
	$resultset= $stm->fetchAll(PDO::FETCH_ASSOC);

}
else {
	$filtered_records = 0;
	$empty_res = '<tr><td colspan="'.$colspan.'" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>';
}
?>

<div id="dialog" class="dialog" style="display:none"></div>
<div onclick="closeDialog();" id="overlay" style="display:none"></div>
<div id="content" style="display:none"></div>

			
			<!-- SEARCH BOX STARTS HERE -->
			<?php if ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_search']['enabled']) { ?>
				<form id="" action="<?=$page_name?>?action=dp_act" method="post">
				<table width="350" cellspacing="2" cellpadding="2" border="0">
				<?php foreach ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'] as $key => $value) { ?>	
					<?php if ($key != $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_primary_key'] && $value['searchable']) { ?>
					<tr>
						<td class="searchRecord"><?=$value['header']?></td>
						<td class="searchRecord" width="200">
						<?php switch ($value['type']) {
							case "textarea":
							case "text": ?>
								<input 	id="<?=$key?>" 
										name="<?=$key?>" 
										class="searchInput" 
										type="text" 
										value="<?=(isset($_SESSION[$key]))?$_SESSION[$key]:((isset($value['default_value']))?$value['default_value']:"")?>" 
									<?=(isset($value['disabled'])&&$value['disabled'])?"disabled":""?>  
									<?=(isset($value['readonly'])&&$value['readonly'])?"readonly":""?>
								/> 
								<?php break; ?>	
						<?php case "combo": ?>
								<?php 	
									if (isset($_SESSION[$key])) $value['default_value'] = $_SESSION[$key];
									print_custom_combo( $key, $value, $value['default_value'], TRUE ); ?>	
								<?php break; ?>	
						<?php } ?>
 						</td>
					</tr>
					<?php } ?>
				<?php } ?>
					<tr height="10">
						<td colspan="2" class="searchRecord" align="center">
								<input type="submit" class="searchButton" name="search" value="Search">&nbsp;&nbsp;&nbsp;
						<?php if(!$_SESSION['read_only']) { ?>
								<input type="submit" class="searchButton" name="show_all" value="Show All"></td>
						<?php } ?>
					</tr>
				</table>
				</form>
			<?php } ?>
			<!-- SEARCH BOX ENDS HERE -->

			
			<!-- ACTION BUTTONS START HERE -->
			<table style="width:1px!important" align="center" cellspacing="2" cellpadding="4" border="0"><tr>
				<?php if (!$_SESSION['read_only'] && isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_buttons'])) { ?>
					<?php for ($i=0; $i<count($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_buttons']); $i++) { ?>
					<td align="center">
						<form action="<?=$page_name?>?action=<?=$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_buttons'][$i]['action']?>" method="post">
							<input  type = "submit" 
								name = "<?=$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_buttons'][$i]['action']?>" 
								value= "<?=$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_buttons'][$i]['text']?>" 
								class= "<?php if (isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_buttons'][$i]['style']))
											echo($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_buttons'][$i]['style']);
										else
											echo ("button ".$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_buttons'][$i]['color']);?>"
							>
						</form>
					</td>
					<?php
					}
					if ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['reload']) {
						echo '<td align="center">';
						echo '<form action="<?=$page_name?>" method="post">';
						echo '<input onclick="apply_changes()" name="reload" class="formButton" value="Reload on Server" type="button"/>';
						echo '</form>';
						echo '</td>';
					}

				} ?>
			</tr></table>
			<!-- ACTION BUTTONS END HERE -->

			<!-- PRELOAD combo  STARTS HERE -->
			<?php
			$combo_cache = array();
			foreach ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'] as $key => $value) {
				if ($value['type'] == "combo")
					$combo_cache[ $key ] = get_custom_combo_options($value);
			}
			?>
			<!-- PRELOAD combo  STARTS HERE -->

			<!-- TABLE STARTS HERE -->
			<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
				<tr align="center">
					<?php foreach ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'] as $key => $value) {
						if ( !isset($value['visible']) || $value['visible']==true)	
							echo('<th class="listTitle">'.$value['header'].'</th>');
						}
						if(!$_SESSION['read_only']){ 
							for ($i=0; $i<count($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_columns']); $i++) {
								$header_name = ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_columns'][$i]['header'])?$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_columns'][$i]['header']:"";
								echo "<th class='listTitle'>".$header_name."</th>";
							}
						} 
					?>
				</tr>
				<?php
					$checklist_cache = array();
					if (isset($resultset) && count($resultset) > 0){
						for ($i=0; $i<count($resultset);$i++){
							$row_style = ($i%2 == 1)?"rowOdd":"rowEven";
							echo "<tr>";
							foreach ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'] as $key => $value) {
								if ( isset($value['visible']) && $value['visible']==false)
									continue;
								echo "<td class='".$row_style."'>";
								switch ($value['type']) {
								case "combo":
									$text = isset($resultset[$i][$key]) ? $combo_cache[$key][ $resultset[$i][$key] ]['display'] : "";
									break;
								case "textarea":
									if (isset($value['textarea_display_size']))
										$size = $value['textarea_display_size'];
									else
										$size = 50;
									if (strlen($resultset[$i][$key]) > ($size + 3/* ... */))
										$text = substr($resultset[$i][$key], 0, $size)."...";
									else
										$text = $resultset[$i][$key];
									break;
								case "checklist":
									$text = display_custom_checklist($resultset[$i][$key], $value, $checklist_cache);
									break;
								case "text":
								default:
									$text = $resultset[$i][$key];
									break;
								}
								if (isset($value['value_wrapper_func']))
									echo $value['value_wrapper_func']( $key, $text, $resultset[$i] );
								else
									echo $text;
								echo "</td>";
							}
							if(!$_SESSION['read_only']){ 
								for ($j=0; $j<count($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_columns']); $j++) {
										if (isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_columns'][$j]['action_target']))
											$custom_page_name = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_columns'][$j]['action_target'];
										else
											$custom_page_name = $page_name;
										$action_link	 = "<a href='".$custom_page_name."?action=";
										$action_link	.= $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_columns'][$j]['action'];
										$action_link	.= "&".$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_primary_key']."=";
										$action_link	.= $resultset[$i][$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_primary_key']];
										$action_link	.= "'";
										if (isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_columns'][$j]['events']))
											$action_link .= " ".$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_columns'][$j]['events']; 
										$action_link	.= ">";
										$action_link	.= "<img src='".$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_columns'][$j]['icon']."' border='0'>";
										$action_link	.= "</a>";

										echo "<td class='".$row_style."Img' align='center'>";
										echo $action_link;
										echo "</td>";
								}
							} 
							echo "</tr>";
						}
					}
					else {
						echo $empty_res;
					}
				?>
						<!-- PAGING STARTS HERE -->
						<tr>
							<th colspan="<?=$colspan?>">
								<table class="pagingTable">
									<tr>
										<th align="left">Page:
									   <?php
										if ($filtered_records==0) 
											echo('<font class="pageActive">0</font>&nbsp;');
										else {
											$max_pages = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['page_range'];;
											// start page
											if ($page % $max_pages == 0) 
												$start_page = $page - $max_pages + 1;
											else 
												$start_page = $page - ($page % $max_pages) + 1;
											
											// end page
											$end_page = $start_page + $max_pages - 1;
											if ($end_page > $page_no) 
												$end_page = $page_no;

											// back block
											if ($start_page!=1) 
												echo('&nbsp;<a href="'.$page_name.'?action=dp_act&page='.($start_page-$max_pages).'" class="menuItem"><b>&lt;&lt;</b></a>&nbsp;');

											// current pages
											for($i=$start_page;$i<=$end_page;$i++)
												if ($i==$page) 
													echo('<font class="pageActive">'.$i.'</font>&nbsp;');
												else 
													echo('<a href="'.$page_name.'?action=dp_act&page='.$i.'" class="pageList">'.$i.'</a>&nbsp;');
										
											// next block
											if ($end_page!=$page_no) echo('&nbsp;<a href="'.$page_name.'?action=dp_act&page='.($start_page+$max_pages).'" class="menuItem"><b>&gt;&gt;</b></a>&nbsp;');
									   }
									   ?>
										</th>
										<th align="right">
											Total Records: <?=$filtered_records?>&nbsp;
										</th>
									</tr>
								</table>
							</th>
						</tr>
					</table>
				<br>

