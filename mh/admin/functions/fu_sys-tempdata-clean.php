<?php
$initCondPage = true;
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

if($varSQL['type'] == 'loadPage'){
	$query = $CONFIG['dbconn'][0]->prepare('
										DELETE FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:id_uid)
										');
	$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
	$query->execute();

	$query = $CONFIG['dbconn'][0]->prepare('
										DELETE FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_uid = (:id_uid)
										');
	$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
	$query->execute();
}

if($varSQL['type'] == 'prevRow' || $varSQL['type'] == 'nextRow'){
//	foreach($varSQL['moduls'] as $modul){
		$query = $CONFIG['dbconn'][0]->prepare('
											DELETE FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:id_uid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
											');
		$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
		$query->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
		$query->execute();

		$query = $CONFIG['dbconn'][0]->prepare('
											DELETE FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_uid = (:id_uid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_mod = (:id_mod)
											');
		$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
		$query->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
		$query->execute();
//	}
}

if($varSQL['type'] == 'cancelForm'){
//	foreach($varSQL['moduls'] as $modul){
		$query = $CONFIG['dbconn'][0]->prepare('
											DELETE FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:id_uid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
											');
		$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
		$query->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
		$query->execute();

		$query = $CONFIG['dbconn'][0]->prepare('
											DELETE FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_uid = (:id_uid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_mod = (:id_mod)
											');
		$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
		$query->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
		$query->execute();
//	}
}

if($varSQL['type'] == 'closeDialog'){
//	foreach($varSQL['moduls'] as $modul){
		$query = $CONFIG['dbconn'][0]->prepare('
											DELETE FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:id_uid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
											');
		$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
		$query->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
		$query->execute();

		$query = $CONFIG['dbconn'][0]->prepare('
											DELETE FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_uid = (:id_uid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_mod = (:id_mod)
											');
		$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
		$query->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
		$query->execute();
//	}
}

if($varSQL['type'] == 'success'){
//	foreach($varSQL['moduls'] as $modul){
		$query = $CONFIG['dbconn'][0]->prepare('
											DELETE FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:id_uid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
											');
		$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
		$query->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
		$query->execute();

		$query = $CONFIG['dbconn'][0]->prepare('
											DELETE FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_uid = (:id_uid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempupload.id_mod = (:id_mod)
											');
		$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
		$query->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
		$query->execute();
//	}
}



echo 'OK';



?>