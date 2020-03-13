<?php
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-app.php');
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');



$queryPr = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_assets_loc SET
										del = (:now)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_assets_loc.id_asid = (:id_asid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_assets_loc.id_pcid = (:id_pcid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_assets_loc.id_ppid = (:id_ppid)
									');
$queryPr->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
$queryPr->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryPr->bindValue(':id_asid', $varSQL['asid'], PDO::PARAM_INT);
$queryPr->bindValue(':now', $now, PDO::PARAM_INT);
$queryPr->execute();

$queryPr = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_assets_uni SET
										del = (:now)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_asid = (:id_asid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_pcid = (:id_pcid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_ppid = (:id_ppid)
									');
$queryPr->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
$queryPr->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryPr->bindValue(':id_asid', $varSQL['asid'], PDO::PARAM_INT);
$queryPr->bindValue(':now', $now, PDO::PARAM_INT);
$queryPr->execute();
	

?>