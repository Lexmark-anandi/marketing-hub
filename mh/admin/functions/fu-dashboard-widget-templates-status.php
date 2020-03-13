<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$functionPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'];
$functionFile = 'fu-' . $CONFIG['aModul']['modul_name'] . '-widget-templates-status.php';
$functionFileOne2n = 'fu-' . $CONFIG['aModul']['modul_name'] . '-widget-templates-status-one2n.php';
$functionFilePre = 'fu-' . $CONFIG['aModul']['modul_name'] . '-widget-templates-status-pre.php';
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php');


$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$con = '<table class="dashboardTable" cellpadding="0" cellspacing="0">';
									

$condRight = '';
if($CONFIG['user']['right'] == 4) $condRight = 'AND (' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.transrequest_at <> (:nultime) OR ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.create_from = ' . $CONFIG['user']['id'] . ')';									


$aTemp = array(0);
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
									FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni 
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_
										ON ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_countid IN (' . implode(',', array_keys($CONFIG['user']['countries'])) . ')
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.published_at = (:nultime)
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
										' . $condRight . '
										
									GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
									'); 
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	array_push($aTemp, $row['id_tempid']);
}


$condRightProm = '';
if($CONFIG['user']['right'] == 4) $condRightProm = 'AND (' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.transrequest_at <> (:nultime) OR ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.create_from = ' . $CONFIG['user']['id'] . ')';									

$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
									FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni 
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni
										ON ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.del = (:nultime)
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_
										ON ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid IN (' . implode(',', array_keys($CONFIG['user']['countries'])) . ')
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_promid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.published_at = (:nultime)
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.del = (:nultime)
										' . $condRightProm . '
										
									GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
									'); 
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	array_push($aTemp, $row['id_tempid']);
}



									
//										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.transrequest_from <> (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.transrequest_at <> (:nultime)
//									ORDER BY IF(' . $CONFIG['db'][0]['prefix'] . '_templates_uni.transrequest_at="0000-00-00 00:00:00", 1, 0) ASC
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid,
										' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid,
										' . $CONFIG['db'][0]['prefix'] . '_templates_uni.title,
										' . $CONFIG['db'][0]['prefix'] . '_templates_uni.create_from,
										' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.title AS title_promotion
										
									FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni 

									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni
										ON ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid
											
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid IN (' . implode(',', $aTemp) . ')

									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.create_at
									'); 
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();



//											AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.transrequest_from <> (:nul)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.transrequest_at <> (:nultime)
foreach($rows as $row){
	if($row['id_promid'] == 0){
		$query2 = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.transrequest_at,
												' . $CONFIG['db'][0]['prefix'] . '_templates_uni.published_at,
												' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid,
												' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country,
												' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid,
												' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
												
											FROM ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_ 
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
												ON ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_countid
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_langid
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_tempid
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_countid
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_langid
											 
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_countid IN (' . implode(',', array_keys($CONFIG['user']['countries'])) . ')
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_tempid = (:id_tempid)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country, ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
											'); 
		$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$query2->bindValue(':nul', 0, PDO::PARAM_INT);
		$query2->bindValue(':id_tempid', $row['id_tempid'], PDO::PARAM_INT);
		$query2->execute();
		$rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$num2 = $query2->rowCount();
	}else{
		$query2 = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.transrequest_at,
												' . $CONFIG['db'][0]['prefix'] . '_templates_uni.published_at,
												' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid,
												' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country,
												' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid,
												' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
												
											FROM ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_ 
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
												ON ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_langid
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid = (:id_tempid)
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_langid
											 
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid IN (' . implode(',', array_keys($CONFIG['user']['countries'])) . ')
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_promid = (:id_promid)
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country, ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
											'); 
		$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$query2->bindValue(':nul', 0, PDO::PARAM_INT);
		$query2->bindValue(':id_promid', $row['id_promid'], PDO::PARAM_INT);
		$query2->bindValue(':id_tempid', $row['id_tempid'], PDO::PARAM_INT);
		$query2->execute();
		$rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$num2 = $query2->rowCount();
	}

	
	$list = '';
	$status = 0;
	foreach($rows2 as $row2){
		$classStatus = 'open';
		if($row2['published_at'] != '0000-00-00 00:00:00') $classStatus = 'done';
		if($row2['published_at'] == '0000-00-00 00:00:00' && $row2['transrequest_at'] != '0000-00-00 00:00:00'){
			$classStatus = 'notdonelate';
			$status = 1; 
		}
		
		$aL = array();
		$aL['page'] = 116;
		$aL['country'] = $row2['id_countid'];
		$aL['language'] = $row2['id_langid'];
		$aL['data'] = $row['id_tempid'];
		$aL['function'] = 'rowEdit';
		
		$link = '';
		if($classStatus == 'notdonelate') $link = (isset($_SERVER['HTTPS'])) ? 'https' : 'http' . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?dl=' . base64_encode(json_encode($aL));
		
		if($link == ''){
			$list .= '<div style="white-space:nowrap" class="' . $classStatus . '">' . $row2['country'] .' / ' . $row2['language'] . '</div>';
		}else{
			$list .= '<div style="white-space:nowrap" class="' . $classStatus . '"><a href="' . $link . '" class="' . $classStatus . '">' . $row2['country'] .' / ' . $row2['language'] . '</a></div>';
		}
	}
	if($status == 1 && ($CONFIG['user']['right'] == 1 || $CONFIG['user']['right'] == 2 || $CONFIG['user']['right'] == 3)) $list .= '<div class="dashboardListitemCountriesButton"><button class="formButton formButtonLeft">' . $TEXT['sendReminder'] . '</button></div>';
	
	//$con .= '<tr class="dashboardListitemRow" onclick="f_'.$varSQL['idModul'].'.directLink('.$row['id_taskid'].', \'p-tasks.php\', \'rowEdit\', this)">';
	$con .= '<tr class="dashboardListitemRow" data-temp="' . $row['id_tempid'] . '" data-prom="' . $row['id_promid'] . '">';
	$con .= '<td class="dashboardListitemContent"><strong>' . $row['title'] . '</strong><div>' . $row['title_promotion'] . '</div></td>';
	$con .= '<td class="dashboardListitemCountries"><div class="listTransReqCount">' . $list . '</div></td>';
	$con .= '<td class="dashboardListitemButton"><i class="fa fa-caret-down"></i></td>';
	$con .= '</tr>';
}
 
if($num == 0){
	$con .= '<tr class="dashboardListitemRow">';
	$con .= '<td class="dashboardListitemContent">' . $TEXT['noRecords'] . '</td>';
	$con .= '</tr>';
}

$con .= '</table>';




$out = array();
$out['con'] = $con;

echo json_encode($out);;



?>