<?php

function checkContentSelectRequired($field, $aData){
	global $CONFIG, $TEXT;

	$error = '';
	if(isset($aData[$field]) && $aData[$field] == '' && ($aData['id_caid'] == 4 || $aData['id_caid'] == 5)){ 
		$error = $TEXT['fieldRequired'];
	}
    return $error;
}



 




?>