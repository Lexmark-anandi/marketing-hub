<?php
$query = $CONFIG['dbconn'][0]->prepare('
									DELETE FROM ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_uid = (:id)
									');
$query->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
$query->execute();


$query = $CONFIG['dbconn'][0]->prepare('
									DELETE ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user2countries2languages.id_uid = (:id)
									');
$query->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
$query->execute();

?>