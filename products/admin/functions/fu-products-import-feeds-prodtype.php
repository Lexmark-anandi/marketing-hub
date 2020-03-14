<?php
$aProdtype = array();
$aProdtype['prod_type'] = $product->general->prod_type;
$aProdtype['product_type_id'] = $product->general->prod_type[@product_type_id];

$aChangedVersions = array();

$aProdtype['rank'] = 0;
foreach($CONFIG['system']['prodtype_rank'] as $pt_id){
	$aProdtype['rank'] += 10;
	if($pt_id == $aProdtype['product_type_id']) break;
}

$aProdtype['is_printer'] = 2;
if(in_array($aProdtype['product_type_id'], $CONFIG['system']['prodtype_isprinter'])) $aProdtype['is_printer'] = 1;
$aProdtype['is_color'] = 2;
if(in_array($aProdtype['product_type_id'], $CONFIG['system']['prodtype_iscolor'])) $aProdtype['is_color'] = 1;


$queryPt = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_ext.id_ptid
									FROM ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_ext 
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_ext.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_ext.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_ext.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_ext.product_type_id = (:product_type_id)
										AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_ext.del = (:nultime)
										
									');
$queryPt->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryPt->bindValue(':nul', 0, PDO::PARAM_INT);
$queryPt->bindValue(':product_type_id', $aProdtype['product_type_id'], PDO::PARAM_INT);
$queryPt->execute();
$rowsPt = $queryPt->fetchAll(PDO::FETCH_ASSOC);
$numPt = $queryPt->rowCount();

if($numPt == 0){
	array_push($aChangedVersions, array(0,0,0));

	// first time for all / all
	$queryPt2 = $CONFIG['dbconn']->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_prodtypes
											(create_at, create_from)
										VALUES
											(:create_at, :create_from)
										');
	$queryPt2->bindValue(':create_at', $now, PDO::PARAM_STR);
	$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
	$queryPt2->execute();
	$aProdtype['id_ptid'] = $CONFIG['dbconn']->lastInsertId();
	
	// save all / all
	$queryPt2 = $CONFIG['dbconn']->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_ext
											(id_ptid, id_count, id_lang, id_dev, prod_type, product_type_id, is_printer, is_color, rank, create_at, create_from)
										VALUES
											(:id_ptid, :id_count, :id_lang, :id_dev, :prod_type, :product_type_id, :is_printer, :is_color, :rank, :create_at, :create_from)
										');
	$queryPt2->bindValue(':id_ptid', $aProdtype['id_ptid'], PDO::PARAM_INT);
	$queryPt2->bindValue(':id_count', 0, PDO::PARAM_INT);
	$queryPt2->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryPt2->bindValue(':prod_type', $aProdtype['prod_type'], PDO::PARAM_STR);
	$queryPt2->bindValue(':product_type_id', $aProdtype['product_type_id'], PDO::PARAM_INT);
	$queryPt2->bindValue(':is_printer', $aProdtype['is_printer'], PDO::PARAM_INT);
	$queryPt2->bindValue(':is_color', $aProdtype['is_color'], PDO::PARAM_INT);
	$queryPt2->bindValue(':rank', $aProdtype['rank'], PDO::PARAM_INT);
	$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
	$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
	$queryPt2->execute();
	
	// save country / language
	$queryPt2 = $CONFIG['dbconn']->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_ext
											(id_ptid, id_count, id_lang, id_dev, prod_type, product_type_id, is_printer, is_color, rank, create_at, create_from)
										VALUES
											(:id_ptid, :id_count, :id_lang, :id_dev, :prod_type, :product_type_id, :is_printer, :is_color, :rank, :create_at, :create_from)
										');
	$queryPt2->bindValue(':id_ptid', $aProdtype['id_ptid'], PDO::PARAM_INT);
	$queryPt2->bindValue(':id_count', $id_count, PDO::PARAM_INT);
	$queryPt2->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
	$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryPt2->bindValue(':prod_type', $aProdtype['prod_type'], PDO::PARAM_STR);
	$queryPt2->bindValue(':product_type_id', $aProdtype['product_type_id'], PDO::PARAM_INT);
	$queryPt2->bindValue(':is_printer', $aProdtype['is_printer'], PDO::PARAM_INT);
	$queryPt2->bindValue(':is_color', $aProdtype['is_color'], PDO::PARAM_INT);
	$queryPt2->bindValue(':rank', $aProdtype['rank'], PDO::PARAM_INT);
	$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
	$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
	$queryPt2->execute();
	
}else{
	array_push($aChangedVersions, array($id_count, $id_lang, 0));
	$aProdtype['id_ptid'] = $rowsPt[0]['id_ptid'];
	
	$col = '';
	$value = '';
	$upd = '';
	foreach($aProdtype as $key => $val){
		$col .= ', ' . $key;
		$value .= ', "' . str_replace('"', '\"', $val) . '"';
		$upd .= $key.' = "' . str_replace('"', '\"', $val) . '", ' ;
	}
	
	$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_ext ';
	$qry .= '(id_count, id_lang, id_dev, create_at, create_from ' . $col . ') ';
	$qry .= 'VALUES ';					
	$qry .= '('.$id_count.', '.$id_lang.', 0, "'.$now.'", 0 ' . $value . ') '; 
	$qry .= 'ON DUPLICATE KEY UPDATE ';	
	$qry .= '' . $upd . ' change_from=0, del="0000-00-00 00:00:00";';
	$queryPt2 = $CONFIG['dbconn']->prepare($qry);
	$queryPt2->execute();
}



