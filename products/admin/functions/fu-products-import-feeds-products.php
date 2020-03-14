<?php
$aProduct = array();
$aProduct['revenue_pid'] = $product[@revenue_pid];
$aProduct['is_printer'] = $aProdtype['is_printer'];
$aProduct['is_color'] = $aProdtype['is_color'];
$aProduct['is_bsd'] = $bsd;

$aProduct['out_of_catalogue'] = (strtolower($product->out_of_catalogue) == 'yes') ? 1 : 2;

$aProduct['world_revenue_pid'] = $product->general->world_revenue_pid;
$aProduct['jde_mkt_name'] = $product->general->jde_mkt_name;
$aProduct['ef_shortname'] = $product->general->ef_shortname;
$aProduct['prod_description'] = $product->general->prod_description;
$aProduct['model_number'] = $product->general->model_number;
$aProduct['ctry_of_origin'] = $product->general->ctry_of_origin;
$aProduct['prod_type'] = $product->general->prod_type;
$aProduct['product_type_id'] = $product->general->prod_type[@product_type_id];
$aProduct['id_ptid'] = $aProdtype['id_ptid'];
$aProduct['technology_family'] = $product->general->technology_family;
$aProduct['techno_family_id'] = $product->general->technology_family[@techno_family_id];
$aProduct['id_tfid'] = $aTechnofamily['id_tfid'];
$aProduct['base_model'] = $product->general->base_model;

$aProduct['pn_text'] = $product->pn[@pn_text];
$aProduct['upc'] = $product->pn->upc;
$aProduct['pn_display'] = $product->pn->pn_display;

$aProduct['mkt_name'] = $product->mkt_general[@mkt_name];
$aProduct['mkt_shortname'] = $product->mkt_general->mkt_shortname;
$aProduct['mkt_paragraph'] = $product->mkt_general->mkt_paragraph;
$aProduct['tagline'] = $product->mkt_general->tagline;
$d = DateTime::createFromFormat('M d Y', $product->mkt_general->announce_date);
$aProduct['announce_date'] = ($product->mkt_general->announce_date != '') ? $d->format('Y-m-d') : '0000-00-00';
$d = DateTime::createFromFormat('M d Y', $product->mkt_general->withdraw_date);
$aProduct['withdraw_date'] = ($product->mkt_general->withdraw_date != '') ? $d->format('Y-m-d') : '0000-00-00';
$d = DateTime::createFromFormat('M d Y', $product->mkt_general->withdraw_spare_date);
$aProduct['withdraw_spare_date'] = ($product->mkt_general->withdraw_spare_date != '') ? $d->format('Y-m-d') : '0000-00-00';
$d = DateTime::createFromFormat('M d Y', $product->mkt_general->withdraw_supplies_date);
$aProduct['withdraw_supplies_date'] = ($product->mkt_general->withdraw_supplies_date != '') ? $d->format('Y-m-d') : '0000-00-00';
$aProduct['status'] = $product->mkt_general->status;
$aProduct['archive_flag'] = (strtolower($product->mkt_general->archive_flag) == 'yes') ? 1 : 2;

$aProduct['list'] = str_replace(',', '', $product->prices->list);
$aProduct['street'] = str_replace(',', '', $product->prices->street);
$aProduct['channel'] = str_replace(',', '', $product->prices->channel);
$aProduct['web'] = str_replace(',', '', $product->prices->web);
$aProduct['street_price_with_tax'] = str_replace(',', '', $product->prices->street_price_with_tax);
$aProduct['bsd_price_level1'] = str_replace(',', '', $product->prices->bsd_price_level1);
$aProduct['best_dealer_price'] = str_replace(',', '', $product->prices->best_dealer_price);
$aProduct['bsd_price_level2'] = str_replace(',', '', $product->prices->bsd_price_level2);
$aProduct['best_wholesaler_price'] = str_replace(',', '', $product->prices->best_wholesaler_price);
$aProduct['currency'] = $product->prices->currency;


$aChangedVersions = array();


