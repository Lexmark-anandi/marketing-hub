<?php
function setValuesCheck($aArgs=array()){
	global $CONFIG, $TEXT; 

	if(!isset($aArgs['data'])) $aArgs['data'] = array();
	if(!isset($aArgs['fields'])) $aArgs['fields'] = array();
	if(!isset($aArgs['id_count'])) $aArgs['id_count'] = 0;
	if(!isset($aArgs['id_lang'])) $aArgs['id_lang'] = 0;
	if(!isset($aArgs['id_dev'])) $aArgs['id_dev'] = 0;

	$res = array();

//	// Mandatory
//	if(isset($aArgs['fields']['mandatory'])){
//		foreach($aArgs['fields']['mandatory'] as $field){
//			if($aArgs['data'][$aArgs['id_count']][$aArgs['id_lang']][$aArgs['id_dev']][$field] != ''){
//				$res[$field] = 'OK';
//			}else{
//				$res[$field] = $TEXT['fieldRequired'];
//			}
//		}
//	}
// 
////	// Floats
////	if(isset($aArgs['fields']['floats'])){
////		foreach($aArgs['fields']['floats'] as $field){
////			if($aArgs['data'][$aArgs['id_count']][$aArgs['id_lang']][$aArgs['id_dev']][$field] != ''){
////				$aArgs['data'][$aArgs['id_count']][$aArgs['id_lang']][$aArgs['id_dev']][$field] = str_replace(',','.',str_replace('.','',$aArgs['data'][$aArgs['id_count']][$aArgs['id_lang']][$aArgs['id_dev']][$field]));
////			}else{
////				$aArgs['data'][$aArgs['id_count']][$aArgs['id_lang']][$aArgs['id_dev']][$field] = '';
////			}
////		}
////	}
//	
//	// Timestamps
//	if(isset($aArgs['fields']['timestamps'])){
//		foreach($aArgs['fields']['timestamps'] as $field){
//			if(strlen($aArgs['data'][$aArgs['id_count']][$aArgs['id_lang']][$aArgs['id_dev']][$field]) == 19){
//				$pattern = '/^(19|20)[0-9]{2}[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])[ ](0[1-9]|1[012]|2[01234])[:]([0-5][0-9])[:]([0-5][0-9])$/';
//				if(preg_match($pattern, $aArgs['data'][$aArgs['id_count']][$aArgs['id_lang']][$aArgs['id_dev']][$field])){
//					$res[$field] = 'OK';
//				}else{
//					$res[$field] = $TEXT['fieldCheckDatetime'] . ' ' . $TEXT['fieldFormatText'];
//				}
//			}else if(strlen($aArgs['data'][$aArgs['id_count']][$aArgs['id_lang']][$aArgs['id_dev']][$field]) == 10){
//				$pattern = '/^(19|20)[0-9]{2}[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/';
//				if(preg_match($pattern, $aArgs['data'][$aArgs['id_count']][$aArgs['id_lang']][$aArgs['id_dev']][$field])){
//					$res[$field] = 'OK';
//				}else{
//					$res[$field] = $TEXT['fieldCheckDate'] . ' ' . $TEXT['fieldFormatText'];
//				}
//			}else{
//				$res[$field] = $TEXT['fieldCheckDate'] . ' ' . $TEXT['fieldFormatText'];
//			}
//		}
//	}
//	
//	// Dates
//	if(isset($aArgs['fields']['dates'])){
//		foreach($aArgs['fields']['dates'] as $field){
//			$pattern = '/^(19|20)[0-9]{2}[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/';
//			if(preg_match($pattern, $aArgs['data'][$aArgs['id_count']][$aArgs['id_lang']][$aArgs['id_dev']][$field])){
//				$res[$field] = 'OK';
//			}else{
//				$res[$field] = $TEXT['fieldCheckDate'] . ' ' . $TEXT['fieldFormatText'];
//			}
//		}
//	}
//	
////	// Field to Text
////	if(isset($aArgs['fields']['field2Text'])){
////		foreach($aArgs['fields']['field2Text'] as $field){
////			$aArgs['data'][$field . 'T'] = $TEXT[$field . $aArgs['data'][$field]];
////		}
////	}
////
////	// yes / no Text
////	if(isset($aArgs['fields']['yesNo2Text'])){
////		foreach($aArgs['fields']['yesNo2Text'] as $field){
////			$aArgs['data'][$field."T"] = $TEXT['check'.$aArgs['data'][$field]];
////		}
////	}
////	
////	// Checkbox to Radio
////	if(isset($aArgs['fields']['check2Radio'])){
////		foreach($aArgs['fields']['check2Radio'] as $field){
////			if($aArgs['id_count'] == 0 && $aArgs['id_lang'] == 0 && $aArgs['id_dev'] == 0){
////				if($aArgs['data'][$field] == "") $aArgs['data'][$field] = $aArgs['data'][$field.'_full'];
////			}else{
////				if($aArgs['data'][$field] == "") $aArgs['data'][$field] = 0;
////			}
////		}
////	}
	
	return $res;
}





?>