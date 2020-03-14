<?php
$CONFIG_TMP['USER'] = array();

if(isset($_COOKIE['access'])){
	$aToken = explode('.', $_COOKIE['access']);
	$aTokenHeader = json_decode(base64_decode($aToken[0]), true);
	$aTokenContent = json_decode(base64_decode($aToken[1]), true);
	
	$CONFIG['USER_CONF'] = json_decode($_COOKIE['userconfig'],true);	
	
	
	include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-userinit-pages.php'); 
	include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-userinit-localization-data.php'); 
	include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-userinit-localization-system.php'); 
	
	
	
	###########################################################################
	// Array for clients
	$CONFIG_TMP['USER']['clients'] = array();
		
	//	$extQuery = ',
	//		' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.street,
	//		' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.zip,
	//		' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.city,
	//		' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.phone,
	//		' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.mobile,
	//		' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.fax,
	//		' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.email,
	//		' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.web
	$extQuery = '';
	$queryC = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid, 
											' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
											' . $extQuery . '
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni
										
										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_clid = ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid
											
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.active = (:active)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_uid = (:id_uid)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryC->bindValue(':active', 1, PDO::PARAM_INT);
	$queryC->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);
	
	if($aTokenContent['user']['right_client'] == 1){
		$queryC = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid, 
												' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
												' . $extQuery . '
											FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni
											
											INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_clid = ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid
												
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_uid = (:id_uid)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
											');
		$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryC->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);
	}
	
	if($aTokenContent['user']['right_client'] == 2){
		$queryC = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid, 
												' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
												' . $extQuery . '
											FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.active = (:active)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
											');
		$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryC->bindValue(':active', 1, PDO::PARAM_INT);
	}
	
	if($aTokenContent['user']['right_client'] == 9){
		$queryC = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid, 
												' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
												' . $extQuery . '
											FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_dev = (:nul)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
											');
		$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
	}
	
	$queryC->execute();
	$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
	$numC = $queryC->rowCount();
	$nC = 0;

	foreach($rowsC as $datC){
		$CONFIG_TMP['USER']['clients'][$datC['id_clid']] = array();
		
		foreach($datC as $key=>$val){
			$CONFIG_TMP['USER']['clients'][$datC['id_clid']][$key] = $val;
		}
	}
	//if(!array_key_exists($CONFIG['USER_CONF']['activeClient'], $CONFIG_TMP['USER']['clients'])){
	//	$CONFIG['USER_CONF']['activeClient'] = $CONFIG_TMP['USER']['clients'][$rowsC[0]['id_clid']];
	//}
	###########################################################################





	
	
	###########################################################################
	$CONFIG['USER'] = $aTokenContent['user'];
	$CONFIG['USER'] += json_decode($_COOKIE['userconfig'],true);
	$CONFIG['USER'] += $CONFIG_TMP['USER'];
	
	#######################################################
	if($CONFIG['USER_CONF']['activeCountry'] == NULL || !array_key_exists($CONFIG['USER_CONF']['activeCountry'], $CONFIG_TMP['USER']['countries'])){
		$aKey = array_keys($CONFIG_TMP['USER']['countries']);
		$CONFIG['USER_CONF']['activeCountry'] = $CONFIG_TMP['USER']['countries'][$aKey[0]]['id_countid'];
		if($aTokenContent['user']['right_editallcountries'] == 9) $CONFIG['USER_CONF']['activeCountry'] = 0;
	}

	if($CONFIG['USER_CONF']['activeLanguage'] == NULL || !in_array($CONFIG['USER_CONF']['activeLanguage'], $CONFIG_TMP['USER']['countries'][$CONFIG['USER_CONF']['activeCountry']]['languages'])){
		$CONFIG['USER_CONF']['activeLanguage'] = $CONFIG_TMP['USER']['countries'][$CONFIG['USER_CONF']['activeCountry']]['languages'][0];
		if($aTokenContent['user']['right_editalllanguages'] == 9 && $CONFIG['USER_CONF']['activeCountry'] == 0) $CONFIG['USER_CONF']['activeLanguage'] = 0;
	}

	if($CONFIG['USER_CONF']['activeDevice'] == NULL || !array_key_exists($CONFIG['USER_CONF']['activeDevice'], $CONFIG_TMP['USER']['devices'])){
		$aKey = array_keys($CONFIG_TMP['USER']['devices']);
		$CONFIG['USER_CONF']['activeDevice'] = $CONFIG_TMP['USER']['devices'][$aKey[0]]['id_devid'];
		if($aTokenContent['user']['right_editalldevices'] == 9) $CONFIG['USER_CONF']['activeDevice'] = 0;
	}
	

	$aChange = array();
	$aChange['activeCountry'] = $CONFIG['USER_CONF']['activeCountry'];
	$aChange['activeLanguage'] = $CONFIG['USER_CONF']['activeLanguage'];
	$aChange['activeDevice'] = $CONFIG['USER_CONF']['activeDevice'];
	changeCookie($name='userconfig', $aChange);
	
	
	#######################################################
	if($CONFIG['USER_CONF']['activeSysCountry']== NULL || !array_key_exists($CONFIG['USER_CONF']['activeSysCountry'], $CONFIG_TMP['USER']['syscountries'])){
		$aKey = array_keys($CONFIG_TMP['USER']['syscountries']);
		$CONFIG['USER_CONF']['activeSysCountry'] = $CONFIG_TMP['USER']['syscountries'][$aKey[0]]['id_sys_count'];
		if($aTokenContent['user']['right_editallcountries'] == 9) $CONFIG['USER_CONF']['activeSysCountry'] = 0;
	}

	if($CONFIG['USER_CONF']['activeSysLanguage'] == NULL || !in_array($CONFIG['USER_CONF']['activeSysLanguage'], $CONFIG_TMP['USER']['syscountries'][$CONFIG['USER_CONF']['activeSysCountry']]['languages'])){
		$CONFIG['USER_CONF']['activeSysLanguage'] = $CONFIG_TMP['USER']['syscountries'][$CONFIG['USER_CONF']['activeSysCountry']]['languages'][0];
		if($aTokenContent['user']['right_editalllanguages'] == 9 && $CONFIG['USER_CONF']['activeCountry'] == 0) $CONFIG['USER_CONF']['activeSysLanguage'] = 0;
	}

	if($CONFIG['USER_CONF']['activeSysDevice'] == NULL || !array_key_exists($CONFIG['USER_CONF']['activeSysDevice'], $CONFIG_TMP['USER']['sysdevices'])){
		$aKey = array_keys($CONFIG_TMP['USER']['sysdevices']);
		$CONFIG['USER_CONF']['activeSysDevice'] = $CONFIG_TMP['USER']['sysdevices'][$aKey[0]]['id_sys_dev'];
		if($aTokenContent['user']['right_editalldevices'] == 9) $CONFIG['USER_CONF']['activeSysDevice'] = 0;
	}

	$aChange = array();
	$aChange['activeSysCountry'] = $CONFIG['USER_CONF']['activeSysCountry'];
	$aChange['activeSysLanguage'] = $CONFIG['USER_CONF']['activeSysLanguage'];
	$aChange['activeSysDevice'] = $CONFIG['USER_CONF']['activeSysDevice'];
	changeCookie($name='userconfig', $aChange);
	
	
	$query = $CONFIG['dbconn']->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_user SET
											active_country = (:activeCountry), 
											active_language = (:activeLanguage),
											active_device = (:activeDevice) ,
											active_syscountry = (:activeSysCountry), 
											active_syslanguage = (:activeSysLanguage),
											active_sysdevice = (:activeSysDevice) 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = (:id_uid)
										LIMIT 1
										');
	$query->bindValue(':activeCountry', $CONFIG['USER_CONF']['activeCountry'], PDO::PARAM_INT);
	$query->bindValue(':activeLanguage', $CONFIG['USER_CONF']['activeLanguage'], PDO::PARAM_INT);
	$query->bindValue(':activeDevice', $CONFIG['USER_CONF']['activeDevice'], PDO::PARAM_INT);
	$query->bindValue(':activeSysCountry', $CONFIG['USER_CONF']['activeSysCountry'], PDO::PARAM_INT);
	$query->bindValue(':activeSysLanguage', $CONFIG['USER_CONF']['activeSysLanguage'], PDO::PARAM_INT);
	$query->bindValue(':activeSysDevice', $CONFIG['USER_CONF']['activeSysDevice'], PDO::PARAM_INT);
	$query->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);
	$query->execute();
	$num = $query->rowCount();
	
	###########################################################################
	
	//var_dump($CONFIG['USER']);
}

?>