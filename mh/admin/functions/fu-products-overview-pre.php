<?php
$specialCondition = 'AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.status IN ("Public", "Not Public - B2B") ';


//$aArgsLV = array();
//$aArgsLV['type'] = 'all';
//$aLocalVersions = localVariationsBuild($aArgsLV);
//
//$useCount = 0;
//$useLang = 0;
//$useDev = 0;
//foreach($aLocalVersions as $aVersion){
//	if($aVersion[0] != 0) $useCount = 1; 
//	if($aVersion[1] != 0) $useLang = 1; 
//	if($aVersion[2] != 0) $useDev = 1; 
//}
//
//
//foreach($aLocalVersions as $aVersion){
//	###########################################
//	// build codes
//	###########################################
//	// code country
//	if($aVersion[0] == 0){
//		$codeCount = 'all';
//	}else{
//		$query = $CONFIG['dbconn'][0]->prepare('
//											SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code
//											FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni 
//											WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = (:count)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
//											LIMIT 1
//											');
//		$query->bindValue(':count', $aVersion[0], PDO::PARAM_INT);
//		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//		$query->execute();
//		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//		$num = $query->rowCount();
//		if($num > 0) $codeCount = $rows[0]['code'];
//	}
//	
//	// code language
//	if($aVersion[1] == 0){
//		$codeLang = 'all';
//	}else{
//		$query = $CONFIG['dbconn'][0]->prepare('
//											SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code
//											FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni 
//											WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = (:lang)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
//											LIMIT 1
//											');
//		$query->bindValue(':lang', $aVersion[1], PDO::PARAM_INT);
//		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//		$query->execute();
//		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//		$num = $query->rowCount();
//		if($num > 0) $codeLang = $rows[0]['code'];
//	}
//	
//	// code device
//	if($aVersion[2] == 0){
//		$codeDev = 'all';
//	}else{
//		$query = $CONFIG['dbconn'][0]->prepare('
//											SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_devices.device AS code
//											FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_devices 
//											WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_devices.id_sys_dev = (:dev)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices.del = (:nultime)
//											LIMIT 1
//											');
//		$query->bindValue(':dev', $aVersion[2], PDO::PARAM_INT);
//		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//		$query->execute();
//		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//		$num = $query->rowCount();
//		if($num > 0) $codeDev = $rows[0]['code'];
//	}
//	
//	###########################################
//	// build terms
//	###########################################
//	$terms = array();
//	$query = $CONFIG['dbconn'][0]->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.var,
//											' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.term
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni 
//	
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_count = (:count)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_lang = (:lang)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_dev = (:dev)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_cl IN (0,' . $CONFIG['activeSettings']['id_clid'] . ')
//											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.del = (:nultime)
//										');
//	$query->bindValue(':count', $aVersion[0], PDO::PARAM_INT);
//	$query->bindValue(':lang', $aVersion[1], PDO::PARAM_INT);
//	$query->bindValue(':dev', $aVersion[2], PDO::PARAM_INT);
//	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$query->execute();
//	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//	$num = $query->rowCount();
//	
//	foreach($rows as $row){
//		$terms[$row['var']] = ($row['term'] == NULL) ? '' : nl2br(trim($row['term']));
//	}
//	$storage_terms = gzcompress(serialize($terms));
//	$storage_terms = gzcompress(serialize($terms));
//	
//	$filename = '';
//	if($aVersion[0] == 0 && $aVersion[1] == 0 && $aVersion[2] == 0){
//		$filename = 'all';
//	}else{
//		if($useCount == 0 && $useLang == 0 && $useDev == 0) $filename = 'all';
//		if($useCount == 0 && $useLang == 0 && $useDev == 1) $filename = strtolower($codeDev);
//		if($useCount == 0 && $useLang == 1 && $useDev == 0) $filename = strtolower($codeLang);
//		if($useCount == 1 && $useLang == 0 && $useDev == 0) $filename = strtolower($codeCount);
//		if($useCount == 0 && $useLang == 1 && $useDev == 1) $filename = strtolower($codeLang) . '_' . strtolower($codeDev);
//		if($useCount == 1 && $useLang == 0 && $useDev == 1) $filename = strtolower($codeCount) . '_' . strtolower($codeDev);
//		if($useCount == 1 && $useLang == 1 && $useDev == 0) $filename = strtolower($codeCount) . '_' . strtolower($codeLang);
//		if($useCount == 1 && $useLang == 1 && $useDev == 1) $filename = strtolower($codeCount) . '_' . strtolower($codeLang) . '_' . strtolower($codeDev);
//	}
//	$filename .= '.lang';
//	
//	$handle = fopen($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathApp'] . 'i18n/' . $filename, 'w');
//	fwrite ($handle, $storage_terms);
//	fclose ($handle);
//}
//

?>