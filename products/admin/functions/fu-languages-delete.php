<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php'); 
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData(json_decode($_POST['data'], true));
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php'); 

$table = $CONFIG['db'][0]['prefix'] . 'sys_languages';
$primekey = 'id_langid';
$aDeleteAdd = array();


$date = new DateTime();
$now = $date->format('Y-m-d H:i:s'); 


include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-default-delete.php'); 



$query = $CONFIG['dbconn']->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages SET
									del = (:now)
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid = (:id_langid)
									');
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->bindValue(':id_langid', $varSQL['id'], PDO::PARAM_INT);
$query->execute();

?>