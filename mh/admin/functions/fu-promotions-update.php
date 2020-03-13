<?php
$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');



$aFields = array();
$aFields['title'] = array();
$aFields['title_transrequired'] = array('default'=>'1', 'val2read'=>array('bool2text'=>array('text'=>'check')));
$aFields['startdate'] = array('val2read'=>array('date'=>array()));
$aFields['enddate'] = array('val2read'=>array('date'=>array()));


$aArgs = array();
$aArgs['id_count'] = $CONFIG['settings']['selectCountry'];
$aArgs['id_lang'] = $CONFIG['settings']['selectLanguage']; 
$aArgs['id_dev'] = $CONFIG['settings']['selectDevice'];
$aArgs['usesystem'] = 0;

$aArgs['fields'] = array();
foreach($aFields as $key => $field){
	if(isset($field['val2read'])){
		foreach($field['val2read'] as $type => $aVal2read){
			if(!array_key_exists($type, $aArgs['fields'])) $aArgs['fields'][$type] = array();
			$aArgs['fields'][$type][$key] = $aVal2read;
		}
	}
}
	
$aArgsSaveN = array();
$aArgsSaveN2 = array();

$aArgsSave = array();
$aArgsSave['id_data'] = $CONFIG['page']['id_data'];
$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . '_promotions_';
$aArgsSave['primarykey'] = 'id_promid';
$aArgsSave['allVersions'] = array();
$aArgsSave['changedVersions'] = array();

$aArgsSave['columns'] = array();
$aArgsSave['columns']['id_promid'] = 'i';
$aArgsSave['columns']['title'] = 's';
$aArgsSave['columns']['title_transrequired'] = 'i';
$aArgsSave['columns']['bsd_only'] = 'i';
$aArgsSave['columns']['startdate'] = 's';
$aArgsSave['columns']['enddate'] = 's';
$aArgsSave['columns']['preview_thumbnail'] = 'i';

$aArgsSave['aFieldsNumbers'] = array();
array_push($aArgsSave['aFieldsNumbers'], 'id_promid');
array_push($aArgsSave['aFieldsNumbers'], 'title_transrequired');
array_push($aArgsSave['aFieldsNumbers'], 'bsd_only');
array_push($aArgsSave['aFieldsNumbers'], 'preview_thumbnail');

$aArgsSave['excludeUpdateUni'] = array();
$aArgsSave['excludeUpdateUni']['id_promid'] = array('');
$aArgsSave['excludeUpdateUni']['title'] = array('');
$aArgsSave['excludeUpdateUni']['title_transrequired'] = array('',0);
$aArgsSave['excludeUpdateUni']['bsd_only'] = array('',0);
$aArgsSave['excludeUpdateUni']['startdate'] = array('0000-00-00 00:00:00');
$aArgsSave['excludeUpdateUni']['enddate'] = array('0000-00-00 00:00:00');
$aArgsSave['excludeUpdateUni']['preview_thumbnail'] = array('',0);

$aFieldsSaveMaster = array();
array_push($aFieldsSaveMaster, 'id_promid');
array_push($aFieldsSaveMaster, 'title');
array_push($aFieldsSaveMaster, 'title_transrequired');
array_push($aFieldsSaveMaster, 'bsd_only');
array_push($aFieldsSaveMaster, 'startdate');
array_push($aFieldsSaveMaster, 'enddate');
array_push($aFieldsSaveMaster, 'preview_thumbnail');
$aFieldsSaveNotMaster = array();
array_push($aFieldsSaveNotMaster, 'id_promid');
array_push($aFieldsSaveNotMaster, 'title');
array_push($aFieldsSaveNotMaster, 'title_transrequired');
array_push($aFieldsSaveNotMaster, 'bsd_only');
array_push($aFieldsSaveNotMaster, 'startdate');
array_push($aFieldsSaveNotMaster, 'enddate');
array_push($aFieldsSaveNotMaster, 'preview_thumbnail');

$aProcessedFiles = array();	

// select master
$queryMaster = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev = (:nul)
									');
$queryMaster->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
$queryMaster->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
$queryMaster->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
$queryMaster->bindValue(':nul', 0, PDO::PARAM_INT);
$queryMaster->execute();
$rowsMaster = $queryMaster->fetchAll(PDO::FETCH_ASSOC);
$numMaster = $queryMaster->rowCount();
	
	
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
									');
