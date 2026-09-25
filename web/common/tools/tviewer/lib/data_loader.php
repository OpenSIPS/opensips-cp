<?php

	session_start();

	$module_id = $_SESSION['module_id'];
	$branch = $_SESSION['branch'];
	
	require_once("../../../../config/tools/".$branch."/".$module_id."/db.inc.php");
	require_once("../../../../config/db.inc.php");
	require_once("../../../../web/common/cfg_comm.pgp");

	session_load_from_tool($module_id);
	if (file_exists("../../../../config/tools/".$branch."/".$module_id."/tviewer.inc.php"))
		require_once("../../../../config/tools/".$branch."/".$module_id."/tviewer.inc.php");
	
    global $config;
	global $custom_config;
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array_keys($custom_config[$module_id]['custom_table_column_defs']) ;
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = $custom_config[$module_id]['custom_table_primary_key'];
	
	/* Connect to DB */

	
	require_once(__DIR__."/../../../db_pdo.php");
	$db = $custom_config[$module_id];
	if (!isset($db['db_host'], $db['db_user'], $db['db_name']))
		$db = db_tool_settings($module_id);
	$link = db_connect($db);

	/* DB table to use */
	$sTable = $custom_config[$module_id]['custom_table'];
	
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
	 * no need to edit below this line
	 */
	
	/* 
	 * Local functions
	 */
	function fatal_error ( $sErrorMessage = '' )
	{
		global $link;
		header( $_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error' );
		die( $sErrorMessage );
	}

	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayLength'] ).
			" OFFSET ".mysql_real_escape_string( $_GET['iDisplayStart'] );
	}
	
	
	/*
	 * Ordering
	 */
	$sOrder = "";
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= "`".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".
				 	mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = "";
	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
	{
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( isset($_GET[$aColumns[$i]]) && $_GET[$aColumns[$i]] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string($_GET[$aColumns[$i]])."%' ";
		}
	}
	
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".implode(",",$aColumns)." 
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
		";

	$rResult = $link->query( $sQuery );
	if($rResult === false) {
		fatal_error( 'SQL Error: ' . $link->errorInfo()[2] );
	}

	$rResult = $rResult->fetchAll(PDO::FETCH_ASSOC);
	
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = $link->query( $sQuery );
	if($rResultFilterTotal === false) {
		fatal_error( 'SQL Error: ' . $link->errorInfo()[2] );
	}
	
	$iFilteredTotal = $rResultFilterTotal->fetchColumn(0);
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(`".$sIndexColumn."`)
		FROM   $sTable
	";
	$rResultTotal = $link->query( $sQuery ); 
	if($rResultTotal === false) {
		fatal_error( 'SQL Error: ' . $link->errorInfo()[2] );
	}
	$iTotal = $rResultTotal->fetchColumn(0);
	
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	
	
	for ($i=0; $i<count($rResult); $i++)
	{
		$row = array();
		for ( $j=0 ; $j<count($rResult[$i]) ; $j++ )
		{
			if ( $aColumns[$j] != ' ')
			{
				if ($custom_config[$module_id]['custom_table_column_defs'][$aColumns[$j]]['type'] == "combo" && $custom_config[$module_id]['custom_table_column_defs'][$aColumns[$j]]['combo_table'] != NULL){
					$query = "select ".$custom_config[$module_id]['custom_table_column_defs'][$aColumns[$j]]['combo_display_col'] ." from ".$custom_config[$module_id]['custom_table_column_defs'][$aColumns[$j]]['combo_table'] ." where ".$custom_config[$module_id]['custom_table_column_defs'][$aColumns[$j]]['combo_value_col'] ." = '".$rResult[$i][ $aColumns[$j] ]."'";
					$translated_value = $link->query( $query );
					if($translated_value) {
         					fatal_error( 'SQL Error: ' . $link->errorInfo()[2] );
        				}
					$row[] = $translated_value->fetchColumn(0);
				} else if (isset($custom_config[$module_id]['custom_table_column_defs'][$aColumns[$j]]['combo_default_values']) && is_array($custom_config[$module_id]['custom_table_column_defs'][$aColumns[$j]]['combo_default_values']) && count($custom_config[$module_id]['custom_table_column_defs'][$aColumns[$j]]['combo_default_values'])){
					foreach ( $custom_config[$module_id]['custom_table_column_defs'][$aColumns[$j]]['combo_default_values'] as $k => $v){
						if ($k == $rResult[$i][ $aColumns[$j]])
							$row[] = $v;
					}
				}
				else {
					$row[] = $rResult[$i][ $aColumns[$j] ];
				}
			}
		}
								//onClick="'.$custom_config[$module_id]['custom_action_columns'][$k]['js_function'].'("'.$rResult[$i][$sIndexColumn].'");"
		//LOAD THE ACTION COLUMNS
		if (!$_SESSION['read_only']){
			for ($k=0; $k<count($custom_config[$module_id]['custom_action_columns']); $k++) {
				if ($custom_config[$module_id]['custom_action_columns'][$k]['type'] == "javascript")
					$row[] = '<a href="javascript:;" >
							<img 	src="'.$custom_config[$module_id]['custom_action_columns'][$k]['icon'].'" 
								border="0"
								onClick="'.$custom_config[$module_id]['custom_action_columns'][$k]['js_function'].'(\''.$rResult[$i][$sIndexColumn].'\')"; 
							/>
						</a>';
				else
					$row[] = '<a href="?action='.$custom_config[$module_id]['custom_action_columns'][$k]['action'].'&id='.$rResult[$i][$sIndexColumn].'"><img src="'.$custom_config[$module_id]['custom_action_columns'][$k]['icon'].'" border="0"></a>';
			}
		}
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>
