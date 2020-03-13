<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();
if(!isset($varSQL['mode'])) $varSQL['mode'] = 'grid';

$str = '';
$str .= '<select>';
$str .= '<option value="">' . $TEXT['All'] . '</option>';
$str .= '<option value="1">' . $TEXT['yes'] . '</option>';
$str .= '<option value="2">' . $TEXT['no'] . '</option>';
$str .= '</select>';
    
echo $str;
?>