<?php
#####################################################################################
// check only if no multiple variation will be saved

#####################################################################################
// check rights for system pages
if($CONFIG['aModul']['specifications'][9] == 9){
	if($CONFIG['aModul']['specifications'][0] != 0){
		// Check requested syscountry
		if(!array_key_exists($CONFIG['settings']['selectCountry'], $CONFIG['user']['syscountries'])){ 
			header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=9a');
			exit();
		}
	}
	
	if($CONFIG['aModul']['specifications'][1] != 0){
		// Check requested syslanguage
		if(!in_array($CONFIG['settings']['selectLanguage'], $CONFIG['user']['syscountries'][$CONFIG['settings']['selectCountry']]['languages'])){
			header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=9b');
			exit();
		}
	}
	
	if($CONFIG['aModul']['specifications'][2] != 0){
		// Check requested sysdevice
		if(!array_key_exists($CONFIG['settings']['selectDevice'], $CONFIG['user']['sysdevices'])){
			header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=9c');
			exit();
		}
	}
	
	
	if($CONFIG['aModul']['specifications'][0] != 0){
		// Check requested syscountry / form
		if(!array_key_exists($CONFIG['settings']['formCountry'], $CONFIG['user']['syscountries'])){
			header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=9d');
			exit();
		}
	}
	
	if($CONFIG['aModul']['specifications'][1] != 0){
		// Check requested syslanguage / form
		if(!in_array($CONFIG['settings']['formLanguage'], $CONFIG['user']['syscountries'][$CONFIG['settings']['formCountry']]['languages'])){
			header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=9e');
			exit();
		}
	}
	
	if($CONFIG['aModul']['specifications'][2] != 0){
		// Check requested sysdevice / form
		if(!array_key_exists($CONFIG['settings']['formDevice'], $CONFIG['user']['sysdevices'])){
			header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=9f');
			exit();
		}
	}
}

#####################################################################################
	 
	// check rights for data pages
else if($CONFIG['aModul']['specifications'][9] == 0){
	if($CONFIG['aModul']['specifications'][0] != 0){
		// Check requested country
		if(!array_key_exists($CONFIG['settings']['selectCountry'], $CONFIG['user']['countries'])){
			header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=10a');
			exit();
		}
	}
	
	if($CONFIG['aModul']['specifications'][1] != 0){
		// Check requested language
		if(!in_array($CONFIG['settings']['selectLanguage'], $CONFIG['user']['countries'][$CONFIG['settings']['selectCountry']]['languages'])){
			header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=10b');
			exit();
		}
	}
	
	if($CONFIG['aModul']['specifications'][2] != 0){
		// Check requested device
		if(!array_key_exists($CONFIG['settings']['selectDevice'], $CONFIG['user']['devices'])){
			header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=10c');
			exit();
		}
	}
	
	
	if($CONFIG['aModul']['specifications'][0] != 0){
		// Check requested country / form
		if(!array_key_exists($CONFIG['settings']['formCountry'], $CONFIG['user']['countries'])){
			header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=10d');
			exit();
		}
	}
	
	if($CONFIG['aModul']['specifications'][1] != 0){
		// Check requested language / form
		if(!in_array($CONFIG['settings']['formLanguage'], $CONFIG['user']['countries'][$CONFIG['settings']['formCountry']]['languages'])){
			header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=10e');
			exit();
		}
	}
	
	if($CONFIG['aModul']['specifications'][2] != 0){
		// Check requested device / form
		if(!array_key_exists($CONFIG['settings']['formDevice'], $CONFIG['user']['devices'])){
			header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=10f');
			exit();
		}
	}
}
	
#####################################################################################
// if not system or data
else{
	header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=11');
	exit();
}

?>