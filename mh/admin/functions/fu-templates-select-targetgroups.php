<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();
if(!isset($varSQL['mode'])) $varSQL['mode'] = 'grid';

$str = '<select>';
$str .= '<option value="">' . $TEXT['All'] . '</option>';
$str .= '<option value="2">All Partners</option>';
$str .= '<option value="1">Business Solutions only</option>';
$str .= '<option value="3">Distribution only</option>';
$str .= '</select>';
    
echo $str;

