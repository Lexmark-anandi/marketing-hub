<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();
if(!isset($varSQL['mode'])) $varSQL['mode'] = 'grid';

//$aS = explode('..', $_GET['t']);
//$aArgs = array();
//$aArgs['data']['t'] = $aS[0];
//$aArgs['data']['s'] = $aS[1];
//$aArgs['data']['c'] = $aS[2];
//$aArgs['fields']['cryption'] = array('t' => array(), 's' => array(), 'c' => array());
//$aArgs['data'] = valuesDecrypt($aArgs);
//
//$aCol = explode('AS', $aArgs['data']['c']);
//$aArgs['data']['c'] = trim($aCol[0]);

$table = 'sys_countries_uni';

//$query = $CONFIG['dbconn'][0]->prepare('SHOW KEYS FROM ' . $CONFIG['db'][0]['prefix'] . $table . ' WHERE Key_name = "PRIMARY"');
//$query->execute();
//$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//$num = $query->rowCount(); 
$primary = 'id_countid';
//
//
//if($aArgs['data']['s'] == 1) $table .= 'uni';
//
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . $table . '.' . $primary . ' AS id,
										' . $CONFIG['db'][0]['prefix'] . $table . '.country AS term
									FROM ' . $CONFIG['db'][0]['prefix'] . $table . ' 
									WHERE ' . $CONFIG['db'][0]['prefix'] . $table . '.id_count = (:count)
										AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_lang = (:lang)
										AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_dev = (:dev)
										AND ' . $CONFIG['db'][0]['prefix'] . $table . '.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . $table . '.del = (:nultime)
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

//asort($aResult, SORT_NATURAL | SORT_FLAG_CASE);

$str = '<select>';
$str .= '<option value="">' . $TEXT['All'] . '</option>';
foreach($aResult as $id => $term){
	$str .= '<option value="' . $id . '">' . $term . '</option>';
}
$str .= '</select>';
    
echo $str;
?>