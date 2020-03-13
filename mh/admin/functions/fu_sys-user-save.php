<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();


$aModul = ($CONFIG['page']['id_mod_parent'] == 0) ? $CONFIG['user']['pages2moduls'][$CONFIG['page']['id_page']]['moduls']['i_' . $CONFIG['page']['id_mod']] : $CONFIG['user']['childmoduls'][$CONFIG['page']['id_mod_parent']]['i_' . $CONFIG['page']['id_mod']];

// save main settings for user
if($aModul['specifications'][9] == 9){
	// save settings for system pages
	$query = $CONFIG['dbconn'][0]->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_user SET
											active_syscountry = (:country),
											active_syslanguage = (:language),
											active_sysdevice = (:device),
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = (:id_uid)
										LIMIT 1
										');
}else{
	// save settings for data pages
	$query = $CONFIG['dbconn'][0]->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_user SET
											active_country = (:country),
											active_language = (:language),
											active_device = (:device),
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = (:id_uid)
										LIMIT 1
										');
}
$query->bindValue(':country', $CONFIG['settings']['selectCountry'], PDO::PARAM_INT);
$query->bindValue(':language', $CONFIG['settings']['selectLanguage'], PDO::PARAM_INT);
$query->bindValue(':device', $CONFIG['settings']['selectDevice'], PDO::PARAM_INT);
$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
$query->execute();
$num = $query->rowCount();


###################################################
// save settings for modul
$aModulSrc = ($CONFIG['page']['id_mod_parent'] == 0) ? $CONFIG['user']['pages2moduls'][$CONFIG['page']['id_page']]['moduls']['i_' . $CONFIG['page']['id_mod']] : $CONFIG['user']['childmoduls'][$CONFIG['page']['id_mod_parent']]['i_' . $CONFIG['page']['id_mod']];

// parent moduls
foreach($CONFIG['user']['pages2moduls'] as &$aPages){
	foreach($aPages['moduls'] as &$aModul){
		if(($CONFIG['system']['synchronizeModulFilter'] == 1 && $aModul['specifications'][12] == 9 && $aModul['specifications'][9] == $aModulSrc['specifications'][9]) || $aModul['id_mod'] == $CONFIG['page']['id_mod']){
			$query = $CONFIG['dbconn'][0]->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_user2settings
													(id_uid, id_mod, id_mod_parent, id_page, active_country, active_language, active_device)
												VALUES
													(:id_uid, :id_mod, :id_mod_parent, :id_page, :active_country, :active_language, :active_device)
												ON DUPLICATE KEY UPDATE 
													active_country = (:active_country),
													active_language = (:active_language), 
													active_device = (:active_device),
													del = (:nultime)
												');

			$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
			$query->bindValue(':id_mod', $aModul['id_mod'], PDO::PARAM_INT);
			$query->bindValue(':id_mod_parent', 0, PDO::PARAM_INT);
			$query->bindValue(':id_page', 0, PDO::PARAM_INT); // $query->bindValue(':id_page', $CONFIG['page']['id_page'], PDO::PARAM_INT);
			$query->bindValue(':active_country', $CONFIG['settings']['selectCountry'], PDO::PARAM_INT);
			$query->bindValue(':active_language', $CONFIG['settings']['selectLanguage'], PDO::PARAM_INT);
			$query->bindValue(':active_device', $CONFIG['settings']['selectDevice'], PDO::PARAM_INT);
			$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query->execute();
		}
	}
}

// child moduls
foreach($CONFIG['user']['childmoduls'] as &$aParents){
	foreach($aParents as $id_mod => &$aModul){
		if(($CONFIG['system']['synchronizeModulFilter'] == 1 && $aModul['specifications'][12] == 9 && $aModul['specifications'][9] == $aModulSrc['specifications'][9]) || $aModul['id_mod'] == $CONFIG['page']['id_mod']){
			$query = $CONFIG['dbconn'][0]->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_user2settings
													(id_uid, id_mod, id_mod_parent, id_page, active_country, active_language, active_device)
												VALUES
													(:id_uid, :id_mod, :id_mod_parent, :id_page, :active_country, :active_language, :active_device)
												ON DUPLICATE KEY UPDATE 
													active_country = (:active_country),
													active_language = (:active_language), 
													active_device = (:active_device),
													del = (:nultime)
												');

			$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
			$query->bindValue(':id_mod', $aModul['id_mod'], PDO::PARAM_INT);
			$query->bindValue(':id_mod_parent', $id_mod, PDO::PARAM_INT);
			$query->bindValue(':id_page', 0, PDO::PARAM_INT); // $query->bindValue(':id_page', $CONFIG['page']['id_page'], PDO::PARAM_INT);
			$query->bindValue(':active_country', $CONFIG['settings']['selectCountry'], PDO::PARAM_INT);
			$query->bindValue(':active_language', $CONFIG['settings']['selectLanguage'], PDO::PARAM_INT);
			$query->bindValue(':active_device', $CONFIG['settings']['selectDevice'], PDO::PARAM_INT);
			$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query->execute();
		}
	}
}

?>