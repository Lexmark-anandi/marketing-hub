<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-page2modul.php');

$script = '';

if(isset($varSQL['filespecial'])){
	$file = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathJsAdmin'] . $varSQL['filespecial']; 
	if(file_exists($file)) $script .= file_get_contents($file);
}else{
	// load default script
	$file = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathJsAdmin'] . 'js_sys-default.js';
	if(file_exists($file)) $script .= file_get_contents($file);
	$file = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathJsAdmin'] . 'js_sys-default-callbacks.js';
	if(file_exists($file)) $script .= file_get_contents($file);
	
	// load special modul script
	$file = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathJsAdmin'] . 'js-' . $CONFIG['page']['modul_name'] . '.js';
	if(file_exists($file)) $script .= file_get_contents($file);
}

// replace placeholder for modul in functions
foreach($CONFIG['page'] as $key => $val){
	$script = str_replace('##' . $key . '##', $val, $script);
}

echo $script; 
?>