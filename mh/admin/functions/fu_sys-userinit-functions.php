<?php
########################################################
// Array for functions
$CONFIG_TMP['user']['functions'] = array();
$queryR = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod2f
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions 
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod2f = ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions.id_mod2f
										
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions.id_r = (:right)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.active = (:active)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.specifications = (:spec)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod IN (' . implode(',', $CONFIG_TMP['user']['moduls']) . ')
									');
$queryR->bindValue(':right', $aTokenContent['user']['right'], PDO::PARAM_INT);
$queryR->bindValue(':active', 1, PDO::PARAM_INT);
$queryR->bindValue(':spec', 9, PDO::PARAM_INT);

if($aTokenContent['user']['specifications'][1] == 0){
	####################################
	// user is admin but not systemadmin
	if($aTokenContent['user']['specifications'][0] == 1){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod2f
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions 
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod2f = ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions.id_mod2f
												
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions.id_r = (:right)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.specifications <> (:spec)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod IN (' . implode(',', $CONFIG_TMP['user']['moduls']) . ')
											');
		$queryR->bindValue(':right', $aTokenContent['user']['right'], PDO::PARAM_INT);
		$queryR->bindValue(':spec', 0, PDO::PARAM_INT);
	}

	if($aTokenContent['user']['specifications'][0] == 8){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod2f
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.active = (:active)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.specifications <> (:spec)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod IN (' . implode(',', $CONFIG_TMP['user']['moduls']) . ')
											');
		$queryR->bindValue(':active', 1, PDO::PARAM_INT);
		$queryR->bindValue(':spec', 0, PDO::PARAM_INT);
	}

	if($aTokenContent['user']['specifications'][0] == 9){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod2f
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.specifications <> (:spec)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod IN (' . implode(',', $CONFIG_TMP['user']['moduls']) . ')
											');
		$queryR->bindValue(':spec', 0, PDO::PARAM_INT);
	}
}else{
	####################################
	// user is systemadmin
	if($aTokenContent['user']['specifications'][0] == 1){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod2f
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions 
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod2f = ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions.id_mod2f
												
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions.id_r = (:right)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.specifications <> (:spec)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod IN (' . implode(',', $CONFIG_TMP['user']['moduls']) . ')
											');
		$queryR->bindValue(':right', $aTokenContent['user']['right'], PDO::PARAM_INT);
		$queryR->bindValue(':spec', 1, PDO::PARAM_INT);
	}

	if($aTokenContent['user']['specifications'][0] == 8){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod2f
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.active = (:active)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.specifications <> (:spec)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod IN (' . implode(',', $CONFIG_TMP['user']['moduls']) . ')
											');
		$queryR->bindValue(':active', 1, PDO::PARAM_INT);
		$queryR->bindValue(':spec', 1, PDO::PARAM_INT);
	}

	if($aTokenContent['user']['specifications'][0] == 9){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod2f
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.specifications <> (:spec)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod IN (' . implode(',', $CONFIG_TMP['user']['moduls']) . ')
											');
		$queryR->bindValue(':spec', 1, PDO::PARAM_INT);
	}
}

$queryR->execute();
$rowsR = $queryR->fetchAll(PDO::FETCH_ASSOC);
$numR = $queryR->rowCount();

foreach($rowsR as $datR){
	array_push($CONFIG_TMP['user']['functions'], intval($datR['id_mod2f']));  
}
###########################################################################
?>