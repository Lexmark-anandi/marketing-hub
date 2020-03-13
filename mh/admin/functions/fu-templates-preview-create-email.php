<?php
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
		$queryF->bindValue(':id_etid', $row['id_etid'], PDO::PARAM_INT);
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
			' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.fixed
		FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni 
	
		WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_count = (:id_count)
			AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_lang = (:id_lang)
			AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_dev = (:id_dev)
			AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_cl IN (0, 1)
			AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.del = (:nultime)
			AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tempid = (:id_tempid)
			AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.page = (:page)
	';
	$queryTPE = $CONFIG['dbconn'][0]->prepare($queryStrTPE);
	$queryTPE->bindValue(':id_count', $row['id_countid'], PDO::PARAM_INT);
	$queryTPE->bindValue(':id_lang', $row['id_langid'], PDO::PARAM_INT);
	$queryTPE->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryTPE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryTPE->bindValue(':id_tempid', $row['id_tempid'], PDO::PARAM_INT);
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
		$content = $rowTPE['content'];
		$content_add = $rowTPE['content_add'];
		
		switch($rowTPE['id_tcid']){
			// Pricefield
			case '2':
//				preg_match('/[0-9\.,]*/', $content, $reg);
//				$content = $reg[0] . ' ' . $row['currency'];
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
				$queryCS->bindValue(':id_count', $row['id_countid'], PDO::PARAM_INT);
				$queryCS->bindValue(':id_lang', $row['id_langid'], PDO::PARAM_INT);
				$queryCS->bindValue(':id_dev', 0, PDO::PARAM_INT);
				$queryCS->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$queryCS->bindValue(':id_tmid', $rowTPE['content'], PDO::PARAM_INT);
				$queryCS->execute();
				$rowsCS = $queryCS->fetchAll(PDO::FETCH_ASSOC);
				$numCS = $queryCS->rowCount();
				$content = $rowsCS[0]['modultext'];
				break;
				
			// File upload
			case '15':
				$addClass = 'align' . $rowTPE['alignment'];
				
				$content = str_replace('src="' . $CONFIG['system']['directoryInstallation'] . '', 'src="https://qashrwrtapp001.lex1.lexmark.com' . $CONFIG['system']['directoryInstallation'] . '', $content);
				break;
				
			// Partner logo
			case '11':
				$addClass = 'align' . $rowTPE['alignment'];
			
				$content = 'https://qashrwrtapp001.lex1.lexmark.com' . $CONFIG['system']['directoryInstallation'] . 'custom/admin/img/dummy-logo.png';
				break;
		
			// Partner contact
			case '10':
				$align = 'left';
				if($rowTPE['alignment'] == 'right') $align = 'right';
				
				$content = '<p align="' . $align . '">';
				$content .= 'Company Name<br />';
				$content .= 'Street<br />';
				$content .= 'Zip City';
				$content .= '</p>';
				$content .= '<p align="' . $align . '">';
				$content .= '+49 69 / 12 34 56 78<br />';
				$content .= '<a href="mailto:info@company.com">info@company.com</a><br />';
				$content .= '<a href="http://www.domain.com">www.domain.com</a>';
				$content .= '</p>';

				if($rowTPE['alignment'] == 'center'){
					$align = 'center';

					$content = '<p align="' . $align . '">';
					$content .= 'Company Name - ';
					$content .= 'Street - ';
					$content .= 'Zip City';
					$content .= '</p>';
					$content .= '<p align="' . $align . '">';
					$content .= '+49 69 / 12 34 56 78 - ';
					$content .= '<a href="mailto:info@company.com">info@company.com</a> - ';
					$content .= '<a href="http://www.domain.com">www.domain.com</a>';
					$content .= '</p>';
				}
				break;
					
			// Partner contact / logo combination
			case '16':
				$content = '<div class="partnerContactCombination contactalignleft"><div class="componentPartnerlogo"><img src="https://qashrwrtapp001.lex1.lexmark.com' . $CONFIG['system']['directoryInstallation'] . 'custom/admin/img/dummy-logo.png"></div><div class="dummyPartnercontact"><span><span>Company Name</span><span class="contactDelimiter"></span><span>Street</span><span class="contactDelimiter"></span><span>Zip City</span></span><span><span>+49 69 / 12 34 56 78</span><span class="contactDelimiter"></span><span>info@company.com</span><span class="contactDelimiter"></span><span>www.domain.com</span></span></div></div>';
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

	$l = rand(0, 50);
	$queryS = $CONFIG['dbconn'][0]->prepare('SELECT
												' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_pid, 
												' . $CONFIG['db'][0]['prefix'] . '_products_uni.pn_text, 
												' . $CONFIG['db'][0]['prefix'] . '_products_uni.mkt_name, 
												' . $CONFIG['db'][0]['prefix'] . '_products_uni.mkt_paragraph, 
												' . $CONFIG['db'][0]['prefix'] . '_products_uni.tagline, 
												' . $CONFIG['db'][0]['prefix'] . '_products_uni.description_text_25, 
												' . $CONFIG['db'][0]['prefix'] . '_products_uni.description_text_50, 
												' . $CONFIG['db'][0]['prefix'] . '_products_uni.description_text_100,
												' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.prod_type,
												' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filename,
												' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename
											FROM ' . $CONFIG['db'][0]['prefix'] . '_products_uni
	
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni
												ON ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.id_dev = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.id_cl IN (0, 1)
													AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.id_ptid = ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_ptid
	
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni
												ON ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.id_dev = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.id_cl IN (0, 1)
													AND ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.id_data_parent = ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_pid
	
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_dev = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_cl IN (0, 1)
													AND ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.image = ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.is_printer = (:is_printer)
												AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.status = (:status)
											LIMIT ' . $l . ', 3
										');
	$queryS->bindValue(':id_count', $row['id_countid'], PDO::PARAM_INT);
	$queryS->bindValue(':id_lang', $row['id_langid'], PDO::PARAM_INT);
	$queryS->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryS->bindValue(':is_printer', 1, PDO::PARAM_INT);
	$queryS->bindValue(':status', 'Public', PDO::PARAM_STR);
	$queryS->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryS->execute();
	$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
	$numS = $queryS->rowCount();
	
	$n = 0;
	$rowX = 0;
	$nRow = 0;
	foreach($rowsS as $rowS){
		$htmlProd = (isset($aPT[$n])) ? $aProductTemplates[$aPT[$n]] : $aProductTemplates[$aPT[(count($aPT) - 1)]];
		$n++;
		
		$numRow = (isset($aPTrow[$rowX])) ? $aPTrow[$rowX] : $aPTrow[(count($aPTrow) - 1)];
		$nRow++;
		$rowEnd = 0;
		if($nRow == $numRow){
			$nRow = 0;
			$rowEnd = 1;
			$rowX++;
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
				AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.page = (:page)
		';

		$queryTPE = $CONFIG['dbconn'][0]->prepare($queryStrTPE);
		$queryTPE->bindValue(':id_count', $row['id_countid'], PDO::PARAM_INT);
		$queryTPE->bindValue(':id_lang', $row['id_langid'], PDO::PARAM_INT);
		$queryTPE->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$queryTPE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryTPE->bindValue(':id_tempid', $row['id_tempid'], PDO::PARAM_INT);
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
				
					if($rowS['filesys_filename'] != ''){
						$content = '<div class="componentProductimage"><img src="https://qashrwrtapp001.lex1.lexmark.com' . $CONFIG['system']['directoryInstallation'] . 'media/' . $rowS['filesys_filename'] . '"></div>';
									}
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
					$content = $TEXT['yourprice'];
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
					$queryCS->bindValue(':id_lang', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
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
				
					$content = str_replace('src="' . $CONFIG['system']['directoryInstallation'] . '', 'src="https://qashrwrtapp001.lex1.lexmark.com' . $CONFIG['system']['directoryInstallation'] . '', $content);
					break;
	
				// Partner logo
				case '11':
					$addClass = 'align' . $rowTPE['alignment'];
				
					$content = 'https://qashrwrtapp001.lex1.lexmark.com' . $CONFIG['system']['directoryInstallation'] . 'custom/admin/img/dummy-logo.png';
					break;
			
				// Partner contact
				case '10':
					$align = 'left';
					if($rowTPE['alignment'] == 'right') $align = 'right';
					
					$content = '<p align="' . $align . '">';
					$content .= 'Company Name<br />';
					$content .= 'Street<br />';
					$content .= 'Zip City';
					$content .= '</p>';
					$content .= '<p align="' . $align . '">';
					$content .= '+49 69 / 12 34 56 78<br />';
					$content .= '<a href="mailto:info@company.com">info@company.com</a><br />';
					$content .= '<a href="http://www.domain.com">www.domain.com</a>';
					$content .= '</p>';
	
					if($rowTPE['alignment'] == 'center'){
						$align = 'center';
	
						$content = '<p align="' . $align . '">';
						$content .= 'Company Name - ';
						$content .= 'Street - ';
						$content .= 'Zip City';
						$content .= '</p>';
						$content .= '<p align="' . $align . '">';
						$content .= '+49 69 / 12 34 56 78 - ';
						$content .= '<a href="mailto:info@company.com">info@company.com</a> - ';
						$content .= '<a href="http://www.domain.com">www.domain.com</a>';
						$content .= '</p>';
					}
					break;
				
				// Partner contact / logo combination
				case '16':
					$content = '<div class="partnerContactCombination contactalignleft"><div class="componentPartnerlogo"><img src="' . $CONFIG['system']['directoryInstallation'] . 'media/' . $rows[0]['filesys_filename'] . '"></div><div class="dummyPartnercontact"><span><span>' . $rows[0]['company_name'] . '</span><span class="contactDelimiter"></span><span>' . $rows[0]['address1'] . '</span><span class="contactDelimiter"></span><span>' . $rows[0]['zipcode'] . ' ' . $rows[0]['city'] . '</span></span><span><span>' . $rows[0]['phone'] . '</span><span class="contactDelimiter"></span><span>' . $rows[0]['email'] . '</span><span class="contactDelimiter"></span><span>' . $rows[0]['url'] . '</span></span></div></div>';
					break;
			
				// Color area
				case '18':
					$content = 'background-color:' . $rowTPE['background_color'] . ';';
					break;
			
				// Call to action
				case '19':
					$aCon = json_decode($rowS['not_lpmd'], true);
					$label = (isset($aCon[$rowTPE['id_tpeid']])) ? $aCon[$rowTPE['id_tpeid']] : $TEXT['ordernow'];
					
					$aConAdd = json_decode($rowS['content_add'], true);
					$url = (isset($aConAdd['calltoactionurl'])) ? $aConAdd['calltoactionurl'] : '';
					if(substr($url, 0, 4) != 'http') $url = 'http://' . $url;

					$content = '<table border="0" cellpadding="0" cellspacing="0" class="buttonCalltoaction"><tr><td><a href="' . $url . '">' . $label . '</a></td></tr></table>';
					break;
			}
			
			if($rowTPE['id_tcid'] != 18 && $rowTPE['id_tcid'] != 11) $content = '<div class="compboxOuter ' . $addClass . '" style="' . $style . '">' . $content . '</div>';
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
	
	$dirTarget = $CONFIG['system']['directoryRoot'] . 'assetimages/templates_thumbnails/';
	$fileThumbnail = str_pad($row['id_tempid'], 6 ,'0', STR_PAD_LEFT) . '-' . $row['id_countid'] . '-' . $row['id_langid'] . '-' . md5($row['id_tempid'] . '_template') . '-1.png';
	system('convert ' . $folder . '/content.png -resize 400x400^ ' . $dirTarget . $fileThumbnail);
	chmod($dirTarget . $fileThumbnail, 0777);

	$dirTarget = $CONFIG['system']['directoryRoot'] . 'assetimages/templates_preview/';
	system('convert ' . $folder . '/content.png -resize 700x700^ ' . $dirTarget . $fileThumbnail);
	chmod($dirTarget . $fileThumbnail, 0777);

	unlink($folder . '/content.html');
	unlink($folder . '/content.png');
	rmdir($folder);



?>