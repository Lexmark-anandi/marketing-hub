<?php 
//include_once(__DIR__ . '/fu-templates-read-components.php');


###############################
// read components
$aTPE = array('id_temp' => $CONFIG['page']['id_data'], 'pages' => array());
$rowsD[0]['components'] = '';

$queryStrTPE = 'SELECT 
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpeid,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_caid,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tempid,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpid,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tcid,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.page_id,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.page,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.elementtitle,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.position_left,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.position_top,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.width,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.height,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.fontsize, 
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.fontcolor,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.fontstyle,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.background_color, 
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.content,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.content_transrequired,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc_bool.content_transrequired AS content_transrequired_loc,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_ext_bool.content_transrequired AS content_transrequired_ext,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.editable,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc_bool.editable AS editable_loc,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_ext_bool.editable AS editable_ext,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.active,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc_bool.active AS active_loc,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_ext_bool.active AS active_ext,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.max_char,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.alignment,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.verticalalignment,
		' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.fixed
	FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni 
';

$tableTPE = '_templatespageselements_uni';
$tableAliasTPE = '_templatespageselements_loc_bool';
$queryStrTPE .= 'LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_loc AS ' . $CONFIG['db'][0]['prefix'] . $tableAliasTPE . ' ';
$queryStrTPE .= 'ON ' . $CONFIG['db'][0]['prefix'] . $tableAliasTPE . '.id_count = ' . $CONFIG['db'][0]['prefix'] . $tableTPE . '.id_count ';
$queryStrTPE .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAliasTPE . '.id_lang = ' . $CONFIG['db'][0]['prefix'] . $tableTPE . '.id_lang ';
$queryStrTPE .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAliasTPE . '.id_dev = ' . $CONFIG['db'][0]['prefix'] . $tableTPE . '.id_dev ';
$queryStrTPE .= 'AND (' . $CONFIG['db'][0]['prefix'] . $tableAliasTPE . '.id_cl = ' . $CONFIG['db'][0]['prefix'] . $tableTPE . '.id_cl ';
$queryStrTPE .= 'OR ' . $CONFIG['db'][0]['prefix'] . $tableAliasTPE . '.id_cl = 0) ';
$queryStrTPE .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAliasTPE . '.id_tpeid = ' . $CONFIG['db'][0]['prefix'] . $tableTPE . '.id_tpeid ';
$queryStrTPE .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAliasTPE . '.del = (:nultime) ';

$tableAliasTPE = '_templatespageselements_ext_bool';
$queryStrTPE .= 'LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_ext AS ' . $CONFIG['db'][0]['prefix'] . $tableAliasTPE . ' ';
$queryStrTPE .= 'ON ' . $CONFIG['db'][0]['prefix'] . $tableAliasTPE . '.id_count = ' . $CONFIG['db'][0]['prefix'] . $tableTPE . '.id_count ';
$queryStrTPE .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAliasTPE . '.id_lang = ' . $CONFIG['db'][0]['prefix'] . $tableTPE . '.id_lang ';
$queryStrTPE .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAliasTPE . '.id_dev = ' . $CONFIG['db'][0]['prefix'] . $tableTPE . '.id_dev ';
$queryStrTPE .= 'AND (' . $CONFIG['db'][0]['prefix'] . $tableAliasTPE . '.id_cl = ' . $CONFIG['db'][0]['prefix'] . $tableTPE . '.id_cl ';
$queryStrTPE .= 'OR ' . $CONFIG['db'][0]['prefix'] . $tableAliasTPE . '.id_cl = 0) ';
$queryStrTPE .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAliasTPE . '.id_tpeid = ' . $CONFIG['db'][0]['prefix'] . $tableTPE . '.id_tpeid ';
$queryStrTPE .= 'AND ' . $CONFIG['db'][0]['prefix'] . $tableAliasTPE . '.del = (:nultime) ';

$queryStrTPE .= 'WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_count = (:id_count)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_lang = (:id_lang)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_dev = (:id_dev)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.del = (:nultime)
		AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tempid = (:id_tempid)
';

