<?php 
###############################
// read components
$aTPE = array('id_temp' => $varSQL['id_tempid'], 'pages' => array());

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
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.fixed,
		' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_pid,
		' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.content AS content_asset,
		' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.content_add AS content_add_asset
	FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni 
	
	LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp
		ON ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_tpeid = ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpeid
			AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_count = (:id_count)
			AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_lang = (:id_lang)
			AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.del = (:nultime)
			AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_ppid = (:id_ppid)
			AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_pcid = (:id_pcid)
			AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_tmp.id_asid = (:id_asid)

	WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_count = (:id_count)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_lang = (:id_lang)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_dev = (:id_dev)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.del = (:nultime)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tempid = (:id_tempid)
';

$queryTPE = $CONFIG['dbconn'][0]->prepare($queryStrTPE);
$queryTPE->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$queryTPE->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$queryTPE->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryTPE->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
$queryTPE->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryTPE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryTPE->bindValue(':id_tempid', $varSQL['id_tempid'], PDO::PARAM_INT);
$queryTPE->bindValue(':id_asid', $out['id_asid'], PDO::PARAM_INT);
$queryTPE->execute();
$rowsTPE = $queryTPE->fetchAll(PDO::FETCH_ASSOC);
$numTPE = $queryTPE->rowCount();


