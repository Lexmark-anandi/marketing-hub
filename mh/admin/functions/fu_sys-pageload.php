<?php
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-page.php');

$pagefile = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathPagesAdmin'] . 'p-' . $CONFIG['user']['pages2moduls'][$CONFIG['activeSettings']['id_page']]['pagename'] . '.php';
if(file_exists($pagefile)){
	include_once($pagefile);
}else{
	include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathAdmin'] . 'templates/content.php');
}

?>