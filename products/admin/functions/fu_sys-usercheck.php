<?php
$CONFIG_TMP['USER'] = array();

$queryT = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_config_access.algorithm,
										' . $CONFIG['db'][0]['prefix'] . 'system_config_access.key_token,
										' . $CONFIG['db'][0]['prefix'] . 'system_config_access.key_csrf,
										' . $CONFIG['db'][0]['prefix'] . 'system_config_access.token_refresh,
										' . $CONFIG['db'][0]['prefix'] . 'system_config_access.token_expire
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_config_access
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_config_access.del = (:nultime)
									');
$queryT->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryT->execute();
$rowsT = $queryT->fetchAll(PDO::FETCH_ASSOC);
$numT = $queryT->rowCount();

########################################################
if(!isset($_COOKIE['access'])){
	header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=1');
	exit();
}

$aToken = explode('.', $_COOKIE['access']);
$aTokenHeader = json_decode(base64_decode($aToken[0]), true);
$aTokenContent = json_decode(base64_decode($aToken[1]), true);


########################################################
if($_SERVER['HTTP_HOST'] != $aTokenContent['url']){
	header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=2');
	exit();
}


########################################################
$date = new DateTime('-'.$rowsT[0]['token_expire'].'sec');
$expire = $date->format('Y-m-d H:i:s');
if($expire > $aTokenContent['create']){
	header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=3');
	exit();
}


########################################################
$token = $aToken[0] . '.' . $aToken[1];
$hash = hash_hmac($rowsT[0]['algorithm'], $token, $rowsT[0]['key_token']);
$signature = base64_encode($hash);

if($signature != $aToken[2]){
	header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=4');
	exit();
}


########################################################
// Check user
$queryR = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_user 
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_roles
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_r = ' . $CONFIG['db'][0]['prefix'] . 'system_roles.id_r

									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = (:id)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_r = (:id_r)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_roles.local_specific = (:rights)
									');
$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryR->bindValue(':id', $aTokenContent['user']['id'], PDO::PARAM_INT);
$queryR->bindValue(':id_r', $aTokenContent['user']['right'], PDO::PARAM_INT);
$queryR->bindValue(':rights', $aTokenContent['user']['rights_specific'], PDO::PARAM_STR);
$queryR->execute();
$rowsR = $queryR->fetchAll(PDO::FETCH_ASSOC);
$numR = $queryR->rowCount();

if($numR != 1){
	header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=5');
	exit();
}
###########################################################################




#####################################################################
#####################################################################
// Validated
#####################################################################
#####################################################################
$CONFIG['USER'] = $aTokenContent['user'];
$CONFIG['USER'] += json_decode($_COOKIE['userconfig'],true);
$CONFIG['USER'] += $CONFIG_TMP['USER'];

$date = new DateTime('-'.$rowsT[0]['token_refresh'].'sec');
$refresh = $date->format('Y-m-d H:i:s');
if($refresh > $aTokenContent['create']){
	createAccesstoken();
}
###########################################################################



?>