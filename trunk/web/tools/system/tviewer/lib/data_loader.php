<?php
	require("../init.php");	

	session_start();
	
	require_once("../../../../../config/tools/".$branch."/".$module_id."/local.inc.php");
	require_once("../../../../../config/tools/".$branch."/".$module_id."/db.inc.php");
	require_once("../../../../../config/db.inc.php");
	require_once("MDB2.php");
	
        global $config;
	global $custom_config;
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array_keys($custom_config[$module_id]['custom_table_column_defs']) ;
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = $custom_config[$module_id]['custom_table_primary_key'];
	
	/* Connect to DB */

	
	if (isset($custom_config[$module_id]['db_driver']) && isset($custom_config[$module_id]['db_host']) && isset($custom_config[$module_id]['db_user']) && isset($custom_config[$module_id]['db_name']) ) {
                $config->db_driver = $custom_config[$module_id]['db_driver'];
                $config->db_host = $custom_config[$module_id]['db_host'];
                $config->db_port = $custom_config[$module_id]['db_port'];
                $config->db_user = $custom_config[$module_id]['db_user'];
                $config->db_pass = $custom_config[$module_id]['db_pass'];
                $config->db_name = $custom_config[$module_id]['db_name'];
		if (isset($config->db_port) && is_int((int)$config->db_port) && 1 < $config->db_port && $config->db_port < 65535) {
			$config->db_host = $config->db_host.":".$config->db_port;
		}
        }

       
	$dsn = $config->db_driver.'://' . $config->db_user.':'.$config->db_pass . '@' . $config->db_host . '/'. $config->db_name;
        $link = & MDB2::connect($dsn);
        $link->setFetchMode(MDB2_FETCHMODE_ASSOC);
        if(PEAR::isError($link)) {
		die("Error while connecting : " . $link->getMessage());
        }	
	
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
	 * MySQL connection
	 */
	if(PEAR::isError($link)){
		fatal_error( 'Could not open connection to server' );
	}
	
	
	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
			mysql_real_escape_string( $_GET['iDisplayLength'] );
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

	$rResult = $link->queryAll( $sQuery );
	
	
	
	if(PEAR::isError($rResult)) {
		fatal_error( 'SQL Error: ' . $rResult->getMessage() );
	}

	
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = $link->queryOne( $sQuery );
	if(PEAR::isError($rResultFilterTotal)) {
		fatal_error( 'SQL Error: ' . $rResultFilterTotal->getMessage() );
	}
	
	$iFilteredTotal = $rResultFilterTotal;
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(`".$sIndexColumn."`)
		FROM   $sTable
	";
	$rResultTotal = $link->queryOne( $sQuery ); 
	if(PEAR::isError($rResultTotal)) {
		fatal_error( 'SQL Error: ' . $rResultTotal->getMessage() );
	}
	$iTotal = $rResultTotal;
	
	
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
					$translated_value = $link->queryOne( $query );
					if(PEAR::isError($translated_value)) {
         					fatal_error( 'SQL Error: ' . $translated_value->getMessage() );
        				}
					$row[] = $translated_value;
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
