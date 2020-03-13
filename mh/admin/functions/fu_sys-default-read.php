<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$functionPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'];
$functionFile = 'fu-' . $CONFIG['aModul']['modul_name'] . '-read.php';
$functionFileOne2n = 'fu-' . $CONFIG['aModul']['modul_name'] . '-read-one2n.php';
$functionFilePre = 'fu-' . $CONFIG['aModul']['modul_name'] . '-read-pre.php';
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php');


if(file_exists($functionPath . $functionFile)){
	include_once($functionPath . $functionFile);
	
}else{
	#########################################

	if(file_exists($functionPath . $functionFilePre)){ 
		include_once($functionPath . $functionFilePre);
	}

	#########################################

	$date = new DateTime();
	$now = $date->format('Y-m-d H:i:s');
	
	$out = array();
	
	$aArgsLV = array();
	$aArgsLV['type'] = 'temp';
	$aLocalVersions = localVariationsBuild($aArgsLV);
	$variation = ($CONFIG['settings']['formCountry'] == 0 && $CONFIG['settings']['formLanguage'] == 0 && $CONFIG['settings']['formDevice'] == 0) ? 'master' : 'local';
	
	$aNewRecord = array();
	$aArgs = array();
	$aArgs['fields'] = array();
	$aArgs['usesystem'] = 1;
	$aArgs['useboolean'] = array();
	foreach($CONFIG['aModul']['form'] as $aFieldsets){
		foreach($aFieldsets['fields'] as $field){
			if(in_array($field['specifications'][1], $CONFIG['system']['aFieldsAllowedSpecs'])){
				$aNewRecord[$field['colname']] = $field['default'];
				
				##################################################################
				// Handle uploadfield with multiple upload for editing single file
				if($field['type'] == 'file' && $CONFIG['page']['id_data'] != 0 && isset($CONFIG['aModul']['addoptions']['insertLoopField']) && $CONFIG['aModul']['addoptions']['insertLoopField'] != '') $field['default'] = NULL;
				##################################################################
					
				if($field['default'] == NULL) $aNewRecord[$field['colname']] = '';
				if($field['default'] == '[]') $aNewRecord[$field['colname']] = array(); 
				
				// set id_data_parent for child
				if($field['parentassign'] == 1 && isset($CONFIG['page'][$field['colname']])) $aNewRecord[$field['colname']] = $CONFIG['page'][$field['colname']];
				if($field['parentassign'] == 2) $aNewRecord[$field['colname']] = $CONFIG['page']['id_data_parent'];
	
				if($field['colname_save'] != $field['colname']){
					$aNewRecord[$field['colname_save']] = $field['default'];
					if($field['default'] == NULL) $aNewRecord[$field['colname_save']] = '';
					if($field['default'] == '[]') $aNewRecord[$field['colname_save']] = array();
					if($field['parentassign'] == 1 && isset($CONFIG['page'][$field['colname_save']])) $aNewRecord[$field['colname_save']] = $CONFIG['page'][$field['colname_save']];
				}
				
				if(count($field['val2read']) > 0){
					foreach($field['val2read'] as $type => $aVal2read){
						if(!array_key_exists($type, $aArgs['fields'])) $aArgs['fields'][$type] = array();
						$aArgs['fields'][$type][$field['colname']] = $aVal2read;
					}
				}
				
				if($field['type'] == 'boolean') array_push($aArgs['useboolean'], $field['colname']);
			}
		}
	}


	if($CONFIG['page']['id_data'] == 0){
		#######################################################
		// create new record
		#######################################################
		foreach($aLocalVersions as $aVersion){
			$outTmp = array();
			$outTmp['id_count'] = strval($aVersion[0]);
			$outTmp['id_lang'] = strval($aVersion[1]);
			$outTmp['id_dev'] = strval($aVersion[2]);
			$outTmp['id_cl'] = strval($CONFIG['activeSettings']['id_clid']);
			$outTmp['identifier'] = '';
			foreach($aNewRecord as $k => $v){
				$outTmp[$k] = $v;
			}
		
			$query2 = $CONFIG['dbconn'][0]->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
													(
													id,
													id_count,
													id_lang,
													id_dev,
													id_cl,
													id_uid,
													id_mod,
													modulname,
													data,
													create_at
													)
												VALUES
													(
													:id,
													:id_count,
													:id_lang,
													:id_dev,
													:id_cl,
													:uid,
													:id_mod,
													:modulname,
													:data,
													:create_at
													)
												');
			$query2->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
			$query2->bindValue(':id_count', $outTmp['id_count'], PDO::PARAM_INT);
			$query2->bindValue(':id_lang', $outTmp['id_lang'], PDO::PARAM_INT);
			$query2->bindValue(':id_dev', $outTmp['id_dev'], PDO::PARAM_INT);
			$query2->bindValue(':id_cl', $outTmp['id_cl'], PDO::PARAM_INT);
			$query2->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
			$query2->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
			$query2->bindValue(':modulname', $CONFIG['aModul']['modul_name'], PDO::PARAM_STR);
			$query2->bindValue(':data', json_encode($outTmp), PDO::PARAM_STR);
			$query2->bindValue(':create_at', $now, PDO::PARAM_STR);
			$query2->execute();

			if($aVersion[0] == $CONFIG['settings']['formCountry'] && $aVersion[1] == $CONFIG['settings']['formLanguage'] && $aVersion[2] == $CONFIG['settings']['formDevice']) $out = $outTmp;
		}
		
	}else{ 
		#######################################################
		// read / update existing record
		#######################################################
		
		############
		// build query string (first part without condition)
		$table = ($CONFIG['aModul']['table_suffix'] == 0) ? $CONFIG['aModul']['table_name'] : $CONFIG['aModul']['table_name'] . 'uni';
		$tableLoc = ($CONFIG['aModul']['table_suffix'] == 0) ? $CONFIG['aModul']['table_name'] : $CONFIG['aModul']['table_name'] . 'loc';
		$tableExt = ($CONFIG['aModul']['table_suffix'] == 0) ? $CONFIG['aModul']['table_name'] : $CONFIG['aModul']['table_name'] . 'ext';
	
		$queryStr = 'SELECT ';
		$queryStr .= '' . $CONFIG['db'][0]['prefix'] . $table . '.id_count, ';
		$queryStr .= '' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang, ';
		$queryStr .= '' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev, ';
		$queryStr .= '' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl, ';
		$queryStr .= '' . $CONFIG['db'][0]['prefix'] . $table . '.restricted_all, ';
		foreach($CONFIG['aModul']['form'] as $aFieldsets){
			foreach($aFieldsets['fields'] as $field){
				if(in_array($field['specifications'][1], $CONFIG['system']['aFieldsAllowedSpecs'])){
					if($field['array'] == 0 || $field['type'] == 'file'){
						$field['tableLoc'] = ($field['suffix'] == 0) ? $field['table'] : $field['table'] . 'loc';
						$field['tableExt'] = ($field['suffix'] == 0) ? $field['table'] : $field['table'] . 'ext';
						$field['table'] = ($field['suffix'] == 0) ? $field['table'] : $field['table'] . 'uni';
						if($field['table'] != '' && $field['colname'] != ''){
							$queryStr .= $CONFIG['db'][0]['prefix'] . $field['table'] . '.' . $field['colname'] . ', '; 
							if($field['type'] == 'boolean'){
								$queryStr .= $CONFIG['db'][0]['prefix'] . $field['tableLoc'] . '_bool.' . $field['colname'] . ' AS ' . $field['colname'] . '_loc, '; 
								$queryStr .= $CONFIG['db'][0]['prefix'] . $field['tableExt'] . '_bool.' . $field['colname'] . ' AS ' . $field['colname'] . '_ext, '; 
							}
						}
	
						if($field['colname_save'] != $field['colname']){
							$field['table_save'] = ($field['suffix_save'] == 0) ? $field['table_save'] : $field['table_save'] . 'uni';
							if($field['table_save'] != '' && $field['colname_save'] != '') $queryStr .= $CONFIG['db'][0]['prefix'] . $field['table_save'] . '.' . $field['colname_save'] . ', '; 
						}
					}
				}
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
				$queryStr .= 'ON ' . $CONFIG['db'][0]['prefix'] . $alias . '.id_count = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count ';
				$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $alias . '.id_lang = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang ';
				$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $alias . '.id_dev = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev ';
				$queryStr .= 'AND (' . $CONFIG['db'][0]['prefix'] . $alias . '.id_cl = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl ';
				$queryStr .= 'OR ' . $CONFIG['db'][0]['prefix'] . $alias . '.id_cl = 0) ';
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
		// additional join for boolean fields
		if(count($aArgs['useboolean']) > 0){
			$tableAlias = $tableLoc . '_bool';
			$queryStr .= 'LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . $tableLoc . ' AS ' . $CONFIG['db'][0]['prefix'] . $tableAlias . ' ';
			$queryStr .= 'ON ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_count = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count ';
			$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_lang = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang ';
			$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_dev = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev ';
			$queryStr .= 'AND (' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_cl = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl ';
			$queryStr .= 'OR ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_cl = 0) ';
			$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.' . $CONFIG['aModul']['primarykey'] . ' = ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $CONFIG['aModul']['primarykey'] . ' ';
			$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.del = (:nultime) ';
			
			$tableAlias = $tableExt . '_bool';
			$queryStr .= 'LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . $tableExt . ' AS ' . $CONFIG['db'][0]['prefix'] . $tableAlias . ' ';
			$queryStr .= 'ON ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_count = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count ';
			$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_lang = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang ';
			$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_dev = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev ';
			$queryStr .= 'AND (' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_cl = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl ';
			$queryStr .= 'OR ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_cl = 0) ';
			$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.' . $CONFIG['aModul']['primarykey'] . ' = ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $CONFIG['aModul']['primarykey'] . ' ';
			$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.del = (:nultime) ';
		}
		############


		############
		// read base record (master record for additional/new countries, languages / entry in tempdata)
		$aConditionPDO = array();
		$aConditionPDO['id'] = array($CONFIG['page']['id_data'], 'd');
		$aConditionPDO['nultime'] = array('0000-00-00 00:00:00', 's');

		$queryStrB = $queryStr;
		$queryStrB .= 'WHERE ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ') ';
		$queryStrB .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $CONFIG['aModul']['primarykey'] . ' = (:id) ';
		$queryStrB .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.del = (:nultime) ';

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

		$queryStrB .= 'GROUP BY CONCAT(' . $CONFIG['db'][0]['prefix'] . $table . '.' . $CONFIG['aModul']['primarykey'] . ', "-", ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count, "-", ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang, "-", ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev) ';
		$queryStrB .= 'ORDER BY ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count, ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang, ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev, ' . $CONFIG['db'][0]['prefix'] . $table . '.restricted_all  ';
		$queryStrB .= 'LIMIT 1  ';
//echo $queryStrB;
		$queryDb = $CONFIG['dbconn'][0]->prepare($queryStrB);
		foreach($aConditionPDO as $k=>$v){
			if($v[1] == 'd'){
				$queryDb->bindValue(':'.$k, $v[0], PDO::PARAM_INT);
			}else if($v[1] == 'sl'){
				$queryDb->bindValue(':'.$k, '%'.$v[0].'%', PDO::PARAM_STR);
			}else if($v[1] == 'slb'){
				$queryDb->bindValue(':'.$k, '%'.$v[0], PDO::PARAM_STR);
			}else if($v[1] == 'sle'){
				$queryDb->bindValue(':'.$k, $v[0].'%', PDO::PARAM_STR);
			}else{
				$queryDb->bindValue(':'.$k, $v[0], PDO::PARAM_STR);
			}
		}
		$queryDb->execute();
		$rowsDb = $queryDb->fetchAll(PDO::FETCH_ASSOC);
		$numDb = $queryDb->rowCount();
		
		$aArgs['id_count'] = $rowsDb[0]['id_count'];
		$aArgs['id_lang'] = $rowsDb[0]['id_lang'];
		$aArgs['id_dev'] = $rowsDb[0]['id_dev'];
		$aArgs['data'] = $rowsDb[0];
		$rowsDb[0] = setValuesRead($aArgs);

		$rowsDb[0]['identifier'] = '';
		foreach($CONFIG['aModul']['modul_identifier'] as $ident){
			$rowsDb[0]['identifier'] .= (isset($rowsDb[0][substr($ident, 2)]) && substr($ident, 0,2) == '##') ? $rowsDb[0][substr($ident, 2)] : $ident;
		}

		
		############
		// read local versions
		foreach($aLocalVersions as $aVersion){
			$query = $CONFIG['dbconn'][0]->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data
												FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev = (:id_dev)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
												');
			$query->bindValue(':id_count', $aVersion[0], PDO::PARAM_INT);
			$query->bindValue(':id_lang', $aVersion[1], PDO::PARAM_INT);
			$query->bindValue(':id_dev', $aVersion[2], PDO::PARAM_INT);
			$query->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
			$query->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
			$query->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
			$query->execute(); 
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);
			$num = $query->rowCount();
			
			if($num == 0){
				$aConditionPDO = array();
				$aConditionPDO['id'] = array($CONFIG['page']['id_data'], 'd');
				$aConditionPDO['count'] = array($aVersion[0], 'd');
				$aConditionPDO['lang'] = array($aVersion[1], 'd');
				$aConditionPDO['dev'] = array($aVersion[2], 'd');
				$aConditionPDO['nultime'] = array('0000-00-00 00:00:00', 's');
				
				$queryStrL = $queryStr;
				$queryStrL .= 'WHERE ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count = (:count) ';
				$queryStrL .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang = (:lang) ';
				$queryStrL .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev = (:dev) ';
				$queryStrL .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ') ';
				$queryStrL .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.restricted_all IN ("0", "' . $CONFIG['user']['restricted_all'] . '") ';
				$queryStrL .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $CONFIG['aModul']['primarykey'] . ' = (:id) ';
				$queryStrL .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.del = (:nultime) ';

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

				$queryStrL .= 'GROUP BY CONCAT(' . $CONFIG['db'][0]['prefix'] . $table . '.' . $CONFIG['aModul']['primarykey'] . ', "-", ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count, "-", ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang, "-", ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev, "-", ' . $CONFIG['db'][0]['prefix'] . $table . '.restricted_all) ';
		
				$queryD = $CONFIG['dbconn'][0]->prepare($queryStrL);
				foreach($aConditionPDO as $k=>$v){
					if($v[1] == 'd'){
						$queryD->bindValue(':'.$k, $v[0], PDO::PARAM_INT);
					}else if($v[1] == 'sl'){
						$queryD->bindValue(':'.$k, '%'.$v[0].'%', PDO::PARAM_STR);
					}else if($v[1] == 'slb'){
						$queryD->bindValue(':'.$k, '%'.$v[0], PDO::PARAM_STR);
					}else if($v[1] == 'sle'){
						$queryD->bindValue(':'.$k, $v[0].'%', PDO::PARAM_STR);
					}else{
						$queryD->bindValue(':'.$k, $v[0], PDO::PARAM_STR);
					}
				}
				$queryD->execute();
				$rowsD = $queryD->fetchAll(PDO::FETCH_ASSOC);
				$numD = $queryD->rowCount();
				
				#####################################################################################
				// set restricted master to absolute master if in master and restricted master exists 
				$rowTmp = array();
				if($aVersion[0] == 0 && $aVersion[1] == 0 && $aVersion[2] == 0 && $numD == 2){
					foreach($rowsD as $rowD){
						if($rowD['restricted_all'] == $CONFIG['user']['restricted_all']){
							$rowTmp = $rowD;
						}
					}
					$rowsD[0] = $rowTmp;
				}
				#####################################################################################

			
				if($numD > 0){
					if(file_exists($functionPath . $functionFileOne2n)){
						include($functionPath . $functionFileOne2n);
					}else{
						foreach($CONFIG['aModul']['form'] as $aFieldsets){
							foreach($aFieldsets['fields'] as $field){
								if(in_array($field['specifications'][1], $CONFIG['system']['aFieldsAllowedSpecs'])){
									#########################################
									// add default field for boolean fields
									if($field['type'] == 'boolean'){
										$rowsD[0][$field['colname'] . '_default'] = $rowsDb[0][$field['colname'] . 'G'];
										if(($rowsD[0][$field['colname'] . '_ext'] == NULL || $rowsD[0][$field['colname'] . '_ext'] == 0) && ($rowsD[0][$field['colname'] . '_loc'] == NULL || $rowsD[0][$field['colname'] . '_loc'] == 0)) $rowsD[0][$field['colname']] = 0;
									}
									
									#########################################
									// read 1 to n fields
									if($field['array'] == 1 && $field['array_options']['primarykey'] != '' && $field['type'] != 'file'){
										$conditionN = '';
										$aConditionN = array();
										$aConditionN['count'] = array($rowsD[0]['id_count'], 'd');
										$aConditionN['lang'] = array($rowsD[0]['id_lang'], 'd');
										$aConditionN['dev'] = array($rowsD[0]['id_dev'], 'd');
										$aConditionN['nultime'] = array('0000-00-00 00:00:00', 's');
										$conditionParentN = '';
										$aConditionParentN = array();
			
										$rowsD[0][$field['index']] = array();
										$field['table'] = ($field['suffix'] == 0) ? $field['table'] : $field['table'] . 'uni';
			
										if(!isset($field['array_options']['output'])) $field['array_options']['output'] = array();
										if(!isset($field['array_options']['delimter'])) $field['array_options']['delimter'] = '';
										if(!isset($field['array_options']['order'])) $field['array_options']['order'] = array();
										if(!isset($field['array_options']['group'])) $field['array_options']['group'] = '';
										if(!isset($field['array_options']['condition'])) $field['array_options']['condition'] = array();
			
										$queryStrN = 'SELECT ';
										$queryStrN .= $CONFIG['db'][0]['prefix'] . $field['table'] . '.' . $field['array_options']['primarykey'] . ' '; 
										$queryStrN = rtrim($queryStrN, ', ');
										$queryStrN .= ' ';
										$queryStrN .= 'FROM ' . $CONFIG['db'][0]['prefix'] . $field['table'] . ' ';
										foreach($field['join'] as $alias => $aJoin){
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
										}
										$queryStrN .= 'WHERE ' . $CONFIG['db'][0]['prefix'] . $field['table'] . '.id_count = (:count) ';
										$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . $field['table'] . '.id_lang = (:lang) ';
										$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . $field['table'] . '.id_dev = (:dev) ';
										$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . $field['table'] . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ') ';
										$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . $field['table'] . '.del = (:nultime) ';
										foreach($field['array_options']['condition'] as $aCondition){
											$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . $aCondition['table'] . '.' . $aCondition['col'] . ' = ' . $rowsD[0][$aCondition['val']] . ' ';
											
										}
										$queryStrN .= $conditionN . ' ';
										$queryStrN .= $conditionParentN . ' ';
										if($field['array_options']['group'] != '') $queryStrN .= 'GROUP BY ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $field['array_options']['group'] . ' ';
		
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
											array_push($rowsD[0][$field['index']], $rowN[$field['array_options']['primarykey']]);
										}
									}
								}
							}
						}
					}
					#########################################
		
					$rowsD[0]['identifier'] = '';
					foreach($CONFIG['aModul']['modul_identifier'] as $ident){
						$rowsD[0]['identifier'] .= (isset($rowsD[0][substr($ident, 2)]) && substr($ident, 0,2) == '##') ? $rowsD[0][substr($ident, 2)] : $ident;
					}
					
					#########################################
			
					$aArgs['id_count'] = $rowsD[0]['id_count'];
					$aArgs['id_lang'] = $rowsD[0]['id_lang'];
					$aArgs['id_dev'] = $rowsD[0]['id_dev'];
					$aArgs['data'] = $rowsD[0];
					$rowsD[0] = setValuesRead($aArgs);
				
					$query2 = $CONFIG['dbconn'][0]->prepare('
														INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
															(
															id,
															id_count,
															id_lang,
															id_dev,
															id_cl,
															id_uid,
															id_mod,
															modulname,
															data,
															create_at
															)
														VALUES
															(
															:id,
															:id_count,
															:id_lang,
															:id_dev,
															:id_cl,
															:id_uid,
															:id_mod,
															:modulname,
															:data,
															:create_at
															)
														');
					$query2->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
					$query2->bindValue(':id_count', $rowsD[0]['id_count'], PDO::PARAM_INT);
					$query2->bindValue(':id_lang', $rowsD[0]['id_lang'], PDO::PARAM_INT);
					$query2->bindValue(':id_dev', $rowsD[0]['id_dev'], PDO::PARAM_INT);
					$query2->bindValue(':id_cl', $rowsD[0]['id_cl'], PDO::PARAM_INT);
					$query2->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
					$query2->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
					$query2->bindValue(':modulname', $CONFIG['aModul']['modul_name'], PDO::PARAM_STR);
					$query2->bindValue(':data', json_encode($rowsD[0]), PDO::PARAM_STR);
					$query2->bindValue(':create_at', $now, PDO::PARAM_STR);
					$query2->execute();
					
					if($rowsD[0]['id_count'] == $CONFIG['settings']['formCountry'] && $rowsD[0]['id_lang'] == $CONFIG['settings']['formLanguage'] && $rowsD[0]['id_dev'] == $CONFIG['settings']['formDevice']) $out = $rowsD[0];
					
				}else{
					#################################
					// local version not existing in _uni
					$outTmp = array();
					$outTmp['id_count'] = strval($aVersion[0]);
					$outTmp['id_lang'] = strval($aVersion[1]);
					$outTmp['id_dev'] = strval($aVersion[2]);
					$outTmp['id_cl'] = strval($CONFIG['activeSettings']['id_clid']);
					$outTmp['identifier'] = '';
					foreach($aNewRecord as $k => $v){
						$outTmp[$k] = $v;
					}
				
					$query2 = $CONFIG['dbconn'][0]->prepare('
														INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
															(
															id,
															id_count,
															id_lang,
															id_dev,
															id_cl,
															id_uid,
															id_mod,
															modulname,
															data,
															create_at
															)
														VALUES
															(
															:id,
															:id_count,
															:id_lang,
															:id_dev,
															:id_cl,
															:uid,
															:id_mod,
															:modulname,
															:data,
															:create_at
															)
														');
					$query2->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
					$query2->bindValue(':id_count', $outTmp['id_count'], PDO::PARAM_INT);
					$query2->bindValue(':id_lang', $outTmp['id_lang'], PDO::PARAM_INT);
					$query2->bindValue(':id_dev', $outTmp['id_dev'], PDO::PARAM_INT);
					$query2->bindValue(':id_cl', $outTmp['id_cl'], PDO::PARAM_INT);
					$query2->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
					$query2->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
					$query2->bindValue(':modulname', $CONFIG['aModul']['modul_name'], PDO::PARAM_STR);
					$query2->bindValue(':data', json_encode($outTmp), PDO::PARAM_STR);
					$query2->bindValue(':create_at', $now, PDO::PARAM_STR);
					$query2->execute();
		
					if($aVersion[0] == $CONFIG['settings']['formCountry'] && $aVersion[1] == $CONFIG['settings']['formLanguage'] && $aVersion[2] == $CONFIG['settings']['formDevice']) $out = $outTmp;


//					$rowsDb[0]['id_count'] = $aVersion[0];
//					$rowsDb[0]['id_lang'] = $aVersion[1];
//					$rowsDb[0]['id_dev'] = $aVersion[2];
//	
//					$query2 = $CONFIG['dbconn'][0]->prepare('
//														INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
//															(
//															id,
//															id_count,
//															id_lang,
//															id_dev,
//															id_cl,
//															id_uid,
//															id_mod,
//															modulname,
//															data,
//															create_at
//															)
//														VALUES
//															(
//															:id,
//															:id_count,
//															:id_lang,
//															:id_dev,
//															:id_cl,
//															:id_uid,
//															:id_mod,
//															:modulname,
//															:data,
//															:create_at
//															)
//														');
//					$query2->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
//					$query2->bindValue(':id_count', $aVersion[0], PDO::PARAM_INT);
//					$query2->bindValue(':id_lang', $aVersion[1], PDO::PARAM_INT);
//					$query2->bindValue(':id_dev', $aVersion[2], PDO::PARAM_INT);
//					$query2->bindValue(':id_cl', $rowsDb[0]['id_cl'], PDO::PARAM_INT);
//					$query2->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
//					$query2->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
//					$query2->bindValue(':modulname', $CONFIG['aModul']['modul_name'], PDO::PARAM_STR);
//					$query2->bindValue(':data', json_encode($rowsDb[0]), PDO::PARAM_STR);
//					$query2->bindValue(':create_at', $now, PDO::PARAM_STR);
//					$query2->execute();
//					
//					if($aVersion[0] == $CONFIG['settings']['formCountry'] && $aVersion[1] == $CONFIG['settings']['formLanguage'] && $aVersion[2] == $CONFIG['settings']['formDevice']) $out = $rowsDb[0];
				}
			}else{
				
				if($aVersion[0] == $CONFIG['settings']['formCountry'] && $aVersion[1] == $CONFIG['settings']['formLanguage'] && $aVersion[2] == $CONFIG['settings']['formDevice']) $out = json_decode($rows[0]['data'], true);
			}
		}
	}
	
	echo json_encode($out);
}

?>