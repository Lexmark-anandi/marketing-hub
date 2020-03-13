<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();
$aData = json_decode($varSQL['data'], true); 


#################################################################
// Form check
#################################################################
$checkfileModul = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-' . $CONFIG['aModul']['modul_name'] . '-formcheck.php';
if(file_exists($checkfileModul)) include_once($checkfileModul);

$aError = array();
foreach($aData['check'] as $aCheck){
	$field = (isset($aCheck['field'])) ? $aCheck['field'] : '';
	$aFunction = (isset($aCheck['function'])) ? explode(';', $aCheck['function']) : array();
	$message = (isset($aCheck['message'])) ? $aCheck['message'] : '';
	
	foreach($aFunction as $function){
		if($function != '' && function_exists($function)){
			$res = $function($field, $aData['formdata']);
			if($res != ''){
				$err = array();
				$err['field'] = $field;
				$err['message'] = (isset($TEXT[$message]) && $message != '') ? $TEXT[$message] : $res;
				array_push($aError, $err);
			}
		}
	}
}



#################################################################
// Save tempdata
#################################################################
if(count($aError) == 0){
	$date = new DateTime();
	$now = $date->format('Y-m-d H:i:s');
	$variation = ($CONFIG['settings']['formCountry'] == 0 && $CONFIG['settings']['formLanguage'] == 0 && $CONFIG['settings']['formDevice'] == 0) ? 'master' : 'local';

	$query = $CONFIG['dbconn'][0]->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
											(id_count, id_lang, id_dev, id_cl, id_uid, id_mod, id, modulname, data, create_at)
										VALUES
											(:id_count, :id_lang, :id_dev, :id_cl, :id_uid, :id_mod, :id, :modulname, :data, :create_at)
										ON DUPLICATE KEY UPDATE 
											data = (:data)
										');
	$query->bindValue(':id_count', $aData['formdata']['id_count'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $aData['formdata']['id_lang'], PDO::PARAM_INT);
	$query->bindValue(':id_dev', $aData['formdata']['id_dev'], PDO::PARAM_INT);
	$query->bindValue(':id_cl', $aData['formdata']['id_cl'], PDO::PARAM_INT);
	$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
	$query->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
	$query->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
	$query->bindValue(':modulname', $CONFIG['aModul']['modul_name'], PDO::PARAM_STR);
	$query->bindValue(':data', json_encode($aData['formdata']), PDO::PARAM_STR);
	$query->bindValue(':create_at', $now, PDO::PARAM_STR);
	$query->execute();
	
	
	
	########################################################################
	// Synchronize fields
	foreach($aData['sync'] as $sync => $aSync){
		$numS = 0;
		if($sync == 'all'){
			$queryS = $CONFIG['dbconn'][0]->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl
												FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												');
			$queryS->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
			$queryS->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
			$queryS->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
			$queryS->execute();
			$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
			$numS = $queryS->rowCount();
		}
		
		if($sync == 'country'){
			$queryS = $CONFIG['dbconn'][0]->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl
												FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev = (:id_dev)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
												');
			$queryS->bindValue(':id_lang', $aData['formdata']['id_lang'], PDO::PARAM_INT);
			$queryS->bindValue(':id_dev', $aData['formdata']['id_dev'], PDO::PARAM_INT);
			$queryS->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
			$queryS->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
			$queryS->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
			$queryS->execute();
			$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
			$numS = $queryS->rowCount();
		}
		
		if($sync == 'language'){
			$queryS = $CONFIG['dbconn'][0]->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl
												FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev = (:id_dev)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
												');
			$queryS->bindValue(':id_count', $aData['formdata']['id_count'], PDO::PARAM_INT);
			$queryS->bindValue(':id_dev', $aData['formdata']['id_dev'], PDO::PARAM_INT);
			$queryS->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
			$queryS->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
			$queryS->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
			$queryS->execute();
			$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
			$numS = $queryS->rowCount();
		}
		
		if($sync == 'device'){
			$queryS = $CONFIG['dbconn'][0]->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl
												FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
												');
			$queryS->bindValue(':id_count', $aData['formdata']['id_count'], PDO::PARAM_INT);
			$queryS->bindValue(':id_lang', $aData['formdata']['id_lang'], PDO::PARAM_INT);
			$queryS->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
			$queryS->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
			$queryS->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
			$queryS->execute();
			$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
			$numS = $queryS->rowCount();
		}
		
		if($sync == 'countrylanguage'){
			$queryS = $CONFIG['dbconn'][0]->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl
												FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev = (:id_dev)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
												');
			$queryS->bindValue(':id_dev', $aData['formdata']['id_dev'], PDO::PARAM_INT);
			$queryS->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
			$queryS->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
			$queryS->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
			$queryS->execute();
			$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
			$numS = $queryS->rowCount();
		}
		
		if($sync == 'countrydevice'){
			$queryS = $CONFIG['dbconn'][0]->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl
												FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
												');
			$queryS->bindValue(':id_lang', $aData['formdata']['id_lang'], PDO::PARAM_INT);
			$queryS->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
			$queryS->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
			$queryS->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
			$queryS->execute();
			$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
			$numS = $queryS->rowCount();
		}
		
		if($sync == 'languagedevice'){
			$queryS = $CONFIG['dbconn'][0]->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl
												FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
												');
			$queryS->bindValue(':id_count', $aData['formdata']['id_count'], PDO::PARAM_INT);
			$queryS->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
			$queryS->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
			$queryS->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
			$queryS->execute();
			$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
			$numS = $queryS->rowCount();
		}
		
		
		if($numS > 0){
			foreach($rowsS as $rowS){
				$aDataTmp = json_decode($rowS['data'], true);
				
				foreach($aSync as $field){
					if(isset($aDataTmp[$field])) $aDataTmp[$field] = $aData['formdata'][$field];
				}
				
				$query2 = $CONFIG['dbconn'][0]->prepare('
													UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata SET
														data = (:data)
													WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:id_count)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:id_lang)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev = (:id_dev)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl = (:id_cl)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
													LIMIT 1
													');
				$query2->bindValue(':data', json_encode($aDataTmp), PDO::PARAM_STR);
				$query2->bindValue(':id_count', $rowS['id_count'], PDO::PARAM_INT);
				$query2->bindValue(':id_lang', $rowS['id_lang'], PDO::PARAM_INT);
				$query2->bindValue(':id_dev', $rowS['id_dev'], PDO::PARAM_INT);
				$query2->bindValue(':id_cl', $rowS['id_cl'], PDO::PARAM_INT);
				$query2->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
				$query2->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
				$query2->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
				$query2->execute();
				$num2 = $query2->rowCount();
			}
		}
	}
	
	
	
	
	
	
	
	
	#######################################################################
	#######################################################################
	#######################################################################
	// Create data_read
	
	
	$tabTmp = array('ext', 'loc', 'res', 'uni');
	foreach($tabTmp as $t){
		$qry = '';
		$qry .= 'DROP TEMPORARY TABLE IF EXISTS ' . $t . '; ';
		$qry .= 'CREATE TEMPORARY TABLE ' . $t . ' LIKE ' . $CONFIG['db'][0]['prefix'] . $CONFIG['aModul']['table_name'] . $t . '; ';
		$qry .= 'INSERT ' . $t . ' SELECT * FROM ' . $CONFIG['db'][0]['prefix'] . $CONFIG['aModul']['table_name'] . $t . ' WHERE ' . $CONFIG['db'][0]['prefix'] . $CONFIG['aModul']['table_name'] . $t . '.' . $CONFIG['aModul']['primarykey'] . ' = ' . $CONFIG['page']['id_data'] . '; ';
		$query = $CONFIG['dbconn'][0]->prepare($qry);
		$query->execute();
	}
	
	
	
	





	
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
	$aArgsSave['table'] = '';
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
	$queryS = $CONFIG['dbconn'][0]->prepare('
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
	$queryS->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
	$queryS->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
	$queryS->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
	$queryS->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryS->execute();
	$rowsMaster = $queryS->fetchAll(PDO::FETCH_ASSOC);
	$numMaster = $queryS->rowCount();

	
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
		
		$restricted_all = 0;	
		$tab = 'loc';
		$id_count = $aArgsSave['aData']['id_count'];
		$id_lang = $aArgsSave['aData']['id_lang'];
		$id_dev = $aArgsSave['aData']['id_dev'];
		
		if($CONFIG['user']['specifications'][14] == 8 && $variation == 'master'){
			$restricted_all = $CONFIG['user']['restricted_all'];	
			$tab = 'res';
			$id_count = 0;
			$id_lang = 0;
			$id_dev = 0;
		}
			
			
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


		if(!in_array(array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']), $aArgsSave['allVersions'])) array_push($aArgsSave['allVersions'], array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']));
		if($numC > 0 || count($aChange['aDataOld'] == 0)){
			if(!in_array(array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']), $aArgsSave['changedVersions']))  array_push($aArgsSave['changedVersions'], array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']));
		}
	
