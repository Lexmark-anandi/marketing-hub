<?php
$queryC1 = $CONFIG['dbconn'][0]->prepare('
			UPDATE ' . $CONFIG['db'][0]['prefix'] . 'system_fields SET
				del = (:del)
			WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_fields.id_mod = 111
				AND ' . $CONFIG['db'][0]['prefix'] . 'system_fields.g_index LIKE "banner_original_' . $aArgsSave['id_data'] . '_%"
			');
$queryC1->bindValue(':del', $now, PDO::PARAM_STR); 
$queryC1->execute();

?>