<?php
$aTechnofamily = array();
$aTechnofamily['technology_family'] = $product->general->technology_family;
$aTechnofamily['techno_family_id'] = $product->general->technology_family[@techno_family_id];

$aChangedVersions = array();

$queryPt = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_technofamilies_ext.id_tfid
									FROM ' . $CONFIG['db'][0]['prefix'] . '_technofamilies_ext 
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_technofamilies_ext.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_technofamilies_ext.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_technofamilies_ext.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_technofamilies_ext.techno_family_id = (:techno_family_id)
										AND ' . $CONFIG['db'][0]['prefix'] . '_technofamilies_ext.del = (:nultime)
										
									');
$queryPt->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryPt->bindValue(':nul', 0, PDO::PARAM_INT);
$queryPt->bindValue(':techno_family_id', $aTechnofamily['techno_family_id'], PDO::PARAM_INT);
$queryPt->execute();
$rowsPt = $queryPt->fetchAll(PDO::FETCH_ASSOC);
$numPt = $queryPt->rowCount();

if($numPt == 0){
	array_push($aChangedVersions, array(0,0,0));

	// first time for all / all
	$queryPt2 = $CONFIG['dbconn']->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_technofamilies
											(create_at, create_from)
										VALUES
											(:create_at, :create_from)
										');
	$queryPt2->bindValue(':create_at', $now, PDO::PARAM_STR);
	$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
	$queryPt2->execute();
	$aTechnofamily['id_tfid'] = $CONFIG['dbconn']->lastInsertId();
	
	// save all / all
	$queryPt2 = $CONFIG['dbconn']->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_technofamilies_ext
											(id_tfid, id_count, id_lang, id_dev, technology_family, techno_family_id, create_at, create_from)
										VALUES
											(:id_tfid, :id_count, :id_lang, :id_dev, :technology_family, :techno_family_id, :create_at, :create_from)
										');
	$queryPt2->bindValue(':id_tfid', $aTechnofamily['id_tfid'], PDO::PARAM_INT);
	$queryPt2->bindValue(':id_count', 0, PDO::PARAM_INT);
	$queryPt2->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryPt2->bindValue(':technology_family', $aTechnofamily['technology_family'], PDO::PARAM_STR);
	$queryPt2->bindValue(':techno_family_id', $aTechnofamily['techno_family_id'], PDO::PARAM_INT);
	$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
	$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
	$queryPt2->execute();
	
	// save country / language
	$queryPt2 = $CONFIG['dbconn']->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_technofamilies_ext
											(id_tfid, id_count, id_lang, id_dev, technology_family, techno_family_id, create_at, create_from)
										VALUES
											(:id_tfid, :id_count, :id_lang, :id_dev, :technology_family, :techno_family_id, :create_at, :create_from)
										');
	$queryPt2->bindValue(':id_tfid', $aTechnofamily['id_tfid'], PDO::PARAM_INT);
	$queryPt2->bindValue(':id_count', $id_count, PDO::PARAM_INT);
	$queryPt2->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
	$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryPt2->bindValue(':technology_family', $aTechnofamily['technology_family'], PDO::PARAM_STR);
	$queryPt2->bindValue(':techno_family_id', $aTechnofamily['techno_family_id'], PDO::PARAM_INT);
	$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
	$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
	$queryPt2->execute();
	
}else{
	array_push($aChangedVersions, array($id_count, $id_lang, 0));
	$aTechnofamily['id_tfid'] = $rowsPt[0]['id_tfid'];
	
	$col = '';
	$value = '';
	$upd = '';
	foreach($aTechnofamily as $key => $val){
		$col .= ', ' . $key;
		$value .= ', "' . str_replace('"', '\"', $val) . '"';
		$upd .= $key.' = "' . str_replace('"', '\"', $val) . '", ' ;
	}
	
	$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_technofamilies_ext ';
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
$table = $CONFIG['db'][0]['prefix'] . '_technofamilies';
$primekey = 'id_tfid';
$aFieldsNumbers = array('techno_family_id');

$columnsExtAll = $table . '_##TYPE##.' . $primekey . ',
			' . $table . '_##TYPE##.technology_family,
			' . $table . '_##TYPE##.techno_family_id
';
$columnsExtLoc = $table . '_##TYPE##.' . $primekey . ' AS ' . $primekey . '_extloc,
			' . $table . '_##TYPE##.technology_family AS technology_family_extloc,
			' . $table . '_##TYPE##.techno_family_id AS techno_family_id_extloc
';
$columnsLocAll = $table . '_##TYPE##.' . $primekey . ' AS ' . $primekey . '_locall,
			' . $table . '_##TYPE##.technology_family AS technology_family_locall,
			' . $table . '_##TYPE##.techno_family_id AS techno_family_id_locall
';
$columnsLocLoc = $table . '_##TYPE##.' . $primekey . ' AS ' . $primekey . '_locloc,
			' . $table . '_##TYPE##.technology_family AS technology_family_locloc,
			' . $table . '_##TYPE##.techno_family_id AS techno_family_id_locloc
';
$aColumns = array($columnsExtAll, $columnsExtLoc, $columnsLocAll, $columnsLocLoc);

insertAllProducts($modul, $table, $primekey, $aTechnofamily['id_tfid'], $aColumns, $aFieldsNumbers, $aChangedVersions, '', $aArgs['saveVer']);

#############################


?>