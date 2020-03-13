<?php
###########################################################################
// Array for clients
$CONFIG_TMP['user']['clients'] = array();
$extQuery = '';
//	$extQuery = ',
//		' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.street,
//		' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.zip,
//		' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.city,
//		' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.phone,
//		' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.mobile,
//		' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.fax,
//		' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.email,
//		' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.web
	
$queryC = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid, 
										' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
										' . $extQuery . '
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni
									
									INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_clid = ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid
										
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.active = (:active)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_uid = (:id_uid)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
									');
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
$queryC->bindValue(':active', 1, PDO::PARAM_INT);
$queryC->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);

if($aTokenContent['user']['specifications'][5] == 1){
	$queryC = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid, 
											' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
											' . $extQuery . '
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni
										
										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_clid = ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid
											
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_uid = (:id_uid)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryC->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);
}

if($aTokenContent['user']['specifications'][5] == 8){
	$queryC = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid, 
											' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
											' . $extQuery . '
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.active = (:active)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryC->bindValue(':active', 1, PDO::PARAM_INT);
}

if($aTokenContent['user']['specifications'][5] == 9){
	$queryC = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid, 
											' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
											' . $extQuery . '
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.del = (:nultime)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
										');
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
}

$queryC->execute();
$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
$numC = $queryC->rowCount();
$nC = 0;

foreach($rowsC as $datC){
	$CONFIG_TMP['user']['clients'][$datC['id_clid']] = array();
	
	foreach($datC as $key=>$val){
		$CONFIG_TMP['user']['clients'][$datC['id_clid']][$key] = $val;
	}
}

if($CONFIG['initLogin'] == true && !array_key_exists($CONFIG['activeSettings']['id_clid'], $CONFIG_TMP['user']['clients'])){
	$CONFIG['activeSettings']['id_clid'] = intval($CONFIG_TMP['user']['clients'][$rowsC[0]['id_clid']]);

	$aChangeCookie = array();
	if(isset($CONFIG['activeSettings']['id_clid'])) $aChangeCookie['id_clid'] = $CONFIG['activeSettings']['id_clid'];
	if(count($aChangeCookie) > 0) changeCookie($name='activesettings', $aChangeCookie);
}
###########################################################################







?>