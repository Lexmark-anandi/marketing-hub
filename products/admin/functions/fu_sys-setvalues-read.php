<?php
function setValuesRead($dat, $aFields=array(), $keyC=0, $keyL=0, $keyD=0){
	global $CONFIG, $TEXT; 

	// Floats
	if(isset($aFields['floats'])){
		foreach($aFields['floats'] as $field){
			if($dat[$field] > 0){
				$dat[$field] = number_format($dat[$field], 2, ",", ".");
			}else{
				$dat[$field] = '';
			}
		}
	}
	
	// Timestamps
	if(isset($aFields['timestamps'])){
		foreach($aFields['timestamps'] as $field){
			if($dat[$field] != '0000-00-00 00:00:00' && $dat[$field] != '0000-00-00'){
				if(strlen($dat[$field]) == 19){
					$aTmp = explode(' ', $dat[$field]);
					$aTmp2 = explode('-', $aTmp[0]);
					$dat[$field] = $aTmp2[2].'.'.$aTmp2[1].'.'.$aTmp2[0].' '.$aTmp[1];
				}else{
					$aTmp = explode('-', $dat[$field]);
					$dat[$field] = $aTmp[2].'.'.$aTmp[1].'.'.$aTmp[0];
				}
			}else{
				$dat[$field] = '';
			}
		}
	}
	
	// Dates
	if(isset($aFields['dates'])){ 
		foreach($aFields['dates'] as $field){
			if($dat[$field] > 0){
				$dat[$field] = date('d.m.Y', $dat[$field]);
			}else{
				$dat[$field] = '';
			}
		}
	}
	
	// Field to Text
	if(isset($aFields['field2Text'])){
		foreach($aFields['field2Text'] as $field){
			$dat[$field . 'T'] = $TEXT[$field . $dat[$field]];
		}
	}

	// yes / no Text
	if(isset($aFields['yesNo2Text'])){
		foreach($aFields['yesNo2Text'] as $field){
			$dat[$field . 'T'] = (isset($TEXT['check'.$dat[$field]])) ? $TEXT['check'.$dat[$field]] : $dat[$field]; 
		}
	}
	
	// Checkbox to Radio
	if(isset($aFields['check2Radio'])){
		foreach($aFields['check2Radio'] as $field){
			if($keyC == 0 && $keyL == 0 && $keyD == 0){
				if($dat[$field] == "") $dat[$field] = $dat[$field.'_uni'];
			}else{
				if($dat[$field] == "") $dat[$field] = 0;
			}
		}
	}
	
	// File ID to Filename
	if(isset($aFields['files'])){
		foreach($aFields['files'] as $field){
			$dat[$field . 'T'] = '';
			
			if(is_array($dat[$field])){
				$vals = implode(',', $dat[$field]);
				
				$query = $CONFIG['dbconn']->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_mid_data,
														' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_mid,
														' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.filename,
														' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.filesys_filename
													FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full 
													WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_count = (:count)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_lang = (:lang)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_dev = (:dev)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_clid = (:id_clid)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_mid IN ('.$vals.')
													ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.filename
													');
				$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$query->bindValue(':count', $keyC, PDO::PARAM_INT);
				$query->bindValue(':lang', $keyL, PDO::PARAM_INT);
				$query->bindValue(':dev', $keyD, PDO::PARAM_INT);
				$query->bindValue(':id_clid', $_SESSION['admin']['USER']['activeClient'], PDO::PARAM_INT);
				$query->execute();
				$rows = $query->fetchAll(PDO::FETCH_ASSOC);
				$num = $query->rowCount();
					
				foreach($rows as $row){
					$dat[$field . 'T'] .= '<div class="fileUploadedOuter" data-id="' . $row['id_mid'] . '"><div class="fileUploadDelete"><span class="ui-icon ui-icon-trash" title="' . $TEXT['fileDelete'] . '" onclick="checkDeleteFile(' . $row['id_mid'] . ', this)"></span></div><div class="fileUploadFilename"><a href="' . $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathMedia'] . $row['filesys_filename'] . '" target="_blank">' . $row['filename'] . '</a></div></div>';
				}
				$dat[$field] = array();
			}else{
				$query = $CONFIG['dbconn']->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_mid_data,
														' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_mid,
														' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.filename,
														' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.filesys_filename
													FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full 
													WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_count = (:count)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_lang = (:lang)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_dev = (:dev)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_clid = (:id_clid)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_mid = (:id_mid)
													');
				$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$query->bindValue(':count', $keyC, PDO::PARAM_INT);
				$query->bindValue(':lang', $keyL, PDO::PARAM_INT);
				$query->bindValue(':dev', $keyD, PDO::PARAM_INT);
				$query->bindValue(':id_clid', $_SESSION['admin']['USER']['activeClient'], PDO::PARAM_INT);
				$query->bindValue(':id_mid', $dat[$field], PDO::PARAM_INT);
				$query->execute();
				$rows = $query->fetchAll(PDO::FETCH_ASSOC);
				$num = $query->rowCount();
				
				if($num > 0) $dat[$field . 'T'] = '<div class="fileUploadedOuter" data-id="' . $rows[0]['id_mid'] . '"><div class="fileUploadDelete"><span class="ui-icon ui-icon-trash" title="' . $TEXT['fileDelete'] . '" onclick="checkDeleteFile(' . $rows[0]['id_mid'] . ', this)"></span></div><div class="fileUploadFilename"><a href="' . $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathMedia'] . $rows[0]['filesys_filename'] . '" target="_blank">' . $rows[0]['filename'] . '</a></div></div>';
				$dat[$field] = '';
			}
		}
	}
	
	// File ID to Thumbnail
	if(isset($aFields['files2thumb'])){
		foreach($aFields['files2thumb'] as $field => $pf){
			$dat[$field . 'T'] = '';
			
			if(is_array($dat[$field])){
				// If field is array, no thumbnail will be generated
				$vals = implode(',', $dat[$field]);
				
				$query = $CONFIG['dbconn']->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_mid_data,
														' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_mid,
														' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.filename,
														' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.filesys_filename
													FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full 
													WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_count = (:count)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_lang = (:lang)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_dev = (:dev)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_clid = (:id_clid)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_mid IN ('.$vals.')
													ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.filename
													');
				$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$query->bindValue(':count', $keyC, PDO::PARAM_INT);
				$query->bindValue(':lang', $keyL, PDO::PARAM_INT);
				$query->bindValue(':dev', $keyD, PDO::PARAM_INT);
				$query->bindValue(':id_clid', $_SESSION['admin']['USER']['activeClient'], PDO::PARAM_INT);
				$query->execute();
				$rows = $query->fetchAll(PDO::FETCH_ASSOC);
				$num = $query->rowCount();
					
				foreach($rows as $row){
					$dat[$field . 'T'] .= '<div class="fileUploadedOuter" data-id="' . $row['id_mid'] . '"><div class="fileUploadDelete"><span class="ui-icon ui-icon-trash" title="' . $TEXT['fileDelete'] . '" onclick="checkDeleteFile(' . $row['id_mid'] . ', this)"></span></div><div class="fileUploadFilename"><a href="' . $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathMedia'] . $row['filesys_filename'] . '" target="_blank">' . $row['filename'] . '</a></div></div>';
				}
				$dat[$field] = array();
			}else{
				$query = $CONFIG['dbconn']->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_mid_data,
														' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_mid,
														' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.filename,
														' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.filesys_filename,
														' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.filehash
													FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full 
													WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_count = (:count)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_lang = (:lang)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_dev = (:dev)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_clid = (:id_clid)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_media_data_full.id_mid = (:id_mid)
													');
				$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$query->bindValue(':count', $keyC, PDO::PARAM_INT);
				$query->bindValue(':lang', $keyL, PDO::PARAM_INT);
				$query->bindValue(':dev', $keyD, PDO::PARAM_INT);
				$query->bindValue(':id_clid', $_SESSION['admin']['USER']['activeClient'], PDO::PARAM_INT);
				$query->bindValue(':id_mid', $dat[$field], PDO::PARAM_INT);
				$query->execute();
				$rows = $query->fetchAll(PDO::FETCH_ASSOC);
				$num = $query->rowCount();
				
				if($num > 0){
					$thumbnail = '';
					$pic = pictureSize($CONFIG['system']['pathInclude'].$CONFIG['system']['pathMedia'], $rows[0]['filesys_filename'], $CONFIG['system']['pathAssets'], $pf, $rows[0]['id_mid'], $rows[0]['filehash']);
					if(file_exists($CONFIG['system']['pathInclude'].$pic)){
						$info = getimagesize($CONFIG['system']['pathInclude'].$pic);
						$thumbnail = '<img src="'.$CONFIG['system']['pathInclude'].$pic.'" width="'.$info[0].'" height="'.$info[1].'">';
					}
					
					$dat[$field . 'T'] = '<div class="fileUploadedOuter fileUploadedOuterThumb" data-id="' . $rows[0]['id_mid'] . '"><div class="fileUploadDelete"><span class="ui-icon ui-icon-trash" title="' . $TEXT['fileDelete'] . '" onclick="checkDeleteFile(' . $rows[0]['id_mid'] . ', this)"></span></div><div class="fileUploadThumb">'.$thumbnail.'</div><div class="fileUploadFilename"><a href="' . $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathMedia'] . $rows[0]['filesys_filename'] . '" target="_blank">' . $rows[0]['filename'] . '</a></div></div>';
				}
				$dat[$field] = '';
			}
		}
	}
	
	return $dat;
}




?>