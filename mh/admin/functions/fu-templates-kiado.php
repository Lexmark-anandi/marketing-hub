<?php
use \Howtomakeaturn\PDFInfo\PDFInfo;
		
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$modulpath = $CONFIG['page']['modulpath'];
$mediaPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'];

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tpid
									FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_dev = (:id_dev)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = (:id_tempid)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.del = (:nultime)
									');
$query->bindValue(':id_count', 0, PDO::PARAM_INT);
$query->bindValue(':id_lang', 0, PDO::PARAM_INT);  
$query->bindValue(':id_dev', 0, PDO::PARAM_INT);
$query->bindValue(':id_tempid', $varSQL['tempid'], PDO::PARAM_INT); 
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

if($num > 0){
	$id_tpid = $rows[0]['id_tpid'];
	$kiadocode = $varSQL['kiado'];
	
	
	$id_kcid = '';
	$pageNum = '';
	$aDimension = '';
	
	if($kiadocode != ''){

		##################################################
		// save kiadocode (if not exists)
		##################################################
		$query1 = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.id_kcid,
												' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.link,
												' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.master,
												' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.lastmodified,
												' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.page_number,
												' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_loc.page_dimension
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
		
		if($num1 == 0){
			##############################################################
			// Process Master PDF
			##############################################################
			$urlKiado = 'http://kdr.lexmark.com/media/' . $kiadocode . '?lang=XYXYXY_XYXYXY&format=high';
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
		
			$date->setTimestamp($filetime);
			$lastmodified = $date->format('Y-m-d H:i:s');	
			//echo "ERROR :: ".curl_error($ch)."<BR>";
			//echo "CURL CODE :: ".$code."<BR>";
			if($code == '200'){
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
				$idNewK = $CONFIG['dbconn'][0]->lastInsertId();
				$aArgsSaveK['id_data'] = $idNewK;
				$id_kcid = $idNewK;


				// save mediafiles
				$field = '0_0_0';
				$mediafileIdData = $id_kcid;
				$mediafileIdMod = 119;
				$mediafileIdModParent = 0;
				$mediafileIdPage = 119;
				$mediafileFieldname = 'kiadofile';
				include($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-media-upload-file.php');
				
				$id_mid = $aArgsSave['id_data'];
	
	
				// save kiadocodes
				$aArgsSaveK = array();
				$aArgsSaveK['id_data'] = $id_kcid;
				$aArgsSaveK['table'] = $CONFIG['db'][0]['prefix'] . '_kiadocodes_';
				$aArgsSaveK['primarykey'] = 'id_kcid';
				$aArgsSaveK['orgfieldname'] = 'kiadofile';
				$aArgsSaveK['multiple'] = '';
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
				$queryK->bindValue(':id_kcid', $idNewK, PDO::PARAM_INT);
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
				
				
				$queryK = $CONFIG['dbconn'][0]->prepare('
													INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_kiadocodes_uni
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
				$queryK->bindValue(':id_kcid', $idNewK, PDO::PARAM_INT);
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
				
				
//				$aArgsSaveK['changedVersions'] = array(array(0,0,0));
//				$aArgsSaveK['allVersions'] = $aLocalVersionsK;
//				insertAll($aArgsSaveK);
			}
		}else{
			$id_kcid = $rows1[0]['id_kcid'];
			$code = '200';
			$urlReal = $rows1[0]['link'];
			$master = $rows1[0]['master'];
			$lastmodified = $rows1[0]['lastmodified'];	
			$pageNum = $rows1[0]['page_number'];	
			$aDimension = $rows1[0]['page_dimension'];	
		}
		
		
		// update templatespages
		$queryK = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc SET
												id_kcid = (:id_kcid), 
												file_original = (:file_original), 
												change_from =(:change_from)
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_tempid = (:id_tempid)
											LIMIT 1
											');
		$queryK->bindValue(':id_count', 0, PDO::PARAM_INT);
		$queryK->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$queryK->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$queryK->bindValue(':id_tempid', $varSQL['tempid'], PDO::PARAM_INT);
		$queryK->bindValue(':id_kcid', $id_kcid, PDO::PARAM_INT);
		$queryK->bindValue(':file_original', 0, PDO::PARAM_INT);
		$queryK->bindValue(':change_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
		$queryK->execute();
	
		$aArgsSaveT = array();
		$aArgsSaveT['id_data'] = $id_tpid;
		$aArgsSaveT['table'] = $CONFIG['db'][0]['prefix'] . '_templatespages_';
		$aArgsSaveT['primarykey'] = 'id_tpid';
		$aArgsSaveT['orgfieldname'] = 'kiadofile';
		$aArgsSaveT['multiple'] = '';
		$aArgsSaveT['allVersions'] = array();
		$aArgsSaveT['changedVersions'] = array();
		
		$aArgsSaveT['columns'] = array('id_tpid' => 'i', 'id_kcid' => 'i', 'id_tempid' => 'i', 'page' => 'i', 'file_original' => 'i');
		$aArgsSaveT['aFieldsNumbers'] = array('id_tpid', 'id_kcid', 'id_tempid', 'page', 'file_original');
		$aArgsSaveT['excludeUpdateUni'] = array('id_tpid' => array('',0), 'id_kcid' => array('',0), 'id_tempid' => array('',0), 'page' => array('',0), 'file_original' => array('',0));
		
		$aArgsLVT = array();
		$aArgsLVT['type'] = 'sysall';
		$aLocalVersionsT = localVariationsBuild($aArgsLVT);
	
		$aArgsSaveT['changedVersions'] = array(array(0,0,0));
		$aArgsSaveT['allVersions'] = $aLocalVersionsT;
		insertAll($aArgsSaveT);
	}
	
	
	echo $id_kcid;
}


?>