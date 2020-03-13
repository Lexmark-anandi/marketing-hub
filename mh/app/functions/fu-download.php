<?php
include_once(__DIR__ . '/../config-app.php');
$varSQL = getPostData(); 

//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-csrf.php'); 

$file = $varSQL['file'];
$file = str_replace('./', '', $file);

$download = $file;
if($varSQL['type'] == 'media') $download = $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathMedia'] . $file;
$filename = $varSQL['filename'];


header("Content-type: application/force-download; charset=utf-8");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=" . urlencode($filename) . "");
readfile($download);
if($varSQL['type'] != 'media'){
	unlink($download);
	if(isset($varSQL['folder'])) rmdir($varSQL['folder']);
}

?>