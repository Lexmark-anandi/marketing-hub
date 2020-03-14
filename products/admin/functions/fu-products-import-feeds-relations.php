<?php
$aT = array('ext', 'loc', 'uni');
foreach($aT as $t){
	$queryDel = $CONFIG['dbconn']->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . '_relations_' . $t . ' SET
											del = (:now)
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_relations_' . $t . '.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_relations_' . $t . '.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_relations_' . $t . '.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . '_relations_' . $t . '.id_clid = (:nul)
											AND (' . $CONFIG['db'][0]['prefix'] . '_relations_' . $t . '.id_pid = (:id_pid)
												OR ' . $CONFIG['db'][0]['prefix'] . '_relations_' . $t . '.id_pid_option = (:id_pid))
											AND ' . $CONFIG['db'][0]['prefix'] . '_relations_' . $t . '.del = (:nultime)
										');
	$queryDel->bindValue(':now', $now, PDO::PARAM_STR);
	$queryDel->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryDel->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryDel->bindValue(':id_pid', $aProduct['id_pid'], PDO::PARAM_INT);
	$queryDel->bindValue(':id_count', $id_count, PDO::PARAM_INT);
	$queryDel->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
	$queryDel->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryDel->execute();
}


foreach($product->relationships->product_to as $relation) {							
	$aChangedVersions = array();
	
	$queryR = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_pid,
											' . $CONFIG['db'][0]['prefix'] . '_products_ext.is_printer
										FROM ' . $CONFIG['db'][0]['prefix'] . '_products_ext 
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_clid = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_products_ext.revenue_pid = (:pid)
										');
	$queryR->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryR->bindValue(':pid', $relation[@productTo_PID], PDO::PARAM_INT);
	$queryR->execute();
	$rowsR = $queryR->fetchAll(PDO::FETCH_ASSOC);
	$numR = $queryR->rowCount();

	if($numR > 0){
		$aRelation = array();
		if($aProduct['is_printer'] == 1){
			$aRelation['id_pid'] = $aProduct['id_pid'];
			$aRelation['id_pid_option'] = $rowsR[0]['id_pid'];
		}else{
			$aRelation['id_pid_option'] = $aProduct['id_pid'];
			$aRelation['id_pid'] = $rowsR[0]['id_pid'];
		}
		$aRelation['productTo_state'] = $relation[@productTo_state];
	
		// search for relation
		$queryPt = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_relations_ext.id_relid
											FROM ' . $CONFIG['db'][0]['prefix'] . '_relations_ext 
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_relations_ext.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_relations_ext.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_relations_ext.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_relations_ext.id_clid = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_relations_ext.id_pid = (:id_pid)
												AND ' . $CONFIG['db'][0]['prefix'] . '_relations_ext.id_pid_option = (:id_pid_option)
											');
		$queryPt->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryPt->bindValue(':id_pid', $aRelation['id_pid'], PDO::PARAM_INT);
		$queryPt->bindValue(':id_pid_option', $aRelation['id_pid_option'], PDO::PARAM_INT);
		$queryPt->execute();
		$rowsPt = $queryPt->fetchAll(PDO::FETCH_ASSOC);
		$numPt = $queryPt->rowCount();
		
		if($numPt == 0){
			// first time for all / all
			foreach($aArgs['aListLanguagesByCountries'][$id_count] as $lang){
				if($lang != 0) array_push($aChangedVersions, array($id_count, $lang, 0));
			}

			$queryPt2 = $CONFIG['dbconn']->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_relations
													(create_at, create_from)
												VALUES
													(:create_at, :create_from)
												');
			$queryPt2->bindValue(':create_at', $now, PDO::PARAM_STR);
			$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
			$queryPt2->execute();
			$aRelation['id_relid'] = $CONFIG['dbconn']->lastInsertId();
			
			// save all / all
			$queryPt2 = $CONFIG['dbconn']->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_relations_ext
													(id_count, id_lang, id_dev, create_at, create_from, ' . implode(', ', array_keys($aRelation)) . ')
												VALUES
													(:id_count, :id_lang, :id_dev, :create_at, :create_from, :' . implode(', :', array_keys($aRelation)) . ')
												');
			$queryPt2->bindValue(':id_count', 0, PDO::PARAM_INT);
			$queryPt2->bindValue(':id_lang', 0, PDO::PARAM_INT);
			$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
			$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
			foreach($aRelation as $field => $value){
				$queryPt2->bindValue(':'.$field, $value, PDO::PARAM_STR);
			}
			$queryPt2->execute();
			
			$queryPt2 = $CONFIG['dbconn']->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_relations_uni
													(id_count, id_lang, id_dev, create_at, create_from, ' . implode(', ', array_keys($aRelation)) . ')
												VALUES
													(:id_count, :id_lang, :id_dev, :create_at, :create_from, :' . implode(', :', array_keys($aRelation)) . ')
												');
			$queryPt2->bindValue(':id_count', 0, PDO::PARAM_INT);
			$queryPt2->bindValue(':id_lang', 0, PDO::PARAM_INT);
			$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
			$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
			foreach($aRelation as $field => $value){
				$queryPt2->bindValue(':'.$field, $value, PDO::PARAM_STR);
			}
			$queryPt2->execute();
			
			// save country / language
			$queryPt2 = $CONFIG['dbconn']->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_relations_ext
													(id_count, id_lang, id_dev, create_at, create_from, ' . implode(', ', array_keys($aRelation)) . ')
												VALUES
													(:id_count, :id_lang, :id_dev, :create_at, :create_from, :' . implode(', :', array_keys($aRelation)) . ')
												');
			$queryPt2->bindValue(':id_count', $id_count, PDO::PARAM_INT);
			$queryPt2->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
			$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
			$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
			foreach($aRelation as $field => $value){
				$queryPt2->bindValue(':'.$field, $value, PDO::PARAM_STR);
			}
			$queryPt2->execute();
			
		}else{
			$aRelation['id_relid'] = $rowsPt[0]['id_relid'];
			array_push($aChangedVersions, array($id_count, $id_lang, 0));
	
			$col = '';
			$value = '';
			$upd = '';
			foreach($aRelation as $key => $val){
				$col .= ', ' . $key;
				$value .= ', "' . str_replace('"', '\"', $val) . '"';
				$upd .= $key.' = "' . str_replace('"', '\"', $val) . '", ' ;
			}
			
			$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_relations_ext ';
			$qry .= '(id_count, id_lang, id_dev, create_at, create_from ' . $col . ') ';
			$qry .= 'VALUES ';					
			$qry .= '('.$id_count.', '.$id_lang.', 0, "'.$now.'", 0 ' . $value . ') '; 
			$qry .= 'ON DUPLICATE KEY UPDATE ';	
			$qry .= '' . $upd . ' change_from=0, del="0000-00-00 00:00:00";';
			$queryPt2 = $CONFIG['dbconn']->prepare($qry);
			$queryPt2->execute();

	
			foreach($aT as $t){
				$queryDel = $CONFIG['dbconn']->prepare('
													UPDATE ' . $CONFIG['db'][0]['prefix'] . '_relations_' . $t . ' SET
														del = (:nultime)
													WHERE ' . $CONFIG['db'][0]['prefix'] . '_relations_' . $t . '.id_count = (:id_count)
														AND ' . $CONFIG['db'][0]['prefix'] . '_relations_' . $t . '.id_lang = (:id_lang)
														AND ' . $CONFIG['db'][0]['prefix'] . '_relations_' . $t . '.id_dev = (:id_dev)
														AND ' . $CONFIG['db'][0]['prefix'] . '_relations_' . $t . '.id_clid = (:nul)
														AND ' . $CONFIG['db'][0]['prefix'] . '_relations_' . $t . '.id_pid = (:id_pid)
														AND ' . $CONFIG['db'][0]['prefix'] . '_relations_' . $t . '.id_pid_option = (:id_pid_option)
														AND ' . $CONFIG['db'][0]['prefix'] . '_relations_' . $t . '.id_relid = (:id_relid)
														
													');
				$queryDel->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$queryDel->bindValue(':nul', 0, PDO::PARAM_INT);
				$queryDel->bindValue(':id_pid', $aRelation['id_pid'], PDO::PARAM_INT);
				$queryDel->bindValue(':id_pid_option', $aRelation['id_pid_option'], PDO::PARAM_INT);
				$queryDel->bindValue(':id_relid', $aRelation['id_relid'], PDO::PARAM_INT);
				$queryDel->bindValue(':id_count', $id_count, PDO::PARAM_INT);
				$queryDel->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
				$queryDel->bindValue(':id_dev', 0, PDO::PARAM_INT);
				$queryDel->execute();
			}
		}
		
		
		
		#############################################################################
		$modul = 'import';
		$table = $CONFIG['db'][0]['prefix'] . '_relations';
		$primekey = 'id_relid';
		$aFieldsNumbers = array('id_pid', 'id_pid_option');

		$columnsExtAll = '';
		$columnsExtLoc = '';
		$columnsLocAll = '';
		$columnsLocLoc = '';
		foreach($aRelation as $field => $value){
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
		
		insertAllProducts($modul, $table, $primekey, $aRelation['id_relid'], $aColumns, $aFieldsNumbers, $aChangedVersions, ' AND ' . $CONFIG['db'][0]['prefix'] . '_relations_##TYPE##.id_pid=' . $aRelation['id_pid'] . ' AND ' . $CONFIG['db'][0]['prefix'] . '_relations_##TYPE##.id_pid_option=' . $aRelation['id_pid_option'], $aArgs['saveVer']);
		
		#############################
	}
	
}
	
?>