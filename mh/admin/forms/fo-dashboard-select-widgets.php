<?php
$initCondPage = true;
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();


$date = new DateTime();
$today = $date->format('Y-m-d');

 
$aAssignedDatasets = array();
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_dashboard2user.dashboard 
									FROM ' . $CONFIG['db'][0]['prefix'] . '_dashboard2user 
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_dashboard2user.id_uid = (:id_uid)
									'); 
$query->bindValue(':id_uid', $CONFIG['user']['id'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$aDashboard = json_decode($rows[0]['dashboard'], true);
foreach($aDashboard as $widget){
	array_push($aAssignedDatasets, $widget['id_dashid']);
}
$num = count($aDashboard);









$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_dashboard.id_dashid,
										' . $CONFIG['db'][0]['prefix'] . '_dashboard.title
									FROM ' . $CONFIG['db'][0]['prefix'] . '_dashboard 

									WHERE ' . $CONFIG['db'][0]['prefix'] . '_dashboard.active = (:active)
										AND ' . $CONFIG['db'][0]['prefix'] . '_dashboard.id_mod2f IN ('.implode(',', $CONFIG['user']['functions']).')
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_dashboard.title
									');
$query->bindValue(':active', 1, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();


$list = '';
$listSelected = '';
$listAvailable = '';
foreach($rows as $row){
	$row['identifier'] = $TEXT[$row['title']];



	$sel = '';
	if(in_array($row['id_dashid'], $aAssignedDatasets)) $sel = 'selected="selected"';
	$list .= '<option value="'.$row['id_dashid'].'" '.$sel.'>'.$row['identifier'].'</option>';
	
	$display = 'listassignhidden';
	if(in_array($row['id_dashid'], $aAssignedDatasets)) $display = 'listassignvisible';
	$listSelected .= '<li class="ui-state-default ui-element ui-draggable ui-draggable-handle '.$display.'" data-value="'.$row['id_dashid'].'" data-search="'.strtolower($row['identifier']).'">'.$row['identifier'].'<a href="javascript:void(null)" class="action"><span class="ui-corner-all ui-icon ui-icon-minus"></span></a></li>';
	
	$display = 'listassignhidden';
	if(!in_array($row['id_dashid'], $aAssignedDatasets)) $display = 'listassignvisible searchvisible';
	$listAvailable .= '<li class="ui-state-default ui-element ui-draggable ui-draggable-handle '.$display.'" data-value="'.$row['id_dashid'].'" data-search="'.strtolower($row['identifier']).'">'.$row['identifier'].'<a href="javascript:void(null)" class="action"><span class="ui-corner-all ui-icon ui-icon-plus"></span></a></li>';
}


######################################################################
$FORM = '
<!--<form enctype="multipart/form-data" onsubmit="return false" action="" method="post" name="diaForm" id="diaForm" class="assignForm">
	<fieldset>
		<div class="formTab" data-formtab="formGeneral">
			<div class="formRow">
				<select name="widgets[]" id="widgets" class="selectassign" multiple="multiple">
					'.$list.'
				</select>
				<div class="ui-multiselect ui-helper-clearfix ui-widget">
					<div class="selected">
						<div class="actions ui-widget-header ui-helper-clearfix">
							<span class="count"><span class="counter">'. count($aAssignedDatasets) .'</span> '.$TEXT['numselected'].'</span><a href="javascript:void(null)" class="remove-all">'.$TEXT['allout'].'</a>
						</div>
						<ul class="selected connected-list">
							<li class="ui-helper-hidden-accessible"></li>
							'.$listSelected.'
						</ul>
					</div>
					<div class="available">
						<div class="actions ui-widget-header ui-helper-clearfix">
							<input type="text" class="search searchText empty ui-widget-content ui-corner-all" style="display:block;opacity:1;width:150px;" placeholder="'.$TEXT['search'].'"><a href="javascript:void(null)" class="add-all">'.$TEXT['allin'].'</a>
						</div>
						<ul class="available connected-list">
							<li class="ui-helper-hidden-accessible"></li>
							'.$listAvailable.'
						</ul>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" class="" value="'.$CONFIG['user']['id'].'" name="assignParent">
		<input type="hidden" class="fieldEMode" value="export" name="mode">
		<input type="hidden" class="fieldEModul" value="' . $varSQL['idModul'] . '" name="idModul">
		<input type="hidden" class="gridID" value="' . intval($varSQL['id_grid_d']) . '" name="id_grid_d">
	</fieldset>
</form>-->








	<form enctype="multipart/form-data" onsubmit="return false" action="" method="post" name="diaForm" id="diaForm" class="assignForm">
		<div class="formRow">
			<select name="selectassign_widgets[]" id="selectassign_widgets" class="selectassign selectassign_" multiple="multiple" data-sync="widgets">
				'.$list.'
			</select>
			<div class="ui-multiselect ui-helper-clearfix ui-widget">
				<div class="selected">
					<div class="actions ui-widget-header ui-helper-clearfix">
						<span class="count"><span class="counter">'. count($aAssignedDatasets) .'</span> '.$TEXT['numselected'].'</span><a href="javascript:void(null)" class="remove-all">'.$TEXT['allout'].'</a>
					</div>
					<ul class="selected connected-list" data-assign="">
						<li class="ui-helper-hidden-accessible"></li>
						'.$listSelected.'
					</ul>
				</div>
				<div class="available">
					<div class="actions ui-widget-header ui-helper-clearfix">
						<input type="text" class="search searchText empty ui-widget-content ui-corner-all" style="display:block;opacity:1;width:150px;height:18px;" placeholder="'.$TEXT['search'].'"><a href="javascript:void(null)" class="add-all">'.$TEXT['allin'].'</a>
					</div>
					<ul class="available connected-list">
						<li class="ui-helper-hidden-accessible"></li>
						'.$listAvailable.'
					</ul>
				</div>
			</div>
		</div>
	</form>





';
######################################################################




echo $FORM;

?>