<?php
$CONFIG['system']['pathInclude'] = "../../";
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData();

$script = '';
$file = $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathJsAdmin'] . 'js_sys-default.js';
if(file_exists($file)) $script .= file_get_contents($file);
$file = $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathJsAdmin'] . 'js-' . $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'] . '.js';
if(file_exists($file)) $script .= file_get_contents($file);

foreach($CONFIG['page']['moduls'][$varSQL['modul']] as $key => $val){
	$script = str_replace('aPage.' . $key, $val, $script);
}

echo $script; 
?>