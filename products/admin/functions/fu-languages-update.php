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

$listFields = array(
					"language"			=>	array('language', "s"), 
					"code"			=>	array('code', "s"), 
					"code_add"			=>	array('code_add', "s"), 
					"active"			=>	array('active', "d"), 
					);


include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-default-update.php'); 

?>