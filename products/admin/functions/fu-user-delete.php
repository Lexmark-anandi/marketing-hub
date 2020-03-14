<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php'); 
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData(json_decode($_POST['data'], true));
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php'); 

$table = $CONFIG['db'][0]['prefix'] . 'sys_countries';
$primekey = 'id_countid';
$aDeleteAdd = array();


$date = new DateTime();
$now = $date->format('Y-m-d H:i:s'); 


$query = $CONFIG['dbconn']->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_user SET
										del = (:now),
										change_from = (:create_from)
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = (:id)
									LIMIT 1
									');
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
$query->bindValue(':create_from', $CONFIG['USER']['id_real'], PDO::PARAM_INT);
$query->execute();


$query = $CONFIG['dbconn']->prepare('
									DELETE FROM ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_uid = (:id)
									');
$query->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
$query->execute();

?>