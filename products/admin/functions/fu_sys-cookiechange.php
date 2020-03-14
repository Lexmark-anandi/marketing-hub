<?php
function changeCookie($name='', $aChange=array(), $aConfigCookie=array('expire'=>0, 'path'=>'/admin/', 'httponly'=>false)){ 
	global $CONFIG, $TEXT; 

	$aCookie = json_decode($_COOKIE[$name], true);	
	foreach($aChange as $key => $val){
		$aCookie[$key] = $val;
		$CONFIG['USER'][$key] = $val;
	}
	setcookie($name, json_encode($aCookie), $aConfigCookie['expire'], $aConfigCookie['path'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], $aConfigCookie['httponly']);
}
?>