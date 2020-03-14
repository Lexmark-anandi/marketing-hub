<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData(json_decode($_POST['data'], true));
$aData = json_decode($varSQL['data'], true);

#################################################################
// Form check
#################################################################
$checkfileModul = $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-' . $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'] . '-formcheck.php';
if(file_exists($checkfileModul)) include_once($checkfileModul);

$aError = array();
foreach($varSQL['check'] as $aCheck){
	$field = (isset($aCheck['field'])) ? $aCheck['field'] : '';
	$aFunction = (isset($aCheck['function'])) ? explode(';', $aCheck['function']) : array();
	$message = (isset($aCheck['message'])) ? $aCheck['message'] : '';
	
	foreach($aFunction as $function){
		if($function != '' && function_exists($function)){
			$res = $function($field, $aData);
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
	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data,
											' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:keyCountry)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:keyLanguage)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev = (:keyDevice)
											AND (' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid = (:clid)
												OR ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid = (:nul))
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.modulname = (:modulname)
										');
	$query->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
	$query->bindValue(':keyCountry', $aData['id_count'], PDO::PARAM_INT);
	$query->bindValue(':keyLanguage', $aData['id_lang'], PDO::PARAM_INT);
	$query->bindValue(':keyDevice', $aData['id_dev'], PDO::PARAM_INT);
	$query->bindValue(':clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
	$query->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
	$query->bindValue(':nul', 0, PDO::PARAM_INT);
	$query->bindValue(':modulname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	
	if($num == 0){
		$query2 = $CONFIG['dbconn']->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
												(
												id,
												id_count,
												id_lang,
												id_dev,
												id_clid,
												id_uid,
												modulname,
												data
												)
											VALUES
												(
												:id,
												:keyCountry,
												:keyLanguage,
												:keyDevice,
												:clid,
												:uid,
												:modulname,
												:data
												)
											');
		$query2->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
		$query2->bindValue(':keyCountry', $aData['id_count'], PDO::PARAM_INT);
		$query2->bindValue(':keyLanguage', $aData['id_lang'], PDO::PARAM_INT);
		$query2->bindValue(':keyDevice', $aData['id_dev'], PDO::PARAM_INT);
		$query2->bindValue(':clid', $aData['id_clid'], PDO::PARAM_INT);
		$query2->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
		$query2->bindValue(':modulname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
		$query2->bindValue(':data', $varSQL['data'], PDO::PARAM_STR);
		$query2->execute();
	}else{
		$query2 = $CONFIG['dbconn']->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata SET
												data = (:data)
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:keyCountry)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:keyLanguage)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev = (:keyDevice)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid = (:clid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.modulname = (:modulname)
											LIMIT 1
											');
		$query2->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
		$query2->bindValue(':keyCountry', $aData['id_count'], PDO::PARAM_INT);
		$query2->bindValue(':keyLanguage', $aData['id_lang'], PDO::PARAM_INT);
		$query2->bindValue(':keyDevice', $aData['id_dev'], PDO::PARAM_INT);
		$query2->bindValue(':clid', $rows[0]['id_clid'], PDO::PARAM_INT);
		$query2->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
		$query2->bindValue(':modulname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
		$query2->bindValue(':data', $varSQL['data'], PDO::PARAM_STR);
		$query2->execute();
	}
	
	
	########################################################################
	// Synchronize fields
	foreach($varSQL['sync'] as $sync => $aSync){
		$numS = 0;
		if($sync == 'all'){
			$queryS = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid
												FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
													AND (' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid = (:clid)
														OR ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid = (:nul))
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.modulname = (:modulname)
												');
			$queryS->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
			$queryS->bindValue(':clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
			$queryS->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
			$queryS->bindValue(':nul', 0, PDO::PARAM_INT);
			$queryS->bindValue(':modulname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
			$queryS->execute();
			$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
			$numS = $queryS->rowCount();
		}
		
		if($sync == 'country'){
			$queryS = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid
												FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:keyLanguage)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev = (:keyDevice)
													AND (' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid = (:clid)
														OR ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid = (:nul))
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.modulname = (:modulname)
												');
			$queryS->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
			$queryS->bindValue(':keyLanguage', $varSQL['formLanguage'], PDO::PARAM_INT);
			$queryS->bindValue(':keyDevice', $varSQL['formDevice'], PDO::PARAM_INT);
			$queryS->bindValue(':clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
			$queryS->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
			$queryS->bindValue(':nul', 0, PDO::PARAM_INT);
			$queryS->bindValue(':modulname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
			$queryS->execute();
			$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
			$numS = $queryS->rowCount();
		}
		
		if($sync == 'language'){
			$queryS = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid
												FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:keyCountry)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev = (:keyDevice)
													AND (' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid = (:clid)
														OR ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid = (:nul))
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.modulname = (:modulname)
												');
			$queryS->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
			$queryS->bindValue(':keyCountry', $varSQL['formCountry'], PDO::PARAM_INT);
			$queryS->bindValue(':keyDevice', $varSQL['formDevice'], PDO::PARAM_INT);
			$queryS->bindValue(':clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
			$queryS->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
			$queryS->bindValue(':nul', 0, PDO::PARAM_INT);
			$queryS->bindValue(':modulname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
			$queryS->execute();
			$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
			$numS = $queryS->rowCount();
		}
		
		if($sync == 'device'){
			$queryS = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid
												FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:keyCountry)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:keyLanguage)
													AND (' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid = (:clid)
														OR ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid = (:nul))
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.modulname = (:modulname)
												');
			$queryS->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
			$queryS->bindValue(':keyCountry', $varSQL['formCountry'], PDO::PARAM_INT);
			$queryS->bindValue(':keyLanguage', $varSQL['formLanguage'], PDO::PARAM_INT);
			$queryS->bindValue(':clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
			$queryS->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
			$queryS->bindValue(':nul', 0, PDO::PARAM_INT);
			$queryS->bindValue(':modulname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
			$queryS->execute();
			$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
			$numS = $queryS->rowCount();
		}
		
		if($sync == 'countrylanguage'){
			$queryS = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid
												FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev = (:keyDevice)
													AND (' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid = (:clid)
														OR ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid = (:nul))
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.modulname = (:modulname)
												');
			$queryS->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
			$queryS->bindValue(':keyDevice', $varSQL['formDevice'], PDO::PARAM_INT);
			$queryS->bindValue(':clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
			$queryS->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
			$queryS->bindValue(':nul', 0, PDO::PARAM_INT);
			$queryS->bindValue(':modulname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
			$queryS->execute();
			$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
			$numS = $queryS->rowCount();
		}
		
		if($sync == 'countrydevice'){
			$queryS = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid
												FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:keyLanguage)
													AND (' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid = (:clid)
														OR ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid = (:nul))
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.modulname = (:modulname)
												');
			$queryS->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
			$queryS->bindValue(':keyLanguage', $varSQL['formLanguage'], PDO::PARAM_INT);
			$queryS->bindValue(':clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
			$queryS->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
			$queryS->bindValue(':nul', 0, PDO::PARAM_INT);
			$queryS->bindValue(':modulname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
			$queryS->execute();
			$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
			$numS = $queryS->rowCount();
		}
		
		if($sync == 'languagedevice'){
			$queryS = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
													' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid
												FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:keyCountry)
													AND (' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid = (:clid)
														OR ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid = (:nul))
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.modulname = (:modulname)
												');
			$queryS->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
			$queryS->bindValue(':keyCountry', $varSQL['formCountry'], PDO::PARAM_INT);
			$queryS->bindValue(':clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
			$queryS->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
			$queryS->bindValue(':nul', 0, PDO::PARAM_INT);
			$queryS->bindValue(':modulname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
			$queryS->execute();
			$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
			$numS = $queryS->rowCount();
		}
		
		if($numS > 0){
			foreach($rowsS as $rowS){
				$aDataTmp = json_decode($rowS['data'], true);
				
				foreach($aSync as $field){
					if(isset($aDataTmp[$field])) $aDataTmp[$field] = $aData[$field];
				}
				
				$query2 = $CONFIG['dbconn']->prepare('
													UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata SET
														data = (:data)
													WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:keyCountry)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:keyLanguage)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev = (:keyDevice)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.modulname = (:modulname)
													LIMIT 1
													');
				$query2->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
				$query2->bindValue(':keyCountry', $rowS['id_count'], PDO::PARAM_INT);
				$query2->bindValue(':keyLanguage', $rowS['id_lang'], PDO::PARAM_INT);
				$query2->bindValue(':keyDevice', $rowS['id_dev'], PDO::PARAM_INT);
				$query2->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
				$query2->bindValue(':modulname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
				$query2->bindValue(':data', json_encode($aDataTmp), PDO::PARAM_STR);
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