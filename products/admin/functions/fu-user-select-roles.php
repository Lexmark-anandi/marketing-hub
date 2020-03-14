<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');
$varSQL = getPostData();
$CONFIG['page'] = json_decode($varSQL['pageConfig'], true);
 
 

$str = '<select><option value="">' . $TEXT['All'] . '</option>';

$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_roles.id_r as id,
										' . $CONFIG['db'][0]['prefix'] . 'system_roles.role as term
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_roles 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_roles.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_roles.show = (:active)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_roles.rank
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':active', 1, PDO::PARAM_INT);
$query->execute();

if($CONFIG['USER']['right_systemadmin'] == 9){
	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_roles.id_r as id,
											' . $CONFIG['db'][0]['prefix'] . 'system_roles.role as term
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_roles 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_roles.del = (:nultime)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_roles.rank
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->execute();
}

$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$aResult = array();
foreach($rows as $row){
	$row['term'] = (isset($TEXT[$row['term']])) ? $TEXT[$row['term']] : $row['term'];
	$aResult[$row['id']] = $row['term'];
}

//asort($aResult, SORT_NATURAL | SORT_FLAG_CASE);
foreach($aResult as $id => $term){
	$str .= '<option value="' . $id . '">' . $term . '</option>';
}

$str .= '</select>';
    
echo $str;
?>