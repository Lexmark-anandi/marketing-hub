<?php
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-app.php');

$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$out = array();
$aExportfiles = array();

if($varSQL['id_promid'] == 0 && $varSQL['id_campid'] == 0){
	
	$queryAS = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.components,
											' . $CONFIG['db'][0]['prefix'] . '_assets_uni.title,
											' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_tempid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.currency

										FROM ' . $CONFIG['db'][0]['prefix'] . '_assets_uni 
	
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_ 
											ON ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_countid = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_langid = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_tempid 
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.published_at < (:now)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.published_at <> (:nultime)
	
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
	
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_cl IN (0, 1)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_asid = (:id_asid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_ppid = (:id_ppid)
										');	
	$queryAS->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$queryAS->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$queryAS->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryAS->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryAS->bindValue(':now', $now, PDO::PARAM_STR);
	$queryAS->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
	$queryAS->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$queryAS->execute();
	$rowsAS = $queryAS->fetchAll(PDO::FETCH_ASSOC);
	$numAS = $queryAS->rowCount();
	
}else if($varSQL['id_promid'] > 0){
	
	$queryAS = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.components,
											' . $CONFIG['db'][0]['prefix'] . '_assets_uni.title,
											' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_tempid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.currency

										FROM ' . $CONFIG['db'][0]['prefix'] . '_assets_uni 
	
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_
											ON ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_langid = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_promid = ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_promid
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.published_at < (:now)
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.published_at <> (:nultime)
	
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
	
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_cl IN (0, 1)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_asid = (:id_asid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_ppid = (:id_ppid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_promid = (:id_promid)
										');	
	$queryAS->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$queryAS->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$queryAS->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryAS->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryAS->bindValue(':now', $now, PDO::PARAM_STR);
	$queryAS->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
	$queryAS->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$queryAS->bindValue(':id_promid', $varSQL['id_promid'], PDO::PARAM_INT);
	$queryAS->execute();
	$rowsAS = $queryAS->fetchAll(PDO::FETCH_ASSOC);
	$numAS = $queryAS->rowCount();
	
		
}else if($varSQL['id_campid'] > 0){
	
		
	$queryAS = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.components,
											' . $CONFIG['db'][0]['prefix'] . '_assets_uni.title,
											' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_tempid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.currency

										FROM ' . $CONFIG['db'][0]['prefix'] . '_assets_uni 
	
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_
											ON ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_countid = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_langid = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_campid = ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_campid
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.published_at < (:now)
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.published_at <> (:nultime)
	
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
	
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_cl IN (0, 1)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_asid = (:id_asid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_ppid = (:id_ppid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_campid = (:id_campid)
										');	
	$queryAS->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$queryAS->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$queryAS->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryAS->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryAS->bindValue(':now', $now, PDO::PARAM_STR);
	$queryAS->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
	$queryAS->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$queryAS->bindValue(':id_campid', $varSQL['id_campid'], PDO::PARAM_INT);
	$queryAS->execute();
	$rowsAS = $queryAS->fetchAll(PDO::FETCH_ASSOC);
	$numAS = $queryAS->rowCount();
	
	
}

//$time_start_for_loop = microtime(true);


foreach($rowsAS as $rowAS){
	$aExportTmp = array();
	$html = '';
	$time_start_1 = microtime(true);
	
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT 
											' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid,
											' . $CONFIG['db'][0]['prefix'] . '_templates_uni.title AS title_template,
											' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_etid,
											' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid,
											' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_kcid,
											' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.file_original,
											' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page,
											' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page_number,
											' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page_dimension,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filename,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename
	
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni 
	
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_cl IN (0, 1)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
	
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_cl IN (0, 1)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.file_original = ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid
	
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_cl IN (0, 1)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid = (:id_tempid)
										');
	$query->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$query->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':id_tempid', $rowAS['id_tempid'], PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	
	//$time_end_1 = microtime(true);
	
	//$execution_time_1 = ($time_end_1 - $time_start_1);
   //echo "Total Execution Time 1 :: ".$execution_time_1." microsecs.";
	
	if($num > 0){
		
		$time_start_2 = microtime(true);
		
		$rows[0]['id_tempid'] = $rowAS['id_tempid'];
		$rows[0]['title'] = $rowAS['title'];
		$rows[0]['currency'] = $rowAS['currency'];
		$rows[0]['components'] = $rowAS['components'];
		$id_caid = $rows[0]['id_caid'];


		// save export for stats
		$queryEx = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assets2export_
												(id_asid, id_count, id_lang, id_dev, id_cl, id_promid, id_campid, id_tempid, id_pcid, id_ppid, exported_at)
												VALUES
												(:id_asid, :id_count, :id_lang, :id_dev, :id_cl, :id_promid, :id_campid, :id_tempid, :id_pcid, :id_ppid, :exported_at)
											');
		$queryEx->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
		$queryEx->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
		$queryEx->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
		$queryEx->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$queryEx->bindValue(':id_cl', 0, PDO::PARAM_INT);
		$queryEx->bindValue(':id_promid', $varSQL['id_promid'], PDO::PARAM_INT);
		$queryEx->bindValue(':id_campid', $varSQL['id_campid'], PDO::PARAM_INT);
		$queryEx->bindValue(':id_tempid', $rows[0]['id_tempid'], PDO::PARAM_INT);
		$queryEx->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
		$queryEx->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
		$queryEx->bindValue(':exported_at', $now, PDO::PARAM_STR);
		$queryEx->execute();

		
		//$time_end_2 = microtime(true);
			
		//$execution_time_2 = ($time_end_2 - $time_start_2);
		//echo "Total Execution Time 2 :: ".$execution_time_2." microsecs.";
		
		$foldername = $CONFIG['user']['id_ppid'] . '-' . str_replace(' ', '_', microtime());
		$folder = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathApp'] . 'tmp/' . $foldername;
		mkdir($folder); 
		chmod($folder, 0777);
		
		//$time_start_3 = microtime(true);
		

	
		####################################
		// Select partner contact and logo
		$queryPC = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.company_name,
												' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.address1,
												' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.zipcode,
												' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.city,
												' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.phone,
												' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.url,
												' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.email,
												' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.logo,
												' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.hide_company_name,
												' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.hide_address1,
												' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.hide_zipcode,
												' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.hide_city,
												' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.hide_phone,
												' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.hide_url,
												' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.hide_email,
												' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.hide_logo,
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

		$pagenumber = $rows[0]['page_number'];
		$aPageDimension = json_decode($rows[0]['page_dimension'], true);
		$aComponents = json_decode($rows[0]['components'], true);
		$mediafile = '';
		####################################
	
		//$time_end_3 = microtime(true);
				
		//$execution_time_3 = ($time_end_3 - $time_start_3);
		//echo "Total Execution Time 3 :: ".$execution_time_3." Micoins.";

		//$time_start_4 = microtime(true);
		
		switch($id_caid){
			// Banner
			case '1': 
				include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsApp'] . 'fu-asset-export-banner.php');
				break;
				
			// Print Ad
			case '2': 
				include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsApp'] . 'fu-asset-export-pdf.php');
				break;
				
			// Email
			case '3': 
				include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsApp'] . 'fu-asset-export-email.php');
				break;
				
			// Specsheets
			case '4': 
				include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsApp'] . 'fu-asset-export-pdf.php');
				break;
				
			// Brochure
			case '5': 
				include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsApp'] . 'fu-asset-export-pdf.php');
				break;
				
			// Flyer
			case '8': 
				include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsApp'] . 'fu-asset-export-pdf.php');
				break;
				
			// Poster
			case '9': 
				include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsApp'] . 'fu-asset-export-pdf.php');
				break;
				
			// Rollup
			case '10': 
				include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsApp'] . 'fu-asset-export-pdf.php');
				break;
				
				
		}
                                            
		
		//$time_end_4 = microtime(true);
			
		//$execution_time_4 = ($time_end_4 - $time_start_4);
		//echo "Total Execution Time 4 :: ".$execution_time_4." Micrsecs.";
		
		array_push($aExportfiles, $aExportTmp);


        //$time_start_5 = microtime(true);
		        
                ##################################################################
                // allocation of assets on the basis of contact data, if these were created by internal employees - for statistics
                if($CONFIG['user']['id_pcid'] == 29){
                    $qry = 'SELECT ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_asid,
                                    ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.content,
                                    ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_count,
                                    ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_lang
                                     FROM ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni
                                     WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_asid = (:id_asid)
                                        AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.content LIKE "%partnercontact%"
                                        AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_count <> (:nul)
                                        AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_lang <> (:nul)
                                    ';
                    $queryP = $CONFIG['dbconn'][0]->prepare($qry);
                    $queryP->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
                    $queryP->bindValue(':nul', 0, PDO::PARAM_INT);
                    $queryP->execute();
                    $rowsP = $queryP->fetchAll(PDO::FETCH_ASSOC);
                    $numP = $queryP->rowCount();
                    
                    if($numP > 0){
                        $rowP = $rowsP[0];
                    
                        $name = '';
                        $street = '';
                        $zip = '';
                        $city = '';
                        $email = '';
                        $url = '';
                        $emailCompl = '';
                        $urlCompl = '';

                        $str = $rowP['content'];
         
                        $search = '/(pc_company_name">)(.*)(<\/span>)/U';
                        preg_match($search, $str, $matches);
                        $name = $matches[2];

                        $search = '/(pc_address1">)(.*)(<\/span>)/U';
                        preg_match($search, $str, $matches);
                        $street = $matches[2];

                        $search = '/(pc_zipcode">)(.*)(<\/span>)/U';
                        preg_match($search, $str, $matches);
                        $aZip = explode(' ', $matches[2]);
                        $zip = array_shift($aZip);
                        $city = implode(' ', $aZip);

                        $search = '/(pc_email">)(.*)(<\/span>)/U';
                        preg_match($search, $str, $matches);
                        $email = $matches[2];
                        $emailCompl = $matches[2];
    
                        $search = '/(pc_url">)(.*)(<\/span>)/U';
                        preg_match($search, $str, $matches);
                        $url = $matches[2];
                        $urlCompl = $matches[2];
                        $url = str_replace('www.', '', $url);
                        $url = str_replace('https://', '', $url);
                        $url = str_replace('http://', '', $url);

                        if($name == '' && $email == '' && $url == ''){
                            $search = '/(<span>)(.*)(@)(.*)(<\/span>)/U';
                            preg_match($search, $str, $matches);
                            $email = $matches[2] . $matches[3] . $matches[4];
                            $emailCompl = $matches[2] . $matches[3] . $matches[4];

                            $search = '/(>)(.*)(www.)(.*)(<\/span>)/U';
                            preg_match($search, $str, $matches);
                            $url = $matches[3] . $matches[4];
                            $url = $matches[4];
                        }
    
                        if($email != ''){
                            $aEmail = explode('@', $email);
                            $email = $aEmail[1];
                        }
    
                        ###################################################

                        if($name != '' || $email != '' || $url != ''){
                            ($name == '') ? $nameS = 'xxx' : $nameS = $name;
                            ($email == '') ? $emailS = 'xxx' : $emailS = $email;
                            ($url == '') ? $urlS = 'xxx' : $urlS = $url;
        
                            $qry = 'SELECT ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_pcid,
                                            ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.company_name,
                                            ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.zipcode,
                                            ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.city,
                                            ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.email,
                                            ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.url,
                                            ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_countid,
                                            ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_langid
                                             FROM ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni
                                             WHERE (' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.company_name LIKE "%' . $nameS . '%"
                                                OR ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.email LIKE "%' . $emailS . '%"
                                                OR ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.url LIKE "%' . $urlS . '%")
                                                AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_pcid <> 29
                                            ';
                            $queryP2 = $CONFIG['dbconn'][0]->prepare($qry);
                            $queryP2->execute();
                            $rowsP2 = $queryP2->fetchAll(PDO::FETCH_ASSOC);
                            $numP2 = $queryP2->rowCount();
                            $pcid = '';
                            if($numP2 == 1) $pcid = $rowsP2[0]['id_pcid'];
                            if($numP2 > 1){
                                $pcid = $rowsP2[0]['id_pcid'];
                                $aPC = array();
                                foreach($rowsP2 as $rowP2){
                                    if(!array_key_exists($rowP2['id_pcid'], $aPC)) $aPC[$rowP2['id_pcid']] = 0;
                                    if(substr_count($str, $rowP2['company_name']) > 0 && $rowP2['company_name'] != ''){
                                        $aPC[$rowP2['id_pcid']] += 5;
                                    }
                                    if($rowP2['company_name'] == $name  && $rowP2['company_name'] != '') $aPC[$rowP2['id_pcid']] += 5;

                                    if(substr_count($str, $rowP2['zipcode']) > 0 && $rowP2['zipcode'] != ''){
                                        $aPC[$rowP2['id_pcid']] += 3;
                                    }
                                    if($rowP2['zipcode'] == $zip  && $rowP2['zipcode'] != '') $aPC[$rowP2['id_pcid']] += 3;

                                    if(substr_count($str, $rowP2['city']) > 0 && $rowP2['city'] != ''){
                                        $aPC[$rowP2['id_pcid']] += 3;
                                    }
                                    if($rowP2['city'] == $city  && $rowP2['city'] != '') $aPC[$rowP2['id_pcid']] += 3;

                                    if(substr_count($str, $rowP2['email']) > 0 && $rowP2['email'] != ''){
                                        $aPC[$rowP2['id_pcid']] += 1;
                                    }
                                    if($rowP2['email'] == $emailCompl  && $rowP2['email'] != '') $aPC[$rowP2['id_pcid']] += 3;

                                    if(substr_count($str, $rowP2['url']) > 0 && $rowP2['url'] != ''){
                                        $aPC[$rowP2['id_pcid']] += 1;
                                    }
                                    if($rowP2['url'] == $urlCompl  && $rowP2['url'] != '') $aPC[$rowP2['id_pcid']] += 1;


                                    if($rowP2['id_countid'] == $rowP['id_count']) $aPC[$rowP2['id_pcid']] += 5;
                                    if($rowP2['id_langid'] == $rowP['id_lang']) $aPC[$rowP2['id_pcid']] += 5;
                                    if($rowP2['id_countid'] == $rowP['id_count'] && $rowP2['id_langid'] == $rowP['id_lang']){
                                        $aPC[$rowP2['id_pcid']] += 5;
                                    }
                                }
                                arsort($aPC);
                                $pcid = key($aPC);
                            }
        
                            if($pcid == ''){
                                $queryI = $CONFIG['dbconn'][0]->prepare('
                                                        INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_
                                                        (id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
                                                        VALUES
                                                        (:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
                                                        ');
                                $queryI->bindValue(':nul', 0, PDO::PARAM_INT);
                                $queryI->bindValue(':id_cl', 1, PDO::PARAM_INT);
                                $queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
                                $queryI->bindValue(':create_from', 888888888, PDO::PARAM_INT);
                                $queryI->execute();
                                $pcid = $CONFIG['dbconn'][0]->lastInsertId();
            
            
                                if(!isset($name)) $name = '';
                                if(!isset($street)) $street = '';
                                if(!isset($zip)) $zip = '';
                                if(!isset($city)) $city = '';
                                if(!isset($emailCompl)) $emailCompl = '';
                                if(!isset($urlCompl)) $urlCompl = '';
             
                                $qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_ext
                                                        (id_count, id_lang, id_dev, id_cl, restricted_all, id_pcid, reseller_id, organisationid, company_name, address1, address2, address3, zipcode, city, id_countid, id_langid, phone, mobile, email, url, program_tier, bsd_silver, bsd_gold, bsd_diamond, parent_program_name, create_at, create_from, change_from)
                                                VALUES
                                                        (:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_pcid, :reseller_id, :organisationid, :company_name, :address1, :address2, :address3, :zipcode, :city, :id_countid, :id_langid, :phone, :mobile, :email, :url, :program_tier, :bsd_silver, :bsd_gold, :bsd_diamond, :parent_program_name, :now, :create_from, :create_from)
                                                ';
                                $queryC = $CONFIG['dbconn'][0]->prepare($qry);
                                $queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
                                $queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
                                $queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
                                $queryC->bindValue(':id_cl', 1, PDO::PARAM_INT);
                                $queryC->bindValue(':restricted_all', 0, PDO::PARAM_INT);
                                $queryC->bindValue(':id_pcid', $pcid, PDO::PARAM_INT);
                                $queryC->bindValue(':reseller_id', '', PDO::PARAM_STR);
                                $queryC->bindValue(':organisationid', '', PDO::PARAM_STR);
                                $queryC->bindValue(':company_name', $name, PDO::PARAM_STR);
                                $queryC->bindValue(':address1', $street, PDO::PARAM_STR);
                                $queryC->bindValue(':address2', '', PDO::PARAM_STR);
                                $queryC->bindValue(':address3', '', PDO::PARAM_STR);
                                $queryC->bindValue(':zipcode', $zip, PDO::PARAM_STR);
                                $queryC->bindValue(':city', $city, PDO::PARAM_STR);
                                $queryC->bindValue(':id_countid', $rowP['id_count'], PDO::PARAM_STR);
                                $queryC->bindValue(':id_langid', $rowP['id_lang'], PDO::PARAM_STR);
                                $queryC->bindValue(':phone', '', PDO::PARAM_STR);
                                $queryC->bindValue(':mobile', '', PDO::PARAM_STR);
                                $queryC->bindValue(':email', $emailCompl, PDO::PARAM_STR);
                                $queryC->bindValue(':url', $urlCompl, PDO::PARAM_STR);
                                $queryC->bindValue(':program_tier', '', PDO::PARAM_STR);
                                $queryC->bindValue(':bsd_silver', '', PDO::PARAM_STR);
                                $queryC->bindValue(':bsd_gold', '', PDO::PARAM_STR);
                                $queryC->bindValue(':bsd_diamond', '', PDO::PARAM_STR);
                                $queryC->bindValue(':parent_program_name', '#NV', PDO::PARAM_STR);
                                $queryC->bindValue(':now', $now, PDO::PARAM_STR);
                                $queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
                                $queryC->bindValue(':create_from', 888888888, PDO::PARAM_INT); 
                                $queryC->execute();
                                $numC = $queryC->rowCount();

                                $qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni
                                                        (id_count, id_lang, id_dev, id_cl, restricted_all, id_pcid, reseller_id, organisationid, company_name, address1, address2, address3, zipcode, city, id_countid, id_langid, phone, mobile, email, url, program_tier, bsd_silver, bsd_gold, bsd_diamond, parent_program_name, create_at, create_from, change_from)
                                                VALUES
                                                        (:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_pcid, :reseller_id, :organisationid, :company_name, :address1, :address2, :address3, :zipcode, :city, :id_countid, :id_langid, :phone, :mobile, :email, :url, :program_tier, :bsd_silver, :bsd_gold, :bsd_diamond, :parent_program_name, :now, :create_from, :create_from)
                                                ';
                                $queryC = $CONFIG['dbconn'][0]->prepare($qry);
                                $queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
                                $queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
                                $queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
                                $queryC->bindValue(':id_cl', 1, PDO::PARAM_INT);
                                $queryC->bindValue(':restricted_all', 0, PDO::PARAM_INT);
                                $queryC->bindValue(':id_pcid', $pcid, PDO::PARAM_INT);
                                $queryC->bindValue(':reseller_id', '', PDO::PARAM_STR);
                                $queryC->bindValue(':organisationid', '', PDO::PARAM_STR);
                                $queryC->bindValue(':company_name', $name, PDO::PARAM_STR);
                                $queryC->bindValue(':address1', $street, PDO::PARAM_STR);
                                $queryC->bindValue(':address2', '', PDO::PARAM_STR);
                                $queryC->bindValue(':address3', '', PDO::PARAM_STR);
                                $queryC->bindValue(':zipcode', $zip, PDO::PARAM_STR);
                                $queryC->bindValue(':city', $city, PDO::PARAM_STR);
                                $queryC->bindValue(':id_countid', $rowP['id_count'], PDO::PARAM_INT);
                                $queryC->bindValue(':id_langid', $rowP['id_lang'], PDO::PARAM_INT);
                                $queryC->bindValue(':phone', '', PDO::PARAM_STR);
                                $queryC->bindValue(':mobile', '', PDO::PARAM_STR);
                                $queryC->bindValue(':email', $emailCompl, PDO::PARAM_STR);
                                $queryC->bindValue(':url', $urlCompl, PDO::PARAM_STR);
                                $queryC->bindValue(':program_tier', '', PDO::PARAM_STR);
                                $queryC->bindValue(':bsd_silver', '', PDO::PARAM_STR);
                                $queryC->bindValue(':bsd_gold', '', PDO::PARAM_STR);
                                $queryC->bindValue(':bsd_diamond', '', PDO::PARAM_STR);
                                $queryC->bindValue(':parent_program_name', '#NV', PDO::PARAM_STR);
                                $queryC->bindValue(':now', $now, PDO::PARAM_STR);
                                $queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
                                $queryC->bindValue(':create_from', 888888888, PDO::PARAM_INT); 
                                $queryC->execute();
                                $numC = $queryC->rowCount();    

                            }
        

                            $aTables = array('assets', 'assetsproducts', 'assetspages', 'assetspageselements');
                            $aTab = array('ext','loc','res','tmp','uni');
        
                            foreach($aTables as $table){
                                foreach($aTab as $tab){
                                    $qry = 'UPDATE ' . $CONFIG['db'][0]['prefix'] . '_' . $table . '_' . $tab . ' SET
                                                id_pcid = (:id_pcid)
                                            WHERE id_asid = (:id_asid)
                                            ';
                                    $queryA = $CONFIG['dbconn'][0]->prepare($qry);
                                    $queryA->bindValue(':id_pcid', $pcid, PDO::PARAM_INT);
                                    $queryA->bindValue(':id_asid', $rowP['id_asid'], PDO::PARAM_INT);
                                    $queryA->execute();
                                    $numA = $queryA->rowCount();                   
                                    $numA = $queryA->rowCount(); 
                                }
                            }

                            $qry = 'UPDATE ' . $CONFIG['db'][0]['prefix'] . '_assets2export_ SET
                                        id_pcid = (:id_pcid)
                                    WHERE id_asid = (:id_asid)
                                    ';
                            $queryA = $CONFIG['dbconn'][0]->prepare($qry);
                            $queryA->bindValue(':id_pcid', $pcid, PDO::PARAM_INT);
                            $queryA->bindValue(':id_asid', $rowP['id_asid'], PDO::PARAM_INT);
                            $queryA->execute();
                            $numA = $queryA->rowCount();    

                            $qry = 'UPDATE ' . $CONFIG['db'][0]['prefix'] . '_assets2download_ SET
                                        id_pcid = (:id_pcid)
                                    WHERE id_asid = (:id_asid)
                                    ';
                            $queryA = $CONFIG['dbconn'][0]->prepare($qry);
                            $queryA->bindValue(':id_pcid', $pcid, PDO::PARAM_INT);
                            $queryA->bindValue(':id_asid', $rowP['id_asid'], PDO::PARAM_INT);
                            $queryA->execute();
                            $numA = $queryA->rowCount();    
                        }
                    }
                }
				
				
				//$time_end_5 = microtime(true);
							
				//$execution_time_5 = ($time_end_5 - $time_start_5);
				//echo "Total Execution Time 5 :: ".$execution_time_5." Microsecs.";
                ##################################################################
	}
}

//$time_end_for_loop = microtime(true);

//$execution_time_for_loop = ($time_end_for_loop - $time_start_for_loop)/60;
//echo "Total Execution Time FOR LOOP :: ".$execution_time_for_loop." Mins.";

//var_dump($aExportfiles);

if(count($aExportfiles) > 1){
	//$time_start = microtime(true);
	//$currenttime = date('h:i:s:u');
	//list($hrs,$mins,$secs,$msecs) = explode(':',$currenttime);
	//echo " START 9 => $hrs:$mins:$secs\n";
	
	$folder = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathApp'] . 'tmp/' . $CONFIG['user']['id_ppid'] . '-' . str_replace(' ', '_', microtime());
	mkdir($folder); 
	chmod($folder, 0777);
	
	$zip = new ZipArchive();
	$fileZIP = $folder . '/output.zip';

	if ($zip->open($fileZIP, ZipArchive::CREATE)!==TRUE) {
		exit();
	}
	foreach($aExportfiles as $key => $aExportfile){
		//if(file_exists($aExportfile['filesys_filename']))
		//	echo $aExportfile['filesys_filename'];
		$zip->addFile($aExportfile['filesys_filename'], str_replace(' ', '_', str_replace(':', '-', $aExportfile['filename_template'])));
	}
	$zip->close();
	
	$out['filename'] = $aExportfiles[0]['title_asset'] . '.zip';
	$out['filesys_filename'] = $fileZIP;
	$out['folder'] = $folder;
	
	$out['thumbnail'] = '';
	foreach($aExportfiles as $key => $aExportfile){
		if(substr($aExportfile['filesys_filename'], -4) == '.pdf'){
			copy($aExportfile['folder'] . '/' . $aExportfile['thumbnail'], $CONFIG['system']['directoryRoot'] . 'assetimages/assets_thumbnails/' . $aExportfile['thumbnail']);
			$out['thumbnail'] = $aExportfile['thumbnail'];
			break;
		}
	}
	if($out['thumbnail'] == ''){
		copy($aExportfiles[0]['folder'] . '/' . $aExportfiles[0]['thumbnail'], $CONFIG['system']['directoryRoot'] . 'assetimages/assets_thumbnails/' . $aExportfiles[0]['thumbnail']);
		$out['thumbnail'] = $aExportfiles[0]['thumbnail'];
	}
	
	foreach($aExportfiles as $key => $aExportfile){
		unlink($aExportfile['filesys_filename']);
		unlink($aExportfile['folder'] . '/' . $aExportfile['thumbnail']);
		rmdir($aExportfile['folder']);
	}
	//var_dump($aExportfiles);
	
	//$time_end = microtime(true);
	//$currenttime = date('h:i:s:u');
	//list($hrs,$mins,$secs,$msecs) = explode(':',$currenttime);
	//echo " END 9 => $hrs:$mins:$secs\n";
	//$execution_time = ($time_end - $time_start)/60;
	//echo "Total Execution Time 9 :: ".$execution_time." Mins.";
}else{
	//$time_start = microtime(true);
	//$currenttime = date('h:i:s:u');
	//list($hrs,$mins,$secs,$msecs) = explode(':',$currenttime);
	//echo " START 10 => $hrs:$mins:$secs\n";
	if(file_exists($aExportfiles[0]['folder'] . '/' . $aExportfiles[0]['thumbnail'])) rename($aExportfiles[0]['folder'] . '/' . $aExportfiles[0]['thumbnail'], $CONFIG['system']['directoryRoot'] . 'assetimages/assets_thumbnails/' . $aExportfiles[0]['thumbnail']);
	
	$out['filename'] = $aExportfiles[0]['filename'];
	$out['filesys_filename'] = $aExportfiles[0]['filesys_filename'];
	$out['thumbnail'] = $aExportfiles[0]['thumbnail'] . '?t=' . time();
	$out['folder'] = $aExportfiles[0]['folder'];
	//$time_end = microtime(true);
	//$currenttime = date('h:i:s:u');
	//list($hrs,$mins,$secs,$msecs) = explode(':',$currenttime);
	//echo " END 10 => $hrs:$mins:$secs\n";
	//$execution_time = ($time_end - $time_start)/60;
	//echo "Total Execution Time 10 :: ".$execution_time." Mins.";
}

		
echo json_encode($out);

?>