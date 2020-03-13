<?php
$CONFIG_TMP['user'] = array();

if(isset($_COOKIE['access'])){
	$aToken = explode('.', $_COOKIE['access']);
	$aTokenHeader = json_decode(base64_decode($aToken[0]), true);
	$aTokenContent = json_decode(base64_decode($aToken[1]), true);
	
	include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsApp'] . 'fu_sys-userinit-country.php'); 
//	include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-userinit-localization-data.php'); 
//	include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-userinit-localization-system.php'); 
//	include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-userinit-pages.php'); 
//	include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-userinit-moduls.php'); 
//	include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-userinit-functions.php'); 
//	include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-userinit-fields-grids.php'); 
//	include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-userinit-fields-forms.php'); 
//	include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-userinit-pages2moduls.php'); 
//	include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-userinit-childmoduls.php'); 
//	include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-userinit-restricted-all.php'); 
	
	###########################################################################
	$CONFIG['user'] = $aTokenContent['user'];
	$CONFIG['user'] += $CONFIG_I;

//	$CONFIG['system']['aFieldsAllowedSpecs'] = array(9);
//	if($CONFIG['user']['type'] == 'admin') $CONFIG['system']['aFieldsAllowedSpecs'] = array(1,2,9);
//	if($CONFIG['user']['type'] == 'systemadmin') $CONFIG['system']['aFieldsAllowedSpecs'] = array(0,2,9);
//	
//	$query = $CONFIG['dbconn'][0]->prepare('
//										UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_user SET
//											active_country = (:activeCountry), 
//											active_language = (:activeLanguage),
//											active_device = (:activeDevice),
//											active_client = (:activeClient),
//											active_syscountry = (:activeSysCountry), 
//											active_syslanguage = (:activeSysLanguage),
//											active_sysdevice = (:activeSysDevice),
//											system_country = (:systemCountry),
//											system_language = (:systemLanguage),
//											grid_num_rows = (:gridNumRows) 
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = (:id_uid)
//										LIMIT 1
//										');
//	$query->bindValue(':activeCountry', $CONFIG['activeSettings']['id_countid'], PDO::PARAM_INT);
//	$query->bindValue(':activeLanguage', $CONFIG['activeSettings']['id_langid'], PDO::PARAM_INT);
//	$query->bindValue(':activeDevice', $CONFIG['activeSettings']['id_devid'], PDO::PARAM_INT);
//	$query->bindValue(':activeClient', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
//	$query->bindValue(':activeSysCountry', $CONFIG['activeSettings']['id_sys_count'], PDO::PARAM_INT);
//	$query->bindValue(':activeSysLanguage', $CONFIG['activeSettings']['id_sys_lang'], PDO::PARAM_INT);
//	$query->bindValue(':activeSysDevice', $CONFIG['activeSettings']['id_sys_dev'], PDO::PARAM_INT);
//	$query->bindValue(':systemCountry', $CONFIG['activeSettings']['systemCountry'], PDO::PARAM_INT);
//	$query->bindValue(':systemLanguage', $CONFIG['activeSettings']['systemLanguage'], PDO::PARAM_STR);
//	$query->bindValue(':gridNumRows', $CONFIG['activeSettings']['gridNumRows'], PDO::PARAM_INT);
//	$query->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);
//	$query->execute();
//	$num = $query->rowCount();
//	
//	###########################################################################
	
	//var_dump($CONFIG);
}


function sortConfig($a,$b){
    return $a['rank'] < $b['rank'] ? -1 : $a['rank'] == $b['rank'] ? 0 : 1;
}

?>