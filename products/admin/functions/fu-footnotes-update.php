<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php'); 
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData(json_decode($_POST['data'], true));
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php'); 


$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$table = $CONFIG['db'][0]['prefix'] . 'sys_footnotes';
$primekey = 'id_fnid';
$aFieldsNumbers = array();

$columns = $table . '_##TYPE##.' . $primekey . ',
			' . $table . '_##TYPE##.marking,
			' . $table . '_##TYPE##.footnote
';

$listFields = array(
					"marking"			=>	array('marking', "s"), 
					"footnote"			=>	array('footnote', "s"), 
					);



include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-default-update.php'); 

?>