<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php'); 
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData(json_decode($_POST['data'], true));
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php'); 


$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$table = $CONFIG['db'][0]['prefix'] . 'sys_languages';
$primekey = 'id_langid';
$aFieldsNumbers = array('active');

$columns = $table . '_##TYPE##.' . $primekey . ',
			' . $table . '_##TYPE##.language,
			' . $table . '_##TYPE##.code,
			' . $table . '_##TYPE##.code_add,
			' . $table . '_##TYPE##.active
';

$aFields = array(
					"language"			=>	array('language', "s"), 
					"code"			=>	array('code', "s"), 
					"code_add"			=>	array('code_add', "s"), 
					"active"			=>	array('active', "d"), 
					);

##########################################################################

include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-default-insert.php'); 




$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_count2lang
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid = (:id_langid)
									');
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->bindValue(':id_langid', $out['id'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

if($num == 0){
	$query = $CONFIG['dbconn']->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages
										(id_countid, id_langid, default_, del)
										VALUES
										(:nul, :id_langid, :nul, :nultime)
										');
	$query->bindValue(':nul', 0, PDO::PARAM_INT);
	$query->bindValue(':id_langid', $out['id'], PDO::PARAM_INT);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->execute();
}else{
	$query = $CONFIG['dbconn']->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages SET
										del = (:nultime)
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_count2lang = (:id_count2lang)
										LIMIT 1
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':id_count2lang', $rows[0]['id_count2lang'], PDO::PARAM_INT);
	$query->execute();
}
?>