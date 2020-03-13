<?php  
$FORM_TOP_LEFT = ''; 
$FORM_BOTTOM_LEFT = '';
$FORM_TOP_RIGHT = '';
$FORM_BOTTOM_RIGHT = '';
$f_fieldshidden = '';

###########################################################

//$listCountries = '<div class="countryTable">
//		<div class="countryTableRow">
//			<div class="countryTableCell countryTableCellBox"><input type="checkbox" name="selectall" id="selectall_' . $modulpath . '" class="checkfieldselectall"  data-checkfunction="" data-checkmessage="" data-checksync=""></div>
//			<div class="countryTableCell countryTableCellCountry formgeogroup"><label for="selectall_' . $modulpath . '">' . $TEXT['all_none'] . '</label></div>
//		</div>';
//$query = $CONFIG['dbconn'][0]->prepare('
//									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid as id,
//										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country AS term,
//										' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.id_geoid,
//										' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.geography
//									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni 
//									
//									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni
//										ON ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_count = (:nul)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_lang = (:nul)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_dev = (:nul)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.del = (:nultime)
//									
//									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_geographies_uni
//										ON ' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.id_count = (:nul)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.id_lang = (:nul)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.id_dev = (:nul)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.id_geoid = ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_geoid
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.del = (:nultime)
//									
//									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid IN (' . implode(',', array_keys($CONFIG['user']['countries'])) . ')
//									ORDER BY IF(' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.geography = "" or ' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.geography IS NULL, 1, 0), ' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.geography, ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country
//									');
//$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//$query->bindValue(':nul', 0, PDO::PARAM_INT);
//$query->execute();
//$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//$num = $query->rowCount();
//$geoOld = '';
//foreach($rows as $row){
//	if($row['geography'] == '') $row['geography'] = $TEXT['other'];
//	if($row['geography'] != $geoOld){
//		$listCountries .= '<div class="countryTableRow">';
//		$listCountries .= '<div class="countryTableCell countryTableCellBox"><input type="checkbox" name="selectgeo" id="selectgeo_' . $modulpath . '_'.$row['id_geoid'].'" class="checkfieldselectgeo" value="' . $row['id_geoid'] . '" data-checkfunction="" data-checkmessage="" data-checksync=""></div>';
//		$listCountries .= '<div class="countryTableCell countryTableCellCountry formgeogroup"><label for="selectgeo_' . $modulpath . '_'.$row['id_geoid'].'">' . $row['geography'] . '</label></div>';
//		$listCountries .= '</div>';
//	}
//	
//	$listCountries .= '<div class="countryTableRow" data-geo="' . $row['id_geoid'] . '">';
//	$listCountries .= '<div class="countryTableCell countryTableCellBox"><input type="checkbox" name="country[]" id="country_' . $modulpath . '_'.$row['id'].'" class="checkfield " value="' . $row['id'] . '" data-checkfunction="checkArrayRequired" data-checkmessage="" data-checksync="all"></div>';
//	$listCountries .= '<div class="countryTableCell countryTableCellCountry"><label for="country_' . $modulpath . '_'.$row['id'].'">' . $row['term'] . '</label></div>';
//	$listCountries .= '</div>';
//	
//	$countryOld = $row['id'];
//	$geoOld = $row['geography'];
//}
//$listCountries .= '</div>';


$listCountries = '<div class="countryTable">
		<div class="countryTableRow">
			<div class="countryTableCell countryTableCellBox"><input type="checkbox" name="selectall" id="selectall_' . $modulpath . '" class="checkfieldselectall"  data-checkfunction="" data-checkmessage="" data-checksync=""></div>
			<div class="countryTableCell countryTableCellCountry formgeogroup"><label for="selectall_' . $modulpath . '">' . $TEXT['all_none'] . '</label></div>
		</div>';
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid as id,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country AS term
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid IN (' . implode(',', array_keys($CONFIG['user']['countries'])) . ')
									GROUP BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
$geoOld = '';
foreach($rows as $row){
	$listCountries .= '<div class="countryTableRow"">';
	$listCountries .= '<div class="countryTableCell countryTableCellBox"><input type="checkbox" name="country[]" id="country_' . $modulpath . '_'.$row['id'].'" class="checkfield " value="' . $row['id'] . '" data-checkfunction="checkArrayRequired" data-checkmessage="" data-checksync="all"></div>';
	$listCountries .= '<div class="countryTableCell countryTableCellCountry"><label for="country_' . $modulpath . '_'.$row['id'].'">' . $row['term'] . '</label></div>';
	$listCountries .= '</div>';
	
	$countryOld = $row['id'];
}
$listCountries .= '</div>';

