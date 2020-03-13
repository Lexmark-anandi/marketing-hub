<?php
$varSQL['orgfieldname'] = 'image';
$varSQL['multiple'] = 'multiple';
$varSQL['targetpath'] = 'productimages';
$variation = 'master';
$aExistImages = array(0);


$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_productsimages_loc.id_data_parent
									FROM ' . $CONFIG['db'][0]['prefix'] . '_productsimages_loc 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_productsimages_loc.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_productsimages_loc.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_productsimages_loc.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_productsimages_loc.del = (:nultime)
									GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_productsimages_loc.id_data_parent
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	array_push($aExistImages, $row['id_data_parent']);
}


$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_products_uni.pn_text,
										' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_pid
									FROM ' . $CONFIG['db'][0]['prefix'] . '_products_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.is_printer = (:is_printer)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.pn_text <> ""
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_pid NOT IN (' . implode(',', $aExistImages) . ')
									GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_products_uni.pn_text
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->bindValue(':is_printer', 1, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

foreach($rows as $row){
	$urlXML = 'https://www.lexmark.com/common/xml/' . substr($row['pn_text'], 0,3) . '/' . $row['pn_text'] . '.xml';

	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $urlXML);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FILETIME, true);
	$data = curl_exec($ch);
	
	$urlReal = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);	
	$filetime = curl_getinfo($ch, CURLINFO_FILETIME);	
	
	if($code == '200'){
		$date = new DateTime();
		$date->setTimestamp($filetime);
		$lastmodified = $date->format('Y-m-d H:i:s');	

		$xml = simplexml_load_string(str_replace('xmlns=', 'ns=', $data));
		
		$result = $xml->xpath('/product/media/img[@key="large"]');
		$urlImg = (substr($result[0]['src'], 0, 4) != 'http') ? 'http:' . $result[0]['src'] : $result[0]['src'];
		
		$chImg = curl_init(); 
		curl_setopt($chImg, CURLOPT_URL, $urlImg);
		curl_setopt($chImg, CURLOPT_HEADER, false);
		curl_setopt($chImg, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($chImg, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($chImg, CURLOPT_FILETIME, true);
		$dataImg = curl_exec($chImg);
		
		$urlRealImg = curl_getinfo($chImg, CURLINFO_EFFECTIVE_URL);
		$codeImg = curl_getinfo($chImg, CURLINFO_HTTP_CODE);	
		$filetimeImg = curl_getinfo($chImg, CURLINFO_FILETIME);	
		
		if($codeImg != '200'){
			$result = $xml->xpath('/product/media/img[@key="standard"]');
			$urlImg = (substr($result[0]['src'], 0, 4) != 'http') ? 'http:' . $result[0]['src'] : $result[0]['src'];
			
			$chImg = curl_init(); 
			curl_setopt($chImg, CURLOPT_URL, $urlImg);
			curl_setopt($chImg, CURLOPT_HEADER, false);
			curl_setopt($chImg, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($chImg, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($chImg, CURLOPT_FILETIME, true);
			$dataImg = curl_exec($chImg);
			
			$urlRealImg = curl_getinfo($chImg, CURLINFO_EFFECTIVE_URL);
			$codeImg = curl_getinfo($chImg, CURLINFO_HTTP_CODE);	
			$filetimeImg = curl_getinfo($chImg, CURLINFO_FILETIME);	
		}

		if($codeImg == '200'){
			// save mediafolder
			include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-media-upload-folder.php');
			
			// download file 
			$num = 0;
			$filenameOrg = basename($urlImg);
			$lastCharOrg = strrpos($filenameOrg,".");
			$filenameOrgBase = substr($filenameOrg, 0, $lastCharOrg);
			$filenameOrgEnd = strtolower(substr(strrchr($filenameOrg, "."), 1));
			$filenameBase = md5($filenameOrgBase);
			$filename = $filenameBase . '.' . $filenameOrgEnd;
		
			$handle = opendir($mediaPath);
			while(file_exists($mediaPath . $filename)){
				$num++;
				$filename = $filenameBase . "-" . $num . '.' . $filenameOrgEnd;
			}
			closedir($handle);
			
			@copy($urlImg, $mediaPath . $filename);
			
			if(file_exists($mediaPath . $filename) && strtolower($filenameOrgEnd) == 'png'){
				system('convert ' . $mediaPath . $filename . ' -fuzz 1% -trim +repage ' . $mediaPath . $filename . '');
				chmod($mediaPath . $filename , 0777);
		
				// save mediafiles
				$aArgsLV = array();
				$aArgsLV['type'] = 'sysall';
				$aLocalVersionsMedia = localVariationsBuild($aArgsLV);
				
				$field = '0_0_0';
				$CONFIG['page']['id_data'] = $row['id_pid'];
				$CONFIG['page']['id_mod'] = 106;
				$CONFIG['page']['id_mod_parent'] = 102;
				$CONFIG['page']['id_page'] = 102;
	
				include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-media-upload-file.php');
				
				$id_mid = $aArgsSave['id_data'];
				
				
				// save productimage
				$aArgsSavePI = array();
				$aArgsSavePI['table'] = $CONFIG['db'][0]['prefix'] . '_productsimages_';
				$aArgsSavePI['primarykey'] = 'id_piid';
				$aArgsSavePI['allVersions'] = array();
				$aArgsSavePI['changedVersions'] = array();
				
				$aArgsSavePI['columns'] = array();
				$aArgsSavePI['columns']['id_piid'] = 'i';
				$aArgsSavePI['columns']['id_data_parent'] = 'i';
				$aArgsSavePI['columns']['id_mod_parent'] = 'i';
				$aArgsSavePI['columns']['id_page'] = 'i';
				$aArgsSavePI['columns']['image'] = 'i';
				$aArgsSavePI['columns']['imagetitle'] = 's';
				$aArgsSavePI['columns']['rank'] = 'i';
				$aArgsSavePI['columns']['active'] = 'i';
				
				$aArgsSavePI['aFieldsNumbers'] = array();
				array_push($aArgsSavePI['aFieldsNumbers'], 'id_piid');
				array_push($aArgsSavePI['aFieldsNumbers'], 'id_data_parent');
				array_push($aArgsSavePI['aFieldsNumbers'], 'id_mod_parent');
				array_push($aArgsSavePI['aFieldsNumbers'], 'id_page');
				array_push($aArgsSavePI['aFieldsNumbers'], 'image');
				array_push($aArgsSavePI['aFieldsNumbers'], 'rank');
				array_push($aArgsSavePI['aFieldsNumbers'], 'active');
	
				$aArgsSavePI['excludeUpdateUni'] = array();
				$aArgsSavePI['excludeUpdateUni']['id_piid'] = array('', 0);
				$aArgsSavePI['excludeUpdateUni']['id_data_parent'] = array('', 0);
				$aArgsSavePI['excludeUpdateUni']['id_mod_parent'] = array('', 0);
				$aArgsSavePI['excludeUpdateUni']['id_page'] = array('', 0);
				$aArgsSavePI['excludeUpdateUni']['image'] = array('', 0);
				$aArgsSavePI['excludeUpdateUni']['imagetitle'] = array('');
				$aArgsSavePI['excludeUpdateUni']['rank'] = array('', 0);
				$aArgsSavePI['excludeUpdateUni']['active'] = array('', 0);
	
				$aFieldsSaveMaster = array();
				array_push($aFieldsSaveMaster, 'id_piid');
				array_push($aFieldsSaveMaster, 'id_data_parent');
				array_push($aFieldsSaveMaster, 'id_mod_parent');
				array_push($aFieldsSaveMaster, 'id_page');
				array_push($aFieldsSaveMaster, 'image');
				array_push($aFieldsSaveMaster, 'imagetitle');
				array_push($aFieldsSaveMaster, 'rank');
				array_push($aFieldsSaveMaster, 'active');
				$aFieldsSaveNotMaster = array();
				array_push($aFieldsSaveNotMaster, 'id_piid');
				array_push($aFieldsSaveNotMaster, 'id_data_parent');
				array_push($aFieldsSaveNotMaster, 'id_mod_parent');
				array_push($aFieldsSaveNotMaster, 'id_page');
				array_push($aFieldsSaveNotMaster, 'image');
				array_push($aFieldsSaveNotMaster, 'imagetitle');
				array_push($aFieldsSaveNotMaster, 'rank');
				array_push($aFieldsSaveNotMaster, 'active');
				
				$aArgsSavePI['aData'] = array();
				$aArgsSavePI['aData']['id_data_parent'] = $row['id_pid'];
				$aArgsSavePI['aData']['id_mod_parent'] = 102;
				$aArgsSavePI['aData']['id_page'] = 102;
				$aArgsSavePI['aData']['image'] = $id_mid;
				$aArgsSavePI['aData']['imagetitle'] = '';
				$aArgsSavePI['aData']['rank'] = 10;
				$aArgsSavePI['aData']['active'] = 1;
				$aArgsSavePI['aData']['id_cl'] = $CONFIG['activeSettings']['id_clid'];
	
				$queryI = $CONFIG['dbconn'][0]->prepare('
													INSERT INTO ' . $aArgsSavePI['table'] . '
													(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
													VALUES
													(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
													');
				$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
				$queryI->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
				$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
				$queryI->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
				$queryI->execute();
				$aArgsSavePI['id_data'] = $CONFIG['dbconn'][0]->lastInsertId();
	
	
				$col = '';
				$val = '';
				$upd = '';
				foreach($aArgsSavePI['columns'] as $field => $format){
					if(($variation == 'master' && in_array($field, $aFieldsSaveMaster)) || ($variation == 'local' && in_array($field, $aFieldsSaveNotMaster))){
						if($field != $aArgsSavePI['primarykey']){
							$col .= ', ' . $field;
							$val .= ', :' . $field . '';
							$upd .= $field.' = (:'.$field.'), ' ;
						}
					}
				}
	
				$qry = 'INSERT INTO ' . $aArgsSavePI['table'] . 'loc
							(' . $aArgsSavePI['primarykey'] . ', id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from' . $col . ')
						VALUES
							(:id, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from' . $val . ')
						ON DUPLICATE KEY UPDATE 
							' . $upd . '
							change_from = (:create_from),
							del = (:nultime)
						';
				$queryC = $CONFIG['dbconn'][0]->prepare($qry);
				$queryC->bindValue(':id', $aArgsSavePI['id_data'], PDO::PARAM_INT);
				$queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
				$queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
				$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
				$queryC->bindValue(':id_cl', $aArgsSavePI['aData']['id_cl'], PDO::PARAM_INT);
				$queryC->bindValue(':now', $now, PDO::PARAM_STR);
				$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$queryC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
				
				foreach($aArgsSavePI['columns'] as $field => $format){
					if(($variation == 'master' && in_array($field, $aFieldsSaveMaster)) || ($variation == 'local' && in_array($field, $aFieldsSaveNotMaster))){
						if($field != $aArgsSavePI['primarykey']){
							if($format == 'i' || $format == 'si' || $format == 'b'){
								$queryC->bindValue(':'.$field, (is_array($aArgsSavePI['aData'][$field])) ? json_encode($aArgsSavePI['aData'][$field]) : trim($aArgsSavePI['aData'][$field]), PDO::PARAM_INT);
							}else{ 
								$queryC->bindValue(':'.$field, (is_array($aArgsSavePI['aData'][$field])) ? json_encode($aArgsSavePI['aData'][$field]) : trim($aArgsSavePI['aData'][$field]), PDO::PARAM_STR);
							}
						}
					}
				}
				$queryC->execute();
				$numC = $queryC->rowCount();
	
				
				
				$aArgsSavePI['changedVersions'] = array(array(0,0,0));
				$aArgsSavePI['allVersions'] = $aLocalVersionsMedia;
				insertAll($aArgsSavePI);
				
				
			}
		}
	}
}












?>