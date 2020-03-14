<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData(json_decode($_POST['data'], true));

if($varSQL['type'] == 'loadPage'){
	$query = $CONFIG['dbconn']->prepare('
										DELETE FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:id_uid)
										');
	$query->bindValue(':id_uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
	$query->execute();
}

if($varSQL['type'] == 'prevRow' || $varSQL['type'] == 'nextRow'){
	foreach($varSQL['moduls'] as $modul){
		$query = $CONFIG['dbconn']->prepare('
											DELETE FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:id_uid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.modulname = (:modulname)
											');
		$query->bindValue(':id_uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
		$query->bindValue(':modulname', $CONFIG['page']['moduls'][$modul]['modulname'], PDO::PARAM_STR);
		$query->execute();
	}
}

if($varSQL['type'] == 'cancelForm'){
	foreach($varSQL['moduls'] as $modul){
		$query = $CONFIG['dbconn']->prepare('
											DELETE FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:id_uid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.modulname = (:modulname)
											');
		$query->bindValue(':id_uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
		$query->bindValue(':modulname', $CONFIG['page']['moduls'][$modul]['modulname'], PDO::PARAM_STR);
		$query->execute();
	}
}

if($varSQL['type'] == 'closeDialog'){
	foreach($varSQL['moduls'] as $modul){
		$query = $CONFIG['dbconn']->prepare('
											DELETE FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:id_uid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.modulname = (:modulname)
											');
		$query->bindValue(':id_uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
		$query->bindValue(':modulname', $CONFIG['page']['moduls'][$modul]['modulname'], PDO::PARAM_STR);
		$query->execute();
	}
}



echo 'OK';



?>