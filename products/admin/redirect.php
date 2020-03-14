<?php
$CONFIG['system']['pathInclude'] = "../";
$CONFIG['noCheck'] = true;
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');

$aReqURLSearch = explode('#', $_SERVER['REQUEST_URI']);
$aReqURL = explode('/', $aReqURLSearch[0]);

$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.link = (:link)
									');
$query->bindValue(':link', $aReqURL[(count($aReqURL) - 1)], PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

setcookie('refreshpage', $rows[0]['id_page'], 0, '/'.$CONFIG['system']['directorySystem'].$CONFIG['system']['pathAdmin'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], false);

header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['pathPagesAdmin'] . 'p_sys-default.php');
exit();
?>