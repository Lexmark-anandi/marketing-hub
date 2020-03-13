<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();
  
$modulpath = $CONFIG['page']['modulpath'];

$listBanner = '';
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_bfid as id,
										' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.bannername AS term,
										' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.width,
										' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.height,
										' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.animated
									FROM ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.del = (:nultime)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.rank
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	$pages = ($row['animated'] == 1) ? 3 : 1;
	$listBanner .= '<div style="padding: 20px 0 10px 0">' . $row['term'] . ' (' . $row['width'] . 'x' . $row['height'] . ')</div>';
	
	for($i=1; $i <= $pages; $i++){
		$bannername = 'banner_original_' . $row['id'] . '_' . $i;
		$label = '';
		if($i == 1) $label = '<span style="padding-left: 20px;font-style:italic">' . $TEXT['firstframe'] . '</span>';
		if($i == 2) $label = '<span style="padding-left: 20px;font-style:italic">' . $TEXT['productframe'] . '</span>';
		if($i == 3) $label = '<span style="padding-left: 20px;font-style:italic">' . $TEXT['lastframe'] . '</span>';
		
		foreach($CONFIG['user']['countries'] as $id_countid => $aCountry){
			foreach($aCountry['languages'] as $id_langid){
				$listBanner .= '<div class="formRow formRowHidden " data-fieldvariation="'.$id_countid.'_'.$id_langid.'_x##' . $bannername . '"data-fieldname="' . $bannername . '">';
				$listBanner .= '<div class="formLabel">';
				$listBanner .= '<label for="logo_'.$modulpath.'">' . $label . '</label>';
				$listBanner .= '</div>';
				$listBanner .= '<div class="formField">';
				$listBanner .= '<input type="file" name="'.$id_countid.'_'.$id_langid.'_x_' . $bannername . '" id="'.$id_countid.'_'.$id_langid.'_x_' . $bannername . '_'.$modulpath.'" class="textfield fileupload " value="" data-checkfunction="" data-checkmessage="" data-fieldname="' . $bannername . '"  data-allowedtypes="gif,jpg,jgep,png,tif,tiff" data-target="banner" data-checksync="device">';
				$listBanner .= '</div>';
				$listBanner .= '</div>';
			}
		}
	}
}






echo $listBanner;


?>