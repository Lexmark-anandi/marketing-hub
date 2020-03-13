<?php 
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-app.php');
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$out = array();
$aExportfiles = array();
$mediafile = '';

$query = $CONFIG['dbconn'][0]->prepare('
									SELECT 
										' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid,
										' . $CONFIG['db'][0]['prefix'] . '_templates_uni.title AS title_template,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_etid,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_kcid,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.file_original,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page, 
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page_number,
										' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.page_dimension,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filename,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename

									FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni 

									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_dev = (:id_dev) 
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_cl IN (0, 1)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid

									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_cl IN (0, 1)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.file_original = ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid

									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev = (:id_dev)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_cl IN (0, 1)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid = (:id_tempid)
									');
$query->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$query->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$query->bindValue(':id_dev', 0, PDO::PARAM_INT);
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':id_tempid', $varSQL['tempid'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();


// save export for stats
$queryEx = $CONFIG['dbconn'][0]->prepare('
									INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_assets2download_
										(id_asid, id_count, id_lang, id_dev, id_cl, id_promid, id_campid, id_tempid, id_pcid, id_ppid, exported_at)
										VALUES
										(:id_asid, :id_count, :id_lang, :id_dev, :id_cl, :id_promid, :id_campid, :id_tempid, :id_pcid, :id_ppid, :exported_at)
									');
$queryEx->bindValue(':id_asid', $varSQL['asid'], PDO::PARAM_INT);
$queryEx->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$queryEx->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$queryEx->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryEx->bindValue(':id_cl', 0, PDO::PARAM_INT);
$queryEx->bindValue(':id_promid', $varSQL['promid'], PDO::PARAM_INT);
$queryEx->bindValue(':id_campid', $varSQL['campid'], PDO::PARAM_INT);
$queryEx->bindValue(':id_tempid', $varSQL['tempid'], PDO::PARAM_INT);
$queryEx->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
$queryEx->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryEx->bindValue(':exported_at', $now, PDO::PARAM_STR);
$queryEx->execute();


		
if($rows[0]['id_kcid'] > 0){
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
	$queryF->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$queryF->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$queryF->bindValue(':id_dev', 0, PDO::PARAM_INT); 
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
		$queryF->bindValue(':id_kcid', $rows[0]['id_kcid'], PDO::PARAM_INT);
		$queryF->execute();
		$rowsF = $queryF->fetchAll(PDO::FETCH_ASSOC);
		$numF = $queryF->rowCount();
	}
	
	if($numF > 0){
		$pagenumber = $rowsF[0]['page_number'];
		$aPageDimension = json_decode($rowsF[0]['page_dimension'], true);
		$mediafile = $rowsF[0]['filesys_filename'];
	}
}else if($rows[0]['id_bfid'] == 0 && $rows[0]['id_etid'] == 0 && $rows[0]['file_original'] > 0){
	#################################################
	// for uploaded document
	#################################################
	$pagenumber = $rows[0]['page_number'];
	$aPageDimension = json_decode($rows[0]['page_dimension'], true);
	$mediafile = $rows[0]['filesys_filename'];
}



$folder = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathApp'] . 'tmp/' . $CONFIG['user']['id_ppid'] . '-' . str_replace(' ', '_', microtime());
mkdir($folder); 
chmod($folder, 0777);

copy($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'] . $mediafile, $folder . '/' . $mediafile);


$aExportfiles[0]['filename'] = urlencode($rows[0]['title_template']) . '.pdf';
$aExportfiles[0]['filesys_filename'] = $folder .'/' . $mediafile;
$aExportfiles[0]['folder'] = $folder;


$out['filename'] = $aExportfiles[0]['filename'];
$out['filesys_filename'] = $aExportfiles[0]['filesys_filename'];
$out['folder'] = $aExportfiles[0]['folder'];

echo json_encode($out);




		
?>