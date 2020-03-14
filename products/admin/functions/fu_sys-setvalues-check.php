<?php
function setValuesCheck($dat, $aFields=array(), $keyC=0, $keyL=0, $keyD=0){
	global $CONFIG, $TEXT; 
	
	$res = array();

	// Mandatory
	if(isset($aFields['mandatory'])){
		foreach($aFields['mandatory'] as $field){
			if($dat[$keyC][$keyL][$keyD][$field] != ''){
				$res[$field] = 'OK';
			}else{
				$res[$field] = $TEXT['fieldRequired'];
			}
		}
	}
 
//	// Floats
//	if(isset($aFields['floats'])){
//		foreach($aFields['floats'] as $field){
//			if($dat[$keyC][$keyL][$keyD][$field] != ''){
//				$dat[$keyC][$keyL][$keyD][$field] = str_replace(',','.',str_replace('.','',$dat[$keyC][$keyL][$keyD][$field]));
//			}else{
//				$dat[$keyC][$keyL][$keyD][$field] = '';
//			}
//		}
//	}
	
	// Timestamps
	if(isset($aFields['timestamps'])){
		foreach($aFields['timestamps'] as $field){
			if(strlen($dat[$keyC][$keyL][$keyD][$field]) == 19){
				$pattern = '/^(19|20)[0-9]{2}[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])[ ](0[1-9]|1[012]|2[01234])[:]([0-5][0-9])[:]([0-5][0-9])$/';
				if(preg_match($pattern, $dat[$keyC][$keyL][$keyD][$field])){
					$res[$field] = 'OK';
				}else{
					$res[$field] = $TEXT['fieldCheckDatetime'] . ' ' . $TEXT['fieldFormatText'];
				}
			}else if(strlen($dat[$keyC][$keyL][$keyD][$field]) == 10){
				$pattern = '/^(19|20)[0-9]{2}[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/';
				if(preg_match($pattern, $dat[$keyC][$keyL][$keyD][$field])){
					$res[$field] = 'OK';
				}else{
					$res[$field] = $TEXT['fieldCheckDate'] . ' ' . $TEXT['fieldFormatText'];
				}
			}else{
				$res[$field] = $TEXT['fieldCheckDate'] . ' ' . $TEXT['fieldFormatText'];
			}
		}
	}
	
	// Dates
	if(isset($aFields['dates'])){
		foreach($aFields['dates'] as $field){
			$pattern = '/^(19|20)[0-9]{2}[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/';
			if(preg_match($pattern, $dat[$keyC][$keyL][$keyD][$field])){
				$res[$field] = 'OK';
			}else{
				$res[$field] = $TEXT['fieldCheckDate'] . ' ' . $TEXT['fieldFormatText'];
			}
		}
	}
	
//	// Field to Text
//	if(isset($aFields['field2Text'])){
//		foreach($aFields['field2Text'] as $field){
//			$dat[$field . 'T'] = $TEXT[$field . $dat[$field]];
//		}
//	}
//
//	// yes / no Text
//	if(isset($aFields['yesNo2Text'])){
//		foreach($aFields['yesNo2Text'] as $field){
//			$dat[$field."T"] = $TEXT['check'.$dat[$field]];
//		}
//	}
//	
//	// Checkbox to Radio
//	if(isset($aFields['check2Radio'])){
//		foreach($aFields['check2Radio'] as $field){
//			if($keyC == 0 && $keyL == 0 && $keyD == 0){
//				if($dat[$field] == "") $dat[$field] = $dat[$field.'_full'];
//			}else{
//				if($dat[$field] == "") $dat[$field] = 0;
//			}
//		}
//	}
	
	return $res;
}





?>