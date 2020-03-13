<?php
include_once(__DIR__ . '/../config-all.php');
include_once(__DIR__ . '/../custom/config-all-custom.php');

include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-connect.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-local-variations.php');
include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-insert-all.php');

$CONFIG['user']['id_real'] = 0;
$CONFIG['user']['activeClient'] = 1;
$CONFIG['user']['restricted_all'] = 0;
$CONFIG['user']['specifications'][14] = 9;
$CONFIG['activeSettings']['id_clid'] = 1;
$mediaPath = $CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathMedia'];

ini_set("display_errors", "on");
ini_set("memory_limit", "512M");
ini_set("max_execution_time", "6000");

getConnection(0); 

$newCount = 41;
$newLang = 2;

$aTabs = array(
				'sys_terms_fe' => 'id_tfe_data',
				'sys_mediapaths' => 'id_mpid_data',
				'sys_mediafiles' => 'id_mid_data',
				'sys_clients' => 'id_cl_data',
				'sys_clientprofiles' => 'id_clp_data',
				'_textmoduls' => 'id_tm_data',
				'_templates' => 'id_temp_data',
				'_templatespages' => 'id_tp_data',
				'_templatespageselements' => 'id_tpe_data',
				'_templatecomponents' => 'id_tc_data',
				'_templatecomponents2categories_assets' => 'id_tc2ca_data',
				'_promotions' => 'id_prom_data',
				'_productsimages' => 'id_pi_data',
				'_emailnotifications' => 'id_en_data',
				'_categories_specsheets' => 'id_css_data',
				'_categories_brochures' => 'id_cb_data',
				'_categories_assets' => 'id_ca_data',
				'_campaigns' => 'id_camp_data',
				'_bannerformats' => 'id_bf_data',
				);

foreach($aTabs as $tab => $id){
	$tab = $tab . '_uni';

	$qry = '';
	$qry .= 'DROP TEMPORARY TABLE IF EXISTS tmp; ';
	$qry .= 'CREATE TEMPORARY TABLE tmp LIKE ' . $CONFIG['db'][0]['prefix'] . $tab . '; ';
	$query = $CONFIG['dbconn'][0]->prepare($qry);
	$query->execute();
	
	
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . $tab . '.' . $id . ' AS id
										FROM ' . $CONFIG['db'][0]['prefix'] . $tab . ' 
										WHERE ' . $CONFIG['db'][0]['prefix'] . $tab . '.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . $tab . '.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . $tab . '.id_dev = (:id_dev)
											AND ' . $CONFIG['db'][0]['prefix'] . $tab . '.id_cl IN(0,' . $CONFIG['activeSettings']['id_clid'] . ')
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':id_count', 0, PDO::PARAM_INT);
	$query->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$query->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();

	foreach($rows as $row){
		$qry = 'TRUNCATE tmp; ';
		$qry .= 'INSERT tmp SELECT * FROM ' . $CONFIG['db'][0]['prefix'] . $tab . ' WHERE ' . $CONFIG['db'][0]['prefix'] . $tab . '.' . $id . ' = ' . $row['id'] . '; ';
		$qry .= 'UPDATE tmp SET ' . $id . ' = NULL; ';
		$qry .= 'UPDATE tmp SET id_count = ' . $newCount . '; ';
		$qry .= 'UPDATE tmp SET id_lang = ' . $newLang . '; ';
		$qry .= 'INSERT ' . $CONFIG['db'][0]['prefix'] . $tab . ' SELECT * FROM tmp; ';
		$qry .= 'TRUNCATE tmp; ';
		$query2 = $CONFIG['dbconn'][0]->prepare($qry);
//////		$query2->execute();
	}
}



?>