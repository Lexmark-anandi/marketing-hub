<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();
$aResultFiles = json_decode($varSQL['files'], true);

$idfile = 0;
foreach($aResultFiles as $field => $aFile){
	$idfile = $aFile['idfile']; 
}

$queryMo = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mpid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.width,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.height
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mid = (:id_mid)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_lang = (:id_lang)
									');
$queryMo->bindValue(':id_mid', $idfile, PDO::PARAM_INT);
$queryMo->bindValue(':id_count', 0, PDO::PARAM_INT);
$queryMo->bindValue(':id_lang', 0, PDO::PARAM_INT);
$queryMo->execute();
$rowsMo = $queryMo->fetchAll(PDO::FETCH_ASSOC);
$numMo = $queryMo->rowCount();

$aBannerFiles = array();
$out = array();
$out['bannerformats'] = '';
$out['thumbnails'] = array();
$out['thumbnails'][$varSQL['bannername']] = array();


##############################################################
##############################################################
##############################################################
// create bannerformat
$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$aUploadedFilesId = array();

$aArgs = array();
$aArgs['id_count'] = $CONFIG['settings']['formCountry'];
$aArgs['id_lang'] = $CONFIG['settings']['formLanguage'];
$aArgs['id_dev'] = $CONFIG['settings']['formDevice'];
$aArgs['usesystem'] = 1;
$aArgs['fields'] = array();

$aArgs['data'] = array();
$aArgs['data']['id_bfid'] = 0;
$aArgs['data']['bannername'] = $varSQL['bannername'];
$aArgs['data']['width'] = ($numMo > 0) ? $rowsMo[0]['width'] : 0;
$aArgs['data']['height'] = ($numMo > 0) ? $rowsMo[0]['height'] : 0;
$aArgs['data']['rank'] = 0;
$aArgs['data']['active'] = 1;
$aArgs['data']['animated'] = 1;
$aArgs['data']['id_tempid'] = $CONFIG['page']['id_data'];


$aArgsSaveN = array();

$aArgsSave = array();
$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . '_bannerformats_';
$aArgsSave['primarykey'] = 'id_bfid';
$aArgsSave['allVersions'] = array();
$aArgsSave['changedVersions'] = array();

$aArgsSave['columns'] = array();
$aArgsSave['columns']['id_bfid'] = 'i';
$aArgsSave['columns']['bannername'] = 's';
$aArgsSave['columns']['width'] = 'i';
$aArgsSave['columns']['height'] = 'i';
$aArgsSave['columns']['rank'] = 'i';
$aArgsSave['columns']['active'] = 'i';
$aArgsSave['columns']['animated'] = 'i';
$aArgsSave['columns']['id_tempid'] = 'i';


$aArgsSave['aFieldsNumbers'] = array();
array_push($aArgsSave['aFieldsNumbers'], 'id_bfid');
array_push($aArgsSave['aFieldsNumbers'], 'width');
array_push($aArgsSave['aFieldsNumbers'], 'height');
array_push($aArgsSave['aFieldsNumbers'], 'rank');
array_push($aArgsSave['aFieldsNumbers'], 'active');
array_push($aArgsSave['aFieldsNumbers'], 'animated');
array_push($aArgsSave['aFieldsNumbers'], 'id_tempid');

