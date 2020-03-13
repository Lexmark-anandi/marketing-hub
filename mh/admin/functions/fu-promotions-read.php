<?php
$out = array();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$aArgsLV = array();
$aArgsLV['type'] = 'temp';
$aLocalVersions = localVariationsBuild($aArgsLV); 


if($CONFIG['page']['id_data'] == 0){
	include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-promotions-read-new.php');

}else{
 
	$aFields = array();
	$aFields['id_promid'] = array();
	$aFields['title'] = array();
	$aFields['title_transrequired'] = array('default'=>'1', 'val2read'=>array('bool2text'=>array('text'=>'check')));
	$aFields['bsd_only'] = array('default'=>'2', 'val2read'=>array('bool2text'=>array('text'=>'check')));
	$aFields['startdate'] = array('val2read'=>array('date'=>array()));
	$aFields['enddate'] = array('val2read'=>array('date'=>array()));
	$aFields['published_at'] = '';
	$aFields['transrequest_at'] = '';
	$aFields['preview_thumbnail'] = array('default'=>'0');
	$aFieldsLoc = array();
	$aFieldsLoc['title_transrequired'] = '';
	$aFieldsExt = array();
	$aFieldsExt['title_transrequired'] = '';
	
	$aArgs = array();
	$aArgs['fields'] = array();
	foreach($aFields as $key => $field){
		if(isset($field['val2read'])){
			foreach($field['val2read'] as $type => $aVal2read){
				if(!array_key_exists($type, $aArgs['fields'])) $aArgs['fields'][$type] = array();
				$aArgs['fields'][$type][$key] = $aVal2read;
			}
		}
	}
	$aArgs['fields']['file']['preview_thumbnail'] = array('pf' => '0', 'thumbnail' => '0', 'functions' => array(22), 'gridheight' => 'fold', 'type' => 'single');
	
	$aArgs['usesystem'] = 0;
	
	$aArgs['useboolean'] = array();
	array_push($aArgs['useboolean'], 'title_transrequired');

	#######################################################
	// read / update existing record
	#######################################################
			
	############
	// build query string (first part without condition)
	$table = '_promotions_uni';
	$tableLoc = '_promotions_loc';
	$tableExt = '_promotions_ext';
	
	$primarykey = 'id_promid';
		
	$queryStr = 'SELECT ';
	$queryStr .= '' . $CONFIG['db'][0]['prefix'] . $table . '.id_count, ';
	$queryStr .= '' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang, ';
	$queryStr .= '' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev, ';
	$queryStr .= '' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl, ';
	
	foreach($aFields as $field => $default){
		$queryStr .= $CONFIG['db'][0]['prefix'] . $table . '.' . $field . ', '; 
	}
	foreach($aFieldsLoc as $field => $default){
		$queryStr .= $CONFIG['db'][0]['prefix'] . $tableLoc . '_bool.' . $field . ' AS ' . $field . '_loc, '; 
	}
	foreach($aFieldsExt as $field => $default){
		$queryStr .= $CONFIG['db'][0]['prefix'] . $tableExt . '_bool.' . $field . ' AS ' . $field . '_ext, '; 
	}
	
	$queryStr = rtrim($queryStr, ', ');
	$queryStr .= ' ';
	$queryStr .= 'FROM ' . $CONFIG['db'][0]['prefix'] . $table . ' ';
	
	
	// additional join for boolean fields
	if(count($aArgs['useboolean']) > 0){
		$tableAlias = $tableLoc . '_bool';
		$queryStr .= 'LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . $tableLoc . ' AS ' . $CONFIG['db'][0]['prefix'] . $tableAlias . ' ';
		$queryStr .= 'ON ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_count = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count ';
		$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_lang = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang ';
		$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_dev = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev ';
		$queryStr .= 'AND (' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_cl = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl ';
		$queryStr .= 'OR ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_cl = 0) ';
		$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.' . $primarykey . ' = ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primarykey . ' ';
		$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.del = (:nultime) ';
		
		$tableAlias = $tableExt . '_bool';
		$queryStr .= 'LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . $tableExt . ' AS ' . $CONFIG['db'][0]['prefix'] . $tableAlias . ' ';
		$queryStr .= 'ON ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_count = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count ';
		$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_lang = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang ';
		$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_dev = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev ';
		$queryStr .= 'AND (' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_cl = ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl ';
		$queryStr .= 'OR ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.id_cl = 0) ';
		$queryStr .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAlias . '.' . $primarykey . ' = ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primarykey . ' ';
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
	$queryStrB .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primarykey . ' = (:id) ';
	$queryStrB .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.del = (:nultime) ';
	
	if(isset($specialCondition)) $queryStr .= $specialCondition;
	
	$queryStrB .= 'GROUP BY CONCAT(' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primarykey . ', "-", ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count, "-", ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang, "-", ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev) ';
	$queryStrB .= 'ORDER BY ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count, ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang, ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev  ';
	$queryStrB .= 'LIMIT 1  ';

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
	$aArgsSV = $aArgs;
	$aArgsSV['usesystem'] = 1;
	$rowsDb[0] = setValuesRead($aArgsSV);
	
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
			$queryStrL .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primarykey . ' = (:id) ';
			$queryStrL .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.del = (:nultime) ';
	
			if(isset($specialCondition)) $queryStr .= $specialCondition;
	
			$queryStrL .= 'GROUP BY CONCAT(' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primarykey . ', "-", ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count, "-", ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang, "-", ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev) ';
			
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
				
			if($numD > 0){
				#########################################
				// add default field for boolean fields
				foreach($aArgs['useboolean'] as $fieldboolean){
					$rowsD[0][$fieldboolean . '_default'] = $rowsDb[0][$fieldboolean . 'G'];
					if(($rowsD[0][$fieldboolean . '_ext'] == NULL || $rowsD[0][$fieldboolean . '_ext'] == 0) && ($rowsD[0][$fieldboolean . '_loc'] == NULL || $rowsD[0][$fieldboolean . '_loc'] == 0)) $rowsD[0][$fieldboolean] = 0;
				}
				#########################################
				
				###############################
				// read countries
				$rowsD[0]['country'] = array();
				$queryN = $CONFIG['dbconn'][0]->prepare('
													SELECT 
														' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_count2lang
													FROM ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_ 
													
													WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_promid = (:id)
														AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid IN ('. implode(',', array_keys($CONFIG['user']['countries'])) . ')
														AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.del = (:nultime)
													');
				$queryN->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
				$queryN->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$queryN->execute();
				$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
				$numN = $queryN->rowCount();
				
				foreach($rowsN as $rowN){
					array_push($rowsD[0]['country'], $rowN['id_count2lang']);
				}
				#########################################
				
				###############################
				// read partner
				$rowsD[0]['partnercompany'] = array();
				$queryN = $CONFIG['dbconn'][0]->prepare('
													SELECT 
														' . $CONFIG['db'][0]['prefix'] . '_promotions2partner_.id_pcid
													FROM ' . $CONFIG['db'][0]['prefix'] . '_promotions2partner_ 
													
													WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions2partner_.id_promid = (:id)
														AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2partner_.del = (:nultime)
													');
				$queryN->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
				$queryN->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$queryN->execute();
				$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
				$numN = $queryN->rowCount();
				
				foreach($rowsN as $rowN){
					array_push($rowsD[0]['partnercompany'], $rowN['id_pcid']);
				}
				#########################################
				
				###############################
				// read products
				$rowsD[0]['selectassign_products'] = array();
				$queryN = $CONFIG['dbconn'][0]->prepare('
													SELECT 
														' . $CONFIG['db'][0]['prefix'] . '_promotions2products_.id_pid
													FROM ' . $CONFIG['db'][0]['prefix'] . '_promotions2products_ 
													
													WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions2products_.id_promid = (:id)
														AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2products_.del = (:nultime)
													');
				$queryN->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
				$queryN->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$queryN->execute();
				$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
				$numN = $queryN->rowCount();
				
				foreach($rowsN as $rowN){
					array_push($rowsD[0]['selectassign_products'], $rowN['id_pid']);
				}
				#########################################








				
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

				$aArgsSV = $aArgs;
				$aArgsSV['usesystem'] = 1;
				$rowsD[0] = setValuesRead($aArgsSV);
			
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
				$rowsDb[0]['id_count'] = $aVersion[0];
				$rowsDb[0]['id_lang'] = $aVersion[1];
				$rowsDb[0]['id_dev'] = $aVersion[2];
	
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
				$query2->bindValue(':id_count', $aVersion[0], PDO::PARAM_INT);
				$query2->bindValue(':id_lang', $aVersion[1], PDO::PARAM_INT);
				$query2->bindValue(':id_dev', $aVersion[2], PDO::PARAM_INT);
				$query2->bindValue(':id_cl', $rowsDb[0]['id_cl'], PDO::PARAM_INT);
				$query2->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
				$query2->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
				$query2->bindValue(':modulname', $CONFIG['aModul']['modul_name'], PDO::PARAM_STR);
				$query2->bindValue(':data', json_encode($rowsDb[0]), PDO::PARAM_STR);
				$query2->bindValue(':create_at', $now, PDO::PARAM_STR);
				$query2->execute();
				
				if($aVersion[0] == $CONFIG['settings']['formCountry'] && $aVersion[1] == $CONFIG['settings']['formLanguage'] && $aVersion[2] == $CONFIG['settings']['formDevice']) $out = $rowsDb[0];
			}
		}else{
			
			if($aVersion[0] == $CONFIG['settings']['formCountry'] && $aVersion[1] == $CONFIG['settings']['formLanguage'] && $aVersion[2] == $CONFIG['settings']['formDevice']) $out = json_decode($rows[0]['data'], true);
		}
	}



	
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.formfile
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_caid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid = (:id)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':nul', 0, PDO::PARAM_INT);
	$query->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
//	
//	$formfile = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-templates-read-' . $rows[0]['formfile'] . '.php';
//	if(file_exists($formfile)) include_once($formfile);
}


echo json_encode($out);

?>