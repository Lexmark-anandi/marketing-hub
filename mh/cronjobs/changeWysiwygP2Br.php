<?php
include_once(__DIR__ . '/../config-all.php');
include_once(__DIR__ . '/../custom/config-all-custom.php');

include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-connect.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-local-variations.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-insert-all.php');

$CONFIG['user']['id_real'] = 0;
$CONFIG['user']['activeClient'] = 1;
$CONFIG['user']['restricted_all'] = 0;
$CONFIG['user']['specifications'][14] = 9;
$CONFIG['activeSettings']['id_clid'] = 1;
$mediaPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'];

ini_set("display_errors", "on");
ini_set("memory_limit", "512M");
ini_set("max_execution_time", "6000");

getConnection(0); 



$aTabs = array('_ext', '_loc', '_res', '_uni');
$aTabsAs = array('_ext', '_loc', '_res', '_uni', '_tmp');


foreach($aTabsAs as $tab){
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts' . $tab . '.id_ap_data,
											' . $CONFIG['db'][0]['prefix'] . '_assetsproducts' . $tab . '.not_lpmd
										FROM ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts' . $tab . ' 
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts' . $tab . '.not_lpmd <> ""
										');
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	//echo $num.'<br>';
	foreach($rows as $row){
		$aC = json_decode($row['not_lpmd'], true);
		
		
		
		foreach($aC as $key => &$value){
			$c = $value;
			$c = trim(preg_replace('/\s\s+/', ' ', $c));
			$c = str_replace("</p> <p", '</p><br><p', $c);
			$c = str_replace("<p>", '<div>', $c);
			$c = str_replace("<p style", '<div style', $c);
			$c = str_replace("</p>", '</div>', $c);
			$aC[$key] = $c;
		}

		$query2 = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts' . $tab . ' SET
												not_lpmd = (:not_lpmd)
												
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetsproducts' . $tab . '.id_ap_data = (:id_ap_data)
											LIMIT 1
											');
		$query2->bindValue(':not_lpmd', json_encode($aC), PDO::PARAM_STR);
		$query2->bindValue(':id_ap_data', $row['id_ap_data'], PDO::PARAM_INT);
		$query2->execute();
	}
}



#################################################################


foreach($aTabsAs as $tab){
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_assets' . $tab . '.id_as_data,
											' . $CONFIG['db'][0]['prefix'] . '_assets' . $tab . '.components
										FROM ' . $CONFIG['db'][0]['prefix'] . '_assets' . $tab . ' 
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_assets' . $tab . '.components <> ""
										');
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	//echo $num.'<br>';
	foreach($rows as $row){
		$aC = json_decode($row['components'], true);
		
		foreach($aC['pages'] as $pageid => &$page){
			foreach($page as $compid => &$comp){
				if($comp['id_tcid'] == 14 || $comp['id_tcid'] == 0 || $comp['id_tcid'] == 17){
					$c = $comp['content'];
					$c = trim(preg_replace('/\s\s+/', ' ', $c));
					$c = str_replace("</p> <p", '</p><br><p', $c); 
					$c = str_replace("<p>", '<div>', $c);
					$c = str_replace("<p style", '<div style', $c);
					$c = str_replace("</p>", '</div>', $c);
					$aC['pages'][$pageid][$compid]['content'] = $c;
					
					if(isset($comp['contentOrg'])){
						$c = $comp['contentOrg'];
						$c = trim(preg_replace('/\s\s+/', ' ', $c));
						$c = str_replace("</p> <p", '</p><br><p', $c);
						$c = str_replace("<p>", '<div>', $c);
						$c = str_replace("<p style", '<div style', $c);
						$c = str_replace("</p>", '</div>', $c);
						$aC['pages'][$pageid][$compid]['contentOrg'] = $c;
					}
				}
			}
		}

		$query2 = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_assets' . $tab . ' SET
												components = (:components)
												
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_assets' . $tab . '.id_as_data = (:id_as_data)
											LIMIT 1
											');
		$query2->bindValue(':components', json_encode($aC), PDO::PARAM_STR);
		$query2->bindValue(':id_as_data', $row['id_as_data'], PDO::PARAM_INT);
		$query2->execute();
	}
}



#################################################################


foreach($aTabs as $tab){
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates' . $tab . '.id_temp_data,
											' . $CONFIG['db'][0]['prefix'] . '_templates' . $tab . '.components
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templates' . $tab . ' 
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates' . $tab . '.components <> ""
										');
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	//echo $num.'<br>';
	foreach($rows as $row){
		$aC = json_decode($row['components'], true);
		
		foreach($aC['pages'] as $pageid => &$page){
			foreach($page as $compid => &$comp){
				if($comp['id_tcid'] == 14 || $comp['id_tcid'] == 0 || $comp['id_tcid'] == 17){
					$c = $comp['content'];
					$c = trim(preg_replace('/\s\s+/', ' ', $c));
					$c = str_replace("</p> <p", '</p><br><p', $c);
					$c = str_replace("<p>", '<div>', $c);
					$c = str_replace("<p style", '<div style', $c);
					$c = str_replace("</p>", '</div>', $c);
					$aC['pages'][$pageid][$compid]['content'] = $c;
					
					if(isset($comp['contentOrg'])){
						$c = $comp['contentOrg'];
						$c = trim(preg_replace('/\s\s+/', ' ', $c));
						$c = str_replace("</p> <p", '</p><br><p', $c);
						$c = str_replace("<p>", '<div>', $c);
						$c = str_replace("<p style", '<div style', $c);
						$c = str_replace("</p>", '</div>', $c);
						$aC['pages'][$pageid][$compid]['contentOrg'] = $c;
					}
				}
			}
		}

		$query2 = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates' . $tab . ' SET
												components = (:components)
												
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates' . $tab . '.id_temp_data = (:id_temp_data)
											LIMIT 1
											');
		$query2->bindValue(':components', json_encode($aC), PDO::PARAM_STR);
		$query2->bindValue(':id_temp_data', $row['id_temp_data'], PDO::PARAM_INT);
		$query2->execute();
	}
}

