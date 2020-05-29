<?php
	if($rows[0]['id_bfid'] > 0){
		$aDownloadFiles = array();
		$dirTarget = $CONFIG['system']['directoryRoot'] . 'assetimages/assets_thumbnails/';
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
		$queryBf->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
		$queryBf->bindValue(':id_lang',$CONFIG['user']['id_langid'], PDO::PARAM_INT);
		$queryBf->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$queryBf->bindValue(':id_tempid', $rows[0]['id_tempid'], PDO::PARAM_INT);
		$queryBf->execute();
		$rowsBf = $queryBf->fetchAll(PDO::FETCH_ASSOC);
		$numBf = $queryBf->rowCount();
	
		foreach($rowsBf as $rowBf){
			$animated = 0;
			$aBannerfiles = array();
			
			$queryB = $CONFIG['dbconn'][0]->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tpid,
													' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page,
													' . $CONFIG['db'][0]['prefix'] . '_assetspages_uni.duration,
													' . $CONFIG['db'][0]['prefix'] . '_assetspages_uni.showframe,
													' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filename,
													' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename
												FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni 
						
												LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_assetspages_uni
													ON ' . $CONFIG['db'][0]['prefix'] . '_assetspages_uni.id_tpid = ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tpid
														AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_uni.id_count = (:id_count)
														AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_uni.id_lang = (:id_lang)
														AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_uni.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_uni.id_ppid = (:id_ppid)
														AND ' . $CONFIG['db'][0]['prefix'] . '_assetspages_uni.id_asid = (:id_asid)

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
			$queryB->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
			$queryB->bindValue(':id_lang',$CONFIG['user']['id_langid'], PDO::PARAM_INT);
			$queryB->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryB->bindValue(':id_bfid', $rowBf['id_bfid'], PDO::PARAM_INT);
			$queryB->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
			$queryB->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
			$queryB->execute();
			$rowsB = $queryB->fetchAll(PDO::FETCH_ASSOC);
			$numB = $queryB->rowCount();
			if($numB > 1) $animated = 1;

			foreach($rowsB as $rowB){
				include($CONFIG['system']['directoryRoot'] . 'custom/assets/assets-css.php');

				if($rowB['page'] == 1 || $rowB['page'] == 3){
					if($rowB['showframe'] == 1){
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
								AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.page_id = (:pageid)
						';
						$queryTPE = $CONFIG['dbconn'][0]->prepare($queryStrTPE);
						$queryTPE->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
						$queryTPE->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
						$queryTPE->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
						$queryTPE->bindValue(':id_dev', 0, PDO::PARAM_INT);
						$queryTPE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
						$queryTPE->bindValue(':id_tempid', $rows[0]['id_tempid'], PDO::PARAM_INT);
						$queryTPE->bindValue(':id_asid', $varSQL['id_asid'], PDO::PARAM_INT);
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
							$content = ($rowTPE['content_asset'] == '') ? $rowTPE['content'] : $rowTPE['content_asset'];
							$content_add = ($rowTPE['content_add_asset'] == '') ? $rowTPE['content_add'] : $rowTPE['content_add_asset'];
				
							switch($rowTPE['id_tcid']){
								case '3': // Product name
									break;
					
								case '9': // Product category
									break;
					
								case '12': // Product image
									$addClass = 'align' . $rowTPE['alignment'];
									
									$content = str_replace('src="' . $CONFIG['system']['directoryInstallation'] . '', 'src="http://193.110.207.229' . $CONFIG['system']['directoryInstallation'] . '', $content);
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
//									preg_match('/[0-9\.,]*/', $content, $reg);
//									$content = $reg[0] . ' ' . $CONFIG['user']['configCountry']['currency'];
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
						
						$aBannerfiles[$folder . '/content-' . str_pad($rowB['page'], 3 ,'0', STR_PAD_LEFT)] = array('format' => 'jpg', 'duration' => $rowB['duration'], 'bfid' => $rowBf['id_bfid']);
					}
					
				######################################################################
				######################################################################
				}else{
				######################################################################
				######################################################################
					$animated = 1;

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
																AND ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts_uni.id_bfid = (:id_bfid)
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
					$queryS->bindValue(':id_bfid', $rowBf['id_bfid'], PDO::PARAM_INT);
					$queryS->bindValue(':one', 1, PDO::PARAM_INT);
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
							//$content = ($rowTPE['content_asset'] == '') ? $rowTPE['content'] : $rowTPE['content_asset'];
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
								
									$content = str_replace('src="' . $CONFIG['system']['directoryInstallation'] . '', 'src="http://193.110.207.229' . $CONFIG['system']['directoryInstallation'] . '', $rowS['image']);
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
//									$content = $reg[0] . ' ' . $CONFIG['user']['configCountry']['currency'];
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
									$aCon = json_decode($rowS['not_lpmd'], true);
									$label = (isset($aCon[$rowTPE['id_tpeid']])) ? $aCon[$rowTPE['id_tpeid']] : '';
									
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
						
						$aBannerfiles[$folder . '/content-' . str_pad($rowB['page'], 3 ,'0', STR_PAD_LEFT) . '-' . str_pad($n, 2 ,'0', STR_PAD_LEFT)] = array('format' => 'jpg', 'duration' => $rowS['duration'], 'bfid' => $rowBf['id_bfid']);
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

				$date = new DateTime();
				$now = $date->format('Y-m-d H:i:s');
				$bname = $rowBf['bannername'];
				$bname = str_replace('/', '', $bname);
//					$bname = str_replace(')', '', $bname);
//					$bname = str_replace(')', '', $bname);
//					$bname = str_replace(' ', '_', $bname);
				$bname = urlencode($bname);
				rename($fileGIF, $folderImg . '/' . $bname . '-' . $now . '.gif');
				array_push($aDownloadFiles, $bname . '-' . $now . '.gif');

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

					$date = new DateTime();
					$now = $date->format('Y-m-d H:i:s');
					$bname = $rowBf['bannername'];
					$bname = str_replace('/', '', $bname);
//					$bname = str_replace(')', '', $bname);
//					$bname = str_replace(')', '', $bname);
//					$bname = str_replace(' ', '_', $bname);
					$bname = urlencode($bname);
					rename($bannerfile . '.' . $aFile['format'], $folderImg . '/' . $bname . '-' . $now . '.' . $aFile['format']);
					array_push($aDownloadFiles, $bname . '-' . $now . '.' . $aFile['format']);

					if(file_exists($bannerfile . '.html')) unlink($bannerfile . '.html');
				}
			}
		}


		$listT = '';
		$t = 0;
		foreach($fileThumbnail as $bfFile){
			if($t < 4) $listT .= ' ' . $folder . '/' . $bfFile;
			$t++;
		}
		$fileThumb = str_pad($varSQL['id_asid'], 6 ,'0', STR_PAD_LEFT) . '-' . $CONFIG['user']['id_countid'] . '-' . $CONFIG['user']['id_langid'] . '-' . md5($varSQL['id_asid'] . '_asset'). '-1.png';
		system('montage -background "#efefef" -tile 2x2 ' . $listT . ' ' . $folder . '/' . $fileThumb);
		foreach($fileThumbnail as $bfFile){
			unlink($folder . '/' . $bfFile);
		}




		$zip = new ZipArchive();
		$fileZIP = $folder . '/output.zip';
		if ($zip->open($fileZIP, ZipArchive::CREATE)!==TRUE) {
			exit();
		}
		foreach($aDownloadFiles as $file){
			$zip->addFile($folderImg . '/' . $file, str_replace(' ', '_', str_replace(':', '-', urldecode($file))));
		}
		$zip->close();
		
		foreach($aDownloadFiles as $file){
			unlink($folderImg . '/' . $file);
		}
		rmdir($folderImg);
		
		
		$aExportTmp['filename'] = $rows[0]['title'] . '.zip';
		$aExportTmp['filename_template'] = $rows[0]['title_template'] . '.zip';
		$aExportTmp['title_asset'] = $rows[0]['title'];
		$aExportTmp['filesys_filename'] = $folder.'/output.zip';
		$aExportTmp['thumbnail'] = $fileThumb . '';
		$aExportTmp['folder'] = $folder;
	}

	

?>