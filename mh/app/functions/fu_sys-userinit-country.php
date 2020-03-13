<?php
########################################################
$CONFIG_I['configCountry'] = array();
$queryR = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.currency
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni 

									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = (:id_countid)
									');
$queryR->bindValue(':id_countid', $aTokenContent['user']['id_countid'], PDO::PARAM_INT);
$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryR->execute();
$rowsR = $queryR->fetchAll(PDO::FETCH_ASSOC);
$numR = $queryR->rowCount();

foreach($rowsR[0] as $key => $val){
	$CONFIG_I['configCountry'][$key] = $val;
}


?>