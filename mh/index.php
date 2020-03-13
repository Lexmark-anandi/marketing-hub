<?php
$CONFIG['noCheck'] = true;
include_once(__DIR__ . '/app/config-app.php');
$varSQL = getPostData();

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');


setcookie('access', '', time() - 1000, $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathApp'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], true);
setcookie('activesettings', '', time() - 1000, $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathApp'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], false);
setcookie('csrf', '', time() - 1000, $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathApp'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], false);



#############################################
// check for subcountry
$qry = 'SELECT ' . $CONFIG['db'][0]['prefix'] . '_subcountries_uni.id_countid_master
		 FROM ' . $CONFIG['db'][0]['prefix'] . '_subcountries_uni
		 WHERE ' . $CONFIG['db'][0]['prefix'] . '_subcountries_uni.code = (:code)
			 AND ' . $CONFIG['db'][0]['prefix'] . '_subcountries_uni.del = (:nultime)
		';
$query = $CONFIG['dbconn'][0]->prepare($qry);
$query->bindValue(':code', $varSQL['COUNTRY'], PDO::PARAM_STR);
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
if($num > 0){
	$qry2 = 'SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code
			 FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
			 WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = (:id_countid)
				 AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
			';
	$query2 = $CONFIG['dbconn'][0]->prepare($qry2);
	$query2->bindValue(':id_countid', $rows[0]['id_countid_master'], PDO::PARAM_INT);
	$query2->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query2->execute();
	$rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$num2 = $query2->rowCount();
	if($num2 > 0) $varSQL['COUNTRY'] = $rows2[0]['code'];
}
#############################################



$CONFIG['USER_TMP'] = array();

$CONFIG['user'] = array();
$CONFIG['user']['id_countid'] = 0;
$CONFIG['user']['country'] = $varSQL['COUNTRY'];
$CONFIG['user']['id_langid'] = 0;
$CONFIG['user']['language'] = $varSQL['LANG'];

$CONFIG['user']['bsd'] = 0;
$CONFIG['user']['distri'] = 0;
if(isset($varSQL['PARENT_PROGRAM_NAME']) && $varSQL['PARENT_PROGRAM_NAME'] != ''){
    $aProgTmp = explode(',', $varSQL['PARENT_PROGRAM_NAME']);
    foreach($aProgTmp as $prog){
        if(in_array(trim($prog), $CONFIG['aProgramm']['BSD'])){
            $CONFIG['user']['bsd'] = 1;
        }
        if(in_array(trim($prog), $CONFIG['aProgramm']['Distribution'])){
            $CONFIG['user']['distri'] = 1;
        }
    }
}else{
    if(strtolower($varSQL['BSD_Silver']) == 'yes' || strtolower($varSQL['BSD_Gold']) == 'yes' || strtolower($varSQL['BSD_Diamond']) == 'yes') $CONFIG['user']['bsd'] = 1;
}

$qry = 'SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid
		 FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
		 WHERE (' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code = (:code)
		 		OR ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code_add = (:code)
		 		OR ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code_punch = (:code))
			 AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
		';
$query = $CONFIG['dbconn'][0]->prepare($qry);
$query->bindValue(':code', $varSQL['COUNTRY'], PDO::PARAM_STR);
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
if($num > 0) $CONFIG['user']['id_countid'] = $rows[0]['id_countid'];

$qry = 'SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid
		 FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
		 WHERE (' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code = (:code)
		 		OR ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code_add = (:code))
			 AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
		';
$query = $CONFIG['dbconn'][0]->prepare($qry);
$query->bindValue(':code', $varSQL['LANG'], PDO::PARAM_STR);
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
if($num > 0) $CONFIG['user']['id_langid'] = $rows[0]['id_langid'];


$CONFIG['activeSettings'] = array();
$CONFIG['activeSettings']['appLanguage'] = strtolower($CONFIG['user']['country']) . '_' . strtolower($CONFIG['user']['language']);
$CONFIG['activeSettings']['id_countid'] = $CONFIG['user']['id_countid'];
$CONFIG['activeSettings']['id_langid'] = $CONFIG['user']['id_langid'];
$CONFIG['activeSettings']['ovPage'] = 1;
$CONFIG['activeSettings']['ovRange'] = $CONFIG['system']['ovRange'][0];
$CONFIG['activeSettings']['ovType'] = 'grid';
$CONFIG['activeSettings']['ovOrder'] = 'title';
$CONFIG['activeSettings']['ovOrderDir'] = 'ASC';


###############################################################################
// check for existing country/language
###############################################################################
$qry = 'SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang 
		 FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni 
		 WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid = (:count) 
			AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid <> (:nul)  
			AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid = (:lang)  
			AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid <> (:nul)  
			AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.del = (:nultime) 
		';
$queryCL = $CONFIG['dbconn'][0]->prepare($qry);
$queryCL->bindValue(':count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$queryCL->bindValue(':lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$queryCL->bindValue(':nul', 0, PDO::PARAM_INT);
$queryCL->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryCL->execute();
$rowsCL = $queryCL->fetchAll(PDO::FETCH_ASSOC);
$numCL = $queryCL->rowCount();

if($numCL == 0){
	header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . 'error.php');
	exit();
}
###############################################################################





###############################################################################
// save partnercompany
###############################################################################
$aArgsSave = array();
$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . '_partnercompanies_';
$aArgsSave['primarykey'] = 'id_pcid';
$aArgsSave['allVersions'] = array();
$aArgsSave['changedVersions'] = array();

$aArgsSave['columns'] = array();
$aArgsSave['columns']['id_pcid'] = 'i';
$aArgsSave['columns']['reseller_id'] = 's';
$aArgsSave['columns']['organisationid'] = 's';
$aArgsSave['columns']['company_name'] = 's';
$aArgsSave['columns']['address1'] = 's';
$aArgsSave['columns']['address2'] = 's';
$aArgsSave['columns']['address3'] = 's';
$aArgsSave['columns']['zipcode'] = 's';
$aArgsSave['columns']['city'] = 's';
$aArgsSave['columns']['id_countid'] = 'i';
$aArgsSave['columns']['id_langid'] = 'i';
$aArgsSave['columns']['phone'] = 's';
$aArgsSave['columns']['mobile'] = 's';
$aArgsSave['columns']['email'] = 's';
$aArgsSave['columns']['url'] = 's';
$aArgsSave['columns']['program_tier'] = 's';
$aArgsSave['columns']['bsd_silver'] = 's';
$aArgsSave['columns']['bsd_gold'] = 's';
$aArgsSave['columns']['bsd_diamond'] = 's';
$aArgsSave['columns']['parent_program_name'] = 's';

$aArgsSave['aFieldsNumbers'] = array();
array_push($aArgsSave['aFieldsNumbers'], 'id_pcid');
array_push($aArgsSave['aFieldsNumbers'], 'id_countid');
array_push($aArgsSave['aFieldsNumbers'], 'id_langid');

$aArgsSave['excludeUpdateUni'] = array();
$aArgsSave['excludeUpdateUni']['id_pcid'] = array('',0);
$aArgsSave['excludeUpdateUni']['reseller_id'] = array('');
$aArgsSave['excludeUpdateUni']['organisationid'] = array('');
$aArgsSave['excludeUpdateUni']['company_name'] = array('');
$aArgsSave['excludeUpdateUni']['address1'] = array('');
$aArgsSave['excludeUpdateUni']['address2'] = array('');
$aArgsSave['excludeUpdateUni']['address3'] = array('');
$aArgsSave['excludeUpdateUni']['zipcode'] = array('');
$aArgsSave['excludeUpdateUni']['city'] = array('');
$aArgsSave['excludeUpdateUni']['id_countid'] = array('',0);
$aArgsSave['excludeUpdateUni']['id_langid'] = array('',0);
$aArgsSave['excludeUpdateUni']['phone'] = array('');
$aArgsSave['excludeUpdateUni']['mobile'] = array('');
$aArgsSave['excludeUpdateUni']['email'] = array('');
$aArgsSave['excludeUpdateUni']['url'] = array('');
$aArgsSave['excludeUpdateUni']['program_tier'] = array('');
$aArgsSave['excludeUpdateUni']['bsd_silver'] = array('');
$aArgsSave['excludeUpdateUni']['bsd_gold'] = array('');
$aArgsSave['excludeUpdateUni']['bsd_diamond'] = array('');
$aArgsSave['excludeUpdateUni']['parent_program_name'] = array('');

$aFieldsSaveMaster = array();
array_push($aFieldsSaveMaster, 'id_pcid');
array_push($aFieldsSaveMaster, 'reseller_id');
array_push($aFieldsSaveMaster, 'organisationid');
array_push($aFieldsSaveMaster, 'company_name');
array_push($aFieldsSaveMaster, 'address1');
array_push($aFieldsSaveMaster, 'address2');
array_push($aFieldsSaveMaster, 'address3');
array_push($aFieldsSaveMaster, 'zipcode');
array_push($aFieldsSaveMaster, 'city');
array_push($aFieldsSaveMaster, 'id_countid');
array_push($aFieldsSaveMaster, 'id_langid');
array_push($aFieldsSaveMaster, 'phone');
array_push($aFieldsSaveMaster, 'mobile');
array_push($aFieldsSaveMaster, 'email');
array_push($aFieldsSaveMaster, 'url');
array_push($aFieldsSaveMaster, 'program_tier');
array_push($aFieldsSaveMaster, 'bsd_silver');
array_push($aFieldsSaveMaster, 'bsd_gold');
array_push($aFieldsSaveMaster, 'bsd_diamond');
array_push($aFieldsSaveMaster, 'parent_program_name');
$aFieldsSaveNotMaster = array();
array_push($aFieldsSaveNotMaster, 'id_pcid');
array_push($aFieldsSaveNotMaster, 'reseller_id');
array_push($aFieldsSaveNotMaster, 'organisationid');
array_push($aFieldsSaveNotMaster, 'company_name');
array_push($aFieldsSaveNotMaster, 'address1');
array_push($aFieldsSaveNotMaster, 'address2');
array_push($aFieldsSaveNotMaster, 'address3');
array_push($aFieldsSaveNotMaster, 'zipcode');
array_push($aFieldsSaveNotMaster, 'city');
array_push($aFieldsSaveNotMaster, 'id_countid');
array_push($aFieldsSaveNotMaster, 'id_langid');
array_push($aFieldsSaveNotMaster, 'phone');
array_push($aFieldsSaveNotMaster, 'mobile');
array_push($aFieldsSaveNotMaster, 'email');
array_push($aFieldsSaveNotMaster, 'url');
array_push($aFieldsSaveNotMaster, 'program_tier');
array_push($aFieldsSaveNotMaster, 'bsd_silver');
array_push($aFieldsSaveNotMaster, 'bsd_gold');
array_push($aFieldsSaveNotMaster, 'bsd_diamond');
array_push($aFieldsSaveNotMaster, 'parent_program_name');

$aArgsSave['aData']['id_count'] = 0;
$aArgsSave['aData']['id_lang'] = 0;
$aArgsSave['aData']['id_dev'] = 0;
$aArgsSave['aData']['id_cl'] = 1;

$newLogin = 0;


$qry = 'SELECT ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_pcid
		 FROM ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni
		 WHERE ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.reseller_id = (:reseller_id)
		';
$queryP = $CONFIG['dbconn'][0]->prepare($qry);
$queryP->bindValue(':reseller_id', $varSQL['RESELLER_ID'], PDO::PARAM_STR);
$queryP->execute();
$rowsP = $queryP->fetchAll(PDO::FETCH_ASSOC);
$numP = $queryP->rowCount();

if($numP == 0){
	$queryI = $CONFIG['dbconn'][0]->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_
										(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
										VALUES
										(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
										');
	$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryI->bindValue(':id_cl', 1, PDO::PARAM_INT);
	$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
	$queryI->bindValue(':create_from', 999999999, PDO::PARAM_INT);
	$queryI->execute();
	$CONFIG['user']['id_pcid'] = $CONFIG['dbconn'][0]->lastInsertId();
	
	$newLogin = 1;
}else{
	$CONFIG['user']['id_pcid'] = $rowsP[0]['id_pcid'];
}
$aArgsSave['id_data'] = $CONFIG['user']['id_pcid'];


$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_ext
			(id_count, id_lang, id_dev, id_cl, restricted_all, id_pcid, reseller_id, organisationid, company_name, address1, address2, address3, zipcode, city, id_countid, id_langid, phone, mobile, email, url, program_tier, bsd_silver, bsd_gold, bsd_diamond, parent_program_name, create_at, create_from, change_from)
		VALUES
			(:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_pcid, :reseller_id, :organisationid, :company_name, :address1, :address2, :address3, :zipcode, :city, :id_countid, :id_langid, :phone, :mobile, :email, :url, :program_tier, :bsd_silver, :bsd_gold, :bsd_diamond, :parent_program_name, :now, :create_from, :create_from)
		ON DUPLICATE KEY UPDATE 
			organisationid = (:organisationid),
			company_name = (:company_name),
			address1 = (:address1),
			address2 = (:address2),
			address3 = (:address3),
			zipcode = (:zipcode),
			city = (:city),
			id_countid = (:id_countid),
			id_langid = (:id_langid),
			phone = (:phone),
			mobile = (:mobile),
			email = (:email),
			url = (:url),
			program_tier = (:program_tier),
			bsd_silver = (:bsd_silver),
			bsd_gold = (:bsd_gold),
			bsd_diamond = (:bsd_diamond),
                        parent_program_name = (:parent_program_name),
			change_from = (:create_from),
			del = (:nultime)
		';
$queryC = $CONFIG['dbconn'][0]->prepare($qry);
$queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
$queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryC->bindValue(':id_cl', 1, PDO::PARAM_INT);
$queryC->bindValue(':restricted_all', 0, PDO::PARAM_INT);
$queryC->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
$queryC->bindValue(':reseller_id', $varSQL['RESELLER_ID'], PDO::PARAM_STR);
$queryC->bindValue(':organisationid', $varSQL['ORGANISATIONID'], PDO::PARAM_STR);
$queryC->bindValue(':company_name', $varSQL['COMPANY_NAME'], PDO::PARAM_STR);
$queryC->bindValue(':address1', $varSQL['ADDRESS1'], PDO::PARAM_STR);
$queryC->bindValue(':address2', $varSQL['ADDRESS2'], PDO::PARAM_STR);
$queryC->bindValue(':address3', $varSQL['ADDRESS3'], PDO::PARAM_STR);
$queryC->bindValue(':zipcode', $varSQL['ZIPCODE'], PDO::PARAM_STR);
$queryC->bindValue(':city', $varSQL['CITY'], PDO::PARAM_STR);
$queryC->bindValue(':id_countid', $CONFIG['user']['id_countid'], PDO::PARAM_STR);
$queryC->bindValue(':id_langid', $CONFIG['user']['id_langid'], PDO::PARAM_STR);
$queryC->bindValue(':phone', $varSQL['PHONE'], PDO::PARAM_STR);
$queryC->bindValue(':mobile', $varSQL['MOBILE'], PDO::PARAM_STR);
$queryC->bindValue(':email', $varSQL['EMAIL'], PDO::PARAM_STR);
$queryC->bindValue(':url', '', PDO::PARAM_STR);
$queryC->bindValue(':program_tier', $varSQL['Program_Tier'], PDO::PARAM_STR);
$queryC->bindValue(':bsd_silver', $varSQL['BSD_Silver'], PDO::PARAM_STR);
$queryC->bindValue(':bsd_gold', $varSQL['BSD_Gold'], PDO::PARAM_STR);
$queryC->bindValue(':bsd_diamond', $varSQL['BSD_Diamond'], PDO::PARAM_STR);
$queryC->bindValue(':parent_program_name', $varSQL['PARENT_PROGRAM_NAME'], PDO::PARAM_STR);
$queryC->bindValue(':now', $now, PDO::PARAM_STR);
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':create_from', 999999999, PDO::PARAM_INT); 
$queryC->execute();
$numC = $queryC->rowCount();





###############################################################################
// save partner person
###############################################################################
$aArgsSavePP = array();
$aArgsSavePP['table'] = $CONFIG['db'][0]['prefix'] . '_partnerpersons_';
$aArgsSavePP['primarykey'] = 'id_ppid';
$aArgsSavePP['allVersions'] = array();
$aArgsSavePP['changedVersions'] = array();

$aArgsSavePP['columns'] = array();
$aArgsSavePP['columns']['id_ppid'] = 'i';
$aArgsSavePP['columns']['id_pcid'] = 'i';
$aArgsSavePP['columns']['userid'] = 's';
$aArgsSavePP['columns']['salutation'] = 's';
$aArgsSavePP['columns']['firstname'] = 's';
$aArgsSavePP['columns']['lastname'] = 's';
$aArgsSavePP['columns']['id_countid'] = 'i';
$aArgsSavePP['columns']['id_langid'] = 'i';
$aArgsSavePP['columns']['phone'] = 's';
$aArgsSavePP['columns']['mobile'] = 's';
$aArgsSavePP['columns']['email'] = 's';

$aArgsSavePP['aFieldsNumbers'] = array();
array_push($aArgsSavePP['aFieldsNumbers'], 'id_ppid');
array_push($aArgsSavePP['aFieldsNumbers'], 'id_pcid');
array_push($aArgsSavePP['aFieldsNumbers'], 'id_countid');
array_push($aArgsSavePP['aFieldsNumbers'], 'id_langid');

$aArgsSavePP['excludeUpdateUni'] = array();
$aArgsSavePP['excludeUpdateUni']['id_ppid'] = array('',0);
$aArgsSavePP['excludeUpdateUni']['id_pcid'] = array('',0);
$aArgsSavePP['excludeUpdateUni']['userid'] = array('');
$aArgsSavePP['excludeUpdateUni']['salutation'] = array('');
$aArgsSavePP['excludeUpdateUni']['firstname'] = array('');
$aArgsSavePP['excludeUpdateUni']['lastname'] = array('');
$aArgsSavePP['excludeUpdateUni']['id_countid'] = array('',0);
$aArgsSavePP['excludeUpdateUni']['id_langid'] = array('',0);
$aArgsSavePP['excludeUpdateUni']['phone'] = array('');
$aArgsSavePP['excludeUpdateUni']['mobile'] = array('');
$aArgsSavePP['excludeUpdateUni']['email'] = array('');

$aFieldsSaveMaster = array();
array_push($aFieldsSaveMaster, 'id_ppid');
array_push($aFieldsSaveMaster, 'id_pcid');
array_push($aFieldsSaveMaster, 'userid');
array_push($aFieldsSaveMaster, 'salutation');
array_push($aFieldsSaveMaster, 'firstname');
array_push($aFieldsSaveMaster, 'lastname');
array_push($aFieldsSaveMaster, 'id_countid');
array_push($aFieldsSaveMaster, 'id_langid');
array_push($aFieldsSaveMaster, 'phone');
array_push($aFieldsSaveMaster, 'mobile');
array_push($aFieldsSaveMaster, 'email');
$aFieldsSaveNotMaster = array();
array_push($aFieldsSaveNotMaster, 'id_ppid');
array_push($aFieldsSaveNotMaster, 'id_pcid');
array_push($aFieldsSaveNotMaster, 'userid');
array_push($aFieldsSaveNotMaster, 'salutation');
array_push($aFieldsSaveNotMaster, 'firstname');
array_push($aFieldsSaveNotMaster, 'lastname');
array_push($aFieldsSaveNotMaster, 'id_countid');
array_push($aFieldsSaveNotMaster, 'id_langid');
array_push($aFieldsSaveNotMaster, 'phone');
array_push($aFieldsSaveNotMaster, 'mobile');
array_push($aFieldsSaveNotMaster, 'email');

$aArgsSavePP['aData']['id_count'] = 0;
$aArgsSavePP['aData']['id_lang'] = 0;
$aArgsSavePP['aData']['id_dev'] = 0;
$aArgsSavePP['aData']['id_cl'] = 1;



$qry = 'SELECT ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.id_ppid
		 FROM ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni
		 WHERE ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.id_pcid = (:id_pcid)
			 AND ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.userid = (:userid)
		';
$queryP = $CONFIG['dbconn'][0]->prepare($qry);
$queryP->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
$queryP->bindValue(':userid', $varSQL['USERID'], PDO::PARAM_STR);
$queryP->execute();
$rowsP = $queryP->fetchAll(PDO::FETCH_ASSOC);
$numP = $queryP->rowCount();

if($numP == 0){
	$queryI = $CONFIG['dbconn'][0]->prepare('
										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_
										(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
										VALUES
										(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
										');
	$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
	$queryI->bindValue(':id_cl', 1, PDO::PARAM_INT);
	$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
	$queryI->bindValue(':create_from', 999999999, PDO::PARAM_INT);
	$queryI->execute();
	$CONFIG['user']['id_ppid'] = $CONFIG['dbconn'][0]->lastInsertId();
}else{
	$CONFIG['user']['id_ppid'] = $rowsP[0]['id_ppid'];
}
$aArgsSavePP['id_data'] = $CONFIG['user']['id_ppid'];


$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_ext
			(id_count, id_lang, id_dev, id_cl, restricted_all, id_ppid, id_pcid, userid, salutation, firstname, lastname, id_countid, id_langid, phone, mobile, email, create_at, create_from, change_from)
		VALUES
			(:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_ppid, :id_pcid, :userid, :salutation, :firstname, :lastname, :id_countid, :id_langid, :phone, :mobile, :email, :now, :create_from, :create_from)
		ON DUPLICATE KEY UPDATE 
			salutation = (:salutation),
			firstname = (:firstname),
			lastname = (:lastname),
			id_countid = (:id_countid),
			id_langid = (:id_langid),
			phone = (:phone),
			mobile = (:mobile),
			email = (:email),
			change_from = (:create_from),
			del = (:nultime)
		';
$queryC = $CONFIG['dbconn'][0]->prepare($qry);
$queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
$queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
$queryC->bindValue(':id_cl', 1, PDO::PARAM_INT);
$queryC->bindValue(':restricted_all', 0, PDO::PARAM_INT);
$queryC->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
$queryC->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
$queryC->bindValue(':userid', $varSQL['USERID'], PDO::PARAM_STR);
$queryC->bindValue(':salutation', $varSQL['SALUTATION'], PDO::PARAM_STR);
$queryC->bindValue(':firstname', $varSQL['FIRST_NAME'], PDO::PARAM_STR);
$queryC->bindValue(':lastname', $varSQL['LAST_NAME'], PDO::PARAM_STR);
$queryC->bindValue(':id_countid', $CONFIG['user']['id_countid'], PDO::PARAM_STR);
$queryC->bindValue(':id_langid', $CONFIG['user']['id_langid'], PDO::PARAM_STR);
$queryC->bindValue(':phone', $varSQL['PHONE'], PDO::PARAM_STR);
$queryC->bindValue(':mobile', $varSQL['MOBILE'], PDO::PARAM_STR);
$queryC->bindValue(':email', $varSQL['EMAIL'], PDO::PARAM_STR);
$queryC->bindValue(':now', $now, PDO::PARAM_STR);
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':create_from', 999999999, PDO::PARAM_INT); 
$queryC->execute();
$numC = $queryC->rowCount();






###########################################################################
// Token
createAccesstoken();

// Config Cookie
setcookie('activesettings', json_encode($CONFIG['activeSettings']), 0, $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathApp'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], false);
###########################################################################



$aArgsSave['changedVersions'] = array(array(0,0,0));
$aArgsSave['allVersions'] = array(array(0,0,0));
insertAll($aArgsSave);

$aArgsSavePP['changedVersions'] = array(array(0,0,0));
$aArgsSavePP['allVersions'] = array(array(0,0,0));
insertAll($aArgsSavePP);



header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathApp'] . 'index.php?initLogin=1&newLogin=' . $newLogin);
exit();





			
?>