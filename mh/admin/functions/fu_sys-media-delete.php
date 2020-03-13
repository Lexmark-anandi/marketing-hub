<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');

$varSQL = getPostData();

$link = basename($varSQL['url']);
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php'); 

if($checkfunction == 'ok') { 
	getConnection(0); 
	
	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.id_mid,
											' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.filename,
											' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.filesys_filename
										FROM ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full 
		
										WHERE ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.id_count = (:count)
											AND ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.id_lang = (:lang)
											AND ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.id_dev = (:dev)
											AND ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.id_clid = (:id_clid)
											AND ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.id_mid = (:id)
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':count', 0, PDO::PARAM_INT);
	$query->bindValue(':lang', 0, PDO::PARAM_INT);
	$query->bindValue(':dev', 0, PDO::PARAM_INT);
	$query->bindValue(':id_clid', $_SESSION['admin']['USER']['activeClient'], PDO::PARAM_INT);
	$query->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	
	if($num != 0){
		$args = array('id'=>$varSQL['id'], 'filename'=>$rows[0]['filename'], 'filesys_filename'=>$rows[0]['filesys_filename'], 'fieldname'=>$varSQL['fieldname']);
	
		unlink($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathMedia'] . $rows[0]['filesys_filename']);

		$aDelete = array($CONFIG['db'][0]['prefix_sys'] . 'media'=>'id_mid');
		if($num > 0) deleteRow($aDelete, $varSQL['id'], 1);

		echo json_encode($args);
	}else{
		echo 0;
	}

}

?>