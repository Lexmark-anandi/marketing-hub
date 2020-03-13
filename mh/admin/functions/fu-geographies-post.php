<?php
$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$aT = array('', 'ext', 'loc', 'uni');
	
	 
	
	
$aCount = array();	
$queryC = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang
									FROM ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni
									 
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid <> (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.del = (:nultime)
									 
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_geoid = (:id_geoid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.del = (:nultime)
									');
$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':id_geoid', $aArgsSave['id_data'], PDO::PARAM_INT);
$queryC->execute();
$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
$numC = $queryC->rowCount();
foreach($rowsC as $rowC){
	array_push($aCount, $rowC['id_count2lang']);
}
	
	
	
	
$queryC = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_user
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_geoid = (:id_geoid)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.del = (:nultime)
									');
$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':id_geoid', $aArgsSave['id_data'], PDO::PARAM_INT);
$queryC->execute();
$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
$numC = $queryC->rowCount();
foreach($rowsC as $rowC){
	$queryG = $CONFIG['dbconn'][0]->prepare('
										DELETE FROM ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid = (:id_uid)
										');
	$queryG->bindValue(':id_uid', $rowC['id_uid'], PDO::PARAM_INT);
	$queryG->execute();
	
	foreach($aCount as $val){
		$queryG = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
											(id_uid, id_count2lang)
											VALUES
											(:id_uid, :id_count2lang)
											');
		$queryG->bindValue(':id_uid', $rowC['id_uid'], PDO::PARAM_INT);
		$queryG->bindValue(':id_count2lang', $val, PDO::PARAM_INT);
		$queryG->execute();
	}
}

?>