<?php
use \Howtomakeaturn\PDFInfo\PDFInfo;

$varSQL['orgfieldname'] = 'kiadofile';
$varSQL['multiple'] = '';
$varSQL['targetpath'] = 'specsheets';
$variation = 'master';

$aCodes = array();

$query = $CONFIG['dbconn'][0]->prepare('
									SELECT DISTINCT(' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.code)
									FROM ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.del = (:nultime)
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	array_push($aCodes, $row['code']);
}




###################################
// check if master files are existing
###################################
foreach($aCodes as $kiadocode){
	##############################################################
	// Search Master PDF
	##############################################################
	$urlKiado = 'https://kdr.lexmark.com/media/' . $kiadocode . '?lang=XYXYXY_XYXYXY&format=high';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $urlKiado);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FILETIME, true);
	curl_exec($ch);
	
	$urlReal = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);	
	$filetime = curl_getinfo($ch, CURLINFO_FILETIME);	
	$master = (substr_count($urlReal, '/master/') > 0) ? 1 : 2;
	
	if($code != '200'){
		$query1 = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_kcid,
												' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.status
											FROM ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc 
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.code = (:code)
											');
		$query1->bindValue(':nul', 0, PDO::PARAM_INT);
		$query1->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$query1->bindValue(':code', $kiadocode, PDO::PARAM_STR);
		$query1->execute();
		$rows1 = $query1->fetchAll(PDO::FETCH_ASSOC);
		$num1 = $query1->rowCount();
		
		if($num1 > 0){
			$status = 0;
			if($rows1[0]['status'] < 3){
				$status = $rows1[0]['status'] + 1;
				
				$query2 = $CONFIG['dbconn'][0]->prepare('
													UPDATE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc SET 
														status = (:status)
													
													WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_kcid = (:id_kcid)
													');
				$query2->bindValue(':status', $status, PDO::PARAM_INT);
				$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$query2->bindValue(':id_kcid', $rows1[0]['id_kcid'], PDO::PARAM_INT);
				$query2->execute();
				
				$query2 = $CONFIG['dbconn'][0]->prepare('
													UPDATE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni SET 
														status = (:status)
													
													WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_kcid = (:id_kcid)
													');
				$query2->bindValue(':status', $status, PDO::PARAM_INT);
				$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$query2->bindValue(':id_kcid', $rows1[0]['id_kcid'], PDO::PARAM_INT);
				$query2->execute();
			}
			
			if($status == 3){
				$query1t = $CONFIG['dbconn'][0]->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_tempid
													FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc 
													
													WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_count = (:nul)
														AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_lang = (:nul)
														AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_dev = (:nul)
														AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_kcid = (:id_kcid)
													');
				$query1t->bindValue(':nul', 0, PDO::PARAM_INT);
				$query1t->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$query1t->bindValue(':id_kcid', $rows1[0]['id_kcid'], PDO::PARAM_STR);
				$query1t->execute();
				$rows1t = $query1->fetchAll(PDO::FETCH_ASSOC);
				$num1t = $query1t->rowCount();
				
				$aT = array(0);
				foreach($rows1t as $row1t){
					array_push($aT, $row1t['id_tempid']);
				}
			
				
				$query2 = $CONFIG['dbconn'][0]->prepare('
													UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_loc SET 
														del = (:del)
													
													WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_loc.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . '_templates_loc.id_kcid IN (' . implode(',', $aT) . ')
													');
				$query2->bindValue(':del', '1970-11-11 11:11:11', PDO::PARAM_STR);
				$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$query2->bindValue(':id_kcid', $rows1[0]['id_kcid'], PDO::PARAM_INT);
				$query2->execute();
				
				$query2 = $CONFIG['dbconn'][0]->prepare('
													UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni SET 
														del = (:del)
													
													WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_kcid IN (' . implode(',', $aT) . ')
													');
				$query2->bindValue(':del', '1970-11-11 11:11:11', PDO::PARAM_STR);
				$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$query2->bindValue(':id_kcid', $rows1[0]['id_kcid'], PDO::PARAM_INT);
				$query2->execute();
			}
		}
	}else{
		$query1 = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_kcid,
												' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.status
											FROM ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc 
											
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_count = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_lang = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_dev = (:nul)
												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.code = (:code)
											');
		$query1->bindValue(':nul', 0, PDO::PARAM_INT);
		$query1->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$query1->bindValue(':code', $kiadocode, PDO::PARAM_STR);
		$query1->execute();
		$rows1 = $query1->fetchAll(PDO::FETCH_ASSOC);
		$num1 = $query1->rowCount();
		
		if($num1 > 0){
			$query2 = $CONFIG['dbconn'][0]->prepare('
												UPDATE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc SET 
													status = (:status)
												
												WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_kcid = (:id_kcid)
												');
			$query2->bindValue(':status', 0, PDO::PARAM_INT);
			$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query2->bindValue(':id_kcid', $rows1[0]['id_kcid'], PDO::PARAM_INT);
			$query2->execute();
	
			$query2 = $CONFIG['dbconn'][0]->prepare('
												UPDATE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni SET 
													status = (:status)
												
												WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_kcid = (:id_kcid)
												');
			$query2->bindValue(':status', 0, PDO::PARAM_INT);
			$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query2->bindValue(':id_kcid', $rows1[0]['id_kcid'], PDO::PARAM_INT);
			$query2->execute();
		}
	}
}


##############################################################
// check local version
##############################################################
$query1n = $CONFIG['dbconn'][0]->prepare('
                        SELECT COUNT(' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_kcid) AS num
                        FROM ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc 
                        WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.del = (:nultime)
                                AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.master = (:master)
                                AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.status < 3
                        ');
$query1n->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query1n->bindValue(':master', 2, PDO::PARAM_INT);
$query1n->execute();
$rowsln = $query1n->fetchAll(PDO::FETCH_ASSOC);
$num1n = $query1n->rowCount();
 
$query1n = null;
$CONFIG['dbconn'][0] = null;
for($i = 0; $i < $rowsln[0]['num']; $i += 500){
    getConnection(0);
    
    $query1 = $CONFIG['dbconn'][0]->prepare('
                            SELECT ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_kcid,
                                    ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_mid,
                                    ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_count,
                                    ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_lang,
                                    ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.code,
                                    ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.link
                            FROM ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc 

                            WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.del = (:nultime)
                                    AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.master = (:master)
                                    AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.status < 3
                            LIMIT ' . $i . ', 500
                            ');
    $query1->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
    $query1->bindValue(':master', 2, PDO::PARAM_INT);
    $query1->execute();
    $rows1 = $query1->fetchAll(PDO::FETCH_ASSOC);
    $num1 = $query1->rowCount();

    foreach($rows1 as $row1){
            $urlKiado = $row1['link'];
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $urlKiado);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FILETIME, true);
            curl_exec($ch);

            $urlReal = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);	
            $filetime = curl_getinfo($ch, CURLINFO_FILETIME);	
            $master = (substr_count($urlReal, '/master/') > 0) ? 1 : 2;

            $date = new DateTime();
            $date->setTimestamp($filetime);
            $lastmodified = $date->format('Y-m-d H:i:s');	

            if($code != '200'){
                    $queryM = $CONFIG['dbconn'][0]->prepare('
                                            SELECT ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.link
                                            FROM ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc 

                                            WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_count = (:nul)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_lang = (:nul)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_dev = (:nul)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.del = (:nultime)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_kcid = (:id_kcid)
                                            ');
                    $queryM->bindValue(':nul', 0, PDO::PARAM_INT);
                    $queryM->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
                    $queryM->bindValue(':id_kcid', $row1['id_kcid'], PDO::PARAM_INT);
                    $queryM->execute();
                    $rowsM = $queryM->fetchAll(PDO::FETCH_ASSOC);
                    $numM = $queryM->rowCount();


                    $query2 = $CONFIG['dbconn'][0]->prepare('
                                            UPDATE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc SET 
                                                    link = (:link),
                                                    master = 1

                                            WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_count = (:id_count)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_lang = (:id_lang)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_dev = (:nul)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.del = (:nultime)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_kcid = (:id_kcid)
                                            ');
                    $query2->bindValue(':link', $rowsM[0]['link'], PDO::PARAM_STR);
                    $query2->bindValue(':id_count', $row1['id_count'], PDO::PARAM_INT);
                    $query2->bindValue(':id_lang', $row1['id_lang'], PDO::PARAM_INT);
                    $query2->bindValue(':nul', 0, PDO::PARAM_INT);
                    $query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
                    $query2->bindValue(':id_kcid', $row1['id_kcid'], PDO::PARAM_INT);
                    $query2->execute();

                    $query2 = $CONFIG['dbconn'][0]->prepare('
                                            UPDATE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni SET 
                                                    link = (:link),
                                                    master = 1

                                            WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_count = (:id_count)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_lang = (:id_lang)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_dev = (:nul)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.del = (:nultime)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni.id_kcid = (:id_kcid)
                                            ');
                    $query2->bindValue(':link', $rowsM[0]['link'], PDO::PARAM_STR);
                    $query2->bindValue(':id_count', $row1['id_count'], PDO::PARAM_INT);
                    $query2->bindValue(':id_lang', $row1['id_lang'], PDO::PARAM_INT);
                    $query2->bindValue(':nul', 0, PDO::PARAM_INT);
                    $query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
                    $query2->bindValue(':id_kcid', $row1['id_kcid'], PDO::PARAM_INT);
                    $query2->execute();



                    $queryM = $CONFIG['dbconn'][0]->prepare('
                                            SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.filesys_filename
                                                    ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.filehash
                                            FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc 

                                            WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_count = (:nul)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_lang = (:nul)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_dev = (:nul)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_cl IN (0,1)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mid = (:id_mid)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.del = (:nultime)
                                            ');
                    $queryM->bindValue(':nul', 0, PDO::PARAM_INT);
                    $queryM->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
                    $queryM->bindValue(':id_mid', $row1['id_mid'], PDO::PARAM_INT);
                    $queryM->execute();
                    $rowsM = $queryM->fetchAll(PDO::FETCH_ASSOC);
                    $numM = $queryM->rowCount();
                    
                    if($numM == 0){
                        $rowsM = [];
                        $rowsM[]['filesys_filename'] = '';
                        $rowsM[]['filehash'] = '';
                    }

                    $query2 = $CONFIG['dbconn'][0]->prepare('
                                            UPDATE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc SET 
                                                    filesys_filename = (:filesys_filename),
                                                    filehash = (:filehash)

                                            WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_count = (:id_count)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_lang = (:id_lang)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_dev = (:nul)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_cl IN (0,1)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mid = (:id_mid)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.del = (:nultime)
                                            ');
                    $query2->bindValue(':filesys_filename', $rowsM[0]['filesys_filename'], PDO::PARAM_STR);
                    $query2->bindValue(':filehash', $rowsM[0]['filehash'], PDO::PARAM_STR);
                    $query2->bindValue(':id_count', $row1['id_count'], PDO::PARAM_INT);
                    $query2->bindValue(':id_lang', $row1['id_lang'], PDO::PARAM_INT);
                    $query2->bindValue(':nul', 0, PDO::PARAM_INT);
                    $query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
                    $query2->bindValue(':id_mid', $row1['id_mid'], PDO::PARAM_INT);
                    $query2->execute();

                    $query2 = $CONFIG['dbconn'][0]->prepare('
                                            UPDATE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni SET 
                                                    filesys_filename = (:filesys_filename),
                                                    filehash = (:filehash)

                                            WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_count = (:id_count)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_lang = (:id_lang)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_dev = (:nul)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_cl IN (0,1)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.id_mid = (:id_mid)
                                                    AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_loc.del = (:nultime)
                                            ');
                    $query2->bindValue(':filesys_filename', $rowsM[0]['filesys_filename'], PDO::PARAM_STR);
                    $query2->bindValue(':filehash', $rowsM[0]['filehash'], PDO::PARAM_STR);
                    $query2->bindValue(':id_count', $row1['id_count'], PDO::PARAM_INT);
                    $query2->bindValue(':id_lang', $row1['id_lang'], PDO::PARAM_INT);
                    $query2->bindValue(':nul', 0, PDO::PARAM_INT);
                    $query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
                    $query2->bindValue(':id_mid', $row1['id_mid'], PDO::PARAM_INT);
                    $query2->execute();
            }
    }
    
    $query2 = null;
    $CONFIG['dbconn'][0] = null;
}
##############################################################
##############################################################


getConnection(0);
    
##############################################################
##############################################################
//$query = $CONFIG['dbconn'][0]->prepare('
//									SELECT ' . $CONFIG['db'][0]['prefix'] . '_products_uni.pn_text,
//										' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_pid
//									FROM ' . $CONFIG['db'][0]['prefix'] . '_products_uni 
//									
//									WHERE ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_count = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_lang = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_dev = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.del = (:nultime)
//										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.pn_text <> ""
//									GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_products_uni.pn_text
//									');
//$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//$query->bindValue(':nul', 0, PDO::PARAM_INT);
//$query->execute();
//$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//$num = $query->rowCount();
//
//foreach($rows as $row){
//	$urlXML = 'https://www.lexmark.com/common/xml/' . substr($row['pn_text'], 0,3) . '/' . $row['pn_text'] . '.xml';
//
//	$ch = curl_init(); 
//	curl_setopt($ch, CURLOPT_URL, $urlXML);
//	curl_setopt($ch, CURLOPT_HEADER, false);
//	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//	curl_setopt($ch, CURLOPT_FILETIME, true);
//	$data = curl_exec($ch);
//	
//	$urlReal = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
//	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);	
//	$filetime = curl_getinfo($ch, CURLINFO_FILETIME);	
//	
//	if($code == '200'){
//		$date = new DateTime();
//		$date->setTimestamp($filetime);
//		$lastmodified = $date->format('Y-m-d H:i:s');	
//
//		$xml = simplexml_load_string(str_replace('xmlns=', 'ns=', $data));
//		
//		$result = $xml->xpath('/product/media/doc[@key="brochure"]');
//		
//		foreach($result as $res){
//			$src = $res['src'];
//			if(substr($src, 0, 11) == 'https://kdr.'){
//				$aSrc = explode('?', $src);
//				$aCode = explode('/', $aSrc[0]);
//				$code = array_pop($aCode);
//				if(!in_array($code, $aCodes)) array_push($aCodes, $code);
//			}
//		}
//	}
//}


#############################################################
// update kiado codes
#############################################################
$aCount = [];
$queryCL = $CONFIG['dbconn'][0]->prepare('
                        SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code_spec AS code_count,
                                ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid,
                                ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code_spec AS code_lang,
                                ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid
                        FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni 

                        INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
                                ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid

                        INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
                                ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid

                        WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
                                AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
                                AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
                                AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
                                AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')

                                AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
                                AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
                                AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
                                AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
                                AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')');
$queryCL->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryCL->bindValue(':nul', 0, PDO::PARAM_INT);
$queryCL->execute();
$rowsCL = $queryCL->fetchAll(PDO::FETCH_ASSOC);
$numCL = $queryCL->rowCount();

foreach($rowsCL as $rowCL){
    $aCount[] = $rowCL;
}

$queryCL = null;
$CONFIG['dbconn'][0] = null;
foreach($aCodes as $kiadocode){
        getConnection(0);
        $date = new DateTime();
	$now = $date->format('Y-m-d H:i:s');

	$query1 = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_kcid,
											' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.lastmodified
										FROM ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc 
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.code = (:code)
										');
	$query1->bindValue(':nul', 0, PDO::PARAM_INT);
	$query1->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query1->bindValue(':code', $kiadocode, PDO::PARAM_STR);
	$query1->execute();
	$rows1 = $query1->fetchAll(PDO::FETCH_ASSOC);
	$num1 = $query1->rowCount();
	$id_kcid = ($num1 > 0) ? $rows1[0]['id_kcid'] : 0;
	
	//if($num1 > 1) mail('hebbel.t@online.de', 'MH doppelter Kiadocode', $kiadocode);
        
	##############################################################
	// Process Master PDF
	##############################################################
	$urlKiado = 'https://kdr.lexmark.com/media/' . $kiadocode . '?lang=XYXYXY_XYXYXY&format=high';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $urlKiado);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FILETIME, true);
	curl_exec($ch);
	
	$urlReal = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);	
	$filetime = curl_getinfo($ch, CURLINFO_FILETIME);	
	$master = (substr_count($urlReal, '/master/') > 0) ? 1 : 2;

	$date = new DateTime();
	$date->setTimestamp($filetime);
	$lastmodified = $date->format('Y-m-d H:i:s');	
	
	if($code == '200' && $master == 1){
		if($num1 == 0 || $lastmodified > $rows1[0]['lastmodified']){
			$aArgsSaveK = array();
			
			if($num1 == 0){
				// create ID for kiado code
				$queryK = $CONFIG['dbconn'][0]->prepare('
													INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_
													(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
													VALUES
													(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
													');
				$queryK->bindValue(':nul', 0, PDO::PARAM_INT);
				$queryK->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
				$queryK->bindValue(':create_at', $now, PDO::PARAM_STR);
				$queryK->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
				$queryK->execute();
				$id_kcid = $CONFIG['dbconn'][0]->lastInsertId();
			}


			// save mediafolder
			include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-media-upload-folder.php');
			
			// download file from kiado
			$num = 0;
			//$filenameOrg = basename($urlReal);
			$filenameOrg = $urlReal;
			$lastCharOrg = strrpos($filenameOrg,".");
			$filenameOrgBase = substr($filenameOrg, 0, $lastCharOrg);
			$filenameOrgEnd = strtolower(substr(strrchr($filenameOrg, "."), 1));
			$filenameBase = md5($filenameOrgBase);
			$filename = $filenameBase . '.' . $filenameOrgEnd;
		
//			$handle = opendir($mediaPath);
//			while(file_exists($mediaPath . $filename)){
//				$num++;
//				$filename = $filenameBase . "-" . $num . '.' . $filenameOrgEnd;
//			}
//			closedir($handle);
			
			copy($urlReal, $mediaPath . $filename);
			chmod($mediaPath . $filename , 0777);
		
		
			// split and create images
			$fileOriginal = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'] . $filename;
			$dirTarget = $CONFIG['system']['directoryRoot'] . 'assetimages/';
			if(file_exists($fileOriginal)){
				$aFilenameOriginal = explode('.', $filename);
				array_pop($aFilenameOriginal);
				$filenameOriginal = implode('.', $aFilenameOriginal);
		
				system('pdftoppm -png -r 96 -cropbox -aa yes ' . $fileOriginal . ' ' . $dirTarget . 'pictures/' . $filenameOriginal);
				system('pdftoppm -png -r 96 -cropbox -aa yes -scale-to 140 ' . $fileOriginal . ' ' . $dirTarget . 'thumbnails/' . $filenameOriginal);
				
				// get document dimensions in pt (1pt = 1/72 * 25,4mm)
				$pdf = new PDFInfo($fileOriginal);
				$pageNum = $pdf->pages;
				$aMediaBox = $pdf->mediaBox;
				$aCropBox = $pdf->cropBox;
				$aBleedBox = $pdf->bleedBox;
				$aTrimBox = $pdf->trimBox;
				$aArtBox = $pdf->artBox;
				$aDimension = json_encode(array('mediabox' => $aMediaBox, 'cropbox' => $aCropBox, 'bleedbox' => $aBleedBox, 'trimbox' => $aTrimBox, 'artbox' => $aArtBox));

				if($pageNum > 9){
					for($p=1; $p<10; $p++){
						$fileSearch = $dirTarget . 'pictures/' . $filenameOriginal . '-0' . $p . '.png';
						if(file_exists($fileSearch)){
							rename($fileSearch, str_replace('-0' . $p . '.png', '-' . $p . '.png', $fileSearch));
						}
						$fileSearch = $dirTarget . 'thumbnails/' . $filenameOriginal . '-0' . $p . '.png';
						if(file_exists($fileSearch)){
							rename($fileSearch, str_replace('-0' . $p . '.png', '-' . $p . '.png', $fileSearch));
						}
					}
				}
			}
			
			
			// save mediafiles
			$aArgsLV = array();
			$aArgsLV['type'] = 'sysall';
			$aLocalVersionsMedia = localVariationsBuild($aArgsLV);
			
			$field = '0_0_0';
			$CONFIG['page']['id_data'] = $id_kcid;
			$CONFIG['page']['id_mod'] = 119;
			$CONFIG['page']['id_mod_parent'] = 0;
			$CONFIG['page']['id_page'] = 119;
	
			$mediafileIdData = $id_kcid;
			$mediafileIdMod = 119;
			$mediafileIdModParent = 0;
			$mediafileIdPage = 119;
			$mediafileFieldname = 'kiadofile';
			include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-media-upload-file.php');
			
			$id_mid = $aArgsSave['id_data'];


			// save kiadocodes
			$aArgsSaveK['id_data'] = $id_kcid;
			$aArgsSaveK['table'] = $CONFIG['db'][0]['prefix'] . '_kiadocodes_';
			$aArgsSaveK['primarykey'] = 'id_kcid';
			$aArgsSaveK['orgfieldname'] = $varSQL['orgfieldname'];
			$aArgsSaveK['multiple'] = $varSQL['multiple'];
			$aArgsSaveK['allVersions'] = array();
			$aArgsSaveK['changedVersions'] = array();
			
			$aArgsSaveK['columns'] = array('id_kcid' => 'i', 'id_mid' => 'i', 'code' => 's', 'link' => 's', 'master' => 'i', 'lastmodified' => 's', 'page_number' => 'i', 'page_dimension' => 's');
			$aArgsSaveK['aFieldsNumbers'] = array('id_kcid', 'id_mid', 'master', 'page_number');
			$aArgsSaveK['excludeUpdateUni'] = array('id_kcid' => array('',0), 'id_mid' => array('',0), 'code' => array(''), 'link' => array(''), 'master' => array('',0), 'lastmodified' => array(''), 'page_number' => array('',0), 'page_dimension' => array(''));
			
			$aArgsLVK = array();
			$aArgsLVK['type'] = 'sysall';
			$aLocalVersionsK = localVariationsBuild($aArgsLVK);
		
			
			$queryK = $CONFIG['dbconn'][0]->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc
													(id_count, id_lang, id_dev, id_cl, id_mid, id_kcid, code, link, master, lastmodified, page_number, page_dimension, create_at, create_from, change_from)
												VALUES
													(:id_count, :id_lang, :id_dev, :id_cl, :id_mid, :id_kcid, :code, :link, :master, :lastmodified, :page_number, :page_dimension, :create_at, :create_from, :create_from)
												ON DUPLICATE KEY UPDATE 
													link = (:link),
													master = (:master),
													lastmodified = (:lastmodified),
													page_number = (:page_number),
													page_dimension = (:page_dimension),
													change_from = (:create_from)
												');
			$queryK->bindValue(':id_count', 0, PDO::PARAM_INT);
			$queryK->bindValue(':id_lang', 0, PDO::PARAM_INT);
			$queryK->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryK->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
			$queryK->bindValue(':id_mid', $id_mid, PDO::PARAM_INT);
			$queryK->bindValue(':id_kcid', $id_kcid, PDO::PARAM_INT);
			$queryK->bindValue(':code', $kiadocode, PDO::PARAM_STR);
			$queryK->bindValue(':link', $urlReal, PDO::PARAM_STR);
			$queryK->bindValue(':master', $master, PDO::PARAM_INT);
			$queryK->bindValue(':lastmodified', $lastmodified, PDO::PARAM_STR);
			$queryK->bindValue(':page_number', $pageNum, PDO::PARAM_INT);
			$queryK->bindValue(':page_dimension', $aDimension, PDO::PARAM_STR);
			$queryK->bindValue(':create_at', $now, PDO::PARAM_STR);
			$queryK->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
			$queryK->bindValue(':change_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
			$queryK->execute();
			
			
			$aArgsSaveK['changedVersions'] = array(array(0,0,0));
			$aArgsSaveK['allVersions'] = $aLocalVersionsK;
			insertAll($aArgsSaveK);
		}
	}
	
	
	##############################################################
	// Process Local PDF
	##############################################################
    foreach($aCount as $k => $rowCL){
		$query1 = $CONFIG['dbconn'][0]->prepare('
                                        SELECT ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_kcid,
                                                ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.link,
                                                ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.lastmodified
                                        FROM ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc 

                                        WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_count = (:id_count)
                                                AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_lang = (:id_lang)
                                                AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_dev = (:id_dev)
                                                AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
                                                AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.del = (:nultime)
                                                AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.code = (:code)
                                        ');
		$query1->bindValue(':id_count', $rowCL['id_countid'], PDO::PARAM_INT);
		$query1->bindValue(':id_lang', $rowCL['id_langid'], PDO::PARAM_INT);
		$query1->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$query1->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$query1->bindValue(':code', $kiadocode, PDO::PARAM_STR);
		$query1->execute();
		$rows1 = $query1->fetchAll(PDO::FETCH_ASSOC);
		$num1 = $query1->rowCount();
		
		
		##############################################################
		// Process local PDF
		##############################################################
		$urlKiado = 'https://kdr.lexmark.com/media/' . $kiadocode . '?lang=' . strtolower($rowCL['code_lang']) . '_' . strtoupper($rowCL['code_count']) . '&format=high';
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $urlKiado);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FILETIME, true);
		curl_exec($ch);
		
		$urlReal = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);	
		$filetime = curl_getinfo($ch, CURLINFO_FILETIME);	
		$master = (substr_count($urlReal, '/master/') > 0) ? 1 : 2;
	
		$date = new DateTime();
		$date->setTimestamp($filetime);
		$lastmodified = $date->format('Y-m-d H:i:s');	

		if($code == '200' && $master == 2){
			if($num1 == 0 || $lastmodified > $rows1[0]['lastmodified'] || $urlReal != $rows1[0]['link']){
        
       
				// save mediafolder
				include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-media-upload-folder.php');
				
				// download file from kiado
				$num = 0;
				//$filenameOrg = basename($urlReal);
				$filenameOrg = $urlReal;
				$lastCharOrg = strrpos($filenameOrg,".");
				$filenameOrgBase = substr($filenameOrg, 0, $lastCharOrg);
				$filenameOrgEnd = strtolower(substr(strrchr($filenameOrg, "."), 1));
				$filenameBase = md5($filenameOrgBase);
				$filename = $filenameBase . '.' . $filenameOrgEnd;
			
//				$handle = opendir($mediaPath);
//				while(file_exists($mediaPath . $filename)){
//					$num++;
//					$filename = $filenameBase . "-" . $num . '.' . $filenameOrgEnd;
//				}
//				closedir($handle);
				
				copy($urlReal, $mediaPath . $filename);
                                if(file_exists($mediaPath . $filename)){
                                    chmod($mediaPath . $filename , 0777);
                                }
		
				// split and create images
				$fileOriginal = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'] . $filename;
				$dirTarget = $CONFIG['system']['directoryRoot'] . 'assetimages/';
				if(file_exists($fileOriginal)){
					$aFilenameOriginal = explode('.', $filename);
					array_pop($aFilenameOriginal);
					$filenameOriginal = implode('.', $aFilenameOriginal);
			
					system('pdftoppm -png -r 96 -cropbox -aa yes ' . $fileOriginal . ' ' . $dirTarget . 'pictures/' . $filenameOriginal);
					system('pdftoppm -png -r 96 -cropbox -aa yes -scale-to 140 ' . $fileOriginal . ' ' . $dirTarget . 'thumbnails/' . $filenameOriginal);
					
					// get document dimensions in pt (1pt = 1/72 * 25,4mm)
					$pdf = new PDFInfo($fileOriginal);
					$pageNum = $pdf->pages;
					$aMediaBox = $pdf->mediaBox;
					$aCropBox = $pdf->cropBox;
					$aBleedBox = $pdf->bleedBox;
					$aTrimBox = $pdf->trimBox;
					$aArtBox = $pdf->artBox;
					$aDimension = json_encode(array('mediabox' => $aMediaBox, 'cropbox' => $aCropBox, 'bleedbox' => $aBleedBox, 'trimbox' => $aTrimBox, 'artbox' => $aArtBox));

					if($pageNum > 9){
						for($p=1; $p<10; $p++){
							$fileSearch = $dirTarget . 'pictures/' . $filenameOriginal . '-0' . $p . '.png';
							if(file_exists($fileSearch)){
								rename($fileSearch, str_replace('-0' . $p . '.png', '-' . $p . '.png', $fileSearch));
							}
							$fileSearch = $dirTarget . 'thumbnails/' . $filenameOriginal . '-0' . $p . '.png';
							if(file_exists($fileSearch)){
								rename($fileSearch, str_replace('-0' . $p . '.png', '-' . $p . '.png', $fileSearch));
							}
						}
					}
				}

				// save mediafiles
				$aArgsLV = array();
				$aArgsLV['type'] = 'sysall';
				$aLocalVersionsMedia = localVariationsBuild($aArgsLV);
				
				$field = $rowCL['id_countid'].'_'.$rowCL['id_langid'].'_0';
				$CONFIG['page']['id_data'] = $id_kcid;
				$CONFIG['page']['id_mod'] = 119;
				$CONFIG['page']['id_mod_parent'] = 0;
				$CONFIG['page']['id_page'] = 119;
		
				$mediafileIdData = $id_kcid;
				$mediafileIdMod = 119;
				$mediafileIdModParent = 0;
				$mediafileIdPage = 119;
				$mediafileFieldname = 'kiadofile';
				include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-media-upload-file.php');
				
				$id_mid = 0;


				###################
				// save kiadocodes
				$aArgsSaveK = array();
				$aArgsSaveK['id_data'] = $id_kcid;
				$aArgsSaveK['table'] = $CONFIG['db'][0]['prefix'] . '_kiadocodes_';
				$aArgsSaveK['primarykey'] = 'id_kcid';
				$aArgsSaveK['orgfieldname'] = $varSQL['orgfieldname'];
				$aArgsSaveK['multiple'] = $varSQL['multiple'];
				$aArgsSaveK['allVersions'] = array();
				$aArgsSaveK['changedVersions'] = array();
				
				$aArgsSaveK['columns'] = array('id_kcid' => 'i', 'id_mid' => 'i', 'code' => 's', 'link' => 's', 'master' => 'i', 'lastmodified' => 's', 'page_number' => 'i', 'page_dimension' => 's');
				$aArgsSaveK['aFieldsNumbers'] = array('id_kcid', 'id_mid', 'master', 'page_number');
				$aArgsSaveK['excludeUpdateUni'] = array('id_kcid' => array('',0), 'id_mid' => array('',0), 'code' => array(''), 'link' => array(''), 'master' => array('',0), 'lastmodified' => array(''), 'page_number' => array('',0), 'page_dimension' => array(''));
				
				$aArgsLVK = array();
				$aArgsLVK['type'] = 'sysall';
				$aLocalVersionsK = localVariationsBuild($aArgsLVK);

		
				$queryK = $CONFIG['dbconn'][0]->prepare('
													INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc
														(id_count, id_lang, id_dev, id_cl, id_kcid, id_mid, code, link, master, lastmodified, page_number, page_dimension, create_at, create_from, change_from, del)
													VALUES
														(:id_count, :id_lang, :id_dev, :id_cl, :id_kcid, :id_mid, :code, :link, :master, :lastmodified, :page_number, :page_dimension, :create_at, :create_from, :create_from, :nultime)
													ON DUPLICATE KEY UPDATE 
														link = (:link),
														master = (:master),
														lastmodified = (:lastmodified),
														page_number = (:page_number),
														page_dimension = (:page_dimension),
														change_from = (:create_from),
														del = (:nultime)
													');
				$queryK->bindValue(':id_count', $rowCL['id_countid'], PDO::PARAM_INT);
				$queryK->bindValue(':id_lang', $rowCL['id_langid'], PDO::PARAM_INT);
				$queryK->bindValue(':id_dev', 0, PDO::PARAM_INT);
				$queryK->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
				$queryK->bindValue(':id_kcid', $id_kcid, PDO::PARAM_INT);
				$queryK->bindValue(':id_mid', $id_mid, PDO::PARAM_INT);
				$queryK->bindValue(':code', $kiadocode, PDO::PARAM_STR);
				$queryK->bindValue(':link', $urlReal, PDO::PARAM_STR);
				$queryK->bindValue(':master', $master, PDO::PARAM_INT);
				$queryK->bindValue(':lastmodified', $lastmodified, PDO::PARAM_STR);
				$queryK->bindValue(':page_number', $pageNum, PDO::PARAM_INT);
				$queryK->bindValue(':page_dimension', $aDimension, PDO::PARAM_STR);
				$queryK->bindValue(':create_at', $now, PDO::PARAM_STR);
				$queryK->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
				$queryK->bindValue(':change_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
				$queryK->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$queryK->execute();

				$aArgsSaveK['changedVersions'] = array(array(0,0,0), array($rowCL['id_countid'],$rowCL['id_langid'],0));
				$aArgsSaveK['allVersions'] = $aLocalVersionsK;
				insertAll($aArgsSaveK);
			}
		}
	}
 
        $query1 = null;
        $CONFIG['dbconn'][0] = null;
}


getConnection(0);





?>