<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();
$aComponents = json_decode($varSQL['components'], true);

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');


$aArgsSave = array();
$aArgsSave['id_data'] = 0;
$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . '_templatesconfigurations_';
$aArgsSave['primarykey'] = 'id_tconid';
$aArgsSave['allVersions'] = array();
$aArgsSave['changedVersions'] = array(); 

$aArgsSave['columns'] = array();
$aArgsSave['columns']['id_tconid'] = 'i';
$aArgsSave['columns']['id_caid'] = 'i';
$aArgsSave['columns']['id_rid'] = 'i';
$aArgsSave['columns']['configurationname'] = 's';

$aArgsSave['aFieldsNumbers'] = array();
array_push($aArgsSave['aFieldsNumbers'], 'id_tconid');
array_push($aArgsSave['aFieldsNumbers'], 'id_caid');
array_push($aArgsSave['aFieldsNumbers'], 'id_rid');

$aArgsSave['excludeUpdateUni'] = array();
$aArgsSave['excludeUpdateUni']['id_tconid'] = array('');
$aArgsSave['excludeUpdateUni']['id_caid'] = array('');
$aArgsSave['excludeUpdateUni']['id_rid'] = array('',0);
$aArgsSave['excludeUpdateUni']['configurationname'] = array('');

$aArgsSave['aFieldsSaveMaster'] = array();
array_push($aArgsSave['aFieldsSaveMaster'], 'id_tconid');
array_push($aArgsSave['aFieldsSaveMaster'], 'id_caid');
array_push($aArgsSave['aFieldsSaveMaster'], 'id_rid');
array_push($aArgsSave['aFieldsSaveMaster'], 'configurationname');

$aArgsSave['aFieldsSaveNotMaster'] = array();
array_push($aArgsSave['aFieldsSaveNotMaster'], 'id_tconid');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'id_caid');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'id_rid');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'configurationname');

$aArgs['fields'] = array();

$aArgsLV = array();
$aArgsLV['type'] = 'sysall';
$aLocalVersions = localVariationsBuild($aArgsLV);
$variation = ($CONFIG['settings']['formCountry'] == 0 && $CONFIG['settings']['formLanguage'] == 0 && $CONFIG['settings']['formDevice'] == 0) ? 'master' : 'local';



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
$tconid = $CONFIG['dbconn'][0]->lastInsertId();



$aArgs['data'] = array();
$aArgs['data']['id_tconid'] = $tconid;
$aArgs['data']['id_caid'] = $varSQL['caid'];
$aArgs['data']['id_rid'] = $CONFIG['user']['right'];
$aArgs['data']['configurationname'] = $varSQL['configurationname'];

$aArgs['data']['id_count'] = $CONFIG['settings']['formCountry'];
$aArgs['data']['id_lang'] = $CONFIG['settings']['formLanguage'];
$aArgs['data']['id_dev'] = $CONFIG['settings']['formDevice'];
$aArgs['data']['id_cl'] = $CONFIG['activeSettings']['id_clid'];


$aArgsSave['aData'] = setValuesSave($aArgs);
$aArgsSave['aData']['id_count'] = $CONFIG['settings']['formCountry'];
$aArgsSave['aData']['id_lang'] = $CONFIG['settings']['formLanguage'];
$aArgsSave['aData']['id_dev'] = $CONFIG['settings']['formDevice'];
$aArgsSave['aData']['id_cl'] = $CONFIG['activeSettings']['id_clid'];
$aArgsSave['aData']['id_data'] = $tconid;
$aArgsSave['id_data'] = $tconid;



