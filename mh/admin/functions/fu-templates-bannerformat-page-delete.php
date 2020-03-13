<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');



$aArgsDelete = array();
$aArgsDelete['id_data'] = $varSQL['tpid'];
$aArgsDelete['table'] = $CONFIG['db'][0]['prefix'] . '_templatespages_';
$aArgsDelete['suffix'] = 1;
$aArgsDelete['primarykey'] = 'id_tpid';
deleteRecord($aArgsDelete);


$aArgsDelete = array();
$aArgsDelete['id_data'] = $varSQL['tpid'];
$aArgsDelete['table'] = $CONFIG['db'][0]['prefix'] . '_templatespageselements_';
$aArgsDelete['suffix'] = 1;
$aArgsDelete['primarykey'] = 'id_tpid';
deleteRecord($aArgsDelete);




//#####################################################################
//// update tempdata
//#####################################################################
//$out = array();
//$queryTd = $CONFIG['dbconn'][0]->prepare('
//									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl,
//										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data
//									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
//									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
//									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count, ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang, ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev
//									');
//$queryTd->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
//$queryTd->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
//$queryTd->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
//$queryTd->execute();
//$rowsTd = $queryTd->fetchAll(PDO::FETCH_ASSOC);
//$numTd = $queryTd->rowCount();
//
//foreach($rowsTd as $rowTd){
//	$aTemddata = json_decode($rowTd['data'], true);
//	$aComp = json_decode($aTemddata['components'], true);
//	
//	$key = 'page_' . $varSQL['tpid'] . '_1';
//	if(isset($aComp[$key])) unset($aComp[$key]);
//	$aTemddata['components'] = json_encode($aComp);
//	
//	$queryTd2 = $CONFIG['dbconn'][0]->prepare('
//										UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata SET
//											data = (:data)
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:id_count)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:id_lang)
//										LIMIT 1
//										');
//	$queryTd2->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
//	$queryTd2->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
//	$queryTd2->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
//	$queryTd2->bindValue(':id_count', $rowTd['id_count'], PDO::PARAM_INT);
//	$queryTd2->bindValue(':id_lang', $rowTd['id_lang'], PDO::PARAM_INT);
//	$queryTd2->bindValue(':data', json_encode($aTemddata), PDO::PARAM_INT);
//	$queryTd2->execute();
//	$numTd2 = $queryTd2->rowCount();
//	
//	if($CONFIG['settings']['formCountry'] == $rowTd['id_count'] && $CONFIG['settings']['formLanguage'] == $rowTd['id_lang'] && $CONFIG['settings']['formDevice'] == $rowTd['id_dev']){
//		$out = $aComp;
//	}
//}
//
//
//
//echo json_encode($out);
?>