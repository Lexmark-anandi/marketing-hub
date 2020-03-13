<?php
ini_set("display_errors", "off");
ini_set("memory_limit", "512M");
//ini_set("max_execution_time", "6000");

include_once(__DIR__ . '/../config-all.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathCustom'] . 'config-all-custom.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-connect.php');
getConnection(0); 

if(isset($_COOKIE['activesettings'])) $CONFIG['activeSettings'] = json_decode($_COOKIE['activesettings'],true);	

if(!isset($CONFIG['noCheck'])) $CONFIG['noCheck'] = false;
if(!isset($CONFIG['initLogin'])) $CONFIG['initLogin'] = false;
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsApp'] . 'fu_sys-cookiechange.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsApp'] . 'fu_sys-getlang.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsApp'] . 'fu_sys-accesstoken-create.php');
if($CONFIG['noCheck'] != true) include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsApp'] . 'fu_sys-usercheck-access.php'); 
//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-cryption.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsApp'] . 'fu_sys-userinit.php'); 

$CONFIG['activeSettings']['id_clid'] = 1;

//if(count($CONFIG['page']) > 0){
//	$CONFIG['aModul'] = ($CONFIG['page']['id_mod_parent'] == 0) ? $CONFIG['user']['pages2moduls'][$CONFIG['page']['id_page']]['moduls']['i_' . $CONFIG['page']['id_mod']] : $CONFIG['user']['childmoduls'][$CONFIG['page']['id_mod_parent']]['i_' . $CONFIG['page']['id_mod']];
//}else{
//	$CONFIG['aModul'] = array();
//}


include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-magicquotes.php');
//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-setvalues-read.php');
//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-setvalues-save.php');
//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-setvalues-check.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-local-variations.php');
//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-parse-variables.php');
////include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-localization.php');
////include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-localization-system.php');
////include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-uploadsize.php');
////include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-modulname.php');
//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-check-changes.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-insert-all.php');
//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-formcheck.php');
//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-delete-record.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-picturesize.php');
////include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-mail-create.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-mail-send.php');
//
//
//
//
//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'phpass-0.3/PasswordHash.php');
//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'zip/pclzip.lib.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'PHPMailer-5.2.13/class.phpmailer.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'PHPMailer-5.2.13/class.pop3.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'PHPMailer-5.2.13/class.smtp.php');
//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'PHPExcel-1.8.1/Classes/PHPExcel/IOFactory.php');
//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'PDFInfo/PDFInfo.php');


$CONFIG['system']['ovRange'] = array(10, 20, 50);
$CONFIG['system']['exportExCobranding'] = array(4,5);
$CONFIG['aProgramm'] = [];
$CONFIG['aProgramm']['Commercial'] = ['Commercial Authorized', 'Commercial Silver', 'Commercial Gold', 'Commercial Diamond'];
$CONFIG['aProgramm']['BSD'] = ['Business Solutions Silver', 'Business Solutions Gold', 'Business Solutions Diamond'];
$CONFIG['aProgramm']['Distribution'] = ['Distribution Authorized'];

?>