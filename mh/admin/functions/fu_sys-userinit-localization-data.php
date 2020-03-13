<?php
###########################################################################
// Settings for countries, languages and devices for data
###########################################################################
// Array for countries
$CONFIG_TMP['user']['countries'] = array();
$extQuery = ',
	' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.currency,
	' . $CONFIG['db'][0]['prefix'] . 'system_format_date.format AS date_format,
	' . $CONFIG['db'][0]['prefix'] . 'system_format_date.code AS date_code, 
	' . $CONFIG['db'][0]['prefix'] . 'system_format_time.format AS time_format, 
	' . $CONFIG['db'][0]['prefix'] . 'system_format_time.code AS time_code, 
	' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_decimal.code AS sep_decimal, 
	' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_thousand.code AS sep_thousand 
';
//$extQuery = ',
//	' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code,
//	' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code_add,
//	' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.tax_name,
//	' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.tax,
//	' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.fee_name,
//	' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.email_sender,
//	' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.email_sendername,
//	' . $CONFIG['db'][0]['prefix'] . 'system_timezones.timezone,
//	' . $CONFIG['db'][0]['prefix'] . 'system_timezones.abbr AS timezone_abbr,
//	' . $CONFIG['db'][0]['prefix'] . 'system_timezones.offset AS timezone_offset,
//	' . $CONFIG['db'][0]['prefix'] . 'system_continents.continent,

$queryC = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country
											' . $extQuery . '
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
									
									INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
									
									INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_timezones
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_tz = ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.id_tz
									
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_continents
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.id_cont = ' . $CONFIG['db'][0]['prefix'] . 'system_continents.id_cont
									
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_date
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_fd = ' . $CONFIG['db'][0]['prefix'] . 'system_format_date.id_fd
										
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_time
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_ft = ' . $CONFIG['db'][0]['prefix'] . 'system_format_time.id_ft
										
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator AS ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_decimal
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_fs_decimal = ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_decimal.id_fs
										
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator AS ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_thousand
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_fs_thousand = ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_thousand.id_fs
																								
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.active = (:active)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid = (:id_uid)
									GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country
									');
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
$queryC->bindValue(':active', 1, PDO::PARAM_INT);
$queryC->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);

if($aTokenContent['user']['specifications'][2] == 1){
	$queryC = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country
											' . $extQuery . '
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
										
										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										
										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_timezones
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_tz = ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.id_tz
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_continents
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.id_cont = ' . $CONFIG['db'][0]['prefix'] . 'system_continents.id_cont
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_date
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_fd = ' . $CONFIG['db'][0]['prefix'] . 'system_format_date.id_fd
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_time
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_ft = ' . $CONFIG['db'][0]['prefix'] . 'system_format_time.id_ft
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator AS ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_decimal
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_fs_decimal = ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_decimal.id_fs
											
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator AS ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_thousand
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_fs_thousand = ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_thousand.id_fs
																										
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid = (:id_uid)
										GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);
}

