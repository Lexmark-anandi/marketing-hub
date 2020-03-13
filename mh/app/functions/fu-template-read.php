<?php
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-app.php');
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$out = array();
$out['type'] = '';
$out['title'] = '';
$out['id_asid'] = 0;
$out['thumbnails'] = array();
$out['preview'] = array();
$out['components'] = array();
//$out['aComponents'] = '';
$out['toolsform'] = '';
$out['configuration'] = array();
$out['configurationform'] = '';
$out['printer'] = array();
//$out['bannerformats'] = '';

$aBannerFiles = array();


if($varSQL['id_asid'] == 0 || $varSQL['id_asid'] == ''){ 
	$queryI = $CONFIG['dbconn'][0]->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assets_
										(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
										VALUES
										(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
										');
	$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryI->bindValue(':id_cl', 1, PDO::PARAM_INT);
	$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
	$queryI->bindValue(':create_from', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$queryI->execute();
	$out['id_asid'] = $CONFIG['dbconn'][0]->lastInsertId();
	
	$queryCo = $CONFIG['dbconn'][0]->prepare('SELECT
												id_count, id_lang, id_dev, id_cl, restricted_all, id_promid, id_campid, id_tempid, title, components, id_caid
												FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
												WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid = (:id_tempid)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
										');
	$queryCo->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$queryCo->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$queryCo->bindValue(':id_tempid', $varSQL['id_tempid'], PDO::PARAM_INT);
	$queryCo->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryCo->execute();
	$rowsCo = $queryCo->fetchAll(PDO::FETCH_ASSOC);
	$numCo = $queryCo->rowCount();
	
	foreach($rowsCo as $rowCo){
		$queryCo2 = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp
											(id_asid, id_count, id_lang, id_dev, id_cl, restricted_all, id_promid, id_campid, id_tempid, id_pcid, id_ppid, title, components)
											VALUES
											(:id_asid, :id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_promid, :id_campid, :id_tempid, :id_pcid, :id_ppid, :title, :components)
											');
		$queryCo2->bindValue(':id_asid', $out['id_asid'], PDO::PARAM_INT);
		$queryCo2->bindValue(':id_count', $rowCo['id_count'], PDO::PARAM_INT);
		$queryCo2->bindValue(':id_lang', $rowCo['id_lang'], PDO::PARAM_INT);
		$queryCo2->bindValue(':id_dev', $rowCo['id_dev'], PDO::PARAM_INT);
		$queryCo2->bindValue(':id_cl', $rowCo['id_cl'], PDO::PARAM_INT);
		$queryCo2->bindValue(':restricted_all', $rowCo['restricted_all'], PDO::PARAM_INT);
		$queryCo2->bindValue(':id_promid', $rowCo['id_promid'], PDO::PARAM_INT);
		$queryCo2->bindValue(':id_campid', $rowCo['id_campid'], PDO::PARAM_INT);
		$queryCo2->bindValue(':id_tempid', $rowCo['id_tempid'], PDO::PARAM_INT);
		$queryCo2->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
		$queryCo2->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
		$queryCo2->bindValue(':title', $rowCo['title'], PDO::PARAM_STR);
		$queryCo2->bindValue(':components', $rowCo['components'], PDO::PARAM_STR);
		$queryCo2->execute();
	
	
		if($rowCo['id_caid'] == 1){
			$queryCoP = $CONFIG['dbconn'][0]->prepare('SELECT
														id_count, id_lang, id_dev, id_cl, restricted_all, id_tpid, id_bfid, id_etid, page
														FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni
														WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_count = (:id_count)
															AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_lang = (:id_lang)
															AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = (:id_tempid)
															AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.del = (:nultime)
															AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page IN (1,3)
												');
			$queryCoP->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
			$queryCoP->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
			$queryCoP->bindValue(':id_tempid', $rowCo['id_tempid'], PDO::PARAM_INT);
			$queryCoP->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$queryCoP->execute();
			$rowsCoP = $queryCoP->fetchAll(PDO::FETCH_ASSOC);
			$numCoP = $queryCoP->rowCount();
	
			foreach($rowsCoP as $rowCoP){
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
				$out['id_apageid'] = $CONFIG['dbconn'][0]->lastInsertId();
		
				$queryCoP2 = $CONFIG['dbconn'][0]->prepare('
													INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp
													(id_apageid, id_count, id_lang, id_dev, id_cl, restricted_all, id_asid, id_tpid, id_bfid, id_etid, id_pcid, id_ppid, page, duration, showframe)
													VALUES
													(:id_apageid, :id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_asid, :id_tpid, :id_bfid, :id_etid, :id_pcid, :id_ppid, :page, :duration, :showframe)
													');
				$queryCoP2->bindValue(':id_apageid', $out['id_apageid'], PDO::PARAM_INT);
				$queryCoP2->bindValue(':id_count', $rowCoP['id_count'], PDO::PARAM_INT);
				$queryCoP2->bindValue(':id_lang', $rowCoP['id_lang'], PDO::PARAM_INT);
				$queryCoP2->bindValue(':id_dev', $rowCoP['id_dev'], PDO::PARAM_INT);
				$queryCoP2->bindValue(':id_cl', $rowCoP['id_cl'], PDO::PARAM_INT);
				$queryCoP2->bindValue(':restricted_all', $rowCoP['restricted_all'], PDO::PARAM_INT);
				$queryCoP2->bindValue(':id_asid', $out['id_asid'], PDO::PARAM_INT);
				$queryCoP2->bindValue(':id_tpid', $rowCoP['id_tpid'], PDO::PARAM_INT);
				$queryCoP2->bindValue(':id_bfid', $rowCoP['id_bfid'], PDO::PARAM_INT);
				$queryCoP2->bindValue(':id_etid', $rowCoP['id_etid'], PDO::PARAM_INT);
				$queryCoP2->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
				$queryCoP2->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
				$queryCoP2->bindValue(':page', $rowCoP['page'], PDO::PARAM_INT);
				$queryCoP2->bindValue(':duration', $CONFIG['system']['durationBannerframe'], PDO::PARAM_INT);
				$queryCoP2->bindValue(':showframe', 1, PDO::PARAM_INT);
				$queryCoP2->execute();
			}
		}
		
	
		$queryCoP = $CONFIG['dbconn'][0]->prepare('SELECT
													id_count, id_lang, id_dev, id_cl, restricted_all, id_tpeid, content, content_add
													FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni
													WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_count = (:id_count)
														AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_lang = (:id_lang)
														AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tempid = (:id_tempid)
														AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.del = (:nultime)
											');
		$queryCoP->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
		$queryCoP->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
		$queryCoP->bindValue(':id_tempid', $rowCo['id_tempid'], PDO::PARAM_INT);
		$queryCoP->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryCoP->execute();
		$rowsCoP = $queryCoP->fetchAll(PDO::FETCH_ASSOC);
		$numCoP = $queryCoP->rowCount();
	
		foreach($rowsCoP as $rowCoP){
			$queryI = $CONFIG['dbconn'][0]->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_
												(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
												VALUES
												(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
												');
			$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
			$queryI->bindValue(':id_cl', 1, PDO::PARAM_INT);
			$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
			$queryI->bindValue(':create_from', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
			$queryI->execute();
			$out['id_apeid'] = $CONFIG['dbconn'][0]->lastInsertId();
	
			$queryCoP2 = $CONFIG['dbconn'][0]->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp
												(id_apeid, id_count, id_lang, id_dev, id_cl, restricted_all, id_asid, id_tempid, id_tpeid, id_pcid, id_ppid, content, content_add)
												VALUES
												(:id_apeid, :id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_asid, :id_tempid, :id_tpeid, :id_pcid, :id_ppid, :content, :content_add)
												');
			$queryCoP2->bindValue(':id_apeid', $out['id_apeid'], PDO::PARAM_INT);
			$queryCoP2->bindValue(':id_count', $rowCoP['id_count'], PDO::PARAM_INT);
			$queryCoP2->bindValue(':id_lang', $rowCoP['id_lang'], PDO::PARAM_INT);
			$queryCoP2->bindValue(':id_dev', $rowCoP['id_dev'], PDO::PARAM_INT);
			$queryCoP2->bindValue(':id_cl', $rowCoP['id_cl'], PDO::PARAM_INT);
			$queryCoP2->bindValue(':restricted_all', $rowCoP['restricted_all'], PDO::PARAM_INT);
			$queryCoP2->bindValue(':id_asid', $out['id_asid'], PDO::PARAM_INT);
			$queryCoP2->bindValue(':id_tempid', $rowCo['id_tempid'], PDO::PARAM_INT);
			$queryCoP2->bindValue(':id_tpeid', $rowCoP['id_tpeid'], PDO::PARAM_INT);
			$queryCoP2->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
			$queryCoP2->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
			$queryCoP2->bindValue(':content', $rowCoP['content'], PDO::PARAM_STR);
			$queryCoP2->bindValue(':content_add', $rowCoP['content_add'], PDO::PARAM_STR);
			$queryCoP2->execute();
		}
	}

#############################################################################################
}else{
#############################################################################################
	
	$out['id_asid'] = $varSQL['id_asid'];
	
	#############################################################################################
	#############################################################################################
	// copy data to tmp
	#############################################################################################
	if($varSQL['id_promid'] == 0 && $varSQL['id_campid'] == 0){
		$queryCo = $CONFIG['dbconn'][0]->prepare('
											DELETE 
											FROM ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.id_ppid = (:id_ppid)
											');
		$queryCo->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
		$queryCo->execute();
	
		$queryCo = $CONFIG['dbconn'][0]->prepare('
											DELETE 
											FROM ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp.id_ppid = (:id_ppid)
											');
		$queryCo->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
		$queryCo->execute();
	
		$queryCo = $CONFIG['dbconn'][0]->prepare('
											DELETE 
											FROM ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_ppid = (:id_ppid)
											');
		$queryCo->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
		$queryCo->execute();
	
		$queryCo = $CONFIG['dbconn'][0]->prepare('
											DELETE 
											FROM ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_ppid = (:id_ppid)
											');
		$queryCo->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
		$queryCo->execute();
		
		
		$queryCo = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp
											(id_asid, id_count, id_lang, id_dev, id_cl, restricted_all, id_promid, id_campid, id_tempid, id_pcid, id_ppid, title, components, thumbnail)
											SELECT
											id_asid, id_count, id_lang, id_dev, id_cl, restricted_all, id_promid, id_campid, id_tempid, id_pcid, id_ppid, title, components, thumbnail
											FROM ' . $CONFIG['db'][0]['prefix'] . '_assets_uni
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_asid = (:id_asid)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.del = (:nultime)
											');
		$queryCo->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
		$queryCo->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
		$queryCo->bindValue(':id_asid', $out['id_asid'], PDO::PARAM_INT);
		$queryCo->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryCo->execute();
	
		$queryCo = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assetspages_tmp
											(id_apageid, id_count, id_lang, id_dev, id_cl, restricted_all, id_asid, id_tpid, id_bfid, id_etid, id_pcid, id_ppid, page, duration, showframe)
											SELECT
											id_apageid, id_count, id_lang, id_dev, id_cl, restricted_all, id_asid, id_tpid, id_bfid, id_etid, id_pcid, id_ppid, page, duration, showframe
											FROM ' . $CONFIG['db'][0]['prefix'] . '_assetspages_uni
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetspages_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_uni.id_asid = (:id_asid)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_uni.del = (:nultime)
											');
		$queryCo->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
		$queryCo->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
		$queryCo->bindValue(':id_asid', $out['id_asid'], PDO::PARAM_INT);
		$queryCo->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryCo->execute();
	
		$queryCo = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp
											(id_apid, id_count, id_lang, id_dev, id_cl, restricted_all, id_asid, id_tempid, id_bfid, id_etid, id_tpid, id_pcid, id_ppid, rank, id_pid, revenue_pid, prod_type, id_ptid, pn_text, mkt_name, mkt_paragraph, tagline, price, description_text_25, description_text_50, description_text_100, image, id_piid, not_lpmd, content_add, duration, showframe)
											SELECT
											id_apid, id_count, id_lang, id_dev, id_cl, restricted_all, id_asid, id_tempid, id_bfid, id_etid, id_tpid, id_pcid, id_ppid, rank, id_pid, revenue_pid, prod_type, id_ptid, pn_text, mkt_name, mkt_paragraph, tagline, price, description_text_25, description_text_50, description_text_100, image, id_piid, not_lpmd, content_add, duration, showframe
											FROM ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_asid = (:id_asid)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.del = (:nultime)
											');
		$queryCo->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
		$queryCo->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
		$queryCo->bindValue(':id_asid', $out['id_asid'], PDO::PARAM_INT);
		$queryCo->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryCo->execute();
	
		$queryCo = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp
											(id_apeid, id_count, id_lang, id_dev, id_cl, restricted_all, id_asid, id_tempid, id_tpeid, id_pid, id_pcid, id_ppid, content)
											SELECT
											id_apeid, id_count, id_lang, id_dev, id_cl, restricted_all, id_asid, id_tempid, id_tpeid, id_pid, id_pcid, id_ppid, content
											FROM ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_asid = (:id_asid)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.del = (:nultime)
											');
		$queryCo->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
		$queryCo->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
		$queryCo->bindValue(':id_asid', $out['id_asid'], PDO::PARAM_INT);
		$queryCo->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryCo->execute();
	}
	#############################################################################################
	#############################################################################################
}


#######################################################
// read template for asset category
#######################################################
//if(is_numeric($CONFIG['activeSettings']['id_page']) || $CONFIG['activeSettings']['id_page'] == 'myassets'){
if(isset($varSQL['id_tempid']) && $varSQL['id_tempid'] != '' && $varSQL['id_tempid'] != 0 && $varSQL['id_tempid'] != 'undefined'){
	$condProm = '';
	$condCamp = '';
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
			$group = $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid';
			$order = $CONFIG['db'][0]['prefix'] . '_promotions2countries_.published_at DESC';
			
		}else if($varSQL['id_caid'] == 'campaigns'){
			$condCamp = 'AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid = (:id_campid) ';
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
		$group = $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid';
		$order = $CONFIG['db'][0]['prefix'] . '_promotions2countries_.published_at DESC';
		
	}else if($CONFIG['activeSettings']['id_page'] == 'campaigns'){
		// config campaigns
		$condCamp = 'AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid = (:id_campid) ';
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
											' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid,
											' . $CONFIG['db'][0]['prefix'] . '_templates_uni.title,
											' . $CONFIG['db'][0]['prefix'] . '_assets_tmp.title AS title_asset,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filename,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename,
											' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.bannername,
											' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.width,
											' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.height,
											' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.currency
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni 
	
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
	
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_
											ON ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_countid = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_langid = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.published_at < (:now)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.published_at <> (:nultime)
	
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
										
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
												' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.width,
												' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.height,
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
												' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.width,
												' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.height,
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
												' . $condCamp . '
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
	$query->bindValue(':id_caid', $varSQL['id_page'], PDO::PARAM_INT);
	if($condProm != '') $query->bindValue(':id_promid', $varSQL['id_promid'], PDO::PARAM_INT);
	if($condCamp != '') $query->bindValue(':id_campid', $varSQL['id_campid'], PDO::PARAM_INT);
	if($condCat != '') $query->bindValue(':id_caid', $CONFIG['activeSettings']['id_page'], PDO::PARAM_INT);
	if($condPP != '') $query->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	if($condAS != '') $query->bindValue(':id_asid', $out['id_asid'], PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();

	if($rows[0]['id_caid'] == 1 && !isset($varSQL['id_bfid'])){
		//set id_bfid for first bannerformat
		$varSQL['id_bfid'] = $rows[0]['id_bfid'];
	}

	$id_acid = 0;
	$id_bfid = $rows[0]['id_bfid'];
	
	if($num > 0){
		$id_acid = $rows[0]['id_caid'];
		$out['title'] = ($rows[0]['title_asset'] != '') ? $rows[0]['title_asset'] : $rows[0]['title'];
		$out['configuration'][$rows[0]['id_tpid']]['duration'] = $CONFIG['system']['durationBannerframe'];
		$out['configuration'][$rows[0]['id_tpid']]['showframe'] = 1;
		
		if($rows[0]['id_bfid'] > 0){
			foreach($rows as $row){
				if(!array_key_exists($row['id_bfid'], $aBannerFiles)) $aBannerFiles[$row['id_bfid']] = array();
				if(!array_key_exists($row['page'], $aBannerFiles[$row['id_bfid']])){
					$aBannerFiles[$row['id_bfid']][$row['page']] = array();
					$aBannerFiles[$row['id_bfid']][$row['page']]['tpid'] = $row['id_tpid'];
					$aBannerFiles[$row['id_bfid']][$row['page']]['mid'] = $row['file_original'];
					$aBannerFiles[$row['id_bfid']][$row['page']]['filename'] = $row['filename'];
					$aBannerFiles[$row['id_bfid']][$row['page']]['filesys_filename'] = $row['filesys_filename'];
				}
			}
		}
		
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
			
			if($numF > 0){
				$aFilenameOriginal = explode('.', $rowsF[0]['filesys_filename']);
				$filenameOriginalEnd = array_pop($aFilenameOriginal);
				$filenameOriginalBase = implode('.', $aFilenameOriginal);
				$dirTarget = $CONFIG['system']['directoryInstallation'] . 'assetimages/';
				
				// build thumnails
				$out['thumbnails']['na'] = array();
				for($i=1; $i <= $rowsF[0]['page_number']; $i++){
					$out['thumbnails']['na'][$i] = array('src' => $dirTarget . 'thumbnails/' . $filenameOriginalBase . '-' . $i . '.png', 'tp' => $rows[0]['id_tpid'], 'pageid' => $rows[0]['id_tpid'] . '_' . $i, 'page' => $i, 'pagelabel' => $i, 'bfid' => '0');
				}
			
				// build preview page
				$firstpage = $CONFIG['system']['directoryRoot'] . 'assetimages/pictures/' . $filenameOriginalBase . '-1.png';
				if(file_exists($firstpage)){
					$info = getimagesize($firstpage);
					array_push($out['preview'], array('src' => '<img src="' . $dirTarget . 'pictures/' . $filenameOriginalBase . '-1.png" data-width="' . $info[0] . '" data-height="' . $info[1] . '">', 'temp' => $varSQL['id_tempid'], 'tp' => $rows[0]['id_tpid'], 'pageid' => $rows[0]['id_tpid'] . '_1', 'page' => '1', 'pagelabel' => '1', 'bfid' => $rows[0]['id_bfid']));
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
				$queryCp->bindValue(':id_asid', $out['id_asid'], PDO::PARAM_INT);
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
		
				$dirTarget = $CONFIG['system']['directoryInstallation'] . 'media/';
		
				$out['thumbnails'][$row['bannername']][$row['page']] = array('src' => $dirTarget . '' . $row['filesys_filename'], 'tp' => $row['id_tpid'], 'bfid' => $row['id_bfid'], 'pageid' => $row['id_tpid'] . '_' . $row['page'], 'page' => $row['page'], 'dimension' => '(' . $row['width'] . 'x' . $row['height'] . ')', 'pagelabel' => $pagelabel);
				if($row['page'] == 2 && !in_array($row['id_bfid'], $aBannerAnimated)) array_push($aBannerAnimated, $row['id_bfid']);
				
				if($row['id_bfid'] == $id_bfid){
					// build preview pages
					$firstpage = $CONFIG['system']['directoryRoot'] . 'media/' . $row['filesys_filename'] . '';
					if(file_exists($firstpage)){
						if(!in_array($row['page'], $aBannerframes)) array_push($aBannerframes, $row['page']);
						$info = getimagesize($firstpage);
						
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
							$queryPr->bindValue(':id_asid', $out['id_asid'], PDO::PARAM_INT);
							$queryPr->bindValue(':id_bfid', $id_bfid, PDO::PARAM_INT);
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
									if($rowPr['id_bfid'] == $id_bfid) array_push($out['printer'], $aPr);
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
			$out['type'] = 'email';

			$queryF = $CONFIG['dbconn'][0]->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.css,
													' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.css_frontend,
													' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.html,
													' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.products_div
												FROM ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni 
												
												WHERE ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_count = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_lang = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_dev = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_etid = (:id_etid)
												');
//			$queryF->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//			$queryF->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//			$queryF->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
			$queryF->bindValue(':nul', 0, PDO::PARAM_INT);
			$queryF->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$queryF->bindValue(':id_etid', $rows[0]['id_etid'], PDO::PARAM_INT);
			$queryF->execute();
			$rowsF = $queryF->fetchAll(PDO::FETCH_ASSOC);
			$numF = $queryF->rowCount();
			
		
			$out['thumbnails']['na'] = array();
			$out['thumbnails']['na']['1'] = array('src' => '', 'tp' => $rows[0]['id_tpid'], 'pageid' => $rows[0]['id_tpid'] . '_1', 'page' => '1', 'pagelabel' => '1', 'bfid' => '0', 'etid' => $rows[0]['id_etid']);

			$html = $rowsF[0]['html'];
			$replace = '<div class="editPreviewInner" data-tempid="' . $varSQL['id_tempid'] . '" data-page="2" data-tp="' . $rows[0]['id_tpid'] . '" data-pageid="' . $rows[0]['id_tpid'] . '_2_0">' . $rowsF[0]['products_div'] . '</div>';






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
													AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_tempid = (:id_tempid)
												ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.rank
												');
			$queryPr->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
			$queryPr->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
			$queryPr->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryPr->bindValue(':id_asid', $out['id_asid'], PDO::PARAM_INT);
			$queryPr->bindValue(':id_tempid', $varSQL['id_tempid'], PDO::PARAM_INT);
			$queryPr->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$queryPr->execute();
			$rowsPr = $queryPr->fetchAll(PDO::FETCH_ASSOC);
			$numPr = $queryPr->rowCount();
			
			foreach($rowsPr as $rowPr){
				$replace .= '<div class="editPreviewInner" data-tempid="' . $varSQL['id_tempid'] . '" data-page="2" data-tp="' . $rows[0]['id_tpid'] . '" data-pageid="' . $rows[0]['id_tpid'] . '_2_' . $rowPr['id_apid'] . '">' . $rowsF[0]['products_div'] . '</div>';

				$aPr = array();
				$aPr['apid'] = $rowPr['id_apid'];
				foreach($rowPr as $k=>$v){
					$aPr[$k] = $v;
				}
				array_push($out['printer'], $aPr);
			}

			$html = str_replace('##products##', $replace, $html);
			
			array_push($out['preview'], array('src' => '', 'previewcode' => '<div id="cssWall">' . $html . '</div>', 'productcode' => $rowsF[0]['products_div'], 'previewcss' => $rowsF[0]['css'], 'editcss' => $rowsF[0]['css_frontend'], 'temp' => $varSQL['id_tempid'], 'tp' => $rows[0]['id_tpid'], 'pageid' => $rows[0]['id_tpid'] . '_1', 'page' => '1', 'pagelabel' => '1', 'bfid' => $rows[0]['id_bfid']));

			
			########################################################################################
			include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsApp'] . 'fu-template-read-configuration-email.php');
			########################################################################################

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
				$out['thumbnails']['na'][$i] = array('src' => $dirTarget . 'thumbnails/' . $filenameOriginalBase . '-' . $i . '.png', 'tp' => $rows[0]['id_tpid'], 'pageid' => $rows[0]['id_tpid'] . '_' . $i, 'page' => $i, 'pagelabel' => $i, 'bfid' => '0');
			}
		
			// build preview page
			$firstpage = $CONFIG['system']['directoryRoot'] . 'assetimages/pictures/' . $filenameOriginalBase . '-1.png';
			if(file_exists($firstpage)){
				$info = getimagesize($firstpage);
				array_push($out['preview'], array('src' => '<img src="' . $dirTarget . 'pictures/' . $filenameOriginalBase . '-1.png" data-width="' . $info[0] . '" data-height="' . $info[1] . '">', 'temp' => $varSQL['id_tempid'], 'tp' => $rows[0]['id_tpid'], 'pageid' => $rows[0]['id_tpid'] . '_1', 'page' => '1', 'pagelabel' => '1', 'bfid' => $rows[0]['id_bfid']));
			}else{
				$out['preview'] = array();
			}
		}
	}



	##########################################################################
	// build form for components
	##########################################################################
	$queryC = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents_uni.id_tcid,
											' . $CONFIG['db'][0]['prefix'] . '_templatecomponents_uni.componentname
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents_uni 
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents2categories_assets_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents2categories_assets_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents2categories_assets_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents2categories_assets_uni.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents2categories_assets_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents2categories_assets_uni.id_tcid = ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents_uni.id_tcid
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents2categories_assets_uni.del = (:nultime)
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents_uni.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents_uni.active = (:active)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents2categories_assets_uni.id_caid = (:id_caid)
										GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents_uni.id_tcid
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents_uni.rank
										');
	$queryC->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':active', 1, PDO::PARAM_INT);
	$queryC->bindValue(':id_caid', $id_acid, PDO::PARAM_INT);
	$queryC->execute();
	$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
	$numC = $queryC->rowCount();
	
	foreach($rowsC as $rowC){
		$out['toolsform'] .= '<div class="fieldset" data-tcid="' . $rowC['id_tcid'] . '">';
		//$out['toolsform'] .= '<div class="formComponentHeadline">'.$rowC['componentname'].'</div>';
		
		switch($rowC['id_tcid']){
			case '3': // Product name
				$out['toolsform'] .= '';
				break;

			case '9': // Product category
				$out['toolsform'] .= '<div class="formRow formRowSpace" data-type="textfield">
											<div class="formLabel">' . $TEXT['yourCategory'] . '</div>
											<div class="formField"><input type="text" class="textfield componentformfield" name="content" id=""></div>
										</div>';
				break;

			case '12': // Product image
				$out['toolsform'] .= '<div class="formRow formRowSpace" data-type="textfield">
											<div class="formLabel">' . $TEXT['yourPicture'] . '</div>
											<div class="formField formFieldPictures"></div>
										</div>';
				break;
				
			case '4': // PN
				$out['toolsform'] .= '';
				break;

			case '13': // Tagline
				$out['toolsform'] .= '<div class="formRow formRowSpace" data-type="textfield">
											<div class="formLabel">' . $TEXT['yourText'] . '</div>
											<div class="formField"><input type="text" class="textfield componentformfield" name="content" id=""></div>
										</div>';
				break;

			case '5': // short description
				$out['toolsform'] .= '<div class="formRow formRowSpace" data-type="textarea">
											<div class="formLabel">' . $TEXT['yourText'] . '</div>
											<div class="formField"><textarea class="textfield componentformfield h150" name="content" id=""></textarea></div>
										</div>';
				break;

			case '6': // 25 word description
				$out['toolsform'] .= '<div class="formRow formRowSpace" data-type="textarea">
											<div class="formLabel">' . $TEXT['yourText'] . '</div>
											<div class="formField"><textarea class="textfield componentformfield h150" name="content" id=""></textarea></div>
										</div>';
				break;

			case '7': // 50 word description
				$out['toolsform'] .= '<div class="formRow formRowSpace" data-type="textarea">
											<div class="formLabel">' . $TEXT['yourText'] . '</div>
											<div class="formField"><textarea class="textfield componentformfield h150" name="content" id=""></textarea></div>
										</div>';
				break;

			case '8': // 100 word description
				$out['toolsform'] .= '<div class="formRow formRowSpace" data-type="textarea">
											<div class="formLabel">' . $TEXT['yourText'] . '</div>
											<div class="formField"><textarea class="textfield componentformfield h150" name="content" id=""></textarea></div>
										</div>';
				break;

			case '2': // pricefield
				$out['toolsform'] .= '<div class="formRow formRowSpace" data-type="pricefield">
											<div class="formLabel">' . $TEXT['yourPrice'] . '</div>
											<div class="formField"><input type="text" class="textfield componentformfield pricefield" name="content" id=""> <span class="formCurrency">' . $rows[0]['currency'] . '</span></div>
										</div>';
				break;

			case '14': // wysiwyg
				$wysiwyg_conf = htmlspecialchars(json_encode(array("toolbar" => "SYS_MIN")));
				$out['toolsform'] .= '<div class="formRow formRowSpace" data-type="textarea">
										<div class="formLabel">' . $TEXT['yourText'] . '</div>
										<div class="formField"><textarea name="content" id="" class="textfield componentformfield wysiwyg h500" data-config="' . $wysiwyg_conf . '"></textarea></div>
										</div>';
				break;

			case '1': // textfield
				$out['toolsform'] .= '<div class="formRow formRowSpace" data-type="textarea">
											<div class="formLabel">' . $TEXT['yourText'] . '</div>
											<div class="formField"><textarea class="textfield componentformfield h150" name="content" id=""></textarea></div>
										</div>';
				break;

			case '17': // Textmodul
				$out['toolsform'] .= '';
				break;

			case '15': // Fileupload
				$out['toolsform'] .= '';
				break;

			case '11': // partner logo
				$out['toolsform'] .= '<div class="formRow formRowSpace">
											<div class="formLabel"><strong>' . $TEXT['attention'] . '</strong></div>
											<div class="formField">' . $TEXT['changeLogo'] . '</div>
										</div>';
				break;

			case '10': // partner logo
				$out['toolsform'] .= '<div class="formRow formRowSpace">
											<div class="formLabel"><strong>' . $TEXT['attention'] . '</strong></div>
											<div class="formField">' . $TEXT['changeContactDetails'] . '</div>
										</div>';
				break;

			case '16': // partner logo / contact combination
				$out['toolsform'] .= '<div class="formRow formRowSpace">
											<div class="formLabel"><strong>' . $TEXT['attention'] . '</strong></div>
											<div class="formField">' . $TEXT['changeLogoContact'] . '</div>
										</div>';
				break;
			
			// Color area
			case '18':
				break;

			case '19': // Call to action
				$out['toolsform'] .= '<div class="formRow" data-type="textfield">
											<div class="formLabel">' . $TEXT['calltoactionlabel'] . '</div>
											<div class="formField"><input type="text" class="textfield componentformfield" name="content" id=""></div>
										</div>
										<div class="formRow formRowSpace" data-type="textfield">
											<div class="formLabel">' . $TEXT['calltoactionurl'] . '</div>
											<div class="formField"><input type="text" class="textfield componentformfield" name="calltoactionurl" id=""></div>
										</div>';
				break;

		}
		
		
		
		
		$out['toolsform'] .= '</div>';
		$out['toolsform'] .= '</div>';
	}

	$out['toolsform'] .= '<input type="hidden" name="id_tempid" id="id_tempid" value="' . $varSQL['id_tempid'] . '">';





}



#################################################
// build bannerformats
#################################################
//if($id_acid == 1){
//	$queryBf = $CONFIG['dbconn'][0]->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_bfid,
//											' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.bannername,
//											' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.width,
//											' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.height
//										FROM ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni 
//										
//										WHERE ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_count = (:id_count)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_lang = (:id_lang)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_dev = (:id_dev)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//											AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.del = (:nultime)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_tempid = (:id_tempid)
//										GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_bfid
//										ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_bfid
//										');
//	$queryBf->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$queryBf->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//	$queryBf->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//	$queryBf->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
//	$queryBf->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_INT);
//	$queryBf->execute();
//	$rowsBf = $queryBf->fetchAll(PDO::FETCH_ASSOC);
//	$numBf = $queryBf->rowCount();
//
//	foreach($rowsBf as $rowBf){
//		$out['bannerformats'] .= '<div class="formBannerformatOuter" data-bfid="' . $rowBf['id_bfid'] . '">';
//		$out['bannerformats'] .= '<div class="formRow formRowNoBorder formRowBannerfiles">';
//		$out['bannerformats'] .= '<span class="formBannerformatName"><strong>' . $rowBf['bannername']. '</strong></span> (' . $rowBf['width'] . 'x' . $rowBf['height'] . ')';
//		$out['bannerformats'] .= '<div class="modulIcon modulIconForm modulIconFloatRight modulIconDelete" title="'. $TEXT['titleDeleteRow'] . '"><i class="fa fa-trash"></i></div>';
//		$out['bannerformats'] .= '<div class="modulIcon modulIconForm modulIconFloatRight modulIconEdit" title="'. $TEXT['titleEditRow'] . '"><i class="fa fa-pencil"></i></div>';
//		$out['bannerformats'] .= '</div>';
//		
//		for($i = 1; $i <= 3; $i++){
//			$label = '';
//			if($i == 1) $label = '<span style="padding-left: 20px;font-style:italic">' . $TEXT['firstframe'] . '</span>';
//			if($i == 2) $label = '<span style="padding-left: 20px;font-style:italic">' . $TEXT['productframe'] . '</span>';
//			if($i == 3) $label = '<span style="padding-left: 20px;font-style:italic">' . $TEXT['lastframe'] . '</span>';
//			
//			$classSpace = '';
//			if($i == 3) $classSpace = 'formRowSpace';
//			
//			$out['bannerformats'] .= '<div class="formRow ' . $classSpace . '">';
//			$out['bannerformats'] .= '<div class="formLabel">';
//			$out['bannerformats'] .= '<label for="">' . $label . '</label>';
//			$out['bannerformats'] .= '</div>';
//			$out['bannerformats'] .= '<div class="formField">';
//			if(isset($aBannerFiles[$rowBf['id_bfid']][$i])) $out['bannerformats'] .= '<div class="formBannerFile" data-tpid="' . $aBannerFiles[$rowBf['id_bfid']][$i]['tpid'] . '" data-mid="' . $aBannerFiles[$rowBf['id_bfid']][$i]['mid'] . '"><a href="' . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathMedia'] . $aBannerFiles[$rowBf['id_bfid']][$i]['filesys_filename'] . '" target="_blank">' . $aBannerFiles[$rowBf['id_bfid']][$i]['filename'] . '</a></div>';
//			$out['bannerformats'] .= '</div>';
//			$out['bannerformats'] .= '</div>';
//		}
//		$out['bannerformats'] .= '</div>';
//	}
//}

include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsApp'] . 'fu-templatecomponents-read.php');
$out['components'] = $aTPE;



echo json_encode($out);

?>