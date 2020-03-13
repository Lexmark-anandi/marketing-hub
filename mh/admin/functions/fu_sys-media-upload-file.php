<?php
$aArgsSave = array();
$aArgsSave['id_data'] = 0;
$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_';
$aArgsSave['primarykey'] = 'id_mid';
$aArgsSave['orgfieldname'] = $varSQL['orgfieldname'];
$aArgsSave['multiple'] = $varSQL['multiple'];
$aArgsSave['allVersions'] = array();
$aArgsSave['changedVersions'] = array();

$aArgsSave['columns'] = array('id_mid' => 'i', 'id_mpid' => 'i', 'filename' => 's', 'filesys_filename' => 's', 'width' => 'i', 'height' => 'i', 'mediatype' => 's', 'size' => 'i', 'filetype' => 's', 'alttext' => 's', 'keywords' => 's', 'id_data' => 'i', 'id_mod' => 'i', 'id_mod_parent' => 'i', 'id_page' => 'i', 'fieldname' => 's', 'filehash' => 's');
$aArgsSave['aFieldsNumbers'] = array('id_mid', 'id_mpid', 'width', 'height', 'size', 'id_data', 'id_mod', 'id_mod_parent', 'id_page');
$aArgsSave['excludeUpdateUni'] = array('id_mid' => array(''), 'id_mpid' => array(''), 'filename' => array(''), 'filesys_filename' => array(''), 'width' => array(''), 'height' => array(''), 'mediatype' => array(''), 'size' => array(''), 'filetype' => array(''), 'alttext' => array(''), 'keywords' => array(''), 'id_data' => array(''), 'id_mod' => array(''), 'id_mod_parent' => array(''), 'id_page' => array(''), 'fieldname' => array(''), 'filehash' => array(''));

$aArgsLV = array();
$aArgsLV['type'] = 'sysall';
if(!isset($aLocalVersionsMedia)) $aLocalVersionsMedia = localVariationsBuild($aArgsLV);


// build data
$fmediatype = finfo_open(FILEINFO_MIME_TYPE);
$picInfo = getimagesize($mediaPath . $filename); 
$picWidth = (isset($picInfo[0])) ? $picInfo[0] : 0;
$picHeight = (isset($picInfo[1])) ? $picInfo[1] : 0;

$aField = explode('_', $field);
$aData = array();
$aData['id_count'] = $aField[0];
$aData['id_lang'] = $aField[1];
$aData['id_dev'] = $aField[2];
$aData['id_mpid'] = $idPath;
$aData['filename'] = $filenameOrg;
$aData['filesys_filename'] = $filename;
$aData['width'] = $picWidth;
$aData['height'] = $picHeight;
$aData['mediatype'] = finfo_file($fmediatype, $mediaPath . $filename);
$aData['size'] = filesize($mediaPath . $filename);
$aData['filetype'] = $filenameOrgEnd;
$aData['alttext'] = '';
$aData['keywords'] = '';
$aData['id_data'] = (isset($mediafileIdData)) ? $mediafileIdData : $CONFIG['page']['id_data'];
$aData['id_mod'] = (isset($mediafileIdMod)) ? $mediafileIdMod : $CONFIG['page']['id_mod'];
$aData['id_mod_parent'] = (isset($mediafileIdModParent)) ? $mediafileIdModParent : $CONFIG['page']['id_mod_parent'];
$aData['id_page'] = (isset($mediafileIdPage)) ? $mediafileIdPage : $CONFIG['page']['id_page'];
$aData['fieldname'] = (isset($mediafileFieldname)) ? $mediafileFieldname : $aArgsSave['orgfieldname'];
$aData['filehash'] = md5_file($mediaPath . $filename);
	

// save database
$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$isNew = 0;

