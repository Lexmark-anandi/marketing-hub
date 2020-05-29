<?php
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-app.php');
$varSQL = getPostData();

$date = new DateTime(); 
$now = $date->format('Y-m-d H:i:s');

$out = array();
//$out['type'] = '';
$out['title'] = '';
$out['id_asid'] = 0;
$out['templates'] = array();
//$out['preview'] = array();
//$out['components'] = array();
////$out['aComponents'] = '';
//$out['toolsform'] = '';
//$out['configuration'] = array();
//$out['configurationform'] = '';
//$out['printer'] = array();
////$out['bannerformats'] = '';
//
//$aBannerFiles = array();
//
// 
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
													' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count, 
													' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang, 
													' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev, 
													' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_cl, 
													' . $CONFIG['db'][0]['prefix'] . '_templates_uni.restricted_all, 
													' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_campid, 
													' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid, 
													' . $CONFIG['db'][0]['prefix'] . '_templates_uni.title, 
													' . $CONFIG['db'][0]['prefix'] . '_templates_uni.components, 
													' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid, 
													' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.title AS title_campaign
												FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
		
												INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni
													ON ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_count = (:id_count)
														AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_lang = (:id_lang)
														AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid = (:id_campid)
														AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.del = (:nultime)

												WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_campid = (:id_campid)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
										');
	$queryCo->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$queryCo->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$queryCo->bindValue(':id_campid', $varSQL['id_campid'], PDO::PARAM_INT);
	$queryCo->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryCo->execute();
	$rowsCo = $queryCo->fetchAll(PDO::FETCH_ASSOC);
	$numCo = $queryCo->rowCount();
	
	foreach($rowsCo as $rowCo){
		$queryCo2 = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assets_tmp
											(id_asid, id_count, id_lang, id_dev, id_cl, restricted_all, id_campid, id_tempid, id_pcid, id_ppid, title, components)
											VALUES
											(:id_asid, :id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_campid, :id_tempid, :id_pcid, :id_ppid, :title, :components)
											');
		$queryCo2->bindValue(':id_asid', $out['id_asid'], PDO::PARAM_INT);
		$queryCo2->bindValue(':id_count', $rowCo['id_count'], PDO::PARAM_INT);
		$queryCo2->bindValue(':id_lang', $rowCo['id_lang'], PDO::PARAM_INT);
		$queryCo2->bindValue(':id_dev', $rowCo['id_dev'], PDO::PARAM_INT);
		$queryCo2->bindValue(':id_cl', $rowCo['id_cl'], PDO::PARAM_INT);
		$queryCo2->bindValue(':restricted_all', $rowCo['restricted_all'], PDO::PARAM_INT);
		$queryCo2->bindValue(':id_campid', $rowCo['id_campid'], PDO::PARAM_INT);
		$queryCo2->bindValue(':id_tempid', $rowCo['id_tempid'], PDO::PARAM_INT);
		$queryCo2->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
		$queryCo2->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
		$queryCo2->bindValue(':title', $rowCo['title_campaign'], PDO::PARAM_INT);
		$queryCo2->bindValue(':components', $rowCo['components'], PDO::PARAM_INT);
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
	
	
	
}else{
	$out['id_asid'] = $varSQL['id_asid'];
	
	#############################################################################################
	#############################################################################################
	// copy data to tmp
	#############################################################################################
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
										(id_asid, id_count, id_lang, id_dev, id_cl, restricted_all, id_campid, id_tempid, id_pcid, id_ppid, title, components, thumbnail)
										SELECT
										id_asid, id_count, id_lang, id_dev, id_cl, restricted_all, id_campid, id_tempid, id_pcid, id_ppid, title, components, thumbnail
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
										(id_apeid, id_count, id_lang, id_dev, id_cl, restricted_all, id_asid, id_tempid, id_tpeid, id_pid, id_pcid, id_ppid, content, content_add)
										SELECT
										id_apeid, id_count, id_lang, id_dev, id_cl, restricted_all, id_asid, id_tempid, id_tpeid, id_pid, id_pcid, id_ppid, content, content_add
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
	#############################################################################################
	#############################################################################################
}


#######################################################
// read templates for campaign
#######################################################
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid,
										' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.title,
										' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid,
										' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid,
										' . $CONFIG['db'][0]['prefix'] . '_templates_uni.title AS title_template,
										' . $CONFIG['db'][0]['prefix'] . '_templates_uni.thumbnail,
										' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_asid,
										' . $CONFIG['db'][0]['prefix'] . '_assets_uni.title AS title_asset,
										' . $CONFIG['db'][0]['prefix'] . '_assets_uni.thumbnail AS thumbnail_asset,
										' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.category
									FROM ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni
	
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_
										ON ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_countid = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_langid = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_campid = ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid
											AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.published_at < (:now)
											AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.published_at <> (:nultime)
	
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
										ON ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_campid = ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid
									
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_assets_uni
										ON ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_ppid = (:id_ppid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.del = (:nultime)
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni
										ON ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_caid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid
											AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.active = (:active)
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid = (:id_campid)
									GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.rank
									');
$query->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$query->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$query->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$query->bindValue(':id_campid', $varSQL['id_campid'], PDO::PARAM_INT);
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':active', 1, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$out['title'] = ($CONFIG['activeSettings']['id_page'] == 'myassets') ? $rows[0]['title_asset'] : $rows[0]['title'];

foreach($rows as $row){
	$dirTarget = $CONFIG['system']['directoryInstallation'] . 'custom/assets/';
	$fileThumbnail = '';
	if($row['id_caid'] == 1) $fileThumbnail = 'banner.png';
	if($row['id_caid'] == 2) $fileThumbnail = 'print-ad.png';
	if($row['id_caid'] == 3) $fileThumbnail = 'email.png';
	if($row['id_caid'] == 4) $fileThumbnail = '';
	if($row['id_caid'] == 5) $fileThumbnail = '';
	if($row['id_caid'] == 8) $fileThumbnail = 'poster.png';
	if($row['id_caid'] == 9) $fileThumbnail = 'poster.png';
	if($row['id_caid'] == 10) $fileThumbnail = 'rollup.png';
	
	//$fileThumbnail = str_pad($row['id_tempid'], 6 ,'0', STR_PAD_LEFT) . '-' . $CONFIG['user']['id_countid'] . '-' . $CONFIG['user']['id_langid'] . '-' . md5($row['id_tempid'] . '_template') . '-1.png';
	$out['templates']['a' . '#' . $row['id_tempid']] = array('src' => $dirTarget . $fileThumbnail, 'temp' => $row['id_tempid'], 'pagelabel' => $row['title_template']);
}


echo json_encode($out);

?>