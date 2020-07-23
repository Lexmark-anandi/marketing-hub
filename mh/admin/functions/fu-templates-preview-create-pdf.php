<?php
		$pagenumber = $row['page_number'];
		$aPageDimension = json_decode($row['page_dimension'], true);
		$aComponents = json_decode($row['components'], true);
		$mediafile = '';
		$rowBf = array();
		####################################
		 
		 
		if($row['id_kcid'] > 0){
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
			$queryF->bindValue(':id_count', $row['id_countid'], PDO::PARAM_INT);
			$queryF->bindValue(':id_lang', $row['id_langid'], PDO::PARAM_INT);
			$queryF->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryF->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$queryF->bindValue(':id_kcid', $row['id_kcid'], PDO::PARAM_INT);
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
				$queryF->bindValue(':id_kcid', $row['id_kcid'], PDO::PARAM_INT);
				$queryF->execute();
				$rowsF = $queryF->fetchAll(PDO::FETCH_ASSOC);
				$numF = $queryF->rowCount();
			}
			
			if($numF > 0){
				$pagenumber = $rowsF[0]['page_number'];
				$aPageDimension = json_decode($rowsF[0]['page_dimension'], true);
				$mediafile = $rowsF[0]['filesys_filename'];
			}
		}else if($row['id_bfid'] == 0 && $row['id_etid'] == 0 && $row['file_original'] > 0){
			#################################################
			// for uploaded document
			#################################################
			$pagenumber = $row['page_number'];
			$aPageDimension = json_decode($row['page_dimension'], true);
			$mediafile = $row['filesys_filename'];
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
				$content = $rowTPE['content'];
				
				switch($rowTPE['id_tcid']){
					// Pricefield
					case '2':
//						preg_match('/[0-9\.,]*/', $content, $reg);
//						$content = $reg[0] . ' ' . $row['currency'];
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
					
						$content = str_replace('src="' . $CONFIG['system']['directoryInstallation'] . '', 'src="'.$CONFIG['system']['directoryRoot'] . '', $content);
	
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
						$background .= 'url('.$CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathAdmin'] . 'tmp/' . $foldername. '/' . $rowTPE['id_tpeid'] . '.png),';
					
						$content = '';
						break;
						
					// Partner logo
					case '11':
						$addClass = 'align' . $rowTPE['alignment'];
				
						$content = '<div class="componentPartnerlogo"><img src="'.$CONFIG['system']['directoryRoot'] . 'custom/admin/img/dummy-logo.png"></div>';
						break;
				
					// Partner contact
					case '10':
						$addClass = 'contactalign' . $rowTPE['alignment'];
						
						$content = '<div class="dummyPartnercontact"><span><span>Company Name</span><span class="contactDelimiter"></span><span>Street</span><span class="contactDelimiter"></span><span>Zip City</span></span><span><span>+49 69 / 12 34 56 78</span><span class="contactDelimiter"></span><span>info@company.com</span><span class="contactDelimiter"></span><span>www.domain.com</span></span></div>';
						break;
							
					// Partner contact / logo combination
					case '16':
						$content = '<div class="partnerContactCombination contactalignleft"><div class="componentPartnerlogo"><img src="'.$CONFIG['system']['directoryRoot'] . 'custom/admin/img/dummy-logo.png"></div><div class="dummyPartnercontact"><span><span>Company Name</span><span class="contactDelimiter"></span><span>Street</span><span class="contactDelimiter"></span><span>Zip City</span></span><span><span>+49 69 / 12 34 56 78</span><span class="contactDelimiter"></span><span>info@company.com</span><span class="contactDelimiter"></span><span>www.domain.com</span></span></div></div>';
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
				
		
		$base = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'] . $mediafile;
		$overlay = '"' . $folder . '/content.pdf"';
		system('pdftk ' . $base . ' multistamp ' . $overlay . '  output "' . $folder . '/output.pdf"');
		
		
		$dirTarget = $CONFIG['system']['directoryRoot'] . 'assetimages/templates_thumbnails/';
		$fileThumbnail = str_pad($row['id_tempid'], 6 ,'0', STR_PAD_LEFT) . '-' . $row['id_countid'] . '-' . $row['id_langid'] . '-' . md5($row['id_tempid'] . '_template');
		system('pdftoppm -png -r 96 -cropbox -aa yes -scale-to 400 -f 1 -l 1 "' . $folder . '/output.pdf" ' . $dirTarget . $fileThumbnail);
		
		$fileSearch = $dirTarget . $fileThumbnail . '-01.png';
		if(file_exists($fileSearch)){
			rename($fileSearch, str_replace('-01.png', '-1.png', $fileSearch));
		}
		chmod($dirTarget . $fileThumbnail . '-1.png', 0777);
		
		$dirTarget = $CONFIG['system']['directoryRoot'] . 'assetimages/templates_preview/';
		system('pdftoppm -png -r 96 -cropbox -aa yes "' . $folder . '/output.pdf" ' . $dirTarget . $fileThumbnail);
		
		for($p=1; $p<10; $p++){
			$fileSearch = $dirTarget . $fileThumbnail . '-0' . $p . '.png';
			if(file_exists($fileSearch)){
				rename($fileSearch, str_replace('-0' . $p . '.png', '-' . $p . '.png', $fileSearch));
			}
			$fileSearch = $dirTarget . 'thumbnails/' . $filenameOriginal . '-0' . $p . '.png';
			if(file_exists($fileSearch)){
				rename($fileSearch, str_replace('-0' . $p . '.png', '-' . $p . '.png', $fileSearch));
			}
		}
		
		
		foreach($aImagesCreated as $img){
			unlink($img);
		}
		unlink($folder . '/htmlImg.html');
		unlink($folder . '/content.html');
		unlink($folder . '/content.pdf');
		unlink($folder . '/output.pdf');
		rmdir($folder);


?>