$aArgsSave['excludeUpdateUni'] = array();
$aArgsSave['excludeUpdateUni']['id_bfid'] = array('');
$aArgsSave['excludeUpdateUni']['bannername'] = array('');
$aArgsSave['excludeUpdateUni']['width'] = array('',0);
$aArgsSave['excludeUpdateUni']['height'] = array('',0);
$aArgsSave['excludeUpdateUni']['rank'] = array('',0);
$aArgsSave['excludeUpdateUni']['active'] = array('',0);
$aArgsSave['excludeUpdateUni']['animated'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_tempid'] = array('',0);

$aArgsSave['aData'] = setValuesSave($aArgs);
$aArgsSave['aData']['id_count'] = $aArgs['id_count'];
$aArgsSave['aData']['id_lang'] = $aArgs['id_lang'];
$aArgsSave['aData']['id_dev'] = $aArgs['id_dev'];
$aArgsSave['aData']['id_cl'] = $CONFIG['activeSettings']['id_clid'];

$aFieldsSaveMaster = array();
array_push($aFieldsSaveMaster, 'id_bfid');
array_push($aFieldsSaveMaster, 'bannername');
array_push($aFieldsSaveMaster, 'width');
array_push($aFieldsSaveMaster, 'height');
array_push($aFieldsSaveMaster, 'rank');
array_push($aFieldsSaveMaster, 'active');
array_push($aFieldsSaveMaster, 'animated');
array_push($aFieldsSaveMaster, 'id_tempid');

$aFieldsSaveNotMaster = array();
array_push($aFieldsSaveNotMaster, 'id_bfid');
array_push($aFieldsSaveNotMaster, 'bannername');
array_push($aFieldsSaveNotMaster, 'width');
array_push($aFieldsSaveNotMaster, 'height');
array_push($aFieldsSaveNotMaster, 'rank');
array_push($aFieldsSaveNotMaster, 'active');
array_push($aFieldsSaveNotMaster, 'animated');
array_push($aFieldsSaveNotMaster, 'id_tempid');




$aArgsLV = array();
$aArgsLV['type'] = 'sysall';
$aLocalVersions = localVariationsBuild($aArgsLV);

######################################################
// create new ID
$queryI = $CONFIG['dbconn'][0]->prepare('
									INSERT INTO ' . $aArgsSave['table'] . '
									(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
									VALUES
									(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
									');
$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
$queryI->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
$queryI->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
$queryI->execute();
$aArgsSave['id_data'] = $CONFIG['dbconn'][0]->lastInsertId();
$id_bfid = $aArgsSave['id_data'];
$aBannerFiles[$id_bfid] = array();
######################################################

$variation = ($aArgs['id_count'] == 0 && $aArgs['id_lang'] == 0 && $aArgs['id_dev'] == 0) ? 'master' : 'local';




$col = '';
$val = '';
$upd = '';
foreach($aArgsSave['columns'] as $field => $format){
	if(($variation == 'master' && in_array($field, $aFieldsSaveMaster)) || ($variation == 'local' && in_array($field, $aFieldsSaveNotMaster))){
		if($field != $aArgsSave['primarykey']){
			$col .= ', ' . $field;
			$val .= ', :' . $field . '';
			$upd .= $field.' = (:'.$field.'), ' ;
		}
	}
}

// insert master null
$qry = 'INSERT INTO ' . $aArgsSave['table'] . 'loc
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
$queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
$queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryC->bindValue(':id_cl', $aArgsSave['aData']['id_cl'], PDO::PARAM_INT);
$queryC->bindValue(':restricted_all', 0, PDO::PARAM_STR);
$queryC->bindValue(':now', $now, PDO::PARAM_STR);
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 

foreach($aArgsSave['columns'] as $field => $format){
	if(($variation == 'master' && in_array($field, $aFieldsSaveMaster)) || ($variation == 'local' && in_array($field, $aFieldsSaveNotMaster))){
		if($field != $aArgsSave['primarykey']){
			if($format == 'i' || $format == 'si' || $format == 'b'){
				$queryC->bindValue(':'.$field, (is_array($aArgsSave['aData'][$field])) ? json_encode($aArgsSave['aData'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_INT);
			}else{ 
				$queryC->bindValue(':'.$field, (is_array($aArgsSave['aData'][$field])) ? json_encode($aArgsSave['aData'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_STR);
			}
		}
	}
}
$queryC->execute();
$numC = $queryC->rowCount();


// insert local version
$qry = 'INSERT INTO ' . $aArgsSave['table'] . 'loc
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
$queryC->bindValue(':id_count', $aArgsSave['aData']['id_count'], PDO::PARAM_INT);
$queryC->bindValue(':id_lang', $aArgsSave['aData']['id_lang'], PDO::PARAM_INT);
$queryC->bindValue(':id_dev', $aArgsSave['aData']['id_dev'], PDO::PARAM_INT);
$queryC->bindValue(':id_cl', $aArgsSave['aData']['id_cl'], PDO::PARAM_INT);
$queryC->bindValue(':restricted_all', 0, PDO::PARAM_STR);
$queryC->bindValue(':now', $now, PDO::PARAM_STR);
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 

foreach($aArgsSave['columns'] as $field => $format){
	if(($variation == 'master' && in_array($field, $aFieldsSaveMaster)) || ($variation == 'local' && in_array($field, $aFieldsSaveNotMaster))){
		if($field != $aArgsSave['primarykey']){
			if($format == 'i' || $format == 'si' || $format == 'b'){
				$queryC->bindValue(':'.$field, (is_array($aArgsSave['aData'][$field])) ? json_encode($aArgsSave['aData'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_INT);
			}else{ 
				$queryC->bindValue(':'.$field, (is_array($aArgsSave['aData'][$field])) ? json_encode($aArgsSave['aData'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_STR);
			}
		}
	}
}
$queryC->execute();
$numC = $queryC->rowCount();

$aArgsSave['changedVersions'] = array(array(0,0,0));
$aArgsSave['allVersions'] = $aLocalVersions;
insertAll($aArgsSave);
##############################################################
##############################################################
##############################################################


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
	$aArgsM['data']['fieldname'] = $rowsMo[0]['fieldname'] . '_' . $id_bfid;
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
	$aArgsMLV['type'] = 'sysall';
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


##############################################################
##############################################################
##############################################################
// create templatespage
$pages = 3;
for($i=1; $i <= $pages; $i++){
	$page = $i;
	$bannername = 'banner_original_' . $page . '_' . $id_bfid;

	$queryM = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.filename,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.filesys_filename
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_data = (:id_data)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mod = (:id_mod)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.fieldname = (:fieldname)
										');
	$queryM->bindValue(':id_data', $CONFIG['page']['id_data'], PDO::PARAM_INT);
	$queryM->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
	$queryM->bindValue(':fieldname', $bannername, PDO::PARAM_STR);
	$queryM->execute();
	$rowsM = $queryM->fetchAll(PDO::FETCH_ASSOC);
	$numM = $queryM->rowCount();
	
	if(!array_key_exists($page, $aBannerFiles[$id_bfid])){
		$aBannerFiles[$id_bfid][$page] = array();
		$aBannerFiles[$id_bfid][$page]['tpid'] = 0;
		$aBannerFiles[$id_bfid][$page]['mid'] = 0;
		$aBannerFiles[$id_bfid][$page]['filename'] = '';
		$aBannerFiles[$id_bfid][$page]['filesys_filename'] = '';
	}


	if($numM > 0){
		$id_mid = $rowsM[0]['id_mid'];
		
		$queryP1 = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_tpid
											FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc 
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_tempid = (:id_tempid)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.page = (:page)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_bfid = (:id_bfid)
											');
		$queryP1->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_INT);
		$queryP1->bindValue(':page', $page, PDO::PARAM_INT);
		$queryP1->bindValue(':id_bfid', $id_bfid, PDO::PARAM_INT);
		$queryP1->execute();
		$rowsP1 = $queryP1->fetchAll(PDO::FETCH_ASSOC);
		$numP1 = $queryP1->rowCount();

		if($numP1 == 0){
			$queryI = $CONFIG['dbconn'][0]->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_templatespages_
												(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
												VALUES
												(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
												');
			$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
			$queryI->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
			$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
			$queryI->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
			$queryI->execute();
			$tpid = $CONFIG['dbconn'][0]->lastInsertId();
		}else{
			$tpid = $rowsP1[0]['id_tpid'];
		}

		$pagelabel = '';
		if($page == 1) $pagelabel = $TEXT['firstframe'];
		if($page == 2) $pagelabel = $TEXT['productframe'];
		if($page == 3) $pagelabel = $TEXT['lastframe'];
		$dirTarget = $CONFIG['system']['directoryInstallation'] . 'media/';
		$out['thumbnails'][$varSQL['bannername']][$page] = array('src' => $dirTarget . '' . $rowsM[0]['filesys_filename'], 'tp' => $tpid, 'bfid' => $id_bfid, 'pageid' => $tpid . '_' . $page, 'page' => $page, 'pagelabel' => $pagelabel);

		$aBannerFiles[$id_bfid][$page]['tpid'] = $tpid;
		$aBannerFiles[$id_bfid][$page]['mid'] = $id_mid;
		$aBannerFiles[$id_bfid][$page]['filename'] = $rowsM[0]['filename'];
		$aBannerFiles[$id_bfid][$page]['filesys_filename'] = $rowsM[0]['filesys_filename'];

		if(!array_key_exists('n_' . $tpid, $aArgsSaveN)){
			$aArgsSaveN['n_' . $tpid] = array();
			$aArgsSaveN['n_' . $tpid]['id_data'] = $tpid;
			$aArgsSaveN['n_' . $tpid]['table'] = $CONFIG['db'][0]['prefix'] . '_templatespages_';
			$aArgsSaveN['n_' . $tpid]['primarykey'] = 'id_tpid';
			$aArgsSaveN['n_' . $tpid]['allVersions'] = array();
			$aArgsSaveN['n_' . $tpid]['changedVersions'] = array();
			
			$aArgsSaveN['n_' . $tpid]['columns'] = array();
			$aArgsSaveN['n_' . $tpid]['columns']['id_tpid'] = 'i';
			$aArgsSaveN['n_' . $tpid]['columns']['id_tempid'] = 'i';
			$aArgsSaveN['n_' . $tpid]['columns']['id_bfid'] = 'i';
			$aArgsSaveN['n_' . $tpid]['columns']['id_cssid'] = 'i';
			$aArgsSaveN['n_' . $tpid]['columns']['id_cbid'] = 'i';
			$aArgsSaveN['n_' . $tpid]['columns']['id_etid'] = 'i';
			$aArgsSaveN['n_' . $tpid]['columns']['kiado_code'] = 's';
			$aArgsSaveN['n_' . $tpid]['columns']['file_original'] = 'i';
			$aArgsSaveN['n_' . $tpid]['columns']['page'] = 'i';
			$aArgsSaveN['n_' . $tpid]['columns']['page_number'] = 'i';
			$aArgsSaveN['n_' . $tpid]['columns']['page_duration'] = 's';
			$aArgsSaveN['n_' . $tpid]['columns']['page_dimension'] = 's';

			$aArgsSaveN['n_' . $tpid]['aFieldsNumbers'] = array();
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsNumbers'], 'id_tpid');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsNumbers'], 'id_tempid');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsNumbers'], 'id_bfid');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsNumbers'], 'id_cssid');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsNumbers'], 'id_cbid');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsNumbers'], 'id_etid');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsNumbers'], 'file_original');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsNumbers'], 'page');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsNumbers'], 'page_number');

			$aArgsSaveN['n_' . $tpid]['excludeUpdateUni'] = array();
			$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['id_tpid'] = array('',0);
			$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['id_tempid'] = array('',0);
			$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['id_bfid'] = array('',0);
			$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['id_cssid'] = array('',0);
			$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['id_cbid'] = array('',0);
			$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['id_etid'] = array('',0);
			$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['kiado_code'] = array('');
			$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['file_original'] = array('',0);
			$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['page'] = array('',0);
			$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['page_number'] = array('',0);
			$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['page_duration'] = array('');
			$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['page_dimension'] = array('');

			$aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'] = array();
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'id_tpid');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'id_tempid');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'id_bfid');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'id_cssid');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'id_cbid');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'id_etid');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'kiado_code');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'file_original');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'page');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'page_number');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'page_duration');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'page_dimension');
			$aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'] = array();
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'], 'id_tpid');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'], 'id_tempid');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'], 'id_bfid');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'], 'id_cssid');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'], 'id_cbid');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'], 'file_original');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'], 'page');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'], 'page_number');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'], 'page_duration');
			array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'], 'page_dimension');

			$aArgsN['n_' . $tpid]['fields'] = array();
			//$aArgsN['n_' . $tpid]['fields']['bool2text']['feldname'] = array('text'=>'check');
		}

		$aArgsN['n_' . $tpid]['data'] = array();
		$aArgsN['n_' . $tpid]['data']['id_tpid'] = $tpid;
		$aArgsN['n_' . $tpid]['data']['id_tempid'] = $CONFIG['page']['id_data'];
		$aArgsN['n_' . $tpid]['data']['id_bfid'] = $id_bfid;
		$aArgsN['n_' . $tpid]['data']['id_cssid'] = 0;
		$aArgsN['n_' . $tpid]['data']['id_cbid'] = 0;
		$aArgsN['n_' . $tpid]['data']['id_etid'] = 0;
		$aArgsN['n_' . $tpid]['data']['kiado_code'] = '';
		$aArgsN['n_' . $tpid]['data']['file_original'] = $id_mid;
		$aArgsN['n_' . $tpid]['data']['page'] = $page;
		$aArgsN['n_' . $tpid]['data']['page_number'] = 1;
		$aArgsN['n_' . $tpid]['data']['page_duration'] = '';
		$aArgsN['n_' . $tpid]['data']['page_dimension'] = '';
		$aArgsN['n_' . $tpid]['data']['id_count'] = 0;
		$aArgsN['n_' . $tpid]['data']['id_lang'] = 0;
		$aArgsN['n_' . $tpid]['data']['id_dev'] = 0;
		$aArgsN['n_' . $tpid]['data']['id_cl'] = $CONFIG['activeSettings']['id_clid'];


		$aArgsSaveN['n_' . $tpid]['aData'] = setValuesSave($aArgsN['n_' . $tpid]);
		$aArgsSaveN['n_' . $tpid]['aData']['id_count'] = 0;
		$aArgsSaveN['n_' . $tpid]['aData']['id_lang'] = 0;
		$aArgsSaveN['n_' . $tpid]['aData']['id_dev'] = 0;
		$aArgsSaveN['n_' . $tpid]['aData']['id_cl'] = $CONFIG['activeSettings']['id_clid'];
		$aArgsSaveN['n_' . $tpid]['aData']['id_data'] = $tpid;


		$aChangeN = checkChanges($aArgsSaveN['n_' . $tpid]);


		$col = '';
		$val = '';
		$upd = '';
		foreach($aChangeN['aChangedFields'] as $field){
			if(($variation == 'master' && in_array($field, $aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'])) || ($variation == 'local' && in_array($field, $aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster']))){
				if($field != $aArgsSaveN['n_' . $tpid]['primarykey'] && $field != 'id_tempid' && $field != 'file_original' && $field != 'page' && $field != 'id_bfid'){
					$col .= ', ' . $field;
					$val .= ', :' . $field . '';
					$upd .= $field.' = (:'.$field.'), ' ;
				}
			}
		}
		
							
		$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc ';
		$qry .= '(' . $aArgsSaveN['n_' . $tpid]['primarykey'] . ', id_tempid, id_bfid, file_original, page, id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from' . $col . ') ';
		$qry .= 'VALUES ';					
		$qry .= '(:id, :id_tempid, :id_bfid, :file_original, :page, :id_count, :id_lang, :id_dev, :id_cl, :create_at, :create_from, :create_from' . $val . ') '; 
		$qry .= 'ON DUPLICATE KEY UPDATE ';	
		$qry .= $upd;
		$qry .= 'change_from = '.$CONFIG['user']['id_real'].', ';
		$qry .= 'del = "0000-00-00 00:00:00" ';
		$qry = rtrim($qry, ', ');
		$qry .= ' ';

		$queryP2 = $CONFIG['dbconn'][0]->prepare($qry);
		$queryP2->bindValue(':id', $aArgsSaveN['n_' . $tpid]['aData']['id_data'], PDO::PARAM_INT);
		$queryP2->bindValue(':id_tempid', $aArgsSaveN['n_' . $tpid]['aData']['id_tempid'], PDO::PARAM_INT);
		$queryP2->bindValue(':file_original', $aArgsSaveN['n_' . $tpid]['aData']['file_original'], PDO::PARAM_INT);
		$queryP2->bindValue(':id_bfid', $aArgsSaveN['n_' . $tpid]['aData']['id_bfid'], PDO::PARAM_INT);
		$queryP2->bindValue(':page', $aArgsSaveN['n_' . $tpid]['aData']['page'], PDO::PARAM_INT);
		$queryP2->bindValue(':id_count', $aArgsSaveN['n_' . $tpid]['aData']['id_count'], PDO::PARAM_INT);
		$queryP2->bindValue(':id_lang', $aArgsSaveN['n_' . $tpid]['aData']['id_lang'], PDO::PARAM_INT);
		$queryP2->bindValue(':id_dev', $aArgsSaveN['n_' . $tpid]['aData']['id_dev'], PDO::PARAM_INT);
		$queryP2->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
		$queryP2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryP2->bindValue(':create_at', $now, PDO::PARAM_STR); 
		$queryP2->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 

		foreach($aChangeN['aChangedFields'] as $field){
			if(($variation == 'master' && in_array($field, $aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'])) || ($variation == 'local' && in_array($field, $aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster']))){
				if($field != $aArgsSaveN['n_' . $tpid]['primarykey']){
					if($aArgsSaveN['n_' . $tpid]['columns'][$field] == 'i' || $aArgsSaveN['n_' . $tpid]['columns'][$field] == 'si' || $aArgsSaveN['n_' . $tpid]['columns'][$field] == 'b'){
						$queryP2->bindValue(':'.$field, (is_array($aArgsSaveN['n_' . $tpid]['aData'][$field])) ? json_encode($aArgsSaveN['n_' . $tpid]['aData'][$field]) : trim($aArgsSaveN['n_' . $tpid]['aData'][$field]), PDO::PARAM_INT);
					}else{ 
						$queryP2->bindValue(':'.$field, (is_array($aArgsSaveN['n_' . $tpid]['aData'][$field])) ? json_encode($aArgsSaveN['n_' . $tpid]['aData'][$field]) : trim($aArgsSaveN['n_' . $tpid]['aData'][$field]), PDO::PARAM_STR);
					}
				}
			}
		}
		$queryP2->execute();
		$numP2 = $queryP2->rowCount();

		if($numP2 > 0) array_push($aArgsSaveN['n_' . $tpid]['changedVersions'], array($aArgsSaveN['n_' . $tpid]['aData']['id_count'], $aArgsSaveN['n_' . $tpid]['aData']['id_lang'], $aArgsSaveN['n_' . $tpid]['aData']['id_dev']));
	}
}

