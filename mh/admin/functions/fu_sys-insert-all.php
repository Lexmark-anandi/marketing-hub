<?php
##################################################################################### 
// Fill xxx_uni
#####################################################################################
function insertAll($aArgsSave){
	global $CONFIG;
	
	$date = new DateTime(); 
	$now = $date->format('Y-m-d H:i:s');
	
	if(!isset($aArgsSave['addCondition'])) $aArgsSave['addCondition'] = '';
	
	$aSaveVersions = (in_array(array(0,0,0), $aArgsSave['changedVersions'])) ? $aArgsSave['allVersions'] : $aArgsSave['changedVersions'];
	if(!isset($CONFIG['activeSettings']['id_clid'])) $CONFIG['activeSettings']['id_clid'] = 1;
	if(!isset($CONFIG['user']['restricted_all'])) $CONFIG['user']['restricted_all'] = 0;
	if(!isset($CONFIG['user']['specifications'][14])) $CONFIG['user']['specifications'][14] = 9;
	if(!isset($CONFIG['user']['id_real'])) $CONFIG['user']['id_real'] = 999999999;
	
	
	foreach($aSaveVersions as $aVersion){
		$id_count = $aVersion[0];
		$id_lang = $aVersion[1];
		$id_dev = $aVersion[2];
		$variation = ($id_count == 0 && $id_lang == 0 && $id_dev == 0) ? 'master' : 'local';
		
		######################################################################
		// read variations
		$numE = 0;
		
		##################
		// ext master
		$queryStr = 'SELECT ';
		$queryStr .= $aArgsSave['table'] . 'ext.id_cl, '; 
		$queryStr .= $aArgsSave['table'] . 'ext.id_count, '; 
		$queryStr .= $aArgsSave['table'] . 'ext.id_lang, '; 
		foreach($aArgsSave['columns'] as $column => $format){
			$queryStr .= $aArgsSave['table'] . 'ext.' . $column . ', '; 
		}
		$queryStr = rtrim($queryStr, ', ');
		$queryStr .= ' ';
		$queryStr .= 'FROM ' . $aArgsSave['table'] . 'ext ';
		$queryStr .= 'WHERE ' . $aArgsSave['table'] . 'ext.id_count = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.id_lang = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.id_dev = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.id_cl IN (0,' . $CONFIG['activeSettings']['id_clid'] . ') ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.restricted_all = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.' . $aArgsSave['primarykey'] . ' = (:id) ';
		//$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.del = (:nultime) ';
		$queryStr .= str_replace('##TYPE##', 'ext', $aArgsSave['addCondition']) . ' ';

		$queryE = $CONFIG['dbconn'][0]->prepare($queryStr);
		$queryE->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
		$queryE->bindValue(':nul', 0, PDO::PARAM_INT);
		//$queryE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryE->execute();
		$rowsExt = $queryE->fetchAll(PDO::FETCH_ASSOC); 
		$numExt = $queryE->rowCount();
		$numE += $numExt;
		
		##################
		// ext local
		$queryStr = 'SELECT ';
		$queryStr .= $aArgsSave['table'] . 'ext.id_cl, '; 
		$queryStr .= $aArgsSave['table'] . 'ext.id_count, '; 
		$queryStr .= $aArgsSave['table'] . 'ext.id_lang, '; 
		foreach($aArgsSave['columns'] as $column => $format){
			$queryStr .= $aArgsSave['table'] . 'ext.' . $column . ', '; 
		}
		$queryStr = rtrim($queryStr, ', ');
		$queryStr .= ' ';
		$queryStr .= 'FROM ' . $aArgsSave['table'] . 'ext ';
		$queryStr .= 'WHERE ' . $aArgsSave['table'] . 'ext.id_count = (:id_count) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.id_lang = (:id_lang) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.id_dev = (:id_dev) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.id_cl IN (0,' . $CONFIG['activeSettings']['id_clid'] . ') ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.restricted_all = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.' . $aArgsSave['primarykey'] . ' = (:id) ';
		//$queryStr .= 'AND ' . $aArgsSave['table'] . 'ext.del = (:nultime) ';
		$queryStr .= str_replace('##TYPE##', 'ext', $aArgsSave['addCondition']) . ' ';

		$queryE = $CONFIG['dbconn'][0]->prepare($queryStr);
		$queryE->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
		$queryE->bindValue(':id_count', $id_count, PDO::PARAM_INT);
		$queryE->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
		$queryE->bindValue(':id_dev', $id_dev, PDO::PARAM_INT);
		$queryE->bindValue(':nul', 0, PDO::PARAM_INT);
		//$queryE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryE->execute();
		$rowsExtloc = $queryE->fetchAll(PDO::FETCH_ASSOC); 
		$numExtloc = $queryE->rowCount();
		$numE += $numExtloc;
		
		
		##################
		// res local
		$queryStr = 'SELECT ';
		$queryStr .= $aArgsSave['table'] . 'res.id_cl, '; 
		$queryStr .= $aArgsSave['table'] . 'res.id_count, '; 
		$queryStr .= $aArgsSave['table'] . 'res.id_lang, '; 
		foreach($aArgsSave['columns'] as $column => $format){
			$queryStr .= $aArgsSave['table'] . 'res.' . $column . ', '; 
		}
		$queryStr = rtrim($queryStr, ', ');
		$queryStr .= ' ';
		$queryStr .= 'FROM ' . $aArgsSave['table'] . 'res ';
		$queryStr .= 'WHERE ' . $aArgsSave['table'] . 'res.id_count = (:id_count) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'res.id_lang = (:id_lang) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'res.id_dev = (:id_dev) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'res.id_cl IN (0,' . $CONFIG['activeSettings']['id_clid'] . ') ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'res.restricted_all = (:restricted_all) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'res.' . $aArgsSave['primarykey'] . ' = (:id) ';
		//$queryStr .= 'AND ' . $aArgsSave['table'] . 'res.del = (:nultime) ';
		$queryStr .= str_replace('##TYPE##', 'res', $aArgsSave['addCondition']) . ' ';

		$queryE = $CONFIG['dbconn'][0]->prepare($queryStr);
		$queryE->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
		$queryE->bindValue(':id_count', $id_count, PDO::PARAM_INT);
		$queryE->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
		$queryE->bindValue(':id_dev', $id_dev, PDO::PARAM_INT);
		$queryE->bindValue(':restricted_all', ($id_count == 0 && $id_lang == 0 && $id_dev == 0) ? $CONFIG['user']['restricted_all'] : '0', PDO::PARAM_STR);
		//$queryE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryE->execute();
		$rowsRes = $queryE->fetchAll(PDO::FETCH_ASSOC); 
		$numRes = $queryE->rowCount();
		$numE += $numRes;
				
		
		##################
		// loc master
		$queryStr = 'SELECT ';
		$queryStr .= $aArgsSave['table'] . 'loc.id_cl, '; 
		$queryStr .= $aArgsSave['table'] . 'loc.id_count, '; 
		$queryStr .= $aArgsSave['table'] . 'loc.id_lang, '; 
		foreach($aArgsSave['columns'] as $column => $format){
			$queryStr .= $aArgsSave['table'] . 'loc.' . $column . ', '; 
		}
		$queryStr = rtrim($queryStr, ', ');
		$queryStr .= ' ';
		$queryStr .= 'FROM ' . $aArgsSave['table'] . 'loc ';
		$queryStr .= 'WHERE ' . $aArgsSave['table'] . 'loc.id_count = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.id_lang = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.id_dev = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.id_cl IN (0,' . $CONFIG['activeSettings']['id_clid'] . ') ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.restricted_all = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.' . $aArgsSave['primarykey'] . ' = (:id) ';
		//$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.del = (:nultime) ';
		$queryStr .= str_replace('##TYPE##', 'loc', $aArgsSave['addCondition']) . ' ';

		$queryE = $CONFIG['dbconn'][0]->prepare($queryStr);
		$queryE->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
		$queryE->bindValue(':nul', 0, PDO::PARAM_INT);
		//$queryE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryE->execute();
		$rowsLoc = $queryE->fetchAll(PDO::FETCH_ASSOC); 
		$numLoc = $queryE->rowCount();
		$numE += $numLoc;
		
		##################
		// loc local
		$queryStr = 'SELECT ';
		$queryStr .= $aArgsSave['table'] . 'loc.id_cl, '; 
		$queryStr .= $aArgsSave['table'] . 'loc.id_count, '; 
		$queryStr .= $aArgsSave['table'] . 'loc.id_lang, '; 
		foreach($aArgsSave['columns'] as $column => $format){
			$queryStr .= $aArgsSave['table'] . 'loc.' . $column . ', '; 
		}
		$queryStr = rtrim($queryStr, ', ');
		$queryStr .= ' ';
		$queryStr .= 'FROM ' . $aArgsSave['table'] . 'loc ';
		$queryStr .= 'WHERE ' . $aArgsSave['table'] . 'loc.id_count = (:id_count) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.id_lang = (:id_lang) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.id_dev = (:id_dev) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.id_cl IN (0,' . $CONFIG['activeSettings']['id_clid'] . ') ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.restricted_all = (:nul) ';
		$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.' . $aArgsSave['primarykey'] . ' = (:id) ';
		//$queryStr .= 'AND ' . $aArgsSave['table'] . 'loc.del = (:nultime) ';
		$queryStr .= str_replace('##TYPE##', 'loc', $aArgsSave['addCondition']) . ' ';

		$queryE = $CONFIG['dbconn'][0]->prepare($queryStr);
		$queryE->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
		$queryE->bindValue(':id_count', $id_count, PDO::PARAM_INT);
		$queryE->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
		$queryE->bindValue(':id_dev', $id_dev, PDO::PARAM_INT);
		$queryE->bindValue(':nul', 0, PDO::PARAM_INT);
		//$queryE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryE->execute();
		$rowsLocloc = $queryE->fetchAll(PDO::FETCH_ASSOC); 
		$numLocloc = $queryE->rowCount();
		$numE += $numLocloc;
		
		
		$aResult = array();
		$aResult['id_cl'] = 0;
		if($numExt > 0 && $rowsExt[0]['id_cl'] > 0) $aResult['id_cl'] = $rowsExt[0]['id_cl'];
		if($numExtloc > 0 && $rowsExtloc[0]['id_cl'] > 0) $aResult['id_cl'] = $rowsExtloc[0]['id_cl'];


		if($numRes > 0 && $rowsRes[0]['id_cl'] > 0) $aResult['id_cl'] = $rowsRes[0]['id_cl'];


		if($numLoc > 0 && $rowsLoc[0]['id_cl'] > 0) $aResult['id_cl'] = $rowsLoc[0]['id_cl'];
		if($numLocloc > 0 && $rowsLocloc[0]['id_cl'] > 0) $aResult['id_cl'] = $rowsLocloc[0]['id_cl'];
		foreach($aArgsSave['columns'] as $column => $format){
			$aResult[$column] = '';
			
			if(isset($rowsExt[0][$column])){
				$aResult[$column] = $rowsExt[0][$column];
			}
			
			if(isset($rowsExtloc[0][$column])){
				if(!in_array($rowsExtloc[0][$column], $aArgsSave['excludeUpdateUni'][$column]) || $aResult[$column] == NULL) $aResult[$column] = $rowsExtloc[0][$column];
			}

			if(isset($rowsLoc[0][$column])){
				if(!in_array($rowsLoc[0][$column], $aArgsSave['excludeUpdateUni'][$column]) || $aResult[$column] == NULL) $aResult[$column] = $rowsLoc[0][$column];
			}

			if(isset($rowsRes[0][$column])){
				if(!in_array($rowsRes[0][$column], $aArgsSave['excludeUpdateUni'][$column]) || $aResult[$column] == NULL) $aResult[$column] = $rowsRes[0][$column];
			}
			
			if(isset($rowsLocloc[0][$column])){
				if(!in_array($rowsLocloc[0][$column], $aArgsSave['excludeUpdateUni'][$column]) || $aResult[$column] == NULL) $aResult[$column] = $rowsLocloc[0][$column];
			}
			
			// if restricted acces to ALL and master then overwrite _loc with _res
//			if($CONFIG['user']['specifications'][14] == 8 && $variation == 'master'){
//				if(isset($rowsRes[0][$column])){
//					if(!in_array($rowsRes[0][$column], $aArgsSave['excludeUpdateUni'][$column]) || $aResult[$column] == NULL) $aResult[$column] = $rowsRes[0][$column];
//				}
//			}
		}
		

		if($numE > 0){
			$addDel = 1;
			$col = '';
			$val = '';
			$upd = "";
			foreach($aResult as $column => $value){
				if($column == 'del') $addDel = 0;
				$col .= ', ' . $column;
				$val .= ', :' . $column;
				$upd .= $column.' = (:' . $column . '), ' ;
			}

			// save master ALL in uni
			$qry = 'INSERT INTO ' . $aArgsSave['table'] . 'uni ';
			$qry .= '(id_count, id_lang, id_dev, restricted_all, create_at, create_from, change_from' . $col . ') ';
			$qry .= 'VALUES ';					
			$qry .= '(:id_count, :id_lang, :id_dev, :restricted_all, :create_at, :create_from, :create_from' . $val . ') '; 
			$qry .= 'ON DUPLICATE KEY UPDATE ';	
			if($CONFIG['user']['specifications'][14] != 8 || $variation == 'local') $qry .= $upd;
			$qry .= 'change_from = (:create_from), ';
			if($addDel == 1) $qry .= 'del = (:nultime) ';
			$qry = rtrim($qry, ', ');
			$qry .= ' ';
			
			$query = $CONFIG['dbconn'][0]->prepare($qry);
			$query->bindValue(':id_count', $id_count, PDO::PARAM_INT);
			$query->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
			$query->bindValue(':id_dev', $id_dev, PDO::PARAM_INT);
			$query->bindValue(':restricted_all', '0', PDO::PARAM_STR);
			if($addDel == 1) $query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query->bindValue(':create_at', $now, PDO::PARAM_STR); 
			$query->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
			foreach($aResult as $column => $value){
				if(in_array($column, $aArgsSave['aFieldsNumbers'])){
					$query->bindValue(':'.$column, $value, PDO::PARAM_INT);
				}else{
					$query->bindValue(':'.$column, $value, PDO::PARAM_STR);
				}
			}
			$query->execute();
			$numUni = $query->rowCount();
			
			if($variation == 'master'){
				// save uni for restricted access to ALL
				$qry = 'INSERT INTO ' . $aArgsSave['table'] . 'uni ';
				$qry .= '(id_count, id_lang, id_dev, restricted_all, create_at, create_from, change_from' . $col . ') ';
				$qry .= 'VALUES ';					
				$qry .= '(:id_count, :id_lang, :id_dev, :restricted_all, :create_at, :create_from, :create_from' . $val . ') '; 
				$qry .= 'ON DUPLICATE KEY UPDATE ';	
				$qry .= $upd;
				$qry .= 'change_from = (:create_from), ';
				if($addDel == 1) $qry .= 'del = (:nultime) ';
				$qry = rtrim($qry, ', ');
				$qry .= ' ';
				
				$query = $CONFIG['dbconn'][0]->prepare($qry);
				$query->bindValue(':id_count', $id_count, PDO::PARAM_INT);
				$query->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
				$query->bindValue(':id_dev', $id_dev, PDO::PARAM_INT);
				$query->bindValue(':restricted_all', $CONFIG['user']['restricted_all'], PDO::PARAM_STR);
				if($addDel == 1) $query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$query->bindValue(':create_at', $now, PDO::PARAM_STR); 
				$query->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
				foreach($aResult as $column => $value){
					if(in_array($column, $aArgsSave['aFieldsNumbers'])){
						$query->bindValue(':'.$column, $value, PDO::PARAM_INT);
					}else{
						$query->bindValue(':'.$column, $value, PDO::PARAM_STR);
					}
				}
				$query->execute();
				$numUni = $query->rowCount();
			}
			
//			$arr = $query->errorInfo();
//			print_r($arr);
			
			
			$num = $query->rowCount();
		}
	}
}


?>