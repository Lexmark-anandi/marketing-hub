<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');

//getConnection(0); 
$varSQL = getPostData(); 
 
  

$str = '<select><option value="">' . $TEXT['All'] . '</option>';
$str .= '<option value=".">' . $TEXT['dot'] . ' (.)</option>';
$str .= '<option value=",">' . $TEXT['comma'] . ' (,)</option>';
$str .= '</select>';

    
echo $str;


?>