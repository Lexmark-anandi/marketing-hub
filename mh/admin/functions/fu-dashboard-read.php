<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$functionPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'];
$functionFile = 'fu-' . $CONFIG['aModul']['modul_name'] . '-read.php';
$functionFileOne2n = 'fu-' . $CONFIG['aModul']['modul_name'] . '-read-one2n.php';
$functionFilePre = 'fu-' . $CONFIG['aModul']['modul_name'] . '-read-pre.php';
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php');


$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');


$aDashboard = array();
$aDashboard['grid'] = array();
$aDashboard['numCol'] = 0;
$aDashboard['numRow'] = 0;
$aDashboard['baseW'] = 0;
$aDashboard['baseH'] = 0;
$aDashboard['spaceW'] = 10;
$aDashboard['spaceH'] = 10;
$baseFactor = 4;

$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_dashboard2user.dashboard
									FROM ' . $CONFIG['db'][0]['prefix'] . '_dashboard2user 
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_dashboard2user.id_uid = (:id_uid)
									'); 
$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

if($num > 0){
	$aDashboardTmp = json_decode($rows[0]['dashboard'], true);
		
	foreach($aDashboardTmp as $key=>$val){ 
		$val['filename'] = str_replace('fu-', '', str_replace('.php', '', $val['filename']));

		$query = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_dashboard.id_dashid
											FROM ' . $CONFIG['db'][0]['prefix'] . '_dashboard 
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_dashboard.active = (:one)
												AND ' . $CONFIG['db'][0]['prefix'] . '_dashboard.id_dashid = (:id_dashid)
												AND ' . $CONFIG['db'][0]['prefix'] . '_dashboard.id_mod2f IN ('.implode(',', $CONFIG['user']['functions']).')
											'); 
		$query->bindValue(':one', 1, PDO::PARAM_INT);
		$query->bindValue(':id_dashid', $val['id_dashid'], PDO::PARAM_INT);
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		$numCheck = $query->rowCount();

		if($numCheck > 0){
			$val['functions'] = array();
			$queryF = $CONFIG['dbconn'][0]->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions.id_dash2df,
													' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions.id_dashid,
													' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions.function,
													' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions.title,
													' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions.icon
												FROM ' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions 
												
												WHERE ' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions.active = (:one)
													AND ' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions.id_dashid = (:id_dashid)
												ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions.rank
												'); 
			$queryF->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$queryF->bindValue(':one', 1, PDO::PARAM_INT);
			$queryF->bindValue(':id_dashid', $val['id_dashid'], PDO::PARAM_INT);
			$queryF->execute();
			$rowsF = $queryF->fetchAll(PDO::FETCH_ASSOC);
			$numF = $queryF->rowCount();
			foreach($rowsF as $rowF){
				$val['functions']['df_' . $rowF['id_dash2df']] = array();
				$val['functions']['df_' . $rowF['id_dash2df']]['function'] = $rowF['function'];
				$val['functions']['df_' . $rowF['id_dash2df']]['icon'] = $rowF['icon'];
				$val['functions']['df_' . $rowF['id_dash2df']]['title'] = (isset($TEXT[$rowF['title']])) ? $TEXT[$rowF['title']] : $rowF['title'];
			}

		
			array_push($aDashboard['grid'], $val);
		}
	}
}else{
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_dashboard.id_dashid,
											' . $CONFIG['db'][0]['prefix'] . '_dashboard.id_mod2f,
											' . $CONFIG['db'][0]['prefix'] . '_dashboard.title,
											' . $CONFIG['db'][0]['prefix'] . '_dashboard.col,
											' . $CONFIG['db'][0]['prefix'] . '_dashboard.row,
											' . $CONFIG['db'][0]['prefix'] . '_dashboard.size_x,
											' . $CONFIG['db'][0]['prefix'] . '_dashboard.size_y,
											' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.filename
										FROM ' . $CONFIG['db'][0]['prefix'] . '_dashboard 
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions ON ' . $CONFIG['db'][0]['prefix'] . '_dashboard.id_mod2f=' . $CONFIG['db'][0]['prefix'] . 'system_moduls2functions.id_mod2f
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_dashboard.active = (:one)
											AND ' . $CONFIG['db'][0]['prefix'] . '_dashboard.id_mod2f IN ('.implode(',', $CONFIG['user']['functions']).')
										'); 
	$query->bindValue(':one', 1, PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	foreach($rows as $row){
		$aDashboardTmp = array();
		$aDashboardTmp['title'] = $row['title'];
		$aDashboardTmp['id_dashid'] = $row['id_dashid'];
		$aDashboardTmp['filename'] = str_replace('fu-', '', str_replace('.php', '', $row['filename']));
		$aDashboardTmp['col'] = $row['col'];
		$aDashboardTmp['row'] = $row['row'];
		$aDashboardTmp['size_x'] = $row['size_x'];
		$aDashboardTmp['size_y'] = $row['size_y'];
		$aDashboardTmp['functions'] = array();
		
		$queryF = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions.id_dash2df,
												' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions.id_dashid,
												' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions.function,
												' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions.title,
												' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions.icon
											FROM ' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions 
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions.active = (:one)
												AND ' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions.id_dashid = (:id_dashid)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_dashboard2functions.rank
											'); 
		$queryF->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryF->bindValue(':one', 1, PDO::PARAM_INT);
		$queryF->bindValue(':id_dashid', $row['id_dashid'], PDO::PARAM_INT);
		$queryF->execute();
		$rowsF = $queryF->fetchAll(PDO::FETCH_ASSOC);
		$numF = $queryF->rowCount();
		foreach($rowsF as $rowF){
			$aDashboardTmp['functions']['df_' . $rowF['id_dash2df']] = array();
			$aDashboardTmp['functions']['df_' . $rowF['id_dash2df']]['function'] = $rowF['function'];
			$aDashboardTmp['functions']['df_' . $rowF['id_dash2df']]['icon'] = $rowF['icon'];
			$aDashboardTmp['functions']['df_' . $rowF['id_dash2df']]['title'] = (isset($TEXT[$rowF['title']])) ? $TEXT[$rowF['title']] : $rowF['title'];
		}
		
		
		array_push($aDashboard['grid'], $aDashboardTmp);
	}

	// Beginning from col 1 and row 1
	$col1 = 0;
	while($col1 == 0){
		foreach($aDashboard['grid'] as $key){
			if($key['col'] == 1) $col1 = 1;
		}
		if($col1 == 0){
			foreach($aDashboard['grid'] as &$singleGrid){
				$singleGrid['col'] -= 1;
			}
		}
	}
		
	$query2 = $CONFIG['dbconn'][0]->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_dashboard2user
										(id_uid, dashboard)
										VALUES
										(:id_uid, :dashboard)
										'); 
	$query2->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
	$query2->bindValue(':dashboard', json_encode($aDashboard['grid'], JSON_NUMERIC_CHECK), PDO::PARAM_STR);
	$query2->execute();
	$rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$num2 = $query2->rowCount();
}
	
	
foreach($aDashboard['grid'] as $key){
	if(($key['col'] + $key['size_x'] - 1) > $aDashboard['numCol']) $aDashboard['numCol'] = $key['col'] + $key['size_x'] - 1;
	if(($key['row'] + $key['size_y'] - 1) > $aDashboard['numRow']) $aDashboard['numRow'] = $key['row'] + $key['size_y'] - 1;
}

foreach($aDashboard['grid'] as &$singleGrid){
	$singleGrid['col'] = ($singleGrid['col'] * $baseFactor) - $baseFactor + 1;
	$singleGrid['size_x'] = $singleGrid['size_x'] * $baseFactor;
	$singleGrid['row'] = ($singleGrid['row'] * $baseFactor) - $baseFactor + 1;
	$singleGrid['size_y'] = $singleGrid['size_y'] * $baseFactor;
}

$aDashboard['baseW'] = floor((($varSQL['outerW'] - ($aDashboard['spaceW'] * 2 * $aDashboard['numCol'] * $baseFactor)) / $aDashboard['numCol']) / $baseFactor);
$aDashboard['baseH'] = floor((($varSQL['outerH'] - ($aDashboard['spaceH'] * 2 * $aDashboard['numRow'] * $baseFactor)) / $aDashboard['numRow']) / $baseFactor);

$aDashboard['maxCol'] = floor($varSQL['outerW'] / ($aDashboard['baseW'] + ($aDashboard['spaceW'] * 2)));
$aDashboard['maxRow'] = floor($varSQL['outerH'] / ($aDashboard['baseH'] + ($aDashboard['spaceH'] * 2)));


echo json_encode($aDashboard, JSON_NUMERIC_CHECK);



?>