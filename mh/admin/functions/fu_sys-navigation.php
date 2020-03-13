<?php
if(!isset($CONFIG['user']['pages'])) $CONFIG['user']['pages'] = array(0); 
$NAVIGATION = '';
$navLeft = '';
$navRight = '';

###################################################
/* Menue left*/
###################################################
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.link,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.target
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.parent_id = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menue = (:active)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.position = (:position)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page IN (' . implode(',', $CONFIG['user']['pages']) . ')
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_pages.rank
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->bindValue(':active', 1, PDO::PARAM_INT);
$query->bindValue(':position', 'left', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$navLeft .= '<li class="menueCloseMain"><div>Menu</div></li>';
foreach($rows as $row){
	$query1 = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.page,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.link,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.target
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.parent_id = (:parent)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menue = (:active)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.position = (:position)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page IN (' . implode(',', $CONFIG['user']['pages']) . ')
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_pages.rank
										');
	$query1->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query1->bindValue(':parent', $row['id_page'], PDO::PARAM_INT);
	$query1->bindValue(':active', 1, PDO::PARAM_INT);
	$query1->bindValue(':position', 'left', PDO::PARAM_STR);
	$query1->execute();
	$rows1 = $query1->fetchAll(PDO::FETCH_ASSOC);
	$num1 = $query1->rowCount();

	if($num1 == 0) $rows1[0]['id_page'] = '';
	if($num1 == 1){
		$row['page'] = $rows1[0]['page'];
		$row['link'] = $rows1[0]['link'];
		$row['target'] = $rows1[0]['target'];
	}
	(isset($TEXT[$row['page']])) ? $linktext = $TEXT[$row['page']] : $linktext = $row['page'];
	
	if($row['link'] != ''){
		($row['target'] != '') ? $target = 'target="' . $row['target'] . '"' : $target ='';
		$item = '<li class="menueExtern"><a href="' . $row['link'] . $target . '>' . $linktext . '</a>';
	}else{
		$item = '<li class="menueIntern"><div data-pageid="' . $rows1[0]['id_page'] . '">' . $linktext . '</div>';
	}
	$navLeft .= $item;
	
	// Second level
	$queryS = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.page,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.link,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.target
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.parent_id = (:parent)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menue = (:active)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.position = (:position)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page IN (' . implode(',', $CONFIG['user']['pages']) . ')
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_pages.rank
										');
	$queryS->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryS->bindValue(':parent', $row['id_page'], PDO::PARAM_INT);
	$queryS->bindValue(':active', 1, PDO::PARAM_INT);
	$queryS->bindValue(':position', 'left', PDO::PARAM_STR);
	$queryS->execute();
	$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
	$numS = $queryS->rowCount();
	
	if($numS > 1){
		$navLeft .= '<ul>';
		$navLeft .= '<li class="menueClose"><div>' . $linktext . '</div></li>';
		
		
		
		foreach($rowsS as $rowS){
			(isset($TEXT[$rowS['page']])) ? $linktext = $TEXT[$rowS['page']] : $linktext = $rowS['page'];

			if($rowS['link'] != ''){
				($rowS['target'] != '') ? $target = 'target="' . $rowS['target'] . '"' : $target ='';
				$item = '<li class="menueExtern"><a href="' . $rows1[0]['link'] . $target . '>' . $linktext . '</a>';
			}else{
				$item = '<li class="menueIntern"><div data-pageid="' . $rowS['id_page'] . '">' . $linktext . '</div>';
			}
			$navLeft .= $item;
		}
		$navLeft .= '</ul>';
	}
	$navLeft .= '</li>';
}


###################################################
/* Menue right (Usermenue)*/
###################################################
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.target
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.parent_id = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menue = (:active)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.position = (:position)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page IN (' . implode(',', $CONFIG['user']['pages']) . ')
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_pages.rank
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->bindValue(':active', 1, PDO::PARAM_INT);
$query->bindValue(':position', 'right', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

foreach($rows as $row){
	$linktext = $CONFIG['user']['name'];
	$navRight .= '<li class="menueIntern"><div>' . $linktext . "</div><ul>";
	$navRight .= '<li class="menueClose"><div>' . $linktext . '</div></li>';

	if($CONFIG['system']['useChangeClient'] == 1 && $CONFIG['user']['specifications'][9] == 9 && count($CONFIG['user']['clients']) > 1){
		$navRight .= '<li onclick="changeClient(this)"><div>' . $TEXT['changeClient'] . '</div></li>';
	}
	if($CONFIG['system']['useChangeUser'] == 1 && $CONFIG['user']['specifications'][10] == 9){
		$navRight .= '<li onclick="changeUser(this)"><div>' . $TEXT['changeUser'] . '</div></li>';
	}

	if($CONFIG['system']['useChangeLanguage'] == 1 && $CONFIG['user']['specifications'][11] == 9){
		$navRight .= '<li onclick="changeLanguage(this)"><div>' . $TEXT['changeLanguage'] . '</div></li>';
	}
	if($CONFIG['system']['useSystemConfiguration'] == 1){
		$navRight .= '<li onclick="changeConfiguration(this)"><div>' . $TEXT['systemConfiguration'] . '</div></li>';
	}


	// Second level
	$queryS = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.page,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.link,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.target
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.parent_id = (:parent)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menue = (:active)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.position = (:position)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page IN (' . implode(',', $CONFIG['user']['pages']) . ')
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_pages.rank
										');
	$queryS->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryS->bindValue(':parent', $row['id_page'], PDO::PARAM_INT);
	$queryS->bindValue(':active', 1, PDO::PARAM_INT);
	$queryS->bindValue(':position', 'right', PDO::PARAM_STR);
	$queryS->execute();
	$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
	$numS = $queryS->rowCount();
	
	foreach($rowsS as $rowS){
		(isset($TEXT[$rowS['page']])) ? $linktext = $TEXT[$rowS['page']] : $linktext = $rowS['page'];

		if($rowS['link'] != ''){
			($rowS['target'] != '') ? $target = 'target="' . $rowS['target'] . '"' : $target ='';
			$item = '<li class="menueExtern"><a href="' . $rows1[0]['link'] . $target . '>' . $linktext . '</a>';
		}else{
			$item = '<li class="menueIntern"><div data-pageid="' . $rowS['id_page'] . '">' . $linktext . '</div>';
		}
		$navRight .= $item;
	}

	$navRight .= '<li class="menueLogout"><a id="navLogout" href="' . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=1">' . $TEXT['logout'] . '</a></li></ul></li>';
}



$NAVIGATION = '<ul class="navLeft">' . $navLeft . '</ul>
			   <ul class="navRight">' . $navRight . '</ul>';


?>