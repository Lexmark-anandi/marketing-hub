<?php
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-app.php');
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$aOrder = explode(',', $varSQL['order']);


$rank = 10;
foreach($aOrder as $order){
	$queryPr = $CONFIG['dbconn'][0]->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp SET
											rank = (:rank)
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_apid = (:id_apid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_ppid = (:id_ppid)
										');
	$queryPr->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$queryPr->bindValue(':id_apid', $order, PDO::PARAM_INT);
	$queryPr->bindValue(':rank', $rank, PDO::PARAM_INT);
	$queryPr->execute();
	
	$rank += 10;
}

?>