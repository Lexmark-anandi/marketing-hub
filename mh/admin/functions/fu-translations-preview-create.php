<?php
include(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();


$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

//$tid_trans = $CONFIG['page']['id_data'];
//include(__DIR__ . '/fu-templates-preview-create.php');

$queryP = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid,
										' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_campid
									FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni 
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev = (:id_dev)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid = (:id)
									');
$queryP->bindValue(':id_count', 0, PDO::PARAM_INT);
$queryP->bindValue(':id_lang', 0, PDO::PARAM_INT);
$queryP->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryP->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
$queryP->execute(); 
$rowsP = $queryP->fetchAll(PDO::FETCH_ASSOC);
$numP = $queryP->rowCount();

if($rowsP[0]['id_promid'] != 0){
	// create promotion preview
	$tid = $CONFIG['page']['id_data'];
	$pid_trans = $rowsP[0]['id_promid'];
	include(__DIR__ . '/fu-promotions-preview-create.php');
}else if($rowsP[0]['id_campid'] != 0){
	// create campaign preview
	$tid = $CONFIG['page']['id_data'];
	$pid_trans = $rowsP[0]['id_campid'];
	include(__DIR__ . '/fu-campaigns-preview-create.php');
}else{
	#################################################################	
	// create template preview
	$tid_trans = $CONFIG['page']['id_data'];
	include(__DIR__ . '/fu-templates-preview-create.php');
}



?>