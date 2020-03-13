<?php
$date = new DateTime();
$now = $date->format('Y-m-d');


$qry = 'SELECT ';
$qry .= 'products.200_products_uni.id_pid AS id_pid_products, ';
$qry .= 'products.200_products_uni.id_count, ';
$qry .= 'products.200_products_uni.id_lang, ';
$qry .= 'products.200_products_uni.announce_date, ';
$qry .= 'products.200_products_uni.withdraw_date ';
$qry .= 'FROM products.200_products_uni ';
$qry .= 'INNER JOIN 200_products_uni AS products_mh ';
$qry .= 'ON products.200_products_uni.id_count = products_mh.id_count ';
$qry .= 'AND products.200_products_uni.id_lang = products_mh.id_lang ';
$qry .= 'AND products.200_products_uni.id_pid = products_mh.id_pid_products ';
$qry .= 'AND (products.200_products_uni.announce_date <> products_mh.announce_date OR products.200_products_uni.withdraw_date <> products_mh.withdraw_date) ';
$queryS = $CONFIG['dbconn'][0]->prepare($qry);
$queryS->execute();
$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
$numS = $queryS->rowCount();
 
foreach($rowsS as $rowS){
	$queryP2 = $CONFIG['dbconn'][0]->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . '_products_ext SET 
											announce_date = (:announce_date),
											withdraw_date = (:withdraw_date)
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_pid_products = (:id_pid_products)
											AND ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_lang = (:id_lang)
										');
	$queryP2->bindValue(':id_pid_products', $rowS['id_pid_products'], PDO::PARAM_INT);
	$queryP2->bindValue(':id_count', $rowS['id_count'], PDO::PARAM_INT);
	$queryP2->bindValue(':id_lang', $rowS['id_lang'], PDO::PARAM_INT);
	$queryP2->bindValue(':announce_date', $rowS['announce_date'], PDO::PARAM_STR);
	$queryP2->bindValue(':withdraw_date', $rowS['withdraw_date'], PDO::PARAM_STR); 
	$queryP2->execute();

	$queryP2 = $CONFIG['dbconn'][0]->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . '_products_loc SET 
											announce_date = (:announce_date),
											withdraw_date = (:withdraw_date)
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_products_loc.id_pid_products = (:id_pid_products)
											AND ' . $CONFIG['db'][0]['prefix'] . '_products_loc.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_products_loc.id_lang = (:id_lang)
										');
	$queryP2->bindValue(':id_pid_products', $rowS['id_pid_products'], PDO::PARAM_INT);
	$queryP2->bindValue(':id_count', $rowS['id_count'], PDO::PARAM_INT);
	$queryP2->bindValue(':id_lang', $rowS['id_lang'], PDO::PARAM_INT);
	$queryP2->bindValue(':announce_date', $rowS['announce_date'], PDO::PARAM_STR);
	$queryP2->bindValue(':withdraw_date', $rowS['withdraw_date'], PDO::PARAM_STR); 
	$queryP2->execute();

	$queryP2 = $CONFIG['dbconn'][0]->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . '_products_uni SET 
											announce_date = (:announce_date),
											withdraw_date = (:withdraw_date)
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_pid_products = (:id_pid_products)
											AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_lang = (:id_lang)
										');
	$queryP2->bindValue(':id_pid_products', $rowS['id_pid_products'], PDO::PARAM_INT);
	$queryP2->bindValue(':id_count', $rowS['id_count'], PDO::PARAM_INT);
	$queryP2->bindValue(':id_lang', $rowS['id_lang'], PDO::PARAM_INT);
	$queryP2->bindValue(':announce_date', $rowS['announce_date'], PDO::PARAM_STR);
	$queryP2->bindValue(':withdraw_date', $rowS['withdraw_date'], PDO::PARAM_STR); 
	$queryP2->execute();
}






//
//$queryP = $CONFIG['dbconn'][0]->prepare('
//									SELECT cc.' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_p_data,
//										' . $CONFIG['db'][1]['prefix'] . '_products_ext.announce_date,
//										' . $CONFIG['db'][1]['prefix'] . '_products_ext.withdraw_date
//									FROM cc.' . $CONFIG['db'][0]['prefix'] . '_products_ext 
//									
//									INNER JOIN ' . $CONFIG['db'][1]['prefix'] . '_products_ext
//										ON cc.' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_count = ' . $CONFIG['db'][1]['prefix'] . '_products_ext.id_count
//											AND cc.' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_lang = ' . $CONFIG['db'][1]['prefix'] . '_products_ext.id_lang
//											AND cc.' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_pid_products = ' . $CONFIG['db'][1]['prefix'] . '_products_ext.id_pid
//									');
//$queryP->execute();
//$rowsP = $queryP->fetchAll(PDO::FETCH_ASSOC);
//$numP = $queryP->rowCount();
//foreach($rowsP as $rowP){
//	$queryP2 = $CONFIG['dbconn'][0]->prepare('
//										UPDATE ' . $CONFIG['db'][0]['prefix'] . '_products_ext SET 
//											announce_date = (:announce_date),
//											withdraw_date = (:withdraw_date)
//										WHERE ' . $CONFIG['db'][0]['prefix'] . '_products_ext.id_p_data = (:id_p_data)
//										LIMIT 1
//										');
//	$queryP2->bindValue(':id_p_data', $rowP['id_p_data'], PDO::PARAM_INT);
//	$queryP2->bindValue(':announce_date', $rowP['announce_date'], PDO::PARAM_STR);
//	$queryP2->bindValue(':withdraw_date', $rowP['withdraw_date'], PDO::PARAM_STR); 
//	$queryP2->execute();
//
//	$queryP2 = $CONFIG['dbconn'][0]->prepare('
//										UPDATE ' . $CONFIG['db'][0]['prefix'] . '_products_uni SET
//											announce_date = (:announce_date),
//											withdraw_date = (:withdraw_date)
//										WHERE ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_p_data = (:id_p_data)
//										LIMIT 1
//										');
//	$queryP2->bindValue(':id_p_data', $rowP['id_p_data'], PDO::PARAM_INT);
//	$queryP2->bindValue(':announce_date', $rowP['announce_date'], PDO::PARAM_STR);
//	$queryP2->bindValue(':withdraw_date', $rowP['withdraw_date'], PDO::PARAM_STR);
//	$queryP2->execute();
//}
//
?>