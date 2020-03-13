<?php

//if($varSQL['exportname'] != '' && isset($varSQL['exportfields'])){
//	$query = $CONFIG['dbconn']->prepare('
//										SELECT ' . $CONFIG['db'][0]['prefix_sys'] . 'grids2export.id_g2e
//										FROM ' . $CONFIG['db'][0]['prefix_sys'] . 'grids2export
//										WHERE ' . $CONFIG['db'][0]['prefix_sys'] . 'grids2export.id_uid = (:uid)
//											AND ' . $CONFIG['db'][0]['prefix_sys'] . 'grids2export.id_grid_d = (:id_grid_d)
//											AND ' . $CONFIG['db'][0]['prefix_sys'] . 'grids2export.exportname = (:exportname)
//										');
//	$query->bindValue(':uid', $_SESSION['admin']['USER']['id_real'], PDO::PARAM_INT);
//	$query->bindValue(':id_grid_d', $varSQL['id_grid_d'], PDO::PARAM_INT);
//	$query->bindValue(':exportname', $varSQL['exportname'], PDO::PARAM_STR);
//	$query->execute();
//	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//	$num = $query->rowCount();
//	
//	if($num == 0){
//		$query2 = $CONFIG['dbconn']->prepare('
//											INSERT INTO ' . $CONFIG['db'][0]['prefix_sys'] . 'grids2export
//											(id_uid, id_grid_d, exportname, exportdata, exportfields)
//											VALUES
//											(:uid, :id_grid_d, :exportname, :exportdata, :exportfields)
//											');
//		$query2->bindValue(':uid', $_SESSION['admin']['USER']['id_real'], PDO::PARAM_INT);
//		$query2->bindValue(':id_grid_d', $varSQL['id_grid_d'], PDO::PARAM_INT);
//		$query2->bindValue(':exportname', $varSQL['exportname'], PDO::PARAM_STR);
//		$query2->bindValue(':exportdata', $varSQL['exportdata'], PDO::PARAM_STR);
//		$query2->bindValue(':exportfields', implode(',',$varSQL['exportfields']), PDO::PARAM_STR);
//		$query2->execute();
//	}else{
//		$query2 = $CONFIG['dbconn']->prepare('
//											UPDATE ' . $CONFIG['db'][0]['prefix_sys'] . 'grids2export SET
//											exportdata = (:exportdata), 
//											exportfields = (:exportfields)
//											WHERE ' . $CONFIG['db'][0]['prefix_sys'] . 'grids2export.id_g2e = (:id_g2e)
//											LIMIT 1
//											');
//		$query2->bindValue(':exportdata', $varSQL['exportdata'], PDO::PARAM_STR);
//		$query2->bindValue(':exportfields', implode(',',$varSQL['exportfields']), PDO::PARAM_STR);
//		$query2->bindValue(':id_g2e', $rows[0]['id_g2e'], PDO::PARAM_INT);
//		$query2->execute();
//	}
//}


#################################################################################
ini_set("memory_limit", "4000M");
ini_set("max_execution_time", "5000");

$aCols = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
			   'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
			   'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ',
			   'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ',
			   'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ'
			   );
$border_style= array('borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)));


$colId = '';
$condEx = '';
if(isset($varSQL['exportfields'])){
	$colId = implode(',', $varSQL['exportfields']);
	$condEx = 'AND ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.id_col_d IN (' . $colId . ')';
}


$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathAdmin'] . 'templates/export-default.xlsx');


############################################################################
// Set Dropdown
$objWorksheet1 = $objPHPExcel->createSheet();
$objWorksheet1->setTitle('selects');
$objPHPExcel->setActiveSheetIndexByName('selects');
$objPHPExcel->getActiveSheet()->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);


$c = 0;

$queryS = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.exp_format,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.exp_selectoptions,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_colname,
										' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_index
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user
									INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default
										ON ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.id_col_d = ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.id_col_d
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_active = (:g_active)
										' . $condEx . '
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.id_grid_d = (:id_grid_d)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.id_uid = (:uid)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.id_modul_parent = (:id_modul_parent)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_name <> (:actions)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.exp_format IN ("select","b")
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_rank
									');
