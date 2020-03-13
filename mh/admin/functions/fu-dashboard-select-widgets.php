<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$functionPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'];
$functionFile = 'fu-' . $CONFIG['aModul']['modul_name'] . '-select-widgets.php';
$functionFileOne2n = 'fu-' . $CONFIG['aModul']['modul_name'] . '-select-widgets-one2n.php';
$functionFilePre = 'fu-' . $CONFIG['aModul']['modul_name'] . '-select-widgets-pre.php';
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php');


$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');


$aAssignedDatasets = array();
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_dashboard2user.dashboard
									FROM ' . $CONFIG['db'][0]['prefix'] . '_dashboard2user 
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_dashboard2user.id_uid = (:id_uid)
									'); 
$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$aDashboard = json_decode($rows[0]['dashboard'], true);
foreach($aDashboard as $widget){
	array_push($aAssignedDatasets, $widget['id_dashid']);
}
$num = count($aDashboard);



$aAllDatasets = array();
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_dashboard.id_dashid,
										' . $CONFIG['db'][0]['prefix'] . '_dashboard.title
									FROM ' . $CONFIG['db'][0]['prefix'] . '_dashboard 

									WHERE ' . $CONFIG['db'][0]['prefix'] . '_dashboard.active = (:one)
										AND ' . $CONFIG['db'][0]['prefix'] . '_dashboard.id_mod2f IN ('.implode(',', $CONFIG['user']['functions']).')
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_dashboard.title
									');
$query->bindValue(':active', 1, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

foreach($rows as $row){
	array_push($aAllDatasets, $row['id_dashid']);
}


$aSelectedDatasets = $varSQL['selectassign_widgets'];

$aChange = array();
$aChange['removeWidgets'] = array_diff($aAssignedDatasets, $aSelectedDatasets);
$aChange['addWidgets'] = array_diff($aSelectedDatasets, $aAssignedDatasets);
$aChange['addWidgetsGrid'] = array();

foreach($aChange['addWidgets'] as $id){
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
											AND ' . $CONFIG['db'][0]['prefix'] . '_dashboard.id_dashid = (:id_dashid)
										'); 
	$query->bindValue(':one', 1, PDO::PARAM_INT);
	$query->bindValue(':id_dashid', $id, PDO::PARAM_INT);
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
		}
		
		array_push($aChange['addWidgetsGrid'], $aDashboardTmp);
	}
}



$baseFactor = 4;

foreach($aChange['addWidgetsGrid'] as &$singleGrid){
	$singleGrid['col'] = ($singleGrid['col'] * $baseFactor) - $baseFactor + 1;
	$singleGrid['size_x'] = $singleGrid['size_x'] * $baseFactor;
	$singleGrid['row'] = ($singleGrid['row'] * $baseFactor) - $baseFactor + 1;
	$singleGrid['size_y'] = $singleGrid['size_y'] * $baseFactor;
}



echo json_encode($aChange, JSON_NUMERIC_CHECK);




?>