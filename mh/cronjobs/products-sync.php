<?php
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-insert-all.php');

//$qry = '';
//$qry .= 'DROP TEMPORARY TABLE IF EXISTS products; ';
//$qry .= 'CREATE TEMPORARY TABLE products LIKE products.200_products_uni; ';
//$qry .= 'INSERT products SELECT * FROM products.200_products_uni; ';
//$qry .= 'ALTER TABLE products ADD INDEX (id_count,id_lang,id_pid,change_at); ';
//
//$qry .= 'DROP TEMPORARY TABLE IF EXISTS descriptions; ';
//$qry .= 'CREATE TEMPORARY TABLE descriptions LIKE products.200_descriptions_uni; ';
//$qry .= 'INSERT descriptions SELECT * FROM products.200_descriptions_uni; ';
//$qry .= 'ALTER TABLE descriptions ADD INDEX (id_count,id_lang,id_pid,change_at); ';
//
//$query = $CONFIG['dbconn'][0]->prepare($qry);
//$query->execute();

#############################################

$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid,
										' . $CONFIG['db'][0]['prefix'] . '_import_updates.products_sync
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni 
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni 
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
									
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_import_updates 
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . '_import_updates.id_count2lang
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->bindValue(':active', 1, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

foreach($rows as $row){
	$date = new DateTime();
	$now = $date->format('Y-m-d H:i:s');
	
	$lastsync = ($row['products_sync'] == '') ? '0000-00-00 00:00:00' : $row['products_sync'];
	if($lastsync != '0000-00-00 00:00:00'){
		$dateSync = new DateTime($lastsync);
		$dateSync->sub(new DateInterval('P1D'));
		$lastsync = $dateSync->format('Y-m-d H:i:s');
	}

	$aArgs = array();
	$aArgs['id_count'] = $row['id_countid'];
	$aArgs['id_lang'] = $row['id_langid'];
	$aArgs['id_dev'] = 0;
	$aArgs['usesystem'] = 0;
	$aArgs['fields'] = array();

	$aFieldsSaveMaster = array();
	$aFieldsSaveNotMaster = array();

	$aArgsSaveN = array();

	$aArgsSave = array();
	$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . '_products_';
	$aArgsSave['primarykey'] = 'id_pid';
	$aArgsSave['allVersions'] = array();
	$aArgsSave['changedVersions'] = array();
	
	$aArgsSave['columns'] = array();
	$aArgsSave['columns']['id_pid_products'] = 'i';
	$aArgsSave['columns']['id_pid'] = 'i';
	$aArgsSave['columns']['revenue_pid'] = 'i';
	$aArgsSave['columns']['mkt_name'] = 's';
	$aArgsSave['columns']['pn_text'] = 's';
	$aArgsSave['columns']['tagline'] = 's';
	$aArgsSave['columns']['mkt_paragraph'] = 's';
	$aArgsSave['columns']['id_ptid'] = 'i';
	$aArgsSave['columns']['status'] = 's';
	$aArgsSave['columns']['announce_date'] = 's';
	$aArgsSave['columns']['withdraw_date'] = 's';
	$aArgsSave['columns']['is_printer'] = 'i';
	$aArgsSave['columns']['is_color'] = 'i';
	$aArgsSave['columns']['is_bsd'] = 'i';
	
	$aArgsSave['aFieldsNumbers'] = array('id_pid', 'revenue_pid', 'id_ptid', 'is_printer', 'is_color', 'is_bsd');
	
	$aArgsSave['excludeUpdateUni'] = array();
	$aArgsSave['excludeUpdateUni']['id_pid_products'] = array('',0);
	$aArgsSave['excludeUpdateUni']['id_pid'] = array('',0);
	$aArgsSave['excludeUpdateUni']['revenue_pid'] = array('',0);
	$aArgsSave['excludeUpdateUni']['mkt_name'] = array('');
	$aArgsSave['excludeUpdateUni']['pn_text'] = array('');
	$aArgsSave['excludeUpdateUni']['tagline'] = array('');
	$aArgsSave['excludeUpdateUni']['mkt_paragraph'] = array('');
	$aArgsSave['excludeUpdateUni']['id_ptid'] = array('',0);
	$aArgsSave['excludeUpdateUni']['status'] = array('');
	$aArgsSave['excludeUpdateUni']['announce_date'] = array('');
	$aArgsSave['excludeUpdateUni']['withdraw_date'] = array('');
	$aArgsSave['excludeUpdateUni']['is_printer'] = array('',0);
	$aArgsSave['excludeUpdateUni']['is_color'] = array('',0);
	$aArgsSave['excludeUpdateUni']['is_bsd'] = array('',0);
	$aArgsSave['excludeUpdateUni']['description_text_25'] = array('');
	$aArgsSave['excludeUpdateUni']['description_text_50'] = array('');
	$aArgsSave['excludeUpdateUni']['description_text_100'] = array('');
	$aArgsSave['excludeUpdateUni']['description_text_200'] = array('');

	$aLocalVersions = array(array(0,0,0), array($row['id_countid'],$row['id_langid'],0));
	
	$col = '';
	$val = '';
	$upd = '';
	foreach($aArgsSave['columns'] as $field => $format){
		if($field != $aArgsSave['primarykey']){
			$col .= ', ' . $field;
			$val .= ', :' . $field . '';
			$upd .= $field.' = (:'.$field.'), ' ;
		}
	}

	#############################################################
	
//	$qry = 'SELECT ';
//	$qry .= 'products.id_pid AS id_pid_products, ';
//	$qry .= 'products.revenue_pid, ';
//	$qry .= 'products.mkt_name, ';
//	$qry .= 'products.pn_text, ';
//	$qry .= 'products.tagline, ';
//	$qry .= 'products.mkt_paragraph, ';
//	$qry .= 'products.id_ptid, ';
//	$qry .= 'products.status, ';
//	$qry .= 'products.announce_date, ';
//	$qry .= 'products.withdraw_date, ';
//	$qry .= 'products.is_printer, ';
//	$qry .= 'products.is_color, ';
//	$qry .= 'products.is_bsd, ';
//	$qry .= 'descriptions.type_id, ';
//	$qry .= 'descriptions.description_text ';
//	$qry .= 'FROM products ';
//	$qry .= 'LEFT JOIN descriptions ';
//	$qry .= 'ON descriptions.id_count = ' . $row['id_countid'] . ' ';
//	$qry .= 'AND descriptions.id_lang = ' . $row['id_langid'] . ' ';
//	$qry .= 'AND products.id_pid = descriptions.id_pid ';
//	$qry .= 'WHERE products.id_count = ' . $row['id_countid'] . ' ';
//	$qry .= 'AND products.id_lang = ' . $row['id_langid'] . ' ';
//	$qry .= 'AND (products.change_at > "' . $lastsync . '" ';
//	$qry .= 'OR descriptions.change_at > "' . $lastsync . '")';

	$qry = 'SELECT ';
	$qry .= 'products.200_products_uni.id_pid AS id_pid_products, ';
	$qry .= 'products.200_products_uni.revenue_pid, ';
	$qry .= 'products.200_products_uni.mkt_name, ';
	$qry .= 'products.200_products_uni.pn_text, ';
	$qry .= 'products.200_products_uni.tagline, ';
	$qry .= 'products.200_products_uni.mkt_paragraph, ';
	$qry .= 'products.200_products_uni.id_ptid, ';
	$qry .= 'products.200_products_uni.status, ';
	$qry .= 'products.200_products_uni.announce_date, ';
	$qry .= 'products.200_products_uni.withdraw_date, ';
	$qry .= 'products.200_products_uni.is_printer, ';
	$qry .= 'products.200_products_uni.is_color, ';
	$qry .= 'products.200_products_uni.is_bsd, ';
	$qry .= 'products.200_descriptions_uni.type_id, ';
	$qry .= 'products.200_descriptions_uni.description_text ';
	$qry .= 'FROM products.200_products_uni ';
	$qry .= 'LEFT JOIN products.200_descriptions_uni ';
	$qry .= 'ON products.200_descriptions_uni.id_count = ' . $row['id_countid'] . ' ';
	$qry .= 'AND products.200_descriptions_uni.id_lang = ' . $row['id_langid'] . ' ';
	$qry .= 'AND products.200_products_uni.id_pid = products.200_descriptions_uni.id_pid ';
	$qry .= 'WHERE products.200_products_uni.id_count = ' . $row['id_countid'] . ' ';
	$qry .= 'AND products.200_products_uni.id_lang = ' . $row['id_langid'] . ' ';
	$qry .= 'AND (products.200_products_uni.change_at > "' . $lastsync . '" ';
	$qry .= 'OR products.200_descriptions_uni.change_at > "' . $lastsync . '")';
	
	$queryS = $CONFIG['dbconn'][0]->prepare($qry);
	$queryS->execute();
	$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
	$numS = $queryS->rowCount();
	 
	foreach($rowsS as $rowS){
		$aArgsSave['changedVersions'] = array();
		
		$desc = 'lock_from';
		if($rowS['type_id'] == 1) $desc = 'description_text_25';
		if($rowS['type_id'] == 2) $desc = 'description_text_50';
		if($rowS['type_id'] == 3) $desc = 'description_text_100';
		if($rowS['type_id'] == 4) $desc = 'description_text_200';
		if($desc == 'lock_from') $rowS['description_text'] = 0;
		
		unset($aArgsSave['columns']['description_text_25']);
		unset($aArgsSave['columns']['description_text_50']);
		unset($aArgsSave['columns']['description_text_100']);
		unset($aArgsSave['columns']['description_text_200']);
		
		$queryP = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_pid
											FROM ' . $CONFIG['db'][0]['prefix'] . '_products_ext 
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_pid_products = (:id_pid_products)
											LIMIT 1
											');
		$queryP->bindValue(':id_pid_products', $rowS['id_pid_products'], PDO::PARAM_INT);
		$queryP->execute();
		$rowsP = $queryP->fetchAll(PDO::FETCH_ASSOC);
		$numP = $queryP->rowCount();
		
		if($numP == 0){
			$queryI = $CONFIG['dbconn'][0]->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_products_
												(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
												VALUES
												(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
												');
			$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
			$queryI->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
			$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
			$queryI->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
			$queryI->execute();
			$aArgsSave['id_data'] = $CONFIG['dbconn'][0]->lastInsertId();
		}else{
			$aArgsSave['id_data'] = $rowsP[0]['id_pid'];
		}
			
		// Master
		$queryP = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_pid
											FROM ' . $CONFIG['db'][0]['prefix'] . '_products_ext 
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_pid = (:id_pid)
											LIMIT 1
											');
		$queryP->bindValue(':id_count', 0, PDO::PARAM_INT);
		$queryP->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$queryP->bindValue(':id_pid', $aArgsSave['id_data'], PDO::PARAM_INT);
		$queryP->execute();
		$rowsP = $queryP->fetchAll(PDO::FETCH_ASSOC);
		$numP = $queryP->rowCount();
			
		if($numP == 0){
			$qry = 'INSERT INTO ' . $aArgsSave['table'] . 'ext
						(' . $aArgsSave['primarykey'] . ', id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from, ' . $desc . '' . $col . ')
					VALUES
						(:id, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from, :desc' . $val . ')
					';
			$queryC = $CONFIG['dbconn'][0]->prepare($qry);
			$queryC->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
			$queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
			$queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
			$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryC->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
			$queryC->bindValue(':now', $now, PDO::PARAM_STR);
			$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
			$queryC->bindValue(':desc', $rowS['description_text'], PDO::PARAM_STR); 
			
			foreach($aArgsSave['columns'] as $field => $format){
				if($field != $aArgsSave['primarykey']){
					if($format == 'i' || $format == 'si' || $format == 'b'){
						$queryC->bindValue(':'.$field, $rowS[$field], PDO::PARAM_INT);
					}else{ 
						$queryC->bindValue(':'.$field, $rowS[$field], PDO::PARAM_STR);
					}
				}
			}
			$queryC->execute();
			$numC = $queryC->rowCount();
			
			array_push($aArgsSave['changedVersions'], array(0,0,0));
		}else{
		}
			
		// Local
		$queryP = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_pid
											FROM ' . $CONFIG['db'][0]['prefix'] . '_products_ext 
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_pid = (:id_pid)
											LIMIT 1
											');
		$queryP->bindValue(':id_count', $row['id_countid'], PDO::PARAM_INT);
		$queryP->bindValue(':id_lang', $row['id_langid'], PDO::PARAM_INT);
		$queryP->bindValue(':id_pid', $aArgsSave['id_data'], PDO::PARAM_INT);
		$queryP->execute();
		$rowsP = $queryP->fetchAll(PDO::FETCH_ASSOC);
		$numP = $queryP->rowCount();
			
		if($numP == 0){
			$qry = 'INSERT INTO ' . $aArgsSave['table'] . 'ext
						(' . $aArgsSave['primarykey'] . ', id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from, ' . $desc . '' . $col . ')
					VALUES
						(:id, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from, :desc' . $val . ')
					';
			$queryC = $CONFIG['dbconn'][0]->prepare($qry);
			$queryC->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
			$queryC->bindValue(':id_count', $row['id_countid'], PDO::PARAM_INT);
			$queryC->bindValue(':id_lang', $row['id_langid'], PDO::PARAM_INT);
			$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryC->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
			$queryC->bindValue(':now', $now, PDO::PARAM_STR);
			$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
			$queryC->bindValue(':desc', $rowS['description_text'], PDO::PARAM_STR); 
			
			foreach($aArgsSave['columns'] as $field => $format){
				if($field != $aArgsSave['primarykey']){
					if($format == 'i' || $format == 'si' || $format == 'b'){
						$queryC->bindValue(':'.$field, $rowS[$field], PDO::PARAM_INT);
					}else{ 
						$queryC->bindValue(':'.$field, $rowS[$field], PDO::PARAM_STR);
					}
				}
			}
			$queryC->execute();
			$numC = $queryC->rowCount();
		}else{
			$qry = 'UPDATE ' . $aArgsSave['table'] . 'ext SET
						' . $upd . '
						' . $desc . ' = (:desc),
						change_from = (:create_from),
						del = (:nultime)
					WHERE ' . $aArgsSave['table'] . 'ext.id_count = (:id_count)
						AND ' . $aArgsSave['table'] . 'ext.id_lang = (:id_lang)
						AND ' . $aArgsSave['table'] . 'ext.id_dev = (:id_dev)
						AND ' . $aArgsSave['table'] . 'ext.id_pid = (:id)
					';
			$queryC = $CONFIG['dbconn'][0]->prepare($qry);
			$queryC->bindValue(':id', $aArgsSave['id_data'], PDO::PARAM_INT);
			$queryC->bindValue(':id_count', $row['id_countid'], PDO::PARAM_INT);
			$queryC->bindValue(':id_lang', $row['id_langid'], PDO::PARAM_INT);
			$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
			$queryC->bindValue(':desc', $rowS['description_text'], PDO::PARAM_STR); 
			
			foreach($aArgsSave['columns'] as $field => $format){
				if($field != $aArgsSave['primarykey']){
					if($format == 'i' || $format == 'si' || $format == 'b'){
						$queryC->bindValue(':'.$field, $rowS[$field], PDO::PARAM_INT);
					}else{ 
						$queryC->bindValue(':'.$field, $rowS[$field], PDO::PARAM_STR);
					}
				}
			}
			$queryC->execute();
			$numC = $queryC->rowCount();
		}
		
		array_push($aArgsSave['changedVersions'], array($row['id_countid'],$row['id_langid'],0));


		if($rowS['type_id'] == 1) $aArgsSave['columns']['description_text_25'] = 's';
		if($rowS['type_id'] == 2) $aArgsSave['columns']['description_text_50'] = 's';
		if($rowS['type_id'] == 3) $aArgsSave['columns']['description_text_100'] = 's';
		if($rowS['type_id'] == 4) $aArgsSave['columns']['description_text_200'] = 's';


		$aArgsSave['allVersions'] = $aLocalVersions;
		insertAll($aArgsSave);
	}
	
	
	$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_import_updates
				(id_count2lang, products_sync)
			VALUES
				(:id_count2lang, :products_sync)
			ON DUPLICATE KEY UPDATE 
				products_sync = (:products_sync)
			';
	$queryC = $CONFIG['dbconn'][0]->prepare($qry);
	$queryC->bindValue(':id_count2lang', $row['id_count2lang'], PDO::PARAM_INT);
	$queryC->bindValue(':products_sync', $now, PDO::PARAM_STR);
	$queryC->execute();
	$numC = $queryC->rowCount();
	

//echo $row['id_countid'].'.'.$row['id_langid'].'.'.$numS.'-';
}


?>