<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php'); 
$varSQL = getPostData();
$CONFIG['page'] = json_decode($varSQL['pageConfig'], true);
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php'); 

if(!isset($varSQL['mode'])) $varSQL['mode'] = 'grid';
$modulname = $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'];
$rowsortable = 0;
$primekey = 'id_countid';


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
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_index,
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
		if(isset($varSQL[$rowF['g_index']]) && $rowF['format'] == 'i'){
			if($varSQL[$rowF['g_index']] != ''){
				$condition .= ' AND ' . $CONFIG['db'][0]['prefix'] . $rowF['table_name'] . '_uni.' . $rowF['tab_colname'] . ' = (:'.$rowF['g_index'].')';
				$aConditionPDO[$rowF['g_index']] = array($varSQL[$rowF['g_index']], 'd');
			}
		}
		
		// string
		if(isset($varSQL[$rowF['g_index']]) && ($rowF['format'] == 's' || $rowF['format'] == 'f' || $rowF['format'] == 'c')){
			if($varSQL[$rowF['g_index']] != ''){
				$condition .= ' AND ' . $CONFIG['db'][0]['prefix'] . $rowF['table_name'] . '_uni.' . $rowF['tab_colname'] . ' LIKE (:'.$rowF['g_index'].')';
				$aConditionPDO[$rowF['g_index']] = array($varSQL[$rowF['g_index']], 'sl');
			}
		}
		
		// date
		if(isset($varSQL[$rowF['g_index']]) && $rowF['format'] == 'd'){
			if($varSQL[$rowF['g_index']] != ''){
				$aB = explode('.', $varSQL[$rowF['g_index']]);
				$rev = array_reverse($aB);
				$sB = implode('-', $rev);
				$condition .= ' AND ' . $CONFIG['db'][0]['prefix'] . $rowF['table_name'] . '_uni.' . $rowF['tab_colname'] . ' LIKE (:'.$rowF['g_index'].')';
				$aConditionPDO[$rowF['g_index']] = array($sB, 'sl');
			}
		}
	}


	####################
	// special filter

	if(isset($varSQL['language'])){
		if($varSQL['language'] != ''){
			$condition .= ' AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid = (:language)';
			$aConditionPDO['language'] = array($varSQL['language'], 'd');
		}
	}

	if($CONFIG['USER']['right_country'] == 0 || $CONFIG['USER']['right_country'] == 1){
		$condition .= ' AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid IN ('. implode(',', array_keys($CONFIG['USER']['countries'])) . ')';
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
										SELECT DISTINCT(' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid)
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni 
					
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages 
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid
					
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_timezones 
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_tz = ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.id_tz
		
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_date 
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_fd = ' . $CONFIG['db'][0]['prefix'] . 'system_format_date.id_fd
		
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_time 
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_ft = ' . $CONFIG['db'][0]['prefix'] . 'system_format_time.id_ft
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:count)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:lang)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:dev)
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:cl)
												OR ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:nul))
											' . $conditionParent . '
											' . $condition . '
										GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':nul', 0, PDO::PARAM_INT);
	$query->bindValue(':count', $CONFIG['page']['moduls'][$varSQL['modul']]['activeSysCountry'], PDO::PARAM_INT);
	$query->bindValue(':lang', $CONFIG['page']['moduls'][$varSQL['modul']]['activeSysLanguage'], PDO::PARAM_INT);
	$query->bindValue(':dev', $CONFIG['page']['moduls'][$varSQL['modul']]['activeSysDevice'], PDO::PARAM_INT);
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
$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code_add,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_tz,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_fd,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_ft,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.currency,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.tax_name,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.tax,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.fee_name,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.sep_decimal,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.sep_thousand,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.email_sender,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.email_sendername,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.active,
										' . $CONFIG['db'][0]['prefix'] . 'system_timezones.timezone,
										' . $CONFIG['db'][0]['prefix'] . 'system_timezones.abbr,
										' . $CONFIG['db'][0]['prefix'] . 'system_format_date.format,
										' . $CONFIG['db'][0]['prefix'] . 'system_format_time.format AS format_time
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni 
				
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages 
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid
				
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_timezones 
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_tz = ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.id_tz
	
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_date 
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_fd = ' . $CONFIG['db'][0]['prefix'] . 'system_format_date.id_fd
	
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_time 
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_ft = ' . $CONFIG['db'][0]['prefix'] . 'system_format_time.id_ft
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:count)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:lang)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:dev)
										AND (' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:cl)
											OR ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:nul))
										' . $conditionParent . '
										' . $condition . '
									GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
									ORDER BY ' . $order . ' ' . $dir . '
									' . $queryLimit . '
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->bindValue(':count', $CONFIG['page']['moduls'][$varSQL['modul']]['activeSysCountry'], PDO::PARAM_INT);
$query->bindValue(':lang', $CONFIG['page']['moduls'][$varSQL['modul']]['activeSysLanguage'], PDO::PARAM_INT);
$query->bindValue(':dev', $CONFIG['page']['moduls'][$varSQL['modul']]['activeSysDevice'], PDO::PARAM_INT);
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

	######################################################
	$rowTmp['language'] = '';
	$rowExp['language'] = '';
	$query2 = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language,
											' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.default_
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni 
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid
											
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:count)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:lang)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:dev)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid = (:id)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.del = (:nultime)
										');
	$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query2->bindValue(':count', 0, PDO::PARAM_INT);
	$query2->bindValue(':lang', 0, PDO::PARAM_INT);
	$query2->bindValue(':dev', 0, PDO::PARAM_INT);
	$query2->bindValue(':id', $row['id_countid'], PDO::PARAM_INT);
	$query2->execute();
	$rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$num2 = $query2->rowCount();
	foreach($rows2 as $row2){
		$rowTmp['language'] .= '<div>' . $row2['language'] . '</div>';
		$rowExp['language'] .= $row2['language'] . "\n";
	}
	$rowExp['language'] = trim($rowExp['language']);
	######################################################



	$rowExp = array();
	$aFields = array();
	$aFields['floats'] = array('tax');
	$aFields['yesNo2Text'] = array('active');
	$row = setValuesRead($row, $aFields, $CONFIG['page']['moduls'][$varSQL['modul']]['activeSysCountry'], $CONFIG['page']['moduls'][$varSQL['modul']]['activeSysLanguage'], $CONFIG['page']['moduls'][$varSQL['modul']]['activeSysDevice']);

	$contentRaw->rows[$i] = $row + $rowExp;
	$contentRaw->rows[$i]['id'] = $row[$primekey];
		
	$row['language'] = $rowTmp['language'];

	foreach($row as $k=>$v){
		$row[$k] = '<div class="gridCellFold">' . $v . '</div>';
	}
	
	$row[$primekey] = strip_tags($row[$primekey]);
	
	$content->rows[$i] = $row;
	$content->rows[$i]['id'] = $row[$primekey];

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