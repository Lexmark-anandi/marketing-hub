<?php 
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-app.php');
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$out = '';


if(isset($varSQL['id_tempid'])){
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
				AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_ppid = (:id_ppid)
			';
	$queryC = $CONFIG['dbconn'][0]->prepare($qry);
	$queryC->bindValue(':title', $varSQL['assettitle'], PDO::PARAM_STR);
	$queryC->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$queryC->execute();
	$numC = $queryC->rowCount();
	
	
	
	########################################################
	// save content
	########################################################
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
							(id_count, id_lang, id_dev, id_cl, restricted_all, id_apeid, id_asid, id_tempid, id_tpeid, id_pid, id_pcid, id_ppid, content, content_add, create_at, create_from, change_from)
						VALUES
							(:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_apeid, :id_asid, :id_tempid, :id_tpeid, :id_pid, :id_pcid, :id_ppid, :content, :content_add, :now, :create_from, :create_from)
						ON DUPLICATE KEY UPDATE 
							id_pid = (:id_pid),
							content = (:content),
							content_add = (:content_add),
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
				$queryC->bindValue(':content_add', json_encode($aComponent['content_add']), PDO::PARAM_STR);
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
	//					preg_match('/[0-9\.,]*/', $aComponent['content'], $reg);
	//					$aComponent['content'] = $reg[0] . ' ' . $CONFIG['user']['configCountry']['currency'];
	//					echo $aComponent['content'];
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
			
					// Call to action
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
}

echo $out;

?>