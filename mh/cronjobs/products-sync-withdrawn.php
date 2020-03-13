<?php
$date = new DateTime();
$now = $date->format('Y-m-d');


$queryP = $CONFIG['dbconn'][0]->prepare('
									SELECT cc.' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_p_data,
										cc.' . $CONFIG['db'][0]['prefix'] . '_products_ext.is_bsd
									FROM cc.' . $CONFIG['db'][0]['prefix'] . '_products_ext 
									
									INNER JOIN ' . $CONFIG['db'][1]['prefix'] . '_products_ext
										ON cc.' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_count = ' . $CONFIG['db'][1]['prefix'] . '_products_ext.id_count
											AND cc.' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_lang = ' . $CONFIG['db'][1]['prefix'] . '_products_ext.id_lang
											AND cc.' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_pid_products = ' . $CONFIG['db'][1]['prefix'] . '_products_ext.id_pid
									
									WHERE ' . $CONFIG['db'][1]['prefix'] . '_products_ext.del = (:nultime)
										AND ' . $CONFIG['db'][1]['prefix'] . '_products_ext.withdraw_date <> "0000-00-00"
										AND ' . $CONFIG['db'][1]['prefix'] . '_products_ext.withdraw_date < (:now)
										AND ' . $CONFIG['db'][1]['prefix'] . '_products_ext.status IN ("Public", "Not Public - B2B")
									');
$queryP->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryP->bindValue(':now', $now, PDO::PARAM_STR);
$queryP->execute();
$rowsP = $queryP->fetchAll(PDO::FETCH_ASSOC);
$numP = $queryP->rowCount();
foreach($rowsP as $rowP){
	$queryP2 = $CONFIG['dbconn'][0]->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . '_products_ext SET
											status = (:status)
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_p_data = (:id_p_data)
										LIMIT 1
										');
	$queryP2->bindValue(':id_p_data', $rowP['id_p_data'], PDO::PARAM_INT);
	$queryP2->bindValue(':status', ($rowP['is_bsd'] == 1) ? 'B2B - Withdrawn' : 'Withdrawn from Marketing', PDO::PARAM_STR);
	$queryP2->execute();

	$queryP2 = $CONFIG['dbconn'][0]->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . '_products_uni SET
											status = (:status)
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_p_data = (:id_p_data)
										LIMIT 1
										');
	$queryP2->bindValue(':id_p_data', $rowP['id_p_data'], PDO::PARAM_INT);
	$queryP2->bindValue(':status', ($rowP['is_bsd'] == 1) ? 'B2B - Withdrawn' : 'Withdrawn from Marketing', PDO::PARAM_STR);
	$queryP2->execute();
}

?>