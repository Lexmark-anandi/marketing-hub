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

	</div>
</form>
';
######################################################################




echo $FORM;

?>