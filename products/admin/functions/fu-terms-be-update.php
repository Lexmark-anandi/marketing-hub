<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php'); 
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData(json_decode($_POST['data'], true));
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php'); 


$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$table = $CONFIG['db'][0]['prefix'] . 'sys_terms_be';
$primekey = 'id_tbeid';
$aFieldsNumbers = array();

$columns = $table . '_##TYPE##.' . $primekey . ',
			' . $table . '_##TYPE##.var,
			' . $table . '_##TYPE##.term
';

$listFields = array(
					"var"			=>	array('var', "s"), 
					"term"			=>	array('term', "s"), 
					);


include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-default-update.php'); 

include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-terms-be-writetext.php'); 


?>