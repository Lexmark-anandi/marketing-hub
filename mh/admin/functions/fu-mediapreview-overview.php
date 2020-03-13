<?php
$out = '';

$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filename,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filehash,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filetype,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.mediatype,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.alttext,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.keywords,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.size,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.width,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.height
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni 

									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:count)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:lang)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_dev = (:dev)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename = (:filename)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.del = (:nultime)
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':count', $CONFIG['settings']['selectCountry'], PDO::PARAM_INT);
$query->bindValue(':lang', $CONFIG['settings']['selectLanguage'], PDO::PARAM_INT);
$query->bindValue(':dev', $CONFIG['settings']['selectDevice'], PDO::PARAM_INT);
$query->bindValue(':filename', $varSQL['filename'], PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$rows[0]['dimension'] = '';
if($rows[0]['width'] > 0 && $rows[0]['height'] > 0) $rows[0]['dimension'] = $rows[0]['width'] . 'x' . $rows[0]['height'];

if($rows[0]['size'] < 1024){
	$rows[0]['size'] = $rows[0]['size'].' B';
}else if($rows[0]['size'] < pow(1024, 2)){
	$rows[0]['size'] = round(($rows[0]['size'] / 1000), 2) . ' KB';
}else if($rows[0]['size'] < pow(1024, 3)){
	$rows[0]['size'] = round(($rows[0]['size'] / 1000000), 2) . ' MB';
}else if($rows[0]['size'] < pow(1024, 4)){
	$rows[0]['size'] = round(($rows[0]['size'] / 1000000000), 2) . ' GB';
} 

$preview = $TEXT['noPreviewAvailable'];

$filelink = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'] . $rows[0]['filesys_filename'];

if(file_exists($filelink)){
	$rows[0]['lastchange'] = filemtime($filelink);

	if($rows[0]['dimension'] != ''){
		$aArgsPic['id_count'] = $CONFIG['settings']['selectCountry'];
		$aArgsPic['id_lang'] = $CONFIG['settings']['selectLanguage'];
		$aArgsPic['id_dev'] = $CONFIG['settings']['selectDevice'];
		$aArgsPic['id_mid'] = $rows[0]['id_mid'];
		$aArgsPic['pathOrg'] = $CONFIG['system']['pathMedia'];
		$aArgsPic['fileOrg'] = $rows[0]['filesys_filename'];
		$aArgsPic['pathNew'] = $CONFIG['system']['pathAssets'];
		$aArgsPic['filehash'] = $rows[0]['filehash'];
		$aArgsPic['id_pf'] = 2;
		$aArgsPic['onlyShrink'] = 'Y';
		$aArgsPic['sizing'] = 'Y';

		$pic = pictureSize($aArgsPic);
		if(file_exists($CONFIG['system']['directoryRoot'] . $pic)){
			$info = getimagesize($CONFIG['system']['directoryRoot']  .$pic);
			$preview = '<img src="' . $CONFIG['system']['directoryInstallation'] . $pic .'">';
		}else{
			$rows[0]['dimension'] = '';
		}
	}
}

$out .= '<div class="mediaPreviewOuter">';
$out .= '<div>
					<table cellpadding="0" cellspacing="0" border="0" class="tabMediaPreview">
						<tr>
							<td colspan="4"><div>' . $rows[0]['filename'] . '</div><div class="mediaPreviewFilesys">' . $rows[0]['filesys_filename'] . '</div></td>
							<td rowspan="2" class="buttonMediaDownload" onclick="downloadMedia(\''.$rows[0]['filesys_filename'].'\', \''.$rows[0]['filename'].'\', \'media\')">'.$TEXT['download'].'</td>
						</tr> 
						<tr>
							<td>' . strtoupper($rows[0]['filetype']) . '</td>
							<td>' . $rows[0]['dimension'] . '</td>
							<td>' . $rows[0]['size'] . '</td>
							<td>' . date('d.m.Y H:i', $rows[0]['lastchange']) . '</td>
						</tr>
					</table>
					
					
					
					
				</div>';




$out .= '<div class="mediaPreviewImage">' . $preview . '</div>';

$out .= '</div>';

echo $out;

?>