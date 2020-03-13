<?php
function setValuesRead($aArgs=array()){
	global $CONFIG, $TEXT; 

	if(!isset($aArgs['data'])) $aArgs['data'] = array();
	if(!isset($aArgs['fields'])) $aArgs['fields'] = array();
	if(!isset($aArgs['id_count'])) $aArgs['id_count'] = $CONFIG['settings']['selectCountry'];
	if(!isset($aArgs['id_lang'])) $aArgs['id_lang'] = $CONFIG['settings']['selectLanguage'];
	if(!isset($aArgs['id_dev'])) $aArgs['id_dev'] = $CONFIG['settings']['selectDevice'];
	if(!isset($aArgs['usesystem'])) $aArgs['usesystem'] = 1;
	
	$aCountry = ($aArgs['usesystem'] == 1) ? $CONFIG['user']['syscountries'][$CONFIG['activeSettings']['systemCountry']] : $CONFIG['user']['countries'][$aArgs['id_count']];

	// Number
	if(isset($aArgs['fields']['number'])){
		foreach($aArgs['fields']['number'] as $field => $aFormat){
			if(!isset($aFormat['decimal'])) $aFormat['decimal'] = 0;
			if(!isset($aFormat['nul'])) $aFormat['nul'] = '';
			if(!isset($aFormat['sep_decimal'])) $aFormat['sep_decimal'] = (isset($aCountry['sep_decimal'])) ? $aCountry['sep_decimal'] : $CONFIG['system']['sep_decimal'];
			if(!isset($aFormat['sep_thousand'])) $aFormat['sep_thousand'] = (isset($aCountry['sep_decimal'])) ? $aCountry['sep_thousand'] : $CONFIG['system']['sep_thousand'];
			
			$aArgs['data'][$field] = ($aArgs['data'][$field] != 0) ? number_format($aArgs['data'][$field], intval($aFormat['decimal']), $aFormat['sep_decimal'], $aFormat['sep_thousand']) : $aFormat['nul'];
		}
	}

	// Currency
	if(isset($aArgs['fields']['currency'])){
		foreach($aArgs['fields']['currency'] as $field => $aFormat){
			if(!isset($aFormat['decimal'])) $aFormat['decimal'] = 2;
			if(!isset($aFormat['nul'])) $aFormat['nul'] = '';
			if(!isset($aFormat['sep_decimal'])) $aFormat['sep_decimal'] = (isset($aCountry['sep_decimal'])) ? $aCountry['sep_decimal'] : $CONFIG['system']['sep_decimal'];
			if(!isset($aFormat['sep_thousand'])) $aFormat['sep_thousand'] = (isset($aCountry['sep_decimal'])) ? $aCountry['sep_thousand'] : $CONFIG['system']['sep_thousand'];
			if(!isset($aFormat['sign'])) $aFormat['sign'] = (isset($aCountry['currency'])) ? $aCountry['currency'] : $CONFIG['system']['currency'];
			
			$aArgs['data'][$field] = ($aArgs['data'][$field] != 0) ? number_format($aArgs['data'][$field], intval($aFormat['decimal']), $aFormat['sep_decimal'], $aFormat['sep_thousand']) . ' ' . $aFormat['sign'] : (($aFormat['nul'] != '') ? $aFormat['nul'] . ' ' . $aFormat['sign'] : $aFormat['nul']);
		}
	}
	
	// Date
	if(isset($aArgs['fields']['date'])){ 
		foreach($aArgs['fields']['date'] as $field => $aFormat){
			if(!isset($aFormat['nul'])) $aFormat['nul'] = '';
			if(!isset($aFormat['date_code'])) $aFormat['date_code'] = (isset($aCountry['date_code'])) ? $aCountry['date_code'] : $CONFIG['system']['date_code'];
			if(!isset($aFormat['date_format'])) $aFormat['date_format'] = (isset($aCountry['date_format'])) ? $aCountry['date_format'] : $CONFIG['system']['date_format'];

			$objDate = new DateTime($aArgs['data'][$field]);
			$dateFormat = $objDate->format($aFormat['date_code']);
			$aArgs['data'][$field] = ($aArgs['data'][$field] != '0000-00-00 00:00:00' && $aArgs['data'][$field] != '0000-00-00') ? $dateFormat : $aFormat['nul'];
		}
	}
	
	// Date and Time
	if(isset($aArgs['fields']['datetime'])){
		foreach($aArgs['fields']['datetime'] as $field => $aFormat){
			if(!isset($aFormat['nul'])) $aFormat['nul'] = '';
			if(!isset($aFormat['date_code'])) $aFormat['date_code'] = (isset($aCountry['date_code'])) ? $aCountry['date_code'] : $CONFIG['system']['date_code'];
			if(!isset($aFormat['date_format'])) $aFormat['date_format'] = (isset($aCountry['date_format'])) ? $aCountry['date_format'] : $CONFIG['system']['date_format'];
			if(!isset($aFormat['time_code'])) $aFormat['time_code'] = (isset($aCountry['time_code'])) ? $aCountry['time_code'] : $CONFIG['system']['time_code'];
			if(!isset($aFormat['time_format'])) $aFormat['time_format'] = (isset($aCountry['time_format'])) ? $aCountry['time_format'] : $CONFIG['system']['time_format'];
			if(!isset($aFormat['seconds'])) $aFormat['seconds'] = 1;
			if($aFormat['seconds'] == 0){
				$aFormat['time_code'] = str_replace('s', '', $aFormat['time_code']);
				$aFormat['time_code'] = trim($aFormat['time_code'], ':');
			}

			$objDate = new DateTime($aArgs['data'][$field]);
			$dateFormat = $objDate->format($aFormat['date_code'] . ' ' . $aFormat['time_code']);
			$aArgs['data'][$field] = ($aArgs['data'][$field] != '0000-00-00 00:00:00' && $aArgs['data'][$field] != '0000-00-00') ? $dateFormat : $aFormat['nul'];
		}
	}
	
	// Value to Text
	if(isset($aArgs['fields']['value2text'])){
		foreach($aArgs['fields']['value2text'] as $field => $aFormat){
			$aArgs['data'][$field . 'G'] = $TEXT[$field . $aArgs['data'][$field]];
		}
	}

	// Boolean to Text
	if(isset($aArgs['fields']['bool2text'])){
		foreach($aArgs['fields']['bool2text'] as $field => $aFormat){
			if(!isset($aFormat['text'])) $aFormat['text'] = 'check';
			
			$aArgs['data'][$field . 'G'] = (isset($TEXT[$aFormat['text'].$aArgs['data'][$field]])) ? $TEXT[$aFormat['text'].$aArgs['data'][$field]] : $aArgs['data'][$field]; 
		}
	}

	// Empty
	if(isset($aArgs['fields']['placeholder'])){
		foreach($aArgs['fields']['placeholder'] as $field => $aFormat){
			if(!isset($aFormat['char'])) $aFormat['char'] = '';

			$aArgs['data'][$field] = $aFormat['char'];
		}
	}

	// Decrypt
	if(isset($aArgs['fields']['cryption'])){
		foreach($aArgs['fields']['cryption'] as $field => $aFormat){
			$aCrypt = array();
			$aCrypt['data'][$field] = $aArgs['data'][$field];
			$aCrypt['fields']['cryption'] = array($field => $aFormat);
			$aArgs['data'][$field] = ($aArgs['data'][$field] != '') ? valuesDecrypt($aCrypt) : '';
		}
	}
	
//	// Checkbox to Radio
//	if(isset($aArgs['fields']['check2Radio'])){
//		foreach($aArgs['fields']['check2Radio'] as $field){
//			if($aArgs['id_count'] == 0 && $aArgs['id_lang'] == 0 && $aArgs['id_dev'] == 0){
//				if($aArgs['data'][$field] == "") $aArgs['data'][$field] = $aArgs['data'][$field.'_uni'];
//			}else{
//				if($aArgs['data'][$field] == "") $aArgs['data'][$field] = 0;
//			}
//		}
//	}
	
	// File
	if(isset($aArgs['fields']['file'])){
		foreach($aArgs['fields']['file'] as $field => $aFormat){
			
			##################################################################
			// Handle file field with multiple upload for editing single file
			if(isset($CONFIG['page']['id_data']) && $CONFIG['page']['id_data'] != 0 && isset($CONFIG['aModul']['addoptions']['insertLoopField']) && $CONFIG['aModul']['addoptions']['insertLoopField'] != '') $aFormat['type'] = 'single';
			##################################################################
			
			if(!isset($aArgs['data'][$field])) $aArgs['data'][$field] = ($aFormat['type'] == 'multiple') ? array() : '';
			$aArgs['data'][$field . 'G'] = '';
			$aArgs['data'][$field . 'F'] = '';
			
			if($aFormat['type'] == 'multiple' && !is_array($aArgs['data'][$field])) $aArgs['data'][$field] = json_decode($aArgs['data'][$field], true);
			$aField = (is_array($aArgs['data'][$field])) ? $aArgs['data'][$field] : array($aArgs['data'][$field]);
				
			foreach($aField as $fileid){ 
				$query = $CONFIG['dbconn'][0]->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid_data,
														' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid,
														' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filename,
														' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename,
														' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filehash
													FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni 
													WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:count)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:lang)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_dev = (:dev)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid = (:id_mid)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.del = (:nultime)
													');
				$query->bindValue(':count', $aArgs['id_count'], PDO::PARAM_INT);
				$query->bindValue(':lang', $aArgs['id_lang'], PDO::PARAM_INT);
				$query->bindValue(':dev', $aArgs['id_dev'], PDO::PARAM_INT);
				$query->bindValue(':id_mid', $fileid, PDO::PARAM_INT);
				$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$query->execute();
				$rows = $query->fetchAll(PDO::FETCH_ASSOC);
				$num = $query->rowCount();
				
				if($num > 0){
					$linkfile = '<div><a href="' . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathMedia'] . $rows[0]['filesys_filename'] . '" target="_blank">' . $rows[0]['filename'] . '</a></div>';
					
					$functionsfile = '';
					foreach($CONFIG['aModul']['functions'] as $aFunction){
						if(in_array($aFunction['id_mod2f'], $CONFIG['aModul']['fileFunctions']) && in_array($aFunction['id_f'], $aFormat['functions'])){
							$functionsfile .= '<div class="modulIcon modulIconForm" title="' . $TEXT[$aFunction['title']] . '" onclick="f_' . $CONFIG['aModul']['modul_name'] . '.' . $aFunction['function'] . '(' . $rows[0]['id_mid'] . ', this)"><i class="fa ' . $aFunction['icon'] . '"></i></div>';
						}
					}
					
					$thumbnailfile = '';
					$classThumbnail = '';
					$aArgsPic = array();
					
					if($aFormat['thumbnail'] == 1){
						$aArgsPic['id_count'] = $aArgs['id_count'];
						$aArgsPic['id_lang'] = $aArgs['id_lang'];
						$aArgsPic['id_dev'] = $aArgs['id_dev'];
						$aArgsPic['id_mid'] = $rows[0]['id_mid'];
						$aArgsPic['pathOrg'] = $CONFIG['system']['pathMedia'];
						$aArgsPic['fileOrg'] = $rows[0]['filesys_filename'];
						$aArgsPic['pathNew'] = $CONFIG['system']['pathAssets'];
						$aArgsPic['filehash'] = $rows[0]['filehash'];
						$aArgsPic['id_pf'] = $aFormat['pf'];
						$aArgsPic['onlyShrink'] = 'Y';
						$aArgsPic['sizing'] = 'Y';

						$pic = pictureSize($aArgsPic);
						if(file_exists($CONFIG['system']['directoryRoot'] . $pic)){
							$info = getimagesize($CONFIG['system']['directoryRoot']  .$pic);
							$thumbnailfile = '<img src="'.$CONFIG['system']['directoryInstallation'] . $pic.'" width="' . $info[0] . '" height="' . $info[1] . '">';
						}

						if($aFormat['gridheight'] == 'complete'){
							$aArgs['data'][$field . 'G'] .= '<div class="gridHeightComplete">' . $thumbnailfile . '</div>';
						}else{
							$aArgs['data'][$field . 'G'] .= $thumbnailfile;
						}
						
						$classThumbnail = 'fileUploadedOuterThumb';
					}else{
						$aArgs['data'][$field . 'G'] .= $linkfile;
					}
					
					$aArgs['data'][$field . 'F'] .= '<div class="fileUploadedOuter ' . $classThumbnail . '" data-id="' . $rows[0]['id_mid'] . '"><div class="fileUploadFunctions"> ' . $functionsfile . '</div><div class="fileUploadThumb">' . $thumbnailfile . '</div><div class="fileUploadFilename">' . $linkfile . '</div></div>';
				}
			}
			
			if($aFormat['type'] == 'multiple' && !is_array($aArgs['data'][$field])) $aArgs['data'][$field] = explode(',', $aArgs['data'][$field]);
		}
	}
	
	return $aArgs['data'];
}




?>