// first check if 'orgfieldname' exists (if not multiple)
if($aArgsSave['multiple'] != 'multiple'){
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mid
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_data = (:id_data)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_data <> (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mod = (:id_mod)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mod_parent = (:id_mod_parent)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_page = (:id_page)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.fieldname = (:fieldname)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.del = (:nultime)
										LIMIT 1
										');
	$query->bindValue(':id_data', $aData['id_data'], PDO::PARAM_INT);
	$query->bindValue(':nul', 0, PDO::PARAM_INT);
	$query->bindValue(':id_mod', $aData['id_mod'], PDO::PARAM_INT);
	$query->bindValue(':id_mod_parent', $aData['id_mod_parent'], PDO::PARAM_INT);

	$query->bindValue(':id_page', $aData['id_page'], PDO::PARAM_INT);
	$query->bindValue(':fieldname', trim($aData['fieldname']), PDO::PARAM_STR);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();

	if($num > 0){
		$idNew = $rows[0]['id_mid'];
		$aArgsSave['id_data'] = $rows[0]['id_mid'];
	}
}

// create new id (if not exists or is multiple)
if($aArgsSave['multiple'] == 'multiple' || $aArgsSave['id_data'] == 0){
	$query = $CONFIG['dbconn'][0]->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_
										(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
										VALUES
										(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
										');
	$query->bindValue(':nul', 0, PDO::PARAM_INT);
	$query->bindValue(':id_cl', $rowsR[0]['id_cl'], PDO::PARAM_INT);
	$query->bindValue(':create_at', $now, PDO::PARAM_STR);
	$query->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
	$query->execute();
	$idNew = $CONFIG['dbconn'][0]->lastInsertId();
	$aArgsSave['id_data'] = $idNew;

	$isNew = 1;
}

// check sync
$aVariationsSave = array();
if($isNew == 1) array_push($aVariationsSave, array(0,0,0));
// master
if($aData['id_count'] == 'x' && $aData['id_lang'] == 'x' && $aData['id_dev'] == 'x'){
	array_push($aVariationsSave, array(0,0,0));
}
// sync = country
if($aData['id_count'] == 'x' && $aData['id_lang'] != 'x' && $aData['id_dev'] != 'x'){
	foreach($aLocalVersionsMedia as $aVersion){
		if($aVersion[1] == $aData['id_lang'] && $aVersion[2] == $aData['id_dev']) array_push($aVariationsSave, $aVersion);
	}
}
// sync = language
if($aData['id_count'] != 'x' && $aData['id_lang'] == 'x' && $aData['id_dev'] != 'x'){
	foreach($aLocalVersionsMedia as $aVersion){
		if($aVersion[0] == $aData['id_count'] && $aVersion[2] == $aData['id_dev']) array_push($aVariationsSave, $aVersion);
	}
}
// sync = device
if($aData['id_count'] != 'x' && $aData['id_lang'] != 'x' && $aData['id_dev'] == 'x'){
	foreach($aLocalVersionsMedia as $aVersion){
		if($aVersion[0] == $aData['id_count'] && $aVersion[1] == $aData['id_lang']) array_push($aVariationsSave, $aVersion);
	}
}
// sync = countrylanguage
if($aData['id_count'] == 'x' && $aData['id_lang'] == 'x' && $aData['id_dev'] != 'x'){
	foreach($aLocalVersionsMedia as $aVersion){
		if($aVersion[2] == $aData['id_dev']) array_push($aVariationsSave, $aVersion);
	}
}
// sync = countrydevice
if($aData['id_count'] == 'x' && $aData['id_lang'] != 'x' && $aData['id_dev'] == 'x'){
	foreach($aLocalVersionsMedia as $aVersion){
		if($aVersion[1] == $aData['id_lang']) array_push($aVariationsSave, $aVersion);
	}
}
// sync = languagedevice
if($aData['id_count'] != 'x' && $aData['id_lang'] == 'x' && $aData['id_dev'] == 'x'){
	foreach($aLocalVersionsMedia as $aVersion){
		if($aVersion[0] == $aData['id_count']) array_push($aVariationsSave, $aVersion);
	}
}
// no sync
if($aData['id_count'] != 'x' && $aData['id_lang'] != 'x' && $aData['id_dev'] != 'x'){
	array_push($aVariationsSave, array($aData['id_count'], $aData['id_lang'], $aData['id_dev']));
}

foreach($aVariationsSave as $aVariation){
	$id_count = $aVariation[0];
	$id_lang = $aVariation[1];
	$id_dev = $aVariation[2];
	
	$query = $CONFIG['dbconn'][0]->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc
											(id_count, id_lang, id_dev, id_cl, id_mid, id_mpid, id_data, id_mod, id_mod_parent, id_page, fieldname, filename, filesys_filename, filehash, width, height, mediatype, size, filetype, alttext, keywords, create_at, create_from, change_from)
										VALUES
											(:id_count, :id_lang, :id_dev, :id_cl, :id_mid, :id_mpid, :id_data, :id_mod, :id_mod_parent, :id_page, :fieldname, :filename, :filesys_filename, :filehash, :width, :height, :mediatype, :size, :filetype, :alttext, :keywords, :create_at, :create_from, :create_from)
										ON DUPLICATE KEY UPDATE 
											filename = (:filename),
											filesys_filename = (:filesys_filename),
											filehash = (:filehash),
											width = (:width),
											height = (:height),
											mediatype = (:mediatype),
											size = (:size),
											filetype = (:filetype),
											alttext = (:alttext),
											keywords = (:keywords),
											change_from = (:create_from)
										');
	$query->bindValue(':id_count', $id_count, PDO::PARAM_INT);
	$query->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
	$query->bindValue(':id_dev', $id_dev, PDO::PARAM_INT);
	$query->bindValue(':id_cl', $rowsR[0]['id_cl'], PDO::PARAM_INT);
	$query->bindValue(':id_mid', $idNew, PDO::PARAM_INT);
	$query->bindValue(':id_mpid', $aData['id_mpid'], PDO::PARAM_INT);
	$query->bindValue(':id_data', $aData['id_data'], PDO::PARAM_INT);
	$query->bindValue(':id_mod', $aData['id_mod'], PDO::PARAM_INT);
	$query->bindValue(':id_mod_parent', $aData['id_mod_parent'], PDO::PARAM_INT);
	$query->bindValue(':id_page', $aData['id_page'], PDO::PARAM_INT);
	$query->bindValue(':fieldname', trim($aData['fieldname']), PDO::PARAM_STR);
	$query->bindValue(':filename', trim($aData['filename']), PDO::PARAM_STR);
	$query->bindValue(':filesys_filename', trim($aData['filesys_filename']), PDO::PARAM_STR);
	$query->bindValue(':filehash', trim($aData['filehash']), PDO::PARAM_STR);
	$query->bindValue(':width', $aData['width'], PDO::PARAM_INT);
	$query->bindValue(':height', $aData['height'], PDO::PARAM_INT);
	$query->bindValue(':mediatype', trim($aData['mediatype']), PDO::PARAM_STR);
	$query->bindValue(':size', $aData['size'], PDO::PARAM_INT);
	$query->bindValue(':filetype', trim($aData['filetype']), PDO::PARAM_STR);
	$query->bindValue(':alttext', trim($aData['alttext']), PDO::PARAM_STR);
	$query->bindValue(':keywords', trim($aData['keywords']), PDO::PARAM_STR);
	$query->bindValue(':create_at', $now, PDO::PARAM_STR);
	$query->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
	$query->bindValue(':change_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
	$query->execute();
}

$aArgsSave['changedVersions'] = array(array(0,0,0));
$aArgsSave['allVersions'] = $aLocalVersionsMedia;
insertAll($aArgsSave);
	
	
if(isset($mediafileIdData)) unset($mediafileIdData);
if(isset($mediafileIdMod)) unset($mediafileIdMod);
if(isset($mediafileIdModParent)) unset($mediafileIdModParent);
if(isset($mediafileIdPage)) unset($mediafileIdPage);
if(isset($mediafileFieldname)) unset($mediafileFieldname);
		
?>