$queryS->bindValue(':g_active', 1, PDO::PARAM_INT);
$queryS->bindValue(':actions', 'actions', PDO::PARAM_STR);
$queryS->bindValue(':id_grid_d', $varSQL['id_grid_d'], PDO::PARAM_INT);
$queryS->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
$queryS->bindValue(':id_modul_parent', $varSQL['idModulParent'], PDO::PARAM_STR);
$queryS->execute();
$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
$numS = $queryS->rowCount();

$aSelect = array();
foreach($rowsS as $rowS){
	$r = 1;
	
	if($rowS['exp_format'] == 'b'){
		$objPHPExcel->getActiveSheet()->getCell($aCols[$c].$r)->setValueExplicit(TRUE, PHPExcel_Cell_DataType::TYPE_BOOL);
		$r++;
		$objPHPExcel->getActiveSheet()->getCell($aCols[$c].$r)->setValueExplicit(FALSE, PHPExcel_Cell_DataType::TYPE_BOOL);
		$r++;
	}else{
		$vTmp = array();
		$aV = explode(";", $rowS['exp_selectoptions']);
		foreach($aV as $vT){
			$aV2 = explode(':', $vT);
			$vTmp[$aV2[0]] = $aV2[1];
		}
		
		include($vTmp['dataUrl']);
		
		foreach($rows as $row){
			$val = $row['term'];
			$objPHPExcel->getActiveSheet()->getCell($aCols[$c].$r)->setValueExplicit($val, PHPExcel_Cell_DataType::TYPE_STRING);
			$r++;
		}
	}
	$aSelect[$rowS['g_index']] = '$'.$aCols[$c].'$1:$'.$aCols[$c].'$'.$r;
	$objPHPExcel->addNamedRange(new PHPExcel_NamedRange($rowS['g_index'], $objPHPExcel->getActiveSheet(), '$'.$aCols[$c].'$1:$'.$aCols[$c].'$'.$r));
	
	$c++;
}
############################################################################




