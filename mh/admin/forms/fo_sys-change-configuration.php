<?php
$initCondPage = true;
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();


$listLanguages = '<option value="all"></option>';
$aLanguages = array_keys($CONFIG['user']['syslanguages']);
$aResult = array();
foreach($aLanguages as $id_lang){
	$language = $CONFIG['user']['syslanguages'][$id_lang]['language']; 
	$language = (isset($TEXT[$language])) ? $TEXT[$language] : $language;
	$aResult[$id_lang] = $language;
}

asort($aResult, SORT_NATURAL | SORT_FLAG_CASE);
foreach($aResult as $id => $term){
	if($id != 0){
		$sel = '';
		if($CONFIG['activeSettings']['systemLanguage'] == $CONFIG['user']['syslanguages'][$id]['code']) $sel = 'selected';
		$listLanguages .= '<option value="' . $CONFIG['user']['syslanguages'][$id]['code'] . '" ' . $sel . '>' . $term . '</option>';
	}
}




$listFormat = '<option value="0"></option>';
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count as id,
										' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_decimal.code AS sep_decimal,
										' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_thousand.code AS sep_thousand,
										' . $CONFIG['db'][0]['prefix'] . 'system_format_date.format AS format_date,
										' . $CONFIG['db'][0]['prefix'] . 'system_format_time.format AS format_time
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_countries 
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator AS ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_decimal
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_fs_decimal = ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_decimal.id_fs
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator AS ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_thousand
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_fs_thousand = ' . $CONFIG['db'][0]['prefix'] . 'system_format_separator_thousand.id_fs
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_date
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_fd = ' . $CONFIG['db'][0]['prefix'] . 'system_format_date.id_fd
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_format_time
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_ft = ' . $CONFIG['db'][0]['prefix'] . 'system_format_time.id_ft
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_countries.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count IN ('. implode(',', array_keys($CONFIG['user']['syscountries'])) . ')

									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_countries.country
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$aResult = array();
foreach($rows as $row){
	$aResult[$row['id']] = $row['format_date'] . ' ' . $row['format_time'] . ' - 1' . $row['sep_thousand'] . '234' . $row['sep_decimal'] . '00';
}

foreach($aResult as $id => $term){
	$sel = '';
	if($CONFIG['activeSettings']['systemCountry'] == $id) $sel = 'selected';
	$listFormat .= '<option value="' . $id . '" ' . $sel . '>' . $term . '</option>';
}



######################################################################
$FORM = '
<form enctype="multipart/form-data" onsubmit="return false" action="" method="post" name="diaForm" id="diaForm" class="exportForm">
	<div class="formTab" data-formtab="formGeneralExport">
		<div class="formRow">
			<div class="formLabel">
				<label for="newlanguage">' . $TEXT['NewLanguage'] . '</label>
			</div>
			<div class="formField">
				<select name="newlanguage" id="newlanguage" class="textfield">
				' . $listLanguages . '
				</select>
			</div>
		</div>

		<div class="formRow">
			<div class="formLabel">
				<label for="system_country">' . $TEXT['system_country'] . '</label>
			</div>
			<div class="formField">
				<select name="system_country" id="system_country" class="textfield">
				' . $listFormat . '
				</select>
			</div>
		</div>

	</div>
</form>
';
######################################################################




echo $FORM;

?>