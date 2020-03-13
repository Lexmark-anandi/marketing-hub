<?php
$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$aT = array('', 'ext', 'loc', 'uni');
	
	 
	
	
$queryC = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.animated
									FROM ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni
									 
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.id_bfid = (:id_bfid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_bannerformats_uni.del = (:nultime)
									');
$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':id_bfid', $aArgsSave['id_data'], PDO::PARAM_INT);
$queryC->execute();
$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
$numC = $queryC->rowCount();

$pages = ($rowsC[0]['animated'] == 1) ? 3 : 1;


$queryC1 = $CONFIG['dbconn'][0]->prepare('
			UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_fields SET
				del = (:del)
			WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_mod = 111
				AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_index LIKE "banner_original_' . $aArgsSave['id_data'] . '_%"
			');
$queryC1->bindValue(':del', $now, PDO::PARAM_STR); 
$queryC1->execute();



for($i=1; $i<=$pages; $i++){
	$bannername = 'banner_original_' . $aArgsSave['id_data'] . '_' . $i;
	$del = '0000-00-00 00:00:00';
	
	$queryC1 = $CONFIG['dbconn'][0]->prepare('
			INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_fields
				(id_field, id_mod, g_colname, g_name, g_index, g_frozen, g_width, g_sortable, g_search, g_title, g_hidden, g_resizable, g_stype, g_align, g_searchoptions, g_editable, g_editoptions, g_editrules, g_edittype, g_classes, g_options, g_options_conditions, g_not_idmodparent, g_active, g_gridactive, g_specifications, g_val2read, g_rank, id_fs, f_name, f_label, f_type, f_selectoptions, f_default, f_multiple, f_readonly, f_editable, f_parentassign, f_checkdirect, f_checkfunction, f_checkmessage, f_checksync, f_js_functions, f_data_attributes, f_config_wysiwyg, f_classes_field, f_classes_row, f_class_space, f_wrapper, f_options, f_options_conditions, f_not_idmodparent, f_active, f_formactive, f_specifications, f_rank, t_table, t_table_save, t_suffix, t_suffix_save, t_join, t_colname, t_colname_save, t_primarykey, t_array, t_array_options, always_update, format, exp_format, exp_selectoptions, del)
			VALUES
				(:id_field, :id_mod, :g_colname, :g_name, :g_index, :g_frozen, :g_width, :g_sortable, :g_search, :g_title, :g_hidden, :g_resizable, :g_stype, :g_align, :g_searchoptions, :g_editable, :g_editoptions, :g_editrules, :g_edittype, :g_classes, :g_options, :g_options_conditions, :g_not_idmodparent, :g_active, :g_gridactive, :g_specifications, :g_val2read, :g_rank, :id_fs, :f_name, :f_label, :f_type, :f_selectoptions, :f_default, :f_multiple, :f_readonly, :f_editable, :f_parentassign, :f_checkdirect, :f_checkfunction, :f_checkmessage, :f_checksync, :f_js_functions, :f_data_attributes, :f_config_wysiwyg, :f_classes_field, :f_classes_row, :f_class_space, :f_wrapper, :f_options, :f_options_conditions, :f_not_idmodparent, :f_active, :f_formactive, :f_specifications, :f_rank, :t_table, :t_table_save, :t_suffix, :t_suffix_save, :t_join, :t_colname, :t_colname_save, :t_primarykey, :t_array, :t_array_options, :always_update, :format, :exp_format, :exp_selectoptions, :del)
			ON DUPLICATE KEY UPDATE 
				del = (:del)
			');
			
	$queryC1->bindValue(':id_field', NULL, PDO::PARAM_STR); 
	$queryC1->bindValue(':id_mod', 111, PDO::PARAM_INT); 
	$queryC1->bindValue(':g_colname', $bannername, PDO::PARAM_STR); 
	$queryC1->bindValue(':g_name', $bannername.'G', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_index', $bannername, PDO::PARAM_STR); 
	$queryC1->bindValue(':g_frozen', 'false', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_width', 120, PDO::PARAM_INT); 
	$queryC1->bindValue(':g_sortable', 'true', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_search', 'true', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_title', 'false', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_hidden', 'false', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_resizable', 'true', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_stype', 'text', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_align', '', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_searchoptions', '', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_editable', 'false', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_editoptions', '', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_editrules', '', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_edittype', 'text', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_classes', '', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_options', NULL, PDO::PARAM_STR); 
	$queryC1->bindValue(':g_options_conditions', NULL, PDO::PARAM_STR); 
	$queryC1->bindValue(':g_not_idmodparent', '', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_active', '1', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_gridactive', '0', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_specifications', '9', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_val2read', '{"file":{"pf":"1","thumbnail":"0","functions":[22],"gridheight":"fold","type":"single"}}', PDO::PARAM_STR); 
	$queryC1->bindValue(':g_rank', '120', PDO::PARAM_STR); 
	$queryC1->bindValue(':id_fs', '113', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_name', '', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_label', '', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_type', 'file', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_selectoptions', NULL, PDO::PARAM_STR); 
	$queryC1->bindValue(':f_default', NULL, PDO::PARAM_STR); 
	$queryC1->bindValue(':f_multiple', '', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_readonly', '', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_editable', '9', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_parentassign', '0', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_checkdirect', '', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_checkfunction', '', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_checkmessage', '', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_checksync', 'device', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_js_functions', NULL, PDO::PARAM_STR); 
	$queryC1->bindValue(':f_data_attributes', '{"allowedtypes":"pdf","target":"printads"}', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_config_wysiwyg', NULL, PDO::PARAM_STR); 
	$queryC1->bindValue(':f_classes_field', '', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_classes_row', '', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_class_space', '', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_wrapper', NULL, PDO::PARAM_STR); 
	$queryC1->bindValue(':f_options', NULL, PDO::PARAM_STR); 
	$queryC1->bindValue(':f_options_conditions', NULL, PDO::PARAM_STR); 
	$queryC1->bindValue(':f_not_idmodparent', '', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_active', '1', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_formactive', '1', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_specifications', '999', PDO::PARAM_STR); 
	$queryC1->bindValue(':f_rank', '100', PDO::PARAM_STR); 
	$queryC1->bindValue(':t_table', '_templatespages_', PDO::PARAM_STR); 
	$queryC1->bindValue(':t_table_save', '', PDO::PARAM_STR); 
	$queryC1->bindValue(':t_suffix', '1', PDO::PARAM_STR); 
	$queryC1->bindValue(':t_suffix_save', '1', PDO::PARAM_STR); 
	$queryC1->bindValue(':t_join', NULL, PDO::PARAM_STR); 
	$queryC1->bindValue(':t_colname', $bannername, PDO::PARAM_STR); 
	$queryC1->bindValue(':t_colname_save', $bannername, PDO::PARAM_STR); 
	$queryC1->bindValue(':t_primarykey', '0', PDO::PARAM_STR); 
	$queryC1->bindValue(':t_array', '0', PDO::PARAM_STR); 
	$queryC1->bindValue(':t_array_options', NULL, PDO::PARAM_STR); 
	$queryC1->bindValue(':always_update', '0', PDO::PARAM_STR); 
	$queryC1->bindValue(':format', 's', PDO::PARAM_STR); 
	$queryC1->bindValue(':exp_format', 'si', PDO::PARAM_STR); 
	$queryC1->bindValue(':exp_selectoptions', '', PDO::PARAM_STR); 
	$queryC1->bindValue(':del', $del, PDO::PARAM_STR); 
	$queryC1->execute();
	
//			$arr = $queryC1->errorInfo();
//			print_r($arr);

}






?>