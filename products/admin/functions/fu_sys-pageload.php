<?php
$CONFIG['system']['pathInclude'] = "../../";
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData();

include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-page.php'); 

$pagefile = $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathPagesAdmin'] . 'p-' . $CONFIG['page']['page'] . '.php';
if(file_exists($pagefile)){
	include_once($pagefile);
}else{
	include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathAdmin'] . 'templates/content.php');
}

?>