#############################################################################
$modul = 'import';
$table = $CONFIG['db'][0]['prefix'] . '_prodtypes';
$primekey = 'id_ptid';
$aFieldsNumbers = array('product_type_id', 'is_printer', 'is_color', 'rank');

$columnsExtAll = $table . '_##TYPE##.' . $primekey . ',
			' . $table . '_##TYPE##.prod_type,
			' . $table . '_##TYPE##.product_type_id,
			' . $table . '_##TYPE##.is_printer,
			' . $table . '_##TYPE##.is_color,
			' . $table . '_##TYPE##.rank
';
$columnsExtLoc = $table . '_##TYPE##.' . $primekey . ' AS ' . $primekey . '_extloc,
			' . $table . '_##TYPE##.prod_type AS prod_type_extloc,
			' . $table . '_##TYPE##.product_type_id AS prod_type_id_extloc,
			' . $table . '_##TYPE##.is_printer AS is_printer_extloc,
			' . $table . '_##TYPE##.is_color AS is_color_extloc,
			' . $table . '_##TYPE##.rank AS rank_extloc
';
$columnsLocAll = $table . '_##TYPE##.' . $primekey . ' AS ' . $primekey . '_locall,
			' . $table . '_##TYPE##.prod_type AS prod_type_locall,
			' . $table . '_##TYPE##.product_type_id AS prod_type_id_locall,
			' . $table . '_##TYPE##.is_printer AS is_printer_locall,
			' . $table . '_##TYPE##.is_color AS is_color_locall,
			' . $table . '_##TYPE##.rank AS rank_locall
';
$columnsLocLoc = $table . '_##TYPE##.' . $primekey . ' AS ' . $primekey . '_locloc,
			' . $table . '_##TYPE##.prod_type AS prod_type_locloc,
			' . $table . '_##TYPE##.product_type_id AS prod_type_id_locloc,
			' . $table . '_##TYPE##.is_printer AS is_printer_locloc,
			' . $table . '_##TYPE##.is_color AS is_color_locloc,
			' . $table . '_##TYPE##.rank AS rank_locloc
';
$aColumns = array($columnsExtAll, $columnsExtLoc, $columnsLocAll, $columnsLocLoc);

insertAllProducts($modul, $table, $primekey, $aProdtype['id_ptid'], $aColumns, $aFieldsNumbers, $aChangedVersions, '', $aArgs['saveVer']);

#############################


?>