<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php'); 
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData(json_decode($_POST['data'], true));
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php'); 

$out = array();
$all = array();

$queryD = $CONFIG['dbconn']->prepare(' 
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count_data as id_data,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid as id,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code_add,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_tz,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_fd,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_ft,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.currency,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.tax_name,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.tax,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.fee_name,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.sep_decimal,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.sep_thousand,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.email_sender,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.email_sendername,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.active AS active_uni,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_loc.active,
										' . $CONFIG['db'][0]['prefix'] . 'system_timezones.timezone,
										' . $CONFIG['db'][0]['prefix'] . 'system_timezones.abbr,
										' . $CONFIG['db'][0]['prefix'] . 'system_format_date.format,
										' . $CONFIG['db'][0]['prefix'] . 'system_format_time.format AS format_time
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni 
	
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_loc 
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_loc.id_countid
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_countries_loc.id_count = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count OR ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_loc.id_count IS NULL)
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_countries_loc.id_lang = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang OR ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_loc.id_lang IS NULL)
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_countries_loc.id_dev = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev OR ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_loc.id_dev IS NULL)
								
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_timezones 
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_tz = ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.id_tz
	
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_date 
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_fd = ' . $CONFIG['db'][0]['prefix'] . 'system_format_date.id_fd
	
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_time 
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_ft = ' . $CONFIG['db'][0]['prefix'] . 'system_format_time.id_ft
	
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
										AND (' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:clid)
											OR ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:nul))
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = (:id)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count, ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang, ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev
									');
$queryD->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryD->bindValue(':clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
$queryD->bindValue(':nul', 0, PDO::PARAM_INT);
$queryD->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
$queryD->execute();
$rowsD = $queryD->fetchAll(PDO::FETCH_ASSOC);
$numD = $queryD->rowCount();

$aFields = array();
$aFields['yesNo2Text'] = array('active');
$aFields['floats'] = array('tax');
$aFields['check2Radio'] = array('active');

$outTmp = array();
$outTmp['country'] = '';
$outTmp['code'] = '';
$outTmp['code_add'] = '';
$outTmp['active_uni'] = 2;
$outTmp['active'] = 2;
$outTmp['activeT'] = '';
$outTmp['id_tz'] = '';
$outTmp['id_fd'] = '';
$outTmp['id_ft'] = '';
$outTmp['currency'] = '';
$outTmp['tax_name'] = '';
$outTmp['tax'] = '';
$outTmp['fee_name'] = '';
$outTmp['sep_decimal'] = '';
$outTmp['sep_thousand'] = '';
$outTmp['email_sender'] = '';
$outTmp['email_sendername'] = '';
$outTmp['default_'] = '';

$outTmp['languages'] = array();




$identifier = array('##country');

$addColumns = array();
$addColumns['languages'] = array();
$addColumns['default_'] = '';

if($numD > 0){
	$query2 = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.default_
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni 
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid
											
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:count)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:lang)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:dev)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid = (:id)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.del = (:nultime)
										');
	$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query2->bindValue(':count', 0, PDO::PARAM_INT);
	$query2->bindValue(':lang', 0, PDO::PARAM_INT);
	$query2->bindValue(':dev', 0, PDO::PARAM_INT);
	$query2->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
	$query2->execute();
	$rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$num2 = $query2->rowCount();
	foreach($rows2 as $row2){
		array_push($addColumns['languages'], $row2['id_langid']);
		if($row2['default_'] == 1) $addColumns['default_'] = $row2['id_langid'];
	}
}

include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-default-read.php'); 



?>