<?php
$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $table . '.' . $primekey . '
									FROM ' . $table . ' 
									WHERE ' . $table . '.' . $primekey . ' = (:id)
									');
$query->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$aArgs = array();
$aArgs['id'] = $varSQL['id'];
$aArgs['aDelete'] = array($table => $primekey);
if($num > 0) deleteRow($aArgs);


foreach($aDeleteAdd as $table=>$key){
	$query = $CONFIG['dbconn']->prepare('
										SELECT DISTINCT(' . $table . '_uni.' . $key . ')
										FROM ' . $table . '_uni 
										WHERE ' . $table . '_uni.' . $primekey . ' = (:id)
										');
	$query->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	
	foreach($rows as $row){
		$aArgs = array();
		$aArgs['id'] = $row[$key];
		$aArgs['aDelete'] = array($table => $key);
		if($num > 0) deleteRow($aArgs);
	}
}

?>