<?php
$CONFIG_TMP['user'] = array();

$queryT = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.algorithm,
										' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.key_token,
										' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.key_csrf,
										' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.token_refresh,
										' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.token_expire
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.id_cl IN (0, 1)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.del = (:nultime)
									');
$queryT->bindValue(':nul', 0, PDO::PARAM_INT);
$queryT->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryT->execute();
$rowsT = $queryT->fetchAll(PDO::FETCH_ASSOC);
$numT = $queryT->rowCount();

########################################################
if(!isset($_COOKIE['access'])){
	header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . 'error.php?logout=1');
	exit();
}

$aToken = explode('.', $_COOKIE['access']);
$aTokenHeader = json_decode(base64_decode($aToken[0]), true);
$aTokenContent = json_decode(base64_decode($aToken[1]), true); 


########################################################
if($_SERVER['HTTP_HOST'] != $aTokenContent['url']){
	header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . 'error.php?logout=2');
	exit();
}


########################################################
$date = new DateTime('-'.$rowsT[0]['token_expire'].'sec');
$expire = $date->format('Y-m-d H:i:s');
if($expire > $aTokenContent['create']){
	header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . 'error.php?logout=3');
	exit();
}


########################################################
$token = $aToken[0] . '.' . $aToken[1];
$hash = hash_hmac($rowsT[0]['algorithm'], $token, $rowsT[0]['key_token']);
$signature = base64_encode($hash);

if($signature != $aToken[2]){
	header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . 'error.php?logout=4');
	exit();
}


//########################################################
//if($aTokenContent['csrf'] != $_SERVER['HTTP_CSRFTOKEN']){
//	header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . 'error.php?logout=7');
//	exit();
//}


########################################################
// Check user
$queryR = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.id_ppid
									FROM ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni 

									WHERE ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.id_ppid = (:id)
									');
$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryR->bindValue(':id', $aTokenContent['user']['id_ppid'], PDO::PARAM_INT);
$queryR->execute();
$rowsR = $queryR->fetchAll(PDO::FETCH_ASSOC);
$numR = $queryR->rowCount();

if($numR != 1){
	header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . 'error.php?logout=5');
	exit();
}
###########################################################################




#####################################################################
#####################################################################
// Validated
#####################################################################
#####################################################################
$CONFIG['user'] = $aTokenContent['user'];
$CONFIG['user'] += $CONFIG_TMP['user'];

$date = new DateTime('-'.$rowsT[0]['token_refresh'].'sec');
$refresh = $date->format('Y-m-d H:i:s');
if($refresh > $aTokenContent['create']){
	createAccesstoken();
}
###########################################################################



?>