<?php
function localVariationsBuild($aArgs = array()){ 
	global $CONFIG, $TEXT; 
	
	if(!isset($aArgs['type'])) $aArgs['type'] = 'temp';
	
	$aLocalVersions = array();
	$aCountries = array();
	$aLanguages = array();
	$aDevices = array();

	if($aArgs['type'] == 'sysall'){
		$aDevices = array(0);
//		$query = $CONFIG['dbconn'][0]->prepare('
//											SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_devid
//											FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni 
//											
//											WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.del = (:nultime)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_count = (:nul)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_lang = (:nul)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_dev = (:nul)
//											');
//		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//		$query->bindValue(':nul', 0, PDO::PARAM_INT);
//		$query->execute();
//		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//		$num = $query->rowCount();
//		foreach($rows as $row){
//			array_push($aDevices, $row['id_devid']);
//		}

		$query = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code_spec AS code_count,
												' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid,
												' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code_spec AS code_lang,
												' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid
											FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni 
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
		
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)

												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.del = (:nultime)
											');
		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$query->bindValue(':nul', 0, PDO::PARAM_INT);
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		$num = $query->rowCount();

		array_push($aLocalVersions, array(0,0,0));
		foreach($rows as $row){
			foreach($aDevices as $id_dev){
				array_push($aLocalVersions, array(intval($row['id_countid']), intval($row['id_langid']), intval($id_dev)));
			}
		}

	}else{
		
		$aCountriesSrc = ($CONFIG['aModul']['specifications'][9] == 9) ? $CONFIG['user']['syscountries'] : $CONFIG['user']['countries'];
		$aLanguagesSrc = ($CONFIG['aModul']['specifications'][9] == 9) ? $CONFIG['user']['syslanguages'] : $CONFIG['user']['languages'];
		$aDevicesSrc = ($CONFIG['aModul']['specifications'][9] == 9) ? $CONFIG['user']['sysdevices'] : $CONFIG['user']['devices'];
		
		if((isset($CONFIG['page']['id_data']) && $CONFIG['page']['id_data'] != 0) || $aArgs['type'] == 'all'){
			if($CONFIG['aModul']['specifications'][0] == 0) $aCountries = array(0);
			if($CONFIG['aModul']['specifications'][0] == 1) $aCountries = array($CONFIG['settings']['formCountry']);
			if($CONFIG['aModul']['specifications'][0] == 2){
				//if(array_key_exists(0, $aCountriesSrc)) array_push($aCountries, 0);
				array_push($aCountries, $CONFIG['settings']['formCountry']);
			}
			if($CONFIG['aModul']['specifications'][0] == 9) $aCountries = array_keys($aCountriesSrc);
			if($CONFIG['aModul']['specifications'][0] == 9 && $CONFIG['aModul']['specifications'][3] == 0 && in_array(0, $aCountries)) unset($aCountries[array_search(0,$aCountries)]);
			
			if($CONFIG['aModul']['specifications'][1] == 0) $aLanguages = array(0);
			if($CONFIG['aModul']['specifications'][1] == 1) $aLanguages = array($CONFIG['settings']['formLanguage']);
			if($CONFIG['aModul']['specifications'][1] == 9) $aLanguages = array_keys($aLanguagesSrc);
			if($CONFIG['aModul']['specifications'][1] == 9 && $CONFIG['aModul']['specifications'][4] == 0 && in_array(0, $aLanguages)) unset($aLanguages[array_search(0,$aLanguages)]);
			
			if($CONFIG['aModul']['specifications'][2] == 0) $aDevices = array(0);
			if($CONFIG['aModul']['specifications'][2] == 1) $aDevices = array($CONFIG['settings']['formDevice']);
			if($CONFIG['aModul']['specifications'][2] == 9) $aDevices = array_keys($aDevicesSrc);
			if($CONFIG['aModul']['specifications'][2] == 9 && $CONFIG['aModul']['specifications'][5] == 0 && in_array(0, $aDevices)) unset($aDevices[array_search(0,$aDevices)]);
		}else{
			$aCountries = array($CONFIG['settings']['formCountry']);
			$aLanguages = array($CONFIG['settings']['formLanguage']);
			$aDevices = array($CONFIG['settings']['formDevice']);
		}
			if($CONFIG['aModul']['specifications'][0] == 2){
				array_push($aLocalVersions, array(0,0,0));
			}
		foreach($aCountries as $id_count){
			if((isset($CONFIG['page']['id_data']) && $CONFIG['page']['id_data'] != 0) || $aArgs['type'] == 'all'){
				if($CONFIG['aModul']['specifications'][1] == 8) $aLanguages = $aCountriesSrc[$id_count]['languages'];
				if($CONFIG['aModul']['specifications'][1] == 8 && $CONFIG['aModul']['specifications'][4] == 0 && in_array(0, $aLanguages)) unset($aLanguages[array_search(0,$aLanguages)]);
			}
			
			foreach($aLanguages as $id_lang){
				foreach($aDevices as $id_dev){
					array_push($aLocalVersions, array(intval($id_count), intval($id_lang), intval($id_dev)));
				}
			}
		}
	}
	
	return $aLocalVersions;
}
?>