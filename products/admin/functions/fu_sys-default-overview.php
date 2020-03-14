<?php
$varSQL = getPostData();
$CONFIG['page'] = json_decode($varSQL['pageConfig'], true);
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php'); 

if(!isset($varSQL['mode'])) $varSQL['mode'] = 'grid';
$modulname = $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'];
$rowsortable = 0;


if($varSQL['mode'] == 'grid'){
	#####################
	// settings for grid mode
	#####################
	
	####################
	// filter for parent grid
	$conditionParent = '';
	$aConditionParentPDO = array();
	if($CONFIG['page']['moduls'][$varSQL['modul']]['dataIdParent'] != 0){
		// From ...
//			if($varSQL['primeryfieldDataParent'] == 'id_classid'){
//				$conditionParent .= ' AND ' . $CONFIG['db'][0]['prefix'] . 'masterdata2classes.id_classid = (:fieldParent)';
//				$aConditionParentPDO['fieldParent'] = array($varSQL['idDataParent'], 'd');
//			}
	}
	
	####################
	// filter for main table
	$condition = "";
	$aConditionPDO = array();
	$queryF = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.table_name,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_name,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.tab_colname,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.format
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default 
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default
											ON (' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.id_grid_d = ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.id_grid_d
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.table_name = ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.tab_name)
											
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.grid_name = (:grid_name)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.id_page = (:id_page)
										');
	$queryF->bindValue(':grid_name', $modulname, PDO::PARAM_STR);
	$queryF->bindValue(':id_page', $CONFIG['page']['pageId'], PDO::PARAM_INT);
	$queryF->execute();
	$rowsF = $queryF->fetchAll(PDO::FETCH_ASSOC);
	$numF = $queryF->rowCount();
	
	foreach($rowsF as $rowF){
		// integer
		if(isset($varSQL[$rowF['g_name']]) && $rowF['format'] == 'i'){
			if($varSQL[$rowF['g_name']] != ''){
				$condition .= ' AND ' . $CONFIG['db'][0]['prefix'] . $rowF['table_name'] . '_uni.' . $rowF['tab_colname'] . ' = (:'.$rowF['g_name'].')';
				$aConditionPDO[$rowF['g_name']] = array($varSQL[$rowF['g_name']], 'd');
			}
		}
		
		// string
		if(isset($varSQL[$rowF['g_name']]) && ($rowF['format'] == 's' || $rowF['format'] == 'f' || $rowF['format'] == 'c')){
			if($varSQL[$rowF['g_name']] != ''){
				$condition .= ' AND ' . $CONFIG['db'][0]['prefix'] . $rowF['table_name'] . '_uni.' . $rowF['tab_colname'] . ' LIKE (:'.$rowF['g_name'].')';
				$aConditionPDO[$rowF['g_name']] = array($varSQL[$rowF['g_name']], 'sl');
			}
		}
		
		// date
		if(isset($varSQL[$rowF['g_name']]) && $rowF['format'] == 'd'){
			if($varSQL[$rowF['g_name']] != ''){
				$aB = explode('.', $varSQL[$rowF['g_name']]);
				$rev = array_reverse($aB);
				$sB = implode('-', $rev);
				$condition .= ' AND ' . $CONFIG['db'][0]['prefix'] . $rowF['table_name'] . '_uni.' . $rowF['tab_colname'] . ' LIKE (:'.$rowF['g_name'].')';
				$aConditionPDO[$rowF['g_name']] = array($sB, 'sl');
			}
		}
	}


	####################
	// special filter






	$CONFIG['page']['moduls'][$varSQL['modul']]['activeConditionParent'] = $conditionParent;
	$CONFIG['page']['moduls'][$varSQL['modul']]['activeConditionParentPDO'] = $aConditionParentPDO;
	$CONFIG['page']['moduls'][$varSQL['modul']]['activeCondition'] = $condition;
	$CONFIG['page']['moduls'][$varSQL['modul']]['activeConditionPDO'] = $aConditionPDO;
	if($condition == '') $rowsortable = 1;
	
	####################
	
	
	####################
	// Set Parameter
	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . $rowsF[0]['table_name'] . '_uni.' . $rowsF[0]['tab_colname'] . '
										FROM ' . $CONFIG['db'][0]['prefix'] . $rowsF[0]['table_name'] . '_uni 
										WHERE ' . $CONFIG['db'][0]['prefix'] . $rowsF[0]['table_name'] . '_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . $rowsF[0]['table_name'] . '_uni.id_count = (:count)
											AND ' . $CONFIG['db'][0]['prefix'] . $rowsF[0]['table_name'] . '_uni.id_lang = (:lang)
											AND ' . $CONFIG['db'][0]['prefix'] . $rowsF[0]['table_name'] . '_uni.id_dev = (:dev)
											AND (' . $CONFIG['db'][0]['prefix'] . $rowsF[0]['table_name'] . '_uni.id_clid = (:cl)
												OR ' . $CONFIG['db'][0]['prefix'] . $rowsF[0]['table_name'] . '_uni.id_clid = (:nul))
											' . $conditionParent . '
											' . $condition . '
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':nul', 0, PDO::PARAM_INT);
	$query->bindValue(':count', $CONFIG['page']['moduls'][$varSQL['modul']]['activeCountry'], PDO::PARAM_INT);
	$query->bindValue(':lang', $CONFIG['page']['moduls'][$varSQL['modul']]['activeLanguage'], PDO::PARAM_INT);
	$query->bindValue(':dev', $CONFIG['page']['moduls'][$varSQL['modul']]['activeDevice'], PDO::PARAM_INT);
	$query->bindValue(':cl', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
	foreach($aConditionParentPDO as $k=>$v){
		if($v[1] == 'd'){
			$query->bindValue(':'.$k, $v[0], PDO::PARAM_INT);
		}else if($v[1] == 'sl'){
			$query->bindValue(':'.$k, '%'.$v[0].'%', PDO::PARAM_STR);
		}else if($v[1] == 'slb'){
			$query->bindValue(':'.$k, '%'.$v[0], PDO::PARAM_STR);
		}else if($v[1] == 'sle'){
			$query->bindValue(':'.$k, $v[0].'%', PDO::PARAM_STR);
		}else{
			$query->bindValue(':'.$k, $v[0], PDO::PARAM_STR);
		}
	}
	foreach($aConditionPDO as $k=>$v){
		if($v[1] == 'd'){
			$query->bindValue(':'.$k, $v[0], PDO::PARAM_INT);
		}else if($v[1] == 'sl'){
			$query->bindValue(':'.$k, '%'.$v[0].'%', PDO::PARAM_STR);
		}else if($v[1] == 'slb'){
			$query->bindValue(':'.$k, '%'.$v[0], PDO::PARAM_STR);
		}else if($v[1] == 'sle'){
			$query->bindValue(':'.$k, $v[0].'%', PDO::PARAM_STR);
		}else{
			$query->bindValue(':'.$k, $v[0], PDO::PARAM_STR);
		}
	}
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();

	$numAll = $num;
	$page = $varSQL['page']; 
	$limit = $varSQL['rows']; 
	$order = $varSQL['sidx']; 
	if($order == "") $sidx = 1; 
	$dir = $varSQL['sord']; 
	
	if($numAll > 0) { 
		$total_pages = ceil($numAll/$limit); 
	}else{ 
		$total_pages = 0; 
	}
	if($page > $total_pages) $page = $total_pages; 
	$start = $limit * $page - $limit;
	if($start < 0) $start = 0;
	
	$queryLimit = 'LIMIT ' . $start . ', ' . $limit . '';
	
}else if($varSQL['mode'] == 'export'){
	#####################
	// settings for export mode
	#####################
	// Filter
	$conditionParent = $CONFIG['page']['moduls'][$varSQL['modul']]['activeConditionParent'];
	$aConditionParentPDO = $CONFIG['page']['moduls'][$varSQL['modul']]['activeConditionParentPDO'];
	$condition = "";
	if($varSQL['exportdata'] == 'filter') $condition = $CONFIG['page']['moduls'][$varSQL['modul']]['activeCondition'];
	$aConditionPDO = $CONFIG['page']['moduls'][$varSQL['modul']]['activeConditionPDO'];
	
	$order = '' . $CONFIG['db'][0]['prefix'] . $rowF['table_name'] . '_uni.' . $rowsF[0]['tab_colname'];
	$dir = 'ASC';
	$queryLimit = '';
}

	


