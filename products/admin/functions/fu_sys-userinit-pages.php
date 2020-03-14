<?php
########################################################
// Array for pages and functions
$link = basename($_SERVER['PHP_SELF']);
$CONFIG_TMP['USER']['pages'] = array();
$CONFIG_TMP['USER']['pages2functions2files'] = array();
$queryR = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.link
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages 
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page = ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles.id_page
										
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles.id_r = (:right)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.status = (:active)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.systemadmin = (:nul)
									');
$queryR->bindValue(':right', $aTokenContent['user']['right'], PDO::PARAM_INT);
$queryR->bindValue(':active', 1, PDO::PARAM_INT);
$queryR->bindValue(':nul', 0, PDO::PARAM_INT);

if($aTokenContent['user']['right_systemadmin'] == 9){
	$queryR = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.link
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages 
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page = ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles.id_page
											
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles.id_r = (:right)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.status = (:active)
										');
	$queryR->bindValue(':right', $aTokenContent['user']['right'], PDO::PARAM_INT);
	$queryR->bindValue(':active', 1, PDO::PARAM_INT);
}

if($aTokenContent['user']['right_admin'] == 1){
	$queryR = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.link
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages 
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page = ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles.id_page
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles.id_r = (:right)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.systemadmin = (:nul)
										');
	$queryR->bindValue(':right', $aTokenContent['user']['right'], PDO::PARAM_INT);
	$queryR->bindValue(':nul', 0, PDO::PARAM_INT);
}

if($aTokenContent['user']['right_admin'] == 1 && $aTokenContent['user']['right_systemadmin'] == 9){
	$queryR = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.link
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages 
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page = ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles.id_page
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages2roles.id_r = (:right)
										');
	$queryR->bindValue(':right', $aTokenContent['user']['right'], PDO::PARAM_INT);
}

if($aTokenContent['user']['right_admin'] == 2){
	$queryR = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.link
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.status = (:active)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.systemadmin = (:nul)
										');
	$queryR->bindValue(':active', 1, PDO::PARAM_INT);
	$queryR->bindValue(':nul', 0, PDO::PARAM_INT);
}

if($aTokenContent['user']['right_admin'] == 2 && $aTokenContent['user']['right_systemadmin'] == 9){
	$queryR = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.link
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.status = (:active)
										');
	$queryR->bindValue(':active', 1, PDO::PARAM_INT);
}

if($aTokenContent['user']['right_admin'] == 9){
	$queryR = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.link
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.systemadmin = (:nul)
										');
	$queryR->bindValue(':nul', 0, PDO::PARAM_INT);
}

if($aTokenContent['user']['right_admin'] == 9 && $aTokenContent['user']['right_systemadmin'] == 9){
	$queryR = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.link
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages 
										');
}

$queryR->execute();
$rowsR = $queryR->fetchAll(PDO::FETCH_ASSOC);
$numR = $queryR->rowCount();

