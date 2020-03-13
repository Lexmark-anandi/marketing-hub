<?php
include_once(__DIR__ . '/fu-templates-publish.php');


// update promotion
$queryP = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid,
										' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_campid
									FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni 
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev = (:id_dev)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid = (:id)
									');
$queryP->bindValue(':id_count', 0, PDO::PARAM_INT);
$queryP->bindValue(':id_lang', 0, PDO::PARAM_INT);
$queryP->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryP->bindValue(':id', $CONFIG['page']['id_data'], PDO::PARAM_INT);
$queryP->execute(); 
$rowsP = $queryP->fetchAll(PDO::FETCH_ASSOC);
$numP = $queryP->rowCount();

if($rowsP[0]['id_promid'] != 0){
	$query2 = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni 
											
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_
											ON ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_langid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_promid = (:id_promid)

										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid = (:id_promid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.published_at = (:nultime)
										');
	$query2->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
	$query2->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
	$query2->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$query2->bindValue(':id_promid', $rowsP[0]['id_promid'], PDO::PARAM_INT);
	$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query2->execute(); 
	$rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$num2 = $query2->rowCount();
	
	if($num2 == 0){
		$cond = '';
		if($CONFIG['settings']['formCountry'] != 0){
			$cond = 'AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid = (:id_count)';
			$cond .= 'AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_langid = (:id_lang)';
		}
		
		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_ SET
												published_at = (:now),
												published_from = (:published_from)
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_promid = (:id_promid)
												' . $cond . '
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':published_from', $CONFIG['user']['id'], PDO::PARAM_STR);
		$query->bindValue(':id_promid', $rowsP[0]['id_promid'], PDO::PARAM_STR);
		if($CONFIG['settings']['formCountry'] != 0){
			$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
			$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
		}
		$query->execute();
		
		
		##########################################
		
		
		$condOrg = '';
		if($CONFIG['settings']['formCountry'] != 0){
			$condOrg = 'AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_##tab##.id_count = (:id_count)';
			$condOrg .= 'AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_##tab##.id_lang = (:id_lang)';
		}
		
		
		$cond = str_replace('##tab##', 'ext', $condOrg);
		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_promotions_ext SET
												published_at = (:now),
												published_from = (:published_from)
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions_ext.id_promid = (:id_promid)
												' . $cond . '
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':published_from', $CONFIG['user']['id'], PDO::PARAM_STR);
		$query->bindValue(':id_promid', $rowsP[0]['id_promid'], PDO::PARAM_STR);
		if($CONFIG['settings']['formCountry'] != 0){
			$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
			$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
		}
		$query->execute();
		
		$cond = str_replace('##tab##', 'loc', $condOrg);
		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_promotions_loc SET
												published_at = (:now),
												published_from = (:published_from)
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions_loc.id_promid = (:id_promid)
												' . $cond . '
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':published_from', $CONFIG['user']['id'], PDO::PARAM_STR);
		$query->bindValue(':id_promid', $rowsP[0]['id_promid'], PDO::PARAM_STR);
		if($CONFIG['settings']['formCountry'] != 0){
			$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
			$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
		}
		$query->execute();
		
		$cond = str_replace('##tab##', 'res', $condOrg);
		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_promotions_res SET
												published_at = (:now),
												published_from = (:published_from)
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions_res.id_promid = (:id_promid)
												' . $cond . '
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':published_from', $CONFIG['user']['id'], PDO::PARAM_STR);
		$query->bindValue(':id_promid', $rowsP[0]['id_promid'], PDO::PARAM_STR);
		if($CONFIG['settings']['formCountry'] != 0){
			$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
			$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
		}
		$query->execute();
		
		$cond = str_replace('##tab##', 'uni', $condOrg);
		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni SET
												published_at = (:now),
												published_from = (:published_from)
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid = (:id_promid)
												' . $cond . '
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':published_from', $CONFIG['user']['id'], PDO::PARAM_STR);
		$query->bindValue(':id_promid', $rowsP[0]['id_promid'], PDO::PARAM_STR);
		if($CONFIG['settings']['formCountry'] != 0){
			$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
			$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
		}
		$query->execute();
	}
}



