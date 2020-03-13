<?php
$initCondPage = true;
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();
$aData = json_decode($varSQL['data'], true);


$cond = '';
if($CONFIG['user']['right'] == 4){
	$cond = 'AND ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurations_uni.create_from = ' . $CONFIG['user']['id'] . '';
}
if($CONFIG['user']['right'] == 2 || $CONFIG['user']['right'] == 3){
	$cond = 'AND ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurations_uni.id_rid IN (2,3)';
}

//										AND ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurations_uni.id_caid = (:id_caid)
//$query->bindValue(':id_caid', $aData['caid'], PDO::PARAM_INT);
$listConf = '<option value="all"></option>';
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurations_uni.id_tconid AS id,
										' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurations_uni.configurationname AS term
									FROM ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurations_uni
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurations_uni.id_count = (:count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurations_uni.id_lang = (:lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurations_uni.id_dev = (:dev)
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurations_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurations_uni.del = (:nultime)
										' . $cond . '
									GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurations_uni.id_tconid
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_templatesconfigurations_uni.configurationname
									');
$query->bindValue(':count', 0, PDO::PARAM_INT);
$query->bindValue(':lang', 0, PDO::PARAM_INT);
$query->bindValue(':dev', 0, PDO::PARAM_INT);
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount(); 

foreach($rows as $row){
	$listConf .= '<option value="' . $row['id'] . '">' . $row['term'] . '</option>';
}


######################################################################
$FORM = '
<form enctype="multipart/form-data" onsubmit="return false" action="" method="post" name="diaForm" id="diaForm" class="exportForm">
	<div class="formTab" data-formtab="formGeneralConfLoad">
		<div class="formRow">
			<div class="formLabel">
				<label for="configurationnameLoad">' . $TEXT['configurationname'] . '</label>
			</div>
			<div class="formField">
				<select name="configurationnameLoad" id="configurationnameLoad" class="textfield">
				' . $listConf . '
				</select>
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
			</div>
			<div class="formField">
				<strong>' . $TEXT['noteConfigSave'] . '</strong>
			</div>
		</div>
	</div>
</form>
';
######################################################################




echo $FORM;

?>