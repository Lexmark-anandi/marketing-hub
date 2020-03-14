<?php
//function readSystemCountries(){
//	global $CONFIG, $TEXT; 
//
//	getConnection(0); 
//	
//	$aListCountries = array();
//
//	$query = $CONFIG['dbconn']->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count,
//											' . $CONFIG['db'][0]['prefix'] . 'system_countries.country
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_countries 
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_countries.del = (:nultime)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries.active = (:active)
//										');
//	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$query->bindValue(':active', 1, PDO::PARAM_INT);
//	$query->execute();
//	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//	$num = $query->rowCount();
//
//	foreach($rows as $row){
//		$aListCountries[$row['id_sys_count']] = $row['country'];
//	}
//	natcasesort($aListCountries);
//	
//	$aListCountries = array(0 => 'All') + $aListCountries;
//	if($CONFIG['system']['useSysMultiple'] == 0 || $CONFIG['system']['useSysMultipleCountry'] == 0) $aListCountries = array(0 => 'All');
//	
//	return $aListCountries;
//}



//function readSystemCountriesSpecCountry($count=0){
//	global $CONFIG, $TEXT; 
//
//	getConnection(0); 
//	
//	$aListLanguages = array();
//
//	$query = $CONFIG['dbconn']->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count,
//											' . $CONFIG['db'][0]['prefix'] . 'system_countries.country
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_countries 
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_countries.del = (:nultime)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count = (:count)
//										');
//	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$query->bindValue(':count', $count, PDO::PARAM_INT);
//	$query->execute();
//	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//	$num = $query->rowCount();
//
//	foreach($rows as $row){
//		$aListCountries[$row['id_sys_count']] = $row['country'];
//	}
//	natcasesort($aListCountries);
//	
//	$aListCountries = array(0 => 'All') + $aListCountries;
//	if($CONFIG['system']['useSysMultiple'] == 0 || $CONFIG['system']['useSysMultipleCountry'] == 0) $aListCountries = array(0 => 'All');
//	
//	return $aListCountries;
//}




########################################################################################## 
########################################################################################## 




function readSystemLanguages(){
//	global $CONFIG, $TEXT; 
//
//	getConnection(0); 
//	
//	$aListLanguages = array();
//
//	$query = $CONFIG['dbconn']->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang,
//											' . $CONFIG['db'][0]['prefix'] . 'system_languages.language
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_languages 
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_languages.del = (:nultime)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_languages.active = (:active)
//										');
//	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$query->bindValue(':active', 1, PDO::PARAM_INT);
//	$query->execute();
//	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//	$num = $query->rowCount();
//
//	foreach($rows as $row){
//		$aListLanguages[$row['id_sys_lang']] = $row['language'];
//	}
//	natcasesort($aListLanguages);
//	
//	$aListLanguages = array(0 => 'All') + $aListLanguages;
//	if($CONFIG['system']['useSysMultiple'] == 0 || $CONFIG['system']['useSysMultipleLanguage'] == 0) $aListLanguages = array(0 => $TEXT['alllanguages']);
//	
//	return $aListLanguages;
}



//function readSystemLanguagesSpecCountry($count=0){
//	global $CONFIG, $TEXT; 
//
//	getConnection(0); 
//	
//	$aListLanguages = array();
//
//	$query = $CONFIG['dbconn']->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang,
//											' . $CONFIG['db'][0]['prefix'] . 'system_languages.language
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_languages 
//										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages 
//											ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_lang = ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_languages.del = (:nultime)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_languages.active = (:active)
//										');
//	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$query->bindValue(':active', 1, PDO::PARAM_INT);
//	$query->execute();
//	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//	$num = $query->rowCount();
//
//	foreach($rows as $row){
//		$aListLanguages[$row['id_sys_lang']] = $row['language'];
//	}
//	natcasesort($aListLanguages);
//	
//	$aListLanguages = array(0 => $TEXT['alllanguages']) + $aListLanguages;
//	if($CONFIG['system']['useSysMultiple'] == 0 || $CONFIG['system']['useSysMultipleLanguage'] == 0) $aListLanguages = array(0 => $TEXT['alllanguages']);
//	
//	return $aListLanguages;
//}




