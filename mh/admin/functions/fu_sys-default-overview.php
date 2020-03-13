<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();
if(!isset($varSQL['mode'])) $varSQL['mode'] = 'grid';

$functionPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'];
$functionFile = 'fu-' . $CONFIG['aModul']['modul_name'] . '-overview.php';
$functionFileOne2n = 'fu-' . $CONFIG['aModul']['modul_name'] . '-overview-one2n.php';
$functionFilePre = 'fu-' . $CONFIG['aModul']['modul_name'] . '-overview-pre.php';
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php');

if(file_exists($functionPath . $functionFile)){
	include_once($functionPath . $functionFile);
	
}else{
	#########################################

	if(file_exists($functionPath . $functionFilePre)){ 
		include_once($functionPath . $functionFilePre);
	}

	#########################################

	############################################
	// SETTINGS
	############################################
	$table = ($CONFIG['aModul']['table_suffix'] == 0) ? $CONFIG['aModul']['table_name'] : $CONFIG['aModul']['table_name'] . 'uni';

	############################################
	// grid mode
	if($varSQL['mode'] == 'grid'){
		####################
		// grid filter
		$condition = '';
		$aConditionPDO = array();
		$aConditionPDO['count'] = array($CONFIG['settings']['selectCountry'], 'd');
		$aConditionPDO['lang'] = array($CONFIG['settings']['selectLanguage'], 'd');
		$aConditionPDO['dev'] = array($CONFIG['settings']['selectDevice'], 'd');
		$aConditionPDO['nultime'] = array('0000-00-00 00:00:00', 's');
		
		foreach($CONFIG['aModul']['colmodel'] as $field){
			$field['table'] = ($field['t_suffix'] == 0) ? $field['t_table'] : $field['t_table'] . 'uni';
			// integer
			if(isset($varSQL[$field['index']]) && ($field['format'] == 'i' || $field['format'] == 'b')){
				if($varSQL[$field['index']] != ''){
					$condition .= ' AND ' . $CONFIG['db'][0]['prefix'] . $field['table'] . '.' . $field['t_colname'] . ' = (:'.$field['index'].')';
					$aConditionPDO[$field['index']] = array($varSQL[$field['index']], 'd');
				}
			}
			
			// string to integer
			if(isset($varSQL[$field['index']]) && $field['format'] == 'si'){
				if($varSQL[$field['index']] != ''){
					$condition .= ' AND ' . $CONFIG['db'][0]['prefix'] . $field['searchoptions']['searchcolumn'] . ' = (:'.$field['index'].')';
					$aConditionPDO[$field['index']] = array($varSQL[$field['index']], 'd');
				}
			}
			
			// string
			if(isset($varSQL[$field['index']]) && ($field['format'] == 's' || $field['format'] == 'f' || $field['format'] == 'c')){
				if($varSQL[$field['index']] != ''){
					$condition .= ' AND ' . $CONFIG['db'][0]['prefix'] . $field['table'] . '.' . $field['t_colname'] . ' LIKE (:'.$field['index'].')';
					$aConditionPDO[$field['index']] = array($varSQL[$field['index']], 'sl');
				}
			}
			
			// date
			if(isset($varSQL[$field['index']]) && $field['format'] == 'd'){
				if($varSQL[$field['index']] != ''){
					$dateCode = (isset($CONFIG['user']['countries'][$CONFIG['settings']['selectCountry']]['date_code'])) ? $CONFIG['user']['countries'][$CONFIG['settings']['selectCountry']]['date_code'] : $CONFIG['system']['date_code'];
					$timeCode = (isset($CONFIG['user']['countries'][$CONFIG['settings']['selectCountry']]['time_code'])) ? $CONFIG['user']['countries'][$CONFIG['settings']['selectCountry']]['time_code'] : $CONFIG['system']['time_code'];
					
					if(array_key_exists('date', $field['val2read']) || array_key_exists('datetime', $field['val2read'])){
						$search = "/([a-zA-Z0-9\*\?]+)([^a-zA-Z0-9\*\?]+)([a-zA-Z0-9\*\?]+)([^a-zA-Z0-9\*\?]+)([a-zA-Z0-9\*\?]+)$/siU";
						preg_match($search, $dateCode, $aDateCode);
						preg_match($search, $varSQL[$field['index']], $aSearchDate);
						
						if(count($aSearchDate) == 6){
							$aSearchStr = array();
							$aSearchStr[$aDateCode[1]] = $aSearchDate[1];
							$aSearchStr[$aDateCode[3]] = $aSearchDate[3];
							$aSearchStr[$aDateCode[5]] = $aSearchDate[5];
							
							// y -> Y
							if(isset($aSearchStr['y']) && !isset($aSearchStr['Y'])) $aSearchStr['Y'] = $aSearchStr['y'];
							// M -> m
							if(isset($aSearchStr['M']) && !isset($aSearchStr['m'])) $aSearchStr['m'] = $CONFIG['system']['months2num'][$aSearchStr['M']];
							// F -> m
							if(isset($aSearchStr['F']) && !isset($aSearchStr['m'])) $aSearchStr['m'] = $CONFIG['system']['monthslong2num'][$aSearchStr['F']];
							// 1st -> 2stellig /2 -> 4
							if(strlen($aSearchStr['d']) == 1) $aSearchStr['d'] = ($aSearchStr['d'] == '*' || $aSearchStr['d'] == '?') ? '*' . $aSearchStr['d'] : '0' . $aSearchStr['d'];
							if(strlen($aSearchStr['m']) == 1) $aSearchStr['m'] = ($aSearchStr['m'] == '*' || $aSearchStr['m'] == '?') ? '*' . $aSearchStr['m'] : '0' . $aSearchStr['m'];
							if(strlen($aSearchStr['Y']) == 2) $aSearchStr['Y'] = '**' . $aSearchStr['Y'];
							
							$dateSearch = $aSearchStr['Y'] . '-' . $aSearchStr['m'] . '-' . $aSearchStr['d'];
							$dateSearch = str_replace('?', '_', $dateSearch);
							$dateSearch = str_replace('*', '_', $dateSearch);
		
							$condition .= ' AND ' . $CONFIG['db'][0]['prefix'] . $field['table'] . '.' . $field['t_colname'] . ' LIKE (:'.$field['index'].')';
							$aConditionPDO[$field['index']] = array($dateSearch, 'sl');
						}
					}
				}
			}
		}
	
		####################
		// filter for childmodul
		$conditionParent = '';
		$aConditionParentPDO = array();
		if($CONFIG['page']['id_mod_parent'] != 0){
			if(isset($CONFIG['aModul']['cond_parent']['script'])){
				if(file_exists($functionPath . $CONFIG['aModul']['cond_parent']['script'])) include_once($functionPath . $CONFIG['aModul']['cond_parent']['script']);
			}else{
				if(!isset($CONFIG['aModul']['cond_parent']['condition'])) $CONFIG['aModul']['cond_parent']['condition'] = array(array());
				foreach($CONFIG['aModul']['cond_parent']['condition'] as $aCondition){
					if(!isset($aCondition['table'])) $aCondition['table'] = $CONFIG['aModul']['table_name'];
					if(!isset($aCondition['suffix'])) $aCondition['suffix'] = 1;
					if(!isset($aCondition['field'])) $aCondition['field'] = 'id_data_parent';
					if(!isset($aCondition['value'])) $aCondition['value'] = 'id_data_parent';
					if(!isset($aCondition['valtype'])) $aCondition['valtype'] = 'd';
					
					$conditionParentTable = ($aCondition['suffix'] == 1) ? $aCondition['table'] . 'uni' : $aCondition['table'];
					$conditionParent .= ' AND ' . $CONFIG['db'][0]['prefix'] . $conditionParentTable . '.' . $aCondition['field'] . ' = (:' . $aCondition['value'] . ')';
					if(!in_array($aCondition['value'], $aConditionPDO)) $aConditionParentPDO[$aCondition['value']] = array($CONFIG['page'][$aCondition['value']], $aCondition['valtype']);
				}
			}
		}



	
//		$CONFIG['page']['moduls'][$varSQL['modul']]['activeConditionParent'] = $conditionParent;
//		$CONFIG['page']['moduls'][$varSQL['modul']]['activeConditionParentPDO'] = $aConditionParentPDO;
//		$CONFIG['page']['moduls'][$varSQL['modul']]['activeCondition'] = $condition;
//		$CONFIG['page']['moduls'][$varSQL['modul']]['activeConditionPDO'] = $aConditionPDO;
		$rowsortable = ($condition == '') ? 1 : 0;
		####################

		####################
		// Set Parameter
		$queryStr = 'SELECT ';
		$queryStr .= 'DISTINCT(' . $CONFIG['db'][0]['prefix'] . $table . '.' . $CONFIG['aModul']['primarykey'] . ') ';
		$queryStr .= 'FROM ' . $CONFIG['db'][0]['prefix'] . $table . ' ';
		foreach($CONFIG['aModul']['table_join'] as $alias => $aJoin){
			$queryStr .= $aJoin['type'] . ' JOIN ' . $CONFIG['db'][0]['prefix'] . $aJoin['table'] . ' AS ' . $CONFIG['db'][0]['prefix'] . $alias . ' ';
			
			if($aJoin['condition_default'] == 0 && (!isset($aJoin['conditions']) || count($aJoin['conditions']) == 0)){
				$queryStr .= 'ON ' . $CONFIG['db'][0]['prefix'] . $aJoin['on']['left'] . ' = ' . $CONFIG['db'][0]['prefix'] . $aJoin['on']['right'] . ' ';
			}
			
			if($aJoin['condition_default'] == 1){
				$queryStr .= 'ON ' . $CONFIG['db'][0]['prefix'] . $alias . '.id_count = (:count) ';
				$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $alias . '.id_lang = (:lang) ';
				$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $alias . '.id_dev = (:dev) ';
				$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $alias . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ') ';
				$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $aJoin['on']['left'] . ' = ' . $CONFIG['db'][0]['prefix'] . $aJoin['on']['right'] . ' ';
				$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $alias . '.del = (:nultime) ';
			}
			
			if(isset($aJoin['condition_text']) && $aJoin['condition_text'] != ''){
				$queryStr .= str_replace('##tabprefix##', $CONFIG['db'][0]['prefix'], $aJoin['condition_text']) . ' ';
			}
			
			if(isset($aJoin['conditions'])){
				$nJ = 0;
				foreach($aJoin['conditions'] as $aCondition){
					$nJ++;
					$cond_value = ($aCondition['bind_variable'] != '') ? parseVariableValue(parseVariableName($aCondition['bind_variable'])) : $aCondition['bind_value'];
					
					if($nJ == 1 && $aJoin['condition_default'] == 0) $aCondition['type'] = 'ON';
					if(isset($aCondition['bind_column']) && count($aCondition['bind_column']) > 0){
						$queryStr .= $aCondition['type'] . ' ' . $CONFIG['db'][0]['prefix'] . $aCondition['bind_column']['left'] . ' ' . $aCondition['op'] . ' ' . $CONFIG['db'][0]['prefix'] . $aCondition['bind_column']['right'] . ' ';
					}else{
						if($aCondition['column'] != '') $queryStr .= $aCondition['type'] . ' ' . $CONFIG['db'][0]['prefix'] . $aCondition['column'] . ' ' . $aCondition['op'] . ' ' . '(:' . $aCondition['bind_param'] . ') ';
						$aConditionPDO[$aCondition['bind_param']] = array($cond_value, $aCondition['bind_type']);
					}
				}
			}
		}
		$queryStr .= 'WHERE ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count = (:count) ';
		$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang = (:lang) ';
		$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev = (:dev) ';
		$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ') ';
		$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.restricted_all IN ("0") ';
		$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.del = (:nultime) ';
		$queryStr .= $condition . ' ';
		$queryStr .= $conditionParent . ' ';
	
		if(isset($CONFIG['aModul']['addoptions']['specCondRead'])){
			foreach($CONFIG['aModul']['addoptions']['specCondRead'] as $aSpecCondRead){
				$valSpec = '';
				if($aSpecCondRead['typeUserArray'] == 'value') $valSpec = $CONFIG['user'][$aSpecCondRead['keyUserArray']];
				if($aSpecCondRead['typeUserArray'] == 'arrayKey') $valSpec = '(' . implode(',', array_keys($CONFIG['user'][$aSpecCondRead['keyUserArray']])) . ')';
				if($aSpecCondRead['typeUserArray'] == 'arrayValue') $valSpec = '(' . implode(',', $CONFIG['user'][$aSpecCondRead['keyUserArray']]) . ')';
				$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $aSpecCondRead['table'] . '.' . $aSpecCondRead['column'] . ' ' . $aSpecCondRead['op'] . ' ' . $valSpec . ' ';
			}
		}
		if(isset($specialCondition)) $queryStr .= $specialCondition;
		
		$queryStr .= 'GROUP BY ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $CONFIG['aModul']['primarykey'] . ' ';
		
		
		$query = $CONFIG['dbconn'][0]->prepare($queryStr);
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
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		$num = $query->rowCount();
	
		$numAll = $num;
		$limit = $varSQL['rows']; 
		$total_pages = ($numAll > 0) ? ceil($numAll/$limit) : 0; 
		$page = ($varSQL['page'] > $total_pages) ? $total_pages : $varSQL['page']; 
		$start = (($limit * $page - $limit) < 0) ? 0 : $limit * $page - $limit;
		$order = ($varSQL['sidx'] == '') ? 1 : $varSQL['sidx']; 
		$dir = $varSQL['sord']; 
		$queryLimit = 'LIMIT ' . $start . ', ' . $limit . '';
	}
	############################################
	

	############################################
	// export mode
	if($varSQL['mode'] == 'export'){
//	#####################
//	// settings for export mode
//	#####################
//	// Filter
//	$conditionParent = $CONFIG['page']['moduls'][$varSQL['modul']]['activeConditionParent'];
//	$aConditionParentPDO = $CONFIG['page']['moduls'][$varSQL['modul']]['activeConditionParentPDO'];
//	$condition = "";
//	if($varSQL['exportdata'] == 'filter') $condition = $CONFIG['page']['moduls'][$varSQL['modul']]['activeCondition'];
//	$aConditionPDO = $CONFIG['page']['moduls'][$varSQL['modul']]['activeConditionPDO'];
//	
//	$order = '' . $CONFIG['db'][0]['prefix'] . $field['table'] . '.' . $rowsF[0]['t_colname'];
//	$dir = 'ASC';
//	$queryLimit = '';
	}



	############################################
	// GET DATA
	############################################
	$queryStr = 'SELECT ';
	$queryStr .= '' . $CONFIG['db'][0]['prefix'] . $table . '.restricted_all, ';
	foreach($CONFIG['aModul']['colmodel'] as $field){
		if($field['t_array'] == 0 || array_key_exists('file', $field['val2read'])){
			$field['table'] = ($field['t_suffix'] == 0) ? $field['t_table'] : $field['t_table'] . 'uni';
			if($field['t_table'] != '' && $field['t_colname'] != '') $queryStr .= $CONFIG['db'][0]['prefix'] . $field['table'] . '.' . $field['t_colname'] . ', '; 
		}
	}
	$queryStr = rtrim($queryStr, ', ');
	$queryStr .= ' ';
	$queryStr .= 'FROM ' . $CONFIG['db'][0]['prefix'] . $table . ' ';
	foreach($CONFIG['aModul']['table_join'] as $alias => $aJoin){
		$queryStr .= $aJoin['type'] . ' JOIN ' . $CONFIG['db'][0]['prefix'] . $aJoin['table'] . ' AS ' . $CONFIG['db'][0]['prefix'] . $alias . ' ';
		
		if($aJoin['condition_default'] == 0 && (!isset($aJoin['conditions']) || count($aJoin['conditions']) == 0)){
			$queryStr .= 'ON ' . $CONFIG['db'][0]['prefix'] . $aJoin['on']['left'] . ' = ' . $CONFIG['db'][0]['prefix'] . $aJoin['on']['right'] . ' ';
		}
		
		if($aJoin['condition_default'] == 1){
			$queryStr .= 'ON ' . $CONFIG['db'][0]['prefix'] . $alias . '.id_count = (:count) ';
			$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $alias . '.id_lang = (:lang) ';
			$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $alias . '.id_dev = (:dev) ';
			$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $alias . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ') ';
			$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $aJoin['on']['left'] . ' = ' . $CONFIG['db'][0]['prefix'] . $aJoin['on']['right'] . ' ';
			$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $alias . '.del = (:nultime) ';
		}
		
		if(isset($aJoin['condition_text']) && $aJoin['condition_text'] != ''){
			$queryStr .= str_replace('##tabprefix##', $CONFIG['db'][0]['prefix'], $aJoin['condition_text']) . ' ';
		}
		
		if(isset($aJoin['conditions'])){
			$nJ = 0;
			foreach($aJoin['conditions'] as $aCondition){
				$nJ++;
				$cond_value = ($aCondition['bind_variable'] != '') ? parseVariableValue(parseVariableName($aCondition['bind_variable'])) : $aCondition['bind_value'];
				
				if($nJ == 1 && $aJoin['condition_default'] == 0) $aCondition['type'] = 'ON';
				if(isset($aCondition['bind_column']) && count($aCondition['bind_column']) > 0){
					$queryStr .= $aCondition['type'] . ' ' . $CONFIG['db'][0]['prefix'] . $aCondition['bind_column']['left'] . ' ' . $aCondition['op'] . ' ' . $CONFIG['db'][0]['prefix'] . $aCondition['bind_column']['right'] . ' ';
				}else{
					if($aCondition['column'] != '') $queryStr .= $aCondition['type'] . ' ' . $CONFIG['db'][0]['prefix'] . $aCondition['column'] . ' ' . $aCondition['op'] . ' ' . '(:' . $aCondition['bind_param'] . ') ';
					$aConditionPDO[$aCondition['bind_param']] = array($cond_value, $aCondition['bind_type']);
				}
			}
		}
	}
	$queryStr .= 'WHERE ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count = (:count) ';
	$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang = (:lang) ';
	$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev = (:dev) ';
	$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ') ';
	$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.restricted_all IN ("0") ';
	$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.del = (:nultime) ';
	$queryStr .= $condition . ' ';
	$queryStr .= $conditionParent . ' ';
	
	if(isset($CONFIG['aModul']['addoptions']['specCondRead'])){
		foreach($CONFIG['aModul']['addoptions']['specCondRead'] as $aSpecCondRead){
			$valSpec = '';
			if($aSpecCondRead['typeUserArray'] == 'value') $valSpec = $CONFIG['user'][$aSpecCondRead['keyUserArray']];
			if($aSpecCondRead['typeUserArray'] == 'arrayKey') $valSpec = '(' . implode(',', array_keys($CONFIG['user'][$aSpecCondRead['keyUserArray']])) . ')';
			if($aSpecCondRead['typeUserArray'] == 'arrayValue') $valSpec = '(' . implode(',', $CONFIG['user'][$aSpecCondRead['keyUserArray']]) . ')';
			$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $aSpecCondRead['table'] . '.' . $aSpecCondRead['column'] . ' ' . $aSpecCondRead['op'] . ' ' . $valSpec . ' ';
		}
	}
	if(isset($specialCondition)) $queryStr .= $specialCondition;
	
	$queryStr .= 'GROUP BY ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $CONFIG['aModul']['primarykey'] . ' ';

	$queryStr .= 'ORDER BY ' . $order . ' ' . $dir . ' ';
	$queryStr .= $queryLimit . ' ';
	

	$query = $CONFIG['dbconn'][0]->prepare($queryStr);
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
//	$rowExp = array();

		#########################################
		// read 1 to n fields
		if(file_exists($functionPath . $functionFileOne2n)){
			include($functionPath . $functionFileOne2n);
		}else{
			foreach($CONFIG['aModul']['colmodel'] as $field){
				if($field['t_array'] == 1 && $field['t_array_options']['primarykey'] != '' && !array_key_exists('file', $field['val2read'])){
					$conditionN = '';
					$aConditionN = array();
					if(!isset($field['t_array_options']['cond_default_main'])) $field['t_array_options']['cond_default_main'] = 1;
					if($field['t_array_options']['cond_default_main'] == '1'){
						$aConditionN['count'] = array($CONFIG['settings']['selectCountry'], 'd');
						$aConditionN['lang'] = array($CONFIG['settings']['selectLanguage'], 'd');
						$aConditionN['dev'] = array($CONFIG['settings']['selectDevice'], 'd');
					}
					$aConditionN['nultime'] = array('0000-00-00 00:00:00', 's');
					$conditionParentN = '';
					$aConditionParentN = array();
					
					$row[$field['index']] = '';
					$field['table'] = ($field['t_suffix'] == 0) ? $field['t_table'] : $field['t_table'] . 'uni';
					
					if(!isset($field['t_array_options']['output'])) $field['t_array_options']['output'] = array();
					if(!isset($field['t_array_options']['delimiter'])) $field['t_array_options']['delimiter'] = ' ';
					if(!isset($field['t_array_options']['order'])) $field['t_array_options']['order'] = array();
					if(!isset($field['t_array_options']['group'])) $field['t_array_options']['group'] = '';
					if(!isset($field['t_array_options']['condition'])) $field['t_array_options']['condition'] = array();
					
					$queryStrN = 'SELECT ';
					$queryStrN .= $CONFIG['db'][0]['prefix'] . $field['table'] . '.' . $field['t_array_options']['primarykey'] . ', '; 
					foreach($field['t_array_options']['output'] as $f){
						$queryStrN .= $CONFIG['db'][0]['prefix'] . $field['table'] . '.' . $f . ', '; 
					}
					$queryStrN = rtrim($queryStrN, ', ');
					$queryStrN .= ' ';
					$queryStrN .= 'FROM ' . $CONFIG['db'][0]['prefix'] . $field['table'] . ' ';
					foreach($field['t_join'] as $alias => $aJoin){
						$queryStrN .= $aJoin['type'] . ' JOIN ' . $CONFIG['db'][0]['prefix'] . $aJoin['table'] . ' AS ' . $CONFIG['db'][0]['prefix'] . $alias . ' ';
						
						if($aJoin['condition_default'] == 0 && (!isset($aJoin['conditions']) || count($aJoin['conditions']) == 0)){
							$queryStrN .= 'ON ' . $CONFIG['db'][0]['prefix'] . $aJoin['on']['left'] . ' = ' . $CONFIG['db'][0]['prefix'] . $aJoin['on']['right'] . ' ';
						}
						
						if($aJoin['condition_default'] == 1){
							$queryStrN .= 'ON ' . $CONFIG['db'][0]['prefix'] . $alias . '.id_count = (:count) ';
							$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . $alias . '.id_lang = (:lang) ';
							$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . $alias . '.id_dev = (:dev) ';
							$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . $alias . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ') ';
							$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . $aJoin['on']['left'] . ' = ' . $CONFIG['db'][0]['prefix'] . $aJoin['on']['right'] . ' ';
							$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . $alias . '.del = (:nultime) ';
						}
						
						if(isset($aJoin['condition_text']) && $aJoin['condition_text'] != ''){
							$queryStrN .= str_replace('##tabprefix##', $CONFIG['db'][0]['prefix'], $aJoin['condition_text']) . ' ';
						}
						
						if(isset($aJoin['conditions'])){
							$nJ = 0;
							foreach($aJoin['conditions'] as $aCondition){
								$nJ++;
								$cond_value = ($aCondition['bind_variable'] != '') ? parseVariableValue(parseVariableName($aCondition['bind_variable'])) : $aCondition['bind_value'];
								
								if($nJ == 1 && $aJoin['condition_default'] == 0) $aCondition['type'] = 'ON';
								if(isset($aCondition['bind_column']) && count($aCondition['bind_column']) > 0){
									$queryStrN .= $aCondition['type'] . ' ' . $CONFIG['db'][0]['prefix'] . $aCondition['bind_column']['left'] . ' ' . $aCondition['op'] . ' ' . $CONFIG['db'][0]['prefix'] . $aCondition['bind_column']['right'] . ' ';
								}else{
									if($aCondition['column'] != '') $queryStrN .= $aCondition['type'] . ' ' . $CONFIG['db'][0]['prefix'] . $aCondition['column'] . ' ' . $aCondition['op'] . ' ' . '(:' . $aCondition['bind_param'] . ') ';
									$aConditionN[$aCondition['bind_param']] = array($cond_value, $aCondition['bind_type']);
								}
							}
						}
						$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . $alias . '.del = (:nultime) ';
					}

					if($field['t_array_options']['cond_default_main'] == '1'){
						$queryStrN .= 'WHERE ' . $CONFIG['db'][0]['prefix'] . $field['table'] . '.id_count = (:count) ';
						$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . $field['table'] . '.id_lang = (:lang) ';
						$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . $field['table'] . '.id_dev = (:dev) ';
						$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . $field['table'] . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ') ';
						$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . $field['table'] . '.del = (:nultime) ';
					}else{
						$queryStrN .= 'WHERE ' . $CONFIG['db'][0]['prefix'] . $field['table'] . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ') ';
						$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . $field['table'] . '.del = (:nultime) ';
					}
					foreach($field['t_array_options']['condition'] as $aCondition){
						$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . $aCondition['table'] . '.' . $aCondition['col'] . ' = ' . $row[$aCondition['val']] . ' ';
						
					}
					$queryStrN .= $conditionN . ' ';
					$queryStrN .= $conditionParentN . ' ';
					if($field['t_array_options']['group'] != '') $queryStrN .= 'GROUP BY ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $field['t_array_options']['group'] . ' ';

					$queryStrN .= 'GROUP BY ' . $CONFIG['db'][0]['prefix'] . $field['table'] . '.' . $field['t_array_options']['primarykey'] . ' ';

					if(count($field['t_array_options']['order']) > 0){
						$queryStrN .= 'ORDER BY ';
						foreach($field['t_array_options']['order'] as $aOrder){
							$queryStrN .= $CONFIG['db'][0]['prefix'] . $field['table'] . '.' . $aOrder['col'] . ' ' . $aOrder['dir'] . ', ';
						}
						$queryStrN = rtrim($queryStrN, ', ');
						$queryStrN .= ' ';
					}

					$queryN = $CONFIG['dbconn'][0]->prepare($queryStrN);
					foreach($aConditionN as $k=>$v){
						if($v[1] == 'd'){
							$queryN->bindValue(':'.$k, $v[0], PDO::PARAM_INT);
						}else if($v[1] == 'sl'){
							$queryN->bindValue(':'.$k, '%'.$v[0].'%', PDO::PARAM_STR);
						}else if($v[1] == 'slb'){
							$queryN->bindValue(':'.$k, '%'.$v[0], PDO::PARAM_STR);
						}else if($v[1] == 'sle'){
							$queryN->bindValue(':'.$k, $v[0].'%', PDO::PARAM_STR);
						}else{
							$queryN->bindValue(':'.$k, $v[0], PDO::PARAM_STR);
						}
					}
					foreach($aConditionParentN as $k=>$v){
						if($v[1] == 'd'){
							$queryN->bindValue(':'.$k, $v[0], PDO::PARAM_INT);
						}else if($v[1] == 'sl'){
							$queryN->bindValue(':'.$k, '%'.$v[0].'%', PDO::PARAM_STR);
						}else if($v[1] == 'slb'){
							$queryN->bindValue(':'.$k, '%'.$v[0], PDO::PARAM_STR);
						}else if($v[1] == 'sle'){
							$queryN->bindValue(':'.$k, $v[0].'%', PDO::PARAM_STR);
						}else{
							$queryN->bindValue(':'.$k, $v[0], PDO::PARAM_STR);
						}
					}
					$queryN->execute();
					$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
					$numN = $queryN->rowCount();
	
					foreach($rowsN as $rowN){
						$outN = array();
						foreach($field['t_array_options']['output'] as $f){
							array_push($outN, $rowN[$f]);
						}
						$row[$field['index']] .= '<div>' . implode($field['t_array_options']['delimiter'], $outN) . '</div>';;
					}
				}
			}
		}
		#########################################



		$aArgs = array();
		$aArgs['data'] = $row;
		$aArgs['id_count'] = $CONFIG['settings']['selectCountry'];
		$aArgs['id_lang'] = $CONFIG['settings']['selectLanguage'];
		$aArgs['id_dev'] = $CONFIG['settings']['selectDevice'];
		$aArgs['usesystem'] = 1;
		$aArgs['fields'] = array();
		foreach($CONFIG['aModul']['colmodel'] as $field){
			if(count($field['val2read']) > 0){
				foreach($field['val2read'] as $type => $aVal2read){
					if(!array_key_exists($type, $aArgs['fields'])) $aArgs['fields'][$type] = array();
					$aArgs['fields'][$type][$field['index']] = $aVal2read;
				}
			}
		}
		$row = setValuesRead($aArgs);
		
		

		
//
//
//	$contentRaw->rows[$i] = $row + $rowExp;
//	$contentRaw->rows[$i]['id'] = $row['id_devid'];
	
		foreach($row as $k=>$v){
			$v = (is_array($v)) ? implode(',', $v) : $v;
			$row[$k] = '<div class="gridCellFold">' . $v . '</div>';
		}
			
		$row[$CONFIG['aModul']['primarykey']] = strip_tags($row[$CONFIG['aModul']['primarykey']]);
		
		$content->rows[$i] = $row;
		$content->rows[$i]['id'] = $row[$CONFIG['aModul']['primarykey']];
	
		$i++;
	} 


	############################################
	// OUTPUT
	############################################
	#####################
	// grid mode
	if($varSQL['mode'] == 'grid'){
		echo json_encode($content); 
	}
	

	#####################
	// export mode
	if($varSQL['mode'] == 'export'){
	}
}


?>