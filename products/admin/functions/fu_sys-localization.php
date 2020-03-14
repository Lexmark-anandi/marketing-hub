<?php
#############################################################
// returns arrays for saving _uni (including "all")
#############################################################
function readCountries($modul){
	global $CONFIG, $TEXT; 
	
	$aListCountries = array(0 => $TEXT['allcountries']);
	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:cl)
												OR ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:nul))
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':nul', 0, PDO::PARAM_INT);
	$query->bindValue(':cl', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
	
	if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 6, 1) == 9){
		$query = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count as id_countid,
												' . $CONFIG['db'][0]['prefix'] . 'system_countries.country
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_countries 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_countries.del = (:nultime)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count
											');
		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}
	
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();

	foreach($rows as $row){
		if(($CONFIG['system']['useMultiple'] == 1 && substr($CONFIG['page']['moduls'][$modul]['specifics'], 0, 1) == 9) || $row['id_countid'] == $CONFIG['page']['moduls'][$modul]['formCountry']){
			$aListCountries[$row['id_countid']] = $row['country'];
		}
	}
	
	return $aListCountries;
}



function readCountriesSpecCountry($modul, $count=0){
//	global $CONFIG, $TEXT; 
//
//	getConnection(0); 
//	
//	$aListLanguages = array();
//	$query = $CONFIG['dbconn']->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid,
//											' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni 
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = (:count)
//											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:cl)
//												OR ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:nul))
//										');
//	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$query->bindValue(':nul', 0, PDO::PARAM_INT);
//	$query->bindValue(':count', $count, PDO::PARAM_INT);
//	$query->bindValue(':cl', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
//	$query->execute();
//	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//	$num = $query->rowCount();
//
//	foreach($rows as $row){
//		$aListCountries[$row['id_countid']] = $row['country'];
//	}
//	natcasesort($aListCountries);
//	
//	//$aListCountries = array(0 => $TEXT['allcountries']) + $aListCountries;
//	if($CONFIG['system']['useMultiple'] == 0 || substr($CONFIG['page']['moduls'][$modul]['specifics'], 3, 1) == 0) $aListCountries = array(0 => $TEXT['allcountries']);
//	
//	return $aListCountries;
}




########################################################################################## 
########################################################################################## 




function readLanguages($modul){
//	global $CONFIG, $TEXT; 
//
//	getConnection(0); 
//	
//	$aListLanguages = array();
//	$query = $CONFIG['dbconn']->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid,
//											' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni 
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
//											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:cl)
//												OR ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:nul))
//										');
//	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$query->bindValue(':nul', 0, PDO::PARAM_INT);
//	$query->bindValue(':cl', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
//	$query->execute();
//	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//	$num = $query->rowCount();
//
//	foreach($rows as $row){
//		$aListLanguages[$row['id_langid']] = $row['language'];
//	}
//	natcasesort($aListLanguages);
//	
//	//$aListLanguages = array(0 => $TEXT['alllanguages']) + $aListLanguages;
//	if($CONFIG['system']['useMultiple'] == 0 || substr($CONFIG['page']['moduls'][$modul]['specifics'], 4, 1) == 0) $aListLanguages = array(0 => $TEXT['alllanguages']);
//	
//	return $aListLanguages;
}



function readLanguagesSpecCountry($modul, $count=0){
	global $CONFIG, $TEXT; 

	$aListLanguages = array(0 => $TEXT['alllanguages']);
	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni 
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages 
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:cl)
												OR ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:nul))
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid = (:count)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid <> (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid <> (:nul)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':nul', 0, PDO::PARAM_INT);
	$query->bindValue(':cl', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
	$query->bindValue(':count', $count, PDO::PARAM_INT);
	
	if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 6, 1) == 9){
		$query = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang as id_langid,
												' . $CONFIG['db'][0]['prefix'] . 'system_languages.language
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_languages 
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages 
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_lang = ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_languages.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_count = (:count)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_lang <> (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_count <> (:nul)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang
											');
		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$query->bindValue(':nul', 0, PDO::PARAM_INT);
		$query->bindValue(':count', $count, PDO::PARAM_INT);
	}
	
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();

	foreach($rows as $row){
		if(($CONFIG['system']['useMultiple'] == 1 && substr($CONFIG['page']['moduls'][$modul]['specifics'], 1, 1) == 9) || $row['id_langid'] == $CONFIG['page']['moduls'][$modul]['formLanguage']){
			$aListLanguages[$row['id_langid']] = $row['language'];
		}
	}
	
	return $aListLanguages;
}




