<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');
$varSQL = getPostData();
$CONFIG['page'] = json_decode($varSQL['pageConfig'], true);
 
 

$str = '<select><option value="">' . $TEXT['All'] . '</option>';

//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.active = (:active)
$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid as id,
										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language as term
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
										AND (' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:id_clid)
											OR ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:nul))
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->bindValue(':id_clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
//$query->bindValue(':active', 1, PDO::PARAM_INT);
$query->execute();
//
//if($CONFIG['USER']['right_systemadmin'] == 9){
//	$query = $CONFIG['dbconn']->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_roles.id_r,
//											' . $CONFIG['db'][0]['prefix'] . 'system_roles.role
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_roles 
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_roles.del = (:nultime)
//										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_roles.rank
//										');
//	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$query->execute();
//}

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