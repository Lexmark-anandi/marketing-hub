<?php
$CONFIG['system']['pathInclude'] = "";
include_once($CONFIG['system']['pathInclude'] . 'app/config-app.php');
unset($_SESSION['app']['USER']);

if(empty($_POST)){
	$CONTENT = '<div class="accesserror">' . $TEXT['accessError'] . '</div>';
	
###########################	
}else{
###########################	
	getConnection(0); 
	$varSQL = getPostData();

	$_SESSION['app']['USER'] = array();
	foreach($varSQL as $key=>$val){
		$_SESSION['app']['USER'][$key] = $val;
	}
	$_SESSION['app']['USER']['lang'] = $_SESSION['app']['USER']['LANG'];
	$_SESSION['app']['USER']['COUNTID_SUB'] = 0;
	$_SESSION['app']['USER']['COUNTRY_SUB'] = '';
	

	
	// Select subcountry
	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'subcountries_data_full.id_subcountid,
											' . $CONFIG['db'][0]['prefix'] . 'subcountries_data_full.id_count_parent,
											' . $CONFIG['db'][0]['prefix'] . 'subcountries_data_full.currency,
											' . $CONFIG['db'][0]['prefix'] . 'subcountries_data_full.tax_name,
											' . $CONFIG['db'][0]['prefix'] . 'subcountries_data_full.tax,
											' . $CONFIG['db'][0]['prefix'] . 'subcountries_data_full.sep_thousand,
											' . $CONFIG['db'][0]['prefix'] . 'subcountries_data_full.sep_decimal,
											' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.code AS code_country,
											' . $CONFIG['db'][0]['prefix_sys'] . 'format_date.format,
											' . $CONFIG['db'][0]['prefix_sys'] . 'format_date.code
										FROM ' . $CONFIG['db'][0]['prefix'] . 'subcountries_data_full 
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full
											ON (' . $CONFIG['db'][0]['prefix'] . 'subcountries_data_full.id_count_parent = ' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.id_countid
												AND ' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.id_count = (:count)
												AND ' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.id_lang = (:lang)
												AND ' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.id_dev = (:dev))
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix_sys'] . 'format_date
											ON (' . $CONFIG['db'][0]['prefix'] . 'subcountries_data_full.id_fd = ' . $CONFIG['db'][0]['prefix_sys'] . 'format_date.id_fd
												AND ' . $CONFIG['db'][0]['prefix_sys'] . 'format_date.del = (:nultime))
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'subcountries_data_full.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'subcountries_data_full.id_count = (:count)
											AND ' . $CONFIG['db'][0]['prefix'] . 'subcountries_data_full.id_lang = (:lang)
											AND ' . $CONFIG['db'][0]['prefix'] . 'subcountries_data_full.id_dev = (:dev)
											AND ' . $CONFIG['db'][0]['prefix'] . 'subcountries_data_full.code = (:code)
											AND ' . $CONFIG['db'][0]['prefix'] . 'subcountries_data_full.active = (:active)
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':active', 1, PDO::PARAM_INT);
	$query->bindValue(':count', 0, PDO::PARAM_INT);
	$query->bindValue(':lang', 0, PDO::PARAM_INT);
	$query->bindValue(':dev', 0, PDO::PARAM_INT);
	$query->bindValue(':code', $_SESSION['app']['USER']['COUNTRY'], PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$numSC = $query->rowCount();
	
	if($numSC > 0){	
		$_SESSION['app']['USER']['COUNTID_SUB'] = $rows[0]['id_subcountid'];
		$_SESSION['app']['USER']['COUNTID'] = $rows[0]['id_count_parent'];
		$_SESSION['app']['USER']['COUNTRY_SUB'] = $_SESSION['app']['USER']['COUNTRY'];
		$_SESSION['app']['USER']['COUNTRY'] = $rows[0]['code_country'];
		$_SESSION['app']['USER']['CURRENCY'] = $rows[0]['currency'];
		$_SESSION['app']['USER']['TAXNAME'] = $rows[0]['tax_name'];
		$_SESSION['app']['USER']['TAX'] = $rows[0]['tax'];
		$_SESSION['app']['USER']['SEPTHOUSAND'] = $rows[0]['sep_thousand'];
		$_SESSION['app']['USER']['SEPDECIMAL'] = $rows[0]['sep_decimal'];
		$_SESSION['app']['USER']['FORMAT'] = $rows[0]['format'];
		$_SESSION['app']['USER']['FORMATCODE'] = $rows[0]['code'];
		$_SESSION['app']['USER']['LXK_CUSTOMERSHARE'] = $rows[0]['lxk_customer_share'];
		$_SESSION['app']['USER']['LXK_LOGOEMAIL'] = $rows[0]['lxk_logo_email'];
	}
	

	// Select country
	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.id_countid,
											' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.currency,
											' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.tax_name,
											' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.tax,
											' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.sep_thousand,
											' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.sep_decimal,
											' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.lxk_customer_share,
											' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.lxk_logo_email,
											' . $CONFIG['db'][0]['prefix_sys'] . 'format_date.format,
											' . $CONFIG['db'][0]['prefix_sys'] . 'format_date.code
										FROM ' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full 
										
										LEFT JOIN ' . $CONFIG['db'][0]['prefix_sys'] . 'format_date
											ON (' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.id_fd = ' . $CONFIG['db'][0]['prefix_sys'] . 'format_date.id_fd
												AND ' . $CONFIG['db'][0]['prefix_sys'] . 'format_date.del = (:nultime))
										
										WHERE ' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.id_count = (:count)
											AND ' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.id_lang = (:lang)
											AND ' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.id_dev = (:dev)
											AND ' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.code = (:code)
											AND ' . $CONFIG['db'][0]['prefix_sys'] . 'countries_data_full.active = (:active)
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':active', 1, PDO::PARAM_INT);
	$query->bindValue(':count', 0, PDO::PARAM_INT);
	$query->bindValue(':lang', 0, PDO::PARAM_INT);
	$query->bindValue(':dev', 0, PDO::PARAM_INT);
	$query->bindValue(':code', $_SESSION['app']['USER']['COUNTRY'], PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	
	if($numSC == 0){
		$_SESSION['app']['USER']['COUNTID'] = $rows[0]['id_countid'];
		$_SESSION['app']['USER']['CURRENCY'] = $rows[0]['currency'];
		$_SESSION['app']['USER']['TAXNAME'] = $rows[0]['tax_name'];
		$_SESSION['app']['USER']['TAX'] = $rows[0]['tax'];
		$_SESSION['app']['USER']['SEPTHOUSAND'] = $rows[0]['sep_thousand'];
		$_SESSION['app']['USER']['SEPDECIMAL'] = $rows[0]['sep_decimal'];
		$_SESSION['app']['USER']['FORMAT'] = $rows[0]['format'];
		$_SESSION['app']['USER']['FORMATCODE'] = $rows[0]['code'];
	}
	$_SESSION['app']['USER']['LXK_CUSTOMERSHARE'] = $rows[0]['lxk_customer_share'];
	$_SESSION['app']['USER']['LXK_LOGOEMAIL'] = $rows[0]['lxk_logo_email'];
	
	
	// Select language
	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix_sys'] . 'languages_data_full.id_langid
										FROM ' . $CONFIG['db'][0]['prefix_sys'] . 'languages_data_full 
										
										WHERE ' . $CONFIG['db'][0]['prefix_sys'] . 'languages_data_full.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix_sys'] . 'languages_data_full.id_count = (:count)
											AND ' . $CONFIG['db'][0]['prefix_sys'] . 'languages_data_full.id_lang = (:lang)
											AND ' . $CONFIG['db'][0]['prefix_sys'] . 'languages_data_full.id_dev = (:dev)
											AND ' . $CONFIG['db'][0]['prefix_sys'] . 'languages_data_full.code = (:code)
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':count', 0, PDO::PARAM_INT);
	$query->bindValue(':lang', 0, PDO::PARAM_INT);
	$query->bindValue(':dev', 0, PDO::PARAM_INT);
	$query->bindValue(':code', $_SESSION['app']['USER']['LANG'], PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	
	$_SESSION['app']['USER']['LANGID'] = $rows[0]['id_langid'];
	
	
	// Select logo
	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.id_partnerid,
											' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.logo,
											' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.filename,
											' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.filesys_filename,
											' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.filehash
										FROM ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full 
	
										LEFT JOIN ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full 
											ON ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.logo = ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.id_mid
												AND (' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.id_count = (:count) OR ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.id_count IS NULL)
												AND (' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.id_lang = (:lang) OR ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.id_lang IS NULL)
												AND (' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.id_dev = (:dev) OR ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.id_dev IS NULL)
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.id_count = (:count)
											AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.id_lang = (:lang)
											AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.id_dev = (:dev)
											AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.organisationid = (:organisationid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.logo <> (:nul)
										LIMIT 1
										');
	$query->bindValue(':nul', 0, PDO::PARAM_INT);
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':count', 0, PDO::PARAM_INT);
	$query->bindValue(':lang', 0, PDO::PARAM_INT);
	$query->bindValue(':dev', 0, PDO::PARAM_INT);
	$query->bindValue(':organisationid', $_SESSION['app']['USER']['ORGANISATIONID'], PDO::PARAM_STR);
	$query->bindValue(':reseller_id', $_SESSION['app']['USER']['RESELLER_ID'], PDO::PARAM_STR);
	$query->bindValue(':userid', $_SESSION['app']['USER']['USERID'], PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	$_SESSION['app']['USER']['LOGO_ID'] = $rows[0]['logo'];
	if($rows[0]['logo'] == '') $_SESSION['app']['USER']['LOGO_ID'] = 0;
	$_SESSION['app']['USER']['LOGO_FILENAME'] = $rows[0]['filename'];
	$_SESSION['app']['USER']['LOGO_FILESYSFILENAME'] = $rows[0]['filesys_filename'];
	$_SESSION['app']['USER']['LOGO_FILEHASH'] = $rows[0]['filehash'];
	

	// Select pricetypes
	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'prices_data_full.file_price_a,
											' . $CONFIG['db'][0]['prefix'] . 'prices_data_full.file_price_b
										FROM ' . $CONFIG['db'][0]['prefix'] . 'prices_data_full 
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'prices_data_full.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'prices_data_full.id_count = (:count)
											AND ' . $CONFIG['db'][0]['prefix'] . 'prices_data_full.id_lang = (:lang)
											AND ' . $CONFIG['db'][0]['prefix'] . 'prices_data_full.id_dev = (:dev)
											AND ' . $CONFIG['db'][0]['prefix'] . 'prices_data_full.id_country = (:id_country)
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':count', 0, PDO::PARAM_INT);
	$query->bindValue(':lang', 0, PDO::PARAM_INT);
	$query->bindValue(':dev', 0, PDO::PARAM_INT);
	$query->bindValue(':id_country', $_SESSION['app']['USER']['COUNTID'], PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	$_SESSION['app']['USER']['PRICETYPE_A'] = $rows[0]['file_price_a'];
	$_SESSION['app']['USER']['PRICETYPE_B'] = $rows[0]['file_price_b'];
	

	// Select / insert partner
	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.id_partnerid
										FROM ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full 
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.id_count = (:count)
											AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.id_lang = (:lang)
											AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.id_dev = (:dev)
											AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.organisationid = (:organisationid)
											AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.reseller_id = (:reseller_id)
											AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.userid = (:userid)
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':count', 0, PDO::PARAM_INT);
	$query->bindValue(':lang', 0, PDO::PARAM_INT);
	$query->bindValue(':dev', 0, PDO::PARAM_INT);
	$query->bindValue(':organisationid', $_SESSION['app']['USER']['ORGANISATIONID'], PDO::PARAM_STR);
	$query->bindValue(':reseller_id', $_SESSION['app']['USER']['RESELLER_ID'], PDO::PARAM_STR);
	$query->bindValue(':userid', $_SESSION['app']['USER']['USERID'], PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();
	$_SESSION['app']['USER']['PARTNERID'] = $rows[0]['id_partnerid'];
	
	if(!isset($_SESSION['app']['USER']['OVERVIEW_START'])) $_SESSION['app']['USER']['OVERVIEW_START'] = 0;
	if(!isset($_SESSION['app']['USER']['OVERVIEW_LIMIT'])) $_SESSION['app']['USER']['OVERVIEW_LIMIT'] = 10;
	if(!isset($_SESSION['app']['USER']['OVERVIEW_PAGE'])) $_SESSION['app']['USER']['OVERVIEW_PAGE'] = 1;

	
	if($num == 0){
		$date = new DateTime();
		$now = $date->format('Y-m-d H:i:s');

		$query = $CONFIG['dbconn']->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'partner_id
											(create_at, create_from, change_from)
											VALUES
											(:create_at, :create_from, :create_from)
											');
		$query->bindValue(':create_at', $now, PDO::PARAM_STR);
		$query->bindValue(':create_from', 0, PDO::PARAM_INT);
		$query->execute();
		$idNew = $CONFIG['dbconn']->lastInsertId();
		$_SESSION['app']['USER']['PARTNERID'] = $idNew;
		
		$query = $CONFIG['dbconn']->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'partner_data
											(
												id_partnerid,
												id_count,
												id_lang,
												id_dev,
												organisationid,
												reseller_id,
												userid,
												company_name,
												id_country,
												id_subcountry,
												id_language,
												email,
												logo,
												create_at
											)
											VALUES
											(
												:id_partnerid,
												:id_count,
												:id_lang,
												:id_dev,
												:organisationid,
												:reseller_id,
												:userid,
												:company_name,
												:id_country,
												:id_subcountry,
												:id_language,
												:email,
												:logo,
												:create_at
											)
											');
		$query->bindValue(':id_partnerid', $idNew, PDO::PARAM_INT);
		$query->bindValue(':id_count', 0, PDO::PARAM_INT);
		$query->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$query->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$query->bindValue(':organisationid', $_SESSION['app']['USER']['ORGANISATIONID'], PDO::PARAM_STR);
		$query->bindValue(':reseller_id', $_SESSION['app']['USER']['RESELLER_ID'], PDO::PARAM_STR);
		$query->bindValue(':userid', $_SESSION['app']['USER']['USERID'], PDO::PARAM_STR);
		$query->bindValue(':company_name', $_SESSION['app']['USER']['COMPANY_NAME'], PDO::PARAM_STR);
		$query->bindValue(':id_country', $_SESSION['app']['USER']['COUNTID'], PDO::PARAM_INT);
		$query->bindValue(':id_subcountry', $_SESSION['app']['USER']['COUNTID_SUB'], PDO::PARAM_INT);
		$query->bindValue(':id_language', $_SESSION['app']['USER']['LANGID'], PDO::PARAM_INT);
		$query->bindValue(':email', $_SESSION['app']['USER']['EMAIL'], PDO::PARAM_STR);
		$query->bindValue(':logo', $_SESSION['app']['USER']['LOGO_ID'], PDO::PARAM_INT);
		$query->bindValue(':create_at', $now, PDO::PARAM_STR);
		$query->execute();
		$num = $query->rowCount();
		
		$query = $CONFIG['dbconn']->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full
											(
												id_partnerid,
												id_count,
												id_lang,
												id_dev,
												organisationid,
												reseller_id,
												userid,
												company_name,
												id_country,
												id_subcountry,
												id_language,
												email,
												logo,
												create_at
											)
											VALUES
											(
												:id_partnerid,
												:id_count,
												:id_lang,
												:id_dev,
												:organisationid,
												:reseller_id,
												:userid,
												:company_name,
												:id_country,
												:id_subcountry,
												:id_language,
												:email,
												:logo,
												:create_at
											)
											');
		$query->bindValue(':id_partnerid', $idNew, PDO::PARAM_INT);
		$query->bindValue(':id_count', 0, PDO::PARAM_INT);
		$query->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$query->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$query->bindValue(':organisationid', $_SESSION['app']['USER']['ORGANISATIONID'], PDO::PARAM_STR);
		$query->bindValue(':reseller_id', $_SESSION['app']['USER']['RESELLER_ID'], PDO::PARAM_STR);
		$query->bindValue(':userid', $_SESSION['app']['USER']['USERID'], PDO::PARAM_STR);
		$query->bindValue(':company_name', $_SESSION['app']['USER']['COMPANY_NAME'], PDO::PARAM_STR);
		$query->bindValue(':id_country', $_SESSION['app']['USER']['COUNTID'], PDO::PARAM_INT);
		$query->bindValue(':id_subcountry', $_SESSION['app']['USER']['COUNTID_SUB'], PDO::PARAM_INT);
		$query->bindValue(':id_language', $_SESSION['app']['USER']['LANGID'], PDO::PARAM_INT);
		$query->bindValue(':email', $_SESSION['app']['USER']['EMAIL'], PDO::PARAM_STR);
		$query->bindValue(':logo', $_SESSION['app']['USER']['LOGO_ID'], PDO::PARAM_INT);
		$query->bindValue(':create_at', $now, PDO::PARAM_STR);
		$query->execute();
		$num = $query->rowCount();
	}else{
		$query = $CONFIG['dbconn']->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . 'partner_data SET
												company_name = (:company_name),
												id_country = (:id_country),
												id_subcountry = (:id_subcountry),
												id_language = (:id_language),
												email = (:email)
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'partner_data.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data.id_count = (:count)
												AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data.id_lang = (:lang)
												AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data.id_dev = (:dev)
												AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data.organisationid = (:organisationid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data.reseller_id = (:reseller_id)
												AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data.userid = (:userid)
											LIMIT 1
											');
		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$query->bindValue(':count', 0, PDO::PARAM_INT);
		$query->bindValue(':lang', 0, PDO::PARAM_INT);
		$query->bindValue(':dev', 0, PDO::PARAM_INT);
		$query->bindValue(':organisationid', $_SESSION['app']['USER']['ORGANISATIONID'], PDO::PARAM_STR);
		$query->bindValue(':reseller_id', $_SESSION['app']['USER']['RESELLER_ID'], PDO::PARAM_STR);
		$query->bindValue(':userid', $_SESSION['app']['USER']['USERID'], PDO::PARAM_STR);
		$query->bindValue(':company_name', $_SESSION['app']['USER']['COMPANY_NAME'], PDO::PARAM_STR);
		$query->bindValue(':id_country', $_SESSION['app']['USER']['COUNTID'], PDO::PARAM_INT);
		$query->bindValue(':id_subcountry', $_SESSION['app']['USER']['COUNTID_SUB'], PDO::PARAM_INT);
		$query->bindValue(':id_language', $_SESSION['app']['USER']['LANGID'], PDO::PARAM_INT);
		$query->bindValue(':email', $_SESSION['app']['USER']['EMAIL'], PDO::PARAM_STR);
		$query->execute();
		$num = $query->rowCount();

		$query = $CONFIG['dbconn']->prepare('
											UPDATE ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full SET
												company_name = (:company_name),
												id_country = (:id_country),
												id_subcountry = (:id_subcountry),
												id_language = (:id_language),
												email = (:email)
											WHERE ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.id_count = (:count)
												AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.id_lang = (:lang)
												AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.id_dev = (:dev)
												AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.organisationid = (:organisationid)
												AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.reseller_id = (:reseller_id)
												AND ' . $CONFIG['db'][0]['prefix'] . 'partner_data_full.userid = (:userid)
											LIMIT 1
											');
		$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$query->bindValue(':count', 0, PDO::PARAM_INT);
		$query->bindValue(':lang', 0, PDO::PARAM_INT);
		$query->bindValue(':dev', 0, PDO::PARAM_INT);
		$query->bindValue(':organisationid', $_SESSION['app']['USER']['ORGANISATIONID'], PDO::PARAM_STR);
		$query->bindValue(':reseller_id', $_SESSION['app']['USER']['RESELLER_ID'], PDO::PARAM_STR);
		$query->bindValue(':userid', $_SESSION['app']['USER']['USERID'], PDO::PARAM_STR);
		$query->bindValue(':company_name', $_SESSION['app']['USER']['COMPANY_NAME'], PDO::PARAM_STR);
		$query->bindValue(':id_country', $_SESSION['app']['USER']['COUNTID'], PDO::PARAM_INT);
		$query->bindValue(':id_subcountry', $_SESSION['app']['USER']['COUNTID_SUB'], PDO::PARAM_INT);
		$query->bindValue(':id_language', $_SESSION['app']['USER']['LANGID'], PDO::PARAM_INT);
		$query->bindValue(':email', $_SESSION['app']['USER']['EMAIL'], PDO::PARAM_STR);
		$query->execute();
		$num = $query->rowCount();
	}


	include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsApp'] . 'fu_sys-jsvars.php');
	
	//include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsApp'] . 'fu-quotes-overview.php');
	include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFormsApp'] . 'fo-quotes.php');
	include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFormsApp'] . 'fo-printers.php');
}

include_once($CONFIG['system']['pathInclude'] . "app/templates/main.php");
















?>
