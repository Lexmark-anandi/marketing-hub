<?php
$initCondPage = true;
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$aData = json_decode($varSQL['data'], true);
$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');


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
	<div class="formTab" data-formtab="formGeneralConfSave">
		<div class="formRow">
			<div class="formLabel">
				<label for="configurationname">' . $TEXT['configurationname'] . '</label>
			</div>
			<div class="formField">
				<input type="text" class="textfield" name="configurationname" id="configurationname" value="' . $aData['template'] . '-' . $aData['page'] . ' ' . $now . '">
			</div>
		</div>

	</div>
</form>
';
######################################################################




echo $FORM;

?>