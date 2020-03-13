<?php
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
$CONFIG['noCheck'] = true;
include_once(__DIR__ . '/../app/config-app.php');
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$out = array();



$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang = (:count2lang)
									');
$query->bindValue(':count2lang', $varSQL['count2lang'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC); 
$num = $query->rowCount();
$id_count = $rows[0]['id_countid'];
$id_lang = $rows[0]['id_langid'];


$listOut = '<option value="0"></option>';
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_pcid,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.company_name,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.program_tier,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.bsd_silver,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.bsd_gold,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.bsd_diamond,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.parent_program_name
									FROM ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni 

									WHERE ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_countid = (:count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_langid = (:lang)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.company_name
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':count', $id_count, PDO::PARAM_INT);
$query->bindValue(':lang', $id_lang, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
echo $num;
foreach($rows as $row){
	$range = 'all';
	if(strtolower($row['bsd_silver']) == 'yes' || strtolower($row['bsd_gold']) == 'yes' || strtolower($row['bsd_diamond']) == 'yes') $range = 'bsd';
	if(in_array($row['parent_program_name'], $CONFIG['aProgramm']['BSD'])) $range = 'bsd';
	if(in_array($row['parent_program_name'], $CONFIG['aProgramm']['Distribution'])) $range = 'distribution';
        echo $range;
	
	if($range == $varSQL['range']) $listOut .= '<option value="' . $row['id_pcid'] . '">' . $row['company_name'] . '</option>';
}





echo $listOut;

?>