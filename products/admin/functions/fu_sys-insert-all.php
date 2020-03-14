<?php
#####################################################################################
// Fill xxx_uni for LPMD import
#####################################################################################
function insertAllProducts($modul, $table, $primekey, $id, $columns, $aFieldsNumbers, $aChangedVersions, $addCondition='', $saveVer=array()){
	global $CONFIG, $insertAll;
	
	$date = new DateTime(); 
	$now = $date->format('Y-m-d H:i:s');
	$now0 = '0000-00-00 00:00:00';
	
	$aListLanguagesByCountries = array();
	$aListDevices = array();
	$aSaveVersions = array();

	if(in_array(array(0,0,0), $aChangedVersions)){
		$aSaveVersions = $saveVer;
	}else{
		// if master has not changed, change only changed versions
		$aSaveVersions = $aChangedVersions;
	}

	
	foreach($aSaveVersions as $aVersion){
		$id_count = $aVersion[0];
		$id_lang = $aVersion[1];
		$id_dev = $aVersion[2];
		$id_clid = 0;
		
		// External variation
		$queryE = $CONFIG['dbconn']->prepare('
											SELECT ' . str_replace('##TYPE##', 'ext', $columns[0]) . ',
												' . str_replace('##TYPE##', 'extloc', $columns[1]) . ',
												' . str_replace('##TYPE##', 'loc', $columns[3]) . '
											FROM ' . $table . '_ext 
											
											LEFT JOIN ' . $table . '_ext AS  ' . $table . '_extloc
												ON ' . $table . '_ext.' . $primekey . '=' . $table . '_extloc.' . $primekey . '
													AND ' . $table . '_extloc.id_count = (:id_count)
													AND ' . $table . '_extloc.id_lang = (:id_lang)
													AND ' . $table . '_extloc.id_dev = (:id_dev)
													AND ' . $table . '_extloc.id_clid = (:id_clid)
													AND ' . $table . '_extloc.del = (:nultime) 
													' . str_replace('##TYPE##', 'extloc', $addCondition) . '
											
											LEFT JOIN ' . $table . '_loc 
												ON ' . $table . '_ext.' . $primekey . '=' . $table . '_loc.' . $primekey . '
													AND ' . $table . '_loc.id_count = (:id_count)
													AND ' . $table . '_loc.id_lang = (:id_lang)
													AND ' . $table . '_loc.id_dev = (:id_dev)
													AND ' . $table . '_loc.id_clid = (:id_clid)
													AND ' . $table . '_loc.del = (:nultime) 
													' . str_replace('##TYPE##', 'loc', $addCondition) . '
											
											WHERE ' . $table . '_ext.id_count = (:nul)
												AND ' . $table . '_ext.id_lang = (:nul)
												AND ' . $table . '_ext.id_dev = (:nul)
												AND ' . $table . '_ext.id_clid = (:id_clid)
												AND ' . $table . '_ext.' . $primekey . ' = (:primekey)
												AND ' . $table . '_ext.del = (:nultime)
												' . str_replace('##TYPE##', 'ext', $addCondition) . '
											');
		$queryE->bindValue(':primekey', $id, PDO::PARAM_INT);
		$queryE->bindValue(':id_count', $id_count, PDO::PARAM_INT);
		$queryE->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
		$queryE->bindValue(':id_dev', $id_dev, PDO::PARAM_INT);
		$queryE->bindValue(':id_clid', $id_clid, PDO::PARAM_INT);
		$queryE->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryE->execute();
		$rowsE = $queryE->fetchAll(PDO::FETCH_ASSOC); 
		$numE = $queryE->rowCount();
		if($numE > 0){
			foreach($rowsE[0] as $k=>$v){
				if(substr_count($k, '_extloc') == 0 && substr_count($k, '_locall') == 0 && substr_count($k, '_locloc') == 0){
					if(isset($rowsE[0][$k.'_extloc'])){
						if((!in_array($k, $aFieldsNumbers) && $rowsE[0][$k.'_extloc'] != "") || (in_array($k, $aFieldsNumbers) && $rowsE[0][$k.'_extloc'] > 0)) $rowsE[0][$k] = $rowsE[0][$k.'_extloc'];
						//unset($rowsE[0][$k.'_extloc']);
					}
					if(isset($rowsE[0][$k.'_locloc'])){
						if((!in_array($k, $aFieldsNumbers) && $rowsE[0][$k.'_locloc'] != "") || (in_array($k, $aFieldsNumbers) && $rowsE[0][$k.'_locloc'] > 0)) $rowsE[0][$k] = $rowsE[0][$k.'_locloc'];
						//unset($rowsE[0][$k.'_locloc']);
					}
				}
			}
		}
		

		if($numE > 0){
			$col2 = '';
			$value2 = '';
			$upd2 = "";
			foreach($rowsE[0] as $key2 => $val2){
				if(substr_count($key2, '_extloc') == 0 && substr_count($key2, '_locall') == 0 && substr_count($key2, '_locloc') == 0){
					$col2 .= ', ' . $key2;
					$value2 .= ', "' . str_replace('"', '\"', $val2) . '"';
					$upd2 .= $key2.' = "' . str_replace('"', '\"', $val2) . '", ' ;
				}
			}

			$query = 'INSERT INTO ' . $table . '_uni ';
			$query .= '(id_count, id_lang, id_dev, id_clid, create_at, create_from, change_from' . $col2 . ') ';
			$query .= 'VALUES ';					
			$query .= '('.$id_count.', '.$id_lang.', '.$id_dev.', '.$id_clid.', "'.$now0.'", '.$CONFIG['USER']['id_real'].', '.$CONFIG['USER']['id_real']. $value2 . ') '; 
			$query .= 'ON DUPLICATE KEY UPDATE ';	
			$query .= '' . $upd2 . ' change_from='.$CONFIG['USER']['id_real'].', del="0000-00-00 00:00:00";';
			$insertAll .= $query . '
';
		}
	}
}






#####################################################################################
// Fill xxx_uni
#####################################################################################
function insertAll($modul, $table, $primekey, $id, $columns, $aFieldsNumbers, $aChangedVersions, $addCondition=''){
	global $CONFIG;
	
	$date = new DateTime(); 
	$now = $date->format('Y-m-d H:i:s');
	
	$aListLanguagesByCountries = array();
	$aListDevices = array();
	$aSaveVersions = array();

	if(in_array(array(0,0,0), $aChangedVersions)){
		// if master has changed, change all in _uni
		$aListLanguagesByCountries = (substr($CONFIG['page']['moduls'][$modul]['specifics'], 3, 1) == 9) ? readLanguagesByCountries($modul) : readLanguagesByCountriesSpecCountry($modul, 0);
		$aListDevices = readDevices($modul);

		foreach($aListLanguagesByCountries as $id_count => $aListLanguages){
			foreach($aListLanguages as $id_lang){
				foreach($aListDevices as $id_dev => $device){
					array_push($aSaveVersions, array((int)$id_count, (int)$id_lang, (int)$id_dev));
				}
			}
		}
	}else{
		// if master has not changed, change only changed versions
		$aSaveVersions = $aChangedVersions;
	}


//	var_dump($aSaveVersions);
//	exit();
//	
	
	foreach($aSaveVersions as $aVersion){
		$id_count = $aVersion[0];
		$id_lang = $aVersion[1];
		$id_dev = $aVersion[2];
		$id_clid = (substr($CONFIG['page']['moduls'][$modul]['specifics'], 7, 1) == 9) ? $CONFIG['USER']['activeClient'] : 0;
		
		// External all
		$queryE = $CONFIG['dbconn']->prepare('
											SELECT ' . str_replace('##TYPE##', 'ext', $columns) . '
											FROM ' . $table . '_ext 
											WHERE ' . $table . '_ext.' . $primekey . ' = (:primekey)
												AND ' . $table . '_ext.id_count = (:id_count)
												AND ' . $table . '_ext.id_lang = (:id_lang)
												AND ' . $table . '_ext.id_dev = (:id_dev)
												AND (' . $table . '_ext.id_clid = (:id_clid)
													OR ' . $table . '_ext.id_clid = (:nul))
												AND ' . $table . '_ext.del = (:nultime)
												' . str_replace('##TYPE##', 'ext', $addCondition) . '
											');
		$queryE->bindValue(':primekey', $id, PDO::PARAM_INT);
		$queryE->bindValue(':id_count', 0, PDO::PARAM_INT);
		$queryE->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$queryE->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$queryE->bindValue(':id_clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
		$queryE->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryE->execute();
		$rowsE = $queryE->fetchAll(PDO::FETCH_ASSOC);
		$numE = $queryE->rowCount();
		
		// External variation
		$queryEv = $CONFIG['dbconn']->prepare('
											SELECT ' . str_replace('##TYPE##', 'ext', $columns) . '
											FROM ' . $table . '_ext 
											WHERE ' . $table . '_ext.' . $primekey . ' = (:primekey)
												AND ' . $table . '_ext.id_count = (:id_count)
												AND ' . $table . '_ext.id_lang = (:id_lang)
												AND ' . $table . '_ext.id_dev = (:id_dev)
												AND (' . $table . '_ext.id_clid = (:id_clid)
													OR ' . $table . '_ext.id_clid = (:nul))
												AND ' . $table . '_ext.del = (:nultime)
												' . str_replace('##TYPE##', 'ext', $addCondition) . '
											');
		$queryEv->bindValue(':primekey', $id, PDO::PARAM_INT);
		$queryEv->bindValue(':id_count', $id_count, PDO::PARAM_INT);
		$queryEv->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
		$queryEv->bindValue(':id_dev', $id_dev, PDO::PARAM_INT);
		$queryEv->bindValue(':id_clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
		$queryEv->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryEv->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryEv->execute();
		$rowsEv = $queryEv->fetchAll(PDO::FETCH_ASSOC);
		$numEv = $queryEv->rowCount();
		if($numEv > 0){
			foreach($rowsEv[0] as $k=>$v){
				if((!in_array($k, $aFieldsNumbers) && $v != "") || (in_array($k, $aFieldsNumbers) && $v > 0)) $rowsE[0][$k] = $v;
			}
		}
		
		// Local all
		$queryL = $CONFIG['dbconn']->prepare('
											SELECT ' . str_replace('##TYPE##', 'loc', $columns) . '
											FROM ' . $table . '_loc 
											WHERE ' . $table . '_loc.' . $primekey . ' = (:primekey)
												AND ' . $table . '_loc.id_count = (:id_count)
												AND ' . $table . '_loc.id_lang = (:id_lang)
												AND ' . $table . '_loc.id_dev = (:id_dev)
												AND (' . $table . '_loc.id_clid = (:id_clid)
													OR ' . $table . '_loc.id_clid = (:nul))
												AND ' . $table . '_loc.del = (:nultime)
												' . str_replace('##TYPE##', 'loc', $addCondition) . '
											');
		$queryL->bindValue(':primekey', $id, PDO::PARAM_INT);
		$queryL->bindValue(':id_count', 0, PDO::PARAM_INT);
		$queryL->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$queryL->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$queryL->bindValue(':id_clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
		$queryL->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryL->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryL->execute();
		$rowsL = $queryL->fetchAll(PDO::FETCH_ASSOC);
		$numL = $queryL->rowCount();
		if($numL > 0){
			foreach($rowsL[0] as $k=>$v){
				if((!in_array($k, $aFieldsNumbers) && $v != "") || (in_array($k, $aFieldsNumbers) && $v > 0)) $rowsE[0][$k] = $v;
			}
		}
		
		// Local variation
		$queryLv = $CONFIG['dbconn']->prepare('
											SELECT ' . str_replace('##TYPE##', 'loc', $columns) . '
											FROM ' . $table . '_loc 
											WHERE ' . $table . '_loc.' . $primekey . ' = (:primekey)
												AND ' . $table . '_loc.id_count = (:id_count)
												AND ' . $table . '_loc.id_lang = (:id_lang)
												AND ' . $table . '_loc.id_dev = (:id_dev)
												AND (' . $table . '_loc.id_clid = (:id_clid)
													OR ' . $table . '_loc.id_clid = (:nul))
												AND ' . $table . '_loc.del = (:nultime)
												' . str_replace('##TYPE##', 'loc', $addCondition) . '
											');
		$queryLv->bindValue(':primekey', $id, PDO::PARAM_INT);
		$queryLv->bindValue(':id_count', $id_count, PDO::PARAM_INT);
		$queryLv->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
		$queryLv->bindValue(':id_dev', $id_dev, PDO::PARAM_INT);
		$queryLv->bindValue(':id_clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
		$queryLv->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryLv->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryLv->execute();
		$rowsLv = $queryLv->fetchAll(PDO::FETCH_ASSOC);
		$numLv = $queryLv->rowCount();
		if($numLv > 0){
			foreach($rowsLv[0] as $k=>$v){
				if((!in_array($k, $aFieldsNumbers) && $v != "") || (in_array($k, $aFieldsNumbers) && $v > 0)) $rowsE[0][$k] = $v;
			}
		}
		

		// Check if recordset exists
		$query = $CONFIG['dbconn']->prepare('
											SELECT ' . $table . '_uni.' . $primekey . '
											FROM ' . $table . '_uni 
											WHERE ' . $table . '_uni.' . $primekey . ' = (:primekey)
												AND ' . $table . '_uni.id_count = (:id_count)
												AND ' . $table . '_uni.id_lang = (:id_lang)
												AND ' . $table . '_uni.id_dev = (:id_dev)
												AND (' . $table . '_uni.id_clid = (:id_clid)
													OR ' . $table . '_uni.id_clid = (:nul))
												' . str_replace('##TYPE##', 'uni', $addCondition) . '
											');
		$query->bindValue(':primekey', $id, PDO::PARAM_INT);
		$query->bindValue(':id_count', $id_count, PDO::PARAM_INT);
		$query->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
		$query->bindValue(':id_dev', $id_dev, PDO::PARAM_INT);
		$query->bindValue(':id_clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
		$query->bindValue(':nul', 0, PDO::PARAM_INT);
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		$num = $query->rowCount();

		if(($numE + $numEv + $numL + $numLv) > 0){
			if($num == 0){
				$col2 = '';
				$value2 = '';
				foreach($rowsE[0] as $key2 => $val2){
					$col2 .= ', ' . $key2;
					$value2 .= ', (:' . $key2 . ')';
				}
	
				$query = $CONFIG['dbconn']->prepare('
													INSERT INTO ' . $table . '_uni
													(id_count, id_lang, id_dev, id_clid, create_at, create_from, change_from' . $col2 . ')
													VALUES
													(:id_count, :id_lang, :id_dev, :id_clid, :now, :change_from, :change_from' . $value2 . ')
													');
				$query->bindValue(':id_count', $id_count, PDO::PARAM_INT);
				$query->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
				$query->bindValue(':id_dev', $id_dev, PDO::PARAM_INT);
				$query->bindValue(':id_clid', $id_clid, PDO::PARAM_INT);
				$query->bindValue(':now', $now, PDO::PARAM_STR);
				$query->bindValue(':change_from', $CONFIG['USER']['id_real'], PDO::PARAM_INT); 

				foreach($rowsE[0] as $key2=>$val2){
					if(in_array($key2, $aFieldsNumbers)){
						$query->bindValue(':'.$key2, $val2, PDO::PARAM_INT);
					}else{
						$query->bindValue(':'.$key2, $val2, PDO::PARAM_STR);
					}
				}
			}else{
				$upd2 = "";
				foreach($rowsE[0] as $key2=>$val2){
					$upd2 .= $key2.' = (:'.$key2.'), ' ;
				}
				
				$query = $CONFIG['dbconn']->prepare('
													UPDATE ' . $table . '_uni SET
													' . $upd2 . '
													change_from = (:change_from),
													del = (:nultime)
													WHERE ' . $table . '_uni.' . $primekey . ' = (:primekey)
														AND ' . $table . '_uni.id_count = (:id_count)
														AND ' . $table . '_uni.id_lang = (:id_lang)
														AND ' . $table . '_uni.id_dev = (:id_dev)
														AND (' . $table . '_uni.id_clid = (:id_clid)
															OR ' . $table . '_uni.id_clid = (:nul))
														' . str_replace('##TYPE##', 'uni', $addCondition) . '
													LIMIT 1
													');
				$query->bindValue(':primekey', $id, PDO::PARAM_INT);
				$query->bindValue(':id_count', $id_count, PDO::PARAM_INT);
				$query->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
				$query->bindValue(':id_dev', $id_dev, PDO::PARAM_INT);
				$query->bindValue(':id_clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
				$query->bindValue(':nul', 0, PDO::PARAM_INT);
				$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$query->bindValue(':change_from', $CONFIG['USER']['id_real'], PDO::PARAM_INT); 

				foreach($rowsE[0] as $key2=>$val2){
					if(in_array($key2, $aFieldsNumbers)){
						$query->bindValue(':'.$key2, $val2, PDO::PARAM_INT);
					}else{
						$query->bindValue(':'.$key2, $val2, PDO::PARAM_STR);
					}
				}
			}
			$query->execute();
		}
	}
}


?>