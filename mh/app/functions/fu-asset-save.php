<?php 
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-app.php');
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$out = '';


########################################################
########################################################
// save tmp
########################################################
$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp
			(id_count, id_lang, id_dev, id_cl, restricted_all, id_asid, id_promid, id_campid, id_tempid, id_pcid, id_ppid, title, components, create_at, create_from, change_from)
		VALUES
			(:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_asid, :id_promid, :id_campid, :id_tempid, :id_pcid, :id_ppid, :title, :components, :now, :create_from, :create_from)
		ON DUPLICATE KEY UPDATE 
			title = (:title),
			components = (:components),
			change_from = (:create_from),
			del = (:nultime)
		';
$queryC = $CONFIG['dbconn'][0]->prepare($qry); 
$queryC->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$queryC->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryC->bindValue(':id_cl', 1, PDO::PARAM_INT);
$queryC->bindValue(':restricted_all', 0, PDO::PARAM_INT);
$queryC->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
$queryC->bindValue(':id_promid', $varSQL['id_promid'], PDO::PARAM_INT);
$queryC->bindValue(':id_campid', $varSQL['id_campid'], PDO::PARAM_INT);
$queryC->bindValue(':id_tempid', $varSQL['id_tempid'], PDO::PARAM_INT);
$queryC->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
$queryC->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryC->bindValue(':title', $varSQL['assettitle'], PDO::PARAM_STR);
$queryC->bindValue(':components', $varSQL['components'], PDO::PARAM_STR);
$queryC->bindValue(':now', $now, PDO::PARAM_STR);
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':create_from', $CONFIG['user']['id_ppid'], PDO::PARAM_INT); 
$queryC->execute();
$numC = $queryC->rowCount();


$qry = 'UPDATE ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp SET
			title = (:title)
		WHERE ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_asid = (:id_asid)
			AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_pcid = (:id_pcid)
			AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_ppid = (:id_ppid)
		';
$queryC = $CONFIG['dbconn'][0]->prepare($qry);
$queryC->bindValue(':title', $varSQL['assettitle'], PDO::PARAM_STR);
$queryC->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
$queryC->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
$queryC->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryC->execute();
$numC = $queryC->rowCount();



$aDataComponents = json_decode($varSQL['components'], true);

