<?php
function changeCookie($name='', $aChange=array(), $aConfigCookie=array(), $arrayName='activeSettings'){ 
	global $CONFIG, $TEXT; 

	if(!isset($aConfigCookie['expire'])) $aConfigCookie['expire'] = 0;
	if(!isset($aConfigCookie['path'])) $aConfigCookie['path'] = $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathApp'];
	if(!isset($aConfigCookie['domain'])) $aConfigCookie['domain'] = $_SERVER['HTTP_HOST'];
	if(!isset($aConfigCookie['secure'])) $aConfigCookie['secure'] = $CONFIG['system']['cookie_secure'];
	if(!isset($aConfigCookie['httponly'])) $aConfigCookie['httponly'] = false;
	
	$aCookie = array();
	if(isset($_COOKIE[$name])) $aCookie = json_decode($_COOKIE[$name], true);	
	foreach($aChange as $key => $val){
		$aCookie[$key] = $val;
		$CONFIG[$arrayName][$key] = $val;
	}
	setcookie($name, json_encode($aCookie), $aConfigCookie['expire'], $aConfigCookie['path'], $aConfigCookie['domain'], $aConfigCookie['secure'], $aConfigCookie['httponly']);
}
?>