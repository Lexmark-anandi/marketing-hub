<?php
###########################################################################
// Settings for countries, languages and devices for system
###########################################################################
// Array for countries
$CONFIG_TMP['user']['syscountries'] = array();
$extQuery = ',
	' . $CONFIG['db'][0]['prefix'] . 'system_format_date.format AS date_format,
	' . $CONFIG['db'][0]['prefix'] . 'system_format_date.code AS date_code,
	' . $CONFIG['db'][0]['prefix'] . 'system_format_time.format AS time_format,
	' . $CONFIG['db'][0]['prefix'] . 'system_format_time.code AS time_code, 
	' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_decimal.code AS sep_decimal, 
	' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_thousand.code AS sep_thousand 
	';
//	$extQuery = ',
//		' . $CONFIG['db'][0]['prefix'] . 'system_countries.country_org

$queryC = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count,
										' . $CONFIG['db'][0]['prefix'] . 'system_countries.country
										' . $extQuery . '
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_countries
										
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_timezones
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_tz = ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.id_tz
									
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_continents
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.id_cont = ' . $CONFIG['db'][0]['prefix'] . 'system_continents.id_cont
									
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_date
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_fd = ' . $CONFIG['db'][0]['prefix'] . 'system_format_date.id_fd
										
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_time
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_ft = ' . $CONFIG['db'][0]['prefix'] . 'system_format_time.id_ft
										
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator AS ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_decimal
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_fs_decimal = ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_decimal.id_fs
										
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator AS ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_thousand
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_fs_thousand = ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_thousand.id_fs
								
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_countries.del = (:nultime)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_countries.country
									');
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);

$queryC->execute();
$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
$numC = $queryC->rowCount();
$nC = 0;

$CONFIG_TMP['user']['syscountries'][0] = array();
$CONFIG_TMP['user']['syscountries'][0]['id_sys_count'] = 0;
$CONFIG_TMP['user']['syscountries'][0]['country'] = 'allcountries';
$CONFIG_TMP['user']['syscountries'][0]['code'] = 'all';
$CONFIG_TMP['user']['syscountries'][0]['sep_decimal'] = $CONFIG['system']['sep_decimal'];
$CONFIG_TMP['user']['syscountries'][0]['sep_thousand'] = $CONFIG['system']['sep_thousand'];
$CONFIG_TMP['user']['syscountries'][0]['date_format'] = $CONFIG['system']['date_format'];
$CONFIG_TMP['user']['syscountries'][0]['date_code'] = $CONFIG['system']['date_code'];
$CONFIG_TMP['user']['syscountries'][0]['time_format'] = $CONFIG['system']['time_format'];
$CONFIG_TMP['user']['syscountries'][0]['time_code'] = $CONFIG['system']['time_code'];

foreach($rowsC as $datC){
	$CONFIG_TMP['user']['syscountries'][$datC['id_sys_count']] = array();
	
	foreach($datC as $key=>$val){
		$CONFIG_TMP['user']['syscountries'][$datC['id_sys_count']][$key] = $val;
	}
}

if($CONFIG['initLogin'] == true && !array_key_exists($CONFIG['activeSettings']['id_sys_count'], $CONFIG_TMP['user']['syscountries'])){
	reset($CONFIG_TMP['user']['syscountries']);
	$firstKey = key($CONFIG_TMP['user']['syscountries']);
	$CONFIG['activeSettings']['id_sys_count'] = intval($CONFIG_TMP['user']['syscountries'][$firstKey]['id_sys_count']);
	$CONFIG['activeSettings']['id_sys_count_form'] = intval($CONFIG_TMP['user']['syscountries'][$firstKey]['id_sys_count']);

	$aChangeCookie = array();
	if(isset($CONFIG['activeSettings']['id_sys_count'])) $aChangeCookie['id_sys_count'] = $CONFIG['activeSettings']['id_sys_count'];
	if(isset($CONFIG['activeSettings']['id_sys_count_form'])) $aChangeCookie['id_sys_count_form'] = $CONFIG['activeSettings']['id_sys_count_form'];
	if(count($aChangeCookie) > 0) changeCookie($name='activesettings', $aChangeCookie);
}
###########################################################################




###########################################################################
// Array for languages
$CONFIG_TMP['user']['syslanguages'] = array();
$extQuery = ',
	' . $CONFIG['db'][0]['prefix'] . 'system_languages.code
';
//	$extQuery = ',
//		' . $CONFIG['db'][0]['prefix'] . 'system_languages.language_org

