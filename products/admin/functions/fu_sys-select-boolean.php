<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');
$varSQL = getPostData();
$CONFIG['page'] = json_decode($varSQL['pageConfig'], true);
 
 
$str = '<select><option value="">' . $TEXT['All'] . '</option>';
$str .= '<option value="1">' . $TEXT['yes'] . '</option>';
$str .= '<option value="2">' . $TEXT['no'] . '</option>';
$str .= '</select>';

    
echo $str;


?>