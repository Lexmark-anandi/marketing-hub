<?php
$CONFIG['system']['pathInclude'] = "../";
$CONFIG['noCheck'] = true;
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');

getConnection(0); 

writeLang();
 

function writeLang(){
	global $CONFIG, $TEXT;
	
	$aList = array(0 => array(0));
	
	if($CONFIG['system']['useSysMultiple'] == 1 && $CONFIG['system']['useSysMultipleLanguage'] == 1){
		$query = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_languages 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_languages.del = (:nultime)
											');
		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		$num = $query->rowCount();
		
		foreach($rows as $row){
			$aList[0][] = $row['id_sys_lang'];
		}
	}
	
	
	if($CONFIG['system']['useSysMultiple'] == 1 && $CONFIG['system']['useSysMultipleCountry'] == 1){
		$query = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_countries 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_countries.del = (:nultime)
											');
		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		$num = $query->rowCount();
		
		foreach($rows as $row){
			$aList[$row['id_sys_count']] = array();
			$aList[$row['id_sys_count']][] = 0;

			if($CONFIG['system']['useSysMultipleLanguage'] == 1){
				$query2 = $CONFIG['dbconn']->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang
													FROM ' . $CONFIG['db'][0]['prefix'] . 'system_languages 
													INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages 
														ON ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_lang = ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang
													WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_languages.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries2languages.id_sys_count = (:count)
													ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_languages.language
													');
				$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$query2->bindValue(':count', $row['id_sys_count'], PDO::PARAM_INT);
				$query2->bindValue(':active', 1, PDO::PARAM_INT);
				$query2->execute();
				$rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
				$num2 = $query2->rowCount();
			
				foreach($rows2 as $row2){
					$aList[$row['id_sys_count']][] = $row2['id_sys_lang'];
				}
			}
		}
	}
	
	
	foreach($aList as $count => $aLang){
		$query = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_countries.code
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_countries 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_countries.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count = (:count)
											');
		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$query->bindValue(':count', $count, PDO::PARAM_INT);
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		$num = $query->rowCount();
		if($num > 0) $codeCount = $rows[0]['code'];
		if($count == 0) $codeCount = 'all';
		
		foreach($aLang as $lang){
			$query = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_languages.code
												FROM ' . $CONFIG['db'][0]['prefix'] . 'system_languages 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_languages.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang = (:lang)
												');
			$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query->bindValue(':lang', $lang, PDO::PARAM_INT);
			$query->execute();
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);
			$num = $query->rowCount();
			if($num > 0) $codeLang = $rows[0]['code'];
			if($lang == 0) $codeLang = 'all';
	
	
			$terms = array();
			$query = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.var,
													' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.term
												FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni 
			
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_count = (:count)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_lang = (:lang)
												');
			$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query->bindValue(':count', $count, PDO::PARAM_INT);
			$query->bindValue(':lang', $lang, PDO::PARAM_INT);
			$query->execute();
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);
			$num = $query->rowCount();
			
			foreach($rows as $row){
				$terms[$row['var']] = $row['term'];
			}
			$storage_terms = gzcompress(serialize($terms));
			
			$filename = '';
			if($CONFIG['system']['useSysMultiple'] == 1 && $CONFIG['system']['useSysMultipleCountry'] == 1) $filename .= strtoupper($codeCount);
			if($CONFIG['system']['useSysMultiple'] == 1 && $CONFIG['system']['useSysMultipleCountry'] == 1 && $CONFIG['system']['useSysMultipleLanguage'] == 1) $filename .= '_';
			if($CONFIG['system']['useSysMultiple'] == 1 && $CONFIG['system']['useSysMultipleLanguage'] == 1) $filename .= strtolower($codeLang);
			if($CONFIG['system']['useSysMultiple'] == 0 || ($CONFIG['system']['useSysMultipleCountry'] == 0) && $CONFIG['system']['useSysMultipleLanguage'] == 0) $filename = strtolower($codeLang);
			$filename .= '.lang';
			
			
			
			
			
			$handle = fopen($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathAdmin'] . 'i18n/' . $filename, 'w');
			fwrite ($handle, $storage_terms);
			fclose ($handle);
		}
	}
}


?>