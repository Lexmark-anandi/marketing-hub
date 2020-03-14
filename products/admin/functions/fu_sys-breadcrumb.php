<?php
$CONFIG['system']['pathInclude'] = "../../";
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData();

$BREADCRUMB = '';

$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.parent_id,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.target
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page = (:activePage)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page IN (' . implode(',', $CONFIG['USER']['pages']) . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menue = (:active)
									');
$query->bindValue(':activePage', $CONFIG['page']['pageId'], PDO::PARAM_INT);
$query->bindValue(':active', 1, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$queryM = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.target
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page = (:parentPage)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page IN (' . implode(',', $CONFIG['USER']['pages']) . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menue = (:active)
									');
$queryM->bindValue(':parentPage', $rows[0]['parent_id'], PDO::PARAM_INT);
$queryM->bindValue(':active', 1, PDO::PARAM_INT);
$queryM->execute();
$rowsM = $queryM->fetchAll(PDO::FETCH_ASSOC);
$numM = $queryM->rowCount();

$queryN = $CONFIG['dbconn']->prepare('
									SELECT COUNT(' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page) as quant
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.parent_id = (:parentPage)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page IN (' . implode(',', $CONFIG['USER']['pages']) . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menue = (:active)
									');
$queryN->bindValue(':parentPage', $rows[0]['parent_id'], PDO::PARAM_INT);
$queryN->bindValue(':active', 1, PDO::PARAM_INT);
$queryN->execute();
$rowsN = $queryN->fetchAll(PDO::FETCH_ASSOC);
$numN = $queryN->rowCount();


(isset($TEXT[$rowsM[0]['page']])) ? $linktext = $TEXT[$rowsM[0]['page']] : $linktext = $rowsM[0]['page'];
if($rowsN[0]['quant'] > 1) $BREADCRUMB .= '<span class="breadmenue">' . $linktext . '</span>';

(isset($TEXT[$rows[0]['page']])) ? $linktext = $TEXT[$rows[0]['page']] : $linktext = $rows[0]['page'];
$BREADCRUMB .= '<span class="breadmenue">' . $linktext . '</span>';




//                <span id="nav21" class="breadmenue" data-link="../../admin/pages/p-masterdata.php">Personen</span>
//                <span id="nav22" class="breadmenue" data-link="../../admin/pages/p-masterdata.php">Stammdaten</span>
//                <span class="breaddataset">Hebbel, Thomas</span>
//                <span class="breadmenue">Veranstaltungen</span>
//                <span class="breaddataset">Unternehmertum der Zukunft / Social Entrepreneurship</span>


echo $BREADCRUMB;

?>