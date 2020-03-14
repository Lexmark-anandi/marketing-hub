<?php
if(!isset($CONFIG['USER']['pages'])) $CONFIG['USER']['pages'] = array();
$NAVIGATION = '';
$navLeft = '';
$navRight = '';

###################################################
/* Menue left*/
###################################################
$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.target
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.parent_id = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page IN (' . implode(',', $CONFIG['USER']['pages']) . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menue = (:active)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menuetype = (:type)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_pages.rank
									');
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->bindValue(':active', 1, PDO::PARAM_INT);
$query->bindValue(':type', 'left', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$navLeft .= '<li class="menueCloseMain"><div>Menu</div></li>';
foreach($rows as $row){
	$query1 = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.page,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.link
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.parent_id = (:parent)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page IN (' . implode(',', $CONFIG['USER']['pages']) . ')
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menue = (:active)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menuetype = (:type)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_pages.rank
										');
	$query1->bindValue(':parent', $row['id_page'], PDO::PARAM_INT);
	$query1->bindValue(':active', 1, PDO::PARAM_INT);
	$query1->bindValue(':type', 'left', PDO::PARAM_STR);
	$query1->execute();
	$rows1 = $query1->fetchAll(PDO::FETCH_ASSOC);
	$num1 = $query1->rowCount();

	if($num1 == 1) $row['page'] = $rows1[0]['page'];
	(isset($TEXT[$row['page']])) ? $linktext = $TEXT[$row['page']] : $linktext = $row['page'];
	
	if($row['target'] != ''){
		$item = '<li class="menueExtern"><a href="' . $rows1[0]['link'] . '" target="' . $row['target'] . '">' . $linktext . '</a>';
	}else{
		$item = '<li class="menueIntern"><div data-link="' . $rows1[0]['link'] . '" data-pageid="' . $rows1[0]['id_page'] . '">' . $linktext . '</div>';
	}
	$navLeft .= $item;
	
	// Second level
	$queryS = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.page,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.link,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.target
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.parent_id = (:parent)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page IN (' . implode(',', $CONFIG['USER']['pages']) . ')
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menue = (:active)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menuetype = (:type)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_pages.rank
										');
	$queryS->bindValue(':parent', $row['id_page'], PDO::PARAM_INT);
	$queryS->bindValue(':active', 1, PDO::PARAM_INT);
	$queryS->bindValue(':type', 'left', PDO::PARAM_STR);
	$queryS->execute();
	$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
	$numS = $queryS->rowCount();
	
	if($numS > 1){
		$navLeft .= '<ul>';
		$navLeft .= '<li class="menueClose"><div>' . $linktext . '</div></li>';
		
		
		
		foreach($rowsS as $rowS){
			(isset($TEXT[$rowS['page']])) ? $linktext = $TEXT[$rowS['page']] : $linktext = $rowS['page'];

			if($rowS['target'] != ''){
				$item = '<li class="menueExtern"><a href="' . $rowS['link'] . '" target="' . $rowS['target'] . '">' . $linktext . '</a></li>';
			}else{
				$item = '<li class="menueIntern"><div data-link="' . $rowS['link'] . '" data-pageid="' . $rowS['id_page'] . '">' . $linktext . '</div></li>';
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
$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.page,
										' . $CONFIG['db'][0]['prefix'] . 'system_pages.target
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.parent_id = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page IN (' . implode(',', $CONFIG['USER']['pages']) . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menue = (:active)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menuetype = (:type)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_pages.rank
									');
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->bindValue(':active', 1, PDO::PARAM_INT);
$query->bindValue(':type', 'right', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

foreach($rows as $row){
	$linktext = $CONFIG['USER']['name'];
	$navRight .= '<li class="menueIntern"><div>' . $linktext . "</div><ul>";
	$navRight .= '<li class="menueClose"><div>' . $linktext . '</div></li>';

	if($CONFIG['system']['useChangeClient'] == 1 && $CONFIG['USER']['right_changeclient'] == 9 && count($CONFIG['USER']['clients']) > 1){
		$navRight .= '<li onclick="changeClient()"><div>' . $TEXT['changeClient'] . '</div></li>';
	}
	if($CONFIG['system']['useChangeUser'] == 1 && $CONFIG['USER']['right_changeuser'] == 9){
		$navRight .= '<li onclick="changeUser()"><div>' . $TEXT['changeUser'] . '</div></li>';
	}

	if($CONFIG['system']['useChangeLanguage'] == 1 && $CONFIG['USER']['right_changelanguage'] == 9){
		$navRight .= '<li onclick="changeLanguage()"><div>' . $TEXT['changeLanguage'] . '</div></li>';
	}


	// Second level
	$queryS = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.page,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.link,
											' . $CONFIG['db'][0]['prefix'] . 'system_pages.target
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_pages 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_pages.parent_id = (:parent)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.id_page IN (' . implode(',', $CONFIG['USER']['pages']) . ')
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menue = (:active)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_pages.menuetype = (:type)
										ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_pages.rank
										');
	$queryS->bindValue(':parent', $row['id_page'], PDO::PARAM_INT);
	$queryS->bindValue(':active', 1, PDO::PARAM_INT);
	$queryS->bindValue(':type', 'right', PDO::PARAM_STR);
	$queryS->execute();
	$rowsS = $queryS->fetchAll(PDO::FETCH_ASSOC);
	$numS = $queryS->rowCount();
	
	foreach($rowsS as $rowS){
		(isset($TEXT[$rowS['page']])) ? $linktext = $TEXT[$rowS['page']] : $linktext = $rowS['page'];

		if($rowS['target'] != ''){
			$item = '<li class="menueExtern"><a href="' . $rowS['link'] . '" target="' . $rowS['target'] . '">' . $linktext . '</a></li>';
		}else{
			$item = '<li class="menueIntern"><div data-link="' . $rowS['link'] . '" data-pageid="' . $rowS['id_page'] . '">' . $linktext . '</div></li>';
		}
		$navRight .= $item;
	}

	$navRight .= '<li class="menueLogout"><a id="navLogout" href="' . $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathAdmin'] . 'index.php?logout=1">' . $TEXT['logout'] . '</a></li></ul></li>';
}



$NAVIGATION = '<ul class="navLeft">' . $navLeft . '</ul>
			   <ul class="navRight">' . $navRight . '</ul>';


?>