<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$aData = json_decode($varSQL['comp'], true);

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');


$query = $CONFIG['dbconn'][0]->prepare('
									INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_
									(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
									VALUES
									(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
									');
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
$query->bindValue(':create_at', $now, PDO::PARAM_STR);
$query->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
$query->execute();
$aArgsSave['id_data'] = $CONFIG['dbconn'][0]->lastInsertId();
$aArgsSave['id_cl'] = $CONFIG['activeSettings']['id_clid'];


//$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc
//			(id_tpeid, id_count, id_lang, id_dev, id_cl, id_tempid, id_tpid, id_tcid, position_left, position_top, width, height, create_at, create_from, change_from, del)
//		VALUES
//			(:id_tpeid, :id_count, :id_lang, :id_dev, :id_cl, :id_tempid, :id_tpid, :id_tcid, :position_left, :position_top, :width, :height, :now, :create_from, :create_from, :now)
//		';
//$queryC = $CONFIG['dbconn'][0]->prepare($qry);
//$queryC->bindValue(':id_tpeid', $aArgsSave['id_data'], PDO::PARAM_INT);
//$queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
//$queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
//$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
//$queryC->bindValue(':id_cl', $aArgsSave['id_cl'], PDO::PARAM_INT);
//$queryC->bindValue(':id_tempid', $aData['id_tempid'], PDO::PARAM_INT);
//$queryC->bindValue(':id_tpid', $aData['id_tpid'], PDO::PARAM_INT);
//$queryC->bindValue(':id_tcid', $aData['id_tcid'], PDO::PARAM_INT);
//$queryC->bindValue(':position_left', $aData['compLeftPerc'], PDO::PARAM_STR);
//$queryC->bindValue(':position_top', $aData['compTopPerc'], PDO::PARAM_STR);
//$queryC->bindValue(':width', $aData['compWidthPerc'], PDO::PARAM_STR);
//$queryC->bindValue(':height', $aData['compHeightPerc'], PDO::PARAM_STR);
//$queryC->bindValue(':now', $now, PDO::PARAM_STR);
//$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
//$queryC->execute();
//$numC = $queryC->rowCount();



echo $aArgsSave['id_data'];



?>