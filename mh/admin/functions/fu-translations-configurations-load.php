<?php
include_once(__DIR__ . '/fu-templates-configurations-load.php');

//include_once(__DIR__ . '/../config-admin.php');
//$varSQL = getPostData();
//
//
//$date = new DateTime();
//$now = $date->format('Y-m-d H:i:s');
//$variation = ($CONFIG['settings']['formCountry'] == 0 && $CONFIG['settings']['formLanguage'] == 0 && $CONFIG['settings']['formDevice'] == 0) ? 'master' : 'local';
//
//##################################################################### 
//// delete exisiting components
//#####################################################################
//$aTPE = array(0);
//$query = $CONFIG['dbconn'][0]->prepare('
//									SELECT DISTINCT(' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpeid)
//									FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni 
//									
//									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_count = (:id_count)
//										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_lang = (:id_lang)
//										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_dev = (:id_dev)
//										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.del = (:nultime)
//										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tempid = (:id_tempid)
//									');
//$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//$query->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
//$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//$query->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_INT);
//$query->execute();
//$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//$num = $query->rowCount();
//foreach($rows as $row){
//	array_push($aTPE, $row['id_tpeid']);
//}
//
//$condOrg = '';
//$condTd = '';
//if($variation == 'local'){ 
//	$condOrg = '
//		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_##tab##.id_count = (:id_count)
//		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_##tab##.id_lang = (:id_lang)
//		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_##tab##.id_dev = (:id_dev)
//		';
//	$condTce = '
//		AND ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.id_count = (:id_count)
//		AND ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.id_lang = (:id_lang)
//		AND ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.id_dev = (:id_dev)
//		';
//	$condTd = '
//		AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:id_count)
//		AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:id_lang)
//		AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev = (:id_dev)
//		';
//}
//
////$query = $CONFIG['dbconn'][0]->prepare('
////									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_ SET
////										del = (:now)
////									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_.id_tpeid IN (' . implode(',', $aTPE) . ')
////										' . $cond . '
////										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
////									');
////$query->bindValue(':now', $now, PDO::PARAM_STR);
////if($variation == 'local'){
////	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
////	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
////	$query->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
////}
////$query->execute();
//
//$cond = str_replace('##tab##', 'ext', $condOrg);
//$query = $CONFIG['dbconn'][0]->prepare('
//									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_ext SET
//										del = (:now)
//									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_ext.id_tpeid IN (' . implode(',', $aTPE) . ')
//										' . $cond . '
//										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_ext.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//									');
//$query->bindValue(':now', $now, PDO::PARAM_STR);
//if($variation == 'local'){
//	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//	$query->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
//}
//$query->execute();
//
//$cond = str_replace('##tab##', 'loc', $condOrg);
//$query = $CONFIG['dbconn'][0]->prepare('
//									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc SET
//										del = (:now)
//									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc.id_tpeid IN (' . implode(',', $aTPE) . ')
//										' . $cond . '
//										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//									');
//$query->bindValue(':now', $now, PDO::PARAM_STR);
//if($variation == 'local'){
//	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//	$query->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
//}
//$query->execute();
//
//$cond = str_replace('##tab##', 'res', $condOrg);
//$query = $CONFIG['dbconn'][0]->prepare('
//									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_res SET
//										del = (:now)
//									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_res.id_tpeid IN (' . implode(',', $aTPE) . ')
//										' . $cond . '
//										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_res.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//									');
//$query->bindValue(':now', $now, PDO::PARAM_STR);
//if($variation == 'local'){
//	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//	$query->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
//}
//$query->execute();
//
//$cond = str_replace('##tab##', 'uni', $condOrg);
//$query = $CONFIG['dbconn'][0]->prepare('
//									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni SET
//										del = (:now)
//									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpeid IN (' . implode(',', $aTPE) . ')
//										' . $cond . '
//										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//									');
//$query->bindValue(':now', $now, PDO::PARAM_STR);
//if($variation == 'local'){
//	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//	$query->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
//}
//$query->execute();
//$num = $query->rowCount();
//
//
//
//
//
//#####################################################################
//// load configuration
//#####################################################################
//$aTCE = array(0);
//$aComponentsTempdata = array();
//$query = $CONFIG['dbconn'][0]->prepare('
//									SELECT DISTINCT(' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.id_tceid)
//									FROM ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni 
//									
//									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.id_count = (:id_count)
//										AND ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.id_lang = (:id_lang)
//										AND ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.id_dev = (:id_dev)
//										AND ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//										AND ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.del = (:nultime)
//										AND ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.id_tconid = (:id_tconid)
//									');
//$query->bindValue(':id_count', 0, PDO::PARAM_INT);
//$query->bindValue(':id_lang', 0, PDO::PARAM_INT);
//$query->bindValue(':id_dev', 0, PDO::PARAM_INT);
//$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//$query->bindValue(':id_tconid', $varSQL['configuration'], PDO::PARAM_INT);
//$query->execute();
//$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//$num = $query->rowCount();
//foreach($rows as $row){
//	array_push($aTCE, $row['id_tceid']);
//}
//
//
//// _loc
//foreach($aTCE as $tce){
//	$query = $CONFIG['dbconn'][0]->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.id_count,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.id_lang,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.id_dev,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.id_cl,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.id_tcid,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.page,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.elementtitle,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.position_left,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.position_top,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.width,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.height,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.fontsize,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.fontcolor,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.fontstyle,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.alignment,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.bold,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.italic,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.underline,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.max_char,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.editable,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.content,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.content_transrequired,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.id_piid,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.image,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.contact_alignment,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.fixed,
//											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.active
//											
//										FROM ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni 
//										
//										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//											AND ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.del = (:nultime)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements_uni.id_tceid = (:id_tceid)
//										');
//	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$query->bindValue(':id_tceid', $tce, PDO::PARAM_INT);
//	$query->execute();
//	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//	$num = $query->rowCount();
//	
//
//	$i = 0;
//	foreach($rows as $row){
//		if($i == 0){
//			// create new ID
//			$queryI = $CONFIG['dbconn'][0]->prepare('
//												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_
//												(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
//												VALUES
//												(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
//												');
//			$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
//			$queryI->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
//			$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
//			$queryI->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
//			$queryI->execute();
//			$tpeid = $CONFIG['dbconn'][0]->lastInsertId();
//		}
//		$i++;
//		
//		
//		$queryTP = $CONFIG['dbconn'][0]->prepare('
//											SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tpid,
//												' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid,
//												' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page
//											FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni 
//											
//											WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_count = (:id_count)
//												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_lang = (:id_lang)
//												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_dev = (:id_dev)
//												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.del = (:nultime)
//												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = (:id_tempid)
//											');
//		$queryTP->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//		$queryTP->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//		$queryTP->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
//		$queryTP->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//		$queryTP->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_INT);
//		$queryTP->execute();
//		$rowsTP = $queryTP->fetchAll(PDO::FETCH_ASSOC);
//		$numTP = $queryTP->rowCount();
//		
//		$tp = 0;
//		foreach($rowsTP as $rowTP){
//			if($rowTP['id_bfid'] > 0){
//				if($rowTP['page'] == $row['page']) $tp = $rowTP['id_tpid'];
//			}else{
//				$tp = $rowTP['id_tpid'];
//			}
//		}
//			
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
//												alignment,
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
//												:alignment,
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
//		$queryI->bindValue(':id_tpeid', $tpeid, PDO::PARAM_INT);
//		$queryI->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//		$queryI->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//		$queryI->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
//		$queryI->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
//		$queryI->bindValue(':restricted_all', 0, PDO::PARAM_INT);
//		$queryI->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_INT);
//		$queryI->bindValue(':id_caid', $varSQL['caid'], PDO::PARAM_INT);
//		$queryI->bindValue(':id_tpid', $tp, PDO::PARAM_INT);
//		$queryI->bindValue(':id_tcid', $row['id_tcid'], PDO::PARAM_INT);
//		$queryI->bindValue(':page_id', $tp . '_' . $row['page'], PDO::PARAM_INT);
//		$queryI->bindValue(':page', $row['page'], PDO::PARAM_INT);
//		$queryI->bindValue(':elementtitle', $row['elementtitle'], PDO::PARAM_STR);
//		$queryI->bindValue(':position_left', $row['position_left'], PDO::PARAM_STR);
//		$queryI->bindValue(':position_top', $row['position_top'], PDO::PARAM_STR);
//		$queryI->bindValue(':width', $row['width'], PDO::PARAM_STR);
//		$queryI->bindValue(':height', $row['height'], PDO::PARAM_STR);
//		$queryI->bindValue(':fontsize', $row['fontsize'], PDO::PARAM_INT);
//		$queryI->bindValue(':fontcolor', $row['fontcolor'], PDO::PARAM_STR);
//		$queryI->bindValue(':fontstyle', $row['fontstyle'], PDO::PARAM_INT);
//		$queryI->bindValue(':alignment', $row['alignment'], PDO::PARAM_STR);
//		$queryI->bindValue(':bold', $row['bold'], PDO::PARAM_INT);
//		$queryI->bindValue(':italic', $row['italic'], PDO::PARAM_INT);
//		$queryI->bindValue(':underline', $row['underline'], PDO::PARAM_INT);
//		$queryI->bindValue(':max_char', $row['max_char'], PDO::PARAM_INT);
//		$queryI->bindValue(':editable', $row['editable'], PDO::PARAM_INT);
//		$queryI->bindValue(':content', $row['content'], PDO::PARAM_STR);
//		$queryI->bindValue(':content_transrequired', $row['content_transrequired'], PDO::PARAM_INT);
//		$queryI->bindValue(':id_piid', $row['id_piid'], PDO::PARAM_INT);
//		$queryI->bindValue(':image', $row['image'], PDO::PARAM_INT);
//		$queryI->bindValue(':contact_alignment', $row['contact_alignment'], PDO::PARAM_STR);
//		$queryI->bindValue(':fixed', $row['fixed'], PDO::PARAM_INT);
//		$queryI->bindValue(':active', $row['active'], PDO::PARAM_INT);
//		$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
//		$queryI->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
//		$queryI->execute();
//		$numI = $queryI->rowCount();
//		
//		
//		$queryI = $CONFIG['dbconn'][0]->prepare('
//											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni
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
//												alignment,
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
//												:alignment,
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
//		$queryI->bindValue(':id_tpeid', $tpeid, PDO::PARAM_INT);
//		$queryI->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//		$queryI->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//		$queryI->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
//		$queryI->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
//		$queryI->bindValue(':restricted_all', 0, PDO::PARAM_INT);
//		$queryI->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_INT);
//		$queryI->bindValue(':id_caid', $varSQL['caid'], PDO::PARAM_INT);
//		$queryI->bindValue(':id_tpid', $tp, PDO::PARAM_INT);
//		$queryI->bindValue(':id_tcid', $row['id_tcid'], PDO::PARAM_INT);
//		$queryI->bindValue(':page_id', $tp . '_' . $row['page'], PDO::PARAM_INT);
//		$queryI->bindValue(':page', $row['page'], PDO::PARAM_INT);
//		$queryI->bindValue(':elementtitle', $row['elementtitle'], PDO::PARAM_STR);
//		$queryI->bindValue(':position_left', $row['position_left'], PDO::PARAM_STR);
//		$queryI->bindValue(':position_top', $row['position_top'], PDO::PARAM_STR);
//		$queryI->bindValue(':width', $row['width'], PDO::PARAM_STR);
//		$queryI->bindValue(':height', $row['height'], PDO::PARAM_STR);
//		$queryI->bindValue(':fontsize', $row['fontsize'], PDO::PARAM_INT);
//		$queryI->bindValue(':fontcolor', $row['fontcolor'], PDO::PARAM_STR);
//		$queryI->bindValue(':fontstyle', $row['fontstyle'], PDO::PARAM_INT);
//		$queryI->bindValue(':alignment', $row['alignment'], PDO::PARAM_STR);
//		$queryI->bindValue(':bold', $row['bold'], PDO::PARAM_INT);
//		$queryI->bindValue(':italic', $row['italic'], PDO::PARAM_INT);
//		$queryI->bindValue(':underline', $row['underline'], PDO::PARAM_INT);
//		$queryI->bindValue(':max_char', $row['max_char'], PDO::PARAM_INT);
//		$queryI->bindValue(':editable', $row['editable'], PDO::PARAM_INT);
//		$queryI->bindValue(':content', $row['content'], PDO::PARAM_STR);
//		$queryI->bindValue(':content_transrequired', $row['content_transrequired'], PDO::PARAM_INT);
//		$queryI->bindValue(':id_piid', $row['id_piid'], PDO::PARAM_INT);
//		$queryI->bindValue(':image', $row['image'], PDO::PARAM_INT);
//		$queryI->bindValue(':contact_alignment', $row['contact_alignment'], PDO::PARAM_STR);
//		$queryI->bindValue(':fixed', $row['fixed'], PDO::PARAM_INT);
//		$queryI->bindValue(':active', $row['active'], PDO::PARAM_INT);
//		$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
//		$queryI->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
//		$queryI->execute();
//		
//		
//		// build tempdate
//		$key = $CONFIG['settings']['formCountry'] . '_' . $CONFIG['settings']['formLanguage'];
//		if(!array_key_exists($key, $aComponentsTempdata)){
//			$aComponentsTempdata[$key] = array();
//			$aComponentsTempdata[$key]['id_temp'] = $CONFIG['page']['id_data'];
//			$aComponentsTempdata[$key]['pages'] = array();
//		}
//		
//		$page_id = 'page_' . $tp . '_' . $row['page'];
//		if(!array_key_exists($page_id, $aComponentsTempdata[$key]['pages'])){
//			$aComponentsTempdata[$key]['pages'][$page_id] = array();
//		}
//		
//		$compid = 'compboxOuter_' . $tpeid;
//		if(!array_key_exists($compid, $aComponentsTempdata[$key]['pages'][$page_id])){
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid] = array();
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['id_tpeid'] = $tpeid;
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['id_caid'] = $varSQL['caid'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['id_tpid'] = $tp;
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['id_tcid'] = $row['id_tcid'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['pageid'] = $tp . '_' . $row['page'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['page'] = $row['page'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['width'] = $row['width'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['height'] = $row['height'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['left'] = $row['position_left'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['top'] = $row['position_top'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['fontsize'] = $row['fontsize'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['fontcolor'] = $row['fontcolor'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['fontstyle'] = $row['fontstyle'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['content'] = $row['content'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['maxchars'] = $row['max_char'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['alignment'] = $row['alignment'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['fixed'] = $row['fixed'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['transrequired'] = $row['content_transrequired'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['transrequired_default'] = ($row['content_transrequired'] == 1) ? $TEXT['yes'] : $TEXT['no'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['editable'] = $row['editable'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['editable_default'] = ($row['editable'] == 1) ? $TEXT['yes'] : $TEXT['no'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['active'] = $row['active'];
//			$aComponentsTempdata[$key]['pages'][$page_id][$compid]['active_default'] = ($row['active'] == 1) ? $TEXT['yes'] : $TEXT['no'];
//		}
//	}
//}
//
//
//
//
//#####################################################################
//// update tempdata
//#####################################################################
//$out = array();
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
//										' . $condTd . '
//									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count, ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang, ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev
//									');
//$queryTd->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
//$queryTd->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
//$queryTd->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
//if($variation == 'local'){
//	$queryTd->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//	$queryTd->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//	$queryTd->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
//}
//$queryTd->execute();
//$rowsTd = $queryTd->fetchAll(PDO::FETCH_ASSOC);
//$numTd = $queryTd->rowCount();
//
//foreach($rowsTd as $rowTd){
//	$aTemddata = json_decode($rowTd['data'], true);
//	$key = $rowTd['id_count'] . '_' . $rowTd['id_lang'];
//	$aTemddata['components'] = json_encode($aComponentsTempdata[$key]);
//	
//	$queryTd2 = $CONFIG['dbconn'][0]->prepare('
//										UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata SET
//											data = (:data)
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:id_count)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:id_lang)
//										LIMIT 1
//										');
//	$queryTd2->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
//	$queryTd2->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
//	$queryTd2->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
//	$queryTd2->bindValue(':id_count', $rowTd['id_count'], PDO::PARAM_INT);
//	$queryTd2->bindValue(':id_lang', $rowTd['id_lang'], PDO::PARAM_INT);
//	$queryTd2->bindValue(':data', json_encode($aTemddata), PDO::PARAM_INT);
//	$queryTd2->execute();
//	$numTd2 = $queryTd2->rowCount();
//	
//	if($CONFIG['settings']['formCountry'] == $rowTd['id_count'] && $CONFIG['settings']['formLanguage'] == $rowTd['id_lang'] && $CONFIG['settings']['formDevice'] == $rowTd['id_dev']){
//		$out = $aComponentsTempdata[$key];
//	}
//}
//
//
//
//
////echo $num;
////			$arr = $query->errorInfo();
////			print_r($arr);
////
//
//
//
//echo json_encode($out);

?>