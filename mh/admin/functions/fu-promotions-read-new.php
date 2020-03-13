<?php
$aFields = array();
$aFields['title'] = '';
$aFields['title_transrequired'] = 2;
$aFields['startdate'] = '';
$aFields['enddate'] = '';
$aFields['published_at'] = '';
$aFields['transrequest_at'] = '';
$aFields['bsd_only'] = 2;
$aFields['preview_thumbnail'] = '';

$aArgs = array();
$aArgs['fields'] = array();
$aArgs['usesystem'] = 0; 
$aArgs['useboolean'] = array();


#######################################################
// create new record
#######################################################
foreach($aLocalVersions as $aVersion){
	$outTmp = array();
	$outTmp['id_count'] = strval($aVersion[0]);
	$outTmp['id_lang'] = strval($aVersion[1]);
	$outTmp['id_dev'] = strval($aVersion[2]);
	$outTmp['id_cl'] = strval($CONFIG['activeSettings']['id_clid']);
	$outTmp['identifier'] = '';
	foreach($aFields as $k => $v){
		$outTmp[$k] = $v;
	}
	$outTmp['country'] = array(); 

	$query2 = $CONFIG['dbconn'][0]->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
											(
											id,
											id_count,
											id_lang,
											id_dev,
											id_cl,
											id_uid,
											id_mod,
											modulname,
											data,
											create_at
											)
										VALUES
											(
											:id,
											:id_count,
											:id_lang,
											:id_dev,
											:id_cl,
											:uid,
											:id_mod,
											:modulname,
											:data,
											:create_at
											)
										');
	$query2->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
	$query2->bindValue(':id_count', $outTmp['id_count'], PDO::PARAM_INT);
	$query2->bindValue(':id_lang', $outTmp['id_lang'], PDO::PARAM_INT);
	$query2->bindValue(':id_dev', $outTmp['id_dev'], PDO::PARAM_INT);
	$query2->bindValue(':id_cl', $outTmp['id_cl'], PDO::PARAM_INT);
	$query2->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
	$query2->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
	$query2->bindValue(':modulname', $CONFIG['aModul']['modul_name'], PDO::PARAM_STR);
	$query2->bindValue(':data', json_encode($outTmp), PDO::PARAM_STR);
	$query2->bindValue(':create_at', $now, PDO::PARAM_STR);
	$query2->execute();

	if($aVersion[0] == $CONFIG['settings']['formCountry'] && $aVersion[1] == $CONFIG['settings']['formLanguage'] && $aVersion[2] == $CONFIG['settings']['formDevice']) $out = $outTmp;
}
		
	

?>