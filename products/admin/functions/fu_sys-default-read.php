<?php

if($numD > 0){
	foreach($rowsD as $rowD){
		$rowD['identifier'] = '';
		foreach($identifier as $ident){
			$rowD['identifier'] .= (substr($ident, 0,2) == '##') ? $rowD[substr($ident, 2)] : $ident;
		}
		
		foreach($addColumns as $col => $val){
			$rowD[$col] = $val;
		}
		
		$query = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data,
												' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid
											FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_clid = (:clid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.modulname = (:modulname)
											');
		$query->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
		$query->bindValue(':id_count', $rowD['id_count'], PDO::PARAM_INT);
		$query->bindValue(':id_lang', $rowD['id_lang'], PDO::PARAM_INT);
		$query->bindValue(':id_dev', $rowD['id_dev'], PDO::PARAM_INT);
		$query->bindValue(':clid', $rowD['id_clid'], PDO::PARAM_INT);
		$query->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
		$query->bindValue(':modulname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
		$query->execute(); 
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		$num = $query->rowCount();
		
		if($num == 0){
			$rowD = setValuesRead($rowD, $aFields, $rowD['id_count'], $rowD['id_lang'], $rowD['id_dev']);
		
			$query2 = $CONFIG['dbconn']->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
													(
													id,
													id_count,
													id_lang,
													id_dev,
													id_clid,
													id_uid,
													modulname,
													data
													)
												VALUES
													(
													:id,
													:id_count,
													:id_lang,
													:id_dev,
													:clid,
													:uid,
													:modulname,
													:data
													)
												');
			$query2->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
			$query2->bindValue(':id_count', $rowD['id_count'], PDO::PARAM_INT);
			$query2->bindValue(':id_lang', $rowD['id_lang'], PDO::PARAM_INT);
			$query2->bindValue(':id_dev', $rowD['id_dev'], PDO::PARAM_INT);
			$query2->bindValue(':clid', $rowD['id_clid'], PDO::PARAM_INT);
			$query2->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
			$query2->bindValue(':modulname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
			$query2->bindValue(':data', json_encode($rowD), PDO::PARAM_STR);
			$query2->execute();
			
			if($rowD['id_count'] == $CONFIG['page']['moduls'][$varSQL['modul']]['formCountry'] && $rowD['id_lang'] == $CONFIG['page']['moduls'][$varSQL['modul']]['formLanguage'] && $rowD['id_dev'] == $CONFIG['page']['moduls'][$varSQL['modul']]['formDevice']) $out = json_encode($rowD);
		}else{
			if($rowD['id_count'] == $CONFIG['page']['moduls'][$varSQL['modul']]['formCountry'] && $rowD['id_lang'] == $CONFIG['page']['moduls'][$varSQL['modul']]['formLanguage'] && $rowD['id_dev'] == $CONFIG['page']['moduls'][$varSQL['modul']]['formDevice']) $out = $rows[0]['data'];
		}
	} 
	
	if($out == ''){
//		$query2 = $CONFIG['dbconn']->prepare('
//											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
//												(
//												id,
//												id_count,
//												id_lang,
//												id_dev,
//												id_clid,
//												id_uid,
//												modulname,
//												data
//												)
//											VALUES
//												(
//												:id,
//												:id_count,
//												:id_lang,
//												:id_dev,
//												:clid,
//												:uid,
//												:modulname,
//												:data
//												)
//											');
//		$query2->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
//		$query2->bindValue(':id_count', $varSQL['formCountry'], PDO::PARAM_INT);
//		$query2->bindValue(':id_lang', $varSQL['formLanguage'], PDO::PARAM_INT);
//		$query2->bindValue(':id_dev', $varSQL['formDevice'], PDO::PARAM_INT);
//		$query2->bindValue(':clid', $rowD['id_clid'], PDO::PARAM_INT);
//		$query2->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
//		$query2->bindValue(':modulname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
//		$query2->bindValue(':data', json_encode($rowsD[0]), PDO::PARAM_STR);
//		$query2->execute();
//		
		$out = json_encode($rowsD[0]);
	}
	
}else{
	$out['id_data'] = '0';
	$out['id'] = '0';
	$out['id_count'] = $CONFIG['page']['moduls'][$varSQL['modul']]['formCountry'];
	$out['id_lang'] = $CONFIG['page']['moduls'][$varSQL['modul']]['formLanguage'];
	$out['id_dev'] = $CONFIG['page']['moduls'][$varSQL['modul']]['formDevice'];
	$out['id_clid'] = (substr($CONFIG['page']['moduls'][$varSQL['modul']]['specifics'], 7, 1) == 9) ? $CONFIG['USER']['activeClient'] : 0;
	foreach($outTmp as $k => $v){
		$out[$k] = $v;
	}

	$query2 = $CONFIG['dbconn']->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
											(
											id,
											id_count,
											id_lang,
											id_dev,
											id_clid,
											id_uid,
											modulname,
											data
											)
										VALUES
											(
											:id,
											:id_count,
											:id_lang,
											:id_dev,
											:clid,
											:uid,
											:modulname,
											:data
											)
										');
	$query2->bindValue(':id', $out['id'], PDO::PARAM_INT);
	$query2->bindValue(':id_count', $CONFIG['page']['moduls'][$varSQL['modul']]['formCountry'], PDO::PARAM_INT);
	$query2->bindValue(':id_lang', $CONFIG['page']['moduls'][$varSQL['modul']]['formLanguage'], PDO::PARAM_INT);
	$query2->bindValue(':id_dev', $CONFIG['page']['moduls'][$varSQL['modul']]['formDevice'], PDO::PARAM_INT);
	$query2->bindValue(':clid', $out['id_clid'], PDO::PARAM_INT);
	$query2->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
	$query2->bindValue(':modulname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
	$query2->bindValue(':data', json_encode($out), PDO::PARAM_STR);
	$query2->execute();
	
	$out = json_encode($out);
}

echo $out;

?>