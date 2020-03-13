<?php
$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$aT = array('', 'ext', 'loc', 'uni');
	

$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_count,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_lang,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_dev,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_cl,
										' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.data
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
									');
$query->bindValue(':id', 0, PDO::PARAM_INT);
$query->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
$query->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

foreach($rows as $row){
	####################################################################
	// read from Product DB
	$aDataTmp = json_decode($row['data'], true);
	
	getConnection(1); 
	$queryD = $CONFIG['dbconn'][1]->prepare(' 
										SELECT ' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.id_count_data,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.id_countid,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.id_count,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.id_lang,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.id_dev,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.id_clid,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.country,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.code,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.code_add,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.id_tz,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.id_fd,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.id_ft,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.currency,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.tax_name,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.tax,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.fee_name,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.sep_decimal,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.sep_thousand,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.email_sender,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.email_sendername,
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.active,
											
											' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni.id_lang_data,
											' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni.id_langid,
											' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni.id_count AS id_count_lang,
											' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni.id_lang AS id_lang_lang,
											' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni.id_dev AS id_dev_lang,
											' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni.id_clid AS id_clid_lang,
											' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni.language,
											' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni.code AS code_lang,
											' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni.code_add AS code_add_lang,
											' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni.active AS active_lang,
											
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages.id_count2lang, 
											' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages.default_ 
											
										FROM ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages 

										INNER JOIN ' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni 
											ON ' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages.id_countid

										INNER JOIN ' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni 
											ON ' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages.id_langid
		
										WHERE ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages.id_count2lang = (:id_count2lang)
											AND ' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
										');
	$queryD->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryD->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryD->bindValue(':id_count2lang', $aDataTmp['id_count2lang'], PDO::PARAM_INT);
	$queryD->execute();
	$rowsD = $queryD->fetchAll(PDO::FETCH_ASSOC);
	$numD = $queryD->rowCount();
	
	$rowsD[0]['sep_decimal'] = ($rowsD[0]['sep_decimal'] == ',') ? 1 : 2;
	$rowsD[0]['sep_thousand'] = ($rowsD[0]['sep_thousand'] == '.') ? 2 : 1;

	
	
	####################################################################
	####################################################################
	// insert into CC
	getConnection(0); 

	####################################################################
	// language
	$aUploadedFilesId = array();
	
	$aArgs = array();
	$aArgs['id_count'] = $CONFIG['settings']['selectCountry'];
	$aArgs['id_lang'] = $CONFIG['settings']['selectLanguage'];
	$aArgs['id_dev'] = $CONFIG['settings']['selectDevice'];
	$aArgs['usesystem'] = 1;
	$aArgs['fields'] = array();
	
	$aFieldsSaveMaster = array();
	$aFieldsSaveNotMaster = array();
	
	$aArgsSaveN = array();
	
	$aArgsSave = array();
	$aArgsSave['id_data'] = $rowsD[0]['id_langid'];
	$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . 'sys_languages_';
	$aArgsSave['primarykey'] = 'id_langid';
	$aArgsSave['allVersions'] = array();
	$aArgsSave['changedVersions'] = array();
	
	$aArgsSave['columns'] = array();
	$aArgsSave['columns']['id_langid'] = array('i');
	$aArgsSave['columns']['language'] = array('s');
	$aArgsSave['columns']['code'] = array('s');
	$aArgsSave['columns']['code_add'] = array('s');
	$aArgsSave['columns']['active'] = array('i');
	$aArgsSave['aFieldsNumbers'] = array('active');
	$aArgsSave['excludeUpdateUni'] = array();
	$aArgsSave['excludeUpdateUni']['id_langid'] = array('');	
	$aArgsSave['excludeUpdateUni']['language'] = array('');	
	$aArgsSave['excludeUpdateUni']['code'] = array('');	
	$aArgsSave['excludeUpdateUni']['code_add'] = array('');	
	$aArgsSave['excludeUpdateUni']['active'] = array('');	
	
	$aArgsLV = array();
	$aArgsLV['type'] = 'all';
	$aLocalVersions = localVariationsBuild($aArgsLV);
	
	
	$queryI = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_.id_langid
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_ 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_.id_langid = (:id_langid)
										');
	$queryI->bindValue(':id_langid', $rowsD[0]['id_langid'], PDO::PARAM_INT);
	$queryI->execute();
	$rowsI = $queryI->fetchAll(PDO::FETCH_ASSOC);
	$numI = $queryI->rowCount();
	
	if($numI == 0){
		$query = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $aArgsSave['table'] . '
											(id_langid, id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
											VALUES
											(:id_langid, :nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
											');
		$query->bindValue(':id_langid', $rowsD[0]['id_langid'], PDO::PARAM_INT);
		$query->bindValue(':nul', 0, PDO::PARAM_INT);
		$query->bindValue(':id_cl', $rows[0]['id_cl'], PDO::PARAM_INT);
		$query->bindValue(':create_at', $now, PDO::PARAM_STR);
		$query->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
		$query->execute();
		

		$query = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_loc
											(id_lang_data, id_langid, id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from, language, code, code_add, active)
											VALUES
											(:id_lang_data, :id_langid, :id_count, :id_lang, :id_dev, :id_cl, :create_at, :create_from, :create_from, :language, :code, :code_add, :active)
											');
		$query->bindValue(':id_lang_data', $rowsD[0]['id_lang_data'], PDO::PARAM_INT);
		$query->bindValue(':id_langid', $rowsD[0]['id_langid'], PDO::PARAM_INT);
		$query->bindValue(':id_count', 0, PDO::PARAM_INT);
		$query->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$query->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$query->bindValue(':id_cl', $rows[0]['id_cl'], PDO::PARAM_INT);
		$query->bindValue(':create_at', $now, PDO::PARAM_STR);
		$query->bindValue(':create_from', $CONFIG['user']['id'], PDO::PARAM_INT);
		$query->bindValue(':language', $rowsD[0]['language'], PDO::PARAM_STR);
		$query->bindValue(':code', $rowsD[0]['code_lang'], PDO::PARAM_STR);
		$query->bindValue(':code_add', $rowsD[0]['code_add_lang'], PDO::PARAM_STR);
		$query->bindValue(':active', $rowsD[0]['active_lang'], PDO::PARAM_INT);
		$query->execute();
	}else{
		foreach($aT as $t){
			$query = $CONFIG['dbconn'][0]->prepare('
												UPDATE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_' . $t . ' SET
													del = (:nultime)
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_' . $t . '.id_langid = (:id_langid)
												');
			$query->bindValue(':id_langid', $rowsD[0]['id_langid'], PDO::PARAM_INT);
			$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query->execute();
		}
	}

	#########################################
	
	$aArgsSave['changedVersions'] = array(array(0,0,0));
	$aArgsSave['allVersions'] = $aLocalVersions;
	insertAll($aArgsSave);

	#########################################
	#########################################

	####################################################################
	// country
	$aUploadedFilesId = array();
	
	$aArgs = array();
	$aArgs['id_count'] = $CONFIG['settings']['selectCountry'];
	$aArgs['id_lang'] = $CONFIG['settings']['selectLanguage'];
	$aArgs['id_dev'] = $CONFIG['settings']['selectDevice'];
	$aArgs['usesystem'] = 1;
	$aArgs['fields'] = array();
	
	$aFieldsSaveMaster = array();
	$aFieldsSaveNotMaster = array();
	
	$aArgsSaveN = array();
	
	$aArgsSave = array();
	$aArgsSave['id_data'] = $rowsD[0]['id_countid'];
	$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . 'sys_countries_';
	$aArgsSave['primarykey'] = 'id_countid';
	$aArgsSave['allVersions'] = array();
	$aArgsSave['changedVersions'] = array();
	
	$aArgsSave['columns'] = array();
	$aArgsSave['columns']['id_countid'] = array('i');
	$aArgsSave['columns']['country'] = array('s');
	$aArgsSave['columns']['code'] = array('s');
	$aArgsSave['columns']['code_add'] = array('s');
	$aArgsSave['columns']['id_tz'] = array('i');
	$aArgsSave['columns']['id_fd'] = array('i');
	$aArgsSave['columns']['id_ft'] = array('i');
	$aArgsSave['columns']['currency'] = array('s');
	$aArgsSave['columns']['tax_name'] = array('s');
	$aArgsSave['columns']['tax'] = array('s');
	$aArgsSave['columns']['fee_name'] = array('s');
	$aArgsSave['columns']['id_fs_decimal'] = array('i');
	$aArgsSave['columns']['id_fs_thousand'] = array('i');
	$aArgsSave['columns']['email_sender'] = array('s');
	$aArgsSave['columns']['email_sendername'] = array('s');
	$aArgsSave['columns']['active'] = array('i');
	$aArgsSave['aFieldsNumbers'] = array('active', 'id_tz', 'id_fd', 'id_ft', 'id_fs_decimal', 'id_fs_thousand');
	$aArgsSave['excludeUpdateUni'] = array();
	$aArgsSave['excludeUpdateUni']['id_countid'] = array('');	
	$aArgsSave['excludeUpdateUni']['country'] = array('');	
	$aArgsSave['excludeUpdateUni']['code'] = array('');	
	$aArgsSave['excludeUpdateUni']['code_add'] = array('');	
	$aArgsSave['excludeUpdateUni']['id_tz'] = array('');	
	$aArgsSave['excludeUpdateUni']['id_fd'] = array('');	
	$aArgsSave['excludeUpdateUni']['id_ft'] = array('');	
	$aArgsSave['excludeUpdateUni']['currency'] = array('');	
	$aArgsSave['excludeUpdateUni']['tax_name'] = array('');	
	$aArgsSave['excludeUpdateUni']['tax'] = array('');	
	$aArgsSave['excludeUpdateUni']['fee_name'] = array('');	
	$aArgsSave['excludeUpdateUni']['id_fs_decimal'] = array('');	
	$aArgsSave['excludeUpdateUni']['id_fs_thousand'] = array('');	
	$aArgsSave['excludeUpdateUni']['email_sender'] = array('');	
	$aArgsSave['excludeUpdateUni']['email_sendername'] = array('');	
	$aArgsSave['excludeUpdateUni']['active'] = array('');	
	
	
	$aArgsLV = array();
	$aArgsLV['type'] = 'all';
	$aLocalVersions = localVariationsBuild($aArgsLV);
	
	
	$queryI = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_.id_countid
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_ 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_.id_countid = (:id_countid)
										');
	$queryI->bindValue(':id_countid', $rowsD[0]['id_countid'], PDO::PARAM_INT);
	$queryI->execute();
	$rowsI = $queryI->fetchAll(PDO::FETCH_ASSOC);
	$numI = $queryI->rowCount();
	
	if($numI == 0){
		$query = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $aArgsSave['table'] . '
											(id_countid, id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
											VALUES
											(:id_countid, :nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
											');
		$query->bindValue(':id_countid', $rowsD[0]['id_countid'], PDO::PARAM_INT);
		$query->bindValue(':nul', 0, PDO::PARAM_INT);
		$query->bindValue(':id_cl', $rows[0]['id_cl'], PDO::PARAM_INT);
		$query->bindValue(':create_at', $now, PDO::PARAM_STR);
		$query->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT);
		$query->execute();
		

		$query = $CONFIG['dbconn'][0]->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_loc
											(id_count_data, id_countid, id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from, country, code, code_add, id_tz, id_fd, id_ft, currency, tax_name, tax, fee_name, id_fs_decimal, id_fs_thousand, email_sender, email_sendername, active)
											VALUES
											(:id_count_data, :id_countid, :id_count, :id_lang, :id_dev, :id_cl, :create_at, :create_from, :create_from, :country, :code, :code_add, :id_tz, :id_fd, :id_ft, :currency, :tax_name, :tax, :fee_name, :id_fs_decimal, :id_fs_thousand, :email_sender, :email_sendername, :active)
											');
		$query->bindValue(':id_count_data', $rowsD[0]['id_count_data'], PDO::PARAM_INT);
		$query->bindValue(':id_countid', $rowsD[0]['id_countid'], PDO::PARAM_INT);
		$query->bindValue(':id_count', 0, PDO::PARAM_INT);
		$query->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$query->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$query->bindValue(':id_cl', $rows[0]['id_cl'], PDO::PARAM_INT);
		$query->bindValue(':create_at', $now, PDO::PARAM_STR);
		$query->bindValue(':create_from', $CONFIG['user']['id'], PDO::PARAM_INT);
		$query->bindValue(':country', $rowsD[0]['country'], PDO::PARAM_STR);
		$query->bindValue(':code', $rowsD[0]['code'], PDO::PARAM_STR);
		$query->bindValue(':code_add', $rowsD[0]['code_add'], PDO::PARAM_STR);
		$query->bindValue(':id_tz', $rowsD[0]['id_tz'], PDO::PARAM_INT);
		$query->bindValue(':id_fd', $rowsD[0]['id_fd'], PDO::PARAM_INT);
		$query->bindValue(':id_ft', $rowsD[0]['id_ft'], PDO::PARAM_INT);
		$query->bindValue(':currency', $rowsD[0]['currency'], PDO::PARAM_STR);
		$query->bindValue(':tax_name', $rowsD[0]['tax_name'], PDO::PARAM_STR);
		$query->bindValue(':tax', $rowsD[0]['tax'], PDO::PARAM_STR);
		$query->bindValue(':fee_name', $rowsD[0]['fee_name'], PDO::PARAM_STR);
		$query->bindValue(':id_fs_decimal', $rowsD[0]['sep_decimal'], PDO::PARAM_STR);
		$query->bindValue(':id_fs_thousand', $rowsD[0]['sep_thousand'], PDO::PARAM_STR);
		$query->bindValue(':email_sender', $rowsD[0]['email_sender'], PDO::PARAM_STR);
		$query->bindValue(':email_sendername', $rowsD[0]['email_sendername'], PDO::PARAM_STR);
		$query->bindValue(':active', $rowsD[0]['active'], PDO::PARAM_INT);
		$query->execute();
	}else{
		foreach($aT as $t){
			$query = $CONFIG['dbconn'][0]->prepare('
												UPDATE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_' . $t . ' SET
													del = (:nultime)
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_' . $t . '.id_countid = (:id_countid)
												');
			$query->bindValue(':id_countid', $rowsD[0]['id_countid'], PDO::PARAM_INT);
			$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$query->execute();
		}
	}

	#########################################
	
	$aArgsSave['changedVersions'] = array(array(0,0,0));
	$aArgsSave['allVersions'] = $aLocalVersions;
	insertAll($aArgsSave);

	#########################################
	#########################################
	
	####################################################################
	// country2language
	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc.id_count2lang
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc.id_countid = (:id_countid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc.id_langid = (:id_langid)
										');
	$query->bindValue(':id_countid', $rowsD[0]['id_countid'], PDO::PARAM_INT);
	$query->bindValue(':id_langid', 0, PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	
	if($num == 0){
		getConnection(1); 
		$queryD2 = $CONFIG['dbconn'][1]->prepare('
											SELECT ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages.id_count2lang
											FROM ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages 
											WHERE ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages.id_countid = (:id_countid)
												AND ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages.id_langid = (:id_langid)
											');
		$queryD2->bindValue(':id_countid', $rowsD[0]['id_countid'], PDO::PARAM_INT);
		$queryD2->bindValue(':id_langid', 0, PDO::PARAM_INT);
		$queryD2->execute();
		$rowsD2 = $queryD2->fetchAll(PDO::FETCH_ASSOC);
		$numD2 = $queryD2->rowCount();

		getConnection(0); 
		$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_
					(id_count2lang, id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
				VALUES
					(:id_count2lang, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from)
				';
		$queryP = $CONFIG['dbconn'][0]->prepare($qry);
		$queryP->bindValue(':id_count2lang', $rowsD2[0]['id_count2lang'], PDO::PARAM_INT);
		$queryP->bindValue(':id_count', 0, PDO::PARAM_INT);
		$queryP->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$queryP->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$queryP->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
		$queryP->bindValue(':now', $now, PDO::PARAM_STR);
		$queryP->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
		$queryP->execute();
		$idNewP = $rowsD2[0]['id_count2lang'];
	}else{
		$idNewP = $rows[0]['id_count2lang'];
	}

	$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc
				(id_count2lang, id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from, id_countid, id_langid)
			VALUES
				(:id, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from, :id_countid, :id_langid)
			ON DUPLICATE KEY UPDATE 
				change_from = (:create_from),
				del = (:nultime)
			';
	$queryP = $CONFIG['dbconn'][0]->prepare($qry);
	$queryP->bindValue(':id', $idNewP, PDO::PARAM_INT);
	$queryP->bindValue(':id_count', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
	$queryP->bindValue(':now', $now, PDO::PARAM_STR);
	$queryP->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryP->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
	$queryP->bindValue(':id_countid', $rowsD[0]['id_countid'], PDO::PARAM_INT);
	$queryP->bindValue(':id_langid', 0, PDO::PARAM_INT);
	$queryP->execute();
	$numP = $queryP->rowCount();
		
		
	$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
				(id_count2lang, id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from, id_countid, id_langid)
			VALUES
				(:id, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from, :id_countid, :id_langid)
			ON DUPLICATE KEY UPDATE 
				change_from = (:create_from),
				del = (:nultime)
			';
	$queryP = $CONFIG['dbconn'][0]->prepare($qry);
	$queryP->bindValue(':id', $idNewP, PDO::PARAM_INT);
	$queryP->bindValue(':id_count', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
	$queryP->bindValue(':now', $now, PDO::PARAM_STR);
	$queryP->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryP->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
	$queryP->bindValue(':id_countid', $rowsD[0]['id_countid'], PDO::PARAM_INT);
	$queryP->bindValue(':id_langid', 0, PDO::PARAM_INT);
	$queryP->execute();
	$numP = $queryP->rowCount();
	#######

	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc.id_count2lang
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc.id_countid = (:id_countid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc.id_langid = (:id_langid)
										');
	$query->bindValue(':id_countid', 0, PDO::PARAM_INT);
	$query->bindValue(':id_langid', $rowsD[0]['id_langid'], PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	
	if($num == 0){
		getConnection(1); 
		$queryD2 = $CONFIG['dbconn'][1]->prepare('
											SELECT ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages.id_count2lang
											FROM ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages 
											WHERE ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages.id_countid = (:id_countid)
												AND ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages.id_langid = (:id_langid)
											');
		$queryD2->bindValue(':id_countid', 0, PDO::PARAM_INT);
		$queryD2->bindValue(':id_langid', $rowsD[0]['id_langid'], PDO::PARAM_INT);
		$queryD2->execute();
		$rowsD2 = $queryD2->fetchAll(PDO::FETCH_ASSOC);
		$numD2 = $queryD2->rowCount();

		getConnection(0); 
		$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_
					(id_count2lang, id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
				VALUES
					(:id_count2lang, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from)
				';
		$queryP = $CONFIG['dbconn'][0]->prepare($qry);
		$queryP->bindValue(':id_count2lang', $rowsD2[0]['id_count2lang'], PDO::PARAM_INT);
		$queryP->bindValue(':id_count', 0, PDO::PARAM_INT);
		$queryP->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$queryP->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$queryP->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
		$queryP->bindValue(':now', $now, PDO::PARAM_STR);
		$queryP->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
		$queryP->execute();
		$idNewP = $rowsD2[0]['id_count2lang'];
	}else{
		$idNewP = $rows[0]['id_count2lang'];
	}

	$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc
				(id_count2lang, id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from, id_countid, id_langid)
			VALUES
				(:id, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from, :id_countid, :id_langid)
			ON DUPLICATE KEY UPDATE 
				change_from = (:create_from),
				del = (:nultime)
			';
	$queryP = $CONFIG['dbconn'][0]->prepare($qry);
	$queryP->bindValue(':id', $idNewP, PDO::PARAM_INT);
	$queryP->bindValue(':id_count', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
	$queryP->bindValue(':now', $now, PDO::PARAM_STR);
	$queryP->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryP->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
	$queryP->bindValue(':id_countid', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_langid', $rowsD[0]['id_langid'], PDO::PARAM_INT);
	$queryP->execute();
	$numP = $queryP->rowCount();
		
		
	$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
				(id_count2lang, id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from, id_countid, id_langid)
			VALUES
				(:id, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from, :id_countid, :id_langid)
			ON DUPLICATE KEY UPDATE 
				change_from = (:create_from),
				del = (:nultime)
			';
	$queryP = $CONFIG['dbconn'][0]->prepare($qry);
	$queryP->bindValue(':id', $idNewP, PDO::PARAM_INT);
	$queryP->bindValue(':id_count', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
	$queryP->bindValue(':now', $now, PDO::PARAM_STR);
	$queryP->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryP->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
	$queryP->bindValue(':id_countid', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_langid', $rowsD[0]['id_langid'], PDO::PARAM_INT);
	$queryP->execute();
	$numP = $queryP->rowCount();
	#######

	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc.id_count2lang
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc.id_countid = (:id_countid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc.id_langid = (:id_langid)
										');
	$query->bindValue(':id_countid', $rowsD[0]['id_countid'], PDO::PARAM_INT);
	$query->bindValue(':id_langid', $rowsD[0]['id_langid'], PDO::PARAM_INT);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	
	if($num == 0){
		getConnection(1); 
		$queryD2 = $CONFIG['dbconn'][1]->prepare('
											SELECT ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages.id_count2lang
											FROM ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages 
											WHERE ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages.id_countid = (:id_countid)
												AND ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages.id_langid = (:id_langid)
											');
		$queryD2->bindValue(':id_countid', $rowsD[0]['id_countid'], PDO::PARAM_INT);
		$queryD2->bindValue(':id_langid', $rowsD[0]['id_langid'], PDO::PARAM_INT);
		$queryD2->execute();
		$rowsD2 = $queryD2->fetchAll(PDO::FETCH_ASSOC);
		$numD2 = $queryD2->rowCount();

		getConnection(0); 
		$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_
					(id_count2lang, id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
				VALUES
					(:id_count2lang, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from)
				';
		$queryP = $CONFIG['dbconn'][0]->prepare($qry);
		$queryP->bindValue(':id_count2lang', $rowsD2[0]['id_count2lang'], PDO::PARAM_INT);
		$queryP->bindValue(':id_count', 0, PDO::PARAM_INT);
		$queryP->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$queryP->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$queryP->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
		$queryP->bindValue(':now', $now, PDO::PARAM_STR);
		$queryP->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
		$queryP->execute();
		$idNewP = $rowsD2[0]['id_count2lang'];
	}else{
		$idNewP = $rows[0]['id_count2lang'];
	}

	$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_loc
				(id_count2lang, id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from, id_countid, id_langid)
			VALUES
				(:id, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from, :id_countid, :id_langid)
			ON DUPLICATE KEY UPDATE 
				change_from = (:create_from),
				del = (:nultime)
			';
	$queryP = $CONFIG['dbconn'][0]->prepare($qry);
	$queryP->bindValue(':id', $idNewP, PDO::PARAM_INT);
	$queryP->bindValue(':id_count', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
	$queryP->bindValue(':now', $now, PDO::PARAM_STR);
	$queryP->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryP->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
	$queryP->bindValue(':id_countid', $rowsD[0]['id_countid'], PDO::PARAM_INT);
	$queryP->bindValue(':id_langid', $rowsD[0]['id_langid'], PDO::PARAM_INT);
	$queryP->execute();
	$numP = $queryP->rowCount();
		
		
	$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni
				(id_count2lang, id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from, id_countid, id_langid)
			VALUES
				(:id, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from, :id_countid, :id_langid)
			ON DUPLICATE KEY UPDATE 
				change_from = (:create_from),
				del = (:nultime)
			';
	$queryP = $CONFIG['dbconn'][0]->prepare($qry);
	$queryP->bindValue(':id', $idNewP, PDO::PARAM_INT);
	$queryP->bindValue(':id_count', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_lang', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_dev', 0, PDO::PARAM_INT);
	$queryP->bindValue(':id_cl', $CONFIG['activeSettings']['id_clid'], PDO::PARAM_INT);
	$queryP->bindValue(':now', $now, PDO::PARAM_STR);
	$queryP->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryP->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
	$queryP->bindValue(':id_countid', $rowsD[0]['id_countid'], PDO::PARAM_INT);
	$queryP->bindValue(':id_langid', $rowsD[0]['id_langid'], PDO::PARAM_INT);
	$queryP->execute();
	$numP = $queryP->rowCount();
}

	
#########################################

$query2 = $CONFIG['dbconn'][0]->prepare('
									DELETE 
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id = (:id)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_uid = (:uid)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_tempdata.id_mod = (:id_mod)
									');
$query2->bindValue(':id', 0, PDO::PARAM_INT);
$query2->bindValue(':uid', $CONFIG['user']['id'], PDO::PARAM_INT);
$query2->bindValue(':id_mod', $CONFIG['page']['id_mod'], PDO::PARAM_INT);
$query2->execute();

	
#########################################

$out = array();
$out['id_data'] = $aArgsSave['id_data'];

echo json_encode($out);

?>