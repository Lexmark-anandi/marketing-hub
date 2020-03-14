<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');
$aConf = json_decode($_SERVER['HTTP_USER'], true);
$varSQL = getPostData();

$query = $CONFIG['dbconn']->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_user SET
										langsys = (:langsys),
										active_country = (:active_country),
										active_language = (:active_language),
										active_device = (:active_device),
										active_syscountry = (:active_syscountry),
										active_syslanguage = (:active_syslanguage),
										active_sysdevice = (:active_sysdevice),
										active_client = (:active_client)
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = (:id_uid)
									LIMIT 1
									');
$query->bindValue(':langsys', $aConf['systemlang'], PDO::PARAM_STR);
$query->bindValue(':active_country', $aConf['activeCountry'], PDO::PARAM_INT);
$query->bindValue(':active_language', $aConf['activeLanguage'], PDO::PARAM_INT);
$query->bindValue(':active_device', $aConf['activeDevice'], PDO::PARAM_INT);
$query->bindValue(':active_syscountry', $aConf['activeSysCountry'], PDO::PARAM_INT);
$query->bindValue(':active_syslanguage', $aConf['activeSysLanguage'], PDO::PARAM_INT);
$query->bindValue(':active_sysdevice', $aConf['activeSysDevice'], PDO::PARAM_INT);
$query->bindValue(':active_client', $aConf['activeClient'], PDO::PARAM_INT);
$query->bindValue(':id_uid', $aConf['id'], PDO::PARAM_INT);
$query->execute();
$num = $query->rowCount();

$aChange = array();
$aChange['systemlang'] = $aConf['systemlang'];
$aChange['activeCountry'] = $aConf['activeCountry'];
$aChange['activeLanguage'] = $aConf['activeLanguage'];
$aChange['activeDevice'] = $aConf['activeDevice'];
$aChange['activeSysCountry'] = $aConf['activeSysCountry'];
$aChange['activeSysLanguage'] = $aConf['activeSysLanguage'];
$aChange['activeSysDevice'] = $aConf['activeSysDevice'];
$aChange['activeClient'] = $aConf['activeClient'];
//changeCookie($name='userconfig', $aChange);

echo $num;



?>