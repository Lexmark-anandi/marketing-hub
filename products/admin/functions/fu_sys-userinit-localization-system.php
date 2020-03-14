<?php
###########################################################################
// Settings for countries, languages and devices for system
###########################################################################
// Array for countries
$CONFIG_TMP['USER']['syscountries'] = array();
//if($aTokenContent['user']['right_editallcountries'] == 9){
	$CONFIG_TMP['USER']['syscountries'][0] = array();
	
	$CONFIG_TMP['USER']['syscountries'][0]['id_sys_count'] = 0;
	$CONFIG_TMP['USER']['syscountries'][0]['country'] = 'allcountries';
	$CONFIG_TMP['USER']['syscountries'][0]['code'] = 'all';
//}
//	
////	$extQuery = ',
////		' . $CONFIG['db'][0]['prefix'] . 'system_countries.country_org
//$extQuery = ',
//	' . $CONFIG['db'][0]['prefix'] . 'system_countries.code
//	';
//$queryC = $CONFIG['dbconn']->prepare('
//									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count,
//											' . $CONFIG['db'][0]['prefix'] . 'system_countries.country
//											' . $extQuery . '
//									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_countries
//									
//									INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages
//										ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count = ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_count
//									
//									INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2syscountries2syslanguages
//										ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_count2sys_lang = ' . $CONFIG['db'][0]['prefix'] . 'system_user2syscountries2syslanguages.id_sys_count2sys_lang
//																								
//									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user2syscountries2syslanguages.id_uid = (:id_uid)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries.del = (:nultime)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries.active = (:active)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.del = (:nultime)
//									GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count
//									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_countries.country
//									');
//$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//$queryC->bindValue(':active', 1, PDO::PARAM_INT);
//$queryC->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);
//
//if($aTokenContent['user']['right_country'] == 1){
//	$queryC = $CONFIG['dbconn']->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count,
//											' . $CONFIG['db'][0]['prefix'] . 'system_countries.country
//											' . $extQuery . '
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_countries
//										
//										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages
//											ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count = ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_count
//										
//										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2syscountries2syslanguages
//											ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_count2sys_lang = ' . $CONFIG['db'][0]['prefix'] . 'system_user2syscountries2syslanguages.id_sys_count2sys_lang
//																									
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user2syscountries2syslanguages.id_uid = (:id_uid)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries.del = (:nultime)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.del = (:nultime)
//										GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count
//										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_countries.country
//										');
//	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$queryC->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);
//}
//
//if($aTokenContent['user']['right_country'] == 2){
//	$queryC = $CONFIG['dbconn']->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count,
//											' . $CONFIG['db'][0]['prefix'] . 'system_countries.country
//											' . $extQuery . '
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_countries
//										
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_countries.del = (:nultime)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries.active = (:active)
//										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_countries.country
//										');
//	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$queryC->bindValue(':active', 1, PDO::PARAM_INT);
//}
//
//if($aTokenContent['user']['right_country'] == 9){
	$queryC = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count,
											' . $CONFIG['db'][0]['prefix'] . 'system_countries.country
											' . $extQuery . '
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_countries
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_countries.del = (:nultime)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_countries.country
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//}

$queryC->execute();
$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
$numC = $queryC->rowCount();
$nC = 0;

foreach($rowsC as $datC){
	$CONFIG_TMP['USER']['syscountries'][$datC['id_sys_count']] = array();
	
	foreach($datC as $key=>$val){
		$CONFIG_TMP['USER']['syscountries'][$datC['id_sys_count']][$key] = $val;
	}
}
###########################################################################




###########################################################################
// Array for languages
$CONFIG_TMP['USER']['syslanguages'] = array();
//if($aTokenContent['user']['right_editalllanguages'] == 9){
	$CONFIG_TMP['USER']['syslanguages'][0] = array();
	
	$CONFIG_TMP['USER']['syslanguages'][0]['id_sys_lang'] = 0;
	$CONFIG_TMP['USER']['syslanguages'][0]['language'] = 'alllanguages';
	$CONFIG_TMP['USER']['syslanguages'][0]['code'] = 'all';
