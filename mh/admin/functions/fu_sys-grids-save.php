<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();
$aParam = json_decode($varSQL['param'], true);

#########################################################
// Modul
$query = $CONFIG['dbconn'][0]->prepare('
									INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2user 
										(id_mod, id_mod_parent, id_page, id_uid, modul_sortorder, modul_sortname) 
									VALUES 
										(:id_mod, :id_mod_parent, :id_page, :id_uid, :modul_sortorder, :modul_sortname) 
									ON DUPLICATE KEY UPDATE 
										modul_sortorder = (:modul_sortorder),
										modul_sortname = (:modul_sortname),
										del = (:nultime)
									');
$query->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
$query->bindValue(':id_mod_parent', $CONFIG['page']['id_mod_parent'], PDO::PARAM_INT);
$query->bindValue(':id_page', 0, PDO::PARAM_INT);
$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
$query->bindValue(':modul_sortorder', $aParam['sortorder'], PDO::PARAM_STR);
$query->bindValue(':modul_sortname', $aParam['sortname'], PDO::PARAM_STR);
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->execute();



#########################################################
// Colmodel
$rank = 0;
foreach($aParam['colModel'] as $aColmodel) {
	$name = (isset($aColmodel['name'])) ? $aColmodel['name'] : '';
	$width = $aColmodel['width'];
	$hidden = ($aColmodel['hidden'] == 1) ? 'true' : 'false';
	$rank += 10;

	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_field
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_fields 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_mod = (:id_mod)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_name = (:g_name)
										');
	$query->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':g_name', $name, PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();

	if($num > 0){
		$query2 = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_fields2user
												(id_field, id_mod_parent, id_page, id_uid, g_width, g_hidden, g_rank)
											VALUES
												(:id_field, :id_mod_parent, :id_page, :id_uid, :g_width, :g_hidden, :g_rank)
											ON DUPLICATE KEY UPDATE 
												g_width = (:g_width),
												g_hidden = (:g_hidden), 
												g_rank = (:g_rank),
												del = (:nultime)
											');
		$query2->bindValue(':id_field', $rows[0]['id_field'], PDO::PARAM_INT);
		$query2->bindValue(':id_mod_parent', $CONFIG['page']['id_mod_parent'], PDO::PARAM_INT);
		$query2->bindValue(':id_page', 0, PDO::PARAM_INT);
		$query2->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
		$query2->bindValue(':g_width', $width, PDO::PARAM_INT);
		$query2->bindValue(':g_hidden', $hidden, PDO::PARAM_STR);
		$query2->bindValue(':g_rank', $rank, PDO::PARAM_INT);
		$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$query2->execute();
	}
}


#########################################################
// num rows
$query2 = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_user SET
									grid_num_rows = (:grid_num_rows)
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = (:id_uid)
									LIMIT 1
									');
$query2->bindValue(':grid_num_rows', $aParam['rowNum'], PDO::PARAM_INT);
$query2->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
//$query2->execute();

// save num rows for modul
$aModulSrc = ($CONFIG['page']['id_mod_parent'] == 0) ? $CONFIG['user']['pages2moduls'][$CONFIG['page']['id_page']]['moduls']['i_' . $CONFIG['page']['id_mod']] : $CONFIG['user']['childmoduls'][$CONFIG['page']['id_mod_parent']]['i_' . $CONFIG['page']['id_mod']];

// parent moduls
foreach($CONFIG['user']['pages2moduls'] as &$aPages){
	foreach($aPages['moduls'] as &$aModul){
		if(($CONFIG['system']['synchronizeModulFilter'] == 1 && $aModul['specifications'][12] == 9 && $aModul['specifications'][9] == $aModulSrc['specifications'][9]) || $aModul['id_mod'] == $CONFIG['page']['id_mod']){
			$query = $CONFIG['dbconn'][0]->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_user2settings
													(id_uid, id_mod, id_mod_parent, id_page, grid_num_rows)
												VALUES
													(:id_uid, :id_mod, :id_mod_parent, :id_page, :grid_num_rows)
												ON DUPLICATE KEY UPDATE 
													grid_num_rows = (:grid_num_rows),
													del = (:nultime)
												');

			$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
			$query->bindValue(':id_mod', $aModul['id_mod'], PDO::PARAM_INT);
			$query->bindValue(':id_mod_parent', 0, PDO::PARAM_INT);
			$query->bindValue(':id_page', 0, PDO::PARAM_INT); // $query->bindValue(':id_page', $CONFIG['page']['id_page'], PDO::PARAM_INT);
			$query->bindValue(':grid_num_rows', $aParam['rowNum'], PDO::PARAM_INT);
			$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query->execute();
		}
	}
}

// child moduls
foreach($CONFIG['user']['childmoduls'] as &$aParents){
	foreach($aParents as $id_mod => &$aModul){
		if(($CONFIG['system']['synchronizeModulFilter'] == 1 && $aModul['specifications'][12] == 9 && $aModul['specifications'][9] == $aModulSrc['specifications'][9]) || $aModul['id_mod'] == $CONFIG['page']['id_mod']){
			$query = $CONFIG['dbconn'][0]->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_user2settings
													(id_uid, id_mod, id_mod_parent, id_page, grid_num_rows)
												VALUES
													(:id_uid, :id_mod, :id_mod_parent, :id_page, :grid_num_rows)
												ON DUPLICATE KEY UPDATE 
													grid_num_rows = (:grid_num_rows),
													del = (:nultime)
												');

			$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
			$query->bindValue(':id_mod', $aModul['id_mod'], PDO::PARAM_INT);
			$query->bindValue(':id_mod_parent', $id_mod, PDO::PARAM_INT);
			$query->bindValue(':id_page', 0, PDO::PARAM_INT); // $query->bindValue(':id_page', $CONFIG['page']['id_page'], PDO::PARAM_INT);
			$query->bindValue(':grid_num_rows', $aParam['rowNum'], PDO::PARAM_INT);
			$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query->execute();
		}
	}
}



?>