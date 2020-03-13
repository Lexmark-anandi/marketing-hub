<?php
// main content for page (including parent moduls)

$out = array();
$out['content'] = '<div id="modulOuter">';
foreach($CONFIG['user']['pages2moduls'][$CONFIG['activeSettings']['id_page']]['moduls'] as $aModuls){ 
	$modulpath = $CONFIG['activeSettings']['id_page'] .$CONFIG['system']['delimiterPathAttr'] . '0' . $CONFIG['system']['delimiterPathAttr'] . $aModuls['id_mod'];
	
	#############################################################################
	// translate, sort and create modul filter
	$listCountries = '';
	$listLanguages = '';
	$listDevices = '';
	if($CONFIG['system']['useMultiple'] == 1){
		$filtertype = ($aModuls['specifications'][9] == 9) ? 'sys' : '';
		$fieldIdCount = ($aModuls['specifications'][9] == 9) ? 'id_sys_count' : 'id_countid';
		$fieldIdLang = ($aModuls['specifications'][9] == 9) ? 'id_sys_lang' : 'id_langid';
		$fieldIdDev = ($aModuls['specifications'][9] == 9) ? 'id_sys_dev' : 'id_devid';

		#######
		if($aModuls['specifications'][6] == 9){
			$aRes = array();
			foreach($CONFIG['user'][$filtertype . 'countries'] as $id => $aVal){
				$text = (isset($TEXT[$aVal['country']])) ? $TEXT[$aVal['country']] : $aVal['country'];
				$aRes[$aVal[$fieldIdCount]] = $text;
			}
			asort($aRes, SORT_NATURAL | SORT_FLAG_CASE);
			
			if(array_key_exists(0, $CONFIG['user'][$filtertype . 'countries'])){
				$text = (isset($TEXT[$CONFIG['user'][$filtertype . 'countries'][0]['country']])) ? $TEXT[$CONFIG['user'][$filtertype . 'countries'][0]['country']] : $CONFIG['user'][$filtertype . 'countries'][0]['country'];
				$sel = '';
				if($aModuls['activeSettings']['selectCountry'] == 0) $sel = 'selected';
				$listCountries .= '<option value="0" ' . $sel . '>' . $text . '</option>';
			}
			
			foreach($aRes as $id => $text){
				$sel = '';
				if($id > 0){
					if($id == $aModuls['activeSettings']['selectCountry']) $sel = 'selected';
					$listCountries .= '<option value="' . $id . '" ' . $sel . '>' . $text . '</option>';
				}
			}
		}
			
		#######
		if($aModuls['specifications'][7] == 9){
			$aRes = array();
			foreach($CONFIG['user'][$filtertype . 'countries'][$aModuls['activeSettings']['selectCountry']]['languages'] as $id){
				$text = (isset($TEXT[$CONFIG['user'][$filtertype . 'languages'][$id]['language']])) ? $TEXT[$CONFIG['user'][$filtertype . 'languages'][$id]['language']] : $CONFIG['user'][$filtertype . 'languages'][$id]['language'];
				$aRes[$id] = $text;
			}
			asort($aRes, SORT_NATURAL | SORT_FLAG_CASE);
			
			if(in_array(0, $CONFIG['user'][$filtertype . 'countries'][$aModuls['activeSettings']['selectCountry']]['languages'])){
				$text = (isset($TEXT[$CONFIG['user'][$filtertype . 'languages'][0]['language']])) ? $TEXT[$CONFIG['user'][$filtertype . 'languages'][0]['language']] : $CONFIG['user'][$filtertype . 'languages'][0]['language'];
				$sel = '';
				if($aModuls['activeSettings']['selectLanguage'] == 0) $sel = 'selected';
				$listLanguages .= '<option value="0" ' . $sel . '>' . $text . '</option>';
			}
			
			foreach($aRes as $id => $text){
				$sel = '';
				if($id > 0){
					if($id == $aModuls['activeSettings']['selectLanguage']) $sel = 'selected';
					$listLanguages .= '<option value="' . $id . '" ' . $sel . '>' . $text . '</option>';
				}
			}
		}
		
		#######
		if($aModuls['specifications'][8] == 9){
			$aRes = array();
			foreach($CONFIG['user'][$filtertype . 'devices'] as $id => $aVal){
				$text = (isset($TEXT[$aVal['device']])) ? $TEXT[$aVal['device']] : $aVal['device'];
				$aRes[$aVal[$fieldIdDev]] = $text;
			}
			asort($aRes, SORT_NATURAL | SORT_FLAG_CASE);
			
			if(array_key_exists(0, $CONFIG['user'][$filtertype . 'devices'])){
				$text = (isset($TEXT[$CONFIG['user'][$filtertype . 'devices'][0]['device']])) ? $TEXT[$CONFIG['user'][$filtertype . 'devices'][0]['device']] : $CONFIG['user'][$filtertype . 'devices'][0]['device'];
				$sel = '';
				if($aModuls['activeSettings']['selectDevice'] == 0) $sel = 'selected';
				$listDevices .= '<option value="0" ' . $sel . '>' . $text . '</option>';
			}
			
			foreach($aRes as $id => $text){
				$sel = '';
				if($id > 0){
					if($id == $aModuls['activeSettings']['selectDevice']) $sel = 'selected';
					$listDevices .= '<option value="' . $id . '" ' . $sel . '>' . $text . '</option>';
				}
			}
		}
	}


	#############################################################################
	// Modul
	$out['content'] .= '<div id="modul_' . $modulpath . '" class="modul ' . $aModuls['modul_class'] . '" data-modulpath="' . $modulpath . '">';
	#############################################################################
	
	#############################################################################
	// Grid
	if($aModuls['specifications'][11] == 9){
		$out['content'] .= '<div id="grid_' . $modulpath . '" class="grid">';
		$out['content'] .= '<div id="" class="tabModulFilter modulFilter">';
		$out['content'] .= '<div id="" class="tabModulFilterInner">';
		
		if($CONFIG['system']['useMultiple'] == 1){
			if($aModuls['specifications'][6] == 9){
				$out['content'] .= ' <label class="formFilterLabel" for="filterModulCountry_' . $modulpath . '">' . $TEXT['filterCountry'] . '</label> <div class="wModulFilterOuter"><select class="textfield wModulFilter filterModulCountry" name="filterModulCountry" id="filterModulCountry_' . $modulpath . '">' . $listCountries . '</select></div>';
			}
			if($aModuls['specifications'][7] == 9){
				$out['content'] .= ' <label class="formFilterLabel" for="filterModulLanguage_' . $modulpath . '">' . $TEXT['filterLanguage'] . '</label> <div class="wModulFilterOuter"><select class="textfield wModulFilter filterModulLanguage" name="filterModulLanguage" id="filterModulLanguage_' . $modulpath . '">' . $listLanguages . '</select></div>';
			}
			if($aModuls['specifications'][8] == 9){
				$out['content'] .= ' <label class="formFilterLabel" for="filterModulDevice_' . $modulpath . '">' . $TEXT['filterDevice'] . '</label> <div class="wModulFilterOuter"><select class="textfield wModulFilter filterModulDevice" name="filterModulDevice" id="filterModulDevice_' . $modulpath . '">' . $listDevices . '</select></div>';
			}
		}
		$out['content'] .= '</div>';

		$out['content'] .= '<div id="" class="tabModulFilterInnerMobile">';
		$out['content'] .= '<div class="wModulFilterOuterMobile">DE / de</div>';
		$out['content'] .= '<div class="modulIcon modulIconBox gridMenueFilter" title=""><i class="fa fa-pencil"></i></div>';
		$out['content'] .= '</div>';
		
		$out['content'] .= '<div class="tabModulFilterButtonsRight">';
		$out['content'] .= '<div class="modulIcon modulIconBox gridMenueFunctions" title=""><i class="fa fa-navicon"></i></div>';
		$out['content'] .= '<div class="modulIcon modulIconBox gridExpandAll" title=""><i class="fa fa-chevron-down"></i></div>';
		$out['content'] .= '</div>';
		$out['content'] .= '</div>';
		
		$out['content'] .= '<table id="gridTable_' . $modulpath . '" class="gridTable"></table>';
		
		################
		// pager
		$listRows = '';
		foreach($CONFIG['system']['aGridNumRows'] as $row){
			$listRows .= '<option value="' . $row . '">' . $row . '</option>';
		}
		$out['content'] .= '<div id="gridPager_' . $modulpath . '" class="gridPager">';
		
		$out['content'] .= '<div id="gridPager_' . $modulpath . '_left" class="gridPagerInner gridPagerLeft">';
		$out['content'] .= '<div class="modulIcon pagerRefresh" title=""><i class="fa fa-refresh"></i></div>';
		$out['content'] .= '<div class="modulIcon pagerSettings" title=""><i class="fa fa-sliders"></i></div>';
		$out['content'] .= '</div>';
		
		$out['content'] .= '<div id="gridPager_' . $modulpath . '_right" class="gridPagerInner gridPagerRight"><span class="pagerRecords"></span></div>';
		
		$out['content'] .= '<div id="gridPager_' . $modulpath . '_center" class="gridPagerInner gridPagerCenter">';
		$out['content'] .= '<div class="modulIcon pagerFirstPage" title=""><i class="fa fa-fast-backward"></i></div>';
		$out['content'] .= '<div class="modulIcon pagerPrevPage" title=""><i class="fa fa-flip-horizontal fa-play"></i></div>';
		$out['content'] .= '<div class="modulIcon pagerPage" title=""><input type="text" name="pagerActPage" id="pagerActPage_' . $modulpath . '" value="" class="pagerActPage"> / <span class="pagerTotalPages"></span></div>';
		$out['content'] .= '<div class="modulIcon pagerNextPage" title=""><i class="fa fa-play"></i></div>';
		$out['content'] .= '<div class="modulIcon pagerLastPage" title=""><i class="fa fa-fast-forward"></i></div>';
		$out['content'] .= '<div class="modulIcon pagerRows" title=""><select name="pagerRows" id="pagerRows_' . $modulpath . '" class="pagerSelectRows">' . $listRows . '</select></div>';
		$out['content'] .= '</div>';
		
		$out['content'] .= '</div>';
		
		$out['content'] .= '</div>';
		################
	}
	#############################################################################
	

	
	#############################################################################
	// Form
	$out['content'] .= '<div id="form_' . $modulpath . '" class="form hidden">';
	$out['content'] .= '<div id="" class="tabFormFilter formFilter">';
	
	if($CONFIG['system']['useMultiple'] == 1){
		if($aModuls['specifications'][6] == 9){
			$out['content'] .= ' <label class="formFilterLabel" for="filterFormCountry_' . $modulpath . '">' . $TEXT['filterCountry'] . '</label> <div class="wFormFilterOuter"><select class="textfield wFormFilter filterFormCountry" name="filterFormCountry" id="filterFormCountry_' . $modulpath . '">' . $listCountries . '</select></div>';
		}
		if($aModuls['specifications'][7] == 9){
			$out['content'] .= ' <label class="formFilterLabel" for="filterFormLanguage_' . $modulpath . '">' . $TEXT['filterLanguage'] . '</label> <div class="wFormFilterOuter"><select class="textfield wFormFilter filterFormLanguage" name="filterFormLanguage" id="filterFormLanguage_' . $modulpath . '">' . $listLanguages . '</select></div>';
		}
		if($aModuls['specifications'][8] == 9){
			$out['content'] .= ' <label class="formFilterLabel" for="filterFormDevice_' . $modulpath . '">' . $TEXT['filterDevice'] . '</label> <div class="wFormFilterOuter"><select class="textfield wFormFilter filterFormDevice" name="filterFormDevice" id="filterFormDevice_' . $modulpath . '">' . $listDevices . '</select></div>';
		}
	}

	$out['content'] .= '<div class="modulIcon modulIconBox formNavButton formNavButtonPrev" title="' . $TEXT['prevRow'] . '"><i class="fa fa-play fa-flip-horizontal"></i></div>';
	$out['content'] .= '<div class="modulIcon modulIconBox formNavButton formNavButtonNext" title="' . $TEXT['nextRow'] . '"><i class="fa fa-play"></i></div>';
	$out['content'] .= '<div class="modulIcon modulIconBox formNavButton formNavButtonMax" title="' . $TEXT['maximizeForm'] . '"><i class="fa fa-window-maximize"></i></div>';
	$out['content'] .= '</div>';

	$out['content'] .= '<div class="formContent"></div>';
	$out['content'] .= '</div>';
	#############################################################################
	
	
	#############################################################################
	$out['content'] .= '</div>';
	#############################################################################
}

$out['content'] .= '</div>';

echo json_encode($out);
?>