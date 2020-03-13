<?php
foreach($CONFIG['aModul']['form'] as $aFieldsets){
	foreach($aFieldsets['fields'] as $field){
		if(in_array($field['specifications'][0], $CONFIG['system']['aFieldsAllowedSpecs']) && $field['specifications'][2] != 0){
			if(($variation == 'master' && in_array($field['name'], $aFieldsSaveMaster)) || ($variation == 'local' && in_array($field['name'], $aFieldsSaveNotMaster))){
				if($field['array'] == 1 && $field['array_options']['primarykey'] != '' && $field['type'] != 'file'){
					$queryN = $CONFIG['dbconn'][0]->prepare('SHOW KEYS FROM ' . $CONFIG['db'][0]['prefix'] . $field['table'] . ' WHERE Key_name = "PRIMARY"');
					$queryN->execute();
					$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
					$numN = $queryN->rowCount(); 
					$column_condN = $rowsN[0]['Column_name'];
					
					$queryN = $CONFIG['dbconn'][0]->prepare('SHOW KEYS FROM ' . $CONFIG['db'][0]['prefix'] . $field['array_options']['table_save'] . ' WHERE Key_name = "PRIMARY"');
					$queryN->execute();
					$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
					$numN = $queryN->rowCount(); 
					$primaryN = $rowsN[0]['Column_name'];
					
					$tableN = ($field['array_options']['table_save_suffix'] == 0) ? $field['array_options']['table_save'] : $field['array_options']['table_save'] . 'uni';
					$tableNl = ($field['array_options']['table_save_suffix'] == 0) ? $field['array_options']['table_save'] : $field['array_options']['table_save'] . 'loc';
					
		
					#############################################################################################
					// Check for changes
					// 1. Abfrage in _uni für Produkt und Variante welche Einträge vorhanden sind (array(261))
					// 2. Vergleich mit "foreach($aArgsSave['aData'][$field['name']] as $valueN){"
					//    2a. in 1 fehlende Eintrage werden in _loc eingetragen
					//    2b. in 2 fehlende Eintrage werden in _loc eingetragen und auf del gesetzt
					
					$aArgsN = array();
					$aArgsN['id_data'] = $aArgsSave['id_data'];
					$aArgsN['table'] = $CONFIG['db'][0]['prefix'] . $tableN; 
					$aArgsN['primarykey'] = $field['array_options']['column_cond'];
					$aArgsN['columns'] = array($column_condN);
					$aArgsN['field'] = $field['name'];
					$aArgsN['aData'] = $aArgsSave['aData'];
					$aChangeN = checkChangesN($aArgsN);
					
					foreach($aChangeN['aDataAdd'] as $valueN){
						// exists 1:n pair? or create new id for pair
						$queryStrN = 'SELECT ';
						$queryStrN .= $CONFIG['db'][0]['prefix'] . $tableN . '.' . $primaryN . ' '; 
						$queryStrN .= 'FROM ' . $CONFIG['db'][0]['prefix'] . $tableN . ' ';
						$queryStrN .= 'WHERE ' . $CONFIG['db'][0]['prefix'] . $tableN . '.' . $column_condN . ' = (:condN) ';
						$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableN . '.' . $field['array_options']['column_cond'] . ' = (:cond) ';
						$queryStrN .= 'LIMIT 1 ';
						
						$queryN = $CONFIG['dbconn'][0]->prepare($queryStrN);
						$queryN->bindValue(':condN', $valueN, PDO::PARAM_INT);
						$queryN->bindValue(':cond', $aArgsSave['id_data'], PDO::PARAM_INT);
						$queryN->execute();
						$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
						$numN = $queryN->rowCount();
						
						if($numN == 0){
							$queryNi = $CONFIG['dbconn'][0]->prepare('
																INSERT INTO ' . $CONFIG['db'][0]['prefix'] . $field['array_options']['table_save'] . '
																(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
																VALUES
																(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
																');
							$queryNi->bindValue(':nul', 0, PDO::PARAM_INT);
							$queryNi->bindValue(':id_cl', $aArgsSave['aData']['id_cl'], PDO::PARAM_INT);
							$queryNi->bindValue(':create_at', $now, PDO::PARAM_STR);
							$queryNi->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
							$queryNi->execute();
							$idN = $CONFIG['dbconn'][0]->lastInsertId();
						}else{
							$idN = $rowsN[0][$primaryN];
						}
						
						// insert / update _loc
						$qryNx = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . $tableNl . '
										(' . $primaryN . ', id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from, ' . $column_condN . ', ' . $field['array_options']['column_cond'] . ')
								  VALUES
										(:id, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from, :condN, :cond)
								  ON DUPLICATE KEY UPDATE 
										change_from = (:create_from),
										del = (:nultime)
								 ';
						$queryNx = $CONFIG['dbconn'][0]->prepare($qryNx);
						$queryNx->bindValue(':id', $idN, PDO::PARAM_INT);
						$queryNx->bindValue(':id_count', $aArgsSave['aData']['id_count'], PDO::PARAM_INT);
						$queryNx->bindValue(':id_lang', $aArgsSave['aData']['id_lang'], PDO::PARAM_INT);
						$queryNx->bindValue(':id_dev', $aArgsSave['aData']['id_dev'], PDO::PARAM_INT);
						$queryNx->bindValue(':id_cl', $aArgsSave['aData']['id_cl'], PDO::PARAM_INT);
						$queryNx->bindValue(':now', $now, PDO::PARAM_STR);
						$queryNx->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
						$queryNx->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
						$queryNx->bindValue(':condN', $valueN, PDO::PARAM_INT);
						$queryNx->bindValue(':cond', $aArgsSave['id_data'], PDO::PARAM_INT);
						$queryNx->execute();
						$numNx = $queryNx->rowCount();
		
		
						if(!array_key_exists('n_' . $idN, $aArgsSaveN)){
							$aArgsSaveN['n_' . $idN] = array();
							$aArgsSaveN['n_' . $idN]['id_data'] = $idN;
							$aArgsSaveN['n_' . $idN]['table'] = $CONFIG['db'][0]['prefix'] . $field['array_options']['table_save'];
							$aArgsSaveN['n_' . $idN]['primarykey'] = $primaryN;
							$aArgsSaveN['n_' . $idN]['allVersions'] = array();
							$aArgsSaveN['n_' . $idN]['changedVersions'] = array();
							$aArgsSaveN['n_' . $idN]['columns'] = array($primaryN => 'i', $column_condN => 'i', $field['array_options']['column_cond'] => 'i', 'del' => 's');
							$aArgsSaveN['n_' . $idN]['aFieldsNumbers'] = array($column_condN, $field['array_options']['column_cond']);
							$aArgsSaveN['n_' . $idN]['excludeUpdateUni'] = array($primaryN => array(''), $column_condN => array(''), $field['array_options']['column_cond'] => array(''), 'del' => array(''));
						}
						if($numNx > 0) array_push($aArgsSaveN['n_' . $idN]['changedVersions'], array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']));
					}
					
					foreach($aChangeN['aDataDelete'] as $valueN){
						// exists 1:n pair? or create new id for pair
						$queryStrN = 'SELECT ';
						$queryStrN .= $CONFIG['db'][0]['prefix'] . $tableN . '.' . $primaryN . ' '; 
						$queryStrN .= 'FROM ' . $CONFIG['db'][0]['prefix'] . $tableN . ' ';
						$queryStrN .= 'WHERE ' . $CONFIG['db'][0]['prefix'] . $tableN . '.' . $column_condN . ' = (:condN) ';
						$queryStrN .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableN . '.' . $field['array_options']['column_cond'] . ' = (:cond) ';
						$queryStrN .= 'LIMIT 1 ';
						
						$queryN = $CONFIG['dbconn'][0]->prepare($queryStrN);
						$queryN->bindValue(':condN', $valueN, PDO::PARAM_INT);
						$queryN->bindValue(':cond', $aArgsSave['id_data'], PDO::PARAM_INT);
						$queryN->execute();
						$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
						$numN = $queryN->rowCount();
						
						if($numN == 0){
							$queryNi = $CONFIG['dbconn'][0]->prepare('
																INSERT INTO ' . $CONFIG['db'][0]['prefix'] . $field['array_options']['table_save'] . '
																(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
																VALUES
																(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
																');
							$queryNi->bindValue(':nul', 0, PDO::PARAM_INT);
							$queryNi->bindValue(':id_cl', $aArgsSave['aData']['id_cl'], PDO::PARAM_INT);
							$queryNi->bindValue(':create_at', $now, PDO::PARAM_STR);
							$queryNi->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
							$queryNi->execute();
							$idN = $CONFIG['dbconn'][0]->lastInsertId();
						}else{
							$idN = $rowsN[0][$primaryN];
						}
						
						// insert / update _loc
						$qryNx = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . $tableNl . '
										(' . $primaryN . ', id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from, del, ' . $column_condN . ', ' . $field['array_options']['column_cond'] . ')
								  VALUES
										(:id, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from, :now, :condN, :cond)
								  ON DUPLICATE KEY UPDATE 
										change_from = (:create_from),
										del = (:now)
								 ';
						$queryNx = $CONFIG['dbconn'][0]->prepare($qryNx);
						$queryNx->bindValue(':id', $idN, PDO::PARAM_INT);
						$queryNx->bindValue(':id_count', $aArgsSave['aData']['id_count'], PDO::PARAM_INT);
						$queryNx->bindValue(':id_lang', $aArgsSave['aData']['id_lang'], PDO::PARAM_INT);
						$queryNx->bindValue(':id_dev', $aArgsSave['aData']['id_dev'], PDO::PARAM_INT);
						$queryNx->bindValue(':id_cl', $aArgsSave['aData']['id_cl'], PDO::PARAM_INT);
						$queryNx->bindValue(':now', $now, PDO::PARAM_STR);
						$queryNx->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
						$queryNx->bindValue(':condN', $valueN, PDO::PARAM_INT);
						$queryNx->bindValue(':cond', $aArgsSave['id_data'], PDO::PARAM_INT);
						$queryNx->execute();
						$numNx = $queryNx->rowCount();
		
		
						if(!array_key_exists('n_' . $idN, $aArgsSaveN)){
							$aArgsSaveN['n_' . $idN] = array();
							$aArgsSaveN['n_' . $idN]['id_data'] = $idN;
							$aArgsSaveN['n_' . $idN]['table'] = $CONFIG['db'][0]['prefix'] . $field['array_options']['table_save'];
							$aArgsSaveN['n_' . $idN]['primarykey'] = $primaryN;
							$aArgsSaveN['n_' . $idN]['allVersions'] = array();
							$aArgsSaveN['n_' . $idN]['changedVersions'] = array();
							$aArgsSaveN['n_' . $idN]['columns'] = array($primaryN => 'i', $column_condN => 'i', $field['array_options']['column_cond'] => 'i', 'del' => 's');
							$aArgsSaveN['n_' . $idN]['aFieldsNumbers'] = array($column_condN, $field['array_options']['column_cond']);
							$aArgsSaveN['n_' . $idN]['excludeUpdateUni'] = array($primaryN => array(''), $column_condN => array(''), $field['array_options']['column_cond'] => array(''), 'del' => array(''));
						}
						if($numNx > 0) array_push($aArgsSaveN['n_' . $idN]['changedVersions'], array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']));
					}
				}
			}
		}
	}
}

?>