<?php
$aArgsSave = array();
$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_';
$aArgsSave['primarykey'] = 'id_mpid';
$aArgsSave['allVersions'] = array();
$aArgsSave['changedVersions'] = array();

$aArgsSave['columns'] = array('id_parent' => 'i', 'id_mpid' => 'i', 'folder' => 's', 'protected' => 'i');
$aArgsSave['aFieldsNumbers'] = array('id_parent', 'id_mpid', 'protected');
$aArgsSave['excludeUpdateUni'] = array('id_parent' => array(''), 'id_mpid' => array(''), 'folder' => array(''), 'protected' => array(''));

// find root folder
$queryR = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni.id_mpid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni.id_cl
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni.id_count = (:count)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni.id_lang = (:lang)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni.id_dev = (:dev)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni.id_parent = (:id_parent)
									');
$queryR->bindValue(':count', 0, PDO::PARAM_INT);
$queryR->bindValue(':lang', 0, PDO::PARAM_INT);
$queryR->bindValue(':dev', 0, PDO::PARAM_INT);
$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryR->bindValue(':id_parent', 0, PDO::PARAM_INT);
$queryR->execute();
$rowsR = $queryR->fetchAll(PDO::FETCH_ASSOC);
$numR = $queryR->rowCount();

// insert / find target folder
$aTarget = explode('/', $varSQL['targetpath']);
$idParent = $rowsR[0]['id_mpid'];
$idPath = $rowsR[0]['id_mpid'];

foreach($aTarget as $target){
	if($target != ''){
		$query = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni.id_mpid_data,
												' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni.id_mpid,
												' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni.id_parent
											FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni.id_count = (:count)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni.id_lang = (:lang)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni.id_dev = (:dev)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni.id_parent = (:id_parent)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_uni.folder = (:folder)
												
											');
		$query->bindValue(':count', 0, PDO::PARAM_INT);
		$query->bindValue(':lang', 0, PDO::PARAM_INT);
		$query->bindValue(':dev', 0, PDO::PARAM_INT);
		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$query->bindValue(':id_parent', $idParent, PDO::PARAM_INT);
		$query->bindValue(':folder', trim($target), PDO::PARAM_STR);
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		$num = $query->rowCount();

		if($num == 0){
			$date = new DateTime();
			$now = $date->format('Y-m-d H:i:s');
		
			$query = $CONFIG['dbconn'][0]->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_
												(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
												VALUES
												(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
												');
			$query->bindValue(':nul', 0, PDO::PARAM_INT);
			$query->bindValue(':id_cl', $rowsR[0]['id_cl'], PDO::PARAM_INT);
			$query->bindValue(':create_at', $now, PDO::PARAM_STR);
			$query->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
			$query->execute();
			$idNew = $CONFIG['dbconn'][0]->lastInsertId();
			$aArgsSave['id_data'] = $idNew;

			$query = $CONFIG['dbconn'][0]->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_mediapaths_loc
												(
													id_mpid,
													id_count,
													id_lang,
													id_dev,
													id_cl,
													id_parent,
													folder,
													protected,
													create_at,
													create_from,
													change_from
												)
												VALUES
												(
													:id_mpid,
													:id_count,
													:id_lang,
													:id_dev,
													:id_cl,
													:id_parent,
													:folder,
													:protected,
													:create_at,
													:create_from,
													:change_from
												)
												');
			$query->bindValue(':id_mpid', $idNew, PDO::PARAM_INT);
			$query->bindValue(':id_count', 0, PDO::PARAM_INT);
			$query->bindValue(':id_lang', 0, PDO::PARAM_INT);
			$query->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$query->bindValue(':id_cl', $rowsR[0]['id_cl'], PDO::PARAM_INT);
			$query->bindValue(':id_parent', $idParent, PDO::PARAM_INT);
			$query->bindValue(':folder', trim($target), PDO::PARAM_STR);
			$query->bindValue(':protected', 1, PDO::PARAM_INT);
			$query->bindValue(':create_at', $now, PDO::PARAM_STR);
			$query->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
			$query->bindValue(':change_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
			$query->execute();
			
			$aArgsLV = array();
			$aArgsLV['type'] = 'sysall';
			$aLocalVersions = localVariationsBuild($aArgsLV);

			$aArgsSave['changedVersions'] = array(array(0,0,0));
			$aArgsSave['allVersions'] = $aLocalVersions;
			insertAll($aArgsSave);
			

			$idParent = $idNew;
			$idPath = $idNew;
		}else{
			$idParent = $rows[0]['id_mpid'];
			$idPath = $rows[0]['id_mpid'];
		}
	}
}

?>