############################################################################
// SELECT
############################################################################
$queryStr = '';
$queryS = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.table_name,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.tab_name,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.tab_colname
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default 
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.id_grid_d = ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.id_grid_d
										
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.grid_name = (:grid_name)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.id_page = (:id_page)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.tab_colname <> (:empty)
									');
$queryS->bindValue(':grid_name', $modulname, PDO::PARAM_STR);
$queryS->bindValue(':id_page', $CONFIG['page']['pageId'], PDO::PARAM_INT);
$queryS->bindValue(':empty', '', PDO::PARAM_STR);
$queryS->execute();
$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
$numS = $queryS->rowCount();

foreach($rowsS as $rowS){
	$queryStr .= $CONFIG['db'][0]['prefix'] . $rowS['tab_name'] . '_uni.' . $rowS['tab_colname'] . ', '; 
}
$queryStr = rtrim($queryStr, ', ');

$query = $CONFIG['dbconn']->prepare('
									SELECT 
										' . $queryStr . '
									FROM ' . $CONFIG['db'][0]['prefix'] . $rowsF[0]['table_name'] . '_uni 
									WHERE ' . $CONFIG['db'][0]['prefix'] . $rowsF[0]['table_name'] . '_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . $rowsF[0]['table_name'] . '_uni.id_count = (:count)
										AND ' . $CONFIG['db'][0]['prefix'] . $rowsF[0]['table_name'] . '_uni.id_lang = (:lang)
										AND ' . $CONFIG['db'][0]['prefix'] . $rowsF[0]['table_name'] . '_uni.id_dev = (:dev)
										AND (' . $CONFIG['db'][0]['prefix'] . $rowsF[0]['table_name'] . '_uni.id_clid = (:cl)
											OR ' . $CONFIG['db'][0]['prefix'] . $rowsF[0]['table_name'] . '_uni.id_clid = (:nul))
										' . $conditionParent . '
										' . $condition . '
									ORDER BY ' . $order . ' ' . $dir . '
									' . $queryLimit . '
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->bindValue(':count', $CONFIG['page']['moduls'][$varSQL['modul']]['activeCountry'], PDO::PARAM_INT);
$query->bindValue(':lang', $CONFIG['page']['moduls'][$varSQL['modul']]['activeLanguage'], PDO::PARAM_INT);
$query->bindValue(':dev', $CONFIG['page']['moduls'][$varSQL['modul']]['activeDevice'], PDO::PARAM_INT);
$query->bindValue(':cl', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
foreach($aConditionParentPDO as $k=>$v){
	if($v[1] == 'd'){
		$query->bindValue(':'.$k, $v[0], PDO::PARAM_INT);
	}else if($v[1] == 'sl'){
		$query->bindValue(':'.$k, '%'.$v[0].'%', PDO::PARAM_STR);
	}else if($v[1] == 'slb'){
		$query->bindValue(':'.$k, '%'.$v[0], PDO::PARAM_STR);
	}else if($v[1] == 'sle'){
		$query->bindValue(':'.$k, $v[0].'%', PDO::PARAM_STR);
	}else{
		$query->bindValue(':'.$k, $v[0], PDO::PARAM_STR);
	}
}
foreach($aConditionPDO as $k=>$v){
	if($v[1] == 'd'){
		$query->bindValue(':'.$k, $v[0], PDO::PARAM_INT);
	}else if($v[1] == 'sl'){
		$query->bindValue(':'.$k, '%'.$v[0].'%', PDO::PARAM_STR);
	}else if($v[1] == 'slb'){
		$query->bindValue(':'.$k, '%'.$v[0], PDO::PARAM_STR);
	}else if($v[1] == 'sle'){
		$query->bindValue(':'.$k, $v[0].'%', PDO::PARAM_STR);
	}else{
		$query->bindValue(':'.$k, $v[0], PDO::PARAM_STR);
	}
}
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$content = new StdClass();
$contentRaw = new StdClass();
$content->page = $page;
$content->total = $total_pages;
$content->records = $numAll;
$content->rowsortable = $rowsortable;
$content->pageconfig = json_encode($CONFIG['page']);
$i = 0;

foreach($rows as $row){
	$rowExp = array();
	$aFields = array();
	$aFields['yesNo2Text'] = array('active');
	//$row = setValuesRead($row, $aFields, $_SESSION['admin']['USER']['selectedCountry'][$CONFIG['page']['modul']], $_SESSION['admin']['USER']['selectedLanguage'][$CONFIG['page']['modul']], $_SESSION['admin']['USER']['selectedDevice'][$CONFIG['page']['modul']]);


	$contentRaw->rows[$i] = $row + $rowExp;
	$contentRaw->rows[$i]['id'] = $row['id_devid'];

	foreach($row as $k=>$v){
		$row[$k] = '<div class="gridCellFold">' . $v . '</div>';
	}
	
	$row['id_devid'] = strip_tags($row['id_devid']);
	
	$content->rows[$i] = $row;
	$content->rows[$i]['id'] = $row['id_devid'];

	$i++;
} 



?>