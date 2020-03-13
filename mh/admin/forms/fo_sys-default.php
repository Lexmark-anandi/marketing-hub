<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$functionPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFormsAdmin'];
$functionFile = 'fo-' . $CONFIG['aModul']['modul_name'] . '.php'; 

if(file_exists($functionPath . $functionFile)){
	include_once($functionPath . $functionFile);
	
}else{



	$FORM_TOP_LEFT = ''; 
	$FORM_BOTTOM_LEFT = '';
	$FORM_TOP_RIGHT = '';
	$FORM_BOTTOM_RIGHT = '';
	$f_fieldshidden = '';





	//var_dump($CONFIG['aModul']);









	######################################################################
	foreach($CONFIG['aModul']['form'] as $aFieldsets){
		if(count($aFieldsets['fields']) > 0){
			$FORM_TOP_LEFT .= '<div class="fieldset" data-formtab="' . $aFieldsets['fieldset'] . '">';
	
			foreach($aFieldsets['fields'] as $aField){
				$aObj = array();
				
				##################################################################
				// Handle uploadfield with multiple upload for editing single file
				if($aField['type'] == 'file' && $CONFIG['page']['id_data'] != 0 && isset($CONFIG['aModul']['addoptions']['insertLoopField']) && $CONFIG['aModul']['addoptions']['insertLoopField'] != ''){
					$aField['multiple'] = 'false';
					$aField['default'] = '';
					$aField['checksync'] = $aField['checksyncorg'];
				}
				##################################################################
				
				$aObj['multiple'] = ($aField['multiple'] == 'true') ? true : false;  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
				
				
				
				
				$aObj['readonly'] = ($aField['readonly'] == 'true') ? true : false;
				$aObj['class_space'] = ($aField['class_space'] == 'true') ? true : false;
				$aObj['checkdirect'] = ($aField['checkdirect'] == 'true') ? true : false;
				
				$aObj['f_id'] = $aField['name'] . '_' . $CONFIG['page']['modulpath']; 
				$aObj['f_name'] = ($aObj['multiple'] == true && $aField['type'] != 'file') ? $aField['name'] . '[]' : $aField['name']; 
				$aObj['f_label'] = (isset($TEXT[$aField['label']])) ? $TEXT[$aField['label']] : $aField['label']; 
				$aObj['f_default'] = $aField['default'];
				$aObj['f_readonly'] = ($aObj['readonly'] == true) ? ' readonly' : '';
				if(!in_array($aField['specifications'][0], $CONFIG['system']['aFieldsAllowedSpecs']) || $aField['specifications'][2] == 0) $aObj['f_readonly'] = ' readonly'; 
				$aObj['f_multiple'] = ($aObj['multiple'] == true) ? ' multiple' : '';
				
				$aObj['f_classes_row'] = $aField['classes_row'];
				if($aObj['class_space'] == true) $aObj['f_classes_row'] .= ' formRowSpace';
				$aObj['f_classes_field'] = $aField['classes_field'];
				if($aObj['checkdirect'] == true) $aObj['f_classes_field'] .= ' checkDirect';
				
				$aObj['f_checkfunction'] = $aField['checkfunction'];
				$aObj['f_checkmessage'] = $aField['checkmessage'];
				
				$aObj['f_data_attributes'] = '';
				foreach($aField['data_attributes'] as $att => $val) $aObj['f_data_attributes'] .= ' data-' . $att . '="' . $val . '"';
				if($aField['checksync'] != '') $aObj['f_data_attributes'] .= ' data-checksync="' . $aField['checksync'] . '"';
				if($aField['specifications'][2] != 0 && $aField['specifications'][2] != 9) $aObj['f_data_attributes'] .= ' data-editspecs="' . $aField['specifications'][2] . '"';
				
				$aObj['f_js_functions'] = '';
				foreach($aField['js_functions'] as $ev => $aFunc){
					$aObj['f_js_functions'] .= ' on' . $ev . '="callFunction(\'' . implode(';', $aFunc) . '\', this)"';
				}
				
				$aObj['f_config_wysiwyg'] = $aField['config_wysiwyg'];
				$aObj['f_options'] = $aField['options'];
				if(!isset($aObj['f_options']['rowwidth'])) $aObj['f_options']['rowwidth'] = '';
				if(!isset($aObj['f_options']['labelposition'])) $aObj['f_options']['labelposition'] = '';
				
				$f_selectoptions = $aField['selectoptions'];
				if(!isset($f_selectoptions['dataUrl'])) $f_selectoptions['dataUrl'] = 'fo_sys-default-select.php';
				if(!isset($f_selectoptions['dataFunction'])) $f_selectoptions['dataFunction'] = 'buildSelection';
				if(!isset($f_selectoptions['type'])) $f_selectoptions['type'] = 'raw';
				if(!isset($f_selectoptions['connection'])) $f_selectoptions['connection'] = 0;
				if(!isset($f_selectoptions['table'])) $f_selectoptions['table'] = (isset($CONFIG['aModul']['colmodel']['i_' . $aField['id_field']]['t_table'])) ? $CONFIG['aModul']['colmodel']['i_' . $aField['id_field']]['t_table'] : '';
				if(!isset($f_selectoptions['suffix'])) $f_selectoptions['suffix'] = (isset($CONFIG['aModul']['colmodel']['i_' . $aField['id_field']]['t_suffix'])) ? $CONFIG['aModul']['colmodel']['i_' . $aField['id_field']]['t_suffix'] : 1;
				if(!isset($f_selectoptions['primarykey'])) $f_selectoptions['primarykey'] = '';
				if(!isset($f_selectoptions['fields'])) $f_selectoptions['fields'] = (isset($CONFIG['aModul']['colmodel']['i_' . $aField['id_field']]['t_colname'])) ? array($CONFIG['aModul']['colmodel']['i_' . $aField['id_field']]['t_colname']) : array();
				if(!isset($f_selectoptions['order'])) $f_selectoptions['order'] = (isset($CONFIG['aModul']['colmodel']['i_' . $aField['id_field']]['t_colname'])) ? array(array($CONFIG['aModul']['colmodel']['i_' . $aField['id_field']]['t_colname'] => 'ASC')) : array();
				//if(!isset($f_selectoptions['translate'])) $f_selectoptions['translate'] = array($CONFIG['aModul']['colmodel']['i_' . $aField['id_field']]['t_colname']);
				if(!isset($f_selectoptions['sorting'])) $f_selectoptions['sorting'] = false;
				if(!isset($f_selectoptions['output'])) $f_selectoptions['output'] = '';
				$f_selectoptions['obj'] = $aObj;
				
				if($aField['parentassign'] != 0 && $CONFIG['page']['id_mod_parent'] > 0) $aField['type'] = 'hidden';

				$f_field = '';
				switch($aField['type']){
					case 'text':
						$f_field = '<input type="text" name="' . $aObj['f_name'] . '" id="' . $aObj['f_id'] . '" class="textfield ' . $aObj['f_classes_field'] . '" value="' . $aObj['f_default'] . '" data-checkfunction="' . $aObj['f_checkfunction'] . '" data-checkmessage="' . $aObj['f_checkmessage'] . '"' . $aObj['f_js_functions'] . $aObj['f_data_attributes'] . $aObj['f_readonly'] . '>';
						break;
	
					case 'textarea':
						$f_field = '<textarea name="' . $aObj['f_name'] . '" id="' . $aObj['f_id'] . '" class="textfield ' . $aObj['f_classes_field'] . '" data-checkfunction="' . $aObj['f_checkfunction'] . '" data-checkmessage="' . $aObj['f_checkmessage'] . '"' . $aObj['f_js_functions'] . $aObj['f_data_attributes'] . $aObj['f_readonly'] . '>' . $aObj['f_default'] . '</textarea>';
						break;
	
					case 'wysiwyg':
						$f_field = '<textarea name="' . $aObj['f_name'] . '" id="' . $aObj['f_id'] . '" class="textfield wysiwyg h500 ' . $aObj['f_classes_field'] . '" data-checkfunction="' . $aObj['f_checkfunction'] . '" data-checkmessage="' . $aObj['f_checkmessage'] . '"' . $aObj['f_js_functions'] . $aObj['f_data_attributes'] . $aObj['f_readonly'] . ' data-config="' . htmlspecialchars(json_encode($aObj['f_config_wysiwyg']), ENT_QUOTES) . '">' . $aObj['f_default'] . '</textarea>';
						break;
	
					case 'select':
						$f_selectoptions['type'] = 'select';
						include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFormsAdmin'] . $f_selectoptions['dataUrl']);
						$options = $f_selectoptions['dataFunction']($f_selectoptions);

						$f_field = '<select name="' . $aObj['f_name'] . '" id="' . $aObj['f_id'] . '" class="textfield ' . $aObj['f_classes_field'] . '" data-checkfunction="' . $aObj['f_checkfunction'] . '" data-checkmessage="' . $aObj['f_checkmessage'] . '"' . $aObj['f_js_functions'] . $aObj['f_data_attributes'] . $aObj['f_readonly'] . $aObj['f_multiple'] . '>';
						$f_field .= '<option value="0"></option>';
						$f_field .= $options;
						$f_field .= '</select>';

						break;
	
					case 'radio':
						$f_selectoptions['type'] = 'radio';
						include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFormsAdmin'] . $f_selectoptions['dataUrl']);
						$f_field = $f_selectoptions['dataFunction']($f_selectoptions);
						break;
	
					case 'checkbox':
						$f_field = '<input type="checkbox" name="' . $aObj['f_name'] . '" id="' . $aObj['f_id'] . '" class="checkfield ' . $aObj['f_classes_field'] . '" value="' . $aObj['f_default'] . '" data-checkfunction="' . $aObj['f_checkfunction'] . '" data-checkmessage="' . $aObj['f_checkmessage'] . '"' . $aObj['f_js_functions'] . $aObj['f_data_attributes'] . $aObj['f_readonly'] . '>';
						
						if($aObj['multiple'] == true){
							$f_selectoptions['type'] = 'checkbox';
							include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFormsAdmin'] . $f_selectoptions['dataUrl']);
							$f_field = $f_selectoptions['dataFunction']($f_selectoptions);
						}
						break;
	
					case 'boolean':
						$f_selectoptions['type'] = 'boolean';
						$f_selectoptions['table'] = 'system_boolean';
						$f_selectoptions['suffix'] = 0;
						$f_selectoptions['primarykey'] = '';
						$f_selectoptions['fields'] = array('boolvalue');
						$f_selectoptions['order'] = array(array('rank' => 'ASC'));
						$f_selectoptions['translate'] = array('boolvalue');
						$f_selectoptions['sorting'] = false;

						include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFormsAdmin'] . $f_selectoptions['dataUrl']);
						$f_field = $f_selectoptions['dataFunction']($f_selectoptions);
						break;
	
					case 'file':
						$f_field = array();

						$aArgsLV = array();
						$aArgsLV['type'] = 'all';
						$aLocalVersions = localVariationsBuild($aArgsLV);
						
						foreach($aLocalVersions as $aVersion){
							$id_count = strval($aVersion[0]);
							$id_lang = strval($aVersion[1]);
							$id_dev = strval($aVersion[2]);
							
							$id_var = '';
							switch($aField['checksync']){
								case 'all':
									$id_var .= 'x_x_x';
									break;
									
								case 'country':
									$id_var .= 'x_' . $id_lang . '_' . $id_dev;
									break;
									
								case 'language':
									$id_var .= $id_count . '_x_' . $id_dev;
									break;
									
								case 'device':
									$id_var .= $id_count . '_' . $id_lang . '_x';
									break;
									
								case 'countrylanguage':
									$id_var .= 'x_x_' . $id_dev;
									break;
									
								case 'countrydevice':
									$id_var .= 'x_' . $id_lang . '_x';
									break;
									
								case 'languagedevice':
									$id_var .= $id_count . '_x_x';
									break;
									
								default:
									$id_var .= $id_count . '_' . $id_lang . '_' . $id_dev;
							}
							
							$labelUpload = ($aObj['multiple'] == true) ? $TEXT['selectFiles'] : $TEXT['selectFile'];

							$fieldTmp = '<div data-name="' . $id_var . '_' . $aObj['f_name'] . 'F" class=""></div>';
							$fieldTmp .= '<div class="textfield textfieldUpload"><input type="button" class="formButton formButtonUpload" value="' . $labelUpload . '" /></div>';
							$fieldTmp .= '<input type="file" name="' . $id_var . '_' . $aObj['f_name'] . '" id="' . $id_var . '_' . $aObj['f_id'] . '" class="textfield fileupload ' . $aObj['f_classes_field'] . '" value="' . $aObj['f_default'] . '" data-checkfunction="' . $aObj['f_checkfunction'] . '" data-checkmessage="' . $aObj['f_checkmessage'] . '" data-fieldname="' . $aObj['f_name'] . '" ' . $aObj['f_js_functions'] . $aObj['f_data_attributes'] . $aObj['f_readonly'] . $aObj['f_multiple'] . '>';
							//$f_field[$id_var . '##' . str_replace('[]', '', $aObj['f_name'])] = $fieldTmp;
							$f_field[$id_var . '##' . $aObj['f_name']] = $fieldTmp;
						}
						break;
	
					case 'hidden':
						$f_fieldshidden .= '<input type="hidden" name="' . $aObj['f_name'] . '" id="' . $aObj['f_id'] . '" class="textfield ' . $aObj['f_classes_field'] . '" value="' . $aObj['f_default'] . '" data-checkfunction="' . $aObj['f_checkfunction'] . '" data-checkmessage="' . $aObj['f_checkmessage'] . '"' . $aObj['f_js_functions'] . $aObj['f_data_attributes'] . $aObj['f_readonly'] . '>';
						break;
	
					case 'password':
						$f_field = '<input type="password" name="' . $aObj['f_name'] . '" id="' . $aObj['f_id'] . '" class="textfield ' . $aObj['f_classes_field'] . '" value="' . $aObj['f_default'] . '" data-checkfunction="' . $aObj['f_checkfunction'] . '" data-checkmessage="' . $aObj['f_checkmessage'] . '"' . $aObj['f_js_functions'] . $aObj['f_data_attributes'] . $aObj['f_readonly'] . '>';
						break;
				}
				
				if($aField['wrapper'] != '') $f_field = str_replace('##field##', $f_field, $aField['wrapper']);
				
				if(is_array($f_field)){
					if(count($f_field) > 0){
						foreach($f_field as $key => $f){
							$aKey = explode('##', $key);
							if($aObj['f_options']['rowwidth'] == 'full'){
								$FORM_TOP_LEFT .= '<div class="formRow formRowHidden ' . $aObj['f_classes_row'] . '" data-fieldvariation="' . $key . '" data-fieldname="' . $aKey[1] . '">';
								if($aObj['f_options']['labelposition'] == 'top'){
									$FORM_TOP_LEFT .= '<div class="formLabel formLabelFullwidth">';
									$FORM_TOP_LEFT .= '<label for="' . $aObj['f_id'] . '">' . $aObj['f_label'] . '</label>';
									$FORM_TOP_LEFT .= '</div>';
								}
								$FORM_TOP_LEFT .= '<div class="formField formFieldFullwidth">';
								$FORM_TOP_LEFT .= $f;
								$FORM_TOP_LEFT .= '</div>';
								$FORM_TOP_LEFT .= '</div>';
							}else{
								$FORM_TOP_LEFT .= '<div class="formRow formRowHidden ' . $aObj['f_classes_row'] . '" data-fieldvariation="' . $key . '"data-fieldname="' . $aKey[1] . '">';
								$FORM_TOP_LEFT .= '<div class="formLabel">';
								$FORM_TOP_LEFT .= '<label for="' . $aObj['f_id'] . '">' . $aObj['f_label'] . '</label>';
								$FORM_TOP_LEFT .= '</div>';
								$FORM_TOP_LEFT .= '<div class="formField">';
								$FORM_TOP_LEFT .= $f;
								$FORM_TOP_LEFT .= '</div>';
								$FORM_TOP_LEFT .= '</div>';
							}
						}
					}
				}else{
					if($f_field != ''){
						if($aObj['f_options']['rowwidth'] == 'full'){
							$FORM_TOP_LEFT .= '<div class="formRow ' . $aObj['f_classes_row'] . '">';
							if($aObj['f_options']['labelposition'] == 'top'){
								$FORM_TOP_LEFT .= '<div class="formLabel formLabelFullwidth">';
								$FORM_TOP_LEFT .= '<label for="' . $aObj['f_id'] . '">' . $aObj['f_label'] . '</label>';
								$FORM_TOP_LEFT .= '</div>';
							}
							$FORM_TOP_LEFT .= '<div class="formField formFieldFullwidth">';
							$FORM_TOP_LEFT .= $f_field;
							$FORM_TOP_LEFT .= '</div>';
							$FORM_TOP_LEFT .= '</div>';
						}else{
							$FORM_TOP_LEFT .= '<div class="formRow ' . $aObj['f_classes_row'] . '">';
							$FORM_TOP_LEFT .= '<div class="formLabel">';
							$FORM_TOP_LEFT .= '<label for="' . $aObj['f_id'] . '">' . $aObj['f_label'] . '</label>';
							$FORM_TOP_LEFT .= '</div>';
							$FORM_TOP_LEFT .= '<div class="formField">';
							$FORM_TOP_LEFT .= $f_field;
							$FORM_TOP_LEFT .= '</div>';
							$FORM_TOP_LEFT .= '</div>';
						}
					}
				}
				
				################
				
				if(substr($aField['type'], 0, 8) == 'getFunc_'){
					if(file_exists($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFormsAdmin'] . 'fo-' . $CONFIG['page']['modul_name'] . '-select.php')){
						include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFormsAdmin'] . 'fo-' . $CONFIG['page']['modul_name'] . '-select.php');
						
						$func = str_replace('getFunc_', '', $aField['type']);
						$FORM_TOP_LEFT .= $func($f_selectoptions);
					}
				}
			}
			
			$FORM_TOP_LEFT .= '</div>';
		}
	}
	
















	
	
	######################################################################


######################################################################
$FORM_BOTTOM_LEFT = '';
######################################################################




######################################################################
$FORM_TOP_RIGHT = '';
######################################################################
if(isset($CONFIG['user']['childmoduls'][$CONFIG['page']['id_mod']])){
	foreach($CONFIG['user']['childmoduls'][$CONFIG['page']['id_mod']] as $kModuls => $aModuls){ 
		$modulpath = $CONFIG['page']['modulpath'] . $CONFIG['system']['delimiterPathAttr'] . $CONFIG['page']['id_mod'] . $CONFIG['system']['delimiterPathAttr'] . $aModuls['id_mod'];
	
		#############################################################################
		// translate, sort and create modul filter
		$listCountries = '';
		$listLanguages = '';
		$listDevices = '';
		if($CONFIG['system']['useMultiple'] == 1){
			$filtertype = ($aModuls['specifications'][9] == 9) ? 'sys' : '';
	
			#######
			if($aModuls['specifications'][6] == 9){
				$aRes = array();
				foreach($CONFIG['user'][$filtertype . 'countries'] as $id => $aVal){
					$text = (isset($TEXT[$aVal['country']])) ? $TEXT[$aVal['country']] : $aVal['country'];
					$aRes[$aVal['id_countid']] = $text;
				}
				asort($aRes, SORT_NATURAL | SORT_FLAG_CASE);
				
				if(array_key_exists(0, $CONFIG['user'][$filtertype . 'countries'])){
					$text = (isset($TEXT[$CONFIG['user'][$filtertype . 'countries'][0]['country']])) ? $TEXT[$CONFIG['user'][$filtertype . 'countries'][0]['country']] : $CONFIG['user'][$filtertype . 'countries'][0]['country'];
					$sel = '';
					if($aModuls['activeSettings']['selectCountry'] == 0) $sel = 'selected';
					$listCountries .= '<option value="0" ' . $sel . '>' . $text . '</option>';
				}
				
				foreach($aRes as $id => $text){
					$sel = '';
					if($id > 0){
						if($id == $aModuls['activeSettings']['selectCountry']) $sel = 'selected';
						$listCountries .= '<option value="' . $id . '" ' . $sel . '>' . $text . '</option>';
					}
				}
			}
				
			#######
			if($aModuls['specifications'][7] == 9){
				$aRes = array();
				foreach($CONFIG['user'][$filtertype . 'countries'][$aModuls['activeSettings']['selectCountry']]['languages'] as $id){
					$text = (isset($TEXT[$CONFIG['user'][$filtertype . 'languages'][$id]['language']])) ? $TEXT[$CONFIG['user'][$filtertype . 'languages'][$id]['language']] : $CONFIG['user'][$filtertype . 'languages'][$id]['language'];
					$aRes[$id] = $text;
				}
				asort($aRes, SORT_NATURAL | SORT_FLAG_CASE);
				
				if(in_array(0, $CONFIG['user'][$filtertype . 'countries'][$aModuls['activeSettings']['selectCountry']]['languages'])){
					$text = (isset($TEXT[$CONFIG['user'][$filtertype . 'languages'][0]['language']])) ? $TEXT[$CONFIG['user'][$filtertype . 'languages'][0]['language']] : $CONFIG['user'][$filtertype . 'languages'][0]['language'];
					$sel = '';
					if($aModuls['activeSettings']['selectLanguage'] == 0) $sel = 'selected';
					$listLanguages .= '<option value="0" ' . $sel . '>' . $text . '</option>';
				}
				
				foreach($aRes as $id => $text){
					$sel = '';
					if($id > 0){
						if($id == $aModuls['activeSettings']['selectLanguage']) $sel = 'selected';
						$listLanguages .= '<option value="' . $id . '" ' . $sel . '>' . $text . '</option>';
					}
				}
			}
			
			#######
			if($aModuls['specifications'][8] == 9){
				$aRes = array();
				foreach($CONFIG['user'][$filtertype . 'devices'] as $id => $aVal){
					$text = (isset($TEXT[$aVal['device']])) ? $TEXT[$aVal['device']] : $aVal['device'];
					$aRes[$aVal['id_devid']] = $text;
				}
				asort($aRes, SORT_NATURAL | SORT_FLAG_CASE);
				
				if(array_key_exists(0, $CONFIG['user'][$filtertype . 'devices'])){
					$text = (isset($TEXT[$CONFIG['user'][$filtertype . 'devices'][0]['device']])) ? $TEXT[$CONFIG['user'][$filtertype . 'devices'][0]['device']] : $CONFIG['user'][$filtertype . 'devices'][0]['device'];
					$sel = '';
					if($aModuls['activeSettings']['selectDevice'] == 0) $sel = 'selected';
					$listDevices .= '<option value="0" ' . $sel . '>' . $text . '</option>';
				}
				
				foreach($aRes as $id => $text){
					$sel = '';
					if($id > 0){
						if($id == $aModuls['activeSettings']['selectDevice']) $sel = 'selected';
						$listDevices .= '<option value="' . $id . '" ' . $sel . '>' . $text . '</option>';
					}
				}
			}
		}
	
			
	
	
	



		#############################################################################
		// Modul
		$FORM_TOP_RIGHT .= '<div id="modul_' . $modulpath . '" class="childmodul hidden ' . $aModuls['modul_class'] . '" data-modulpath="' . $modulpath . '">';
		#############################################################################
		
		#############################################################################
		// Grid
		if($aModuls['specifications'][11] == 9){
			$FORM_TOP_RIGHT .= '<div id="grid_' . $modulpath . '" class="grid">';
//			$FORM_TOP_RIGHT .= '<div id="" class="tabModulFilter modulFilter">';
//			$FORM_TOP_RIGHT .= '<div id="" class="tabModulFilterInner">';
//			
//			if($CONFIG['system']['useMultiple'] == 1){
//				if($aModuls['specifications'][6] == 9){
//					$FORM_TOP_RIGHT .= ' <label class="formFilterLabel" for="filterModulCountry_' . $modulpath . '">' . $TEXT['filterCountry'] . '</label> <div class="wModulFilterOuter"><select class="textfield wModulFilter filterModulCountry" name="filterModulCountry" id="filterModulCountry_' . $modulpath . '">' . $listCountries . '</select></div>';
//				}
//				if($aModuls['specifications'][7] == 9){
//					$FORM_TOP_RIGHT .= ' <label class="formFilterLabel" for="filterModulLanguage_' . $modulpath . '">' . $TEXT['filterLanguage'] . '</label> <div class="wModulFilterOuter"><select class="textfield wModulFilter filterModulLanguage" name="filterModulLanguage" id="filterModulLanguage_' . $modulpath . '">' . $listLanguages . '</select></div>';
//				}
//				if($aModuls['specifications'][8] == 9){
//					$FORM_TOP_RIGHT .= ' <label class="formFilterLabel" for="filterModulDevice_' . $modulpath . '">' . $TEXT['filterDevice'] . '</label> <div class="wModulFilterOuter"><select class="textfield wModulFilter filterModulDevice" name="filterModulDevice" id="filterModulDevice_' . $modulpath . '">' . $listDevices . '</select></div>';
//				}
//			}
//			$FORM_TOP_RIGHT .= '</div>';
//	
//			$FORM_TOP_RIGHT .= '<div id="" class="tabModulFilterInnerMobile">';
//			$FORM_TOP_RIGHT .= '<div class="wModulFilterOuterMobile">DE / de</div>';
//			$FORM_TOP_RIGHT .= '<div class="modulIcon modulIconBox gridMenueFilter" title=""><i class="fa fa-pencil"></i></div>';
//			$FORM_TOP_RIGHT .= '</div>';
//			
			$FORM_TOP_RIGHT .= '<div class="tabModulFilterButtonsRight">';
			$FORM_TOP_RIGHT .= '<div class="modulIcon modulIconBox gridMenueFunctions" title=""><i class="fa fa-navicon"></i></div>';
			$FORM_TOP_RIGHT .= '<div class="modulIcon modulIconBox gridExpandAll" title=""><i class="fa fa-chevron-down"></i></div>';
			$FORM_TOP_RIGHT .= '</div>';
//			$FORM_TOP_RIGHT .= '</div>';
			
			$FORM_TOP_RIGHT .= '<table id="gridTable_' . $modulpath . '" class="gridTable"></table>';
			
			################
			// pager
			$listRows = '';
			foreach($CONFIG['system']['aGridNumRows'] as $row){
				$listRows .= '<option value="' . $row . '">' . $row . '</option>';
			}
			$FORM_TOP_RIGHT .= '<div id="gridPager_' . $modulpath . '" class="gridPager">';
			
			$FORM_TOP_RIGHT .= '<div id="gridPager_' . $modulpath . '_left" class="gridPagerInner gridPagerLeft">';
			$FORM_TOP_RIGHT .= '<div class="modulIcon pagerRefresh" title=""><i class="fa fa-refresh"></i></div>';
			$FORM_TOP_RIGHT .= '<div class="modulIcon pagerSettings" title=""><i class="fa fa-sliders"></i></div>';
			$FORM_TOP_RIGHT .= '</div>';
			
			$FORM_TOP_RIGHT .= '<div id="gridPager_' . $modulpath . '_right" class="gridPagerInner gridPagerRight"><span class="pagerRecords"></span></div>';
			
			$FORM_TOP_RIGHT .= '<div id="gridPager_' . $modulpath . '_center" class="gridPagerInner gridPagerCenter">';
			$FORM_TOP_RIGHT .= '<div class="modulIcon pagerFirstPage" title=""><i class="fa fa-fast-backward"></i></div>';
			$FORM_TOP_RIGHT .= '<div class="modulIcon pagerPrevPage" title=""><i class="fa fa-flip-horizontal fa-play"></i></div>';
			$FORM_TOP_RIGHT .= '<div class="modulIcon pagerPage" title=""><input type="text" name="pagerActPage" id="pagerActPage_' . $modulpath . '" value="" class="pagerActPage"> / <span class="pagerTotalPages"></span></div>';
			$FORM_TOP_RIGHT .= '<div class="modulIcon pagerNextPage" title=""><i class="fa fa-play"></i></div>';
			$FORM_TOP_RIGHT .= '<div class="modulIcon pagerLastPage" title=""><i class="fa fa-fast-forward"></i></div>';
			$FORM_TOP_RIGHT .= '<div class="modulIcon pagerRows" title=""><select name="pagerRows" id="pagerRows_' . $modulpath . '" class="pagerSelectRows">' . $listRows . '</select></div>';
			$FORM_TOP_RIGHT .= '</div>';
			
			$FORM_TOP_RIGHT .= '</div>';
			
			$FORM_TOP_RIGHT .= '</div>';
			################
		}
		#############################################################################
		
	
		
		#############################################################################
		// Form
		$FORM_TOP_RIGHT .= '<div id="form_' . $modulpath . '" class="form hidden">';
		$FORM_TOP_RIGHT .= '<div id="" class="tabFormFilter formFilter">';
		
		if($CONFIG['system']['useMultiple'] == 1){
			if($aModuls['specifications'][6] == 9){
				$FORM_TOP_RIGHT .= ' <label class="formFilterLabel" for="filterFormCountry_' . $modulpath . '">' . $TEXT['filterCountry'] . '</label> <div class="wFormFilterOuter"><select class="textfield wFormFilter filterFormCountry" name="filterFormCountry" id="filterFormCountry_' . $modulpath . '">' . $listCountries . '</select></div>';
			}
			if($aModuls['specifications'][7] == 9){
				$FORM_TOP_RIGHT .= ' <label class="formFilterLabel" for="filterFormLanguage_' . $modulpath . '">' . $TEXT['filterLanguage'] . '</label> <div class="wFormFilterOuter"><select class="textfield wFormFilter filterFormLanguage" name="filterFormLanguage" id="filterFormLanguage_' . $modulpath . '">' . $listLanguages . '</select></div>';
			}
			if($aModuls['specifications'][8] == 9){
				$FORM_TOP_RIGHT .= ' <label class="formFilterLabel" for="filterFormDevice_' . $modulpath . '">' . $TEXT['filterDevice'] . '</label> <div class="wFormFilterOuter"><select class="textfield wFormFilter filterFormDevice" name="filterFormDevice" id="filterFormDevice_' . $modulpath . '">' . $listDevices . '</select></div>';
			}
		}
	
		$FORM_TOP_RIGHT .= '<div class="modulIcon modulIconBox formNavButton formNavButtonPrev" title="' . $TEXT['prevRow'] . '"><i class="fa fa-play fa-flip-horizontal"></i></div>';
		$FORM_TOP_RIGHT .= '<div class="modulIcon modulIconBox formNavButton formNavButtonNext" title="' . $TEXT['nextRow'] . '"><i class="fa fa-play"></i></div>';
		$FORM_TOP_RIGHT .= '<div class="modulIcon modulIconBox formNavButton formNavButtonMax" title="' . $TEXT['maximizeForm'] . '"><i class="fa fa-window-maximize"></i></div>';
		$FORM_TOP_RIGHT .= '</div>';
	
		$FORM_TOP_RIGHT .= '<div class="formContent"></div>';
		$FORM_TOP_RIGHT .= '</div>';
		#############################################################################
		
		
		#############################################################################
		$FORM_TOP_RIGHT .= '</div>';
		#############################################################################







	}
}





######################################################################
$FORM_BOTTOM_RIGHT = ' ';
######################################################################




if(!isset($FORM_TABS_RIGHT)) $FORM_TABS_RIGHT = '<ul></ul>';









	######################################################################
	$FORM = '
		<div class="formLeft">
			<div class="formLeftInner">

				<div class="formTabs"><ul></ul></div>
		
				<form enctype="multipart/form-data" onsubmit="return false" action="" method="post" name="iFormLeft" id="form_' . $CONFIG['page']['modulpath'] . '" class="inputForm">
				
					' . $FORM_TOP_LEFT . '
				
					<div class="formFooter">
						<input type="hidden" class="field_id_data" value="" name="id_data">
						<input type="hidden" class="field_formdata" value="" name="formdata">
						' . $f_fieldshidden . '
					
						<button class="formButton cancelForm" type="button">' . $TEXT['Cancel'] . '</button>
						<button class="formButton saveForm" value="" name="save" type="submit">' . $TEXT['Save'] . '</button>
						<button class="formButton closeForm" value="" name="close" type="submit">' . $TEXT['SaveClose'] . '</button>
						
						<div class="errorMess" id="errorMessage">&nbsp;</div>
					</div>
				</form>
				
			</div>
		</div>
		
		<div class="formMiddle"></div>
		
		<div class="formRight">
			<div class="formRightInner">

				<div class="formTabs" style="height: 41px;">' . $FORM_TABS_RIGHT . '</div>
             
                ' . $FORM_TOP_RIGHT . '
			</div>
		</div>
	';
    ######################################################################

	echo $FORM;
}


?>