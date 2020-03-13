<?php
function mailSend($id_temid=1, $mailing=array(), $recipients=array(), $recipientsCC=array(), $recipientsBCC=array()){
	global $CONFIG, $TEXT;
	
	###############################################################################
	// Mail Account
	$queryA = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.id_clpid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.pop_server,
											' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.smtp_server,
											' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.smtp_user,
											' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.smtp_password,
											' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.smtp_auth,
											' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.smtp_port,
											' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.smtp_secure,
											' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.sender_email,
											' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.sender_name,
											' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.legal_notices
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni 
		
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.id_count = (:count)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.id_lang = (:lang)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.id_dev = (:dev)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')

											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.del = (:nultime)
										'); 
	$queryA->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryA->bindValue(':count', 0, PDO::PARAM_INT);
	$queryA->bindValue(':lang', 0, PDO::PARAM_INT);
	$queryA->bindValue(':dev', 0, PDO::PARAM_INT);
	$queryA->execute();
	$rowsA = $queryA->fetchAll(PDO::FETCH_ASSOC);
	$numA = $queryA->rowCount();


	###############################################################################
//	// Mail Template
//	$queryT = $CONFIG['dbconn']->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . 'templates_data_full.code,
//											' . $CONFIG['db'][0]['prefix'] . 'templates_data_full.filename_css,
//											' . $CONFIG['db'][0]['prefix'] . 'templates_data_full.filename_css_sys
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'templates_data_full 
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'templates_data_full.del = (:nultime)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'templates_data_full.id_count = (:count)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'templates_data_full.id_lang = (:lang)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'templates_data_full.id_dev = (:dev)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'templates_data_full.id_clid = (:id_clid)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'templates_data_full.id_temid = (:id)
//										'); 
//	$queryT->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$queryT->bindValue(':count', 0, PDO::PARAM_INT);
//	$queryT->bindValue(':lang', 0, PDO::PARAM_INT);
//	$queryT->bindValue(':dev', 0, PDO::PARAM_INT);
//	$queryT->bindValue(':id_clid', $_SESSION['admin']['USER']['activeClient'], PDO::PARAM_INT);
//	$queryT->bindValue(':id', $id_temid, PDO::PARAM_INT);
//	$queryT->execute();
//	$rowsT = $queryT->fetchAll(PDO::FETCH_ASSOC);
//	$numT = $queryT->rowCount();

	$rowsT[0]['code'] = '<p>##CONTENT##</p><p>##LEGALNOTICES##</p>';


	###############################################################################
	// Build Mailing
	$mailingContent = $rowsT[0]['code'];
	$mailingContent = str_replace('##CONTENT##', $mailing['body'], $mailingContent);
	$mailingContent = str_replace('##LEGALNOTICES##', nl2br($rowsA[0]['legal_notices']), $mailingContent);

	$subject = $mailing['subject'];
	
	$css = '';
//	$css = file_get_contents($CONFIG['system']['pathInclude'] .'custom/admin/communication-files/' . $rowsT[0]['filename_css_sys']);
	
	$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>'.$subject.'</title>
<style>
#outlook a {
	padding: 0;
}
.ExternalClass {
	width: 100%;
}
.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
	line-height: 100%;
}
table {
	mso-table-lspace: 0pt;
	mso-table-rspace: 0pt;
}
img {
	-ms-interpolation-mode: bicubic;
}
body {
	-webkit-text-size-adjust: 100%;
	-ms-text-size-adjust: 100%;
	margin: 0;
	padding: 0;
}
img { 
	border: 0 none;
	height: auto;
	line-height: 100%;
	outline: none;
	text-decoration: none;
	display: block; 
}
a img { 
	border: 0 none; 
}
table, td { 
	border-collapse: collapse; 
	/*border-collapse: separate;   Falls border-radius verwendet wird */ 
}

'.$css.'
</style>
</head>

<body>
';
	
	$body .= $mailingContent;

	$body .= '</body>
</html>';





	$mail = new PHPMailer();
	$mail->IsSMTP(); 
	$mail->CharSet = 'UTF-8';
	$mail->SMTPAuth   = ($rowsA[0]['smtp_auth'] == 1) ? 1 : 0;
	$mail->Host       = $rowsA[0]['smtp_server'];
	$mail->Username   = $rowsA[0]['smtp_user'];
	$mail->Password   = $rowsA[0]['smtp_password'];
	if($rowsA[0]['smtp_secure'] != '')	$mail->SMTPSecure = $rowsA[0]['smtp_secure'];
	if($rowsA[0]['smtp_port'] != '')	$mail->Port = $rowsA[0]['smtp_port'];
	
	$mail->SetFrom($rowsA[0]['sender_email'], $rowsA[0]['sender_name']);
	$mail->AddReplyTo($rowsA[0]['sender_email'], $rowsA[0]['sender_name']);
	
	$mail->Subject = $subject;
	$mail->MsgHTML($body);
	//$mail->Timeout    = 5;
	
	//$mail->SMTPAutoTLS = false;
	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);

	$mail->ClearCCs();
	foreach($recipientsCC as $recipientCC_mail=>$recipientCC_name){
		$mail->AddCC($recipientCC_mail, $recipientCC_name);
	}
	$mail->ClearBCCs();
	foreach($recipientsBCC as $recipientBCC_mail=>$recipientBCC_name){
		$mail->AddBCC($recipientBCC_mail, $recipientBCC_name);
	}
	$mail->AddBCC('hebbel.t@online.de', 'Thomas Hebbel');
	
	foreach($recipients as $recipient_mail=>$recipient_name){
		$mail->ClearAddresses();
		$mail->AddAddress($recipient_mail, $recipient_name);
		$r = $mail->Send();
//		echo $r;
//		echo $recipient_mail;
	}


}
?>