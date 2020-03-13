<?php 
include_once(__DIR__ . '/fu-templates-update-brochure.php');

//use \Howtomakeaturn\PDFInfo\PDFInfo;
//		
//$aFields = array();
//$aFields['title'] = array();
//$aFields['title_transrequired'] = array('default'=>'1', 'val2read'=>array('bool2text'=>array('text'=>'check')));
//
//$aArgs = array();
//$aArgs['id_count'] = $CONFIG['settings']['selectCountry'];
//$aArgs['id_lang'] = $CONFIG['settings']['selectLanguage'];
//$aArgs['id_dev'] = $CONFIG['settings']['selectDevice'];
//$aArgs['usesystem'] = 1;
//
//$aArgs['fields'] = array();
//foreach($aFields as $key => $field){
//	if(isset($field['val2read'])){
//		foreach($field['val2read'] as $type => $aVal2read){
//			if(!array_key_exists($type, $aArgs['fields'])) $aArgs['fields'][$type] = array();
//			$aArgs['fields'][$type][$key] = $aVal2read;
//		}
//	}
//}
//	
//$aArgsSaveN = array();
//$aArgsSaveTPE = array();
//
//$aArgsSave = array();
//$aArgsSave['id_data'] = $CONFIG['page']['id_data'];
//$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . '_templates_';
//$aArgsSave['primarykey'] = 'id_tempid';
//$aArgsSave['allVersions'] = array();
//$aArgsSave['changedVersions'] = array();
//
//$aArgsSave['columns'] = array();
//$aArgsSave['columns']['id_tempid'] = 'i';
//$aArgsSave['columns']['id_caid'] = 'i';
//$aArgsSave['columns']['title'] = 's';
//$aArgsSave['columns']['contentselect'] = 's';
//$aArgsSave['columns']['title_transrequired'] = 'i';
//$aArgsSave['columns']['components'] = 's';
//
//$aArgsSave['aFieldsNumbers'] = array();
//array_push($aArgsSave['aFieldsNumbers'], 'id_tempid');
//array_push($aArgsSave['aFieldsNumbers'], 'id_caid');
//array_push($aArgsSave['aFieldsNumbers'], 'title_transrequired');
//
//$aArgsSave['excludeUpdateUni'] = array();
//$aArgsSave['excludeUpdateUni']['id_tempid'] = array('');
//$aArgsSave['excludeUpdateUni']['id_caid'] = array('',0);
//$aArgsSave['excludeUpdateUni']['title'] = array('');
//$aArgsSave['excludeUpdateUni']['contentselect'] = array('');
//$aArgsSave['excludeUpdateUni']['title_transrequired'] = array('');
//$aArgsSave['excludeUpdateUni']['components'] = array('');
//
//$aFieldsSaveMaster = array();
//array_push($aFieldsSaveMaster, 'id_tempid');
//array_push($aFieldsSaveMaster, 'id_caid');
//array_push($aFieldsSaveMaster, 'title');
//array_push($aFieldsSaveMaster, 'contentselect');
//array_push($aFieldsSaveMaster, 'title_transrequired');
//array_push($aFieldsSaveMaster, 'components');
//$aFieldsSaveNotMaster = array();
//array_push($aFieldsSaveNotMaster, 'id_tempid');
//array_push($aFieldsSaveNotMaster, 'id_caid');
//array_push($aFieldsSaveNotMaster, 'title');
//array_push($aFieldsSaveNotMaster, 'contentselect');
//array_push($aFieldsSaveNotMaster, 'title_transrequired');
//array_push($aFieldsSaveNotMaster, 'components');
//
//$aProcessedFiles = array();	
//	
//$query = $CONFIG['dbconn'][0]->prepare('
//									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data
//									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
//									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
//									');
//$query->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
//$query->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
//$query->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
//$query->execute();
//$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//$num = $query->rowCount();
//
//foreach($rows as $row){
//	$variation = ($row['id_count'] == 0 && $row['id_lang'] == 0 && $row['id_dev'] == 0) ? 'master' : 'local';
//	
//	$aArgs['data'] = json_decode($row['data'], true);
//
//	$aArgsSave['aData'] = setValuesSave($aArgs);
//	$aArgsSave['aData']['id_count'] = $row['id_count'];
//	$aArgsSave['aData']['id_lang'] = $row['id_lang'];
//	$aArgsSave['aData']['id_dev'] = $row['id_dev'];
//	$aArgsSave['aData']['id_cl'] = $row['id_cl'];
//
//	$aChange = checkChanges($aArgsSave);
//
//	$col = '';
//	$val = '';
//	$upd = '';
//	foreach($aChange['aChangedFields'] as $field){
//		if(($variation == 'master' && in_array($field, $aFieldsSaveMaster)) || ($variation == 'local' && in_array($field, $aFieldsSaveNotMaster))){
//			if($field != $aArgsSave['primarykey']){
//				$col .= ', ' . $field;
//				$val .= ', :' . $field . '';
//				$upd .= $field.' = (:'.$field.'), ' ;
//			}
//		}
//	}
//
//	$qry = 'INSERT INTO ' . $aArgsSave['table'] . 'loc
//				(' . $aArgsSave['primarykey'] . ', id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from' . $col . ')
//			VALUES
//				(:id, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from' . $val . ')
//			ON DUPLICATE KEY UPDATE 
//				' . $upd . '
//				change_from = (:create_from),
//				del = (:nultime)
//			';
//	$queryC = $CONFIG['dbconn'][0]->prepare($qry);
//	$queryC->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
//	$queryC->bindValue(':id_count', $aArgsSave['aData']['id_count'], PDO::PARAM_INT);
//	$queryC->bindValue(':id_lang', $aArgsSave['aData']['id_lang'], PDO::PARAM_INT);
//	$queryC->bindValue(':id_dev', $aArgsSave['aData']['id_dev'], PDO::PARAM_INT);
//	$queryC->bindValue(':id_cl', $aArgsSave['aData']['id_cl'], PDO::PARAM_INT);
//	$queryC->bindValue(':now', $now, PDO::PARAM_STR);
//	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
//	
//	foreach($aChange['aChangedFields'] as $field){
//		if(($variation == 'master' && in_array($field, $aFieldsSaveMaster)) || ($variation == 'local' && in_array($field, $aFieldsSaveNotMaster))){
//			if($field != $aArgsSave['primarykey']){
//				if($aArgsSave['columns'][$field] == 'i' || $aArgsSave['columns'][$field] == 'si' || $aArgsSave['columns'][$field] == 'b'){
//					$queryC->bindValue(':'.$field, (is_array($aArgsSave['aData'][$field])) ? json_encode($aArgsSave['aData'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_INT);
//				}else{ 
//					$queryC->bindValue(':'.$field, (is_array($aArgsSave['aData'][$field])) ? json_encode($aArgsSave['aData'][$field]) : trim($aArgsSave['aData'][$field]), PDO::PARAM_STR);
//				}
//			}
//		}
//	}
//	$queryC->execute();
//	$numC = $queryC->rowCount();
//
//	array_push($aArgsSave['allVersions'], array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']));
//	if($numC > 0 || count($aChange['aDataOld'] == 0)) array_push($aArgsSave['changedVersions'], array($aArgsSave['aData']['id_count'], $aArgsSave['aData']['id_lang'], $aArgsSave['aData']['id_dev']));
//	
//
//
//	#########################################
//	// save countries
//	$queryN = $CONFIG['dbconn'][0]->prepare('
//										DELETE 
//										FROM ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_ 
//										
//										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_tempid = (:id)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_countid IN ('. implode(',', array_keys($CONFIG['user']['countries'])) . ')
//										');
//	$queryN->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
//	$queryN->execute();
//
//	if(isset($aArgsSave['aData']['country'])){
//		$aArgsSave['aData']['country'] = array_unique($aArgsSave['aData']['country']);
//		foreach($aArgsSave['aData']['country'] as $val){
//			$queryN = $CONFIG['dbconn'][0]->prepare('
//												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_
//												(id_tempid, id_countid)
//												VALUES
//												(:id, :id_countid)
//												');
//			$queryN->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
//			$queryN->bindValue(':id_countid', $val, PDO::PARAM_INT);
//			$queryN->execute();
//		}
//	}
//
//
//	#########################################
//	// save pages
//	$page = (isset($aArgs['data']['page']) && $aArgs['data']['page'] != '') ? $aArgs['data']['page'] : 1;
//	$pageNum = (isset($aArgs['data']['pageNum']) && $aArgs['data']['pageNum'] != '') ? $aArgs['data']['pageNum'] : '';
//	$aDimension = (isset($aArgs['data']['aDimension'])) ? $aArgs['data']['aDimension'] : '';
//	$aArgsSave['aData']['file_original'][0] = '';
//					
//	if(isset($aArgsSave['aData']['specsheet_original'][0])){
//		$aArgsSave['aData']['file_original'][0] = $aArgsSave['aData']['specsheet_original'][0];
//				
//		$queryP = $CONFIG['dbconn'][0]->prepare('
//											SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.filesys_filename
//											FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc 
//											WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_count = (:id_count)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_lang = (:id_lang)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_dev = (:id_dev)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mid = (:id_mid)
//											');
//		$queryP->bindValue(':id_count', $aArgsSave['aData']['id_count'], PDO::PARAM_INT);
//		$queryP->bindValue(':id_lang', $aArgsSave['aData']['id_lang'], PDO::PARAM_INT);
//		$queryP->bindValue(':id_dev', $aArgsSave['aData']['id_dev'], PDO::PARAM_INT);
//		$queryP->bindValue(':id_mid', $aArgsSave['aData']['file_original'][0], PDO::PARAM_INT);
//		$queryP->execute();
//		$rowsP = $queryP->fetchAll(PDO::FETCH_ASSOC);
//		$numP = $queryP->rowCount();
//		
//		if($numP > 0){
//			$fileOriginal = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'] . $rowsP[0]['filesys_filename'];
//			$dirTarget = $CONFIG['system']['directoryRoot'] . 'assetimages/';
//			if(!in_array($rowsP[0]['filesys_filename'], $aProcessedFiles)){
//				if(file_exists($fileOriginal)){
//					$aFilenameOriginal = explode('.', $rowsP[0]['filesys_filename']);
//					array_pop($aFilenameOriginal);
//					$filenameOriginal = implode('.', $aFilenameOriginal);
//			
//					system('pdftoppm -png -r 96 -cropbox -aa yes ' . $fileOriginal . ' ' . $dirTarget . 'pictures/' . $filenameOriginal);
//					system('pdftoppm -png -r 96 -cropbox -aa yes -scale-to 140 ' . $fileOriginal . ' ' . $dirTarget . 'thumbnails/' . $filenameOriginal);
//					
//					// get document dimensions in pt (1pt = 1/72 * 25,4mm)
//					$pdf = new PDFInfo($fileOriginal);
//					$pageNum = $pdf->pages;
//					$aMediaBox = $pdf->mediaBox;
//					$aCropBox = $pdf->cropBox;
//					$aBleedBox = $pdf->bleedBox;
//					$aTrimBox = $pdf->trimBox;
//					$aArtBox = $pdf->artBox;
//					$aDimension = json_encode(array('mediabox' => $aMediaBox, 'cropbox' => $aCropBox, 'bleedbox' => $aBleedBox, 'trimbox' => $aTrimBox, 'artbox' => $aArtBox));
//		
//					array_push($aProcessedFiles, $rowsP[0]['filesys_filename']);
//				}
//			}
//		}
//	}
//
//	
//	$queryP1 = $CONFIG['dbconn'][0]->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_tpid
//										FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc 
//										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_tempid = (:id_tempid)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.page = (:page)
//										');
//	$queryP1->bindValue(':id_tempid', $aArgsSave['id_data'], PDO::PARAM_INT);
//	$queryP1->bindValue(':page', $page, PDO::PARAM_INT);
//	$queryP1->execute();
//	$rowsP1 = $queryP1->fetchAll(PDO::FETCH_ASSOC);
//	$numP1 = $queryP1->rowCount();
//	
//	if($numP1 == 0){
//		$queryI = $CONFIG['dbconn'][0]->prepare('
//											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_templatespages_
//											(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
//											VALUES
//											(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
//											');
//		$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
//		$queryI->bindValue(':id_cl', $rows[0]['id_cl'], PDO::PARAM_INT);
//		$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
//		$queryI->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
//		$queryI->execute();
//		$tpid = $CONFIG['dbconn'][0]->lastInsertId();
//	}else{
//		$tpid = $rowsP1[0]['id_tpid'];
//	}
//	
//	if(!array_key_exists('n_' . $tpid, $aArgsSaveN)){
//		$aArgsSaveN['n_' . $tpid] = array();
//		$aArgsSaveN['n_' . $tpid]['id_data'] = $tpid;
//		$aArgsSaveN['n_' . $tpid]['table'] = $CONFIG['db'][0]['prefix'] . '_templatespages_';
//		$aArgsSaveN['n_' . $tpid]['primarykey'] = 'id_tpid';
//		$aArgsSaveN['n_' . $tpid]['allVersions'] = array();
//		$aArgsSaveN['n_' . $tpid]['changedVersions'] = array();
//		
//		$aArgsSaveN['n_' . $tpid]['columns'] = array();
//		$aArgsSaveN['n_' . $tpid]['columns']['id_tpid'] = 'i';
//		$aArgsSaveN['n_' . $tpid]['columns']['id_tempid'] = 'i';
//		$aArgsSaveN['n_' . $tpid]['columns']['id_bfid'] = 'i';
//		$aArgsSaveN['n_' . $tpid]['columns']['id_cssid'] = 'i';
//		$aArgsSaveN['n_' . $tpid]['columns']['id_cbid'] = 'i';
//		$aArgsSaveN['n_' . $tpid]['columns']['id_etid'] = 'i';
//		$aArgsSaveN['n_' . $tpid]['columns']['kiado_code'] = 's';
//		$aArgsSaveN['n_' . $tpid]['columns']['file_original'] = 'i';
//		$aArgsSaveN['n_' . $tpid]['columns']['page'] = 'i';
//		$aArgsSaveN['n_' . $tpid]['columns']['page_number'] = 'i';
//		$aArgsSaveN['n_' . $tpid]['columns']['page_duration'] = 's';
//		$aArgsSaveN['n_' . $tpid]['columns']['page_dimension'] = 's';
//
//		$aArgsSaveN['n_' . $tpid]['aFieldsNumbers'] = array();
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsNumbers'], 'id_tpid');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsNumbers'], 'id_tempid');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsNumbers'], 'id_bfid');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsNumbers'], 'id_cssid');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsNumbers'], 'id_cbid');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsNumbers'], 'id_etid');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsNumbers'], 'file_original');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsNumbers'], 'page');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsNumbers'], 'page_number');
//
//		$aArgsSaveN['n_' . $tpid]['excludeUpdateUni'] = array();
//		$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['id_tpid'] = array('');
//		$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['id_tempid'] = array('');
//		$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['id_bfid'] = array('',0);
//		$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['id_cssid'] = array('',0);
//		$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['id_cbid'] = array('',0);
//		$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['id_etid'] = array('',0);
//		$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['kiado_code'] = array('');
//		$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['file_original'] = array('',0);
//		$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['page'] = array('',0);
//		$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['page_number'] = array('',0);
//		$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['page_duration'] = array('',0);
//		$aArgsSaveN['n_' . $tpid]['excludeUpdateUni']['page_dimension'] = array('');
//
//		$aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'] = array();
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'id_tpid');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'id_tempid');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'id_bfid');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'id_cssid');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'id_cbid');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'id_etid');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'kiado_code');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'file_original');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'page');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'page_number');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'page_duration');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'], 'page_dimension');
//		$aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'] = array();
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'], 'id_tpid');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'], 'id_tempid');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'], 'id_cssid');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'], 'id_cbid');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'], 'file_original');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'], 'page');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'], 'page_number');
//
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'], 'page_duration');
//		array_push($aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster'], 'page_dimension');
//
//		$aArgsN['n_' . $tpid]['fields'] = array();
//		//$aArgsN['n_' . $tpid]['fields']['bool2text']['feldname'] = array('text'=>'check');
//	}
//	
//		
//	$aArgsN['n_' . $tpid]['data'] = array();
//	$aArgsN['n_' . $tpid]['data']['id_tpid'] = $tpid;
//	$aArgsN['n_' . $tpid]['data']['id_tempid'] = $aArgsSave['id_data'];
//	$aArgsN['n_' . $tpid]['data']['id_bfid'] = 0;
//	$aArgsN['n_' . $tpid]['data']['id_cssid'] = 0;
//	$aArgsN['n_' . $tpid]['data']['id_cbid'] = $aArgsSave['aData']['id_cbid'];
//	$aArgsN['n_' . $tpid]['data']['id_etid'] = 0;
//	$aArgsN['n_' . $tpid]['data']['kiado_code'] = $aArgsSave['aData']['kiado_code'];
//	$aArgsN['n_' . $tpid]['data']['file_original'] = $aArgsSave['aData']['file_original'][0];
//	$aArgsN['n_' . $tpid]['data']['page'] = $page;
//	$aArgsN['n_' . $tpid]['data']['page_number'] = $pageNum;
//	$aArgsN['n_' . $tpid]['data']['page_duration'] = '';
//	$aArgsN['n_' . $tpid]['data']['page_dimension'] = $aDimension;
//	$aArgsN['n_' . $tpid]['data']['id_count'] = $row['id_count'];
//	$aArgsN['n_' . $tpid]['data']['id_lang'] = $row['id_lang'];
//	$aArgsN['n_' . $tpid]['data']['id_dev'] = $row['id_dev'];
//	$aArgsN['n_' . $tpid]['data']['id_cl'] = $row['id_cl'];
//
//
//	$aArgsSaveN['n_' . $tpid]['aData'] = setValuesSave($aArgsN['n_' . $tpid]);
//	$aArgsSaveN['n_' . $tpid]['aData']['id_count'] = $row['id_count'];
//	$aArgsSaveN['n_' . $tpid]['aData']['id_lang'] = $row['id_lang'];
//	$aArgsSaveN['n_' . $tpid]['aData']['id_dev'] = $row['id_dev'];
//	$aArgsSaveN['n_' . $tpid]['aData']['id_cl'] = $row['id_cl'];
//	$aArgsSaveN['n_' . $tpid]['aData']['id_data'] = $tpid;
//
//
//	$aChangeN = checkChanges($aArgsSaveN['n_' . $tpid]);
//
//	$col = '';
//	$val = '';
//	$upd = '';
//	foreach($aChangeN['aChangedFields'] as $field){
//		if(($variation == 'master' && in_array($field, $aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'])) || ($variation == 'local' && in_array($field, $aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster']))){
//			if($field != $aArgsSaveN['n_' . $tpid]['primarykey'] && $field != 'id_tempid' && $field != 'file_original' && $field != 'page' && $field != 'id_bfid'){
//				$col .= ', ' . $field;
//				$val .= ', :' . $field . '';
//				$upd .= $field.' = (:'.$field.'), ' ;
//			}
//		}
//	}
//
//
//	$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc ';
//	$qry .= '(' . $aArgsSaveN['n_' . $tpid]['primarykey'] . ', id_tempid, id_bfid, file_original, page, id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from' . $col . ') ';
//	$qry .= 'VALUES ';					
//	$qry .= '(:id, :id_tempid, :id_bfid, :file_original, :page, :id_count, :id_lang, :id_dev, :id_cl, :create_at, :create_from, :create_from' . $val . ') '; 
//	$qry .= 'ON DUPLICATE KEY UPDATE ';	
//	$qry .= $upd;
//	$qry .= 'change_from = (:create_from), ';
//	$qry .= 'del = (:nultime) ';
//	$qry = rtrim($qry, ', ');
//	$qry .= ' ';
//
//	$queryP2 = $CONFIG['dbconn'][0]->prepare($qry);
//	$queryP2->bindValue(':id', $aArgsSaveN['n_' . $tpid]['aData']['id_data'], PDO::PARAM_INT);
//	$queryP2->bindValue(':id_tempid', $aArgsSaveN['n_' . $tpid]['aData']['id_tempid'], PDO::PARAM_INT);
//	$queryP2->bindValue(':id_bfid', $aArgsSaveN['n_' . $tpid]['aData']['id_bfid'], PDO::PARAM_INT);
//	$queryP2->bindValue(':file_original', $aArgsSaveN['n_' . $tpid]['aData']['file_original'], PDO::PARAM_INT);
//	$queryP2->bindValue(':page', $aArgsSaveN['n_' . $tpid]['aData']['page'], PDO::PARAM_INT);
//	$queryP2->bindValue(':id_count', $aArgsSaveN['n_' . $tpid]['aData']['id_count'], PDO::PARAM_INT);
//	$queryP2->bindValue(':id_lang', $aArgsSaveN['n_' . $tpid]['aData']['id_lang'], PDO::PARAM_INT);
//	$queryP2->bindValue(':id_dev', $aArgsSaveN['n_' . $tpid]['aData']['id_dev'], PDO::PARAM_INT);
//	$queryP2->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
//	$queryP2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$queryP2->bindValue(':create_at', $now, PDO::PARAM_STR); 
//	$queryP2->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
//
//	foreach($aChangeN['aChangedFields'] as $field){
//		if(($variation == 'master' && in_array($field, $aArgsSaveN['n_' . $tpid]['aFieldsSaveMaster'])) || ($variation == 'local' && in_array($field, $aArgsSaveN['n_' . $tpid]['aFieldsSaveNotMaster']))){
//			if($field != $aArgsSaveN['n_' . $tpid]['primarykey']){
//				if($aArgsSaveN['n_' . $tpid]['columns'][$field] == 'i' || $aArgsSaveN['n_' . $tpid]['columns'][$field] == 'si' || $aArgsSaveN['n_' . $tpid]['columns'][$field] == 'b'){
//					$queryP2->bindValue(':'.$field, (is_array($aArgsSaveN['n_' . $tpid]['aData'][$field])) ? json_encode($aArgsSaveN['n_' . $tpid]['aData'][$field]) : trim($aArgsSaveN['n_' . $tpid]['aData'][$field]), PDO::PARAM_INT);
//				}else{ 
//					$queryP2->bindValue(':'.$field, (is_array($aArgsSaveN['n_' . $tpid]['aData'][$field])) ? json_encode($aArgsSaveN['n_' . $tpid]['aData'][$field]) : trim($aArgsSaveN['n_' . $tpid]['aData'][$field]), PDO::PARAM_STR);
//				}
//			}
//		}
//	}
//	$queryP2->execute();
//	$numP2 = $queryP2->rowCount();
//
//
//	if($numP2 > 0) array_push($aArgsSaveN['n_' . $tpid]['changedVersions'], array($aArgsSaveN['n_' . $tpid]['aData']['id_count'], $aArgsSaveN['n_' . $tpid]['aData']['id_lang'], $aArgsSaveN['n_' . $tpid]['aData']['id_dev']));
//
//
//	#########################################
//	// save components
//	include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-translations-update-components.php');
//}
//
//
//$aArgsLV = array();
//$aArgsLV['type'] = 'sysall';
//$aLocalVersions = localVariationsBuild($aArgsLV);
//
//$aArgsSave['changedVersions'] = array(array(0,0,0));
//$aArgsSave['allVersions'] = $aLocalVersions;
//insertAll($aArgsSave);
//
//foreach($aArgsSaveN as $kSave => $aSave){
//	$aSave['changedVersions'] = $aArgsSave['changedVersions'];
//	$aSave['allVersions'] = $aArgsSave['allVersions'];
//	insertAll($aSave);
//}
//
//$aArgsLV = array();
//$aArgsLV['type'] = 'sysall';
//$aLocalVersions = localVariationsBuild($aArgsLV);
//
//foreach($aArgsSaveTPE as $kSave => $aSave){
//	$aSave['changedVersions'] = $aArgsSave['changedVersions'];
//	$aSave['allVersions'] = $aLocalVersions;
//	insertAll($aSave);
//}
//
//
//#########################################
//
//$query2 = $CONFIG['dbconn'][0]->prepare('
//									DELETE 
//									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload 
//									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id = (:id)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_uid = (:uid)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_mod = (:id_mod)
//									');
//$query2->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
//$query2->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
//$query2->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
//$query2->execute();


?>