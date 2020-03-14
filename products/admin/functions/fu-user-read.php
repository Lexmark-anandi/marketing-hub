<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php'); 
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData(json_decode($_POST['data'], true));
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php'); 

$out = array();
$all = array();

$queryD = $CONFIG['dbconn']->prepare(' 
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid as id,
										' . $CONFIG['db'][0]['prefix'] . 'system_user.firstname,
										' . $CONFIG['db'][0]['prefix'] . 'system_user.lastname,
										' . $CONFIG['db'][0]['prefix'] . 'system_user.email,
										' . $CONFIG['db'][0]['prefix'] . 'system_user.id_r,
										' . $CONFIG['db'][0]['prefix'] . 'system_user.username,
										' . $CONFIG['db'][0]['prefix'] . 'system_roles.role,
										' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_clid
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_user 
											
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_uid

									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_roles 
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_r = ' . $CONFIG['db'][0]['prefix'] . 'system_roles.id_r
	
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = (:id)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_clid = (:clid)
									');
$queryD->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryD->bindValue(':clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
$queryD->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
$queryD->execute();
$rowsD = $queryD->fetchAll(PDO::FETCH_ASSOC);
$numD = $queryD->rowCount();

$rowsD[0]['password'] = '';

$rowsD[0]['id_count'] = 0;
$rowsD[0]['id_lang'] = 0;
$rowsD[0]['id_dev'] = 0;

$aFields = array();
$aFields['yesNo2Text'] = array();
$aFields['floats'] = array();
$aFields['check2Radio'] = array();

$outTmp = array();
$outTmp['firstname'] = '';
$outTmp['lastname'] = '';
$outTmp['email'] = '';
$outTmp['id_r'] = 0;
$outTmp['username'] = '';
$outTmp['password'] = '';
$outTmp['role'] = '';
$outTmp['countries'] = array();
$outTmp['clients'] = array($CONFIG['USER']['activeClient']);


$identifier = array('##lastname', ', ', '##firstname');

$addColumns = array();
$addColumns['countries'] = array();

if($numD > 0){
	$query2 = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid = (:id_uid)
										');
	$query2->bindValue(':id_uid', $varSQL['id'], PDO::PARAM_INT);
	$query2->execute();
	$rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$num2 = $query2->rowCount();
	foreach($rows2 as $row2){
		array_push($addColumns['countries'], $row2['id_count2lang']);
	}
}

include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-default-read.php'); 



?>