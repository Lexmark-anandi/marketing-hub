<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');

####
$varSQL = getPostData();
$CONFIG['page'] = json_decode($varSQL['pageConfig'], true);
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php'); 

if(!isset($varSQL['mode'])) $varSQL['mode'] = 'grid';
$modulname = $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'];
$rowsortable = 0;

if($varSQL['mode'] == 'grid'){
	#####################
	// Grid mode
	#####################
	
	####################
	// filter for parent grid
	$conditionParent = '';
	$aConditionParentPDO = array();
//		if($varSQL['idDataParent'] != ''){
////			// From ...
////			if($varSQL['primeryfieldDataParent'] == 'id_classid'){
////				$conditionParent .= ' AND ' . $CONFIG['db'][0]['prefix'] . 'masterdata2classes.id_classid = (:fieldParent)';
////				$aConditionParentPDO['fieldParent'] = array($varSQL['idDataParent'], 'd');
////			}
//		}
	
	####################
	// filter for main table
	$condition = "";
	$aConditionPDO = array();
	$queryF = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.table_name,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_index,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.tab_colname,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.format
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default 
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default
											ON (' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.id_grid_d = ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.id_grid_d
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.table_name = ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.tab_name)
											
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.grid_name = (:grid_name)
										');
	$queryF->bindValue(':grid_name', $modulname, PDO::PARAM_STR);
	$queryF->execute();
	$rowsF = $queryF->fetchAll(PDO::FETCH_ASSOC);
	$numF = $queryF->rowCount();
	
	foreach($rowsF as $rowF){
		// integer
		if(isset($varSQL[$rowF['g_index']]) && $rowF['format'] == 'i'){
			if($varSQL[$rowF['g_index']] != ''){
				$condition .= ' AND ' . $CONFIG['db'][0]['prefix'] . $rowF['table_name'] . '.' . $rowF['tab_colname'] . ' = (:'.$rowF['g_index'].')';
				$aConditionPDO[$rowF['g_index']] = array($varSQL[$rowF['g_index']], 'd');
			}
		}
		
		// string
		if(isset($varSQL[$rowF['g_index']]) && ($rowF['format'] == 's' || $rowF['format'] == 'f' || $rowF['format'] == 'c')){
			if($varSQL[$rowF['g_index']] != ''){
				$condition .= ' AND ' . $CONFIG['db'][0]['prefix'] . $rowF['table_name'] . '.' . $rowF['tab_colname'] . ' LIKE (:'.$rowF['g_index'].')';
				$aConditionPDO[$rowF['g_index']] = array($varSQL[$rowF['g_index']], 'sl');
			}
		}
		
		// date
		if(isset($varSQL[$rowF['g_index']]) && $rowF['format'] == 'd'){
			if($varSQL[$rowF['g_index']] != ''){
				$aB = explode('.', $varSQL[$rowF['g_index']]);
				$rev = array_reverse($aB);
				$sB = implode('-', $rev);
				$condition .= ' AND ' . $CONFIG['db'][0]['prefix'] . $rowF['table_name'] . '.' . $rowF['tab_colname'] . ' LIKE (:'.$rowF['g_index'].')';
				$aConditionPDO[$rowF['g_index']] = array($sB, 'sl');
			}
		}
	}





	
	if(isset($varSQL['countries'])){
		if($varSQL['countries'] != ''){
			$condition .= ' AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid = (:countries)';
			$aConditionPDO['countries'] = array($varSQL['countries'], 'd');
		}
	}

	if($CONFIG['USER']['right_country'] == 0 || $CONFIG['USER']['right_country'] == 1){
		$condition .= ' AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid IN ('. implode(',', array_keys($CONFIG['USER']['countries'])) . ')';
	}

	if($CONFIG['USER']['right_language'] == 0 || $CONFIG['USER']['right_language'] == 1){
		$condition .= ' AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid IN ('. implode(',', array_keys($CONFIG['USER']['languages'])) . ')';
	}

	if($CONFIG['USER']['right'] != 1){
		$condition .= ' AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_r <> (:systemadmin)';
		$aConditionPDO['systemadmin'] = array(1, 'd');
	}





	$CONFIG['page']['moduls'][$varSQL['modul']]['activeConditionParent'] = $conditionParent;
	$CONFIG['page']['moduls'][$varSQL['modul']]['activeConditionParentPDO'] = $aConditionParentPDO;
	$CONFIG['page']['moduls'][$varSQL['modul']]['activeCondition'] = $condition;
	$CONFIG['page']['moduls'][$varSQL['modul']]['activeConditionPDO'] = $aConditionPDO;
	if($condition == '') $rowsortable = 1;
	
	####################
	####################



	// Set Parameter
	$query = $CONFIG['dbconn']->prepare('
										SELECT DISTINCT(' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid)
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_user 
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_uid
					
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages 
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid
					
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages 
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_count2lang
											
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_clid = (:id_clid)
											' . $conditionParent . '
											' . $condition . '
										GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':id_clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
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
	if($limit == 0) $limit = 10;
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
	// Export mode
	#####################
	// Filter
	$conditionParent = $CONFIG['USER']['activeConditionParent'][$CONFIG['page']['modul']];
	$aConditionParentPDO = $CONFIG['USER']['activeConditionParentPDO'][$CONFIG['page']['modul']];
	$condition = "";
	if($CONFIG['page']['exportdata'] == 'filter') $condition = $CONFIG['USER']['activeCondition'][$CONFIG['page']['modul']];
	$aConditionPDO = $CONFIG['USER']['activeConditionPDO'][$CONFIG['page']['modul']];
	
	$order = '' . $CONFIG['db'][0]['prefix'] . 'system_user.lastname';
	$dir = 'ASC';
	$queryLimit = '';

}


// SELECT
$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid,
										' . $CONFIG['db'][0]['prefix'] . 'system_user.firstname,
										' . $CONFIG['db'][0]['prefix'] . 'system_user.lastname,
										' . $CONFIG['db'][0]['prefix'] . 'system_user.email,
										' . $CONFIG['db'][0]['prefix'] . 'system_user.id_r,
										' . $CONFIG['db'][0]['prefix'] . 'system_roles.role
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_user 
										
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_uid
											
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages 
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid
					
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages 
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_count2lang
											
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_roles 
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_r = ' . $CONFIG['db'][0]['prefix'] . 'system_roles.id_r
	
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_clid = (:id_clid)
										' . $conditionParent . '
										' . $condition . '
									GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid
									ORDER BY ' . $order . ' ' . $dir . '
									' . $queryLimit . '
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':id_clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
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
	$row['role'] = (isset($TEXT[$row['role']])) ? $TEXT[$row['role']] : $row['role'];

	$rowExp = array();

	######################################################
	$rowTmp['countries'] = '';
	$rowExp['countries'] = '';

	$query2 = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country,
											' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_count2lang
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni  
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid

										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:clid)
												OR ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:nul))
											
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:clid)
												OR ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:nul))
											
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid = (:id)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country, ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
										');
	$query2->bindValue(':id', $row['id_uid'], PDO::PARAM_INT);
	$query2->bindValue(':nul', 0, PDO::PARAM_INT);
	$query2->bindValue(':clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
	$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query2->execute();
	$rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$num2 = $query2->rowCount();
	foreach($rows2 as $row2){
		$row2['country'] = (isset($TEXT[$row2['country']])) ? $TEXT[$row2['country']] : $row2['country'];
		$row2['language'] = (isset($TEXT[$row2['language']])) ? $TEXT[$row2['language']] : $row2['language'];
		
		$rowTmp['countries'] .= '<div>' . $row2['country'] . ' (' . $row2['language'] . ')</div>';
		$rowExp['countries'] .= $row2['country'] . " (" . $row2['language'] . ")\n";
	}
	$rowExp['countries'] = trim($rowExp['countries']);
	######################################################



	$aFields = array();
	$aFields['yesNo2Text'] = array();
	$row = setValuesRead($row, $aFields, 0,0,0);


	$contentRaw->rows[$i] = $row + $rowExp;
	$contentRaw->rows[$i]['id'] = $row['id_uid'];
	
	$row['countries'] = $rowTmp['countries'];

	foreach($row as $k=>$v){
		$row[$k] = '<div class="gridCellFold">' . $v . '</div>';
	}
	
	$row['id_uid'] = strip_tags($row['id_uid']);
	
	$content->rows[$i] = $row;
	$content->rows[$i]['id'] = $row['id_uid'];

	$i++;
} 


if($varSQL['mode'] == 'grid'){
	#####################
	// Grid mode
	#####################
	echo json_encode($content);
	
}else if($varSQL['mode'] == 'export'){
	#####################
	// Export mode
	#####################
}


?>