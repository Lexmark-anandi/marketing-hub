<?php
function checkChanges($table, $primekey, $listFields, $columns, $aData){
	global $CONFIG;

	// Vergleich für Änderungen
	$queryOld = $CONFIG['dbconn']->prepare('
										SELECT ' . str_replace('##TYPE##', 'uni', $columns) . '
										FROM ' . $table . '_uni 
										WHERE ' . $table . '_uni.' . $primekey . ' = (:primekey)
											AND ' . $table . '_uni.id_count = (:id_count)
											AND ' . $table . '_uni.id_lang = (:id_lang)
											AND ' . $table . '_uni.id_dev = (:id_dev)
											AND ' . $table . '_uni.id_clid = (:id_clid)
										');
	$queryOld->bindValue(':id_count', $aData['id_count'], PDO::PARAM_INT);
	$queryOld->bindValue(':id_lang', $aData['id_lang'], PDO::PARAM_INT);
	$queryOld->bindValue(':id_dev', $aData['id_dev'], PDO::PARAM_INT);
	$queryOld->bindValue(':id_clid', $aData['id_clid'], PDO::PARAM_INT);
	$queryOld->bindValue(':primekey', $aData['id'], PDO::PARAM_INT);
	$queryOld->execute();
	$rowsOld = $queryOld->fetchAll(PDO::FETCH_ASSOC);
	$numOld = $queryOld->rowCount();


	$listCASE = "";
	foreach($listFields as $key=>$val){
		$listCASE .= "CASE ".$key." WHEN (:".$key.") THEN '' ELSE '".$key."' END,";
	}
	$listCASE = rtrim($listCASE, ",\r\n");


	$aChangedFields = array();
	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $listCASE . '
										FROM ' . $table . '_uni 
										WHERE ' . $table . '_uni.' . $primekey . ' = (:primekey)
											AND ' . $table . '_uni.id_count = (:id_count)
											AND ' . $table . '_uni.id_lang = (:id_lang)
											AND ' . $table . '_uni.id_dev = (:id_dev)
											AND ' . $table . '_uni.id_clid = (:id_clid)
										');
	$query->bindValue(':id_count', $aData['id_count'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $aData['id_lang'], PDO::PARAM_INT);
	$query->bindValue(':id_dev', $aData['id_dev'], PDO::PARAM_INT);
	$query->bindValue(':id_clid', $aData['id_clid'], PDO::PARAM_INT);
	$query->bindValue(':primekey', $aData['id'], PDO::PARAM_INT);
	foreach($listFields as $key=>$val){
		if($val[1] == 'd'){
			$query->bindValue(':'.$key, $aData[$val[0]], PDO::PARAM_INT);
		}else{
			$query->bindValue(':'.$key, $aData[$val[0]], PDO::PARAM_STR);
		}
	}
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();

	if($num > 0){
		foreach($rows[0] as $key=>$val){
			if($val != "") array_push($aChangedFields, $val);
		}
	}
	
	
	$out = array($aChangedFields, $rowsOld);
	return $out;
}


?>