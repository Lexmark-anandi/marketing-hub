<?php
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load($path . '/' . $file);
$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);


########################################################################################################
// Check data
########################################################################################################
$checkData = true;
foreach($sheetData as $key=>$val){
	if($key > 1){
		$aErrorRow = array();
		$objPHPExcel->getActiveSheet()->getStyle('A'.$key.':'.$aColsXLS['error'].$key)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_NONE);
		$objPHPExcel->getActiveSheet()->setCellValue($aColsXLS['error'].$key, '');

		$aData = explode('.', trim($val[$aColsXLS['id_data']]));
		$id_data = $aData[0];
		$id = $aData[1];
		$id_count = $aData[2];
		$id_lang = $aData[3];
		$id_dev = $aData[4];
		$id_clid = $aData[5];
		
		$var = trim($val[$aColsXLS['var']]);
		$term = trim($val[$aColsXLS['term']]);


		// Check for id_data
		if(count($aData) == 0){
			$objPHPExcel->getActiveSheet()->getStyle($aColsXLS['id_data'].$key)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle($aColsXLS['id_data'].$key)->getFill()->getStartColor()->setARGB('FFFE949E');
			$checkData = false;
			array_push($aErrorRow, $TEXT['errorUploadIdMissing']);
			if(!in_array($TEXT['errorPriceimportInternal'], $aErrors[$file])) array_push($aErrors[$file], $TEXT['errorPriceimportInternal']);
		}else{
			$queryP = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_tfe_data AS id_data,
													' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_tfeid AS id,
													' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_count,
													' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_lang,
													' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_dev,
													' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_clid,
													' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.var
												FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_fe_uni.id_tfe_data = (:id_data)
												');
			$queryP->bindValue(':id_data', $id_data, PDO::PARAM_INT);
			$queryP->execute();
			$rowsP = $queryP->fetchAll(PDO::FETCH_ASSOC);
			$numP = $queryP->rowCount();
			
			// Check for id_data
			if($numP == 0){
				$objPHPExcel->getActiveSheet()->getStyle($aColsXLS['id_data'].$key)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle($aColsXLS['id_data'].$key)->getFill()->getStartColor()->setARGB('FFFE949E');
				$checkData = false;
				if(!in_array($TEXT['errorUploadId'], $aErrorRow)) array_push($aErrorRow, $TEXT['errorUploadId']);
				if(!in_array($TEXT['errorPriceimportInternal'], $aErrors[$file])) array_push($aErrors[$file], $TEXT['errorPriceimportInternal']);
			}else{
				// Check for id
				if($id != $rowsP[0]['id'] || $id != trim($val[$aColsXLS['id']])){
					$objPHPExcel->getActiveSheet()->getStyle($aColsXLS['id_data'].$key)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle($aColsXLS['id_data'].$key)->getFill()->getStartColor()->setARGB('FFFE949E');
					$checkData = false;
					if(!in_array($TEXT['errorUploadId'], $aErrorRow)) array_push($aErrorRow, $TEXT['errorUploadId']);
					if(!in_array($TEXT['errorPriceimportInternal'], $aErrors[$file])) array_push($aErrors[$file], $TEXT['errorPriceimportInternal']);
				}
				
				// Check for country
				if($id_count != $rowsP[0]['id_count']){
					$objPHPExcel->getActiveSheet()->getStyle($aColsXLS['id_data'].$key)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle($aColsXLS['id_data'].$key)->getFill()->getStartColor()->setARGB('FFFE949E');
					$checkData = false;
					if(!in_array($TEXT['errorUploadId'], $aErrorRow)) array_push($aErrorRow, $TEXT['errorUploadId']);
					if(!in_array($TEXT['errorPriceimportInternal'], $aErrors[$file])) array_push($aErrors[$file], $TEXT['errorPriceimportInternal']);
				}
				
				// Check for language
				if($id_lang != $rowsP[0]['id_lang']){
					$objPHPExcel->getActiveSheet()->getStyle($aColsXLS['id_data'].$key)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle($aColsXLS['id_data'].$key)->getFill()->getStartColor()->setARGB('FFFE949E');
					$checkData = false;
					if(!in_array($TEXT['errorUploadId'], $aErrorRow)) array_push($aErrorRow, $TEXT['errorUploadId']);
					if(!in_array($TEXT['errorPriceimportInternal'], $aErrors[$file])) array_push($aErrors[$file], $TEXT['errorPriceimportInternal']);
				}
				
				// Check for device
				if($id_dev != $rowsP[0]['id_dev']){
					$objPHPExcel->getActiveSheet()->getStyle($aColsXLS['id_data'].$key)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle($aColsXLS['id_data'].$key)->getFill()->getStartColor()->setARGB('FFFE949E');
					$checkData = false;
					if(!in_array($TEXT['errorUploadId'], $aErrorRow)) array_push($aErrorRow, $TEXT['errorUploadId']);
					if(!in_array($TEXT['errorPriceimportInternal'], $aErrors[$file])) array_push($aErrors[$file], $TEXT['errorPriceimportInternal']);
				}
				
				// Check for client
				if($id_clid != $rowsP[0]['id_clid']){
					$objPHPExcel->getActiveSheet()->getStyle($aColsXLS['id_data'].$key)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle($aColsXLS['id_data'].$key)->getFill()->getStartColor()->setARGB('FFFE949E');
					$checkData = false;
					if(!in_array($TEXT['errorUploadId'], $aErrorRow)) array_push($aErrorRow, $TEXT['errorUploadId']);
					if(!in_array($TEXT['errorPriceimportInternal'], $aErrors[$file])) array_push($aErrors[$file], $TEXT['errorPriceimportInternal']);
				}
				
				// Check for var
				if($var != $rowsP[0]['var']){
					$objPHPExcel->getActiveSheet()->getStyle($aColsXLS['var'].$key)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle($aColsXLS['var'].$key)->getFill()->getStartColor()->setARGB('FFFE949E');
					$checkData = false;
					array_push($aErrorRow, $TEXT['errorUploadTermsVar']);
					if(!in_array($TEXT['errorPriceimportInternal'], $aErrors[$file])) array_push($aErrors[$file], $TEXT['errorPriceimportInternal']);
				}
			}
		}


		$listErrorRow = '';
		if(count($aErrorRow) > 0){
			$nE = 1;
			foreach($aErrorRow as $error){
				$listErrorRow .= $nE . '. ' . $error . ' ';
				$nE++;
			}
			$objPHPExcel->getActiveSheet()->getStyle($aColsXLS['error'].$key)->getFont()->getColor()->setRGB('CC0000');
			$objPHPExcel->getActiveSheet()->setCellValue($aColsXLS['error'].$key, $listErrorRow);
		}
	}
}
	
