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
$query->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
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


	$query = $CONFIG['dbconn'][0]->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_user SET
											firstname = (:firstname),
											lastname = (:lastname),
											email = (:email),
											change_from = (:create_from)
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = (:id)
										LIMIT 1
										');
	$query->bindValue(':firstname', $aData['firstname'], PDO::PARAM_STR);
	$query->bindValue(':lastname', $aData['lastname'], PDO::PARAM_STR);
	$query->bindValue(':email', $aData['email'], PDO::PARAM_STR);
	$query->bindValue(':id', $CONFIG['user']['id'], PDO::PARAM_INT);
	$query->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
	$query->execute();
	
	
	if($aData['username'] != ""){
		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_user SET
												username = (:username),											
												change_from = (:create_from)
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = (:id)
											LIMIT 1
											');
		$query->bindValue(':username', $aData['username'], PDO::PARAM_STR);
		$query->bindValue(':id', $CONFIG['user']['id'], PDO::PARAM_INT);
		$query->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
		$query->execute();
	}
	
	if($aData['password'] != ""){
		$t_hasher = new PasswordHash(8, false);
		$aData['password'] = $t_hasher->HashPassword($aData['password']);

		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_user SET
												password = (:password),											
												change_from = (:create_from)
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = (:id)
											LIMIT 1
											');
		$query->bindValue(':password', $aData['password'], PDO::PARAM_STR);
		$query->bindValue(':id', $CONFIG['user']['id'], PDO::PARAM_INT);
		$query->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
		$query->execute();
	}
}





$out = array();
$out['id_data'] = $CONFIG['page']['id_data'];

echo json_encode($out);

?>