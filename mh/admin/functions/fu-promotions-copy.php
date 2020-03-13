<?php
$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$aTabs = array('ext', 'loc', 'res', 'uni');
 


$aNewId = array();
$aNewId['promotions'] = array(0 => 0);

$aNewId['promotions'] = array(0 => 0);
$queryI = $CONFIG['dbconn'][0]->prepare('
									INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_promotions_
									(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
									VALUES
									(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
									');
$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
$queryI->bindValue(':id_cl', 1, PDO::PARAM_INT); 
$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
$queryI->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
$queryI->execute();
$aNewId['promotions'][$CONFIG['page']['id_data']] = $CONFIG['dbconn'][0]->lastInsertId();

$pkey = 'id_prom_data';
$id = 'id_promid';
foreach($aNewId['promotions'] as $idOld => $idNew){
	// copy data
	foreach($aTabs as $tab){
		$table = $CONFIG['db'][0]['prefix'] . '_promotions_' . $tab;
	
		$qry = '';
		$qry .= 'DROP TEMPORARY TABLE IF EXISTS tmp; ';
		$qry .= 'CREATE TEMPORARY TABLE tmp LIKE ' . $table . '; ';
		$query = $CONFIG['dbconn'][0]->prepare($qry);
		$query->execute();
	
		$query = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $table . '.' . $pkey . ' AS pkey,
												' . $table . '.title  
											FROM ' . $table . ' 
											WHERE ' . $table . '.' . $id . ' = (:id)
											');
		$query->bindValue(':id', $idOld, PDO::PARAM_INT);
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		$num = $query->rowCount();
	
		foreach($rows as $row){
			$title = ($row['title'] == '') ? '' : $row['title'] . ' (copy)';
			
			$qry = 'TRUNCATE tmp; ';
			$qry .= 'INSERT tmp SELECT * FROM ' . $table . ' WHERE ' . $table . '.' . $pkey . ' = ' . $row['pkey'] . '; ';
			$qry .= 'UPDATE tmp SET ' . $pkey . ' = NULL; ';
			$qry .= 'UPDATE tmp SET ' . $id . ' = ' . $aNewId['promotions'][$idOld] . '; ';
			$qry .= 'UPDATE tmp SET title = "' . $title . '"; ';
			$qry .= 'UPDATE tmp SET transrequest_at = "0000-00-00 00:00:00"; ';
			$qry .= 'UPDATE tmp SET transrequest_from = 0; ';
			$qry .= 'UPDATE tmp SET published_at = "0000-00-00 00:00:00"; ';
			$qry .= 'UPDATE tmp SET published_from = 0; ';
			$qry .= 'UPDATE tmp SET create_at = "' . $now . '"; ';
			$qry .= 'UPDATE tmp SET create_from = ' . $CONFIG['user']['id_real'] . '; ';
			$qry .= 'UPDATE tmp SET change_at = "' . $now . '"; ';
			$qry .= 'UPDATE tmp SET change_from = ' . $CONFIG['user']['id_real'] . '; ';
			$qry .= 'INSERT ' . $table . ' SELECT * FROM tmp; ';
			$qry .= 'TRUNCATE tmp; ';
			$query2 = $CONFIG['dbconn'][0]->prepare($qry);
			$query2->execute();
		}
	}
}



$aTemplates = array();
foreach($aNewId['promotions'] as $idOld => $idNew){
	$queryT = $CONFIG['dbconn'][0]->prepare('
										SELECT DISTINCT(' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid)
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid = (:id)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid <> (:nul)
										');
	$queryT->bindValue(':id', $idOld, PDO::PARAM_INT);
	$queryT->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryT->execute();
	$rowsT = $queryT->fetchAll(PDO::FETCH_ASSOC);
	$numT = $queryT->rowCount();
	foreach($rowsT as $rowT){
		if(!in_array($rowT['id_tempid'], $aTemplates)) array_push($aTemplates, $rowT['id_tempid']);
	}
}



include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-templates-copy-include.php');



###############################################################
$pkey = 'id_prom2count';
$id = 'id_promid';
foreach($aNewId['promotions'] as $idOld => $idNew){
	// copy data
	$table = $CONFIG['db'][0]['prefix'] . '_promotions2countries_';

	$qry = '';
	$qry .= 'DROP TEMPORARY TABLE IF EXISTS tmp; ';
	$qry .= 'CREATE TEMPORARY TABLE tmp LIKE ' . $table . '; ';
	$query = $CONFIG['dbconn'][0]->prepare($qry);
	$query->execute();
	
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $table . '.' . $pkey . ' AS pkey
										FROM ' . $table . ' 
										WHERE ' . $table . '.' . $id . ' = (:id)
										');
	$query->bindValue(':id', $idOld, PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	
	foreach($rows as $row){
		$qry = 'TRUNCATE tmp; ';
		$qry .= 'INSERT tmp SELECT * FROM ' . $table . ' WHERE ' . $table . '.' . $pkey . ' = ' . $row['pkey'] . '; ';
		$qry .= 'UPDATE tmp SET ' . $pkey . ' = NULL; ';
		$qry .= 'UPDATE tmp SET ' . $id . ' = ' . $aNewId['promotions'][$idOld] . '; ';
		$qry .= 'UPDATE tmp SET transrequest_at = "0000-00-00 00:00:00"; ';
		$qry .= 'UPDATE tmp SET transrequest_from = 0; ';
		$qry .= 'UPDATE tmp SET published_at = "0000-00-00 00:00:00"; ';
		$qry .= 'UPDATE tmp SET published_from = 0; ';
		$qry .= 'UPDATE tmp SET create_at = "' . $now . '"; ';
		$qry .= 'UPDATE tmp SET create_from = ' . $CONFIG['user']['id_real'] . '; ';
		$qry .= 'UPDATE tmp SET change_at = "' . $now . '"; ';
		$qry .= 'UPDATE tmp SET change_from = ' . $CONFIG['user']['id_real'] . '; ';
		$qry .= 'INSERT ' . $table . ' SELECT * FROM tmp; ';
		$qry .= 'TRUNCATE tmp; ';
		$query2 = $CONFIG['dbconn'][0]->prepare($qry);
		$query2->execute();
	}
}
###############################################################


###############################################################
$pkey = 'id_prom2p';
$id = 'id_promid';
foreach($aNewId['promotions'] as $idOld => $idNew){
	// copy data
	$table = $CONFIG['db'][0]['prefix'] . '_promotions2products_';

	$qry = '';
	$qry .= 'DROP TEMPORARY TABLE IF EXISTS tmp; ';
	$qry .= 'CREATE TEMPORARY TABLE tmp LIKE ' . $table . '; ';
	$query = $CONFIG['dbconn'][0]->prepare($qry);
	$query->execute();
	
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $table . '.' . $pkey . ' AS pkey
										FROM ' . $table . ' 
										WHERE ' . $table . '.' . $id . ' = (:id)
										');
	$query->bindValue(':id', $idOld, PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	
	foreach($rows as $row){
		$qry = 'TRUNCATE tmp; ';
		$qry .= 'INSERT tmp SELECT * FROM ' . $table . ' WHERE ' . $table . '.' . $pkey . ' = ' . $row['pkey'] . '; ';
		$qry .= 'UPDATE tmp SET ' . $pkey . ' = NULL; ';
		$qry .= 'UPDATE tmp SET ' . $id . ' = ' . $aNewId['promotions'][$idOld] . '; ';
		$qry .= 'UPDATE tmp SET create_at = "' . $now . '"; ';
		$qry .= 'UPDATE tmp SET create_from = ' . $CONFIG['user']['id_real'] . '; ';
		$qry .= 'UPDATE tmp SET change_at = "' . $now . '"; ';
		$qry .= 'UPDATE tmp SET change_from = ' . $CONFIG['user']['id_real'] . '; ';
		$qry .= 'INSERT ' . $table . ' SELECT * FROM tmp; ';
		$qry .= 'TRUNCATE tmp; ';
		$query2 = $CONFIG['dbconn'][0]->prepare($qry);
		$query2->execute();
	}
}



echo $aNewId['promotions'][$CONFIG['page']['id_data']];


?>