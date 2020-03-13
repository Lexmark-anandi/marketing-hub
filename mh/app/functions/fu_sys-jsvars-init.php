<?php
//$CONFIG['noCheck'] = true;
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-app.php');

$aOut = array();
$aOut['user'] = $CONFIG['user'];
$aOut['text'] = $TEXT;

echo json_encode($aOut)
?>