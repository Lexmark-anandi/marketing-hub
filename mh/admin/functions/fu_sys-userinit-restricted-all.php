<?php
########################################################
// Setting for restricted access to countries (alles unter Obernull)

// Default (unrestricted user)
$CONFIG_TMP['user']['restricted_all'] = '0';  

$functionFile = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-restricted-all.php';
if(file_exists($functionFile)){ 
	include_once($functionFile);
}
?>