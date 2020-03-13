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
	
	echo 'OK';
}else{
	echo json_encode($aError);
}


?>