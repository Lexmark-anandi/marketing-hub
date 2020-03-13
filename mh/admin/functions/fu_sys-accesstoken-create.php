<?php
function createAccesstoken($args=array()){
	global $CONFIG, $TEXT; 

	getConnection(0); 

	$date = new DateTime();
	$now = $date->format('Y-m-d H:i:s');
	
	$queryT = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_config_access.algorithm,
											' . $CONFIG['db'][0]['prefix'] . 'system_config_access.key_token,
											' . $CONFIG['db'][0]['prefix'] . 'system_config_access.key_csrf,
											' . $CONFIG['db'][0]['prefix'] . 'system_config_access.token_refresh, 
											' . $CONFIG['db'][0]['prefix'] . 'system_config_access.token_expire
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_config_access
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_config_access.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_config_access.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_config_access.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_config_access.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_config_access.del = (:nultime)
										');
	$queryT->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryT->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryT->execute();
	$rowsT = $queryT->fetchAll(PDO::FETCH_ASSOC);
	$numT = $queryT->rowCount();
	
	$csrfToken = uniqid('', true);
	$csrfHash = hash_hmac($rowsT[0]['algorithm'], $csrfToken, $rowsT[0]['key_csrf']);
	$csrfSignature = base64_encode($csrfHash);
	setcookie('csrf', $csrfSignature, 0, $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], false);


	$aTokenHeader = array('typ'=>'JWT','alg'=>$rowsT[0]['algorithm']);
	$tokenHeader = base64_encode(utf8_encode(json_encode($aTokenHeader)));
	$aTokenContent = array('create'=>$now, 'url'=>$_SERVER['HTTP_HOST'], 'csrf'=>$csrfSignature, 'user'=>$CONFIG['user']);
	$tokenContent = base64_encode(utf8_encode(json_encode($aTokenContent, JSON_UNESCAPED_SLASHES)));
	
	$token = $tokenHeader . '.' . $tokenContent;
	$hash = hash_hmac($rowsT[0]['algorithm'], $token, $rowsT[0]['key_token']);
	$signature = base64_encode($hash);
	$token .= '.' . $signature;
	setcookie('access', $token, 0, $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], true);
}




?>