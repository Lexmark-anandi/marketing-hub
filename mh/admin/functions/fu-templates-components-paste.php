<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();


$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');
$variation = ($CONFIG['settings']['formCountry'] == 0 && $CONFIG['settings']['formLanguage'] == 0 && $CONFIG['settings']['formDevice'] == 0) ? 'master' : 'local';

$aComponent = json_decode($varSQL['component'], true);
$aComponentsTempdata = array();
 

// create new ID
$queryI = $CONFIG['dbconn'][0]->prepare('
									INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_
									(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
									VALUES
									(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
									');
$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
$queryI->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
$queryI->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
$queryI->execute();
$tpeid = $CONFIG['dbconn'][0]->lastInsertId();

$aComponent['id_tpeid'] = $tpeid;
$aComponent['id_caid'] = $varSQL['caid'];
$aComponent['id_tpid'] = $varSQL['tpid'];
$aComponent['pageid'] = $varSQL['pageid'];
$aComponent['page'] = $varSQL['page'];


//$queryP1e = $CONFIG['dbconn'][0]->prepare('
//									SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_loc.create_from
//									FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_loc 
//									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_loc.id_tempid = (:id_tempid)
//									LIMIT 1
//									');
//$queryP1e->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_INT);
//$queryP1e->execute();
//$rowsP1e = $queryP1e->fetchAll(PDO::FETCH_ASSOC);
//$numP1e = $queryP1e->rowCount();
//
//
//if($CONFIG['user']['specifications'][14] == 8 && $CONFIG['settings']['formCountry'] == 0 && $CONFIG['settings']['formLanguage'] == 0 && $CONFIG['settings']['formDevice'] == 0){
//	$aArgsLV = array();
//	$aArgsLV['type'] = 'temp';
//	$aLocalVersions = localVariationsBuild($aArgsLV);
//	
//	// delete master version for restricted all access
//	if($numP1e > 0 && $CONFIG['user']['id'] != $rowsP1e[0]['create_from']){
//		$key0 = array_search(array(0,0,0), $aLocalVersions);
//		unset($aLocalVersions[$key0]); 
//	}
//
//	foreach($aLocalVersions as $version){
//		$queryI = $CONFIG['dbconn'][0]->prepare('
//											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc
//												(id_tpeid,
//												id_count,
//												id_lang,
//												id_dev,
//												id_cl,
//												restricted_all,
//												id_tempid,
//												id_caid,
//												id_tpid,
//												id_tcid,
//												page_id,
//												page,
//												elementtitle,
//												position_left,
//												position_top,
//												width,
//												height,
//												fontsize,
//												fontcolor,
//												fontstyle,
//												background_color,
//												alignment,
//												verticalalignment,
//												bold,
//												italic,
//												underline,
//												max_char,
//												editable,
//												content,
//												content_transrequired,
//												id_piid,
//												image,
//												contact_alignment,
//												fixed,
//												active,
//												create_at,
//												create_from)
//											VALUES
//												(:id_tpeid,
//												:id_count,
//												:id_lang,
//												:id_dev,
//												:id_cl,
//												:restricted_all,
//												:id_tempid,
//												:id_caid,
//												:id_tpid,
//												:id_tcid,
//												:page_id,
//												:page,
//												:elementtitle,
//												:position_left,
//												:position_top,
//												:width,
//												:height,
//												:fontsize,
//												:fontcolor,
//												:fontstyle,
//												:background_color,
//												:alignment,
//												:verticalalignment,
//												:bold,
//												:italic,
//												:underline,
//												:max_char,
//												:editable,
//												:content,
//												:content_transrequired,
//												:id_piid,
//												:image,
//												:contact_alignment,
//												:fixed,
//												:active,
//												:create_at,
//												:create_from)
//											');
//											
//		$queryI->bindValue(':id_tpeid', $aComponent['id_tpeid'], PDO::PARAM_INT);
//		$queryI->bindValue(':id_count', $version[0], PDO::PARAM_INT);
//		$queryI->bindValue(':id_lang', $version[1], PDO::PARAM_INT);
//		$queryI->bindValue(':id_dev', $version[2], PDO::PARAM_INT);
//		$queryI->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
//		$queryI->bindValue(':restricted_all', 0, PDO::PARAM_INT);
//		$queryI->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_INT);
//		$queryI->bindValue(':id_caid', $aComponent['id_caid'], PDO::PARAM_INT);
//		$queryI->bindValue(':id_tpid', $aComponent['id_tpid'], PDO::PARAM_INT);
//		$queryI->bindValue(':id_tcid', $aComponent['id_tcid'], PDO::PARAM_INT);
//		$queryI->bindValue(':page_id', $aComponent['pageid'], PDO::PARAM_INT);
//		$queryI->bindValue(':page', $aComponent['page'], PDO::PARAM_INT);
//		$queryI->bindValue(':elementtitle', $aComponent['elementtitle'], PDO::PARAM_STR);
//		$queryI->bindValue(':position_left', $aComponent['left'], PDO::PARAM_STR);
//		$queryI->bindValue(':position_top', $aComponent['top'], PDO::PARAM_STR);
//		$queryI->bindValue(':width', $aComponent['width'], PDO::PARAM_STR);
//		$queryI->bindValue(':height', $aComponent['height'], PDO::PARAM_STR);
//		$queryI->bindValue(':fontsize', $aComponent['fontsize'], PDO::PARAM_INT);
//		$queryI->bindValue(':fontcolor', $aComponent['fontcolor'], PDO::PARAM_STR);
//		$queryI->bindValue(':fontstyle', $aComponent['fontstyle'], PDO::PARAM_INT);
//		$queryI->bindValue(':background_color', $aComponent['background_color'], PDO::PARAM_INT);
//		$queryI->bindValue(':alignment', $aComponent['alignment'], PDO::PARAM_STR);
//		$queryI->bindValue(':verticalalignment', $aComponent['verticalalignment'], PDO::PARAM_STR);
//		$queryI->bindValue(':bold', '', PDO::PARAM_INT);
//		$queryI->bindValue(':italic', '', PDO::PARAM_INT);
//		$queryI->bindValue(':underline', '', PDO::PARAM_INT);
//		$queryI->bindValue(':max_char', $aComponent['maxchars'], PDO::PARAM_INT);
//		$queryI->bindValue(':editable', $aComponent['editable'], PDO::PARAM_INT);
//		$queryI->bindValue(':content', $aComponent['content'], PDO::PARAM_STR);
//		$queryI->bindValue(':content_transrequired', $aComponent['transrequired'], PDO::PARAM_INT);
//		$queryI->bindValue(':id_piid', 0, PDO::PARAM_INT);
//		$queryI->bindValue(':image', 0, PDO::PARAM_INT);
//		$queryI->bindValue(':contact_alignment', '', PDO::PARAM_STR);
//		$queryI->bindValue(':fixed', $aComponent['fixed'], PDO::PARAM_INT);
//		$queryI->bindValue(':active', $aComponent['active'], PDO::PARAM_INT);
//		$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
//		$queryI->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
//		$queryI->execute();
//		$numI = $queryI->rowCount();
//	}
//
//}else{
//	$queryI = $CONFIG['dbconn'][0]->prepare('
//										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc
//											(id_tpeid,
//											id_count,
//											id_lang,
//											id_dev,
//											id_cl,
//											restricted_all,
//											id_tempid,
//											id_caid,
//											id_tpid,
//											id_tcid,
//											page_id,
//											page,
//											elementtitle,
//											position_left,
//											position_top,
//											width,
//											height,
//											fontsize,
//											fontcolor,
//											fontstyle,
//											background_color,
//											alignment,
//											verticalalignment,
//											bold,
//											italic,
//											underline,
//											max_char,
//											editable,
//											content,
//											content_transrequired,
//											id_piid,
//											image,
//											contact_alignment,
//											fixed,
//											active,
//											create_at,
//											create_from)
//										VALUES
//											(:id_tpeid,
//											:id_count,
//											:id_lang,
//											:id_dev,
//											:id_cl,
//											:restricted_all,
//											:id_tempid,
//											:id_caid,
//											:id_tpid,
//											:id_tcid,
//											:page_id,
//											:page,
//											:elementtitle,
//											:position_left,
//											:position_top,
//											:width,
//											:height,
//											:fontsize,
//											:fontcolor,
//											:fontstyle,
//											:background_color,
//											:alignment,
//											:verticalalignment,
//											:bold,
//											:italic,
//											:underline,
//											:max_char,
//											:editable,
//											:content,
//											:content_transrequired,
//											:id_piid,
//											:image,
//											:contact_alignment,
//											:fixed,
//											:active,
//											:create_at,
//											:create_from)
//										');
//	$queryI->bindValue(':id_tpeid', $aComponent['id_tpeid'], PDO::PARAM_INT);
//	$queryI->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//	$queryI->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//	$queryI->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
//	$queryI->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
//	$queryI->bindValue(':restricted_all', 0, PDO::PARAM_INT);
//	$queryI->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_INT);
//	$queryI->bindValue(':id_caid', $aComponent['id_caid'], PDO::PARAM_INT);
//	$queryI->bindValue(':id_tpid', $aComponent['id_tpid'], PDO::PARAM_INT);
//	$queryI->bindValue(':id_tcid', $aComponent['id_tcid'], PDO::PARAM_INT);
//	$queryI->bindValue(':page_id', $aComponent['pageid'], PDO::PARAM_INT);
//	$queryI->bindValue(':page', $aComponent['page'], PDO::PARAM_INT);
//	$queryI->bindValue(':elementtitle', $aComponent['elementtitle'], PDO::PARAM_STR);
//	$queryI->bindValue(':position_left', $aComponent['left'], PDO::PARAM_STR);
//	$queryI->bindValue(':position_top', $aComponent['top'], PDO::PARAM_STR);
//	$queryI->bindValue(':width', $aComponent['width'], PDO::PARAM_STR);
//	$queryI->bindValue(':height', $aComponent['height'], PDO::PARAM_STR);
//	$queryI->bindValue(':fontsize', $aComponent['fontsize'], PDO::PARAM_INT);
//	$queryI->bindValue(':fontcolor', $aComponent['fontcolor'], PDO::PARAM_STR);
//	$queryI->bindValue(':fontstyle', $aComponent['fontstyle'], PDO::PARAM_INT);
//	$queryI->bindValue(':background_color', $aComponent['background_color'], PDO::PARAM_INT);
//	$queryI->bindValue(':alignment', $aComponent['alignment'], PDO::PARAM_STR);
//	$queryI->bindValue(':verticalalignment', $aComponent['verticalalignment'], PDO::PARAM_STR);
//	$queryI->bindValue(':bold', '', PDO::PARAM_INT);
//	$queryI->bindValue(':italic', '', PDO::PARAM_INT);
//	$queryI->bindValue(':underline', '', PDO::PARAM_INT);
//	$queryI->bindValue(':max_char', $aComponent['maxchars'], PDO::PARAM_INT);
//	$queryI->bindValue(':editable', $aComponent['editable'], PDO::PARAM_INT);
//	$queryI->bindValue(':content', $aComponent['content'], PDO::PARAM_STR);
//	$queryI->bindValue(':content_transrequired', $aComponent['transrequired'], PDO::PARAM_INT);
//	$queryI->bindValue(':id_piid', 0, PDO::PARAM_INT);
//	$queryI->bindValue(':image', 0, PDO::PARAM_INT);
//	$queryI->bindValue(':contact_alignment', '', PDO::PARAM_STR);
//	$queryI->bindValue(':fixed', $aComponent['fixed'], PDO::PARAM_INT);
//	$queryI->bindValue(':active', $aComponent['active'], PDO::PARAM_INT);
//	$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
//	$queryI->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
//	$queryI->execute();
//	$numI = $queryI->rowCount();
//	
//
//	$aLocalVersions = array(array($CONFIG['settings']['formCountry'], $CONFIG['settings']['formLanguage'], $CONFIG['settings']['formDevice']));
//	if($variation == 'master'){
//		$aArgsLV = array();
//		$aArgsLV['type'] = 'sysall';
//		$aLocalVersions = localVariationsBuild($aArgsLV);
//	}
//}
//
//foreach($aLocalVersions as $version){
//	$queryI = $CONFIG['dbconn'][0]->prepare('
//										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni
//											(id_tpeid,
//											id_count,
//											id_lang,
//											id_dev,
//											id_cl,
//											restricted_all,
//											id_tempid,
//											id_caid,
//											id_tpid,
//											id_tcid,
//											page_id,
//											page,
//											elementtitle,
//											position_left,
//											position_top,
//											width,
//											height,
//											fontsize,
//											fontcolor,
//											fontstyle,
//											background_color,
//											alignment,
//											verticalalignment,
//											bold,
//											italic,
//											underline,
//											max_char,
//											editable,
//											content,
//											content_transrequired,
//											id_piid,
//											image,
//											contact_alignment,
//											fixed,
//											active,
//											create_at,
//											create_from)
//										VALUES
//											(:id_tpeid,
//											:id_count,
//											:id_lang,
//											:id_dev,
//											:id_cl,
//											:restricted_all,
//											:id_tempid,
//											:id_caid,
//											:id_tpid,
//											:id_tcid,
//											:page_id,
//											:page,
//											:elementtitle,
//											:position_left,
//											:position_top,
//											:width,
//											:height,
//											:fontsize,
//											:fontcolor,
//											:fontstyle,
//											:background_color,
//											:alignment,
//											:verticalalignment,
//											:bold,
//											:italic,
//											:underline,
//											:max_char,
//											:editable,
//											:content,
//											:content_transrequired,
//											:id_piid,
//											:image,
//											:contact_alignment,
//											:fixed,
//											:active,
//											:create_at,
//											:create_from)
//										');
//	$queryI->bindValue(':id_tpeid', $aComponent['id_tpeid'], PDO::PARAM_INT);
//	$queryI->bindValue(':id_count', $version[0], PDO::PARAM_INT);
//	$queryI->bindValue(':id_lang', $version[1], PDO::PARAM_INT);
//	$queryI->bindValue(':id_dev', $version[2], PDO::PARAM_INT);
//	$queryI->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
//	$queryI->bindValue(':restricted_all', 0, PDO::PARAM_INT);
//	$queryI->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_INT);
//	$queryI->bindValue(':id_caid', $aComponent['id_caid'], PDO::PARAM_INT);
//	$queryI->bindValue(':id_tpid', $aComponent['id_tpid'], PDO::PARAM_INT);
//	$queryI->bindValue(':id_tcid', $aComponent['id_tcid'], PDO::PARAM_INT);
//	$queryI->bindValue(':page_id', $aComponent['pageid'], PDO::PARAM_INT);
//	$queryI->bindValue(':page', $aComponent['page'], PDO::PARAM_INT);
//	$queryI->bindValue(':elementtitle', $aComponent['elementtitle'], PDO::PARAM_STR);
//	$queryI->bindValue(':position_left', $aComponent['left'], PDO::PARAM_STR);
//	$queryI->bindValue(':position_top', $aComponent['top'], PDO::PARAM_STR);
//	$queryI->bindValue(':width', $aComponent['width'], PDO::PARAM_STR);
//	$queryI->bindValue(':height', $aComponent['height'], PDO::PARAM_STR);
//	$queryI->bindValue(':fontsize', $aComponent['fontsize'], PDO::PARAM_INT);
//	$queryI->bindValue(':fontcolor', $aComponent['fontcolor'], PDO::PARAM_STR);
//	$queryI->bindValue(':fontstyle', $aComponent['fontstyle'], PDO::PARAM_INT);
//	$queryI->bindValue(':background_color', $aComponent['background_color'], PDO::PARAM_INT);
//	$queryI->bindValue(':alignment', $aComponent['alignment'], PDO::PARAM_STR);
//	$queryI->bindValue(':verticalalignment', $aComponent['verticalalignment'], PDO::PARAM_STR);
//	$queryI->bindValue(':bold', '', PDO::PARAM_INT);
//	$queryI->bindValue(':italic', '', PDO::PARAM_INT);
//	$queryI->bindValue(':underline', '', PDO::PARAM_INT);
//	$queryI->bindValue(':max_char', $aComponent['maxchars'], PDO::PARAM_INT);
//	$queryI->bindValue(':editable', $aComponent['editable'], PDO::PARAM_INT);
//	$queryI->bindValue(':content', $aComponent['content'], PDO::PARAM_STR);
//	$queryI->bindValue(':content_transrequired', $aComponent['transrequired'], PDO::PARAM_INT);
//	$queryI->bindValue(':id_piid', 0, PDO::PARAM_INT);
//	$queryI->bindValue(':image', 0, PDO::PARAM_INT);
//	$queryI->bindValue(':contact_alignment', '', PDO::PARAM_STR);
//	$queryI->bindValue(':fixed', $aComponent['fixed'], PDO::PARAM_INT);
//	$queryI->bindValue(':active', $aComponent['active'], PDO::PARAM_INT);
//	$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
//	$queryI->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
//	$queryI->execute();
//	$numI = $queryI->rowCount();
//}
//
//
//
//
//#####################################################################
//// update tempdata
//#####################################################################
//$out = array();
//foreach($aLocalVersions as $version){
//	$queryTd = $CONFIG['dbconn'][0]->prepare('
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
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:id_count)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:id_lang)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev = (:id_dev)
//										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count, ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang, ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev
//										');
//	$queryTd->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
//	$queryTd->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
//	$queryTd->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
//	$queryTd->bindValue(':id_count', $version[0], PDO::PARAM_INT);
//	$queryTd->bindValue(':id_lang', $version[1], PDO::PARAM_INT);
//	$queryTd->bindValue(':id_dev', $version[2], PDO::PARAM_INT);
//	$queryTd->execute();
//	$rowsTd = $queryTd->fetchAll(PDO::FETCH_ASSOC);
//	$numTd = $queryTd->rowCount();
//	
//	foreach($rowsTd as $rowTd){
//		$aTemddata = json_decode($rowTd['data'], true);
//		$aComponentsTempdata = json_decode($aTemddata['components'], true);
//		$aComponentsTempdata['pages']['page_' . $varSQL['pageid']]['compboxOuter_' . $tpeid] = $aComponent;
//		$aTemddata['components'] = json_encode($aComponentsTempdata);
//		
//		$queryTd2 = $CONFIG['dbconn'][0]->prepare('
//											UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata SET
//												data = (:data)
//											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:id_count)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:id_lang)
//											LIMIT 1
//											');
//		$queryTd2->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
//		$queryTd2->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
//		$queryTd2->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
//		$queryTd2->bindValue(':id_count', $rowTd['id_count'], PDO::PARAM_INT);
//		$queryTd2->bindValue(':id_lang', $rowTd['id_lang'], PDO::PARAM_INT);
//		$queryTd2->bindValue(':data', json_encode($aTemddata), PDO::PARAM_INT);
//		$queryTd2->execute();
//		$numTd2 = $queryTd2->rowCount();
//	}
//}
//
//$queryTd = $CONFIG['dbconn'][0]->prepare('
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
//									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count, ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang, ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev
//									');
//$queryTd->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
//$queryTd->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
//$queryTd->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
//$queryTd->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//$queryTd->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//$queryTd->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
//$queryTd->execute();
//$rowsTd = $queryTd->fetchAll(PDO::FETCH_ASSOC);
//$numTd = $queryTd->rowCount();
//
//$aTemddata = json_decode($rowsTd[0]['data'], true);
//$aComp = json_decode($aTemddata['components'], true);
//$out['pages'] = $aComp['pages'];
////echo $num;
////			$arr = $query->errorInfo();
////			print_r($arr);
////



echo json_encode($aComponent);


?>