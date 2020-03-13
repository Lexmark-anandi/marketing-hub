<?php
########################################################
// Array for childmoduls
$CONFIG_TMP['user']['childmoduls'] = array();
$queryR = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.id_mod, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls.name AS modul_name, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls.label AS modul_label, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls.modul_width, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls.modul_height, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls.modul_sortname, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls.modul_sortorder, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls.modul_sortable_rows, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls.modul_options, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls.modul_options_conditions, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls.modul_class, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls.modul_identifier, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls.modul_rank, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls.table_name, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls.table_suffix, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls.table_join, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls.cond_parent, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls.specifications, 

										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.id_mod_parent, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.name AS modul_name_child, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.label AS modul_label_child, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.modul_width AS modul_width_child, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.modul_height AS modul_height_child, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.modul_sortname AS modul_sortname_child, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.modul_sortorder AS modul_sortorder_child, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.modul_sortable_rows AS modul_sortable_rows_child, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.modul_options AS modul_options_child, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.modul_options_conditions AS modul_options_conditions_child, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.modul_class AS modul_class_child, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.modul_identifier AS modul_identifier_child, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.modul_rank AS modul_rank_child, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.table_name AS table_name_child, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.table_suffix AS table_suffix_child, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.table_join AS table_join_child, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.cond_parent AS cond_parent_child, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.specifications AS specifications_child, 
										
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2user.modul_width AS modul_width_user, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2user.modul_height AS modul_height_user, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2user.modul_sortname AS modul_sortname_user, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2user.modul_sortorder AS modul_sortorder_user, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2user.modul_rank AS modul_rank_user, 

										' . $CONFIG['db'][0]['prefix'] . 'system_functions.id_f, 
										' . $CONFIG['db'][0]['prefix'] . 'system_functions.function, 
										' . $CONFIG['db'][0]['prefix'] . 'system_functions.title, 
										' . $CONFIG['db'][0]['prefix'] . 'system_functions.icon, 
										' . $CONFIG['db'][0]['prefix'] . 'system_functions.type, 
										' . $CONFIG['db'][0]['prefix'] . 'system_functions.rank, 
										' . $CONFIG['db'][0]['prefix'] . 'system_functions.show_not_if, 
										
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod2f, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod_parent AS id_mod_parent_function, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_page_parent AS id_page_parent_function, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.filename, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.function AS function_modul, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.title AS title_modul, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.icon AS icon_modul, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.type AS type_modul, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.rank AS rank_modul, 
										' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.show_not_if AS show_not_if_modul, 
										
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_field,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_colname,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_name,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_index,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_frozen,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_width,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_sortable,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_search,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_title,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_hidden,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_resizable,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_stype,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_align,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_searchoptions,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_editable,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_editoptions,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_editrules,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_edittype,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_classes,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_specifications,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_rank,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_options,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_options_conditions,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_not_idmodparent,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_val2read,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.t_table,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.t_suffix,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.t_join,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.t_colname,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.t_primarykey,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.t_array,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.t_array_options,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields.format,

										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.id_field AS f_id_field,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.g_index AS f_index,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.g_colname AS f_colname_label,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_name,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_label,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_type,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_selectoptions,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_default,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_multiple,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_readonly,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_editable,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_parentassign,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_checkdirect,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_checkfunction,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_checkmessage,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_checksync,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_js_functions,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_data_attributes,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_config_wysiwyg,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_classes_field,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_classes_row,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_class_space,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_wrapper,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_options,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_options_conditions,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_not_idmodparent,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_specifications,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.f_rank,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.g_val2read AS f_val2read,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.t_table AS f_table,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.t_table_save AS f_table_save,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.t_suffix AS f_suffix,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.t_suffix_save AS f_suffix_save,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.t_join AS f_join,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.t_colname AS f_colname,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.t_colname_save AS f_colname_save,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.t_array AS f_array,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.t_array_options AS f_array_options,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.format AS f_format,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.always_update AS f_always_update,

										' . $CONFIG['db'][0]['prefix'] . 'system_fieldsets.id_fs,
										' . $CONFIG['db'][0]['prefix'] . 'system_fieldsets.fieldset,
										' . $CONFIG['db'][0]['prefix'] . 'system_fieldsets.rank AS fs_rank,

										' . $CONFIG['db'][0]['prefix'] . 'system_fields2user.g_width AS g_width_user,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields2user.g_hidden AS g_hidden_user,
										' . $CONFIG['db'][0]['prefix'] . 'system_fields2user.g_rank AS g_rank_user,
										
										' . $CONFIG['db'][0]['prefix'] . 'system_user2settings.active_country,
										' . $CONFIG['db'][0]['prefix'] . 'system_user2settings.active_language,
										' . $CONFIG['db'][0]['prefix'] . 'system_user2settings.active_device,
										' . $CONFIG['db'][0]['prefix'] . 'system_user2settings.grid_num_rows

									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_moduls 
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.id_mod = ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.id_mod
									
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2user
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2user.id_mod = ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.id_mod
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2user.id_mod_parent = ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.id_mod_parent
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2user.id_uid = (:id_uid)
									
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.id_mod = ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod2f IN (' . implode(',', $CONFIG_TMP['user']['functions']) . ')
											
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_functions
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_f = ' . $CONFIG['db'][0]['prefix'] . 'system_functions.id_f

									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_fields
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.id_mod = ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_mod
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_field IN (' . implode(',', $CONFIG_TMP['user']['fields_grids']) . ')
									
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_fields2user
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_fields2user.id_field = ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_field
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields2user.id_mod_parent = ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.id_mod_parent
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields2user.id_uid = (:id_uid)

									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_fields AS ' . $CONFIG['db'][0]['prefix'] . 'system_fields_form
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.id_mod = ' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.id_mod
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.id_field IN (' . implode(',', $CONFIG_TMP['user']['fields_forms']) . ')

									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_fieldsets
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_fields_form.id_fs = ' . $CONFIG['db'][0]['prefix'] . 'system_fieldsets.id_fs
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_fieldsets.active = (:active)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_fieldsets.del = (:nultime)
											
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_user2settings
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_user2settings.id_mod = ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.id_mod
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_user2settings.id_mod_parent = ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.id_mod_parent
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_user2settings.id_uid = (:id_uid)

									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_moduls.id_mod IN (' . implode(',', $CONFIG_TMP['user']['moduls']) . ')
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2children.id_mod_parent
									');
