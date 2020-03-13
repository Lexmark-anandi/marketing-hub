<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();


$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');


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
$query->bindValue(':id_promid', $CONFIG['page']['id_data'], PDO::PARAM_STR);
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
$query->bindValue(':id_promid', $CONFIG['page']['id_data'], PDO::PARAM_STR);
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
$query->bindValue(':id_promid', $CONFIG['page']['id_data'], PDO::PARAM_STR);
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
$query->bindValue(':id_promid', $CONFIG['page']['id_data'], PDO::PARAM_STR);
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
$query->bindValue(':id_promid', $CONFIG['page']['id_data'], PDO::PARAM_STR);
if($CONFIG['settings']['formCountry'] != 0){
	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
}
$query->execute();



##########################################


$condOrg = '';
if($CONFIG['settings']['formCountry'] != 0){
	$condOrg = 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_##tab##.id_count = (:id_count)';
	$condOrg .= 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_##tab##.id_lang = (:id_lang)';
}


$cond = str_replace('##tab##', 'ext', $condOrg);
$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_ext SET
										published_at = (:now),
										published_from = (:published_from)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_ext.id_promid = (:id_promid)
										' . $cond . '
									');
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->bindValue(':published_from', $CONFIG['user']['id'], PDO::PARAM_STR);
$query->bindValue(':id_promid', $CONFIG['page']['id_data'], PDO::PARAM_STR);
if($CONFIG['settings']['formCountry'] != 0){
	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
}
$query->execute();

$cond = str_replace('##tab##', 'loc', $condOrg);
$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_loc SET
										published_at = (:now),
										published_from = (:published_from)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_loc.id_promid = (:id_promid)
										' . $cond . '
									');
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->bindValue(':published_from', $CONFIG['user']['id'], PDO::PARAM_STR);
$query->bindValue(':id_promid', $CONFIG['page']['id_data'], PDO::PARAM_STR);
if($CONFIG['settings']['formCountry'] != 0){
	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
}
$query->execute();

$cond = str_replace('##tab##', 'res', $condOrg);
$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_res SET
										published_at = (:now),
										published_from = (:published_from)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_res.id_promid = (:id_promid)
										' . $cond . '
									');
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->bindValue(':published_from', $CONFIG['user']['id'], PDO::PARAM_STR);
$query->bindValue(':id_promid', $CONFIG['page']['id_data'], PDO::PARAM_STR);
if($CONFIG['settings']['formCountry'] != 0){
	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
}
$query->execute();

$cond = str_replace('##tab##', 'uni', $condOrg);
$query = $CONFIG['dbconn'][0]->prepare('
									UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni SET
										published_at = (:now),
										published_from = (:published_from)
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid = (:id_promid)
										' . $cond . '
									');
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->bindValue(':published_from', $CONFIG['user']['id'], PDO::PARAM_STR);
$query->bindValue(':id_promid', $CONFIG['page']['id_data'], PDO::PARAM_STR);
if($CONFIG['settings']['formCountry'] != 0){
	$query->bindValue(':id_count', $CONFIG['settings']['formCountry'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['settings']['formLanguage'], PDO::PARAM_INT);
}
$query->execute();





?>