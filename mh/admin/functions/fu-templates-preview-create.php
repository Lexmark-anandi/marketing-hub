<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

 
$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$tid = (isset($tid_trans)) ? $tid_trans : $CONFIG['page']['id_data'];


$html = ''; 

$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.components,
										' . $CONFIG['db'][0]['prefix'] . '_templates_uni.title,
										' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_etid,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_kcid,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.file_original,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page_number,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page_dimension,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filename,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.currency

									FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni 

									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_count = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_lang = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_dev = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_cl IN (0, 1)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid

									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_
										ON ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.del = (:nultime)

									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
										ON ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid

									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid <> (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.del = (:nultime)

									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_dev = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_cl IN (0, 1)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.file_original = ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid

									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid = (:id_tempid)
									GROUP BY CONCAT(' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid, "-", ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang)
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->bindValue(':id_tempid', $tid, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	$id_caid = $row['id_caid'];
	$foldername = $CONFIG['user']['id'] . '-' . str_replace(' ', '_', microtime());
	$folder = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathAdmin'] . 'tmp/' . $foldername;
	mkdir($folder); 
	chmod($folder, 0777);

	switch($id_caid){
		// Banner
		case '1': 
			include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-templates-preview-create-banner.php');
			break;
			
		// Print Ad
		case '2': 
			include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-templates-preview-create-pdf.php');
			break;
			
		// Email
		case '3': 
			include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-templates-preview-create-email.php');
			break;
			
		// Specsheets
		case '4': 
			include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-templates-preview-create-pdf.php');
			break;
			
		// Brochure
		case '5': 
			include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-templates-preview-create-pdf.php');
			break;
			
		// Flyer (Promotion)
		case '8': 
			break;
			
		// Poster (Campaign)
		case '9': 
			break;
			
		// Rollup
		case '10': 
			include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-templates-preview-create-pdf.php');
			break;
	}


}



$dirTargetGallery = $CONFIG['system']['directoryRoot'] . 'assetimages/templates_gallery/';
array_map('unlink', glob($dirTargetGallery . "/" . str_pad($tid, 6 ,'0', STR_PAD_LEFT) . "*"));

		


?>