<?php 
include_once(__DIR__ . '/../config-admin.php'); 
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');
$now2 = $date->format('Y-m-d_H:i:s');

ini_set("memory_limit", "4000M");
ini_set("max_execution_time", "5000");

$aCols = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
			   'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
			   'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ',
			   'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ',
			   'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ'
			   );
$border_style= array('borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)));

$aColor = array('black' => 'FF000000', 'lead_green' => 'FF00C425', 'light_green' => 'FF3AF23A', 'mid_green' => 'FF008A44', 'dark_green' => 'FF006446', 'blue' => 'FF1C64B4', 'yellow' => 'FFFAA519', 'lead_grey' => 'FFEBEBEB', 'light_grey' => 'FFCACACA', 'mid_grey' => 'FF8A8A8A', 'dark_grey' => 'FF32323C', 'white' => 'FFFFFFFF', 'red' => 'FFFF0000', 'note_iso' => 'FF000000', 'note_duty' => 'FF000000', 'bg_yield' => 'FFC5D9F1');



$out = array();
$out['path'] = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathAdmin'] . 'tmp/';
$out['folder'] = $CONFIG['user']['id'] . '-' . str_replace(' ', '_', microtime());
$out['filename'] = 'Partners-per-Country' . $now2 . '.xlsx';
$out['filesys_filename'] = 'export.xlsx';
$out['thumbnail'] = ''; 


mkdir($out['path'] . $out['folder']); 
chmod($out['path'] . $out['folder'], 0777);


$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathAdmin'] . 'templates/dashboard-widget-export.xlsx');


#########################################################
// Summary
$objPHPExcel->setActiveSheetIndex(0);
$col = 0;
$row = 1;

// Headline
$objPHPExcel->getActiveSheet()->getStyle($aCols[$col].$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getColumnDimension($aCols[$col])->setWidth(25);
$objPHPExcel->getActiveSheet()->getCell($aCols[$col].$row)->setValueExplicit('', PHPExcel_Cell_DataType::TYPE_STRING);

$col++;
$objPHPExcel->getActiveSheet()->getStyle($aCols[$col].$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getColumnDimension($aCols[$col])->setWidth(18);
$objPHPExcel->getActiveSheet()->getCell($aCols[$col].$row)->setValueExplicit('Quantity Partner', PHPExcel_Cell_DataType::TYPE_STRING);


// Countries
$queryC = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code AS code_country,
										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code AS code_language
										
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
										
									INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid
										
									INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid

									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang IN (' . implode(',', $CONFIG['user']['count2lang']) . ')
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code, ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code
									');
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->execute();
$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
$numC = $queryC->rowCount();

foreach($rowsC as $rowC){
	$col = 0;
	$row++;
	
	$label = $rowC['code_country'] . ' / '. $rowC['code_language'];
	$objPHPExcel->getActiveSheet()->getStyle($aCols[$col].$row)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getCell($aCols[$col].$row)->setValueExplicit($label, PHPExcel_Cell_DataType::TYPE_STRING);

	$numAllRow = 0;
	
	$numAll = 0;
	$queryN = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_pcid
											
										FROM ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni
											
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_countid = (:count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_langid = (:lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.del = (:nultime)
										GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_pcid
										');
	$queryN->bindValue(':count', $rowC['id_countid'], PDO::PARAM_INT);
	$queryN->bindValue(':lang', $rowC['id_langid'], PDO::PARAM_INT);
	$queryN->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryN->execute();
	$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
	$numN = $queryN->rowCount();
	$numAll += $numN;


	$col++;
	$objPHPExcel->getActiveSheet()->getStyle($aCols[$col].$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getCell($aCols[$col].$row)->setValueExplicit($numAll, PHPExcel_Cell_DataType::TYPE_NUMERIC);
	
	$numAllRow += $numAll;
}

$col = 0;
$row++;

$label = 'Sum';
$objPHPExcel->getActiveSheet()->getStyle($aCols[$col].$row)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getCell($aCols[$col].$row)->setValueExplicit($label, PHPExcel_Cell_DataType::TYPE_STRING);

$col++;
$objPHPExcel->getActiveSheet()->getStyle($aCols[$col].$row)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle($aCols[$col].$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue($aCols[($col)].$row.'', '=SUM('.$aCols[$col].'2:'.$aCols[$col].($row-1).')');




$maxRow = $objPHPExcel->getActiveSheet()->getHighestDataRow(); 
$maxCol = $objPHPExcel->getActiveSheet()->getHighestDataColumn(); 

$objPHPExcel->getActiveSheet()->setAutoFilter($aCols[0].'1:'.$maxCol.$maxRow);
###########################################



###########################################
// Basic data
$objPHPExcel->setActiveSheetIndex(1);

$col = 0;
$row = 1;

// Headline
$objPHPExcel->getActiveSheet()->getColumnDimension($aCols[$col])->setWidth(10);
$objPHPExcel->getActiveSheet()->getStyle($aCols[$col].$row)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle($aCols[$col].$row)->getFont()->getColor()->setARGB($aColor['black']);
$objPHPExcel->getActiveSheet()->getCell($aCols[$col].$row)->setValueExplicit($TEXT['id'], PHPExcel_Cell_DataType::TYPE_STRING);
$col++;

$objPHPExcel->getActiveSheet()->getColumnDimension($aCols[$col])->setWidth(18);
$objPHPExcel->getActiveSheet()->getStyle($aCols[$col].$row)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getCell($aCols[$col].$row)->setValueExplicit($TEXT['country'], PHPExcel_Cell_DataType::TYPE_STRING);
$col++;

$objPHPExcel->getActiveSheet()->getColumnDimension($aCols[$col])->setWidth(18);
$objPHPExcel->getActiveSheet()->getStyle($aCols[$col].$row)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getCell($aCols[$col].$row)->setValueExplicit($TEXT['language'], PHPExcel_Cell_DataType::TYPE_STRING);
$col++;

$objPHPExcel->getActiveSheet()->getColumnDimension($aCols[$col])->setWidth(30);
$objPHPExcel->getActiveSheet()->getStyle($aCols[$col].$row)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getCell($aCols[$col].$row)->setValueExplicit($TEXT['clientname'], PHPExcel_Cell_DataType::TYPE_STRING);
$col++;

$objPHPExcel->getActiveSheet()->getColumnDimension($aCols[$col])->setWidth(25);
$objPHPExcel->getActiveSheet()->getStyle($aCols[$col].$row)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getCell($aCols[$col].$row)->setValueExplicit('Partnertype', PHPExcel_Cell_DataType::TYPE_STRING);
$col++;




// Data
$queryT = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_pcid,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.company_name,
										' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.parent_program_name,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country,
										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
										
									FROM ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_countid
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = 0
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = 0
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_langid
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = 0
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = 0
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.del = (:nultime)
										
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_countid <> (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_langid <> (:nul)
									GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_pcid
									');
$queryT->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryT->bindValue(':nul', 0, PDO::PARAM_INT);
$queryT->execute();
$rowsT = $queryT->fetchAll(PDO::FETCH_ASSOC);
$numT = $queryT->rowCount();

foreach($rowsT as $rowT){
        if($rowT['parent_program_name'] == '') $rowT['parent_program_name'] = '#NV';
        
	$col = 0;
	$row++;
	
	$objPHPExcel->getActiveSheet()->getStyle($aCols[$col].$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getCell($aCols[$col].$row)->setValueExplicit($rowT['id_pcid'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
	$col++;
	
	$objPHPExcel->getActiveSheet()->getCell($aCols[$col].$row)->setValueExplicit($rowT['country'], PHPExcel_Cell_DataType::TYPE_STRING);
	$col++;
	
	$objPHPExcel->getActiveSheet()->getCell($aCols[$col].$row)->setValueExplicit($rowT['language'], PHPExcel_Cell_DataType::TYPE_STRING);
	$col++;
	
	$objPHPExcel->getActiveSheet()->getCell($aCols[$col].$row)->setValueExplicit($rowT['company_name'], PHPExcel_Cell_DataType::TYPE_STRING);
	$col++;

        $objPHPExcel->getActiveSheet()->getCell($aCols[$col].$row)->setValueExplicit($rowT['parent_program_name'], PHPExcel_Cell_DataType::TYPE_STRING);
	$col++;
}


$maxRow = $objPHPExcel->getActiveSheet()->getHighestDataRow(); 
$maxCol = $objPHPExcel->getActiveSheet()->getHighestDataColumn(); 

$objPHPExcel->getActiveSheet()->setAutoFilter($aCols[0].'1:'.$maxCol.$maxRow);
###########################################



$objPHPExcel->setActiveSheetIndex(0);

//$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
//$objPHPExcel->getActiveSheet()->getStyle('B1:'.$maxCol.$maxRow)->getProtection()->setLocked( PHPExcel_Style_Protection::PROTECTION_UNPROTECTED );

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($out['path'] . $out['folder'] . '/' . $out['filesys_filename']);

	
echo json_encode($out);

?>