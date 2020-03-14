<?php
function importXML($aArgs=array()){
	global $CONFIG, $TEXT, $nTv;

	if(in_array($aArgs['file'], $CONFIG['import']['aDoneFiles'])) exit();
	array_push($CONFIG['import']['aDoneFiles'], $aArgs['file']);
	
	$bsd = 2;
	$fieldbsd = "";
	if($aArgs['type'] == 'bsd'){
		$bsd = 1;
		$fieldbsd = '_bsd';
	} 
	
	// unpack zip (in own directorty)
	$subfolder = microtime();
	mkdir($CONFIG['import']['pathData'] . $subfolder, 0777);
	chmod($CONFIG['import']['pathData'] . $subfolder, 0777);
	$archive = new PclZip($CONFIG['import']['pathFeedsfiles'] . $aArgs['file']);
	if($archive->extract(PCLZIP_OPT_PATH, $CONFIG['import']['pathData'] . $subfolder . '/') == 0) {
		//echo($aArgs['file'] . 'Error : ' . $archive->errorInfo(true)) . '<br>';
	}
	
	
	// Read XML
	$lastUpdate = '';
	$directory = opendir($CONFIG['import']['pathData'] . $subfolder . '/');
	while($file = readdir($directory)){ 
		if($file != '.' && $file != '..' && !is_dir($CONFIG['import']['pathData'] . $subfolder . '/' . $file) && (substr_count($file, '.xml') != 0)){
			if(file_exists($CONFIG['import']['pathData'] . $subfolder . '/' . $file)) {
				$xml = simplexml_load_file($CONFIG['import']['pathData'] . $subfolder . '/' . $file);
				
				
				// define country
				$country = $xml[0][@country];
				//echo $country;
				$id_count = 0;
				$query = $CONFIG['dbconn']->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
													FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni 
													WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code_add = (:code)
													');
				$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$query->bindValue(':nul', 0, PDO::PARAM_INT);
				$query->bindValue(':code', $country, PDO::PARAM_STR);
				$query->execute();
				$rowsC = $query->fetchAll(PDO::FETCH_ASSOC);
				$num_count = $query->rowCount();
				if($num_count > 0){
					foreach($rowsC as $rowC){
						$id_count = $rowC['id_countid'];
	
						// define language
						$language = $xml[0][@language];
						$id_lang = 0;
						$query = $CONFIG['dbconn']->prepare('
															SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid
															FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni 
															WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
																AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
																AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
																AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
																AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code_add = (:code)
															');
						$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
						$query->bindValue(':nul', 0, PDO::PARAM_INT);
						$query->bindValue(':code', $language, PDO::PARAM_STR);
						$query->execute();
						$rowsL = $query->fetchAll(PDO::FETCH_ASSOC);
						$num_lang = $query->rowCount();
						if($num_lang > 0){
							foreach($rowsL as $rowL){
								$id_lang = $rowL['id_langid'];
			
								// define last update
								if($lastUpdate == ''){
									$queryUp = $CONFIG['dbconn']->prepare('
																		SELECT ' . $CONFIG['db'][0]['prefix'] . '_import_updates.last_update' . $fieldbsd . ' AS last_update
																		FROM ' . $CONFIG['db'][0]['prefix'] . '_import_updates
																		WHERE ' . $CONFIG['db'][0]['prefix'] . '_import_updates.id_countid = (:id_countid)
																			AND ' . $CONFIG['db'][0]['prefix'] . '_import_updates.id_langid = (:id_langid)
																		');
									$queryUp->bindValue(':id_countid', $id_count, PDO::PARAM_INT);
									$queryUp->bindValue(':id_langid', $id_lang, PDO::PARAM_INT);
									$queryUp->execute();
									$rowsUp = $queryUp->fetchAll(PDO::FETCH_ASSOC);
									$numUp = $queryUp->rowCount();
									
									if($numUp > 0) $lastUpdate = $rowsUp[0]['last_update'];
								}

								$qry = 'SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_count2lang
										 FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages
										 WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid = (:count) 
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid = (:lang) 
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.del = (:nultime)
										';
								$queryCL = $CONFIG['dbconn']->prepare($qry);
								$queryCL->bindValue(':count', $id_count, PDO::PARAM_INT);
								$queryCL->bindValue(':lang', $id_lang, PDO::PARAM_INT);
								$queryCL->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
								$queryCL->execute();
								$rowsCL = $queryCL->fetchAll(PDO::FETCH_ASSOC);
								$numCL = $queryCL->rowCount();
					
					
								if($lastUpdate < $aArgs['time']){
									if($id_count != 0 && $id_lang != 0 && $numCL != 0){
										// read product
										foreach ($xml->product as $product) {
											$date = new DateTime();
											$now = $date->format('Y-m-d H:i:s');
					
					
											#############################
											// Product type
											include($CONFIG['system']['pathInclude'] . 'admin/functions/fu-products-import-feeds-prodtype.php');
											#############################
					
					
											#############################
											// Technologie family
											include($CONFIG['system']['pathInclude'] . 'admin/functions/fu-products-import-feeds-technofamilies.php');
											#############################
					
					
											#############################
											// Product
											include($CONFIG['system']['pathInclude'] . 'admin/functions/fu-products-import-feeds-products.php');
											#############################
					
					
											#############################
											// Features
											include($CONFIG['system']['pathInclude'] . 'admin/functions/fu-products-import-feeds-features.php');
											#############################
					
					
											#############################
											// Descriptions
											include($CONFIG['system']['pathInclude'] . 'admin/functions/fu-products-import-feeds-descriptions.php');
											#############################
					
					
											#############################
											// In the box
											include($CONFIG['system']['pathInclude'] . 'admin/functions/fu-products-import-feeds-inthebox.php');
											#############################
					
					
											#############################
											// Images
											include($CONFIG['system']['pathInclude'] . 'admin/functions/fu-products-import-feeds-images.php');
											#############################
					
					
											#############################
											// Techspecs
//											if($aProduct['is_printer'] == 1 || in_array($aProduct['product_type_id'], $CONFIG['system']['prodtype_techspecs_options'])){
												include($CONFIG['system']['pathInclude'] . 'admin/functions/fu-products-import-feeds-techspecs.php');
//											}
											#############################
					
					
											#############################
											// Relations
											include($CONFIG['system']['pathInclude'] . 'admin/functions/fu-products-import-feeds-relations.php');
											#############################
				
				
											
				
				
				
										}
				
					
										
										############################################################
										##### Cleaning ############################################
										$queryU = $CONFIG['dbconn']->prepare('
																			SELECT ' . $CONFIG['db'][0]['prefix'] . '_import_updates.id_iuid
																			FROM ' . $CONFIG['db'][0]['prefix'] . '_import_updates
																			WHERE ' . $CONFIG['db'][0]['prefix'] . '_import_updates.id_countid = (:id_countid)
																				AND ' . $CONFIG['db'][0]['prefix'] . '_import_updates.id_langid = (:id_langid)
																			');
										$queryU->bindValue(':id_countid', $id_count, PDO::PARAM_INT);
										$queryU->bindValue(':id_langid', $id_lang, PDO::PARAM_INT);
										$queryU->execute();
										$rowsU = $queryU->fetchAll(PDO::FETCH_ASSOC);
										$numU = $queryU->rowCount();
					
										if($numU == 0){
											$queryU = $CONFIG['dbconn']->prepare('
																				INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_import_updates
																					(id_countid, id_langid, last_update' . $fieldbsd . ')
																				VALUES
																					(:id_countid, :id_langid, :last_update)
																				');
											$queryU->bindValue(':last_update', $aArgs['time'], PDO::PARAM_STR);
											$queryU->bindValue(':id_countid', $id_count, PDO::PARAM_INT);
											$queryU->bindValue(':id_langid', $id_lang, PDO::PARAM_INT);
											$queryU->execute();
											
											$lastUpdate = '0000-00-00 00:00:00';
										}else{
											$queryU = $CONFIG['dbconn']->prepare('
																				UPDATE ' . $CONFIG['db'][0]['prefix'] . '_import_updates SET
																					last_update' . $fieldbsd . ' = (:last_update)
																				WHERE ' . $CONFIG['db'][0]['prefix'] . '_import_updates.id_iuid = (:id_iuid)
																				LIMIT 1
																				');
											$queryU->bindValue(':last_update', $aArgs['time'], PDO::PARAM_STR);
											$queryU->bindValue(':id_iuid', $rowsU[0]['id_iuid'], PDO::PARAM_INT);
											$queryU->execute();
										}
					
										$aArgs['message'] = '<span class="sucMsg">' . $TEXT['UploadReady'] . $file . '</span>';
									}
								}else{
									$aArgs['message'] = $TEXT['uploadNotPosibble']; 
								}
							}
						}
					}
				}
				
				unlink($CONFIG['import']['pathData'] . $subfolder . '/' . $file);
			}
		}else{
			############################################################
			##### Aufräumen ############################################
			if($file != '.' && $file != '..' && !is_dir($CONFIG['import']['pathData'] . $subfolder . '/' . $file) && (substr_count($file, '.dtd') != 0)){
				unlink($CONFIG['import']['pathData'] . $subfolder . '/' . $file);
			}
		}
	}
	############################################################
	##### Subordner löschen #####################################
    rmdir($CONFIG['import']['pathData'] . $subfolder);

}











########################################################################################################################################################


function chmodnum($mode) {
       $realmode = "";
       $legal =  array("","w","r","x","-");
       $attarray = preg_split("//",$mode);
       for($i=0;$i<count($attarray);$i++){
           if($key = array_search($attarray[$i],$legal)){
               $realmode .= $legal[$key];
           }
       }
       $mode = str_pad($realmode,9,'-');
       $trans = array('-'=>'0','r'=>'4','w'=>'2','x'=>'1');
       $mode = strtr($mode,$trans);
       $newmode = '';
       $newmode .= $mode[0]+$mode[1]+$mode[2];
       $newmode .= $mode[3]+$mode[4]+$mode[5];
       $newmode .= $mode[6]+$mode[7]+$mode[8];
       return $newmode;
    }
	
function get_size($size)
     {
         if ($size < 1024)
          {
              return round($size,2).' Byte';
          }
         elseif ($size < (1024*1024))
          {
              return round(($size/1024),2).' MB';
          }
         elseif ($size < (1024*1024*1024))
          {
              return round((($size/1024)/1024),2).' GB';
          }
         elseif ($size < (1024*1024*1024*1024))
          {
              return round(((($size/1024)/1024)/1024),2).' TB';
          }
}



?>