<?php
########################################################
// Array for pages
$link = basename($_SERVER['PHP_SELF']);

$CONFIG_TMP['user']['pages'] = array();
$queryR = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages 
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page = ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles.id_page
										
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles.id_r = (:right)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.active = (:active)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.specifications = (:spec)
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
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages 
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page = ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles.id_page
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles.id_r = (:right)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.specifications <> (:spec)
											');
		$queryR->bindValue(':right', $aTokenContent['user']['right'], PDO::PARAM_INT);
		$queryR->bindValue(':spec', 0, PDO::PARAM_INT);
		$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}

	if($aTokenContent['user']['specifications'][0] == 8){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.active = (:active)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.specifications <> (:spec)
											');
		$queryR->bindValue(':active', 1, PDO::PARAM_INT);
		$queryR->bindValue(':spec', 0, PDO::PARAM_INT);
		$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}

	if($aTokenContent['user']['specifications'][0] == 9){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.specifications <> (:spec)
											');
		$queryR->bindValue(':spec', 0, PDO::PARAM_INT);
		$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}
}else{
	####################################
	// user is systemadmin
	if($aTokenContent['user']['specifications'][1] == 1){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages 
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page = ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles.id_page
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles.id_r = (:right)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.specifications <> (:spec)
											');
		$queryR->bindValue(':right', $aTokenContent['user']['right'], PDO::PARAM_INT);
		$queryR->bindValue(':spec', 1, PDO::PARAM_INT);
		$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}

	if($aTokenContent['user']['specifications'][1] == 8){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.active = (:active)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.specifications <> (:spec)
											');
		$queryR->bindValue(':active', 1, PDO::PARAM_INT);
		$queryR->bindValue(':spec', 1, PDO::PARAM_INT);
		$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}

	if($aTokenContent['user']['specifications'][1] == 9){
		$queryR = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.specifications <> (:spec)
											');
		$queryR->bindValue(':spec', 1, PDO::PARAM_INT);
		$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}
}

$queryR->execute();
$rowsR = $queryR->fetchAll(PDO::FETCH_ASSOC);
$numR = $queryR->rowCount();

$linkcheck = false;
//$CONFIG['activeSettings']['id_page'] = 0;
foreach($rowsR as $datR){
//	if($datR['link'] == $link){
//		$linkcheck = true;
//		$CONFIG['activeSettings']['id_page'] = $datR['id_page'];
//	}
//	if(!isset($CONFIG['activeSettings']['id_page'])) $CONFIG['activeSettings']['id_page'] = $datR['id_page'];
	
	array_push($CONFIG_TMP['user']['pages'], intval($datR['id_page']));  
}
###########################################################################


// Search first page
if(!isset($CONFIG['activeSettings']['id_page'])){
	$queryP = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.parent_id = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menue = (:active)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.position = (:position)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page IN (' . implode(',', $CONFIG_TMP['user']['pages']) . ')
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_pages.rank
										LIMIT 1
										');
	$queryP->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryP->bindValue(':active', 1, PDO::PARAM_INT);
	$queryP->bindValue(':position', 'left', PDO::PARAM_STR);
	$queryP->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryP->execute();
	$rowsP = $queryP->fetchAll(PDO::FETCH_ASSOC);
	$numP = $queryP->rowCount();
	
	$queryP2 = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.parent_id = (:parent)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menue = (:active)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.position = (:position)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page IN (' . implode(',', $CONFIG_TMP['user']['pages']) . ')
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_pages.rank
										LIMIT 1
										');
	$queryP2->bindValue(':active', 1, PDO::PARAM_INT);
	$queryP2->bindValue(':parent', $rowsP[0]['id_page'], PDO::PARAM_INT);
	$queryP2->bindValue(':position', 'left', PDO::PARAM_STR);
	$queryP2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryP2->execute();
	$rowsP2 = $queryP2->fetchAll(PDO::FETCH_ASSOC);
	$numP2 = $queryP2->rowCount();

	$CONFIG['activeSettings']['id_page'] = $rowsP2[0]['id_page'];

	$aChangeCookie = array();
	if(isset($CONFIG['activeSettings']['id_page'])) $aChangeCookie['id_page'] = $CONFIG['activeSettings']['id_page']; 
	if(count($aChangeCookie) > 0) changeCookie($name='activesettings', $aChangeCookie);
}
?>