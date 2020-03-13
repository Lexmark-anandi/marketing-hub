<?php
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
ini_set("memory_limit", "512M");
ini_set("max_execution_time", "12000");

getConnection(0); 

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




	$dateS = new DateTime();
	$nowS = $dateS->format('Y-m-d-H-i-s');
	$subfolder = 'a'.$nowS;
	mkdir('../admin/tmp/cron/' . $subfolder, 0777);
	chmod('../admin/tmp/cron/' . $subfolder, 0777);



include_once($CONFIG['system']['directoryRoot'] . 'cronjobs/db-backup.php');

include_once($CONFIG['system']['directoryRoot'] . 'cronjobs/products-sync.php');
include_once($CONFIG['system']['directoryRoot'] . 'cronjobs/prodtypes-sync.php');
//include_once($CONFIG['system']['directoryRoot'] . 'cronjobs/productimages-sync.php');
include_once($CONFIG['system']['directoryRoot'] . 'cronjobs/kiado-sync.php');
include_once($CONFIG['system']['directoryRoot'] . 'cronjobs/products-sync-withdrawn.php');
include_once($CONFIG['system']['directoryRoot'] . 'cronjobs/products-set-announce.php');




	$dateS = new DateTime();
	$nowS = $dateS->format('Y-m-d-H-i-s');
	$subfolder = 'z'.$nowS;
	mkdir('../admin/tmp/cron/' . $subfolder, 0777);
	chmod('../admin/tmp/cron/' . $subfolder, 0777);


?>