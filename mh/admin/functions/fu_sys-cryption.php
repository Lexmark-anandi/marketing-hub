<?php
function valuesEncrypt($aArgs=array()){
	global $CONFIG, $TEXT; 

	if(!isset($aArgs['data'])) $aArgs['data'] = array();
	if(!isset($aArgs['fields'])) $aArgs['fields'] = array();
	
	$queryT = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_config_access.method_cryption,
											' . $CONFIG['db'][0]['prefix'] . 'system_config_access.key_cryption
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
	
	// Encrypt
	if(isset($aArgs['fields']['cryption'])){
		foreach($aArgs['fields']['cryption'] as $field => $aFormat){ 
			$method = $rowsT[0]['method_cryption'];
			$key = $rowsT[0]['key_cryption'];
			//$iv = openssl_random_pseudo_bytes(16);
			$iv = "";
	
			//$aArgs['data']['encrypt_iv'] = base64_encode($iv);
			$aArgs['data'][$field] = ($aArgs['data'][$field] != '') ? base64_encode(openssl_encrypt($aArgs['data'][$field], $method, $key, OPENSSL_RAW_DATA, $iv)) : '';
		}
	}
	
	return $aArgs['data'];
}


function valuesDecrypt($aArgs=array()){
	global $CONFIG, $TEXT; 

	if(!isset($aArgs['data'])) $aArgs['data'] = array();
	if(!isset($aArgs['fields'])) $aArgs['fields'] = array();
	
	$queryT = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_config_access.method_cryption,
											' . $CONFIG['db'][0]['prefix'] . 'system_config_access.key_cryption
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
	
	// Decrypt
	if(isset($aArgs['fields']['cryption'])){
		foreach($aArgs['fields']['cryption'] as $field => $aFormat){
			$method = $rowsT[0]['method_cryption'];
			$key = $rowsT[0]['key_cryption'];
			//$iv = base64_decode($rows[0]['num']); 
			$iv = "";

			$aArgs['data'][$field] = ($aArgs['data'][$field] != '') ? trim(openssl_decrypt(base64_decode($aArgs['data'][$field]), $method, $key, OPENSSL_RAW_DATA, $iv)) : '';
		}
	}
	
	return $aArgs['data'];
}





?>