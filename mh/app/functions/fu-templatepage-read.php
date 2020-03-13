<?php
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-app.php');
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$out = array();
$out['type'] = '';
$out['thumbnails'] = array();
$out['preview'] = array();
//$out['components'] = '';
//$out['aComponents'] = '';
//$out['toolsform'] = '';
//$out['bannerformats'] = '';
$out['configuration'] = array();
$out['configurationform'] = '';
$out['printer'] = array();

$aBannerFiles = array();


####################################################### 
// read template for asset category
#######################################################
if(isset($varSQL['id_tempid']) && $varSQL['id_tempid'] != '' && $varSQL['id_tempid'] != 0 && $varSQL['id_tempid'] != 'undefined'){
	$condProm = '';
	$condCat = '';
	$condPP = '';
	$condAS = '';
	$group = '';
	$order = '';
	if(is_numeric($CONFIG['activeSettings']['id_page'])){
		// config categories
		$condCat = 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid = (:id_caid)';
		$group = $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid';
		$order = $CONFIG['db'][0]['prefix'] . '_templates2countries_.published_at DESC';
	}else if($CONFIG['activeSettings']['id_page'] == 'myassets'){
		// config my assets
		if($varSQL['id_caid'] == 'promotions'){
			$condProm = 'AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid = (:id_promid) ';
			$group = $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_tempid';
			$order = $CONFIG['db'][0]['prefix'] . '_promotions2countries_.published_at DESC';
			
		}else if($varSQL['id_caid'] == 'campaigns'){
			$condProm = 'AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid = (:id_campid) ';
			$group = $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid';
			$order = $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.published_at DESC';
			
		}else{
			$condPP = 'AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_ppid = (:id_ppid)';
			$condAS = 'AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_asid = (:id_asid)';
			$condAS .= 'AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.del = (:nultime)';
			$group = $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_asid';
			$order = $CONFIG['db'][0]['prefix'] . '_assets_tmp.create_at DESC';
		}
	}else if($CONFIG['activeSettings']['id_page'] == 'promotions'){
		// config promotions
		$condProm = 'AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid = (:id_promid) ';
		$group = $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_tempid';
		$order = $CONFIG['db'][0]['prefix'] . '_promotions2countries_.published_at DESC';
		
	}else if($CONFIG['activeSettings']['id_page'] == 'campaigns'){
		// config campaigns
		$condProm = 'AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid = (:id_campid) ';
		$group = $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid';
		$order = $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.published_at DESC';
	}
	
	
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tpid,
											' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_etid,
											' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid,
											' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_kcid,
											' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.file_original,
											' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page,
											' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page_number,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename,
											' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.bannername
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni 
	
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
	
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_
											ON ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_countid = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.published_at < (:now)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.published_at <> (:nultime)
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp
											ON ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
												AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.del = (:nultime)
	
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.file_original = ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid
	
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid = ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_bfid
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = (:id_tempid)
											' . $condProm . '
											' . $condCat . '
											' . $condPP . '
											' . $condAS . '
										GROUP BY CONCAT(' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tpid, "_", ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid, ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page
										');

	if($CONFIG['activeSettings']['id_page'] == 'promotions' || $varSQL['id_caid'] == 'promotions'){
		$query = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tpid,
												' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_etid,
												' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid,
												' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_kcid,
												' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.file_original,
												' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page,
												' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page_number,
												' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid,
												' . $CONFIG['db'][0]['prefix'] . '_templates_uni.title,
												' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.title AS title_promotion,
												' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.title AS title_asset,
												' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filename,
												' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename,
												' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.bannername,
												' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.currency
											FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni 
		
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
												ON ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev = (:id_dev)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
		
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni
												ON ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_dev = (:id_dev)
													AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid = (:id_promid)
		
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_
												ON ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_langid = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_promid = (:id_promid)
													AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.published_at < (:now)
													AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.published_at <> (:nultime)
		
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
												ON ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
											
											LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp
												ON ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
													AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.del = (:nultime)
		
											LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_dev = (:id_dev)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.file_original = ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid
		
											LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni
												ON ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_dev = (:id_dev)
													AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid = ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_bfid
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = (:id_tempid)
												' . $condProm . '
												' . $condCat . '
												' . $condPP . '
												' . $condAS . '
											GROUP BY CONCAT(' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tpid, "_", ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid, ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page
											');
	}

	if($CONFIG['activeSettings']['id_page'] == 'campaigns' || $varSQL['id_caid'] == 'campaigns'){
		$query = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tpid,
												' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_etid,
												' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid,
												' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_kcid,
												' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.file_original,
												' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page,
												' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page_number,
												' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid,
												' . $CONFIG['db'][0]['prefix'] . '_templates_uni.title,
												' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.title AS title_promotion,
												' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.title AS title_asset,
												' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filename,
												' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename,
												' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.bannername,
												' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.currency
											FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni 
		
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
												ON ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev = (:id_dev)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
		
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni
												ON ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_dev = (:id_dev)
													AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid = (:id_campid)
		
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_
												ON ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_countid = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_langid = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_campid = (:id_campid)
													AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.published_at < (:now)
													AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.published_at <> (:nultime)
		
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
												ON ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
											
											LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp
												ON ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
													AND ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.del = (:nultime)
		
											LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_dev = (:id_dev)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.file_original = ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid
		
											LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni
												ON ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_dev = (:id_dev)
													AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid = ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_bfid
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = (:id_tempid)
												' . $condProm . '
												' . $condCat . '
												' . $condPP . '
												' . $condAS . '
											GROUP BY CONCAT(' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tpid, "_", ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid, ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page
											');
	}


	$query->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$query->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':now', $now, PDO::PARAM_STR);
	$query->bindValue(':id_tempid', $varSQL['id_tempid'], PDO::PARAM_INT);
	if($condProm != '') $query->bindValue(':id_campid', $varSQL['id_campid'], PDO::PARAM_INT);
	if($condCat != '') $query->bindValue(':id_caid', $CONFIG['activeSettings']['id_page'], PDO::PARAM_INT);
	if($condPP != '') $query->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	if($condAS != '') $query->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();

	$id_bfid = $rows[0]['id_bfid'];

	if($num > 0){
		$out['configuration'][$rows[0]['id_tpid']]['duration'] = $CONFIG['system']['durationBannerframe'];
		$out['configuration'][$rows[0]['id_tpid']]['showframe'] = 1;

		if($rows[0]['id_kcid'] > 0){
			#################################################
			// for kiado document
			$queryF = $CONFIG['dbconn'][0]->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_mid,
													' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.page_number,
													' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.page_dimension,
													' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename
												FROM ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni 
			
												INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
													ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_dev = (:id_dev)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
														AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_mid = ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid
												
												WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_dev = (:id_dev)
													AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_kcid = (:id_kcid)
												');
			$queryF->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
			$queryF->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
			$queryF->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryF->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$queryF->bindValue(':id_kcid', $rows[0]['id_kcid'], PDO::PARAM_INT);
			$queryF->execute();
			$rowsF = $queryF->fetchAll(PDO::FETCH_ASSOC);
			$numF = $queryF->rowCount();
			
			if($numF == 0){
				$queryF = $CONFIG['dbconn'][0]->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_mid,
														' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.page_number,
														' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.page_dimension,
														' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename
													FROM ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni 
				
													INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
														ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
															AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
															AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_dev = (:id_dev)
															AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
															AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_mid = ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid
													
													WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_count = (:id_count)
														AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_lang = (:id_lang)
														AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_dev = (:id_dev)
														AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
														AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_kcid = (:id_kcid)
													');
				$queryF->bindValue(':id_count', 0, PDO::PARAM_INT);
				$queryF->bindValue(':id_lang', 0, PDO::PARAM_INT);
				$queryF->bindValue(':id_dev', 0, PDO::PARAM_INT);
				$queryF->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$queryF->bindValue(':id_kcid', $rows[0]['id_kcid'], PDO::PARAM_INT);
				$queryF->execute();
				$rowsF = $queryF->fetchAll(PDO::FETCH_ASSOC);
				$numF = $queryF->rowCount();
			}
			
			$out['preview'] = array();
			$out['thumbnails']['na'] = array();
			if($numF > 0){
				$aFilenameOriginal = explode('.', $rowsF[0]['filesys_filename']);
				$filenameOriginalEnd = array_pop($aFilenameOriginal);
				$filenameOriginalBase = implode('.', $aFilenameOriginal);
				$dirTarget = $CONFIG['system']['directoryInstallation'] . 'assetimages/';
				
				// build thumbnails
				$out['thumbnails']['na'] = array();
				for($i=1; $i <= $rowsF[0]['page_number']; $i++){
					$out['thumbnails']['na'][$i] = array('src' => $dirTarget . 'thumbnails/' . $filenameOriginalBase . '-' . $i . '.png', 'tp' => $rows[0]['id_tpid'], 'pageid' => $rows[0]['id_tpid'] . '_' . $i, 'page' => $i, 'pagelabel' => $i);
				}
			
				// build preview page
				$page = $CONFIG['system']['directoryRoot'] . 'assetimages/pictures/' . $filenameOriginalBase . '-' . $varSQL['page'] . '.png';
				if(file_exists($page)){
					$info = getimagesize($page);
					array_push($out['preview'], array('src' => '<img src="' . $dirTarget . 'pictures/' . $filenameOriginalBase . '-' . $varSQL['page'] . '.png" data-width="' . $info[0] . '" data-height="' . $info[1] . '">', 'temp' => $varSQL['id_tempid'], 'tp' => $rows[0]['id_tpid'], 'pageid' => $rows[0]['id_tpid'] . '_' . $varSQL['page'], 'page' => $varSQL['page'], 'pagelabel' => $varSQL['page'], 'bfid' => $rows[0]['id_bfid']));
				}else{
					$out['preview'] = array();
				}
			}
		
		}else if($rows[0]['id_bfid'] > 0){
			#################################################
			// for banner document
			$out['type'] = 'banner';
			$aBannerframes = array();
			$aBannerAnimated = array();
			
			// build thumnails
			$i = 0;
			foreach($rows as $row){
				$queryCp = $CONFIG['dbconn'][0]->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.page,
														' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.duration,
														' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.showframe
													FROM ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp 
													
													WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_count = (:id_count)
														AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_lang = (:id_lang)
														AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_dev = (:id_dev)
														AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_cl IN (0, 1)
														AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_asid = (:id_asid)
														AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_tpid = (:id_tpid)
													');
				$queryCp->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
				$queryCp->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
				$queryCp->bindValue(':id_dev', 0, PDO::PARAM_INT);
				$queryCp->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$queryCp->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
				$queryCp->bindValue(':id_tpid', $row['id_tpid'], PDO::PARAM_INT);
				$queryCp->execute();
				$rowsCp = $queryCp->fetchAll(PDO::FETCH_ASSOC);
				$numCp = $queryCp->rowCount();
				
				$out['configuration'][$row['id_tpid']]['duration'] = ($numCp > 0) ? $rowsCp[0]['duration'] : $CONFIG['system']['durationBannerframe'];
				$out['configuration'][$row['id_tpid']]['showframe'] = ($numCp > 0) ? $rowsCp[0]['showframe'] : 1;


				if(!isset($out['thumbnails'][$row['bannername']])) $out['thumbnails'][$row['bannername']] = array();
				$pagelabel = '';
				if($row['page'] == 1) $pagelabel = $TEXT['firstframe'];
				if($row['page'] == 2) $pagelabel = $TEXT['productframe'];
				if($row['page'] == 3) $pagelabel = $TEXT['lastframe'];
		
				$i++;
				$dirTarget = $CONFIG['system']['directoryInstallation'] . 'media/';
		
				$out['thumbnails'][$row['bannername']][$row['page']] = array('src' => $dirTarget . '' . $row['filesys_filename'], 'tp' => $row['id_tpid'], 'pageid' => $row['id_tpid'] . '_' . $i, 'page' => $row['page'], 'pagelabel' => $pagelabel);
				if($row['page'] == 2 && !in_array($row['id_bfid'], $aBannerAnimated)) array_push($aBannerAnimated, $row['id_bfid']);
		
				// build preview page
				if($row['id_bfid'] == $varSQL['id_bfid']){
					$page = $CONFIG['system']['directoryRoot'] . 'media/' . $row['filesys_filename'] . '';
					if(file_exists($page)){
						if(!in_array($row['page'], $aBannerframes)) array_push($aBannerframes, $row['page']);
						$info = getimagesize($page);
						
						// First frame
						if($row['page'] == 1){
							array_push($out['preview'], array('src' => '<img src="' . $dirTarget . '' . $row['filesys_filename'] . '" data-width="' . $info[0] . '" data-height="' . $info[1] . '">', 'temp' => $varSQL['id_tempid'], 'tp' => $row['id_tpid'], 'pageid' => $row['id_tpid'] . '_' . $row['page'], 'page' => $row['page'], 'pagelabel' => $pagelabel, 'bfid' => $row['id_bfid']));
						}

						// Product frame
						if($row['page'] == 2){
							
							$queryPr = $CONFIG['dbconn'][0]->prepare('
																SELECT 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_apid, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_asid, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_tempid, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_bfid, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_etid, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_tpid, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_pcid, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_ppid, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.rank, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_pid, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.revenue_pid, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.prod_type, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_ptid, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.pn_text, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.mkt_name, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.mkt_paragraph, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.tagline, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.price, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.description_text_25, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.description_text_50, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.description_text_100, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_piid, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.not_lpmd, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.duration, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.showframe, 
																	' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.del
																FROM ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp 
																
																WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_count = (:id_count)
																	AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_lang = (:id_lang)
																	AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_dev = (:id_dev)
																	AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_cl IN (0, 1)
																	AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.del = (:nultime)
																	AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_asid = (:id_asid)
																	AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_bfid = (:id_bfid)
																ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.rank
																');
							$queryPr->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
							$queryPr->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
							$queryPr->bindValue(':id_dev', 0, PDO::PARAM_INT);
							$queryPr->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
							$queryPr->bindValue(':id_bfid', $varSQL['id_bfid'], PDO::PARAM_INT);
							$queryPr->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
							$queryPr->execute();
							$rowsPr = $queryPr->fetchAll(PDO::FETCH_ASSOC);
							$numPr = $queryPr->rowCount();

							array_push($out['preview'], array('src' => '<img src="' . $dirTarget . '' . $row['filesys_filename'] . '" data-width="' . $info[0] . '" data-height="' . $info[1] . '">', 'temp' => $varSQL['id_tempid'], 'tp' => $row['id_tpid'], 'pageid' => $row['id_tpid'] . '_' . $row['page'] . '_0', 'page' => $row['page'], 'pagelabel' => $pagelabel, 'bfid' => $row['id_bfid']));
							if($numPr == 0){
							}else{
								foreach($rowsPr as $rowPr){
									array_push($out['preview'], array('src' => '<img src="' . $dirTarget . '' . $row['filesys_filename'] . '" data-width="' . $info[0] . '" data-height="' . $info[1] . '">', 'temp' => $varSQL['id_tempid'], 'tp' => $row['id_tpid'], 'pageid' => $row['id_tpid'] . '_' . $row['page'] . '_' . $rowPr['id_apid'], 'page' => $row['page'], 'pagelabel' => $pagelabel, 'bfid' => $row['id_bfid']));
									
									$aPr = array();
									$aPr['apid'] = $rowPr['id_apid'];
									foreach($rowPr as $k=>$v){
										$aPr[$k] = $v;
									}
									if(isset($varSQL['id_bfid'])){
										if($rowPr['id_bfid'] == $varSQL['id_bfid']) array_push($out['printer'], $aPr);
									}else{
										if($rowPr['id_bfid'] == $id_bfid) array_push($out['printer'], $aPr);
									}
								}
							}
						}
						
						// Last frame
						if($row['page'] == 3){
							array_push($out['preview'], array('src' => '<img src="' . $dirTarget . '' . $row['filesys_filename'] . '" data-width="' . $info[0] . '" data-height="' . $info[1] . '">', 'temp' => $varSQL['id_tempid'], 'tp' => $row['id_tpid'], 'pageid' => $row['id_tpid'] . '_' . $row['page'], 'page' => $row['page'], 'pagelabel' => $pagelabel, 'bfid' => $row['id_bfid']));
						}

					}else{
						$out['preview'] = array();
					}
				}
			}


			########################################################################################
			include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsApp'] . 'fu-template-read-configuration.php');
			########################################################################################
		
		}else if($rows[0]['id_etid'] > 0){
			#################################################
			// for email document
	
//	kann nicht aufgerufen werden, da kein Thumbnail bei Email Asset vorhanden ist
//			
//			$rows[0]['filesys_filename'] = 'emea_en_pr_6.jpg';
//			
//			// build thumnails
//	//		foreach($rows as $row){
//	//			if(!isset($out['thumbnails'][$rows[0]['bannername']])) $out['thumbnails'][$rows[0]['bannername']] = array();
//	//			$pagelabel = '';
//	//			if($row['page'] == 1) $pagelabel = $TEXT['firstframe'];
//	//			if($row['page'] == 2) $pagelabel = $TEXT['productframe'];
//	//			if($row['page'] == 3) $pagelabel = $TEXT['lastframe'];
//		
//				$dirTarget = $CONFIG['system']['directoryInstallation'] . 'media/';
//		
//				$out['thumbnails']['na'] = array();
//				$out['thumbnails']['na'][1] = array('src' => $dirTarget . '' . $rows[0]['filesys_filename'], 'tp' => $rows[0]['id_tpid'], 'pageid' => $rows[0]['id_tpid'] . '_1', 'page' => 1, 'pagelabel' => '1');
//	//		}
//			
//			// build preview page
//			$firstpage = $CONFIG['system']['directoryRoot'] . 'media/' . $rows[0]['filesys_filename'] . '';
//			if(file_exists($firstpage)){
//				$info = getimagesize($firstpage);
//				array_push($out['preview'], array('src' => '<img src="' . $dirTarget . '' . $rows[0]['filesys_filename'] . '" data-width="' . $info[0] . '" data-height="' . $info[1] . '">', 'temp' => $varSQL['id_tempid'], 'tp' => $rows[0]['id_tpid'], 'pageid' => $rows[0]['id_tpid'] . '_' . $varSQL['page'], 'page' => $varSQL['page'], 'pagelabel' => $varSQL['page'], 'bfid' => $rows[0]['id_bfid']));
//			}else{
//				$out['preview'] = array();
//			}
	
		
		}else if($rows[0]['id_bfid'] == 0 && $rows[0]['id_etid'] == 0 && $rows[0]['file_original'] > 0){
			#################################################
			// for uploaded document
	
			// build thumnails
			$aFilenameOriginal = explode('.', $rows[0]['filesys_filename']);
			$filenameOriginalEnd = array_pop($aFilenameOriginal);
			$filenameOriginalBase = implode('.', $aFilenameOriginal);
			$dirTarget = $CONFIG['system']['directoryInstallation'] . 'assetimages/';
			
			$out['thumbnails']['na'] = array();
			for($i=1; $i <= $rows[0]['page_number']; $i++){
				$out['thumbnails']['na'][$i] = array('src' => $dirTarget . 'thumbnails/' . $filenameOriginalBase . '-' . $i . '.png', 'tp' => $rows[0]['id_tpid'], 'pageid' => $rows[0]['id_tpid'] . '_' . $i, 'page' => $i, 'pagelabel' => $i);
			}
		
			// build page
			$page = $CONFIG['system']['directoryRoot'] . 'assetimages/pictures/' . $filenameOriginalBase . '-' . $varSQL['page'] . '.png';
			if(file_exists($page)){
				$info = getimagesize($page);
				array_push($out['preview'], array('src' => '<img src="' . $dirTarget . 'pictures/' . $filenameOriginalBase . '-' . $varSQL['page'] . '.png" data-width="' . $info[0] . '" data-height="' . $info[1] . '">', 'temp' => $varSQL['id_tempid'], 'tp' => $rows[0]['id_tpid'], 'pageid' => $rows[0]['id_tpid'] . '_' . $varSQL['page'], 'page' => $varSQL['page'], 'pagelabel' => $varSQL['page'], 'bfid' => $rows[0]['id_bfid']));
			}else{
				$out['preview'] = array();
			}
		}
	}
}



echo json_encode($out);

?>