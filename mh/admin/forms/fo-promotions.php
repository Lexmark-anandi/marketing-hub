<?php 
$modulpath = $CONFIG['page']['modulpath'];
$modulpathT = '115-0-113-113-114';

$FORM = '';

###########################################################

$fieldPreview = '';
$fieldname = 'preview_thumbnail';
foreach($CONFIG['user']['countries'] as $id_countid => $aCountry){
	foreach($aCountry['languages'] as $id_langid){
		$fieldPreview .= '<div class="formRow formRowHidden formRowSpace " data-fieldvariation="'.$id_countid.'_'.$id_langid.'_x##' . $fieldname . '"data-fieldname="' . $fieldname . '">';
		$fieldPreview .= '<div class="formLabel">';
		$fieldPreview .= '<label for="">' . $TEXT['fileuploadpreview'] . '</label>';
		$fieldPreview .= '<br>(Dimension 268 x 200px)';
		$fieldPreview .= '</div>';
		$fieldPreview .= '<div class="formField">';
		$fieldPreview .= '<div data-name="'.$id_countid.'_'.$id_langid.'_x_' . $fieldname . 'F" class=""></div>';
		$fieldPreview .= '<div class="textfield textfieldUpload"><input type="button" class="formButton formButtonUpload" value="' . $TEXT['selectFile'] . '" /></div>';
		$fieldPreview .= '<input type="file" name="'.$id_countid.'_'.$id_langid.'_x_' . $fieldname . '" id="'.$id_countid.'_'.$id_langid.'_x_' . $fieldname . '_'.$modulpath.'" class="textfield fileupload " value="" data-checkfunction="" data-checkmessage="" data-fieldname="' . $fieldname . '"  data-allowedtypes="png,jpg,jpeg" data-target="previewthumbnails" data-checksync="device">';
		$fieldPreview .= '</div>';
		$fieldPreview .= '</div>'; 
	}
}

###########################################################

$listAC = '<option value="0"></option>';
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_caid as id,
										' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.category AS term
									FROM ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.del = (:nultime)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.rank
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->execute(); 
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	$listAC .= '<option value="'.$row['id'].'">' . $row['term'] . '</option>';
}

###########################################################

$listCountries = '<div class="countryTable">
		<div class="countryTableRow">
			<div class="countryTableCell countryTableCellBox"><input type="checkbox" name="selectall" id="selectall_' . $modulpath . '" class="checkfieldselectall"  data-checkfunction="" data-checkmessage="" data-checksync=""></div>
			<div class="countryTableCell countryTableCellCountry formgeogroup"><label for="selectall_' . $modulpath . '">' . $TEXT['all_none'] . '</label></div>
			<div class="countryTableCell countryTableCellLanguage"></div>
		</div>';
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang AS id,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid,
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
$geoOld = '';
foreach($rows as $row){
	$checkbox = '<input type="checkbox" name="country[]" id="country_' . $modulpath . '_'.$row['id'].'" class="checkfield " value="' . $row['id'] . '" data-checkfunction="" data-checkmessage="" data-checksync="all">';
	$country = $row['country'];
	$classRow = 'countryTableRowBorder';

	$listCountries .= '<div class="countryTableRow ' . $classRow . '" data-count2lang="' . $row['id'] . '" data-geo="">';
	$listCountries .= '<div class="countryTableCell countryTableCellBox">' . $checkbox . '</div>';
	$listCountries .= '<div class="countryTableCell countryTableCellCountry"><label for="country_' . $modulpath . '_'.$row['id'].'">' . $country . '</label></div>';
	$listCountries .= '<div class="countryTableCell countryTableCellLanguage">' . $row['language'] . '</div>';
	$listCountries .= '</div>';
		
	$numAll++;
}
$listCountries .= '</div>';

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


$aAssignedDatasetsAll = array();
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_promotions2products_.id_pid
									FROM ' . $CONFIG['db'][0]['prefix'] . '_promotions2products_ 
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions2products_.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2products_.id_promid = (:id)
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	array_push($aAssignedDatasetsAll, $row['id_pid']);
}

