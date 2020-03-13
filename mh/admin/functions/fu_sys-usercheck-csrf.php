<?php
########################################################
// check csrf token
if(isset($_COOKIE['access'])){
	$aToken = explode('.', $_COOKIE['access']);
	$aTokenHeader = json_decode(base64_decode($aToken[0]), true);
	$aTokenContent = json_decode(base64_decode($aToken[1]), true);
	
	if($aTokenContent['csrf'] != $_SERVER['HTTP_CSRFTOKEN']){
		header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=7');
		exit();
	}
}else{
	header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=1');
	exit();
}


?>