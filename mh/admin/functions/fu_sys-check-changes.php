<?php
function checkChanges($aArgsSave){ 
	global $CONFIG;
	
	$out = array();
	$out['aChangedFields'] = array();
	$out['aChangedFieldsMaster'] = array();
	$out['aDataOld'] = array(); 
	
	$table = ($CONFIG['aModul']['table_suffix'] == 0) ? $aArgsSave['table'] : $aArgsSave['table'] . 'uni';
	$variation = ($aArgsSave['aData']['id_count'] == 0 && $aArgsSave['aData']['id_lang'] == 0 && $aArgsSave['aData']['id_dev'] == 0) ? 'master' : 'local';
	
	######################################
	// Select Old Data (for data history)
	$queryStr = 'SELECT ';
	foreach($aArgsSave['columns'] as $column => $format){
		$queryStr .= $table . '.' . $column . ', '; 
	}
	$queryStr = rtrim($queryStr, ', ');
	$queryStr .= ' ';
	$queryStr .= 'FROM ' . $table . ' ';
	$queryStr .= 'WHERE ' . $table . '.id_count = (:id_count) ';
	$queryStr .= 'AND ' . $table . '.id_lang = (:id_lang) ';
	$queryStr .= 'AND ' . $table . '.id_dev = (:id_dev) ';
	$queryStr .= 'AND ' . $table . '.id_cl IN (0,' . $aArgsSave['aData']['id_cl'] . ') ';
	$queryStr .= 'AND ' . $table . '.' . $aArgsSave['primarykey'] . ' = (:id) ';


	$queryOld = $CONFIG['dbconn'][0]->prepare($queryStr);
	$queryOld->bindValue(':id_count', $aArgsSave['aData']['id_count'], PDO::PARAM_INT);
	$queryOld->bindValue(':id_lang', $aArgsSave['aData']['id_lang'], PDO::PARAM_INT);
	$queryOld->bindValue(':id_dev', $aArgsSave['aData']['id_dev'], PDO::PARAM_INT);
	$queryOld->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
	$queryOld->execute();
	$rowsOld = $queryOld->fetchAll(PDO::FETCH_ASSOC);
	$numOld = $queryOld->rowCount();
	$out['aDataOld'] = $rowsOld;
	######################################


	######################################
	// Compare for changes
	$listCASE = "";
	foreach($aArgsSave['columns'] as $column => $format){
		$listCASE .= "CASE ".$column." WHEN (:".$column.") THEN '' ELSE '".$column."' END,";
	}
	$listCASE = rtrim($listCASE, ",\r\n");


	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $listCASE . '
										FROM ' . $table . ' 
										WHERE ' . $table . '.id_count = (:id_count)
											AND ' . $table . '.id_lang = (:id_lang)
											AND ' . $table . '.id_dev = (:id_dev)
											AND ' . $table . '.id_cl IN (0,' . $aArgsSave['aData']['id_cl'] . ') 
											AND ' . $table . '.' . $aArgsSave['primarykey'] . ' = (:id)
										');
	$query->bindValue(':id_count', $aArgsSave['aData']['id_count'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $aArgsSave['aData']['id_lang'], PDO::PARAM_INT);
	$query->bindValue(':id_dev', $aArgsSave['aData']['id_dev'], PDO::PARAM_INT);
	$query->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
	foreach($aArgsSave['columns'] as $column => $format){
		if($format == 'i' || $format == 'si'){
			$query->bindValue(':'.$column, (is_array($aArgsSave['aData'][$column])) ? json_encode($aArgsSave['aData'][$column]) : $aArgsSave['aData'][$column], PDO::PARAM_INT);
		}else{
			$query->bindValue(':'.$column, (is_array($aArgsSave['aData'][$column])) ? json_encode($aArgsSave['aData'][$column]) : $aArgsSave['aData'][$column], PDO::PARAM_STR);
		}
	}
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();

	if($num > 0){
		foreach($rows[0] as $key => $val){
			if($val != ''){
				array_push($out['aChangedFields'], $val);
			}
		}
	}else{
		foreach($aArgsSave['columns'] as $column => $format){
			array_push($out['aChangedFields'], $column);
		}
	}

	##############################################
	
	if($CONFIG['user']['specifications'][14] == 8 && $variation == 'master' && $CONFIG['settings']['formCountry'] == 0 && $CONFIG['settings']['formLanguage'] == 0 && $CONFIG['settings']['formDevice'] == 0){
//		$query = $CONFIG['dbconn'][0]->prepare('
//											SELECT ' . $listCASE . '
//											FROM ' . $table . ' 
//											WHERE ' . $table . '.id_count = (:id_count)
//												AND ' . $table . '.id_lang = (:id_lang)
//												AND ' . $table . '.id_dev = (:id_dev)
//												AND ' . $table . '.id_cl IN (0,' . $aArgsSave['aDataMaster']['id_cl'] . ') 
//												AND ' . $table . '.' . $aArgsSave['primarykey'] . ' = (:id)
//											');
//		$query->bindValue(':id_count', $aArgsSave['aDataMaster']['id_count'], PDO::PARAM_INT);
//		$query->bindValue(':id_lang', $aArgsSave['aDataMaster']['id_lang'], PDO::PARAM_INT);
//		$query->bindValue(':id_dev', $aArgsSave['aDataMaster']['id_dev'], PDO::PARAM_INT);
//		$query->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
//		foreach($aArgsSave['columns'] as $column => $format){
//			if($format == 'i' || $format == 'si'){
//				$query->bindValue(':'.$column, (is_array($aArgsSave['aDataMaster'][$column])) ? json_encode($aArgsSave['aDataMaster'][$column]) : $aArgsSave['aDataMaster'][$column], PDO::PARAM_INT);
//			}else{
//				$query->bindValue(':'.$column, (is_array($aArgsSave['aDataMaster'][$column])) ? json_encode($aArgsSave['aDataMaster'][$column]) : $aArgsSave['aDataMaster'][$column], PDO::PARAM_STR);
//			}
//		}
//		$query->execute();
//		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//		$num = $query->rowCount();
//	
//		if($num > 0){
//			foreach($rows[0] as $key => $val){
//				if($val != '' && !in_array($val, $out['aChangedFields']) && $aArgsSave['aDataMaster'][$val] != $aArgsSave['aData'][$val]){
//					array_push($out['aChangedFieldsMaster'], $val);
//				}
//			}
//		}

		// anstatt einzelne Felder auf Änderungen zu prüfen, werden bei eingeschränkten Alle Rechten alle Felder überschrieben (Geo-Admin Marketing Hub)
		foreach($aArgsSave['columns'] as $column => $format){
			if(!in_array($column, $out['aChangedFields'])){
				array_push($out['aChangedFieldsMaster'], $column);
			}
		}
	}

	##############################################
	
	return $out;
}




function checkChangesN($aArgsSave){ 
	// check for changes in 1:n datasets
	global $CONFIG;

	$out = array();
	$out['aDataOld'] = array();
	$out['aDataAdd'] = array();
	$out['aDataDelete'] = array();
	
	$table = $aArgsSave['table'];

	######################################
	// Select Old Data 
	$queryStr = 'SELECT ';
	foreach($aArgsSave['columns'] as $column){
		$queryStr .= $table . '.' . $column . ', '; 
	}
	$queryStr = rtrim($queryStr, ', ');
	$queryStr .= ' ';
	$queryStr .= 'FROM ' . $table . ' ';
	$queryStr .= 'WHERE ' . $table . '.id_count = (:id_count) ';
	$queryStr .= 'AND ' . $table . '.id_lang = (:id_lang) ';
	$queryStr .= 'AND ' . $table . '.id_dev = (:id_dev) ';
	$queryStr .= 'AND ' . $table . '.id_cl IN (0,' . $aArgsSave['aData']['id_cl'] . ') ';
	$queryStr .= 'AND ' . $table . '.' . $aArgsSave['primarykey'] . ' = (:id) ';
	$queryStr .= 'AND ' . $table . '.del = (:nultime) ';
	
	$queryOld = $CONFIG['dbconn'][0]->prepare($queryStr);
	$queryOld->bindValue(':id_count', $aArgsSave['aData']['id_count'], PDO::PARAM_INT);
	$queryOld->bindValue(':id_lang', $aArgsSave['aData']['id_lang'], PDO::PARAM_INT);
	$queryOld->bindValue(':id_dev', $aArgsSave['aData']['id_dev'], PDO::PARAM_INT);
	$queryOld->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
	$queryOld->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryOld->execute();
	$rowsOld = $queryOld->fetchAll(PDO::FETCH_ASSOC);
	$numOld = $queryOld->rowCount();

	foreach($rowsOld as $rowOld){
		array_push($out['aDataOld'], $rowOld[$aArgsSave['columns'][0]]);
	}
	######################################
		
	$out['aDataAdd'] = array_diff($aArgsSave['aData'][$aArgsSave['field']], $out['aDataOld']);
	$out['aDataDelete'] = array_diff($out['aDataOld'], $aArgsSave['aData'][$aArgsSave['field']]);

	
	return $out;
}

?>