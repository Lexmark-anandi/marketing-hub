<?php
$aT = array('ext', 'loc', 'uni');
foreach($aT as $t){
	$queryDel = $CONFIG['dbconn']->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . '_techvalues_' . $t . ' SET
											del = (:now)
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_techvalues_' . $t . '.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_' . $t . '.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_' . $t . '.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_' . $t . '.id_clid = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_' . $t . '.id_pid = (:id_pid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_' . $t . '.id_tsid = (:id_tsid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_' . $t . '.del = (:nultime)
										');
	$queryDel->bindValue(':now', $now, PDO::PARAM_STR);
	$queryDel->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryDel->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryDel->bindValue(':id_pid', $aProduct['id_pid'], PDO::PARAM_INT);
	$queryDel->bindValue(':id_tsid', $aTechspec['id_tsid'], PDO::PARAM_INT);
	$queryDel->bindValue(':id_count', $id_count, PDO::PARAM_INT);
	$queryDel->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
	$queryDel->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryDel->execute();
}


foreach($attribute->value as $value) {	
	$aChangedVersions = array();

	$aTechvalue = array();
	$aTechvalue['id_pid'] = $aProduct['id_pid']; 
	$aTechvalue['id_tsid'] = $aTechspec['id_tsid'];
	$aTechvalue['attribute_id'] = $aTechspec['attribute_id'];
	$aTechvalue['value'] = $value;
	$aTechvalue['value_id'] = $value[@value_id];
	
	// search for value_id
	$queryPt = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext.id_tvid
										FROM ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext 
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext.value_id = (:value_id)
										');
	$queryPt->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryPt->bindValue(':value_id', $aTechvalue['value_id'], PDO::PARAM_INT);
	$queryPt->execute();
	$rowsPt = $queryPt->fetchAll(PDO::FETCH_ASSOC);
	$numPt = $queryPt->rowCount();

	if($numPt == 0){
		// first time for all / all
		foreach($aArgs['aListLanguagesByCountries'][$id_count] as $lang){
			if($lang != 0) array_push($aChangedVersions, array($id_count, $lang, 0));
		}

		$queryPt2 = $CONFIG['dbconn']->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_techvalues
												(create_at, create_from)
											VALUES
												(:create_at, :create_from)
											');
		$queryPt2->bindValue(':create_at', $now, PDO::PARAM_STR);
		$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
		$queryPt2->execute();
		$aTechvalue['id_tvid'] = $CONFIG['dbconn']->lastInsertId();
		
		// save all / all
		$queryPt2 = $CONFIG['dbconn']->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext
												(id_count, id_lang, id_dev, create_at, create_from, ' . implode(', ', array_keys($aTechvalue)) . ')
											VALUES
												(:id_count, :id_lang, :id_dev, :create_at, :create_from, :' . implode(', :', array_keys($aTechvalue)) . ')
											');
		$queryPt2->bindValue(':id_count', 0, PDO::PARAM_INT);
		$queryPt2->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
		$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
		foreach($aTechvalue as $field => $value){
			$queryPt2->bindValue(':'.$field, $value, PDO::PARAM_STR);
		}
		$queryPt2->execute();
		
		$queryPt2 = $CONFIG['dbconn']->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_techvalues_uni
												(id_count, id_lang, id_dev, create_at, create_from, ' . implode(', ', array_keys($aTechvalue)) . ')
											VALUES
												(:id_count, :id_lang, :id_dev, :create_at, :create_from, :' . implode(', :', array_keys($aTechvalue)) . ')
											');
		$queryPt2->bindValue(':id_count', 0, PDO::PARAM_INT);
		$queryPt2->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
		$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
		foreach($aTechvalue as $field => $value){
			$queryPt2->bindValue(':'.$field, $value, PDO::PARAM_STR);
		}
		$queryPt2->execute();
		
		// save country / language
		$queryPt2 = $CONFIG['dbconn']->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext
												(id_count, id_lang, id_dev, create_at, create_from, ' . implode(', ', array_keys($aTechvalue)) . ')
											VALUES
												(:id_count, :id_lang, :id_dev, :create_at, :create_from, :' . implode(', :', array_keys($aTechvalue)) . ')
											');
		$queryPt2->bindValue(':id_count', $id_count, PDO::PARAM_INT);
		$queryPt2->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
		$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
		$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
		foreach($aTechvalue as $field => $value){
			$queryPt2->bindValue(':'.$field, $value, PDO::PARAM_STR);
		}
		$queryPt2->execute();
		
	}else{
		$aTechvalue['id_tvid'] = $rowsPt[0]['id_tvid'];

		// search for value_id and tsid and pid
		$queryPtX = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext.id_tvid
											FROM ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext 
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext.id_clid = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext.id_pid = (:id_pid)
												AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext.id_tsid = (:id_tsid)
												AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext.value_id = (:value_id)
											');
		$queryPtX->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryPtX->bindValue(':id_pid', $aTechvalue['id_pid'], PDO::PARAM_INT);
		$queryPtX->bindValue(':id_tsid', $aTechvalue['id_tsid'], PDO::PARAM_INT);
		$queryPtX->bindValue(':value_id', $aTechvalue['value_id'], PDO::PARAM_INT);
		$queryPtX->execute();
		$rowsPtX = $queryPtX->fetchAll(PDO::FETCH_ASSOC);
		$numPtX = $queryPtX->rowCount();
		
		if($numPtX == 0){
			// first time for all / all / tsid / pid
			foreach($aArgs['aListLanguagesByCountries'][$id_count] as $lang){
				if($lang != 0) array_push($aChangedVersions, array($id_count, $lang, 0));
			}

			// save all / all
			$queryPt2 = $CONFIG['dbconn']->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext
													(id_count, id_lang, id_dev, create_at, create_from, ' . implode(', ', array_keys($aTechvalue)) . ')
												VALUES
													(:id_count, :id_lang, :id_dev, :create_at, :create_from, :' . implode(', :', array_keys($aTechvalue)) . ')
												');
			$queryPt2->bindValue(':id_count', 0, PDO::PARAM_INT);
			$queryPt2->bindValue(':id_lang', 0, PDO::PARAM_INT);
			$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
			$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
			foreach($aTechvalue as $field => $value){
				$queryPt2->bindValue(':'.$field, $value, PDO::PARAM_STR);
			}
			$queryPt2->execute();
			
			$queryPt2 = $CONFIG['dbconn']->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_techvalues_uni
													(id_count, id_lang, id_dev, create_at, create_from, ' . implode(', ', array_keys($aTechvalue)) . ')
												VALUES
													(:id_count, :id_lang, :id_dev, :create_at, :create_from, :' . implode(', :', array_keys($aTechvalue)) . ')
												');
			$queryPt2->bindValue(':id_count', 0, PDO::PARAM_INT);
			$queryPt2->bindValue(':id_lang', 0, PDO::PARAM_INT);
			$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
			$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
			foreach($aTechvalue as $field => $value){
				$queryPt2->bindValue(':'.$field, $value, PDO::PARAM_STR);
			}
			$queryPt2->execute();
			
			// save country / language
			$queryPt2 = $CONFIG['dbconn']->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext
													(id_count, id_lang, id_dev, create_at, create_from, ' . implode(', ', array_keys($aTechvalue)) . ')
												VALUES
													(:id_count, :id_lang, :id_dev, :create_at, :create_from, :' . implode(', :', array_keys($aTechvalue)) . ')
												');
			$queryPt2->bindValue(':id_count', $id_count, PDO::PARAM_INT);
			$queryPt2->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
			$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
			$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
			foreach($aTechvalue as $field => $value){
				$queryPt2->bindValue(':'.$field, $value, PDO::PARAM_STR);
			}
			$queryPt2->execute();
		
		}else{
			array_push($aChangedVersions, array($id_count, $id_lang, 0));
			
			$col = '';
			$value = '';
			$upd = '';
			foreach($aTechvalue as $key => $val){
				$col .= ', ' . $key;
				$value .= ', "' . str_replace('"', '\"', $val) . '"';
				$upd .= $key.' = "' . str_replace('"', '\"', $val) . '", ' ;
			}
			
			$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_techvalues_ext ';
			$qry .= '(id_count, id_lang, id_dev, create_at, create_from ' . $col . ') ';
			$qry .= 'VALUES ';					
			$qry .= '('.$id_count.', '.$id_lang.', 0, "'.$now.'", 0 ' . $value . ') '; 
			$qry .= 'ON DUPLICATE KEY UPDATE ';	
			$qry .= '' . $upd . ' change_from=0, del="0000-00-00 00:00:00";';
			$queryPt2 = $CONFIG['dbconn']->prepare($qry);
			$queryPt2->execute();
	
	
			foreach($aT as $t){
				$queryDel = $CONFIG['dbconn']->prepare('
													UPDATE ' . $CONFIG['db'][0]['prefix'] . '_techvalues_' . $t . ' SET
														del = (:nultime)
													WHERE ' . $CONFIG['db'][0]['prefix'] . '_techvalues_' . $t . '.id_count = (:id_count)
														AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_' . $t . '.id_lang = (:id_lang)
														AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_' . $t . '.id_dev = (:id_dev)
														AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_' . $t . '.id_clid = (:nul)
														AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_' . $t . '.id_pid = (:id_pid)
														AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_' . $t . '.id_tsid = (:id_tsid)
														AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_' . $t . '.id_tvid = (:id_tvid)
													');
				$queryDel->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$queryDel->bindValue(':nul', 0, PDO::PARAM_INT);
				$queryDel->bindValue(':id_pid', $aTechvalue['id_pid'], PDO::PARAM_INT);
				$queryDel->bindValue(':id_tsid', $aTechvalue['id_tsid'], PDO::PARAM_INT);
				$queryDel->bindValue(':id_tvid', $aTechvalue['id_tvid'], PDO::PARAM_INT);
				$queryDel->bindValue(':id_count', $id_count, PDO::PARAM_INT);
				$queryDel->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
				$queryDel->bindValue(':id_dev', 0, PDO::PARAM_INT);
				$queryDel->execute();
			}
		}
	}
	
	
	
	#############################################################################
	$modul = 'import';
	$table = $CONFIG['db'][0]['prefix'] . '_techvalues';
	$primekey = 'id_tvid';
	$aFieldsNumbers = array('value_id', 'id_pid', 'id_tsid');
	
	$columnsExtAll = '';
	$columnsExtLoc = '';
	$columnsLocAll = '';
	$columnsLocLoc = '';
	foreach($aTechvalue as $field => $value){
		$columnsExtAll .= '' . $table . '_##TYPE##.' . $field . ', ';
		$columnsExtLoc .= '' . $table . '_##TYPE##.' . $field . ' AS ' . $field . '_extloc, ';
		$columnsLocAll .= '' . $table . '_##TYPE##.' . $field . ' AS ' . $field . '_locall, ';
		$columnsLocLoc .= '' . $table . '_##TYPE##.' . $field . ' AS ' . $field . '_locloc, ';
	}
	$columnsExtAll = rtrim($columnsExtAll, ', ');
	$columnsExtLoc = rtrim($columnsExtLoc, ', ');
	$columnsLocAll = rtrim($columnsLocAll, ', ');
	$columnsLocLoc = rtrim($columnsLocLoc, ', ');
	$aColumns = array($columnsExtAll, $columnsExtLoc, $columnsLocAll, $columnsLocLoc);

	insertAllProducts($modul, $table, $primekey, $aTechvalue['id_tvid'], $aColumns, $aFieldsNumbers, $aChangedVersions, ' AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_##TYPE##.id_pid=' . $aTechvalue['id_pid'] . ' AND ' . $CONFIG['db'][0]['prefix'] . '_techvalues_##TYPE##.id_tsid=' . $aTechvalue['id_tsid'], $aArgs['saveVer']);
	
	#############################
	
}
	
?>