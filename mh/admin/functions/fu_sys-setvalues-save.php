<?php
function setValuesSave($aArgs=array()){
	global $CONFIG, $TEXT; 

	if(!isset($aArgs['data'])) $aArgs['data'] = array();
	if(!isset($aArgs['fields'])) $aArgs['fields'] = array();
	if(!isset($aArgs['id_count'])) $aArgs['id_count'] = $CONFIG['settings']['selectCountry'];
	if(!isset($aArgs['id_lang'])) $aArgs['id_lang'] = $CONFIG['settings']['selectLanguage'];
	if(!isset($aArgs['id_dev'])) $aArgs['id_dev'] = $CONFIG['settings']['selectDevice'];
	if(!isset($aArgs['usesystem'])) $aArgs['usesystem'] = 1;
	
	$aCountry = ($aArgs['usesystem'] == 1) ? $CONFIG['user']['syscountries'][$CONFIG['activeSettings']['systemCountry']] : $CONFIG['user']['countries'][$aArgs['id_count']];
	

	// Number
	if(isset($aArgs['fields']['number'])){
		foreach($aArgs['fields']['number'] as $field => $aFormat){
			if(!isset($aFormat['decimal'])) $aFormat['decimal'] = 0;
			if(!isset($aFormat['nul'])) $aFormat['nul'] = '';
			if(!isset($aFormat['sep_decimal'])) $aFormat['sep_decimal'] = (isset($aCountry['sep_decimal'])) ? $aCountry['sep_decimal'] : $CONFIG['system']['sep_decimal'];
			if(!isset($aFormat['sep_thousand'])) $aFormat['sep_thousand'] = (isset($aCountry['sep_decimal'])) ? $aCountry['sep_thousand'] : $CONFIG['system']['sep_thousand'];
			
			if($aArgs['data'][$field] != ''){
				$aArgs['data'][$field] = str_replace($aFormat['sep_thousand'], '', $aArgs['data'][$field]);
				$aArgs['data'][$field] = str_replace($aFormat['sep_decimal'], '.', $aArgs['data'][$field]);
			}else{
				$aArgs['data'][$field] = $aFormat['nul'];
			}
		}
	}

	// Currency
	if(isset($aArgs['fields']['currency'])){
		foreach($aArgs['fields']['currency'] as $field => $aFormat){
		}
	}
	
	// Date
	if(isset($aArgs['fields']['date'])){ 
		foreach($aArgs['fields']['date'] as $field => $aFormat){
			if($aArgs['data'][$field] != ''){
				if(!isset($aFormat['date_code'])) $aFormat['date_code'] = (isset($aCountry['date_code'])) ? $aCountry['date_code'] : $CONFIG['system']['date_code'];
				if(!isset($aFormat['date_format'])) $aFormat['date_format'] = (isset($aCountry['date_format'])) ? $aCountry['date_format'] : $CONFIG['system']['date_format'];
				if(strlen($aArgs['data'][$field]) == 10) $aFormat['date_code'] = str_replace('y', 'Y', $aFormat['date_code']);
				
				$objDate = DateTime::createFromFormat($aFormat['date_code'], $aArgs['data'][$field]);
				$y = $objDate->format('Y');
				if($y < 1000) $objDate = DateTime::createFromFormat(strtolower($aFormat['date_code']), $aArgs['data'][$field]);
				$aArgs['data'][$field] = $objDate->format('Y-m-d');
			}else{
				$aArgs['data'][$field] = '0000-00-00';
			}
		}
	}
	
	// Date and Time
	if(isset($aArgs['fields']['datetime'])){
		foreach($aArgs['fields']['datetime'] as $field => $aFormat){
		}
	}
	
	// Value to Text
	if(isset($aArgs['fields']['value2text'])){
		foreach($aArgs['fields']['value2text'] as $field => $aFormat){
		}
	}

	// Boolean to Text
	if(isset($aArgs['fields']['bool2text'])){
		foreach($aArgs['fields']['bool2text'] as $field => $aFormat){
		}
	}

	// Empty
	if(isset($aArgs['fields']['placeholder'])){
		foreach($aArgs['fields']['placeholder'] as $field => $aFormat){
		}
	}
	
	// Encrypt
	if(isset($aArgs['fields']['cryption'])){
		foreach($aArgs['fields']['cryption'] as $field => $aFormat){
			$aCrypt = array();
			$aCrypt['data'][$field] = $aArgs['data'][$field];
			$aCrypt['fields']['cryption'] = array($field => $aFormat);
			$aArgs['data'][$field] = ($aArgs['data'][$field] != '') ? valuesEncrypt($aCrypt) : '';
		}
	}

//	
//	// Dates
//	if(isset($aArgs['fields']['dates'])){
//		foreach($aArgs['fields']['dates'] as $field){
//			if($aArgs['data'][$field] != ''){
//				$aTmp = explode('.', $aArgs['data'][$field]);
//				$aArgs['data'][$field] = $aTmp[2].'-'.$aTmp[1].'-'.$aTmp[0];
//			}else{
//				$aArgs['data'][$field] = '0000-00-00';
//			}
//		}
//	}
//	// Floats
//	if(isset($aArgs['fields']['floats'])){
//		foreach($aArgs['fields']['floats'] as $field){
//			if($aArgs['data'][$field] != ''){
//				$aArgs['data'][$field] = str_replace(',','.',str_replace('.','',$aArgs['data'][$field]));
//			}else{
//				$aArgs['data'][$field] = '';
//			}
//		}
//	}
//	
//	// Timestamps
//	if(isset($aArgs['fields']['timestamps'])){
//		foreach($aArgs['fields']['timestamps'] as $field){
//			if(strlen($aArgs['data'][$field]) == 19){
//				$aTmp = explode(' ', $aArgs['data'][$field]);
//				$aTmp2 = explode('.', $aTmp[0]);
//				$aArgs['data'][$field] = $aTmp2[2].'-'.$aTmp2[1].'-'.$aTmp2[0].' '.$aTmp[1];
//			}
//			if(strlen($aArgs['data'][$field]) == 10){
//				$aTmp = explode('.', $aArgs['data'][$field]);
//				$aArgs['data'][$field] = $aTmp[2].'-'.$aTmp[1].'-'.$aTmp[0];
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
	
	return $aArgs['data'];
}





?>