//function readSystemLanguagesByCountries(){
//	global $CONFIG, $TEXT; 
//
//	getConnection(0); 
//	
//	$aListLanguagesByCountries = array(0 => array(0));
//
//	if($CONFIG['system']['useSysMultiple'] == 1 && $CONFIG['system']['useSysMultipleCountry'] == 1){
//		$query = $CONFIG['dbconn']->prepare('
//											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count
//											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_countries 
//											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_countries.del = (:nultime)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries.active = (:active)
//											');
//		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//		$query->bindValue(':active', 1, PDO::PARAM_INT);
//		$query->execute();
//		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//		$num = $query->rowCount();
//	
//		foreach($rows as $row){
//			$aListLanguagesByCountries[$row['id_sys_count']] = array();
//			$aListLanguagesByCountries[$row['id_sys_count']][] = 0;
//	
//			if($CONFIG['system']['useSysMultipleLanguage'] == 1){
//				$query2 = $CONFIG['dbconn']->prepare('
//													SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang
//													FROM ' . $CONFIG['db'][0]['prefix'] . 'system_languages 
//													INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages 
//														ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_lang = ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang
//													WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_languages.del = (:nultime)
//														AND ' . $CONFIG['db'][0]['prefix'] . 'system_languages.active = (:active)
//														AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.del = (:nultime)
//														AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_count = (:count)
//													ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_languages.language
//													');
//				$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//				$query2->bindValue(':count', $row['id_sys_count'], PDO::PARAM_INT);
//				$query2->bindValue(':active', 1, PDO::PARAM_INT);
//				$query2->execute();
//				$rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
//				$num2 = $query2->rowCount();
//			
//				foreach($rows2 as $row2){
//					$aListLanguagesByCountries[$row['id_sys_count']][] = $row2['id_sys_lang'];
//				}
//			}
//		}
//	}
//	
//	return $aListLanguagesByCountries;
//}




//function readSystemLanguagesByCountriesSpecCountry($count=0){
//	global $CONFIG, $TEXT; 
//
//	getConnection(0); 
//	
//	$aListLanguagesByCountries = array(0 => array(0));
//
//	if($CONFIG['system']['useSysMultiple'] == 1 && $CONFIG['system']['useSysMultipleCountry'] == 1){
//		$query = $CONFIG['dbconn']->prepare('
//											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count
//											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_countries 
//											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_countries.del = (:nultime)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries.active = (:active)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count = (:count)
//											');
//		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//		$query->bindValue(':count', $count, PDO::PARAM_INT);
//		$query->bindValue(':active', 1, PDO::PARAM_INT);
//		$query->execute();
//		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//		$num = $query->rowCount();
//	
//		foreach($rows as $row){
//			$aListLanguagesByCountries[$row['id_sys_count']] = array();
//			$aListLanguagesByCountries[$row['id_sys_count']][] = 0;
//	
//			if($CONFIG['system']['useSysMultipleLanguage'] == 1){
//				$query2 = $CONFIG['dbconn']->prepare('
//													SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang
//													FROM ' . $CONFIG['db'][0]['prefix'] . 'system_languages 
//													INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages 
//														ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_lang = ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang
//													WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_languages.del = (:nultime)
//														AND ' . $CONFIG['db'][0]['prefix'] . 'system_languages.active = (:active)
//														AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.del = (:nultime)
//														AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_count = (:count)
//													ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_languages.language
//													');
//				$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//				$query2->bindValue(':count', $row['id_sys_count'], PDO::PARAM_INT);
//				$query2->bindValue(':active', 1, PDO::PARAM_INT);
//				$query2->execute();
//				$rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
//				$num2 = $query2->rowCount();
//			
//				foreach($rows2 as $row2){
//					$aListLanguagesByCountries[$row['id_sys_count']][] = $row2['id_sys_lang'];
//				}
//			}
//		}
//	}
//	
//	return $aListLanguagesByCountries;
//}




########################################################################################## 
########################################################################################## 




//function readSystemDevices(){
//	global $CONFIG, $TEXT; 
//
//	getConnection(0); 
//	
//	$aListDevices = array();
//
//	$query = $CONFIG['dbconn']->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_devices.id_sys_dev,
//											' . $CONFIG['db'][0]['prefix'] . 'system_devices.device
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_devices 
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_devices.del = (:nultime)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_devices.active = (:active)
//										');
//	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$query->bindValue(':active', 1, PDO::PARAM_INT);
//	$query->execute();
//	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//	$num = $query->rowCount();
//
//	foreach($rows as $row){
//		$aListDevices[$row['id_sys_dev']] = $row['device'];
//	}
//	natcasesort($aListDevices);
//	
//	$aListDevices = array(0 => $TEXT['alldevices']) + $aListDevices;
//	
//	if($CONFIG['system']['useSysMultiple'] == 0 || $CONFIG['system']['useSysMultipleDevice'] == 0) $aListDevices = array(0 => $TEXT['alldevices']);
//	
//	return $aListDevices;
//}




?>