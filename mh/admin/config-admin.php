<?php
ini_set("display_errors", "off");
ini_set("memory_limit", "4000M");
//ini_set("max_execution_time", "6000");

$CONFIG['page'] = (isset($_SERVER['HTTP_PAGE'])) ? json_decode($_SERVER['HTTP_PAGE'], true) : array();
$CONFIG['settings'] = (isset($_SERVER['HTTP_SETTINGS'])) ? json_decode($_SERVER['HTTP_SETTINGS'], true) : array();

include_once(__DIR__ . '/../config-all.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathCustom'] . 'config-all-custom.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-connect.php');
getConnection(0); 

//if(!isset($CONFIG['activeSettings'])){
//	$CONFIG['activeSettings'] = array();
//	$CONFIG['activeSettings']['id_clid'] = 0;
//	$CONFIG['activeSettings']['id_countid'] = 0;
//	$CONFIG['activeSettings']['id_langid'] = 0;
//	$CONFIG['activeSettings']['id_devid'] = 0;
//	$CONFIG['activeSettings']['id_sys_count'] = 0;
//	$CONFIG['activeSettings']['id_sys_lang'] = 0;
//	$CONFIG['activeSettings']['id_sys_dev'] = 0;
//	$CONFIG['activeSettings']['systemLanguage'] = $CONFIG['system']['langDefaultAdmin'];
//	$CONFIG['activeSettings']['gridNumRows'] = 10;
//}
if(isset($_COOKIE['activesettings'])) $CONFIG['activeSettings'] = json_decode($_COOKIE['activesettings'],true);	

if(!isset($CONFIG['noCheck'])) $CONFIG['noCheck'] = false;
if(!isset($CONFIG['initLogin'])) $CONFIG['initLogin'] = false;
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-cookiechange.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-getlang.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-accesstoken-create.php');
if($CONFIG['noCheck'] != true) include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-access.php'); 
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-cryption.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-userinit.php'); 


if(count($CONFIG['page']) > 0){
	$CONFIG['aModul'] = ($CONFIG['page']['id_mod_parent'] == 0) ? $CONFIG['user']['pages2moduls'][$CONFIG['page']['id_page']]['moduls']['i_' . $CONFIG['page']['id_mod']] : $CONFIG['user']['childmoduls'][$CONFIG['page']['id_mod_parent']]['i_' . $CONFIG['page']['id_mod']];
}else{
	$CONFIG['aModul'] = array();
}


include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-magicquotes.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-setvalues-read.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-setvalues-save.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-setvalues-check.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-local-variations.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-parse-variables.php');
//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-localization.php');
//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-localization-system.php');
//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-uploadsize.php');
//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-modulname.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-check-changes.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-insert-all.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-formcheck.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-delete-record.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-picturesize.php');
//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-mail-create.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-mail-send.php');

//###########################################################################
//if(isset($_COOKIE['access'])){
//	$aChangeCookie = array();
//	if(isset($CONFIG['activeSettings']['id_countid'])) $aChangeCookie['id_countid'] = $CONFIG['activeSettings']['id_countid'];
//	if(isset($CONFIG['activeSettings']['id_langid'])) $aChangeCookie['id_langid'] = $CONFIG['activeSettings']['id_langid'];
//	if(isset($CONFIG['activeSettings']['id_devid'])) $aChangeCookie['id_devid'] = $CONFIG['activeSettings']['id_devid'];
//	if(isset($CONFIG['activeSettings']['id_sys_count'])) $aChangeCookie['id_sys_count'] = $CONFIG['activeSettings']['id_sys_count'];
//	if(isset($CONFIG['activeSettings']['id_sys_lang'])) $aChangeCookie['id_sys_lang'] = $CONFIG['activeSettings']['id_sys_lang'];
//	if(isset($CONFIG['activeSettings']['id_sys_dev'])) $aChangeCookie['id_sys_dev'] = $CONFIG['activeSettings']['id_sys_dev'];
//	if(isset($CONFIG['activeSettings']['id_countid_form'])) $aChangeCookie['id_countid_form'] = $CONFIG['activeSettings']['id_countid_form'];
//	if(isset($CONFIG['activeSettings']['id_langid_form'])) $aChangeCookie['id_langid_form'] = $CONFIG['activeSettings']['id_langid_form'];
//	if(isset($CONFIG['activeSettings']['id_devid_form'])) $aChangeCookie['id_devid_form'] = $CONFIG['activeSettings']['id_devid_form'];
//	if(isset($CONFIG['activeSettings']['id_sys_count_form'])) $aChangeCookie['id_sys_count_form'] = $CONFIG['activeSettings']['id_sys_count_form'];
//	if(isset($CONFIG['activeSettings']['id_sys_lang_form'])) $aChangeCookie['id_sys_lang_form'] = $CONFIG['activeSettings']['id_sys_lang_form'];
//	if(isset($CONFIG['activeSettings']['id_sys_dev_form'])) $aChangeCookie['id_sys_dev_form'] = $CONFIG['activeSettings']['id_sys_dev_form'];
//	if(isset($CONFIG['activeSettings']['id_clid'])) $aChangeCookie['id_clid'] = $CONFIG['activeSettings']['id_clid'];
//	if(isset($CONFIG['activeSettings']['id_page'])) $aChangeCookie['id_page'] = $CONFIG['activeSettings']['id_page']; 
//	if(isset($CONFIG['activeSettings']['systemLanguage'])) $aChangeCookie['systemLanguage'] = $CONFIG['activeSettings']['systemLanguage'];
//	if(count($aChangeCookie) > 0) changeCookie($name='activesettings', $aChangeCookie);
//}
//###########################################################################



include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'phpass-0.3/PasswordHash.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'zip/pclzip.lib.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'PHPMailer-5.2.13/class.phpmailer.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'PHPMailer-5.2.13/class.pop3.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'PHPMailer-5.2.13/class.smtp.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'PHPExcel-1.8.1/Classes/PHPExcel/IOFactory.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'PDFInfo/PDFInfo.php');


?>