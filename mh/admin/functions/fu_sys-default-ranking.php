<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$functionPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'];
$functionFile = 'fu-' . $CONFIG['aModul']['modul_name'] . '-ranking.php';
//$functionFileOne2n = 'fu-' . $CONFIG['aModul']['modul_name'] . '-update-one2n.php';
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php');


if(file_exists($functionPath . $functionFile)){
	include_once($functionPath . $functionFile);
	
}else{
	$date = new DateTime();
	$now = $date->format('Y-m-d H:i:s');

	$variation = ($CONFIG['settings']['selectCountry'] == 0 && $CONFIG['settings']['selectLanguage'] == 0 && $CONFIG['settings']['selectDevice'] == 0) ? 'master' : 'local';
	
	$sync = '';
	$primaryRankinggroup = '';
	$doRank = false;
	foreach($CONFIG['aModul']['form'] as $aFieldsets){
		foreach($aFieldsets['fields'] as $aFields){
			if($aFields['index'] == 'rank'){
				if(in_array($aFields['specifications'][0], $CONFIG['system']['aFieldsAllowedSpecs']) && $aFields['specifications'][2] != 0) $doRank = true;
				if($aFields['specifications'][2] != 9){
					if($variation == 'master' && $aFields['specifications'][2] != 1) $doRank = false; 
					if($variation == 'local' && $aFields['specifications'][2] != 2) $doRank = false; 
				}
				
				$sync = $aFields['checksync'];
				if(isset($aFields['data_attributes']['primaryRankinggroup'])) $primaryRankinggroup = $aFields['data_attributes']['primaryRankinggroup'];
				break;
			}
		}
	}

	if($doRank == true){
		if($primaryRankinggroup == '') $primaryRankinggroup = 'id_cl';
		
		$table = ($CONFIG['aModul']['table_suffix'] == 0) ? $CONFIG['aModul']['table_name'] : $CONFIG['aModul']['table_name'] . 'uni';
		$tableLoc = ($CONFIG['aModul']['table_suffix'] == 0) ? $CONFIG['aModul']['table_name'] : $CONFIG['aModul']['table_name'] . 'loc';
		$primarykey = $CONFIG['aModul']['primarykey'];
	
		$aConditionRank = '';
		$aConditionRankPDO = array();
		//	if($varSQL['idPageParent'] == 26){
		//		$aConditionRank = 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '_data_full.id_page_parent = (:id_page_parent)';
		//		$aConditionRankPDO['id_page_parent'] = array($varSQL['idPageParent'], 'd');
		//	}
	
		
		######################################################
		// search for idRankinggroup
		if($varSQL['dir'] == 'desc'){
			$query = $CONFIG['dbconn'][0]->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primaryRankinggroup . ',
													' . $CONFIG['db'][0]['prefix'] . $table . '.rank
												FROM ' . $CONFIG['db'][0]['prefix'] . $table . ' 
												WHERE ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev = (:id_dev)
													AND ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primarykey . ' = (:id)
													AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . $table . '.del = (:nultime)
												'); 
			$query->bindValue(':id_count', $CONFIG['settings']['selectCountry'], PDO::PARAM_INT);
			$query->bindValue(':id_lang', $CONFIG['settings']['selectLanguage'], PDO::PARAM_INT);
			$query->bindValue(':id_dev', $CONFIG['settings']['selectDevice'], PDO::PARAM_INT);
			$query->bindValue(':id', $varSQL['idPrev'], PDO::PARAM_INT);
			$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query->execute();
			$num = $query->rowCount();
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);
			
			if($num > 0){
				$rankNew = $rows[0]['rank'] - 1;
				$idRankinggroup  = $rows[0][$primaryRankinggroup];
			}
			
			// if there is no previous dataset -> look for next dataset
			if($num == 0){
				$query = $CONFIG['dbconn'][0]->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primaryRankinggroup . ',
														' . $CONFIG['db'][0]['prefix'] . $table . '.rank
													FROM ' . $CONFIG['db'][0]['prefix'] . $table . ' 
													WHERE ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count = (:id_count)
														AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang = (:id_lang)
														AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev = (:id_dev)
														AND ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primarykey . ' = (:id)
														AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
														AND ' . $CONFIG['db'][0]['prefix'] . $table . '.del = (:nultime)
													'); 
				$query->bindValue(':id_count', $CONFIG['settings']['selectCountry'], PDO::PARAM_INT);
				$query->bindValue(':id_lang', $CONFIG['settings']['selectLanguage'], PDO::PARAM_INT);
				$query->bindValue(':id_dev', $CONFIG['settings']['selectDevice'], PDO::PARAM_INT);
				$query->bindValue(':id', $varSQL['idNext'], PDO::PARAM_INT);
				$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$query->execute();
				$num = $query->rowCount();
				$rows = $query->fetchAll(PDO::FETCH_ASSOC);
				
				if($num > 0){
					$rankNew = $rows[0]['rank'] + 1;
					$idRankinggroup  = $rows[0][$primaryRankinggroup];
				}
			}
		}else{
			$query = $CONFIG['dbconn'][0]->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primaryRankinggroup . ',
													' . $CONFIG['db'][0]['prefix'] . $table . '.rank
												FROM ' . $CONFIG['db'][0]['prefix'] . $table . ' 
												WHERE ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev = (:id_dev)
													AND ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primarykey . ' = (:id)
													AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . $table . '.del = (:nultime)
												'); 
			$query->bindValue(':id_count', $CONFIG['settings']['selectCountry'], PDO::PARAM_INT);
			$query->bindValue(':id_lang', $CONFIG['settings']['selectLanguage'], PDO::PARAM_INT);
			$query->bindValue(':id_dev', $CONFIG['settings']['selectDevice'], PDO::PARAM_INT);
			$query->bindValue(':id', $varSQL['idPrev'], PDO::PARAM_INT);
			$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query->execute();
			$num = $query->rowCount();
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);
			
			if($num > 0){
				$rankNew = $rows[0]['rank'] + 1;
				$idRankinggroup  = $rows[0][$primaryRankinggroup];
			}
			
			// if there is no previous dataset -> look for next dataset
			if($num == 0){
				$query = $CONFIG['dbconn'][0]->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primaryRankinggroup . ',
														' . $CONFIG['db'][0]['prefix'] . $table . '.rank
													FROM ' . $CONFIG['db'][0]['prefix'] . $table . ' 
													WHERE ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count = (:id_count)
														AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang = (:id_lang)
														AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev = (:id_dev)
														AND ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primarykey . ' = (:id)
														AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
														AND ' . $CONFIG['db'][0]['prefix'] . $table . '.del = (:nultime)
													'); 
				$query->bindValue(':id_count', $CONFIG['settings']['selectCountry'], PDO::PARAM_INT);
				$query->bindValue(':id_lang', $CONFIG['settings']['selectLanguage'], PDO::PARAM_INT);
				$query->bindValue(':id_dev', $CONFIG['settings']['selectDevice'], PDO::PARAM_INT);
				$query->bindValue(':id', $varSQL['idNext'], PDO::PARAM_INT);
				$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$query->execute();
				$num = $query->rowCount();
				$rows = $query->fetchAll(PDO::FETCH_ASSOC);
				
				if($num > 0){
					$rankNew = $rows[0]['rank'] - 1;
					$idRankinggroup  = $rows[0][$primaryRankinggroup];
				}
			}
		}
		######################################################
		
		######################################################
		// update dataset rank in _loc	
		if($sync == ''){
			$aArgsRank = array();
			$aArgsRank['table']= $table;
			$aArgsRank['tableLoc']= $tableLoc;
			$aArgsRank['primarykey']= $primarykey;
			$aArgsRank['aConditionRank']= $aConditionRank;
			$aArgsRank['aConditionRankPDO']= $aConditionRankPDO;
			$aArgsRank['sync']= $sync;
			$aArgsRank['primaryRankinggroup']= $primaryRankinggroup;
			$aArgsRank['rankNew']= $rankNew;
			$aArgsRank['idRankinggroup']= $idRankinggroup;
			$aArgsRank['id_count']= $CONFIG['settings']['selectCountry'];
			$aArgsRank['id_lang']= $CONFIG['settings']['selectLanguage'];
			$aArgsRank['id_dev']= $CONFIG['settings']['selectDevice'];
			$aArgsRank['id']= $varSQL['id'];
			$aArgsRank['now']= $now;
			doRanking($aArgsRank);
			
		}else{
		
			$aArgsLV = array();
			$aArgsLV['type'] = 'all';
			$aLocalVersions = localVariationsBuild($aArgsLV);
			
			if($sync == 'all'){
				foreach($aLocalVersions as $aVersion){
					$id_count = strval($aVersion[0]);
					$id_lang = strval($aVersion[1]);
					$id_dev = strval($aVersion[2]);
				
					$aArgsRank = array();
					$aArgsRank['table']= $table;
					$aArgsRank['tableLoc']= $tableLoc;
					$aArgsRank['primarykey']= $primarykey;
					$aArgsRank['aConditionRank']= $aConditionRank;
					$aArgsRank['aConditionRankPDO']= $aConditionRankPDO;
					$aArgsRank['sync']= $sync;
					$aArgsRank['primaryRankinggroup']= $primaryRankinggroup;
					$aArgsRank['rankNew']= $rankNew;
					$aArgsRank['idRankinggroup']= $idRankinggroup;
					$aArgsRank['id_count']= $id_count;
					$aArgsRank['id_lang']= $id_lang;
					$aArgsRank['id_dev']= $id_dev;
					$aArgsRank['id']= $varSQL['id'];
					$aArgsRank['now']= $now;
					doRanking($aArgsRank);
				}
			}
			
			if($sync == 'country'){
				foreach($aLocalVersions as $aVersion){
					$id_count = strval($aVersion[0]);
					$id_lang = strval($aVersion[1]);
					$id_dev = strval($aVersion[2]);
					
					if($id_lang == $CONFIG['settings']['selectLanguage'] && $id_dev == $CONFIG['settings']['selectDevice']){
						$aArgsRank = array();
						$aArgsRank['table']= $table;
						$aArgsRank['tableLoc']= $tableLoc;
						$aArgsRank['primarykey']= $primarykey;
						$aArgsRank['aConditionRank']= $aConditionRank;
						$aArgsRank['aConditionRankPDO']= $aConditionRankPDO;
						$aArgsRank['sync']= $sync;
						$aArgsRank['primaryRankinggroup']= $primaryRankinggroup;
						$aArgsRank['rankNew']= $rankNew;
						$aArgsRank['idRankinggroup']= $idRankinggroup;
						$aArgsRank['id_count']= $id_count;
						$aArgsRank['id_lang']= $id_lang;
						$aArgsRank['id_dev']= $id_dev;
						$aArgsRank['id']= $varSQL['id'];
						$aArgsRank['now']= $now;
						doRanking($aArgsRank);
					}
				}
			}
			
			if($sync == 'language'){
				foreach($aLocalVersions as $aVersion){
					$id_count = strval($aVersion[0]);
					$id_lang = strval($aVersion[1]);
					$id_dev = strval($aVersion[2]);
					
					if($id_count == $CONFIG['settings']['selectCountry'] && $id_dev == $CONFIG['settings']['selectDevice']){
						$aArgsRank = array();
						$aArgsRank['table']= $table;
						$aArgsRank['tableLoc']= $tableLoc;
						$aArgsRank['primarykey']= $primarykey;
						$aArgsRank['aConditionRank']= $aConditionRank;
						$aArgsRank['aConditionRankPDO']= $aConditionRankPDO;
						$aArgsRank['sync']= $sync;
						$aArgsRank['primaryRankinggroup']= $primaryRankinggroup;
						$aArgsRank['rankNew']= $rankNew;
						$aArgsRank['idRankinggroup']= $idRankinggroup;
						$aArgsRank['id_count']= $id_count;
						$aArgsRank['id_lang']= $id_lang;
						$aArgsRank['id_dev']= $id_dev;
						$aArgsRank['id']= $varSQL['id'];
						$aArgsRank['now']= $now;
						doRanking($aArgsRank);
					}
				}
			}
			
			if($sync == 'device'){
				foreach($aLocalVersions as $aVersion){
					$id_count = strval($aVersion[0]);
					$id_lang = strval($aVersion[1]);
					$id_dev = strval($aVersion[2]);
					
					if($id_count == $CONFIG['settings']['selectCountry'] && $id_lang == $CONFIG['settings']['selectLanguage']){
						$aArgsRank = array();
						$aArgsRank['table']= $table;
						$aArgsRank['tableLoc']= $tableLoc;
						$aArgsRank['primarykey']= $primarykey;
						$aArgsRank['aConditionRank']= $aConditionRank;
						$aArgsRank['aConditionRankPDO']= $aConditionRankPDO;
						$aArgsRank['sync']= $sync;
						$aArgsRank['primaryRankinggroup']= $primaryRankinggroup;
						$aArgsRank['rankNew']= $rankNew;
						$aArgsRank['idRankinggroup']= $idRankinggroup;
						$aArgsRank['id_count']= $id_count;
						$aArgsRank['id_lang']= $id_lang;
						$aArgsRank['id_dev']= $id_dev;
						$aArgsRank['id']= $varSQL['id'];
						$aArgsRank['now']= $now;
						doRanking($aArgsRank);
					}
				}
			}
			
			if($sync == 'countrylanguage'){
				foreach($aLocalVersions as $aVersion){
					$id_count = strval($aVersion[0]);
					$id_lang = strval($aVersion[1]);
					$id_dev = strval($aVersion[2]);
					
					if($id_dev == $CONFIG['settings']['selectDevice']){
						$aArgsRank = array();
						$aArgsRank['table']= $table;
						$aArgsRank['tableLoc']= $tableLoc;
						$aArgsRank['primarykey']= $primarykey;
						$aArgsRank['aConditionRank']= $aConditionRank;
						$aArgsRank['aConditionRankPDO']= $aConditionRankPDO;
						$aArgsRank['sync']= $sync;
						$aArgsRank['primaryRankinggroup']= $primaryRankinggroup;
						$aArgsRank['rankNew']= $rankNew;
						$aArgsRank['idRankinggroup']= $idRankinggroup;
						$aArgsRank['id_count']= $id_count;
						$aArgsRank['id_lang']= $id_lang;
						$aArgsRank['id_dev']= $id_dev;
						$aArgsRank['id']= $varSQL['id'];
						$aArgsRank['now']= $now;
						doRanking($aArgsRank);
					}
				}
			}
			
			if($sync == 'countrydevice'){
				foreach($aLocalVersions as $aVersion){
					$id_count = strval($aVersion[0]);
					$id_lang = strval($aVersion[1]);
					$id_dev = strval($aVersion[2]);
					
					if($id_lang == $CONFIG['settings']['selectLanguage']){
						$aArgsRank = array();
						$aArgsRank['table']= $table;
						$aArgsRank['tableLoc']= $tableLoc;
						$aArgsRank['primarykey']= $primarykey;
						$aArgsRank['aConditionRank']= $aConditionRank;
						$aArgsRank['aConditionRankPDO']= $aConditionRankPDO;
						$aArgsRank['sync']= $sync;
						$aArgsRank['primaryRankinggroup']= $primaryRankinggroup;
						$aArgsRank['rankNew']= $rankNew;
						$aArgsRank['idRankinggroup']= $idRankinggroup;
						$aArgsRank['id_count']= $id_count;
						$aArgsRank['id_lang']= $id_lang;
						$aArgsRank['id_dev']= $id_dev;
						$aArgsRank['id']= $varSQL['id'];
						$aArgsRank['now']= $now;
						doRanking($aArgsRank);
					}
				}
			}
		
			if($sync == 'languagedevice'){
				foreach($aLocalVersions as $aVersion){
					$id_count = strval($aVersion[0]);
					$id_lang = strval($aVersion[1]);
					$id_dev = strval($aVersion[2]);
					
					if($id_count == $CONFIG['settings']['selectCountry']){
						$aArgsRank = array();
						$aArgsRank['table']= $table;
						$aArgsRank['tableLoc']= $tableLoc;
						$aArgsRank['primarykey']= $primarykey;
						$aArgsRank['aConditionRank']= $aConditionRank;
						$aArgsRank['aConditionRankPDO']= $aConditionRankPDO;
						$aArgsRank['sync']= $sync;
						$aArgsRank['primaryRankinggroup']= $primaryRankinggroup;
						$aArgsRank['rankNew']= $rankNew;
						$aArgsRank['idRankinggroup']= $idRankinggroup;
						$aArgsRank['id_count']= $id_count;
						$aArgsRank['id_lang']= $id_lang;
						$aArgsRank['id_dev']= $id_dev;
						$aArgsRank['id']= $varSQL['id'];
						$aArgsRank['now']= $now;
						doRanking($aArgsRank);
					}
				}
			}
		}
	}
}
	
	
function doRanking($aArgsRank){
	global $CONFIG;
	
	########################################################################
	// edit _uni because only there are all records and variations available
	$query = $CONFIG['dbconn'][0]->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . $aArgsRank['table'] . '
											(id_count, id_lang, id_dev, id_cl, ' . $aArgsRank['primarykey'] . ', rank, create_at, create_from, change_from)
										VALUES
											(:id_count, :id_lang, :id_dev, :id_cl, :id, :rank, :create_at, :create_from, :create_from)
										ON DUPLICATE KEY UPDATE 
											rank = (:rank),
											change_from = (:create_from)
										');
	$query->bindValue(':id_count', $aArgsRank['id_count'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $aArgsRank['id_lang'], PDO::PARAM_INT);
	$query->bindValue(':id_dev', $aArgsRank['id_dev'], PDO::PARAM_INT);
	$query->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
	$query->bindValue(':id', $aArgsRank['id'], PDO::PARAM_INT);
	$query->bindValue(':rank', $aArgsRank['rankNew'], PDO::PARAM_INT);
	$query->bindValue(':create_at', $aArgsRank['now'], PDO::PARAM_STR);
	$query->bindValue(':create_from', $CONFIG['user']['id'], PDO::PARAM_INT);
	$query->execute();
	########################################################################
	
	########################################################################
	// set ranking to 10+
	// select from _uni ...
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . $aArgsRank['table'] . '.' . $aArgsRank['primarykey'] . '
										FROM ' . $CONFIG['db'][0]['prefix'] . $aArgsRank['table'] . ' 
										WHERE ' . $CONFIG['db'][0]['prefix'] . $aArgsRank['table'] . '.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . $aArgsRank['table'] . '.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . $aArgsRank['table'] . '.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . $aArgsRank['table'] . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . $aArgsRank['table'] . '.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . $aArgsRank['table'] . '.' . $aArgsRank['primaryRankinggroup'] . ' = (:idRankinggroup)
											' . $aArgsRank['aConditionRank'] . '
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . $aArgsRank['table'] . '.rank
										'); 
	$query->bindValue(':id_count', $aArgsRank['id_count'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $aArgsRank['id_lang'], PDO::PARAM_INT);
	$query->bindValue(':id_dev', $aArgsRank['id_dev'], PDO::PARAM_INT);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':idRankinggroup', $aArgsRank['idRankinggroup'], PDO::PARAM_INT);
	foreach($aArgsRank['aConditionRankPDO'] as $k=>$v){
		if($v[1] == 'd'){
			$query->bindValue(':'.$k, $v[0], PDO::PARAM_INT);
		}else if($v[1] == 'sl'){
			$query->bindValue(':'.$k, '%'.$v[0].'%', PDO::PARAM_STR);
		}else if($v[1] == 'slb'){
			$query->bindValue(':'.$k, '%'.$v[0], PDO::PARAM_STR);
		}else if($v[1] == 'sle'){
			$query->bindValue(':'.$k, $v[0].'%', PDO::PARAM_STR);
		}else{
			$query->bindValue(':'.$k, $v[0], PDO::PARAM_STR);
		}
	}
	$query->execute();
	$num = $query->rowCount();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	
	$rank = 10;
	foreach($rows as $row){
		// ... save to _loc ...
		$query2 = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . $aArgsRank['tableLoc'] . '
												(id_count, id_lang, id_dev, id_cl, ' . $aArgsRank['primarykey'] . ', rank, create_at, create_from, change_from)
											VALUES
												(:id_count, :id_lang, :id_dev, :id_cl, :id, :rank, :create_at, :create_from, :create_from)
											ON DUPLICATE KEY UPDATE 
												rank = (:rank),
												change_from = (:create_from)
											');
		$query2->bindValue(':id_count', $aArgsRank['id_count'], PDO::PARAM_INT);
		$query2->bindValue(':id_lang', $aArgsRank['id_lang'], PDO::PARAM_INT);
		$query2->bindValue(':id_dev', $aArgsRank['id_dev'], PDO::PARAM_INT);
		$query2->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
		$query2->bindValue(':id', $row[$aArgsRank['primarykey']], PDO::PARAM_INT);
		$query2->bindValue(':rank', $rank, PDO::PARAM_INT);
		$query2->bindValue(':create_at', $aArgsRank['now'], PDO::PARAM_STR);
		$query2->bindValue(':create_from', $CONFIG['user']['id'], PDO::PARAM_INT);
		$query2->execute();
		
		// ... and same to _uni
		$query2 = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . $aArgsRank['table'] . '
												(id_count, id_lang, id_dev, id_cl, ' . $aArgsRank['primarykey'] . ', rank, create_at, create_from, change_from)
											VALUES
												(:id_count, :id_lang, :id_dev, :id_cl, :id, :rank, :create_at, :create_from, :create_from)
											ON DUPLICATE KEY UPDATE 
												rank = (:rank),
												change_from = (:create_from)
											');
		$query2->bindValue(':id_count', $aArgsRank['id_count'], PDO::PARAM_INT);
		$query2->bindValue(':id_lang', $aArgsRank['id_lang'], PDO::PARAM_INT);
		$query2->bindValue(':id_dev', $aArgsRank['id_dev'], PDO::PARAM_INT);
		$query2->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
		$query2->bindValue(':id', $row[$aArgsRank['primarykey']], PDO::PARAM_INT);
		$query2->bindValue(':rank', $rank, PDO::PARAM_INT);
		$query2->bindValue(':create_at', $aArgsRank['now'], PDO::PARAM_STR);
		$query2->bindValue(':create_from', $CONFIG['user']['id'], PDO::PARAM_INT);
		$query2->execute();
		
		$rank += 10;
	}
}
########################################################################



?>