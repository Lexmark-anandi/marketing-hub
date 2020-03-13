<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$aData = json_decode($varSQL['comp'], true);

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$queryP1e = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc.id_tpeid,
										' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc.create_from
									FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc 
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc.id_tpeid = (:id_tpeid)
									LIMIT 1
									');
$queryP1e->bindValue(':id_tpeid', $varSQL['tpeid'], PDO::PARAM_INT);
$queryP1e->execute();
$rowsP1e = $queryP1e->fetchAll(PDO::FETCH_ASSOC);
$numP1e = $queryP1e->rowCount();


if($CONFIG['user']['specifications'][14] == 8 && $CONFIG['settings']['formCountry'] == 0 && $CONFIG['settings']['formLanguage'] == 0 && $CONFIG['settings']['formDevice'] == 0){
	$aArgsLV = array();
	$aArgsLV['type'] = 'temp';
	$aLocalVersions = localVariationsBuild($aArgsLV);
	
	// delete master version for restricted all access 
	if($numP1e > 0 && $CONFIG['user']['id'] != $rowsP1e[0]['create_from']){
		$key0 = array_search(array(0,0,0), $aLocalVersions);
		unset($aLocalVersions[$key0]); 
	}
	
	$condOrg = '
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_##tab##.id_count = (:id_count)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_##tab##.id_lang = (:id_lang)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_##tab##.id_dev = (:id_dev)
		';
		
	foreach($aLocalVersions as $version){
		$cond = str_replace('##tab##', 'ext', $condOrg);
		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_ext SET
												del = (:now)
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_ext.id_tpeid = (:id_tpeid)
												' . $cond . '
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':id_tpeid', $varSQL['tpeid'], PDO::PARAM_INT);
		$query->bindValue(':id_count', $version[0], PDO::PARAM_INT);
		$query->bindValue(':id_lang', $version[1], PDO::PARAM_INT);
		$query->bindValue(':id_dev', $version[2], PDO::PARAM_INT);
		$query->execute();
		
		
		$cond = str_replace('##tab##', 'loc', $condOrg);
		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc SET
												del = (:now)
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc.id_tpeid = (:id_tpeid)
												' . $cond . '
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':id_tpeid', $varSQL['tpeid'], PDO::PARAM_INT);
		$query->bindValue(':id_count', $version[0], PDO::PARAM_INT);
		$query->bindValue(':id_lang', $version[1], PDO::PARAM_INT);
		$query->bindValue(':id_dev', $version[2], PDO::PARAM_INT);
		$query->execute();
		
		
		$cond = str_replace('##tab##', 'res', $condOrg);
		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_res SET
												del = (:now)
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_res.id_tpeid = (:id_tpeid)
												' . $cond . '
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':id_tpeid', $varSQL['tpeid'], PDO::PARAM_INT);
		$query->bindValue(':id_count', $version[0], PDO::PARAM_INT);
		$query->bindValue(':id_lang', $version[1], PDO::PARAM_INT);
		$query->bindValue(':id_dev', $version[2], PDO::PARAM_INT);
		$query->execute();
		
		
		$cond = str_replace('##tab##', 'uni', $condOrg);
		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni SET
												del = (:now)
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpeid = (:id_tpeid)
												' . $cond . '
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':id_tpeid', $varSQL['tpeid'], PDO::PARAM_INT);
		$query->bindValue(':id_count', $version[0], PDO::PARAM_INT);
		$query->bindValue(':id_lang', $version[1], PDO::PARAM_INT);
		$query->bindValue(':id_dev', $version[2], PDO::PARAM_INT);
		$query->execute();
	}
}else{
	$variation = ($CONFIG['settings']['formCountry'] == 0 && $CONFIG['settings']['formLanguage'] == 0 && $CONFIG['settings']['formDevice'] == 0) ? 'master' : 'local';
	
	$condOrg = '';
	if($variation == 'local'){ 
		$condOrg = '
			AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_##tab##.id_count = (:id_count)
			AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_##tab##.id_lang = (:id_lang)
			AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_##tab##.id_dev = (:id_dev)
			';
	}
	
	
	//$query = $CONFIG['dbconn'][0]->prepare('
	//									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_ SET
	//										del = (:now)
	//									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_.id_tpeid = (:id_tpeid)
	//									LIMIT 1
	//									');
	//$query->bindValue(':now', $now, PDO::PARAM_STR);
	//$query->bindValue(':id_tpeid', $varSQL['tpeid'], PDO::PARAM_INT);
	//$query->execute();
	
	$cond = str_replace('##tab##', 'ext', $condOrg);
	$query = $CONFIG['dbconn'][0]->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_ext SET
											del = (:now)
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_ext.id_tpeid = (:id_tpeid)
											' . $cond . '
										');
	$query->bindValue(':now', $now, PDO::PARAM_STR);
	$query->bindValue(':id_tpeid', $varSQL['tpeid'], PDO::PARAM_INT);
	if($variation == 'local'){
		$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
		$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
		$query->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
	}
	$query->execute();
	
	
	$cond = str_replace('##tab##', 'loc', $condOrg);
	$query = $CONFIG['dbconn'][0]->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc SET
											del = (:now)
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc.id_tpeid = (:id_tpeid)
											' . $cond . '
										');
	$query->bindValue(':now', $now, PDO::PARAM_STR);
	$query->bindValue(':id_tpeid', $varSQL['tpeid'], PDO::PARAM_INT);
	if($variation == 'local'){
		$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
		$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
		$query->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
	}
	$query->execute();
	
	
	$cond = str_replace('##tab##', 'res', $condOrg);
	$query = $CONFIG['dbconn'][0]->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_res SET
											del = (:now)
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_res.id_tpeid = (:id_tpeid)
											' . $cond . '
										');
	$query->bindValue(':now', $now, PDO::PARAM_STR);
	$query->bindValue(':id_tpeid', $varSQL['tpeid'], PDO::PARAM_INT);
	if($variation == 'local'){
		$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
		$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
		$query->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
	}
	$query->execute();
	
	
	$cond = str_replace('##tab##', 'uni', $condOrg);
	$query = $CONFIG['dbconn'][0]->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni SET
											del = (:now)
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpeid = (:id_tpeid)
											' . $cond . '
										');
	$query->bindValue(':now', $now, PDO::PARAM_STR);
	$query->bindValue(':id_tpeid', $varSQL['tpeid'], PDO::PARAM_INT);
	if($variation == 'local'){
		$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
		$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
		$query->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
	}
	$query->execute();
}















echo 'ok';



?>