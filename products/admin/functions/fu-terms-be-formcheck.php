<?php

function checkUnique($field, $aData){
	global $CONFIG, $TEXT;

	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.var
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.var = (:var)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.var <> (:empty)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_tbeid <> (:id_tbe)
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':var', $aData[$field], PDO::PARAM_STR);
	$query->bindValue(':empty', '', PDO::PARAM_STR);
	$query->bindValue(':id_tbe', $aData['id'], PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	
	$error = '';
	if($num > 0){
		$error = 'Doppelt';
	}
    return $error;
}





?>