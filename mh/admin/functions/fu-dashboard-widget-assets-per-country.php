<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$functionPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'];
$functionFile = 'fu-' . $CONFIG['aModul']['modul_name'] . '-widget-assets-per-country.php';
$functionFileOne2n = 'fu-' . $CONFIG['aModul']['modul_name'] . '-widget-assets-per-country-one2n.php';
$functionFilePre = 'fu-' . $CONFIG['aModul']['modul_name'] . '-widget-assets-per-country-pre.php';
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


$queryC = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code AS code_country,
										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code AS code_language
										
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
										
									INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid
										
									INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid

									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang IN (' . implode(',', $CONFIG['user']['count2lang']) . ')
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country, ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
									');
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->execute();
$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
$numC = $queryC->rowCount();

foreach($rowsC as $rowC){
	$aTmp = array();
	$aTmp['label'] = '';
	$aTmp['values'] = array();
	$aTmp['colors'] = array();
	
	$aTmp['label'] = $rowC['code_country'] . ' / '. $rowC['code_language'];
	
	foreach($rowsAC as $rowAC){
		$numAll = 0;
		$queryN = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_asid
												
											FROM ' . $CONFIG['db'][0]['prefix'] . '_assets_uni
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
												ON ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_count
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_lang
												 	AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_tempid

											WHERE ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_count = (:count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_lang = (:lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid = (:id_caid)
											GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_asid
											');
		$queryN->bindValue(':count', $rowC['id_countid'], PDO::PARAM_INT);
		$queryN->bindValue(':lang', $rowC['id_langid'], PDO::PARAM_INT);
		$queryN->bindValue(':id_caid', $rowAC['id_caid'], PDO::PARAM_INT);
		$queryN->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryN->execute();
		$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
		$numN = $queryN->rowCount();
		$numAll += $numN;

		$aTmp['values'][$rowAC['category']] = $numAll;
		$aTmp['colors'][$rowAC['category']] = $rowAC['datacolor'];
	}
	array_push($aData, $aTmp);
}











$out = array();
$out['con'] = '<div style="height:100%"><canvas id="canvas_' . $varSQL['id_dashid'] . '_' . md5($varSQL['target']) . '" class="widgetcanvas"></canvas></div>';
$out['data'] = $aData;

echo json_encode($out);;

?>