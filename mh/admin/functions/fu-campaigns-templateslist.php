<?php
$CONFIG['noCheck'] = true;
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$out = '';

$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.title
									FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_campid = (:id_campid)
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR); 
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->bindValue(':id_campid', $varSQL['prom'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

foreach($rows as $row){
	$out .= '<div>' . $row['title'] . '</div>';
}

echo $out




?>