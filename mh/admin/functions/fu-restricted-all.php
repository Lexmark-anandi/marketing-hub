<?php
########################################################
// Setting for restricted access to countries (alles unter Obernull)

//if($aTokenContent['user']['right'] == 3){
//	$queryRa = $CONFIG['dbconn'][0]->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_geoid
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_user 
//											
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid = (:id_uid)
//										');
//	$queryRa->bindValue(':id_uid', $aTokenContent['user']['id'], PDO::PARAM_INT);
//	$queryRa->execute();
//	$rowsRa = $queryRa->fetchAll(PDO::FETCH_ASSOC);
//	$numRa = $queryRa->rowCount();
//
//	$CONFIG_TMP['user']['restricted_all'] = $rowsRa[0]['id_geoid'];  
//}



?>