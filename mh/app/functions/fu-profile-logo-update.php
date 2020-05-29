<?php 
$CONFIG['noCheck'] = true;
include_once(__DIR__ . '/../config-app.php');
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');


$varSQL['targetpath'] = 'partnerlogos';
$field = 'partner_logo';
$mediaPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'];
$CONFIG['user']['id_real'] = $CONFIG['user']['id_ppid'];
$varSQL['orgfieldname'] = 'partner_logo';
$varSQL['multiple'] = '';
$mediafileIdData = $CONFIG['user']['id_pcid'];
$mediafileIdMod = 999999999;
$mediafileIdModParent = 0;
$mediafileIdPage = 999999999;

// save mediafolder
include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-media-upload-folder.php');


// Process File
$num = 0;
$filenameOrg = $_FILES[$field]['name'];
$lastCharOrg = strrpos($filenameOrg,".");
$filenameOrgBase = substr($filenameOrg, 0, $lastCharOrg);
$filenameOrgEnd = strtolower(substr(strrchr($filenameOrg, "."), 1));
$filenameBase = md5($filenameOrgBase);
$filename = $filenameBase . '.' . $filenameOrgEnd;

$handle = opendir($mediaPath);
while(file_exists($mediaPath . $filename)){
	$num++;
	$filename = $filenameBase . "-" . $num . '.' . $filenameOrgEnd;
}
closedir($handle);

move_uploaded_file($_FILES[$field]['tmp_name'], $mediaPath . $filename);
chmod($mediaPath . $filename , 0777);
system('convert ' . $mediaPath . $filename . ' -colorspace sRGB ' . $mediaPath . $filename);


// save mediafiles
$field = '0_0_0';
include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-media-upload-file.php');

$id_mid = $aArgsSave['id_data'];



// update partnerprofile
$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_loc SET
										logo = (:logo)

									WHERE ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_loc.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_loc.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_loc.id_pcid = (:id_pcid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_loc.del = (:nultime)
									');
$query->bindValue(':logo', $id_mid, PDO::PARAM_INT);
$query->bindValue(':id_count', 0, PDO::PARAM_INT);
$query->bindValue(':id_lang', 0, PDO::PARAM_INT);
$query->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->execute();
$num = $query->rowCount();

$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni SET
										logo = (:logo)

									WHERE ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_pcid = (:id_pcid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.del = (:nultime)
									');
$query->bindValue(':logo', $id_mid, PDO::PARAM_INT);
$query->bindValue(':id_count', 0, PDO::PARAM_INT);
$query->bindValue(':id_lang', 0, PDO::PARAM_INT);
$query->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->execute();
$num = $query->rowCount();


$logo_thumbnail = '';
$aArgsPic['id_count'] = 0;
$aArgsPic['id_lang'] = 0;
$aArgsPic['id_dev'] = 0;
$aArgsPic['id_mid'] = $id_mid;
$aArgsPic['pathOrg'] = $CONFIG['system']['pathMedia'];
$aArgsPic['fileOrg'] = $filename;
$aArgsPic['pathNew'] = $CONFIG['system']['pathAssets'];
$aArgsPic['filehash'] = md5_file($mediaPath . $filename);
$aArgsPic['id_pf'] = 4;
$aArgsPic['onlyShrink'] = 'Y';
$aArgsPic['sizing'] = 'Y';

$pic = pictureSize($aArgsPic);
if(file_exists($CONFIG['system']['directoryRoot'] . $pic)){
	$info = getimagesize($CONFIG['system']['directoryRoot']  .$pic);
	$logo_thumbnail = '<img src="'.$CONFIG['system']['directoryInstallation'] . $pic.'" width="' . $info[0] . '" height="' . $info[1] . '">';
}

echo $logo_thumbnail;


?>