$aAssignedDatasets = array();
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_promotions2products_.id_pid
									FROM ' . $CONFIG['db'][0]['prefix'] . '_promotions2products_ 
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions2products_.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2products_.id_promid = (:id)
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	array_push($aAssignedDatasets, $row['id_pid']);
}



$listPT = '<option value="0"></option>';
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.id_ptid as id,
										' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.prod_type as term
									FROM ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni 
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.is_printer = (:yes)
										AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.product_type_id < 90000
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.prod_type
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':yes', 1, PDO::PARAM_INT);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount(); 

$aResult = array(); 
foreach($rows as $row){
	$row['term'] = (isset($TEXT[$row['term']])) ? $TEXT[$row['term']] : $row['term'];
	$aResult[$row['id']] = $row['term'];
}

//asort($aResult, SORT_NATURAL | SORT_FLAG_CASE);
foreach($aResult as $id => $term){
	$listPT .= '<option value="prodtype#' . $id . '#">' . $term . '</option>';
}




$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_pid,
										' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_ptid,
										' . $CONFIG['db'][0]['prefix'] . '_products_uni.pn_text,
										' . $CONFIG['db'][0]['prefix'] . '_products_uni.mkt_name
									FROM ' . $CONFIG['db'][0]['prefix'] . '_products_uni 

									WHERE ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_count = (:count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_lang = (:lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_dev = (:dev)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.is_printer = (:yes)
									GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_pid
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_products_uni.mkt_name
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':yes', 1, PDO::PARAM_INT);
$query->bindValue(':count', 0, PDO::PARAM_INT);
$query->bindValue(':lang', 0, PDO::PARAM_INT);
$query->bindValue(':dev', 0, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();


$list = '';
$listSelected = '';
$listAvailable = '';
foreach($rows as $row){
	$row['identifier'] = '';
//	if($row['pn_text'] != ''){
//		$row['identifier'] = $row['pn_text'];
//		$row['identifier'] .= ' - ';
//	}
	if($row['mkt_name'] != ''){
		$row['identifier'] .= $row['mkt_name'];
		$row['identifier'] .= ' - ';
	}
	$row['identifier'] .= 'prodtype#' . $row['id_ptid'] . '#';
	$row['identifier'] = rtrim($row['identifier'], '- ');

	$row['output'] = '';
//	if($row['pn_text'] != ''){
//		$row['output'] = $row['pn_text'];
//		$row['output'] .= ' - ';
//	}
	if($row['mkt_name'] != ''){
		$row['output'] .= $row['mkt_name'];
		$row['output'] .= ' - ';
	}
	$row['output'] = rtrim($row['output'], '- ');



	$sel = '';
	if(in_array($row['id_pid'], $aAssignedDatasets)) $sel = 'selected="selected"';
	$list .= '<option value="'.$row['id_pid'].'" '.$sel.'>'.$row['output'].'</option>';
	
	$display = 'listassignhidden';
	if(in_array($row['id_pid'], $aAssignedDatasets)) $display = 'listassignvisible';
	$listSelected .= '<li class="ui-state-default ui-element ui-draggable ui-draggable-handle '.$display.'" data-value="'.$row['id_pid'].'" data-search="'.strtolower($row['identifier']).'">'.$row['output'].'<a href="javascript:void(null)" class="action"><span class="ui-corner-all ui-icon ui-icon-minus"></span></a></li>';
	
	$display = 'listassignhidden';
	if(!in_array($row['id_pid'], $aAssignedDatasetsAll)) $display = 'listassignvisible searchvisible';
	$listAvailable .= '<li class="ui-state-default ui-element ui-draggable ui-draggable-handle '.$display.'" data-value="'.$row['id_pid'].'" data-search="'.strtolower($row['identifier']).'">'.$row['output'].'<a href="javascript:void(null)" class="action"><span class="ui-corner-all ui-icon ui-icon-plus"></span></a></li>';
}






######################################################
######################################################
######################################################

$FORM .= '
	<div class="formLeft formLeftFull formPromotions">
		<div class="formLeftInner">

			<div class="formTabs"><ul></ul></div>
	
			<form enctype="multipart/form-data" onsubmit="return false" action="" method="post" name="iFormLeft" id="form_' . $CONFIG['page']['modulpath'] . '" class="inputForm">
';			

######################################################
// Step 1
######################################################
$FORM .= '
	<div class="fieldset" data-formtab="Step1P" data-step="1">
		<div class="formRow formRowNoBorder">
			<div class="formLabel">
				<label for="title_' . $modulpath . '">' . $TEXT['templatetitle'] . '</label>
			</div>
			<div class="formField">
				<input type="text" name="title" id="title_' . $modulpath . '" class="textfield textfieldTitle" value="" data-checkfunction="" data-checkmessage="">
			</div>
		</div>
		
		<div class="formRow formRowSpace">
		  <div class="formLabel">
			<label for="title_transrequired_' . $modulpath . '">' . $TEXT['transrequired'] . '</label>
		  </div>
		  <div class="formField">
			<div class="inlineRadiofield">
			  <label><input type="radio" name="title_transrequired" id="title_transrequired_' . $modulpath . '_1" class="booleanfield" value="1" data-checkfunction="" data-checkmessage="" data-checksync="all"> ' . $TEXT['yes'] . '</label>
			</div>
			<div class="inlineRadiofield">
			  <label><input type="radio" name="title_transrequired" id="title_transrequired_' . $modulpath . '_2" class="booleanfield" value="2" data-checkfunction="" data-checkmessage="" data-checksync="all"> ' . $TEXT['no'] . '</label>
			</div>
			<div class="inlineRadiofield" style="opacity:0">
			  <label><input type="radio" name="title_transrequired" id="title_transrequired_' . $modulpath . '_0" class="booleanfield" value="0" data-checkfunction="" data-checkmessage="" data-checksync="all"> ' . $TEXT['master'] . ' <span class="valuedefault"></span></label>
			</div>
		  </div>
		</div>
		<div class="formRow formRowSpace">
		  <div class="formLabel">
			<label for="bsd_only_' . $modulpath . '">Target group<!--' . $TEXT['bsdonly'] . '--></label>
		  </div>
		  <div class="formField">
			<div class="inlineRadiofield" style="display:block">
			  <label><input type="radio" name="bsd_only" id="bsd_only_' . $modulpath . '_2" class="booleanfield" value="2" data-checkfunction="" data-checkmessage="" data-checksync="all"> All Partners<!--' . $TEXT['no'] . '--></label>
			</div>
			<div class="inlineRadiofield" style="display:block">
			  <label><input type="radio" name="bsd_only" id="bsd_only_' . $modulpath . '_1" class="booleanfield" value="1" data-checkfunction="" data-checkmessage="" data-checksync="all"> Business Solutions only<!--' . $TEXT['yes'] . '--></label>
			</div>
			<div class="inlineRadiofield" style="display:block">
			  <label><input type="radio" name="bsd_only" id="bsd_only_' . $modulpath . '_3" class="booleanfield" value="3" data-checkfunction="" data-checkmessage="" data-checksync="all"> Distribution only</label>
			</div>
			<div class="inlineRadiofield" style="opacity:0">
			  <label><input type="radio" name="bsd_only" id="bsd_only_' . $modulpath . '_0" class="booleanfield" value="0" data-checkfunction="" data-checkmessage="" data-checksync="all"> ' . $TEXT['master'] . ' <span class="valuedefault"></span></label>
			</div>
		  </div>
		</div>
	
				
		' . $fieldPreview . '

		<div class="formRow">
			<div class="formLabel">
				<label for="startdate_' . $modulpath . '">' . $TEXT['startdate'] . '</label>
			</div>
			<div class="formField">
				<input type="text" name="startdate" id="startdate_' . $modulpath . '" class="textfield calendar" value="" data-checkfunction="" data-checkmessage="">
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="enddate_' . $modulpath . '">' . $TEXT['enddate'] . '</label>
			</div>
			<div class="formField">
				<input type="text" name="enddate" id="enddate_' . $modulpath . '" class="textfield calendar" value="" data-checkfunction="" data-checkmessage="">
			</div>
		</div>
	</div>
';



######################################################
// Step 2
######################################################
$FORM .= '
	<div class="fieldset" data-formtab="Step2P" data-step="2">
	  <div class="formRow formRowCountries">
		<div class="formField formFieldFullwidth">
		  ' . $listCountries . '
		</div>
	  </div>
	</div>
';




######################################################
// Step 3
######################################################
$FORM .= '
	<div class="fieldset" data-formtab="Step3P" data-step="3">
		<div class="formRow">
			<div class="formLabel">
				<label for="search_' . $modulpath . '">' . $TEXT['search'] . '</label>
			</div>
			<div class="formField">
				<input type="text" name="search" id="search_' . $modulpath . '" class="textfield textfieldPartnersearch" value="" data-checkfunction="" data-checkmessage="">
			</div>
		</div>

		<div class="formRow formRowSpace">
			<div class="formLabel">
				<label for="bsdonly_' . $modulpath . '">' . $TEXT['bsdonly'] . '</label>
			</div>
			<div class="formField">
				<input type="checkbox" name="bsdonly" id="bsdonly_' . $modulpath . '" class="checkBsdonly" value="1" data-checkfunction="" data-checkmessage="">
			</div>
		</div>
		<div class="formRow formRowPartner">
			<div class="formLabel">
				<label for="partnercompany_' . $modulpath . '">' . $TEXT['partnercompany'] . '</label>
			</div>
			<div class="formField">
				<select name="partnercompany" id="partnercompany_' . $modulpath . '" class="textfield selectPartner" value="" data-checkfunction="" data-checkmessage="">
					<option value="0"></option>
				</select>
			</div>
		</div>
	</div>
';




######################################################
// Step 4
######################################################
$FORM .= '
	<div class="fieldset fieldsetSelectassign" data-formtab="Step3PP" data-step="4">
		<div class="formRow formRowSelectAssign">
			<div class="selectassignNote">'.$TEXT['productNote'].'</div>
			<div class="selectassignNote">'.$TEXT['productNote2'].'</div>
			<select name="selectassign_products[]" id="selectassign_products" class="selectassign selectassign_" multiple="multiple" data-sync="products">
				'.$list.'
			</select>
			<div class="ui-multiselect ui-helper-clearfix ui-widget">
				<div class="selected">
					<div class="actions ui-widget-header ui-helper-clearfix">
						<span class="count"><span class="counter">'. count($aAssignedDatasets) .'</span> '.$TEXT['numselected'].'</span><a href="javascript:void(null)" class="remove-all">'.$TEXT['allout'].'</a>
					</div>
					<ul class="selected connected-list" data-assign="">
						<li class="ui-helper-hidden-accessible"></li>
						'.$listSelected.'
					</ul>
				</div>
				<div class="available">
					<div class="actions ui-widget-header ui-helper-clearfix">
						<input type="text" class="search searchText empty ui-widget-content ui-corner-all" style="display:block;opacity:1;width:150px;height:18px;" placeholder="'.$TEXT['search'].'"><select name="" id="" class="search searchSelect ui-widget-content ui-corner-all" style="display:block;opacity:1;width:150px;height:18px;margin-top: 4px;padding: 0;">' . $listPT . '</select><a href="javascript:void(null)" class="add-all">'.$TEXT['allin'].'</a>
					</div>
					<ul class="available connected-list">
						<li class="ui-helper-hidden-accessible"></li>
						'.$listAvailable.'
					</ul>
				</div>
			</div>
		</div>
	</div>
';



######################################################
// Step 5
######################################################
$FORM .= '<div class="fieldset fieldsetFullWidth fieldsetGrid" data-formtab="Step4PT" data-step="5">';
$FORM .= '</div>';
#############################################################################



######################################################
// Step 6
######################################################
$FORM .= '
	<div class="fieldset" data-formtab="Step5P" data-step="6">
	  <div class="formRow">
		<div class="formLabel">
			<label for="">' . $TEXT['title'] . '</label>
		</div>
		<div class="formField overviewTitle"></div>
	  </div>
	  <div class="formRow">
		<div class="formLabel">
			<label for="">' . $TEXT['countries'] . '</label>
		</div>
		<div class="formField overviewCountries"></div>
	  </div>
	  <div class="formRow">
		<div class="formLabel">
			<label for="">' . $TEXT['partnercompany'] . '</label>
		</div>
		<div class="formField overviewPartner"></div>
	  </div>
	  <div class="formRow">
		<div class="formLabel">
			<label for="">' . $TEXT['Products'] . '</label>
		</div>
		<div class="formField overviewProducts"></div>
	  </div>
	  <div class="formRow formRowSpace">
		<div class="formLabel">
			<label for="">' . $TEXT['templates'] . '</label>
		</div>
		<div class="formField overviewTemplates"></div>
	  </div>

	  <div class="formRow formRowNoBorder">
		<div class="formField formFieldFullwidth">
			<button class="formButton formButtonRight templatePublish" type="button">' . $TEXT['TemplatePublish'] . '</button>
		</div>
	  </div>

	</div>
';



######################################################



$FORM .= '
				<div class="formFooter">
					<input type="hidden" class="field_id_data" value="" name="id_data">
					<input type="hidden" class="field_formdata" value="" name="formdata">

					<input type="hidden" name="id_tempid" id="id_tempid_112-0-111" class="textfield " value="' . $CONFIG['page']['id_data'] . '" data-checkfunction="" data-checkmessage="">

					<input type="hidden" class="" value="" name="contentselection">
					<input type="hidden" class="" value="" name="components">
					<input type="hidden" class="" value="" name="caid">
					<input type="hidden" class="" value="" name="id_tpid">
					<input type="hidden" class="" value="" name="activeComp">
				
					<button class="formButton cancelForm" type="button">' . $TEXT['Cancel'] . '</button>
					<button class="formButton previousStep" value="" name="save" type="submit">' . $TEXT['PreviousStep'] . '</button>
					<button class="formButton nextStep" value="" name="save" type="submit">' . $TEXT['NextStep'] . '</button>
					
					<div class="errorMess" id="errorMessage">&nbsp;</div>
				</div>
			
			</form>
		</div>
	</div>
';





######################################################
######################################################
######################################################

$FORM .= '
	<div class="formRight formRightFull formFullHide formTemplates">
		<div class="formRightInner">

			<div class="formTabs"><ul></ul></div>
';
			
			
$FORM .= '<div id="modul_' . $modulpathT . '" class="childmodul" data-modulpath="' . $modulpathT . '">';
$FORM .= '<div id="grid_' . $modulpathT . '" class="grid">';

$FORM .= '<div class="tabModulFilterButtonsRight">';
$FORM .= '<div class="modulIcon modulIconBox gridMenueFunctions" title=""><i class="fa fa-navicon"></i></div>';
$FORM .= '<div class="modulIcon modulIconBox gridExpandAll" title=""><i class="fa fa-chevron-down"></i></div>';
$FORM .= '</div>';

$FORM .= '<table id="gridTable_' . $modulpathT . '" class="gridTable"></table>';

################
// pager
$listRows = '';
foreach($CONFIG['system']['aGridNumRows'] as $row){
	$listRows .= '<option value="' . $row . '">' . $row . '</option>';
}
$FORM .= '<div id="gridPager_' . $modulpathT . '" class="gridPager">';

$FORM .= '<div id="gridPager_' . $modulpathT . '_left" class="gridPagerInner gridPagerLeft">';
$FORM .= '<div class="modulIcon pagerRefresh" title=""><i class="fa fa-refresh"></i></div>';
$FORM .= '<div class="modulIcon pagerSettings" title=""><i class="fa fa-sliders"></i></div>';
$FORM .= '</div>';

$FORM .= '<div id="gridPager_' . $modulpathT . '_right" class="gridPagerInner gridPagerRight"><span class="pagerRecords"></span></div>';

$FORM .= '<div id="gridPager_' . $modulpathT . '_center" class="gridPagerInner gridPagerCenter">';
$FORM .= '<div class="modulIcon pagerFirstPage" title=""><i class="fa fa-fast-backward"></i></div>';
$FORM .= '<div class="modulIcon pagerPrevPage" title=""><i class="fa fa-flip-horizontal fa-play"></i></div>';
$FORM .= '<div class="modulIcon pagerPage" title=""><input type="text" name="pagerActPage" id="pagerActPage_' . $modulpathT . '" value="" class="pagerActPage"> / <span class="pagerTotalPages"></span></div>';
$FORM .= '<div class="modulIcon pagerNextPage" title=""><i class="fa fa-play"></i></div>';
$FORM .= '<div class="modulIcon pagerLastPage" title=""><i class="fa fa-fast-forward"></i></div>';
$FORM .= '<div class="modulIcon pagerRows" title=""><select name="pagerRows" id="pagerRows_' . $modulpathT . '" class="pagerSelectRows">' . $listRows . '</select></div>';
$FORM .= '</div>';

$FORM .= '</div>';

$FORM .= '</div>';

#############################################################################
// Form
$FORM .= '<div id="form_' . $modulpathT . '" class="form hidden">';
$FORM .= '<div id="" class="tabFormFilter formFilter">';

//if($CONFIG['system']['useMultiple'] == 1){
//	if($aModuls['specifications'][6] == 9){
//		$FORM .= ' <label class="formFilterLabel" for="filterFormCountry_' . $modulpathT . '">' . $TEXT['filterCountry'] . '</label> <div class="wFormFilterOuter"><select class="textfield wFormFilter filterFormCountry" name="filterFormCountry" id="filterFormCountry_' . $modulpathT . '">' . $listCountries . '</select></div>';
//	}
//	if($aModuls['specifications'][7] == 9){
//		$FORM .= ' <label class="formFilterLabel" for="filterFormLanguage_' . $modulpathT . '">' . $TEXT['filterLanguage'] . '</label> <div class="wFormFilterOuter"><select class="textfield wFormFilter filterFormLanguage" name="filterFormLanguage" id="filterFormLanguage_' . $modulpathT . '">' . $listLanguages . '</select></div>';
//	}
//	if($aModuls['specifications'][8] == 9){
//		$FORM .= ' <label class="formFilterLabel" for="filterFormDevice_' . $modulpathT . '">' . $TEXT['filterDevice'] . '</label> <div class="wFormFilterOuter"><select class="textfield wFormFilter filterFormDevice" name="filterFormDevice" id="filterFormDevice_' . $modulpathT . '">' . $listDevices . '</select></div>';
//	}
//} 

$FORM .= '<div class="modulIcon modulIconBox formNavButton formNavButtonPrev" title="' . $TEXT['prevRow'] . '"><i class="fa fa-play fa-flip-horizontal"></i></div>';
$FORM .= '<div class="modulIcon modulIconBox formNavButton formNavButtonNext" title="' . $TEXT['nextRow'] . '"><i class="fa fa-play"></i></div>';
$FORM .= '<div class="modulIcon modulIconBox formNavButton formNavButtonMax" title="' . $TEXT['maximizeForm'] . '"><i class="fa fa-window-maximize"></i></div>';
$FORM .= '</div>';

$FORM .= '<div class="formContent">';
			
$FORM .= '</div>';

######################################################
######################################################
######################################################
























echo $FORM;

?>