<?php
$out = array();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$aArgsLV = array();
$aArgsLV['type'] = 'temp';
$aLocalVersions = localVariationsBuild($aArgsLV);


if($CONFIG['page']['id_data'] == 0){
	include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-translations-read-new.php');
}else{
	
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.formfile
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_caid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid = (:id)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':nul', 0, PDO::PARAM_INT);
	$query->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC); 
	$num = $query->rowCount();
	
	$formfile = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-translations-read-' . $rows[0]['formfile'] . '.php';
	if(file_exists($formfile)) include_once($formfile);
}


echo json_encode($out);

?>