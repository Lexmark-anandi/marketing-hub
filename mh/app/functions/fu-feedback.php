<?php
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-app.php');
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');


$queryI = $CONFIG['dbconn'][0]->prepare('
									INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_feedback
									(id_pcid, id_ppid, scale, additional, create_at)
									VALUES
									(:id_pcid, :id_ppid, :scale, :additional, :create_at)
									');
$queryI->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
$queryI->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryI->bindValue(':scale', $varSQL['feedbackRange'], PDO::PARAM_INT);
$queryI->bindValue(':additional', $varSQL['feedbackAdditional'], PDO::PARAM_STR);
$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
$queryI->execute();


$queryN = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country,
										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
									FROM ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni 
									
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
										ON ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
									
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.id_ppid = (:id_ppid)
									');
$queryN->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryN->execute();
$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
$numN = $queryN->rowCount();
$COUNTRY = $rowsN[0]['country'];
$LANGUAGE = $rowsN[0]['language'];


#####################################################
// sending notification
#####################################################
// select notification
$queryN = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_emailnotifications_uni.subject,
										' . $CONFIG['db'][0]['prefix'] . '_emailnotifications_uni.notification
									FROM ' . $CONFIG['db'][0]['prefix'] . '_emailnotifications_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_emailnotifications_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_emailnotifications_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_emailnotifications_uni.id_dev = (:id_dev)
										AND ' . $CONFIG['db'][0]['prefix'] . '_emailnotifications_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_emailnotifications_uni.id_enid = (:id_enid)
									');
$queryN->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryN->bindValue(':id_count', 0, PDO::PARAM_INT);
$queryN->bindValue(':id_lang', 0, PDO::PARAM_INT);
$queryN->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryN->bindValue(':id_enid', 4, PDO::PARAM_INT);
$queryN->execute();
$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
$numN = $queryN->rowCount();

$RATE = $varSQL['feedbackRange'];
$ADDITIONAL = $varSQL['feedbackAdditional'];

$recipientsCC = array();

$mailing = array();
$recipients = array();
$recipients['souravg@lexmark.com'] = 'Sourav ghosh';
//$recipients['cschleic@lexmark.com'] = 'Christian Schleich';
//$recipients['hebbel.t@online.de'] = 'Christian Schleich';

// Message
$mailing['subject'] = $rowsN[0]['subject'];
$mailing['body'] = '' . nl2br($rowsN[0]['notification']) . '';

$mailing['body'] = str_replace('##COUNTRY##', $COUNTRY, $mailing['body']);
$mailing['body'] = str_replace('##LANGUAGE##', $LANGUAGE, $mailing['body']);
$mailing['body'] = str_replace('##RATE##', $RATE, $mailing['body']);
$mailing['body'] = str_replace('##ADDITIONAL##', $ADDITIONAL, $mailing['body']);

mailSend(1, $mailing, $recipients, $recipientsCC);

?>