###########################################################

//$listCountriesKiado = '<div class="countryTable">
//		<div class="countryTableRow">
//			<div class="countryTableCell countryTableCellBox"><input type="checkbox" name="selectall" id="selectall_' . $modulpath . '" class="checkfieldselectall"  data-checkfunction="" data-checkmessage="" data-checksync=""></div>
//			<div class="countryTableCell countryTableCellCountry formgeogroup"><label for="selectall_' . $modulpath . '">' . $TEXT['all_none'] . '</label></div>
//			<div class="countryTableCell countryTableCellLanguage"></div>
//			<div class="countryTableCell countryTableCellPreview"></div>
//			<div class="countryTableCell countryTableCellMaster countryTableMaster"></div>
//			<div class="countryTableCell countryTableCellPages">' . $TEXT['pages'] . '</div>
//		</div>
//';
//$query = $CONFIG['dbconn'][0]->prepare('
//									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang,
//										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid as id,
//										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country,
//										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code_spec AS code_count,
//										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language,
//										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code_spec AS code_lang,
//										' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.id_geoid,
//										' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.geography
//									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni 
//									
//									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
//										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
//									
//									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
//										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid
//									
//									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni
//										ON ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_count = (:nul)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_lang = (:nul)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_dev = (:nul)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.del = (:nultime)
//									
//									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_geographies_uni
//										ON ' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.id_count = (:nul)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.id_lang = (:nul)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.id_dev = (:nul)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.id_geoid = ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_geoid
//											AND ' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.del = (:nultime)
//									
//									
//									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang IN ('. implode(',', $CONFIG['user']['count2lang']) . ')
//
//									ORDER BY IF(' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.geography = "" or ' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.geography IS NULL, 1, 0), ' . $CONFIG['db'][0]['prefix'] . '_geographies_uni.geography, ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country, ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
//									');
//$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//$query->bindValue(':nul', 0, PDO::PARAM_INT);
//$query->execute();
//$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//$num = $query->rowCount();
//$countryOld = 0;
//$numAll = 0;
//foreach($rows as $row){
//	if($row['geography'] == '') $row['geography'] = $TEXT['other'];
//	if($row['geography'] != $geoOld){
//		$listCountriesKiado .= '<div class="countryTableRow countryTableRowBorder">';
//		$listCountriesKiado .= '<div class="countryTableCell countryTableCellBox"><input type="checkbox" name="selectgeo" id="selectgeo_' . $modulpath . '_'.$row['id_geoid'].'" class="checkfieldselectgeo" value="' . $row['id_geoid'] . '" data-checkfunction="" data-checkmessage="" data-checksync=""></div>';
//		$listCountriesKiado .= '<div class="countryTableCell countryTableCellCountry formgeogroup"><label for="selectgeo_' . $modulpath . '_'.$row['id_geoid'].'">' . $row['geography'] . '</label></div>';
//		$listCountriesKiado .= '<div class="countryTableCell countryTableCellLanguage">&nbsp;</div>';
//		$listCountriesKiado .= '<div class="countryTableCell countryTableCellPreview">&nbsp;</div>';
//		$listCountriesKiado .= '<div class="countryTableCell countryTableCellMaster countryTableMaster">&nbsp;</div>';
//		$listCountriesKiado .= '<div class="countryTableCell countryTableCellPages">&nbsp;</div>';
//		$listCountriesKiado .= '</div>';
//	}
//
//	if($countryOld != $row['id']){
//		$checkbox = '<input type="checkbox" name="country[]" id="countryKiado_' . $modulpath . '_'.$row['id'].'" class="checkfield " value="' . $row['id'] . '" data-checkfunction="checkArrayRequired" data-checkmessage="" data-checksync="all">';
//		$country = $row['country'];
//		$classRow = 'countryTableRowBorder';
//	}else{
//		$checkbox = '';
//		$country = '';
//		$classRow = '';
//	}
//
//	$listCountriesKiado .= '<div class="countryTableRow ' . $classRow . '" data-count2lang="' . $row['id_count2lang'] . '" data-geo="' . $row['id_geoid'] . '">';
//	$listCountriesKiado .= '<div class="countryTableCell countryTableCellBox">' . $checkbox . '</div>';
//	$listCountriesKiado .= '<div class="countryTableCell countryTableCellCountry"><label for="countryKiado_' . $modulpath . '_'.$row['id'].'">' . $country . '</label></div>';
//	$listCountriesKiado .= '<div class="countryTableCell countryTableCellLanguage">' . $row['language'] . '</div>';
//	$listCountriesKiado .= '<div class="countryTableCell countryTableCellPreview"></div>';
//	$listCountriesKiado .= '<div class="countryTableCell countryTableCellMaster countryTableMaster"></div>';
//	$listCountriesKiado .= '<div class="countryTableCell countryTableCellPages"></div>';
//	$listCountriesKiado .= '</div>';
//		
//	$countryOld = $row['id'];
//	$geoOld = $row['geography'];
//	$numAll++;
//}
//$listCountriesKiado .= '</div>';



