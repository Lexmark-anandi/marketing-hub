<?php
###########################################################################
// Settings for countries, languages and devices for data
###########################################################################
// Array for countries
$CONFIG_TMP['USER']['countries'] = array();
if($aTokenContent['user']['right_editallcountries'] == 9){
	$CONFIG_TMP['USER']['countries'][0] = array();
	
	$CONFIG_TMP['USER']['countries'][0]['id_countid'] = 0;
	$CONFIG_TMP['USER']['countries'][0]['country'] = 'allcountries';
}
	
//	$extQuery = ',
//		' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code,
//		' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code_add,
//		' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.currency,
//		' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.tax_name,
//		' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.tax,
//		' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.fee_name,
//		' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.sep_decimal,
//		' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.sep_thousand,
//		' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.email_sender,
//		' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.email_sendername,
//		' . $CONFIG['db'][0]['prefix'] . 'system_timezones.timezone,
//		' . $CONFIG['db'][0]['prefix'] . 'system_timezones.abbr AS timezone_abbr,
//		' . $CONFIG['db'][0]['prefix'] . 'system_timezones.offset AS timezone_offset,
//		' . $CONFIG['db'][0]['prefix'] . 'system_continents.continent,
//		' . $CONFIG['db'][0]['prefix'] . 'system_format_date.format AS date_format,
//		' . $CONFIG['db'][0]['prefix'] . 'system_format_date.code AS date_code,
//		' . $CONFIG['db'][0]['prefix'] . 'system_format_time.format AS time_format,
//		' . $CONFIG['db'][0]['prefix'] . 'system_format_time.code AS time_code
$extQuery = '';
$queryC = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country
											' . $extQuery . '
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
									
									INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid
									
									INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang
										
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_timezones
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_tz = ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.id_tz
									
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_continents
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.id_cont = ' . $CONFIG['db'][0]['prefix'] . 'system_continents.id_cont
									
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_date
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_fd = ' . $CONFIG['db'][0]['prefix'] . 'system_format_date.id_fd
										
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_time
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_ft = ' . $CONFIG['db'][0]['prefix'] . 'system_format_time.id_ft
																								
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid = (:id_uid)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.active = (:active)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
										AND (' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:id_clid)
											OR ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:nul))
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.del = (:nultime)
									GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country
									');
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
$queryC->bindValue(':active', 1, PDO::PARAM_INT);
$queryC->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);
$queryC->bindValue(':id_clid', $CONFIG['USER_CONF']['activeClient'], PDO::PARAM_INT);

if($aTokenContent['user']['right_country'] == 1){
	$queryC = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country
											' . $extQuery . '
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
										
										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid
										
										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_timezones
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_tz = ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.id_tz
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_continents
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.id_cont = ' . $CONFIG['db'][0]['prefix'] . 'system_continents.id_cont
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_date
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_fd = ' . $CONFIG['db'][0]['prefix'] . 'system_format_date.id_fd
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_time
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_ft = ' . $CONFIG['db'][0]['prefix'] . 'system_format_time.id_ft
																									
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid = (:id_uid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:id_clid)
												OR ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:nul))
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.del = (:nultime)
										GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);
	$queryC->bindValue(':id_clid', $CONFIG['USER_CONF']['activeClient'], PDO::PARAM_INT);
}

if($aTokenContent['user']['right_country'] == 2){
	$queryC = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country
											' . $extQuery . '
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_timezones
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_tz = ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.id_tz
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_continents
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.id_cont = ' . $CONFIG['db'][0]['prefix'] . 'system_continents.id_cont
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_date
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_fd = ' . $CONFIG['db'][0]['prefix'] . 'system_format_date.id_fd
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_time
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_ft = ' . $CONFIG['db'][0]['prefix'] . 'system_format_time.id_ft
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.active = (:active)
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:id_clid)
												OR ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:nul))
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryC->bindValue(':active', 1, PDO::PARAM_INT);
	$queryC->bindValue(':id_clid', $CONFIG['USER_CONF']['activeClient'], PDO::PARAM_INT);
}

