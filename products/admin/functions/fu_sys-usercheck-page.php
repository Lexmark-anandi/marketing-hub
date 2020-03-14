<?php
########################################################
// check rights for requested page
$queryR = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page IN (' . implode(',', $CONFIG['USER']['pages']). ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page = (:pageid)
									');
$queryR->bindValue(':pageid', $CONFIG['page']['pageId'], PDO::PARAM_INT);
$queryR->execute();
$rowsR = $queryR->fetchAll(PDO::FETCH_ASSOC);
$numR = $queryR->rowCount();

if($numR == 0){
	header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=6');
	exit();
}



########################################################
// check csrf token
if(isset($_COOKIE['access'])){
	$aToken = explode('.', $_COOKIE['access']);
	$aTokenHeader = json_decode(base64_decode($aToken[0]), true);
	$aTokenContent = json_decode(base64_decode($aToken[1]), true);
	
	if($aTokenContent['csrf'] != $_SERVER['HTTP_CSRFTOKEN']){
		header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=7');
		exit();
	}
}else{
	header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=1');
	exit();
}


?>