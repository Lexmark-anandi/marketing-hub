<?php
########################################################
// Array for functions
$CONFIG_TMP['user']['fields_forms'] = array(0);
$queryR = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_field
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_fields 
										
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_fields.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_active = (:active)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_formactive = (:active)
										AND (SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_specifications, 1, 1) = (:spec)
											OR SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_specifications, 2, 1) = (:spec))
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_fs <> (:nul)
									');
$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryR->bindValue(':active', 1, PDO::PARAM_INT);
$queryR->bindValue(':spec', 9, PDO::PARAM_INT);
$queryR->bindValue(':nul', 0, PDO::PARAM_INT);

if($aTokenContent['user']['specifications'][1] == 0){
	####################################
	// user is admin but not systemadmin
	if($aTokenContent['user']['specifications'][0] == 1){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_field
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_fields 
												
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_fields.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_formactive = (:active)
												AND (SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_specifications, 1, 1) <> (:spec)
													OR SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_specifications, 2, 1) <> (:spec))
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_fs <> (:nul)
											');
		$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryR->bindValue(':active', 1, PDO::PARAM_INT);
		$queryR->bindValue(':spec', 0, PDO::PARAM_INT);
		$queryR->bindValue(':nul', 0, PDO::PARAM_INT);
	}

	if($aTokenContent['user']['specifications'][0] == 8){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_field
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_fields 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_fields.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_active = (:active)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_formactive = (:active)
												AND (SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_specifications, 1, 1) <> (:spec)
													OR SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_specifications, 2, 1) <> (:spec))
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_fs <> (:nul)
											');
		$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryR->bindValue(':active', 1, PDO::PARAM_INT);
		$queryR->bindValue(':spec', 0, PDO::PARAM_INT);
		$queryR->bindValue(':nul', 0, PDO::PARAM_INT);
	}

	if($aTokenContent['user']['specifications'][0] == 9){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_field
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_fields 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_fields.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_formactive = (:active)
												AND (SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_specifications, 1, 1) <> (:spec)
													OR SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_specifications, 2, 1) <> (:spec))
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_fs <> (:nul)
											');
		$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryR->bindValue(':active', 1, PDO::PARAM_INT);
		$queryR->bindValue(':spec', 0, PDO::PARAM_INT);
		$queryR->bindValue(':nul', 0, PDO::PARAM_INT);
	}
}else{
	####################################
	// user is systemadmin
	if($aTokenContent['user']['specifications'][0] == 1){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_field
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_fields 
												
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_fields.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_formactive = (:active)
												AND (SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_specifications, 1, 1) <> (:spec)
													OR SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_specifications, 2, 1) <> (:spec))
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_fs <> (:nul)
											');
		$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryR->bindValue(':active', 1, PDO::PARAM_INT);
		$queryR->bindValue(':spec', 1, PDO::PARAM_INT);
		$queryR->bindValue(':nul', 0, PDO::PARAM_INT);
	}

	if($aTokenContent['user']['specifications'][0] == 8){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_field
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_fields 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_fields.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_active = (:active)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_formactive = (:active)
												AND (SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_specifications, 1, 1) <> (:spec)
													OR SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_specifications, 2, 1) <> (:spec))
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_fs <> (:nul)
											');
		$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryR->bindValue(':active', 1, PDO::PARAM_INT);
		$queryR->bindValue(':spec', 1, PDO::PARAM_INT);
		$queryR->bindValue(':nul', 0, PDO::PARAM_INT);
	}

	if($aTokenContent['user']['specifications'][0] == 9){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_field
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_fields 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_fields.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_formactive = (:active)
												AND (SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_specifications, 1, 1) <> (:spec)
													OR SUBSTRING(' . $CONFIG['db'][0]['prefix'] . 'system_fields.f_specifications, 2, 1) <> (:spec))
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_fs <> (:nul)
											');
		$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryR->bindValue(':active', 1, PDO::PARAM_INT);
		$queryR->bindValue(':spec', 1, PDO::PARAM_INT);
		$queryR->bindValue(':nul', 0, PDO::PARAM_INT);
	}
}

$queryR->execute();
$rowsR = $queryR->fetchAll(PDO::FETCH_ASSOC);
$numR = $queryR->rowCount();

$linkcheck = false;
foreach($rowsR as $datR){
	array_push($CONFIG_TMP['user']['fields_forms'], intval($datR['id_field']));  
}
###########################################################################
?>