if($aTokenContent['user']['right_country'] == 9){
	$queryC = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country
											' . $extQuery . '
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_timezones
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_tz = ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.id_tz
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_continents
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.id_cont = ' . $CONFIG['db'][0]['prefix'] . 'system_continents.id_cont
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_date
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_fd = ' . $CONFIG['db'][0]['prefix'] . 'system_format_date.id_fd
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_time
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_ft = ' . $CONFIG['db'][0]['prefix'] . 'system_format_time.id_ft
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:id_clid)
												OR ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:nul))
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_clid', $CONFIG['USER_CONF']['activeClient'], PDO::PARAM_INT);
}

$queryC->execute();
$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
$numC = $queryC->rowCount();
$nC = 0;

foreach($rowsC as $datC){
	$CONFIG_TMP['USER']['countries'][$datC['id_countid']] = array();
	
	foreach($datC as $key=>$val){
		$CONFIG_TMP['USER']['countries'][$datC['id_countid']][$key] = $val;
	}
}
###########################################################################




###########################################################################
// Array for languages
$CONFIG_TMP['USER']['languages'] = array();
$CONFIG_TMP['USER']['count2lang'] = array();
if($aTokenContent['user']['right_editalllanguages'] == 9){
	$CONFIG_TMP['USER']['languages'][0] = array();
	
	$CONFIG_TMP['USER']['languages'][0]['id_langid'] = 0;
	$CONFIG_TMP['USER']['languages'][0]['language'] = 'alllanguages';
}
	
//	$extQuery = ',
//		' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code,
//		' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code_add
$extQuery = ',
		' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_count2lang';
		
foreach($CONFIG_TMP['USER']['countries'] as $id_count => $val){
	$CONFIG_TMP['USER']['countries'][$id_count]['languages'] = array();
	if($id_count == 0 && $aTokenContent['user']['right_editalllanguages'] == 9){
		array_push($CONFIG_TMP['USER']['countries'][$id_count]['languages'], 0);  
	}
	
	$queryC = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
											' . $extQuery . '
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
										
										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid
										
										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang
												
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid = (:id_uid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.active = (:active)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:id_clid)
												OR ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:nul))
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid = (:id_countid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.del = (:nultime)
										GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryC->bindValue(':active', 1, PDO::PARAM_INT);
	$queryC->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);
	$queryC->bindValue(':id_clid', $CONFIG['USER_CONF']['activeClient'], PDO::PARAM_INT);
	$queryC->bindValue(':id_countid', $id_count, PDO::PARAM_INT);
	
	if($aTokenContent['user']['right_language'] == 1){
		$queryC = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid,
												' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
												' . $extQuery . '
											FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
											
											INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages
												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid
											
											INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang
													
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid = (:id_uid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
												AND (' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:id_clid)
													OR ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:nul))
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid = (:id_countid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.del = (:nultime)
											GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_countid
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
											');
		$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryC->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);
		$queryC->bindValue(':id_clid', $CONFIG['USER_CONF']['activeClient'], PDO::PARAM_INT);
		$queryC->bindValue(':id_countid', $id_count, PDO::PARAM_INT);
	}

	if($aTokenContent['user']['right_language'] == 2){
		$queryC = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid,
												' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
												' . $extQuery . '
											FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
											
											INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages
												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.active = (:active)
												AND (' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:id_clid)
													OR ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:nul))
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid = (:id_countid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.del = (:nultime)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
											');
		$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryC->bindValue(':active', 1, PDO::PARAM_INT);
		$queryC->bindValue(':id_clid', $CONFIG['USER_CONF']['activeClient'], PDO::PARAM_INT);
		$queryC->bindValue(':id_countid', $id_count, PDO::PARAM_INT);
	}
	
	if($aTokenContent['user']['right_language'] == 9){
		$queryC = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid,
												' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
												' . $extQuery . '
											FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
											
											INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages
												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
												AND (' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:id_clid)
													OR ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:nul))
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid = (:id_countid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.del = (:nultime)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
											');
		$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryC->bindValue(':id_clid', $CONFIG['USER_CONF']['activeClient'], PDO::PARAM_INT);
		$queryC->bindValue(':id_countid', $id_count, PDO::PARAM_INT);
	}

	$queryC->execute();
	$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
	$numC = $queryC->rowCount();
	$nC = 0;
	
	
	
	

	foreach($rowsC as $datC){
		array_push($CONFIG_TMP['USER']['countries'][$id_count]['languages'], $datC['id_langid']);  
		if(!in_array($datC['id_count2lang'], $CONFIG_TMP['USER']['count2lang'])) array_push($CONFIG_TMP['USER']['count2lang'], $datC['id_count2lang']);  

		if(!array_key_exists($datC['id_langid'], $CONFIG_TMP['USER']['languages'])){
			$CONFIG_TMP['USER']['languages'][$datC['id_langid']] = array();
			
			foreach($datC as $key=>$val){
				$CONFIG_TMP['USER']['languages'][$datC['id_langid']][$key] = $val;
			}
		}
	}
}
###########################################################################




