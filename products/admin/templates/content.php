<?php
$out = array();
$out['content'] = '';

####################################
// layer for grid
####################################
$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.id_grid_d,
										' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.grid_name,
										' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.grid_width,
										' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.grid_height,
										' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.class,
										' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.specifics
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.id_page = (:id_page)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.rank
									');
$query->bindValue(':id_page', $CONFIG['page']['pageId'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$out['content'] .= '<div id="modulOuter">';
foreach($rows as $row){
	$modul = modulname($CONFIG['system']['directorySystem'] . $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathJsAdmin'] . 'js-' . $row['grid_name'] . 'js');
	$CONFIG['page']['moduls'][$modul]= array();
	$CONFIG['page']['moduls'][$modul]['modul']= $modul;
	$CONFIG['page']['moduls'][$modul]['modulname']= $row['grid_name'];
	$CONFIG['page']['moduls'][$modul]['width']= $row['grid_width'];
	$CONFIG['page']['moduls'][$modul]['height']= $row['grid_height'];
	$CONFIG['page']['moduls'][$modul]['class']= $row['class'];
	$CONFIG['page']['moduls'][$modul]['specifics']= $row['specifics'];
	

	$CONFIG['page']['moduls'][$modul]['activeCountry'] = (!isset($CONFIG['page']['moduls'][$modul]['activeCountry'])) ? $CONFIG['USER']['activeCountry'] : '';
	$CONFIG['page']['moduls'][$modul]['activeLanguage'] = (!isset($CONFIG['page']['moduls'][$modul]['activeLanguage'])) ? $CONFIG['USER']['activeLanguage'] : '';
	$CONFIG['page']['moduls'][$modul]['activeDevice'] = (!isset($CONFIG['page']['moduls'][$modul]['activeDevice'])) ? $CONFIG['USER']['activeDevice'] : '';
	$CONFIG['page']['moduls'][$modul]['activeSysCountry'] = (!isset($CONFIG['page']['moduls'][$modul]['activeSysCountry'])) ? $CONFIG['USER']['activeSysCountry'] : '';
	$CONFIG['page']['moduls'][$modul]['activeSysLanguage'] = (!isset($CONFIG['page']['moduls'][$modul]['activeSysLanguage'])) ? $CONFIG['USER']['activeSysLanguage'] : '';
	$CONFIG['page']['moduls'][$modul]['activeSysDevice'] = (!isset($CONFIG['page']['moduls'][$modul]['activeSysDevice'])) ? $CONFIG['USER']['activeSysDevice'] : '';
	
	if($CONFIG['system']['synchronizeGridFilter'] == 1){
		$CONFIG['page']['moduls'][$modul]['activeCountry'] = $CONFIG['USER']['activeCountry'];
		$CONFIG['page']['moduls'][$modul]['activeLanguage'] = $CONFIG['USER']['activeLanguage'];
		$CONFIG['page']['moduls'][$modul]['activeDevice'] = $CONFIG['USER']['activeDevice'];
		$CONFIG['page']['moduls'][$modul]['activeSysCountry'] = $CONFIG['USER']['activeSysCountry'];
		$CONFIG['page']['moduls'][$modul]['activeSysLanguage'] = $CONFIG['USER']['activeSysLanguage'];
		$CONFIG['page']['moduls'][$modul]['activeSysDevice'] = $CONFIG['USER']['activeSysDevice'];
	}
	
	if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 3, 1) == 0){
		$CONFIG['page']['moduls'][$modul]['activeCountry'] = 0;
		$CONFIG['page']['moduls'][$modul]['activeSysCountry'] = 0;
	}
	if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 4, 1) == 0){
		$CONFIG['page']['moduls'][$modul]['activeLanguage'] = 0;
		$CONFIG['page']['moduls'][$modul]['activeSysLanguage'] = 0;
	}
	if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 5, 1) == 0){
		$CONFIG['page']['moduls'][$modul]['activeDevice'] = 0;
		$CONFIG['page']['moduls'][$modul]['activeSysDevice'] = 0;
	}
	
	$CONFIG['page']['moduls'][$modul]['formCountry'] = $CONFIG['page']['moduls'][$modul]['activeCountry'];
	$CONFIG['page']['moduls'][$modul]['formLanguage'] = $CONFIG['page']['moduls'][$modul]['activeLanguage'];
	$CONFIG['page']['moduls'][$modul]['formDevice'] = $CONFIG['page']['moduls'][$modul]['activeDevice'];
	
	#############################################################################
	// Grid Filter
	$listCountries = '';
	$listLanguages = '';
	$listDevices = '';
	if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 6, 1) == 9){
		// Filter for system country, etc
		if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 3,1) == 9){
			$aRes = array();
			foreach($CONFIG['USER']['syscountries'] as $id => $aVal){
				$text = (isset($TEXT[$aVal['country']])) ? $TEXT[$aVal['country']] : $aVal['country'];
				$aRes[$aVal['id_sys_count']] = $text;
			}
			asort($aRes, SORT_NATURAL | SORT_FLAG_CASE);
			foreach($aRes as $id => $text){
				$sel = '';
				if($id == $CONFIG['page']['moduls'][$modul]['activeSysCountry']) $sel = 'selected';
				$listCountries .= '<option value="' . $id . '" ' . $sel . '>' . $text . '</option>';
			}
		}
			
		#######
		if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 4,1) == 9){
			if($CONFIG['page']['moduls'][$modul]['activeSysCountry'] == 0 && $CONFIG['USER']['right_editallcountries'] == 9 && $CONFIG['system']['useSysMultiple'] == 1 && substr($CONFIG['page']['moduls'][$modul]['specifics'], 3,1) == 9){
				$text = (isset($TEXT['alllanguages'])) ? $TEXT['alllanguages'] : 'alllanguages';
				$sel = '';
				if($CONFIG['page']['moduls'][$modul]['activeSysLanguage'] == 0) $sel = 'selected';
				$listLanguages .= '<option value="0" ' . $sel . '>' . $text . '</option>';
			}else{
				$aRes = array();
				foreach($CONFIG['USER']['syscountries'][$CONFIG['page']['moduls'][$modul]['activeSysCountry']]['languages'] as $id){
					$text = (isset($TEXT[$CONFIG['USER']['syslanguages'][$id]['language']])) ? $TEXT[$CONFIG['USER']['syslanguages'][$id]['language']] : $CONFIG['USER']['syslanguages'][$id]['language'];
					$aRes[$id] = $text;
				}
				asort($aRes, SORT_NATURAL | SORT_FLAG_CASE);
				foreach($aRes as $id => $text){
					$sel = '';
					if($id == $CONFIG['page']['moduls'][$modul]['activeSysLanguage']) $sel = 'selected';
					$listLanguages .= '<option value="' . $id . '" ' . $sel . '>' . $text . '</option>';
				}
			}
		}
			
		#######
		if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 5,1) == 9){
			$aRes = array();
			foreach($CONFIG['USER']['sysdevices'] as $id => $aVal){
				$text = (isset($TEXT[$aVal['device']])) ? $TEXT[$aVal['device']] : $aVal['device'];
				$aRes[$aVal['id_sys_dev']] = $text;
			}
			asort($aRes, SORT_NATURAL | SORT_FLAG_CASE);
			foreach($aRes as $id => $text){
				$sel = '';
				if($id == $CONFIG['page']['moduls'][$modul]['activeSysDevice']) $sel = 'selected';
				$listDevices .= '<option value="' . $id . '" ' . $sel . '>' . $text . '</option>';
			}
		}
	}else{
		if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 3,1) == 9){
			$aRes = array();
			foreach($CONFIG['USER']['countries'] as $id => $aVal){
				$text = (isset($TEXT[$aVal['country']])) ? $TEXT[$aVal['country']] : $aVal['country'];
				$aRes[$aVal['id_countid']] = $text;
			}
			asort($aRes, SORT_NATURAL | SORT_FLAG_CASE);
			foreach($aRes as $id => $text){
				$sel = '';
				if($id == $CONFIG['page']['moduls'][$modul]['activeCountry']) $sel = 'selected';
				$listCountries .= '<option value="' . $id . '" ' . $sel . '>' . $text . '</option>';
			}
		}
			
		#######
		if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 4,1) == 9){
			if($CONFIG['page']['moduls'][$modul]['activeCountry'] == 0 && $CONFIG['USER']['right_editallcountries'] == 9 && $CONFIG['system']['useMultiple'] == 1 && substr($CONFIG['page']['moduls'][$modul]['specifics'], 3,1) == 9){
				$text = (isset($TEXT['alllanguages'])) ? $TEXT['alllanguages'] : 'alllanguages';
				$sel = '';
				if($CONFIG['page']['moduls'][$modul]['activeLanguage'] == 0) $sel = 'selected';
				$listLanguages .= '<option value="0" ' . $sel . '>' . $text . '</option>';
			}else{
				$aRes = array();
				foreach($CONFIG['USER']['countries'][$CONFIG['page']['moduls'][$modul]['activeCountry']]['languages'] as $id){
					$text = (isset($TEXT[$CONFIG['USER']['languages'][$id]['language']])) ? $TEXT[$CONFIG['USER']['languages'][$id]['language']] : $CONFIG['USER']['languages'][$id]['language'];
					$aRes[$id] = $text;
				}
				asort($aRes, SORT_NATURAL | SORT_FLAG_CASE);
				foreach($aRes as $id => $text){
					$sel = '';
					if($id == $CONFIG['page']['moduls'][$modul]['activeLanguage']) $sel = 'selected';
					$listLanguages .= '<option value="' . $id . '" ' . $sel . '>' . $text . '</option>';
				}
			}
		}
			
		#######
		if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 5,1) == 9){
			$aRes = array();
			foreach($CONFIG['USER']['devices'] as $id => $aVal){
				$text = (isset($TEXT[$aVal['device']])) ? $TEXT[$aVal['device']] : $aVal['device'];
				$aRes[$aVal['id_devid']] = $text;
			}
			asort($aRes, SORT_NATURAL | SORT_FLAG_CASE);
			foreach($aRes as $id => $text){
				$sel = '';
				if($id == $CONFIG['page']['moduls'][$modul]['activeDevice']) $sel = 'selected';
				$listDevices .= '<option value="' . $id . '" ' . $sel . '>' . $text . '</option>';
			}
		}
	}



	#############################################################################
	// Modul
	$out['content'] .= '<div id="modul_' . $row['grid_name'] . '" class="modul ' . $row['class'] . '" data-modul="' . $modul . '">';
	#############################################################################
	
	#############################################################################
	// Grid
	$out['content'] .= '<div id="grid_' . $row['grid_name'] . '" class="grid">';
	$out['content'] .= '<div id="" class="tabGridFilter gridFilter" data-cb="gridReload">';
	
	if($CONFIG['system']['useMultiple'] == 1){
		if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 3,1) == 9){
			$out['content'] .= ' <label class="formFilterLabel" for="filterGridCountry">' . $TEXT['filterCountry'] . '</label> <div class="wGridFilterOuter"><select class="textfield wGridFilter filterGridCountry" name="filterGridCountry">' . $listCountries . '</select></div>';
		}
		if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 4,1) == 9){
			$out['content'] .= ' <label class="formFilterLabel" for="filterGridLanguage">' . $TEXT['filterLanguage'] . '</label> <div class="wGridFilterOuter"><select class="textfield wGridFilter filterGridLanguage" name="filterGridLanguage">' . $listLanguages . '</select></div>';
		}
		if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 5,1) == 9){
			$out['content'] .= ' <label class="formFilterLabel" for="filterGridDevice">' . $TEXT['filterDevice'] . '</label> <div class="wGridFilterOuter"><select class="textfield wGridFilter filterGridDevice" name="filterGridDevice">' . $listDevices . '</select></div>';
		}
	}
	
	$out['content'] .= '<span class="gridButton gridExpand gridButtonAll ui-icon-arrowthick-2-n-s" onclick="expandRowAll(\'' . $modul . '\')" title=""></span>';
	$out['content'] .= '</div>';
	
	$out['content'] .= '<table id="gridTable_' . $row['grid_name'] . '" class="gridTable"></table>';
	$out['content'] .= '<div id="gridPager_' . $row['grid_name'] . '" class="gridPager"></div>';
	$out['content'] .= '</div>';
	#############################################################################
	
	
	
	#############################################################################
	// Form
	$out['content'] .= '<div id="form_' . $row['grid_name'] . '" class="form hidden">';
	$out['content'] .= '<div id="" class="tabFormFilter formFilter" data-cb="">';
	
	if($CONFIG['system']['useMultiple'] == 1){
		if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 3,1) == 9){
			$out['content'] .= ' <label class="formFilterLabel" for="filterFormCountry">' . $TEXT['filterCountry'] . '</label> <div class="wFormFilterOuter"><select class="textfield wFormFilter filterFormCountry" name="filterFormCountry">' . $listCountries . '</select></div>';
		}
		if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 4,1) == 9){
			$out['content'] .= ' <label class="formFilterLabel" for="filterFormLanguage">' . $TEXT['filterLanguage'] . '</label> <div class="wFormFilterOuter"><select class="textfield wFormFilter filterFormLanguage" name="filterFormLanguage">' . $listLanguages . '</select></div>';
		}
		if(substr($CONFIG['page']['moduls'][$modul]['specifics'], 5,1) == 9){
			$out['content'] .= ' <label class="formFilterLabel" for="filterFormDevice">' . $TEXT['filterDevice'] . '</label> <div class="wFormFilterOuter"><select class="textfield wFormFilter filterFormDevice" name="filterFormDevice">' . $listDevices . '</select></div>';
		}
	}

	$out['content'] .= '<span class="gridButton gridExpand formNavButton formNavButtonPrev ui-icon-triangle-1-w" title="Vorhergehender Datensatz"></span>';
	$out['content'] .= '<span class="gridButton gridExpand formNavButton formNavButtonNext ui-icon-triangle-1-e" title="Nächster Datensatz"></span>';
	$out['content'] .= '<span class="gridButton gridExpand formNavButton formNavButtonMax ui-icon-newwin" title="Formular maximieren"></span>';
	$out['content'] .= '</div>';

	$out['content'] .= '<div class="formContent"></div>';
	$out['content'] .= '</div>';
	#############################################################################
	
	
	#############################################################################
	$out['content'] .= '</div>';
	#############################################################################
}
$out['content'] .= '</div>';

$out['page'] = $CONFIG['page'];
echo json_encode($out);

?>