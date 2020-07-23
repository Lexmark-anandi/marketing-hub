<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$functionPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'];
$functionFile = 'fu-' . $CONFIG['aModul']['modul_name'] . '-widget-partner-per-country.php';
$functionFileOne2n = 'fu-' . $CONFIG['aModul']['modul_name'] . '-widget-partner-per-country-one2n.php';
$functionFilePre = 'fu-' . $CONFIG['aModul']['modul_name'] . '-widget-partner-per-country-pre.php';
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php');


$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');


$aData = array();


$queryAC = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_caid,
										' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.category,
										' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.datacolor
										
									FROM ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni 

									WHERE ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.active = (:one)
										
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.rank
									');
$queryAC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryAC->bindValue(':nul', 0, PDO::PARAM_INT);
$queryAC->bindValue(':one', 1, PDO::PARAM_INT);
$queryAC->execute();
$rowsAC = $queryAC->fetchAll(PDO::FETCH_ASSOC);
$numAC = $queryAC->rowCount();

$aTmp = array();
$aTmp['label'] = '';
$aTmp['values'] = array();
$aTmp['colors'] = array();

foreach($rowsAC as $rowAC){
	$aTmp['label'] = $rowAC['category'];

	$numAll = 0;
	$queryN = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_asid
											
										FROM ' . $CONFIG['db'][0]['prefix'] . '_assets_uni
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_count
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_lang
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_tempid

										WHERE ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_count <> (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_lang <> (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid = (:id_caid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_count IN (' . implode(',', array_keys($CONFIG['user']['countries'])) . ')
										');
	$queryN->bindValue(':id_caid', $rowAC['id_caid'], PDO::PARAM_INT);
	$queryN->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryN->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryN->execute();
	$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
	$numN = $queryN->rowCount();
	$numAll += $numN;

	$aTmp['values'][0] = $numAll;
	array_push($aTmp['colors'], $rowAC['datacolor']);

	array_push($aData, $aTmp);
	
}




$out = array();
$out['con'] = '<div style="height:100%"><canvas id="canvas_' . $varSQL['id_dashid'] . '_' . md5($varSQL['target']) . '" class="widgetcanvas"></canvas></div>';
$out['data'] = $aData;


//var_dump($out);
echo json_encode($out);;

?>