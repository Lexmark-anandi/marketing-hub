<?php
function deleteRow($aArgs = array()){
	global $CONFIG; 

	$date = new DateTime();
	$now = $date->format('Y-m-d H:i:s'); 
	
	foreach($aArgs['aDelete'] as $table => $key){
		$query = $CONFIG['dbconn']->prepare('
											UPDATE ' . $table . ' SET
											del = (:now)
											WHERE ' . $table . '.' . $key . ' = (:key)
												AND (' . $table . '.id_clid = (:id_clid)
													OR ' . $table . '.id_clid = (:nul))
											LIMIT 1
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':key', $aArgs['id'], PDO::PARAM_INT);
		$query->bindValue(':nul', 0, PDO::PARAM_INT);
		$query->bindValue(':id_clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
		$query->execute();


		$query = $CONFIG['dbconn']->prepare('
											UPDATE ' . $table . '_ext SET
											del = (:now)
											WHERE ' . $table . '_ext.' . $key . ' = (:key)
												AND (' . $table . '_ext.id_clid = (:id_clid)
													OR ' . $table . '_ext.id_clid = (:nul))
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':key', $aArgs['id'], PDO::PARAM_INT);
		$query->bindValue(':nul', 0, PDO::PARAM_INT);
		$query->bindValue(':id_clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
		$query->execute();


		$query = $CONFIG['dbconn']->prepare('
											UPDATE ' . $table . '_loc SET
											del = (:now)
											WHERE ' . $table . '_loc.' . $key . ' = (:key)
												AND (' . $table . '_loc.id_clid = (:id_clid)
													OR ' . $table . '_loc.id_clid = (:nul))
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':key', $aArgs['id'], PDO::PARAM_INT);
		$query->bindValue(':nul', 0, PDO::PARAM_INT);
		$query->bindValue(':id_clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
		$query->execute();


		$query = $CONFIG['dbconn']->prepare('
											UPDATE ' . $table . '_uni SET
											del = (:now)
											WHERE ' . $table . '_uni.' . $key . ' = (:key)
												AND (' . $table . '_uni.id_clid = (:id_clid)
													OR ' . $table . '_uni.id_clid = (:nul))
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':key', $aArgs['id'], PDO::PARAM_INT);
		$query->bindValue(':nul', 0, PDO::PARAM_INT);
		$query->bindValue(':id_clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
		$query->execute();
	}
}
?>