$listCountriesKiado = '<div class="countryTable">
		<div class="countryTableRow">
			<div class="countryTableCell countryTableCellBox"><input type="checkbox" name="selectall" id="selectallK_' . $modulpath . '" class="checkfieldselectall"  data-checkfunction="" data-checkmessage="" data-checksync=""></div>
			<div class="countryTableCell countryTableCellCountry formgeogroup"><label for="selectallK_' . $modulpath . '">' . $TEXT['all_none'] . '</label></div>
			<div class="countryTableCell countryTableCellLanguage"></div>
			<div class="countryTableCell countryTableCellPreview"></div>
			<div class="countryTableCell countryTableCellMaster countryTableMaster"></div>
			<div class="countryTableCell countryTableCellPages">' . $TEXT['pages'] . '</div>
		</div>
';
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid as id,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code_spec AS code_count,
										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language,
										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code_spec AS code_lang
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni 
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')

										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')

										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang IN ('. implode(',', $CONFIG['user']['count2lang']) . ')

									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country, ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
$countryOld = 0;
$numAll = 0;
foreach($rows as $row){
	if($countryOld != $row['id']){
		$checkbox = '<input type="checkbox" name="country[]" id="countryKiado_' . $modulpath . '_'.$row['id'].'" class="checkfield " value="' . $row['id'] . '" data-checkfunction="checkArrayRequired" data-checkmessage="" data-checksync="all">';
		$country = $row['country'];
		$classRow = 'countryTableRowBorder';
	}else{
		$checkbox = '';
		$country = '';
		$classRow = '';
	}

	$listCountriesKiado .= '<div class="countryTableRow ' . $classRow . '" data-count2lang="' . $row['id_count2lang'] . '" data-geo="">';
	$listCountriesKiado .= '<div class="countryTableCell countryTableCellBox">' . $checkbox . '</div>';
	$listCountriesKiado .= '<div class="countryTableCell countryTableCellCountry"><label for="countryKiado_' . $modulpath . '_'.$row['id'].'">' . $country . '</label></div>';
	$listCountriesKiado .= '<div class="countryTableCell countryTableCellLanguage">' . $row['language'] . '</div>';
	$listCountriesKiado .= '<div class="countryTableCell countryTableCellPreview"></div>';
	$listCountriesKiado .= '<div class="countryTableCell countryTableCellMaster countryTableMaster"></div>';
	$listCountriesKiado .= '<div class="countryTableCell countryTableCellPages"></div>';
	$listCountriesKiado .= '</div>';
		
	$countryOld = $row['id'];
	$numAll++;
}
$listCountriesKiado .= '</div>';

###########################################################

$fieldUpload = '';
$fieldname = 'file_original';
foreach($CONFIG['user']['countries'] as $id_countid => $aCountry){
	foreach($aCountry['languages'] as $id_langid){
		$fieldUpload .= '<div class="formRow formRowHidden " data-fieldvariation="'.$id_countid.'_'.$id_langid.'_x##' . $fieldname . '"data-fieldname="' . $fieldname . '">';
		$fieldUpload .= '<div class="formLabel">';
		$fieldUpload .= '<label for="">' . $TEXT['fileupload'] . '</label>';
		$fieldUpload .= '</div>';
		$fieldUpload .= '<div class="formField">';
		$fieldUpload .= '<div data-name="'.$id_countid.'_'.$id_langid.'_x_' . $fieldname . 'F" class=""></div>';
		$fieldUpload .= '<div class="textfield textfieldUpload"><input type="button" class="formButton formButtonUpload" value="' . $TEXT['selectFile'] . '" /></div>';
		$fieldUpload .= '<input type="file" name="'.$id_countid.'_'.$id_langid.'_x_' . $fieldname . '" id="'.$id_countid.'_'.$id_langid.'_x_' . $fieldname . '_'.$modulpath.'" class="textfield fileupload " value="" data-checkfunction="" data-checkmessage="" data-fieldname="' . $fieldname . '"  data-allowedtypes="pdf" data-target="printads" data-checksync="device">';
		$fieldUpload .= '</div>';
		$fieldUpload .= '</div>';
	}
}

