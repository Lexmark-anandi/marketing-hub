<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$out = array();
$out['thumbnails'] = array();
$out['preview'] = '';
$out['components'] = '';
$out['aComponents'] = '';
$out['toolsform'] = '';
$out['bannerformats'] = '';


$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tpid,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_etid,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_kcid,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.file_original,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page_number,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename,
										' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.bannername
									FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni 

									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.file_original = ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid

									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni
										ON ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid = ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_bfid
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_dev = (:id_dev)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = (:id_tempid)
									');
$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
$query->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

if($num > 0){
	if($rows[0]['id_kcid'] > 0){
		#################################################
		// for kiado document
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
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_mid = ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_kcid = (:id_kcid)
											');
		$queryF->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
		$queryF->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
		$queryF->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
		$queryF->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryF->bindValue(':id_kcid', $rows[0]['id_kcid'], PDO::PARAM_INT);
		$queryF->execute();
		$rowsF = $queryF->fetchAll(PDO::FETCH_ASSOC);
		$numF = $queryF->rowCount();
		
		$out['preview'] = '';
		$out['thumbnails']['na'] = array();
		if($numF > 0){
			$aFilenameOriginal = explode('.', $rowsF[0]['filesys_filename']);
			$filenameOriginalEnd = array_pop($aFilenameOriginal);
			$filenameOriginalBase = implode('.', $aFilenameOriginal);
			$dirTarget = $CONFIG['system']['directoryInstallation'] . 'assetimages/';
			
			// build thumbnails
			$out['thumbnails']['na'] = array();
			for($i=1; $i <= $rowsF[0]['page_number']; $i++){
				$out['thumbnails']['na'][$i] = array('src' => $dirTarget . 'thumbnails/' . $filenameOriginalBase . '-' . $i . '.png', 'tp' => $rows[0]['id_tpid'], 'pageid' => $rows[0]['id_tpid'] . '_' . $i, 'page' => $i, 'pagelabel' => $i);
			}
		
			// build preview page
			$page = $CONFIG['system']['directoryRoot'] . 'assetimages/pictures/' . $filenameOriginalBase . '-' . $varSQL['page'] . '.png';
			if(file_exists($page)){
				$info = getimagesize($page);
				$out['preview'] = '<img src="' . $dirTarget . 'pictures/' . $filenameOriginalBase . '-' . $varSQL['page'] . '.png" data-width="' . $info[0] . '" data-height="' . $info[1] . '">';
			}else{
				$out['preview'] = '';
			}
		}
	
	}else if($rows[0]['id_bfid'] > 0){
		#################################################
		// for banner document
		
		// build thumnails
		$i = 0;
		foreach($rows as $row){
			if(!isset($out['thumbnails'][$row['bannername']])) $out['thumbnails'][$row['bannername']] = array();
			$pagelabel = '';
			if($row['page'] == 1) $pagelabel = $TEXT['firstframe'];
			if($row['page'] == 2) $pagelabel = $TEXT['productframe'];
			if($row['page'] == 3) $pagelabel = $TEXT['lastframe'];
	
			$i++;
			$dirTarget = $CONFIG['system']['directoryInstallation'] . 'media/';
	
			$out['thumbnails'][$row['bannername']][$row['page']] = array('src' => $dirTarget . '' . $row['filesys_filename'], 'tp' => $row['id_tpid'], 'pageid' => $row['id_tpid'] . '_' . $i, 'page' => $row['page'], 'pagelabel' => $pagelabel);
	
			// build preview page
			if($row['id_tpid'] == $varSQL['tp']){
				$page = $CONFIG['system']['directoryRoot'] . 'media/' . $row['filesys_filename'] . '';
				if(file_exists($page)){
					$info = getimagesize($page);
					$out['preview'] = '<img src="' . $dirTarget . '' . $row['filesys_filename'] . '" data-width="' . $info[0] . '" data-height="' . $info[1] . '">';
				}else{
					$out['preview'] = '';
				}
			}
		}
	
	
	}else if($rows[0]['id_etid'] > 0){
		#################################################
		// for email document
		$queryF = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_etid,
												' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.templatename,
												' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.image_edit
											FROM ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni 
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_etid = (:id_etid)
											');
//		$queryF->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//		$queryF->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//		$queryF->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
		$queryF->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryF->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryF->bindValue(':id_etid', $rows[0]['id_etid'], PDO::PARAM_INT);
		$queryF->execute();
		$rowsF = $queryF->fetchAll(PDO::FETCH_ASSOC);
		$numF = $queryF->rowCount();

		
		$dirImg = $CONFIG['system']['directoryInstallation'] . 'custom/assets/';

		$out['thumbnails']['na'] = array();
		$out['thumbnails']['na'][1] = array('src' => $dirImg . $rowsF[0]['image_edit'], 'tp' => $rows[0]['id_tpid'], 'pageid' => $rows[0]['id_tpid'] . '_1', 'page' => 1, 'pagelabel' => '1');
		
		// build preview page
		$firstpage = $CONFIG['system']['directoryRoot'] . 'custom/assets/' . $rowsF[0]['image_edit'] . '';
		if(file_exists($firstpage)){
			$info = getimagesize($firstpage);
			$out['preview'] = '<img src="' . $dirImg . $rowsF[0]['image_edit'] . '" data-width="' . $info[0] . '" data-height="' . $info[1] . '">';
		}else{
			$out['preview'] = '';
		}

	
	}else if($rows[0]['id_bfid'] == 0 && $rows[0]['id_etid'] == 0 && $rows[0]['file_original'] > 0){
		#################################################
		// for uploaded document

		// build thumnails
		$aFilenameOriginal = explode('.', $rows[0]['filesys_filename']);
		$filenameOriginalEnd = array_pop($aFilenameOriginal);
		$filenameOriginalBase = implode('.', $aFilenameOriginal);
		$dirTarget = $CONFIG['system']['directoryInstallation'] . 'assetimages/';
		
		$out['thumbnails']['na'] = array();
		for($i=1; $i <= $rows[0]['page_number']; $i++){
			$out['thumbnails']['na'][$i] = array('src' => $dirTarget . 'thumbnails/' . $filenameOriginalBase . '-' . $i . '.png', 'tp' => $rows[0]['id_tpid'], 'pageid' => $rows[0]['id_tpid'] . '_' . $i, 'page' => $i, 'pagelabel' => $i);
		}
	
		// build page
		$page = $CONFIG['system']['directoryRoot'] . 'assetimages/pictures/' . $filenameOriginalBase . '-' . $varSQL['page'] . '.png';
		if(file_exists($page)){
			$info = getimagesize($page);
			$out['preview'] = '<img src="' . $dirTarget . 'pictures/' . $filenameOriginalBase . '-' . $varSQL['page'] . '.png" data-width="' . $info[0] . '" data-height="' . $info[1] . '">';
		}else{
			$out['preview'] = '';
		}
	}
}

//include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-templates-components-build.php');


echo json_encode($out); 

?>