<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData();

$out = array();

if(!isset($CONFIG['page']['moduls'][$varSQL['modul']]['pageIdParent'])) $CONFIG['page']['moduls'][$varSQL['modul']]['pageIdParent'] = 0;
if(!isset($CONFIG['page']['moduls'][$varSQL['modul']]['pageParent'])) $CONFIG['page']['moduls'][$varSQL['modul']]['pageParent'] = '';
if(!isset($CONFIG['page']['moduls'][$varSQL['modul']]['modulParent'])) $CONFIG['page']['moduls'][$varSQL['modul']]['modulParent'] = '';
if(!isset($CONFIG['page']['moduls'][$varSQL['modul']]['dataIdParent'])) $CONFIG['page']['moduls'][$varSQL['modul']]['dataIdParent'] = 0;

$CONFIG['grid']['gridNumRows'] = $CONFIG['system']['gridNumRows'];
$CONFIG['grid']['userNumRows'] = $CONFIG['USER']['gridNumRows'];
$CONFIG['grid']['htmlDir'] = $CONFIG['USER']['htmlDir'];

// Options for grid
$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.id_grid_d,
										' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.grid_width,
										' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.grid_height,
										' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.grid_sortname,
										' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.grid_sortorder,
										' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.sortable_rows,
										' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.grid_options,
										' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.grid_options_conditions
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.grid_name = (:gridname)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.id_page = (:id_page)
									');
