<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php'); 
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData(json_decode($_POST['data'], true));
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php'); 


$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$table = $CONFIG['db'][0]['prefix'] . 'sys_countries';
$primekey = 'id_countid';
$aFieldsNumbers = array('id_countid', 'id_tz', 'id_fd', 'active', 'tax');

$columns = $table . '_##TYPE##.' . $primekey . ',
			' . $table . '_##TYPE##.country,
			' . $table . '_##TYPE##.code,
			' . $table . '_##TYPE##.code_add,
			' . $table . '_##TYPE##.id_tz,
			' . $table . '_##TYPE##.id_fd,
			' . $table . '_##TYPE##.id_ft,
			' . $table . '_##TYPE##.currency,
			' . $table . '_##TYPE##.tax_name,
			' . $table . '_##TYPE##.tax,
			' . $table . '_##TYPE##.fee_name,
			' . $table . '_##TYPE##.sep_decimal,
			' . $table . '_##TYPE##.sep_thousand,
			' . $table . '_##TYPE##.email_sender,
			' . $table . '_##TYPE##.email_sendername,
			' . $table . '_##TYPE##.active
';

$aFields = array(
					"country"							=>	array('country', "s"),
					"code"								=>	array('code', "s"),
					"code_add"							=>	array('code_add', "s"),
					"id_tz"								=>	array('id_tz', "d"),
					"id_fd"								=>	array('id_fd', "d"),
					"id_ft"								=>	array('id_ft', "d"),
					"currency"							=>	array('currency', "s"),
					"tax_name"							=>	array('tax_name', "s"),
					"tax"								=>	array('tax', "s"),
					"fee_name"							=>	array('fee_name', "s"),
					"sep_decimal"						=>	array('sep_decimal', "s"),
					"sep_thousand"						=>	array('sep_thousand', "s"),
					"email_sender"						=>	array('email_sender', "s"),
					"email_sendername"					=>	array('email_sendername', "s"),
					"active"							=>	array('active', "d"),
					);


##########################################################################

include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-default-insert.php'); 


$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_count2lang
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid = (:id_countid)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid = (:nul)
									');
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->bindValue(':id_countid', $out['id'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

if($num == 0){
	$query = $CONFIG['dbconn']->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages
										(id_countid, id_langid, default_, del)
										VALUES
										(:id_countid, :nul, :nul, :nultime)
										');
	$query->bindValue(':id_countid', $out['id'], PDO::PARAM_INT);
	$query->bindValue(':nul', 0, PDO::PARAM_INT);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->execute();
}else{
	$query = $CONFIG['dbconn']->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages SET
										del = (:nultime)
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_count2lang = (:id_count2lang)
										LIMIT 1
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':id_count2lang', $rows[0]['id_count2lang'], PDO::PARAM_INT);
	$query->execute();
}



function addSave($aArg = array()){
	global $CONFIG, $TEXT;

	$date = new DateTime();
	$now = $date->format('Y-m-d H:i:s');
	
	$query = $CONFIG['dbconn']->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages SET
										del = (:now)
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid = (:id_countid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid <> (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid <> (:nul)
										');
	$query->bindValue(':now', $now, PDO::PARAM_STR);
	$query->bindValue(':nul', 0, PDO::PARAM_INT);
	$query->bindValue(':id_countid', $aArg['id'], PDO::PARAM_INT);
	$query->execute();
	
	if(isset($aArg['languages'])){
		foreach($aArg['languages'] as $lang){
			$query = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid
												FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages 
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid = (:id_countid)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid = (:id_langid)
												');
			$query->bindValue(':id_countid', $aArg['id'], PDO::PARAM_INT);
			$query->bindValue(':id_langid', $lang, PDO::PARAM_INT);
			$query->execute();
			$num = $query->rowCount();
			
			if($num == 0){
				$query = $CONFIG['dbconn']->prepare('
													INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages
													(id_countid, id_langid)
													VALUES
													(:id_countid, :id_langid)
													');
				$query->bindValue(':id_countid', $aArg['id'], PDO::PARAM_INT);
				$query->bindValue(':id_langid', $lang, PDO::PARAM_INT);
				$query->execute();
			}else{
				$query = $CONFIG['dbconn']->prepare('
													UPDATE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages SET
														del = (:nultime)
													WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid = (:id_countid)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid = (:id_langid)
													LIMIT 1
													');
				$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$query->bindValue(':id_countid', $aArg['id'], PDO::PARAM_INT);
				$query->bindValue(':id_langid', $lang, PDO::PARAM_INT);
				$query->execute();
			}
		}
	}
	
	########
	
	$query = $CONFIG['dbconn']->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages SET
											default_ = (:nul)
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid = (:id_countid)
										');
	$query->bindValue(':nul', 0, PDO::PARAM_INT);
	$query->bindValue(':id_countid', $aArg['id'], PDO::PARAM_INT);
	$query->execute();
	
	$query = $CONFIG['dbconn']->prepare('
										UPDATE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages SET
											default_ = (:active)
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid = (:id_countid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid = (:id_langid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.del = (:nultime)
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':active', 1, PDO::PARAM_INT);
	$query->bindValue(':id_countid', $aArg['id'], PDO::PARAM_INT);
	$query->bindValue(':id_langid', $aArg['default_'], PDO::PARAM_INT);
	$query->execute();
}

?>