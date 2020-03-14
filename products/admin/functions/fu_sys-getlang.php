<?php
if(!isset($CONFIG['USER']['systemlang'])) $CONFIG['USER']['systemlang'] = '';

if($CONFIG['USER']['systemlang'] == ""){
	$aFileLang = getLang();
	
	$CONFIG['USER']['systemlang'] = strtoupper($CONFIG['system']['countDefaultAdmin']) . '_' . strtolower($CONFIG['system']['langDefaultAdmin']);
	
	if(!file_exists($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathAdmin'] . 'i18n/' . $CONFIG['USER']['systemlang'] . '.lang')){
		$CONFIG['USER']['systemlang'] = strtolower($CONFIG['system']['langDefaultAdmin']);
	}
	if(!file_exists($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathAdmin'] . 'i18n/' . $CONFIG['USER']['systemlang'] . '.lang')){
		$CONFIG['USER']['systemlang'] = 'ALL_all';
	}
	if(!file_exists($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathAdmin'] . 'i18n/' . $CONFIG['USER']['systemlang'] . '.lang')){
		$CONFIG['USER']['systemlang'] = 'all';
	}

	if(is_array($aFileLang)){
		if(!isset($aFileLang[1])) $aFileLang[1] = strtoupper($aFileLang[0]);
		if(file_exists($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathAdmin'] . 'i18n/' . strtoupper($aFileLang[1]) . '_' . strtolower($aFileLang[0]) . '.lang')){
			$CONFIG['USER']['systemlang'] = strtoupper($aFileLang[1]) . '_' . strtolower($aFileLang[0]);
		}else if(file_exists($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathAdmin'] . 'i18n/' . strtolower($aFileLang[0]) . '.lang')){
			$CONFIG['USER']['systemlang'] = strtolower($aFileLang[0]);
		}
	} 
}


$TEXT = array();
$file_contents = file_get_contents($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathAdmin'] . 'i18n/' . $CONFIG['USER']['systemlang'] . '.lang');
$TEXT = unserialize(gzuncompress($file_contents));




function getLang() {
    global $CONFIG;
	
	if(!isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]) || empty($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
        return $CONFIG['system']['langDefaultAdmin'];
    }
 
    $accept = preg_split("{,\s*}", $_SERVER["HTTP_ACCEPT_LANGUAGE"]);
    $lang = $CONFIG['system']['langDefaultAdmin'];
    $quality = 0;
 
    if(is_array($accept) && (count($accept) > 0)) {
        foreach($accept as $key => $value) {
            if(!preg_match("{^([a-z]{1,8}(?:-[a-z]{1,8})*)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$}i", $value, $matches)) {
                continue;
            }
 
            $code = explode("-", $matches[1]);
 
            if(isset($matches[2])) {
                $priority = floatval($matches[2]);
            } else {
                $priority = 1.0;
            }
 
            while(count($code) > 0) {
                if($priority > $quality) {
                    $lang = $code;
                    $quality = $priority;
 
                    break;
                }
 
                break;
            }
        }
    }
 
    return $lang;
}


?>