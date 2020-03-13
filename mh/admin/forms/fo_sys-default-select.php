<?php
function buildSelection($aArgs){
	global $CONFIG, $TEXT;
	
	$table = $aArgs['table'];
	$primary = $aArgs['primarykey'];
	
	if($aArgs['primarykey'] == ''){
		$query = $CONFIG['dbconn'][$aArgs['connection']]->prepare('SHOW KEYS FROM ' . $CONFIG['db'][0]['prefix'] . $table . ' WHERE Key_name = "PRIMARY"');
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		$num = $query->rowCount(); 
		$primary = $rows[0]['Column_name'];
	}
	
	$condUni = '';
	if($aArgs['suffix'] == 1){
		$table .= 'uni';
		$condUni .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count = (:count) ';
		$condUni .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang = (:lang) ';
		$condUni .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev = (:dev) ';
		$condUni .= 'AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ') ';
	}
	
	$queryFields = '';
	foreach($aArgs['fields'] as $field){
		$aF = explode('AS', $field);
		$field = trim($aF[0]);
		$queryFields .= ', ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $field;
	}
	
	$queryOrder = '';
	foreach($aArgs['order'] as $key => $aOrder){
		$aF = explode('AS', key($aOrder));
		$field = trim($aF[0]);

		$dir = $aOrder[$field];
		$queryOrder .= $CONFIG['db'][0]['prefix'] . $table . '.' . $field . ' ' . $dir . ', ';
	}
	$queryOrder = rtrim($queryOrder, ', ');
	if($queryOrder != '') $queryOrder = 'ORDER BY ' . $queryOrder;
	
	$query = $CONFIG['dbconn'][$aArgs['connection']]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primary . ' AS id
											' . $queryFields . '
										FROM ' . $CONFIG['db'][0]['prefix'] . $table . ' 
										WHERE ' . $CONFIG['db'][0]['prefix'] . $table . '.del = (:nultime)
										' . $condUni . '
										' . $queryOrder . '
										');
	if($condUni != '') $query->bindValue(':count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
	if($condUni != '') $query->bindValue(':lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
	if($condUni != '') $query->bindValue(':dev', $CONFIG['settings']['formDevice'], PDO::PARAM_INT);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount(); 
	
	
	$aResult = array();
	foreach($rows as $row){
		$aVal = array();
		if(isset($aArgs['translate'])){
			foreach($aArgs['translate'] as $field){
				$aF = explode('AS', $field);
				$field = trim($aF[0]);
				if($condUni == '') $row[$field] = (isset($TEXT[$row[$field]])) ? $TEXT[$row[$field]] : $row[$field];
				if($field != 'id') array_push($aVal, $row[$field]);
			}
		}else{
			foreach($aArgs['fields'] as $field){
				$aF = explode('AS', $field);
				$field = trim($aF[0]);
				if($field != 'id') array_push($aVal, $row[$field]);
			}
		}
		$aResult[$row['id']] = implode(' / ', $aVal);
	}
	
	
	if($aArgs['sorting'] == true) asort($aResult, SORT_NATURAL | SORT_FLAG_CASE);
	
	########################################################################
	
	$output = '';
	
	switch($aArgs['type']){
		case 'select':
			foreach($aResult as $id => $term){
				$output .= '<option value="' . $id . '">' . $term . '</option>';
			}
			break;	

		case 'radio':
			foreach($aResult as $id => $term){
				$output .= '<div><label><input type="radio" name="' . $aArgs['obj']['f_name'] . '" id="' . $aArgs['obj']['f_id'] . '_' . $id . '" class="radiofield ' . $aArgs['obj']['f_classes_field'] . '" value="' . $id . '" data-checkfunction="' . $aArgs['obj']['f_checkfunction'] . '" data-checkmessage="' . $aArgs['obj']['f_checkmessage'] . '"' . $aArgs['obj']['f_js_functions'] . $aArgs['obj']['f_data_attributes'] . $aArgs['obj']['f_readonly'] . '> ' . $term . '</label></div>';
			}
			break;	

		case 'checkbox':
			foreach($aResult as $id => $term){
				$output .= '<div><label><input type="checkbox" name="' . $aArgs['obj']['f_name'] . '" id="' . $aArgs['obj']['f_id'] . '_' . $id . '" class="checkfield ' . $aArgs['obj']['f_classes_field'] . '" value="' . $id . '" data-checkfunction="' . $aArgs['obj']['f_checkfunction'] . '" data-checkmessage="' . $aArgs['obj']['f_checkmessage'] . '"' . $aArgs['obj']['f_js_functions'] . $aArgs['obj']['f_data_attributes'] . $aArgs['obj']['f_readonly'] . '> ' . $term . '</label></div>';
			}
			break;	

		case 'boolean':
			foreach($aResult as $id => $term){
				$valMaster = ($id == 0) ? ' <span class="valuedefault"></span>' : '';
				$output .= '<div class="inlineRadiofield"><label><input type="radio" name="' . $aArgs['obj']['f_name'] . '" id="' . $aArgs['obj']['f_id'] . '_' . $id . '" class="booleanfield' . $aArgs['obj']['f_classes_field'] . '" value="' . $id . '" data-checkfunction="' . $aArgs['obj']['f_checkfunction'] . '" data-checkmessage="' . $aArgs['obj']['f_checkmessage'] . '"' . $aArgs['obj']['f_js_functions'] . $aArgs['obj']['f_data_attributes'] . $aArgs['obj']['f_readonly'] . '> ' . $term . $valMaster . '</label></div>';
			}
			break;	

		case 'raw':
			$output = $aResult;
			break;	
	}

	return $output;	
}
?>