<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData();
$aParam = json_decode($varSQL['param'], true);


$sortorder = $aParam['sortorder'];
$sortname = $aParam['sortname'];
$row_num = $aParam['rowNum'];

$aCookieChange = array();
$aCookieChange['gridNumRows'] = $row_num;
changeCookie('userconfig', $aCookieChange);

$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_grids_user.id_grid_u
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_grids_user 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_grids_user.id_grid_d = (:id_grid_d)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_grids_user.id_uid = (:id_uid)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_grids_user.id_modul_parent = (:id_modul_parent)
									');
$query->bindValue(':id_grid_d', $CONFIG['page']['moduls'][$varSQL['modul']]['id_grid_d'], PDO::PARAM_INT);
$query->bindValue(':id_uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
$query->bindValue(':id_modul_parent', $CONFIG['page']['moduls'][$varSQL['modul']]['modulParent'], PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

if($num == 0){
	$query2 = $CONFIG['dbconn']->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_grids_user
										(id_grid_d, id_uid, id_modul_parent, grid_sortorder, grid_sortname)
										VALUES
										(:id_grid_d, :id_uid, :id_modul_parent, :grid_sortorder, :grid_sortname)
										');
	$query2->bindValue(':id_grid_d', $CONFIG['page']['moduls'][$varSQL['modul']]['id_grid_d'], PDO::PARAM_INT);
	$query2->bindValue(':id_uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
	$query2->bindValue(':id_modul_parent', $CONFIG['page']['moduls'][$varSQL['modul']]['modulParent'], PDO::PARAM_STR);
	$query2->bindValue(':grid_sortorder', $sortorder, PDO::PARAM_STR);
	$query2->bindValue(':grid_sortname', $sortname, PDO::PARAM_STR);
	$query2->execute();
	$idG = $CONFIG['dbconn']->lastInsertId();
}else{
	$query2 = $CONFIG['dbconn']->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_grids_user SET
										grid_sortorder = (:grid_sortorder),
										grid_sortname = (:grid_sortname)
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_grids_user.id_grid_u = (:id_grid)
										LIMIT 1
										');
	$query2->bindValue(':id_grid', $rows[0]['id_grid_u'], PDO::PARAM_INT);
	$query2->bindValue(':grid_sortorder', $sortorder, PDO::PARAM_STR);
	$query2->bindValue(':grid_sortname', $sortname, PDO::PARAM_STR);
	$query2->execute();
	$idG = $rows[0]['id_grid_u'];
}


$query2 = $CONFIG['dbconn']->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_user SET
									num_row = (:num_row)
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = (:id_uid)
									LIMIT 1
									');
$query2->bindValue(':num_row', $row_num, PDO::PARAM_INT);
$query2->bindValue(':id_uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
$query2->execute();






// Colmodel
$rank = 0;
foreach($aParam['colModel'] as $aColmodel) {
	$name = $aColmodel['name'];
	$width = $aColmodel['width'];
	$hidden = ($aColmodel['hidden'] == 1) ? 'true' : 'false';
	$rank += 10;

	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.id_col_d
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.id_grid_d = (:id_grid_d)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_name = (:g_name)
										');
	$query->bindValue(':id_grid_d', $CONFIG['page']['moduls'][$varSQL['modul']]['id_grid_d'], PDO::PARAM_INT);
	$query->bindValue(':g_name', $name, PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	$col = $rows[0]['id_col_d'];

	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.id_col_u
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.id_col_d = (:id_col_d)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.id_uid = (:id_uid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.id_modul_parent = (:id_modul_parent)
										');
	$query->bindValue(':id_col_d', $col, PDO::PARAM_INT);
	$query->bindValue(':id_uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
	$query->bindValue(':id_modul_parent', $CONFIG['page']['moduls'][$varSQL['modul']]['modulParent'], PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();

	if($num == 0){
		$query2 = $CONFIG['dbconn']->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user
											(id_col_d, id_uid, id_modul_parent, g_width, g_hidden, g_rank, id_grid_d)
											VALUES
											(:id_col_d, :id_uid, :id_modul_parent, :g_width, :g_hidden, :rank, :id_grid_d)
											');
		$query2->bindValue(':id_grid_d', $CONFIG['page']['moduls'][$varSQL['modul']]['id_grid_d'], PDO::PARAM_INT);
		$query2->bindValue(':id_uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
		$query2->bindValue(':id_modul_parent', $CONFIG['page']['moduls'][$varSQL['modul']]['modulParent'], PDO::PARAM_STR);
		$query2->bindValue(':id_col_d', $col, PDO::PARAM_INT);
		$query2->bindValue(':g_width', $width, PDO::PARAM_INT);
		$query2->bindValue(':g_hidden', $hidden, PDO::PARAM_STR);
		$query2->bindValue(':rank', $rank, PDO::PARAM_INT);
		$query2->execute();
	}else{
		$query2 = $CONFIG['dbconn']->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user SET
											g_width = (:g_width),
											g_hidden = (:g_hidden), 
											g_rank = (:rank)
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.id_col_u = (:id_col)
											LIMIT 1
											');
		$query2->bindValue(':g_width', $width, PDO::PARAM_INT);
		$query2->bindValue(':g_hidden', $hidden, PDO::PARAM_STR);
		$query2->bindValue(':rank', $rank, PDO::PARAM_INT);
		$query2->bindValue(':id_col', $rows[0]['id_col_u'], PDO::PARAM_INT);
		$query2->execute();
	}
}



?>