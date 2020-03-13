<?php

//$pic = pictureSize($CONF['inclpath']."media/products/".$_SESSION['USER']['parentid'][$varSQL['fModul']]."/", $rows[0]['filename'], $CONF['inclpath'].'assets', 1, '');
//$info = getimagesize($pic);

function pictureSize($aArgsPic){
	global $CONFIG;
	ini_set("memory_limit", "4000M");
	
	if(!isset($aArgsPic)) $aArgsPic = array();
	if(!isset($aArgsPic['id_count'])) $aArgsPic['id_count'] = 0;
	if(!isset($aArgsPic['id_lang'])) $aArgsPic['id_lang'] = 0;
	if(!isset($aArgsPic['id_dev'])) $aArgsPic['id_dev'] = 0;
	if(!isset($aArgsPic['id_mid'])) $aArgsPic['id_mid'] = '';
	if(!isset($aArgsPic['pathOrg'])) $aArgsPic['pathOrg'] = '';
	if(!isset($aArgsPic['fileOrg'])) $aArgsPic['fileOrg'] = '';
	if(!isset($aArgsPic['pathNew'])) $aArgsPic['pathNew'] = '';
	if(!isset($aArgsPic['filehash'])) $aArgsPic['filehash'] = '';
	if(!isset($aArgsPic['id_pf'])) $aArgsPic['id_pf'] = 0;
	if(!isset($aArgsPic['onlyShrink'])) $aArgsPic['onlyShrink'] = 'Y';
	if(!isset($aArgsPic['sizing'])) $aArgsPic['sizing'] = 'Y';

	$fOrg = $CONFIG['system']['directoryRoot'] . $aArgsPic['pathOrg'] . $aArgsPic['fileOrg'];
	
	// check if original image is animated gif
	$animateGif = 0;
	$extOrgTmp = strrpos($fOrg, '.');
	$extOrg = substr($fOrg, ($extOrgTmp + 1));
	if(strtolower($extOrg) == 'gif'){
		$checkAnimateGif = shell_exec('convert ' . $fOrg . ' -format "%n" info: | tail -n 1');
		if($checkAnimateGif > 1 ) $animateGif = 1;
	}

	// set filehash if not exists
	if($aArgsPic['filehash'] == ''){
		$aArgsPic['filehash'] = md5_file($fOrg);
		
		if($aArgsPic['id_mid'] != ''){
			$query = $CONFIG['dbconn'][0]->prepare('
												UPDATE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc SET
													filehash = (:filehash)
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_count = (:count)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_lang = (:lang)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_dev = (:dev)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND	' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mid = (:id_mid)
												');
			$query->bindValue(':count', $aArgsPic['id_count'], PDO::PARAM_INT);
			$query->bindValue(':lang', $aArgsPic['id_lang'], PDO::PARAM_INT);
			$query->bindValue(':dev', $aArgsPic['id_dev'], PDO::PARAM_INT);
			$query->bindValue(':id_mid', $aArgsPic['id_mid'], PDO::PARAM_INT);
			$query->bindValue(':filehash', $aArgsPic['filehash'], PDO::PARAM_STR);
			$query->execute();

			$query = $CONFIG['dbconn'][0]->prepare('
												UPDATE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni SET
													filehash = (:filehash)
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:count)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:lang)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_dev = (:dev)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND	' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid = (:id_mid)
												');
			$query->bindValue(':count', $aArgsPic['id_count'], PDO::PARAM_INT);
			$query->bindValue(':lang', $aArgsPic['id_lang'], PDO::PARAM_INT);
			$query->bindValue(':dev', $aArgsPic['id_dev'], PDO::PARAM_INT);
			$query->bindValue(':id_mid', $aArgsPic['id_mid'], PDO::PARAM_INT);
			$query->bindValue(':filehash', $aArgsPic['filehash'], PDO::PARAM_STR);
			$query->execute();
		}
	}
	
	// read pictureformat
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats.id_pf,
											 ' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats.width,
											 ' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats.height,
											 ' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats.filetype,
											 ' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats.quality,
											 ' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats.colorspace,
											 ' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats.crop,
											 ' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats.trim,
											 ' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats.alpha,
											 ' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats.bgcolor,
											 ' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats.aspect,
											 ' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats.strip,
											 ' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats.gravity_small,
											 ' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats.gravity
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats.id_count IN (0, ' . $aArgsPic['id_count'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats.id_lang IN (0, ' . $aArgsPic['id_lang'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats.id_dev IN (0, ' . $aArgsPic['id_dev'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND	' . $CONFIG['db'][0]['prefix'] . 'system_pictureformats.id_pf = (:id_pf)
										LIMIT 1
										');
	$query->bindValue(':id_pf', $aArgsPic['id_pf'], PDO::PARAM_INT);
	$query->execute();
	$num = $query->rowCount();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);

	// for animated gifs only some aspect ratios and filetyp Gif is allowed
	if($animateGif == 1){
		if($rows[0]['aspect'] == 1) $rows[0]['aspect'] = 0;
		if($rows[0]['aspect'] == 2) $rows[0]['aspect'] = 0;
		if($rows[0]['aspect'] == 5) $rows[0]['aspect'] = 0;
		if($rows[0]['aspect'] == 6) $rows[0]['aspect'] = 0;
		$rows[0]['filetype'] = 'gif';
	}
	
	// define args for new asset
	$fileNew = $aArgsPic['pathNew'] . $aArgsPic['id_mid'].'x_' . md5($aArgsPic['filehash'] . '_'.$rows[0]['bgcolor'] . $rows[0]['gravity_small'] . $rows[0]['gravity']) . $rows[0]['width'] . $rows[0]['height'] . $rows[0]['quality'] . $rows[0]['crop'] . $rows[0]['trim'] . $rows[0]['alpha'] . $rows[0]['aspect'] . $rows[0]['strip'] . $aArgsPic['onlyShrink'] . '.' . $rows[0]['filetype'];
	$fNew = $CONFIG['system']['directoryRoot'] . $fileNew;
	
	$alpha = '-alpha off';
	if($rows[0]['alpha'] == 1) $alpha = '-alpha on';
	//$alpha = '-colorspace rgb';
	
	$background = '';
	if($rows[0]['bgcolor'] != '') $background = '-background '.$rows[0]['bgcolor'];
	
	$quality = '';
	if($rows[0]['quality'] > 0) $quality = '-quality '.$rows[0]['quality']."%";
	
	$strip = '';
	if($rows[0]['strip'] != '') $strip = '-strip';
	
	$trim = '';
	if($rows[0]['trim'] == 1) $trim = '-fuzz 1% -trim +repage';
	
	$colorspace = '';
	if($rows[0]['colorspace'] != '') $colorspace = '-colorspace ' . $rows[0]['colorspace'];
	
	$shrinkLarger = '';
	if($aArgsPic['onlyShrink'] == 'Y') $shrinkLarger = '\>';
	
	if($aArgsPic['sizing'] == "Y"){
		$fileOK = 1;
		if(file_exists($fNew)){
			// if file exists -> check size
//			$picinfo = getimagesize($fNew);
//			
//			if($rows[0]['aspect'] == 0){
//				if($picinfo[0] > $picinfo[1]){
//					if($rows[0]['width'] != $picinfo[0]) $fileOK = 0;
//				}else{
//					if($rows[0]['height'] != $picinfo[1]) $fileOK = 0;
//				}
//			}
//			if($rows[0]['aspect'] == 1){
//				if($rows[0]['width'] != $picinfo[0] || $rows[0]['height'] != $picinfo[1]) $fileOK = 0;
//			}
//			if($rows[0]['aspect'] == 2){
//				if($rows[0]['width'] != $picinfo[0] || $rows[0]['height'] != $picinfo[1]) $fileOK = 0;
//			}
//			if($rows[0]['aspect'] == 3){
//				if($rows[0]['width'] != $picinfo[0]) $fileOK = 0;
//			}
//			if($rows[0]['aspect'] == 4){
//				if($rows[0]['width'] != $picinfo[0]) $fileOK = 0;
//			}
//			if($rows[0]['aspect'] == 5){
//				if($rows[0]['width'] != $picinfo[0] || $rows[0]['height'] != $picinfo[1]) $fileOK = 0;
//			}
//			if($rows[0]['aspect'] == 6){
//				if($rows[0]['width'] != $picinfo[0] || $rows[0]['height'] != $picinfo[1]) $fileOK = 0;
//			}
		}else{
			$fileOK = 0;
		}
		
	
		if($fileOK == 0){
			switch($rows[0]['aspect']){
				// Skalierung, damit Bild max. die vorgegebenen Ausmaße erreicht
				case 0:
					if($animateGif == 1){
						system("convert '" . $fOrg . "' -coalesce -resize " . $rows[0]['width'] . "x" . $rows[0]['height'] . " -deconstruct " . $fNew);
					}else{
						system("convert " . $alpha . " " . $background . " " . $colorspace . " -profile USWebCoatedSWOP.icc -profile AdobeRGB1998.icc '" . $fOrg . "' " . $quality . " " . $strip . " " . $trim . " -resize " . $rows[0]['width'] . "x" . $rows[0]['height'] . $shrinkLarger . " " . $fNew);
					}
					break;
				
				// Skalierung auf genaue Größe / auffüllend
				case 1:
					// zuerst wie '0'
					system("convert " . $alpha . " " . $background . " " . $colorspace . " -profile USWebCoatedSWOP.icc -profile AdobeRGB1998.icc '" . $fOrg . "' " . $quality . " -resize "  .$rows[0]['width'] . "x" . $rows[0]['height'] . " "  .$fNew);
					// dann auffüllen
					system("convert " . $alpha . " " . $background . " " . $fNew . " -gravity " . $rows[0]['gravity'] . " -extent " . $rows[0]['width'] . "x" . $rows[0]['height'] . " " . $fNew);
					break;
					
				// Skalierung auf genaue Größe / abschneidend
				case 2:
					$picinfo = @getimagesize($fOrg);
					$size = array();
					$rW =  $picinfo[0] / $rows[0]['width'];
					$rH = $picinfo[1] / $rows[0]['height'];
					if($rW > $rH){
						system("convert " . $alpha . " " . $background . " " . $colorspace . " -profile USWebCoatedSWOP.icc -profile AdobeRGB1998.icc '" . $fOrg . "' " . $quality . " -resize x" . $rows[0]['height'] . " " . $fNew);
						system("convert " . $alpha . " " . $background . " " . $fNew . " -gravity " . $rows[0]['gravity'] . " -extent " . $rows[0]['width'] . "x" . $rows[0]['height'] . " " . $fNew);
					}else{
						system("convert " . $alpha . " " . $background . " " . $colorspace . " -profile USWebCoatedSWOP.icc -profile AdobeRGB1998.icc '" . $fOrg . "' " . $quality . " -resize " . $rows[0]['width'] . "x " . $fNew);
						system("convert " . $alpha . " " . $background . " " . $fNew . " -gravity " . $rows[0]['gravity'] . " -extent " . $rows[0]['width'] . "x" . $rows[0]['height'] . " " . $fNew);
					}
					break;
				
				// Skalierung nach Breite / Höhe wird proportional angepasst
				case 3:
					if($animateGif == 1){
						system("convert '" . $fOrg . "' -coalesce -resize " . $rows[0]['width'] . "x -deconstruct " . $fNew);
					}else{
						system("convert " . $alpha . " " . $background . " " . $colorspace . " '" . $fOrg . "' " . $quality . " -resize " . $rows[0]['width'] . "x " . $fNew);
					}
					break;
				
				// Skalierung nach Höhe / Breite wird proportional angepasst
				case 4:
					if($animateGif == 1){
						system("convert '" . $fOrg . "' -coalesce -resize x" . $rows[0]['height'] . " -deconstruct " . $fNew);
					}else{
						system("convert " . $alpha . " " . $background . " " . $colorspace . " '" . $fOrg . "' " . $quality . " -resize x" . $rows[0]['height'] . " " . $fNew);
					}
					break;
				
				// Skalierung nach Breite / Höhe wird abgeschnitten/gefüllt
				case 5:
					// zuerst wie 3
					system("convert " . $alpha . " " . $background . " " . $colorspace . " '" . $fOrg . "' " . $quality . " -resize " . $rows[0]['width'] . "x " . $fNew);
					
					// dann endgültige Größe erstellen
					$picinfo = @getimagesize($fNew);
					if($picinfo[0] < $rows[0]['width'] || $picinfo[1] < $rows[0]['height']){
						// wenn Bild zu klein ist, vor dem Auffüllen 'gravity' ausrichten
						system("convert " . $alpha . " " . $background . " " . $fNew . " -gravity " . $rows[0]['gravity_small'] . " -extent " . $rows[0]['width'] . "x" . $rows[0]['height'] . " " . $fNew);
					}else{
						system("convert " . $alpha . " " . $background . " " . $fNew . " -gravity " . $rows[0]['gravity'] . " -extent " . $rows[0]['width'] . "x" . $rows[0]['height'] . " " . $fNew);
					}
					break;
				
				// Skalierung nach Höhe / Breite wird abgeschnitten/gefüllt
				case 6:
					// zuerst wie 4
					system("convert " . $alpha . " " . $background . " " . $colorspace . " '" . $fOrg . "' " . $quality . " -resize x" . $rows[0]['height'] . " " . $fNew);
					
					// dann endgültige Größe erstellen
					$picinfo = @getimagesize($fNew);
					if($picinfo[0] < $rows[0]['width'] || $picinfo[1] < $rows[0]['height']){
						// wenn Bild zu klein ist, vor dem Auffüllen 'gravity' ausrichten
						system("convert " . $alpha . " " . $background . " " . $fNew . " -gravity " . $rows[0]['gravity_small'] . " -extent " . $rows[0]['width'] . "x" . $rows[0]['height'] . " " . $fNew);
					}else{
						system("convert " . $alpha . " " . $background . " " . $fNew . " -gravity " . $rows[0]['gravity'] . " -extent " . $rows[0]['width'] . "x" . $rows[0]['height'] . " " . $fNew);
					}
					break;
			}
				
			
			if(file_exists($fNew)) chmod($fNew, 0777);
		}
	}
	
	return $fileNew;
}


?>