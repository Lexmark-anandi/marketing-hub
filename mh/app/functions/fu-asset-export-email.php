<?php
	if($rows[0]['id_etid'] > 0){
		$queryF = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.css,
												' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.html,
												' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.products_settings,
												' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.products_per_row
											FROM ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni 
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_cl IN (0, 1)
												AND ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_etid = (:id_etid)
											');
		$queryF->bindValue(':id_count', 0, PDO::PARAM_INT);
		$queryF->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$queryF->bindValue(':id_dev', 0, PDO::PARAM_INT); 
		$queryF->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryF->bindValue(':id_etid', $rows[0]['id_etid'], PDO::PARAM_INT);
		$queryF->execute();
		$rowsF = $queryF->fetchAll(PDO::FETCH_ASSOC);
		$numF = $queryF->rowCount();
		
		$aPT = explode(',', $rowsF[0]['products_settings']); 
		$aPTrow = explode(',', $rowsF[0]['products_per_row']);
		
		$queryFp = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_emailproducttemplates_uni.id_eptid,
												' . $CONFIG['db'][0]['prefix'] . '_emailproducttemplates_uni.html
											FROM ' . $CONFIG['db'][0]['prefix'] . '_emailproducttemplates_uni 
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_emailproducttemplates_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_emailproducttemplates_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_emailproducttemplates_uni.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . '_emailproducttemplates_uni.id_cl IN (0, 1)
												AND ' . $CONFIG['db'][0]['prefix'] . '_emailproducttemplates_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_emailproducttemplates_uni.id_eptid IN (' . implode(',', $aPT) . ')
											');
		$queryFp->bindValue(':id_count', 0, PDO::PARAM_INT);
		$queryFp->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$queryFp->bindValue(':id_dev', 0, PDO::PARAM_INT); 
		$queryFp->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryFp->execute();
		$rowsFp = $queryFp->fetchAll(PDO::FETCH_ASSOC);
		$numFp = $queryFp->rowCount();
		
		$aProductTemplates = array();
		foreach($rowsFp as $rowFp){
			$aProductTemplates[$rowFp['id_eptid']] = $rowFp['html'];
		}
		
		
		$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
	<style>
	#outlook a {
		padding: 0;
	}
	.ExternalClass {
		width: 100%;
	}
	.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
		line-height: 100%;
	}
	table {
		mso-table-lspace: 0pt;
		mso-table-rspace: 0pt;
	}
	img {
		-ms-interpolation-mode: bicubic;
	}
	body {
		-webkit-text-size-adjust: 100%;
		-ms-text-size-adjust: 100%;
		margin: 0;
		padding: 0;
	}
	img { 
		border: 0 none;
		height: auto;
		line-height: 100%;
		outline: none;
		text-decoration: none;
		display: block; 
	}
	a img { 
		border: 0 none; 
	}
	table, td { 
		border-collapse: collapse; 
		/*border-collapse: separate;   Falls border-radius verwendet wird */ 
	}
	h1, h2, h3, h4, h5, h6, p, ul, li, div, input, select, textarea, button, a {
		box-sizing: border-box;
		outline: 0;
	}
	
	' . $rowsF[0]['css'] . '
	</style>
	</head>
	
	<body>
	' . $rowsF[0]['html'] . '
	</body>
	</html>';
		
		
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
			' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.content AS content_asset,
			' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.content_add AS content_add_asset
		FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni 
		
		LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni
			ON ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_tpeid = ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpeid
				AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_count = (:id_count)
				AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_lang = (:id_lang)
				AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.del = (:nultime)
				AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_ppid = (:id_ppid)
				AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_asid = (:id_asid)
	
		WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_count = (:id_count)
			AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_lang = (:id_lang)
			AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_dev = (:id_dev)
			AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_cl IN (0, 1)
			AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.del = (:nultime)
			AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tempid = (:id_tempid)
			AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.page = (:page)
	';
	$queryTPE = $CONFIG['dbconn'][0]->prepare($queryStrTPE);
	$queryTPE->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$queryTPE->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$queryTPE->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$queryTPE->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryTPE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryTPE->bindValue(':id_tempid', $rows[0]['id_tempid'], PDO::PARAM_INT);
	$queryTPE->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
	$queryTPE->bindValue(':page', 1, PDO::PARAM_INT);
	$queryTPE->execute();
	$rowsTPE = $queryTPE->fetchAll(PDO::FETCH_ASSOC);
	$numTPE = $queryTPE->rowCount();
	
	foreach($rowsTPE as $rowTPE){
		$style = '';
//		$style .= 'position:absolute;';
//		$style .= 'top:' . $rowTPE['position_top'] . '%;';
//		$style .= 'left:' . $rowTPE['position_left'] . '%;';
//		$style .= 'width:' . $rowTPE['width'] . '%;';
//		$style .= 'height:' . $rowTPE['height'] . '%;';
		$style .= 'font-size:' . $rowTPE['fontsize'] . 'pt;';
		$style .= 'color:' . $rowTPE['fontcolor'] . ';';
		if($rowTPE['background_color'] != '') $style .= 'background-color:' . $rowTPE['background_color'] . ';';
		if($rowTPE['fontstyle'] == 1) $style .= 'font-weight:700;';
		if($rowTPE['fontstyle'] == 2) $style .= 'font-style:italic;';
		if($rowTPE['fontstyle'] == 3) $style .= 'font-weight:700;font-style:italic;';
		$style .= 'text-align:' . $rowTPE['alignment'] . ';';
		
		$addClass = '';
		$content = ($rowTPE['content_asset'] == '') ? $rowTPE['content'] : $rowTPE['content_asset'];
		$content_add = ($rowTPE['content_add_asset'] == '') ? $rowTPE['content_add'] : $rowTPE['content_add_asset'];

		switch($rowTPE['id_tcid']){
			// Pricefield
			case '2':
//				preg_match('/[0-9\.,]*/', $content, $reg);
//				$content = $reg[0] . ' ' . $CONFIG['user']['configCountry']['currency'];
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
				$content = $rowsCS[0]['modultext'];
				break;

			case '15': // Fileupload
				$addClass = 'align' . $rowTPE['alignment'];
				
				$content = str_replace('src="' . $CONFIG['system']['directoryInstallation'] . '', 'src="http://193.110.207.229' . $CONFIG['system']['directoryInstallation'] . '', $content);
				break;

			// Partner logo
			case '11':
				$addClass = 'align' . $rowTPE['alignment'];
				
				$content = 'http://193.110.207.229' . $CONFIG['system']['directoryInstallation'] . 'media/' . $rowsPC[0]['filesys_filename'];
				break;

			// Partner contact
			case '10':
				$align = 'left';
				if($rowTPE['alignment'] == 'right') $align = 'right';

				$addrTemp1 = array();
				if(trim($rowsPC[0]['company_name']) != '') array_push($addrTemp1, '<span class="pc_single pc_company_name">' . $rowsPC[0]['company_name'] . '</span>');
				if(trim($rowsPC[0]['contactname']) != '') array_push($addrTemp1, '<span class="pc_single pc_contactname">' . $rowsPC[0]['contactname'] . '</span>');
				if(trim($rowsPC[0]['address1']) != '') array_push($addrTemp1, '<span class="pc_single pc_address1">' . $rowsPC[0]['address1'] . '</span>');
				if(trim($rowsPC[0]['zipcode']) != '' || trim($rowsPC[0]['city'])) array_push($addrTemp1, '<span class="pc_single pc_zipcode">' . $rowsPC[0]['zipcode'] . '</span> ' . '<span class="pc_single pc_city">' . $rowsPC[0]['city'] . '</span>');
				$addrTemp2 = array();
				if(trim($rowsPC[0]['phone']) != '') array_push($addrTemp2, '<span class="pc_single pc_phone">' . $rowsPC[0]['phone'] . '</span>');
				if(trim($rowsPC[0]['email']) != '') array_push($addrTemp2, '<span class="pc_single pc_email"><a href="mailto:' . $rowsPC[0]['email'] . '">' . $rowsPC[0]['email'] . '</a></span>');
				if(trim($rowsPC[0]['url']) != '') array_push($addrTemp2, '<span class="pc_single pc_url"><a href="http://' . $rowsPC[0]['url'] . '">' . $rowsPC[0]['url'] . '</a></span>');

				$content = '<p align="' . $align . '">';
				$content .= implode('<br />', $addrTemp1);
				$content .= '</p>';
				$content .= '<p align="' . $align . '">';
				$content .= implode('<br />', $addrTemp2);
				$content .= '</p>';

				if($rowTPE['alignment'] == 'center'){
					$align = 'center';
					$addrTemp1 = array();
					if(trim($rowsPC[0]['company_name']) != '') array_push($addrTemp1, '<span class="pc_single pc_company_name">' . $rowsPC[0]['company_name'] . '</span>');
					if(trim($rowsPC[0]['contactname']) != '') array_push($addrTemp1, '<span class="pc_single pc_contactname">' . $rowsPC[0]['contactname'] . '</span>');
					if(trim($rowsPC[0]['address1']) != '') array_push($addrTemp1, '<span class="pc_single pc_address1">' . $rowsPC[0]['address1'] . '</span>');
					if(trim($rowsPC[0]['zipcode']) != '' || trim($rowsPC[0]['city'])) array_push($addrTemp1, '<span class="pc_single pc_zipcode">' . $rowsPC[0]['zipcode'] . '</span>' . ' ' . '<span class="pc_city">' . $rowsPC[0]['city'] . '</span>');
					$addrTemp2 = array();
					if(trim($rowsPC[0]['phone']) != '') array_push($addrTemp2, '<span class="pc_single pc_phone">' . $rowsPC[0]['phone'] . '</span>');
					if(trim($rowsPC[0]['email']) != '') array_push($addrTemp2, '<span class="pc_single pc_email"><a href="mailto:' . $rowsPC[0]['email'] . '">' . $rowsPC[0]['email'] . '</a></span>');
					if(trim($rowsPC[0]['url']) != '') array_push($addrTemp2, '<span class="pc_single pc_url"><a href="http://' . $rowsPC[0]['url'] . '">' . $rowsPC[0]['url'] . '</a></span>');

					$content = '<p align="' . $align . '">';
					$content .= implode(' - ', $addrTemp1);
					$content .= '</p>';
					$content .= '<p align="' . $align . '">';
					$content .= implode(' - ', $addrTemp2);
					$content .= '</p>';
				}
				break;
			
			// Partner contact / logo combination
			case '16':
				$addrTemp1 = array();
				if(trim($rowsPC[0]['company_name']) != '') array_push($addrTemp1, '<span class="pc_single pc_company_name">' . $rowsPC[0]['company_name'] . '</span>');
				if(trim($rowsPC[0]['contactname']) != '') array_push($addrTemp1, '<span class="pc_single pc_contactname">' . $rowsPC[0]['contactname'] . '</span>');
				if(trim($rowsPC[0]['address1']) != '') array_push($addrTemp1, '<span class="pc_single pc_address1">' . $rowsPC[0]['address1'] . '</span>');
				if(trim($rowsPC[0]['zipcode']) != '' || trim($rowsPC[0]['city'])) array_push($addrTemp1, '<span class="pc_single pc_zipcode">' . $rowsPC[0]['zipcode'] . '</span>' . ' ' . '<span class="pc_single pc_city">' . $rowsPC[0]['city'] . '</span>');
				$addrTemp2 = array();
				if(trim($rowsPC[0]['phone']) != '') array_push($addrTemp2, '<span class="pc_single pc_phone">' . $rowsPC[0]['phone'] . '</span>');
				if(trim($rowsPC[0]['email']) != '') array_push($addrTemp2, '<span class="pc_single pc_email"><a href="mailto:' . $rowsPC[0]['email'] . '">' . $rowsPC[0]['email'] . '</a></span>');
				if(trim($rowsPC[0]['url']) != '') array_push($addrTemp2, '<span class="pc_single pc_url"><a href="http://' . $rowsPC[0]['url'] . '">' . $rowsPC[0]['url'] . '</a></span>');

				$content = '<div class="partnerContactCombination contactalignleft">';
				$content .= '<div class="componentPartnerlogo"><img src="http://193.110.207.229' . $CONFIG['system']['directoryInstallation'] . 'media/' . $rowsPC[0]['filesys_filename'] . '"></div>';
				$content .= '<div class="dummyPartnercontact">';
				$content = '<p align="left">';
				$content .= implode('<br />', $addrTemp1);
				$content .= '</p>';
				$content .= '<p align="left">';
				$content .= implode('<br />', $addrTemp2);
				$content .= '</p>';
				$content .= '</div>';
				$content .= '</div>';
				break;
			
			// Color area
			case '18':
				$content = 'background-color:' . $rowTPE['background_color'] . ';';
				break;
			
			// Call to action
			case '19':
				$aConAdd = json_decode($content_add, true);
				$url = (isset($aConAdd['calltoactionurl'])) ? $aConAdd['calltoactionurl'] : '';
				if(substr($url, 0, 4) != 'http') $url = 'http://' . $url;

				$content = '<table border="0" cellpadding="0" cellspacing="0" class="buttonCalltoaction"><tr><td><a href="' . $url . '">' . $content . '</a></td></tr></table>';
				break;
		}
		
		if($rowTPE['id_tcid'] != 18 && $rowTPE['id_tcid'] != 11) $content = '<div class="compboxOuter ' . $addClass . '" style="' . $style . '">' . $content . '</div>';
		$body = str_replace('##' . $rowTPE['elementtitle'] . '##', $content, $body);
	}
		
	
	############################################################
	// products
	############################################################
	$products = '';

	$queryS = $CONFIG['dbconn'][0]->prepare('SELECT
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_apid, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_asid, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_tempid, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_bfid, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_etid, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_tpid, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_pcid, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_ppid, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.rank, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_pid, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.revenue_pid, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.prod_type, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_ptid, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.pn_text, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.mkt_name, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.mkt_paragraph, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.tagline, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.price, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.description_text_25, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.description_text_50, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.description_text_100, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.image, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_piid, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.not_lpmd, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.content_add, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.duration, 
												' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.del
											FROM ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_asid = (:id_asid)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_etid = (:id_etid)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.showframe = (:one)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_ppid = (:id_ppid)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.rank
										');
	$queryS->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$queryS->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$queryS->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryS->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
	$queryS->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$queryS->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryS->bindValue(':id_etid', $rows[0]['id_etid'], PDO::PARAM_INT);
	$queryS->bindValue(':one', 1, PDO::PARAM_INT);
	$queryS->execute();
	$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
	$numS = $queryS->rowCount();
        
	$n = 0;
	$row = 0;
	$nRow = 0;
	foreach($rowsS as $rowS){
		$htmlProd = (isset($aPT[$n])) ? $aProductTemplates[$aPT[$n]] : $aProductTemplates[$aPT[(count($aPT) - 1)]];
		$n++;
		
		$numRow = (isset($aPTrow[$row])) ? $aPTrow[$row] : $aPTrow[(count($aPTrow) - 1)];
		$nRow++;
		$rowEnd = 0;
		if($nRow == $numRow){
			$nRow = 0;
			$rowEnd = 1;
			$row++;
		}
		
		
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
			$style = '';
	//		$style .= 'position:absolute;';
	//		$style .= 'top:' . $rowTPE['position_top'] . '%;';
	//		$style .= 'left:' . $rowTPE['position_left'] . '%;';
	//		$style .= 'width:' . $rowTPE['width'] . '%;';
	//		$style .= 'height:' . $rowTPE['height'] . '%;';
//			$style .= 'font-size:' . $rowTPE['fontsize'] . 'pt;';
//			$style .= 'color:' . $rowTPE['fontcolor'] . ';';
//			if($rowTPE['background_color'] != '') $style .= 'background-color:' . $rowTPE['background_color'] . ';';
//			if($rowTPE['fontstyle'] == 1) $style .= 'font-weight:700;';
//			if($rowTPE['fontstyle'] == 2) $style .= 'font-style:italic;';
//			if($rowTPE['fontstyle'] == 3) $style .= 'font-weight:700;font-style:italic;';
//			$style .= 'text-align:' . $rowTPE['alignment'] . ';';
		
			$addClass = '';
			$content = $rowTPE['content'];
			
			switch($rowTPE['id_tcid']){
				// Product name
				case '3':
					$content = $rowS['mkt_name'];
					break;
		
				// Product category
				case '9':
					$content = $rowS['prod_type'];
					break;
				
				// Product image
				case '12':
					$addClass = 'align' . $rowTPE['alignment'];
					
					$searchpattern = '/src="(.*)"/siU';
					preg_match ($searchpattern, $rowS['image'], $match);
					$content = 'http://193.110.207.229' . $match[1];
//					
//					$content = str_replace('src="' . $CONFIG['system']['directoryInstallation'] . '', 'src="http://193.110.207.229' . $CONFIG['system']['directoryInstallation'] . '', $rowS['image']);
//
//echo $match[1];					
//				
//					$content = str_replace('src="' . $CONFIG['system']['directoryInstallation'] . '', 'src="http://193.110.207.229' . $CONFIG['system']['directoryInstallation'] . '', $rowS['image']);
					break;
				
				// PN
				case '4':
					$content = $rowS['pn_text'];
					break;
		
				// Tagline
				case '13':
					$content = $rowS['tagline'];
					break;
		
				// Short description
				case '5':
					$content = $rowS['mkt_paragraph'];
					break;
		
				// 25 word description
				case '6':
					$content = $rowS['description_text_25'];
					break;
		
				// 50 word description
				case '7':
					$content = $rowS['description_text_50'];
					break;
		
				// 100 word description
				case '8':
					$content = $rowS['description_text_100'];
					break;
		
				// Pricefield
				case '2':
//					preg_match('/[0-9\.,]*/', $rowS['price'], $reg);
//					$content = $reg[0] . ' ' . $CONFIG['user']['configCountry']['currency'];
					$content = $rowS['price'];
					break;
				
				// WYSIWYG
				case '14':
					$aCon = json_decode($rowS['not_lpmd'], true);
					$content = (isset($aCon[$rowTPE['id_tpeid']])) ? $aCon[$rowTPE['id_tpeid']] : '';
					break;
				
				// Textfield
				case '1':
					$aCon = json_decode($rowS['not_lpmd'], true);
					$content = (isset($aCon[$rowTPE['id_tpeid']])) ? $aCon[$rowTPE['id_tpeid']] : '';
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
					
					$content = $rowsCS[0]['modultext'];
					break;
	
				case '15': // Fileupload
					$addClass = 'align' . $rowTPE['alignment'];
				
					$content = str_replace('src="' . $CONFIG['system']['directoryInstallation'] . '', 'src="http://193.110.207.229' . $CONFIG['system']['directoryInstallation'] . '', $content);
					break;
	
				// Partner logo
				case '11':
					$addClass = 'align' . $rowTPE['alignment'];
				
					$content = $CONFIG['system']['directoryInstallation'] . 'media/' . $rowsPC[0]['filesys_filename'];
					break;
	
				// Partner contact
				case '10':
					$align = 'left';
					if($rowTPE['alignment'] == 'right') $align = 'right';

					$addrTemp1 = array();
					if(trim($rowsPC[0]['company_name']) != '') array_push($addrTemp1, '<span class="pc_single pc_company_name">' . $rowsPC[0]['company_name'] . '</span>');
					if(trim($rowsPC[0]['contactname']) != '') array_push($addrTemp1, '<span class="pc_single pc_contactname">' . $rowsPC[0]['contactname'] . '</span>');
					if(trim($rowsPC[0]['address1']) != '') array_push($addrTemp1, '<span class="pc_single pc_address1">' . $rowsPC[0]['address1'] . '</span>');
					if(trim($rowsPC[0]['zipcode']) != '' || trim($rowsPC[0]['city'])) array_push($addrTemp1, '<span class="pc_single pc_zipcode">' . $rowsPC[0]['zipcode'] . '</span> ' . '<span class="pc_single pc_city">' . $rowsPC[0]['city'] . '</span>');
					$addrTemp2 = array();
					if(trim($rowsPC[0]['phone']) != '') array_push($addrTemp2, '<span class="pc_single pc_phone">' . $rowsPC[0]['phone'] . '</span>');
					if(trim($rowsPC[0]['email']) != '') array_push($addrTemp2, '<span class="pc_single pc_email"><a href="mailto:' . $rowsPC[0]['email'] . '">' . $rowsPC[0]['email'] . '</a></span>');
					if(trim($rowsPC[0]['url']) != '') array_push($addrTemp2, '<span class="pc_single pc_url"><a href="http://' . $rowsPC[0]['url'] . '">' . $rowsPC[0]['url'] . '</a></span>');


					$content = '<p align="' . $align . '">';
					$content .= implode('<br />', $addrTemp1);
					$content .= '</p>';
					$content .= '<p align="' . $align . '">';
					$content .= implode('<br />', $addrTemp2);
					$content .= '</p>';
	
					if($rowTPE['alignment'] == 'center'){
						$align = 'center';
						$addrTemp1 = array();
						if(trim($rowsPC[0]['company_name']) != '') array_push($addrTemp1, '<span class="pc_single pc_company_name">' . $rowsPC[0]['company_name'] . '</span>');
						if(trim($rowsPC[0]['contactname']) != '') array_push($addrTemp1, '<span class="pc_single pc_contactname">' . $rowsPC[0]['contactname'] . '</span>');
						if(trim($rowsPC[0]['address1']) != '') array_push($addrTemp1, '<span class="pc_single pc_address1">' . $rowsPC[0]['address1'] . '</span>');
						if(trim($rowsPC[0]['zipcode']) != '' || trim($rowsPC[0]['city'])) array_push($addrTemp1, '<span class="pc_single pc_zipcode">' . $rowsPC[0]['zipcode'] . '</span>' . ' ' . '<span class="pc_city">' . $rowsPC[0]['city'] . '</span>');
						$addrTemp2 = array();
						if(trim($rowsPC[0]['phone']) != '') array_push($addrTemp2, '<span class="pc_single pc_phone">' . $rowsPC[0]['phone'] . '</span>');
						if(trim($rowsPC[0]['email']) != '') array_push($addrTemp2, '<span class="pc_single pc_email"><a href="mailto:' . $rowsPC[0]['email'] . '">' . $rowsPC[0]['email'] . '</a></span>');
						if(trim($rowsPC[0]['url']) != '') array_push($addrTemp2, '<span class="pc_single pc_url"><a href="http://' . $rowsPC[0]['url'] . '">' . $rowsPC[0]['url'] . '</a></span>');
	
						$content = '<p align="' . $align . '">';
						$content .= implode(' - ', $addrTemp1);
						$content .= '</p>';
						$content .= '<p align="' . $align . '">';
						$content .= implode(' - ', $addrTemp2);
						$content .= '</p>';
					}
					break;

				// Partner contact / logo combination
				case '16':
					$addrTemp1 = array();
					if(trim($rowsPC[0]['company_name']) != '') array_push($addrTemp1, '<span class="pc_single pc_company_name">' . $rowsPC[0]['company_name'] . '</span>');
					if(trim($rowsPC[0]['contactname']) != '') array_push($addrTemp1, '<span class="pc_single pc_contactname">' . $rowsPC[0]['contactname'] . '</span>');
					if(trim($rowsPC[0]['address1']) != '') array_push($addrTemp1, '<span class="pc_single pc_address1">' . $rowsPC[0]['address1'] . '</span>');
					if(trim($rowsPC[0]['zipcode']) != '' || trim($rowsPC[0]['city'])) array_push($addrTemp1, '<span class="pc_single pc_zipcode">' . $rowsPC[0]['zipcode'] . '</span>' . ' ' . '<span class="pc_single pc_city">' . $rowsPC[0]['city'] . '</span>');
					$addrTemp2 = array();
					if(trim($rowsPC[0]['phone']) != '') array_push($addrTemp2, '<span class="pc_single pc_phone">' . $rowsPC[0]['phone'] . '</span>');
					if(trim($rowsPC[0]['email']) != '') array_push($addrTemp2, '<span class="pc_single pc_email"><a href="mailto:' . $rowsPC[0]['email'] . '">' . $rowsPC[0]['email'] . '</a></span>');
					if(trim($rowsPC[0]['url']) != '') array_push($addrTemp2, '<span class="pc_single pc_url"><a href="http://' . $rowsPC[0]['url'] . '">' . $rowsPC[0]['url'] . '</a></span>');
	
					$content = '<div class="partnerContactCombination contactalignleft">';
					$content .= '<div class="componentPartnerlogo"><img src="http://193.110.207.229' . $CONFIG['system']['directoryInstallation'] . 'media/' . $rowsPC[0]['filesys_filename'] . '"></div>';
					$content .= '<div class="dummyPartnercontact">';
					$content = '<p align="left">';
					$content .= implode('<br />', $addrTemp1);
					$content .= '</p>';
					$content .= '<p align="left">';
					$content .= implode('<br />', $addrTemp2);
					$content .= '</p>';
					$content .= '</div>';
					$content .= '</div>';
					break;
			
				// Color area
				case '18':
					$content = 'background-color:' . $rowTPE['background_color'] . ';';
					break;
			
				// Call to action
				case '19':
					$aCon = json_decode($rowS['not_lpmd'], true);
					$label = (isset($aCon[$rowTPE['id_tpeid']])) ? $aCon[$rowTPE['id_tpeid']] : '';
					
					$aConAdd = json_decode($rowS['content_add'], true);
					$url = (isset($aConAdd['calltoactionurl'])) ? $aConAdd['calltoactionurl'] : '';
					if(substr($url, 0, 4) != 'http') $url = 'http://' . $url;

					$content = '<table border="0" cellpadding="0" cellspacing="0" class="buttonCalltoaction"><tr><td><a href="' . $url . '">' . $label . '</a></td></tr></table>';
					break;
			}
			
			if($rowTPE['id_tcid'] != 18 && $rowTPE['id_tcid'] != 11 && $rowTPE['id_tcid'] != 12) $content = '<div class="compboxOuter ' . $addClass . '" style="' . $style . '">' . $content . '</div>';
			$htmlProd = str_replace('##prod_' . $rowTPE['elementtitle'] . '##', $content, $htmlProd);
		}
		
		$htmlProd = ($rowEnd == 1) ? str_replace('##tabProductAlign##', 'right', $htmlProd) : str_replace('##tabProductAlign##', 'left', $htmlProd);
	
	
		$products .= $htmlProd;
		if($rowEnd == 1) $products .= '</td></tr><tr><td class="cellProductRow">';
	}
	
	$products = '' . $products . '';
	$body = str_replace('##products##', $products, $body);
	############################################################




			
	$handle = fopen($folder .'/content.html', 'w');
	fwrite($handle, $body);
	fclose($handle);


	$imgOpt = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'wkhtmltox/bin/wkhtmltoimage ';
	$imgOpt .= '--format png ';
	$imgOpt .= '"' . $folder . '/content.html" ';
	$imgOpt .= '"' . $folder . '/content.png" ';
	system($imgOpt);
	
	$dirTarget = $CONFIG['system']['directoryRoot'] . 'assetimages/assets_thumbnails/';
	$fileThumbnail = str_pad($varSQL['id_asid'], 6 ,'0', STR_PAD_LEFT) . '-' . $CONFIG['user']['id_countid'] . '-' . $CONFIG['user']['id_langid'] . '-' . md5($varSQL['id_asid'] . '_asset'). '-1.png';
	system('convert ' . $folder . '/content.png -resize 400x400^ ' . $dirTarget . $fileThumbnail);
	chmod($dirTarget . $fileThumbnail, 0777);
	
	$zip = new ZipArchive();
	$fileZIP = $folder . '/output.zip';
	if ($zip->open($fileZIP, ZipArchive::CREATE)!==TRUE) {
		exit();
	}
	$zip->addFile($folder .'/content.html', str_replace(' ', '_', str_replace(':', '-', urldecode($rows[0]['title'])) . '.html'));
	$zip->close();



	unlink($folder . '/content.png');
	unlink($folder . '/content.html');
	


	
	$aExportTmp['filename'] = $rows[0]['title'] . '.zip';
	$aExportTmp['filename_template'] = $rows[0]['title_template'] . '.zip';
	$aExportTmp['title_asset'] = $rows[0]['title'];
	$aExportTmp['filesys_filename'] = $folder.'/output.zip';
	$aExportTmp['thumbnail'] = $fileThumbnail . '';
	$aExportTmp['folder'] = $folder;
}
?>