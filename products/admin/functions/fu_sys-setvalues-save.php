<?php
function setValuesSave($dat, $aFields=array(), $keyC=0, $keyL=0, $keyD=0){
	global $CONFIG, $TEXT; 

	// Floats
	if(isset($aFields['floats'])){
		foreach($aFields['floats'] as $field){
			if($dat[$keyC][$keyL][$keyD][$field] != ''){
				$dat[$keyC][$keyL][$keyD][$field] = str_replace(',','.',str_replace('.','',$dat[$keyC][$keyL][$keyD][$field]));
			}else{
				$dat[$keyC][$keyL][$keyD][$field] = '';
			}
		}
	}
	
	// Timestamps
	if(isset($aFields['timestamps'])){
		foreach($aFields['timestamps'] as $field){
			if(strlen($dat[$keyC][$keyL][$keyD][$field]) == 19){
				$aTmp = explode(' ', $dat[$keyC][$keyL][$keyD][$field]);
				$aTmp2 = explode('.', $aTmp[0]);
				$dat[$keyC][$keyL][$keyD][$field] = $aTmp2[2].'-'.$aTmp2[1].'-'.$aTmp2[0].' '.$aTmp[1];
			}
			if(strlen($dat[$keyC][$keyL][$keyD][$field]) == 10){
				$aTmp = explode('.', $dat[$keyC][$keyL][$keyD][$field]);
				$dat[$keyC][$keyL][$keyD][$field] = $aTmp[2].'-'.$aTmp[1].'-'.$aTmp[0];
			}
		}
	}
	
	// Dates
	if(isset($aFields['dates'])){
		foreach($aFields['dates'] as $field){
			if($dat[$keyC][$keyL][$keyD][$field] != ''){
				$aTmp = explode('.', $dat[$keyC][$keyL][$keyD][$field]);
				$dat[$keyC][$keyL][$keyD][$field] = $aTmp[2].'-'.$aTmp[1].'-'.$aTmp[0];
			}else{
				$dat[$keyC][$keyL][$keyD][$field] = '0000-00-00';
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
	
	return $dat;
}





?>