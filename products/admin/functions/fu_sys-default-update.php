<?php

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

$changedVersions = array();
foreach($rows as $row){
	$aData = json_decode($row['data'], true);
	$aData['id_count'] = $row['id_count'];
	$aData['id_lang'] = $row['id_lang'];
	$aData['id_dev'] = $row['id_dev'];
	$aData['id_clid'] = $row['id_clid'];


	$aChange = checkChanges($table, $primekey, $listFields, $columns, $aData);
	$aChangedFields = $aChange[0];
	
	// Check if record exists
	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $table . '_loc.' . $primekey . '
										FROM ' . $table . '_loc 
										WHERE ' . $table . '_loc.' . $primekey . ' = (:primekey)
											AND ' . $table . '_loc.id_count = (:id_count)
											AND ' . $table . '_loc.id_lang = (:id_lang)
											AND ' . $table . '_loc.id_dev = (:id_dev)
											AND ' . $table . '_loc.id_clid = (:id_clid)
											AND ' . $table . '_loc.del = (:nultime)
										');
	$query->bindValue(':primekey', $varSQL['id'], PDO::PARAM_INT);
	$query->bindValue(':id_count', $aData['id_count'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $aData['id_lang'], PDO::PARAM_INT);
	$query->bindValue(':id_dev', $aData['id_dev'], PDO::PARAM_INT);
	$query->bindValue(':id_clid', $aData['id_clid'], PDO::PARAM_INT);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	
	if(count($aChangedFields) > 0 || $num == 0){
		array_push($changedVersions, array($aData['id_count'], $aData['id_lang'], $aData['id_dev']));
		
		if($num == 0){
			$col2 = '';
			$value2 = '';
			foreach($aChangedFields as $val2){
				$col2 .= ', ' . $val2;
				$value2 .= ', (:' . $val2 . ')';
			}
			
			$query = $CONFIG['dbconn']->prepare('
												INSERT INTO ' . $table . '_loc
												(' . $primekey . ', id_count, id_lang, id_dev, id_clid, create_at, create_from, change_from' . $col2 . ')
												VALUES
												(:primekey, :id_count, :id_lang, :id_dev, :id_clid, :now, :change_from, :change_from' . $value2 . ')
												');
			$query->bindValue(':primekey', $aData['id'], PDO::PARAM_INT);
			$query->bindValue(':id_count', $aData['id_count'], PDO::PARAM_INT);
			$query->bindValue(':id_lang', $aData['id_lang'], PDO::PARAM_INT);
			$query->bindValue(':id_dev', $aData['id_dev'], PDO::PARAM_INT);
			$query->bindValue(':id_clid', $aData['id_clid'], PDO::PARAM_INT);
			$query->bindValue(':now', $now, PDO::PARAM_STR);
			$query->bindValue(':change_from', $CONFIG['USER']['id_real'], PDO::PARAM_INT); 
			
			foreach($aChangedFields as $val2){
				if($listFields[$val2][1] == 'd'){
					$query->bindValue(':'.$val2, trim($aData[$listFields[$val2][0]]), PDO::PARAM_INT);
				}else{ 
					$query->bindValue(':'.$val2, trim($aData[$listFields[$val2][0]]), PDO::PARAM_STR);
				}
			}
		}else{
			$upd2 = '';
			foreach($aChangedFields as $val2){
				$upd2 .= $val2.' = (:'.$val2.'), ' ;
			}
	
			$query = $CONFIG['dbconn']->prepare('
												UPDATE ' . $table . '_loc SET
												' . $upd2 . '
												change_from = (:change_from)
												WHERE ' . $table . '_loc.' . $primekey . ' = (:primekey)
													AND ' . $table . '_loc.id_count = (:id_count)
													AND ' . $table . '_loc.id_lang = (:id_lang)
													AND ' . $table . '_loc.id_dev = (:id_dev)
													AND ' . $table . '_loc.id_clid = (:id_clid)
													AND ' . $table . '_loc.del = (:nultime)
												LIMIT 1
												');
			$query->bindValue(':primekey', $aData['id'], PDO::PARAM_INT);
			$query->bindValue(':id_count', $aData['id_count'], PDO::PARAM_INT);
			$query->bindValue(':id_lang', $aData['id_lang'], PDO::PARAM_INT);
			$query->bindValue(':id_dev', $aData['id_dev'], PDO::PARAM_INT);
			$query->bindValue(':id_clid', $aData['id_clid'], PDO::PARAM_INT);
			$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR); 
			$query->bindValue(':change_from', $CONFIG['USER']['id_real'], PDO::PARAM_INT); 
			
			foreach($aChangedFields as $val2){
				if($listFields[$val2][1] == 'd'){
					$query->bindValue(':'.$val2, trim($aData[$listFields[$val2][0]]), PDO::PARAM_INT);
				}else{
					$query->bindValue(':'.$val2, trim($aData[$listFields[$val2][0]]), PDO::PARAM_STR);
				}
			}
		}
		$query->execute();
	}

	if(function_exists('addSave')) addSave($aData);
}
	
insertAll($varSQL['modul'], $table, $primekey, $aData['id'], $columns, $aFieldsNumbers, $changedVersions, '');

$out = array();
$out['id'] = $aData['id'];

echo json_encode($out);

?>