$queryPt = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_pid
									FROM ' . $CONFIG['db'][0]['prefix'] . '_products_ext 
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_ext.revenue_pid = (:revenue_pid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_ext.del = (:nultime)
										
									');
$queryPt->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryPt->bindValue(':nul', 0, PDO::PARAM_INT);
$queryPt->bindValue(':revenue_pid', $aProduct['revenue_pid'], PDO::PARAM_INT);
$queryPt->execute();
$rowsPt = $queryPt->fetchAll(PDO::FETCH_ASSOC);
$numPt = $queryPt->rowCount();

if($numPt == 0){
	// first time for all / all
	foreach($aArgs['aListLanguagesByCountries'][$id_count] as $lang){
		if($lang != 0) array_push($aChangedVersions, array($id_count, $lang, 0));
	}
	
	
	$queryPt2 = $CONFIG['dbconn']->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_products
											(create_at, create_from)
										VALUES
											(:create_at, :create_from)
										');
	$queryPt2->bindValue(':create_at', $now, PDO::PARAM_STR);
	$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
	$queryPt2->execute();
	$aProduct['id_pid'] = $CONFIG['dbconn']->lastInsertId();
	
	
	// save all / all
	$queryPt2 = $CONFIG['dbconn']->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_products_ext
											(id_count, id_lang, id_dev, create_at, create_from, ' . implode(', ', array_keys($aProduct)) . ')
										VALUES
											(:id_count, :id_lang, :id_dev, :create_at, :create_from, :' . implode(', :', array_keys($aProduct)) . ')
										');
	$queryPt2->bindValue(':id_count', 0, PDO::PARAM_INT);
	$queryPt2->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
	$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
	foreach($aProduct as $field => $value){
		$queryPt2->bindValue(':'.$field, $value, PDO::PARAM_STR);
	}
	$queryPt2->execute();
	
	$queryPt2 = $CONFIG['dbconn']->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_products_uni
											(id_count, id_lang, id_dev, create_at, create_from, ' . implode(', ', array_keys($aProduct)) . ')
										VALUES
											(:id_count, :id_lang, :id_dev, :create_at, :create_from, :' . implode(', :', array_keys($aProduct)) . ')
										');
	$queryPt2->bindValue(':id_count', 0, PDO::PARAM_INT);
	$queryPt2->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
	$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
	foreach($aProduct as $field => $value){
		$queryPt2->bindValue(':'.$field, $value, PDO::PARAM_STR);
	}
	$queryPt2->execute();
	
	// save country / language
	$queryPt2 = $CONFIG['dbconn']->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_products_ext
											(id_count, id_lang, id_dev, create_at, create_from, ' . implode(', ', array_keys($aProduct)) . ')
										VALUES
											(:id_count, :id_lang, :id_dev, :create_at, :create_from, :' . implode(', :', array_keys($aProduct)) . ')
										');
	$queryPt2->bindValue(':id_count', $id_count, PDO::PARAM_INT);
	$queryPt2->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
	$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
	$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
	foreach($aProduct as $field => $value){
		$queryPt2->bindValue(':'.$field, $value, PDO::PARAM_STR);
	}
	$queryPt2->execute();
	
}else{
	array_push($aChangedVersions, array($id_count, $id_lang, 0));
	$aProduct['id_pid'] = $rowsPt[0]['id_pid'];
	
	$col = '';
	$value = '';
	$upd = '';
	foreach($aProduct as $key => $val){
		$col .= ', ' . $key;
		$value .= ', "' . str_replace('"', '\"', $val) . '"';
		$upd .= $key.' = "' . str_replace('"', '\"', $val) . '", ' ;
	}
	
	$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_products_ext ';
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
$table = $CONFIG['db'][0]['prefix'] . '_products';
$primekey = 'id_pid';
$aFieldsNumbers = array('revenue_pid', 'out_of_catalogue', 'product_type_id', 'id_ptid', 'techno_family_id', 'id_tfid', 'archive_flag', 'is_printer', 'id_color', 'is_bsd');

$columnsExtAll = '';
$columnsExtLoc = '';
$columnsLocAll = '';
$columnsLocLoc = '';
foreach($aProduct as $field => $value){
	$columnsExtAll .= '' . $table . '_##TYPE##.' . $field . ', ';
	$columnsExtLoc .= '' . $table . '_##TYPE##.' . $field . ' AS ' . $field . '_extloc, ';
	$columnsLocAll .= '' . $table . '_##TYPE##.' . $field . ' AS ' . $field . '_locall, ';
	$columnsLocLoc .= '' . $table . '_##TYPE##.' . $field . ' AS ' . $field . '_locloc, ';
}
$columnsExtAll = rtrim($columnsExtAll, ', ');
$columnsExtLoc = rtrim($columnsExtLoc, ', ');
$columnsLocAll = rtrim($columnsLocAll, ', ');
$columnsLocLoc = rtrim($columnsLocLoc, ', ');
$aColumns = array($columnsExtAll, $columnsExtLoc, $columnsLocAll, $columnsLocLoc);

//array_push($aChangedVersions, array(0,0,0));

insertAllProducts($modul, $table, $primekey, $aProduct['id_pid'], $aColumns, $aFieldsNumbers, $aChangedVersions, '', $aArgs['saveVer']);

#############################


?>