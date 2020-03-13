<?php
include_once(__DIR__ . '/fu-templates-kiado-local.php');

//use \Howtomakeaturn\PDFInfo\PDFInfo;
//		
//include_once(__DIR__ . '/../config-admin.php');
//$varSQL = getPostData();
//
//$modulpath = $CONFIG['page']['modulpath'];
//$mediaPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'];
//
//$date = new DateTime(); 
//$now = $date->format('Y-m-d H:i:s');
//
//$queryCL = $CONFIG['dbconn'][0]->prepare('
//									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code_spec AS code_count,
//										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid,
//										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code_spec AS code_lang,
//										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid
//									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni 
//									
//									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
//										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
//									
//									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
//										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid
//									
//									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//									');
//$queryCL->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//$queryCL->bindValue(':nul', 0, PDO::PARAM_INT);
//$queryCL->execute();
//$rowsCL = $queryCL->fetchAll(PDO::FETCH_ASSOC);
//$numCL = $queryCL->rowCount();
//
//foreach($rowsCL as $rowCL){
//	$query = $CONFIG['dbconn'][0]->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tpid,
//											' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_kcid,
//											' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.kiado_code
//										FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni 
//										
//										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_count = (:id_count)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_lang = (:id_lang)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_dev = (:id_dev)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = (:id_tempid)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.del = (:nultime)
//											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.kiado_code <> ""
//										');
//	$query->bindValue(':id_count', $rowCL['id_countid'], PDO::PARAM_INT);
//	$query->bindValue(':id_lang', $rowCL['id_langid'], PDO::PARAM_INT);
//	$query->bindValue(':id_dev', 0, PDO::PARAM_INT);
//	$query->bindValue(':id_tempid', $varSQL['tempid'], PDO::PARAM_INT);
//	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$query->execute();
//	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//	$num = $query->rowCount();
//
//	if($num > 0){
//		$pageNum = '';
//		$aDimension = '';
//		$id_kcid = $rows[0]['id_kcid'];
//		$id_tpid = $rows[0]['id_tpid'];
//		$kiadocode = $rows[0]['kiado_code'];
//	
//		// exists code in DB for country/language?
//		$query1 = $CONFIG['dbconn'][0]->prepare('
//											SELECT ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_kcid
//											FROM ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc 
//											
//											WHERE ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_count = (:id_count)
//												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_lang = (:id_lang)
//												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_dev = (:id_dev)
//												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
//												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.del = (:nultime)
//												AND ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.code = (:code)
//											');
//		$query1->bindValue(':id_count', $rowCL['id_countid'], PDO::PARAM_INT);
//		$query1->bindValue(':id_lang', $rowCL['id_langid'], PDO::PARAM_INT);
//		$query1->bindValue(':id_dev', 0, PDO::PARAM_INT);
//		$query1->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//		$query1->bindValue(':code', $kiadocode, PDO::PARAM_INT);
//		$query1->execute();
//		$rows1 = $query1->fetchAll(PDO::FETCH_ASSOC);
//		$num1 = $query1->rowCount();
//	
//		if($num1 == 0){
//			##############################################################
//			// Process local PDF
//			##############################################################
//			$urlKiado = 'http://kdr.lexmark.com/media/' . $kiadocode . '?lang=' . strtolower($rowCL['code_lang']) . '_' . strtoupper($rowCL['code_count']) . '&format=high';
//			$ch = curl_init(); 
//			curl_setopt($ch, CURLOPT_URL, $urlKiado);
//			curl_setopt($ch, CURLOPT_HEADER, true);
//			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//			curl_setopt($ch, CURLOPT_FILETIME, true);
//			curl_exec($ch);
//			
//			$urlReal = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
//			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);	
//			$filetime = curl_getinfo($ch, CURLINFO_FILETIME);	
//			$master = (substr_count($urlReal, '/master/') > 0) ? 1 : 2;
//		
//			$date->setTimestamp($filetime);
//			$lastmodified = $date->format('Y-m-d H:i:s');	
//	
//			if($code == '200' && $master == 2){
//				// save mediafolder
//				include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-media-upload-folder.php');
//				
//				// download file from kiado
//				$num = 0;
//				$filenameOrg = basename($urlReal);
//				$lastCharOrg = strrpos($filenameOrg,".");
//				$filenameOrgBase = substr($filenameOrg, 0, $lastCharOrg);
//				$filenameOrgEnd = strtolower(substr(strrchr($filenameOrg, "."), 1));
//				$filenameBase = md5($filenameOrgBase);
//				$filename = $filenameBase . '.' . $filenameOrgEnd;
//			
//				$handle = opendir($mediaPath);
//				while(file_exists($mediaPath . $filename)){
//					$num++;
//					$filename = $filenameBase . "-" . $num . '.' . $filenameOrgEnd;
//				}
//				closedir($handle);
//				
//				copy($urlReal, $mediaPath . $filename);
//				chmod($mediaPath . $filename , 0777);
//		
//				// split and create images
//				$fileOriginal = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'] . $filename;
//				$dirTarget = $CONFIG['system']['directoryRoot'] . 'assetimages/';
//				if(file_exists($fileOriginal)){
//					$aFilenameOriginal = explode('.', $filename);
//					array_pop($aFilenameOriginal);
//					$filenameOriginal = implode('.', $aFilenameOriginal);
//			
//					system('pdftoppm -png -r 96 -cropbox -aa yes ' . $fileOriginal . ' ' . $dirTarget . 'pictures/' . $filenameOriginal);
//					system('pdftoppm -png -r 96 -cropbox -aa yes -scale-to 140 ' . $fileOriginal . ' ' . $dirTarget . 'thumbnails/' . $filenameOriginal);
//					
//					// get document dimensions in pt (1pt = 1/72 * 25,4mm)
//					$pdf = new PDFInfo($fileOriginal);
//					$pageNum = $pdf->pages;
//					$aMediaBox = $pdf->mediaBox;
//					$aCropBox = $pdf->cropBox;
//					$aBleedBox = $pdf->bleedBox;
//					$aTrimBox = $pdf->trimBox;
//					$aArtBox = $pdf->artBox;
//					$aDimension = json_encode(array('mediabox' => $aMediaBox, 'cropbox' => $aCropBox, 'bleedbox' => $aBleedBox, 'trimbox' => $aTrimBox, 'artbox' => $aArtBox));
//				}
//				
//				// save mediafiles
//				$field = $rowCL['id_countid'].'_'.$rowCL['id_langid'].'_0';
//				$mediafileIdData = $id_kcid;
//				include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-media-upload-file.php');
//				
//				//$id_mid = $aArgsSave['id_data'];
//				// set to 0 for local version
//				$id_mid = 0;
//				
//				
//				
//				
//				###################
//				// save kiadocodes
//				$aArgsSaveK = array();
//				$aArgsSaveK['id_data'] = $id_kcid;
//				$aArgsSaveK['table'] = $CONFIG['db'][0]['prefix'] . '_kiadocodes_';
//				$aArgsSaveK['primarykey'] = 'id_kcid';
//				$aArgsSaveK['orgfieldname'] = $varSQL['orgfieldname'];
//				$aArgsSaveK['multiple'] = $varSQL['multiple'];
//				$aArgsSaveK['allVersions'] = array();
//				$aArgsSaveK['changedVersions'] = array();
//				
//				$aArgsSaveK['columns'] = array('id_kcid' => 'i', 'id_mid' => 'i', 'code' => 's', 'link' => 's', 'master' => 'i', 'lastmodified' => 's', 'page_number' => 'i', 'page_dimension' => 's');
//				$aArgsSaveK['aFieldsNumbers'] = array('id_kcid', 'id_mid', 'master', 'page_number');
//				$aArgsSaveK['excludeUpdateUni'] = array('id_kcid' => array(''), 'id_mid' => array('',0), 'code' => array(''), 'link' => array(''), 'master' => array(''), 'lastmodified' => array(''), 'page_number' => array('',0), 'page_dimension' => array(''));
//				
//				$aArgsLVK = array();
//				$aArgsLVK['type'] = 'sysall';
//				$aLocalVersionsK = localVariationsBuild($aArgsLVK);
//				
//
//
//				$queryK = $CONFIG['dbconn'][0]->prepare('
//													INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc
//														(id_count, id_lang, id_dev, id_cl, id_kcid, id_mid, code, link, master, lastmodified, page_number, page_dimension, create_at, create_from, change_from)
//													VALUES
//														(:id_count, :id_lang, :id_dev, :id_cl, :id_kcid, :id_mid, :code, :link, :master, :lastmodified, :page_number, :page_dimension, :create_at, :create_from, :create_from)
//													ON DUPLICATE KEY UPDATE 
//														link = (:link),
//														master = (:master),
//														lastmodified = (:lastmodified),
//														page_number = (:page_number),
//														page_dimension = (:page_dimension),
//														change_from = (:create_from)
//													');
//				$queryK->bindValue(':id_count', $rowCL['id_countid'], PDO::PARAM_INT);
//				$queryK->bindValue(':id_lang', $rowCL['id_langid'], PDO::PARAM_INT);
//				$queryK->bindValue(':id_dev', 0, PDO::PARAM_INT);
//				$queryK->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
//				$queryK->bindValue(':id_kcid', $aArgsSaveK['id_data'], PDO::PARAM_INT);
//				$queryK->bindValue(':id_mid', $id_mid, PDO::PARAM_INT);
//				$queryK->bindValue(':code', $kiadocode, PDO::PARAM_STR);
//				$queryK->bindValue(':link', $urlReal, PDO::PARAM_STR);
//				$queryK->bindValue(':master', $master, PDO::PARAM_INT);
//				$queryK->bindValue(':lastmodified', $lastmodified, PDO::PARAM_STR);
//				$queryK->bindValue(':page_number', $pageNum, PDO::PARAM_INT);
//				$queryK->bindValue(':page_dimension', $aDimension, PDO::PARAM_STR);
//				$queryK->bindValue(':create_at', $now, PDO::PARAM_STR);
//				$queryK->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
//				$queryK->bindValue(':change_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
//				$queryK->execute();
//
//
//				$aArgsSaveK['changedVersions'] = array(array(0,0,0), array($rowCL['id_countid'],$rowCL['id_langid'],0));
//				$aArgsSaveK['allVersions'] = $aLocalVersionsK;
//				insertAll($aArgsSaveK);
//				
//			}
//		}
//	}
//}

?>