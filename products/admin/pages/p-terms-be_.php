<?php
$out = array();
$out['content'] = '';

####################################
// layer for grid
####################################
$listCountries = '';
foreach($CONFIG['USER']['syscountries'] as $id => $aVal){
	$text = (isset($TEXT[$aVal['country']])) ? $TEXT[$aVal['country']] : $aVal['country'];
	$sel = '';
	if($aVal['id_sys_count'] == $CONFIG['USER']['activeSysCountry']) $sel = 'selected';
	$listCountries .= '<option value="' . $aVal['id_sys_count'] . '" ' . $sel . '>' . $text . '</option>';
}

#######
$listLanguages = '';
foreach($CONFIG['USER']['syscountries'][$CONFIG['USER']['activeSysCountry']]['languages'] as $id){
	$text = (isset($TEXT[$CONFIG['USER']['syslanguages'][$id]['language']])) ? $TEXT[$CONFIG['USER']['syslanguages'][$id]['language']] : $CONFIG['USER']['syslanguages'][$id]['language'];
	$sel = '';
	if($id == $CONFIG['USER']['activeSysLanguage']) $sel = 'selected';
	$listLanguages .= '<option value="' . $id . '" ' . $sel . '>' . $text . '</option>';
}

#######
$listDevices = '';
foreach($CONFIG['USER']['sysdevices'] as $id => $aVal){
	$text = (isset($TEXT[$aVal['device']])) ? $TEXT[$aVal['device']] : $aVal['device'];
	$sel = '';
	if($aVal['id_sys_dev'] == $CONFIG['USER']['activeSysDevice']) $sel = 'selected';
	$listDevices .= '<option value="' . $aVal['id_sys_dev'] . '" ' . $sel . '>' . $text . '</option>';
}



$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.id_grid_d,
										' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.grid_name,
										' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.grid_width,
										' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.grid_height,
										' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.class
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.id_page = (:id_page)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_grids_default.rank
									');
$query->bindValue(':id_page', $CONFIG['page']['pageId'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

foreach($rows as $row){
	$modul = modulname($CONFIG['system']['directorySystem'] . $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathJsAdmin'] . 'js-' . $row['grid_name'] . 'js');
	$CONFIG['page']['moduls'][$modul]= array();
	$CONFIG['page']['moduls'][$modul]['modul']= $modul;
	$CONFIG['page']['moduls'][$modul]['modulname']= $row['grid_name'];
	$CONFIG['page']['moduls'][$modul]['width']= $row['grid_width'];
	$CONFIG['page']['moduls'][$modul]['height']= $row['grid_height'];
	$CONFIG['page']['moduls'][$modul]['class']= $row['class'];

	
	$out['content'] .= '<div id="gridOuter_' . $row['grid_name'] . '" class="modul ' . $row['class'] . '" data-modul="' . $modul . '">';
	$out['content'] .= '<div id="" class="tabGridFilter gridFilter" data-cb="gridReload">';
	
	if($CONFIG['system']['useSysMultiple'] == 1){
		if($CONFIG['system']['useSysMultipleCountry'] == 1){
			$out['content'] .= ' <label class="gridFilterLabel" for="filterGridSysCountry">' . $TEXT['filterCountry'] . '</label> <div class="wGridFilterOuter"><select class="textfield wGridFilter filterGridSysCountry" name="filterGridCountry">' . $listCountries . '</select></div>';
		}
		if($CONFIG['system']['useSysMultipleLanguage'] == 1){
			$out['content'] .= ' <label class="gridFilterLabel" for="filterGridSysLanguage">' . $TEXT['filterLanguage'] . '</label> <div class="wGridFilterOuter"><select class="textfield wGridFilter filterGridSysLanguage" name="filterGridLanguage">' . $listLanguages . '</select></div>';
		}
		if($CONFIG['system']['useSysMultipleDevice'] == 1){
			$out['content'] .= ' <label class="gridFilterLabel" for="filterGridSysDevice">' . $TEXT['filterDevice'] . '</label> <div class="wGridFilterOuter"><select class="textfield wGridFilter filterGridDevice" name="filterGridSysDevice">' . $listDevices . '</select></div>';
		}
	}
	
	$out['content'] .= '<span class="gridButton gridExpand gridButtonAll ui-icon-arrowthick-2-n-s" onclick="expandRowAll(\'' . $modul . '\')" title=""></span>';
	$out['content'] .= '</div>';
	
	$out['content'] .= '<table id="gridTable_' . $row['grid_name'] . '" class="gridTable"></table>';
	$out['content'] .= '<div id="gridPager_' . $row['grid_name'] . '" class="gridPager"></div>';
	$out['content'] .= '</div>';
}




$out['page'] = $CONFIG['page'];
echo json_encode($out);



?>