<?php

$aSaveVersions = array();
$aListLanguagesByCountries = (substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 3, 1) == 9) ? readLanguagesByCountries($varSQL['modul']) : readLanguagesByCountriesSpecCountry($varSQL['modul'], 0);
$aListDevices = readDevices($varSQL['modul']);

foreach($aListLanguagesByCountries as $id_count => $aListLanguages){
	foreach($aListLanguages as $id_lang){
		foreach($aListDevices as $id_dev => $device){
			array_push($aSaveVersions, array((int)$id_count, (int)$id_lang, (int)$id_dev));
		}
	}
}

$useCount = 0;
$useLang = 0;
$useDev = 0;
foreach($aSaveVersions as $aVersion){
	if($aVersion[0] != 0) $useCount = 1; 
	if($aVersion[1] != 0) $useLang = 1; 
	if($aVersion[2] != 0) $useDev = 1; 
}

foreach($aSaveVersions as $aVersion){
	if($aVersion[0] == 0){
		$codeCount = 'all';
	}else{
		$query = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_countries.code
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_countries 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_countries.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count = (:count)
											');
		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$query->bindValue(':count', $aVersion[0], PDO::PARAM_INT);
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		$num = $query->rowCount();
		if($num > 0) $codeCount = $rows[0]['code'];
	}

	if($aVersion[1] == 0){
		$codeLang = 'all';
	}else{
		$query = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_languages.code
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_languages 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_languages.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang = (:lang)
											');
		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$query->bindValue(':lang', $aVersion[1], PDO::PARAM_INT);
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		$num = $query->rowCount();
		if($num > 0) $codeLang = $rows[0]['code'];
	}

	if($aVersion[0] == 0){
		$codeDev = 'all';
	}else{
		$query = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_devices.device as code
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_devices 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_devices.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_devices.id_sys_dev = (:dev)
											');
		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$query->bindValue(':dev', $aVersion[2], PDO::PARAM_INT);
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		$num = $query->rowCount();
		if($num > 0) $codeDev = $rows[0]['code'];
	}
	
	
	$terms = array();
	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.var,
											' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.term
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni 
	
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_count = (:count)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_lang = (:lang)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_dev = (:dev)
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':count', $aVersion[0], PDO::PARAM_INT);
	$query->bindValue(':lang', $aVersion[1], PDO::PARAM_INT);
	$query->bindValue(':dev', $aVersion[2], PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	
	foreach($rows as $row){
		$terms[$row['var']] = ($row['term'] == NULL) ? '' : $row['term'];
	}
	$storage_terms = gzcompress(serialize($terms));
	$storage_terms = gzcompress(serialize($terms));
	
	
	$filename = '';
	if($aVersion[0] == 0 && $aVersion[1] == 0 && $aVersion[2] == 0){
		$filename = 'all';
	}else{
		if($useCount == 0 && $useLang == 0 && $useDev == 0) $filename = 'all';
		if($useCount == 0 && $useLang == 0 && $useDev == 1) $filename = strtolower($codeDev);
		if($useCount == 0 && $useLang == 1 && $useDev == 0) $filename = strtolower($codeLang);
		if($useCount == 1 && $useLang == 0 && $useDev == 0) $filename = strtolower($codeCount);
		if($useCount == 0 && $useLang == 1 && $useDev == 1) $filename = strtolower($codeLang) . '_' . strtolower($codeDev);
		if($useCount == 1 && $useLang == 0 && $useDev == 1) $filename = strtolower($codeCount) . '_' . strtolower($codeDev);
		if($useCount == 1 && $useLang == 1 && $useDev == 0) $filename = strtolower($codeCount) . '_' . strtolower($codeLang);
		if($useCount == 1 && $useLang == 1 && $useDev == 1) $filename = strtolower($codeCount) . '_' . strtolower($codeLang) . '_' . strtolower($codeDev);
	}
	$filename .= '.lang';
	
	$handle = fopen($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathAdmin'] . 'i18n/' . $filename, 'w');
	fwrite ($handle, $storage_terms);
	fclose ($handle);
	
}


?>