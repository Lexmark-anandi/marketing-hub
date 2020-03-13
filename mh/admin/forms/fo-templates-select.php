<?php
function selectAssetCategories(){
	global $CONFIG, $TEXT;
	
	$table = '_categories_assets_uniy';
	$primary = 'id_caid'; 

	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primary . ' AS id,
											' . $CONFIG['db'][0]['prefix'] . $table . '.category AS term
										FROM ' . $CONFIG['db'][0]['prefix'] . $table . ' 
										WHERE ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count = (:count)
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang = (:lang)
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev = (:dev)
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.is_standalone = (:one)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . $table . '.rank
											
										');
	$query->bindValue(':count', $CONFIG['settings']['selectCountry'], PDO::PARAM_INT);
	$query->bindValue(':lang', $CONFIG['settings']['selectLanguage'], PDO::PARAM_INT);
	$query->bindValue(':dev', $CONFIG['settings']['selectDevice'], PDO::PARAM_INT);
	$query->bindValue(':rank', $rankAct, PDO::PARAM_INT);
	$query->bindValue(':one', 1, PDO::PARAM_INT);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount(); 
	
	$aResult = array();
	foreach($rows as $row){
		$row['term'] = (isset($TEXT[$row['term']])) ? $TEXT[$row['term']] : $row['term'];
		$aResult[$row['id']] = $row['term'];
	}

	$str = '';
	foreach($aResult as $id => $term){
		$str .= '<option value="' . $id . '">' . $term . '</option>';
	}
	$str .= '';
		
	return $str;
}




?>