$linkcheck = false;
$CONFIG_TMP['USER']['activePageId'] = 0;
foreach($rowsR as $datR){
	if($datR['link'] == $link){
		$linkcheck = true;
		$CONFIG_TMP['USER']['activePageId'] = $datR['id_page'];
	}
	
	array_push($CONFIG_TMP['USER']['pages'], $datR['id_page']);  
	$CONFIG_TMP['USER']['pages2functions2files'][$datR['id_page']] = array();  
	
//	$extQuery = ',
//			' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.function AS function_page,
//			' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.title AS title_page,
//			' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.icon AS icon_page,
//			' . $CONFIG['db'][0]['prefix'] . 'system_functions.function,
//			' . $CONFIG['db'][0]['prefix'] . 'system_functions.title,
//			' . $CONFIG['db'][0]['prefix'] . 'system_functions.icon,
//			' . $CONFIG['db'][0]['prefix'] . 'system_functions.type,
	$extQuery = ', 
				' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page_parent,
				' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.show_not_if AS show_not_if_page,
				' . $CONFIG['db'][0]['prefix'] . 'system_functions.show_not_if
				';
	 
	$queryRf = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page2f2f,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f
											'.$extQuery.'
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_functions
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f = ' . $CONFIG['db'][0]['prefix'] . 'system_functions.id_f
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions.id_page2f2f = ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page2f2f
											
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions.id_r = (:right)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page = (:page)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.systemadmin = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.active = (:active)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_functions.del = (:nultime)
										');
	$queryRf->bindValue(':right', $aTokenContent['user']['right'], PDO::PARAM_INT);
	$queryRf->bindValue(':page', $datR['id_page'], PDO::PARAM_INT);
	$queryRf->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryRf->bindValue(':active', 1, PDO::PARAM_INT);
	$queryRf->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	

	if($aTokenContent['user']['right_systemadmin'] == 9){
		$queryRf = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page2f2f,
												' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f
												'.$extQuery.'
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files
											
											LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_functions
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f = ' . $CONFIG['db'][0]['prefix'] . 'system_functions.id_f
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions.id_page2f2f = ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page2f2f
												
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions.id_r = (:right)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page = (:page)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.active = (:active)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_functions.del = (:nultime)
											');
		$queryRf->bindValue(':right', $aTokenContent['user']['right'], PDO::PARAM_INT);
		$queryRf->bindValue(':page', $datR['id_page'], PDO::PARAM_INT);
		$queryRf->bindValue(':active', 1, PDO::PARAM_INT);
		$queryRf->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}
	
	if($aTokenContent['user']['right_admin'] == 1){
		$queryRf = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page2f2f,
												' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f
												'.$extQuery.'
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files
											
											LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_functions
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f = ' . $CONFIG['db'][0]['prefix'] . 'system_functions.id_f
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions.id_page2f2f = ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page2f2f
												
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions.id_r = (:right)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page = (:page)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.systemadmin = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_functions.del = (:nultime)
											');
		$queryRf->bindValue(':right', $aTokenContent['user']['right'], PDO::PARAM_INT);
		$queryRf->bindValue(':page', $datR['id_page'], PDO::PARAM_INT);
		$queryRf->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryRf->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}
	
	if($aTokenContent['user']['right_admin'] == 1 && $aTokenContent['user']['right_systemadmin'] == 9){
		$queryRf = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page2f2f,
												' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f
												'.$extQuery.'
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files
											
											LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_functions
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f = ' . $CONFIG['db'][0]['prefix'] . 'system_functions.id_f
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions.id_page2f2f = ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page2f2f
												
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_roles2functions.id_r = (:right)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page = (:page)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_functions.del = (:nultime)
											');
		$queryRf->bindValue(':right', $aTokenContent['user']['right'], PDO::PARAM_INT);
		$queryRf->bindValue(':page', $datR['id_page'], PDO::PARAM_INT);
		$queryRf->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}
	
	if($aTokenContent['user']['right_admin'] == 2){
		$queryRf = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page2f2f,
												' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f
												'.$extQuery.'
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files
											
											LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_functions
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f = ' . $CONFIG['db'][0]['prefix'] . 'system_functions.id_f
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page = (:page)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.systemadmin = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.active = (:active)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_functions.del = (:nultime)
											');
		$queryRf->bindValue(':page', $datR['id_page'], PDO::PARAM_INT);
		$queryRf->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryRf->bindValue(':active', 1, PDO::PARAM_INT);
		$queryRf->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}
	
	if($aTokenContent['user']['right_admin'] == 2 && $aTokenContent['user']['right_systemadmin'] == 9){
		$queryRf = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page2f2f,
												' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f
												'.$extQuery.'
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files
											
											LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_functions
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f = ' . $CONFIG['db'][0]['prefix'] . 'system_functions.id_f
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page = (:page)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.active = (:active)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_functions.del = (:nultime)
											');
		$queryRf->bindValue(':page', $datR['id_page'], PDO::PARAM_INT);
		$queryRf->bindValue(':active', 1, PDO::PARAM_INT);
		$queryRf->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}
	
	if($aTokenContent['user']['right_admin'] == 9){
		$queryRf = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page2f2f,
												' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f
												'.$extQuery.'
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files
											
											LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_functions
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f = ' . $CONFIG['db'][0]['prefix'] . 'system_functions.id_f
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page = (:page)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.systemadmin = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_functions.del = (:nultime)
											');
		$queryRf->bindValue(':page', $datR['id_page'], PDO::PARAM_INT);
		$queryRf->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryRf->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}
	
	if($aTokenContent['user']['right_admin'] == 9 && $aTokenContent['user']['right_systemadmin'] == 9){
		$queryRf = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page2f2f,
												' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f
												'.$extQuery.'
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files
											
											LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_functions
												ON ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_f = ' . $CONFIG['db'][0]['prefix'] . 'system_functions.id_f
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page = (:page)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_functions.del = (:nultime)
											');
		$queryRf->bindValue(':page', $datR['id_page'], PDO::PARAM_INT);
		$queryRf->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	}
	
	$queryRf->execute();
	$rowsRf = $queryRf->fetchAll(PDO::FETCH_ASSOC);
	$numRf = $queryRf->rowCount();

	foreach($rowsRf as $datRf){
		$CONFIG_TMP['USER']['pages2functions2files'][$datR['id_page']][$datRf['id_page2f2f']] = array();  
		
		foreach($datRf as $key=>$val){
			$CONFIG_TMP['USER']['pages2functions2files'][$datR['id_page']][$datRf['id_page2f2f']][$key] = $val;
		}
	}
}
###########################################################################
?>