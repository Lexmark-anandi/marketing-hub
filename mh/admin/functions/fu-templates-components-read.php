<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$modulpath = $CONFIG['page']['modulpath'];

$out = array();
$out['thumbnails'] = array();
$out['preview'] = '';
$out['components'] = '';
$out['aComponents'] = '';
$out['toolsform'] = '';
$out['bannerformats'] = '';

$aBannerFiles = array();

$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tpid,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_etid,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_kcid,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.file_original,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page_number,
										' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filename,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename,
										' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.bannername
									FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni 

									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
										ON ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid

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
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid, ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page
									');
$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
$query->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$id_acid = 0;

if($num > 0){
	$id_acid = $rows[0]['id_caid'];
	
	if($rows[0]['id_bfid'] > 0){
		foreach($rows as $row){
			if(!array_key_exists($row['id_bfid'], $aBannerFiles)) $aBannerFiles[$row['id_bfid']] = array();
			if(!array_key_exists($row['page'], $aBannerFiles[$row['id_bfid']])){
				$aBannerFiles[$row['id_bfid']][$row['page']] = array();
				$aBannerFiles[$row['id_bfid']][$row['page']]['tpid'] = $row['id_tpid'];
				$aBannerFiles[$row['id_bfid']][$row['page']]['mid'] = $row['file_original'];
				$aBannerFiles[$row['id_bfid']][$row['page']]['filename'] = $row['filename'];
				$aBannerFiles[$row['id_bfid']][$row['page']]['filesys_filename'] = $row['filesys_filename'];
			}
		}
	}
	
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
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
														AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_mid = ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid
												
												WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_dev = (:id_dev)
													AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
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
			$aFilenameOriginal = explode('.', $rowsF[0]['filesys_filename']);
			$filenameOriginalEnd = array_pop($aFilenameOriginal);
			$filenameOriginalBase = implode('.', $aFilenameOriginal);
			$dirTarget = $CONFIG['system']['directoryInstallation'] . 'assetimages/';
			
			// build thumnails
			$out['thumbnails']['na'] = array();
			for($i=1; $i <= $rowsF[0]['page_number']; $i++){
				$out['thumbnails']['na'][$i] = array('src' => $dirTarget . 'thumbnails/' . $filenameOriginalBase . '-' . $i . '.png', 'tp' => $rows[0]['id_tpid'], 'pageid' => $rows[0]['id_tpid'] . '_' . $i, 'page' => $i, 'pagelabel' => $i);
			}
		
			// build preview page
			$firstpage = $CONFIG['system']['directoryRoot'] . 'assetimages/pictures/' . $filenameOriginalBase . '-1.png';
			if(file_exists($firstpage)){
				$info = getimagesize($firstpage);
				$out['preview'] = '<img src="' . $dirTarget . 'pictures/' . $filenameOriginalBase . '-1.png" data-width="' . $info[0] . '" data-height="' . $info[1] . '">';
			}else{
				$out['preview'] = '';
			}
		}
		
	}else if($rows[0]['id_bfid'] > 0){
		#################################################
		// for banner document
		
		// build thumnails
		foreach($rows as $row){
			if(!isset($out['thumbnails'][$row['bannername']])) $out['thumbnails'][$row['bannername']] = array();
			$pagelabel = '';
			if($row['page'] == 1) $pagelabel = $TEXT['firstframe'];
			if($row['page'] == 2) $pagelabel = $TEXT['productframe'];
			if($row['page'] == 3) $pagelabel = $TEXT['lastframe'];
	
			$dirTarget = $CONFIG['system']['directoryInstallation'] . 'media/';
	
			$out['thumbnails'][$row['bannername']][$row['page']] = array('src' => $dirTarget . '' . $row['filesys_filename'], 'tp' => $row['id_tpid'], 'bfid' => $row['id_bfid'], 'pageid' => $row['id_tpid'] . '_' . $row['page'], 'page' => $row['page'], 'pagelabel' => $pagelabel);
		}
		
		// build preview page
		$firstpage = $CONFIG['system']['directoryRoot'] . 'media/' . $rows[0]['filesys_filename'] . '';
		if(file_exists($firstpage)){
			$info = getimagesize($firstpage);
			$out['preview'] = '<img src="' . $dirTarget . '' . $rows[0]['filesys_filename'] . '" data-width="' . $info[0] . '" data-height="' . $info[1] . '">';
		}else{
			$out['preview'] = '';
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

		
		// build thumnails
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
	
		// build preview page
		$firstpage = $CONFIG['system']['directoryRoot'] . 'assetimages/pictures/' . $filenameOriginalBase . '-1.png';
		if(file_exists($firstpage)){
			$info = getimagesize($firstpage);
			$out['preview'] = '<img src="' . $dirTarget . 'pictures/' . $filenameOriginalBase . '-1.png" data-width="' . $info[0] . '" data-height="' . $info[1] . '">';
		}else{
			$out['preview'] = '';
		}
	}
}