###########################################################

$fieldUploadSpecsheet = '';
$fieldname = 'specsheet_original';
foreach($CONFIG['user']['countries'] as $id_countid => $aCountry){
	foreach($aCountry['languages'] as $id_langid){
		$fieldUploadSpecsheet .= '<div class="formRow formRowHidden " data-fieldvariation="'.$id_countid.'_'.$id_langid.'_x##' . $fieldname . '"data-fieldname="' . $fieldname . '">';
		$fieldUploadSpecsheet .= '<div class="formLabel">';
		$fieldUploadSpecsheet .= '<label for="">' . $TEXT['fileupload'] . '</label>';
		$fieldUploadSpecsheet .= '</div>';
		$fieldUploadSpecsheet .= '<div class="formField">';
		$fieldUploadSpecsheet .= '<div data-name="'.$id_countid.'_'.$id_langid.'_x_' . $fieldname . 'F" class=""></div>';
		$fieldUploadSpecsheet .= '<div class="textfield textfieldUpload"><input type="button" class="formButton formButtonUpload" value="' . $TEXT['selectFile'] . '" /></div>';
		$fieldUploadSpecsheet .= '<input type="file" name="'.$id_countid.'_'.$id_langid.'_x_' . $fieldname . '" id="'.$id_countid.'_'.$id_langid.'_x_' . $fieldname . '_'.$modulpath.'" class="textfield fileupload " value="" data-checkfunction="" data-checkmessage="" data-fieldname="' . $fieldname . '"  data-allowedtypes="pdf" data-target="specsheets" data-checksync="device">';
		$fieldUploadSpecsheet .= '</div>';
		$fieldUploadSpecsheet .= '</div>';
	}
}

###########################################################

$listEmailtemplates = '<option value="0"></option>';
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_etid as id,
										' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.templatename AS term
									FROM ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.del = (:nultime)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_emailtemplates_uni.rank
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	$listEmailtemplates .= '<option value="' . $row['id'] . '"> ' . $row['term'] . '</option>';
}

###########################################################

$listSubSpecsheets = '<option value="0"></option>';
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.id_cssid as id,
										' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.category AS term
									FROM ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.del = (:nultime)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.rank
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	$listSubSpecsheets .= '<option value="' . $row['id'] . '"> ' . $row['term'] . '</option>';
}

###########################################################

$listSubBrochures = '<option value="0"></option>';
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.id_cbid as id,
										' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.category AS term
									FROM ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.del = (:nultime)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.rank
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	$listSubBrochures .= '<option value="' . $row['id'] . '"> ' . $row['term'] . '</option>';
}

###########################################################

