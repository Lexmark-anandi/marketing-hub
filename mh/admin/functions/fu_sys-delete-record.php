<?php
function deleteRecord($aArgsDelete){
	global $CONFIG; 

	$date = new DateTime();
	$now = $date->format('Y-m-d H:i:s'); 
	
	$query = $CONFIG['dbconn'][0]->prepare('
										UPDATE ' . $aArgsDelete['table'] . ' SET
											del = (:now)
										WHERE ' . $aArgsDelete['table'] . '.' . $aArgsDelete['primarykey'] . ' = (:id)
											AND ' . $aArgsDelete['table'] . '.id_cl IN (0,' . $CONFIG['activeSettings']['id_clid'] . ')
										');
	$query->bindValue(':now', $now, PDO::PARAM_STR);
	$query->bindValue(':id', $aArgsDelete['id_data'], PDO::PARAM_INT);
	$query->execute();


	if($aArgsDelete['suffix'] == 1){
		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $aArgsDelete['table'] . 'ext SET
												del = (:now)
											WHERE ' . $aArgsDelete['table'] . 'ext.' . $aArgsDelete['primarykey'] . ' = (:id)
												AND ' . $aArgsDelete['table'] . 'ext.id_cl IN (0,' . $CONFIG['activeSettings']['id_clid'] . ')
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':id', $aArgsDelete['id_data'], PDO::PARAM_INT);
		$query->execute();


		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $aArgsDelete['table'] . 'loc SET
												del = (:now)
											WHERE ' . $aArgsDelete['table'] . 'loc.' . $aArgsDelete['primarykey'] . ' = (:id)
												AND ' . $aArgsDelete['table'] . 'loc.id_cl IN (0,' . $CONFIG['activeSettings']['id_clid'] . ')
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':id', $aArgsDelete['id_data'], PDO::PARAM_INT);
		$query->execute();


		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $aArgsDelete['table'] . 'res SET
												del = (:now)
											WHERE ' . $aArgsDelete['table'] . 'res.' . $aArgsDelete['primarykey'] . ' = (:id)
												AND ' . $aArgsDelete['table'] . 'res.id_cl IN (0,' . $CONFIG['activeSettings']['id_clid'] . ')
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':id', $aArgsDelete['id_data'], PDO::PARAM_INT);
		$query->execute();


		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $aArgsDelete['table'] . 'uni SET
												del = (:now)
											WHERE ' . $aArgsDelete['table'] . 'uni.' . $aArgsDelete['primarykey'] . ' = (:id)
												AND ' . $aArgsDelete['table'] . 'uni.id_cl IN (0,' . $CONFIG['activeSettings']['id_clid'] . ')
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':id', $aArgsDelete['id_data'], PDO::PARAM_INT);
		$query->execute();
	}
}
?>