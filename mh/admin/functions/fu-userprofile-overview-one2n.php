<?php
######################################
// user2countries
$conditionN = '';
$aConditionN = array();
$aConditionN['count'] = array($CONFIG['settings']['selectCountry'], 'd');
$aConditionN['lang'] = array($CONFIG['settings']['selectLanguage'], 'd');
$aConditionN['dev'] = array($CONFIG['settings']['selectDevice'], 'd');
$aConditionN['nultime'] = array('0000-00-00 00:00:00', 's');
$conditionParentN = '';
$aConditionParentN = array();

$row['country'] = '';

$queryStrN = 'SELECT ';
$queryStrN .= $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang, '; 
$queryStrN .= $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country, '; 
$queryStrN .= $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language '; 
$queryStrN .= 'FROM ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages ';

$queryStrN .= 'INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni ';
$queryStrN .= 'ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count = (:count) ';
$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_lang = (:lang) ';
$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_dev = (:dev) ';
$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ') ';
$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang '; 
$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.del = (:nultime) ';

$queryStrN .= 'INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni ';
$queryStrN .= 'ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:count) ';
$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:lang) ';
$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:dev) ';
$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ') ';
$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid ';
$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime) ';

$queryStrN .= 'INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni ';
$queryStrN .= 'ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:count) ';
$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:lang) ';
$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:dev) ';
$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ') ';
$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid ';
$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime) ';

$queryStrN .= 'WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid = (:id) ';
$queryStrN .= 'ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country, ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language ';

$queryN = $CONFIG['dbconn'][0]->prepare($queryStrN);
$queryN->bindValue(':id', $row['id_uid'], PDO::PARAM_INT);
foreach($aConditionN as $k=>$v){
	if($v[1] == 'd'){
		$queryN->bindValue(':'.$k, $v[0], PDO::PARAM_INT);
	}else if($v[1] == 'sl'){
		$queryN->bindValue(':'.$k, '%'.$v[0].'%', PDO::PARAM_STR);
	}else if($v[1] == 'slb'){
		$queryN->bindValue(':'.$k, '%'.$v[0], PDO::PARAM_STR);
	}else if($v[1] == 'sle'){
		$queryN->bindValue(':'.$k, $v[0].'%', PDO::PARAM_STR);
	}else{
		$queryN->bindValue(':'.$k, $v[0], PDO::PARAM_STR);
	}
}
foreach($aConditionParentN as $k=>$v){
	if($v[1] == 'd'){
		$queryN->bindValue(':'.$k, $v[0], PDO::PARAM_INT);
	}else if($v[1] == 'sl'){
		$queryN->bindValue(':'.$k, '%'.$v[0].'%', PDO::PARAM_STR);
	}else if($v[1] == 'slb'){
		$queryN->bindValue(':'.$k, '%'.$v[0], PDO::PARAM_STR);
	}else if($v[1] == 'sle'){
		$queryN->bindValue(':'.$k, $v[0].'%', PDO::PARAM_STR);
	}else{
		$queryN->bindValue(':'.$k, $v[0], PDO::PARAM_STR);
	}
}
$queryN->execute();
$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
$numN = $queryN->rowCount();

foreach($rowsN as $rowN){
	$row['country'] .= '<div>' . $rowN['country'] . ' (' . $rowN['language'] . ')</div>';;
}

?>