//		#########################################
//		// save 1 to n fields
//		if(file_exists($functionPath . $functionFileOne2n)){
//			include($functionPath . $functionFileOne2n);
//		}else{
//			include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-default-one2n.php');
//		}
//		#########################################


	}
	
	
//	insertAll($aArgsSave);

######################################################################################################

	$aSaveVersions = (in_array(array(0,0,0), $aArgsSave['changedVersions'])) ? $aArgsSave['allVersions'] : $aArgsSave['changedVersions'];
	
	foreach($aSaveVersions as $aVersion){
		$id_count = $aVersion[0];
		$id_lang = $aVersion[1];
		$id_dev = $aVersion[2];
		$variation = ($id_count == 0 && $id_lang == 0 && $id_dev == 0) ? 'master' : 'local';
		
		######################################################################
		// read variations
		$numE = 0;
		$aArgsSave['table'] = '';
		
		##################
		// ext master
		$queryStr = 'SELECT ';
		$queryStr .= $aArgsSave['table'] . 'ext.id_cl, '; 
		$queryStr .= $aArgsSave['table'] . 'ext.id_count, '; 
		$queryStr .= $aArgsSave['table'] . 'ext.id_lang, '; 
		foreach($aArgsSave['columns'] as $column => $format){
			$queryStr .= $aArgsSave['table'] . 'ext.' . $column . ', '; 
		}
		$queryStr = rtrim($queryStr, ', ');
		$queryStr .= ' ';
		$queryStr .= 'FROM ' . $aArgsSave['table'] . 'ext ';
		$queryStr .= 'WHERE ' . $aArgsSave['table'] . 'ext.id_count = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.id_lang = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.id_dev = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.id_cl IN (0,' . $CONFIG['activeSettings']['id_clid'] . ') ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.restricted_all = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.' . $aArgsSave['primarykey'] . ' = (:id) ';
		//$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.del = (:nultime) ';
		//$queryStr .= str_replace('##TYPE##', 'ext', $addCondition) . ' ';

		$queryE = $CONFIG['dbconn'][0]->prepare($queryStr);
		$queryE->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
		$queryE->bindValue(':nul', 0, PDO::PARAM_INT);
		//$queryE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryE->execute();
		$rowsExt = $queryE->fetchAll(PDO::FETCH_ASSOC); 
		$numExt = $queryE->rowCount();
		$numE += $numExt;
		
		##################
		// ext local
		$queryStr = 'SELECT ';
		$queryStr .= $aArgsSave['table'] . 'ext.id_cl, '; 
		$queryStr .= $aArgsSave['table'] . 'ext.id_count, '; 
		$queryStr .= $aArgsSave['table'] . 'ext.id_lang, '; 
		foreach($aArgsSave['columns'] as $column => $format){
			$queryStr .= $aArgsSave['table'] . 'ext.' . $column . ', '; 
		}
		$queryStr = rtrim($queryStr, ', ');
		$queryStr .= ' ';
		$queryStr .= 'FROM ' . $aArgsSave['table'] . 'ext ';
		$queryStr .= 'WHERE ' . $aArgsSave['table'] . 'ext.id_count = (:id_count) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.id_lang = (:id_lang) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.id_dev = (:id_dev) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.id_cl IN (0,' . $CONFIG['activeSettings']['id_clid'] . ') ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.restricted_all = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.' . $aArgsSave['primarykey'] . ' = (:id) ';
		//$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.del = (:nultime) ';
		//$queryStr .= str_replace('##TYPE##', 'ext', $addCondition) . ' ';

		$queryE = $CONFIG['dbconn'][0]->prepare($queryStr);
		$queryE->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
		$queryE->bindValue(':id_count', $id_count, PDO::PARAM_INT);
		$queryE->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
		$queryE->bindValue(':id_dev', $id_dev, PDO::PARAM_INT);
		$queryE->bindValue(':nul', 0, PDO::PARAM_INT);
		//$queryE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryE->execute();
		$rowsExtloc = $queryE->fetchAll(PDO::FETCH_ASSOC); 
		$numExtloc = $queryE->rowCount();
		$numE += $numExtloc;
		
		
		
		
		##################
		// res master
		$queryStr = 'SELECT ';
		$queryStr .= $aArgsSave['table'] . 'res.id_cl, '; 
		$queryStr .= $aArgsSave['table'] . 'res.id_count, '; 
		$queryStr .= $aArgsSave['table'] . 'res.id_lang, '; 
		foreach($aArgsSave['columns'] as $column => $format){
			$queryStr .= $aArgsSave['table'] . 'res.' . $column . ', '; 
		}
		$queryStr = rtrim($queryStr, ', ');
		$queryStr .= ' ';
		$queryStr .= 'FROM ' . $aArgsSave['table'] . 'res ';
		$queryStr .= 'WHERE ' . $aArgsSave['table'] . 'res.id_count = (:id_count) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'res.id_lang = (:id_lang) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'res.id_dev = (:id_dev) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'res.id_cl IN (0,' . $CONFIG['activeSettings']['id_clid'] . ') ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'res.restricted_all = (:restricted_all) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'res.' . $aArgsSave['primarykey'] . ' = (:id) ';
		//$queryStr .= 'AND ' . $aArgsSave['table'] . 'res.del = (:nultime) ';
		//$queryStr .= str_replace('##TYPE##', 'res', $addCondition) . ' ';

		$queryE = $CONFIG['dbconn'][0]->prepare($queryStr);
		$queryE->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
		$queryE->bindValue(':id_count', 0, PDO::PARAM_INT);
		$queryE->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$queryE->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$queryE->bindValue(':restricted_all', $CONFIG['user']['restricted_all'], PDO::PARAM_STR);
		//$queryE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryE->execute();
		$rowsRes = $queryE->fetchAll(PDO::FETCH_ASSOC); 
		$numRes = $queryE->rowCount();
		$numE += $numRes;
		
		
		
		
		
		##################
		// loc master
		$queryStr = 'SELECT ';
		$queryStr .= $aArgsSave['table'] . 'loc.id_cl, '; 
		$queryStr .= $aArgsSave['table'] . 'loc.id_count, '; 
		$queryStr .= $aArgsSave['table'] . 'loc.id_lang, '; 
		foreach($aArgsSave['columns'] as $column => $format){
			$queryStr .= $aArgsSave['table'] . 'loc.' . $column . ', '; 
		}
		$queryStr = rtrim($queryStr, ', ');
		$queryStr .= ' ';
		$queryStr .= 'FROM ' . $aArgsSave['table'] . 'loc ';
		$queryStr .= 'WHERE ' . $aArgsSave['table'] . 'loc.id_count = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.id_lang = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.id_dev = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.id_cl IN (0,' . $CONFIG['activeSettings']['id_clid'] . ') ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.restricted_all = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.' . $aArgsSave['primarykey'] . ' = (:id) ';
		//$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.del = (:nultime) ';
		//$queryStr .= str_replace('##TYPE##', 'loc', $addCondition) . ' ';

		$queryE = $CONFIG['dbconn'][0]->prepare($queryStr);
		$queryE->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
		$queryE->bindValue(':nul', 0, PDO::PARAM_INT);
		//$queryE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryE->execute();
		$rowsLoc = $queryE->fetchAll(PDO::FETCH_ASSOC); 
		$numLoc = $queryE->rowCount();
		$numE += $numLoc;
		
		##################
		// loc local
		$queryStr = 'SELECT ';
		$queryStr .= $aArgsSave['table'] . 'loc.id_cl, '; 
		$queryStr .= $aArgsSave['table'] . 'loc.id_count, '; 
		$queryStr .= $aArgsSave['table'] . 'loc.id_lang, '; 
		foreach($aArgsSave['columns'] as $column => $format){
			$queryStr .= $aArgsSave['table'] . 'loc.' . $column . ', '; 
		}
		$queryStr = rtrim($queryStr, ', ');
		$queryStr .= ' ';
		$queryStr .= 'FROM ' . $aArgsSave['table'] . 'loc ';
		$queryStr .= 'WHERE ' . $aArgsSave['table'] . 'loc.id_count = (:id_count) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.id_lang = (:id_lang) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.id_dev = (:id_dev) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.id_cl IN (0,' . $CONFIG['activeSettings']['id_clid'] . ') ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.restricted_all = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.' . $aArgsSave['primarykey'] . ' = (:id) ';
		//$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.del = (:nultime) ';
		//$queryStr .= str_replace('##TYPE##', 'loc', $addCondition) . ' ';

		$queryE = $CONFIG['dbconn'][0]->prepare($queryStr);
		$queryE->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
		$queryE->bindValue(':id_count', $id_count, PDO::PARAM_INT);
		$queryE->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
		$queryE->bindValue(':id_dev', $id_dev, PDO::PARAM_INT);
		$queryE->bindValue(':nul', 0, PDO::PARAM_INT);
		//$queryE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryE->execute();
		$rowsLocloc = $queryE->fetchAll(PDO::FETCH_ASSOC); 
		$numLocloc = $queryE->rowCount();
		$numE += $numLocloc;
		
		
		$aResult = array();
		$aResult['id_cl'] = 0;
		if($numExt > 0 && $rowsExt[0]['id_cl'] > 0) $aResult['id_cl'] = $rowsExt[0]['id_cl'];
		if($numExtloc > 0 && $rowsExtloc[0]['id_cl'] > 0) $aResult['id_cl'] = $rowsExtloc[0]['id_cl'];


		if($numRes > 0 && $rowsRes[0]['id_cl'] > 0) $aResult['id_cl'] = $rowsRes[0]['id_cl'];


		if($numLoc > 0 && $rowsLoc[0]['id_cl'] > 0) $aResult['id_cl'] = $rowsLoc[0]['id_cl'];
		if($numLocloc > 0 && $rowsLocloc[0]['id_cl'] > 0) $aResult['id_cl'] = $rowsLocloc[0]['id_cl'];
		foreach($aArgsSave['columns'] as $column => $format){
			$aResult[$column] = '';
			
			if(isset($rowsExt[0][$column])){
				$aResult[$column] = $rowsExt[0][$column];
			}
			
			if(isset($rowsExtloc[0][$column])){
				if(!in_array($rowsExtloc[0][$column], $aArgsSave['excludeUpdateUni'][$column]) || $aResult[$column] == NULL) $aResult[$column] = $rowsExtloc[0][$column];
			}

			if(isset($rowsLoc[0][$column])){
				if(!in_array($rowsLoc[0][$column], $aArgsSave['excludeUpdateUni'][$column]) || $aResult[$column] == NULL) $aResult[$column] = $rowsLoc[0][$column];
			}

			if(isset($rowsRes[0][$column])){
				if(!in_array($rowsRes[0][$column], $aArgsSave['excludeUpdateUni'][$column]) || $aResult[$column] == NULL) $aResult[$column] = $rowsRes[0][$column];
			}
			
			if(isset($rowsLocloc[0][$column])){
				if(!in_array($rowsLocloc[0][$column], $aArgsSave['excludeUpdateUni'][$column]) || $aResult[$column] == NULL) $aResult[$column] = $rowsLocloc[0][$column];
			}
			
			// if restricted acces to ALL and master then overwrite _loc with _res
			if($CONFIG['user']['specifications'][14] == 8 && $variation == 'master'){
				if(isset($rowsRes[0][$column])){
					if(!in_array($rowsRes[0][$column], $aArgsSave['excludeUpdateUni'][$column]) || $aResult[$column] == NULL) $aResult[$column] = $rowsRes[0][$column];
				}
			}
		}
		

		if($numE > 0){
			$addDel = 1;
			$col = '';
			$val = '';
			$upd = "";
			foreach($aResult as $column => $value){
				if($column == 'del') $addDel = 0;
				$col .= ', ' . $column;
				$val .= ', :' . $column;
				$upd .= $column.' = (:' . $column . '), ' ;
			}

			$qry = 'INSERT INTO ' . $aArgsSave['table'] . 'uni ';
			$qry .= '(id_count, id_lang, id_dev, restricted_all, create_at, create_from, change_from' . $col . ') ';
			$qry .= 'VALUES ';					
			$qry .= '(:id_count, :id_lang, :id_dev, :restricted_all, :create_at, :create_from, :create_from' . $val . ') '; 
			$qry .= 'ON DUPLICATE KEY UPDATE ';	
			$qry .= $upd;
			$qry .= 'change_from = (:create_from), ';
			if($addDel == 1) $qry .= 'del = (:nultime) ';
			$qry = rtrim($qry, ', ');
			$qry .= ' ';
			
			
			$restricted_all = ($variation == 'master') ? $CONFIG['user']['restricted_all'] : '0';
			
			$query = $CONFIG['dbconn'][0]->prepare($qry);
			$query->bindValue(':id_count', $id_count, PDO::PARAM_INT);
			$query->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
			$query->bindValue(':id_dev', $id_dev, PDO::PARAM_INT);
			$query->bindValue(':restricted_all', $restricted_all, PDO::PARAM_STR);
			if($addDel == 1) $query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query->bindValue(':create_at', $now, PDO::PARAM_STR); 
			$query->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
			foreach($aResult as $column => $value){
				if(in_array($column, $aArgsSave['aFieldsNumbers'])){
					$query->bindValue(':'.$column, $value, PDO::PARAM_INT);
				}else{
					$query->bindValue(':'.$column, $value, PDO::PARAM_STR);
				}
			}
			$query->execute();
			$numUni = $query->rowCount();
			
//			$arr = $query->errorInfo();
//			print_r($arr);
			
			
			$num = $query->rowCount();
		}
	}
	
	
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id,
											' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
											' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
											' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
											' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl,
											' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data_read
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
		$query2a = $CONFIG['dbconn'][0]->prepare('
											SELECT * FROM uni 
											WHERE uni.id_count = (:id_count)
												AND uni.id_lang = (:id_lang)
												AND uni.id_dev = (:id_dev)
											');
		$query2a->bindValue(':id_count', $row['id_count'], PDO::PARAM_INT);
		$query2a->bindValue(':id_lang', $row['id_lang'], PDO::PARAM_INT);
		$query2a->bindValue(':id_dev', $row['id_dev'], PDO::PARAM_INT);
		$query2a->execute();
		$rows2a = $query2a->fetchAll(PDO::FETCH_ASSOC);
		$num2a = $query2a->rowCount();
		
		$aData = json_decode($row['data_read'], true);
		
		foreach($aData as $k => $v){
			if(isset($rows2a[0][$k])){
				$aData[$k] = $rows2a[0][$k];
			}
		}
		//var_dump($aData);
				
		$query2 = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata SET
												data_read = (:data)
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl = (:id_cl)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
											LIMIT 1
											');
		$query2->bindValue(':data', json_encode($aData), PDO::PARAM_STR);
		$query2->bindValue(':id_count', $row['id_count'], PDO::PARAM_INT);
		$query2->bindValue(':id_lang', $row['id_lang'], PDO::PARAM_INT);
		$query2->bindValue(':id_dev', $row['id_dev'], PDO::PARAM_INT);
		$query2->bindValue(':id_cl', $row['id_cl'], PDO::PARAM_INT);
		$query2->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
		$query2->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
		$query2->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
		$query2->execute();
		$num2 = $query2->rowCount();
//			$arr = $query2->errorInfo();
//			print_r($arr);
		
		
	}
//			$queryS = $CONFIG['dbconn'][0]->prepare('
//												SELECT * FROM uni 
//												');
//			$queryS->execute();
//			$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
//			$numS = $queryS->rowCount();
//	echo $rowsS[3]['category'];




//	
//	foreach($aArgsSaveN as $kSave => $aSave){
//		$aSave['allVersions'] = $aArgsSave['allVersions'];
//		insertAll($aSave);
//	}
//
//	#########################################

//	if(file_exists($functionPath . $functionFilePost)){ 
//		include_once($functionPath . $functionFilePost);
//	}



	
	
	//$qry .= 'ALTER TABLE products ADD INDEX (id_count,id_lang,id_pid,change_at); ';
	
	
	
	
	
	#######################################################################
	#######################################################################
	#######################################################################
	
	
	
	
	
	
	
	echo 'OK';
}else{
	echo json_encode($aError);
}


?>