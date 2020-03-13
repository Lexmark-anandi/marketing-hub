<?php
$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$aTabs = array('ext', 'loc', 'res', 'uni');



$aNewId = array();
$aNewId['campaigns'] = array(0 => 0);
$aNewId['promotions'] = array(0 => 0);

$aTemplates = array($CONFIG['page']['id_data']);

include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-templates-copy-include.php');



###############################################################
$pkey = 'id_temp2count';
$id = 'id_tempid';
foreach($aNewId['templates'] as $idOld => $idNew){
	// copy data
	$table = $CONFIG['db'][0]['prefix'] . '_templates2countries_'; 

	$qry = '';
	$qry .= 'DROP TEMPORARY TABLE IF EXISTS tmp; ';
	$qry .= 'CREATE TEMPORARY TABLE tmp LIKE ' . $table . '; ';
	$query = $CONFIG['dbconn'][0]->prepare($qry);
	$query->execute();
	
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $table . '.' . $pkey . ' AS pkey
										FROM ' . $table . ' 
										WHERE ' . $table . '.' . $id . ' = (:id)
										');
	$query->bindValue(':id', $idOld, PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	
	foreach($rows as $row){
		$qry = 'TRUNCATE tmp; ';
		$qry .= 'INSERT tmp SELECT * FROM ' . $table . ' WHERE ' . $table . '.' . $pkey . ' = ' . $row['pkey'] . '; ';
		$qry .= 'UPDATE tmp SET ' . $pkey . ' = NULL; ';
		$qry .= 'UPDATE tmp SET ' . $id . ' = ' . $aNewId['templates'][$idOld] . '; ';
		$qry .= 'UPDATE tmp SET transrequest_at = "0000-00-00 00:00:00"; ';
		$qry .= 'UPDATE tmp SET transrequest_from = 0; ';
		$qry .= 'UPDATE tmp SET published_at = "0000-00-00 00:00:00"; ';
		$qry .= 'UPDATE tmp SET published_from = 0; ';
		$qry .= 'UPDATE tmp SET create_at = "' . $now . '"; ';
		$qry .= 'UPDATE tmp SET create_from = ' . $CONFIG['user']['id_real'] . '; ';
		$qry .= 'UPDATE tmp SET change_at = "' . $now . '"; ';
		$qry .= 'UPDATE tmp SET change_from = ' . $CONFIG['user']['id_real'] . '; ';
		$qry .= 'INSERT ' . $table . ' SELECT * FROM tmp; ';
		$qry .= 'TRUNCATE tmp; ';
		$query2 = $CONFIG['dbconn'][0]->prepare($qry);
		$query2->execute();
	}
}



echo $aNewId['templates'][$CONFIG['page']['id_data']];


?>