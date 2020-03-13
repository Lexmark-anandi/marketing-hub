<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-csrf.php'); 

$filesys_filename = $varSQL['filesys_filename'];
$filesys_filename = str_replace('./', '', $filesys_filename);

$download = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathAdmin'] . 'tmp/' . $filesys_filename;
if($varSQL['type'] == 'media') $download = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'] . $filesys_filename;
$filename = $varSQL['filename'];


header("Content-type: application/force-download; charset=utf-8");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=" . $filename . "");
readfile($download);
if($varSQL['type'] == 'export') unlink($download);
if($varSQL['folder'] != '') rmdir($varSQL['folder']);

?>