foreach($CONFIG_TMP['user']['syscountries'] as $id_count => $val){
	$CONFIG_TMP['user']['syscountries'][$id_count]['languages'] = array();
	array_push($CONFIG_TMP['user']['syscountries'][$id_count]['languages'], 0);  

	$queryC = $CONFIG['dbconn'][0]->prepare('
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

	$queryC->execute();
	$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
	$numC = $queryC->rowCount();
	$nC = 0;

	$CONFIG_TMP['user']['syslanguages'][0] = array();
	$CONFIG_TMP['user']['syslanguages'][0]['id_sys_lang'] = 0;
	$CONFIG_TMP['user']['syslanguages'][0]['language'] = 'alllanguages';
	$CONFIG_TMP['user']['syslanguages'][0]['code'] = 'all';

	foreach($rowsC as $datC){
		array_push($CONFIG_TMP['user']['syscountries'][$id_count]['languages'], $datC['id_sys_lang']);  

		if(!array_key_exists($datC['id_sys_lang'], $CONFIG_TMP['user']['syslanguages'])){
			$CONFIG_TMP['user']['syslanguages'][$datC['id_sys_lang']] = array();
			
			foreach($datC as $key=>$val){
				$CONFIG_TMP['user']['syslanguages'][$datC['id_sys_lang']][$key] = $val;
			}
		}
	}
}

if($CONFIG['initLogin'] == true && !in_array($CONFIG['activeSettings']['id_sys_lang'], $CONFIG_TMP['user']['syscountries'][$CONFIG['activeSettings']['id_sys_count']]['languages'])){
	$CONFIG['activeSettings']['id_sys_lang'] = intval($CONFIG_TMP['user']['syscountries'][$CONFIG['activeSettings']['id_sys_count']]['languages'][0]);
	$CONFIG['activeSettings']['id_sys_lang_form'] = intval($CONFIG_TMP['user']['syscountries'][$CONFIG['activeSettings']['id_sys_count']]['languages'][0]);

	$aChangeCookie = array();
	if(isset($CONFIG['activeSettings']['id_sys_lang'])) $aChangeCookie['id_sys_lang'] = $CONFIG['activeSettings']['id_sys_lang'];
	if(isset($CONFIG['activeSettings']['id_sys_lang_form'])) $aChangeCookie['id_sys_lang_form'] = $CONFIG['activeSettings']['id_sys_lang_form'];
	if(count($aChangeCookie) > 0) changeCookie($name='activesettings', $aChangeCookie);
}
###########################################################################




###########################################################################
// Array for devices
$CONFIG_TMP['user']['sysdevices'] = array();
$extQuery = ',
	' . $CONFIG['db'][0]['prefix'] . 'system_devices.code
';
//	$extQuery = ',
//		' . $CONFIG['db'][0]['prefix'] . 'system_devices.device_org,

$queryC = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_devices.id_sys_dev,
										' . $CONFIG['db'][0]['prefix'] . 'system_devices.device
										' . $extQuery . '
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_devices
											
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_devices.del = (:nultime)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_devices.device
									');
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->execute();
$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
$numC = $queryC->rowCount();
$nC = 0;

$CONFIG_TMP['user']['sysdevices'][0] = array();
$CONFIG_TMP['user']['sysdevices'][0]['id_sys_dev'] = 0;
$CONFIG_TMP['user']['sysdevices'][0]['device'] = 'alldevices';
$CONFIG_TMP['user']['sysdevices'][0]['code'] = 'all';

foreach($rowsC as $datC){
	$CONFIG_TMP['user']['sysdevices'][$datC['id_sys_dev']] = array();
	
	foreach($datC as $key=>$val){
		$CONFIG_TMP['user']['sysdevices'][$datC['id_sys_dev']][$key] = $val;
	}
}

if($CONFIG['initLogin'] == true && !array_key_exists($CONFIG['activeSettings']['id_sys_dev'], $CONFIG_TMP['user']['sysdevices'])){
	reset($CONFIG_TMP['user']['sysdevices']);
	$firstKey = key($CONFIG_TMP['user']['sysdevices']);
	$CONFIG['activeSettings']['id_sys_dev'] = intval($CONFIG_TMP['user']['sysdevices'][$firstKey]['id_sys_dev']);
	$CONFIG['activeSettings']['id_sys_dev_form'] = intval($CONFIG_TMP['user']['sysdevices'][$firstKey]['id_sys_dev']);

	$aChangeCookie = array();
	if(isset($CONFIG['activeSettings']['id_sys_dev'])) $aChangeCookie['id_sys_dev'] = $CONFIG['activeSettings']['id_sys_dev'];
	if(isset($CONFIG['activeSettings']['id_sys_dev_form'])) $aChangeCookie['id_sys_dev_form'] = $CONFIG['activeSettings']['id_sys_dev_form'];
	if(count($aChangeCookie) > 0) changeCookie($name='activesettings', $aChangeCookie);
}
###########################################################################
?>