<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$functionPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'];
$functionFile = 'fu-' . $CONFIG['aModul']['modul_name'] . '-copy.php';
$functionFileOne2n = 'fu-' . $CONFIG['aModul']['modul_name'] . '-copy-one2n.php';
$functionFilePost = 'fu-' . $CONFIG['aModul']['modul_name'] . '-copy-post.php';
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php');

 
if(file_exists($functionPath . $functionFile)){ 
	include_once($functionPath . $functionFile);
	
}else{
	$date = new DateTime();
	$now = $date->format('Y-m-d H:i:s');
	
//	$aUploadedFilesId = array();
//	
//	$aArgs = array();
//	$aArgs['id_count'] = $CONFIG['settings']['selectCountry'];
//	$aArgs['id_lang'] = $CONFIG['settings']['selectLanguage'];
//	$aArgs['id_dev'] = $CONFIG['settings']['selectDevice'];
//	$aArgs['usesystem'] = 1;
//	$aArgs['fields'] = array();
//
//	$aFieldsSaveMaster = array();
//	$aFieldsSaveNotMaster = array();
//
//	$aArgsSaveN = array();
//
//	$aArgsSave = array();
//	$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . $CONFIG['aModul']['table_name'];
//	$aArgsSave['primarykey'] = $CONFIG['aModul']['primarykey'];
//	$aArgsSave['allVersions'] = array();
//	$aArgsSave['changedVersions'] = array();
//	
//	$aArgsSave['columns'] = array();
//	$aArgsSave['aFieldsNumbers'] = array();
//	$aArgsSave['excludeUpdateUni'] = array();
//	foreach($CONFIG['aModul']['form'] as $aFieldsets){
//		foreach($aFieldsets['fields'] as $field){
//			if(in_array($field['specifications'][0], $CONFIG['system']['aFieldsAllowedSpecs']) && $field['specifications'][2] != 0){
//				if($field['specifications'][2] == 9){
//					array_push($aFieldsSaveMaster, $field['colname_save']);
//					array_push($aFieldsSaveNotMaster, $field['colname_save']);
//				}
//				if($field['specifications'][2] == 2){
//					array_push($aFieldsSaveNotMaster, $field['colname_save']);
//				}
//				if($field['specifications'][2] == 1){
//					array_push($aFieldsSaveMaster, $field['colname_save']);
//				}
//				
//				if(count($field['val2read']) > 0){
//					foreach($field['val2read'] as $type => $aVal2read){
//						if(!array_key_exists($type, $aArgs['fields'])) $aArgs['fields'][$type] = array();
//						$aArgs['fields'][$type][$field['index']] = $aVal2read;
//					}
//				}
//	
//				if($field['table_save'] == $CONFIG['aModul']['table_name'] && ($field['array'] == 0 || $field['type'] == 'file')){
//					$aArgsSave['columns'][$field['colname_save']] = $field['format'];
//					if($field['format'] == 'i' || $field['format'] == 'si') array_push($aArgsSave['aFieldsNumbers'], $field['colname_save']);
//					
//					// define values for excluding in '_uni'
//					$aArgsSave['excludeUpdateUni'][$field['colname_save']] = array();
//					if($field['always_update'] == 0){
//						switch($field['format']){
//							case 's':
//								array_push($aArgsSave['excludeUpdateUni'][$field['colname_save']], ''); 
//								break;
//							case 'i':
//								array_push($aArgsSave['excludeUpdateUni'][$field['colname_save']], ''); 
//								break;
//							case 'si':
//								array_push($aArgsSave['excludeUpdateUni'][$field['colname_save']], 0); 
//								break;
//							case 'f':
//								array_push($aArgsSave['excludeUpdateUni'][$field['colname_save']], ''); 
//								break;
//							case 'd':
//								array_push($aArgsSave['excludeUpdateUni'][$field['colname_save']], '0000-00-00'); 
//								array_push($aArgsSave['excludeUpdateUni'][$field['colname_save']], '0000-00-00 00:00:00'); 
//								break;
//							case 'c':
//								array_push($aArgsSave['excludeUpdateUni'][$field['colname_save']], ''); 
//								break;
//							case 'b':
//								array_push($aArgsSave['excludeUpdateUni'][$field['colname_save']], 0); 
//								break;
//						}
//					}
//				}
//			}
//		} 
//	}
//
//	$aArgsLV = array();
//	$aArgsLV['type'] = 'all';
//	$aLocalVersions = localVariationsBuild($aArgsLV);
//	
//	
//	$query = $CONFIG['dbconn'][0]->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id,
//											' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
//											' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
//											' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
//											' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl,
//											' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
//										');
//	$query->bindValue(':id', 0, PDO::PARAM_INT);
//	$query->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
//	$query->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
//	$query->execute();
//	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//	$num = $query->rowCount();
//	
//	// create Loop for uploading multiple images (1:n)
//	$aLoop = array(0);
//	if(isset($CONFIG['aModul']['addoptions']['insertLoopField']) && $CONFIG['aModul']['addoptions']['insertLoopField'] != ''){
//		$dataTmp = json_decode($rows[0]['data'], true);
//		if(is_array($dataTmp[$CONFIG['aModul']['addoptions']['insertLoopField']])){
//			$aLoop = $dataTmp[$CONFIG['aModul']['addoptions']['insertLoopField']];
//		}else{
//			$aLoop = array($dataTmp[$CONFIG['aModul']['addoptions']['insertLoopField']]);
//		}
//	}
//	$aLoop = array_unique($aLoop);
//	foreach($aLoop as $idLoop){	
//		$aUploadedFilesId = array();
//
//		######################################################
//		// create new ID
//		$queryI = $CONFIG['dbconn'][0]->prepare('
//											INSERT INTO ' . $aArgsSave['table'] . '
//											(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
//											VALUES
//											(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
//											');
//		$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
//		$queryI->bindValue(':id_cl', $rows[0]['id_cl'], PDO::PARAM_INT);
//		$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
//		$queryI->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
//		$queryI->execute();
//		$aArgsSave['id_data'] = $CONFIG['dbconn'][0]->lastInsertId();
//		######################################################
//	
//		
//		foreach($rows as $row){
//			$variation = ($row['id_count'] == 0 && $row['id_lang'] == 0 && $row['id_dev'] == 0) ? 'master' : 'local';
//			
//			$aArgs['data'] = json_decode($row['data'], true);
//			if(isset($CONFIG['aModul']['addoptions']['insertLoopField']) && $CONFIG['aModul']['addoptions']['insertLoopField'] != ''){
//				##################################################################
//				// Handle uploadfield with multiple upload for editing single file
//				$aArgs['data'][$CONFIG['aModul']['addoptions']['insertLoopField']] = $idLoop;
//			}
//			if(isset($aArgs['data']['uploadedFilesId'])) $aUploadedFilesId = array_merge($aUploadedFilesId, $aArgs['data']['uploadedFilesId']);
//	
//			$aArgsSave['aData'] = setValuesSave($aArgs);
//			$aArgsSave['aData']['id_count'] = $row['id_count'];
//			$aArgsSave['aData']['id_lang'] = $row['id_lang'];
//			$aArgsSave['aData']['id_dev'] = $row['id_dev'];
//			$aArgsSave['aData']['id_cl'] = $row['id_cl'];
//		
//			$col = '';
//			$val = '';
//			$upd = '';
//			foreach($aArgsSave['columns'] as $field => $format){
//				if(($variation == 'master' && in_array($field, $aFieldsSaveMaster)) || ($variation == 'local' && in_array($field, $aFieldsSaveNotMaster))){
//					if($field != $aArgsSave['primarykey']){
//						$col .= ', ' . $field;
//						$val .= ', :' . $field . '';
//						$upd .= $field.' = (:'.$field.'), ' ;
//					}
//				}
//			}
//	
////			if(in_array(array(0,0,0), $aLocalVersions)){
////			}
//			// insert master null
//			$qry = 'INSERT INTO ' . $aArgsSave['table'] . 'loc
//						(' . $aArgsSave['primarykey'] . ', id_count, id_lang, id_dev, id_cl, restricted_all, create_at, create_from, change_from' . $col . ')
//					VALUES
//						(:id, :id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :now, :create_from, :create_from' . $val . ')
//					ON DUPLICATE KEY UPDATE 
//						' . $upd . '
//						change_from = (:create_from),
//						del = (:nultime)
//					';
//			$queryC = $CONFIG['dbconn'][0]->prepare($qry);
//			$queryC->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
//			$queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
//			$queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
//			$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
//			$queryC->bindValue(':id_cl', $aArgsSave['aData']['id_cl'], PDO::PARAM_INT);
//			$queryC->bindValue(':restricted_all', 0, PDO::PARAM_STR);
//			$queryC->bindValue(':now', $now, PDO::PARAM_STR);
//			$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//			$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
//			
//			foreach($aArgsSave['columns'] as $field => $format){
//				if(($variation == 'master' && in_array($field, $aFieldsSaveMaster)) || ($variation == 'local' && in_array($field, $aFieldsSaveNotMaster))){
//					if($field != $aArgsSave['primarykey']){
//						if($format == 'i' || $format == 'si' || $format == 'b'){
//							$queryC->bindValue(':'.$field, (is_array($aArgsSave['aData'][$field])) ? json_encode($aArgsSave['aData'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_INT);
//						}else{ 
//							$queryC->bindValue(':'.$field, (is_array($aArgsSave['aData'][$field])) ? json_encode($aArgsSave['aData'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_STR);
//						}
//					}
//				}
//			}
//			$queryC->execute();
//			$numC = $queryC->rowCount();
//	
//	
//			// insert local version
//			$qry = 'INSERT INTO ' . $aArgsSave['table'] . 'loc
//						(' . $aArgsSave['primarykey'] . ', id_count, id_lang, id_dev, id_cl, restricted_all, create_at, create_from, change_from' . $col . ')
//					VALUES
//						(:id, :id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :now, :create_from, :create_from' . $val . ')
//					ON DUPLICATE KEY UPDATE 
//						' . $upd . '
//						change_from = (:create_from),
//						del = (:nultime)
//					';
//			$queryC = $CONFIG['dbconn'][0]->prepare($qry);
//			$queryC->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
//			$queryC->bindValue(':id_count', $aArgsSave['aData']['id_count'], PDO::PARAM_INT);
//			$queryC->bindValue(':id_lang', $aArgsSave['aData']['id_lang'], PDO::PARAM_INT);
//			$queryC->bindValue(':id_dev', $aArgsSave['aData']['id_dev'], PDO::PARAM_INT);
//			$queryC->bindValue(':id_cl', $aArgsSave['aData']['id_cl'], PDO::PARAM_INT);
//			$queryC->bindValue(':restricted_all', 0, PDO::PARAM_STR);
//			$queryC->bindValue(':now', $now, PDO::PARAM_STR);
//			$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//			$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
//			
//			foreach($aArgsSave['columns'] as $field => $format){
//				if(($variation == 'master' && in_array($field, $aFieldsSaveMaster)) || ($variation == 'local' && in_array($field, $aFieldsSaveNotMaster))){
//					if($field != $aArgsSave['primarykey']){
//						if($format == 'i' || $format == 'si' || $format == 'b'){
//							$queryC->bindValue(':'.$field, (is_array($aArgsSave['aData'][$field])) ? json_encode($aArgsSave['aData'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_INT);
//						}else{ 
//							$queryC->bindValue(':'.$field, (is_array($aArgsSave['aData'][$field])) ? json_encode($aArgsSave['aData'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_STR);
//						}
//					}
//				}
//			}
//			$queryC->execute();
//			$numC = $queryC->rowCount();
//			
//		
//			#########################################
//			// save 1 to n fields
//			if(file_exists($functionPath . $functionFileOne2n)){
//				include($functionPath . $functionFileOne2n);
//			}else{
//				include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-default-one2n.php');
//			}
//		}
//	
//		#########################################
//	
//		// update mediafiles
//		$aUploadedFilesId = array_unique($aUploadedFilesId);
//		if(count($aUploadedFilesId) > 0){
//			$query2 = $CONFIG['dbconn'][0]->prepare('
//												UPDATE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_ext SET
//													id_data = (:id_data)
//												WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_ext.id_mid IN (' . implode(',', $aUploadedFilesId) . ')
//													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_ext.id_mid = (:id_mid)
//													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_ext.id_mod = (:id_mod)
//													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_ext.id_mod_parent = (:id_mod_parent)
//													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_ext.id_page = (:id_page)
//													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_ext.create_from = (:create_from)
//												');
//			$query2->bindValue(':id_mid', $idLoop, PDO::PARAM_INT);
//			$query2->bindValue(':id_data', $aArgsSave['id_data'], PDO::PARAM_INT);
//			$query2->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
//			$query2->bindValue(':id_mod_parent', $CONFIG['page']['id_mod_parent'], PDO::PARAM_INT);
//			$query2->bindValue(':id_page', $CONFIG['page']['id_page'], PDO::PARAM_INT);
//			$query2->bindValue(':create_from', $CONFIG['user']['id'], PDO::PARAM_INT);
//			$query2->execute();
//	
//			$query2 = $CONFIG['dbconn'][0]->prepare('
//												UPDATE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc SET
//													id_data = (:id_data)
//												WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mid IN (' . implode(',', $aUploadedFilesId) . ')
//													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mid = (:id_mid)
//													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mod = (:id_mod)
//													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mod_parent = (:id_mod_parent)
//													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_page = (:id_page)
//													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.create_from = (:create_from)
//												');
//			$query2->bindValue(':id_mid', $idLoop, PDO::PARAM_INT);
//			$query2->bindValue(':id_data', $aArgsSave['id_data'], PDO::PARAM_INT);
//			$query2->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
//			$query2->bindValue(':id_mod_parent', $CONFIG['page']['id_mod_parent'], PDO::PARAM_INT);
//			$query2->bindValue(':id_page', $CONFIG['page']['id_page'], PDO::PARAM_INT);
//			$query2->bindValue(':create_from', $CONFIG['user']['id'], PDO::PARAM_INT);
//			$query2->execute();
//	
//			$query2 = $CONFIG['dbconn'][0]->prepare('
//												UPDATE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni SET
//													id_data = (:id_data)
//												WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid IN (' . implode(',', $aUploadedFilesId) . ')
//													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid = (:id_mid)
//													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mod = (:id_mod)
//													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mod_parent = (:id_mod_parent)
//													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_page = (:id_page)
//													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.create_from = (:create_from)
//												');
//			$query2->bindValue(':id_mid', $idLoop, PDO::PARAM_INT);
//			$query2->bindValue(':id_data', $aArgsSave['id_data'], PDO::PARAM_INT);
//			$query2->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
//			$query2->bindValue(':id_mod_parent', $CONFIG['page']['id_mod_parent'], PDO::PARAM_INT);
//			$query2->bindValue(':id_page', $CONFIG['page']['id_page'], PDO::PARAM_INT);
//			$query2->bindValue(':create_from', $CONFIG['user']['id'], PDO::PARAM_INT);
//			$query2->execute();
//		}
//		
//	
//		$aArgsSave['changedVersions'] = array(array(0,0,0));
//		$aArgsSave['allVersions'] = $aLocalVersions;
//		insertAll($aArgsSave);
//		
//		foreach($aArgsSaveN as $kSave => $aSave){
//			$aSave['allVersions'] = $aArgsSave['allVersions'];
//			insertAll($aSave);
//		}
//	
//		#########################################
//	
//		if(file_exists($functionPath . $functionFilePost)){ 
//			include($functionPath . $functionFilePost);
//		}
//	
//		#########################################
//	}
//	
//	#########################################
//
//	$query2 = $CONFIG['dbconn'][0]->prepare('
//										DELETE 
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
//										');
//	$query2->bindValue(':id', 0, PDO::PARAM_INT);
//	$query2->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
//	$query2->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
//	$query2->execute();
//
//	$query2 = $CONFIG['dbconn'][0]->prepare('
//										DELETE 
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload 
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id = (:id)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_uid = (:uid)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_mod = (:id_mod)
//										');
//	$query2->bindValue(':id', 0, PDO::PARAM_INT);
//	$query2->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
//	$query2->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
//	$query2->execute();
//
//	#########################################
//
//
//
//
//	$out = array();
//	$out['id_data'] = $aArgsSave['id_data'];
//	
//	echo json_encode($out);
}

?>