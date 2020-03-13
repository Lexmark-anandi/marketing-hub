<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();
$aResultFiles = json_decode($varSQL['files'], true);

foreach($aResultFiles as $field => $aFile){
	$idfile = $aFile['idfile'];
}

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

##############################################################
##############################################################
##############################################################
// create new mediafiles
foreach($aResultFiles as $field => $aFile){
	$aFieldname = explode('_', $field);
	
	$queryMo = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mpid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_data,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mod,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mod_parent,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_page,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.fieldname,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.filename,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.filesys_filename,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.filehash,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.width,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.height,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.mediatype,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.size,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.filetype,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.alttext,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.keywords
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mid = (:id_mid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_lang = (:id_lang)
										');
	$queryMo->bindValue(':id_mid', $aFile['idfile'], PDO::PARAM_INT);
	$queryMo->bindValue(':id_count', $aFieldname[0], PDO::PARAM_INT);
	$queryMo->bindValue(':id_lang', $aFieldname[1], PDO::PARAM_INT);
	$queryMo->execute();
	$rowsMo = $queryMo->fetchAll(PDO::FETCH_ASSOC);
	$numMo = $queryMo->rowCount();
	

	$aArgsM = array();
	$aArgsM['id_count'] = $CONFIG['settings']['formCountry'];
	$aArgsM['id_lang'] = $CONFIG['settings']['formLanguage'];
	$aArgsM['id_dev'] = $CONFIG['settings']['formDevice'];
	$aArgsM['usesystem'] = 1;
	$aArgsM['fields'] = array();
	
	$aArgsM['data'] = array();
	$aArgsM['data']['id_mid'] = 0;
	$aArgsM['data']['id_mpid'] = $rowsMo[0]['id_mpid'];
	$aArgsM['data']['id_data'] = $rowsMo[0]['id_data'];
	$aArgsM['data']['id_mod'] = $rowsMo[0]['id_mod'];
	$aArgsM['data']['id_mod_parent'] = $rowsMo[0]['id_mod_parent'];
	$aArgsM['data']['id_page'] = $rowsMo[0]['id_page'];
	$aArgsM['data']['fieldname'] = $varSQL['tpe'];
	$aArgsM['data']['filename'] = $rowsMo[0]['filename'];
	$aArgsM['data']['filesys_filename'] = $rowsMo[0]['filesys_filename'];
	$aArgsM['data']['filehash'] = $rowsMo[0]['filehash'];
	$aArgsM['data']['width'] = $rowsMo[0]['width'];
	$aArgsM['data']['height'] = $rowsMo[0]['height'];
	$aArgsM['data']['mediatype'] = $rowsMo[0]['mediatype'];
	$aArgsM['data']['size'] = $rowsMo[0]['size'];
	$aArgsM['data']['filetype'] = $rowsMo[0]['filetype'];
	$aArgsM['data']['alttext'] = $rowsMo[0]['alttext'];
	$aArgsM['data']['keywords'] = $rowsMo[0]['keywords'];
	
	
	$aArgsSaveMN = array();
	
	$aArgsSaveM = array();
	$aArgsSaveM['table'] = $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_';
	$aArgsSaveM['primarykey'] = 'id_mid';
	$aArgsSaveM['allVersions'] = array();
	$aArgsSaveM['changedVersions'] = array();
	
	$aArgsSaveM['columns'] = array();
	$aArgsSaveM['columns']['id_mid'] = 'i';
	$aArgsSaveM['columns']['id_mpid'] = 'i';
	$aArgsSaveM['columns']['id_data'] = 'i';
	$aArgsSaveM['columns']['id_mod'] = 'i';
	$aArgsSaveM['columns']['id_mod_parent'] = 'i';
	$aArgsSaveM['columns']['id_page'] = 'i';
	$aArgsSaveM['columns']['fieldname'] = 's';
	$aArgsSaveM['columns']['filename'] = 's';
	$aArgsSaveM['columns']['filesys_filename'] = 's';
	$aArgsSaveM['columns']['filehash'] = 's';
	$aArgsSaveM['columns']['width'] = 'i';
	$aArgsSaveM['columns']['height'] = 'i';
	$aArgsSaveM['columns']['mediatype'] = 's';
	$aArgsSaveM['columns']['size'] = 'i';
	$aArgsSaveM['columns']['filetype'] = 's';
	$aArgsSaveM['columns']['alttext'] = 's';
	$aArgsSaveM['columns']['keywords'] = 's';
	
	
	$aArgsSaveM['aFieldsNumbers'] = array();
	array_push($aArgsSaveM['aFieldsNumbers'], 'id_mid');
	array_push($aArgsSaveM['aFieldsNumbers'], 'id_mpid');
	array_push($aArgsSaveM['aFieldsNumbers'], 'id_data');
	array_push($aArgsSaveM['aFieldsNumbers'], 'id_mod');
	array_push($aArgsSaveM['aFieldsNumbers'], 'id_mod_parent');
	array_push($aArgsSaveM['aFieldsNumbers'], 'id_page');
	array_push($aArgsSaveM['aFieldsNumbers'], 'width');
	array_push($aArgsSaveM['aFieldsNumbers'], 'height');
	array_push($aArgsSaveM['aFieldsNumbers'], 'size');
	
	$aArgsSaveM['excludeUpdateUni'] = array();
	$aArgsSaveM['excludeUpdateUni']['id_mid'] = array('',0);
	$aArgsSaveM['excludeUpdateUni']['id_mpid'] = array('',0);
	$aArgsSaveM['excludeUpdateUni']['id_data'] = array('',0);
	$aArgsSaveM['excludeUpdateUni']['id_mod'] = array('',0);
	$aArgsSaveM['excludeUpdateUni']['id_mod_parent'] = array('',0);
	$aArgsSaveM['excludeUpdateUni']['id_page'] = array('',0);
	$aArgsSaveM['excludeUpdateUni']['fieldname'] = array('');
	$aArgsSaveM['excludeUpdateUni']['filename'] = array('');
	$aArgsSaveM['excludeUpdateUni']['filesys_filename'] = array('');
	$aArgsSaveM['excludeUpdateUni']['filehash'] = array('');
	$aArgsSaveM['excludeUpdateUni']['width'] = array('',0);
	$aArgsSaveM['excludeUpdateUni']['height'] = array('',0);
	$aArgsSaveM['excludeUpdateUni']['mediatype'] = array('');
	$aArgsSaveM['excludeUpdateUni']['size'] = array('',0);
	$aArgsSaveM['excludeUpdateUni']['filetype'] = array('');
	$aArgsSaveM['excludeUpdateUni']['alttext'] = array('');
	$aArgsSaveM['excludeUpdateUni']['keywords'] = array('');
	
	$aArgsSaveM['aData'] = setValuesSave($aArgsM);
	$aArgsSaveM['aData']['id_count'] = $aArgsM['id_count'];
	$aArgsSaveM['aData']['id_lang'] = $aArgsM['id_lang'];
	$aArgsSaveM['aData']['id_dev'] = $aArgsM['id_dev'];
	$aArgsSaveM['aData']['id_cl'] = $CONFIG['activeSettings']['id_clid'];
	
	$aFieldsSaveMasterM = array();
	array_push($aFieldsSaveMasterM, 'id_mid');
	array_push($aFieldsSaveMasterM, 'id_mpid');
	array_push($aFieldsSaveMasterM, 'id_data');
	array_push($aFieldsSaveMasterM, 'id_mod');
	array_push($aFieldsSaveMasterM, 'id_mod_parent');
	array_push($aFieldsSaveMasterM, 'id_page');
	array_push($aFieldsSaveMasterM, 'fieldname');
	array_push($aFieldsSaveMasterM, 'filename');
	array_push($aFieldsSaveMasterM, 'filesys_filename');
	array_push($aFieldsSaveMasterM, 'filehash');
	array_push($aFieldsSaveMasterM, 'width');
	array_push($aFieldsSaveMasterM, 'height');
	array_push($aFieldsSaveMasterM, 'mediatype');
	array_push($aFieldsSaveMasterM, 'size');
	array_push($aFieldsSaveMasterM, 'filetype');
	array_push($aFieldsSaveMasterM, 'alttext');
	array_push($aFieldsSaveMasterM, 'keywords');
	
	$aFieldsSaveNotMasterM = array();
	array_push($aFieldsSaveNotMasterM, 'id_mid');
	array_push($aFieldsSaveNotMasterM, 'id_mpid');
	array_push($aFieldsSaveNotMasterM, 'id_data');
	array_push($aFieldsSaveNotMasterM, 'id_mod');
	array_push($aFieldsSaveNotMasterM, 'id_mod_parent');
	array_push($aFieldsSaveNotMasterM, 'id_page');
	array_push($aFieldsSaveNotMasterM, 'fieldname');
	array_push($aFieldsSaveNotMasterM, 'filename');
	array_push($aFieldsSaveNotMasterM, 'filesys_filename');
	array_push($aFieldsSaveNotMasterM, 'filehash');
	array_push($aFieldsSaveNotMasterM, 'width');
	array_push($aFieldsSaveNotMasterM, 'height');
	array_push($aFieldsSaveNotMasterM, 'mediatype');
	array_push($aFieldsSaveNotMasterM, 'size');
	array_push($aFieldsSaveNotMasterM, 'filetype');
	array_push($aFieldsSaveNotMasterM, 'alttext');
	array_push($aFieldsSaveNotMasterM, 'keywords');
	
	$aArgsMLV = array();
	$aArgsMLV['type'] = 'all';
	$aLocalVersionsM = localVariationsBuild($aArgsMLV);


	$variation = ($aArgsM['id_count'] == 0 && $aArgsM['id_lang'] == 0 && $aArgsM['id_dev'] == 0) ? 'master' : 'local';
	
	
	$col = '';
	$val = '';
	$upd = '';
	foreach($aArgsSaveM['columns'] as $field => $format){
		if(($variation == 'master' && in_array($field, $aFieldsSaveMasterM)) || ($variation == 'local' && in_array($field, $aFieldsSaveNotMasterM))){
			if($field != $aArgsSaveM['primarykey']){
				$col .= ', ' . $field;
				$val .= ', :' . $field . '';
				$upd .= $field.' = (:'.$field.'), ' ;
			}
		}
	}


	// create new ID
	$queryMo2 = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mid
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_data = (:id_data)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mod = (:id_mod)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.fieldname = (:fieldname)
										');
	$queryMo2->bindValue(':id_data', $aArgsM['data']['id_data'], PDO::PARAM_INT);
	$queryMo2->bindValue(':id_mod', $aArgsM['data']['id_mod'], PDO::PARAM_INT);
	$queryMo2->bindValue(':fieldname', $aArgsM['data']['fieldname'], PDO::PARAM_STR);
	$queryMo2->execute();
	$rowsMo2 = $queryMo2->fetchAll(PDO::FETCH_ASSOC);
	$numMo2 = $queryMo2->rowCount();

	if($numMo2 == 0){
		$queryI = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $aArgsSaveM['table'] . '
											(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
											VALUES
											(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
											');
		$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryI->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
		$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
		$queryI->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
		$queryI->execute();
		$aArgsM['data']['id_mid'] = $CONFIG['dbconn'][0]->lastInsertId();
		$aArgsSaveM['id_data'] = $aArgsM['data']['id_mid'];
		
		
		// insert master null
		$qry = 'INSERT INTO ' . $aArgsSaveM['table'] . 'loc
					(' . $aArgsSaveM['primarykey'] . ', id_count, id_lang, id_dev, id_cl, restricted_all, create_at, create_from, change_from' . $col . ')
				VALUES
					(:id, :id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :now, :create_from, :create_from' . $val . ')
				ON DUPLICATE KEY UPDATE 
					' . $upd . '
					change_from = (:create_from),
					del = (:nultime)
				';
		$queryC = $CONFIG['dbconn'][0]->prepare($qry);
		$queryC->bindValue(':id', $aArgsSaveM['id_data'], PDO::PARAM_INT);
		$queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
		$queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$queryC->bindValue(':id_cl', $aArgsSaveM['aData']['id_cl'], PDO::PARAM_INT);
		$queryC->bindValue(':restricted_all', 0, PDO::PARAM_STR);
		$queryC->bindValue(':now', $now, PDO::PARAM_STR);
		$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
		
		foreach($aArgsSaveM['columns'] as $field => $format){
			if(($variation == 'master' && in_array($field, $aFieldsSaveMasterM)) || ($variation == 'local' && in_array($field, $aFieldsSaveNotMasterM))){
				if($field != $aArgsSaveM['primarykey']){
					if($format == 'i' || $format == 'si' || $format == 'b'){
						$queryC->bindValue(':'.$field, (is_array($aArgsSaveM['aData'][$field])) ? json_encode($aArgsSaveM['aData'][$field]) : trim($aArgsSaveM['aData'][$field]), PDO::PARAM_INT);
					}else{ 
						$queryC->bindValue(':'.$field, (is_array($aArgsSaveM['aData'][$field])) ? json_encode($aArgsSaveM['aData'][$field]) : trim($aArgsSaveM['aData'][$field]), PDO::PARAM_STR);
					}
				}
			}
		}
		$queryC->execute();
		$numC = $queryC->rowCount();
		
	}else{
		$aArgsM['data']['id_mid'] = $rowsMo2[0]['id_mid'];
		$aArgsSaveM['id_data'] = $aArgsM['data']['id_mid'];
	}
	######################################################
	
	
	
	// insert local version
	$qry = 'INSERT INTO ' . $aArgsSaveM['table'] . 'loc
				(' . $aArgsSaveM['primarykey'] . ', id_count, id_lang, id_dev, id_cl, restricted_all, create_at, create_from, change_from' . $col . ')
			VALUES
				(:id, :id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :now, :create_from, :create_from' . $val . ')
			ON DUPLICATE KEY UPDATE 
				' . $upd . '
				change_from = (:create_from),
				del = (:nultime)
			';
	$queryC = $CONFIG['dbconn'][0]->prepare($qry);
	$queryC->bindValue(':id', $aArgsSaveM['id_data'], PDO::PARAM_INT);
	$queryC->bindValue(':id_count', $aArgsSaveM['aData']['id_count'], PDO::PARAM_INT);
	$queryC->bindValue(':id_lang', $aArgsSaveM['aData']['id_lang'], PDO::PARAM_INT);
	$queryC->bindValue(':id_dev', $aArgsSaveM['aData']['id_dev'], PDO::PARAM_INT);
	$queryC->bindValue(':id_cl', $aArgsSaveM['aData']['id_cl'], PDO::PARAM_INT);
	$queryC->bindValue(':restricted_all', 0, PDO::PARAM_STR);
	$queryC->bindValue(':now', $now, PDO::PARAM_STR);
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
	
	foreach($aArgsSaveM['columns'] as $field => $format){
		if(($variation == 'master' && in_array($field, $aFieldsSaveMasterM)) || ($variation == 'local' && in_array($field, $aFieldsSaveNotMasterM))){
			if($field != $aArgsSaveM['primarykey']){
				if($format == 'i' || $format == 'si' || $format == 'b'){
					$queryC->bindValue(':'.$field, (is_array($aArgsSaveM['aData'][$field])) ? json_encode($aArgsSaveM['aData'][$field]) : trim($aArgsSaveM['aData'][$field]), PDO::PARAM_INT);
				}else{ 
					$queryC->bindValue(':'.$field, (is_array($aArgsSaveM['aData'][$field])) ? json_encode($aArgsSaveM['aData'][$field]) : trim($aArgsSaveM['aData'][$field]), PDO::PARAM_STR);
				}
			}
		}
	}
	$queryC->execute();
	$numC = $queryC->rowCount();
	
	$aArgsSaveM['changedVersions'] = array(array(0,0,0));
	$aArgsSaveM['allVersions'] = $aLocalVersionsM;
	insertAll($aArgsSaveM);
}
##############################################################
##############################################################
##############################################################


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




echo $aArgsM['data']['id_mid'];

?>