<?php
ini_set("display_errors", "off");
ini_set("memory_limit", "512M");
ini_set("max_execution_time", "20000");

include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-localization.php');
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-insert-all.php');
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-products-import-feeds.php');

$CONFIG['import']['pathTmp'] = $CONFIG['system']['pathInclude'] . 'admin/tmp/';
$CONFIG['import']['pathData'] = $CONFIG['import']['pathTmp'] . 'data/';
$CONFIG['import']['pathFeedsdir'] = $CONFIG['system']['pathInclude'] . 'lxpd-feeds/';
$CONFIG['import']['pathFeedsfiles'] = $CONFIG['import']['pathFeedsdir'] . 'files/';
$CONFIG['import']['aDoneFiles'] = array();

$imgUrl = '';

	$dateS = new DateTime();
	$nowS = $dateS->format('Y-m-d-H-i-s');
	$subfolder = 'a'.$nowS;
	mkdir($CONFIG['import']['pathData'] . $subfolder, 0777);
	chmod($CONFIG['import']['pathData'] . $subfolder, 0777);

##################################
######## Download Feeds ##########
//include($CONFIG['system']['pathInclude'] . 'lxpd-feeds/download.php');
##################################


//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid IN (4,42,43) 

$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_import_updates.last_update,
										' . $CONFIG['db'][0]['prefix'] . '_import_updates.last_update_bsd,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code AS count,
										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code AS lang 
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages 
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni 
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid
									
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_import_updates
										ON (' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . '_import_updates.id_countid
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . '_import_updates.id_langid)
										
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.active = (:active)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.active = (:active)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->bindValue(':active', 1, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$arrayFiles = array();
$arrayFilesBSD = array();
foreach($rows as $row){
	if($row['last_update'] == '') $row['last_update'] = '0000-00-00 00:00:00';
	if($row['last_update_bsd'] == '') $row['last_update_bsd'] = '0000-00-00 00:00:00';

	$f = strtoupper($row['count'])."_".strtolower($row['lang'])."_2G_public_";
	$fBSD = strtoupper($row['count'])."_".strtolower($row['lang'])."_2G_BSD_";

	$directory = opendir($CONFIG['import']['pathFeedsfiles']);
	while ($file = readdir($directory)){  
		if($file != "." && $file != ".." && !is_dir($CONFIG['import']['pathFeedsfiles'] . $file) && substr_count($file, ".zip") != 0){
			if (strtolower(substr($file, 0, strlen($f))) == strtolower($f)){
				$fileTmp = str_replace(".zip", "", $file);
				$aFile = explode("_", $fileTmp);
				
				$filetime = $aFile[6].'-'.$CONFIG['system']['months2num'][$aFile[4]].'-'.$aFile[5].' '.$aFile[7].':'.$aFile[8].':00';
				if($row['last_update'] < $filetime){
					$arrayFiles[$file] = $filetime;
				}
			}
			if (strtolower(substr($file, 0, strlen($fBSD))) == strtolower($fBSD)){
				$fileTmp = str_replace(".zip", "", $file);
				$aFile = explode("_", $fileTmp);
				
				$filetime = $aFile[6].'-'.$CONFIG['system']['months2num'][$aFile[4]].'-'.$aFile[5].' '.$aFile[7].':'.$aFile[8].':00';
				if($row['last_update_bsd'] < $filetime){
					$arrayFilesBSD[$file] = $filetime;
				}
			}
		}
	}
	closedir($directory);
}

asort($arrayFiles);
asort($arrayFilesBSD);

#################################################################################



#################################################################################
// build versions for ..._uni
#################################################################################
$CONFIG['page'] = array('moduls');
$CONFIG['page']['moduls'] = array('import');
$CONFIG['page']['moduls']['import'] = array('specifics'=>'990990000', 'formCountry'=>'0', 'formLanguage'=>'0', 'formDevice'=>'0');

$aListLanguagesByCountries = array();
$aListDevices = array();
$aSaveVersionsX = array();

$aListLanguagesByCountries = readLanguagesByCountries('import');
$aListDevices = readDevices('import');
foreach($aListLanguagesByCountries as $id_count => $aListLanguages){
	foreach($aListLanguages as $id_lang){
		foreach($aListDevices as $id_dev => $device){
			array_push($aSaveVersionsX, array((int)$id_count, (int)$id_lang, (int)$id_dev));
		}
	}
}
$CONFIG['saveVersions'] = $aSaveVersionsX;
#################################################################################

	


#################################################################################
// do import
#################################################################################
foreach($arrayFiles as $file => $time){
	$insertAll = '';
	
	$aArgs = array();
	$aArgs['file'] = $file;
	$aArgs['time'] = $time;
	$aArgs['type'] = '';
	$aArgs['saveVer'] = $aSaveVersionsX;
	$aArgs['aListLanguagesByCountries'] = $aListLanguagesByCountries;
	if($file != "") importXML($aArgs);

	if($insertAll != ''){
		$queryfile = $CONFIG['system']['pathInclude'] . 'admin/tmp/query.txt';
		$errorfile = $CONFIG['system']['pathInclude'] . 'admin/tmp/fehler.txt';
		$handle = fopen($queryfile, 'w');
		fwrite($handle, $insertAll);
		system('mysql -h '.$CONFIG['db'][0]['host'].' -u '.$CONFIG['db'][0]['user'].' -p'.$CONFIG['db'][0]['password'].' '.$CONFIG['db'][0]['database'].' < '.$queryfile.' 2>> '.$errorfile.'');
	}
}
foreach($arrayFilesBSD as $file => $time){
	$insertAll = '';
	
	$aArgs = array();
	$aArgs['file'] = $file;  
	$aArgs['time'] = $time;
	$aArgs['type'] = 'bsd';
	$aArgs['saveVer'] = $aSaveVersionsX;
	$aArgs['aListLanguagesByCountries'] = $aListLanguagesByCountries;
	if($file != "") importXML($aArgs);

	if($insertAll != ''){
		$queryfile = $CONFIG['system']['pathInclude'] . 'admin/tmp/query.txt';
		$errorfile = $CONFIG['system']['pathInclude'] . 'admin/tmp/fehler.txt';
		$handle = fopen($queryfile, 'w');
		fwrite($handle, $insertAll);
		system('mysql -h '.$CONFIG['db'][0]['host'].' -u '.$CONFIG['db'][0]['user'].' -p'.$CONFIG['db'][0]['password'].' '.$CONFIG['db'][0]['database'].' < '.$queryfile.' 2>> '.$errorfile.'');
	}
}
#################################################################################



############################
// add local datasets in _uni
$insertAll = '';
include($CONFIG['system']['pathInclude'] . 'admin/functions/fu-products-import-localization.php');

if($insertAll != ''){
	$queryfile = $CONFIG['system']['pathInclude'] . 'admin/tmp/query.txt';
	$errorfile = $CONFIG['system']['pathInclude'] . 'admin/tmp/fehler.txt';
	$handle = fopen($queryfile, 'w');
	fwrite($handle, $insertAll);
	system('mysql -h '.$CONFIG['db'][0]['host'].' -u '.$CONFIG['db'][0]['user'].' -p'.$CONFIG['db'][0]['password'].' '.$CONFIG['db'][0]['database'].' < '.$queryfile.' 2>> '.$errorfile.'');
}
############################



	$dateS = new DateTime();
	$nowS = $dateS->format('Y-m-d-H-i-s');
	$subfolder = 'z'.$nowS;
	mkdir($CONFIG['import']['pathData'] . $subfolder, 0777);
	chmod($CONFIG['import']['pathData'] . $subfolder, 0777);



?>