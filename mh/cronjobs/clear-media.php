<?php
include_once(__DIR__ . '/../config-all.php');
include_once(__DIR__ . '/../custom/config-all-custom.php');

include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-connect.php');

getConnection(0); 


 
$aFiles = array();
$queryP = $CONFIG['dbconn'][0]->prepare('
									SELECT DISTINCT(' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename)
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.del = (:nultime)
									');
$queryP->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryP->execute();
$rowsP = $queryP->fetchAll(PDO::FETCH_ASSOC);
$numP = $queryP->rowCount();
foreach($rowsP as $rowP){
	array_push($aFiles, $rowP['filesys_filename']);
	//echo $rowP['filesys_filename'].'<br>';
}
var_dump($aFiles);

$size = 0;
$num = 0;

$alledateien = scandir('../media/');
foreach ($alledateien as $datei) { 
   if(is_file('../media/' . $datei)){
	if(!in_array($datei, $aFiles) && substr_count($datei, '.pdf') > 0){
	   echo $datei."<br />"; 
	   $size += filesize('../media/' . $datei);
	   $num++;
	}
   }
};

echo $size."<br />"; 
echo $num."<br />"; 
?>
