<?php

$cols = $primekey . ', id_count, id_lang, id_dev, id_clid, create_at, create_from, change_from';
$values = '(:' . $primekey . '), (:id_count), (:id_lang), (:id_dev), (:id_clid), (:create_at), (:create_from), (:create_from)';
foreach($aFields as $field => $aField){
	$cols .= ', ' . $field;
	$values .= ', (:' . $field . ')';
}
$changedVersions = array();


$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.modulname = (:modulname)
									');
$query->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
$query->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
$query->bindValue(':modulname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

foreach($rows as $row){
	$aData = json_decode($row['data'], true);
	$aData['id_count'] = $row['id_count'];
	$aData['id_lang'] = $row['id_lang'];
	$aData['id_dev'] = $row['id_dev'];
	$aData['id_clid'] = $row['id_clid'];

	$query = $CONFIG['dbconn']->prepare('
										INSERT INTO ' . $table . '
										(create_at, create_from, change_from)
										VALUES
										(:create_at, :create_from, :create_from)
										');
	$query->bindValue(':create_at', $now, PDO::PARAM_STR);
	$query->bindValue(':create_from', $CONFIG['USER']['id_real'], PDO::PARAM_INT);
	$query->execute();
	$idNew = $CONFIG['dbconn']->lastInsertId();
	
	// Insert Master
	array_push($changedVersions, array(0, 0, 0));
	$query = $CONFIG['dbconn']->prepare('
										INSERT INTO ' . $table . '_loc
										(' . $cols . ')
										VALUES
										(' . $values . ')
										');
	$query->bindValue(':'.$primekey, $idNew, PDO::PARAM_INT);
	$query->bindValue(':id_count', 0, PDO::PARAM_INT);
	$query->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$query->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$query->bindValue(':id_clid', $aData['id_clid'], PDO::PARAM_INT);
	$query->bindValue(':create_at', $now, PDO::PARAM_STR);
	$query->bindValue(':create_from', $CONFIG['USER']['id_real'], PDO::PARAM_INT);
	
	foreach($aFields as $field => $aField){
		if($aField[1] == 'd'){
			$query->bindValue(':'.$field, trim($aData[$aField[0]]), PDO::PARAM_INT);
		}else{
			$query->bindValue(':'.$field, trim($aData[$aField[0]]), PDO::PARAM_STR);
		}
	}
	$query->execute();
	 
	// Insert local variation
	if($aData['id_count'] != 0 || $aData['id_lang'] != 0 || $aData['id_dev'] != 0){
		array_push($changedVersions, array($aData['id_count'], $aData['id_lang'], $aData['id_dev']));
	
		$query = $CONFIG['dbconn']->prepare('
											INSERT INTO ' . $table . '_loc
											(' . $cols . ')
											VALUES
											(' . $values . ')
											');
		$query->bindValue(':'.$primekey, $idNew, PDO::PARAM_INT);
		$query->bindValue(':id_count', $aData['id_count'], PDO::PARAM_INT);
		$query->bindValue(':id_lang', $aData['id_lang'], PDO::PARAM_INT);
		$query->bindValue(':id_dev', $aData['id_dev'], PDO::PARAM_INT);
		$query->bindValue(':id_clid', $aData['id_clid'], PDO::PARAM_INT);
		$query->bindValue(':create_at', $now, PDO::PARAM_STR);
		$query->bindValue(':create_from', $CONFIG['USER']['id_real'], PDO::PARAM_INT);
		
		foreach($aFields as $field => $aField){
			if($aField[1] == 'd'){
				$query->bindValue(':'.$field, trim($aData[$aField[0]]), PDO::PARAM_INT);
			}else{
				$query->bindValue(':'.$field, trim($aData[$aField[0]]), PDO::PARAM_STR);
			}
		}
		$query->execute();
	}
	
	$aData['id'] = $idNew;
	if(function_exists('addSave')) addSave($aData);
	

	$query2 = $CONFIG['dbconn']->prepare('
										DELETE 
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid = (:id_clid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.modulname = (:modulname)
										LIMIT 1
										');
	$query2->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
	$query2->bindValue(':id_count', $aData['id_count'], PDO::PARAM_INT);
	$query2->bindValue(':id_lang', $aData['id_lang'], PDO::PARAM_INT);
	$query2->bindValue(':id_dev', $aData['id_dev'], PDO::PARAM_INT);
	$query2->bindValue(':id_clid', $aData['id_clid'], PDO::PARAM_INT);
	$query2->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
	$query2->bindValue(':modulname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
	$query2->execute();
}

insertAll($varSQL['modul'], $table, $primekey, $idNew, $columns, $aFieldsNumbers, $changedVersions, '');

$out = array();
$out['id'] = $idNew;

echo json_encode($out);

?>