$col = '';
$val = '';
$upd = '';
foreach($aArgsSave['columns'] as $field => $format){
	if(($variation == 'master' && in_array($field, $aArgsSave['aFieldsSaveMaster'])) || ($variation == 'local' && in_array($field, $aArgsSave['aFieldsSaveNotMaster']))){
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
$queryC->bindValue(':id', $aArgsSave['aData']['id_data'], PDO::PARAM_INT);
$queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
$queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryC->bindValue(':id_cl', $aArgsSave['aData']['id_cl'], PDO::PARAM_INT);
$queryC->bindValue(':restricted_all', 0, PDO::PARAM_STR);
$queryC->bindValue(':now', $now, PDO::PARAM_STR);
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 

foreach($aArgsSave['columns'] as $field => $format){
	if(($variation == 'master' && in_array($field, $aArgsSave['aFieldsSaveMaster'])) || ($variation == 'local' && in_array($field, $aArgsSave['aFieldsSaveNotMaster']))){
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


// insert master null
$qry = 'INSERT INTO ' . $aArgsSave['table'] . 'uni
			(' . $aArgsSave['primarykey'] . ', id_count, id_lang, id_dev, id_cl, restricted_all, create_at, create_from, change_from' . $col . ')
		VALUES
			(:id, :id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :now, :create_from, :create_from' . $val . ')
		ON DUPLICATE KEY UPDATE 
			' . $upd . '
			change_from = (:create_from),
			del = (:nultime)
		';
$queryC = $CONFIG['dbconn'][0]->prepare($qry);
$queryC->bindValue(':id', $aArgsSave['aData']['id_data'], PDO::PARAM_INT);
$queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
$queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryC->bindValue(':id_cl', $aArgsSave['aData']['id_cl'], PDO::PARAM_INT);
$queryC->bindValue(':restricted_all', 0, PDO::PARAM_STR);
$queryC->bindValue(':now', $now, PDO::PARAM_STR);
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 

foreach($aArgsSave['columns'] as $field => $format){
	if(($variation == 'master' && in_array($field, $aArgsSave['aFieldsSaveMaster'])) || ($variation == 'local' && in_array($field, $aArgsSave['aFieldsSaveNotMaster']))){
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

//// insert local
//$qry = 'INSERT INTO ' . $aArgsSave['table'] . 'loc
//			(' . $aArgsSave['primarykey'] . ', id_count, id_lang, id_dev, id_cl, restricted_all, create_at, create_from, change_from' . $col . ')
//		VALUES
//			(:id, :id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :now, :create_from, :create_from' . $val . ')
//		ON DUPLICATE KEY UPDATE 
//			' . $upd . '
//			change_from = (:create_from),
//			del = (:nultime)
//		';
//$queryC = $CONFIG['dbconn'][0]->prepare($qry);
//$queryC->bindValue(':id', $aArgsSave['aData']['id_data'], PDO::PARAM_INT);
//$queryC->bindValue(':id_count', $aArgsSave['aData']['id_count'], PDO::PARAM_INT);
//$queryC->bindValue(':id_lang', $aArgsSave['aData']['id_lang'], PDO::PARAM_INT);
//$queryC->bindValue(':id_dev', $aArgsSave['aData']['id_dev'], PDO::PARAM_INT);
//$queryC->bindValue(':id_cl', $aArgsSave['aData']['id_cl'], PDO::PARAM_INT);
//$queryC->bindValue(':restricted_all', 0, PDO::PARAM_STR);
//$queryC->bindValue(':now', $now, PDO::PARAM_STR);
//$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
//
//foreach($aArgsSave['columns'] as $field => $format){
//	if(($variation == 'master' && in_array($field, $aArgsSave['aFieldsSaveMaster'])) || ($variation == 'local' && in_array($field, $aArgsSave['aFieldsSaveNotMaster']))){
//		if($field != $aArgsSave['primarykey']){
//			if($format == 'i' || $format == 'si' || $format == 'b'){
//				$queryC->bindValue(':'.$field, (is_array($aArgsSave['aData'][$field])) ? json_encode($aArgsSave['aData'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_INT);
//			}else{ 
//				$queryC->bindValue(':'.$field, (is_array($aArgsSave['aData'][$field])) ? json_encode($aArgsSave['aData'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_STR);
//			}
//		}
//	}
//}
//$queryC->execute();
//$numC = $queryC->rowCount();



//$aArgsSave['changedVersions'] = array(array(0,0,0));
//$aArgsSave['allVersions'] = $aLocalVersions;
//insertAll($aArgsSave);




###################################################################
// save elements
###################################################################
$aArgsSave = array();
$aArgsSave['id_data'] = 0;
$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_';
$aArgsSave['primarykey'] = 'id_tceid';

$aArgsSave['columns'] = array();
$aArgsSave['columns']['id_tceid'] = 'i';
$aArgsSave['columns']['id_tconid'] = 'i';
$aArgsSave['columns']['id_tcid'] = 'i';
$aArgsSave['columns']['page'] = 'i';
$aArgsSave['columns']['elementtitle'] = 's';
$aArgsSave['columns']['position_left'] = 's';
$aArgsSave['columns']['position_top'] = 's';
$aArgsSave['columns']['width'] = 's';
$aArgsSave['columns']['height'] = 's';
$aArgsSave['columns']['fontsize'] = 'i';
$aArgsSave['columns']['fontcolor'] = 's';
$aArgsSave['columns']['fontstyle'] = 'i';
$aArgsSave['columns']['background_color'] = 's';
$aArgsSave['columns']['content'] = 's';
$aArgsSave['columns']['content_transrequired'] = 'i';
$aArgsSave['columns']['max_char'] = 'i';
$aArgsSave['columns']['alignment'] = 's';
$aArgsSave['columns']['verticalalignment'] = 's';
$aArgsSave['columns']['editable'] = 'i';
$aArgsSave['columns']['active'] = 'i';
$aArgsSave['columns']['fixed'] = 'i';

$aArgsSave['aFieldsNumbers'] = array();
array_push($aArgsSave['aFieldsNumbers'], 'id_tceid');
array_push($aArgsSave['aFieldsNumbers'], 'id_tconid');
array_push($aArgsSave['aFieldsNumbers'], 'id_tcid');
array_push($aArgsSave['aFieldsNumbers'], 'page');
array_push($aArgsSave['aFieldsNumbers'], 'position_left');
array_push($aArgsSave['aFieldsNumbers'], 'position_top');
array_push($aArgsSave['aFieldsNumbers'], 'width');
array_push($aArgsSave['aFieldsNumbers'], 'height');
array_push($aArgsSave['aFieldsNumbers'], 'fontsize');
array_push($aArgsSave['aFieldsNumbers'], 'fontstyle');
array_push($aArgsSave['aFieldsNumbers'], 'max_char');
array_push($aArgsSave['aFieldsNumbers'], 'content_transrequired');
array_push($aArgsSave['aFieldsNumbers'], 'editable');
array_push($aArgsSave['aFieldsNumbers'], 'active');
array_push($aArgsSave['aFieldsNumbers'], 'fixed');

$aArgsSave['excludeUpdateUni'] = array();
$aArgsSave['excludeUpdateUni']['id_tceid'] = array('');
$aArgsSave['excludeUpdateUni']['id_tconid'] = array('');
$aArgsSave['excludeUpdateUni']['id_tcid'] = array('',0);
$aArgsSave['excludeUpdateUni']['page'] = array('',0);
$aArgsSave['excludeUpdateUni']['elementtitle'] = array('');
$aArgsSave['excludeUpdateUni']['position_left'] = array('',0);
$aArgsSave['excludeUpdateUni']['position_top'] = array('',0);
$aArgsSave['excludeUpdateUni']['width'] = array('',0);
$aArgsSave['excludeUpdateUni']['height'] = array('',0);
$aArgsSave['excludeUpdateUni']['fontsize'] = array('',0);
$aArgsSave['excludeUpdateUni']['fontcolor'] = array('');
$aArgsSave['excludeUpdateUni']['fontstyle'] = array('',0);
$aArgsSave['excludeUpdateUni']['background_color'] = array('');
$aArgsSave['excludeUpdateUni']['content'] = array('');
$aArgsSave['excludeUpdateUni']['content_transrequired'] = array('',0);
$aArgsSave['excludeUpdateUni']['max_char'] = array('',0);
$aArgsSave['excludeUpdateUni']['alignment'] = array('');
$aArgsSave['excludeUpdateUni']['verticalalignment'] = array('');
$aArgsSave['excludeUpdateUni']['editable'] = array('',0);
$aArgsSave['excludeUpdateUni']['active'] = array('',0);
$aArgsSave['excludeUpdateUni']['fixed'] = array('',0);

$aArgsSave['aFieldsSaveMaster'] = array();
array_push($aArgsSave['aFieldsSaveMaster'], 'id_tceid');
array_push($aArgsSave['aFieldsSaveMaster'], 'id_tconid');
array_push($aArgsSave['aFieldsSaveMaster'], 'id_tcid');
array_push($aArgsSave['aFieldsSaveMaster'], 'page');
array_push($aArgsSave['aFieldsSaveMaster'], 'elementtitle');
array_push($aArgsSave['aFieldsSaveMaster'], 'position_left');
array_push($aArgsSave['aFieldsSaveMaster'], 'position_top');
array_push($aArgsSave['aFieldsSaveMaster'], 'width');
array_push($aArgsSave['aFieldsSaveMaster'], 'height');
array_push($aArgsSave['aFieldsSaveMaster'], 'fontsize');
array_push($aArgsSave['aFieldsSaveMaster'], 'fontcolor');
array_push($aArgsSave['aFieldsSaveMaster'], 'fontstyle');
array_push($aArgsSave['aFieldsSaveMaster'], 'background_color');
array_push($aArgsSave['aFieldsSaveMaster'], 'content');
array_push($aArgsSave['aFieldsSaveMaster'], 'content_transrequired');
array_push($aArgsSave['aFieldsSaveMaster'], 'max_char');
array_push($aArgsSave['aFieldsSaveMaster'], 'alignment');
array_push($aArgsSave['aFieldsSaveMaster'], 'verticalalignment');
array_push($aArgsSave['aFieldsSaveMaster'], 'editable');
array_push($aArgsSave['aFieldsSaveMaster'], 'active');
array_push($aArgsSave['aFieldsSaveMaster'], 'fixed');

$aArgsSave['aFieldsSaveNotMaster'] = array();
array_push($aArgsSave['aFieldsSaveNotMaster'], 'id_tceid');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'id_tconid');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'id_tcid');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'page');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'elementtitle');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'position_left');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'position_top');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'width');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'height');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'fontsize');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'fontcolor');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'fontstyle');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'background_color');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'content');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'content_transrequired');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'max_char');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'alignment');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'verticalalignment');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'editable');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'active');
array_push($aArgsSave['aFieldsSaveNotMaster'], 'fixed');

