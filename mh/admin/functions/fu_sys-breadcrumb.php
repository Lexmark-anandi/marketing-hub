<?php
$initCondPage = true;
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();

$aBreadcrumb = json_decode($_SERVER['HTTP_BREADCRUMB'], true);

$BREADCRUMB ='';

$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.parent_id,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.target
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page = (:activePage)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page IN (' . implode(',', $CONFIG['user']['pages']) . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menue = (:active)
									');
$query->bindValue(':activePage', $aBreadcrumb['id_page'], PDO::PARAM_INT);
$query->bindValue(':active', 1, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$queryM = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.target
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page = (:parentPage)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page IN (' . implode(',', $CONFIG['user']['pages']) . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menue = (:active)
									');
$queryM->bindValue(':parentPage', $rows[0]['parent_id'], PDO::PARAM_INT);
$queryM->bindValue(':active', 1, PDO::PARAM_INT);
$queryM->execute();
$rowsM = $queryM->fetchAll(PDO::FETCH_ASSOC);
$numM = $queryM->rowCount();

$queryN = $CONFIG['dbconn'][0]->prepare('
									SELECT COUNT(' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page) AS quant
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.parent_id = (:parentPage)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page IN (' . implode(',', $CONFIG['user']['pages']) . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menue = (:active)
									');
$queryN->bindValue(':parentPage', $rows[0]['parent_id'], PDO::PARAM_INT);
$queryN->bindValue(':active', 1, PDO::PARAM_INT);
$queryN->execute();
$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
$numN = $queryN->rowCount();


(isset($TEXT[$rowsM[0]['page']])) ? $linktext = $TEXT[$rowsM[0]['page']] : $linktext = $rowsM[0]['page'];
if($rowsN[0]['quant'] > 1) $BREADCRUMB .= '<span class="breadmenue" data-modulpath="' . $aBreadcrumb['modulpath'] . '">' . $linktext . '</span>';

(isset($TEXT[$rows[0]['page']])) ? $linktext = $TEXT[$rows[0]['page']] : $linktext = $rows[0]['page'];
$BREADCRUMB .= '<span class="breadmenue" data-modulpath="' . $aBreadcrumb['modulpath'] . '">' . $linktext . '</span>';


echo $BREADCRUMB;

?>