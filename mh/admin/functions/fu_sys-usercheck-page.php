<?php
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-csrf.php'); 

########################################################
// check rights for requested page
if(!in_array($CONFIG['activeSettings']['id_page'], $CONFIG['user']['pages'])){
	header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=6');
	exit();
}





?>