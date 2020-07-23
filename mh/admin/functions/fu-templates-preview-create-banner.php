<?php
//	if($rows[0]['id_bfid'] > 0){
		$aDownloadFiles = array();
		$dirTarget = $CONFIG['system']['directoryRoot'] . 'assetimages/templates_thumbnails/';
		$dirTargetPreview = $CONFIG['system']['directoryRoot'] . 'assetimages/templates_preview/';
		$fileThumbnail = array();
	
		
		$folderImg = $folder . '/img'; 
		mkdir($folderImg); 
		chmod($folderImg, 0777);


		$queryBf = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_bfid,
												' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.bannername,
												' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.width,
												' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.height
											FROM ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni 
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_cl IN (0, 1)
												AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_tempid = (:id_tempid)
											GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_bfid
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_bfid
											');
		$queryBf->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryBf->bindValue(':id_count', $row['id_countid'], PDO::PARAM_INT);
		$queryBf->bindValue(':id_lang',$row['id_langid'], PDO::PARAM_INT);
		$queryBf->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$queryBf->bindValue(':id_tempid', $row['id_tempid'], PDO::PARAM_INT);
		$queryBf->execute();
		$rowsBf = $queryBf->fetchAll(PDO::FETCH_ASSOC);
		$numBf = $queryBf->rowCount();
	
		foreach($rowsBf as $rowBf){
			$animated = 0;
			$aBannerfiles = array();
			
			$queryB = $CONFIG['dbconn'][0]->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tpid,
													' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page,
													' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filename,
													' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename
												FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni 
						
												LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
													ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_dev = (:id_dev)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_cl IN (0, 1)
														AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.file_original = ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid
												
												WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_dev = (:id_dev)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_cl IN (0, 1)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid = (:id_bfid)
												ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid, ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page
												');
			$queryB->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$queryB->bindValue(':id_count', $row['id_countid'], PDO::PARAM_INT);
			$queryB->bindValue(':id_lang',$row['id_langid'], PDO::PARAM_INT);
			$queryB->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryB->bindValue(':id_bfid', $rowBf['id_bfid'], PDO::PARAM_INT);
			$queryB->execute();
			$rowsB = $queryB->fetchAll(PDO::FETCH_ASSOC);
			$numB = $queryB->rowCount();
			if($numB > 1) $animated = 1;

			foreach($rowsB as $rowB){
				include($CONFIG['system']['directoryRoot'] . 'custom/assets/assets-css.php');

				if($rowB['page'] == 1 || $rowB['page'] == 3){
					// generate html
					$html = $htmlHead;
					$html .= '<body class="editBanner">';
					$html .= '<div class="bannerpage">';
	
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
							AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.page_id = (:pageid)
					';
					$queryTPE = $CONFIG['dbconn'][0]->prepare($queryStrTPE);
					$queryTPE->bindValue(':id_count', $row['id_countid'], PDO::PARAM_INT);
					$queryTPE->bindValue(':id_lang', $row['id_langid'], PDO::PARAM_INT);
					$queryTPE->bindValue(':id_dev', 0, PDO::PARAM_INT);
					$queryTPE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
					$queryTPE->bindValue(':id_tempid', $row['id_tempid'], PDO::PARAM_INT);
					$queryTPE->bindValue(':pageid', $rowB['id_tpid'] . '_' . $rowB['page'], PDO::PARAM_INT);
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
						$content_add = $rowTPE['content_add'];
			
						switch($rowTPE['id_tcid']){
							case '3': // Product name
								break;
				
							case '9': // Product category
								break;
				
							case '12': // Product image
								$addClass = 'align' . $rowTPE['alignment'];
								
								$l = rand(0, 50);
								$queryS = $CONFIG['dbconn'][0]->prepare('SELECT
																			' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_pid,
																			' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filename,
																			' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename
																		FROM ' . $CONFIG['db'][0]['prefix'] . '_products_uni
			
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
																		LIMIT ' . $l . ', 1
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

								$content = '';
								if($rowsS[0]['filesys_filename'] != ''){
									$content = '<div class="componentProductimage"><img src="'.$CONFIG['system']['directoryRoot'] . 'media/' . $rowsS[0]['filesys_filename'] . '"></div>';
								}
								break;
								
							case '4': // PN
								break;
				
							case '13': // Tagline
								break;
				
							case '5': // short description
								break;
				
							case '6': // 25 word description
								break;
				
							case '7': // 50 word description
								break;
				
							case '8': // 100 word description
								break;

							// Pricefield
							case '2':
//								preg_match('/[0-9\.,]*/', $content, $reg);
//								$content = $reg[0] . ' ' . $row['currency'];
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
				
							case '15': // Fileupload
								$addClass = 'align' . $rowTPE['alignment'];
				
								$content = str_replace('src="' . $CONFIG['system']['directoryInstallation'] . '', 'src="'.$CONFIG['system']['directoryRoot'] . '', $content);
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
					$html .= '</body>';
					$html .= '</html>';
					$html .= '';
					$html .= '';
							
					$handle = fopen($folder .'/content-' . str_pad($rowB['page'], 3 ,'0', STR_PAD_LEFT) . '.html', 'w');
					fwrite($handle, $html);
					fclose($handle);
				
					
					// create Images
					$imgOpt = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'wkhtmltox/bin/wkhtmltoimage ';
					$imgOpt .= '--width ' . $rowBf['width'] . ' ';
					$imgOpt .= '--height ' . $rowBf['height'] . ' ';
					$imgOpt .= '--format jpg ';
					$imgOpt .= '--quality 100 ';
					$imgOpt .= '"' . $folder . '/content-' . str_pad($rowB['page'], 3 ,'0', STR_PAD_LEFT) . '.html" ';
					$imgOpt .= '"' . $folder . '/content-' . str_pad($rowB['page'], 3 ,'0', STR_PAD_LEFT) . '.jpg" ';
					system($imgOpt);
					
					$aBannerfiles[$folder . '/content-' . str_pad($rowB['page'], 3 ,'0', STR_PAD_LEFT)] = array('format' => 'jpg', 'duration' => $CONFIG['system']['durationBannerframe'], 'bfid' => $rowBf['id_bfid']);
					
					unlink($folder .'/content-' . str_pad($rowB['page'], 3 ,'0', STR_PAD_LEFT) . '.html');
					
				######################################################################
				######################################################################
				}else{
				######################################################################
				######################################################################
					$animated = 1;

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
															LIMIT 1
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
					foreach($rowsS as $rowS){
						$n++;
						
						// generate html
						$html = $htmlHead;
						$html .= '<body>';
						$html .= '<div class="bannerpage">';


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
						$queryTPE->bindValue(':id_count', $row['id_countid'], PDO::PARAM_INT);
						$queryTPE->bindValue(':id_lang', $row['id_langid'], PDO::PARAM_INT);
						$queryTPE->bindValue(':id_dev', 0, PDO::PARAM_INT);
						$queryTPE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
						$queryTPE->bindValue(':id_tempid', $row['id_tempid'], PDO::PARAM_INT);
						$queryTPE->bindValue(':id_tpid', $rowB['id_tpid'], PDO::PARAM_INT);
						$queryTPE->bindValue(':page', 2, PDO::PARAM_INT);
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
									$content = '';
									if($rowS['filesys_filename'] != ''){
										$content = '<div class="componentProductimage"><img src="'.$CONFIG['system']['directoryRoot'] . 'media/' . $rowS['filesys_filename'] . '"></div>';
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
//									preg_match('/[0-9\.,]*/', $rowS['price'], $reg);
//									$content = $reg[0] . ' ' . $row['currency'];
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
					
								case '15': // Fileupload
									$addClass = 'align' . $rowTPE['alignment'];
				
									$content = str_replace('src="' . $CONFIG['system']['directoryInstallation'] . '', 'src="'.$CONFIG['system']['directoryRoot'] . '', $content);
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
									$content = '<div class="partnerContactCombination contactalignleft"><div class="componentPartnerlogo"><img src="'.$CONFIG['system']['directoryRoot']. 'custom/admin/img/dummy-logo.png"></div><div class="dummyPartnercontact"><span><span>Company Name</span><span class="contactDelimiter"></span><span>Street</span><span class="contactDelimiter"></span><span>Zip City</span></span><span><span>+49 69 / 12 34 56 78</span><span class="contactDelimiter"></span><span>info@company.com</span><span class="contactDelimiter"></span><span>www.domain.com</span></span></div></div>';
									break;
			
								// Color area
								case '18':
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
							
							$addClass .= ' verticalalign' . $rowTPE['verticalalignment'];
							if(in_array($rowTPE['id_tcid'], array(3,9,4,13,5,6,7,8,2,14,1,17))) $content = '<div class="verticalalignbox">' . $content . '</div>';
							
							$html .= '<div class="compboxOuter ' . $addClass . '" style="' . $style . '">'; 
							$html .= $content;
							$html .= '</div>';
						}


						$html .= '</div>';
						$html .= '</body>';
						$html .= '</html>';
						$html .= '';
						$html .= '';
								
						$handle = fopen($folder .'/content-' . str_pad($rowB['page'], 3 ,'0', STR_PAD_LEFT) . '-' . str_pad($n, 2 ,'0', STR_PAD_LEFT) . '.html', 'w');
						fwrite($handle, $html);
						fclose($handle);
					
						
						// create Images
						$imgOpt = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'wkhtmltox/bin/wkhtmltoimage ';
						$imgOpt .= '--width ' . $rowBf['width'] . ' ';
						$imgOpt .= '--height ' . $rowBf['height'] . ' ';
						$imgOpt .= '--format jpg ';
						$imgOpt .= '--quality 100 ';
						$imgOpt .= '"' . $folder . '/content-' . str_pad($rowB['page'], 3 ,'0', STR_PAD_LEFT) . '-' . str_pad($n, 2 ,'0', STR_PAD_LEFT) . '.html" ';
						$imgOpt .= '"' . $folder . '/content-' . str_pad($rowB['page'], 3 ,'0', STR_PAD_LEFT) . '-' . str_pad($n, 2 ,'0', STR_PAD_LEFT) . '.jpg" ';
						system($imgOpt);
						
						$aBannerfiles[$folder . '/content-' . str_pad($rowB['page'], 3 ,'0', STR_PAD_LEFT) . '-' . str_pad($n, 2 ,'0', STR_PAD_LEFT)] = array('format' => 'jpg', 'duration' => $CONFIG['system']['durationBannerframe'], 'bfid' => $rowBf['id_bfid']);
					}
				}
			}


			if($animated == 1){
				// Create Animation
				$filename = 'tmp';
				$fileMIFF = $folder . '/' . $filename . '.miff'; 
				$fileGIF = $folder . '/' . $filename . '.gif'; 
				
				$aFilesAnimate = array();
				foreach($aBannerfiles as $bannerfile => $aFile){
					array_push($aFilesAnimate, $bannerfile . '.' . $aFile['format']);
					if(!in_array($aFile['bfid'] . '.' . $aFile['format'], $fileThumbnail)){ 
						array_push($fileThumbnail, $aFile['bfid'] . '.' . $aFile['format']);
						system('convert ' . $bannerfile . '.' . $aFile['format'] . ' ' . $folder . '/' . $aFile['bfid'] . '.' . $aFile['format']);
					}
				}
				
				$f = 0;
				$fS = count($aFilesAnimate);
				foreach($aBannerfiles as $bannerfile => $aFile){
					$fileSrc = $bannerfile . '.' . $aFile['format']; 

					if($f == 0){
						system('convert -delay ' . ($aFile['duration'] * 100) . ' -page +0+0 ' . $fileSrc . ' -loop 0 ' . $fileMIFF . '');
					}else{
						system('convert -page +0+0 ' . $fileMIFF . ' -page +0+0 -delay ' . ($aFile['duration'] * 100) . ' ' . $fileSrc . ' ' . $fileMIFF . '');
					}
					
					// Fade
					$fileTmp = $folder . '/tmp.jpg';
					
					if($f < ($fS - 1)){
						$fileSrcNext = $aFilesAnimate[($f + 1)];
						system('composite -dissolve 66 ' . $fileSrc . ' ' . $fileSrcNext . ' -alpha Set ' . $fileTmp);
						system('convert -page +0+0 ' . $fileMIFF . ' -page +0+0 -delay 5 ' . $fileTmp . ' ' . $fileMIFF);
						
						system('composite -dissolve 33 ' . $fileSrc . ' ' . $fileSrcNext . ' -alpha Set ' . $fileTmp);
						system('convert -page +0+0 ' . $fileMIFF . ' -page +0+0 -delay 5 ' . $fileTmp . ' ' . $fileMIFF);
					}
					if($f == ($fS - 1)){
						$fileSrcNext = $aFilesAnimate[0];
						system('composite -dissolve 66 ' . $fileSrc . ' ' . $fileSrcNext . ' -alpha Set ' . $fileTmp);
						system('convert -page +0+0 ' . $fileMIFF . ' -page +0+0 -delay 5 ' . $fileTmp . ' ' . $fileMIFF);
						
						system('composite -dissolve 33 ' . $fileSrc . ' ' . $fileSrcNext . ' -alpha Set ' . $fileTmp);
						system('convert -page +0+0 ' . $fileMIFF . ' -page +0+0 -delay 5 ' . $fileTmp . ' ' . $fileMIFF);
					}
					
					$f++;
				}

				system('convert ' . $fileMIFF . ' ' . $fileGIF);
				system('convert ' . $fileGIF . ' -coalesce -layers OptimizePlus ' . $fileGIF);

				$newFilename = str_pad($row['id_tempid'], 6 ,'0', STR_PAD_LEFT) . '-' . $row['id_countid'] . '-' . $row['id_langid'] . '-' . md5($row['id_tempid'] . '_' . $rowBf['id_bfid'] . '_template'). '.gif';
				rename($fileGIF, $folderImg . '/' . $newFilename);
				array_push($aDownloadFiles, $newFilename);

				foreach($aBannerfiles as $bannerfile => $aFile){
					if(file_exists($bannerfile . '.' . $aFile['format'])) unlink($bannerfile . '.' . $aFile['format']);
					if(file_exists($bannerfile . '.html')) unlink($bannerfile . '.html');
					if(file_exists($bannerfile . '.jpg')) unlink($bannerfile . '.jpg');
					if(file_exists($bannerfile . '.png')) unlink($bannerfile . '.png');
					if(file_exists($bannerfile . '.gif')) unlink($bannerfile . '.gif');
				}
				if(file_exists($folder . '/tmp.jpg')) unlink($folder . '/tmp.jpg');
				if(file_exists($folder . '/tmp.miff')) unlink($folder . '/tmp.miff');
				if(file_exists($folder . '/tmp.png')) unlink($folder . '/tmp.png');
			}else{
				// Create Static
				foreach($aBannerfiles as $bannerfile => $aFile){
					if(!in_array($aFile['bfid'] . '.' . $aFile['format'], $fileThumbnail)){ 
						array_push($fileThumbnail, $aFile['bfid'] . '.' . $aFile['format']);
						system('convert ' . $bannerfile . '.' . $aFile['format'] . ' ' . $folder . '/' . $aFile['bfid'] . '.' . $aFile['format']);
					}

					$newFilename = str_pad($row['id_tempid'], 6 ,'0', STR_PAD_LEFT) . '-' . $row['id_countid'] . '-' . $row['id_langid'] . '-' . md5($row['id_tempid'] . '_' . $rowBf['id_bfid'] . '_template'). '.' . $aFile['format'];
					rename($bannerfile . '.' . $aFile['format'], $folderImg . '/' . $newFilename);
					array_push($aDownloadFiles, $newFilename);
				}
			}
		}


		$listT = '';
		foreach($fileThumbnail as $bfFile){
			$listT .= ' ' . $folder . '/' . $bfFile;
		}
		$fileThumb = str_pad($row['id_tempid'], 6 ,'0', STR_PAD_LEFT) . '-' . $row['id_countid'] . '-' . $row['id_langid'] . '-' . md5($row['id_tempid'] . '_template'). '-1.png';
		system('montage -background "#efefef" -tile 2x2 ' . $listT . ' ' . $dirTarget . $fileThumb);
		foreach($fileThumbnail as $bfFile){
			unlink($folder . '/' . $bfFile);
		}



		foreach($aDownloadFiles as $file){
			rename($folderImg . '/' . $file, $dirTargetPreview . '/' . $file);
		}
		rmdir($folderImg);
		rmdir($folder);

//	}

 

?>