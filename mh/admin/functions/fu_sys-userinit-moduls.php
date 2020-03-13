<?php
########################################################
// Array for moduls
$CONFIG_TMP['user']['moduls'] = array();
$queryR = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.id_mod
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_moduls 
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2roles
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.id_mod = ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2roles.id_mod
										
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2roles.id_r = (:right)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.active = (:active)
										AND SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_moduls.specifications, 14, 1) = (:spec)
									');
$queryR->bindValue(':right', $aTokenContent['user']['right'], PDO::PARAM_INT);
$queryR->bindValue(':active', 1, PDO::PARAM_INT);
$queryR->bindValue(':spec', 9, PDO::PARAM_INT);
$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);

if($aTokenContent['user']['specifications'][1] == 0){
	####################################
	// user is admin but not systemadmin
	if($aTokenContent['user']['specifications'][0] == 1){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.id_mod
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_moduls 
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2roles
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.id_mod = ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2roles.id_mod
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2roles.id_r = (:right)
												AND SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_moduls.specifications, 14, 1) <> (:spec)
											');
		$queryR->bindValue(':right', $aTokenContent['user']['right'], PDO::PARAM_INT);
		$queryR->bindValue(':spec', 0, PDO::PARAM_INT);
		$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}

	if($aTokenContent['user']['specifications'][0] == 8){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.id_mod
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_moduls 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.active = (:active)
												AND SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_moduls.specifications, 14, 1) <> (:spec)
											');
		$queryR->bindValue(':active', 1, PDO::PARAM_INT);
		$queryR->bindValue(':spec', 0, PDO::PARAM_INT);
		$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}

	if($aTokenContent['user']['specifications'][0] == 9){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.id_mod
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_moduls 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.del = (:nultime)
												AND SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_moduls.specifications, 14, 1) <> (:spec)
											');
		$queryR->bindValue(':spec', 0, PDO::PARAM_INT);
		$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}
}else{
	####################################
	// user is systemadmin
	if($aTokenContent['user']['specifications'][1] == 1){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.id_mod
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_moduls 
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2roles
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.id_mod = ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2roles.id_mod
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2roles.id_r = (:right)
												AND SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_moduls.specifications, 14, 1) <> (:spec)
											');
		$queryR->bindValue(':right', $aTokenContent['user']['right'], PDO::PARAM_INT);
		$queryR->bindValue(':spec', 1, PDO::PARAM_INT);
		$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}

	if($aTokenContent['user']['specifications'][1] == 8){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.id_mod
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_moduls 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.active = (:active)
												AND SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_moduls.specifications, 14, 1) <> (:spec)
											');
		$queryR->bindValue(':active', 1, PDO::PARAM_INT);
		$queryR->bindValue(':spec', 1, PDO::PARAM_INT);
		$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}

	if($aTokenContent['user']['specifications'][1] == 9){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.id_mod
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_moduls 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.del = (:nultime)
												AND SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_moduls.specifications, 14, 1) <> (:spec)
											');
		$queryR->bindValue(':spec', 1, PDO::PARAM_INT);
		$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}
}

$queryR->execute();
$rowsR = $queryR->fetchAll(PDO::FETCH_ASSOC);
$numR = $queryR->rowCount();

foreach($rowsR as $datR){
	array_push($CONFIG_TMP['user']['moduls'], intval($datR['id_mod']));  
}
###########################################################################
?>