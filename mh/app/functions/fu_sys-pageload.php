<?php
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-app.php');
$varSQL = getPostData();

$date = new DateTime(); 
$now = $date->format('Y-m-d H:i:s');

$out = array();
$out['contentLeft'] = '';
$out['contentRight'] = '';
$out['contentPager'] = '';
$out['ovNumPages'] = '';


#######################################################
// list templates for asset categories
#######################################################
if(is_numeric($CONFIG['activeSettings']['id_page']) || $CONFIG['activeSettings']['id_page'] == 'myassets'){
	$conBsdTemp = '';
	$condSearch = '';
	$condProm = '';
	$condCat = '';
	$condCatProm = '';
	$condPP = ''; 
	$group = ''; 
	$order = ''; 
	$join = 'INNER';
	if(is_numeric($CONFIG['activeSettings']['id_page'])){
		// config categories
		$condCat = 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid = (:id_caid)';
		$condProm = 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid = 0';
		$condProm = 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_campid = 0';
		$asCat = $CONFIG['activeSettings']['id_page'];
		$group = $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid';
		
		//$order = $CONFIG['db'][0]['prefix'] . '_templates2countries_.published_at DESC';
		$order = $CONFIG['db'][0]['prefix'] . '_templates_uni.title ' . $CONFIG['activeSettings']['ovOrderDir'] . ' ';
		if($CONFIG['activeSettings']['ovOrder'] == 'category') $order = $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.category ' . $CONFIG['activeSettings']['ovOrderDir'] . ' ';
		if($CONFIG['activeSettings']['ovOrder'] == 'subcategory') $order = $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.category ' . $CONFIG['activeSettings']['ovOrderDir'] . ', ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.category ' . $CONFIG['activeSettings']['ovOrderDir'] . ' ';

		$conBsdTemp = 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.bsd_only = 2 ';
                if($CONFIG['user']['bsd'] == 1) $conBsdTemp = 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.bsd_only IN (2,1) ';
                if($CONFIG['user']['distri'] == 1) $conBsdTemp = 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.bsd_only IN (2,3) ';

	}else{
		// config my assets
		$condPP = 'AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_ppid = (:id_ppid)';
		$group = $CONFIG['db'][0]['prefix'] . '_assets_uni.id_asid';
		//$order = $CONFIG['db'][0]['prefix'] . '_assets_uni.create_at DESC';
		$order = $CONFIG['db'][0]['prefix'] . '_assets_uni.title ' . $CONFIG['activeSettings']['ovOrderDir'] . ' ';
		$join = 'LEFT';
	}
	
	if(isset($varSQL['asset_category']) && is_numeric($varSQL['asset_category'])){
		$condCat = 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid = (:id_caid) ';
		$condCat .= 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid = 0 ';
		$condCat .= 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_campid = 0 ';
		$asCat = $varSQL['asset_category'];
	}
	if(isset($varSQL['asset_category']) && $varSQL['asset_category'] == 'promotions'){
		$condCatProm = 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid <> 0 ';
		$condCatProm .= 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_campid = 0 ';
	}
	if(isset($varSQL['asset_category']) && $varSQL['asset_category'] == 'campaigns'){
		$condCatProm = 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid = 0 ';
		$condCatProm .= 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_campid <> 0 ';
	}
	
	if($CONFIG['activeSettings']['id_page'] == 'myassets'){
		if(isset($varSQL['searchfield']) && $varSQL['searchfield'] != ''){
			$condSearch = 'AND (' . $CONFIG['db'][0]['prefix'] . '_assets_uni.title LIKE (:search) ';
			$condSearch .= 'OR ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.category LIKE (:search) ';
			$condSearch .= 'OR ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.category LIKE (:search) ';
			$condSearch .= 'OR ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.category LIKE (:search)) ';
		}
	}else{
		if(isset($varSQL['searchfield']) && $varSQL['searchfield'] != ''){
			$condSearch = 'AND (' . $CONFIG['db'][0]['prefix'] . '_templates_uni.title LIKE (:search) ';
			$condSearch .= 'OR ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.category LIKE (:search) ';
			$condSearch .= 'OR ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.category LIKE (:search)) ';
		}
	}

	
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
										
										' . $join . ' JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_
											ON ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_countid = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_langid = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.published_at < (:now)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.published_at <> (:nultime)
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.del = (:nultime)
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_assets_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
												AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.del = (:nultime)
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_caid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.active = (:active)
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.id_cssid = ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_cssid
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.active = (:active)
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.id_cbid = ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_cbid
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.active = (:active)

										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.startdate < (:now)
											AND (' . $CONFIG['db'][0]['prefix'] . '_templates_uni.enddate > (:now)
												OR ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.enddate = (:nultime))
											' . $condSearch . '
											' . $condProm . '
											' . $condCat . '
											' . $condCatProm . '
											' . $condPP . '
											' . $conBsdTemp . '
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
										GROUP BY ' . $group . '
										');
	$query->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	if($condSearch != '') $query->bindValue(':search', '%' . $varSQL['searchfield'] . '%', PDO::PARAM_STR);
	if($condCat != '') $query->bindValue(':id_caid', $asCat, PDO::PARAM_INT);
	if($condPP != '') $query->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$query->bindValue(':now', $now, PDO::PARAM_STR);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':active', 1, PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	$out['ovNumPages'] = ceil($num / $CONFIG['activeSettings']['ovRange']);
	
	$limitStart = ($CONFIG['activeSettings']['ovPage'] * $CONFIG['activeSettings']['ovRange']) - $CONFIG['activeSettings']['ovRange'];
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid,
											' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_promid,
											' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_campid,
											' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid,
											' . $CONFIG['db'][0]['prefix'] . '_templates_uni.title,
											' . $CONFIG['db'][0]['prefix'] . '_templates_uni.thumbnail,
											' . $CONFIG['db'][0]['prefix'] . '_templates_uni.preview_thumbnail,
											' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_asid,
											' . $CONFIG['db'][0]['prefix'] . '_assets_uni.title AS title_asset,
											' . $CONFIG['db'][0]['prefix'] . '_assets_uni.thumbnail AS thumbnail_asset,
											' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.category,
											' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.category AS subcatSpecsheet,
											' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.category AS subcatBrochure,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
										
										' . $join . ' JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_
											ON ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_countid = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_langid = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.published_at < (:now)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.published_at <> (:nultime)
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.del = (:nultime)
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_assets_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
												AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_pcid = (:id_pcid)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.id_ppid = (:id_ppid)
												AND ' . $CONFIG['db'][0]['prefix'] . '_assets_uni.del = (:nultime)
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_caid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.active = (:active)
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.id_cssid = ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_cssid
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.active = (:active)
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.id_cbid = ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_cbid
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_brochures_uni.active = (:active)
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.preview_thumbnail
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.startdate < (:now)
											AND (' . $CONFIG['db'][0]['prefix'] . '_templates_uni.enddate > (:now)
												OR ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.enddate = (:nultime))
											' . $condSearch . '
											' . $condProm . '
											' . $condCat . '
											' . $condCatProm . '
											' . $condPP . '
											' . $conBsdTemp . '
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
										GROUP BY ' . $group . '
										ORDER BY ' . $order . '
										LIMIT ' . $limitStart . ', ' . $CONFIG['activeSettings']['ovRange'] . '
										');
	$query->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	if($condSearch != '') $query->bindValue(':search', '%' . $varSQL['searchfield'] . '%', PDO::PARAM_STR);
	if($condCat != '') $query->bindValue(':id_caid', $asCat, PDO::PARAM_INT);
	$query->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
	$query->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$query->bindValue(':now', $now, PDO::PARAM_STR);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':active', 1, PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	

//			$arr = $query->errorInfo();
//			print_r($arr);


	$classOvTab = 'ovCat_' . $asCat;
	if($CONFIG['activeSettings']['id_page'] == 'myassets') $classOvTab = 'myassets';
	if($CONFIG['activeSettings']['id_page'] == 'promotions') $classOvTab = 'promotions';
	if($CONFIG['activeSettings']['id_page'] == 'campaigns') $classOvTab = 'campaigns';
	
	if($num > 0){
		$out['contentLeft'] .= '<div class="ovTab ' . $classOvTab . '">';
		$out['contentLeft'] .= '<div class="ovThead">';
		$out['contentLeft'] .= '<div class="ovBoxOuter">';
		$out['contentLeft'] .= '<div class="ovBoxImg"></div>';
		$out['contentLeft'] .= '<div class="ovBoxHead ovOrder" data-order="title">' . $TEXT['ovTitle'] . ' <i class="icon"></i></div>';
		$out['contentLeft'] .= '<div class="ovBoxCategory ovOrder" data-order="category">' . $TEXT['ovCategory'] . ' <i class="icon"></i></div>';
		$out['contentLeft'] .= '<div class="ovBoxSubcategory ovOrder" data-order="subcategory">' . $TEXT['ovSubcategory'] . ' <i class="icon"></i></div>';
		$out['contentLeft'] .= '<div class="ovBoxButtons"></div>';
		$out['contentLeft'] .= '<div class="ovBoxButtonsList"></div>';
		$out['contentLeft'] .= '<div class="ovBoxScrolldiff"></div>';
		$out['contentLeft'] .= '</div>';
		$out['contentLeft'] .= '</div>';
		$out['contentLeft'] .= '<div class="ovTbody">';
		
		foreach($rows as $row){
			$category = $row['category'];
			if($row['id_promid'] > 0) $category = $TEXT['promotions'];
			if($row['id_campid'] > 0) $category = $TEXT['campaigns'];
			
			$row['id_caid'] = $row['id_caid'];
			if($row['id_promid'] > 0) $row['id_caid'] = 'promotions';
			if($row['id_campid'] > 0) $row['id_caid'] = 'campaigns';
			
			$textedit = $TEXT['create'];
			$textpreview = $TEXT['preview'];
			$actionedit = 'create';
			$actionpreview = 'preview';
			$iconpreview = 'icon-enter_full_screen';
			$buttonEx = '<button type="button" class="buttonAll buttonBig buttonRight buttonFixWidth100" data-action="' . $actionpreview . '">' . $textpreview . '</button>';
			$buttonEdit = '<button type="button" class="buttonAll buttonBig buttonGreen buttonFixWidth100" data-action="' . $actionedit . '">' . $textedit . '</button>';

			if($CONFIG['activeSettings']['id_page'] == 'myassets'){
				$row['title'] = ($row['title_asset'] != '') ? $row['title_asset'] : $row['title'];
				$textedit = $TEXT['edit'];
				$textpreview = $TEXT['export'];
				$actionpreview = 'export';
				$iconpreview = 'icon-export';
				
				if(in_array($row['id_caid'], $CONFIG['system']['exportExCobranding'])){
					$buttonEx = '<div class="buttongroup buttongroupRight">
								  <button type="button" class="buttonAll buttonBig" data-action="' . $actionpreview . '">' . $textpreview . '</button>
								  <button type="button" class="buttonAll buttonBig buttonDropdown" data-action="exportsplit"><span class="buttonCaret"></span></button>
								  <ul class="buttonDropdownMenu" >
								  	<li onclick="exportExCobranding(this)">' .$TEXT['exportExCobranding'] . '</li>
								  	<li onclick="checkDelete(this)">' .$TEXT['delete'] . '</li>
								  </ul>
								</div>';
				}else{
					$buttonEx = '<div class="buttongroup buttongroupRight">
								  <button type="button" class="buttonAll buttonBig" data-action="' . $actionpreview . '">' . $textpreview . '</button>
								  <button type="button" class="buttonAll buttonBig buttonDropdown" data-action="exportsplit"><span class="buttonCaret"></span></button>
								  <ul class="buttonDropdownMenu" >
								  	<li onclick="checkDelete(this)">' .$TEXT['delete'] . '</li>
								  </ul>
								</div>';
				}
				$buttonEdit = '<button type="button" class="buttonAll buttonBig buttonGreen buttonFixWidth100" data-action="' . $actionedit . '">' . $textedit . '</button>';
				
			}else{
				$row['id_asid'] = 0;
				
				if(in_array($row['id_caid'], $CONFIG['system']['exportExCobranding'])){
					$buttonEdit = '<div class="buttongroup buttongroupLeft">
								  <button type="button" class="buttonAll buttonBig buttonGreen" data-action="' . $actionedit . '">' . $textedit . '</button>
								  <button type="button" class="buttonAll buttonBig buttonGreen buttonDropdown" data-action="exportsplit"><span class="buttonCaret"></span></button>
								  <ul class="buttonDropdownMenu">
								  	<li onclick="exportExCobranding(this)">' .$TEXT['exportExCobranding'] . '</li>
								  </ul>
								</div>';
				}
				
			}
			
			$thumb = '';
			if($CONFIG['activeSettings']['id_page'] == 'myassets'){
				$dirTarget = 'assetimages/assets_thumbnails/';
				$thumbnail = str_pad($row['id_asid'], 6 ,'0', STR_PAD_LEFT) . '-' . $CONFIG['user']['id_countid'] . '-' . $CONFIG['user']['id_langid'] . '-' . md5($row['id_asid'] . '_asset') . '-1.png'; 
				
				if(!file_exists($CONFIG['system']['directoryRoot'] . $dirTarget . $thumbnail) || $thumbnail == '' || $row['id_caid'] == 1){
					$dirTarget = 'assetimages/templates_thumbnails/';
					$thumbnail = str_pad($row['id_tempid'], 6 ,'0', STR_PAD_LEFT) . '-' . $CONFIG['user']['id_countid'] . '-' . $CONFIG['user']['id_langid'] . '-' . md5($row['id_tempid'] . '_template') . '-1.png'; 
					
					if($row['filesys_filename'] != ''){
						if(file_exists($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'] . $row['filesys_filename'])){
							$dirTarget = $CONFIG['system']['pathMedia'];
							$thumbnail = $row['filesys_filename']; 
						}
					}

					if($row['id_promid'] > 0){
						$queryX = $CONFIG['dbconn'][0]->prepare('
															SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename
															FROM ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni
															
															LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
																ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
																	AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
																	AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid = ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.preview_thumbnail
															
															WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_count = (:id_count)
																AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_lang = (:id_lang)
																AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid = (:id_promid)
															');
						$queryX->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
						$queryX->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
						$queryX->bindValue(':id_promid', $row['id_promid'], PDO::PARAM_INT);
						$queryX->execute();
						$rowsX = $queryX->fetchAll(PDO::FETCH_ASSOC);
						$numX = $queryX->rowCount();

						$thumbnail = 'p' . str_pad($row['id_promid'], 6 ,'0', STR_PAD_LEFT) . '-' . $CONFIG['user']['id_countid'] . '-' . $CONFIG['user']['id_langid'] . '-' . md5($row['id_promid'] . '_promotion') . '-1.png'; 

						if($rowsX[0]['filesys_filename'] != ''){
							if(file_exists($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'] . $rowsX[0]['filesys_filename'])){
								$dirTarget = $CONFIG['system']['pathMedia'];
								$thumbnail = $rowsX[0]['filesys_filename']; 
							}
						}
					}

					if($row['id_campid'] > 0){
						$queryX = $CONFIG['dbconn'][0]->prepare('
															SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename
															FROM ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni
															
															LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
																ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
																	AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
																	AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid = ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.preview_thumbnail
															
															WHERE ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_count = (:id_count)
																AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_lang = (:id_lang)
																AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid = (:id_campid)
															');
						$queryX->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
						$queryX->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
						$queryX->bindValue(':id_campid', $row['id_campid'], PDO::PARAM_INT);
						$queryX->execute();
						$rowsX = $queryX->fetchAll(PDO::FETCH_ASSOC);
						$numX = $queryX->rowCount();

						$thumbnail = 'c' . str_pad($row['id_campid'], 6 ,'0', STR_PAD_LEFT) . '-' . $CONFIG['user']['id_countid'] . '-' . $CONFIG['user']['id_langid'] . '-' . md5($row['id_campid'] . '_campaign') . '-1.png'; 

						if($rowsX[0]['filesys_filename'] != ''){
							if(file_exists($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'] . $rowsX[0]['filesys_filename'])){
								$dirTarget = $CONFIG['system']['pathMedia'];
								$thumbnail = $rowsX[0]['filesys_filename']; 
							}
						}
					}
				}
			}else{
				$dirTarget = 'assetimages/templates_thumbnails/';
				$thumbnail = str_pad($row['id_tempid'], 6 ,'0', STR_PAD_LEFT) . '-' . $CONFIG['user']['id_countid'] . '-' . $CONFIG['user']['id_langid'] . '-' . md5($row['id_tempid'] . '_template') . '-1.png'; 
				
				if($row['filesys_filename'] != ''){
					if(file_exists($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'] . $row['filesys_filename'])){
						$dirTarget = $CONFIG['system']['pathMedia'];
						$thumbnail = $row['filesys_filename']; 
					}
				}
			}
			if(file_exists($CONFIG['system']['directoryRoot'] . $dirTarget . $thumbnail)){
				$thumb = '<img src="'.$CONFIG['system']['directoryInstallation'] . $dirTarget . $thumbnail . '?t=' . time() . '">';
			}
			
			
			
			

			$out['contentLeft'] .= '
				<div class="ovBoxOuter" data-promid="' . $row['id_promid'] . '" data-campid="' . $row['id_campid'] . '" data-tempid="' . $row['id_tempid'] . '" data-asid="' . $row['id_asid'] . '" data-caid="' . $row['id_caid'] . '">
					<div class="ovBoxImg"><div class="ovImg">' . $thumb . '</div></div>
					<div class="ovBoxHead">' . $row['title'] . '</div> 
					<div class="ovBoxCategory">' . $category . '</div>
					<div class="ovBoxSubcategory">' . $row['subcatSpecsheet'] . '' . $row['subcatBrochure'] . '</div>
					<div class="ovBoxButtons">
						' . $buttonEdit . '
						' . $buttonEx . '
					</div>
					<div class="ovBoxButtonsList">
						<button type="button" class="" data-action="' . $actionedit . '" title="' . $textedit . '"><span class="icon icon-single_annotation"></span></button>
						';
			$out['contentLeft'] .= '<button type="button" class="" data-action="' . $actionpreview . '" title="' . $textpreview . '"><span class="icon ' . $iconpreview . '"></span></button>';
			
			if(in_array($row['id_caid'], $CONFIG['system']['exportExCobranding'])){
				$out['contentLeft'] .= '<button type="button" class="" data-action="download" title="' . $TEXT['exportExCobranding'] . '"><span class="icon icon-download"></span></button>';
			}else if($CONFIG['activeSettings']['id_page'] == 'myassets'){
				$out['contentLeft'] .= '<button type="button" class="" data-action="" title=""><span class="icon icon-download" style="opacity:0"></span></button>';
			}
			
			if($CONFIG['activeSettings']['id_page'] == 'myassets'){
				$out['contentLeft'] .= '<button type="button" class="" data-action="delete" title="' . $TEXT['delete'] . '"><span class="icon icon-delete"></span></button>';
			}
			$out['contentLeft'] .= '
					</div>
				</div>
				';
		}

		$out['contentLeft'] .= '</div>';
		$out['contentLeft'] .= '</div>';
	}else{
		$out['contentLeft'] = '<div>' . $TEXT['noAssetsFound'] . '</div>';
	}

	
	$out['contentRight'] = '
			<div class="buttongroup buttongroupRight buttonSwitchOverview">
				<button type="button" class="buttonAll buttonBlue" data-overview="grid">' . $TEXT['Grid'] . '<span class="icon icon-gallery"></span></button>
				<button type="button" class="buttonAll buttonBlue" data-overview="list">' . $TEXT['List'] . '<span class="icon icon-list"></span></button>
			</div>
			
			<div class="searchOuter">
				<div class="search">
					<input type="text" name="searchfield" id="searchfield" class="textfieldSearch" placeholder="' . $TEXT['placeholderSearch'] . '"><span class="icon icon-search"></span>
				</div>
			</div>
			';
			
	if($CONFIG['activeSettings']['id_page'] == 'myassets'){
		$queryAC = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_caid,
												' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.category
											FROM ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni

											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
												ON ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid = ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_caid
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.published_at < (:now)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.published_at <> (:nultime)
													' . $conBsdTemp . '
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_
												ON ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_countid = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
													AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.del = (:nultime)
									
	
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.is_standalone = (:one)
												AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.active = (:one)
											GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_caid
											ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.rank
											');
		$queryAC->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
		$queryAC->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
		$queryAC->bindValue(':one', 1, PDO::PARAM_INT);
		$queryAC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryAC->bindValue(':now', $now, PDO::PARAM_STR);
		$queryAC->execute();
		$rowsAC = $queryAC->fetchAll(PDO::FETCH_ASSOC);
		$numAC = $queryAC->rowCount();
		
		$listAC = '<option value="">' . $TEXT['selectCategory'] . '</option>';
		if($varSQL['hasCampaign'] == 1) $listAC .= '<option value="campaigns">' . $TEXT['campaigns'] . '</option>';
		if($varSQL['hasPromotion'] == 1) $listAC .= '<option value="promotions">' . $TEXT['promotions'] . '</option>';
		foreach($rowsAC as $rowAC){
			$listAC .= '<option value="' . $rowAC['id_caid'] . '">' . $rowAC['category'] . '</option>';
		}

		$out['contentRight'] .= '
				<div class="searchOuter">
					<div class="search">
						<select name="asset_category" id="asset_category" class="textfieldSearch">
							' . $listAC . '
						</select>
					</div>
				</div>
				';
	}
			
	$out['contentRight'] .= '</div>';
	
	
	
	$listRange = '';
	foreach($CONFIG['system']['ovRange'] as $range){
		$listRange .= '<option value="' . $range . '">' . $range . '</option>';
	}
	
	$listPages = '';
	for($i = 1; $i <= $out['ovNumPages']; $i++){
		$listPages .= '<option value="' . $i . '">' . $i . '</option>';
	}
	
	$out['contentRight'] .= '
		<div id="ovPagerOuter">
			<div class="pagewidth">
				<div class="ovPagerRange">
					<select name="pagingRange" id="pagingRange" class="pagingPage" onchange="changePager(\'range\')">
						' . $listRange . '
					</select>
				</div>
				<div class="ovPagerPaging">
					<div class="ovPagerButton pagingPrev" onclick="changePager(\'prev\')"><span class="icon icon-caret_circle_left"></span></div>
					<div class="ovPagerPages">
						<select name="pagingPage" id="pagingPage" class="pagingPage" onchange="changePager(\'page\')">
							' . $listPages . '
						</select> / <span class="ovNumPages"></span>
					</div>
					<div class="ovPagerButton pagingNext" onclick="changePager(\'next\')"><span class="icon icon-caret_circle_right"></span></div>
			</div>
		</div>
	';
}


#######################################################
// form company profile
#######################################################
if($CONFIG['activeSettings']['id_page'] == 'profile'){
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.company_name,
											' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.address1,
											' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.zipcode,
											' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.city,
											' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.phone,
											' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.url,
											' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.email,
											' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.logo,
											' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.hide_company_name,
											' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.hide_address1,
											' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.hide_zipcode,
											' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.hide_city,
											' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.hide_phone,
											' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.hide_url,
											' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.hide_email,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filename,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filehash,
											' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename
										FROM ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni
	
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
											ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.logo = ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid

										WHERE ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_pcid = (:id_pcid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.del = (:nultime)
										');
	$query->bindValue(':id_count', 0, PDO::PARAM_INT);
	$query->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$query->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();

	$queryP = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.firstname,
											' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.lastname,
											' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.contactname,
											' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.hide_contactname,
											' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.phone,
											' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.email,
											' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.hide_phone,
											' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.hide_email
										FROM ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.id_ppid = (:id_ppid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.del = (:nultime)
										');
	$queryP->bindValue(':id_count', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$queryP->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryP->execute();
	$rowsP = $queryP->fetchAll(PDO::FETCH_ASSOC);
	$numP = $queryP->rowCount();
	
	$rows[0]['contactname'] = $rowsP[0]['contactname'];
	if($rows[0]['contactname'] == '') $rows[0]['contactname'] = $rowsP[0]['firstname'] . ' ' . $rowsP[0]['lastname'];
	$rows[0]['hide_contactname'] = $rowsP[0]['hide_contactname'];
	$rows[0]['phone'] = $rowsP[0]['phone'];
	$rows[0]['email'] = $rowsP[0]['email'];
	$rows[0]['hide_phone'] = $rowsP[0]['hide_phone'];
	$rows[0]['hide_email'] = $rowsP[0]['hide_email'];
	
	$logo_thumbnail = '';
	$aArgsPic['id_count'] = 0;
	$aArgsPic['id_lang'] = 0;
	$aArgsPic['id_dev'] = 0;
	$aArgsPic['id_mid'] = $rows[0]['logo'];
	$aArgsPic['pathOrg'] = $CONFIG['system']['pathMedia'];
	$aArgsPic['fileOrg'] = $rows[0]['filesys_filename'];
	$aArgsPic['pathNew'] = $CONFIG['system']['pathAssets'];
	$aArgsPic['filehash'] = $rows[0]['filehash'];
	$aArgsPic['id_pf'] = 4;
	$aArgsPic['onlyShrink'] = 'Y';
	$aArgsPic['sizing'] = 'Y';
	
	$pic = pictureSize($aArgsPic);
	if(file_exists($CONFIG['system']['directoryRoot'] . $pic)){
		$info = getimagesize($CONFIG['system']['directoryRoot']  .$pic);
		$logo_thumbnail = '<img src="'.$CONFIG['system']['directoryInstallation'] . $pic.'" width="' . $info[0] . '" height="' . $info[1] . '">';
	}
	
	$checkHide1 = '';
	if($rows[0]['hide_company_name'] == 1) $checkHide1 = 'checked';
	$checkHide2 = '';
	if($rows[0]['hide_address1'] == 1) $checkHide2 = 'checked';
	$checkHide3 = '';
	if($rows[0]['hide_zipcode'] == 1) $checkHide3 = 'checked';
	$checkHide4 = '';
	if($rows[0]['hide_city'] == 1) $checkHide4 = 'checked';
	$checkHide5 = '';
	if($rows[0]['hide_phone'] == 1) $checkHide5 = 'checked';
	$checkHide6 = '';
	if($rows[0]['hide_email'] == 1) $checkHide6 = 'checked';
	$checkHide7 = '';
	if($rows[0]['hide_url'] == 1) $checkHide7 = 'checked';
	$checkHide8 = '';
	if($rows[0]['hide_contactname'] == 1) $checkHide8 = 'checked';
		
	
	$out['contentLeft'] = '
		<div class="profileform">
			<form action="" method="" name="formProfile" id="formProfile" class="profileForm">
				<h1>' . $TEXT['companyInformation'] . '</h1>
				<p style="height:50px">' . $TEXT['profileIntro'] . '</p>
				<div class="formRow">
					<div class="formLabel"><label for="company_name">' . $TEXT['Company'] . '</label></div>
					<div class="formField"><input type="text" class="textfield" name="company_name" id="company_name" value="' . $rows[0]['company_name'] . '"><input type="checkbox" class="" name="hide_company_name" id="hide_company_name" value="1" ' . $checkHide1 . '> <label for="hide_company_name">' . $TEXT['hideexport'] . '</label></div>
				</div>
				<div class="formRow">
					<div class="formLabel"><label for="contactname">' . $TEXT['contactname'] . '</label></div>
					<div class="formField"><input type="text" class="textfield" name="contactname" id="contactname" value="' . $rows[0]['contactname'] . '"><input type="checkbox" class="" name="hide_contactname" id="hide_contactname" value="1" ' . $checkHide8 . '> <label for="hide_contactname">' . $TEXT['hideexport'] . '</label></div>
				</div>
				<div class="formRow">
					<div class="formLabel"><label for="address1">' . $TEXT['Street'] . '</label></div>
					<div class="formField"><input type="text" class="textfield" name="address1" id="address1" value="' . $rows[0]['address1'] . '"><input type="checkbox" class="" name="hide_address1" id="hide_address1" value="1" ' . $checkHide2 . '> <label for="hide_address1">' . $TEXT['hideexport'] . '</label></div>
				</div>
				<div class="formRow">
					<div class="formLabel"><label for="zipcode">' . $TEXT['Zip'] . '</label></div>
					<div class="formField"><input type="text" class="textfield" name="zipcode" id="zipcode" value="' . $rows[0]['zipcode'] . '"><input type="checkbox" class="" name="hide_zipcode" id="hide_zipcode" value="1" ' . $checkHide3 . '> <label for="hide_zipcode">' . $TEXT['hideexport'] . '</label></div>
				</div>
				<div class="formRow">
					<div class="formLabel"><label for="city">' . $TEXT['City'] . '</label></div>
					<div class="formField"><input type="text" class="textfield" name="city" id="city" value="' . $rows[0]['city'] . '"><input type="checkbox" class="" name="hide_city" id="hide_city" value="1" ' . $checkHide4 . '> <label for="hide_city">' . $TEXT['hideexport'] . '</label></div>
				</div>
				<div class="formRow">
					<div class="formLabel"><label for="phone">' . $TEXT['Phone'] . '</label></div>
					<div class="formField"><input type="text" class="textfield" name="phone" id="phone" value="' . $rows[0]['phone'] . '"><input type="checkbox" class="" name="hide_phone" id="hide_phone" value="1" ' . $checkHide5 . '> <label for="hide_phone">' . $TEXT['hideexport'] . '</label></div>
				</div>
				<div class="formRow">
					<div class="formLabel"><label for="email">' . $TEXT['Email'] . '</label></div>
					<div class="formField"><input type="text" class="textfield" name="email" id="email" value="' . $rows[0]['email'] . '"><input type="checkbox" class="" name="hide_email" id="hide_email" value="1" ' . $checkHide6 . '> <label for="hide_email">' . $TEXT['hideexport'] . '</label></div>
				</div>
				<div class="formRow">
					<div class="formLabel"><label for="url">' . $TEXT['URL'] . '</label></div>
					<div class="formField"><input type="text" class="textfield" name="url" id="url" value="' . $rows[0]['url'] . '"><input type="checkbox" class="" name="hide_url" id="hide_url" value="1" ' . $checkHide7 . '> <label for="hide_url">' . $TEXT['hideexport'] . '</label></div>
				</div>
				<div class="formRow formRowButton">
					<div class="formmessage"></div>
					<div class="buttongroup buttongroupRight">
						<button type="button" class="buttonAll buttonBig buttonGreen buttonFixWidth100" onclick="sendFormProfile()">' . $TEXT['save'] . '</button>
					</div>
				</div>
			</form>
		</div>
		';
	
	$out['contentRight'] = '
		<div class="profileform">
			<form action="" enctype="multipart/form-data" name="profileLogo" id="profileLogo" class="profileForm">
				<h1>' . $TEXT['companyLogo'] . '</h1>
				<p style="height:50px">&nbsp;</p>
				<div class="formRow rowLogo">
					<div class="formLabel"><label for="partner_logo">'.$TEXT['partner_logo'].'</label></div>
					<div class="formField">
						<div id="logo_thumbnail">' . $logo_thumbnail . '</div>
						<div name="partner_logo" id="partner_logo" class="formTextfield h80 dropzone"></div>
					</div>
				</div>
				<div class="formRow">
					<div class="formmessage" style="text-align:left"></div>
				</div>
			</form>
		</div>
		';
	
}




#######################################################
// list promotions
#######################################################
if($CONFIG['activeSettings']['id_page'] == 'promotions'){
	$condSearch = '';
	$condCat = '';
	$condPP = '';
	$group = '';
	$order = '';

	$conBsdProm = 'AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.bsd_only = 2 ';
        if($CONFIG['user']['bsd'] == 1) $conBsdProm = 'AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.bsd_only IN (2,1) ';
        if($CONFIG['user']['distri'] == 1) $conBsdProm = 'AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.bsd_only IN (2,3) ';

	if(isset($varSQL['searchfield']) && $varSQL['searchfield'] != ''){
		$condSearch = 'AND (' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.title LIKE (:search)) ';
	}


	$asCat = $CONFIG['activeSettings']['id_page'];
	$group = $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid';
	//$order = $CONFIG['db'][0]['prefix'] . '_promotions2countries_.published_at DESC';
	$order = $CONFIG['db'][0]['prefix'] . '_promotions_uni.title ' . $CONFIG['activeSettings']['ovOrderDir'] . ' ';

	
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid,
											' . $CONFIG['db'][0]['prefix'] . '_promotions2partner_.id_pcid
										FROM ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_
											ON ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_langid = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_promid = ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.published_at < (:now)
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.published_at <> (:nultime)
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_promotions2partner_
											ON ' . $CONFIG['db'][0]['prefix'] . '_promotions2partner_.id_promid = ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2partner_.del = (:nultime)
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.startdate < (:now)
											AND (' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.enddate > (:now)
												OR ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.enddate = (:nultime))
											' . $condSearch . '
											' . $conBsdProm . '
										GROUP BY ' . $group . '
										');
	$query->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	if($condSearch != '') $query->bindValue(':search', '%' . $varSQL['searchfield'] . '%', PDO::PARAM_STR);
	if($condCat != '') $query->bindValue(':id_caid', $asCat, PDO::PARAM_INT);
	if($condPP != '') $query->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$query->bindValue(':now', $now, PDO::PARAM_STR);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	//$num = $query->rowCount();
	$num = 0;
	foreach($rows as $row){
		if($row['id_pcid'] == '' || $row['id_pcid'] == $CONFIG['user']['id_pcid']){
			$num++;
		}
	}
	$out['ovNumPages'] = ceil($num / $CONFIG['activeSettings']['ovRange']);
	
	
	$limitStart = ($CONFIG['activeSettings']['ovPage'] * $CONFIG['activeSettings']['ovRange']) - $CONFIG['activeSettings']['ovRange'];
	$query = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid,
												' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.title,
												' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.preview_thumbnail,
												' . $CONFIG['db'][0]['prefix'] . '_promotions2partner_.id_pcid,
												' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename
											FROM ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_
												ON ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_langid = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_promid = ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid
													AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.published_at < (:now)
													AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.published_at <> (:nultime)
										
											LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_promotions2partner_
												ON ' . $CONFIG['db'][0]['prefix'] . '_promotions2partner_.id_promid = ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid
													AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2partner_.del = (:nultime)
										
											LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid = ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.preview_thumbnail
	
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.startdate < (:now)
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.enddate > (:now)
												' . $condSearch . '
												' . $conBsdProm . '
											GROUP BY ' . $group . '
											ORDER BY ' . $order . '
											LIMIT ' . $limitStart . ', ' . $CONFIG['activeSettings']['ovRange'] . '
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$query->bindValue(':now', $now, PDO::PARAM_STR);
	if($condSearch != '') $query->bindValue(':search', '%' . $varSQL['searchfield'] . '%', PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();


	
	$classOvTab = '';
//	if($CONFIG['activeSettings']['id_page'] == 'myassets') $classOvTab = 'myassets';
	if($CONFIG['activeSettings']['id_page'] == 'promotions') $classOvTab = 'promotions';
	if($CONFIG['activeSettings']['id_page'] == 'campaigns') $classOvTab = 'campaigns';
	
	if($num > 0){
		$out['contentLeft'] .= '<div class="ovTab ' . $classOvTab . '">';
		$out['contentLeft'] .= '<div class="ovThead">';
		$out['contentLeft'] .= '<div class="ovBoxOuter">';
		$out['contentLeft'] .= '<div class="ovBoxImg"></div>';
		$out['contentLeft'] .= '<div class="ovBoxHead ovOrder" data-order="title">' . $TEXT['ovTitle'] . ' <i class="icon"></i></div>';
		$out['contentLeft'] .= '<div class="ovBoxCategory ovOrder" data-order="category">' . $TEXT['ovCategory'] . ' <i class="icon"></i></div>';
		$out['contentLeft'] .= '<div class="ovBoxSubcategory ovOrder" data-order="subcategory">' . $TEXT['ovSubcategory'] . ' <i class="icon"></i></div>';
		$out['contentLeft'] .= '<div class="ovBoxButtons"></div>';
		$out['contentLeft'] .= '<div class="ovBoxButtonsList"></div>';
		$out['contentLeft'] .= '<div class="ovBoxScrolldiff"></div>';
		$out['contentLeft'] .= '</div>';
		$out['contentLeft'] .= '</div>';
		$out['contentLeft'] .= '<div class="ovTbody">';
		
		foreach($rows as $row){
			if($row['id_pcid'] == '' || $row['id_pcid'] == $CONFIG['user']['id_pcid']){
				$textedit = $TEXT['create'];
				$textpreview = $TEXT['preview'];
				$actionedit = 'create';
				$actionpreview = 'preview';
				$iconpreview = 'icon-enter_full_screen';
	
				$row['id_asid'] = 0;
				
				$thumb = '';
				$dirTarget = 'assetimages/templates_thumbnails/';
				$thumbnail = 'p' . str_pad($row['id_promid'], 6 ,'0', STR_PAD_LEFT) . '-' . $CONFIG['user']['id_countid'] . '-' . $CONFIG['user']['id_langid'] . '-' . md5($row['id_promid'] . '_promotion') . '-1.png'; 
				
				if($row['filesys_filename'] != ''){
					if(file_exists($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'] . $row['filesys_filename'])){
						$dirTarget = $CONFIG['system']['pathMedia'];
						$thumbnail = $row['filesys_filename']; 
					}
				}

				if(file_exists($CONFIG['system']['directoryRoot'] . $dirTarget . $thumbnail)){
					$thumb = '<img src="'.$CONFIG['system']['directoryInstallation'] . $dirTarget . $thumbnail . '?t=' . time() . '">';
				}
	
				$out['contentLeft'] .= '
					<div class="ovBoxOuter" data-promid="' . $row['id_promid'] . '" data-campid="0" data-caid="promotions">
						<div class="ovBoxImg"><div class="ovImg">' . $thumb . '</div></div>
						<div class="ovBoxHead">' . $row['title'] . '</div> 
						<div class="ovBoxCategory"></div>
						<div class="ovBoxSubcategory"></div>
						<div class="ovBoxButtons">
							<button type="button" class="buttonAll buttonBig buttonGreen buttonFixWidth100" data-action="' . $actionedit . '">' . $textedit . '</button>
							<button type="button" class="buttonAll buttonBig buttonRight buttonFixWidth100" data-action="' . $actionpreview . '">' . $textpreview . '</button>
						</div>
						<div class="ovBoxButtonsList">
							<button type="button" class="" data-action="' . $actionedit . '" title="' . $textedit . '"><span class="icon icon-single_annotation"></span></button>
							<button type="button" class="" data-action="' . $actionpreview . '" title="' . $textpreview . '"><span class="icon ' . $iconpreview . '"></span></button>
						</div>
					</div>
					';
			}
		}

		$out['contentLeft'] .= '</div>';
		$out['contentLeft'] .= '</div>';
	}else{
		$out['contentLeft'] = '<div>' . $TEXT['noAssetsFound'] . '</div>';
	}

	
	$out['contentRight'] = '
			<div class="buttongroup buttongroupRight buttonSwitchOverview">
				<button type="button" class="buttonAll buttonBlue" data-overview="grid">' . $TEXT['Grid'] . '<span class="icon icon-gallery"></span></button>
				<button type="button" class="buttonAll buttonBlue" data-overview="list">' . $TEXT['List'] . '<span class="icon icon-list"></span></button>
			</div>
			
			<div class="searchOuter">
				<div class="search">
					<input type="text" name="searchfield" id="searchfield" class="textfieldSearch" placeholder="' . $TEXT['placeholderSearch'] . '"><span class="icon icon-search"></span>
				</div>
			</div>
			';
			
			
	$out['contentRight'] .= '</div>';
	
	
	
	$listRange = '';
	foreach($CONFIG['system']['ovRange'] as $range){
		$listRange .= '<option value="' . $range . '">' . $range . '</option>';
	}
	
	$listPages = '';
	for($i = 1; $i <= $out['ovNumPages']; $i++){
		$listPages .= '<option value="' . $i . '">' . $i . '</option>';
	}
	
	$out['contentRight'] .= '
		<div id="ovPagerOuter">
			<div class="pagewidth">
				<div class="ovPagerRange">
					<select name="pagingRange" id="pagingRange" class="pagingPage" onchange="changePager(\'range\')">
						' . $listRange . '
					</select>
				</div>
				<div class="ovPagerPaging">
					<div class="ovPagerButton pagingPrev" onclick="changePager(\'prev\')"><span class="icon icon-caret_circle_left"></span></div>
					<div class="ovPagerPages">
						<select name="pagingPage" id="pagingPage" class="pagingPage" onchange="changePager(\'page\')">
							' . $listPages . '
						</select> / <span class="ovNumPages"></span>
					</div>
					<div class="ovPagerButton pagingNext" onclick="changePager(\'next\')"><span class="icon icon-caret_circle_right"></span></div>
			</div>
		</div>
	';
}




#######################################################
// list campaigns
#######################################################
if($CONFIG['activeSettings']['id_page'] == 'campaigns'){
	$condSearch = '';
	$condCat = '';
	$condPP = '';
	$group = '';
	$order = '';

	$conBsdProm = 'AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.bsd_only = 2 ';
        if($CONFIG['user']['bsd'] == 1) $conBsdProm = 'AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.bsd_only IN (2,1) ';
        if($CONFIG['user']['distri'] == 1) $conBsdProm = 'AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.bsd_only IN (2,3) ';

	if(isset($varSQL['searchfield']) && $varSQL['searchfield'] != ''){
		$condSearch = 'AND (' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.title LIKE (:search)) ';
	}


	$asCat = $CONFIG['activeSettings']['id_page'];
	$group = $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid';
	//$order = $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.published_at DESC';
	$order = $CONFIG['db'][0]['prefix'] . '_campaigns_uni.title ' . $CONFIG['activeSettings']['ovOrderDir'] . ' ';

	
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid
										FROM ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_
											ON ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_countid = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_langid = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_campid = ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.published_at < (:now)
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.published_at <> (:nultime)
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.startdate < (:now)
											AND (' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.enddate > (:now)
												OR ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.enddate = (:nultime))
											' . $condSearch . '
											' . $conBsdProm . '
										GROUP BY ' . $group . '
										');
	$query->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	if($condSearch != '') $query->bindValue(':search', '%' . $varSQL['searchfield'] . '%', PDO::PARAM_STR);
	if($condCat != '') $query->bindValue(':id_caid', $asCat, PDO::PARAM_INT);
	if($condPP != '') $query->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
	$query->bindValue(':now', $now, PDO::PARAM_STR);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	$out['ovNumPages'] = ceil($num / $CONFIG['activeSettings']['ovRange']);
	
	
	$limitStart = ($CONFIG['activeSettings']['ovPage'] * $CONFIG['activeSettings']['ovRange']) - $CONFIG['activeSettings']['ovRange'];
	$query = $CONFIG['dbconn'][0]->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid,
												' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.title,
												' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.preview_thumbnail,
												' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.filesys_filename
											FROM ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni
											
											INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_
												ON ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_countid = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_langid = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_campid = ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid
													AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.published_at < (:now)
													AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.published_at <> (:nultime)
										
											LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni
												ON ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_count = (:id_count)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_lang = (:id_lang)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_mediafiles_uni.id_mid = ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.preview_thumbnail
	
											WHERE ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.startdate < (:now)
												AND (' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.enddate > (:now)
													OR ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.enddate = (:nultime))
												' . $condSearch . '
												' . $conBsdProm . '
											GROUP BY ' . $group . '
											ORDER BY ' . $order . '
											LIMIT ' . $limitStart . ', ' . $CONFIG['activeSettings']['ovRange'] . '
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$query->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$query->bindValue(':now', $now, PDO::PARAM_STR);
	if($condSearch != '') $query->bindValue(':search', '%' . $varSQL['searchfield'] . '%', PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();


	
	$classOvTab = '';
//	if($CONFIG['activeSettings']['id_page'] == 'myassets') $classOvTab = 'myassets';
	if($CONFIG['activeSettings']['id_page'] == 'promotions') $classOvTab = 'promotions';
	if($CONFIG['activeSettings']['id_page'] == 'campaigns') $classOvTab = 'campaigns';
	
	if($num > 0){
		$out['contentLeft'] .= '<div class="ovTab ' . $classOvTab . '">';
		$out['contentLeft'] .= '<div class="ovThead">';
		$out['contentLeft'] .= '<div class="ovBoxOuter">';
		$out['contentLeft'] .= '<div class="ovBoxImg"></div>';
		$out['contentLeft'] .= '<div class="ovBoxHead ovOrder" data-order="title">' . $TEXT['ovTitle'] . ' <i class="icon"></i></div>';
		$out['contentLeft'] .= '<div class="ovBoxCategory ovOrder" data-order="category">' . $TEXT['ovCategory'] . ' <i class="icon"></i></div>';
		$out['contentLeft'] .= '<div class="ovBoxSubcategory ovOrder" data-order="subcategory">' . $TEXT['ovSubcategory'] . ' <i class="icon"></i></div>';
		$out['contentLeft'] .= '<div class="ovBoxButtons"></div>';
		$out['contentLeft'] .= '<div class="ovBoxButtonsList"></div>';
		$out['contentLeft'] .= '<div class="ovBoxScrolldiff"></div>';
		$out['contentLeft'] .= '</div>';
		$out['contentLeft'] .= '</div>';
		$out['contentLeft'] .= '<div class="ovTbody">';
		
		foreach($rows as $row){
			$textedit = $TEXT['create'];
			$textpreview = $TEXT['preview'];
			$actionedit = 'create';
			$actionpreview = 'preview';
			$iconpreview = 'icon-enter_full_screen';

			$row['id_asid'] = 0;
			
			$thumb = '';
			$dirTarget = 'assetimages/templates_thumbnails/';
			$thumbnail = 'c' . str_pad($row['id_campid'], 6 ,'0', STR_PAD_LEFT) . '-' . $CONFIG['user']['id_countid'] . '-' . $CONFIG['user']['id_langid'] . '-' . md5($row['id_campid'] . '_campaign') . '-1.png'; 
			
			if($row['filesys_filename'] != ''){
				if(file_exists($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'] . $row['filesys_filename'])){
					$dirTarget = $CONFIG['system']['pathMedia'];
					$thumbnail = $row['filesys_filename']; 
				}
			}

			if(file_exists($CONFIG['system']['directoryRoot'] . $dirTarget . $thumbnail)){
				$thumb = '<img src="'.$CONFIG['system']['directoryInstallation'] . $dirTarget . $thumbnail . '?t=' . time() . '">';
			}

			$out['contentLeft'] .= '
				<div class="ovBoxOuter" data-promid="0"  data-campid="' . $row['id_campid'] . '" data-caid="campaigns">
					<div class="ovBoxImg"><div class="ovImg">' . $thumb . '</div></div>
					<div class="ovBoxHead">' . $row['title'] . '</div> 
					<div class="ovBoxCategory"></div>
					<div class="ovBoxSubcategory"></div>
					<div class="ovBoxButtons">
						<button type="button" class="buttonAll buttonBig buttonGreen buttonFixWidth100" data-action="' . $actionedit . '">' . $textedit . '</button>
						<button type="button" class="buttonAll buttonBig buttonRight buttonFixWidth100" data-action="' . $actionpreview . '">' . $textpreview . '</button>
					</div>
					<div class="ovBoxButtonsList">
						<button type="button" class="" data-action="' . $actionedit . '" title="' . $textedit . '"><span class="icon icon-single_annotation"></span></button>
						<button type="button" class="" data-action="' . $actionpreview . '" title="' . $textpreview . '"><span class="icon ' . $iconpreview . '"></span></button>
					</div>
				</div>
				';
		}

		$out['contentLeft'] .= '</div>';
		$out['contentLeft'] .= '</div>';
	}else{
		$out['contentLeft'] = '<div>' . $TEXT['noAssetsFound'] . '</div>';
	}

	
	$out['contentRight'] = '
			<div class="buttongroup buttongroupRight buttonSwitchOverview">
				<button type="button" class="buttonAll buttonBlue" data-overview="grid">' . $TEXT['Grid'] . '<span class="icon icon-gallery"></span></button>
				<button type="button" class="buttonAll buttonBlue" data-overview="list">' . $TEXT['List'] . '<span class="icon icon-list"></span></button>
			</div>
			
			<div class="searchOuter">
				<div class="search">
					<input type="text" name="searchfield" id="searchfield" class="textfieldSearch" placeholder="' . $TEXT['placeholderSearch'] . '"><span class="icon icon-search"></span>
				</div>
			</div>
			';
			
			
	$out['contentRight'] .= '</div>';
	
	
	
	$listRange = '';
	foreach($CONFIG['system']['ovRange'] as $range){
		$listRange .= '<option value="' . $range . '">' . $range . '</option>';
	}
	
	$listPages = '';
	for($i = 1; $i <= $out['ovNumPages']; $i++){
		$listPages .= '<option value="' . $i . '">' . $i . '</option>';
	}
	
	$out['contentRight'] .= '
		<div id="ovPagerOuter">
			<div class="pagewidth">
				<div class="ovPagerRange">
					<select name="pagingRange" id="pagingRange" class="pagingPage" onchange="changePager(\'range\')">
						' . $listRange . '
					</select>
				</div>
				<div class="ovPagerPaging">
					<div class="ovPagerButton pagingPrev" onclick="changePager(\'prev\')"><span class="icon icon-caret_circle_left"></span></div>
					<div class="ovPagerPages">
						<select name="pagingPage" id="pagingPage" class="pagingPage" onchange="changePager(\'page\')">
							' . $listPages . '
						</select> / <span class="ovNumPages"></span>
					</div>
					<div class="ovPagerButton pagingNext" onclick="changePager(\'next\')"><span class="icon icon-caret_circle_right"></span></div>
			</div>
		</div>
	';
}



echo json_encode($out);

?>