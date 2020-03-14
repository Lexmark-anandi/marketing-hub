<?php
foreach($product->techspecs->attribute as $attribute) {							
	$aChangedVersions = array();

	$aTechspec = array();
	$aTechspec['attribute_id'] = $attribute[@attribute_id];
	$aTechspec['name'] = $attribute[@name];
	$aTechspec['functionality_id'] = $attribute[@functionality_id];
	$aTechspec['functionality'] = $attribute[@functionality];
	$aTechspec['category_id'] = $attribute[@category_id];
	$aTechspec['category'] = $attribute[@category];
	$aTechspec['type'] = $attribute[@type];
	$aTechspec['unit'] = $attribute[@unit];
	$aTechspec['state'] = $attribute[@state];


	$queryPt = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_techspecs_ext.id_tsid
										FROM ' . $CONFIG['db'][0]['prefix'] . '_techspecs_ext 
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_techspecs_ext.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_techspecs_ext.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_techspecs_ext.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_techspecs_ext.attribute_id = (:attribute_id)
											AND ' . $CONFIG['db'][0]['prefix'] . '_techspecs_ext.del = (:nultime)
											
										');
	$queryPt->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryPt->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryPt->bindValue(':attribute_id', $aTechspec['attribute_id'], PDO::PARAM_INT);
	$queryPt->execute();
	$rowsPt = $queryPt->fetchAll(PDO::FETCH_ASSOC);
	$numPt = $queryPt->rowCount();
	
	if($numPt == 0){
		array_push($aChangedVersions, array(0,0,0));

		// first time for all / all
		$queryPt2 = $CONFIG['dbconn']->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_techspecs
												(create_at, create_from)
											VALUES
												(:create_at, :create_from)
											');
		$queryPt2->bindValue(':create_at', $now, PDO::PARAM_STR);
		$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
		$queryPt2->execute();
		$aTechspec['id_tsid'] = $CONFIG['dbconn']->lastInsertId();

		
		// save all / all
		$queryPt2 = $CONFIG['dbconn']->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_techspecs_ext
												(id_count, id_lang, id_dev, create_at, create_from, ' . implode(', ', array_keys($aTechspec)) . ')
											VALUES
												(:id_count, :id_lang, :id_dev, :create_at, :create_from, :' . implode(', :', array_keys($aTechspec)) . ')
											');
		$queryPt2->bindValue(':id_count', 0, PDO::PARAM_INT);
		$queryPt2->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
		$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
		foreach($aTechspec as $field => $value){
			$queryPt2->bindValue(':'.$field, $value, PDO::PARAM_STR);
		}
		$queryPt2->execute();
		
		// save country / language
		$queryPt2 = $CONFIG['dbconn']->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_techspecs_ext
												(id_count, id_lang, id_dev, create_at, create_from, ' . implode(', ', array_keys($aTechspec)) . ')
											VALUES
												(:id_count, :id_lang, :id_dev, :create_at, :create_from, :' . implode(', :', array_keys($aTechspec)) . ')
											');
		$queryPt2->bindValue(':id_count', $id_count, PDO::PARAM_INT);
		$queryPt2->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
		$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
		$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
		foreach($aTechspec as $field => $value){
			$queryPt2->bindValue(':'.$field, $value, PDO::PARAM_STR); 
		}
		$queryPt2->execute();
		
	}else{
		array_push($aChangedVersions, array($id_count, $id_lang, 0));
		$aTechspec['id_tsid'] = $rowsPt[0]['id_tsid'];
		
		$col = '';
		$value = '';
		$upd = '';
		foreach($aTechspec as $key => $val){
			$col .= ', ' . $key;
			$value .= ', "' . str_replace('"', '\"', $val) . '"';
			$upd .= $key.' = "' . str_replace('"', '\"', $val) . '", ' ;
		}
		
		$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_techspecs_ext ';
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
	$table = $CONFIG['db'][0]['prefix'] . '_techspecs';
	$primekey = 'id_tsid';
	$aFieldsNumbers = array('id_tsid', 'attribute_id', 'functionality_id', 'category_id');
	
	$columnsExtAll = '';
	$columnsExtLoc = '';
	$columnsLocAll = '';
	$columnsLocLoc = '';
	foreach($aTechspec as $field => $value){
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
	
	insertAllProducts($modul, $table, $primekey, $aTechspec['id_tsid'], $aColumns, $aFieldsNumbers, $aChangedVersions, '', $aArgs['saveVer']);
	#############################################################################
	

	
	#############################
	// Values
	include($CONFIG['system']['pathInclude'] . 'admin/functions/fu-products-import-feeds-techvalues.php');
	#############################
	
}
?>