$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryR->bindValue(':active', 1, PDO::PARAM_INT);
$queryR->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);
$queryR->execute();
$rowsR = $queryR->fetchAll(PDO::FETCH_ASSOC);
$numR = $queryR->rowCount();


$aSpecFields = array('modul_name', 'modul_label', 'modul_width', 'modul_height', 'specifications', 'modul_sortname', 'modul_sortorder', 'modul_sortable_rows', 'modul_options', 'modul_options_conditions', 'modul_class', 'modul_identifier', 'modul_rank', 'table_name', 'table_join', 'cond_parent', 'table_suffix');
$aSpecFunctions = array('function', 'title', 'icon', 'type', 'rank');
$aSpecFieldsGrid = array('g_width', 'g_hidden', 'g_rank');
$aSetTranslate = array('g_colname');

foreach($rowsR as $datR){
	foreach($aSpecFields as $val){
		$datR[$val] = ($datR[$val . '_child'] != '') ? $datR[$val . '_child'] : ((isset($datR[$val . '_user']) && $datR[$val . '_user'] != '') ? $datR[$val . '_user'] : $datR[$val]);
	}
	foreach($aSpecFunctions as $val){
		$datR[$val] = ($datR[$val . '_modul'] != '') ? $datR[$val . '_modul'] : $datR[$val];
	}
	foreach($aSpecFieldsGrid as $val){
		$datR[$val] = ($datR[$val . '_user'] != '') ? $datR[$val . '_user'] : $datR[$val];
	}
	foreach($aSetTranslate as $val){
		$datR[$val] = (isset($TEXT[$datR[$val]])) ? $TEXT[$datR[$val]] : $datR[$val];
	}


	if(!array_key_exists($datR['id_mod_parent'], $CONFIG_TMP['user']['childmoduls'])){
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']] = array();
	}

	if(!array_key_exists('i_' . $datR['id_mod'], $CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']])){
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']] = array();
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['id_mod'] = $datR['id_mod'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['rank'] = intval($datR['modul_rank']);
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['modul_name'] = $datR['modul_name'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['modul_label'] = $datR['modul_label'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['modul_width'] = intval($datR['modul_width']);
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['modul_height'] = intval($datR['modul_height']);
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['modul_sortname'] = $datR['modul_sortname'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['modul_sortorder'] = $datR['modul_sortorder'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['modul_sortable_rows'] = $datR['modul_sortable_rows'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['modul_class'] = $datR['modul_class'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['modul_identifier'] = ($datR['modul_identifier'] != '') ? explode(',', $datR['modul_identifier']) : array();
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['table_name'] = $datR['table_name'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['table_suffix'] = $datR['table_suffix'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['table_join'] = ($datR['table_join'] != '') ? json_decode($datR['table_join'], true) : array();
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['cond_parent'] = ($datR['cond_parent'] != '') ? json_decode($datR['cond_parent'], true) : array();
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['specifications'] = str_split($datR['specifications']);

		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['addoptions'] = array();
		$aModulOptions = json_decode($datR['modul_options'], true);
		$aModulOptionsConditions = json_decode($datR['modul_options_conditions'], true);
		if(count($aModulOptions) > 0){
			foreach($aModulOptions as $opt=>$val){
				$aModulOptionsConditions[$opt] = ($aModulOptionsConditions[$opt] == '') ? array() : explode(',', $aModulOptionsConditions[$opt]);

				if(count($aModulOptionsConditions[$opt]) == 0 || in_array($datR['id_mod_parent'], $aModulOptionsConditions[$opt])){
					$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['addoptions'][$opt] = $val; 
				}
			}
		}
		
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['colnames'] = array();
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['colmodel'] = array();
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form'] = array();
		
		#########
		// Active settings
		$datR['active_country_form'] = '';
		$datR['active_language_form'] = '';
		$datR['active_device_form'] = '';
		
		// Synchronize moduls
		if($CONFIG['system']['synchronizeModulFilter'] == 1 && $CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['specifications'][12] == 9){
			if($CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['specifications'][9] == 9){
				// for "sys" filter
				$datR['active_country'] = $CONFIG['activeSettings']['id_sys_count'];
				$datR['active_language'] = $CONFIG['activeSettings']['id_sys_lang'];
				$datR['active_device'] = $CONFIG['activeSettings']['id_sys_dev'];
				$datR['active_country_form'] = $CONFIG['activeSettings']['id_sys_count_form'];
				$datR['active_language_form'] = $CONFIG['activeSettings']['id_sys_lang_form'];
				$datR['active_device_form'] = $CONFIG['activeSettings']['id_sys_dev_form'];
			}else{
				// for "data" filter
				$datR['active_country'] = $CONFIG['activeSettings']['id_countid'];
				$datR['active_language'] = $CONFIG['activeSettings']['id_langid'];
				$datR['active_device'] = $CONFIG['activeSettings']['id_devid'];
				$datR['active_country_form'] = $CONFIG['activeSettings']['id_countid_form'];
				$datR['active_language_form'] = $CONFIG['activeSettings']['id_langid_form'];
				$datR['active_device_form'] = $CONFIG['activeSettings']['id_devid_form'];
			}
		}
		if($CONFIG['system']['synchronizeGridNumRow'] == 1 && $CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['specifications'][12] == 9){
			$datR['grid_num_rows'] = $CONFIG['activeSettings']['gridNumRows'];
		}
		
		// Fallback if no modul settings
		if($CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['specifications'][9] == 9){
			if($datR['active_country'] == '') $datR['active_country'] = $CONFIG['activeSettings']['id_sys_count'];
			if($datR['active_language'] == '') $datR['active_language'] = $CONFIG['activeSettings']['id_sys_lang'];
			if($datR['active_device'] == '') $datR['active_device'] = $CONFIG['activeSettings']['id_sys_dev'];
			if($datR['active_country_form'] == '') $datR['active_country_form'] = $CONFIG['activeSettings']['id_sys_count'];
			if($datR['active_language_form'] == '') $datR['active_language_form'] = $CONFIG['activeSettings']['id_sys_lang'];
			if($datR['active_device_form'] == '') $datR['active_device_form'] = $CONFIG['activeSettings']['id_sys_dev'];
		}else{
			if($datR['active_country'] == '') $datR['active_country'] = $CONFIG['activeSettings']['id_countid'];
			if($datR['active_language'] == '') $datR['active_language'] = $CONFIG['activeSettings']['id_langid'];
			if($datR['active_device'] == '') $datR['active_device'] = $CONFIG['activeSettings']['id_devid'];
			if($datR['active_country_form'] == '') $datR['active_country_form'] = $CONFIG['activeSettings']['id_countid'];
			if($datR['active_language_form'] == '') $datR['active_language_form'] = $CONFIG['activeSettings']['id_langid'];
			if($datR['active_device_form'] == '') $datR['active_device_form'] = $CONFIG['activeSettings']['id_devid'];
		}
		if($datR['grid_num_rows'] == '') $datR['grid_num_rows'] = 20;
			
		// set setttings to 0 if no variations should be saved
		if($CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['specifications'][0] == 0 && $CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['specifications'][3] == 9){
			$datR['active_country'] = 0;
			$datR['active_country_form'] = 0;
		}
		if($CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['specifications'][1] == 0 && $CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['specifications'][4] == 9){
			$datR['active_language'] = 0;
			$datR['active_language_form'] = 0;
		}
		if($CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['specifications'][2] == 0 && $CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['specifications'][5] == 9){
			$datR['active_device'] = 0;
			$datR['active_device_form'] = 0;
		}
		
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['activeSettings'] = array();
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['activeSettings']['selectCountry'] = $datR['active_country'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['activeSettings']['selectLanguage'] = $datR['active_language'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['activeSettings']['selectDevice'] = $datR['active_device'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['activeSettings']['formCountry'] = $datR['active_country_form'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['activeSettings']['formLanguage'] = $datR['active_language_form'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['activeSettings']['formDevice'] = $datR['active_device_form'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['activeSettings']['gridNumRows'] = $datR['grid_num_rows'];
		#########
		
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['functions'] = array();
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['barFunctions'] = array();
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['rowFunctions'] = array();
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['fileFunctions'] = array();
	}
		
	// grid configuration
	if($datR['id_field'] != ''){
		if($CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['specifications'][11] == 9){
			$aNotModParent = explode(',', $datR['g_not_idmodparent']);
			if(!in_array($datR['id_mod_parent'], $aNotModParent)){
				// colname
				if(!array_key_exists('i_'.$datR['id_field'], $CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['colnames'])){
					$aColnameSingle = array();
					$aColnameSingle['id_field'] = intval($datR['id_field']);
					$aColnameSingle['rank'] = intval($datR['g_rank']);
					$aColnameSingle['colname'] = $datR['g_colname'];
					$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['colnames']['i_'.$datR['id_field']] = $aColnameSingle;
				}
				
				// colmodel
				if(!array_key_exists('i_'.$datR['id_field'], $CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['colmodel'])){
					$aColmodelSingle = array();
					$aColmodelSingle['id_field'] = intval($datR['id_field']);
					$aColmodelSingle['rank'] = intval($datR['g_rank']);
					$aColmodelSingle['val2read'] = array();
					if($datR['g_val2read'] != '') $aColmodelSingle['val2read'] = json_decode($datR['g_val2read'], true);
					$aColmodelSingle['t_table'] = $datR['t_table'];
					$aColmodelSingle['t_suffix'] = $datR['t_suffix'];
					$aColmodelSingle['t_join'] =  ($datR['t_join'] != '') ? json_decode($datR['t_join'], true) : array();
					$aColmodelSingle['t_colname'] = $datR['t_colname'];
					$aColmodelSingle['t_primarykey'] = intval($datR['t_primarykey']);
					$aColmodelSingle['t_array'] = intval($datR['t_array']);
					$aColmodelSingle['t_array_options'] =  ($datR['t_array_options'] != '') ? json_decode($datR['t_array_options'], true) : array();
					$aColmodelSingle['format'] = $datR['format'];
					foreach($CONFIG['system']['aGridConfig'] as $val){
						if($datR['g_' . $val] != ""){
							if($datR['g_' . $val] == 'false'){
								$aColmodelSingle[$val] = false;
							}else if($datR['g_' . $val] == 'true'){
								$aColmodelSingle[$val] = true;
							}else{
								$aColmodelSingle[$val] = $datR['g_' . $val];
							}
						}
					}
					foreach($CONFIG['system']['aGridConfigOpt'] as $val){
						$aColmodelSingle[$val] = ($datR['g_' . $val] != '') ? json_decode($datR['g_' . $val], true) : array();
					}
					if(isset($aColmodelSingle['searchoptions']['dataUrl'])){
						$aArgs = array();
						$aArgs['data']['t'] = (isset($aColmodelSingle['searchoptions']['selecttable'])) ? $aColmodelSingle['searchoptions']['selecttable'] : $aColmodelSingle['t_table'];
						$aArgs['data']['s'] = $aColmodelSingle['t_suffix'];
						$aArgs['data']['c'] = $aColmodelSingle['t_colname'];
						$aArgs['fields']['cryption'] = array('t' => array(), 's' => array(), 'c' => array());
						$aArgs['data'] = valuesEncrypt($aArgs);
						
						$aColmodelSingle['searchoptions']['dataUrl'] .= '?t=' . urlencode($aArgs['data']['t']) . '..' . urlencode($aArgs['data']['s']) . '..' . urlencode($aArgs['data']['c']);
					}
					
					// set primary key for modul main table
					if(intval($datR['t_primarykey']) == 1 && $datR['t_table'] == $datR['table_name']){
						$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['primarykey'] = $datR['t_colname'];
					}

					$aColmodelSingle['addoptions'] = array();
					$aModulOptions = json_decode($datR['g_options'], true);
					$aModulOptionsConditions = json_decode($datR['g_options_conditions'], true);
					if(count($aModulOptions) > 0){
						foreach($aModulOptions as $opt=>$val){
							$aModulOptionsConditions[$opt] = ($aModulOptionsConditions[$opt] == '') ? array() : explode(',', $aModulOptionsConditions[$opt]);
			
							if(count($aModulOptionsConditions[$opt]) == 0 || in_array($datR['id_mod_parent'], $aModulOptionsConditions[$opt])){
								$aColmodelSingle['addoptions'][$opt] = $val; 
							}
						}
					}

					$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['colmodel']['i_' . $datR['id_field']] = $aColmodelSingle;
				}
			}
		}
	}
		
	// form
	if($datR['id_fs'] != ''){
		if(!array_key_exists('i_' . $datR['id_fs'], $CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form'])){
			$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']] = array();
			$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['id_fs'] = intval($datR['id_fs']);
			$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fieldset'] = $datR['fieldset'];
			$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['rank'] = intval($datR['fs_rank']);
			
			$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields'] = array();
		}
		
		$aNotModParent = explode(',', $datR['f_not_idmodparent']);
		if(!in_array($datR['id_mod_parent'], $aNotModParent)){
			if(!array_key_exists('i_' . $datR['id_field'], $CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields'])){
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']] = array();
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['id_field'] = intval($datR['f_id_field']);
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['index'] = $datR['f_index'];
				//$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['name'] = ($datR['f_name'] != '') ? $datR['f_name'] : $datR['f_index'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['name'] = ($datR['f_colname_save'] != '') ? $datR['f_colname_save'] : $datR['f_colname'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['label'] = ($datR['f_label'] != '') ? $datR['f_label'] : $datR['f_colname_label'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['type'] = $datR['f_type'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['selectoptions'] = ($datR['f_selectoptions'] != '') ? json_decode($datR['f_selectoptions'], true) : array();
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['default'] = $datR['f_default'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['multiple'] = $datR['f_multiple'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['readonly'] = $datR['f_readonly'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['editable'] = intval($datR['f_editable']);
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['parentassign'] = intval($datR['f_parentassign']);
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['checkdirect'] = $datR['f_checkdirect'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['checkfunction'] = $datR['f_checkfunction'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['checkmessage'] = $datR['f_checkmessage'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['checksyncorg'] = $datR['f_checksync'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['checksync'] = $datR['f_checksync'];
				if($datR['f_type'] == 'file' && $datR['f_multiple'] == 'true') $CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['checksync'] = 'all';
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['js_functions'] = ($datR['f_js_functions'] != '') ? json_decode($datR['f_js_functions'], true) : array();
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['data_attributes'] = ($datR['f_data_attributes'] != '') ? json_decode($datR['f_data_attributes'], true) : array();
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['config_wysiwyg'] = ($datR['f_config_wysiwyg'] != '') ? json_decode($datR['f_config_wysiwyg'], true) : array();
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['classes_field'] = $datR['f_classes_field'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['classes_row'] = $datR['f_classes_row'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['class_space'] = $datR['f_class_space'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['wrapper'] = $datR['f_wrapper'];
				
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['options'] = array();
				$aFieldOptions = json_decode($datR['f_options'], true);
				$aFieldOptionsConditions = json_decode($datR['f_options_conditions'], true);
				if(count($aFieldOptions) > 0){
					foreach($aFieldOptions as $opt=>$val){
						$aFieldOptionsConditions[$opt] = (!isset($aFieldOptionsConditions[$opt]) || $aFieldOptionsConditions[$opt] == '') ? array() : explode(',', $aFieldOptionsConditions[$opt]);
		
						if(count($aFieldOptionsConditions[$opt]) == 0 || in_array(0, $aFieldOptionsConditions[$opt])){
							$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['options'][$opt] = $val; 
						}
					}
				}
				
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['not_idmodparent'] = $datR['f_not_idmodparent'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['specifications'] = ($datR['f_specifications'] != '') ? str_split($datR['f_specifications']) : array();
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['rank'] = intval($datR['f_rank']);
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['table'] = $datR['f_table'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['table_save'] = ($datR['f_table_save'] != '') ? $datR['f_table_save'] : $datR['f_table'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['suffix'] = intval($datR['f_suffix']);
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['suffix_save'] = ($datR['f_suffix_save'] != '') ? intval($datR['f_suffix_save']) : intval($datR['f_suffix']);
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['join'] = ($datR['f_join'] == '') ? array() : json_decode($datR['f_join'], true);
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['colname'] = $datR['f_colname'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['colname_save'] = ($datR['f_colname_save'] != '') ? $datR['f_colname_save'] : $datR['f_colname'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['array'] = intval($datR['f_array']);
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['array_options'] = ($datR['f_array_options'] == '') ? array() : json_decode($datR['f_array_options'], true);
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['val2read'] = ($datR['f_val2read'] == '') ? array() : json_decode($datR['f_val2read'], true);
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['format'] = $datR['f_format'];
				$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['form']['i_' . $datR['id_fs']]['fields']['i_' . $datR['f_id_field']]['always_update'] = intval($datR['f_always_update']);
			}
		}
	}
		
	// functions
	$aParentMod = array();
	if($datR['id_mod_parent_function'] != '') $aParentMod = explode(',', $datR['id_mod_parent_function']);
	if(in_array($datR['id_mod_parent'], $aParentMod) && !array_key_exists('i_' . $datR['id_mod2f'], $CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['functions'])){
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['functions']['i_' . $datR['id_mod2f']] = array();
		
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['functions']['i_' . $datR['id_mod2f']]['id_mod2f'] = intval($datR['id_mod2f']);
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['functions']['i_' . $datR['id_mod2f']]['id_page_parent'] = array();
		if($datR['id_page_parent_function'] != '') $CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['functions']['i_' . $datR['id_mod2f']]['id_page_parent'] = explode(',', $datR['id_page_parent_function']);
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['functions']['i_' . $datR['id_mod2f']]['rank'] = intval($datR['rank']);
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['functions']['i_' . $datR['id_mod2f']]['id_f'] = intval($datR['id_f']);
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['functions']['i_' . $datR['id_mod2f']]['filename'] = $datR['filename'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['functions']['i_' . $datR['id_mod2f']]['function'] = $datR['function'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['functions']['i_' . $datR['id_mod2f']]['title'] = $datR['title'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['functions']['i_' . $datR['id_mod2f']]['icon'] = $datR['icon'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['functions']['i_' . $datR['id_mod2f']]['type'] = $datR['type'];
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['functions']['i_' . $datR['id_mod2f']]['show_not_if'] = array();
		if($datR['show_not_if'] != '' && $datR['show_not_if_modul'] == '') $CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['functions']['i_' . $datR['id_mod2f']]['show_not_if'] = explode(',', $datR['show_not_if']);
		$CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['functions']['i_' . $datR['id_mod2f']]['show_not_if_modul'] = array();
		if($datR['show_not_if_modul'] != '') $CONFIG_TMP['user']['childmoduls'][$datR['id_mod_parent']]['i_' . $datR['id_mod']]['functions']['i_' . $datR['id_mod2f']]['show_not_if_modul'] = explode(',', $datR['show_not_if_modul']);
	}
}


// cleaning functions
foreach($CONFIG_TMP['user']['childmoduls'] as &$aChild){
	foreach($aChild as &$aModul){
		$f = array_column($aModul['functions'], 'id_f');
		foreach($aModul['functions'] as $keyFunction => $aFunction){
			foreach($aFunction['show_not_if'] as $idNo){
				$k = array_search($idNo, $f);
				if($k != false){
					$aModul['functions'][$keyFunction]['notshow'] = 1;
					break;
				}
			}
			
			foreach($aFunction['show_not_if_modul'] as $idNo){
				if(array_key_exists('i_' . $idNo, $aModul['functions'])){
					$aModul['functions'][$keyFunction]['notshow'] = 1;
					break;
				}
			}
		}
	}
}


// sorting
foreach($CONFIG_TMP['user']['childmoduls'] as &$aChild){
	uasort($aChild, 'sortConfig');

	foreach($aChild as &$aModul){
		uasort($aModul['colnames'], 'sortConfig');
		uasort($aModul['colmodel'], 'sortConfig');
		uasort($aModul['functions'], 'sortConfig');
		uasort($aModul['form'], 'sortConfig');

		foreach($aModul['form'] as &$aFieldset){
			uasort($aFieldset['fields'], 'sortConfig');
		}
		
		foreach($aModul['functions'] as $aFunction){
			if(!isset($aFunction['notshow'])) $aFunction['notshow'] = 0;
			if($aFunction['notshow'] != 1){
				if(!in_array($aFunction['id_mod2f'], $aModul['barFunctions']) && ($aFunction['type'] == 'functionbar' || $aFunction['type'] == 'folderbar')) array_push($aModul['barFunctions'], $aFunction['id_mod2f']);
				if(!in_array($aFunction['id_mod2f'], $aModul['rowFunctions']) && ($aFunction['type'] == 'dataset' || $aFunction['type'] == 'folderdata')) array_push($aModul['rowFunctions'], $aFunction['id_mod2f']);
				if(!in_array($aFunction['id_mod2f'], $aModul['fileFunctions']) && ($aFunction['type'] == 'file')) array_push($aModul['fileFunctions'], $aFunction['id_mod2f']);
			}
		}
	}
}

###########################################################################
?>