<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();


$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$promid = (isset($varSQL['reminder'])) ? $varSQL['reminder'] : $CONFIG['page']['id_data'];


$cond = '';
if($CONFIG['settings']['formCountry'] != 0){
	$cond = 'AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid = (:id_count)';
	$cond .= 'AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_langid = (:id_lang)';
}

$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_ SET
										transrequest_at = (:now),
										transrequest_from = (:transrequest_from)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_promid = (:id_promid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.published_at = (:nultime)
										' . $cond . '
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':now', $now, PDO::PARAM_STR); 
$query->bindValue(':transrequest_from', $CONFIG['user']['id'], PDO::PARAM_STR);
$query->bindValue(':id_promid', $promid, PDO::PARAM_STR);
if($CONFIG['settings']['formCountry'] != 0){
	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
}
$query->execute();


##########################################


$condOrg = '';
if($CONFIG['settings']['formCountry'] != 0){
	$condOrg = 'AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_##tab##.id_count = (:id_count)';
	$condOrg .= 'AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_##tab##.id_lang = (:id_lang)';
}


$cond = str_replace('##tab##', 'ext', $condOrg);
$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_promotions_ext SET
										transrequest_at = (:now),
										transrequest_from = (:transrequest_from)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions_ext.id_promid = (:id_promid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_ext.published_at = (:nultime)
										' . $cond . '
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->bindValue(':transrequest_from', $CONFIG['user']['id'], PDO::PARAM_STR);
$query->bindValue(':id_promid', $promid, PDO::PARAM_STR);
if($CONFIG['settings']['formCountry'] != 0){
	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
}
$query->execute();

$cond = str_replace('##tab##', 'loc', $condOrg);
$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_promotions_loc SET
										transrequest_at = (:now),
										transrequest_from = (:transrequest_from)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions_loc.id_promid = (:id_promid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_loc.published_at = (:nultime)
										' . $cond . '
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->bindValue(':transrequest_from', $CONFIG['user']['id'], PDO::PARAM_STR);
$query->bindValue(':id_promid', $promid, PDO::PARAM_STR);
if($CONFIG['settings']['formCountry'] != 0){
	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
}
$query->execute();

$cond = str_replace('##tab##', 'res', $condOrg);
$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_promotions_res SET
										transrequest_at = (:now),
										transrequest_from = (:transrequest_from)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions_res.id_promid = (:id_promid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_res.published_at = (:nultime)
										' . $cond . '
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->bindValue(':transrequest_from', $CONFIG['user']['id'], PDO::PARAM_STR);
$query->bindValue(':id_promid', $promid, PDO::PARAM_STR);
if($CONFIG['settings']['formCountry'] != 0){
	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
}
$query->execute();

$cond = str_replace('##tab##', 'uni', $condOrg);
$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni SET
										transrequest_at = (:now),
										transrequest_from = (:transrequest_from)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid = (:id_promid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.published_at = (:nultime)
										' . $cond . '
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->bindValue(':transrequest_from', $CONFIG['user']['id'], PDO::PARAM_STR);
$query->bindValue(':id_promid', $promid, PDO::PARAM_STR);
if($CONFIG['settings']['formCountry'] != 0){
	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
}
$query->execute();



#####################################################

$condOrg = '';
if($CONFIG['settings']['formCountry'] != 0){
	$condOrg = 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_##tab##.id_count = (:id_count)';
	$condOrg .= 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_##tab##.id_lang = (:id_lang)';
}


$cond = str_replace('##tab##', 'ext', $condOrg);
$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_ext SET
										transrequest_at = (:now),
										transrequest_from = (:transrequest_from)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_ext.id_promid = (:id_promid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_ext.published_at = (:nultime)
										' . $cond . '
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->bindValue(':transrequest_from', $CONFIG['user']['id'], PDO::PARAM_STR);
$query->bindValue(':id_promid', $promid, PDO::PARAM_STR);
if($CONFIG['settings']['formCountry'] != 0){
	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
}
$query->execute();

$cond = str_replace('##tab##', 'loc', $condOrg);
$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_loc SET
										transrequest_at = (:now),
										transrequest_from = (:transrequest_from)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_loc.id_promid = (:id_promid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_loc.published_at = (:nultime)
										' . $cond . '
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->bindValue(':transrequest_from', $CONFIG['user']['id'], PDO::PARAM_STR);
$query->bindValue(':id_promid', $promid, PDO::PARAM_STR);
if($CONFIG['settings']['formCountry'] != 0){
	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
}
$query->execute();

$cond = str_replace('##tab##', 'res', $condOrg);
$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_res SET
										transrequest_at = (:now),
										transrequest_from = (:transrequest_from)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_res.id_promid = (:id_promid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_res.published_at = (:nultime)
										' . $cond . '
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->bindValue(':transrequest_from', $CONFIG['user']['id'], PDO::PARAM_STR);
$query->bindValue(':id_promid', $promid, PDO::PARAM_STR);
if($CONFIG['settings']['formCountry'] != 0){
	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
}
$query->execute();

$cond = str_replace('##tab##', 'uni', $condOrg);
$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni SET
										transrequest_at = (:now),
										transrequest_from = (:transrequest_from)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid = (:id_promid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.published_at = (:nultime)
										' . $cond . '
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->bindValue(':transrequest_from', $CONFIG['user']['id'], PDO::PARAM_STR);
$query->bindValue(':id_promid', $promid, PDO::PARAM_STR);
if($CONFIG['settings']['formCountry'] != 0){
	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
}
$query->execute();


#####################################################
// sending requests
#####################################################

// select template
$queryT = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid,
										' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.title
									FROM ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_dev = (:id_dev)
										AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid = (:id_promid)
									');
$queryT->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryT->bindValue(':id_count', 0, PDO::PARAM_INT);
$queryT->bindValue(':id_lang', 0, PDO::PARAM_INT);
$queryT->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryT->bindValue(':id_promid', $promid, PDO::PARAM_INT);
$queryT->execute();
$rowsT = $queryT->fetchAll(PDO::FETCH_ASSOC);
$numT = $queryT->rowCount();

$TEMPLATEID = $rowsT[0]['id_promid'];
$TEMPLATETITLE = $rowsT[0]['title'];

// select notification
$queryN = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_emailnotifications_uni.subject,
										' . $CONFIG['db'][0]['prefix'] . '_emailnotifications_uni.notification
									FROM ' . $CONFIG['db'][0]['prefix'] . '_emailnotifications_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_emailnotifications_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_emailnotifications_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_emailnotifications_uni.id_dev = (:id_dev)
										AND ' . $CONFIG['db'][0]['prefix'] . '_emailnotifications_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_emailnotifications_uni.id_enid = (:id_enid)
									');
$queryN->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryN->bindValue(':id_count', 0, PDO::PARAM_INT);
$queryN->bindValue(':id_lang', 0, PDO::PARAM_INT);
$queryN->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryN->bindValue(':id_enid', 2, PDO::PARAM_INT);
$queryN->execute();
$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
$numN = $queryN->rowCount();

// select countries
$queryC = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid,
										' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid,
										' . $CONFIG['db'][0]['prefix'] . 'system_user.firstname,
										' . $CONFIG['db'][0]['prefix'] . 'system_user.lastname,
										' . $CONFIG['db'][0]['prefix'] . 'system_user.email
									FROM ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_ 
										
									INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
										ON ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang
										
									INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid

									WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_promid = (:id_promid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.published_at = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_r = (:id_r)
									GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid
									');
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':id_promid', $promid, PDO::PARAM_INT);
$queryC->bindValue(':id_r', 4, PDO::PARAM_INT);
$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
$queryC->execute();
$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
$numC = $queryC->rowCount();
//			$arr = $queryC->errorInfo();
//			print_r($arr);

$countAdmin = 1;
if($numC == 0){
	$countAdmin = 0;
	$queryC = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.firstname,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.lastname,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.email
										FROM ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_ 
											
										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
											ON ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang
											
										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid
	
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_promid = (:id_promid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.published_at = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_r = (:id_r)
										GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':id_promid', $promid, PDO::PARAM_INT);
	$queryC->bindValue(':id_r', 3, PDO::PARAM_INT);
	$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryC->execute();
	$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
	$numC = $queryC->rowCount();
}


foreach($rowsC as $rowC){
	$queryCu = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
											
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
											
										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang
	
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid = (:id_uid)
										');
	$queryCu->bindValue(':id_uid', $rowC['id_uid'], PDO::PARAM_INT);
	$queryCu->execute();
	$rowsCu = $queryCu->fetchAll(PDO::FETCH_ASSOC);
	$numCu = $queryCu->rowCount();
	
	$aCountUser = array(0);
	foreach($rowsCu as $rowCu){
		array_push($aCountUser, $rowCu['id_countid']);
	}


	$queryL = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid,
											' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_langid,
											' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid,
											' . $CONFIG['db'][0]['prefix'] . '_templates_uni.title,
											' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country,
											' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language,
											' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.category
										FROM ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_ 
											
										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_promid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_langid
	
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_count = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_lang = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_dev = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_caid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid
											
										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
											
										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid
	
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_promid = (:id_promid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid IN (' . implode(',', $aCountUser) . ')
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.published_at = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.title, ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country, ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
										');
	$queryL->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryL->bindValue(':id_promid', $promid, PDO::PARAM_INT);
	$queryL->execute();
	$rowsL = $queryL->fetchAll(PDO::FETCH_ASSOC);
	$numL = $queryL->rowCount();
//			$arr = $queryL->errorInfo();
//			print_r($arr);

	$TEMPLATE_DEEPLINK = '';
	foreach($rowsL as $rowL){
		$aL = array();
		$aL['page'] = 116;
		$aL['country'] = $rowL['id_countid'];
		$aL['language'] = $rowL['id_langid'];
		$aL['data'] = $rowL['id_tempid'];
		$aL['function'] = 'rowEdit';
		
		
                $link = (isset($_SERVER['HTTPS'])) ? 'https' : 'http';
		$link .= '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?dl=' . base64_encode(json_encode($aL));
		$TEMPLATE_DEEPLINK .= $rowL['title'] . ' (' . $rowL['category'] . '): ' . $rowL['country'] . ' / ' . $rowL['language'] . ':<br><a href="' . $link . '">' . $link . '</a><br><br>';
	}


	$queryG = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_user.firstname,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.lastname,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.email
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_user 
											
										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid
											
										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang
	
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid = (:id_countid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_r = (:id_r)
										GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid
										');
	$queryG->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryG->bindValue(':id_countid', $rowC['id_countid'], PDO::PARAM_INT);
	$queryG->bindValue(':id_r', 3, PDO::PARAM_INT);
	$queryG->execute();
	$rowsG = $queryG->fetchAll(PDO::FETCH_ASSOC);
	$numG = $queryG->rowCount();
	
	$recipientsCC = array();
	foreach($rowsG as $rowG){
		if($countAdmin == 1 && $rowG['email'] != '') $recipientsCC[$rowG['email']] = $rowG['firstname'] . ' ' . $rowG['lastname'];
	}


	$mailing = array();
	$recipients = array();
	if($rowC['email'] != '') $recipients[$rowC['email']] = $rowC['firstname'] . ' ' . $rowC['lastname'];

	// Message
	$mailing['subject'] = $rowsN[0]['subject'];
	$mailing['body'] = '' . nl2br($rowsN[0]['notification']) . '';
	
	$mailing['body'] = str_replace('##CATEGORY##', $TEXT['PromotionPackages'], $mailing['body']);
	$mailing['body'] = str_replace('##TEMPLATETITLE##', $TEMPLATETITLE, $mailing['body']);
	$mailing['body'] = str_replace('##TEMPLATEID##', $TEMPLATEID, $mailing['body']);
	$mailing['body'] = str_replace('##TEMPLATE_DEEPLINK##', $TEMPLATE_DEEPLINK, $mailing['body']);
		
	mailSend(1, $mailing, $recipients);
}


//			$arr = $queryCA->errorInfo();
//			print_r($arr);



?>