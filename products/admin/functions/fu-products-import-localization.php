<?php

###########################################################
// Add Prodtypes
###########################################################
$modul = 'import';
$table = $CONFIG['db'][0]['prefix'] . '_prodtypes';
$primekey = 'id_ptid';
$aFieldsNumbers = array('product_type_id', 'is_printer', 'is_color', 'rank');
$aChangedVersions = array(array(0,0,0));

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

$queryPt = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_ext.id_ptid
									FROM ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_ext 
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_ext.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_ext.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_ext.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_ext.del = (:nultime)
									');
$queryPt->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryPt->bindValue(':nul', 0, PDO::PARAM_INT);
$queryPt->execute();
$rowsPt = $queryPt->fetchAll(PDO::FETCH_ASSOC);
$numPt = $queryPt->rowCount();

foreach($rowsPt as $rowPt){
	insertAllProducts($modul, $table, $primekey, $rowPt['id_ptid'], $aColumns, $aFieldsNumbers, $aChangedVersions, '', $CONFIG['saveVersions']);
}




	
?>