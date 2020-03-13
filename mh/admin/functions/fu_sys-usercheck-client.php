<?php
#####################################################################################
// Check Client
if(!array_key_exists($CONFIG['activeSettings']['id_clid'], $CONFIG['user']['clients'])){
	header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=8');
	exit();
}

?>