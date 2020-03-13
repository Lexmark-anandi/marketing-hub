<?php
include_once(__DIR__ . '/fu-templates-components-delete.php');

//include_once(__DIR__ . '/../config-admin.php');
//$varSQL = getPostData();
//
//$aData = json_decode($varSQL['comp'], true); 
//
//$date = new DateTime();
//$now = $date->format('Y-m-d H:i:s');
//$variation = ($CONFIG['settings']['formCountry'] == 0 && $CONFIG['settings']['formLanguage'] == 0 && $CONFIG['settings']['formDevice'] == 0) ? 'master' : 'local';
//
//
//$condOrg = '';
//if($variation == 'local'){ 
//	$condOrg = '
//		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_##tab##.id_count = (:id_count)
//		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_##tab##.id_lang = (:id_lang)
//		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_##tab##.id_dev = (:id_dev)
//		';
//}
//
//
////$query = $CONFIG['dbconn'][0]->prepare('
////									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_ SET
////										del = (:now)
////									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_.id_tpeid = (:id_tpeid)
////									LIMIT 1
////									');
////$query->bindValue(':now', $now, PDO::PARAM_STR);
////$query->bindValue(':id_tpeid', $varSQL['tpeid'], PDO::PARAM_INT);
////$query->execute();
//
//$cond = str_replace('##tab##', 'ext', $condOrg);
//$query = $CONFIG['dbconn'][0]->prepare('
//									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_ext SET
//										del = (:now)
//									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_ext.id_tpeid = (:id_tpeid)
//										' . $cond . '
//									');
//$query->bindValue(':now', $now, PDO::PARAM_STR);
//$query->bindValue(':id_tpeid', $varSQL['tpeid'], PDO::PARAM_INT);
//if($variation == 'local'){
//	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//	$query->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
//}
//$query->execute();
//
//
//$cond = str_replace('##tab##', 'loc', $condOrg);
//$query = $CONFIG['dbconn'][0]->prepare('
//									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc SET
//										del = (:now)
//									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc.id_tpeid = (:id_tpeid)
//										' . $cond . '
//									');
//$query->bindValue(':now', $now, PDO::PARAM_STR);
//$query->bindValue(':id_tpeid', $varSQL['tpeid'], PDO::PARAM_INT);
//if($variation == 'local'){
//	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//	$query->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
//}
//$query->execute();
//
//
//$cond = str_replace('##tab##', 'res', $condOrg);
//$query = $CONFIG['dbconn'][0]->prepare('
//									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_res SET
//										del = (:now)
//									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_res.id_tpeid = (:id_tpeid)
//										' . $cond . '
//									');
//$query->bindValue(':now', $now, PDO::PARAM_STR);
//$query->bindValue(':id_tpeid', $varSQL['tpeid'], PDO::PARAM_INT);
//if($variation == 'local'){
//	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//	$query->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
//}
//$query->execute();
//
//
//$cond = str_replace('##tab##', 'uni', $condOrg);
//$query = $CONFIG['dbconn'][0]->prepare('
//									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni SET
//										del = (:now)
//									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpeid = (:id_tpeid)
//										' . $cond . '
//									');
//$query->bindValue(':now', $now, PDO::PARAM_STR);
//$query->bindValue(':id_tpeid', $varSQL['tpeid'], PDO::PARAM_INT);
//if($variation == 'local'){
//	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//	$query->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
//}
//$query->execute();
//
//
//
//
//
//
//
//
//
//echo 'ok';



?>