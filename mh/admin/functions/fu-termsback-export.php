<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();


$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');



$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_tfe_data, 
										' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_tfeid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_count,
										' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_lang,
										' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_dev,
										' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_cl,
										' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.var,
										' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.term

									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_dev = (:id_dev)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.del = (:nultime)
									');
$query->bindValue(':id_count', $CONFIG['settings']['selectCountry'], PDO::PARAM_INT);
$query->bindValue(':id_lang', $CONFIG['settings']['selectLanguage'], PDO::PARAM_INT);
$query->bindValue(':id_dev', $CONFIG['settings']['selectDevice'], PDO::PARAM_INT);
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();










$fileCode = '';
if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 6, 1) == 9){
	if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 3, 1) == 9){
		if($CONFIG['USER']['activeSysCountry'] == 0){
			$fileCode .= 'all_';
		}else{
			$query = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_countries.code
												FROM ' . $CONFIG['db'][0]['prefix'] . 'system_countries 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_countries.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count = (:count)
												');
			$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query->bindValue(':count', $CONFIG['USER']['activeSysCountry'], PDO::PARAM_INT);
			$query->execute();
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);
			$num = $query->rowCount();
			if($num > 0) $fileCode .= $rows[0]['code'] . '_';
		}
	}
	if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 4, 1) == 9){
		if($CONFIG['USER']['activeSysLanguage'] == 0){
			$fileCode .= 'all_';
		}else{
			$query = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_languages.code
												FROM ' . $CONFIG['db'][0]['prefix'] . 'system_languages 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_languages.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang = (:lang)
												');
			$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query->bindValue(':lang', $CONFIG['USER']['activeSysLanguage'], PDO::PARAM_INT);
			$query->execute();
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);
			$num = $query->rowCount();
			if($num > 0) $fileCode .= $rows[0]['code'] . '_';
		}
	}
	if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 5, 1) == 9){
		if($CONFIG['USER']['activeSysDevice'] == 0){
			$fileCode .= 'all_';
		}else{
			$query = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_devices.code
												FROM ' . $CONFIG['db'][0]['prefix'] . 'system_devices 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_devices.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_devices.id_sys_dev = (:dev)
												');
			$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query->bindValue(':dev', $CONFIG['USER']['activeSysDevice'], PDO::PARAM_INT);
			$query->execute();
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);
			$num = $query->rowCount();
			if($num > 0) $fileCode .= $rows[0]['code'] . '_';
		}
	}
}else{
	if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 3, 1) == 9){
		if($CONFIG['USER']['activeCountry'] == 0){
			$fileCode .= 'all_';
		}else{
			$query = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code
												FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = (:count)
												');
			$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query->bindValue(':count', $CONFIG['USER']['activeCountry'], PDO::PARAM_INT);
			$query->execute();
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);
			$num = $query->rowCount();
			if($num > 0) $fileCode .= $rows[0]['code'] . '_';
		}
	}
	if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 4, 1) == 9){
		if($CONFIG['USER']['activeLanguage'] == 0){
			$fileCode .= 'all_';
		}else{
			$query = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code
												FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = (:lang)
												');
			$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query->bindValue(':lang', $CONFIG['USER']['activeLanguage'], PDO::PARAM_INT);
			$query->execute();
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);
			$num = $query->rowCount();
			if($num > 0) $fileCode .= $rows[0]['code'] . '_';
		}
	}
	if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 5, 1) == 9){
		if($CONFIG['USER']['activeDevice'] == 0){
			$fileCode .= 'all_';
		}else{
			$query = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.code
												FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_devices_uni.id_devid = (:dev)
												');
			$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query->bindValue(':dev', $CONFIG['USER']['activeDevice'], PDO::PARAM_INT);
			$query->execute();
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);
			$num = $query->rowCount();
			if($num > 0) $fileCode .= $rows[0]['code'] . '_';
		}
	}
}
$fileCode = rtrim($fileCode, '_');
if($fileCode != '') $fileCode = '_' . $fileCode;

$con = array();
$con['file'] = $varSQL['modul'] . '.xlsx';
$con['filename'] = $TEXT['TermsFE'].$fileCode.'_'.$now.'.xlsx';

include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-export.php');

echo json_encode($con);


?>