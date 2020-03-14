<?php
$aImg = array('product_image', 'family_image');

foreach($aImg as $img){
	$aChangedVersions = array();
	
	$aImage = array();
	$aImage['id_pid'] = $aProduct['id_pid'];
	$aImage['type'] = $img;
	$aImage['url'] = $product->Images->$img;
	
	if($aImage['url'] == ''){
		$aImgPrefix = '';
		if(strlen($aProduct['revenue_pid']) == 3) $aImgPrefix = '00';
		if(strlen($aProduct['revenue_pid']) == 4) $aImgPrefix = '0' . substr($aProduct['revenue_pid'], 0, 1);
		if(strlen($aProduct['revenue_pid']) == 5) $aImgPrefix = substr($aProduct['revenue_pid'], 0, 2);
		$aImage['url'] = '//media.lexmark.com/www/product/standard/' . $aImgPrefix . '/' . $aProduct['revenue_pid'] . '.png';
		
		$imgUrl .= $aImage['url'] . '** ';
	}
	
	if($aImage['url'] != ''){
		#############################################################
		// process file
		$targetpath = $CONFIG['system']['pathInclude'] . 'media/';
		
		$filenameOrg = str_replace('/', '_', $aImage['url']);
		$lastCharOrg = strrpos($filenameOrg,".");
		$filenameOrgBase = substr($filenameOrg, 0, $lastCharOrg);
		$filenameOrgEnd = strtolower(substr(strrchr($filenameOrg, "."), 1));
		$filenameBase = md5($filenameOrgBase);
		$filename = $filenameBase . '.' . $filenameOrgEnd;
	
		if(!file_exists($targetpath . $filename)){
			@copy('http:' . $aImage['url'], $targetpath . $filename);
		}
		
		if(file_exists($targetpath . $filename)){
			$fmediatype = finfo_open(FILEINFO_MIME_TYPE);
			$picInfo = getimagesize($targetpath . $filename);
			$picWidth = $picInfo[0];
			$picHeight = $picInfo[1];
			
			$aData = array();
			$aData['id_mpid'] = 2;
			$aData['filename'] = $filenameOrg;
			$aData['filesys_filename'] = $filename;
			$aData['width'] = $picWidth;
			$aData['height'] = $picHeight;
			$aData['mediatype'] = finfo_file($fmediatype, $targetpath . $filename);
			$aData['size'] = filesize($targetpath . $filename);
			$aData['filetype'] = $filenameOrgEnd;
			$aData['alttext'] = '';
			$aData['keywords'] = '';
			$aData['id_page'] = 0;
			$aData['fieldname'] = '';
			$aData['filehash'] = md5_file($targetpath . $filename);
			
			$queryPt = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid
												FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_dev = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename = (:filesys_filename)
												');
			$queryPt->bindValue(':nul', 0, PDO::PARAM_INT);
			$queryPt->bindValue(':filesys_filename', $aData['filesys_filename'], PDO::PARAM_STR);
			$queryPt->execute();
			$rowsPt = $queryPt->fetchAll(PDO::FETCH_ASSOC);
			$numPt = $queryPt->rowCount();
			
			if($numPt == 0){
				foreach($aArgs['aListLanguagesByCountries'][$id_count] as $lang){
					if($lang != 0) array_push($aChangedVersions, array($id_count, $lang, 0));
				}
	
				$queryPt2 = $CONFIG['dbconn']->prepare('
													INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles
													(id_clid, create_at, create_from, change_from)
													VALUES
													(:id_clid, :create_at, :create_from, :create_from)
													');
				$queryPt2->bindValue(':id_clid', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':create_at', $now, PDO::PARAM_STR);
				$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
				$queryPt2->execute();
				$aImage['id_mid'] = $CONFIG['dbconn']->lastInsertId();
		
			
				$queryPt2 = $CONFIG['dbconn']->prepare('
													INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_ext
													(
														id_mid,
														id_count,
														id_lang,
														id_dev,
														id_clid,
														id_mpid,
														id_page,
														fieldname,
														filename,
														filesys_filename,
														filehash,
														width,
														height,
														mediatype,
														size,
														filetype,
														alttext,
														keywords,
														create_at,
														create_from,
														change_from
													)
													VALUES
													(
														:id_mid,
														:id_count,
														:id_lang,
														:id_dev,
														:id_clid,
														:id_mpid,
														:id_page,
														:fieldname,
														:filename,
														:filesys_filename,
														:filehash,
														:width,
														:height,
														:mediatype,
														:size,
														:filetype,
														:alttext,
														:keywords,
														:create_at,
														:create_from,
														:change_from
													)
													');
				$queryPt2->bindValue(':id_mid', $aImage['id_mid'], PDO::PARAM_INT);
				$queryPt2->bindValue(':id_count', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_lang', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_clid', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_mpid', trim($aData['id_mpid']), PDO::PARAM_INT);
				$queryPt2->bindValue(':id_page', trim($aData['id_page']), PDO::PARAM_INT);
				$queryPt2->bindValue(':fieldname', trim($aData['fieldname']), PDO::PARAM_INT);
				$queryPt2->bindValue(':filename', trim($aData['filename']), PDO::PARAM_STR);
				$queryPt2->bindValue(':filesys_filename', trim($aData['filesys_filename']), PDO::PARAM_STR);
				$queryPt2->bindValue(':filehash', trim($aData['filehash']), PDO::PARAM_STR);
				$queryPt2->bindValue(':width', trim($aData['width']), PDO::PARAM_INT);
				$queryPt2->bindValue(':height', trim($aData['height']), PDO::PARAM_INT);
				$queryPt2->bindValue(':mediatype', trim($aData['mediatype']), PDO::PARAM_STR);
				$queryPt2->bindValue(':size', trim($aData['size']), PDO::PARAM_INT);
				$queryPt2->bindValue(':filetype', trim($aData['filetype']), PDO::PARAM_STR);
				$queryPt2->bindValue(':alttext', trim($aData['alttext']), PDO::PARAM_STR);
				$queryPt2->bindValue(':keywords', trim($aData['keywords']), PDO::PARAM_STR);
				$queryPt2->bindValue(':create_at', $now, PDO::PARAM_STR);
				$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':change_from', 0, PDO::PARAM_INT);
				$queryPt2->execute();
		
				$queryPt2 = $CONFIG['dbconn']->prepare('
													INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
													(
														id_mid,
														id_count,
														id_lang,
														id_dev,
														id_clid,
														id_mpid,
														id_page,
														fieldname,
														filename,
														filesys_filename,
														filehash,
														width,
														height,
														mediatype,
														size,
														filetype,
														alttext,
														keywords,
														create_at,
														create_from,
														change_from
													)
													VALUES
													(
														:id_mid,
														:id_count,
														:id_lang,
														:id_dev,
														:id_clid,
														:id_mpid,
														:id_page,
														:fieldname,
														:filename,
														:filesys_filename,
														:filehash,
														:width,
														:height,
														:mediatype,
														:size,
														:filetype,
														:alttext,
														:keywords,
														:create_at,
														:create_from,
														:change_from
													)
													');
				$queryPt2->bindValue(':id_mid', $aImage['id_mid'], PDO::PARAM_INT);
				$queryPt2->bindValue(':id_count', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_lang', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_clid', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_mpid', trim($aData['id_mpid']), PDO::PARAM_INT);
				$queryPt2->bindValue(':id_page', trim($aData['id_page']), PDO::PARAM_INT);
				$queryPt2->bindValue(':fieldname', trim($aData['fieldname']), PDO::PARAM_INT);
				$queryPt2->bindValue(':filename', trim($aData['filename']), PDO::PARAM_STR);
				$queryPt2->bindValue(':filesys_filename', trim($aData['filesys_filename']), PDO::PARAM_STR);
				$queryPt2->bindValue(':filehash', trim($aData['filehash']), PDO::PARAM_STR);
				$queryPt2->bindValue(':width', trim($aData['width']), PDO::PARAM_INT);
				$queryPt2->bindValue(':height', trim($aData['height']), PDO::PARAM_INT);
				$queryPt2->bindValue(':mediatype', trim($aData['mediatype']), PDO::PARAM_STR);
				$queryPt2->bindValue(':size', trim($aData['size']), PDO::PARAM_INT);
				$queryPt2->bindValue(':filetype', trim($aData['filetype']), PDO::PARAM_STR);
				$queryPt2->bindValue(':alttext', trim($aData['alttext']), PDO::PARAM_STR);
				$queryPt2->bindValue(':keywords', trim($aData['keywords']), PDO::PARAM_STR);
				$queryPt2->bindValue(':create_at', $now, PDO::PARAM_STR);
				$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':change_from', 0, PDO::PARAM_INT);
				$queryPt2->execute();
		
			
			
				$queryPt2 = $CONFIG['dbconn']->prepare('
													INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_ext
													(
														id_mid,
														id_count,
														id_lang,
														id_dev,
														id_clid,
														id_mpid,
														id_page,
														fieldname,
														filename,
														filesys_filename,
														filehash,
														width,
														height,
														mediatype,
														size,
														filetype,
														alttext,
														keywords,
														create_at,
														create_from,
														change_from
													)
													VALUES
													(
														:id_mid,
														:id_count,
														:id_lang,
														:id_dev,
														:id_clid,
														:id_mpid,
														:id_page,
														:fieldname,
														:filename,
														:filesys_filename,
														:filehash,
														:width,
														:height,
														:mediatype,
														:size,
														:filetype,
														:alttext,
														:keywords,
														:create_at,
														:create_from,
														:change_from
													)
													');
				$queryPt2->bindValue(':id_mid', $aImage['id_mid'], PDO::PARAM_INT);
				$queryPt2->bindValue(':id_count', $id_count, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_clid', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_mpid', trim($aData['id_mpid']), PDO::PARAM_INT);
				$queryPt2->bindValue(':id_page', trim($aData['id_page']), PDO::PARAM_INT);
				$queryPt2->bindValue(':fieldname', trim($aData['fieldname']), PDO::PARAM_INT);
				$queryPt2->bindValue(':filename', trim($aData['filename']), PDO::PARAM_STR);
				$queryPt2->bindValue(':filesys_filename', trim($aData['filesys_filename']), PDO::PARAM_STR);
				$queryPt2->bindValue(':filehash', trim($aData['filehash']), PDO::PARAM_STR);
				$queryPt2->bindValue(':width', trim($aData['width']), PDO::PARAM_INT);
				$queryPt2->bindValue(':height', trim($aData['height']), PDO::PARAM_INT);
				$queryPt2->bindValue(':mediatype', trim($aData['mediatype']), PDO::PARAM_STR);
				$queryPt2->bindValue(':size', trim($aData['size']), PDO::PARAM_INT);
				$queryPt2->bindValue(':filetype', trim($aData['filetype']), PDO::PARAM_STR);
				$queryPt2->bindValue(':alttext', trim($aData['alttext']), PDO::PARAM_STR);
				$queryPt2->bindValue(':keywords', trim($aData['keywords']), PDO::PARAM_STR);
				$queryPt2->bindValue(':create_at', $now, PDO::PARAM_STR);
				$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':change_from', 0, PDO::PARAM_INT);
				$queryPt2->execute();
				
			}else{
				$aImage['id_mid'] = $rowsPt[0]['id_mid'];
				array_push($aChangedVersions, array($id_count, $id_lang, 0));
			
				$queryPt2 = $CONFIG['dbconn']->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid
													FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni 
													WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_dev = (:id_dev)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename = (:filesys_filename)
													');
				$queryPt2->bindValue(':id_count', $id_count, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':filesys_filename', $aData['filesys_filename'], PDO::PARAM_STR);
				$queryPt2->execute();
				$rowsPt2 = $queryPt2->fetchAll(PDO::FETCH_ASSOC);
				$numPt2 = $queryPt2->rowCount();
		
				if($numPt2 == 0){
					// first time for country / language
					$queryPt3 = $CONFIG['dbconn']->prepare('
														INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_ext
														(
															id_mid,
															id_count,
															id_lang,
															id_dev,
															id_clid,
															id_mpid,
															create_at,
															create_from
														)
														VALUES
														(
															:id_mid,
															:id_count,
															:id_lang,
															:id_dev,
															:id_clid,
															:id_mpid,
															:create_at,
															:create_from
														)
														');
					$queryPt3->bindValue(':id_mid', $aImage['id_mid'], PDO::PARAM_INT);
					$queryPt3->bindValue(':id_count', $id_count, PDO::PARAM_INT);
					$queryPt3->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
					$queryPt3->bindValue(':id_dev', 0, PDO::PARAM_INT);
					$queryPt3->bindValue(':id_clid', 0, PDO::PARAM_INT);
					$queryPt3->bindValue(':id_mpid', trim($aData['id_mpid']), PDO::PARAM_INT);
					$queryPt3->bindValue(':create_at', $now, PDO::PARAM_INT);
					$queryPt3->bindValue(':create_from', 0, PDO::PARAM_INT);
					$queryPt3->execute();
					
				}else{
					// nothing for update for country / language
				}
			}
			
			#############################################################################
			$modul = 'import';
			$table = $CONFIG['db'][0]['prefix'] . 'sys_mediafiles';
			$primekey = 'id_mid';
			$CONFIG['page']['moduls'][$modul]['specifics'] = '09099000';
			$CONFIG['page']['moduls'][$modul]['formCountry'] = $id_count;
			$CONFIG['page']['moduls'][$modul]['formLanguage'] = $id_lang;
			$CONFIG['page']['moduls'][$modul]['formDevice'] = 0;
			$aFieldsNumbers = array('id_mid', 'id_mpid');
			$columns = '';
			$aData['id_mid'] = $aImage['id_mid'];
			foreach($aData as $field => $value){
				$columns .= '' . $table . '_##TYPE##.' . $field . ', ';
			}
			$columns = rtrim($columns, ', ');
			
			insertAll($modul, $table, $primekey, $aImage['id_mid'], $columns, $aFieldsNumbers, $aChangedVersions, '');
			#############################################################
			
			
			
			
			$aChangedVersions = array();
			
			$queryPt = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . '_images_ext.id_imgid
												FROM ' . $CONFIG['db'][0]['prefix'] . '_images_ext 
												WHERE ' . $CONFIG['db'][0]['prefix'] . '_images_ext.id_count = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . '_images_ext.id_lang = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . '_images_ext.id_dev = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . '_images_ext.id_clid = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . '_images_ext.id_pid = (:id_pid)
													AND ' . $CONFIG['db'][0]['prefix'] . '_images_ext.type = (:type)
												');
			$queryPt->bindValue(':nul', 0, PDO::PARAM_INT);
			$queryPt->bindValue(':id_pid', $aImage['id_pid'], PDO::PARAM_INT);
			$queryPt->bindValue(':type', $aImage['type'], PDO::PARAM_INT);
			$queryPt->execute();
			$rowsPt = $queryPt->fetchAll(PDO::FETCH_ASSOC);
			$numPt = $queryPt->rowCount();
			
			if($numPt == 0){
				// first time for all / all
				foreach($aArgs['aListLanguagesByCountries'][$id_count] as $lang){
					if($lang != 0) array_push($aChangedVersions, array($id_count, $lang, 0));
				}
	
				$queryPt2 = $CONFIG['dbconn']->prepare('
													INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_images
														(create_at, create_from)
													VALUES
														(:create_at, :create_from)
													');
				$queryPt2->bindValue(':create_at', $now, PDO::PARAM_STR);
				$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
				$queryPt2->execute();
				$aImage['id_imgid'] = $CONFIG['dbconn']->lastInsertId();
				
				// save all / all
				$queryPt2 = $CONFIG['dbconn']->prepare('
													INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_images_ext
														(id_count, id_lang, id_dev, create_at, create_from, ' . implode(', ', array_keys($aImage)) . ')
													VALUES
														(:id_count, :id_lang, :id_dev, :create_at, :create_from, :' . implode(', :', array_keys($aImage)) . ')
													');
				$queryPt2->bindValue(':id_count', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_lang', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
				$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
				foreach($aImage as $field => $value){
					$queryPt2->bindValue(':'.$field, $value, PDO::PARAM_STR);
				}
				$queryPt2->execute();
				
				$queryPt2 = $CONFIG['dbconn']->prepare('
													INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_images_uni
														(id_count, id_lang, id_dev, create_at, create_from, ' . implode(', ', array_keys($aImage)) . ')
													VALUES
														(:id_count, :id_lang, :id_dev, :create_at, :create_from, :' . implode(', :', array_keys($aImage)) . ')
													');
				$queryPt2->bindValue(':id_count', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_lang', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
				$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
				foreach($aImage as $field => $value){
					$queryPt2->bindValue(':'.$field, $value, PDO::PARAM_STR);
				}
				$queryPt2->execute();
				
				// save country / language
				$queryPt2 = $CONFIG['dbconn']->prepare('
													INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_images_ext
														(id_count, id_lang, id_dev, create_at, create_from, ' . implode(', ', array_keys($aImage)) . ')
													VALUES
														(:id_count, :id_lang, :id_dev, :create_at, :create_from, :' . implode(', :', array_keys($aImage)) . ')
													');
				$queryPt2->bindValue(':id_count', $id_count, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
				$queryPt2->bindValue(':id_dev', 0, PDO::PARAM_INT);
				$queryPt2->bindValue(':create_at', $now, PDO::PARAM_INT);
				$queryPt2->bindValue(':create_from', 0, PDO::PARAM_INT);
				foreach($aImage as $field => $value){
					$queryPt2->bindValue(':'.$field, $value, PDO::PARAM_STR);
				}
				$queryPt2->execute();
				
			}else{
				$aImage['id_imgid'] = $rowsPt[0]['id_imgid'];
				array_push($aChangedVersions, array($id_count, $id_lang, 0));
				
				$col = '';
				$value = '';
				$upd = '';
				foreach($aImage as $key => $val){
					$col .= ', ' . $key;
					$value .= ', "' . str_replace('"', '\"', $val) . '"';
					$upd .= $key.' = "' . str_replace('"', '\"', $val) . '", ' ;
				}
				
				$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_images_ext ';
				$qry .= '(id_count, id_lang, id_dev, create_at, create_from ' . $col . ') ';
				$qry .= 'VALUES ';					
				$qry .= '('.$id_count.', '.$id_lang.', 0, "'.$now.'", 0 ' . $value . ') '; 
				$qry .= 'ON DUPLICATE KEY UPDATE ';	
				$qry .= '' . $upd . ' change_from=0, del="0000-00-00 00:00:00";';
				$queryPt2 = $CONFIG['dbconn']->prepare($qry);
				$queryPt2->execute();
		
			}
			
			
			
			#############################################################################
			$modul = 'import';
			$table = $CONFIG['db'][0]['prefix'] . '_images';
			$primekey = 'id_imgid';
			$aFieldsNumbers = array('id_pid');
	
			$columnsExtAll = '';
			$columnsExtLoc = '';
			$columnsLocAll = '';
			$columnsLocLoc = '';
			foreach($aImage as $field => $value){
				$columnsExtAll .= '' . $table . '_##TYPE##.' . $field . ', ';
				$columnsExtLoc .= '' . $table . '_##TYPE##.' . $field . ' AS ' . $field . '_extloc, ';
				$columnsLocAll .= '' . $table . '_##TYPE##.' . $field . ' AS ' . $field . '_locall, ';
				$columnsLocLoc .= '' . $table . '_##TYPE##.' . $field . ' AS ' . $field . '_locloc, ';
			}
			$columnsExtAll = rtrim($columnsExtAll, ', ');
			$columnsExtLoc = rtrim($columnsExtLoc, ', ');
			$columnsLocAll = rtrim($columnsLocAll, ', ');
			$columnsLocLoc = rtrim($columnsLocLoc, ', ');
			$aColumns = array($columnsExtAll, $columnsExtLoc, $columnsLocAll, $columnsLocLoc);
			
			insertAllProducts($modul, $table, $primekey, $aImage['id_imgid'], $aColumns, $aFieldsNumbers, $aChangedVersions, ' AND ' . $CONFIG['db'][0]['prefix'] . '_images_##TYPE##.id_pid=' . $aImage['id_pid'], $aArgs['saveVer']);
		}
		#############################
	}
}
	
?>