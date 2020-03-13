<?php
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-app.php');
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');


if($varSQL['frame'] == 'first'){
	// create new ID
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_apageid
										FROM ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp 
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_asid = (:id_asid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_tpid = (:id_tpid)
										');
	$query->bindValue(':id_count',$CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$query->bindValue(':id_asid', $varSQL['asid'], PDO::PARAM_INT);
	$query->bindValue(':id_tpid', $varSQL['tpid'], PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();

	if($num == 0){
		$queryI = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assetspages_
											(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
											VALUES
											(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
											');
		$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryI->bindValue(':id_cl', 1, PDO::PARAM_INT);
		$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
		$queryI->bindValue(':create_from', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
		$queryI->execute();
		$varSQL['id_apageid'] = $CONFIG['dbconn'][0]->lastInsertId();
	}else{
		$varSQL['id_apageid'] = $rows[0]['id_apageid'];
	}


	$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp
				(id_count, id_lang, id_dev, id_cl, restricted_all, id_apageid, id_asid, id_tpid, id_bfid, id_etid, id_pcid, id_ppid, page, duration, showframe, create_at, create_from, change_from)
			VALUES
				(:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_apageid, :id_asid, :id_tpid, :id_bfid, :id_etid, :id_pcid, :id_ppid, :page, :duration, :showframe, :now, :create_from, :create_from)
			ON DUPLICATE KEY UPDATE 
				showframe = (:showframe),
				change_from = (:create_from),
				del = (:nultime)
			';
	$queryC = $CONFIG['dbconn'][0]->prepare($qry);
	$queryC->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_cl', 1, PDO::PARAM_INT);
	$queryC->bindValue(':restricted_all', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_apageid', $varSQL['id_apageid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_asid', $varSQL['asid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_tpid', $varSQL['tpid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_bfid', (isset($varSQL['bfid'])) ? $varSQL['bfid'] : 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_etid', (isset($varSQL['etid'])) ? $varSQL['etid'] : 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$queryC->bindValue(':page', 1, PDO::PARAM_INT);
	$queryC->bindValue(':duration', $CONFIG['system']['durationBannerframe'], PDO::PARAM_INT);
	$queryC->bindValue(':showframe', $varSQL['showframe'], PDO::PARAM_INT);
	$queryC->bindValue(':now', $now, PDO::PARAM_STR);
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':create_from', $CONFIG['user']['id_ppid'], PDO::PARAM_INT); 
	$queryC->execute();
	$numC = $queryC->rowCount();
}


if($varSQL['frame'] == 'last'){
	// create new ID
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_apageid
										FROM ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp 
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_asid = (:id_asid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_tpid = (:id_tpid)
										');
	$query->bindValue(':id_count',$CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$query->bindValue(':id_asid', $varSQL['asid'], PDO::PARAM_INT);
	$query->bindValue(':id_tpid', $varSQL['tpid'], PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();

	if($num == 0){
		$queryI = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assetspages_
											(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
											VALUES
											(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
											');
		$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryI->bindValue(':id_cl', 1, PDO::PARAM_INT);
		$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
		$queryI->bindValue(':create_from', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
		$queryI->execute();
		$varSQL['id_apageid'] = $CONFIG['dbconn'][0]->lastInsertId();
	}else{
		$varSQL['id_apageid'] = $rows[0]['id_apageid'];
	}


	$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp
				(id_count, id_lang, id_dev, id_cl, restricted_all, id_apageid, id_asid, id_tpid, id_bfid, id_etid, id_pcid, id_ppid, page, duration, showframe, create_at, create_from, change_from)
			VALUES
				(:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_apageid, :id_asid, :id_tpid, :id_bfid, :id_etid, :id_pcid, :id_ppid, :page, :duration, :showframe, :now, :create_from, :create_from)
			ON DUPLICATE KEY UPDATE 
				showframe = (:showframe),
				change_from = (:create_from),
				del = (:nultime)
			';
	$queryC = $CONFIG['dbconn'][0]->prepare($qry);
	$queryC->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_cl', 1, PDO::PARAM_INT);
	$queryC->bindValue(':restricted_all', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_apageid', $varSQL['id_apageid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_asid', $varSQL['asid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_tpid', $varSQL['tpid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_bfid', (isset($varSQL['bfid'])) ? $varSQL['bfid'] : 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_etid', (isset($varSQL['etid'])) ? $varSQL['etid'] : 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$queryC->bindValue(':page', 3, PDO::PARAM_INT);
	$queryC->bindValue(':duration', $CONFIG['system']['durationBannerframe'], PDO::PARAM_INT);
	$queryC->bindValue(':showframe', $varSQL['showframe'], PDO::PARAM_INT);
	$queryC->bindValue(':now', $now, PDO::PARAM_STR);
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':create_from', $CONFIG['user']['id_ppid'], PDO::PARAM_INT); 
	$queryC->execute();
	$numC = $queryC->rowCount();
}


if($varSQL['frame'] == 'product'){
	$queryPr = $CONFIG['dbconn'][0]->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp SET
											showframe = (:showframe)
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_apid = (:id_apid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_pcid = (:id_pcid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_ppid = (:id_ppid)
										');
	$queryPr->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
	$queryPr->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$queryPr->bindValue(':id_apid', $varSQL['apid'], PDO::PARAM_INT);
	$queryPr->bindValue(':showframe', $varSQL['showframe'], PDO::PARAM_INT);
	$queryPr->execute();
}

?>