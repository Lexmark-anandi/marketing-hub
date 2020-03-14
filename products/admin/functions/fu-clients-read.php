<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php'); 
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData(json_decode($_POST['data'], true));
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php'); 

$out = array();
$all = array();

$queryD = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_cl_data as id_data,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid as id,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_count,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_lang,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_dev,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.street,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.zip,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.city,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.phone,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.mobile,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.fax,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.email,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.web,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.active AS active_uni,
										' . $CONFIG['db'][0]['prefix'] . 'sys_clients_loc.active
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni 
	
									LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_loc 
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid = ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_loc.id_clid
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_clients_loc.id_count = ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_count OR ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_loc.id_count IS NULL)
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_clients_loc.id_lang = ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_lang OR ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_loc.id_lang IS NULL)
											AND (' . $CONFIG['db'][0]['prefix'] . 'sys_clients_loc.id_dev = ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_dev OR ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_loc.id_dev IS NULL)
	
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid = (:id)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_count, ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_lang, ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_dev
									');
$queryD->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryD->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
$queryD->execute();
$rowsD = $queryD->fetchAll(PDO::FETCH_ASSOC);
$numD = $queryD->rowCount();

$rowsD[0]['id_clid'] = $varSQL['id'];

$aFields = array();
$aFields['yesNo2Text'] = array('active');
$aFields['check2Radio'] = array('active');

$outTmp = array();
$outTmp['client'] = '';
$outTmp['street'] = '';
$outTmp['zip'] = '';
$outTmp['city'] = '';
$outTmp['phone'] = '';
$outTmp['mobile'] = '';
$outTmp['fax'] = '';
$outTmp['email'] = '';
$outTmp['web'] = '';
$outTmp['active_uni'] = 2;
$outTmp['active'] = 2;
$outTmp['activeT'] = '';


$identifier = array('##client');

$addColumns = array();

include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-default-read.php'); 



?>