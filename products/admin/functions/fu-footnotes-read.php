<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php'); 
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData(json_decode($_POST['data'], true));
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php'); 

$out = array();
$all = array();

$queryD = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_footnotes_uni.id_fn_data as id_data,
										' . $CONFIG['db'][0]['prefix'] . 'sys_footnotes_uni.id_fnid as id,
										' . $CONFIG['db'][0]['prefix'] . 'sys_footnotes_uni.id_count,
										' . $CONFIG['db'][0]['prefix'] . 'sys_footnotes_uni.id_lang,
										' . $CONFIG['db'][0]['prefix'] . 'sys_footnotes_uni.id_dev,
										' . $CONFIG['db'][0]['prefix'] . 'sys_footnotes_uni.id_clid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_footnotes_uni.marking,
										' . $CONFIG['db'][0]['prefix'] . 'sys_footnotes_uni.footnote
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_footnotes_uni 
	
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_footnotes_uni.del = (:nultime)
										AND (' . $CONFIG['db'][0]['prefix'] . 'sys_footnotes_uni.id_clid = (:clid)
											OR ' . $CONFIG['db'][0]['prefix'] . 'sys_footnotes_uni.id_clid = (:nul))
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_footnotes_uni.id_fnid = (:id)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_footnotes_uni.id_count, ' . $CONFIG['db'][0]['prefix'] . 'sys_footnotes_uni.id_lang, ' . $CONFIG['db'][0]['prefix'] . 'sys_footnotes_uni.id_dev
									');
$queryD->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryD->bindValue(':clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
$queryD->bindValue(':nul', 0, PDO::PARAM_INT);
$queryD->bindValue(':id', $varSQL['id'], PDO::PARAM_INT);
$queryD->execute();
$rowsD = $queryD->fetchAll(PDO::FETCH_ASSOC);
$numD = $queryD->rowCount();

$aFields = array();
$aFields['yesNo2Text'] = array();
$aFields['check2Radio'] = array();

$outTmp = array();
$outTmp['marking'] = '';
$outTmp['footnote'] = '';

$identifier = array('##marking');

$addColumns = array();

include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-default-read.php'); 



?>