$queryTPE = $CONFIG['dbconn'][0]->prepare($queryStrTPE);
$queryTPE->bindValue(':id_count', $aVersion[0], PDO::PARAM_INT);
$queryTPE->bindValue(':id_lang', $aVersion[1], PDO::PARAM_INT);
$queryTPE->bindValue(':id_dev', $aVersion[2], PDO::PARAM_INT);
$queryTPE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryTPE->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_INT);
$queryTPE->execute();
$rowsTPE = $queryTPE->fetchAll(PDO::FETCH_ASSOC);
$numTPE = $queryTPE->rowCount();
if($numTPE > 0){
	foreach($rowsTPE as $rowTPE){
		if(!array_key_exists('page_' . $rowTPE['page_id'], $aTPE['pages'])){
			$aTPE['pages']['page_' . $rowTPE['page_id']] = array();
		}
			
		if(!array_key_exists('compboxOuter_' . $rowTPE['id_tpeid'], $aTPE['pages']['page_' . $rowTPE['page_id']])){
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']] = array();
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['id_tpeid'] = $rowTPE['id_tpeid'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['id_caid'] = $rowTPE['id_caid'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['id_tpid'] = $rowTPE['id_tpid'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['id_tcid'] = $rowTPE['id_tcid'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['elementtitle'] = $rowTPE['elementtitle'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['pageid'] = $rowTPE['page_id'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['page'] = $rowTPE['page'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['width'] = $rowTPE['width'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['height'] = $rowTPE['height'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['left'] = $rowTPE['position_left'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['top'] = $rowTPE['position_top'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['fontsize'] = $rowTPE['fontsize'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['fontcolor'] = $rowTPE['fontcolor'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['fontstyle'] = $rowTPE['fontstyle'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['background_color'] = $rowTPE['background_color'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowTPE['content'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['contentOrg'] = $rowTPE['content'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['maxchars'] = $rowTPE['max_char'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['alignment'] = $rowTPE['alignment'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['verticalalignment'] = $rowTPE['verticalalignment'];
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['fixed'] = $rowTPE['fixed'];
			
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['transrequired'] = $rowTPE['content_transrequired'];
			if(($rowTPE['content_transrequired_ext'] == NULL || $rowTPE['content_transrequired_ext'] == 0) && ($rowTPE['content_transrequired_loc'] == NULL || $rowTPE['content_transrequired_loc'] == 0)) $aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['transrequired'] = 0;
			
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['transrequired_default'] = '';
			if($rowTPE['content_transrequired'] == 1) $aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['transrequired_default'] = $TEXT['yes'];
			if($rowTPE['content_transrequired'] == 2) $aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['transrequired_default'] = $TEXT['no'];


			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['editable'] = $rowTPE['editable'];
			if(($rowTPE['editable_ext'] == NULL || $rowTPE['editable_ext'] == 0) && ($rowTPE['editable_loc'] == NULL || $rowTPE['editable_loc'] == 0)) $aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['editable'] = 0;
			
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['editable_default'] = '';
			if($rowTPE['editable'] == 1) $aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['editable_default'] = $TEXT['yes'];
			if($rowTPE['editable'] == 2) $aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['editable_default'] = $TEXT['no'];

			
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['active'] = $rowTPE['active'];
			if(($rowTPE['active_ext'] == NULL || $rowTPE['active_ext'] == 0) && ($rowTPE['active_loc'] == NULL || $rowTPE['active_loc'] == 0)) $aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['active'] = 0;
			
			$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['active_default'] = '';
			if($rowTPE['active'] == 1) $aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['active_default'] = $TEXT['yes'];
			if($rowTPE['active'] == 2) $aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['active_default'] = $TEXT['no'];
		}
	}
	
	$rowsD[0]['components'] = json_encode($aTPE);
}


######################################################
// Defaults for empty
if($numTPE == 0){
	if(!isset($rowsD[0]['id_etid'])) $rowsD[0]['id_etid'] = 'x';
	
	$queryTp = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_templates_loc.id_caid,
											' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_tpid
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templates_loc 
	
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc
											ON ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
												AND ' . $CONFIG['db'][0]['prefix'] . '_templates_loc.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templatespages_loc.id_tempid
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templates_loc.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_loc.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_loc.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_loc.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_loc.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_loc.id_tempid = (:id_tempid)
										');
	$queryTp->bindValue(':id_count', 0, PDO::PARAM_INT);
	$queryTp->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$queryTp->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryTp->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryTp->bindValue(':id_tempid', $CONFIG['page']['id_data'], PDO::PARAM_INT);
	$queryTp->execute();
	$rowsTp = $queryTp->fetchAll(PDO::FETCH_ASSOC);
	$numTp = $queryTp->rowCount();
	
	if($numTp > 0){
		if($rowsTp[0]['id_caid'] == 3){
			$queryStrTPE = 'SELECT 
					' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.id_tcid,
					' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.page,
					' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.elementtitle,
					' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.position_left,
					' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.position_top,
					' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.width,
					' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.height,
					' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.fontsize,
					' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.fontcolor,
					' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.fontstyle,
					' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.background_color,
					' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.content,
					' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.content_transrequired,
					' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.editable,
					' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.active,
					' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.max_char,
					' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.alignment,
					' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.verticalalignment,
					' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.fixed
				FROM ' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_ 
				
				WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.id_count = (:id_count)
					AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.id_lang = (:id_lang)
					AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.id_dev = (:id_dev)
					AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
					AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.del = (:nultime)
					AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.id_caid = (:id_caid)
					AND ' . $CONFIG['db'][0]['prefix'] . '_templatecomponentsdefaults_.id_etid = (:id_etid)
			';
			
			$queryTPE = $CONFIG['dbconn'][0]->prepare($queryStrTPE);
			$queryTPE->bindValue(':id_count', 0, PDO::PARAM_INT);
			$queryTPE->bindValue(':id_lang', 0, PDO::PARAM_INT);
			$queryTPE->bindValue(':id_dev', 0, PDO::PARAM_INT);
			$queryTPE->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$queryTPE->bindValue(':id_caid', $rowsTp[0]['id_caid'], PDO::PARAM_INT);
			$queryTPE->bindValue(':id_etid', $rowsD[0]['id_etid'], PDO::PARAM_INT);
			$queryTPE->execute();
			$rowsTPE = $queryTPE->fetchAll(PDO::FETCH_ASSOC);
			$numTPE = $queryTPE->rowCount();
			if($numTPE > 0){
				foreach($rowsTPE as $rowTPE){
					$queryE = $CONFIG['dbconn'][0]->prepare('
														INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_
														(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
														VALUES
														(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
														');
					$queryE->bindValue(':nul', 0, PDO::PARAM_INT);
					$queryE->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
					$queryE->bindValue(':create_at', $now, PDO::PARAM_STR);
					$queryE->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
					$queryE->execute();
					$rowTPE['id_tpeid'] = $CONFIG['dbconn'][0]->lastInsertId();
					
					$rowTPE['id_caid'] = $rowsTp[0]['id_caid'];
					$rowTPE['id_tpid'] = $rowsTp[0]['id_tpid'];
					$rowTPE['page_id'] = $rowsTp[0]['id_tpid'] . '_' . $rowTPE['page'];
					
					if(!array_key_exists('page_' . $rowTPE['page_id'], $aTPE['pages'])){
						$aTPE['pages']['page_' . $rowTPE['page_id']] = array();
					}
						
					if(!array_key_exists('compboxOuter_' . $rowTPE['id_tpeid'], $aTPE['pages']['page_' . $rowTPE['page_id']])){
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']] = array();
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['id_tpeid'] = $rowTPE['id_tpeid'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['id_caid'] = $rowTPE['id_caid'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['id_tpid'] = $rowTPE['id_tpid'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['id_tcid'] = $rowTPE['id_tcid'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['elementtitle'] = $rowTPE['elementtitle'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['pageid'] = $rowTPE['page_id'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['page'] = $rowTPE['page'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['width'] = $rowTPE['width'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['height'] = $rowTPE['height'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['left'] = $rowTPE['position_left'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['top'] = $rowTPE['position_top'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['fontsize'] = $rowTPE['fontsize'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['fontcolor'] = $rowTPE['fontcolor'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['fontstyle'] = $rowTPE['fontstyle'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['background_color'] = $rowTPE['background_color'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['content'] = $rowTPE['content'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['contentOrg'] = $rowTPE['content'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['maxchars'] = $rowTPE['max_char'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['alignment'] = $rowTPE['alignment'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['verticalalignment'] = $rowTPE['verticalalignment'];
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['fixed'] = $rowTPE['fixed'];
						
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['transrequired'] = $rowTPE['content_transrequired'];
						
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['transrequired_default'] = '';
						if($rowTPE['content_transrequired'] == 1) $aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['transrequired_default'] = $TEXT['yes'];
						if($rowTPE['content_transrequired'] == 2) $aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['transrequired_default'] = $TEXT['no'];
			
			
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['editable'] = $rowTPE['editable'];
						
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['editable_default'] = '';
						if($rowTPE['editable'] == 1) $aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['editable_default'] = $TEXT['yes'];
						if($rowTPE['editable'] == 2) $aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['editable_default'] = $TEXT['no'];
			
						
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['active'] = $rowTPE['active'];
						
						$aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['active_default'] = '';
						if($rowTPE['active'] == 1) $aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['active_default'] = $TEXT['yes'];
						if($rowTPE['active'] == 2) $aTPE['pages']['page_' . $rowTPE['page_id']]['compboxOuter_' . $rowTPE['id_tpeid']]['active_default'] = $TEXT['no'];
					}
				}
				
				$rowsD[0]['components'] = json_encode($aTPE);
			}
		}
	}
}

?>