//$listBanner = '';
//$query = $CONFIG['dbconn'][0]->prepare('
//									SELECT ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_bfid as id,
//										' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.bannername AS term,
//										' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.width,
//										' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.height,
//										' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.animated
//									FROM ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni 
//									
//									WHERE ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_count = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_lang = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_dev = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//										AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.del = (:nultime)
//									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.rank
//									');
//$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//$query->bindValue(':nul', 0, PDO::PARAM_INT);
//$query->execute();
//$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//$num = $query->rowCount();
//foreach($rows as $row){
//	$pages = ($row['animated'] == 1) ? 3 : 1;
//	$listBanner .= '<div style="padding: 20px 0 10px 0">' . $row['term'] . ' (' . $row['width'] . 'x' . $row['height'] . ')</div>';
//	
//	for($i=1; $i <= $pages; $i++){
//		$bannername = 'banner_original_' . $row['id'] . '_' . $i;
//		$label = '';
//		if($i == 1) $label = '<span style="padding-left: 20px;font-style:italic">' . $TEXT['firstframe'] . '</span>';
//		if($i == 2) $label = '<span style="padding-left: 20px;font-style:italic">' . $TEXT['productframe'] . '</span>';
//		if($i == 3) $label = '<span style="padding-left: 20px;font-style:italic">' . $TEXT['lastframe'] . '</span>';
//		
//		foreach($CONFIG['user']['countries'] as $id_countid => $aCountry){
//			foreach($aCountry['languages'] as $id_langid){
//				$listBanner .= '<div class="formRow formRowHidden " data-fieldvariation="'.$id_countid.'_'.$id_langid.'_x##' . $bannername . '"data-fieldname="' . $bannername . '">';
//				$listBanner .= '<div class="formLabel">';
//				$listBanner .= '<label for="">' . $label . '</label>';
//				$listBanner .= '</div>';
//				$listBanner .= '<div class="formField">';
//				$listBanner .= '<div data-name="'.$id_countid.'_'.$id_langid.'_x_' . $bannername . 'F" class=""></div>';
//				$listBanner .= '<div class="textfield textfieldUpload"><input type="button" class="formButton formButtonUpload" value="' . $TEXT['selectFile'] . '" /></div>';
//				$listBanner .= '<input type="file" name="'.$id_countid.'_'.$id_langid.'_x_' . $bannername . '" id="'.$id_countid.'_'.$id_langid.'_x_' . $bannername . '_'.$modulpath.'" class="textfield fileupload " value="" data-checkfunction="" data-checkmessage="" data-fieldname="' . $bannername . '"  data-allowedtypes="gif,jpg,jgep,png,tif,tiff" data-target="banner" data-checksync="device">';
//				$listBanner .= '</div>';
//				$listBanner .= '</div>';
//			}
//		}
//	}
//}




$listBanner = '
	<div class="formRow formRowNoBorder">
		<div class="formLabel">
			<label for="bannername_' . $modulpath . '">' . $TEXT['bannername'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="bannername" id="bannername_' . $modulpath . '" class="textfield" value="" data-checkfunction="" data-checkmessage="">
		</div>
	</div>
';
$pages = 3;
//$listBanner .= '<div style="padding: 20px 0 10px 0">' . $row['term'] . ' (' . $row['width'] . 'x' . $row['height'] . ')</div>';

for($i=1; $i <= $pages; $i++){
	$bannername = 'banner_original_' . $i;
	$label = '';
	$staticBanner = '';
	if($i == 1) $staticBanner = '<div style="font-size: 90%;font-style:italic">' . $TEXT['staticBanner'] . '</div>';
	if($i == 1) $label = '<span style="padding-left: 20px;font-style:italic">' . $TEXT['firstframe'] . '</span>';
	if($i == 2) $label = '<span style="padding-left: 20px;font-style:italic">' . $TEXT['productframe'] . '</span>';
	if($i == 3) $label = '<span style="padding-left: 20px;font-style:italic">' . $TEXT['lastframe'] . '</span>';
	
	foreach($CONFIG['user']['countries'] as $id_countid => $aCountry){
		foreach($aCountry['languages'] as $id_langid){
			$listBanner .= '<div class="formRow formRowHidden " data-fieldvariation="'.$id_countid.'_'.$id_langid.'_x##' . $bannername . '"data-fieldname="' . $bannername . '">';
			$listBanner .= '<div class="formLabel">';
			$listBanner .= '<label for="">' . $label . '</label>';
			$listBanner .= '</div>';
			$listBanner .= '<div class="formField">';
			$listBanner .= '<div data-name="'.$id_countid.'_'.$id_langid.'_x_' . $bannername . 'F" class=""></div>';
			$listBanner .= '<div class="textfield textfieldUpload"><input type="button" class="formButton formButtonUpload" value="' . $TEXT['selectFile'] . '" /></div>';
			$listBanner .= '<input type="file" name="'.$id_countid.'_'.$id_langid.'_x_' . $bannername . '" id="'.$id_countid.'_'.$id_langid.'_x_' . $bannername . '_'.$modulpath.'" class="textfield fileupload " value="" data-checkfunction="" data-checkmessage="" data-fieldname="' . $bannername . '"  data-allowedtypes="gif,jpg,jgep,png,tif,tiff" data-target="banner" data-checksync="device">' . $staticBanner;
			$listBanner .= '</div>';
			$listBanner .= '</div>';
		}
	}
}
$listBanner .= '
	<div class="formRow formRowSpace formRowBannerAdd">
		<div class="formLabel">
		</div>
		<div class="formField">
			<input type="hidden" name="bannerformat_id_bfid" id="bannerformat_id_bfid_' . $modulpath . '">
			<button class="formButton formButtonRight formButtonBannerAdd" type="button">' . $TEXT['Add'] . '</button>
		</div>
	</div>
	<div class="formRow formRowSpace formRowBannerEdit" style="display: none">
		<div class="">
			<input type="hidden" name="bannerformat_id_bfid" id="bannerformat_id_bfid_' . $modulpath . '">
			<button class="formButton formButtonRight formButtonBannerEdit" type="button">' . $TEXT['Save'] . '</button>
			<button class="formButton formButtonRight formButtonBannerCancel" type="button">' . $TEXT['Cancel'] . '</button>
		</div>
	</div>
