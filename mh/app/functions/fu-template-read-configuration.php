<?php
$condStatus = '';
if($CONFIG['user']['bsd'] != 1) $condStatus = 'AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.status IN ("Public") ';

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$dateAnnounce = new DateTime();
$dateAnnounce->modify('-3 months');
$nowAnnounce = $dateAnnounce->format('Y-m-d');


$aCondProd = array();
if($varSQL['id_promid'] > 0){
	$queryPr = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_promotions2products_.id_pid
										FROM ' . $CONFIG['db'][0]['prefix'] . '_promotions2products_ 
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions2products_.id_promid = (:id_promid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2products_.del = (:nultime)
										');
	$queryPr->bindValue(':id_promid', $varSQL['id_promid'], PDO::PARAM_INT);
	$queryPr->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryPr->execute();
	$rowsPr = $queryPr->fetchAll(PDO::FETCH_ASSOC);
	$numPr = $queryPr->rowCount();
	
	foreach($rowsPr as $rowPr){ 
		array_push($aCondProd, $rowPr['id_pid']);
	}
}
if($varSQL['id_campid'] > 0){
	$queryPr = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_campaigns2products_.id_pid
										FROM ' . $CONFIG['db'][0]['prefix'] . '_campaigns2products_ 
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_campaigns2products_.id_campid = (:id_campid)
											AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2products_.del = (:nultime)
										');
	$queryPr->bindValue(':id_campid', $varSQL['id_campid'], PDO::PARAM_INT); 
	$queryPr->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryPr->execute();
	$rowsPr = $queryPr->fetchAll(PDO::FETCH_ASSOC);
	$numPr = $queryPr->rowCount();
	
	foreach($rowsPr as $rowPr){ 
		array_push($aCondProd, $rowPr['id_pid']);
	}
}


$condProduct = '';
if(count($aCondProd) > 0) $condProduct = 'AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_pid IN (' . implode(',', $aCondProd) . ')';


$queryPr = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_pid,
										' . $CONFIG['db'][0]['prefix'] . '_products_uni.mkt_name,
										' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_ptid,
										' . $CONFIG['db'][0]['prefix'] . '_products_uni.announce_date,
										' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.prod_type
									FROM ' . $CONFIG['db'][0]['prefix'] . '_products_uni 

									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni
										ON ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.id_cl IN (0, 1)
											AND ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.id_ptid = ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_ptid
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_dev = (:id_dev)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.is_printer = (:one)
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.status IN ("Public", "Not Public - B2B")
										AND ' . $CONFIG['db'][0]['prefix'] . '_products_uni.announce_date <= (:now)
										' . $condStatus . '
										' . $condProduct . '
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_prodtypes_uni.rank, ' . $CONFIG['db'][0]['prefix'] . '_products_uni.mkt_name
									');
$queryPr->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$queryPr->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$queryPr->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryPr->bindValue(':one', 1, PDO::PARAM_INT);
$queryPr->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryPr->bindValue(':now', $now, PDO::PARAM_STR);
$queryPr->execute();
$rowsPr = $queryPr->fetchAll(PDO::FETCH_ASSOC);
$numPr = $queryPr->rowCount();
//			$arr = $queryPr->errorInfo();
//			print_r($arr);


$oldPT = 0;
$n = 0;
$listPrinter = '<option value="0">' . $TEXT['selectPrinter'] . '</option>';
foreach($rowsPr as $rowPr){
	if($oldPT != $rowPr['id_ptid']){
		if($n > 0) $listPrinter .= '</optgroup>';
		$listPrinter .= '<optgroup label="' . $rowPr['prod_type'] . '">';
	}
	
	$labelNew = '';
	$classNew = '';
	if($nowAnnounce < $rowPr['announce_date']){
		$labelNew = $TEXT['new'] . ': ';
		$classNew = 'newProduct';
	}
	$listPrinter .= '<option value="' . $rowPr['id_pid'] . '" class="' . $classNew . '">' . $labelNew . $rowPr['mkt_name'] . '</option>';
	
	$n++;
	$oldPT = $rowPr['id_ptid'];
}
$listPrinter .= '</optgroup>';



if(in_array(2, $aBannerframes)){
	// if productframe exists
	$out['configurationform'] .= '<div class="fieldset">
									<div class="formRow formRowNoBorder">
										<div class="textfield textfieldHead">' . $TEXT['configurationFrames'] . '</div>
										<div class="textfield textfieldSec textfieldHead">sec</div>
									</div>';
									
	if(in_array('1', $aBannerframes)){
		$out['configurationform'] .= '<div class="formRow">
											<div class="textfield"><span class="icon icon-dummy">&nbsp;</span>' . $TEXT['firstframe'] . '</div>
											<div class="textfield textfieldSec"><input type="text" class="textfield textfieldDurationFirst" name="durationFirstframe" id="durationFirstframe"><input type="hidden" class="textfield" name="showframeFirstframe" id="showframeFirstframe" value=""></div>
											<div class="textfield textfieldButton textfieldButtonHideFirst"><span class="icon icon-eye" title="' . $TEXT['hidePrinter'] . '"></span></div>
										</div>';
	}
										
									
									
													
	if(in_array('2', $aBannerframes)){
		$out['configurationform'] .= '<div class="sortablePrinter">';

		//$out['configurationform'] .= $configformPrinter;
		
		$out['configurationform'] .= '</div>';
		
		$out['configurationform'] .= '<div class="formRow">
											<div class="textfieldAddOuter">
												<div class="textfield textfieldAdd textfieldAddButton"><span class="icon icon-add"></span>' . $TEXT['addprinter'] . '</div>
											</div>
											<div class="textfieldAddOuter textfieldAddOuterHide">
												<div class="textfield"><select class="textfield" name="selectAddPrinter" id="selectAddPrinter">
													' . $listPrinter . '
												</select></div>
											</div>
										</div>';	
	}
	
	
				
	if(in_array('3', $aBannerframes)){
		$out['configurationform'] .= '<div class="formRow formRowSpace">
											<div class="textfield"><span class="icon icon-dummy">&nbsp;</span>' . $TEXT['lastframe'] . '</div>
											<div class="textfield textfieldSec"><input type="text" class="textfield textfieldDurationLast" name="durationLastframe" id="durationLastframe"><input type="hidden" class="textfield" name="showframeLastframe" id="showframeLastframe" value=""></div>
											<div class="textfield textfieldButton textfieldButtonHideLast"><span class="icon icon-eye" title="' . $TEXT['hidePrinter'] . '"></span></div>
										</div>';	
	}
												
	$out['configurationform'] .= '</div>';				
}else{
	$aTcLpmd = array(3,4,5,6,7,8,9,12,13);
	//$aTcLpmd = array(3);
	
	$queryA = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpeid,
											' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpid,
											' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tcid,
											' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.position_top,
											' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.position_left,
											' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.width,
											' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.height
										FROM ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni 

										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni
											ON ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_count = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_lang = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_dev = (:id_dev)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_cl IN (0, 1)
												AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_tpid = ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tpid
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespageselements_uni.id_tcid IN (' . implode(',', $aTcLpmd) . ')
											AND ' . $CONFIG['db'][0]['prefix'] . '_templatespages_uni.id_bfid = (:id_bfid)
										');
	$queryA->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$queryA->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$queryA->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryA->bindValue(':id_bfid', (isset($varSQL['id_bfid'])) ? $varSQL['id_bfid'] : 0, PDO::PARAM_INT);
	$queryA->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryA->execute();
	$rowsA = $queryA->fetchAll(PDO::FETCH_ASSOC);
	$numA = $queryA->rowCount();

	if($numA == 0){
		$out['configurationform'] .= '<div class="fieldset"><div class="formRow formRowSpace">' . $TEXT['noConfigOpt'] . '</div></div>';
	}else{
		$aListTp = array();
		$aListTc = array();
		$aListTpe = array();
		$aGroupsTpe = array();
		foreach($rowsA as $rowA){
			if(!in_array($rowA['id_tpid'], $aListTp)) array_push($aListTp, $rowA['id_tpid']);
			
			if(!array_key_exists($rowA['id_tcid'], $aListTc)) $aListTc[$rowA['id_tcid']] = 0;
			$aListTc[$rowA['id_tcid']]++;
			
			if(!array_key_exists($rowA['id_tcid'], $aListTpe)) $aListTpe[$rowA['id_tcid']] = array();
			$aListTpeTmp = array();
			foreach($rowA as $kA => $vA){
				$aListTpeTmp[$kA] = $vA;
			}
			array_push($aListTpe[$rowA['id_tcid']], $aListTpeTmp);
		}
		$numProducts = max($aListTc);

		foreach($aListTpe as &$aList){
			$row = 0;
			$posB = -0.1;
			uasort($aList, 'sortRows');
			
			foreach($aList as &$aL){
				if($aL['position_top'] > $posB){
					$row++;
				}
				$posB = $aL['position_top'] + $aL['height'];
				$aL['group'] = $row;
			}
		}
		foreach($aListTpe as &$aList){
			$col = 0;
			$posR = -0.1;
			uasort($aList, 'sortCols');
			
			foreach($aList as &$aL){
				if($aL['position_left'] > $posR){
					$col++;
				}
				$posR = $aL['position_left'] + $aL['width'];
				$aL['group'] .= '_' . $col;
			}
		}
		foreach($aListTpe as &$aList){
			uasort($aList, 'sortGroups');
		}
		//var_dump($aListTpe);
		
		foreach($aListTpe as $list){
			foreach($list as $l){
				if(!array_key_exists($l['group'], $aGroupsTpe)) $aGroupsTpe[$l['group']] = array();
				if(!in_array($l['id_tpeid'], $aGroupsTpe[$l['group']])) array_push($aGroupsTpe[$l['group']], $l['id_tpeid']);
			}
		}

		$out['configurationform'] .= '<div class="formRow formRowSpace"><div class="sortablePrinter">';
		
		foreach($aGroupsTpe as $kGroup => $aGroup){
			$group = json_encode($aGroup);
			
			$out['configurationform'] .= '<div class="rowSelectPrinter">
												<div class="textfieldAddOuter ">
													<div class="textfield"><select class="textfield selectPrinter" name="selectPrinter" id="selectPrinter_' . $kGroup . '" data-group="' . $kGroup . '" data-tpe="' . htmlspecialchars($group) . '">
														' . $listPrinter . '
													</select></div>
												</div>
												</div>
	
	';
		}

		$out['configurationform'] .= '</div></div>';
	}
}


function sortCols($a,$b){
    return $a['position_left'] < $b['position_left'] ? -1 : $a['position_left'] == $b['position_left'] ? 0 : 1;
}
function sortRows($a,$b){
    return $a['position_top'] < $b['position_top'] ? -1 : $a['position_top'] == $b['position_top'] ? 0 : 1;
}
function sortGroups($a,$b){
    return $a['group'] < $b['group'] ? -1 : $a['group'] == $b['group'] ? 0 : 1;
}



?>