//}
//	
////	$extQuery = ',
////		' . $CONFIG['db'][0]['prefix'] . 'system_languages.language_org
//$extQuery = ',
//	' . $CONFIG['db'][0]['prefix'] . 'system_languages.code
//';
foreach($CONFIG_TMP['USER']['syscountries'] as $id_count => $val){
	$CONFIG_TMP['USER']['syscountries'][$id_count]['languages'] = array();
//	if($id_count == 0 && $aTokenContent['user']['right_editalllanguages'] == 9){
		array_push($CONFIG_TMP['USER']['syscountries'][$id_count]['languages'], 0);  
//	}
//	
//	$queryC = $CONFIG['dbconn']->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang,
//											' . $CONFIG['db'][0]['prefix'] . 'system_languages.language
//											' . $extQuery . '
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_languages
//										
//										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages
//											ON ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang = ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_lang
//										
//										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2syscountries2syslanguages
//											ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_count2sys_lang = ' . $CONFIG['db'][0]['prefix'] . 'system_user2syscountries2syslanguages.id_sys_count2sys_lang
//												
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user2syscountries2syslanguages.id_uid = (:id_uid)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_languages.del = (:nultime)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_languages.active = (:active)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_count = (:id_sys_count)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.del = (:nultime)
//										GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang
//										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_languages.language
//										');
//	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$queryC->bindValue(':active', 1, PDO::PARAM_INT);
//	$queryC->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);
//	$queryC->bindValue(':id_sys_count', $id_count, PDO::PARAM_INT);
//	
//	if($aTokenContent['user']['right_language'] == 1){
//		$queryC = $CONFIG['dbconn']->prepare('
//											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang,
//												' . $CONFIG['db'][0]['prefix'] . 'system_languages.language
//												' . $extQuery . '
//											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_languages
//											
//											INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages
//												ON ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang = ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_lang
//											
//											INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2syscountries2syslanguages
//												ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_count2sys_lang = ' . $CONFIG['db'][0]['prefix'] . 'system_user2syscountries2syslanguages.id_sys_count2sys_lang
//													
//											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user2syscountries2syslanguages.id_uid = (:id_uid)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'system_languages.del = (:nultime)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_count = (:id_sys_count)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.del = (:nultime)
//											GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_count
//											ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_languages.language
//											');
//		$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//		$queryC->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);
//		$queryC->bindValue(':id_sys_count', $id_count, PDO::PARAM_INT);
//	}
//
//	if($aTokenContent['user']['right_language'] == 2){
//		$queryC = $CONFIG['dbconn']->prepare('
//											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang,
//												' . $CONFIG['db'][0]['prefix'] . 'system_languages.language
//												' . $extQuery . '
//											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_languages
//											
//											INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages
//												ON ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang = ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_lang
//											
//											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_languages.del = (:nultime)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'system_languages.active = (:active)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_count = (:id_sys_count)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.del = (:nultime)
//											ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_languages.language
//											');
//		$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//		$queryC->bindValue(':active', 1, PDO::PARAM_INT);
//		$queryC->bindValue(':id_sys_count', $id_count, PDO::PARAM_INT);
//	}
//	
//	if($aTokenContent['user']['right_language'] == 9){
		$queryC = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang,
												' . $CONFIG['db'][0]['prefix'] . 'system_languages.language
												' . $extQuery . '
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_languages
											
											INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang = ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_lang
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_languages.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_count = (:id_sys_count)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.del = (:nultime)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_languages.language
											');
		$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryC->bindValue(':id_sys_count', $id_count, PDO::PARAM_INT);
//	}

	$queryC->execute();
	$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
	$numC = $queryC->rowCount();
	$nC = 0;

	foreach($rowsC as $datC){
		array_push($CONFIG_TMP['USER']['syscountries'][$id_count]['languages'], $datC['id_sys_lang']);  

		if(!array_key_exists($datC['id_sys_lang'], $CONFIG_TMP['USER']['syslanguages'])){
			$CONFIG_TMP['USER']['syslanguages'][$datC['id_sys_lang']] = array();
			
			foreach($datC as $key=>$val){
				$CONFIG_TMP['USER']['syslanguages'][$datC['id_sys_lang']][$key] = $val;
			}
		}
	}
}
###########################################################################




###########################################################################
// Array for devices
//$CONFIG_TMP['USER']['sysdevices'] = array();
//if($aTokenContent['user']['right_editalldevices'] == 9){
	$CONFIG_TMP['USER']['sysdevices'][0] = array();
	
	$CONFIG_TMP['USER']['sysdevices'][0]['id_sys_dev'] = 0;
	$CONFIG_TMP['USER']['sysdevices'][0]['device'] = 'alldevices';
	$CONFIG_TMP['USER']['sysdevices'][0]['code'] = 'all';
//}
//	
////	$extQuery = ',
////		' . $CONFIG['db'][0]['prefix'] . 'system_devices.device_org,
//$extQuery = ',
//	' . $CONFIG['db'][0]['prefix'] . 'system_devices.code
//';
//$queryC = $CONFIG['dbconn']->prepare('
//									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_devices.id_sys_dev,
//										' . $CONFIG['db'][0]['prefix'] . 'system_devices.device
//										' . $extQuery . '
//									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_devices
//											
//									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_devices.del = (:nultime)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'system_devices.active = (:active)
//									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_devices.device
//									');
//$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//$queryC->bindValue(':active', 1, PDO::PARAM_INT);
//
//if($aTokenContent['user']['right_device'] == 1){
//	$queryC = $CONFIG['dbconn']->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_devices.id_sys_dev,
//											' . $CONFIG['db'][0]['prefix'] . 'system_devices.device
//											' . $extQuery . '
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_devices
//												
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_devices.del = (:nultime)
//										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_devices.device
//										');
//	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//}
//
//if($aTokenContent['user']['right_device'] == 2){
//	$queryC = $CONFIG['dbconn']->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_devices.id_sys_dev,
//											' . $CONFIG['db'][0]['prefix'] . 'system_devices.device
//											' . $extQuery . '
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_devices
//												
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_devices.del = (:nultime)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_devices.active = (:active)
//										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_devices.device
//										');
//	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$queryC->bindValue(':active', 1, PDO::PARAM_INT);
//}
//
//if($aTokenContent['user']['right_device'] == 9){
	$queryC = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_devices.id_sys_dev,
											' . $CONFIG['db'][0]['prefix'] . 'system_devices.device
											' . $extQuery . '
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_devices
												
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_devices.del = (:nultime)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_devices.device
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//}

$queryC->execute();
$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
$numC = $queryC->rowCount();
$nC = 0;

foreach($rowsC as $datC){
	$CONFIG_TMP['USER']['sysdevices'][$datC['id_sys_dev']] = array();
	
	foreach($datC as $key=>$val){
		$CONFIG_TMP['USER']['sysdevices'][$datC['id_sys_dev']][$key] = $val;
	}
}
###########################################################################
?>