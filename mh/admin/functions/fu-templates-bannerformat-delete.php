<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');


$aArgsDelete = array();
$aArgsDelete['id_data'] = $varSQL['bfid'];
$aArgsDelete['table'] = $CONFIG['db'][0]['prefix'] . '_bannerformats_';
$aArgsDelete['suffix'] = 1;
$aArgsDelete['primarykey'] = 'id_bfid';
deleteRecord($aArgsDelete);

	
$aTP = array();
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_tpid
									FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc 
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_bfid = (:id_bfid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_lang = (:id_lang)
									');
$query->bindValue(':id_bfid', $varSQL['bfid'], PDO::PARAM_INT);
$query->bindValue(':id_count', 0, PDO::PARAM_INT);
$query->bindValue(':id_lang', 0, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	array_push($aTP, $row['id_tpid']);
}


$aArgsDelete = array();
$aArgsDelete['id_data'] = $varSQL['bfid'];
$aArgsDelete['table'] = $CONFIG['db'][0]['prefix'] . '_templatespages_';
$aArgsDelete['suffix'] = 1;
$aArgsDelete['primarykey'] = 'id_bfid';
deleteRecord($aArgsDelete);


foreach($aTP as $tpid){
	$aArgsDelete = array();
	$aArgsDelete['id_data'] = $tpid;
	$aArgsDelete['table'] = $CONFIG['db'][0]['prefix'] . '_templatespageselements_';
	$aArgsDelete['suffix'] = 1;
	$aArgsDelete['primarykey'] = 'id_tpid';
	deleteRecord($aArgsDelete);
}



echo json_encode($aTP);
?>