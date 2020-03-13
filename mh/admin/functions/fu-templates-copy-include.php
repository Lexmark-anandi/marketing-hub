<?php

###############################################################
// create new IDs
###############################################################
$aNewId['templates'] = array();
foreach($aTemplates as $idT){
	$queryI = $CONFIG['dbconn'][0]->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_templates_
										(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
										VALUES
										(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
										');
	$queryI->bindValue(':nul', 0, PDO::PARAM_INT); 
	$queryI->bindValue(':id_cl', 1, PDO::PARAM_INT);
	$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
	$queryI->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
	$queryI->execute();
	$aNewId['templates'][$idT] = $CONFIG['dbconn'][0]->lastInsertId();
}
########################


########################
$aNewId['templatespages'] = array();
foreach($aNewId['templates'] as $idOld => $idNew){
	$queryI1 = $CONFIG['dbconn'][0]->prepare('
										SELECT DISTINCT(' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tpid)
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = (:id)
										');
	$queryI1->bindValue(':id', $idOld, PDO::PARAM_INT);
	$queryI1->execute();
	$rowsI1 = $queryI1->fetchAll(PDO::FETCH_ASSOC);
	$numI1 = $queryI1->rowCount();
	foreach($rowsI1 as $rowI1){
		$queryI = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_templatespages_
											(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
											VALUES
											(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
											');
		$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryI->bindValue(':id_cl', 1, PDO::PARAM_INT);
		$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
		$queryI->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
		$queryI->execute();
		$aNewId['templatespages'][$rowI1['id_tpid']] = $CONFIG['dbconn'][0]->lastInsertId();
	}
}
########################


########################
$aNewId['templatespageselements'] = array();
foreach($aNewId['templates'] as $idOld => $idNew){
	$queryI1 = $CONFIG['dbconn'][0]->prepare('
										SELECT DISTINCT(' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpeid)
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tempid = (:id)
										');
	$queryI1->bindValue(':id', $idOld, PDO::PARAM_INT);
	$queryI1->execute();
	$rowsI1 = $queryI1->fetchAll(PDO::FETCH_ASSOC);
	$numI1 = $queryI1->rowCount();
	foreach($rowsI1 as $rowI1){
		$queryI = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_
											(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
											VALUES
											(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
											');
		$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryI->bindValue(':id_cl', 1, PDO::PARAM_INT);
		$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
		$queryI->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
		$queryI->execute();
		$aNewId['templatespageselements'][$rowI1['id_tpeid']] = $CONFIG['dbconn'][0]->lastInsertId();
	}
}
########################


########################
$aNewId['bannerformats'] = array(0 => 0);
foreach($aNewId['templates'] as $idOld => $idNew){
	$queryI1 = $CONFIG['dbconn'][0]->prepare('
										SELECT DISTINCT(' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_bfid)
										FROM ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_tempid = (:id)
										');
	$queryI1->bindValue(':id', $idOld, PDO::PARAM_INT);
	$queryI1->execute();
	$rowsI1 = $queryI1->fetchAll(PDO::FETCH_ASSOC);
	$numI1 = $queryI1->rowCount();
	foreach($rowsI1 as $rowI1){
		$queryI = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_
											(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
											VALUES
											(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
											');
		$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryI->bindValue(':id_cl', 1, PDO::PARAM_INT);
		$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
		$queryI->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
		$queryI->execute();
		$aNewId['bannerformats'][$rowI1['id_bfid']] = $CONFIG['dbconn'][0]->lastInsertId();
	}
}
########################




###############################################################
// copy data
###############################################################
$pkey = 'id_temp_data';
$id = 'id_tempid';
foreach($aNewId['templates'] as $idOld => $idNew){
	// copy data
	foreach($aTabs as $tab){
		$table = $CONFIG['db'][0]['prefix'] . '_templates_' . $tab;
	
		$qry = '';
		$qry .= 'DROP TEMPORARY TABLE IF EXISTS tmp; ';
		$qry .= 'CREATE TEMPORARY TABLE tmp LIKE ' . $table . '; ';
		$query = $CONFIG['dbconn'][0]->prepare($qry);
		$query->execute();
	
		$query = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $table . '.' . $pkey . ' AS pkey,
												' . $table . '.title,
												' . $table . '.id_promid,
												' . $table . '.id_campid  
											FROM ' . $table . ' 
											WHERE ' . $table . '.' . $id . ' = (:id)
											');
		$query->bindValue(':id', $idOld, PDO::PARAM_INT);
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		$num = $query->rowCount();
	
		foreach($rows as $row){
			//$title = $row['title'] . ' (copy)';
			$title = ($row['title'] == '') ? '' : $row['title'] . ' (copy)';
			if($row['id_promid'] != 0 || $row['id_campid'] != 0) $title = $row['title'];
			
			$qry = 'TRUNCATE tmp; ';
			$qry .= 'INSERT tmp SELECT * FROM ' . $table . ' WHERE ' . $table . '.' . $pkey . ' = ' . $row['pkey'] . '; ';
			$qry .= 'UPDATE tmp SET ' . $pkey . ' = NULL; ';
			$qry .= 'UPDATE tmp SET ' . $id . ' = ' . $aNewId['templates'][$idOld] . '; ';
			$qry .= 'UPDATE tmp SET title = "' . $title . '"; ';
			$qry .= 'UPDATE tmp SET id_promid = ' . $aNewId['promotions'][$row['id_promid']] . '; ';
			$qry .= 'UPDATE tmp SET id_campid = ' . $aNewId['campaigns'][$row['id_campid']] . '; ';
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
###############################################################


###############################################################
$pkey = 'id_tp_data';
$id = 'id_tpid';
foreach($aNewId['templatespages'] as $idOld => $idNew){
	foreach($aTabs as $tab){
		$table = $CONFIG['db'][0]['prefix'] . '_templatespages_' . $tab;
	
		$qry = '';
		$qry .= 'DROP TEMPORARY TABLE IF EXISTS tmp; ';
		$qry .= 'CREATE TEMPORARY TABLE tmp LIKE ' . $table . '; ';
		$query = $CONFIG['dbconn'][0]->prepare($qry);
		$query->execute();
	
		$query = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $table . '.' . $pkey . ' AS pkey,
												' . $table . '.id_tempid,
												' . $table . '.id_bfid  
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
			$qry .= 'UPDATE tmp SET ' . $id . ' = ' . $aNewId['templatespages'][$idOld] . '; ';
			$qry .= 'UPDATE tmp SET id_tempid = ' . $aNewId['templates'][$row['id_tempid']] . '; ';
			$qry .= 'UPDATE tmp SET id_bfid = ' . $aNewId['bannerformats'][$row['id_bfid']] . '; ';
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
###############################################################


###############################################################
$pkey = 'id_tpe_data';
$id = 'id_tpeid';
foreach($aNewId['templatespageselements'] as $idOld => $idNew){
	foreach($aTabs as $tab){
		$table = $CONFIG['db'][0]['prefix'] . '_templatespageselements_' . $tab;
	
		$qry = '';
		$qry .= 'DROP TEMPORARY TABLE IF EXISTS tmp; ';
		$qry .= 'CREATE TEMPORARY TABLE tmp LIKE ' . $table . '; ';
		$query = $CONFIG['dbconn'][0]->prepare($qry);
		$query->execute();
	
		$query = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $table . '.' . $pkey . ' AS pkey,
												' . $table . '.id_tempid,
												' . $table . '.id_tpid,
												' . $table . '.page  
											FROM ' . $table . ' 
											WHERE ' . $table . '.' . $id . ' = (:id)
											');
		$query->bindValue(':id', $idOld, PDO::PARAM_INT);
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		$num = $query->rowCount();
	
		foreach($rows as $row){
			$p_id = '';
			$p_id = $aNewId['templatespages'][$rows[0]['id_tpid']] . '_' . $rows[0]['page'];
			if($p_id == '_') $p_id = '';

			$qry = 'TRUNCATE tmp; ';
			$qry .= 'INSERT tmp SELECT * FROM ' . $table . ' WHERE ' . $table . '.' . $pkey . ' = ' . $row['pkey'] . '; ';
			$qry .= 'UPDATE tmp SET ' . $pkey . ' = NULL; ';
			$qry .= 'UPDATE tmp SET ' . $id . ' = ' . $aNewId['templatespageselements'][$idOld] . '; ';
			$qry .= 'UPDATE tmp SET id_tempid = ' . $aNewId['templates'][$row['id_tempid']] . '; ';
			$qry .= 'UPDATE tmp SET id_tpid = ' . $aNewId['templatespages'][$row['id_tpid']] . '; ';
			$qry .= 'UPDATE tmp SET page_id = "' . $p_id . '"; ';
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
###############################################################


###############################################################
$pkey = 'id_bf_data';
$id = 'id_bfid';
foreach($aNewId['bannerformats'] as $idOld => $idNew){
	foreach($aTabs as $tab){
		$table = $CONFIG['db'][0]['prefix'] . '_bannerformats_' . $tab;
	
		$qry = '';
		$qry .= 'DROP TEMPORARY TABLE IF EXISTS tmp; ';
		$qry .= 'CREATE TEMPORARY TABLE tmp LIKE ' . $table . '; ';
		$query = $CONFIG['dbconn'][0]->prepare($qry);
		$query->execute();
	
		$query = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $table . '.' . $pkey . ' AS pkey,
												' . $table . '.id_tempid
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
			$qry .= 'UPDATE tmp SET ' . $id . ' = ' . $aNewId['bannerformats'][$idOld] . '; ';
			$qry .= 'UPDATE tmp SET id_tempid = ' . $aNewId['templates'][$row['id_tempid']] . '; ';
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
###############################################################







?>