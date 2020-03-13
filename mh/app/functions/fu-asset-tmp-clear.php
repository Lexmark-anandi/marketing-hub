<?php
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-app.php');
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$queryCo = $CONFIG['dbconn'][0]->prepare('
									DELETE 
									FROM ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_ppid = (:id_ppid)
									');
$queryCo->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryCo->execute();

$queryCo = $CONFIG['dbconn'][0]->prepare('
									DELETE 
									FROM ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_ppid = (:id_ppid)
									');
$queryCo->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryCo->execute();

$queryCo = $CONFIG['dbconn'][0]->prepare('
									DELETE 
									FROM ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_ppid = (:id_ppid)
									');
$queryCo->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryCo->execute();

$queryCo = $CONFIG['dbconn'][0]->prepare('
									DELETE 
									FROM ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_ppid = (:id_ppid)
									');
$queryCo->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryCo->execute();
 

?>