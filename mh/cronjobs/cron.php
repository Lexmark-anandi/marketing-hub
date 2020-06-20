<?php
$time_start = microtime(true);

echo date_default_timezone_get();
$currenttime = date('h:i:s:u');
list($hrs,$mins,$secs,$msecs) = explode(':',$currenttime);
echo " START => $hrs:$mins:$secs\n";


include_once(__DIR__ . '/../config-all.php');
include_once(__DIR__ . '/../custom/config-all-custom.php');

include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-connect.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-local-variations.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-insert-all.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'zip/pclzip.lib.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'PHPMailer-5.2.13/class.phpmailer.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'PHPMailer-5.2.13/class.pop3.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'PHPMailer-5.2.13/class.smtp.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathTools'] . 'PDFInfo/PDFInfo.php');

$CONFIG['user']['id_real'] = 0;
$CONFIG['user']['activeClient'] = 1;
$CONFIG['user']['restricted_all'] = 0;
$CONFIG['user']['specifications'][14] = 9;
$CONFIG['activeSettings']['id_clid'] = 1;
$mediaPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'];

ini_set("display_errors", "off");
ini_set("memory_limit", "4000M");
ini_set("max_execution_time", "12000");

getConnection(0);

/*$query_select_mh = $CONFIG['dbconn'][0]->prepare('SELECT status FROM ' . $CONFIG['db'][0]['prefix'] .'_cron_status');
$query_select_mh->execute();
$rowmh = $query_select_mh->fetch(PDO::FETCH_ASSOC);

$query_select_product = $CONFIG['dbconn'][0]->prepare('SELECT status FROM products.200_cron_status');
$query_select_product->execute();
$rowproduct = $query_select_product->fetch(PDO::FETCH_ASSOC);

if(($rowmh['status'] == 0) && ($rowproduct['status'] == 0))
{
	
$query_update = $CONFIG['dbconn'][0]->prepare('UPDATE ' . $CONFIG['db'][0]['prefix'] .'_cron_status SET status = 1');
$query_update->execute();*/


// Set Config Mailings
$queryM = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.pop_server,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.smtp_server,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.smtp_user,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.smtp_password,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.smtp_auth,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.sender_email,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.sender_name,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.legal_notices
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.id_dev = (:id_dev)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.id_cl IN(0,' . $CONFIG['activeSettings']['id_clid'] . ')
									');
$queryM->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryM->bindValue(':id_count', 0, PDO::PARAM_INT);
$queryM->bindValue(':id_lang', 0, PDO::PARAM_INT);
$queryM->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryM->execute();
$rowsM = $queryM->fetchAll(PDO::FETCH_ASSOC);
$numM = $queryM->rowCount();

$CONFIG['mail'][0]['pop_server'] = $rowsM[0]['pop_server'];
$CONFIG['mail'][0]['smtp_server'] = $rowsM[0]['smtp_server'];
$CONFIG['mail'][0]['smtp_user'] = $rowsM[0]['smtp_user'];
$CONFIG['mail'][0]['smtp_password'] = $rowsM[0]['smtp_password'];
$CONFIG['mail'][0]['smtp_auth'] = false;
if($rowsM[0]['smtp_auth'] == 1) $CONFIG['mail'][0]['smtp_auth'] = true;
$CONFIG['mail'][0]['sender_email'] = $rowsM[0]['sender_email'];
$CONFIG['mail'][0]['sender_name'] = $rowsM[0]['sender_name'];
$CONFIG['mail'][0]['legal_notices'] = $rowsM[0]['legal_notices'];




	/*$dateS = new DateTime();
	$nowS = $dateS->format('Y-m-d-H-i-s');
	$subfolder = 'a'.$nowS;
	mkdir('../admin/tmp/cron/' . $subfolder, 0777);
	chmod('../admin/tmp/cron/' . $subfolder, 0777);*/


echo "\nStarts MH DB Backup"."\n";
include_once($CONFIG['system']['directoryRoot'] . 'cronjobs/db-backup.php');
echo "\nStarts products-sync.php"."\n";
include_once($CONFIG['system']['directoryRoot'] . 'cronjobs/products-sync.php');
echo "Starts prodtypes-sync.php"."\n";
include_once($CONFIG['system']['directoryRoot'] . 'cronjobs/prodtypes-sync.php');

//echo "Starts kiado-sync.php"."\n";
//include_once($CONFIG['system']['directoryRoot'] . 'cronjobs/kiado-sync.php');

echo "Starts products-sync-withdrawn.php"."\n";
include_once($CONFIG['system']['directoryRoot'] . 'cronjobs/products-sync-withdrawn.php');

echo "Starts products-set-announce.php"."\n";
include_once($CONFIG['system']['directoryRoot'] . 'cronjobs/products-set-announce.php');




	/*$dateS = new DateTime();
	$nowS = $dateS->format('Y-m-d-H-i-s');
	$subfolder = 'z'.$nowS;
	mkdir('../admin/tmp/cron/' . $subfolder, 0777);
	chmod('../admin/tmp/cron/' . $subfolder, 0777);*/

/*echo '\nUPDATE ' . $CONFIG['db'][0]['prefix'] .'_cron_status SET status = 0';
$query_update = $CONFIG['dbconn'][0]->prepare('UPDATE ' . $CONFIG['db'][0]['prefix'] .'_cron_status SET status = 0');
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