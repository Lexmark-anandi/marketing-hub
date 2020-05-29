<?php
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-app.php');
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$out = array();
$out['apid'] = 0;
$out['form'] = '';
$out['printer'] = array();
$out['printer'][0] = array();
$out['components'] = array();


$rank = 10; 
$queryCo = $CONFIG['dbconn'][0]->prepare('
									SELECT MAX(rank) AS rank
									FROM ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_asid = (:id_asid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.del = (:nultime)
									');
$queryCo->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$queryCo->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$queryCo->bindValue(':id_asid', $varSQL['asid'], PDO::PARAM_INT);
$queryCo->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryCo->execute();
$rowsCo = $queryCo->fetchAll(PDO::FETCH_ASSOC);  
$numCo = $queryCo->rowCount();
if($numCo > 0) $rank = $rowsCo[0]['rank'] + 10;


$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.id_piid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filename,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filehash
									FROM ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni 
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid = ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.image
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.id_data_parent = (:id_pid)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.rank
									LIMIT 1
									');
$query->bindValue(':id_count',$CONFIG['user']['id_countid'], PDO::PARAM_INT);
$query->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':id_pid', $varSQL['pid'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$image = '';
if($num > 0){
	$aArgsPic = array();
	$aArgsPic['id_count'] = $CONFIG['user']['id_countid'];
	$aArgsPic['id_lang'] = $CONFIG['user']['id_langid'];
	$aArgsPic['id_dev'] = 0;
	$aArgsPic['id_mid'] = $rows[0]['id_mid'];
	$aArgsPic['pathOrg'] = $CONFIG['system']['pathMedia'];
	$aArgsPic['fileOrg'] = $rows[0]['filesys_filename'];
	$aArgsPic['pathNew'] = $CONFIG['system']['pathAssets'];
	$aArgsPic['filehash'] = $rows[0]['filehash'];
	$aArgsPic['id_pf'] = 6;
	$aArgsPic['onlyShrink'] = 'Y';
	$aArgsPic['sizing'] = 'Y';

	$pic = pictureSize($aArgsPic);
	if(file_exists($CONFIG['system']['directoryRoot'] . $pic)){
		$info = getimagesize($CONFIG['system']['directoryRoot']  .$pic);
		$image = '<div class="componentProductimage"><img src="'.$CONFIG['system']['directoryInstallation'] . $pic.'" data-piid="' . $rows[0]['id_piid'] . '"></div>';
	}
}



$queryPC = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.company_name,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.address1,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.zipcode,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.city,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.phone,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.url,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.email,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.logo,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filename,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filehash,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename
									FROM ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni

									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.logo = ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid

									WHERE ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_pcid = (:id_pcid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.del = (:nultime)
									');
$queryPC->bindValue(':id_count', 0, PDO::PARAM_INT);
$queryPC->bindValue(':id_lang', 0, PDO::PARAM_INT);
$queryPC->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
$queryPC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryPC->execute();
$rowsPC = $queryPC->fetchAll(PDO::FETCH_ASSOC);
$numPC = $queryPC->rowCount();

$queryPP = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.firstname,
										' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.lastname,
										' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.contactname,
										' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.hide_contactname,
                                                                                ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.phone,
                                                                                ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.hide_phone,
                                                                                ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.email,
                                                                                ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.hide_email
									FROM ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.id_ppid = (:id_ppid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.del = (:nultime)
									');
$queryPP->bindValue(':id_count', 0, PDO::PARAM_INT);
$queryPP->bindValue(':id_lang', 0, PDO::PARAM_INT);
$queryPP->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryPP->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryPP->execute();
$rowsPP = $queryPP->fetchAll(PDO::FETCH_ASSOC);
$numPP = $queryPP->rowCount();
$rowsPC[0]['contactname'] = $rowsPP[0]['contactname'];
if($rowsPC[0]['contactname'] == '') $rowsPC[0]['contactname'] = $rowsPP[0]['firstname'] . ' ' . $rowsPP[0]['lastname'];
$rowsPC[0]['hide_contactname'] = $rowsPP[0]['hide_contactname'];
$rowsPC[0]['phone'] = $rowsPP[0]['phone'];
$rowsPC[0]['email'] = $rowsPP[0]['email'];
$rowsPC[0]['hide_phone'] = $rowsPP[0]['hide_phone'];
$rowsPC[0]['hide_email'] = $rowsPP[0]['hide_email'];

if($rowsPC[0]['hide_company_name'] == 1) $rowsPC[0]['company_name'] = '';
if($rowsPC[0]['hide_address1'] == 1) $rowsPC[0]['address1'] = '';
if($rowsPC[0]['hide_zipcode'] == 1) $rowsPC[0]['zipcode'] = '';
if($rowsPC[0]['hide_city'] == 1) $rowsPC[0]['city'] = '';
if($rowsPC[0]['hide_phone'] == 1) $rowsPC[0]['phone'] = '';
if($rowsPC[0]['hide_email'] == 1) $rowsPC[0]['email'] = '';
if($rowsPC[0]['hide_url'] == 1) $rowsPC[0]['url'] = '';
if($rowsPC[0]['hide_contactname'] == 1) $rowsPC[0]['contactname'] = '';







$queryI = $CONFIG['dbconn'][0]->prepare('
									INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_
									(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
									VALUES
									(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
									');
$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
$queryI->bindValue(':id_cl', 1, PDO::PARAM_INT);
$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
$queryI->bindValue(':create_from', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryI->execute();
$out['apid'] = $CONFIG['dbconn'][0]->lastInsertId();
$out['printer'][0]['apid'] = $out['apid'];

$queryPr = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_pid,
										' . $CONFIG['db'][0]['prefix'] . '_products_uni.revenue_pid,
										' . $CONFIG['db'][0]['prefix'] . '_products_uni.pn_text,
										' . $CONFIG['db'][0]['prefix'] . '_products_uni.mkt_name,
										' . $CONFIG['db'][0]['prefix'] . '_products_uni.mkt_paragraph,
										' . $CONFIG['db'][0]['prefix'] . '_products_uni.tagline,
										' . $CONFIG['db'][0]['prefix'] . '_products_uni.description_text_25,
										' . $CONFIG['db'][0]['prefix'] . '_products_uni.description_text_50,
										' . $CONFIG['db'][0]['prefix'] . '_products_uni.description_text_100,
										' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_ptid,
										' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.prod_type
									FROM ' . $CONFIG['db'][0]['prefix'] . '_products_uni 

									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni
										ON ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.id_cl IN (0, 1)
											AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.id_ptid = ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_ptid
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_dev = (:id_dev)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.is_printer = (:one)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.status IN ("Public", "Not Public - B2B")
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_pid = (:id_pid)
									');
$queryPr->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$queryPr->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$queryPr->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryPr->bindValue(':one', 1, PDO::PARAM_INT);
$queryPr->bindValue(':id_pid', $varSQL['pid'], PDO::PARAM_INT);
$queryPr->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryPr->execute();
$rowsPr = $queryPr->fetchAll(PDO::FETCH_ASSOC);
$numPr = $queryPr->rowCount();

foreach($rowsPr[0] as $k => $v){
	$out['printer'][0][$k] = $v;
}
$out['printer'][0]['duration'] = $CONFIG['system']['durationBannerframe'];
$out['printer'][0]['showframe'] = 1;

$queryI = $CONFIG['dbconn'][0]->prepare('
									INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp
									(id_apid, id_count, id_lang, id_dev, id_cl, restricted_all, id_asid, id_tempid, id_bfid, id_etid, id_tpid, id_pcid, id_ppid, rank, id_pid, revenue_pid, prod_type, id_ptid, pn_text, mkt_name, mkt_paragraph, tagline, description_text_25, description_text_50, description_text_100, image, not_lpmd, content_add, duration, showframe, price, create_at, create_from, change_from)
									VALUES
									(:id_apid, :id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_asid, :id_tempid, :id_bfid, :id_etid, :id_tpid, :id_pcid, :id_ppid, :rank, :id_pid, :revenue_pid, :prod_type, :id_ptid, :pn_text, :mkt_name, :mkt_paragraph, :tagline, :description_text_25, :description_text_50, :description_text_100, :image, :not_lpmd, :content_add, :duration, :showframe, :price, :create_at, :create_from, :create_from)
									');
$queryI->bindValue(':id_apid', $out['apid'], PDO::PARAM_INT);
$queryI->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$queryI->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$queryI->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryI->bindValue(':id_cl', 1, PDO::PARAM_INT);
$queryI->bindValue(':restricted_all', 0, PDO::PARAM_INT);
$queryI->bindValue(':id_asid', $varSQL['asid'], PDO::PARAM_INT);
$queryI->bindValue(':id_tempid', $varSQL['tempid'], PDO::PARAM_INT);
$queryI->bindValue(':id_bfid', $varSQL['bfid'], PDO::PARAM_INT);
$queryI->bindValue(':id_etid', ($varSQL['etid'] != '') ? $varSQL['etid'] : 0, PDO::PARAM_INT);
$queryI->bindValue(':id_tpid', $varSQL['tpid'], PDO::PARAM_INT);
$queryI->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
$queryI->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryI->bindValue(':rank', $rank, PDO::PARAM_INT);
$queryI->bindValue(':id_pid', $rowsPr[0]['id_pid'], PDO::PARAM_INT);
$queryI->bindValue(':revenue_pid', $rowsPr[0]['revenue_pid'], PDO::PARAM_INT);
$queryI->bindValue(':prod_type', $rowsPr[0]['prod_type'], PDO::PARAM_STR);
$queryI->bindValue(':id_ptid', $rowsPr[0]['id_ptid'], PDO::PARAM_INT);
$queryI->bindValue(':pn_text', $rowsPr[0]['pn_text'], PDO::PARAM_STR);
$queryI->bindValue(':mkt_name', $rowsPr[0]['mkt_name'], PDO::PARAM_STR);
$queryI->bindValue(':mkt_paragraph', $rowsPr[0]['mkt_paragraph'], PDO::PARAM_STR);
$queryI->bindValue(':tagline', $rowsPr[0]['tagline'], PDO::PARAM_STR);
$queryI->bindValue(':description_text_25', $rowsPr[0]['description_text_25'], PDO::PARAM_STR);
$queryI->bindValue(':description_text_50', $rowsPr[0]['description_text_50'], PDO::PARAM_STR);
$queryI->bindValue(':description_text_100', $rowsPr[0]['description_text_100'], PDO::PARAM_STR);
$queryI->bindValue(':image', $image, PDO::PARAM_STR);
$queryI->bindValue(':duration', $CONFIG['system']['durationBannerframe'], PDO::PARAM_INT);
$queryI->bindValue(':content_add', json_encode(array('calltoactionurl' => $rowsPC[0]['url'])), PDO::PARAM_STR);
$queryI->bindValue(':showframe', 1, PDO::PARAM_INT);
$queryI->bindValue(':price', $TEXT['yourPrice'], PDO::PARAM_STR);
$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
$queryI->bindValue(':create_from', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);

$aNotLpmd = array();
$queryStrTPE = 'SELECT 
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpeid,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.content
	FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni 
	
	INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents_uni
		ON ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents_uni.id_count = (:id_count)
			AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents_uni.id_lang = (:id_lang)
			AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents_uni.del = (:nultime)
			AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents_uni.id_tcid = ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tcid
			AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponents_uni.flag_product = (:no)
			
	WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_count = (:id_count)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_lang = (:id_lang)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_dev = (:id_dev)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_cl IN (0, 1)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.del = (:nultime)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpid = (:id_tpid)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.page = (:page)
';
$queryTPE = $CONFIG['dbconn'][0]->prepare($queryStrTPE);
$queryTPE->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$queryTPE->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$queryTPE->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryTPE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryTPE->bindValue(':id_tpid', $varSQL['tpid'], PDO::PARAM_INT);
$queryTPE->bindValue(':page', 2, PDO::PARAM_INT);
$queryTPE->bindValue(':no', 2, PDO::PARAM_INT);
$queryTPE->execute();
$rowsTPE = $queryTPE->fetchAll(PDO::FETCH_ASSOC);
$numTPE = $queryTPE->rowCount();
foreach($rowsTPE as $rowTPE){
	$aNotLpmd[$rowTPE['id_tpeid']] = $rowTPE['content'];
}
$queryI->bindValue(':not_lpmd', json_encode($aNotLpmd), PDO::PARAM_STR); 

$queryI->execute();






$aTPE = array();

$queryStrTPE = 'SELECT 
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpeid,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_caid,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tempid,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpid,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tcid,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.page_id,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.page,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.elementtitle,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.position_left,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.position_top,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.width,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.height,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.fontsize,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.fontcolor,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.fontstyle,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.background_color,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.content,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.content_add,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.editable,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.active,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.max_char,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.alignment,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.verticalalignment,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.fixed
	FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni 

	WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_count = (:id_count)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_lang = (:id_lang)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_dev = (:id_dev)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_cl IN (0, 1)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.del = (:nultime)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tempid = (:id_tempid)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpid = (:id_tpid)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.page = (:page)
';

$queryTPE = $CONFIG['dbconn'][0]->prepare($queryStrTPE);
$queryTPE->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$queryTPE->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$queryTPE->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryTPE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryTPE->bindValue(':id_tempid', $varSQL['tempid'], PDO::PARAM_INT);
$queryTPE->bindValue(':id_tpid', $varSQL['tpid'], PDO::PARAM_INT);
$queryTPE->bindValue(':page', 2, PDO::PARAM_INT);
$queryTPE->execute();
$rowsTPE = $queryTPE->fetchAll(PDO::FETCH_ASSOC);
$numTPE = $queryTPE->rowCount();

foreach($rowsTPE as $rowTPE){
	if(!array_key_exists('page_' . $rowTPE['page_id'] . '_' . $out['apid'], $aTPE)){
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']] = array();
	}
		
	if(!array_key_exists('compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid'], $aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']])){
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']] = array();
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['id_apid'] = $out['apid'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['id_tpeid'] = $rowTPE['id_tpeid'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['id_caid'] = $rowTPE['id_caid'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['id_tpid'] = $rowTPE['id_tpid'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['id_tcid'] = $rowTPE['id_tcid'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['pageid'] = $rowTPE['page_id'] . '_' . $out['apid'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['page'] = $rowTPE['page'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['elementtitle'] = $rowTPE['elementtitle'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['width'] = $rowTPE['width'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['height'] = $rowTPE['height'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['left'] = $rowTPE['position_left'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['top'] = $rowTPE['position_top'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['fontsize'] = $rowTPE['fontsize'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['fontcolor'] = $rowTPE['fontcolor'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['fontstyle'] = $rowTPE['fontstyle'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['background_color'] = $rowTPE['background_color'];
		$content_add = $rowTPE['content_add'];
		$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content_add'] = ($content_add != '') ? json_decode($content_add, true) : array();
		//$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = ($rowTPE['content_asset'] != '') ? $rowTPE['content_asset'] : $rowTPE['content'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = $rowTPE['content'];
		
		switch($rowTPE['id_tcid']){
			// Product name
			case '3':
				$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = $rowsPr[0]['mkt_name'];
				break;
	
			// Product category
			case '9':
				$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = $rowsPr[0]['prod_type'];
				break;
			
			// Product image
			case '12':
				$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = $image;
				break;
			
			// PN
			case '4':
				$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = $rowsPr[0]['pn_text'];
				break;
	
			// Tagline
			case '13':
				$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = $rowsPr[0]['tagline'];
				break;
	
			// Short description
			case '5':
				$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = $rowsPr[0]['mkt_paragraph'];
				break;
	
			// 25 word description
			case '6':
				$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = $rowsPr[0]['description_text_25'];
				break;
	
			// 50 word description
			case '7':
				$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = $rowsPr[0]['description_text_50'];
				break;
	
			// 100 word description
			case '8':
				$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = $rowsPr[0]['description_text_100'];
				break;
	
			// Pricefield
			case '2':
//				preg_match('/[0-9\.,]*/', $aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'], $reg);
//				$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = $reg[0] . ' ' . $CONFIG['user']['configCountry']['currency'];
				
				//if($aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] == '') $aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = $TEXT['yourPrice'];
				$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = $TEXT['yourPrice'];
				break;
			
			// WYSIWYG
			case '14':
				break;
			
			// Textfield
			case '1':
				break;
			
			// Textmodul
			case '17':
				$queryCS = $CONFIG['dbconn'][0]->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix'] . '_textmoduls_uni.modultext
													FROM ' . $CONFIG['db'][0]['prefix'] . '_textmoduls_uni 
													
													WHERE ' . $CONFIG['db'][0]['prefix'] . '_textmoduls_uni.id_count = (:id_count)
														AND ' . $CONFIG['db'][0]['prefix'] . '_textmoduls_uni.id_lang = (:id_lang)
														AND ' . $CONFIG['db'][0]['prefix'] . '_textmoduls_uni.id_dev = (:id_dev)
														AND ' . $CONFIG['db'][0]['prefix'] . '_textmoduls_uni.id_cl IN (0, 1)
														AND ' . $CONFIG['db'][0]['prefix'] . '_textmoduls_uni.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . '_textmoduls_uni.id_tmid = (:id_tmid)
													');
				$queryCS->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
				$queryCS->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
				$queryCS->bindValue(':id_dev', 0, PDO::PARAM_INT);
				$queryCS->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$queryCS->bindValue(':id_tmid', $rowTPE['content'], PDO::PARAM_INT);
				$queryCS->execute();
				$rowsCS = $queryCS->fetchAll(PDO::FETCH_ASSOC);
				$numCS = $queryCS->rowCount();
				
				$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = $rowsCS[0]['modultext'];
				break;

			case '15': // Fileupload
				break;
	
			// Partner logo
			case '11':
				$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = '<div class="componentPartnerlogo"><img src="' . $CONFIG['system']['directoryInstallation'] . 'media/' . $rowsPC[0]['filesys_filename'] . '"></div>';
				break;

			// Partner contact
			case '10':
				$addrTemp1 = array();
				if(trim($rowsPC[0]['company_name']) != '') array_push($addrTemp1, '<span>' . $rowsPC[0]['company_name'] . '</span>');
				if(trim($rowsPC[0]['contactname']) != '') array_push($addrTemp1, '<span>' . $rowsPC[0]['contactname'] . '</span>');
				if(trim($rowsPC[0]['address1']) != '') array_push($addrTemp1, '<span>' . $rowsPC[0]['address1'] . '</span>');
				if(trim($rowsPC[0]['zipcode']) != '' || trim($rowsPC[0]['city'])) array_push($addrTemp1, '<span>' . $rowsPC[0]['zipcode'] . ' ' . $rowsPC[0]['city'] . '</span>');
				$addrTemp2 = array();
				if(trim($rowsPC[0]['phone']) != '') array_push($addrTemp2, '<span>' . $rowsPC[0]['phone'] . '</span>');
				if(trim($rowsPC[0]['email']) != '') array_push($addrTemp2, '<span>' . $rowsPC[0]['email'] . '</span>');
				if(trim($rowsPC[0]['url']) != '') array_push($addrTemp2, '<span>' . $rowsPC[0]['url'] . '</span>');

				$conTmp = '<div class="dummyPartnercontact"><span>';
				$conTmp .= implode('<span class="contactDelimiter"></span>', $addrTemp1);
				$conTmp .= '</span>';
				$conTmp .= '<span>';
				$conTmp .= implode('<span class="contactDelimiter"></span>', $addrTemp2);
				$conTmp .= '</span></div>';
				$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = $conTmp;
				break;
			
			// Partner contact / logo combination
			case '16':
				$addrTemp1 = array();
				if(trim($rowsPC[0]['company_name']) != '') array_push($addrTemp1, '<span>' . $rowsPC[0]['company_name'] . '</span>');
				if(trim($rowsPC[0]['contactname']) != '') array_push($addrTemp1, '<span>' . $rowsPC[0]['contactname'] . '</span>');
				if(trim($rowsPC[0]['address1']) != '') array_push($addrTemp1, '<span>' . $rowsPC[0]['address1'] . '</span>');
				if(trim($rowsPC[0]['zipcode']) != '' || trim($rowsPC[0]['city'])) array_push($addrTemp1, '<span>' . $rowsPC[0]['zipcode'] . ' ' . $rowsPC[0]['city'] . '</span>');
				$addrTemp2 = array();
				if(trim($rowsPC[0]['phone']) != '') array_push($addrTemp2, '<span>' . $rowsPC[0]['phone'] . '</span>');
				if(trim($rowsPC[0]['email']) != '') array_push($addrTemp2, '<span>' . $rowsPC[0]['email'] . '</span>');
				if(trim($rowsPC[0]['url']) != '') array_push($addrTemp2, '<span>' . $rowsPC[0]['url'] . '</span>');

				$conTmp = '<div class="partnerContactCombination contactalignleft">';
				$conTmp .= '<div class="componentPartnerlogo"><img src="' . $CONFIG['system']['directoryInstallation'] . 'media/' . $rowsPC[0]['filesys_filename'] . '"></div>';
				$conTmp .= '<div class="dummyPartnercontact"><span>';
				$conTmp .= implode('<span class="contactDelimiter"></span>', $addrTemp1);
				$conTmp .= '</span>';
				$conTmp .= '<span>';
				$conTmp .= implode('<span class="contactDelimiter"></span>', $addrTemp2);
				$conTmp .= '</span></div>';
				$conTmp .= '</div>';
				$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = $conTmp;
				break;
			
			// Color area
			case '18':
				break;
			
			// Call to action
			case '19':
				$aCon = array();
				$aCon[$rowTPE['id_tpeid']] = $TEXT['ordernow'];

				$qry = 'UPDATE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp SET
							not_lpmd = (:content)
						WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_apid = (:id_apid)
						';
				$queryC = $CONFIG['dbconn'][0]->prepare($qry);
				$queryC->bindValue(':id_apid', $out['apid'], PDO::PARAM_INT);
				$queryC->bindValue(':content', json_encode($aCon), PDO::PARAM_STR);
				$queryC->execute();
				//$numC = $queryC->rowCount();

				$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = $TEXT['ordernow'];
				$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content_add']['calltoactionurl'] = $rowsPC[0]['url'];
				break;
		}

		
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['contentOrg'] = $aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['maxchars'] = $rowTPE['max_char'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['alignment'] = $rowTPE['alignment'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['verticalalignment'] = $rowTPE['verticalalignment'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['fixed'] = $rowTPE['fixed'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['editable'] = $rowTPE['editable'];
		$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['active'] = $rowTPE['active'];
	}
}



$out['components'] = $aTPE;




echo json_encode($out);

?>