#################################################
// build bannerformats
#################################################
if($id_acid == 1){
	$queryBf = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_bfid,
											' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.bannername,
											' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.width,
											' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.height
										FROM ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni 
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_tempid = (:id_tempid)
										GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_bfid
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_bfid
										');
	$queryBf->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryBf->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
	$queryBf->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
	$queryBf->bindValue(':id_dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
	$queryBf->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_INT);
	$queryBf->execute();
	$rowsBf = $queryBf->fetchAll(PDO::FETCH_ASSOC);
	$numBf = $queryBf->rowCount();

	foreach($rowsBf as $rowBf){
		$out['bannerformats'] .= '<div class="formBannerformatOuter" data-bfid="' . $rowBf['id_bfid'] . '">';
		$out['bannerformats'] .= '<div class="formRow formRowNoBorder formRowBannerfiles">';
		$out['bannerformats'] .= '<span class="formBannerformatName"><strong>' . $rowBf['bannername']. '</strong></span> (' . $rowBf['width'] . 'x' . $rowBf['height'] . ')';
		$out['bannerformats'] .= '<div class="modulIcon modulIconForm modulIconFloatRight modulIconDelete" title="'. $TEXT['titleDeleteRow'] . '"><i class="fa fa-trash"></i></div>';
		$out['bannerformats'] .= '<div class="modulIcon modulIconForm modulIconFloatRight modulIconEdit" title="'. $TEXT['titleEditRow'] . '"><i class="fa fa-pencil"></i></div>';
		$out['bannerformats'] .= '</div>';
		
		for($i = 1; $i <= 3; $i++){
			$label = '';
			if($i == 1) $label = '<span style="padding-left: 20px;font-style:italic">' . $TEXT['firstframe'] . '</span>';
			if($i == 2) $label = '<span style="padding-left: 20px;font-style:italic">' . $TEXT['productframe'] . '</span>';
			if($i == 3) $label = '<span style="padding-left: 20px;font-style:italic">' . $TEXT['lastframe'] . '</span>';
			
			$classSpace = '';
			if($i == 3) $classSpace = 'formRowSpace';
			
			$out['bannerformats'] .= '<div class="formRow ' . $classSpace . '">';
			$out['bannerformats'] .= '<div class="formLabel">';
			$out['bannerformats'] .= '<label for="">' . $label . '</label>';
			$out['bannerformats'] .= '</div>';
			$out['bannerformats'] .= '<div class="formField">';
			if(isset($aBannerFiles[$rowBf['id_bfid']][$i])) $out['bannerformats'] .= '<div class="formBannerFile" data-tpid="' . $aBannerFiles[$rowBf['id_bfid']][$i]['tpid'] . '" data-mid="' . $aBannerFiles[$rowBf['id_bfid']][$i]['mid'] . '"><a href="' . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathMedia'] . $aBannerFiles[$rowBf['id_bfid']][$i]['filesys_filename'] . '" target="_blank">' . $aBannerFiles[$rowBf['id_bfid']][$i]['filename'] . '</a></div>';
			$out['bannerformats'] .= '</div>';
			$out['bannerformats'] .= '</div>';
		}
		$out['bannerformats'] .= '</div>';
	}
}




include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-templates-components-build.php');


echo json_encode($out); 

?>