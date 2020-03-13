<?php
// parsing variables for join tables

//
//
//$var_subpart = 'lalala';
//$xx = 2;
//$xxx['y']['yy'] = 3;
//$varSQL['modul'] = 'lala';
//$CONFIG['page']['moduls2'][$varSQL['modul']]['activeCountry'] = 'HUHU';
//$CONFIG['page']['moduls3z'][$varSQL['modul']]['activeCountry'] = 'HUHUHUaaHUUU';
//$CONFIG['page']['moduls']['activeCountry'][$var_subpart] = 'HUHAAAA';
//$json = '{"conditions":{"type":"AND","column":"_prodtypes_uni.id_count","op":"=","bind_param":"count","bind_type":"INT","bind_value":"$CONFIG[\'page\'][\'moduls\'][$varSQL[\'modul\']][\'activeCountry\']"}}';
//$lalala = 'HAHAHAHo';
//$aa = json_decode($json, true);
//
//
//
//
//$vname = $aa['conditions']['bind_value'];
//$vname2 = '$CONFIG[\'page\'][\'moduls\'][$varSQL[\'modul\']][\'activeCountry\'][$xx[\'aaa\'][$yy[\'yyyyy\']]]';
//$vname = '$CONFIG[\'page\'][\'moduls\' . $xx][$varSQL[\'modul\']][\'activeCountry\']';
//$vname = '$CONFIG[\'page\'][\'moduls\' . $xxx[\'y\'][\'yy\'] . \'z\'][$varSQL[\'modul\']][\'activeCountry\']';
//$vname1 = '$CONFIG[\'page\'][\'moduls\'][\'activeCountry\'][$var_subpart]';
//$vname1 = '$varSQL[\'modul\']';
//$vname1 = '$lalala';
//$vname1 = '$var_subpart';
//

//$res = parseVariableValue(parseVariableName($vname));
//var_dump($res);




function parseVariableName($var_string){
	global $CONFIG;
	
	if($var_string != ''){
		$var_search = "/(.+)(\[.+\])?$/siU";
		preg_match($var_search, $var_string, $aVarResult);
		
		$var_num_brackets = 0;
		$var_substring = '';
		$aVarArray[$aVarResult[1]] = array();
		if(isset($aVarResult[2])){
			$aVarChars = str_split($aVarResult[2]);
			
			foreach($aVarChars as $var_char){
				$var_num_brackets_old = $var_num_brackets;
				if($var_char == '[') $var_num_brackets++;
				if($var_char == ']') $var_num_brackets--;
				$var_substring .= $var_char;
				
				if($var_num_brackets == 0 && $var_num_brackets_old != $var_num_brackets){
					if(substr_count($var_substring, '$') > 0 && substr_count($var_substring, '.') > 0){
						$var_substring = trim(substr($var_substring, 1, (strlen($var_substring) - 2)));
						$aVarConcated = explode('.', $var_substring);
						$var_substring_concated = '';
						foreach($aVarConcated as $var_concated){
							if(substr_count($var_concated, '$') > 0){
								$var_substring_concated .= parseVariableValue(parseVariableName(trim($var_concated)));
							}else{
								$var_substring_concated .= trim($var_concated, '\' ');
							}
						}
						array_push($aVarArray[$aVarResult[1]], trim($var_substring_concated));
						$var_substring = '';
					}else if(substr_count($var_substring, '$') > 0){
						array_push($aVarArray[$aVarResult[1]], parseVariableName(trim(substr($var_substring, 1, (strlen($var_substring) - 2)))));
						$var_substring = '';
					}else{
						array_push($aVarArray[$aVarResult[1]], trim(substr($var_substring, 1, (strlen($var_substring) - 2)), '\''));
						$var_substring = '';
					}
				}
			}
		}
		
		return $aVarArray;
	}
}




function parseVariableValue($aVarVariable){
	foreach($aVarVariable as $aVarSubpart){
		if(is_array($aVarSubpart)){
			$var_name = str_replace('$','', key($aVarVariable));
			global ${$var_name};
			$var_value = ${$var_name};
			
			foreach($aVarSubpart as $var_subpart){
				if(is_array($var_subpart)){
					$var_value = $var_value[parseVariableValue($var_subpart)];
				}else{
					$var_value = $var_value[$var_subpart];
				}
			}
		}else{
		}
	}
	
	return $var_value;
}


?>