if($checkData == false){
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($fileerror);
}
########################################################################################################
// Check data end
########################################################################################################




########################################################################################################
// Import data
########################################################################################################
if($checkData == true){
	foreach($sheetData as $key=>$val){
		if($key > 1){
			$aID = explode('.', trim($val[$aColsXLS['id_data']]));
			$id_data = $aID[0];
			$id = $aID[1];
			$id_count = $aID[2];
			$id_lang = $aID[3];
			$id_dev = $aID[4];
			$id_clid = $aID[5];
			
			$var = trim($val[$aColsXLS['var']]);
			$term = trim($val[$aColsXLS['term']]);
			
			$aImport = array(
							'id_data' => $id_data,
							'id' => $id,
							'id_count' => $id_count,
							'id_lang' => $id_lang,
							'id_dev' => $id_dev,
							'id_clid' => $id_clid,
							'var' => $var,
							'term' => $term
							);
			
			
			$query2 = $CONFIG['dbconn']->prepare('
												INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
													(
													id,
													id_count,
													id_lang,
													id_dev,
													id_clid,
													id_uid,
													modulname,
													data
													)
												VALUES
													(
													:id,
													:id_count,
													:id_lang,
													:id_dev,
													:clid,
													:uid,
													:modulname,
													:data
													)
												');
			$query2->bindValue(':id', $id, PDO::PARAM_INT);
			$query2->bindValue(':id_count', $id_count, PDO::PARAM_INT);
			$query2->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
			$query2->bindValue(':id_dev', $id_dev, PDO::PARAM_INT);
			$query2->bindValue(':clid', $id_clid, PDO::PARAM_INT);
			$query2->bindValue(':uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
			$query2->bindValue(':modulname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
			$query2->bindValue(':data', json_encode($aImport), PDO::PARAM_STR);
			$query2->execute();
			
			
			
			$date = new DateTime();
			$now = $date->format('Y-m-d H:i:s');
			
			$table = $CONFIG['db'][0]['prefix'] . 'sys_terms_fe';
			$primekey = 'id_tfeid';
			$aFieldsNumbers = array();
			
			$columns = $table . '_##TYPE##.' . $primekey . ',
						' . $table . '_##TYPE##.var,
						' . $table . '_##TYPE##.term
			';
			
			$listFields = array(
								"var"			=>	array('var', "s"), 
								"term"			=>	array('term', "s"), 
								);
			
			
			$varSQL['id'] = $id;
			include($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-default-update.php'); 


			$query3 = $CONFIG['dbconn']->prepare('
												DELETE FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:id_uid)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.modulname = (:modulname)
												');
			$query3->bindValue(':id_uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
			$query3->bindValue(':modulname', $CONFIG['page']['moduls'][$varSQL['modul']]['modulname'], PDO::PARAM_STR);
			$query3->execute();


			
			include($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-terms-fe-writetext.php'); 
		}
	}
}



















?>