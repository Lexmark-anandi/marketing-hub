<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$functionPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'];
$functionFile = 'fu-' . $CONFIG['aModul']['modul_name'] . '-delete.php';
$functionFileOne2n = 'fu-' . $CONFIG['aModul']['modul_name'] . '-delete-one2n.php';
$functionFilePost = 'fu-' . $CONFIG['aModul']['modul_name'] . '-delete-post.php';
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php');


if(file_exists($functionPath . $functionFile)){
	include_once($functionPath . $functionFile);
	
}else{
	$date = new DateTime();
	$now = $date->format('Y-m-d H:i:s');

	$aArgsDelete = array();
	$aArgsDelete['id_data'] = $CONFIG['page']['id_data'];
	$aArgsDelete['table'] = $CONFIG['db'][0]['prefix'] . $CONFIG['aModul']['table_name'];
	$aArgsDelete['suffix'] = $CONFIG['aModul']['table_suffix'];
	$aArgsDelete['primarykey'] = $CONFIG['aModul']['primarykey'];

	deleteRecord($aArgsDelete);
	
	######################################################
	// delete 1 to n fields
	if(file_exists($functionPath . $functionFileOne2n)){
		include($functionPath . $functionFileOne2n);
	}else{
		foreach($CONFIG['aModul']['form'] as $aFieldsets){
			foreach($aFieldsets['fields'] as $field){
				if(in_array($field['specifications'][0], $CONFIG['system']['aFieldsAllowedSpecs']) && $field['specifications'][2] != 0){
					if($field['array'] == 1 && $field['array_options']['primarykey'] != ''){
						$queryN = $CONFIG['dbconn'][0]->prepare('SHOW KEYS FROM ' . $CONFIG['db'][0]['prefix'] . $field['table'] . ' WHERE Key_name = "PRIMARY"');
						$queryN->execute();
						$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
						$numN = $queryN->rowCount(); 
						$column_condN = $rowsN[0]['Column_name'];
						
						$queryN = $CONFIG['dbconn'][0]->prepare('SHOW KEYS FROM ' . $CONFIG['db'][0]['prefix'] . $field['array_options']['table_save'] . ' WHERE Key_name = "PRIMARY"');
						$queryN->execute();
						$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
						$numN = $queryN->rowCount(); 
						$primaryN = $rowsN[0]['Column_name'];
						
						$tableN = ($field['array_options']['table_save_suffix'] == 0) ? $field['array_options']['table_save'] : $field['array_options']['table_save'] . 'uni';
		
						
						$queryStrN = 'SELECT ';
						$queryStrN .= 'DISTINCT(' . $CONFIG['db'][0]['prefix'] . $tableN . '.' . $primaryN . ') AS id '; 
						$queryStrN .= 'FROM ' . $CONFIG['db'][0]['prefix'] . $tableN . ' ';
						$queryStrN .= 'WHERE ' . $CONFIG['db'][0]['prefix'] . $tableN . '.' . $field['array_options']['column_cond'] . ' = (:cond) ';
						
						$queryN = $CONFIG['dbconn'][0]->prepare($queryStrN);
						$queryN->bindValue(':cond', $CONFIG['page']['id_data'], PDO::PARAM_INT);
						$queryN->execute();
						$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
						$numN = $queryN->rowCount();
						
						foreach($rowsN as $rowN){
							$aArgsDelete = array();
							$aArgsDelete['id_data'] = $rowN['id'];
							$aArgsDelete['table'] = $CONFIG['db'][0]['prefix'] . $field['array_options']['table_save'];
							$aArgsDelete['suffix'] = $field['array_options']['table_save_suffix'];
							$aArgsDelete['primarykey'] = $primaryN;
						
							deleteRecord($aArgsDelete);
						}
					}
				}
			}
		}
	}

	#########################################

	if(file_exists($functionPath . $functionFilePost)){ 
		include_once($functionPath . $functionFilePost);
	}
}

?>