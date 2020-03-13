<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$modulpath = $CONFIG['page']['modulpath'];

$out = array();
$out['list'] = '<div class="countryTable">
		<div class="countryTableRow">
			<div class="countryTableCell countryTableCellCountry"></div>
			<div class="countryTableCell countryTableCellPages">' . $TEXT['pages'] . '</div>
		</div>
';


$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.page_number,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country,
										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
									FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni 
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni
										ON ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_count = ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_count
											AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_lang = ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_lang
											AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_kcid = ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_kcid
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_
										ON ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_countid = ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_count
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_langid = ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_lang
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.del = (:nultime)
											
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_countid

									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_langid
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = (:id_tempid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.del = (:nultime)
									');
$query->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_INT);
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

foreach($rows as $row){
	$out['list'] .= '<div class="countryTableRow">';
	$out['list'] .= '<div class="countryTableCell countryTableCellCountry">' . $row['country'] . ' / ' . $row['language'] . '</div>';
	$out['list'] .= '<div class="countryTableCell countryTableCellPages">' . $row['page_number'] . '</div>';
	$out['list'] .= '</div>';
}
$out['list'] .= '</div>';
//			$arr = $query->errorInfo();
//			print_r($arr);










//if($varSQL['kiado'] != ''){
//	$query = $CONFIG['dbconn'][0]->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.link,
//											' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.master,
//											' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.page_number
//										FROM ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni 
//										
//										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
//											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_count
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_lang
//										
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang = (:id_count2lang)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//											AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.del = (:nultime)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.code = (:code)
//										');
//	$query->bindValue(':id_count2lang', $varSQL['count2lang'], PDO::PARAM_INT);
//	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$query->bindValue(':code', $varSQL['kiado'], PDO::PARAM_INT);
//	$query->execute();
//	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//	$num = $query->rowCount();
//	
//	if($num == 0){
//		$query = $CONFIG['dbconn'][0]->prepare('
//											SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code_spec AS code_count,
//												' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code_spec AS code_lang
//											FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni 
//											
//											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
//												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
//											
//											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
//												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid
//											
//											WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//		
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//		
//												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang  = (:id_count2lang)
//											');
//		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//		$query->bindValue(':nul', 0, PDO::PARAM_INT);
//		$query->bindValue(':id_count2lang', $varSQL['count2lang'], PDO::PARAM_INT);
//		$query->execute();
//		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//		$num = $query->rowCount();
//		
//		foreach($rows as $row){
//			$urlKiado = 'http://kdr.lexmark.com/media/' . $varSQL['kiado'] . '?lang=' . strtolower($row['code_lang']) . '_' . strtoupper($row['code_count']) . '&format=high';
//			$ch = curl_init();
//			curl_setopt($ch, CURLOPT_URL, $urlKiado);
//			curl_setopt($ch, CURLOPT_HEADER, true);
//			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//			curl_exec($ch);
//			
//			$urlReal = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
//			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);	
//			
//			$out['preview'] = $urlReal;
//			$out['master'] = (substr_count($urlReal, '/master/') > 0) ? 1 : 2;
//			$out['pages'] = '?';
//			
//			if($code == '404'){
//				$out['preview'] = '';
//				$out['master'] = 2;
//			}
//		}
//		
//	}else{
//		
//		$out['preview'] = $rows[0]['link'];
//		$out['master'] = $rows[0]['master'];
//		$out['pages'] = $rows[0]['page_number'];
//	}
//}

echo json_encode($out); 


?>