############################################################################
$objPHPExcel->setActiveSheetIndex(0);


	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_hidden,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_width,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.id_col_d,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_colname,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_index,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_align,
											' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.exp_format AS format
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user
										INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.id_col_d = ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.id_col_d
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_active = (:g_active)
											' . $condEx . '
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.id_grid_d = (:id_grid_d)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.id_uid = (:uid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.id_modul_parent = (:id_modul_parent)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.g_name <> (:actions)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_default.exp_format <> (:empty)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_gridscols_user.g_rank
										');
	$query->bindValue(':g_active', 1, PDO::PARAM_INT);
	$query->bindValue(':empty', '', PDO::PARAM_STR);
	$query->bindValue(':actions', 'actions', PDO::PARAM_STR);
	$query->bindValue(':id_grid_d', $varSQL['id_grid_d'], PDO::PARAM_INT);
	$query->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
	$query->bindValue(':id_modul_parent', $varSQL['idModulParent'], PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	
	$r = 1;
	$c = 0;

	$objPHPExcel->getActiveSheet()->getColumnDimension($aCols[$c])->setWidth(20);
	$objPHPExcel->getActiveSheet()->getStyle($aCols[$c].$r)->applyFromArray($border_style);
	$c++;

	foreach($rows as $row){
		$field = isset($TEXT[$row['g_colname']]) ? $TEXT[$row['g_colname']] : $row['g_colname'];
		
		$objPHPExcel->getActiveSheet()->getColumnDimension($aCols[$c])->setWidth(($row['g_width'] / 5));
		$objPHPExcel->getActiveSheet()->getStyle($aCols[$c].$r)->applyFromArray($border_style);
		$objPHPExcel->getActiveSheet()->setCellValue($aCols[$c].$r, $field);
		$c++;
	}
	
	
	$r++;
	foreach($contentRaw as $content){
		foreach($content as $dataRow){
			$c = 0;
			
			$id = $dataRow['id_data'] . '.' . $dataRow['id_set'] . '.' . $dataRow['id_count'] . '.' . $dataRow['id_lang'] . '.' . $dataRow['id_dev'] . '.' . $dataRow['id_clid'];
			$objPHPExcel->getActiveSheet()->getCell($aCols[$c].$r)->setValueExplicit($id, PHPExcel_Cell_DataType::TYPE_STRING);
			$c++;
			
			foreach($rows as $row){
				// Achtung: Formatierung verlangsamt den Export erheblich !
	//			$objPHPExcel->getActiveSheet()->getStyle($aCols[$c].$r)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	//			if($row['g_align'] == 'center') $objPHPExcel->getActiveSheet()->getStyle($aCols[$c].$r)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	//			if($row['g_align'] == 'right') $objPHPExcel->getActiveSheet()->getStyle($aCols[$c].$r)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	//			$objPHPExcel->getActiveSheet()->getStyle($aCols[$c].$r)->applyFromArray($border_style);
				$objPHPExcel->getActiveSheet()->getStyle($aCols[$c].$r)->getAlignment()->setWrapText(true);
	
				$out = $dataRow[$row['g_index']];
	
				if($row['format'] == 'i'){
					//$objPHPExcel->getActiveSheet()->getStyle($aCols[$c].$r)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
					$objPHPExcel->getActiveSheet()->getCell($aCols[$c].$r)->setValueExplicit($out, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				}else if($row['format'] == 'b'){
					//$objPHPExcel->getActiveSheet()->getStyle($aCols[$c].$r)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
					if($out == 1) $objPHPExcel->getActiveSheet()->getCell($aCols[$c].$r)->setValueExplicit(TRUE, PHPExcel_Cell_DataType::TYPE_BOOL);
					if($out == 0 || $out == 2) $objPHPExcel->getActiveSheet()->getCell($aCols[$c].$r)->setValueExplicit(FALSE, PHPExcel_Cell_DataType::TYPE_BOOL);
				}else if($row['format'] == 'f'){
					//$objPHPExcel->getActiveSheet()->getStyle($aCols[$c].$r)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
					$objPHPExcel->getActiveSheet()->getCell($aCols[$c].$r)->setValueExplicit($out, PHPExcel_Cell_DataType::TYPE_STRING);
				}else if($row['format'] == 'd'){
					//$objPHPExcel->getActiveSheet()->getStyle($aCols[$c].$r)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME);
					$objPHPExcel->getActiveSheet()->getCell($aCols[$c].$r)->setValueExplicit($out, PHPExcel_Cell_DataType::TYPE_STRING);
				}else if($row['format'] == 'c'){
					//$objPHPExcel->getActiveSheet()->getStyle($aCols[$c].$r)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
					$objPHPExcel->getActiveSheet()->getCell($aCols[$c].$r)->setValueExplicit($out, PHPExcel_Cell_DataType::TYPE_STRING);
				}else{
					//$objPHPExcel->getActiveSheet()->getStyle($aCols[$c].$r)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
					$objPHPExcel->getActiveSheet()->getCell($aCols[$c].$r)->setValueExplicit($out, PHPExcel_Cell_DataType::TYPE_STRING);
				}
	//				$objPHPExcel->getActiveSheet()->setCellValue($aCols[$c].$r, $out);
				
				
				###########
				// Set Dropdown
				if($row['format'] == 'select' || $row['format'] == 'b'){
					$objValidation = $objPHPExcel->getActiveSheet()->getCell($aCols[$c].$r)->getDataValidation();
					$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
					$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_STOP );
					$objValidation->setAllowBlank(true);
					$objValidation->setShowInputMessage(true);
					$objValidation->setShowErrorMessage(true);
					$objValidation->setShowDropDown(true);
					$objValidation->setErrorTitle($TEXT['inputError']);
					$objValidation->setError($TEXT['notInList']);
					//$objValidation->setPromptTitle($TEXT['pickList']);
					//$objValidation->setPrompt($TEXT['pickFromList']);
					$objValidation->setPrompt($TEXT['pickList']);
					
					$objValidation->setFormula1($row['g_index']);
					//$objValidation->setFormula1('"selects!$A$1:$A$5"');
				}
	
				$c++;
			}
			$r++;
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	


$maxRow = $objPHPExcel->getActiveSheet()->getHighestDataRow(); 
$maxCol = $objPHPExcel->getActiveSheet()->getHighestDataColumn(); 

$objPHPExcel->getActiveSheet()->setAutoFilter($aCols[0].'1:'.$aCols[($num)].($r - 1));




////$objPHPExcel->getActiveSheet()->getStyle($aCols[$n].$row)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
////$objPHPExcel->getActiveSheet()->getStyle($aCols[$n].$row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
////$objPHPExcel->getActiveSheet()->getStyle($aCols[$n].$row)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
////$objPHPExcel->getActiveSheet()->getStyle($aCols[$n].$row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
////$objPHPExcel->getActiveSheet()->getStyle($aCols[$n].$row.'')->getNumberFormat()->setFormatCode('#,##0 %');
////$objPHPExcel->getActiveSheet()->getCell($aCols[$n].$row.'')->setValueExplicit($datD['sn'], PHPExcel_Cell_DataType::TYPE_STRING);
////$objPHPExcel->getActiveSheet()->getStyle($aCols[$n].$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
////$objPHPExcel->getActiveSheet()->getStyle($aCols[$n].$row)->getFill()->getStartColor()->setARGB('FFFFFFFF');
////$objPHPExcel->getActiveSheet()->getStyle($aCols[$n].$row.'')->getFont()->getColor()->applyFromArray(array("rgb" => $color));
////$objPHPExcel->getActiveSheet()->setAutoFilter($aCols[$n].'1:'.$aCols[$n].'10');
////$objPHPExcel->getActiveSheet()->setCellValue($aCols[$n].$row.'', $dat['Field']); 
////$objPHPExcel->getActiveSheet()->getStyle('A1')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
	
//TYPE_BOOL
//TYPE_ERROR
//TYPE_FORMULA
//TYPE_INLINE
//TYPE_NULL
//TYPE_NUMERIC
//TYPE_STRING


//FORMAT_CURRENCY_EUR_SIMPLE
//FORMAT_CURRENCY_USD
//FORMAT_CURRENCY_USD_SIMPLE
//FORMAT_DATE_DATETIME
//FORMAT_DATE_DDMMYYYY
//FORMAT_DATE_DMMINUS
//FORMAT_DATE_DMYMINUS
//FORMAT_DATE_DMYSLASH
//FORMAT_DATE_MYMINUS
//FORMAT_DATE_TIME1
//FORMAT_DATE_TIME2
//FORMAT_DATE_TIME3
//FORMAT_DATE_TIME4
//FORMAT_DATE_TIME5
//FORMAT_DATE_TIME6
//FORMAT_DATE_TIME7
//FORMAT_DATE_TIME8
//FORMAT_DATE_YYYYMMDD
//FORMAT_DATE_YYYYMMDDSLASH
//FORMAT_GENERAL
//FORMAT_NUMBER
//FORMAT_NUMBER_00
//FORMAT_NUMBER_COMMA_SEPARATED1
//FORMAT_NUMBER_COMMA_SEPARATED2
//FORMAT_PERCENTAGE
//FORMAT_PERCENTAGE_00
//FORMAT_TEXT


$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
$objPHPExcel->getActiveSheet()->getStyle('B1:'.$maxCol.$maxRow)->getProtection()->setLocked( PHPExcel_Style_Protection::PROTECTION_UNPROTECTED );

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathAdmin'] . 'tmp/' . $con['file']);

?>