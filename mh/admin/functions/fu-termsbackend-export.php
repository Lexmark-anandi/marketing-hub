<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();


$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');



$queryD = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_tbe_data, 
										' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_tbeid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_count,
										' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_lang,
										' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_dev,
										' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_cl,
										' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.var,
										' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.term

									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_dev = (:id_dev)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.del = (:nultime)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_tbeid
									');
$queryD->bindValue(':id_count', $CONFIG['settings']['selectCountry'], PDO::PARAM_INT);
$queryD->bindValue(':id_lang', $CONFIG['settings']['selectLanguage'], PDO::PARAM_INT);
$queryD->bindValue(':id_dev', $CONFIG['settings']['selectDevice'], PDO::PARAM_INT);
$queryD->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryD->execute();
$rowsD = $queryD->fetchAll(PDO::FETCH_ASSOC);
$numD = $queryD->rowCount();

$fileCode = '';

//if($CONFIG['settings']['selectCountry'] == 0){
//	$fileCode .= 'all_';
//}else{
//	$query = $CONFIG['dbconn'][0]->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_countries.code
//										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_countries 
//										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_countries.del = (:nultime)
//											AND ' . $CONFIG['db'][0]['prefix'] . 'system_countries.id_sys_count = (:count)
//										);
//	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//	$query->bindValue(':count', $CONFIG['settings']['selectCountry'], PDO::PARAM_INT);
//	$query->execute();
//	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//	$num = $query->rowCount();
//	if($num > 0) $fileCode .= $rows[0]['code'] . '_';
//}

if($CONFIG['settings']['selectLanguage'] == 0){
	$fileCode .= 'all_';
}else{
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_languages.code
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_languages 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_languages.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_languages.id_sys_lang = (:lang)
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':lang', $CONFIG['settings']['selectLanguage'], PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	if($num > 0) $fileCode .= $rows[0]['code'] . '_';
}


$fileCode = rtrim($fileCode, '_');
if($fileCode == 'all_all') $fileCode = 'all';
if($fileCode != '') $fileCode = '_' . $fileCode;



ini_set("memory_limit", "4000M");
ini_set("max_execution_time", "5000");

$aCols = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
			   'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
			   'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ',
			   'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ',
			   'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ'
			   );
$border_style= array('borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)));


$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathAdmin'] . 'templates/export-default.xlsx');


$objPHPExcel->setActiveSheetIndex(0);

$r = 1;
$c = 0;

$objPHPExcel->getActiveSheet()->getColumnDimension($aCols[$c])->setWidth(20);
$objPHPExcel->getActiveSheet()->getStyle($aCols[$c].$r)->applyFromArray($border_style);


$objPHPExcel->getActiveSheet()->getColumnDimension($aCols[$c])->setWidth(15);
$objPHPExcel->getActiveSheet()->getStyle($aCols[$c].$r)->applyFromArray($border_style);
$objPHPExcel->getActiveSheet()->setCellValue($aCols[$c].$r, ' ID');
$c++;

$objPHPExcel->getActiveSheet()->getColumnDimension($aCols[$c])->setWidth(80);
$objPHPExcel->getActiveSheet()->getStyle($aCols[$c].$r)->applyFromArray($border_style);
$objPHPExcel->getActiveSheet()->setCellValue($aCols[$c].$r, $TEXT['term']);
$c++;

$r++;
foreach($rowsD as $rowD){
	$c = 0;
	$id = $rowD['id_tbe_data'] . '.' . $rowD['id_tbeid'] . '.' . $rowD['id_count'] . '.' . $rowD['id_lang'] . '.' . $rowD['id_dev'] . '.' . $rowD['id_cl'];
	$objPHPExcel->getActiveSheet()->getCell($aCols[$c].$r)->setValueExplicit($id, PHPExcel_Cell_DataType::TYPE_STRING);
	
	$c++;
	$objPHPExcel->getActiveSheet()->getCell($aCols[$c].$r)->setValueExplicit($rowD['term'], PHPExcel_Cell_DataType::TYPE_STRING);
	
	$r++;
}


$maxRow = $objPHPExcel->getActiveSheet()->getHighestDataRow(); 
$maxCol = $objPHPExcel->getActiveSheet()->getHighestDataColumn(); 

$objPHPExcel->getActiveSheet()->setAutoFilter($aCols[0].'1:'.$aCols[($num)].($r - 1));


$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
$objPHPExcel->getActiveSheet()->getStyle('B1:'.$maxCol.$maxRow)->getProtection()->setLocked( PHPExcel_Style_Protection::PROTECTION_UNPROTECTED );


$con = array();
$con['folder'] = '';
$con['filesys_filename'] = md5(microtime()) . '.xlsx';
$con['filename'] = str_replace(' ', '_', $TEXT['Terms Backend']) . $fileCode . '_' . $now . '.xlsx';

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathAdmin'] . 'tmp/' . $con['filesys_filename']);



echo json_encode($con);


?>