<?php
$queryP = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc.id_count2lang
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc.id_countid = (:id_countid)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc.id_langid = (:id_langid)
									LIMIT 1
									');
$queryP->bindValue(':id_countid', $aArgsSave['id_data'], PDO::PARAM_INT);
$queryP->bindValue(':id_langid', 0, PDO::PARAM_INT);
$queryP->execute();
$rowsP = $queryP->fetchAll(PDO::FETCH_ASSOC);
$numP = $queryP->rowCount();
if($numP == 0){
	$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_
				(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
			VALUES
				(:id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from)
			';
	$queryP = $CONFIG['dbconn'][0]->prepare($qry);
	$queryP->bindValue(':id_count', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
	$queryP->bindValue(':now', $now, PDO::PARAM_STR);
	$queryP->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
	$queryP->execute();
	$idNewP = $CONFIG['dbconn'][0]->lastInsertId();
}else{
	$idNewP = $rowsP[0]['id_count2lang'];
}


$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc
			(id_count2lang, id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from, id_countid, id_langid)
		VALUES
			(:id, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from, :id_countid, :id_langid)
		ON DUPLICATE KEY UPDATE 
			change_from = (:create_from),
			del = (:nultime)
		';
$queryP = $CONFIG['dbconn'][0]->prepare($qry);
$queryP->bindValue(':id', $idNewP, PDO::PARAM_INT);
$queryP->bindValue(':id_count', 0, PDO::PARAM_INT);
$queryP->bindValue(':id_lang', 0, PDO::PARAM_INT);
$queryP->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryP->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
$queryP->bindValue(':now', $now, PDO::PARAM_STR);
$queryP->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryP->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
$queryP->bindValue(':id_countid', $aArgsSave['id_data'], PDO::PARAM_INT);
$queryP->bindValue(':id_langid', 0, PDO::PARAM_INT);
$queryP->execute();
$numP = $queryP->rowCount();


$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
			(id_count2lang, id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from, id_countid, id_langid)
		VALUES
			(:id, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from, :id_countid, :id_langid)
		ON DUPLICATE KEY UPDATE 
			change_from = (:create_from),
			del = (:nultime)
		';
$queryP = $CONFIG['dbconn'][0]->prepare($qry);
$queryP->bindValue(':id', $idNewP, PDO::PARAM_INT);
$queryP->bindValue(':id_count', 0, PDO::PARAM_INT);
$queryP->bindValue(':id_lang', 0, PDO::PARAM_INT);
$queryP->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryP->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
$queryP->bindValue(':now', $now, PDO::PARAM_STR);
$queryP->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryP->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
$queryP->bindValue(':id_countid', $aArgsSave['id_data'], PDO::PARAM_INT);
$queryP->bindValue(':id_langid', 0, PDO::PARAM_INT);
$queryP->execute();
$numP = $queryP->rowCount();




?>