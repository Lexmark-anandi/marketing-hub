<?php
include_once(__DIR__ . '/../config-all.php');
include_once(__DIR__ . '/../custom/config-all-custom.php');

include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-connect.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-local-variations.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-insert-all.php');

$CONFIG['user']['id_real'] = 0;
$CONFIG['user']['activeClient'] = 1;
$CONFIG['user']['restricted_all'] = 0;
$CONFIG['user']['specifications'][14] = 9;
$CONFIG['activeSettings']['id_clid'] = 1;
$mediaPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'];

ini_set("display_errors", "on");
ini_set("memory_limit", "512M");
ini_set("max_execution_time", "6000");

getConnection(0); 


$dir = $CONFIG['system']['directoryRoot'] . '/assetimages/thumbnails'; 
$aFiles = scandir($dir); 
foreach ($aFiles as $file) { // Ausgabeschleife
   for($p=1; $p<10; $p++){
	   if(substr_count($file, '-0' . $p . '.png') > 0){
		   rename($dir . '/' . $file, str_replace('-0' . $p . '.png', '-' . $p . '.png', $dir . '/' . $file));
	   }
   }
};



//					for($p=1; $p<10; $p++){
//						$fileSearch = $dirTarget . 'pictures/' . $filenameOriginal . '-0' . $p . '.png';
//						if(file_exists($fileSearch)){
//							rename($fileSearch, str_replace('-0' . $p . '.png', '-' . $p . '.png', $fileSearch));
//						}
//						$fileSearch = $dirTarget . 'thumbnails/' . $filenameOriginal . '-0' . $p . '.png';
//						if(file_exists($fileSearch)){
//							rename($fileSearch, str_replace('-0' . $p . '.png', '-' . $p . '.png', $fileSearch));
//						}
//					}



?>