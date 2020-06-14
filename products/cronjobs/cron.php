<?php
$time_start = microtime(true);

echo date_default_timezone_get();
$currenttime = date('h:i:s:u');
list($hrs,$mins,$secs,$msecs) = explode(':',$currenttime);
echo " START => $hrs:$mins:$secs\n";


$CONFIG['system']['pathInclude'] = "../";
include_once($CONFIG['system']['pathInclude'] . "includes/config-all.php");
include_once($CONFIG['system']['pathInclude'] . "custom/config-all-custom.php");
include_once($CONFIG['system']['pathInclude'] . 'includes/connect.php');
include_once($CONFIG['system']['pathInclude'] . 'includes/zip/pclzip.lib.php');
include_once($CONFIG['system']['pathInclude'] . 'includes/PHPMailer-5.2.13/class.phpmailer.php');
include_once($CONFIG['system']['pathInclude'] . 'includes/PHPMailer-5.2.13/class.pop3.php');
include_once($CONFIG['system']['pathInclude'] . 'includes/PHPMailer-5.2.13/class.smtp.php');

$CONFIG['USER']['id_real'] = 0;
$CONFIG['USER']['activeClient'] = 0;


//ini_set("display_errors", "on");
//ini_set("memory_limit", "512M");
//ini_set("max_execution_time", "6000");

getConnection(0); 

/*$query_select_product = $CONFIG['dbconn']->prepare('SELECT status FROM ' . $CONFIG['db'][0]['prefix'] .'_cron_status');
$query_select_product->execute();
$rowproduct = $query_select_product->fetch(PDO::FETCH_ASSOC);

$query_select_mh = $CONFIG['dbconn']->prepare('SELECT status FROM mh.200_cron_status');
$query_select_mh->execute();
$rowmh = $query_select_mh->fetch(PDO::FETCH_ASSOC);

if(($rowproduct['status'] == 0) && ($rowmh['status'] == 0))
{
	
$query_update = $CONFIG['dbconn']->prepare('UPDATE ' . $CONFIG['db'][0]['prefix'] .'_cron_status SET status = 1');
$query_update->execute();*/

// Set Config Mailings
$queryM = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.pop_server,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.smtp_server,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.smtp_user,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.smtp_password,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.smtp_auth,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.sender_email,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.sender_name
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.id_dev = (:id_dev)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.id_clid = (:id_clid)
									');
$queryM->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryM->bindValue(':id_count', 0, PDO::PARAM_INT);
$queryM->bindValue(':id_lang', 0, PDO::PARAM_INT);
$queryM->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryM->bindValue(':id_clid', 1, PDO::PARAM_INT);
$queryM->execute();
$rowsM = $queryM->fetchAll(PDO::FETCH_ASSOC);
$numM = $queryM->rowCount();

$CONFIG['system']['pop_server'] = $rowsM[0]['pop_server'];
$CONFIG['system']['smtp_server'] = $rowsM[0]['smtp_server'];
$CONFIG['system']['smtp_user'] = $rowsM[0]['smtp_user'];
$CONFIG['system']['smtp_password'] = $rowsM[0]['smtp_password'];
$CONFIG['system']['smtp_auth'] = false;
if($rowsM[0]['smtp_auth'] == 1) $CONFIG['system']['smtp_auth'] = true;
$CONFIG['system']['sender_email'] = $rowsM[0]['sender_email'];
$CONFIG['system']['sender_name'] = $rowsM[0]['sender_name'];



include_once($CONFIG['system']['pathInclude'] . "cronjobs/db-backup.php");

include_once($CONFIG['system']['pathInclude'] . "cronjobs/import-lpmd.php");
//include_once($CONFIG['system']['pathInclude'] . "cronjobs/setimages.php");

/*$query_update = $CONFIG['dbconn']->prepare('UPDATE ' . $CONFIG['db'][0]['prefix'] .'_cron_status SET status = 0');
$query_update->execute();*/

$time_end = microtime(true);

echo date_default_timezone_get();
$currenttime = date('h:i:s:u');
list($hrs,$mins,$secs,$msecs) = explode(':',$currenttime);
echo " END => $hrs:$mins:$secs\n";

$execution_time = ($time_end - $time_start)/60;
echo "Total Execution Time :: ".$execution_time." Mins.";
//}
?>