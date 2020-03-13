<?php
function createAccesstoken($args=array()){
	global $CONFIG, $TEXT; 

	getConnection(0); 

	$date = new DateTime();
	$now = $date->format('Y-m-d H:i:s');
	
	$queryT = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.algorithm,
											' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.key_token,
											' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.key_csrf,
											' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.token_refresh, 
											' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.token_expire
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.id_cl IN (0)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_config_access_app.del = (:nultime)
										');
	$queryT->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryT->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryT->execute();
	$rowsT = $queryT->fetchAll(PDO::FETCH_ASSOC);
	$numT = $queryT->rowCount();
	
	$csrfToken = uniqid('', true);
	$csrfHash = hash_hmac($rowsT[0]['algorithm'], $csrfToken, $rowsT[0]['key_csrf']);
	$csrfSignature = base64_encode($csrfHash);
	setcookie('csrf', $csrfSignature, 0, $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathApp'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], false);


	$aTokenHeader = array('typ'=>'JWT','alg'=>$rowsT[0]['algorithm']);
	$tokenHeader = base64_encode(utf8_encode(json_encode($aTokenHeader)));
	$aTokenContent = array('create'=>$now, 'url'=>$_SERVER['HTTP_HOST'], 'csrf'=>$csrfSignature, 'user'=>$CONFIG['user']);
	$tokenContent = base64_encode(utf8_encode(json_encode($aTokenContent, JSON_UNESCAPED_SLASHES)));
	
	$token = $tokenHeader . '.' . $tokenContent;
	$hash = hash_hmac($rowsT[0]['algorithm'], $token, $rowsT[0]['key_token']);
	$signature = base64_encode($hash);
	$token .= '.' . $signature;
	setcookie('access', $token, 0, $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathApp'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], true);
}




?>