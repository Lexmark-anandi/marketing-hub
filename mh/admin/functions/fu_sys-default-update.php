<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$functionPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'];
$functionFile = 'fu-' . $CONFIG['aModul']['modul_name'] . '-update.php';
$functionFileOne2n = 'fu-' . $CONFIG['aModul']['modul_name'] . '-update-one2n.php';
$functionFilePost = 'fu-' . $CONFIG['aModul']['modul_name'] . '-update-post.php';
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php');


if(file_exists($functionPath . $functionFile)){
	include_once($functionPath . $functionFile);
	
}else{
	$date = new DateTime();
	$now = $date->format('Y-m-d H:i:s');
	
	$aArgs = array();
	$aArgs['id_count'] = $CONFIG['settings']['selectCountry'];
	$aArgs['id_lang'] = $CONFIG['settings']['selectLanguage'];
	$aArgs['id_dev'] = $CONFIG['settings']['selectDevice'];
	$aArgs['usesystem'] = 1;
	$aArgs['fields'] = array();

	$aFieldsSaveMaster = array();
	$aFieldsSaveNotMaster = array();

	$aArgsSaveN = array();
	
	$aArgsSave = array();
	$aArgsSave['id_data'] = $CONFIG['page']['id_data'];
	$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . $CONFIG['aModul']['table_name'];
	$aArgsSave['primarykey'] = $CONFIG['aModul']['primarykey'];
	$aArgsSave['allVersions'] = array();
	$aArgsSave['changedVersions'] = array();
	
	$aArgsSave['columns'] = array();
	$aArgsSave['aFieldsNumbers'] = array();
	$aArgsSave['excludeUpdateUni'] = array();
	foreach($CONFIG['aModul']['form'] as $aFieldsets){
		foreach($aFieldsets['fields'] as $field){
			if(in_array($field['specifications'][0], $CONFIG['system']['aFieldsAllowedSpecs']) && $field['specifications'][2] != 0){
				if($field['specifications'][2] == 9){
					array_push($aFieldsSaveMaster, $field['colname_save']);
					array_push($aFieldsSaveNotMaster, $field['colname_save']);
				}
				if($field['specifications'][2] == 2){
					array_push($aFieldsSaveNotMaster, $field['colname_save']);
				}
				if($field['specifications'][2] == 1){
					array_push($aFieldsSaveMaster, $field['colname_save']);
				}
				
				if(count($field['val2read']) > 0){
					foreach($field['val2read'] as $type => $aVal2read){
						if(!array_key_exists($type, $aArgs['fields'])) $aArgs['fields'][$type] = array();
						$aArgs['fields'][$type][$field['index']] = $aVal2read;
					}
				}
	
				if($field['table_save'] == $CONFIG['aModul']['table_name'] && ($field['array'] == 0 || $field['type'] == 'file')){
					$aArgsSave['columns'][$field['colname_save']] = $field['format'];
					if($field['format'] == 'i' || $field['format'] == 'si') array_push($aArgsSave['aFieldsNumbers'], $field['colname_save']);
					
					// define values for excluding in '_uni'
					$aArgsSave['excludeUpdateUni'][$field['colname_save']] = array();
					if($field['always_update'] == 0){
						switch($field['format']){
							case 's':
								array_push($aArgsSave['excludeUpdateUni'][$field['colname_save']], ''); 
								break;
							case 'i':
								array_push($aArgsSave['excludeUpdateUni'][$field['colname_save']], ''); 
								break;
							case 'si':
								array_push($aArgsSave['excludeUpdateUni'][$field['colname_save']], 0); 
								break;
							case 'f':
								array_push($aArgsSave['excludeUpdateUni'][$field['colname_save']], ''); 
								break;
							case 'd':
								array_push($aArgsSave['excludeUpdateUni'][$field['colname_save']], '0000-00-00'); 
								array_push($aArgsSave['excludeUpdateUni'][$field['colname_save']], '0000-00-00 00:00:00'); 
								break;
							case 'c':
								array_push($aArgsSave['excludeUpdateUni'][$field['colname_save']], ''); 
								break;
							case 'b':
								array_push($aArgsSave['excludeUpdateUni'][$field['colname_save']], 0); 
								break;
						}
					}
				}
			}
		} 
	}
	
	
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
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count, ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang, ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev
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

		$aArgsSave['aData'] = setValuesSave($aArgs);
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
		
		
		//if($CONFIG['user']['specifications'][14] == 8 && $variation == 'master'){
		if($CONFIG['user']['specifications'][14] == 8 && $variation == 'master' && $CONFIG['settings']['formCountry'] == 0 && $CONFIG['settings']['formLanguage'] == 0 && $CONFIG['settings']['formDevice'] == 0){
			$aArgsLV = array();
			$aArgsLV['type'] = 'temp';
			$aLocalVersions = localVariationsBuild($aArgsLV);
			
			// delete master version for restricted all access
			$key0 = array_search(array(0,0,0), $aLocalVersions);
			unset($aLocalVersions[$key0]); 
			
			foreach($aLocalVersions as $version){
				$restricted_all = ($version[0] == 0 && $version[1] == 0 && $version[2] == 0) ? $CONFIG['user']['restricted_all'] : 0;	
				$tab = 'loc';
				$id_count = $version[0];
				$id_lang = $version[1];
				$id_dev = $version[2];
				
				$qry = 'INSERT INTO ' . $aArgsSave['table'] . $tab . '
							(' . $aArgsSave['primarykey'] . ', id_count, id_lang, id_dev, id_cl, restricted_all, create_at, create_from, change_from' . $col . ')
						VALUES
							(:id, :id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :now, :create_from, :create_from' . $val . ')
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
				$queryC->bindValue(':restricted_all', $restricted_all, PDO::PARAM_INT);
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
								$queryC->bindValue(':'.$field, (is_array($aArgsSave['aDataMaster'][$field])) ? json_encode($aArgsSave['aDataMaster'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_STR);
							}
						}
					}
				}
				$queryC->execute();
				$numC = $queryC->rowCount();
		
		
				if(!in_array(array($version[0], $version[1], $version[2]), $aArgsSave['allVersions'])) array_push($aArgsSave['allVersions'], array($version[0], $version[1], $version[2]));
				if($numC > 0 || count($aChange['aDataOld'] == 0)){
					if(!in_array(array($version[0], $version[1], $version[2]), $aArgsSave['changedVersions']))  array_push($aArgsSave['changedVersions'], array($version[0], $version[1], $version[2])); 
				}

			}
			
		}else{
			$restricted_all = 0;	
			$tab = 'loc';
			$id_count = $aArgsSave['aData']['id_count'];
			$id_lang = $aArgsSave['aData']['id_lang'];
			$id_dev = $aArgsSave['aData']['id_dev'];
			
			$qry = 'INSERT INTO ' . $aArgsSave['table'] . $tab . '
						(' . $aArgsSave['primarykey'] . ', id_count, id_lang, id_dev, id_cl, restricted_all, create_at, create_from, change_from' . $col . ')
					VALUES
						(:id, :id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :now, :create_from, :create_from' . $val . ')
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
			$queryC->bindValue(':restricted_all', $restricted_all, PDO::PARAM_INT);
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
	
	
			if(!in_array(array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']), $aArgsSave['allVersions'])) array_push($aArgsSave['allVersions'], array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']));
			if($numC > 0 || count($aChange['aDataOld'] == 0)){
				if(!in_array(array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']), $aArgsSave['changedVersions']))  array_push($aArgsSave['changedVersions'], array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']));
			}
		}
		
		#########################################
		// save 1 to n fields
		if(file_exists($functionPath . $functionFileOne2n)){
			include($functionPath . $functionFileOne2n);
		}else{
			include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-default-one2n.php');
		}
		#########################################
	}
	
	
	insertAll($aArgsSave);
	
	foreach($aArgsSaveN as $kSave => $aSave){
		$aSave['allVersions'] = $aArgsSave['allVersions'];
		insertAll($aSave);
	}

	#########################################

	if(file_exists($functionPath . $functionFilePost)){ 
		include_once($functionPath . $functionFilePost);
	}

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

	##########################################
	
	$out = array();
	$out['id_data'] = $aArgsSave['id_data'];
	
	echo json_encode($out);
}

?>