if($numTPE > 0){
	$query = $CONFIG['dbconn'][0]->prepare('
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
	$query->bindValue(':id_count', 0, PDO::PARAM_INT);
	$query->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$query->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();

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
	$rows[0]['contactname'] = $rowsPP[0]['contactname'];
	if($rows[0]['contactname'] == '') $rows[0]['contactname'] = $rowsPP[0]['firstname'] . ' ' . $rowsPP[0]['lastname'];
	$rows[0]['hide_contactname'] = $rowsPP[0]['hide_contactname'];
	$rows[0]['phone'] = $rowsPP[0]['phone'];
	$rows[0]['email'] = $rowsPP[0]['email'];
	$rows[0]['hide_phone'] = $rowsPP[0]['hide_phone'];
	$rows[0]['hide_email'] = $rowsPP[0]['hide_email'];
		
	if($rows[0]['hide_company_name'] == 1) $rows[0]['company_name'] = '';
	if($rows[0]['hide_address1'] == 1) $rows[0]['address1'] = '';
	if($rows[0]['hide_zipcode'] == 1) $rows[0]['zipcode'] = '';
	if($rows[0]['hide_city'] == 1) $rows[0]['city'] = '';
	if($rows[0]['hide_phone'] == 1) $rows[0]['phone'] = '';
	if($rows[0]['hide_email'] == 1) $rows[0]['email'] = '';
	if($rows[0]['hide_url'] == 1) $rows[0]['url'] = '';
	if($rows[0]['hide_contactname'] == 1) $rows[0]['contactname'] = '';

	foreach($rowsTPE as $rowTPE){
		if(!array_key_exists('page_' . $rowTPE['page_id'], $aTPE['pages'])){
			$aTPE['pages']['page_' . $rowTPE['page_id']] = array();
		}
			
		if(!array_key_exists('compboxOuter_' . $rowTPE['id_tpeid'], $aTPE['pages']['page_' . $rowTPE['page_id']])){
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']] = array();
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['id_tpeid'] = $rowTPE['id_tpeid'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['id_caid'] = $rowTPE['id_caid'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['id_tpid'] = $rowTPE['id_tpid'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['id_tcid'] = $rowTPE['id_tcid'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['id_pid'] = $rowTPE['id_pid'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['elementtitle'] = $rowTPE['elementtitle'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['pageid'] = $rowTPE['page_id'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['page'] = $rowTPE['page'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['width'] = $rowTPE['width'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['height'] = $rowTPE['height'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['left'] = $rowTPE['position_left'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['top'] = $rowTPE['position_top'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['fontsize'] = $rowTPE['fontsize'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['fontcolor'] = $rowTPE['fontcolor'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['fontstyle'] = $rowTPE['fontstyle'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['background_color'] = $rowTPE['background_color'];
			$content_add = ($rowTPE['content_add_asset'] != '') ? $rowTPE['content_add_asset'] : $rowTPE['content_add'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content_add'] = ($content_add != '') ? json_decode($content_add, true) : array();
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = ($rowTPE['content_asset'] != '') ? $rowTPE['content_asset'] : $rowTPE['content'];

			switch($rowTPE['id_tcid']){
//				// Product name
//				case '3':
//					$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowS['mkt_name'];
//					break;
//		
//				// Product category
//				case '9':
//					$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowS['prod_type'];
//					break;
//				
//				// Product image
//				case '12':
//					break;
//				
//				// PN
//				case '4':
//					$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowS['pn_text'];
//					break;
//		
//				// Tagline
//				case '13':
//					$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowS['tagline'];
//					break;
//		
//				// Short description
//				case '5':
//					$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowS['mkt_paragraph'];
//					break;
//		
//				// 25 word description
//				case '6':
//					$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowS['description_text_25'];
//					break;
//		
//				// 50 word description
//				case '7':
//					$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowS['description_text_50'];
//					break;
//		
//				// 100 word description
//				case '8':
//					$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowS['description_text_100'];
//					break;
		
				// Pricefield
				case '2':
//					preg_match('/[0-9\.,]*/', $aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'], $reg);
//					$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $reg[0] . ' ' . $CONFIG['user']['configCountry']['currency'];
					if($aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] == '') $aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $TEXT['yourPrice'];
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
					
					$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowsCS[0]['modultext'];
					break;

				case '15': // Fileupload
					break;
	
				// Partner logo
				case '11':
//					// add some transparent border because acrobat pdf is bad
//					$logofile = '../../media/' . $rows[0]['filesys_filename'];
//					$logofileNew = '../../assets/' . md5($rows[0]['filesys_filename']) . '.png';
//					if(!file_exists($logofileNew)){
//						system('convert -border 5x5 -bordercolor transparent ' . $logofile . ' ' . $logofileNew);
//					}
//					$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = '<div class="componentPartnerlogo"><img src="' . $CONFIG['system']['directoryInstallation'] . 'assets/' . md5($rows[0]['filesys_filename']) . '.png"></div>';
				
					$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = '<div class="componentPartnerlogo"><img src="' . $CONFIG['system']['directoryInstallation'] . 'media/' . $rows[0]['filesys_filename'] . '"></div>';
					break;
	
				// Partner contact
				case '10':
					$addrTemp1 = array();
					if(trim($rows[0]['company_name']) != '') array_push($addrTemp1, '<span class="pc_single pc_company_name">' . $rows[0]['company_name'] . '</span>');
					if(trim($rows[0]['contactname']) != '') array_push($addrTemp1, '<span class="pc_single pc_contactname">' . $rows[0]['contactname'] . '</span>');
					if(trim($rows[0]['address1']) != '') array_push($addrTemp1, '<span class="pc_single pc_address1">' . $rows[0]['address1'] . '</span>');
					if(trim($rows[0]['zipcode']) != '' || trim($rows[0]['city'])) array_push($addrTemp1, '<span class="pc_single pc_zipcode">' . $rows[0]['zipcode'] . ' ' . $rows[0]['city'] . '</span>');
					$addrTemp2 = array();
					if(trim($rows[0]['phone']) != '') array_push($addrTemp2, '<span class="pc_single pc_phone">' . $rows[0]['phone'] . '</span>');
					if(trim($rows[0]['email']) != '') array_push($addrTemp2, '<span class="pc_single pc_email">' . $rows[0]['email'] . '</span>');
					if(trim($rows[0]['url']) != '') array_push($addrTemp2, '<span class="pc_single pc_url">' . $rows[0]['url'] . '</span>');
	
					$conTmp = '<div class="dummyPartnercontact"><span>';
					$conTmp .= implode('<span class="contactDelimiter"></span>', $addrTemp1);
					$conTmp .= '</span>';
					$conTmp .= '<span>';
					$conTmp .= implode('<span class="contactDelimiter"></span>', $addrTemp2);
					$conTmp .= '</span></div>';
					$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $conTmp;
					break;
				
				// Partner contact / logo combination
				case '16':
					$addrTemp1 = array();
					if(trim($rows[0]['company_name']) != '') array_push($addrTemp1, '<span class="pc_single pc_company_name">' . $rows[0]['company_name'] . '</span>');
					if(trim($rows[0]['contactname']) != '') array_push($addrTemp1, '<span class="pc_single pc_contactname">' . $rows[0]['contactname'] . '</span>');
					if(trim($rows[0]['address1']) != '') array_push($addrTemp1, '<span class="pc_single pc_address1">' . $rows[0]['address1'] . '</span>');
					if(trim($rows[0]['zipcode']) != '' || trim($rows[0]['city'])) array_push($addrTemp1, '<span class="pc_single pc_zipcode">' . $rows[0]['zipcode'] . ' ' . $rows[0]['city'] . '</span>');
					$addrTemp2 = array();
					if(trim($rows[0]['phone']) != '') array_push($addrTemp2, '<span class="pc_single pc_phone">' . $rows[0]['phone'] . '</span>');
					if(trim($rows[0]['email']) != '') array_push($addrTemp2, '<span class="pc_single pc_email">' . $rows[0]['email'] . '</span>');
					if(trim($rows[0]['url']) != '') array_push($addrTemp2, '<span class="pc_single pc_url">' . $rows[0]['url'] . '</span>');
	
					$conTmp = '<div class="partnerContactCombination contactalignleft">';
					$conTmp .= '<div class="componentPartnerlogo"><img src="' . $CONFIG['system']['directoryInstallation'] . 'media/' . $rows[0]['filesys_filename'] . '"></div>';
					$conTmp .= '<div class="dummyPartnercontact"><span>';
					$conTmp .= implode('<span class="contactDelimiter"></span>', $addrTemp1);
					$conTmp .= '</span>';
					$conTmp .= '<span>';
					$conTmp .= implode('<span class="contactDelimiter"></span>', $addrTemp2);
					$conTmp .= '</span></div>';
					$conTmp .= '</div>';
					$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $conTmp;
					break;
			
				// Color area
				case '18':
					break;
			
				// Call to action
				case '19':
					break;
			}
			
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['contentOrg'] = $aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['maxchars'] = $rowTPE['max_char'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['alignment'] = $rowTPE['alignment'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['verticalalignment'] = $rowTPE['verticalalignment'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['fixed'] = $rowTPE['fixed'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['editable'] = $rowTPE['editable'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['active'] = $rowTPE['active'];
		}
	}
}




####################################################
// add products
####################################################
$queryS = $CONFIG['dbconn'][0]->prepare('SELECT
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
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.image, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_piid, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.not_lpmd, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.content_add, 
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.del
										FROM ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_asid = (:id_asid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_pcid = (:id_pcid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_tmp.id_ppid = (:id_ppid)
									');
$queryS->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$queryS->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$queryS->bindValue(':nul', 0, PDO::PARAM_INT);
$queryS->bindValue(':id_asid', $out['id_asid'], PDO::PARAM_INT);
$queryS->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
$queryS->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryS->execute();
$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
$numS = $queryS->rowCount();

foreach($rowsS as $rowS){
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
	$queryTPE->bindValue(':id_tempid', $rowS['id_tempid'], PDO::PARAM_INT);
	$queryTPE->bindValue(':id_tpid', $rowS['id_tpid'], PDO::PARAM_INT);
	$queryTPE->bindValue(':page', 2, PDO::PARAM_INT);
	$queryTPE->execute();
	$rowsTPE = $queryTPE->fetchAll(PDO::FETCH_ASSOC);
	$numTPE = $queryTPE->rowCount();

	foreach($rowsTPE as $rowTPE){
		if(!array_key_exists('page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid'], $aTPE['pages'])){
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']] = array();
		}
		if(!array_key_exists('compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid'], $aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']])){
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']] = array();
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['id_apid'] = $rowS['id_apid'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['id_pid'] = $rowS['id_pid'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['id_tpeid'] = $rowTPE['id_tpeid'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['id_caid'] = $rowTPE['id_caid'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['id_tpid'] = $rowTPE['id_tpid'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['id_tcid'] = $rowTPE['id_tcid'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['elementtitle'] = $rowTPE['elementtitle'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['pageid'] = $rowTPE['page_id'] . '_' . $rowS['id_apid'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['page'] = $rowTPE['page'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['width'] = $rowTPE['width'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['height'] = $rowTPE['height'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['left'] = $rowTPE['position_left'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['top'] = $rowTPE['position_top'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['fontsize'] = $rowTPE['fontsize'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['fontcolor'] = $rowTPE['fontcolor'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['fontstyle'] = $rowTPE['fontstyle'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['background_color'] = $rowTPE['background_color'];
			$content_add = $rowS['content_add'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content_add'] = ($content_add != '') ? json_decode($content_add, true) : array();
			
			//$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = ($rowTPE['content_asset'] != '') ? $rowTPE['content_asset'] : $rowTPE['content'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = $rowTPE['content'];
			
			switch($rowTPE['id_tcid']){
				// Product name
				case '3':
					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = $rowS['mkt_name'];
					break;
		
				// Product category
				case '9':
					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = $rowS['prod_type'];
					break;
				
				// Product image
				case '12':
					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = $rowS['image'];
					break;
				
				// PN
				case '4':
					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = $rowS['pn_text'];
					break;
		
				// Tagline
				case '13':
					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = $rowS['tagline'];
					break;
		
				// Short description
				case '5':
					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = $rowS['mkt_paragraph'];
					break;
		
				// 25 word description
				case '6':
					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = $rowS['description_text_25'];
					break;
		
				// 50 word description
				case '7':
					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = $rowS['description_text_50'];
					break;
		
				// 100 word description
				case '8':
					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = $rowS['description_text_100'];
					break;
		
				// Pricefield
				case '2':
//					preg_match('/[0-9\.,]*/', $rowS['price'], $reg);
//					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = $reg[0] . ' ' . $CONFIG['user']['configCountry']['currency'];
					//if($aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] == '') $aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = $TEXT['yourPrice'];
					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = $rowS['price'];
					break;
				
				// WYSIWYG
				case '14':
					$aCon = json_decode($rowS['not_lpmd'], true);
					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = (isset($aCon[$rowTPE['id_tpeid']])) ? $aCon[$rowTPE['id_tpeid']] : '';
					break;
				
				// Textfield
				case '1':
					$aCon = json_decode($rowS['not_lpmd'], true);
					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = (isset($aCon[$rowTPE['id_tpeid']])) ? $aCon[$rowTPE['id_tpeid']] : '';
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
					
					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = $rowsCS[0]['modultext'];
					break;
	
				case '15': // Fileupload
					break;
	
				// Partner logo
				case '11':
//					// add some transparent border because acrobat pdf is bad
//					$logofile = '../../media/' . $rows[0]['filesys_filename'];
//					$logofileNew = '../../assets/' . md5($rows[0]['filesys_filename']) . '.png';
//					if(!file_exists($logofileNew)){
//						system('convert -border 5x5 -bordercolor transparent ' . $logofile . ' ' . $logofileNew);
//					}
//					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = '<div class="componentPartnerlogo"><img src="' . $CONFIG['system']['directoryInstallation'] . 'assets/' . md5($rows[0]['filesys_filename']) . '.png"></div>';
				
					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = '<div class="componentPartnerlogo"><img src="' . $CONFIG['system']['directoryInstallation'] . 'media/' . $rows[0]['filesys_filename'] . '"></div>';
					break;
	
				// Partner contact
				case '10':
					$addrTemp1 = array();
					if(trim($rows[0]['company_name']) != '') array_push($addrTemp1, '<span class="pc_single pc_company_name">' . $rows[0]['company_name'] . '</span>');
					if(trim($rows[0]['contactname']) != '') array_push($addrTemp1, '<span class="pc_single pc_contactname">' . $rows[0]['contactname'] . '</span>');
					if(trim($rows[0]['address1']) != '') array_push($addrTemp1, '<span class="pc_single pc_address1">' . $rows[0]['address1'] . '</span>');
					if(trim($rows[0]['zipcode']) != '' || trim($rows[0]['city'])) array_push($addrTemp1, '<span class="pc_single pc_zipcode">' . $rows[0]['zipcode'] . ' ' . $rows[0]['city'] . '</span>');
					$addrTemp2 = array();
					if(trim($rows[0]['phone']) != '') array_push($addrTemp2, '<span class="pc_single pc_phone">' . $rows[0]['phone'] . '</span>');
					if(trim($rows[0]['email']) != '') array_push($addrTemp2, '<span class="pc_single pc_email">' . $rows[0]['email'] . '</span>');
					if(trim($rows[0]['url']) != '') array_push($addrTemp2, '<span class="pc_single pc_url">' . $rows[0]['url'] . '</span>');
	
					$conTmp = '<div class="dummyPartnercontact"><span>';
					$conTmp .= implode('<span class="contactDelimiter"></span>', $addrTemp1);
					$conTmp .= '</span>';
					$conTmp .= '<span>';
					$conTmp .= implode('<span class="contactDelimiter"></span>', $addrTemp2);
					$conTmp .= '</span></div>';
					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = $conTmp;
					break;

				// Partner contact / logo combination
				case '16':
					$addrTemp1 = array();
					if(trim($rows[0]['company_name']) != '') array_push($addrTemp1, '<span class="pc_single pc_company_name">' . $rows[0]['company_name'] . '</span>');
					if(trim($rows[0]['contactname']) != '') array_push($addrTemp1, '<span class="pc_single pc_contactname">' . $rows[0]['contactname'] . '</span>');
					if(trim($rows[0]['address1']) != '') array_push($addrTemp1, '<span class="pc_single pc_address1">' . $rows[0]['address1'] . '</span>');
					if(trim($rows[0]['zipcode']) != '' || trim($rows[0]['city'])) array_push($addrTemp1, '<span class="pc_single pc_zipcode">' . $rows[0]['zipcode'] . ' ' . $rows[0]['city'] . '</span>');
					$addrTemp2 = array();
					if(trim($rows[0]['phone']) != '') array_push($addrTemp2, '<span class="pc_single pc_phone">' . $rows[0]['phone'] . '</span>');
					if(trim($rows[0]['email']) != '') array_push($addrTemp2, '<span class="pc_single pc_email">' . $rows[0]['email'] . '</span>');
					if(trim($rows[0]['url']) != '') array_push($addrTemp2, '<span class="pc_single pc_url">' . $rows[0]['url'] . '</span>');
	
					$conTmp = '<div class="partnerContactCombination contactalignleft">';
					$conTmp .= '<div class="componentPartnerlogo"><img src="' . $CONFIG['system']['directoryInstallation'] . 'media/' . $rows[0]['filesys_filename'] . '"></div>';
					$conTmp .= '<div class="dummyPartnercontact"><span>';
					$conTmp .= implode('<span class="contactDelimiter"></span>', $addrTemp1);
					$conTmp .= '</span>';
					$conTmp .= '<span>';
					$conTmp .= implode('<span class="contactDelimiter"></span>', $addrTemp2);
					$conTmp .= '</span></div>';
					$conTmp .= '</div>';
					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = $conTmp;
					break;
			
				// Color area
				case '18':
					break;
			
				// Call to action
				case '19':
					$aCon = json_decode($rowS['not_lpmd'], true);
					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'] = (isset($aCon[$rowTPE['id_tpeid']])) ? $aCon[$rowTPE['id_tpeid']] : '';

					$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content_add'] = ($rowS['content_add'] != '') ? json_decode($rowS['content_add'], true) : array();
					break;
			}
			
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['contentOrg'] = $aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['content'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['maxchars'] = $rowTPE['max_char'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['alignment'] = $rowTPE['alignment'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['verticalalignment'] = $rowTPE['verticalalignment'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['fixed'] = $rowTPE['fixed'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['editable'] = $rowTPE['editable'];
			$aTPE['pages']['page_' . $rowTPE['page_id'] . '_' . $rowS['id_apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $rowS['id_apid']]['active'] = $rowTPE['active'];
		}
	}
}
?>