<?php
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-app.php');
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');


if($varSQL['pid'] == 0){
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.id_data_parent
										FROM ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni 
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.id_piid = (:id_piid)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.rank
										');
	$query->bindValue(':id_count',0, PDO::PARAM_INT);
	$query->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':id_piid', $varSQL['piid'], PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	
	if($num > 0) $varSQL['pid'] = $rows[0]['id_data_parent'];
}


$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.id_piid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filename,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filehash
									FROM ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni 
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid = ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.image
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.id_data_parent = (:id_pid)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_productsimages_uni.rank
									');
$query->bindValue(':id_count',$CONFIG['user']['id_countid'], PDO::PARAM_INT);
$query->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':id_pid', $varSQL['pid'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$listImages = '';
foreach($rows as $row){
	$aArgsPic = array();
	$aArgsPic['id_count'] = $CONFIG['user']['id_countid'];
	$aArgsPic['id_lang'] = $CONFIG['user']['id_langid'];
	$aArgsPic['id_dev'] = 0;
	$aArgsPic['id_mid'] = $row['id_mid'];
	$aArgsPic['pathOrg'] = $CONFIG['system']['pathMedia'];
	$aArgsPic['fileOrg'] = $row['filesys_filename'];
	$aArgsPic['pathNew'] = $CONFIG['system']['pathAssets'];
	$aArgsPic['filehash'] = $row['filehash'];
	$aArgsPic['id_pf'] = 6;
	$aArgsPic['onlyShrink'] = 'Y';
	$aArgsPic['sizing'] = 'Y';

	$pic = pictureSize($aArgsPic);
	if(file_exists($CONFIG['system']['directoryRoot'] . $pic)){
		$info = getimagesize($CONFIG['system']['directoryRoot']  .$pic);
		$listImages .= '<div class="selectImageOuter"><img src="'.$CONFIG['system']['directoryInstallation'] . $pic.'" data-piid="' . $row['id_piid'] . '"></div>';
	}
	
}



echo $listImages;
?>