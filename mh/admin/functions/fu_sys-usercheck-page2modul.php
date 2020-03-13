<?php
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-page.php'); 

$aModuls = ($CONFIG['page']['id_mod_parent'] == 0) ? $CONFIG['user']['pages2moduls'][$CONFIG['page']['id_page']]['moduls'] : $CONFIG['user']['childmoduls'][$CONFIG['page']['id_mod_parent']];

#####################################################################################
// Check modul on page
if(!array_key_exists('i_' . $CONFIG['page']['id_mod'], $aModuls)){
	header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=12');
	exit();
}


?>