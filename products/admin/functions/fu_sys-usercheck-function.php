<?php
#####################################################################################
if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 4, 1) == 9){
	// Check requested syscountry
	if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 3,1) == 9 && !array_key_exists($CONFIG['page']['moduls'][$varSQL['modul']]['activeSysCountry'], $CONFIG['USER']['syscountries'])){
		header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=8a1');
		exit();
	}
	// Check requested syslanguage
	if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 4,1) == 9 && !in_array($CONFIG['page']['moduls'][$varSQL['modul']]['activeSysLanguage'], $CONFIG['USER']['syscountries'][$CONFIG['page']['moduls'][$varSQL['modul']]['activeSysCountry']]['languages'])){
		header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=8b');
		exit();
	}
	// Check requested sysdevice
	if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 5,1) == 9 && !array_key_exists($CONFIG['page']['moduls'][$varSQL['modul']]['activeSysDevice'], $CONFIG['USER']['sysdevices'])){
		header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=8c');
		exit();
	}

	###############
	// Check requested syscountry / form
	if(isset($CONFIG['page']['moduls'][$varSQL['modul']]['activeSysCountryForm'])){
		if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 3,1) == 9 && !array_key_exists($CONFIG['page']['moduls'][$varSQL['modul']]['activeSysCountry'], $CONFIG['USER']['syscountries'])){
			header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=8a2');
			exit();
		}
	}
	// Check requested syslanguage / form
	if(isset($CONFIG['page']['moduls'][$varSQL['modul']]['activeSysLanguageForm'])){
		if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 4,1) == 9 && !in_array($CONFIG['page']['moduls'][$varSQL['modul']]['activeSysLanguage'], $CONFIG['USER']['syscountries'][$CONFIG['page']['moduls'][$varSQL['modul']]['activeSysCountry']]['languages'])){
			header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=8b');
			exit();
		}
	}
	// Check requested sysdevice / form
	if(isset($CONFIG['page']['moduls'][$varSQL['modul']]['activeSysDeviceForm'])){
		if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 5,1) == 9 && !array_key_exists($CONFIG['page']['moduls'][$varSQL['modul']]['activeSysDevice'], $CONFIG['USER']['sysdevices'])){
			header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=8c');
			exit();
		}
	}
}else{
	// Check requested country
	if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 3,1) == 9 && !array_key_exists($CONFIG['page']['moduls'][$varSQL['modul']]['activeCountry'], $CONFIG['USER']['countries'])){
		header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=8a3');
		exit();
	}
	// Check requested language
	if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 4,1) == 9 && !in_array($CONFIG['page']['moduls'][$varSQL['modul']]['activeLanguage'], $CONFIG['USER']['countries'][$CONFIG['page']['moduls'][$varSQL['modul']]['activeCountry']]['languages'])){
		header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=8b');
		exit();
	}
	// Check requested device
	if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 5,1) == 9 && !array_key_exists($CONFIG['page']['moduls'][$varSQL['modul']]['activeDevice'], $CONFIG['USER']['devices'])){
		header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=8c');
		exit();
	}

	##############
	// Check requested country / form
	if(isset($CONFIG['page']['moduls'][$varSQL['modul']]['formCountry'])){
		if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 3,1) == 9 && !array_key_exists($CONFIG['page']['moduls'][$varSQL['modul']]['formCountry'], $CONFIG['USER']['countries'])){
			header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=8a4');
			exit();
		}
	}
	// Check requested language / form
	if(isset($CONFIG['page']['moduls'][$varSQL['modul']]['formLanguage'])){
		if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 4,1) == 9 && !in_array($CONFIG['page']['moduls'][$varSQL['modul']]['formLanguage'], $CONFIG['USER']['countries'][$CONFIG['page']['moduls'][$varSQL['modul']]['activeCountry']]['languages'])){
			header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=8b');
			exit();
		}
	}
	// Check requested device / form
	if(isset($CONFIG['page']['moduls'][$varSQL['modul']]['formDevice'])){
		if(substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 5,1) == 9 && !array_key_exists($CONFIG['page']['moduls'][$varSQL['modul']]['formDevice'], $CONFIG['USER']['devices'])){
			header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=8c');
			exit();
		}
	}
}


#####################################################################################
// Check Client
if(!array_key_exists($CONFIG['USER']['activeClient'], $CONFIG['USER']['clients'])){
	header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=8d');
	exit();
}



#####################################################################################
// Check Function
if(!isset($link) || $link == '') $link = basename($_SERVER['PHP_SELF']);
$pagefunctions = array();
foreach($CONFIG['USER']['pages2functions2files'][$CONFIG['page']['pageId']] as $p2f2f){
	array_push($pagefunctions, $p2f2f['id_page2f2f']);
}

$queryR = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page2f2f
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.filename = (:link)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages2functions2files.id_page2f2f IN ('.implode(',', $pagefunctions).')
									');
$queryR->bindValue(':link', $link, PDO::PARAM_STR);
$queryR->execute();
$rowsR = $queryR->fetchAll(PDO::FETCH_ASSOC);
$numR = $queryR->rowCount();

if($numR == 0){
	header("Location:http://" . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=9');
	exit();
}

?>