foreach($aArgsSaveN as $kSave => $aSave){
	$aSave['allVersions'] = $aArgsSave['allVersions'];
	insertAll($aSave);
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







$out['bannerformats'] .= '<div class="formBannerformatOuter" data-bfid="' . $id_bfid . '">';
$out['bannerformats'] .= '<div class="formRow formRowNoBorder formRowBannerfiles">';
$out['bannerformats'] .= '<span class="formBannerformatName"><strong>' . $aArgs['data']['bannername']. '</strong></span> (' . $aArgs['data']['width'] . 'x' . $aArgs['data']['height'] . ')';
$out['bannerformats'] .= '<div class="modulIcon modulIconForm modulIconFloatRight modulIconDelete" title="'. $TEXT['titleDeleteRow'] . '"><i class="fa fa-trash"></i></div>';
$out['bannerformats'] .= '<div class="modulIcon modulIconForm modulIconFloatRight modulIconEdit" title="'. $TEXT['titleEditRow'] . '"><i class="fa fa-pencil"></i></div>';
$out['bannerformats'] .= '</div>';

for($i = 1; $i <= 3; $i++){
	$label = '';
	if($i == 1) $label = '<span style="padding-left: 20px;font-style:italic">' . $TEXT['firstframe'] . '</span>';
	if($i == 2) $label = '<span style="padding-left: 20px;font-style:italic">' . $TEXT['productframe'] . '</span>';
	if($i == 3) $label = '<span style="padding-left: 20px;font-style:italic">' . $TEXT['lastframe'] . '</span>';
	
	$classSpace = '';
	if($i == 3) $classSpace = 'formRowSpace';
	
	$out['bannerformats'] .= '<div class="formRow ' . $classSpace . '">';
	$out['bannerformats'] .= '<div class="formLabel">';
	$out['bannerformats'] .= '<label for="">' . $label . '</label>';
	$out['bannerformats'] .= '</div>';
	$out['bannerformats'] .= '<div class="formField">';
	if(isset($aBannerFiles[$id_bfid][$i]) && $aBannerFiles[$id_bfid][$i]['filename'] != '') $out['bannerformats'] .= '<div class="formBannerFile" data-tpid="' . $aBannerFiles[$id_bfid][$i]['tpid'] . '" data-mid="' . $aBannerFiles[$id_bfid][$i]['mid'] . '"><a href="' . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathMedia'] . $aBannerFiles[$id_bfid][$i]['filesys_filename'] . '" target="_blank">' . $aBannerFiles[$id_bfid][$i]['filename'] . '</a></div>';
	$out['bannerformats'] .= '</div>';
	$out['bannerformats'] .= '</div>';
}
$out['bannerformats'] .= '</div>';


echo json_encode($out);

?>