###########################################################################
// Array for count2lang

###########################################################################




###########################################################################
// Array for devices
$CONFIG_TMP['USER']['devices'] = array();
if($aTokenContent['user']['right_editalldevices'] == 9){
	$CONFIG_TMP['USER']['devices'][0] = array();
	
	$CONFIG_TMP['USER']['devices'][0]['id_devid'] = 0;
	$CONFIG_TMP['USER']['devices'][0]['device'] = 'alldevices';
}
	
//	$extQuery = ',
//		' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.device,
$extQuery = '';
$queryC = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_devid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.device
										' . $extQuery . '
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni
											
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.active = (:active)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_dev = (:nul)
										AND (' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_clid = (:id_clid)
											OR ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_clid = (:nul))
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.device
									');
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
$queryC->bindValue(':active', 1, PDO::PARAM_INT);
$queryC->bindValue(':id_clid', $CONFIG['USER_CONF']['activeClient'], PDO::PARAM_INT);

if($aTokenContent['user']['right_device'] == 1){
	$queryC = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_devid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.device
											' . $extQuery . '
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni
												
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_dev = (:nul)
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_clid = (:id_clid)
												OR ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_clid = (:nul))
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.device
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_clid', $CONFIG['USER_CONF']['activeClient'], PDO::PARAM_INT);
}

if($aTokenContent['user']['right_device'] == 2){
	$queryC = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_devid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.device
											' . $extQuery . '
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni
												
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.active = (:active)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_dev = (:nul)
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_clid = (:id_clid)
												OR ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_clid = (:nul))
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.device
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryC->bindValue(':active', 1, PDO::PARAM_INT);
	$queryC->bindValue(':id_clid', $CONFIG['USER_CONF']['activeClient'], PDO::PARAM_INT);
}

if($aTokenContent['user']['right_device'] == 9){
	$queryC = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_devid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.device
											' . $extQuery . '
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni
												
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_dev = (:nul)
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_clid = (:id_clid)
												OR ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_clid = (:nul))
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.device
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_clid', $CONFIG['USER_CONF']['activeClient'], PDO::PARAM_INT);
}

$queryC->execute();
$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
$numC = $queryC->rowCount();
$nC = 0;

foreach($rowsC as $datC){
	$CONFIG_TMP['USER']['devices'][$datC['id_devid']] = array();
	
	foreach($datC as $key=>$val){
		$CONFIG_TMP['USER']['devices'][$datC['id_devid']][$key] = $val;
	}
}
###########################################################################
?>