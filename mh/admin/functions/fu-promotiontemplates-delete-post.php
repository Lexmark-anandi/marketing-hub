<?php
$queryC1 = $CONFIG['dbconn'][0]->prepare('
			UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_ext SET
				del = (:del)
			WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_ext.id_promid = (:id_promid)
			');
$queryC1->bindValue(':del', $now, PDO::PARAM_STR); 
$queryC1->bindValue(':id_promid', $aArgsSave['id_data'], PDO::PARAM_INT); 
$queryC1->execute();

$queryC1 = $CONFIG['dbconn'][0]->prepare('
			UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_loc SET
				del = (:del)
			WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_loc.id_promid = (:id_promid)
			');
$queryC1->bindValue(':del', $now, PDO::PARAM_STR); 
$queryC1->bindValue(':id_promid', $aArgsSave['id_data'], PDO::PARAM_INT); 
$queryC1->execute();

$queryC1 = $CONFIG['dbconn'][0]->prepare('
			UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_res SET
				del = (:del)
			WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_res.id_promid = (:id_promid)
			');
$queryC1->bindValue(':del', $now, PDO::PARAM_STR); 
$queryC1->bindValue(':id_promid', $aArgsSave['id_data'], PDO::PARAM_INT); 
$queryC1->execute();

$queryC1 = $CONFIG['dbconn'][0]->prepare('
			UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni SET
				del = (:del)
			WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid = (:id_promid)
			');
$queryC1->bindValue(':del', $now, PDO::PARAM_STR); 
$queryC1->bindValue(':id_promid', $aArgsSave['id_data'], PDO::PARAM_INT); 
$queryC1->execute();


?>