foreach($aDataComponents['pages'] as $kPage => $aPageComponents){
	foreach($aPageComponents as $kComponent => $aComponent){
		if(!isset($aComponent['id_apid'])){
			// create new ID
			$query = $CONFIG['dbconn'][0]->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_apeid
												FROM ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp 
												WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_asid = (:id_asid)
													AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_tempid = (:id_tempid)
													AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_tpeid = (:id_tpeid)
												');
			$query->bindValue(':id_count',$CONFIG['user']['id_countid'], PDO::PARAM_INT);
			$query->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
			$query->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
			$query->bindValue(':id_tempid', $varSQL['id_tempid'], PDO::PARAM_INT);
			$query->bindValue(':id_tpeid', $aComponent['id_tpeid'], PDO::PARAM_INT);
			$query->execute();
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);
			$num = $query->rowCount();
		
			if($num == 0){
				$queryI = $CONFIG['dbconn'][0]->prepare('
													INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_
													(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
													VALUES
													(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
													');
				$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
				$queryI->bindValue(':id_cl', 1, PDO::PARAM_INT);
				$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
				$queryI->bindValue(':create_from', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
				$queryI->execute();
				$varSQL['id_apeid'] = $CONFIG['dbconn'][0]->lastInsertId();
			}else{
				$varSQL['id_apeid'] = $rows[0]['id_apeid'];
			}
			$aArgsSave['id_data'] = $varSQL['id_apeid'];
			
			
			$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp
						(id_count, id_lang, id_dev, id_cl, restricted_all, id_apeid, id_asid, id_tempid, id_tpeid, id_pid, id_pcid, id_ppid, content, create_at, create_from, change_from)
					VALUES
						(:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_apeid, :id_asid, :id_tempid, :id_tpeid, :id_pid, :id_pcid, :id_ppid, :content, :now, :create_from, :create_from)
					ON DUPLICATE KEY UPDATE 
						content = (:content),
						change_from = (:create_from),
						del = (:nultime)
					';
			$queryC = $CONFIG['dbconn'][0]->prepare($qry);
			$queryC->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
			$queryC->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
			$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryC->bindValue(':id_cl', 1, PDO::PARAM_INT); 
			$queryC->bindValue(':restricted_all', 0, PDO::PARAM_INT);
			$queryC->bindValue(':id_apeid', $varSQL['id_apeid'], PDO::PARAM_INT);
			$queryC->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
			$queryC->bindValue(':id_tempid', $varSQL['id_tempid'], PDO::PARAM_INT);
			$queryC->bindValue(':id_tpeid', $aComponent['id_tpeid'], PDO::PARAM_INT);
			$queryC->bindValue(':id_pid', (isset($aComponent['id_pid'])) ? $aComponent['id_pid'] : 0, PDO::PARAM_INT);
			$queryC->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
			$queryC->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
			$queryC->bindValue(':content', $aComponent['content'], PDO::PARAM_STR);
			$queryC->bindValue(':now', $now, PDO::PARAM_STR);
			$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$queryC->bindValue(':create_from', $CONFIG['user']['id_ppid'], PDO::PARAM_INT); 
			$queryC->execute();
			$numC = $queryC->rowCount();
		}else{
			########################################################
			// save products
			########################################################
			$col = '';
			switch($aComponent['id_tcid']){
				// Product name
				case '3':
					$col = 'mkt_name';
					break;
		
				// Product category
				case '9':
					$col = 'prod_type';
					break;
				
				// Product image
				case '12':
					$col = 'image';
					break;
				
				// PN
				case '4':
					$col = 'pn_text';
					break;
		
				// Tagline
				case '13':
					$col = 'tagline';
					break;
		
				// Short description
				case '5':
					$col = 'mkt_paragraph';
					break;
		
				// 25 word description
				case '6':
					$col = 'description_text_25';
					break;
		
				// 50 word description
				case '7':
					$col = 'description_text_50';
					break;
		
				// 100 word description
				case '8':
					$col = 'description_text_100';
					break;
		
				// Pricefield
				case '2':
					$col = 'price';
					break;
				
				// WYSIWYG
				case '14':
					$qry = 'SELECT ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.not_lpmd
							FROM ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp
							WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_apid = (:id_apid)
							';
					$queryL = $CONFIG['dbconn'][0]->prepare($qry);
					$queryL->bindValue(':id_apid', $aComponent['id_apid'], PDO::PARAM_INT);
					$queryL->execute();
					$rowsL = $queryL->fetchAll(PDO::FETCH_ASSOC);
					$numL = $queryL->rowCount();
					$aCon = json_decode($rowsL[0]['not_lpmd'], true);
					$aCon[$aComponent['id_tpeid']] = $aComponent['content'];

					$aComponent['content'] = json_encode($aCon);
					$col = 'not_lpmd';
					break;
				
				// Textfield
				case '1':
					$qry = 'SELECT ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.not_lpmd
							FROM ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp
							WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_apid = (:id_apid)
							';
					$queryL = $CONFIG['dbconn'][0]->prepare($qry);
					$queryL->bindValue(':id_apid', $aComponent['id_apid'], PDO::PARAM_INT);
					$queryL->execute();
					$rowsL = $queryL->fetchAll(PDO::FETCH_ASSOC);
					$numL = $queryL->rowCount();
					$aCon = json_decode($rowsL[0]['not_lpmd'], true);
					$aCon[$aComponent['id_tpeid']] = $aComponent['content'];

					$aComponent['content'] = json_encode($aCon);
					$col = 'not_lpmd';
					break;
				
				// Textmodul
				case '17':
					$col = '';
					break;
				
				// Fileupload
				case '15':
					$col = '';
					break;
	
				// Partner logo
				case '11':
					$col = '';
					break;
	
				// Partner contact
				case '10':
					$col = '';
					break;
				
				// Partner contact / logo combination
				case '16':
					$col = '';
					break;
			
				// Color area
				case '18':
					$col = '';
					break;
			
				// Calltoaction
				case '19':
					$qry = 'UPDATE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp SET
								content_add = (:content),
								change_from = (:create_from)
							WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_apid = (:id_apid)
							';
					$queryC = $CONFIG['dbconn'][0]->prepare($qry);
					$queryC->bindValue(':id_apid', $aComponent['id_apid'], PDO::PARAM_INT);
					$queryC->bindValue(':content', json_encode($aComponent['content_add']), PDO::PARAM_STR);
					$queryC->bindValue(':create_from', $CONFIG['user']['id_ppid'], PDO::PARAM_INT); 
					$queryC->execute();
					//$numC = $queryC->rowCount();

					$qry = 'SELECT ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.not_lpmd
							FROM ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp
							WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_apid = (:id_apid)
							';
					$queryL = $CONFIG['dbconn'][0]->prepare($qry);
					$queryL->bindValue(':id_apid', $aComponent['id_apid'], PDO::PARAM_INT);
					$queryL->execute();
					$rowsL = $queryL->fetchAll(PDO::FETCH_ASSOC);
					$numL = $queryL->rowCount();
					$aCon = json_decode($rowsL[0]['not_lpmd'], true);
					$aCon[$aComponent['id_tpeid']] = $aComponent['content'];

					$aComponent['content'] = json_encode($aCon);
					$col = 'not_lpmd';
					break;
			}
			
			$qry = 'UPDATE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp SET
						' . $col . ' = (:content),
						change_from = (:create_from)
					WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_apid = (:id_apid)
					';
			$queryC = $CONFIG['dbconn'][0]->prepare($qry);
			$queryC->bindValue(':id_apid', $aComponent['id_apid'], PDO::PARAM_INT);
			$queryC->bindValue(':content', $aComponent['content'], PDO::PARAM_STR);
			$queryC->bindValue(':create_from', $CONFIG['user']['id_ppid'], PDO::PARAM_INT); 
			if($col != '') $queryC->execute();
			//$numC = $queryC->rowCount();
		}
	}
}
########################################################
########################################################










########################################################
// save asset
########################################################
$aArgsSave = array();
$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . '_assets_';
$aArgsSave['primarykey'] = 'id_asid';
$aArgsSave['allVersions'] = array();
$aArgsSave['changedVersions'] = array();

$aArgsSave['columns'] = array();
$aArgsSave['columns']['id_asid'] = 'i';
$aArgsSave['columns']['id_promid'] = 'i';
$aArgsSave['columns']['id_campid'] = 'i';
$aArgsSave['columns']['id_tempid'] = 'i';
$aArgsSave['columns']['id_pcid'] = 'i';
$aArgsSave['columns']['id_ppid'] = 'i';
$aArgsSave['columns']['title'] = 's';
$aArgsSave['columns']['components'] = 's';
$aArgsSave['columns']['del'] = 's';

$aArgsSave['aFieldsNumbers'] = array();
array_push($aArgsSave['aFieldsNumbers'], 'id_asid');
array_push($aArgsSave['aFieldsNumbers'], 'id_promid');
array_push($aArgsSave['aFieldsNumbers'], 'id_campid');
array_push($aArgsSave['aFieldsNumbers'], 'id_tempid');
array_push($aArgsSave['aFieldsNumbers'], 'id_pcid');
array_push($aArgsSave['aFieldsNumbers'], 'id_ppid');

$aArgsSave['excludeUpdateUni'] = array();
$aArgsSave['excludeUpdateUni']['id_asid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_promid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_campid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_tempid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_pcid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_ppid'] = array('',0);
$aArgsSave['excludeUpdateUni']['title'] = array('');
$aArgsSave['excludeUpdateUni']['components'] = array('');
$aArgsSave['excludeUpdateUni']['del'] = array('');

$aFieldsSaveMaster = array();
array_push($aFieldsSaveMaster, 'id_asid');
array_push($aFieldsSaveMaster, 'id_promid');
array_push($aFieldsSaveMaster, 'id_campid');
array_push($aFieldsSaveMaster, 'id_tempid');
array_push($aFieldsSaveMaster, 'id_pcid');
array_push($aFieldsSaveMaster, 'id_ppid');
array_push($aFieldsSaveMaster, 'title');
array_push($aFieldsSaveMaster, 'components');
array_push($aFieldsSaveMaster, 'del');
$aFieldsSaveNotMaster = array();
array_push($aFieldsSaveNotMaster, 'id_asid');
array_push($aFieldsSaveNotMaster, 'id_promid');
array_push($aFieldsSaveNotMaster, 'id_campid');
array_push($aFieldsSaveNotMaster, 'id_tempid');
array_push($aFieldsSaveNotMaster, 'id_pcid');
array_push($aFieldsSaveNotMaster, 'id_ppid');
array_push($aFieldsSaveNotMaster, 'title');
array_push($aFieldsSaveNotMaster, 'components');
array_push($aFieldsSaveNotMaster, 'del');

$aArgsSave['aData']['id_count'] = 0;
$aArgsSave['aData']['id_lang'] = 0;
$aArgsSave['aData']['id_dev'] = 0;
$aArgsSave['aData']['id_cl'] = 1;

$aArgsSave['id_data'] = $varSQL['id_asid'];


$queryS = $CONFIG['dbconn'][0]->prepare('SELECT
											' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_asid, 
											' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_promid, 
											' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_campid, 
											' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_tempid, 
											' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.title, 
											' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.components, 
											' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.del
										FROM ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_asid = (:id_asid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_pcid = (:id_pcid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_ppid = (:id_ppid)
									');
$queryS->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$queryS->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$queryS->bindValue(':nul', 0, PDO::PARAM_INT);
$queryS->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
$queryS->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
$queryS->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryS->execute();
$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
$numS = $queryS->rowCount();

foreach($rowsS as $rowS){
	$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assets_loc
				(id_count, id_lang, id_dev, id_cl, restricted_all, id_asid, id_promid, id_campid, id_tempid, id_pcid, id_ppid, title, components, create_at, create_from, change_from)
			VALUES
				(:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_asid, :id_promid, :id_campid, :id_tempid, :id_pcid, :id_ppid, :title, :components, :now, :create_from, :create_from)
			ON DUPLICATE KEY UPDATE 
				title = (:title),
				components = (:components),
				change_from = (:create_from),
				del = (:del)
			';
	$queryC = $CONFIG['dbconn'][0]->prepare($qry);
	$queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_cl', 1, PDO::PARAM_INT);
	$queryC->bindValue(':restricted_all', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_asid', $rowS['id_asid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_promid', $rowS['id_promid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_campid', $rowS['id_campid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_tempid', $rowS['id_tempid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$queryC->bindValue(':title', $rowS['title'], PDO::PARAM_STR);
	$queryC->bindValue(':components', $rowS['components'], PDO::PARAM_STR);
	$queryC->bindValue(':now', $now, PDO::PARAM_STR);
	$queryC->bindValue(':del', $rowS['del'], PDO::PARAM_STR);
	$queryC->bindValue(':create_from', $CONFIG['user']['id_ppid'], PDO::PARAM_INT); 
	$queryC->execute();
	$numC = $queryC->rowCount();
	
	
	$aArgsSave['addCondition'] = 'AND ' . $CONFIG['db'][0]['prefix'] . '_assets_##TYPE##.id_tempid = ' . $rowS['id_tempid'];
	$aArgsSave['changedVersions'] = array(array(0,0,0));
	$aArgsSave['allVersions'] = array(array(0,0,0), array($CONFIG['user']['id_countid'],$CONFIG['user']['id_langid'],0));
	insertAll($aArgsSave);
}










########################################################
// save pages
########################################################
$aArgsSave = array();
$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . '_assetspages_';
$aArgsSave['primarykey'] = 'id_apageid';
$aArgsSave['allVersions'] = array();
$aArgsSave['changedVersions'] = array();

$aArgsSave['columns'] = array();
$aArgsSave['columns']['id_apageid'] = 'i';
$aArgsSave['columns']['id_asid'] = 'i';
$aArgsSave['columns']['id_tpid'] = 'i';
$aArgsSave['columns']['id_bfid'] = 'i';
$aArgsSave['columns']['id_etid'] = 'i';
$aArgsSave['columns']['id_pcid'] = 'i';
$aArgsSave['columns']['id_ppid'] = 'i';
$aArgsSave['columns']['page'] = 'i';
$aArgsSave['columns']['duration'] = 'i';
$aArgsSave['columns']['showframe'] = 'i';

$aArgsSave['aFieldsNumbers'] = array();
array_push($aArgsSave['aFieldsNumbers'], 'id_apageid');
array_push($aArgsSave['aFieldsNumbers'], 'id_asid');
array_push($aArgsSave['aFieldsNumbers'], 'id_tpid');
array_push($aArgsSave['aFieldsNumbers'], 'id_bfid');
array_push($aArgsSave['aFieldsNumbers'], 'id_etid');
array_push($aArgsSave['aFieldsNumbers'], 'id_pcid');
array_push($aArgsSave['aFieldsNumbers'], 'id_ppid');
array_push($aArgsSave['aFieldsNumbers'], 'page');
array_push($aArgsSave['aFieldsNumbers'], 'duration');
array_push($aArgsSave['aFieldsNumbers'], 'showframe');

$aArgsSave['excludeUpdateUni'] = array();
$aArgsSave['excludeUpdateUni']['id_apageid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_asid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_tpid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_bfid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_etid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_pcid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_ppid'] = array('',0);
$aArgsSave['excludeUpdateUni']['page'] = array('',0);
$aArgsSave['excludeUpdateUni']['duration'] = array('',0);
$aArgsSave['excludeUpdateUni']['showframe'] = array('',0);

$aFieldsSaveMaster = array();
array_push($aFieldsSaveMaster, 'id_apageid');
array_push($aFieldsSaveMaster, 'id_asid');
array_push($aFieldsSaveMaster, 'id_tpid');
array_push($aFieldsSaveMaster, 'id_bfid');
array_push($aFieldsSaveMaster, 'id_etid');
array_push($aFieldsSaveMaster, 'id_pcid');
array_push($aFieldsSaveMaster, 'id_ppid');
array_push($aFieldsSaveMaster, 'page');
array_push($aFieldsSaveMaster, 'duration');
array_push($aFieldsSaveMaster, 'showframe');
$aFieldsSaveNotMaster = array();
array_push($aFieldsSaveNotMaster, 'id_apageid');
array_push($aFieldsSaveNotMaster, 'id_asid');
array_push($aFieldsSaveNotMaster, 'id_tpid');
array_push($aFieldsSaveNotMaster, 'id_bfid');
array_push($aFieldsSaveNotMaster, 'id_etid');
array_push($aFieldsSaveNotMaster, 'id_pcid');
array_push($aFieldsSaveNotMaster, 'id_ppid');
array_push($aFieldsSaveNotMaster, 'page');
array_push($aFieldsSaveNotMaster, 'duration');
array_push($aFieldsSaveNotMaster, 'showframe');

$aArgsSave['aData']['id_count'] = 0;
$aArgsSave['aData']['id_lang'] = 0;
$aArgsSave['aData']['id_dev'] = 0;
$aArgsSave['aData']['id_cl'] = 1;


$queryS = $CONFIG['dbconn'][0]->prepare('SELECT
											' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_asid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_apageid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_tpid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_bfid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_etid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.page, 
											' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.duration, 
											' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.showframe
										FROM ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_asid = (:id_asid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_pcid = (:id_pcid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_ppid = (:id_ppid)
									');
$queryS->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$queryS->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$queryS->bindValue(':nul', 0, PDO::PARAM_INT);
$queryS->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
$queryS->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
$queryS->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryS->execute();
$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
$numS = $queryS->rowCount();

foreach($rowsS as $rowS){
	$aArgsSave['id_data'] = $rowS['id_apageid'];

	$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assetspages_loc
				(id_count, id_lang, id_dev, id_cl, restricted_all, id_apageid, id_asid, id_tpid, id_bfid, id_etid, id_pcid, id_ppid, page, duration, showframe, create_at, create_from, change_from)
			VALUES
				(:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_apageid, :id_asid, :id_tpid, :id_bfid, :id_etid, :id_pcid, :id_ppid, :page, :duration, showframe, :now, :create_from, :create_from)
			ON DUPLICATE KEY UPDATE 
				duration = (:duration),
				showframe = (:showframe),
				change_from = (:create_from)
			';
	$queryC = $CONFIG['dbconn'][0]->prepare($qry);
	$queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_cl', 1, PDO::PARAM_INT);
	$queryC->bindValue(':restricted_all', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_apageid', $rowS['id_apageid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_asid', $rowS['id_asid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_tpid', $rowS['id_tpid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_bfid', $rowS['id_bfid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_etid', $rowS['id_etid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$queryC->bindValue(':page', $rowS['page'], PDO::PARAM_INT);
	$queryC->bindValue(':duration', $rowS['duration'], PDO::PARAM_INT);
	$queryC->bindValue(':showframe', $rowS['showframe'], PDO::PARAM_INT);
	$queryC->bindValue(':now', $now, PDO::PARAM_STR);
	$queryC->bindValue(':create_from', $CONFIG['user']['id_ppid'], PDO::PARAM_INT); 
	$queryC->execute();
	$numC = $queryC->rowCount();
	
	
	$aArgsSave['changedVersions'] = array(array(0,0,0));
	$aArgsSave['allVersions'] = array(array(0,0,0), array($CONFIG['user']['id_countid'],$CONFIG['user']['id_langid'],0));
	insertAll($aArgsSave);
}




########################################################
// save content
########################################################
$aArgsSave = array();
$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . '_assetspageselements_';
$aArgsSave['primarykey'] = 'id_apeid';
$aArgsSave['allVersions'] = array();
$aArgsSave['changedVersions'] = array();

$aArgsSave['columns'] = array();
$aArgsSave['columns']['id_apeid'] = 'i';
$aArgsSave['columns']['id_asid'] = 'i';
$aArgsSave['columns']['id_tempid'] = 'i';
$aArgsSave['columns']['id_tpeid'] = 'i';
$aArgsSave['columns']['id_pid'] = 'i';
$aArgsSave['columns']['id_pcid'] = 'i';
$aArgsSave['columns']['id_ppid'] = 'i';
$aArgsSave['columns']['content'] = 's';
$aArgsSave['columns']['content_add'] = 's';
$aArgsSave['columns']['del'] = 's';

$aArgsSave['aFieldsNumbers'] = array();
array_push($aArgsSave['aFieldsNumbers'], 'id_apeid');
array_push($aArgsSave['aFieldsNumbers'], 'id_asid');
array_push($aArgsSave['aFieldsNumbers'], 'id_tempid');
array_push($aArgsSave['aFieldsNumbers'], 'id_tpeid');
array_push($aArgsSave['aFieldsNumbers'], 'id_pid');
array_push($aArgsSave['aFieldsNumbers'], 'id_pcid');
array_push($aArgsSave['aFieldsNumbers'], 'id_ppid');

$aArgsSave['excludeUpdateUni'] = array();
$aArgsSave['excludeUpdateUni']['id_apeid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_asid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_tempid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_tpeid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_pid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_pcid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_ppid'] = array('',0);
$aArgsSave['excludeUpdateUni']['content'] = array('');
$aArgsSave['excludeUpdateUni']['content_add'] = array('');
$aArgsSave['excludeUpdateUni']['del'] = array('');

$aFieldsSaveMaster = array();
array_push($aFieldsSaveMaster, 'id_apeid');
array_push($aFieldsSaveMaster, 'id_asid');
array_push($aFieldsSaveMaster, 'id_tempid');
array_push($aFieldsSaveMaster, 'id_tpeid');
array_push($aFieldsSaveMaster, 'id_pid');
array_push($aFieldsSaveMaster, 'id_pcid');
array_push($aFieldsSaveMaster, 'id_ppid');
array_push($aFieldsSaveMaster, 'content');
array_push($aFieldsSaveMaster, 'content_add');
array_push($aFieldsSaveMaster, 'del');
$aFieldsSaveNotMaster = array();
array_push($aFieldsSaveNotMaster, 'id_apeid');
array_push($aFieldsSaveNotMaster, 'id_asid');
array_push($aFieldsSaveNotMaster, 'id_tempid');
array_push($aFieldsSaveNotMaster, 'id_tpeid');
array_push($aFieldsSaveNotMaster, 'id_pid');
array_push($aFieldsSaveNotMaster, 'id_pcid');
array_push($aFieldsSaveNotMaster, 'id_ppid');
array_push($aFieldsSaveNotMaster, 'content');
array_push($aFieldsSaveNotMaster, 'content_add');
array_push($aFieldsSaveNotMaster, 'del');

$aArgsSave['aData']['id_count'] = 0;
$aArgsSave['aData']['id_lang'] = 0;
$aArgsSave['aData']['id_dev'] = 0;
$aArgsSave['aData']['id_cl'] = 1;


$queryS = $CONFIG['dbconn'][0]->prepare('SELECT
											' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_apeid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_asid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_tempid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_tpeid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_pid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.content, 
											' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.content_add, 
											' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.del
										FROM ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_asid = (:id_asid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_pcid = (:id_pcid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_ppid = (:id_ppid)
									');
$queryS->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$queryS->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$queryS->bindValue(':nul', 0, PDO::PARAM_INT);
$queryS->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
$queryS->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
$queryS->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryS->execute();
$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
$numS = $queryS->rowCount();

foreach($rowsS as $rowS){
//	// create new ID
//	$query = $CONFIG['dbconn'][0]->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_loc.id_apeid
//										FROM ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_loc 
//										WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_loc.id_count = (:id_count)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_loc.id_lang = (:id_lang)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_loc.id_asid = (:id_asid)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_loc.id_tempid = (:id_tempid)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_loc.id_tpeid = (:id_tpeid)
//										');
//	$query->bindValue(':id_count',0, PDO::PARAM_INT);
//	$query->bindValue(':id_lang', 0, PDO::PARAM_INT);
//	$query->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
//	$query->bindValue(':id_tempid', $varSQL['id_tempid'], PDO::PARAM_INT);
//	$query->bindValue(':id_tpeid', $rowS['id_tpeid'], PDO::PARAM_INT);
//	$query->execute();
//	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//	$num = $query->rowCount();
//
//	if($num == 0){
//		$queryI = $CONFIG['dbconn'][0]->prepare('
//											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_
//											(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
//											VALUES
//											(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
//											');
//		$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
//		$queryI->bindValue(':id_cl', 1, PDO::PARAM_INT);
//		$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
//		$queryI->bindValue(':create_from', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
//		$queryI->execute();
//		$varSQL['id_apeid'] = $CONFIG['dbconn'][0]->lastInsertId();
//	}else{
		$varSQL['id_apeid'] = $rowS['id_apeid'];
//	}
	$aArgsSave['id_data'] = $varSQL['id_apeid'];
	
	
	$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_loc
				(id_count, id_lang, id_dev, id_cl, restricted_all, id_apeid, id_asid, id_tempid, id_tpeid, id_pid, id_pcid, id_ppid, content, content_add, create_at, create_from, change_from)
			VALUES
				(:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_apeid, :id_asid, :id_tempid, :id_tpeid, :id_pid, :id_pcid, :id_ppid, :content, :content_add, :now, :create_from, :create_from)
			ON DUPLICATE KEY UPDATE 
				id_pid = (:id_pid),
				content = (:content),
				content_add = (:content_add),
				change_from = (:create_from),
				del = (:del)
			';
	$queryC = $CONFIG['dbconn'][0]->prepare($qry);
	$queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_cl', 1, PDO::PARAM_INT);
	$queryC->bindValue(':restricted_all', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_apeid', $rowS['id_apeid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_asid', $rowS['id_asid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_tempid', $rowS['id_tempid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_tpeid', $rowS['id_tpeid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_pid', $rowS['id_pid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$queryC->bindValue(':content', $rowS['content'], PDO::PARAM_STR);
	$queryC->bindValue(':content_add', $rowS['content_add'], PDO::PARAM_STR);
	$queryC->bindValue(':now', $now, PDO::PARAM_STR);
	$queryC->bindValue(':del', $rowS['del'], PDO::PARAM_STR);
	$queryC->bindValue(':create_from', $CONFIG['user']['id_ppid'], PDO::PARAM_INT); 
	$queryC->execute();
	$numC = $queryC->rowCount();
	
	
	$aArgsSave['changedVersions'] = array(array(0,0,0));
	$aArgsSave['allVersions'] = array(array(0,0,0), array($CONFIG['user']['id_countid'],$CONFIG['user']['id_langid'],0));
	insertAll($aArgsSave);
}


















########################################################
// save products
########################################################
$aArgsSave = array();
$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . '_assetsproducts_';
$aArgsSave['primarykey'] = 'id_apid';
$aArgsSave['allVersions'] = array();
$aArgsSave['changedVersions'] = array();

$aArgsSave['columns'] = array();
$aArgsSave['columns']['id_apid'] = 'i';
$aArgsSave['columns']['id_asid'] = 'i';
$aArgsSave['columns']['id_tempid'] = 'i';
$aArgsSave['columns']['id_bfid'] = 'i';
$aArgsSave['columns']['id_etid'] = 'i';
$aArgsSave['columns']['id_tpid'] = 'i';
$aArgsSave['columns']['id_pcid'] = 'i';
$aArgsSave['columns']['id_ppid'] = 'i';
$aArgsSave['columns']['rank'] = 'i';
$aArgsSave['columns']['id_pid'] = 'i';
$aArgsSave['columns']['revenue_pid'] = 'i';
$aArgsSave['columns']['prod_type'] = 's';
$aArgsSave['columns']['id_ptid'] = 'i';
$aArgsSave['columns']['pn_text'] = 's';
$aArgsSave['columns']['mkt_name'] = 's';
$aArgsSave['columns']['mkt_paragraph'] = 's';
$aArgsSave['columns']['tagline'] = 's';
$aArgsSave['columns']['price'] = 's';
$aArgsSave['columns']['description_text_25'] = 's';
$aArgsSave['columns']['description_text_50'] = 's';
$aArgsSave['columns']['description_text_100'] = 's';
$aArgsSave['columns']['image'] = 's';
$aArgsSave['columns']['id_piid'] = 'i';
$aArgsSave['columns']['not_lpmd'] = 's';
$aArgsSave['columns']['content_add'] = 's';
$aArgsSave['columns']['duration'] = 'i';
$aArgsSave['columns']['showframe'] = 'i';
$aArgsSave['columns']['del'] = 's';

$aArgsSave['aFieldsNumbers'] = array();
array_push($aArgsSave['aFieldsNumbers'], 'id_apid');
array_push($aArgsSave['aFieldsNumbers'], 'id_asid');
array_push($aArgsSave['aFieldsNumbers'], 'id_tempid');
array_push($aArgsSave['aFieldsNumbers'], 'id_bfid');
array_push($aArgsSave['aFieldsNumbers'], 'id_etid');
array_push($aArgsSave['aFieldsNumbers'], 'id_tpid');
array_push($aArgsSave['aFieldsNumbers'], 'id_pcid');
array_push($aArgsSave['aFieldsNumbers'], 'id_ppid');
array_push($aArgsSave['aFieldsNumbers'], 'rank');
array_push($aArgsSave['aFieldsNumbers'], 'id_pid');
array_push($aArgsSave['aFieldsNumbers'], 'revenue_pid');
array_push($aArgsSave['aFieldsNumbers'], 'id_ptid');
array_push($aArgsSave['aFieldsNumbers'], 'id_piid');
array_push($aArgsSave['aFieldsNumbers'], 'duration');
array_push($aArgsSave['aFieldsNumbers'], 'showframe');

$aArgsSave['excludeUpdateUni'] = array();
$aArgsSave['excludeUpdateUni']['id_apid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_asid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_tempid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_bfid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_etid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_tpid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_pcid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_ppid'] = array('',0);
$aArgsSave['excludeUpdateUni']['rank'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_pid'] = array('',0);
$aArgsSave['excludeUpdateUni']['revenue_pid'] = array('',0);
$aArgsSave['excludeUpdateUni']['prod_type'] = array('');
$aArgsSave['excludeUpdateUni']['id_ptid'] = array('',0);
$aArgsSave['excludeUpdateUni']['pn_text'] = array('');
$aArgsSave['excludeUpdateUni']['mkt_name'] = array('');
$aArgsSave['excludeUpdateUni']['mkt_paragraph'] = array('');
$aArgsSave['excludeUpdateUni']['tagline'] = array('');
$aArgsSave['excludeUpdateUni']['price'] = array('');
$aArgsSave['excludeUpdateUni']['description_text_25'] = array('');
$aArgsSave['excludeUpdateUni']['description_text_50'] = array('');
$aArgsSave['excludeUpdateUni']['description_text_100'] = array('');
$aArgsSave['excludeUpdateUni']['image'] = array('');
$aArgsSave['excludeUpdateUni']['id_piid'] = array('',0);
$aArgsSave['excludeUpdateUni']['not_lpmd'] = array('');
$aArgsSave['excludeUpdateUni']['content_add'] = array('');
$aArgsSave['excludeUpdateUni']['duration'] = array('',0);
$aArgsSave['excludeUpdateUni']['showframe'] = array('',0);
$aArgsSave['excludeUpdateUni']['del'] = array('');

$aFieldsSaveMaster = array();
array_push($aFieldsSaveMaster, 'id_apid');
array_push($aFieldsSaveMaster, 'id_asid');
array_push($aFieldsSaveMaster, 'id_tempid');
array_push($aFieldsSaveMaster, 'id_bfid');
array_push($aFieldsSaveMaster, 'id_etid');
array_push($aFieldsSaveMaster, 'id_tpid');
array_push($aFieldsSaveMaster, 'id_pcid');
array_push($aFieldsSaveMaster, 'id_ppid');
array_push($aFieldsSaveMaster, 'rank');
array_push($aFieldsSaveMaster, 'id_pid');
array_push($aFieldsSaveMaster, 'revenue_pid');
array_push($aFieldsSaveMaster, 'prod_type');
array_push($aFieldsSaveMaster, 'id_ptid');
array_push($aFieldsSaveMaster, 'pn_text');
array_push($aFieldsSaveMaster, 'mkt_name');
array_push($aFieldsSaveMaster, 'mkt_paragraph');
array_push($aFieldsSaveMaster, 'tagline');
array_push($aFieldsSaveMaster, 'price');
array_push($aFieldsSaveMaster, 'description_text_25');
array_push($aFieldsSaveMaster, 'description_text_50');
array_push($aFieldsSaveMaster, 'description_text_100');
array_push($aFieldsSaveMaster, 'image');
array_push($aFieldsSaveMaster, 'id_piid');
array_push($aFieldsSaveMaster, 'not_lpmd');
array_push($aFieldsSaveMaster, 'content_add');
array_push($aFieldsSaveMaster, 'duration');
array_push($aFieldsSaveMaster, 'showframe');
array_push($aFieldsSaveMaster, 'del');
$aFieldsSaveNotMaster = array();
array_push($aFieldsSaveNotMaster, 'id_apid');
array_push($aFieldsSaveNotMaster, 'id_asid');
array_push($aFieldsSaveNotMaster, 'id_tempid');
array_push($aFieldsSaveNotMaster, 'id_bfid');
array_push($aFieldsSaveNotMaster, 'id_etid');
array_push($aFieldsSaveNotMaster, 'id_tpid');
array_push($aFieldsSaveNotMaster, 'id_pcid');
array_push($aFieldsSaveNotMaster, 'id_ppid');
array_push($aFieldsSaveNotMaster, 'rank');
array_push($aFieldsSaveNotMaster, 'id_pid');
array_push($aFieldsSaveNotMaster, 'revenue_pid');
array_push($aFieldsSaveNotMaster, 'prod_type');
array_push($aFieldsSaveNotMaster, 'id_ptid');
array_push($aFieldsSaveNotMaster, 'pn_text');
array_push($aFieldsSaveNotMaster, 'mkt_name');
array_push($aFieldsSaveNotMaster, 'mkt_paragraph');
array_push($aFieldsSaveNotMaster, 'tagline');
array_push($aFieldsSaveNotMaster, 'price');
array_push($aFieldsSaveNotMaster, 'description_text_25');
array_push($aFieldsSaveNotMaster, 'description_text_50');
array_push($aFieldsSaveNotMaster, 'description_text_100');
array_push($aFieldsSaveNotMaster, 'image');
array_push($aFieldsSaveNotMaster, 'id_piid');
array_push($aFieldsSaveNotMaster, 'not_lpmd');
array_push($aFieldsSaveNotMaster, 'content_add');
array_push($aFieldsSaveNotMaster, 'duration');
array_push($aFieldsSaveNotMaster, 'showframe');
array_push($aFieldsSaveNotMaster, 'del');

$aArgsSave['aData']['id_count'] = 0;
$aArgsSave['aData']['id_lang'] = 0;
$aArgsSave['aData']['id_dev'] = 0;
$aArgsSave['aData']['id_cl'] = 1;


$queryS = $CONFIG['dbconn'][0]->prepare('SELECT
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_apid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_asid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_tempid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_bfid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_etid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_tpid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_pcid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_ppid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.rank, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_pid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.revenue_pid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.prod_type, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_ptid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.pn_text, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.mkt_name, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.mkt_paragraph, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.tagline, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.price, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.description_text_25, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.description_text_50, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.description_text_100, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.image, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_piid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.not_lpmd, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.content_add, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.duration, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.showframe, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.del
										FROM ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_asid = (:id_asid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_pcid = (:id_pcid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_ppid = (:id_ppid)
									');
$queryS->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$queryS->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$queryS->bindValue(':nul', 0, PDO::PARAM_INT);
$queryS->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
$queryS->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
$queryS->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryS->execute();
$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
$numS = $queryS->rowCount();

foreach($rowsS as $rowS){
//	// create new ID
//	$query = $CONFIG['dbconn'][0]->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_loc.id_apid
//										FROM ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_loc 
//										WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_loc.id_count = (:id_count)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_loc.id_lang = (:id_lang)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_loc.id_asid = (:id_asid)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_loc.id_tempid = (:id_tempid)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_loc.id_tpeid = (:id_tpeid)
//										');
//	$query->bindValue(':id_count',0, PDO::PARAM_INT);
//	$query->bindValue(':id_lang', 0, PDO::PARAM_INT);
//	$query->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
//	$query->bindValue(':id_tempid', $varSQL['id_tempid'], PDO::PARAM_INT);
//	$query->bindValue(':id_tpeid', $rowS['id_tpeid'], PDO::PARAM_INT);
//	$query->execute();
//	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//	$num = $query->rowCount();
//
//	if($num == 0){
//		$queryI = $CONFIG['dbconn'][0]->prepare('
//											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_
//											(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
//											VALUES
//											(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
//											');
//		$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
//		$queryI->bindValue(':id_cl', 1, PDO::PARAM_INT);
//		$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
//		$queryI->bindValue(':create_from', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
//		$queryI->execute();
//		$varSQL['id_apeid'] = $CONFIG['dbconn'][0]->lastInsertId();
//	}else{
		$varSQL['id_apid'] = $rowS['id_apid'];
//	}
	$aArgsSave['id_data'] = $varSQL['id_apid'];
	
	
	$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_loc
				(id_count, id_lang, id_dev, id_cl, restricted_all, id_apid, id_asid, id_tempid, id_bfid, id_etid, id_tpid, id_pcid, id_ppid, rank, id_pid, revenue_pid, prod_type, id_ptid, pn_text, price, mkt_name, mkt_paragraph, tagline, description_text_25, description_text_50, description_text_100, image, id_piid, not_lpmd, content_add, duration, showframe, create_at, create_from, change_from)
			VALUES
				(:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_apid, :id_asid, :id_tempid, :id_bfid, :id_etid, :id_tpid, :id_pcid, :id_ppid, :rank, :id_pid, :revenue_pid, :prod_type, :id_ptid, :pn_text, :price, :mkt_name, :mkt_paragraph, :tagline, :description_text_25, :description_text_50, :description_text_100, :image, :id_piid, :not_lpmd, :content_add, :duration, :showframe, :now, :create_from, :create_from)
			ON DUPLICATE KEY UPDATE 
				rank = (:rank),
				pn_text = (:pn_text),
				price = (:price),
				prod_type = (:prod_type),
				mkt_name = (:mkt_name),
				mkt_paragraph = (:mkt_paragraph),
				tagline = (:tagline),
				description_text_25 = (:description_text_25),
				description_text_50 = (:description_text_50),
				description_text_100 = (:description_text_100),
				image = (:image),
				id_piid = (:id_piid),
				not_lpmd = (:not_lpmd),
				content_add = (:content_add),
				duration = (:duration),
				showframe = (:showframe),
				change_from = (:create_from),
				del = (:del)
			';
	$queryC = $CONFIG['dbconn'][0]->prepare($qry);
	$queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_cl', 1, PDO::PARAM_INT);
	$queryC->bindValue(':restricted_all', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_apid', $rowS['id_apid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_tempid', $rowS['id_tempid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_bfid', $rowS['id_bfid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_etid', $rowS['id_etid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_tpid', $rowS['id_tpid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$queryC->bindValue(':rank', $rowS['rank'], PDO::PARAM_INT);
	$queryC->bindValue(':id_pid', $rowS['id_pid'], PDO::PARAM_INT);
	$queryC->bindValue(':revenue_pid', $rowS['revenue_pid'], PDO::PARAM_INT);
	$queryC->bindValue(':price', $rowS['price'], PDO::PARAM_STR);
	$queryC->bindValue(':prod_type', $rowS['prod_type'], PDO::PARAM_STR);
	$queryC->bindValue(':id_ptid', $rowS['id_ptid'], PDO::PARAM_INT);
	$queryC->bindValue(':pn_text', $rowS['pn_text'], PDO::PARAM_STR);
	$queryC->bindValue(':mkt_name', $rowS['mkt_name'], PDO::PARAM_STR);
	$queryC->bindValue(':mkt_paragraph', $rowS['mkt_paragraph'], PDO::PARAM_STR);
	$queryC->bindValue(':tagline', $rowS['tagline'], PDO::PARAM_STR);
	$queryC->bindValue(':description_text_25', $rowS['description_text_25'], PDO::PARAM_STR);
	$queryC->bindValue(':description_text_50', $rowS['description_text_50'], PDO::PARAM_STR);
	$queryC->bindValue(':description_text_100', $rowS['description_text_100'], PDO::PARAM_STR);
	$queryC->bindValue(':image', $rowS['image'], PDO::PARAM_STR);
	$queryC->bindValue(':id_piid', $rowS['id_piid'], PDO::PARAM_INT);
	$queryC->bindValue(':not_lpmd', $rowS['not_lpmd'], PDO::PARAM_STR); 
	$queryC->bindValue(':content_add', $rowS['content_add'], PDO::PARAM_STR); 
	$queryC->bindValue(':duration', $rowS['duration'], PDO::PARAM_INT);
	$queryC->bindValue(':showframe', $rowS['showframe'], PDO::PARAM_INT);
	$queryC->bindValue(':now', $now, PDO::PARAM_STR);
	$queryC->bindValue(':del', $rowS['del'], PDO::PARAM_STR);
	$queryC->bindValue(':create_from', $CONFIG['user']['id_ppid'], PDO::PARAM_INT); 
	$queryC->execute();
	$numC = $queryC->rowCount();
	
	
	$aArgsSave['changedVersions'] = array(array(0,0,0));
	$aArgsSave['allVersions'] = array(array(0,0,0), array($CONFIG['user']['id_countid'],$CONFIG['user']['id_langid'],0));
	insertAll($aArgsSave);
}











$out = '<div class="formmessageOK">' . $TEXT['messageOK'] . '</div>';

echo $out;

?>