';
###########################################################






######################################################################
$FORM_TOP_LEFT = '
<div class="fieldset" data-formtab="General">
	<div class="formRow formRowNoBorder">
		<div class="formLabel">
			<label for="title_' . $modulpath . '">' . $TEXT['templatetitle'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="title" id="title_' . $modulpath . '" class="textfield checkDirect" value="" data-checkfunction="checkRequired" data-checkmessage="">
		</div>
	</div>
	
	<div class="formRow formRowSpace">
	  <div class="formLabel">
		<label for="title_transrequired_' . $modulpath . '">' . $TEXT['transrequired'] . '</label>
	  </div>
	  <div class="formField">
		<div class="inlineRadiofield">
		  <label><input type="radio" name="title_transrequired" id="title_transrequired_' . $modulpath . '_1" class="booleanfield" value="1" data-checkfunction="" data-checkmessage=""> ' . $TEXT['yes'] . '</label>
		</div>
		<div class="inlineRadiofield">
		  <label><input type="radio" name="title_transrequired" id="title_transrequired_' . $modulpath . '_2" class="booleanfield" value="2" data-checkfunction="" data-checkmessage=""> ' . $TEXT['no'] . '</label>
		</div>
		<div class="inlineRadiofield">
		  <label><input type="radio" name="title_transrequired" id="title_transrequired_' . $modulpath . '_0" class="booleanfield" value="0" data-checkfunction="" data-checkmessage=""> ' . $TEXT['master'] . ' <span class="valuedefault"></span></label>
		</div>
	  </div>
	</div>
	
	
	
	
	
	<div id="formPrintad" class="formTemplateCategory" style="display:none">
		' . $fieldUpload . '
	</div>
	
	
	
	
	<div id="formRollup" class="formTemplateCategory" style="display:none">
		' . $fieldUpload . '
	</div>
	
	
	
	<div id="formEmail" class="formTemplateCategory" style="display:none">
		<div class="formRow">
			<div class="formLabel">
				<label for="id_etid_' . $modulpath . '">' . $TEXT['templatename'] . '</label>
			</div>
			<div class="formField">
				<select name="id_etid" id="id_etid_' . $modulpath . '" class="textfield checkDirect" value="" data-checkfunction="" data-checkmessage="" data-checksync="all">
				'.$listEmailtemplates.'
				</select>
			</div>
		</div>
	</div>
	
	
	
	<div id="formBanner" class="formTemplateCategory" style="display:none">
		<div class="formBannerAdd">
			'.$listBanner.'
		</div>
		<div class="formBannerformats">
		</div>
	</div>
	
	
	
	<div id="formSpecsheet" class="formTemplateCategory" style="display:none">
		<div class="formRow formRowSubSpecsheet">
			<div class="formLabel">
				<label for="id_cssid_' . $modulpath . '">' . $TEXT['subcategory'] . '</label>
			</div>
			<div class="formField">
				<select name="id_cssid" id="id_cssid_' . $modulpath . '" class="textfield" value="" data-checkfunction="" data-checkmessage="">
				'.$listSubSpecsheets.'
				</select>
			</div>
		</div>
		<div class="formRow formRowSubBrochure">
			<div class="formLabel">
				<label for="id_cbid_' . $modulpath . '">' . $TEXT['subcategory'] . '</label>
			</div>
			<div class="formField">
				<select name="id_cbid" id="id_cbid_' . $modulpath . '" class="textfield" value="" data-checkfunction="" data-checkmessage="">
				'.$listSubBrochures.'
				</select>
			</div>
		</div>

		<div class="formSpecsheetSource formSpecsheetSourceKiado" style="display: none">
			<div class="formRow">
				<div class="formLabel">
					<label for="kiado_code_' . $modulpath . '">' . $TEXT['kiado_code'] . '</label>
				</div>
				<div class="formField">
					<input type="text" name="kiado_code" id="kiado_code_' . $modulpath . '" class="textfield" value="" data-checkfunction="" data-checkmessage="" data-checksync="all">
				</div>
			</div>
		</div>
		<div class="formSpecsheetSource formSpecsheetSourcePdf" style="display: none">
			' . $fieldUploadSpecsheet . '
		</div>

	</div>







	