$aArgs['fields'] = array();

$aArgsLV = array();
$aArgsLV['type'] = 'sysall';
$aLocalVersions = localVariationsBuild($aArgsLV);




$aComboxId = array();
###################################################
//// read master from tempdata
//$query = $CONFIG['dbconn'][0]->prepare('
//									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data
//									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
//									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:id_count)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:id_lang)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev = (:id_dev)
//									');
//$query->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
//$query->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
//$query->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
//$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//$query->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
//$query->execute();
//$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//$num = $query->rowCount();
//
//foreach($rows as $row){
//	$aData = json_decode($row['data'], true);
//	$aDataComponents = json_decode($aData['components'], true);
//	
//	// update last changes to master
//	if($CONFIG['settings']['formCountry'] == 0 && $CONFIG['settings']['formLanguage'] == 0 && $CONFIG['settings']['formDevice'] == 0){
//		$variation = 'master';
//	}
//	$aDataComponents = $aComponents;
	
	$row['id_count'] = 0;
	$row['id_lang'] = 0;
	$row['id_dev'] = 0;
	$row['id_cl'] = $CONFIG['activeSettings']['id_clid'];
	foreach($aComponents['pages'] as $aPages){
		foreach($aPages as $compid => $aComponent){
                        if($aComponent['fixed'] == '') $aComponent['fixed'] = 0;
			if($aComponent['page'] == $varSQL['page'] && $aComponent['id_tpid'] == $varSQL['tpid']){
				$aArgsSave['allVersions'] = array();
				$aArgsSave['changedVersions'] = array();
				
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
				$tceid = $CONFIG['dbconn'][0]->lastInsertId();
				$aComboxId[$compid] = $tceid;
				
				$aArgs['data'] = array();
				$aArgs['data']['id_tceid'] = $tceid;
				$aArgs['data']['id_tconid'] = $tconid;
				$aArgs['data']['id_tcid'] = $aComponent['id_tcid'];
				$aArgs['data']['page'] = $aComponent['page'];
				$aArgs['data']['elementtitle'] = (isset($aComponent['elementtitle'])) ? $aComponent['elementtitle'] : '';
				$aArgs['data']['position_left'] = $aComponent['left'];
				$aArgs['data']['position_top'] = $aComponent['top'];
				$aArgs['data']['width'] = $aComponent['width'];
				$aArgs['data']['height'] = $aComponent['height'];
				$aArgs['data']['fontsize'] = $aComponent['fontsize'];
				$aArgs['data']['fontcolor'] = $aComponent['fontcolor'];
				$aArgs['data']['fontstyle'] = $aComponent['fontstyle'];
				$aArgs['data']['background_color'] = $aComponent['background_color'];
				$aArgs['data']['content'] = $aComponent['content'];
				$aArgs['data']['content_transrequired'] = $aComponent['transrequired'];
				$aArgs['data']['max_char'] = $aComponent['maxchars'];
				$aArgs['data']['alignment'] = $aComponent['alignment'];
				$aArgs['data']['verticalalignment'] = $aComponent['verticalalignment'];
				$aArgs['data']['editable'] = $aComponent['editable'];
				$aArgs['data']['active'] = $aComponent['active'];
				$aArgs['data']['fixed'] = (isset($aComponent['fixed'])) ? $aComponent['fixed'] : NULL;
	
				$aArgs['data']['id_count'] = $row['id_count'];
				$aArgs['data']['id_lang'] = $row['id_lang'];
				$aArgs['data']['id_dev'] = $row['id_dev'];
				$aArgs['data']['id_cl'] = $row['id_cl'];
		
	
				$aArgsSave['aData'] = setValuesSave($aArgs);
				$aArgsSave['aData']['id_count'] = $row['id_count'];
				$aArgsSave['aData']['id_lang'] = $row['id_lang'];
				$aArgsSave['aData']['id_dev'] = $row['id_dev'];
				$aArgsSave['aData']['id_cl'] = $row['id_cl'];
				$aArgsSave['aData']['id_data'] = $tceid;
				$aArgsSave['id_data'] = $tceid;
	
	
				
				$col = '';
				$val = '';
				$upd = '';
				foreach($aArgsSave['columns'] as $field => $format){
					if(($variation == 'master' && in_array($field, $aArgsSave['aFieldsSaveMaster'])) || ($variation == 'local' && in_array($field, $aArgsSave['aFieldsSaveNotMaster']))){
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
				$queryC->bindValue(':id', $aArgsSave['aData']['id_data'], PDO::PARAM_INT);
				$queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
				$queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
				$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
				$queryC->bindValue(':id_cl', $aArgsSave['aData']['id_cl'], PDO::PARAM_INT);
				$queryC->bindValue(':restricted_all', 0, PDO::PARAM_STR);
				$queryC->bindValue(':now', $now, PDO::PARAM_STR);
				$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
				
				foreach($aArgsSave['columns'] as $field => $format){
					if(($variation == 'master' && in_array($field, $aArgsSave['aFieldsSaveMaster'])) || ($variation == 'local' && in_array($field, $aArgsSave['aFieldsSaveNotMaster']))){
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
				
				// insert master null
				$qry = 'INSERT INTO ' . $aArgsSave['table'] . 'uni
							(' . $aArgsSave['primarykey'] . ', id_count, id_lang, id_dev, id_cl, restricted_all, create_at, create_from, change_from' . $col . ')
						VALUES
							(:id, :id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :now, :create_from, :create_from' . $val . ')
						ON DUPLICATE KEY UPDATE 
							' . $upd . '
							change_from = (:create_from),
							del = (:nultime)
						';
				$queryC = $CONFIG['dbconn'][0]->prepare($qry);
				$queryC->bindValue(':id', $aArgsSave['aData']['id_data'], PDO::PARAM_INT);
				$queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
				$queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
				$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
				$queryC->bindValue(':id_cl', $aArgsSave['aData']['id_cl'], PDO::PARAM_INT);
				$queryC->bindValue(':restricted_all', 0, PDO::PARAM_STR);
				$queryC->bindValue(':now', $now, PDO::PARAM_STR);
				$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
				
				foreach($aArgsSave['columns'] as $field => $format){
					if(($variation == 'master' && in_array($field, $aArgsSave['aFieldsSaveMaster'])) || ($variation == 'local' && in_array($field, $aArgsSave['aFieldsSaveNotMaster']))){
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
				
	//
	//			$aArgsSave['changedVersions'] = array(array(0,0,0));
	//			$aArgsSave['allVersions'] = $aLocalVersions;
	//			insertAll($aArgsSave);
			}
		}
	}
//}



//###################################################
//// read local from tempdata
//$query = $CONFIG['dbconn'][0]->prepare('
//									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data
//									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
//									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count <> (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang <> (:nul)
//									');
//$query->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
//$query->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
//$query->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
//$query->bindValue(':nul', 0, PDO::PARAM_INT);
//$query->execute();
//$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//$num = $query->rowCount();
//
//foreach($rows as $row){
//	$aArgsSave['allVersions'] = array();
//	$aArgsSave['changedVersions'] = array();
//	
//	$variation = 'local';
//	$aData = json_decode($row['data'], true);
//	$aDataComponents = json_decode($aData['components'], true);
//	
//	// update last changes to master
//	if($CONFIG['settings']['formCountry'] == $row['id_count'] && $CONFIG['settings']['formLanguage'] == $row['id_lang'] && $CONFIG['settings']['formDevice'] == $row['id_dev']){
//		$aDataComponents = $aComponents;
//	}
//	
//	foreach($aDataComponents['pages'] as $aPages){
//		foreach($aPages as $compid => $aComponent){
//			$tceid = $aComboxId[$compid];
//			
//			$aArgs['data'] = array();
//			$aArgs['data']['id_tceid'] = $tceid;
//			$aArgs['data']['id_tconid'] = $tconid;
//			$aArgs['data']['id_tcid'] = $aComponent['id_tcid'];
//			$aArgs['data']['page'] = $aComponent['page'];
//			$aArgs['data']['elementtitle'] = ''; //$aComponent['id_tempid'];
//			$aArgs['data']['position_left'] = $aComponent['left'];
//			$aArgs['data']['position_top'] = $aComponent['top'];
//			$aArgs['data']['width'] = $aComponent['width'];
//			$aArgs['data']['height'] = $aComponent['height'];
//			$aArgs['data']['fontsize'] = $aComponent['fontsize'];
//			$aArgs['data']['fontcolor'] = $aComponent['fontcolor'];
//			$aArgs['data']['fontstyle'] = $aComponent['fontstyle'];
//			$aArgs['data']['content'] = $aComponent['content'];
//			$aArgs['data']['content_transrequired'] = $aComponent['transrequired'];
//			$aArgs['data']['max_char'] = $aComponent['maxchars'];
//			$aArgs['data']['alignment'] = $aComponent['alignment'];
//			$aArgs['data']['editable'] = $aComponent['editable'];
//			$aArgs['data']['active'] = $aComponent['active'];
//			$aArgs['data']['fixed'] = (isset($aComponent['fixed'])) ? $aComponent['fixed'] : NULL;
//
//			$aArgs['data']['id_count'] = $row['id_count'];
//			$aArgs['data']['id_lang'] = $row['id_lang'];
//			$aArgs['data']['id_dev'] = $row['id_dev'];
//			$aArgs['data']['id_cl'] = $row['id_cl'];
//	
//
//			$aArgsSave['aData'] = setValuesSave($aArgs);
//			$aArgsSave['aData']['id_count'] = $row['id_count'];
//			$aArgsSave['aData']['id_lang'] = $row['id_lang'];
//			$aArgsSave['aData']['id_dev'] = $row['id_dev'];
//			$aArgsSave['aData']['id_cl'] = $row['id_cl'];
//			$aArgsSave['aData']['id_data'] = $tceid;
//			$aArgsSave['id_data'] = $tceid;
//	
//			$aChange = checkChanges($aArgsSave);
//		
//			$col = '';
//			$val = '';
//			$upd = '';
//			foreach($aChange['aChangedFields'] as $field){
//				if(($variation == 'master' && in_array($field, $aArgsSave['aFieldsSaveMaster'])) || ($variation == 'local' && in_array($field, $aArgsSave['aFieldsSaveNotMaster']))){
//					if($field != $aArgsSave['primarykey']){
//						$col .= ', ' . $field;
//						$val .= ', :' . $field . '';
//						$upd .= $field.' = (:'.$field.'), ' ;
//					}
//				}
//			}
//			foreach($aChange['aChangedFieldsMaster'] as $field){
//				if(($variation == 'master' && in_array($field, $aArgsSave['aFieldsSaveMaster'])) || ($variation == 'local' && in_array($field, $aArgsSave['aFieldsSaveNotMaster']))){
//					if($field != $aArgsSave['primarykey']){
//						$col .= ', ' . $field;
//						$val .= ', :' . $field . '';
//						$upd .= $field.' = (:'.$field.'), ' ;
//					}
//				}
//			}
//
//
//			// insert local
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
//			$queryC->bindValue(':id', $aArgsSave['aData']['id_data'], PDO::PARAM_INT);
//			$queryC->bindValue(':id_count', $row['id_count'], PDO::PARAM_INT);
//			$queryC->bindValue(':id_lang', $row['id_lang'], PDO::PARAM_INT);
//			$queryC->bindValue(':id_dev', $row['id_dev'], PDO::PARAM_INT);
//			$queryC->bindValue(':id_cl', $aArgsSave['aData']['id_cl'], PDO::PARAM_INT);
//			$queryC->bindValue(':restricted_all', 0, PDO::PARAM_STR);
//			$queryC->bindValue(':now', $now, PDO::PARAM_STR);
//			$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//			$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
//			
//			foreach($aChange['aChangedFields'] as $field){
//				if(($variation == 'master' && in_array($field, $aArgsSave['aFieldsSaveMaster'])) || ($variation == 'local' && in_array($field, $aArgsSave['aFieldsSaveNotMaster']))){
//					if($field != $aArgsSave['primarykey']){
//						if($aArgsSave['columns'][$field] == 'i' || $aArgsSave['columns'][$field] == 'si' || $aArgsSave['columns'][$field] == 'b'){
//							$queryC->bindValue(':'.$field, (is_array($aArgsSave['aData'][$field])) ? json_encode($aArgsSave['aData'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_INT);
//						}else{ 
//							$queryC->bindValue(':'.$field, (is_array($aArgsSave['aData'][$field])) ? json_encode($aArgsSave['aData'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_STR);
//						}
//					}
//				}
//			}
//			foreach($aChange['aChangedFieldsMaster'] as $field){
//				if(($variation == 'master' && in_array($field, $aArgsSave['aFieldsSaveMaster'])) || ($variation == 'local' && in_array($field, $aArgsSave['aFieldsSaveNotMaster']))){
//					if($field != $aArgsSave['primarykey']){
//						if($aArgsSave['columns'][$field] == 'i' || $aArgsSave['columns'][$field] == 'si' || $aArgsSave['columns'][$field] == 'b'){
//							$queryC->bindValue(':'.$field, (is_array($aArgsSave['aDataMaster'][$field])) ? json_encode($aArgsSave['aDataMaster'][$field]) : trim($aArgsSave['aDataMaster'][$field]), PDO::PARAM_INT);
//						}else{ 
//							$queryC->bindValue(':'.$field, (is_array($aArgsSave['aDataMaster'][$field])) ? json_encode($aArgsSave['aDataMaster'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_STR);
//						}
//					}
//				}
//			}
//			$queryC->execute();
//			$numC = $queryC->rowCount();
//		
//		
//			if(!in_array(array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']), $aArgsSave['allVersions'])) array_push($aArgsSave['allVersions'], array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']));
//			if($numC > 0 || count($aChange['aDataOld'] == 0)){
//				if(!in_array(array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']), $aArgsSave['changedVersions']))  array_push($aArgsSave['changedVersions'], array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']));
//			}
//		}
//	}
//}
//
//
//$aArgsSave['changedVersions'] = array(array(0,0,0));
//$aArgsSave['allVersions'] = $aLocalVersions;
//insertAll($aArgsSave);

?>