<?php
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-app.php');
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$out = array();
$out['printer'] = array();
$out['printer'][0] = array();
$out['components'] = array();


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
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpeid IN (' . implode(',', json_decode($varSQL['tpeid'], true)) . ')
';

$queryTPE = $CONFIG['dbconn'][0]->prepare($queryStrTPE);
$queryTPE->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$queryTPE->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$queryTPE->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryTPE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryTPE->bindValue(':id_tempid', $varSQL['tempid'], PDO::PARAM_INT);
$queryTPE->execute();
$rowsTPE = $queryTPE->fetchAll(PDO::FETCH_ASSOC);
$numTPE = $queryTPE->rowCount();

foreach($rowsTPE as $rowTPE){
	if(!array_key_exists('page_' . $rowTPE['page_id'], $aTPE)){
		$aTPE['page_' . $rowTPE['page_id']] = array();
	}
		
	if(!array_key_exists('compboxOuter_' . $rowTPE['id_tpeid'], $aTPE['page_' . $rowTPE['page_id']])){
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']] = array();
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['id_tpeid'] = $rowTPE['id_tpeid'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['id_caid'] = $rowTPE['id_caid'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['id_tpid'] = $rowTPE['id_tpid'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['id_tcid'] = $rowTPE['id_tcid'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['id_pid'] = $varSQL['pid'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['pageid'] = $rowTPE['page_id'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['page'] = $rowTPE['page'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['width'] = $rowTPE['width'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['height'] = $rowTPE['height'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['left'] = $rowTPE['position_left'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['top'] = $rowTPE['position_top'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['fontsize'] = $rowTPE['fontsize'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['fontcolor'] = $rowTPE['fontcolor'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['fontstyle'] = $rowTPE['fontstyle'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['background_color'] = $rowTPE['background_color'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content_add'] = array();
		//$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = ($rowTPE['content_asset'] != '') ? $rowTPE['content_asset'] : $rowTPE['content'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowTPE['content'];
		
		switch($rowTPE['id_tcid']){
			// Product name
			case '3':
				$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowsPr[0]['mkt_name'];
				break;
	
			// Product category
			case '9':
				$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowsPr[0]['prod_type'];
				break;
			
			// Product image
			case '12':
				$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $image;
				break;
			
			// PN
			case '4':
				$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowsPr[0]['pn_text'];
				break;
	
			// Tagline
			case '13':
				$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowsPr[0]['tagline'];
				break;
	
			// Short description
			case '5':
				$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowsPr[0]['mkt_paragraph'];
				break;
	
			// 25 word description
			case '6':
				$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowsPr[0]['description_text_25'];
				break;
	
			// 50 word description
			case '7':
				$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowsPr[0]['description_text_50'];
				break;
	
			// 100 word description
			case '8':
				$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowsPr[0]['description_text_100'];
				break;
	
			// Pricefield
			case '2':
//				preg_match('/[0-9\.,]*/', $aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'], $reg);
//				$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $reg[0] . ' ' . $CONFIG['user']['configCountry']['currency'];
				if($aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] == '') $aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $TEXT['yourPrice'];
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
				
				$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowsCS[0]['modultext'];
				break;

			case '15': // Fileupload
				break;
	
			// Partner logo
			case '11':
				$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = '<div class="componentPartnerlogo"><img src="' . $CONFIG['system']['directoryInstallation'] . 'media/' . $rows[0]['filesys_filename'] . '"></div>';
				break;

			// Partner contact
			case '10':
				$addrTemp1 = array();
				if(trim($rows[0]['company_name']) != '') array_push($addrTemp1, '<span>' . $rows[0]['company_name'] . '</span>');
				if(trim($rows[0]['contactname']) != '') array_push($addrTemp1, '<span>' . $rows[0]['contactname'] . '</span>');
				if(trim($rows[0]['address1']) != '') array_push($addrTemp1, '<span>' . $rows[0]['address1'] . '</span>');
				if(trim($rows[0]['zipcode']) != '' || trim($rows[0]['city'])) array_push($addrTemp1, '<span>' . $rows[0]['zipcode'] . ' ' . $rows[0]['city'] . '</span>');
				$addrTemp2 = array();
				if(trim($rows[0]['phone']) != '') array_push($addrTemp2, '<span>' . $rows[0]['phone'] . '</span>');
				if(trim($rows[0]['email']) != '') array_push($addrTemp2, '<span>' . $rows[0]['email'] . '</span>');
				if(trim($rows[0]['url']) != '') array_push($addrTemp2, '<span>' . $rows[0]['url'] . '</span>');

				$conTmp = '<div class="dummyPartnercontact"><span>';
				$conTmp .= implode('<span class="contactDelimiter"></span>', $addrTemp1);
				$conTmp .= '</span>';
				$conTmp .= '<span>';
				$conTmp .= implode('<span class="contactDelimiter"></span>', $addrTemp2);
				$conTmp .= '</span></div>';
				$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $conTmp;
				break;
			
			// Partner contact / logo combination
			case '16':
				$addrTemp1 = array();
				if(trim($rows[0]['company_name']) != '') array_push($addrTemp1, '<span>' . $rows[0]['company_name'] . '</span>');
				if(trim($rows[0]['contactname']) != '') array_push($addrTemp1, '<span>' . $rows[0]['contactname'] . '</span>');
				if(trim($rows[0]['address1']) != '') array_push($addrTemp1, '<span>' . $rows[0]['address1'] . '</span>');
				if(trim($rows[0]['zipcode']) != '' || trim($rows[0]['city'])) array_push($addrTemp1, '<span>' . $rows[0]['zipcode'] . ' ' . $rows[0]['city'] . '</span>');
				$addrTemp2 = array();
				if(trim($rows[0]['phone']) != '') array_push($addrTemp2, '<span>' . $rows[0]['phone'] . '</span>');
				if(trim($rows[0]['email']) != '') array_push($addrTemp2, '<span>' . $rows[0]['email'] . '</span>');
				if(trim($rows[0]['url']) != '') array_push($addrTemp2, '<span>' . $rows[0]['url'] . '</span>');

				$conTmp = '<div class="partnerContactCombination contactalignleft">';
				$conTmp .= '<div class="componentPartnerlogo"><img src="' . $CONFIG['system']['directoryInstallation'] . 'media/' . $rows[0]['filesys_filename'] . '"></div>';
				$conTmp .= '<div class="dummyPartnercontact"><span>';
				$conTmp .= implode('<span class="contactDelimiter"></span>', $addrTemp1);
				$conTmp .= '</span>';
				$conTmp .= '<span>';
				$conTmp .= implode('<span class="contactDelimiter"></span>', $addrTemp2);
				$conTmp .= '</span></div>';
				$conTmp .= '</div>';
				$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $conTmp;
				break;
			
			// Color area
			case '18':
				break;
			
			// Call to action
			case '19':
				$aTPE['page_' . $rowTPE['page_id'] . '_' . $out['apid']]['compboxOuter_' . $rowTPE['id_tpeid'] . '_' . $out['apid']]['content'] = $TEXT['ordernow'];
				break;
		}

		
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['contentOrg'] = $aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['maxchars'] = $rowTPE['max_char'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['alignment'] = $rowTPE['alignment'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['verticalalignment'] = $rowTPE['verticalalignment'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['fixed'] = $rowTPE['fixed'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['editable'] = $rowTPE['editable'];
		$aTPE['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['active'] = $rowTPE['active'];
	}
}



$out['components'] = $aTPE;




echo json_encode($out);

?>