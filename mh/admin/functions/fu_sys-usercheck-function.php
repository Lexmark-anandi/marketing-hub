<?php
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-page2modul.php'); 
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-localization.php'); 
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-client.php'); 

#####################################################################################
// Check function
$checkFunc = false;
foreach($CONFIG['aModul']['functions'] as $aFunction){
	if($aFunction['filename'] == $functionFile){
		$checkFunc = true;
		break;
	}
}
if(!$checkFunc == true){
//	header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=13');
//	exit();
}

?>