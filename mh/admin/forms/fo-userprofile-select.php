<?php
function selectUserRoles(){
	global $CONFIG, $TEXT;
	
	$table = 'system_roles';
	$primary = 'id_r'; 


	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . $table . '.rank
										FROM ' . $CONFIG['db'][0]['prefix'] . $table . ' 
										WHERE ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count = (:count)
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang = (:lang)
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev = (:dev)
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_r = (:id_r)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . $table . '.rank
											
										');
	$query->bindValue(':count', $CONFIG['settings']['selectCountry'], PDO::PARAM_INT);
	$query->bindValue(':lang', $CONFIG['settings']['selectLanguage'], PDO::PARAM_INT);
	$query->bindValue(':dev', $CONFIG['settings']['selectDevice'], PDO::PARAM_INT);
	$query->bindValue(':id_r', $CONFIG['user']['right'], PDO::PARAM_INT);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount(); 
	$rankAct = $rows[0]['rank'];


	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primary . ' AS id,
											' . $CONFIG['db'][0]['prefix'] . $table . '.role AS term
										FROM ' . $CONFIG['db'][0]['prefix'] . $table . ' 
										WHERE ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count = (:count)
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang = (:lang)
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev = (:dev)
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.rank >= (:rank)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . $table . '.rank
											
										');
	$query->bindValue(':count', $CONFIG['settings']['selectCountry'], PDO::PARAM_INT);
	$query->bindValue(':lang', $CONFIG['settings']['selectLanguage'], PDO::PARAM_INT);
	$query->bindValue(':dev', $CONFIG['settings']['selectDevice'], PDO::PARAM_INT);
	$query->bindValue(':rank', $rankAct, PDO::PARAM_INT);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount(); 
	
	$aResult = array();
	foreach($rows as $row){
		$row['term'] = (isset($TEXT[$row['term']])) ? $TEXT[$row['term']] : $row['term'];
		$aResult[$row['id']] = $row['term'];
	}

	$str = '';
	foreach($aResult as $id => $term){
		$str .= '<option value="' . $id . '">' . $term . '</option>';
	}
	$str .= '';
		
	return $str;
}


function selectUserCountries(){
	global $CONFIG, $TEXT;
	
	$listCountries = '';
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang as id,
											' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country,
											' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni 
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
	
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
	
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang IN ('. implode(',', $CONFIG['user']['count2lang']) . ')
	
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country, ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':nul', 0, PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	
	$aResult = array();
	foreach($rows as $row){
		$row['country'] = (isset($TEXT[$row['country']])) ? $TEXT[$row['country']] : $row['country'];
		$row['language'] = (isset($TEXT[$row['language']])) ? $TEXT[$row['language']] : $row['language'];
		$aResult[$row['id']] = $row['country'] . ' (' . $row['language'] . ')';
	}
	
	asort($aResult, SORT_NATURAL | SORT_FLAG_CASE);
	foreach($aResult as $id => $term){
		$listCountries .= '<div><input type="checkbox" name="country[]" id="country_'.$id.'" value="'.$id.'"> <label for="country_'.$id.'"> '.$term.'</label></div>';
	}
		
	return $listCountries;
}


function selectUserGeographies(){
	global $CONFIG, $TEXT;
	
	$table = '_geographies_uni';
	$primary = 'id_geoid';

	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primary . ' AS id,
											' . $CONFIG['db'][0]['prefix'] . $table . '.geography AS term
										FROM ' . $CONFIG['db'][0]['prefix'] . $table . ' 
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.id_geoid = ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_geoid
										WHERE ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count = (:count)
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang = (:lang)
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev = (:dev)
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.del = (:nultime)
	
											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_countid IN ('. implode(',', array_keys($CONFIG['user']['countries'])) . ')
										GROUP BY ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primary . '
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . $table . '.geography
											
										');
	$query->bindValue(':count', $CONFIG['settings']['selectCountry'], PDO::PARAM_INT);
	$query->bindValue(':lang', $CONFIG['settings']['selectLanguage'], PDO::PARAM_INT);
	$query->bindValue(':dev', $CONFIG['settings']['selectDevice'], PDO::PARAM_INT);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount(); 
	
	$aResult = array();
	foreach($rows as $row){
		$row['term'] = (isset($TEXT[$row['term']])) ? $TEXT[$row['term']] : $row['term'];
		$aResult[$row['id']] = $row['term'];
	}

	$str = '';
	foreach($aResult as $id => $term){
		$str .= '<option value="' . $id . '">' . $term . '</option>';
	}
	$str .= '';
		
	return $str;
}


function selectUserCountryconfig(){
	global $CONFIG, $TEXT;
	
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count as id,
											' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_decimal.code AS sep_decimal,
											' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_thousand.code AS sep_thousand,
											' . $CONFIG['db'][0]['prefix'] . 'system_format_date.format AS format_date,
											' . $CONFIG['db'][0]['prefix'] . 'system_format_time.format AS format_time
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_countries 
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator AS ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_decimal
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_fs_decimal = ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_decimal.id_fs
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator AS ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_thousand
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_fs_thousand = ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_thousand.id_fs
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_date
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_fd = ' . $CONFIG['db'][0]['prefix'] . 'system_format_date.id_fd
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_time
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_ft = ' . $CONFIG['db'][0]['prefix'] . 'system_format_time.id_ft
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_countries.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count IN ('. implode(',', array_keys($CONFIG['user']['syscountries'])) . ')
	
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_countries.country
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	
	$aResult = array();
	foreach($rows as $row){
		$aResult[$row['id']] = $row['format_date'] . ' ' . $row['format_time'] . ' - 1' . $row['sep_thousand'] . '234' . $row['sep_decimal'] . '00';
	}
	
	$str = '';
	foreach($aResult as $id => $term){
		$str .= '<option value="' . $id . '">' . $term . '</option>';
	}
	$str .= '';
		
	return $str;
}


?>