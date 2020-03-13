<?php 
$initCondPage = true;
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();


$date = new DateTime();
$today = $date->format('Y-m-d');

$aCountries = array(0);;
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni 

									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang IN (' . $varSQL['countries'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.del = (:nultime)
									');
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	array_push($aCountries, $row['id_countid']);	
}


$listOut = '<option value="0"></option>';
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_pcid,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.company_name,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.address1,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.zipcode,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.city,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.email,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.program_tier,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.bsd_silver,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.bsd_gold,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.bsd_diamond
									FROM ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni 

									WHERE ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_countid IN (' . implode(',', $aCountries) . ')
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.company_name
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

foreach($rows as $row){
	$range = 'default';
	if(strtolower($row['bsd_silver']) == 'yes' || strtolower($row['bsd_gold']) == 'yes' || strtolower($row['bsd_diamond']) == 'yes') $range = 'bsd';
	$identifier = strtolower($row['company_name'] . ' ' . $row['email'] . ' ');
	$listOut .= '<option value="' . $row['id_pcid'] . '" data-identifier="' . $identifier . '" data-range="' . $range . '">' . $row['company_name'] . '</option>';
}
  

echo $listOut;

?>