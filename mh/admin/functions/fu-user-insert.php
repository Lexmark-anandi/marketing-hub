<?php
$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$aT = array('', 'ext', 'loc', 'uni');
	

$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
									');
$query->bindValue(':id', 0, PDO::PARAM_INT);
$query->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
$query->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

foreach($rows as $row){
	$aData = json_decode($row['data'], true);
	$aData['id_count'] = $row['id_count'];
	$aData['id_lang'] = $row['id_lang'];
	$aData['id_dev'] = $row['id_dev'];
	$aData['id_cl'] = $row['id_cl'];

	$passOrg = $aData['password'];

	if($aData['password'] != ""){
		$t_hasher = new PasswordHash(8, false);
		$aData['password'] = $t_hasher->HashPassword($aData['password']);
	}  

	$query = $CONFIG['dbconn'][0]->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_user
										(
											firstname,
											lastname,
											email,
											id_r,											
											id_geoid,											
											username,											
											password,	
											active_client,										
											system_country,										
											system_language,										
											create_at,    
											create_from, 
											change_from
										)
										VALUES
										(
											:firstname,
											:lastname,
											:email,
											:id_r,
											:id_geoid,
											:username,
											:password,
											:active_client,										
											:system_country,										
											:system_language,										
											:create_at, 
											:create_from, 
											:create_from)
										');
	$query->bindValue(':firstname', $aData['firstname'], PDO::PARAM_STR);
	$query->bindValue(':lastname', $aData['lastname'], PDO::PARAM_STR);
	$query->bindValue(':email', $aData['email'], PDO::PARAM_STR);
	$query->bindValue(':id_r', $aData['id_r'], PDO::PARAM_INT);
	$query->bindValue(':id_geoid', $aData['id_geoid'], PDO::PARAM_INT);
	$query->bindValue(':username', $aData['username'], PDO::PARAM_STR);
	$query->bindValue(':password', $aData['password'], PDO::PARAM_STR);
	$query->bindValue(':active_client', $aData['id_cl'], PDO::PARAM_INT);
	$query->bindValue(':system_country', $CONFIG['system']['countDefaultAdminId'], PDO::PARAM_INT);
	$query->bindValue(':system_language', $CONFIG['system']['langDefaultAdmin'], PDO::PARAM_STR);
	$query->bindValue(':create_at', $now, PDO::PARAM_STR);
	$query->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
	$query->execute();
	$idNew = $CONFIG['dbconn'][0]->lastInsertId();

		
	$query = $CONFIG['dbconn'][0]->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients
										(id_uid, id_clid)
										VALUES
										(:id, :id_cl)
										');
	$query->bindValue(':id', $idNew, PDO::PARAM_INT);
	$query->bindValue(':id_cl', $aData['id_cl'], PDO::PARAM_INT);
	$query->execute();




	#################################################
	
	$query = $CONFIG['dbconn'][0]->prepare('
										DELETE ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages 
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_count2lang = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid = (:id_uid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang IN ('. implode(',', $CONFIG['user']['count2lang']) . ')
										');
	$query->bindValue(':id_uid', $idNew, PDO::PARAM_INT);
	$query->execute();
	
	if($aData['id_r'] == 3){
		$queryC = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang
											FROM ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
												ON ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid <> (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.del = (:nultime)
											 
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.id_geoid = (:id_geoid)
												AND ' . $CONFIG['db'][0]['prefix'] . '_geographies2countries_uni.del = (:nultime)
											');
		$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
		$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryC->bindValue(':id_geoid', $aData['id_geoid'], PDO::PARAM_INT);
		$queryC->execute();
		$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
		$numC = $queryC->rowCount();
		
		foreach($rowsC as $rowC){
			$query = $CONFIG['dbconn'][0]->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
												(id_uid, id_count2lang)
												VALUES
												(:id_uid, :id_count2lang)
												');
			$query->bindValue(':id_uid', $idNew, PDO::PARAM_INT);
			$query->bindValue(':id_count2lang', $rowC['id_count2lang'], PDO::PARAM_INT);
			$query->execute();
		}
		
	}else{
		if(isset($aData['country'])){
			foreach($aData['country'] as $val){
				$query = $CONFIG['dbconn'][0]->prepare('
													INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
													(id_uid, id_count2lang)
													VALUES
													(:id_uid, :id_count2lang)
													');
				$query->bindValue(':id_uid', $idNew, PDO::PARAM_INT);
				$query->bindValue(':id_count2lang', $val, PDO::PARAM_INT);
				$query->execute();
			}
		}
	}


	#####################################################
	// sending notification
	#####################################################
	if($aData['email'] != '' && $aData['username'] != '' && $aData['password'] != ''){
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
		$queryN->bindValue(':id_enid', 3, PDO::PARAM_INT);
		$queryN->execute();
		$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
		$numN = $queryN->rowCount();
		
		$LOGINLINK = (isset($_SERVER['HTTPS'])) ? 'https' : 'http' . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php';
		$USERNAME = $aData['username'];
		$PASSWORD = $passOrg;
		
		$recipientsCC = array();
		
		$mailing = array();
		$recipients = array();
		if($aData['email'] != '') $recipients[$aData['email']] = $aData['firstname'] . ' ' . $aData['lastname'];
	
		// Message
		$mailing['subject'] = $rowsN[0]['subject'];
		$mailing['body'] = '' . nl2br($rowsN[0]['notification']) . '';
		
		$mailing['body'] = str_replace('##LOGINLINK##', $LOGINLINK, $mailing['body']);
		$mailing['body'] = str_replace('##USERNAME##', $USERNAME, $mailing['body']);
		$mailing['body'] = str_replace('##PASSWORD##', $PASSWORD, $mailing['body']);
		
		mailSend(1, $mailing, $recipients, $recipientsCC);
	}
}
































$out = array();
$out['id'] = $idNew;

echo json_encode($out);


?>