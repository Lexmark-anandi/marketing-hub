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
	
	//foreach($rowsAC as $rowAC){
		$numAll = 0;
		$queryN = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_pcid
												
											FROM ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni
												
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_countid = (:count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_langid = (:lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.del = (:nultime)
											GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_pcid
											');
		$queryN->bindValue(':count', $rowC['id_countid'], PDO::PARAM_INT);
		$queryN->bindValue(':lang', $rowC['id_langid'], PDO::PARAM_INT);
		$queryN->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryN->execute();
		$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
		$numN = $queryN->rowCount();
		$numAll += $numN;

//		$queryN = $CONFIG['dbconn'][0]->prepare('
//											SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
//												
//											FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
//											
//											INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_
//												ON ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_countid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count
//													AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_langid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang
//												 	AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_campid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_campid
//													AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.del = (:nultime)
//												
//											WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:count)
//												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:lang)
//												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
//												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid = (:id_caid)
//												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.published_at <> (:nultime)
//											GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
//											');
//		$queryN->bindValue(':count', $rowC['id_countid'], PDO::PARAM_INT);
//		$queryN->bindValue(':lang', $rowC['id_langid'], PDO::PARAM_INT);
//		$queryN->bindValue(':id_caid', $rowAC['id_caid'], PDO::PARAM_INT);
//		$queryN->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//		$queryN->execute();
//		$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
//		$numN = $queryN->rowCount();
//		$numAll += $numN;
//
//		$queryN = $CONFIG['dbconn'][0]->prepare('
//											SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
//												
//											FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
//											
//											INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_
//												ON ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count
//													AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_langid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang
//												 	AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_promid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid
//													AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.del = (:nultime)
//												
//											WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:count)
//												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:lang)
//												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
//												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid = (:id_caid)
//												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.published_at <> (:nultime)
//											GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
//											');
//		$queryN->bindValue(':count', $rowC['id_countid'], PDO::PARAM_INT);
//		$queryN->bindValue(':lang', $rowC['id_langid'], PDO::PARAM_INT);
//		$queryN->bindValue(':id_caid', $rowAC['id_caid'], PDO::PARAM_INT);
//		$queryN->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//		$queryN->execute();
//		$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
//		$numN = $queryN->rowCount();
//		$numAll += $numN;


		$aTmp['values']['Partner'] = $numAll;
		$aTmp['colors']['Partner'] = '#7090cc';
	//}
	array_push($aData, $aTmp);
}











$out = array();
$out['con'] = '<div style="height:100%"><canvas id="canvas_' . $varSQL['id_dashid'] . '_' . md5($varSQL['target']) . '" class="widgetcanvas"></canvas></div>';
$out['data'] = $aData;

echo json_encode($out);;

?>