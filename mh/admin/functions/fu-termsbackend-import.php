<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

ini_set("memory_limit", "1000M");
ini_set("max_execution_time", "1000"); 

ignore_user_abort(true);
set_time_limit(0);

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');
$micro = str_replace(' ', '_', microtime());
$aFile = json_decode($varSQL['files'], true);

$aCols = array(
				'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 
				'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 
				'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ', 
				'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ', 
				'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ' 
				);
$aColsXLS = array('id'=>'A', 'term'=>'B');


$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'] . $aFile['uploadfile']['sysname']);
$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);


foreach($sheetData as $key=>$val){
	if($key > 1){
		$aID = explode('.', trim($val[$aColsXLS['id']]));
		$id_data = $aID[0];
		$id = $aID[1];
		$id_count = $aID[2];
		$id_lang = $aID[3];
		$id_dev = $aID[4];
		$id_cl = $aID[5];
		
		$term = trim($val[$aColsXLS['term']]);
		

		$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_loc
					(id_tbeid, id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from, term)
				VALUES
					(:id_tbeid, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from, :term)
				ON DUPLICATE KEY UPDATE 
					term = (:term)
				';
		$query2 = $CONFIG['dbconn'][0]->prepare($qry);
		$query2->bindValue(':id_tbeid', $id, PDO::PARAM_INT);
		$query2->bindValue(':id_count', $id_count, PDO::PARAM_INT);
		$query2->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
		$query2->bindValue(':id_dev', $id_dev, PDO::PARAM_INT);
		$query2->bindValue(':id_cl', $id_cl, PDO::PARAM_INT);
		$query2->bindValue(':term', $term, PDO::PARAM_STR);
		$query2->bindValue(':now', $now, PDO::PARAM_STR);
		$query2->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_STR);
		$query2->execute();
		$num2 = $query2->rowCount();

		$query2 = $CONFIG['dbconn'][0]->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni SET
												term = (:term)
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_tbe_data = (:id_tbe_data)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_tbeid = (:id_tbeid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_terms_be_uni.id_cl = (:id_cl)
											LIMIT 1
											');
		$query2->bindValue(':id_tbe_data', $id_data, PDO::PARAM_INT);
		$query2->bindValue(':id_tbeid', $id, PDO::PARAM_INT);
		$query2->bindValue(':id_count', $id_count, PDO::PARAM_INT);
		$query2->bindValue(':id_lang', $id_lang, PDO::PARAM_INT);
		$query2->bindValue(':id_dev', $id_dev, PDO::PARAM_INT);
		$query2->bindValue(':id_cl', $id_cl, PDO::PARAM_INT);
		$query2->bindValue(':term', $term, PDO::PARAM_STR);
		$query2->execute();
		$num2 = $query2->rowCount();

	}
}

include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu-termsbackend-post.php'); 




?>