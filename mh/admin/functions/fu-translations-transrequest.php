<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();


$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');



$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_ext SET
										transrequest_at = (:now),
										transrequest_from = (:transrequest_from)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_ext.id_tempid = (:id_tempid)
									');
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->bindValue(':transrequest_from', $CONFIG['user']['id'], PDO::PARAM_STR);
$query->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_STR);
$query->execute();

$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_loc SET
										transrequest_at = (:now), 
										transrequest_from = (:transrequest_from)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_loc.id_tempid = (:id_tempid)
									');
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->bindValue(':transrequest_from', $CONFIG['user']['id'], PDO::PARAM_STR);
$query->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_STR);
$query->execute();

$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_res SET
										transrequest_at = (:now),
										transrequest_from = (:transrequest_from)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_res.id_tempid = (:id_tempid)
									');
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->bindValue(':transrequest_from', $CONFIG['user']['id'], PDO::PARAM_STR);
$query->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_STR);
$query->execute();

$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni SET
										transrequest_at = (:now),
										transrequest_from = (:transrequest_from)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid = (:id_tempid)
									');
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->bindValue(':transrequest_from', $CONFIG['user']['id'], PDO::PARAM_STR);
$query->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_STR);
$query->execute();


#####################################################
// sending requests
#####################################################

// select template
$queryT = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid,
										' . $CONFIG['db'][0]['prefix'] . '_templates_uni.title
									FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev = (:id_dev)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid = (:id_tempid)
									');
$queryT->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryT->bindValue(':id_count', 0, PDO::PARAM_INT);
$queryT->bindValue(':id_lang', 0, PDO::PARAM_INT);
$queryT->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryT->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_INT);
$queryT->execute();
$rowsT = $queryT->fetchAll(PDO::FETCH_ASSOC);
$numT = $queryT->rowCount();

$TEMPLATEID = $rowsT[0]['id_tempid'];
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
$queryN->bindValue(':id_enid', 1, PDO::PARAM_INT);
$queryN->execute();
$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
$numN = $queryN->rowCount();

// select countries
$queryC = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_countid,
										' . $CONFIG['db'][0]['prefix'] . 'system_user.firstname,
										' . $CONFIG['db'][0]['prefix'] . 'system_user.lastname,
										' . $CONFIG['db'][0]['prefix'] . 'system_user.email
									FROM ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_ 


										
									INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_countid
										
									INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang
										
									INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid


									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_tempid = (:id_tempid)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_r = (:id_r)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid <> (:nul)
									GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid
									');
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_INT);
$queryC->bindValue(':id_r', 4, PDO::PARAM_INT);
$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
$queryC->execute();
$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
$numC = $queryC->rowCount();
//			$arr = $queryC->errorInfo();
//			print_r($arr);

foreach($rowsC as $rowC){
	$aL = array();
	$aL['page'] = $CONFIG['page']['id_page'];
	$aL['country'] = $rowC['id_countid'];
	$aL['data'] = $rowsT[0]['id_tempid'];
	$aL['function'] = 'rowEdit';
	$link = (isset($_SERVER['HTTPS'])) ? 'https' : 'http' . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?dl=' . base64_encode(json_encode($aL));
	$TEMPLATE_DEEPLINK = '<a href="' . $link . '">' . $link . '</a>';

//	// select country admins
//	$queryCA = $CONFIG['dbconn'][0]->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_user.firstname,
//											' . $CONFIG['db'][0]['prefix'] . 'system_user.lastname,
//											' . $CONFIG['db'][0]['prefix'] . 'system_user.email
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_user
//										
//										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
//											ON ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid
//										
//										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
//											ON ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang
//										
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.del = (:nultime)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_r = (:id_r)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid = (:id_countid)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid <> (:nul)
//										GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid
//										');
//	$queryCA->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$queryCA->bindValue(':id_r', 4, PDO::PARAM_INT);
//	$queryCA->bindValue(':id_countid', $rowC['id_countid'], PDO::PARAM_INT);
//	$queryCA->bindValue(':nul', 0, PDO::PARAM_INT);
//	$queryCA->execute();
//	$rowsCA = $queryCA->fetchAll(PDO::FETCH_ASSOC);
//	$numCA = $queryCA->rowCount();
	
	$mailing = array();
	$recipients = array();
//	if($numCA > 0){
		foreach($rowsC as $rowC){
			if($rowC['email'] != '') $recipients[$rowC['email']] = $rowC['firstname'] . ' ' . $rowC['lastname'];
		}
//	}
}


		// Message
		$mailing['subject'] = $rowsN[0]['subject'];
		$mailing['body'] = '' . nl2br($rowsN[0]['notification']) . '';
		
		$mailing['body'] = str_replace('##TEMPLATETITLE##', $TEMPLATETITLE, $mailing['body']);
		$mailing['body'] = str_replace('##TEMPLATEID##', $TEMPLATEID, $mailing['body']);
		$mailing['body'] = str_replace('##TEMPLATE_DEEPLINK##', $TEMPLATE_DEEPLINK, $mailing['body']);
		
		mailSend(1, $mailing, $recipients);


//			$arr = $queryCA->errorInfo();
//			print_r($arr);



?>