if($rowsP[0]['id_campid'] != 0){
	$query2 = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni 
											
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_
											ON ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_countid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_langid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_campid = (:id_campid)

										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_campid = (:id_campid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.published_at = (:nultime)
										');
	$query2->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
	$query2->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
	$query2->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$query2->bindValue(':id_campid', $rowsP[0]['id_campid'], PDO::PARAM_INT);
	$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query2->execute(); 
	$rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$num2 = $query2->rowCount();
	
	if($num2 == 0){
		$cond = '';
		if($CONFIG['settings']['formCountry'] != 0){
			$cond = 'AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_countid = (:id_count)';
			$cond .= 'AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_langid = (:id_lang)';
		}
		
		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_ SET
												published_at = (:now),
												published_from = (:published_from)
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_campid = (:id_campid)
												' . $cond . '
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':published_from', $CONFIG['user']['id'], PDO::PARAM_STR);
		$query->bindValue(':id_campid', $rowsP[0]['id_campid'], PDO::PARAM_STR);
		if($CONFIG['settings']['formCountry'] != 0){
			$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
			$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
		}
		$query->execute();
		
		
		##########################################
		
		
		$condOrg = '';
		if($CONFIG['settings']['formCountry'] != 0){
			$condOrg = 'AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_##tab##.id_count = (:id_count)';
			$condOrg .= 'AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_##tab##.id_lang = (:id_lang)';
		}
		
		
		$cond = str_replace('##tab##', 'ext', $condOrg);
		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_campaigns_ext SET
												published_at = (:now),
												published_from = (:published_from)
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_campaigns_ext.id_campid = (:id_campid)
												' . $cond . '
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':published_from', $CONFIG['user']['id'], PDO::PARAM_STR);
		$query->bindValue(':id_campid', $rowsP[0]['id_campid'], PDO::PARAM_STR);
		if($CONFIG['settings']['formCountry'] != 0){
			$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
			$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
		}
		$query->execute();
		
		$cond = str_replace('##tab##', 'loc', $condOrg);
		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_campaigns_loc SET
												published_at = (:now),
												published_from = (:published_from)
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_campaigns_loc.id_campid = (:id_campid)
												' . $cond . '
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':published_from', $CONFIG['user']['id'], PDO::PARAM_STR);
		$query->bindValue(':id_campid', $rowsP[0]['id_campid'], PDO::PARAM_STR);
		if($CONFIG['settings']['formCountry'] != 0){
			$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
			$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
		}
		$query->execute();
		
		$cond = str_replace('##tab##', 'res', $condOrg);
		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_campaigns_res SET
												published_at = (:now),
												published_from = (:published_from)
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_campaigns_res.id_campid = (:id_campid)
												' . $cond . '
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':published_from', $CONFIG['user']['id'], PDO::PARAM_STR);
		$query->bindValue(':id_campid', $rowsP[0]['id_campid'], PDO::PARAM_STR);
		if($CONFIG['settings']['formCountry'] != 0){
			$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
			$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
		}
		$query->execute();
		
		$cond = str_replace('##tab##', 'uni', $condOrg);
		$query = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni SET
												published_at = (:now),
												published_from = (:published_from)
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid = (:id_campid)
												' . $cond . '
											');
		$query->bindValue(':now', $now, PDO::PARAM_STR);
		$query->bindValue(':published_from', $CONFIG['user']['id'], PDO::PARAM_STR);
		$query->bindValue(':id_campid', $rowsP[0]['id_campid'], PDO::PARAM_STR);
		if($CONFIG['settings']['formCountry'] != 0){
			$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
			$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
		}
		$query->execute();
	}
}



//include_once(__DIR__ . '/../config-admin.php');
//$varSQL = getPostData();
//
//
//$date = new DateTime();
//$now = $date->format('Y-m-d H:i:s');
//
//$condOrg = '';
//if($CONFIG['settings']['formCountry'] != 0){
//	$condOrg = 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_##tab##.id_count = (:id_count)';
//	$condOrg .= 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_##tab##.id_lang = (:id_lang)';
//}
//
//
//$cond = str_replace('##tab##', 'ext', $condOrg);
//$query = $CONFIG['dbconn'][0]->prepare('
//									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_ext SET
//										published_at = (:now),
//										published_from = (:published_from)
//									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_ext.id_tempid = (:id_tempid)
//										' . $cond . '
//									');
//$query->bindValue(':now', $now, PDO::PARAM_STR);
//$query->bindValue(':published_from', $CONFIG['user']['id'], PDO::PARAM_STR); 
//$query->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_STR);
//if($CONFIG['settings']['formCountry'] != 0){
//	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//}
//$query->execute();
//
//$cond = str_replace('##tab##', 'loc', $condOrg);
//$query = $CONFIG['dbconn'][0]->prepare('
//									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_loc SET
//										published_at = (:now),
//										published_from = (:published_from)
//									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_loc.id_tempid = (:id_tempid)
//										' . $cond . '
//									');
//$query->bindValue(':now', $now, PDO::PARAM_STR);
//$query->bindValue(':published_from', $CONFIG['user']['id'], PDO::PARAM_STR);
//$query->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_STR);
//if($CONFIG['settings']['formCountry'] != 0){
//	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//}
//$query->execute();
//
//$cond = str_replace('##tab##', 'res', $condOrg);
//$query = $CONFIG['dbconn'][0]->prepare('
//									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_res SET
//										published_at = (:now),
//										published_from = (:published_from)
//									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_res.id_tempid = (:id_tempid)
//										' . $cond . '
//									');
//$query->bindValue(':now', $now, PDO::PARAM_STR);
//$query->bindValue(':published_from', $CONFIG['user']['id'], PDO::PARAM_STR);
//$query->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_STR);
//if($CONFIG['settings']['formCountry'] != 0){
//	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//}
//$query->execute();
//
//$cond = str_replace('##tab##', 'uni', $condOrg);
//$query = $CONFIG['dbconn'][0]->prepare('
//									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni SET
//										published_at = (:now),
//										published_from = (:published_from)
//									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid = (:id_tempid)
//										' . $cond . '
//									');
//$query->bindValue(':now', $now, PDO::PARAM_STR);
//$query->bindValue(':published_from', $CONFIG['user']['id'], PDO::PARAM_STR);
//$query->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_STR);
//if($CONFIG['settings']['formCountry'] != 0){
//	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
//	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
//}
//$query->execute();





?>