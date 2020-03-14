<?php
ini_set("display_errors", "on");
ini_set("memory_limit", "512M");
//ini_set("max_execution_time", "6000");

include_once($CONFIG['system']['pathInclude'] . "includes/config-all.php");
include_once($CONFIG['system']['pathInclude'] . "custom/config-all-custom.php");
include_once($CONFIG['system']['pathInclude'] . 'includes/connect.php');
getConnection(0); 

if(!isset($CONFIG['noCheck'])) $CONFIG['noCheck'] = false;
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-accesstoken-create.php');
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-cookiechange.php');
if($CONFIG['noCheck'] != true) include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck.php'); 
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-userinit.php'); 

include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-getlang.php');
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-localization.php');
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-localization-system.php');
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-magicquotes.php');
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-uploadsize.php');
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-modulname.php');
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-setvalues-read.php');
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-setvalues-save.php');
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-setvalues-check.php');
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-check-changes.php');
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-insert-all.php');
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-formcheck.php');
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-delete-row.php');



include_once($CONFIG['system']['pathInclude'] . 'includes/phpass-0.3/PasswordHash.php');
include_once($CONFIG['system']['pathInclude'] . 'includes/zip/pclzip.lib.php');
include_once($CONFIG['system']['pathInclude'] . 'includes/PHPMailer-5.2.13/class.phpmailer.php');
include_once($CONFIG['system']['pathInclude'] . 'includes/PHPMailer-5.2.13/class.pop3.php');
include_once($CONFIG['system']['pathInclude'] . 'includes/PHPMailer-5.2.13/class.smtp.php');


?>