<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$aChangeCookie = array();
$aChangeCookie['systemCountry'] = $varSQL['count'];
$aChangeCookie['systemLanguage'] = $varSQL['lang'];
if(count($aChangeCookie) > 0) changeCookie($name='activesettings', $aChangeCookie);

$CONFIG['activeSettings']['systemCountry'] = $varSQL['count'];
$CONFIG['activeSettings']['systemLanguage'] = $varSQL['lang'];


$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_user SET
										system_country = (:systemCountry),
										system_language = (:systemLanguage)
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = (:id_uid)
									LIMIT 1
									');
$query->bindValue(':systemCountry', $varSQL['count'], PDO::PARAM_STR);
$query->bindValue(':systemLanguage', $varSQL['lang'], PDO::PARAM_STR);
$query->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);
$query->execute(); 
$num = $query->rowCount();



echo 'OK';

?>