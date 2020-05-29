<?php


	$rowBf = array();
	####################################

	if($rows[0]['id_kcid'] > 0){
		#################################################
		// for kiado document
		#################################################
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
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_cl IN (0, 1)
													AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_mid = ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_dev = (:id_dev) 
												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_cl IN (0, 1)
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
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_cl IN (0, 1)
														AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_mid = ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid
			
												WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_dev = (:id_dev)
													AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_cl IN (0, 1)
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
			$pagenumber = $rowsF[0]['page_number'];
			$aPageDimension = json_decode($rowsF[0]['page_dimension'], true);
			$mediafile = $rowsF[0]['filesys_filename'];
		}
	}else if($rows[0]['id_bfid'] == 0 && $rows[0]['id_etid'] == 0 && $rows[0]['file_original'] > 0){
		#################################################
		// for uploaded document
		#################################################
		$pagenumber = $rows[0]['page_number'];
		$aPageDimension = json_decode($rows[0]['page_dimension'], true);
		$mediafile = $rows[0]['filesys_filename'];
	}



	// generate html
	$aImagesCreated = array();
	include($CONFIG['system']['directoryRoot'] . 'custom/assets/assets-css.php');
	$html = $htmlHead;

	$html .= '<body>';
	
	for($i = 1; $i <= $pagenumber; $i++){
		$background = '';
		$html .= '<div style="overflow:hidden;position:relative;width:' . $aPageDimension['mediabox'][2] . 'pt;height:' . $aPageDimension['mediabox'][3] . 'pt;##BACKGROUND##">';
		
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
				' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.fixed,
				' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.content AS content_asset
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
		$queryTPE->bindValue(':page', $i, PDO::PARAM_INT);
		$queryTPE->execute();
		$rowsTPE = $queryTPE->fetchAll(PDO::FETCH_ASSOC);
		$numTPE = $queryTPE->rowCount();
		
		foreach($rowsTPE as $rowTPE){
			$style = '';
			$style .= 'position:absolute;';
			$style .= 'top:' . $rowTPE['position_top'] . '%;';
			$style .= 'left:' . $rowTPE['position_left'] . '%;';
			$style .= 'width:' . $rowTPE['width'] . '%;';
			$style .= 'height:' . $rowTPE['height'] . '%;';
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
//					preg_match('/[0-9\.,]*/', $content, $reg);
//					$content = $reg[0] . ' ' . $CONFIG['user']['configCountry']['currency'];
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

					$htmlImg = $htmlHead;
					$htmlImg .= '<body>';
					$htmlImg .= '<div style="overflow:hidden;margin-top:1px;position:relative;width:' . $aPageDimension['mediabox'][2] . 'pt;height:' . $aPageDimension['mediabox'][3] . 'pt;">';
					$htmlImg .= '<div class="compboxOuter ' . $addClass . '" style="' . $style . '">'; 
					$htmlImg .= $content;
					$htmlImg .= '</div>';
					$htmlImg .= '</div>';
					$htmlImg .= '</body>';
					$htmlImg .= '</html>';
			
					$handle = fopen($folder .'/htmlImg.html', 'w');
					fwrite($handle, $htmlImg);
					fclose($handle);
					
					// create Images
					$imgOpt = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'wkhtmltox/bin/wkhtmltoimage ';
					//$imgOpt .= '--width 1000 ';
					//$imgOpt .= '--height 1000 ';
					$imgOpt .= '--transparent ';
					$imgOpt .= '--format png ';
					$imgOpt .= '"' . $folder . '/htmlImg.html" ';
					$imgOpt .= '"' . $folder . '/' . $rowTPE['id_tpeid'] . '.png" ';
					system($imgOpt);
					
					array_push($aImagesCreated, $folder . '/' . $rowTPE['id_tpeid'] . '.png');
					$background .= 'url(http://193.110.207.229' . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathApp'] . 'tmp/' . $foldername. '/' . $rowTPE['id_tpeid'] . '.png),';
				
					$content = '';
					break;
	 
				// Partner logo
				case '11':
					// add some transparent border because acrobat pdf is bad
//					$logofile = '../../media/' . $rowsPC[0]['filesys_filename'];
//					$logofileNew = '../../assets/' . md5($rowsPC[0]['filesys_filename']) . '.png';
//					$info = getimagesize($logofile);
//					$w = $info[0] + 100;
//					$h = $info[1] + 100;
//					if(!file_exists($logofileNew)){ 
//						system('convert ' . $logofile . ' -background none -gravity center -extent ' . $w . 'x' . $h . ' ' . $logofileNew . '');
//					}

					$addClass = 'align' . $rowTPE['alignment'];
//					$content = '<div class="componentPartnerlogo"><img src="http://193.110.207.229' . $CONFIG['system']['directoryInstallation'] . 'assets/' . md5($rowsPC[0]['filesys_filename']) . '.png"></div>';
				
					$content = '<div class="componentPartnerlogo"><img src="http://193.110.207.229' . $CONFIG['system']['directoryInstallation'] . 'media/' . $rowsPC[0]['filesys_filename'] . '"></div>';
					break;
	
				// Partner contact
				case '10':
					$addClass = 'contactalign' . $rowTPE['alignment'];
					$addrTemp1 = array();
					if(trim($rowsPC[0]['company_name']) != '') array_push($addrTemp1, '<span>' . $rowsPC[0]['company_name'] . '</span>');
					if(trim($rowsPC[0]['contactname']) != '') array_push($addrTemp1, '<span>' . $rowsPC[0]['contactname'] . '</span>');
					if(trim($rowsPC[0]['address1']) != '') array_push($addrTemp1, '<span>' . $rowsPC[0]['address1'] . '</span>');
					if(trim($rowsPC[0]['zipcode']) != '' || trim($rowsPC[0]['city'])) array_push($addrTemp1, '<span>' . $rowsPC[0]['zipcode'] . ' ' . $rowsPC[0]['city'] . '</span>');
					$addrTemp2 = array();
					if(trim($rowsPC[0]['phone']) != '') array_push($addrTemp2, '<span>' . $rowsPC[0]['phone'] . '</span>');
					if(trim($rowsPC[0]['email']) != '') array_push($addrTemp2, '<span>' . $rowsPC[0]['email'] . '</span>');
					if(trim($rowsPC[0]['url']) != '') array_push($addrTemp2, '<span>' . $rowsPC[0]['url'] . '</span>');

					$content = '<div class="dummyPartnercontact"><span>';
					$content .= implode('<span class="contactDelimiter"></span>', $addrTemp1);
					$content .= '</span>';
					$content .= '<span>';
					$content .= implode('<span class="contactDelimiter"></span>', $addrTemp2);
					$content .= '</span></div>';
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

					$content = '<div class="partnerContactCombination contactalignleft">';
					$content .= '<div class="componentPartnerlogo"><img src="http://193.110.207.229' . $CONFIG['system']['directoryInstallation'] . 'media/' . $rowsPC[0]['filesys_filename'] . '"></div>';
					$content .= '<div class="dummyPartnercontact"><span>';
					$content .= implode('<span class="contactDelimiter"></span>', $addrTemp1);
					$content .= '</span>';
					$content .= '<span>';
					$content .= implode('<span class="contactDelimiter"></span>', $addrTemp2);
					$content .= '</span></div>';
					$content .= '</div>';
					break;
			
				// Color area
				case '18':
					break;
			
				// Call to action
				case '19':
					$aConAdd = json_decode($content_add, true);
					$url = (isset($aConAdd['calltoactionurl'])) ? $aConAdd['calltoactionurl'] : '';
					if(substr($url, 0, 4) != 'http') $url = 'http://' . $url;
	
					$content = '<table border="0" cellpadding="0" cellspacing="0" class="buttonCalltoaction"><tr><td><a href="' . $url . '">' . $content . '</a></td></tr></table>';
					break;
			}
			
			$addClass .= ' verticalalign' . $rowTPE['verticalalignment'];
			if(in_array($rowTPE['id_tcid'], array(3,9,4,13,5,6,7,8,2,14,1,17))) $content = '<div class="verticalalignbox">' . $content . '</div>';
			
			$html .= '<div class="compboxOuter ' . $addClass . '" style="' . $style . '">'; 
			$html .= $content;
			$html .= '</div>';
		}
		
		$html .= '</div>';
		$background = rtrim($background, ',');

		$html = str_replace('##BACKGROUND##', 'background-image:' . $background, $html);
	}

	$html .= '</body>';
	$html .= '</html>';
	$html .= '';
	$html .= '';
			
	$handle = fopen($folder .'/content.html', 'w');
	fwrite($handle, $html);
	fclose($handle);
			
			
	$pdfOpt = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'wkhtmltox/bin/wkhtmltopdf ';
	$pdfOpt .= '--page-width ' . $aPageDimension['mediabox'][2] . 'pt ';
	$pdfOpt .= '--page-height ' . $aPageDimension['mediabox'][3] . 'pt ';
	$pdfOpt .= '--margin-top 0mm ';
	$pdfOpt .= '--margin-right 0mm ';
	$pdfOpt .= '--margin-bottom 0mm ';
	$pdfOpt .= '--margin-left 0mm ';
	$pdfOpt .= '"' . $folder . '/content.html" ';
	$pdfOpt .= '"' . $folder . '/content.pdf" ';
	system($pdfOpt);
	
	// convert to cmyk
	$convCmyk = 'gs ';
	$convCmyk .= '-o ';
	$convCmyk .= '"' . $folder . '/content_cmyk.pdf" ';
	$convCmyk .= '-sDEVICE=pdfwrite ';
	$convCmyk .= '-r2400 ';
	$convCmyk .= '-dOverrideICC=true ';
	$convCmyk .= '-sOutputICCProfile=USWebCoatedSWOP.icc ';
	//$convCmyk .= '-sDefaultRGBProfile=AdobeRGB1998.icc ';
	$convCmyk .= '-sColorConversionStrategy=CMYK ';
	$convCmyk .= '-dProcessColorModel=/DeviceCMYK ';
	$convCmyk .= '-dRenderIntent=3 ';
	$convCmyk .= '-dDeviceGrayToK=true ';
	$convCmyk .= '"' . $folder . '/content.pdf" ';
	$convCmyk .= '1>&2 ';
	system($convCmyk);
	unlink($folder . '/content.pdf');
	rename($folder . '/content_cmyk.pdf', $folder . '/content.pdf');


	$base = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'] . $mediafile;
	$overlay = '"' . $folder . '/content.pdf"';
	system('pdftk ' . $base . ' multistamp ' . $overlay . '  output "' . $folder . '/output.pdf"');
	
	
	$dirTarget = $CONFIG['system']['directoryRoot'] . 'assetimages/assets_thumbnails/';
	$fileThumbnail = str_pad($varSQL['id_asid'], 6 ,'0', STR_PAD_LEFT) . '-' . $CONFIG['user']['id_countid'] . '-' . $CONFIG['user']['id_langid'] . '-' . md5($varSQL['id_asid'] . '_asset');
	system('pdftoppm -png -r 96 -cropbox -aa yes -scale-to 400 -f 1 -l 1 "' . $folder . '/output.pdf" ' . $folder . '/' . $fileThumbnail);
	
	$fileSearch = $folder . '/' . $fileThumbnail . '-01.png';
	if(file_exists($fileSearch)){
		rename($fileSearch, str_replace('-01.png', '-1.png', $fileSearch));
	}
	chmod($folder . '/' . $fileThumbnail . '-1.png', 0777);
	
	
	
	foreach($aImagesCreated as $img){
		unlink($img);
	}
	unlink($folder . '/htmlImg.html');
	unlink($folder . '/content.html');
	unlink($folder . '/content.pdf');
	
	
	$aExportTmp['filename'] = $rows[0]['title'] . '.pdf';
	$aExportTmp['filename_template'] = $rows[0]['title_template'] . '.pdf';
	$aExportTmp['title_asset'] = $rows[0]['title'];
	$aExportTmp['filesys_filename'] = $folder.'/output.pdf';
	$aExportTmp['thumbnail'] = $fileThumbnail . '-1.png';
	$aExportTmp['folder'] = $folder;

?>