if($aTokenContent['user']['specifications'][2] == 8){
	$queryC = $CONFIG['dbconn'][0]->prepare('
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
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator AS ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_decimal
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_fs_decimal = ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_decimal.id_fs
											
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator AS ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_thousand
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_fs_thousand = ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_thousand.id_fs
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.active = (:active)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryC->bindValue(':active', 1, PDO::PARAM_INT);
}

if($aTokenContent['user']['specifications'][2] == 9){
	$queryC = $CONFIG['dbconn'][0]->prepare('
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
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator AS ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_decimal
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_fs_decimal = ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_decimal.id_fs
											
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator AS ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_thousand
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_fs_thousand = ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_thousand.id_fs
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
}

$queryC->execute();
$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
$numC = $queryC->rowCount();
$nC = 0;

if($aTokenContent['user']['specifications'][6] == 9 || $numC == 0){
	$CONFIG_TMP['user']['countries'][0] = array();
	$CONFIG_TMP['user']['countries'][0]['id_countid'] = 0;
	$CONFIG_TMP['user']['countries'][0]['country'] = 'allcountries';
}

foreach($rowsC as $datC){
	$CONFIG_TMP['user']['countries'][$datC['id_countid']] = array();
	
	foreach($datC as $key=>$val){
		$CONFIG_TMP['user']['countries'][$datC['id_countid']][$key] = $val;
	}
}


if($CONFIG['initLogin'] == true && !array_key_exists($CONFIG['activeSettings']['id_countid'], $CONFIG_TMP['user']['countries'])){
	reset($CONFIG_TMP['user']['countries']);
	$firstKey = key($CONFIG_TMP['user']['countries']);
	$CONFIG['activeSettings']['id_countid'] = intval($CONFIG_TMP['user']['countries'][$firstKey]['id_countid']);
	$CONFIG['activeSettings']['id_countid_form'] = intval($CONFIG_TMP['user']['countries'][$firstKey]['id_countid']); 

	$aChangeCookie = array();
	if(isset($CONFIG['activeSettings']['id_countid'])) $aChangeCookie['id_countid'] = $CONFIG['activeSettings']['id_countid'];
	if(isset($CONFIG['activeSettings']['id_countid_form'])) $aChangeCookie['id_countid_form'] = $CONFIG['activeSettings']['id_countid_form'];
	if(count($aChangeCookie) > 0) changeCookie($name='activesettings', $aChangeCookie);
}
###########################################################################




###########################################################################
// Array for languages
$CONFIG_TMP['user']['count2lang'] = array();
$CONFIG_TMP['user']['languages'] = array();
$extQuery = ',
		' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang';
//	$extQuery = ',
//		' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code,
//		' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code_add
		
foreach($CONFIG_TMP['user']['countries'] as $id_count => $val){
	$CONFIG_TMP['user']['countries'][$id_count]['languages'] = array();
	if($aTokenContent['user']['specifications'][7] == 9 && ($id_count == 0 || $aTokenContent['user']['specifications'][13] == 9)){
		array_push($CONFIG_TMP['user']['countries'][$id_count]['languages'], 0);  
	}
	
	$queryC = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
											' . $extQuery . '
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
										
										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										
										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.active = (:active)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid = (:id_countid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid = (:id_uid)
										GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryC->bindValue(':active', 1, PDO::PARAM_INT);
	$queryC->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);
	$queryC->bindValue(':id_countid', $id_count, PDO::PARAM_INT);
	
	if($aTokenContent['user']['specifications'][3] == 1){
		$queryC = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid,
												' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
												' . $extQuery . '
											FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
											
											INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_lang = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_dev = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											
											INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_lang = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_dev = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid = (:id_countid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid = (:id_uid)
											GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_countid
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
											');
		$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryC->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);
		$queryC->bindValue(':id_countid', $id_count, PDO::PARAM_INT);
	}

	if($aTokenContent['user']['specifications'][3] == 8){
		$queryC = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid,
												' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
												' . $extQuery . '
											FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
											
											INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_lang = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_dev = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.active = (:active)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid = (:id_countid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.del = (:nultime)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
											');
		$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryC->bindValue(':active', 1, PDO::PARAM_INT);
		$queryC->bindValue(':id_countid', $id_count, PDO::PARAM_INT);
	}
	
	if($aTokenContent['user']['specifications'][3] == 9){
		$queryC = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid,
												' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
												' . $extQuery . '
											FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
											
											INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_lang = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_dev = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid = (:id_countid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.del = (:nultime)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
											');
		$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryC->bindValue(':id_countid', $id_count, PDO::PARAM_INT);
	}

	$queryC->execute();
	$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
	$numC = $queryC->rowCount();
	$nC = 0;
	
	
	if($aTokenContent['user']['specifications'][7] == 9 || $numC == 0){
		$CONFIG_TMP['user']['languages'][0] = array();
		$CONFIG_TMP['user']['languages'][0]['id_langid'] = 0;
		$CONFIG_TMP['user']['languages'][0]['language'] = 'alllanguages';
	}

	if($aTokenContent['user']['specifications'][12] == 9 || $id_count != 0){
		foreach($rowsC as $datC){
			array_push($CONFIG_TMP['user']['countries'][$id_count]['languages'], $datC['id_langid']);  
			if(!in_array($datC['id_count2lang'], $CONFIG_TMP['user']['count2lang'])) array_push($CONFIG_TMP['user']['count2lang'], $datC['id_count2lang']);  
	
			if(!array_key_exists($datC['id_langid'], $CONFIG_TMP['user']['languages'])){
				$CONFIG_TMP['user']['languages'][$datC['id_langid']] = array();
				
				foreach($datC as $key=>$val){
					$CONFIG_TMP['user']['languages'][$datC['id_langid']][$key] = $val;
				}
			}
		}
	}
}

if($CONFIG['initLogin'] == true && !in_array($CONFIG['activeSettings']['id_langid'], $CONFIG_TMP['user']['countries'][$CONFIG['activeSettings']['id_countid']]['languages'])){
	$CONFIG['activeSettings']['id_langid'] = intval($CONFIG_TMP['user']['countries'][$CONFIG['activeSettings']['id_countid']]['languages'][0]);
	$CONFIG['activeSettings']['id_langid_form'] = intval($CONFIG_TMP['user']['countries'][$CONFIG['activeSettings']['id_countid']]['languages'][0]);

	$aChangeCookie = array();
	if(isset($CONFIG['activeSettings']['id_langid'])) $aChangeCookie['id_langid'] = $CONFIG['activeSettings']['id_langid'];
	if(isset($CONFIG['activeSettings']['id_langid_form'])) $aChangeCookie['id_langid_form'] = $CONFIG['activeSettings']['id_langid_form'];
	if(count($aChangeCookie) > 0) changeCookie($name='activesettings', $aChangeCookie);
}
###########################################################################





###########################################################################
// Array for devices
$CONFIG_TMP['user']['devices'] = array();
$extQuery = '';
//	$extQuery = ',
//		' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.device,

$queryC = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_devid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.device
										' . $extQuery . '
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni
											
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.active = (:active)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.device
									');
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':nul', 0, PDO::PARAM_INT); 
$queryC->bindValue(':active', 1, PDO::PARAM_INT);

if($aTokenContent['user']['specifications'][4] == 1){
	$queryC = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_devid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.device
											' . $extQuery . '
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni
												
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.del = (:nultime)
											
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.device
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
}

if($aTokenContent['user']['specifications'][4] == 8){
	$queryC = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_devid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.device
											' . $extQuery . '
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni
												
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.active = (:active)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.device
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryC->bindValue(':active', 1, PDO::PARAM_INT);
}

if($aTokenContent['user']['specifications'][4] == 9){
	$queryC = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_devid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.device
											' . $extQuery . '
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni
												
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.del = (:nultime)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.device
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
}

$queryC->execute();
$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
$numC = $queryC->rowCount();
$nC = 0;

if($aTokenContent['user']['specifications'][8] == 9 || $numC == 0){
	$CONFIG_TMP['user']['devices'][0] = array();
	$CONFIG_TMP['user']['devices'][0]['id_devid'] = 0;
	$CONFIG_TMP['user']['devices'][0]['device'] = 'alldevices';
}

foreach($rowsC as $datC){
	$CONFIG_TMP['user']['devices'][$datC['id_devid']] = array();
	
	foreach($datC as $key=>$val){
		$CONFIG_TMP['user']['devices'][$datC['id_devid']][$key] = $val;
	}
}

if($CONFIG['initLogin'] == true && !array_key_exists($CONFIG['activeSettings']['id_devid'], $CONFIG_TMP['user']['devices'])){
	reset($CONFIG_TMP['user']['devices']);
	$firstKey = key($CONFIG_TMP['user']['devices']);
	$CONFIG['activeSettings']['id_devid'] = intval($CONFIG_TMP['user']['devices'][$firstKey]['id_devid']);
	$CONFIG['activeSettings']['id_devid_form'] = intval($CONFIG_TMP['user']['devices'][$firstKey]['id_devid']);

	$aChangeCookie = array();
	if(isset($CONFIG['activeSettings']['id_devid'])) $aChangeCookie['id_devid'] = $CONFIG['activeSettings']['id_devid'];
	if(isset($CONFIG['activeSettings']['id_devid_form'])) $aChangeCookie['id_devid_form'] = $CONFIG['activeSettings']['id_devid_form'];
	if(count($aChangeCookie) > 0) changeCookie($name='activesettings', $aChangeCookie);
}
###########################################################################
?>