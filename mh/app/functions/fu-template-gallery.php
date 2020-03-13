<?php 
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-app.php');
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$aGallery = json_decode($varSQL['data'], true);

$aImages = array();
$aImgRaw = array();
$listImages = '';

$aGal = array();
$aGal['galWidth'] = 0;
$aGal['galHeight'] = 0;
$aGal['galWidthMax'] = round($aGallery['width'] - (($aGallery['width'] / 100) * 30));
$aGal['galHeightMax'] = round($aGallery['height'] - (($aGallery['height'] / 100) * 30));
$aGal['paddingH'] = 60;
$aGal['paddingV'] = 40;
$classNavigator = '';
 
if($aGallery['width'] < 800){
	$aGal['galWidth'] = round($aGallery['width']);
	$aGal['galHeight'] = round($aGallery['height']);
	$aGal['galWidthMax'] = round($aGallery['width']);
	$aGal['galHeightMax'] = round($aGallery['height']);
	$aGal['paddingH'] = 0;
	$aGal['paddingV'] = 0;
	$classNavigator = 'noSliderNavigator';
}


$dirTarget = 'assetimages/templates_preview/';
$dirTargetGallery = 'assetimages/templates_gallery/';




if($aGallery['campid'] != 0){
	$queryT = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid,
											' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni 
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_campid = (:id_campid)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.rank
										');
	$queryT->bindValue(':id_count',$CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$queryT->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$queryT->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryT->bindValue(':id_campid', $aGallery['campid'], PDO::PARAM_INT);
	
}else if($aGallery['promid'] != 0){
	
	$queryT = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid,
											' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni 
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid = (:id_promid)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.rank
										');
	$queryT->bindValue(':id_count',$CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$queryT->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$queryT->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryT->bindValue(':id_promid', $aGallery['promid'], PDO::PARAM_INT);
	
}else{
	
	$queryT = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid,
											' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni 
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid = (:id_tempid)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.rank
										');
	$queryT->bindValue(':id_count',$CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$queryT->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$queryT->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryT->bindValue(':id_tempid', $aGallery['tempid'], PDO::PARAM_INT);
}


$i = 0;

$queryT->execute();
$rowsT = $queryT->fetchAll(PDO::FETCH_ASSOC);
$numT = $queryT->rowCount();
foreach($rowsT as $rowT){
	$aGallery['tempid'] = $rowT['id_tempid'];
	$aGallery['caid'] = $rowT['id_caid'];
	
	if($aGallery['caid'] == 1){
		// Banner
		$query = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_bfid
											FROM ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni 
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_tempid = (:id_tempid)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.create_at
											');
		$query->bindValue(':id_count',$CONFIG['user']['id_countid'], PDO::PARAM_INT);
		$query->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$query->bindValue(':id_tempid', $aGallery['tempid'], PDO::PARAM_INT);
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		$num = $query->rowCount();
		
		foreach($rows as $row){
			$filenameBase = str_pad($aGallery['tempid'], 6 ,'0', STR_PAD_LEFT) . '-' . $CONFIG['user']['id_countid'] . '-' . $CONFIG['user']['id_langid'] . '-' . md5($aGallery['tempid'] . '_' . $row['id_bfid'] . '_template') . ''; 
			$filename = $filenameBase;
			
			if(file_exists($CONFIG['system']['directoryRoot'] . $dirTarget . $filename . '.gif')) $filename .= '.gif';
			if(file_exists($CONFIG['system']['directoryRoot'] . $dirTarget . $filename . '.jpg')) $filename .= '.jpg';
			if(file_exists($CONFIG['system']['directoryRoot'] . $dirTarget . $filename . '.png')) $filename .= '.png';
			
			$info = getimagesize($CONFIG['system']['directoryRoot'] . $dirTarget . $filename);
			
			$aImages[$i]['filesys_filename'] = $filename;
			$aImages[$i]['width'] = $info[0];
			$aImages[$i]['height'] = $info[1];
			$aImages[$i]['caid'] = $aGallery['caid'];
			$aImages[$i]['tempid'] = $aGallery['tempid'];
	
			$i++;
		}

	#############################################	
	}else{
	#############################################	
		// PDFs
		$filenameBase = str_pad($aGallery['tempid'], 6 ,'0', STR_PAD_LEFT) . '-' . $CONFIG['user']['id_countid'] . '-' . $CONFIG['user']['id_langid'] . '-' . md5($aGallery['tempid'] . '_template') . '-'; 
		
		$n = 1;
		$filename = $filenameBase . $n . '.png';
		while(file_exists($CONFIG['system']['directoryRoot'] . $dirTarget . $filename)){
			$info = getimagesize($CONFIG['system']['directoryRoot'] . $dirTarget . $filename);
			
			$aImages[$i]['filesys_filename'] = $filename;
			$aImages[$i]['width'] = $info[0];
			$aImages[$i]['height'] = $info[1];
			$aImages[$i]['caid'] = $aGallery['caid'];
			$aImages[$i]['tempid'] = $aGallery['tempid'];
			
			$n++;
			$filename = $filenameBase . $n . '.png';
			
			$i++;
		}
	}
}
	
	
	
foreach($aImages as $key => $aFiles){
	if($aFiles['caid'] == 1){
		$info = getimagesize($CONFIG['system']['directoryRoot'] . $dirTarget . $aFiles['filesys_filename']);
		$iWo = $aFiles['width'];
		$iHo = $aFiles['height'];
		$iSo = $iWo / $iHo;
		
		$aCalc = array();
		if($iSo > 1){
			// Landscape
			calcWidth2Height($info, $aGal['galWidthMax'], $aGal['galHeightMax']);
			$iW = $aCalc['W'];
			$iH = $aCalc['H'];
		}else{
			// Portrait
			calcHeight2Width($info, $aGal['galWidthMax'], $aGal['galHeightMax']);
			$iW = $aCalc['W'];
			$iH = $aCalc['H'];
		}
	
	
	
		$pathOrg = $CONFIG['system']['directoryRoot'] . $dirTarget . $aFiles['filesys_filename'];
		$newW = round($iW);
		$newH = round($iH);
	//		$factor = $newW / $newH;
	//		$step = 20;
	//		$quality = 100;
	//		if($newW > 99){
	//			$step = 50;
	//			//$quality = 90;
	//		}
	//		if($newW > 499){
	//			$step = 100;
	//			//$quality = 80;
	//		}
	//		if($newW > 999){
	//			$step = 200;
	//			//$quality = 70;
	//		}
	//		
	//		$newWi = round((ceil($newW / $step)) * $step);
	//		$newHi = round($newWi / $factor);
		
		if($newW > $aGal['galWidth']) $aGal['galWidth'] = $newW;
		if($newH > $aGal['galHeight']) $aGal['galHeight'] = $newH;
		
		$pathNew = $dirTarget . $aFiles['filesys_filename'];
	//		$pathNew = $dirTargetGallery . str_pad($aFiles['tempid'], 6 ,'0', STR_PAD_LEFT) . '-' . $CONFIG['user']['id_countid'] . '-' . $CONFIG['user']['id_langid'] . '-' . md5($aFiles['tempid'] . '_template') . '_' . $quality . '_' . $newWi . '_' . $newHi . '-' . $key . '.jpg';
	//		if(!file_exists($CONFIG['system']['directoryRoot'] . $pathNew)){
	//			system('convert ' . $pathOrg . ' -quality ' . $quality . '% -resize ' . $newWi . 'x' . $newHi . '\> ' . $CONFIG['system']['directoryRoot'] . $pathNew);
	//		}
	
		$listImages .= '<div class="slideSingle"><img src="' . $CONFIG['system']['directoryInstallation'] . $pathNew . '?t=' . time() . '" width="'.$newW.'" " height="'.$newH.'" style="margin-top:-' . (intval($newH) / 2).'px; margin-left:-' . (intval($newW) / 2).'px;" /></div>';
	}else{
		$info = getimagesize($CONFIG['system']['directoryRoot'] . $dirTarget . $aFiles['filesys_filename']);
		$iWo = $aFiles['width'];
		$iHo = $aFiles['height'];
		$iSo = $iWo / $iHo;
		
		$aCalc = array();
		if($iSo > 1){
			// Landscape
			calcWidth2Height($info, $aGal['galWidthMax'], $aGal['galHeightMax']);
			$iW = $aCalc['W'];
			$iH = $aCalc['H'];
		}else{
			// Portrait
			calcHeight2Width($info, $aGal['galWidthMax'], $aGal['galHeightMax']);
			$iW = $aCalc['W'];
			$iH = $aCalc['H'];
		}
	
	
	
		$pathOrg = $CONFIG['system']['directoryRoot'] . $dirTarget . $aFiles['filesys_filename'];
		$newW = round($iW);
		$newH = round($iH);
		$factor = $newW / $newH;
		$step = 20;
		$quality = 100;
		if($newW > 99){
			$step = 50;
			//$quality = 90;
		}
		if($newW > 499){
			$step = 100;
			//$quality = 80;
		}
		if($newW > 999){
			$step = 200;
			//$quality = 70;
		}
		
		$newWi = round((ceil($newW / $step)) * $step);
		$newHi = round($newWi / $factor);
		
		if($newW > $aGal['galWidth']) $aGal['galWidth'] = $newW;
		if($newH > $aGal['galHeight']) $aGal['galHeight'] = $newH;
		
		$pathNew = $dirTargetGallery . str_pad($aFiles['tempid'], 6 ,'0', STR_PAD_LEFT) . '-' . $CONFIG['user']['id_countid'] . '-' . $CONFIG['user']['id_langid'] . '-' . md5($aFiles['tempid'] . '_template') . '_' . $quality . '_' . $newWi . '_' . $newHi . '-' . $key . '.jpg';
		if(!file_exists($CONFIG['system']['directoryRoot'] . $pathNew)){
			system('convert ' . $pathOrg . ' -quality ' . $quality . '% -resize ' . $newWi . 'x' . $newHi . '\> ' . $CONFIG['system']['directoryRoot'] . $pathNew);
		}
	
		$listImages .= '<div class="slideSingle"><img src="' . $CONFIG['system']['directoryInstallation'] . $pathNew . '?t=' . time() . '" width="'.$newW.'" " height="'.$newH.'" style="margin-top:-' . (intval($newH) / 2).'px; margin-left:-' . (intval($newW) / 2).'px;" /></div>';
	}
}
	
	
	
	
	
	
	
	
	
	
$aGal['dialogMarginTop'] =  (($aGallery['height'] - $aGal['galHeight']) / 2) - $aGal['paddingV'];
$aGal['dialogMarginLeft'] =  (($aGallery['width'] - $aGal['galWidth']) / 2) - $aGal['paddingH'];



if(count($aImages) > 0){
	$aGal['content'] = '<div id="galleryOuter" class="galleryOuter" style="width:'.$aGal['galWidth'].'px;height:'.$aGal['galHeight'].'px;padding:'.$aGal['paddingV'].'px '.$aGal['paddingH'].'px">
				<div u="slides" class="galleryInner" style="width:'.($aGal['galWidth'] + ($aGal['paddingH'] * 2)).'px;height:'.($aGal['galHeight'] + ($aGal['paddingV'] * 2)).'px;">';

	$aGal['content'] .= ''.$listImages.'';
				

	$aGal['content'] .= '<div u="any" class="galleryClose" onclick="removeGallery()"></div>
				</div>
				<span u="arrowleft" class="jssora22l '.$classNavigator.'" style="top: '. ((($aGal['galHeight'] + ($aGal['paddingV'] * 2)) / 2) - 29) .'px; left: 8px;"></span>
				<span u="arrowright" class="jssora22r '.$classNavigator.'" style="top: '. ((($aGal['galHeight'] + ($aGal['paddingV'] * 2)) / 2) - 29) .'px; right: 8px;"></span>

				<div u="navigator" class="jssorb14" style="bottom: 16px; right: 6px;">
					<div u="prototype"></div>
				</div>

				</div>';
}
	



echo json_encode($aGal);




function calcWidth2Height($info, $maxWidth, $maxHeight){
	global $aCalc;
	
	$iSo = $info[0] / $info[1];
	$calc = array();

	$calc['W'] = $maxWidth;
	if($calc['W'] > $info[0]) $calc['W'] = $info[0];
	$calc['H'] = $calc['W'] / $iSo;
	
	if($calc['H'] > $maxHeight){
		calcHeight2Width($info, $calc['W'], $maxHeight);
	}else{
		$aCalc = $calc;
	}
}

function calcHeight2Width($info, $maxWidth, $maxHeight){
	global $aCalc;

	$iSo = $info[0] / $info[1];
	$calc = array();

	$calc['H'] = $maxHeight;
	if($calc['H'] > $info[1]) $calc['H'] = $info[1];
	$calc['W'] = $calc['H'] * $iSo;
	
	if($calc['W'] > $maxWidth){
		calcWidth2Height($info, $maxWidth, $calc['H']);
	}else{	
		$aCalc = $calc;
	}
}




?>