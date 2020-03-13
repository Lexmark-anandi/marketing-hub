<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();
if(!isset($varSQL['forceupload'])) $varSQL['forceupload'] = 'no';

$functionPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'];
$functionFile = 'fu-' . $CONFIG['aModul']['modul_name'] . '-insert.php';
$functionFileOne2n = 'fu-' . $CONFIG['aModul']['modul_name'] . '-insert-one2n.php';
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php');


$mediaPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'];

foreach($_FILES as $field => $file){
	$variation = ($CONFIG['settings']['selectCountry'] == 0 && $CONFIG['settings']['selectLanguage'] == 0 && $CONFIG['settings']['selectDevice'] == 0) ? 'master' : 'local';
	$doUpload = false;
	foreach($CONFIG['aModul']['form'] as $aFieldsets){
		foreach($aFieldsets['fields'] as $aFields){
			if($aFields['index'] == $varSQL['orgfieldname']){
				if(in_array($aFields['specifications'][0], $CONFIG['system']['aFieldsAllowedSpecs']) && $aFields['specifications'][2] != 0) $doUpload = true;
				if($aFields['specifications'][2] != 9){
					if($variation == 'master' && $aFields['specifications'][2] != 1) $doUpload = false; 
					if($variation == 'local' && $aFields['specifications'][2] != 2) $doUpload = false; 
				}
				break;
			}
		}
	}
	
	if($doUpload == true || $varSQL['forceupload'] == 'yes'){
		#####################################################################################
		// process folder
		#####################################################################################
		include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-media-upload-folder.php');
			
			
		#####################################################################################
		// process file
		#####################################################################################
		// upload file
		$num = 0;
		$filenameOrg = (is_array($_FILES[$field]['name'])) ? $_FILES[$field]['name'][0] : $_FILES[$field]['name'];
		$lastCharOrg = strrpos($filenameOrg,".");
		$filenameOrgBase = substr($filenameOrg, 0, $lastCharOrg);
		$filenameOrgEnd = strtolower(substr(strrchr($filenameOrg, "."), 1));
		$filenameBase = md5($filenameOrgBase);
		$filename = $filenameBase . '.' . $filenameOrgEnd;
	
		$handle = opendir($mediaPath);
		while(file_exists($mediaPath . $filename)){
			$num++;
			$filename = $filenameBase . "-" . $num . '.' . $filenameOrgEnd;
		}
		closedir($handle);
		
		if(is_array($_FILES[$field]['tmp_name'])){
			move_uploaded_file($_FILES[$field]['tmp_name'][0], $mediaPath . $filename);
			chmod($mediaPath . $filename , 0777);
		}else{
			move_uploaded_file($_FILES[$field]['tmp_name'], $mediaPath . $filename);
			chmod($mediaPath . $filename , 0777);
		}
		
		// insert in db
		include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-media-upload-file.php');
		
	
		
		######################################################################
		// update tempdata
		$queryS = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data,
												' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
												' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
												' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
												' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl,
												' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.modulname
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
	
		if($numS > 0){
			foreach($rowsS as $rowS){
				$aDataTmp = json_decode($rowS['data'], true);
				$aArgsSave['orgfieldname'] = ($aArgsSave['multiple'] == 'multiple') ? str_replace('[]', '', $aArgsSave['orgfieldname']) : $aArgsSave['orgfieldname'];
				if(!isset($aDataTmp[$aArgsSave['orgfieldname']])) $aDataTmp[$aArgsSave['orgfieldname']] = ($aArgsSave['multiple'] == 'multiple') ? array() : '';
				if(!isset($aDataTmp[$aArgsSave['orgfieldname'] . 'G'])) $aDataTmp[$aArgsSave['orgfieldname'] . 'G'] = '';
				if(!isset($aDataTmp[$aArgsSave['orgfieldname'] . 'F'])) $aDataTmp[$aArgsSave['orgfieldname'] . 'F'] = '';
				
				###########################################
				// insert in tempupload
				$queryU = $CONFIG['dbconn'][0]->prepare('
													INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload
														(id, id_count, id_lang, id_dev, id_cl, id_uid, id_mod, modulname, fieldname, id_mid, create_at, create_from, change_from)
													VALUES
														(:id, :id_count, :id_lang, :id_dev, :id_cl, :id_uid, :id_mod, :modulname, :fieldname, :id_mid, :create_at, :id_uid, :id_uid)
														');
				$queryU->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
				$queryU->bindValue(':id_count', $rowS['id_count'], PDO::PARAM_INT);
				$queryU->bindValue(':id_lang', $rowS['id_lang'], PDO::PARAM_INT);
				$queryU->bindValue(':id_dev', $rowS['id_dev'], PDO::PARAM_INT);
				$queryU->bindValue(':id_cl', $rowS['id_cl'], PDO::PARAM_INT);
				$queryU->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
				$queryU->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
				$queryU->bindValue(':modulname', $rowS['modulname'], PDO::PARAM_STR);
				$queryU->bindValue(':fieldname', $aArgsSave['orgfieldname'], PDO::PARAM_STR);
				$queryU->bindValue(':id_mid', $idNew, PDO::PARAM_INT);
				$queryU->bindValue(':create_at', $now, PDO::PARAM_STR);
				$queryU->execute();
				
				// select aus tempupload	
//														AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.fieldname = (:fieldname)
				$querySu = $CONFIG['dbconn'][0]->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_mid,
														' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.fieldname
													FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload 
													WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id = (:id)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_count = (:id_count)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_lang = (:id_lang)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_dev = (:id_dev)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_cl = (:id_cl)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_uid = (:uid)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_mod = (:id_mod)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.modulname = (:modulname)
													');
				$querySu->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
				$querySu->bindValue(':id_count', $rowS['id_count'], PDO::PARAM_INT);
				$querySu->bindValue(':id_lang', $rowS['id_lang'], PDO::PARAM_INT);
				$querySu->bindValue(':id_dev', $rowS['id_dev'], PDO::PARAM_INT);
				$querySu->bindValue(':id_cl', $rowS['id_cl'], PDO::PARAM_INT);
				$querySu->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
				$querySu->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
				$querySu->bindValue(':modulname', $rowS['modulname'], PDO::PARAM_STR);
				//$querySu->bindValue(':fieldname', $aArgsSave['orgfieldname'], PDO::PARAM_STR);
				$querySu->execute();
				$rowsSu = $querySu->fetchAll(PDO::FETCH_ASSOC);
				$numSu = $querySu->rowCount();
				
				$aDataTmp['uploadedFilesId'] = array();
				foreach($rowsSu as $rowSu){
					if(is_array($aDataTmp[$rowSu['fieldname']]) && !array_key_exists($rowSu['fieldname'], $aDataTmp)) $aDataTmp[$rowSu['fieldname']] = array(); 
					array_push($aDataTmp['uploadedFilesId'], $rowSu['id_mid']);

					if(is_array($aDataTmp[$rowSu['fieldname']])){
						array_push($aDataTmp[$rowSu['fieldname']], $rowSu['id_mid']);
						if(count($aDataTmp[$rowSu['fieldname']]) > 1 && $aDataTmp[$rowSu['fieldname']][0] == 0) array_shift($aDataTmp[$rowSu['fieldname']]); 
					}else{
						$aDataTmp[$rowSu['fieldname']] = $rowSu['id_mid'];
					}
					
					if($aArgsSave['multiple'] != 'multiple' && is_array($aDataTmp[$rowSu['fieldname']])){
						$aDataTmp[$rowSu['fieldname']] = array(array_pop($aDataTmp[$rowSu['fieldname']]));
					}
				}
				
				 
				
				
				
//								if(!isset($aDataTmp['uploadedFilesId'])) $aDataTmp['uploadedFilesId'] = array();
//								array_push($aDataTmp['uploadedFilesId'], $idNew);
//								
//								if(is_array($aDataTmp[$aArgsSave['orgfieldname']])){
//									array_push($aDataTmp[$aArgsSave['orgfieldname']], $idNew);
//								}else{
//									$aDataTmp[$aArgsSave['orgfieldname']] = $idNew;
//								}
				###############################
								
								
				
				$aArgsR = array();
				$aArgsR['id_count'] = $aDataTmp['id_count'];
				$aArgsR['id_lang'] = $aDataTmp['id_lang'];
				$aArgsR['id_dev'] = $aDataTmp['id_dev'];
				$aArgsR['data'] = $aDataTmp;
				foreach($CONFIG['aModul']['form'] as $aFieldsets){
					foreach($aFieldsets['fields'] as $aFields){
						if($aFields['name'] == $aArgsSave['orgfieldname']){
							$aArgsR['fields']['file'][$aArgsSave['orgfieldname']] = $aFields['val2read']['file'];
							$aDataTmp = setValuesRead($aArgsR);
						}
					}
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
		######################################################################
	
	
		$return['files'] = array();
		$return['files']['fieldname'] = $field;
		$return['files']['orgfieldname'] = $aArgsSave['orgfieldname'];
		$return['files']['sysname'] = $filename;
		$return['files']['name'] = $filenameOrg;
		$return['files']['idfile'] = $idNew;
		$return['files']['multiple'] = $aArgsSave['multiple'];
		$return['files']['cbSendFiles'] = $varSQL['cbSendFiles'];
	
		echo json_encode($return);
	}
}

?>