<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');
$varSQL = getPostData();
$CONFIG['page'] = json_decode($varSQL['pageConfig'], true);
 

$str = '<select><option value="">' . $TEXT['All'] . '</option>';

$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid as id,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country as term
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid IN (' . implode(',', array_keys($CONFIG['USER']['countries'])) . ')
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

foreach($rows as $row){
	$term = $row['term'];
	if(isset($TEXT[$term])) $term = $TEXT[$term];
	$str .= '<option value="' . $row['id'] . '">' . $term . '</option>';
}

$str .= '</select>';
    
echo $str;

?>