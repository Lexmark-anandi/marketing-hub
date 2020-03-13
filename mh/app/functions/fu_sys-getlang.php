<?php
if(!isset($CONFIG['activeSettings']['appLanguage'])) $CONFIG['activeSettings']['appLanguage'] = '';

if($CONFIG['activeSettings']['appLanguage'] == '' || !file_exists($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathApp'] . 'i18n/' . $CONFIG['activeSettings']['appLanguage'] . '.lang')){
	$CONFIG['activeSettings']['appLanguage'] = 'all';

	$aChangeCookie = array();
	if(isset($CONFIG['activeSettings']['appLanguage'])) $aChangeCookie['appLanguage'] = $CONFIG['activeSettings']['appLanguage'];
	if(count($aChangeCookie) > 0) changeCookie($name='activesettings', $aChangeCookie);
}

$TEXT = array();
$file_contents = file_get_contents($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathApp'] . 'i18n/' . $CONFIG['activeSettings']['appLanguage'] . '.lang');
$TEXT = unserialize(gzuncompress($file_contents));




?>