function readLanguagesByCountries($modul){
	global $CONFIG, $TEXT; 
	
	$aListLanguagesByCountries = array(0 => array(0));
	
	if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 0, 1) == 0 && substr($CONFIG['page']['moduls'][$modul]['specifics'], 1, 1) == 9){
		$aListLanguagesByCountries = readLanguagesByCountriesSpecCountry($modul, 0);
	}
	
	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:cl)
												OR ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:nul))
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':nul', 0, PDO::PARAM_INT);
	$query->bindValue(':cl', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
	
	if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 6, 1) == 9){
		$query = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count as id_countid,
												' . $CONFIG['db'][0]['prefix'] . 'system_countries.country
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_countries 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_countries.del = (:nultime)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count
											');
		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}
	
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();

	foreach($rows as $row){
		if(
			($CONFIG['system']['useMultiple'] == 1 && substr($CONFIG['page']['moduls'][$modul]['specifics'], 0, 1) == 9) 
			|| $row['id_countid'] == $CONFIG['page']['moduls'][$modul]['formCountry']
		){
			
			$aListLanguagesByCountries[$row['id_countid']] = array();
			if(($CONFIG['system']['useMultiple'] == 1 && substr($CONFIG['page']['moduls'][$modul]['specifics'], 1, 1) == 0)){
				array_push($aListLanguagesByCountries[$row['id_countid']], 0);
			}



			$query2 = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid
												FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni 
												INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages 
													ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
													AND (' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:cl)
														OR ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:nul))
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid = (:count)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid <> (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid <> (:nul)
												ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
												');
			$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query2->bindValue(':nul', 0, PDO::PARAM_INT);
			$query2->bindValue(':cl', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
			$query2->bindValue(':count', $row['id_countid'], PDO::PARAM_INT);
			
			if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 6, 1) == 9){
				$query2 = $CONFIG['dbconn']->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang as id_langid
													FROM ' . $CONFIG['db'][0]['prefix'] . 'system_languages 
													INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages 
														ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_lang = ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang
													WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_languages.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_count = (:count)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_lang <> (:nul)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_count <> (:nul)
													ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang
													');
				$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$query2->bindValue(':nul', 0, PDO::PARAM_INT);
				$query2->bindValue(':count', $row['id_countid'], PDO::PARAM_INT);
			}
	
			$query2->execute();
			$rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
			$num2 = $query2->rowCount();
		
			foreach($rows2 as $row2){
				if(($CONFIG['system']['useMultiple'] == 1 && substr($CONFIG['page']['moduls'][$modul]['specifics'], 1, 1) == 9) || $row2['id_langid'] == $CONFIG['page']['moduls'][$modul]['formLanguage']){
					$aListLanguagesByCountries[$row['id_countid']][] = $row2['id_langid'];
				}
			}
		}
	}
	
	return $aListLanguagesByCountries;
}




function readLanguagesByCountriesSpecCountry($modul, $count=0){
	global $CONFIG, $TEXT; 
	
	$aListLanguagesByCountries = array($count => array(0));
	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni 
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages 
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:cl)
												OR ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:nul))
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid = (:count)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid <> (:nul)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':nul', 0, PDO::PARAM_INT);
	$query->bindValue(':cl', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
	$query->bindValue(':count', $count, PDO::PARAM_INT);
	
	if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 6, 1) == 9){
		$query = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang as id_langid,
												' . $CONFIG['db'][0]['prefix'] . 'system_languages.language
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_languages 
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages 
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_lang = ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_languages.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_count = (:count)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_lang <> (:nul)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang
											');
		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$query->bindValue(':nul', 0, PDO::PARAM_INT);
		$query->bindValue(':count', $count, PDO::PARAM_INT);
	}
	
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();

	foreach($rows as $row){
		if(($CONFIG['system']['useMultiple'] == 1 && substr($CONFIG['page']['moduls'][$modul]['specifics'], 1, 1) == 9) || $row['id_langid'] == $CONFIG['page']['moduls'][$modul]['formLanguage']){
			$aListLanguagesByCountries[$count][] = $row['id_langid'];
		}
	}
	return $aListLanguagesByCountries;
}




########################################################################################## 
########################################################################################## 




function readDevices($modul){
	global $CONFIG, $TEXT; 
	
	$aListDevices = array(0 => $TEXT['alldevices']);
	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_devid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.device
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_dev = (:nul)
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_clid = (:cl)
												OR ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_clid = (:nul))
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_devid
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':nul', 0, PDO::PARAM_INT);
	$query->bindValue(':cl', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
	
	if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 6, 1) == 9){
		$query = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_devices.id_sys_dev as id_devid,
												' . $CONFIG['db'][0]['prefix'] . 'system_devices.country
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_devices 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_devices.del = (:nultime)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_devices.id_sys_dev
											');
		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}
	
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();

	foreach($rows as $row){
		if(($CONFIG['system']['useMultiple'] == 1 && substr($CONFIG['page']['moduls'][$modul]['specifics'], 2, 1) == 9) || $row['id_devid'] == $CONFIG['page']['moduls'][$modul]['formDevice']){
			$aListDevices[$row['id_devid']] = $row['device'];
		}
	}
	
	return $aListDevices;
}




?>