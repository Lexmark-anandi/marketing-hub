<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$functionPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'];
$functionFile = 'fu-' . $CONFIG['aModul']['modul_name'] . '-update.php';
$functionFileOne2n = 'fu-' . $CONFIG['aModul']['modul_name'] . '-update-one2n.php';
$functionFilePre = 'fu-' . $CONFIG['aModul']['modul_name'] . '-update-pre.php';
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php');


$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');


$baseFactor = 4;
$aDashboard = json_decode($varSQL['grid'], true);

foreach($aDashboard as &$singleGrid){
	$singleGrid['col'] = (($singleGrid['col'] - 1) + $baseFactor) / $baseFactor;
	$singleGrid['size_x'] = $singleGrid['size_x'] / $baseFactor;
	$singleGrid['row'] = (($singleGrid['row'] - 1) + $baseFactor) / $baseFactor;
	$singleGrid['size_y'] = $singleGrid['size_y'] / $baseFactor;
}

$dashboard = json_encode($aDashboard, JSON_NUMERIC_CHECK);


$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_dashboard2user SET
									dashboard = (:dashboard)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_dashboard2user.id_uid = (:id_uid)
									LIMIT 1
									'); 
$query->bindValue(':dashboard', $dashboard, PDO::PARAM_STR);
$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

echo 'OK';



?>