#################################################################

foreach($aTabs as $tab){
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements' . $tab . '.id_tce_data,
											' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements' . $tab . '.content
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements' . $tab . ' 
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements' . $tab . '.id_tcid IN (0,14,17)
										');
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	//echo $num.'<br>';
	foreach($rows as $row){
		$c = $row['content'];
		$c = trim(preg_replace('/\s\s+/', ' ', $c));
		$c = str_replace("</p> <p", '</p><br><p', $c);

		$c = str_replace("<p>", '<div>', $c);
		$c = str_replace("<p style", '<div style', $c);
		$c = str_replace("</p>", '</div>', $c);
		
	
		
	
		$query2 = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements' . $tab . ' SET
												content = (:content)
												
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurationselements' . $tab . '.id_tce_data = (:id_tce_data)
											LIMIT 1
											');
		$query2->bindValue(':content', $c, PDO::PARAM_STR);
		$query2->bindValue(':id_tce_data', $row['id_tce_data'], PDO::PARAM_INT);
		$query2->execute();
	}
}


#################################################################

foreach($aTabs as $tab){
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_textmoduls' . $tab . '.id_tm_data,
											' . $CONFIG['db'][0]['prefix'] . '_textmoduls' . $tab . '.modultext
										FROM ' . $CONFIG['db'][0]['prefix'] . '_textmoduls' . $tab . ' 
										');
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	//echo $num.'<br>';
	foreach($rows as $row){
		$c = $row['modultext'];
		$c = trim(preg_replace('/\s\s+/', ' ', $c));
		$c = str_replace("</p> <p", '</p><br><p', $c);

		$c = str_replace("<p>", '<div>', $c);
		$c = str_replace("<p style", '<div style', $c);
		$c = str_replace("</p>", '</div>', $c);
		
	
		
	
		$query2 = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_textmoduls' . $tab . ' SET
												modultext = (:modultext)
												
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_textmoduls' . $tab . '.id_tm_data = (:id_tm_data)
											LIMIT 1
											');
		$query2->bindValue(':modultext', $c, PDO::PARAM_STR);
		$query2->bindValue(':id_tm_data', $row['id_tm_data'], PDO::PARAM_INT);
		$query2->execute();
	}
}

#################################################################

foreach($aTabs as $tab){
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements' . $tab . '.id_tpe_data,
											' . $CONFIG['db'][0]['prefix'] . '_templatespageselements' . $tab . '.content
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements' . $tab . ' 
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements' . $tab . '.id_tcid IN (0,14,17)
										');
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	//echo $num.'<br>';
	foreach($rows as $row){
		$c = $row['content'];
		$c = trim(preg_replace('/\s\s+/', ' ', $c));
		$c = str_replace("</p> <p", '</p><br><p', $c);

		$c = str_replace("<p>", '<div>', $c);
		$c = str_replace("<p style", '<div style', $c);
		$c = str_replace("</p>", '</div>', $c);
		
	
		$query2 = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements' . $tab . ' SET
												content = (:content)
												
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements' . $tab . '.id_tpe_data = (:id_tpe_data)
											LIMIT 1
											');
		$query2->bindValue(':content', $c, PDO::PARAM_STR);
		$query2->bindValue(':id_tpe_data', $row['id_tpe_data'], PDO::PARAM_INT);
		$query2->execute();
	}
}

#######################################################################


foreach($aTabsAs as $tab){
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements' . $tab . '.id_ape_data,
											' . $CONFIG['db'][0]['prefix'] . '_assetspageselements' . $tab . '.content
										FROM ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements' . $tab . ' 
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements' . $tab . '.id_tpeid = ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpeid
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_count = 0
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_lang = 0
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tcid IN (0,14,17)
										');
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	//echo $num.'<br>';
	foreach($rows as $row){
		$c = $row['content'];
		$c = trim(preg_replace('/\s\s+/', ' ', $c));
		$c = str_replace("</p> <p", '</p><br><p', $c);

		$c = str_replace("<p>", '<div>', $c);
		$c = str_replace("<p style", '<div style', $c);
		$c = str_replace("</p>", '</div>', $c);
		
	
		$query2 = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements' . $tab . ' SET
												content = (:content)
												
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements' . $tab . '.id_ape_data = (:id_ape_data)
											LIMIT 1
											');
		$query2->bindValue(':content', $c, PDO::PARAM_STR);
		$query2->bindValue(':id_ape_data', $row['id_ape_data'], PDO::PARAM_INT);
		$query2->execute();
	}
}

?>