$query->bindValue(':gridname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
$query->bindValue(':id_page', $CONFIG['page']['pageId'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
$CONFIG['page']['moduls'][$varSQL['modul']]['id_grid_d'] = $rows[0]['id_grid_d'];

$query2 = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_grids_user.grid_width,
										' . $CONFIG['db'][0]['prefix'] . 'system_grids_user.grid_height,
										' . $CONFIG['db'][0]['prefix'] . 'system_grids_user.grid_sortorder,
										' . $CONFIG['db'][0]['prefix'] . 'system_grids_user.grid_sortname
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_grids_user 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_grids_user.id_grid_d = (:id_grid_d)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_grids_user.id_uid = (:id_uid)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_grids_user.id_modul_parent = (:id_modul_parent)
									');
$query2->bindValue(':id_grid_d', $rows[0]['id_grid_d'], PDO::PARAM_INT);
$query2->bindValue(':id_uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
$query2->bindValue(':id_modul_parent', $CONFIG['page']['moduls'][$varSQL['modul']]['modulParent'], PDO::PARAM_STR);
$query2->execute();
$rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$num2 = $query2->rowCount();

if($num2 > 0){
	foreach($rows2[0] as $k=>$v){
		if($v != "") $rows[0][$k] = $v;
	}
}

$CONFIG['grid']['id_grid_d'] = $rows[0]['id_grid_d'];
$CONFIG['grid']['sortname'] = $rows[0]['grid_sortname'];
$CONFIG['grid']['sortorder'] = $rows[0]['grid_sortorder'];
$CONFIG['grid']['sortable_rows'] = $rows[0]['sortable_rows'];

$CONFIG['grid']['addoptions'] = array();
$aGridOptions = json_decode($rows[0]['grid_options'], true);
$aGridOptionsConditions = json_decode($rows[0]['grid_options_conditions'], true);
if(count($aGridOptions) > 0){
	foreach($aGridOptions as $opt=>$val){
		if($aGridOptionsConditions[$opt] == ''){
			$aGridOptionsConditions[$opt] = array();
		}else{
			$aGridOptionsConditions[$opt] = explode(',', $aGridOptionsConditions[$opt]);
		}
		if(count($aGridOptionsConditions[$opt]) == 0 || in_array($CONFIG['page']['moduls'][$varSQL['modul']]['pageIdParent'], $aGridOptionsConditions[$opt])) $CONFIG['grid']['addoptions'][$opt] = $val;
	}
}


$CONFIG['grid']['functions'] = array();
$aPage2f2f = array();
$aF = array();
foreach($CONFIG['USER']['pages2functions2files'][$CONFIG['page']['pageId']] as $aFunctions){
	if(!in_array($aFunctions['id_page2f2f'], $aPage2f2f)) array_push($aPage2f2f, $aFunctions['id_page2f2f']);
	if(!in_array($aFunctions['id_f'], $aF)) array_push($aF, $aFunctions['id_f']);
}
foreach($CONFIG['USER']['pages2functions2files'][$CONFIG['page']['pageId']] as $aFunctions){
	$aPageParent = explode(',', $aFunctions['id_page_parent']);
	
	if(in_array($CONFIG['page']['moduls'][$varSQL['modul']]['pageIdParent'], $aPageParent)){
		$aNotIf = array();
		if($aFunctions['show_not_if'] != '') $aNotIf = explode(',', $aFunctions['show_not_if']);
		$aNotIfPage = array();
		if($aFunctions['show_not_if_page'] != '') $aNotIfPage = explode(',', $aFunctions['show_not_if_page']);
		
		$showF = 1;
		foreach($aNotIf as $f){
			if(in_array($f, $aF)) $showF = 0;
		}
		foreach($aNotIfPage as $f){
			if(in_array($f, $aPage2f2f)) $showF = 0;
		}
		
		if(!in_array($aFunctions['id_page2f2f'], $CONFIG['grid']['functions']) && ($showF == 1 || $CONFIG['system']['showAllFunctions'] == 1)) array_push($CONFIG['grid']['functions'], $aFunctions['id_page2f2f']);
	}
}



############################################################
// Columns for grid
$aColnames = array();
$aColmodel = array();

$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.id_col_d,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_colname,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_name,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_index,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_frozen,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_width,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_sortable,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_search,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_title,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_hidden,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_resizable,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_stype,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_align,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_searchoptions,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_editable,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_editoptions,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_editrules,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_edittype,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_classes,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_rank
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.id_grid_d = (:id_grid_d)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_active = (:active)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_not_idpageparent NOT LIKE (:idpageparent)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_rank
									');
$query->bindValue(':id_grid_d', $CONFIG['grid']['id_grid_d'], PDO::PARAM_INT);
$query->bindValue(':active', 1, PDO::PARAM_INT);
$query->bindValue(':idpageparent', '#'.$CONFIG['page']['moduls'][$varSQL['modul']]['pageIdParent'].'#', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$n = 0;
foreach($rows as $row){
	$n++;
	$strColnames = "";
	$strColmodel = "";

	$query2 = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_colname,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_name,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_index,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_frozen,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_width,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_sortable,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_search,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_title,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_hidden,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_resizable,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_stype,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_align,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_searchoptions,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_editable,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_editoptions,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_editrules,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_classes,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_edittype,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_rank
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.id_col_d = (:id_col_d)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.id_uid = (:id_uid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.id_modul_parent = (:id_modul_parent)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_rank
										');
	$query2->bindValue(':id_col_d', $row['id_col_d'], PDO::PARAM_INT);
	$query2->bindValue(':id_uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
	$query2->bindValue(':id_modul_parent', $CONFIG['page']['moduls'][$varSQL['modul']]['modulParent'], PDO::PARAM_STR);
	$query2->execute();
	$rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$num2 = $query2->rowCount();
	
	if($num2 > 0 && $row['g_name'] != "'choice'"){
		foreach($rows2[0] as $k=>$v){
			if($v != "" && $k != "g_name") $row[$k] = $v;
		}
	}

	$strColnames .= (isset($TEXT[$row['g_colname']])) ? $TEXT[$row['g_colname']] : $row['g_colname'];
	$aColnames[$row['g_rank']] = $strColnames;
	
	$aColmodelSingle = array();
	foreach($row as $k=>$v){
		if(!in_array($k, $CONFIG['system']['aGridExc']) && $v != ""){
			if($v == 'false'){
				$aColmodelSingle[substr($k, 2)] = false;
			}else if($v == 'true'){
				$aColmodelSingle[substr($k, 2)] = true;
			}else{
				$aColmodelSingle[substr($k, 2)] = $v;
			}
			
			$aFieldsArrays = array('searchoptions', 'editoptions', 'editrules');
			foreach($aFieldsArrays as $fa){
				if(substr($k, 2) == $fa){
					$vTmp = array();
					$aV = explode(";", $v);
					foreach($aV as $vT){
						$aV2 = explode(':', $vT);
						$vTmp[$aV2[0]] = $aV2[1];
					}
					$aColmodelSingle[$fa] = $vTmp;
				}
			}
		}
	}
	$aColmodel[$row['g_rank']] = $aColmodelSingle;
}

ksort($aColnames);
ksort($aColmodel);

$CONFIG['grid']['colnames'] = array();
foreach($aColnames as $k=>$v){
	array_push($CONFIG['grid']['colnames'], $v);
}

$CONFIG['grid']['colmodel'] = array();
foreach($aColmodel as $k=>$v){
	array_push($CONFIG['grid']['colmodel'], $v);
}


// Functions for functionbar
$queryF = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_functions.id_f,
										' . $CONFIG['db'][0]['prefix'] . 'system_functions.function,
										' . $CONFIG['db'][0]['prefix'] . 'system_functions.title,
										' . $CONFIG['db'][0]['prefix'] . 'system_functions.icon,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page2f2f,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.function AS function_page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.title AS title_page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.icon AS icon_page
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_functions 
									
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_functions.id_f = ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f
									
									WHERE (' . $CONFIG['db'][0]['prefix'] . 'system_functions.del = (:nultime)
											OR ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f = (:nul))
										AND (' . $CONFIG['db'][0]['prefix'] . 'system_functions.type = (:type)
											OR ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.type = (:type))
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page2f2f IN (' . implode(',', $CONFIG['grid']['functions']) . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page = (:id_page)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_functions.rank, ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.rank
									');
$queryF->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryF->bindValue(':type', 'functionbar', PDO::PARAM_STR);
$queryF->bindValue(':id_page', $CONFIG['page']['pageId'], PDO::PARAM_INT);
$queryF->bindValue(':nul', 0, PDO::PARAM_INT);
$queryF->execute();
$rowsF = $queryF->fetchAll(PDO::FETCH_ASSOC);
$numF = $queryF->rowCount();

$CONFIG['grid']['barFunctions'] = array();
foreach($rowsF as $rowF){
	if($rowF['function_page'] != '') $rowF['function'] = $rowF['function_page'];
	if($rowF['title_page'] != '') $rowF['title'] = $rowF['title_page'];
	if($rowF['icon_page'] != '') $rowF['icon'] = $rowF['icon_page'];
	
	array_push($CONFIG['grid']['barFunctions'], array('function'=>$rowF['function'], 'title'=>$rowF['title'], 'icon'=>$rowF['icon'], 'id_grid_d'=>$CONFIG['grid']['id_grid_d']));
}

// Functions for datasets
$queryF = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_functions.id_f,
										' . $CONFIG['db'][0]['prefix'] . 'system_functions.function,
										' . $CONFIG['db'][0]['prefix'] . 'system_functions.title,
										' . $CONFIG['db'][0]['prefix'] . 'system_functions.icon,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page2f2f,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.function AS function_page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.title AS title_page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.icon AS icon_page
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_functions 
									
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_functions.id_f = ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f
									
									WHERE (' . $CONFIG['db'][0]['prefix'] . 'system_functions.del = (:nultime)
											OR ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f = (:nul))
										AND (' . $CONFIG['db'][0]['prefix'] . 'system_functions.type = (:type)
											OR ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.type = (:type))
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page2f2f IN (' . implode(',', $CONFIG['grid']['functions']) . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page = (:id_page)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_functions.rank, ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.rank
									');
$queryF->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryF->bindValue(':type', 'dataset', PDO::PARAM_STR);
$queryF->bindValue(':id_page', $CONFIG['page']['pageId'], PDO::PARAM_INT);
$queryF->bindValue(':nul', 0, PDO::PARAM_INT);
$queryF->execute();
$rowsF = $queryF->fetchAll(PDO::FETCH_ASSOC);
$numF = $queryF->rowCount();

$CONFIG['grid']['rowFunctions'] = array();
foreach($rowsF as $rowF){
	if($rowF['function_page'] != '') $rowF['function'] = $rowF['function_page'];
	if($rowF['title_page'] != '') $rowF['title'] = $rowF['title_page'];
	if($rowF['icon_page'] != '') $rowF['icon'] = $rowF['icon_page'];
	
	array_push($CONFIG['grid']['rowFunctions'], array('function'=>$rowF['function'], 'title'=>$rowF['title'], 'icon'=>$rowF['icon'], 'id_grid_d'=>$CONFIG['grid']['id_grid_d']));
}

$out['page'] = $CONFIG['page'];
$out['grid'] = $CONFIG['grid'];
echo json_encode($out);
?>