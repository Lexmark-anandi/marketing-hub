<?php
function selectMastercountries(){
	global $CONFIG, $TEXT;
	
	$table = 'sys_countries_uni';
	$primary = 'id_countid';

	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primary . ' AS id,
											' . $CONFIG['db'][0]['prefix'] . $table . '.country AS term
										FROM ' . $CONFIG['db'][0]['prefix'] . $table . ' 
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count = (:count)
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang = (:lang)
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev = (:dev)
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . $table . '.del = (:nultime)
	
										GROUP BY ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primary . '
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . $table . '.country
											
										');
	$query->bindValue(':count', $CONFIG['settings']['selectCountry'], PDO::PARAM_INT);
	$query->bindValue(':lang', $CONFIG['settings']['selectLanguage'], PDO::PARAM_INT); 
	$query->bindValue(':dev', $CONFIG['settings']['selectDevice'], PDO::PARAM_INT);
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