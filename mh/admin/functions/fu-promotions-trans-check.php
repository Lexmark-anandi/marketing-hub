<?php
$CONFIG['noCheck'] = true;
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();



$query = $CONFIG['dbconn'][0]->prepare('
									SELECT COUNT(' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpeid) AS num
									FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.content_transrequired = (:one)
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->bindValue(':one', 1, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();



echo $rows[0]['num']




?>