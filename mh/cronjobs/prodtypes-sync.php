<?php
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-insert-all.php');

$qry = '';
$qry .= 'DROP TEMPORARY TABLE IF EXISTS prodtypes; ';
$qry .= 'CREATE TEMPORARY TABLE prodtypes LIKE products.200_prodtypes_uni; ';
$qry .= 'INSERT prodtypes SELECT * FROM products.200_prodtypes_uni; ';
$query = $CONFIG['dbconn'][0]->prepare($qry);
$query->execute();

#############################################

$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni 
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni 
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
									
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
	$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . '_prodtypes_';
	$aArgsSave['primarykey'] = 'id_ptid';
	$aArgsSave['allVersions'] = array();
	$aArgsSave['changedVersions'] = array();
	
	$aArgsSave['columns'] = array();
	$aArgsSave['columns']['id_ptid'] = 'i';
	$aArgsSave['columns']['prod_type'] = 's';
	$aArgsSave['columns']['product_type_id'] = 'i';
	$aArgsSave['columns']['is_printer'] = 'i';
	$aArgsSave['columns']['is_color'] = 'i';
	$aArgsSave['columns']['rank'] = 'i';
	
	$aArgsSave['aFieldsNumbers'] = array('id_ptid', 'product_type_id', 'is_printer', 'is_color', 'rank');
	
	$aArgsSave['excludeUpdateUni'] = array();
	$aArgsSave['excludeUpdateUni']['id_ptid'] = array('',0);
	$aArgsSave['excludeUpdateUni']['prod_type'] = array('');
	$aArgsSave['excludeUpdateUni']['product_type_id'] = array('',0);
	$aArgsSave['excludeUpdateUni']['is_printer'] = array('',0);
	$aArgsSave['excludeUpdateUni']['is_color'] = array('',0);
	$aArgsSave['excludeUpdateUni']['rank'] = array('',0);

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
	
	$qry = 'SELECT ';
	$qry .= 'prodtypes.id_ptid, ';
	$qry .= 'prodtypes.prod_type, ';
	$qry .= 'prodtypes.product_type_id, ';
	$qry .= 'prodtypes.is_printer, ';
	$qry .= 'prodtypes.is_color, ';
	$qry .= 'prodtypes.rank ';
	$qry .= 'FROM prodtypes ';
	$qry .= 'WHERE prodtypes.id_count = ' . $row['id_countid'] . ' ';
	$qry .= 'AND prodtypes.id_lang = ' . $row['id_langid'] . ' ';
	
	$queryS = $CONFIG['dbconn'][0]->prepare($qry);
	$queryS->execute();
	$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
	$numS = $queryS->rowCount();
	
	foreach($rowsS as $rowS){
		$aArgsSave['changedVersions'] = array();
		$aArgsSave['id_data'] = $rowS['id_ptid'];
		
		$query2 = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $aArgsSave['table'] . '
												(id_ptid, id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
											VALUES
												(:id_ptid, :id_count, :id_lang, :id_dev, :id_cl, :create_at, :create_from, :create_from)
											ON DUPLICATE KEY UPDATE 
												change_from = (:create_from)
											');
		$query2->bindValue(':id_ptid', $aArgsSave['id_data'], PDO::PARAM_INT);
		$query2->bindValue(':id_count', 0, PDO::PARAM_INT);
		$query2->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$query2->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$query2->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
		$query2->bindValue(':create_at', $now, PDO::PARAM_STR);
		$query2->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
		$query2->execute();
		
		// master
		$query2 = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $aArgsSave['table'] . 'ext
												(' . $aArgsSave['primarykey'] . ', id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from' . $col . ')
											VALUES
												(:id, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from' . $val . ')
											ON DUPLICATE KEY UPDATE 
												' . $upd . '
												change_from = (:create_from)
											');
		$query2->bindValue(':id', $rowS['id_ptid'], PDO::PARAM_INT);
		$query2->bindValue(':id_count', 0, PDO::PARAM_INT);
		$query2->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$query2->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$query2->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
		$query2->bindValue(':now', $now, PDO::PARAM_STR);
		$query2->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
		
		foreach($aArgsSave['columns'] as $field => $format){
			if($field != $aArgsSave['primarykey']){
				if($format == 'i' || $format == 'si' || $format == 'b'){
					$query2->bindValue(':'.$field, $rowS[$field], PDO::PARAM_INT);
				}else{ 
					$query2->bindValue(':'.$field, $rowS[$field], PDO::PARAM_STR);
				}
			}
		}
		$query2->execute();
		$num2 = $query2->rowCount();
		array_push($aArgsSave['changedVersions'], array(0,0,0));
		
		// local
		$query2 = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $aArgsSave['table'] . 'ext
												(' . $aArgsSave['primarykey'] . ', id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from' . $col . ')
											VALUES
												(:id, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from' . $val . ')
											ON DUPLICATE KEY UPDATE 
												' . $upd . '
												change_from = (:create_from)
											');
		$query2->bindValue(':id', $rowS['id_ptid'], PDO::PARAM_INT);
		$query2->bindValue(':id_count', $row['id_countid'], PDO::PARAM_INT);
		$query2->bindValue(':id_lang', $row['id_langid'], PDO::PARAM_INT);
		$query2->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$query2->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
		$query2->bindValue(':now', $now, PDO::PARAM_STR);
		$query2->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
		
		foreach($aArgsSave['columns'] as $field => $format){
			if($field != $aArgsSave['primarykey']){
				if($format == 'i' || $format == 'si' || $format == 'b'){
					$query2->bindValue(':'.$field, $rowS[$field], PDO::PARAM_INT);
				}else{ 
					$query2->bindValue(':'.$field, $rowS[$field], PDO::PARAM_STR);
				}
			}
		}
		$query2->execute();
		$num2 = $query2->rowCount();
		array_push($aArgsSave['changedVersions'], array($row['id_countid'],$row['id_langid'],0));
			

		$aArgsSave['allVersions'] = $aLocalVersions;
		insertAll($aArgsSave);
	}
	
	

//echo $row['id_countid'].'.'.$row['id_langid'].'.'.$numS.'-';
}


?>