</div>



<div class="fieldset" data-formtab="countries">
  <div class="formRow formRowCountriesDefault">
    <div class="formField formFieldFullwidth">
      ' . $listCountries . '
    </div>
  </div>
  <div class="formRow formRowCountriesKiado" style="display:none">
    <div class="formField formFieldFullwidth">
      ' . $listCountriesKiado . '
    </div>
  </div>
</div>

';
######################################################################


if(!isset($FORM_TABS_RIGHT)) $FORM_TABS_RIGHT = '<ul></ul>';






$FORM_TABS_RIGHT = '<ul><li data-formtab="Components" class="active"><span class="formTabIconError"><i class="fa" aria-hiddem="true"></i></span>Components</li></ul>';


$FORM_TOP_RIGHT = '
<div id="modul_112-0-11-components" class="childmodul loaded" data-modulpath="112-0-11-components">
<div class="formComponentsOuter">
	<div class="formComponentsTop">
		<div class="formComponentsInner">
			<div class="formComponentsThumbnails"></div>
			<div class="formComponentsPreview">
				<div class="formComponentPreviewOuter">
					<div class="formComponentPreviewBackground"></div>
					<div class="formComponentPreviewComponents"></div>
				</div>
			</div>
			<div class="formComponentsTools">
				<div class="formComponentsComponents"></div>
				<form enctype="multipart/form-data" onsubmit="return false" action="" method="post" name="iFormRight" id="formright_' . $CONFIG['page']['modulpath'] . '" class="inputForm">
					<div class="formComponentsForm"></div>
				</form>
			</div>
		</div>
	</div>
	<div class="formComponentsBottom">
		<button class="formButton configComponentSave" type="button">' . $TEXT['ConfigurationSave'] . '</button>
		<button class="formButton configComponentLoad" type="button">' . $TEXT['ConfigurationLoad'] . '</button>
		<button class="formButton templatePublish" type="button">' . $TEXT['TemplatePublish'] . '</button>
	</div>
</div>
</div>
';












 

######################################################################
$FORM = '
	<div class="formLeft">
		<div class="formLeftInner">

			<div class="formTabs"><ul></ul></div>
	
			<form enctype="multipart/form-data" onsubmit="return false" action="" method="post" name="iFormLeft" id="form_' . $CONFIG['page']['modulpath'] . '" class="inputForm">
			
				' . $FORM_TOP_LEFT . '
			
				<div class="formFooter">
					<input type="hidden" class="field_id_data" value="" name="id_data">
					<input type="hidden" class="field_formdata" value="" name="formdata">
					<input type="hidden" name="id_tempid" id="id_tempid_112-0-111" class="textfield " value="' . $CONFIG['page']['id_data'] . '" data-checkfunction="" data-checkmessage="">

					<input type="hidden" class="" value="" name="contentselect">
					<input type="hidden" class="" value="" name="components">
					<input type="hidden" class="" value="" name="id_caid">
					<input type="hidden" class="" value="" name="id_tpid">
					<input type="hidden" class="" value="" name="activeComp">
					' . $f_fieldshidden . '
				
					<button class="formButton cancelForm" type="button">' . $TEXT['Cancel'] . '</button>
					<button class="formButton saveForm" value="" name="save" type="submit">' . $TEXT['Save'] . '</button>
					<button class="formButton closeForm" value="" name="close" type="submit">' . $TEXT['SaveClose'] . '</button>
					
					<div class="errorMess" id="errorMessage">&nbsp;</div>
				</div>
			</form>
			
		</div>
	</div>
	
	<div class="formMiddle"></div>
	
	<div class="formRight">
		<div class="formRightInner">

			<div class="formTabs" style="height: 41px;">' . $FORM_TABS_RIGHT . '</div>
		 
			' . $FORM_TOP_RIGHT . '
		</div>
	</div>
';
######################################################################

?>