$query->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
$query->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
$query->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

foreach($rows as $row){
	$variation = ($row['id_count'] == 0 && $row['id_lang'] == 0 && $row['id_dev'] == 0) ? 'master' : 'local';
	
	$aArgs['data'] = json_decode($row['data'], true);
	$aArgsMaster['data'] = ($numMaster > 0) ? json_decode($rowsMaster[0]['data'], true) : array();

	$aArgsSV = $aArgs;
	$aArgsSV['usesystem'] = 1;
	$aArgsSave['aData'] = setValuesSave($aArgsSV);
	$aArgsSave['aDataMaster'] = setValuesSave($aArgsMaster);
	$aArgsSave['aData']['id_count'] = $row['id_count'];
	$aArgsSave['aData']['id_lang'] = $row['id_lang'];
	$aArgsSave['aData']['id_dev'] = $row['id_dev'];
	$aArgsSave['aData']['id_cl'] = $row['id_cl'];
	
	$aChange = checkChanges($aArgsSave);

	$col = '';
	$val = '';
	$upd = '';
	foreach($aChange['aChangedFields'] as $field){
		if(($variation == 'master' && in_array($field, $aFieldsSaveMaster)) || ($variation == 'local' && in_array($field, $aFieldsSaveNotMaster))){
			if($field != $aArgsSave['primarykey']){
				$col .= ', ' . $field;
				$val .= ', :' . $field . '';
				$upd .= $field.' = (:'.$field.'), ' ;
			}
		}
	}
	foreach($aChange['aChangedFieldsMaster'] as $field){
		if(($variation == 'master' && in_array($field, $aFieldsSaveMaster)) || ($variation == 'local' && in_array($field, $aFieldsSaveNotMaster))){
			if($field != $aArgsSave['primarykey']){
				$col .= ', ' . $field;
				$val .= ', :' . $field . '';
				$upd .= $field.' = (:'.$field.'), ' ;
			}
		}
	}

	if($CONFIG['user']['specifications'][14] == 8 && $variation == 'master' && $CONFIG['settings']['formCountry'] == 0 && $CONFIG['settings']['formLanguage'] == 0 && $CONFIG['settings']['formDevice'] == 0){
		$aArgsLV = array();
		$aArgsLV['type'] = 'temp';
		$aLocalVersions = localVariationsBuild($aArgsLV);
		
		// delete master version for restricted all access
		$queryP1t = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_promotions_loc.id_promid,
												' . $CONFIG['db'][0]['prefix'] . '_promotions_loc.create_from
											FROM ' . $CONFIG['db'][0]['prefix'] . '_promotions_loc 
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions_loc.id_promid = (:id_promid)
											LIMIT 1
											');
		$queryP1t->bindValue(':id_promid', $aArgsSave['id_data'], PDO::PARAM_INT);
		$queryP1t->execute();
		$rowsP1t = $queryP1t->fetchAll(PDO::FETCH_ASSOC);
		$numP1t = $queryP1t->rowCount();
		if($numP1t > 0 && $CONFIG['user']['id'] != $rowsP1t[0]['create_from']){
			$key0 = array_search(array(0,0,0), $aLocalVersions);
			unset($aLocalVersions[$key0]); 
		}
		
		
		foreach($aLocalVersions as $version){
			$restricted_all = ($version[0] == 0 && $version[1] == 0 && $version[2] == 0) ? $CONFIG['user']['restricted_all'] : 0;	
			$tab = 'loc';
			$id_count = $version[0];
			$id_lang = $version[1];
			$id_dev = $version[2];

			$qry = 'INSERT INTO ' . $aArgsSave['table'] . 'loc
						(' . $aArgsSave['primarykey'] . ', id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from' . $col . ')
					VALUES
						(:id, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from' . $val . ')
					ON DUPLICATE KEY UPDATE 
						' . $upd . '
						change_from = (:create_from),
						del = (:nultime)
					';
			$queryC = $CONFIG['dbconn'][0]->prepare($qry);
			$queryC->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
			$queryC->bindValue(':id_count', $id_count, PDO::PARAM_INT);
			$queryC->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
			$queryC->bindValue(':id_dev', $id_dev, PDO::PARAM_INT);
			$queryC->bindValue(':id_cl', $aArgsSave['aData']['id_cl'], PDO::PARAM_INT);
			$queryC->bindValue(':now', $now, PDO::PARAM_STR);
			$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
			
			foreach($aChange['aChangedFields'] as $field){
				if(($variation == 'master' && in_array($field, $aFieldsSaveMaster)) || ($variation == 'local' && in_array($field, $aFieldsSaveNotMaster))){
					if($field != $aArgsSave['primarykey']){
						if($aArgsSave['columns'][$field] == 'i' || $aArgsSave['columns'][$field] == 'si' || $aArgsSave['columns'][$field] == 'b'){
							$queryC->bindValue(':'.$field, (is_array($aArgsSave['aData'][$field])) ? json_encode($aArgsSave['aData'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_INT);
						}else{ 
							$queryC->bindValue(':'.$field, (is_array($aArgsSave['aData'][$field])) ? json_encode($aArgsSave['aData'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_STR);
						}
					}
				}
			}
			foreach($aChange['aChangedFieldsMaster'] as $field){
				if(($variation == 'master' && in_array($field, $aFieldsSaveMaster)) || ($variation == 'local' && in_array($field, $aFieldsSaveNotMaster))){
					if($field != $aArgsSave['primarykey']){
						if($aArgsSave['columns'][$field] == 'i' || $aArgsSave['columns'][$field] == 'si' || $aArgsSave['columns'][$field] == 'b'){
							$queryC->bindValue(':'.$field, (is_array($aArgsSave['aDataMaster'][$field])) ? json_encode($aArgsSave['aDataMaster'][$field]) : trim($aArgsSave['aDataMaster'][$field]), PDO::PARAM_INT);
						}else{ 
							$queryC->bindValue(':'.$field, (is_array($aArgsSave['aDataMaster'][$field])) ? json_encode($aArgsSave['aDataMaster'][$field]) : trim($aArgsSave['aDataMaster'][$field]), PDO::PARAM_STR);
						}
					}
				}
			}
			$queryC->execute();
			$numC = $queryC->rowCount();
		
			array_push($aArgsSave['allVersions'], array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']));
			if($numC > 0 || count($aChange['aDataOld'] == 0)) array_push($aArgsSave['changedVersions'], array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']));
		}
	}else{
		$qry = 'INSERT INTO ' . $aArgsSave['table'] . 'loc
					(' . $aArgsSave['primarykey'] . ', id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from' . $col . ')
				VALUES
					(:id, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from' . $val . ')
				ON DUPLICATE KEY UPDATE 
					' . $upd . '
					change_from = (:create_from),
					del = (:nultime)
				';
		$queryC = $CONFIG['dbconn'][0]->prepare($qry);
		$queryC->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
		$queryC->bindValue(':id_count', $aArgsSave['aData']['id_count'], PDO::PARAM_INT);
		$queryC->bindValue(':id_lang', $aArgsSave['aData']['id_lang'], PDO::PARAM_INT);
		$queryC->bindValue(':id_dev', $aArgsSave['aData']['id_dev'], PDO::PARAM_INT);
		$queryC->bindValue(':id_cl', $aArgsSave['aData']['id_cl'], PDO::PARAM_INT);
		$queryC->bindValue(':now', $now, PDO::PARAM_STR);
		$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
		
		foreach($aChange['aChangedFields'] as $field){
			if(($variation == 'master' && in_array($field, $aFieldsSaveMaster)) || ($variation == 'local' && in_array($field, $aFieldsSaveNotMaster))){
				if($field != $aArgsSave['primarykey']){
					if($aArgsSave['columns'][$field] == 'i' || $aArgsSave['columns'][$field] == 'si' || $aArgsSave['columns'][$field] == 'b'){
						$queryC->bindValue(':'.$field, (is_array($aArgsSave['aData'][$field])) ? json_encode($aArgsSave['aData'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_INT);
					}else{ 
						$queryC->bindValue(':'.$field, (is_array($aArgsSave['aData'][$field])) ? json_encode($aArgsSave['aData'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_STR);
					}
				}
			}
		}
		foreach($aChange['aChangedFieldsMaster'] as $field){
			if(($variation == 'master' && in_array($field, $aFieldsSaveMaster)) || ($variation == 'local' && in_array($field, $aFieldsSaveNotMaster))){
				if($field != $aArgsSave['primarykey']){
					if($aArgsSave['columns'][$field] == 'i' || $aArgsSave['columns'][$field] == 'si' || $aArgsSave['columns'][$field] == 'b'){
						$queryC->bindValue(':'.$field, (is_array($aArgsSave['aDataMaster'][$field])) ? json_encode($aArgsSave['aDataMaster'][$field]) : trim($aArgsSave['aDataMaster'][$field]), PDO::PARAM_INT);
					}else{ 
						$queryC->bindValue(':'.$field, (is_array($aArgsSave['aDataMaster'][$field])) ? json_encode($aArgsSave['aDataMaster'][$field]) : trim($aArgsSave['aDataMaster'][$field]), PDO::PARAM_STR);
					}
				}
			}
		}
		$queryC->execute();
		$numC = $queryC->rowCount();
	
		array_push($aArgsSave['allVersions'], array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']));
		if($numC > 0 || count($aChange['aDataOld'] == 0)) array_push($aArgsSave['changedVersions'], array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']));
	}
	

	#########################################
	// save countries
	#########################################
	$queryN = $CONFIG['dbconn'][0]->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_ SET
											del = (:now)
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_promid = (:id)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid IN ('. implode(',', array_keys($CONFIG['user']['countries'])) . ')
										');
	$queryN->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
	$queryN->bindValue(':now', $now, PDO::PARAM_STR);
	$queryN->execute();

	if(isset($aArgsSave['aData']['country'])){
		$aArgsSave['aData']['country'] = array_unique($aArgsSave['aData']['country']);
		foreach($aArgsSave['aData']['country'] as $val){
			$queryCL = $CONFIG['dbconn'][0]->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid,
													' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
												FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_dev = (:id_dev)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang = (:id_count2lang)
												');
			$queryCL->bindValue(':id_count', 0, PDO::PARAM_INT);
			$queryCL->bindValue(':id_lang', 0, PDO::PARAM_INT);
			$queryCL->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryCL->bindValue(':id_count2lang', $val, PDO::PARAM_INT);
			$queryCL->execute();
			$rowsCL = $queryCL->fetchAll(PDO::FETCH_ASSOC);
			$numCL = $queryCL->rowCount();

			$queryN = $CONFIG['dbconn'][0]->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_
												(id_count, id_lang, id_dev, id_cl, id_promid, id_countid, id_langid, id_count2lang, create_at, create_from, change_from)
												VALUES
												(:id_count, :id_lang, :id_dev, :id_cl, :id_promid, :id_countid, :id_langid, :id_count2lang, :now, :create_from, :create_from)
												ON DUPLICATE KEY UPDATE 
												del = (:nultime)
												');
			$queryN->bindValue(':id_count', 0, PDO::PARAM_INT);
			$queryN->bindValue(':id_lang', 0, PDO::PARAM_INT);
			$queryN->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryN->bindValue(':id_cl', 0, PDO::PARAM_INT);
			$queryN->bindValue(':id_promid', $aArgsSave['id_data'], PDO::PARAM_INT);
			$queryN->bindValue(':id_countid', $rowsCL[0]['id_countid'], PDO::PARAM_INT);
			$queryN->bindValue(':id_langid', $rowsCL[0]['id_langid'], PDO::PARAM_INT);
			$queryN->bindValue(':id_count2lang', $val, PDO::PARAM_INT);
			$queryN->bindValue(':now', $now, PDO::PARAM_STR);
			$queryN->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$queryN->bindValue(':create_from', $CONFIG['user']['id'], PDO::PARAM_INT);
			$queryN->execute();
		}
	}
	#########################################

	

	#########################################
	// save partner
	#########################################
	$queryN = $CONFIG['dbconn'][0]->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . '_promotions2partner_ SET
											del = (:now)
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions2partner_.id_promid = (:id)
										');
	$queryN->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
	$queryN->bindValue(':now', $now, PDO::PARAM_STR);
	$queryN->execute();

	if(isset($aArgsSave['aData']['partnercompany'])){
		if(!is_array($aArgsSave['aData']['partnercompany'])) $aArgsSave['aData']['partnercompany'] = explode(',', $aArgsSave['aData']['partnercompany']);
		$aArgsSave['aData']['partnercompany'] = array_unique($aArgsSave['aData']['partnercompany']);
		foreach($aArgsSave['aData']['partnercompany'] as $val){
			$queryN = $CONFIG['dbconn'][0]->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_promotions2partner_
												(id_count, id_lang, id_dev, id_cl, id_promid, id_pcid, create_at, create_from, change_from)
												VALUES
												(:id_count, :id_lang, :id_dev, :id_cl, :id_promid, :id_pcid, :now, :create_from, :create_from)
												ON DUPLICATE KEY UPDATE 
												del = (:nultime)
												');
			$queryN->bindValue(':id_count', 0, PDO::PARAM_INT);
			$queryN->bindValue(':id_lang', 0, PDO::PARAM_INT);
			$queryN->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryN->bindValue(':id_cl', 0, PDO::PARAM_INT);
			$queryN->bindValue(':id_promid', $aArgsSave['id_data'], PDO::PARAM_INT);
			$queryN->bindValue(':id_pcid', $val, PDO::PARAM_INT);
			$queryN->bindValue(':now', $now, PDO::PARAM_STR);
			$queryN->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$queryN->bindValue(':create_from', $CONFIG['user']['id'], PDO::PARAM_INT);
			if($val != 0) $queryN->execute();
		}
	}
	#########################################

	

	#########################################
	// save products
	#########################################
	if($row['id_count'] == $CONFIG['settings']['formCountry'] && $row['id_lang'] == $CONFIG['settings']['formLanguage'] && $row['id_dev'] == $CONFIG['settings']['formDevice']){
		$queryN = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_promotions2products_ SET
												del = (:now)
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions2products_.id_promid = (:id)
											');
		$queryN->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
		$queryN->bindValue(':now', $now, PDO::PARAM_STR);
		$queryN->execute();
	
		if(isset($aArgsSave['aData']['selectassign_products'])){
			if(!is_array($aArgsSave['aData']['selectassign_products'])) $aArgsSave['aData']['selectassign_products'] = explode(',', $aArgsSave['aData']['selectassign_products']);
			$aArgsSave['aData']['selectassign_products'] = array_unique($aArgsSave['aData']['selectassign_products']);
			foreach($aArgsSave['aData']['selectassign_products'] as $val){
				$queryN = $CONFIG['dbconn'][0]->prepare('
													INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_promotions2products_
													(id_count, id_lang, id_dev, id_cl, id_promid, id_pid, create_at, create_from, change_from)
													VALUES
													(:id_count, :id_lang, :id_dev, :id_cl, :id_promid, :id_pid, :now, :create_from, :create_from)
													ON DUPLICATE KEY UPDATE 
													del = (:nultime)
													');
				$queryN->bindValue(':id_count', 0, PDO::PARAM_INT);
				$queryN->bindValue(':id_lang', 0, PDO::PARAM_INT);
				$queryN->bindValue(':id_dev', 0, PDO::PARAM_INT);
				$queryN->bindValue(':id_cl', 0, PDO::PARAM_INT);
				$queryN->bindValue(':id_promid', $aArgsSave['id_data'], PDO::PARAM_INT);
				$queryN->bindValue(':id_pid', $val, PDO::PARAM_INT);
				$queryN->bindValue(':now', $now, PDO::PARAM_STR);
				$queryN->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$queryN->bindValue(':create_from', $CONFIG['user']['id'], PDO::PARAM_INT);
				if($val != 0) $queryN->execute();
			}
		}
	}
	#########################################


}

$aArgsLV = array();
$aArgsLV['type'] = 'sysall';
$aLocalVersions = localVariationsBuild($aArgsLV);

$aArgsSave['changedVersions'] = array(array(0,0,0));
$aArgsSave['allVersions'] = $aLocalVersions;
insertAll($aArgsSave);








#########################################

$query2 = $CONFIG['dbconn'][0]->prepare('
									DELETE 
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id = (:id)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_uid = (:uid)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_mod = (:id_mod)
									');
$query2->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
$query2->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
$query2->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
$query2->execute();


































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

//$formfile = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-templates-update-' . $rows[0]['formfile'] . '.php';
//if(file_exists($formfile)) include_once($formfile);




$out = array();
$out['id_data'] = $aArgsSave['id_data'];

echo json_encode($out);

?>