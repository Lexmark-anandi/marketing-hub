<?php
$varSQL = getPostData();

$CONFIG['USER_TMP'] = array();
$CONFIG['user'] = array();
$newLogin = 0;

$queryC = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.code AS code_count,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.code AS code_lang,
										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni 
								 
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang = (:id_count2lang)
									');
$queryC->bindValue(':id_count2lang', $varSQL['country'], PDO::PARAM_INT);
$queryC->execute();
$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
$numC = $queryC->rowCount();

$CONFIG['user']['id_countid'] = $rowsC[0]['id_countid'];
$CONFIG['user']['country'] = $rowsC[0]['code_count'];
$CONFIG['user']['id_langid'] = $rowsC[0]['id_langid'];
$CONFIG['user']['language'] = $rowsC[0]['code_lang'];
$CONFIG['user']['bsd'] = ($varSQL['range'] == 'bsd') ? 1 : 0;
$CONFIG['user']['distri'] = ($varSQL['range'] == 'distribution') ? 1 : 0;


$CONFIG['activeSettings'] = array();
$CONFIG['activeSettings']['appLanguage'] = strtolower($CONFIG['user']['country']) . '_' . strtolower($CONFIG['user']['language']);
$CONFIG['activeSettings']['id_countid'] = $CONFIG['user']['id_countid'];
$CONFIG['activeSettings']['id_langid'] = $CONFIG['user']['id_langid'];
$CONFIG['activeSettings']['ovPage'] = 1;
$CONFIG['activeSettings']['ovRange'] = $CONFIG['system']['ovRange'][0];
$CONFIG['activeSettings']['ovType'] = 'grid';

 

if($varSQL['partner'] == 0){
	###############################################################################
	// LOGIN AS LEXMARK
	###############################################################################

	###############################################################################
	// select clientsprofile
	$queryCP = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.id_clpid,
											' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.client,
											' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.street,
											' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.zip,
											' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.city,
											' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.phone,
											' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.email,
											' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.web
										FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni 
										WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.id_count = (:count)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.id_lang = (:lang)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clientprofiles_uni.id_cl IN (1)
										');
	$queryCP->bindValue(':count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$queryCP->bindValue(':lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
	$queryCP->execute();
	$rowsCP = $queryCP->fetchAll(PDO::FETCH_ASSOC);
	$numCP = $queryCP->rowCount();
	
	
	
	###############################################################################
	// save lexmark as partnercompany in country/language
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
	$aArgsSave['columns']['zipcode'] = 's';
	$aArgsSave['columns']['city'] = 's';
	$aArgsSave['columns']['id_countid'] = 'i';
	$aArgsSave['columns']['id_langid'] = 'i';
	$aArgsSave['columns']['phone'] = 's';
	$aArgsSave['columns']['email'] = 's';
	$aArgsSave['columns']['url'] = 's';
	$aArgsSave['columns']['bsd_gold'] = 's';
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
	$aArgsSave['excludeUpdateUni']['zipcode'] = array('');
	$aArgsSave['excludeUpdateUni']['city'] = array('');
	$aArgsSave['excludeUpdateUni']['id_countid'] = array('',0);
	$aArgsSave['excludeUpdateUni']['id_langid'] = array('',0);
	$aArgsSave['excludeUpdateUni']['phone'] = array('');
	$aArgsSave['excludeUpdateUni']['email'] = array('');
	$aArgsSave['excludeUpdateUni']['url'] = array('');
	$aArgsSave['excludeUpdateUni']['bsd_gold'] = array('');
	$aArgsSave['excludeUpdateUni']['parent_program_name'] = array('');
	
	$aFieldsSaveMaster = array();
	array_push($aFieldsSaveMaster, 'id_pcid');
	array_push($aFieldsSaveMaster, 'reseller_id');
	array_push($aFieldsSaveMaster, 'organisationid');
	array_push($aFieldsSaveMaster, 'company_name');
	array_push($aFieldsSaveMaster, 'address1');
	array_push($aFieldsSaveMaster, 'zipcode');
	array_push($aFieldsSaveMaster, 'city');
	array_push($aFieldsSaveMaster, 'id_countid');
	array_push($aFieldsSaveMaster, 'id_langid');
	array_push($aFieldsSaveMaster, 'phone');
	array_push($aFieldsSaveMaster, 'email');
	array_push($aFieldsSaveMaster, 'url');
	array_push($aFieldsSaveMaster, 'bsd_gold');
	array_push($aFieldsSaveMaster, 'parent_program_name');
	$aFieldsSaveNotMaster = array();
	array_push($aFieldsSaveNotMaster, 'id_pcid');
	array_push($aFieldsSaveNotMaster, 'reseller_id');
	array_push($aFieldsSaveNotMaster, 'organisationid');
	array_push($aFieldsSaveNotMaster, 'company_name');
	array_push($aFieldsSaveNotMaster, 'address1');
	array_push($aFieldsSaveNotMaster, 'zipcode');
	array_push($aFieldsSaveNotMaster, 'city');
	array_push($aFieldsSaveNotMaster, 'id_countid');
	array_push($aFieldsSaveNotMaster, 'id_langid');
	array_push($aFieldsSaveNotMaster, 'phone');
	array_push($aFieldsSaveNotMaster, 'email');
	array_push($aFieldsSaveNotMaster, 'url');
	array_push($aFieldsSaveNotMaster, 'bsd_gold');
	array_push($aFieldsSaveNotMaster, 'parent_program_name');
	
	$aArgsSave['aData']['id_count'] = 0;
	$aArgsSave['aData']['id_lang'] = 0;
	$aArgsSave['aData']['id_dev'] = 0;
	$aArgsSave['aData']['id_cl'] = 1;
	
	
	$qry = 'SELECT ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_pcid
			 FROM ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni
			 WHERE ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.reseller_id = (:reseller_id)
				 AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_countid = (:count)
				 AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_langid = (:lang)
			';
	$queryP = $CONFIG['dbconn'][0]->prepare($qry);
	$queryP->bindValue(':reseller_id', 'lexsales_' . $varSQL['range'], PDO::PARAM_STR);
	$queryP->bindValue(':count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
	$queryP->bindValue(':lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
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
	}else{
		$CONFIG['user']['id_pcid'] = $rowsP[0]['id_pcid'];
	}
	$aArgsSave['id_data'] = $CONFIG['user']['id_pcid'];
	
	      
        $PARENT_PROGRAM_NAME = '';
        if($varSQL['range'] == 'bsd'){
            $CONFIG['user']['bsd'] = 1;
            $PARENT_PROGRAM_NAME = 'Business Solutions Diamond';
        }
        if($varSQL['range'] == 'distribution'){
            $CONFIG['user']['distri'] = 1;
            $PARENT_PROGRAM_NAME = 'Distribution Authorized';
        }
        if($varSQL['range'] == 'all'){
            $PARENT_PROGRAM_NAME = 'Commercial Diamond';
        }
    
        
        $qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_ext
				(id_count, id_lang, id_dev, id_cl, restricted_all, id_pcid, reseller_id, organisationid, company_name, address1, address2, address3, zipcode, city, id_countid, id_langid, phone, mobile, email, url, program_tier, bsd_silver, bsd_gold, bsd_diamond, create_at, create_from, change_from, parent_program_name)
			VALUES
				(:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_pcid, :reseller_id, :organisationid, :company_name, :address1, :address2, :address3, :zipcode, :city, :id_countid, :id_langid, :phone, :mobile, :email, :url, :program_tier, :bsd_silver, :bsd_gold, :bsd_diamond, :now, :create_from, :create_from, :parent_program_name)
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
                                parent_program_name =(:parent_program_name),
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
	$queryC->bindValue(':reseller_id', 'lexsales_' . $varSQL['range'], PDO::PARAM_STR);
	$queryC->bindValue(':organisationid', '', PDO::PARAM_STR);
	$queryC->bindValue(':company_name', $rowsCP[0]['client'], PDO::PARAM_STR);
	$queryC->bindValue(':address1', $rowsCP[0]['street'], PDO::PARAM_STR);
	$queryC->bindValue(':address2', '', PDO::PARAM_STR);
	$queryC->bindValue(':address3', '', PDO::PARAM_STR);
	$queryC->bindValue(':zipcode', $rowsCP[0]['zip'], PDO::PARAM_STR);
	$queryC->bindValue(':city', $rowsCP[0]['city'], PDO::PARAM_STR);
	$queryC->bindValue(':id_countid', $CONFIG['user']['id_countid'], PDO::PARAM_STR);
	$queryC->bindValue(':id_langid', $CONFIG['user']['id_langid'], PDO::PARAM_STR);
	$queryC->bindValue(':phone', $rowsCP[0]['phone'], PDO::PARAM_STR);
	$queryC->bindValue(':mobile', '', PDO::PARAM_STR);
	$queryC->bindValue(':email', $rowsCP[0]['email'], PDO::PARAM_STR);
	$queryC->bindValue(':url', $rowsCP[0]['web'], PDO::PARAM_STR);
	$queryC->bindValue(':program_tier', '', PDO::PARAM_STR);
	$queryC->bindValue(':bsd_silver', 'no', PDO::PARAM_STR);
	$queryC->bindValue(':bsd_gold', ($varSQL['range'] == 'bsd') ? 'yes' : 'no', PDO::PARAM_STR);
	$queryC->bindValue(':bsd_diamond', 'no', PDO::PARAM_STR);
	$queryC->bindValue(':parent_program_name', $PARENT_PROGRAM_NAME, PDO::PARAM_STR);
	$queryC->bindValue(':now', $now, PDO::PARAM_STR);
	$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$queryC->bindValue(':create_from', 999999999, PDO::PARAM_INT); 
	$queryC->execute();
	$numC = $queryC->rowCount();
}else{
	###############################################################################
	// LOGIN AS PARTNER
	###############################################################################
	$CONFIG['user']['id_pcid'] = $varSQL['partner'];
}
	
	
###############################################################################
// save sales person as partnerperson
$aArgsSavePP = array();
$aArgsSavePP['table'] = $CONFIG['db'][0]['prefix'] . '_partnerpersons_';
$aArgsSavePP['primarykey'] = 'id_ppid';
$aArgsSavePP['allVersions'] = array();
$aArgsSavePP['changedVersions'] = array();

$aArgsSavePP['columns'] = array();
$aArgsSavePP['columns']['id_ppid'] = 'i';
$aArgsSavePP['columns']['id_pcid'] = 'i';
$aArgsSavePP['columns']['userid'] = 's';
$aArgsSavePP['columns']['id_countid'] = 'i';
$aArgsSavePP['columns']['id_langid'] = 'i';

$aArgsSavePP['aFieldsNumbers'] = array();
array_push($aArgsSavePP['aFieldsNumbers'], 'id_ppid');
array_push($aArgsSavePP['aFieldsNumbers'], 'id_pcid');
array_push($aArgsSavePP['aFieldsNumbers'], 'id_countid');
array_push($aArgsSavePP['aFieldsNumbers'], 'id_langid');

$aArgsSavePP['excludeUpdateUni'] = array();
$aArgsSavePP['excludeUpdateUni']['id_ppid'] = array('',0);
$aArgsSavePP['excludeUpdateUni']['id_pcid'] = array('',0);
$aArgsSavePP['excludeUpdateUni']['userid'] = array('');
$aArgsSavePP['excludeUpdateUni']['id_countid'] = array('',0);
$aArgsSavePP['excludeUpdateUni']['id_langid'] = array('',0);

$aFieldsSaveMaster = array();
array_push($aFieldsSaveMaster, 'id_ppid');
array_push($aFieldsSaveMaster, 'id_pcid');
array_push($aFieldsSaveMaster, 'userid');
array_push($aFieldsSaveMaster, 'id_countid');
array_push($aFieldsSaveMaster, 'id_langid');
$aFieldsSaveNotMaster = array();
array_push($aFieldsSaveNotMaster, 'id_ppid');
array_push($aFieldsSaveNotMaster, 'id_pcid');
array_push($aFieldsSaveNotMaster, 'userid');
array_push($aFieldsSaveNotMaster, 'id_countid');
array_push($aFieldsSaveNotMaster, 'id_langid');

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
$queryP->bindValue(':userid', 'lexsales', PDO::PARAM_STR);
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
$queryC->bindValue(':userid', 'lexsales', PDO::PARAM_STR);
$queryC->bindValue(':salutation', '', PDO::PARAM_STR);
$queryC->bindValue(':firstname', '', PDO::PARAM_STR);
$queryC->bindValue(':lastname', '', PDO::PARAM_STR);
$queryC->bindValue(':id_countid', $CONFIG['user']['id_countid'], PDO::PARAM_STR);
$queryC->bindValue(':id_langid', $CONFIG['user']['id_langid'], PDO::PARAM_STR);
$queryC->bindValue(':phone', '', PDO::PARAM_STR);
$queryC->bindValue(':mobile', '', PDO::PARAM_STR);
$queryC->bindValue(':email', '', PDO::PARAM_STR);
$queryC->bindValue(':now', $now, PDO::PARAM_STR);
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':create_from', 999999999, PDO::PARAM_INT); 
$queryC->execute();
$numC = $queryC->rowCount();

//
//
//
//
//
//
//###############################################################################
//// save partnercompany
//###############################################################################
//$aArgsSave = array();
//$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . '_partnercompanies_';
//$aArgsSave['primarykey'] = 'id_pcid';
//$aArgsSave['allVersions'] = array();
//$aArgsSave['changedVersions'] = array();
//
//$aArgsSave['columns'] = array();
//$aArgsSave['columns']['id_pcid'] = 'i';
//$aArgsSave['columns']['reseller_id'] = 's';
//$aArgsSave['columns']['organisationid'] = 's';
//$aArgsSave['columns']['company_name'] = 's';
//$aArgsSave['columns']['address1'] = 's';
//$aArgsSave['columns']['address2'] = 's';
//$aArgsSave['columns']['address3'] = 's';
//$aArgsSave['columns']['zipcode'] = 's';
//$aArgsSave['columns']['city'] = 's';
//$aArgsSave['columns']['id_countid'] = 'i';
//$aArgsSave['columns']['id_langid'] = 'i';
//$aArgsSave['columns']['phone'] = 's';
//$aArgsSave['columns']['mobile'] = 's';
//$aArgsSave['columns']['email'] = 's';
//$aArgsSave['columns']['url'] = 's';
//$aArgsSave['columns']['program_tier'] = 's';
//$aArgsSave['columns']['bsd_silver'] = 's';
//$aArgsSave['columns']['bsd_gold'] = 's';
//$aArgsSave['columns']['bsd_diamond'] = 's';
//
//$aArgsSave['aFieldsNumbers'] = array();
//array_push($aArgsSave['aFieldsNumbers'], 'id_pcid');
//array_push($aArgsSave['aFieldsNumbers'], 'id_countid');
//array_push($aArgsSave['aFieldsNumbers'], 'id_langid');
//
//$aArgsSave['excludeUpdateUni'] = array();
//$aArgsSave['excludeUpdateUni']['id_pcid'] = array('',0);
//$aArgsSave['excludeUpdateUni']['reseller_id'] = array('');
//$aArgsSave['excludeUpdateUni']['organisationid'] = array('');
//$aArgsSave['excludeUpdateUni']['company_name'] = array('');
//$aArgsSave['excludeUpdateUni']['address1'] = array('');
//$aArgsSave['excludeUpdateUni']['address2'] = array('');
//$aArgsSave['excludeUpdateUni']['address3'] = array('');
//$aArgsSave['excludeUpdateUni']['zipcode'] = array('');
//$aArgsSave['excludeUpdateUni']['city'] = array('');
//$aArgsSave['excludeUpdateUni']['id_countid'] = array('',0);
//$aArgsSave['excludeUpdateUni']['id_langid'] = array('',0);
//$aArgsSave['excludeUpdateUni']['phone'] = array('');
//$aArgsSave['excludeUpdateUni']['mobile'] = array('');
//$aArgsSave['excludeUpdateUni']['email'] = array('');
//$aArgsSave['excludeUpdateUni']['url'] = array('');
//$aArgsSave['excludeUpdateUni']['program_tier'] = array('');
//$aArgsSave['excludeUpdateUni']['bsd_silver'] = array('');
//$aArgsSave['excludeUpdateUni']['bsd_gold'] = array('');
//$aArgsSave['excludeUpdateUni']['bsd_diamond'] = array('');
//
//$aFieldsSaveMaster = array();
//array_push($aFieldsSaveMaster, 'id_pcid');
//array_push($aFieldsSaveMaster, 'reseller_id');
//array_push($aFieldsSaveMaster, 'organisationid');
//array_push($aFieldsSaveMaster, 'company_name');
//array_push($aFieldsSaveMaster, 'address1');
//array_push($aFieldsSaveMaster, 'address2');
//array_push($aFieldsSaveMaster, 'address3');
//array_push($aFieldsSaveMaster, 'zipcode');
//array_push($aFieldsSaveMaster, 'city');
//array_push($aFieldsSaveMaster, 'id_countid');
//array_push($aFieldsSaveMaster, 'id_langid');
//array_push($aFieldsSaveMaster, 'phone');
//array_push($aFieldsSaveMaster, 'mobile');
//array_push($aFieldsSaveMaster, 'email');
//array_push($aFieldsSaveMaster, 'url');
//array_push($aFieldsSaveMaster, 'program_tier');
//array_push($aFieldsSaveMaster, 'bsd_silver');
//array_push($aFieldsSaveMaster, 'bsd_gold');
//array_push($aFieldsSaveMaster, 'bsd_diamond');
//$aFieldsSaveNotMaster = array();
//array_push($aFieldsSaveNotMaster, 'id_pcid');
//array_push($aFieldsSaveNotMaster, 'reseller_id');
//array_push($aFieldsSaveNotMaster, 'organisationid');
//array_push($aFieldsSaveNotMaster, 'company_name');
//array_push($aFieldsSaveNotMaster, 'address1');
//array_push($aFieldsSaveNotMaster, 'address2');
//array_push($aFieldsSaveNotMaster, 'address3');
//array_push($aFieldsSaveNotMaster, 'zipcode');
//array_push($aFieldsSaveNotMaster, 'city');
//array_push($aFieldsSaveNotMaster, 'id_countid');
//array_push($aFieldsSaveNotMaster, 'id_langid');
//array_push($aFieldsSaveNotMaster, 'phone');
//array_push($aFieldsSaveNotMaster, 'mobile');
//array_push($aFieldsSaveNotMaster, 'email');
//array_push($aFieldsSaveNotMaster, 'url');
//array_push($aFieldsSaveNotMaster, 'program_tier');
//array_push($aFieldsSaveNotMaster, 'bsd_silver');
//array_push($aFieldsSaveNotMaster, 'bsd_gold');
//array_push($aFieldsSaveNotMaster, 'bsd_diamond');
//
//$aArgsSave['aData']['id_count'] = 0;
//$aArgsSave['aData']['id_lang'] = 0;
//$aArgsSave['aData']['id_dev'] = 0;
//$aArgsSave['aData']['id_cl'] = 1;
//
//$newLogin = 0;
//
//
//$qry = 'SELECT ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_pcid
//		 FROM ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni
//		 WHERE ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.reseller_id = (:reseller_id)
//		';
//$queryP = $CONFIG['dbconn'][0]->prepare($qry);
//$queryP->bindValue(':reseller_id', $varSQL['RESELLER_ID'], PDO::PARAM_STR);
//$queryP->execute();
//$rowsP = $queryP->fetchAll(PDO::FETCH_ASSOC);
//$numP = $queryP->rowCount();
//
//if($numP == 0){
//	$queryI = $CONFIG['dbconn'][0]->prepare('
//										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_
//										(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
//										VALUES
//										(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
//										');
//	$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
//	$queryI->bindValue(':id_cl', 1, PDO::PARAM_INT);
//	$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
//	$queryI->bindValue(':create_from', 999999999, PDO::PARAM_INT);
//	$queryI->execute();
//	$CONFIG['user']['id_pcid'] = $CONFIG['dbconn'][0]->lastInsertId();
//	
//	$newLogin = 1;
//}else{
//	$CONFIG['user']['id_pcid'] = $rowsP[0]['id_pcid'];
//}
//$aArgsSave['id_data'] = $CONFIG['user']['id_pcid'];
//
//
//$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_ext
//			(id_count, id_lang, id_dev, id_cl, restricted_all, id_pcid, reseller_id, organisationid, company_name, address1, address2, address3, zipcode, city, id_countid, id_langid, phone, mobile, email, url, program_tier, bsd_silver, bsd_gold, bsd_diamond, create_at, create_from, change_from)
//		VALUES
//			(:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_pcid, :reseller_id, :organisationid, :company_name, :address1, :address2, :address3, :zipcode, :city, :id_countid, :id_langid, :phone, :mobile, :email, :url, :program_tier, :bsd_silver, :bsd_gold, :bsd_diamond, :now, :create_from, :create_from)
//		ON DUPLICATE KEY UPDATE 
//			organisationid = (:organisationid),
//			company_name = (:company_name),
//			address1 = (:address1),
//			address2 = (:address2),
//			address3 = (:address3),
//			zipcode = (:zipcode),
//			city = (:city),
//			id_countid = (:id_countid),
//			id_langid = (:id_langid),
//			phone = (:phone),
//			mobile = (:mobile),
//			email = (:email),
//			url = (:url),
//			program_tier = (:program_tier),
//			bsd_silver = (:bsd_silver),
//			bsd_gold = (:bsd_gold),
//			bsd_diamond = (:bsd_diamond),
//			change_from = (:create_from),
//			del = (:nultime)
//		';
//$queryC = $CONFIG['dbconn'][0]->prepare($qry);
//$queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
//$queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
//$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
//$queryC->bindValue(':id_cl', 1, PDO::PARAM_INT);
//$queryC->bindValue(':restricted_all', 0, PDO::PARAM_INT);
//$queryC->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
//$queryC->bindValue(':reseller_id', $varSQL['RESELLER_ID'], PDO::PARAM_STR);
//$queryC->bindValue(':organisationid', $varSQL['ORGANISATIONID'], PDO::PARAM_STR);
//$queryC->bindValue(':company_name', $varSQL['COMPANY_NAME'], PDO::PARAM_STR);
//$queryC->bindValue(':address1', $varSQL['ADDRESS1'], PDO::PARAM_STR);
//$queryC->bindValue(':address2', $varSQL['ADDRESS2'], PDO::PARAM_STR);
//$queryC->bindValue(':address3', $varSQL['ADDRESS3'], PDO::PARAM_STR);
//$queryC->bindValue(':zipcode', $varSQL['ZIPCODE'], PDO::PARAM_STR);
//$queryC->bindValue(':city', $varSQL['CITY'], PDO::PARAM_STR);
//$queryC->bindValue(':id_countid', $CONFIG['user']['id_countid'], PDO::PARAM_STR);
//$queryC->bindValue(':id_langid', $CONFIG['user']['id_langid'], PDO::PARAM_STR);
//$queryC->bindValue(':phone', $varSQL['PHONE'], PDO::PARAM_STR);
//$queryC->bindValue(':mobile', $varSQL['MOBILE'], PDO::PARAM_STR);
//$queryC->bindValue(':email', $varSQL['EMAIL'], PDO::PARAM_STR);
//$queryC->bindValue(':url', '', PDO::PARAM_STR);
//$queryC->bindValue(':program_tier', $varSQL['Program_Tier'], PDO::PARAM_STR);
//$queryC->bindValue(':bsd_silver', $varSQL['BSD_Silver'], PDO::PARAM_STR);
//$queryC->bindValue(':bsd_gold', $varSQL['BSD_Gold'], PDO::PARAM_STR);
//$queryC->bindValue(':bsd_diamond', $varSQL['BSD_Diamond'], PDO::PARAM_STR);
//$queryC->bindValue(':now', $now, PDO::PARAM_STR);
//$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//$queryC->bindValue(':create_from', 999999999, PDO::PARAM_INT); 
//$queryC->execute();
//$numC = $queryC->rowCount();
//
//
//
//
//
//###############################################################################
//// save partner person
//###############################################################################
//$aArgsSavePP = array();
//$aArgsSavePP['table'] = $CONFIG['db'][0]['prefix'] . '_partnerpersons_';
//$aArgsSavePP['primarykey'] = 'id_ppid';
//$aArgsSavePP['allVersions'] = array();
//$aArgsSavePP['changedVersions'] = array();
//
//$aArgsSavePP['columns'] = array();
//$aArgsSavePP['columns']['id_ppid'] = 'i';
//$aArgsSavePP['columns']['id_pcid'] = 'i';
//$aArgsSavePP['columns']['userid'] = 's';
//$aArgsSavePP['columns']['salutation'] = 's';
//$aArgsSavePP['columns']['firstname'] = 's';
//$aArgsSavePP['columns']['lastname'] = 's';
//$aArgsSavePP['columns']['id_countid'] = 'i';
//$aArgsSavePP['columns']['id_langid'] = 'i';
//$aArgsSavePP['columns']['phone'] = 's';
//$aArgsSavePP['columns']['mobile'] = 's';
//$aArgsSavePP['columns']['email'] = 's';
//
//$aArgsSavePP['aFieldsNumbers'] = array();
//array_push($aArgsSavePP['aFieldsNumbers'], 'id_ppid');
//array_push($aArgsSavePP['aFieldsNumbers'], 'id_pcid');
//array_push($aArgsSavePP['aFieldsNumbers'], 'id_countid');
//array_push($aArgsSavePP['aFieldsNumbers'], 'id_langid');
//
//$aArgsSavePP['excludeUpdateUni'] = array();
//$aArgsSavePP['excludeUpdateUni']['id_ppid'] = array('',0);
//$aArgsSavePP['excludeUpdateUni']['id_pcid'] = array('',0);
//$aArgsSavePP['excludeUpdateUni']['userid'] = array('');
//$aArgsSavePP['excludeUpdateUni']['salutation'] = array('');
//$aArgsSavePP['excludeUpdateUni']['firstname'] = array('');
//$aArgsSavePP['excludeUpdateUni']['lastname'] = array('');
//$aArgsSavePP['excludeUpdateUni']['id_countid'] = array('',0);
//$aArgsSavePP['excludeUpdateUni']['id_langid'] = array('',0);
//$aArgsSavePP['excludeUpdateUni']['phone'] = array('');
//$aArgsSavePP['excludeUpdateUni']['mobile'] = array('');
//$aArgsSavePP['excludeUpdateUni']['email'] = array('');
//
//$aFieldsSaveMaster = array();
//array_push($aFieldsSaveMaster, 'id_ppid');
//array_push($aFieldsSaveMaster, 'id_pcid');
//array_push($aFieldsSaveMaster, 'userid');
//array_push($aFieldsSaveMaster, 'salutation');
//array_push($aFieldsSaveMaster, 'firstname');
//array_push($aFieldsSaveMaster, 'lastname');
//array_push($aFieldsSaveMaster, 'id_countid');
//array_push($aFieldsSaveMaster, 'id_langid');
//array_push($aFieldsSaveMaster, 'phone');
//array_push($aFieldsSaveMaster, 'mobile');
//array_push($aFieldsSaveMaster, 'email');
//$aFieldsSaveNotMaster = array();
//array_push($aFieldsSaveNotMaster, 'id_ppid');
//array_push($aFieldsSaveNotMaster, 'id_pcid');
//array_push($aFieldsSaveNotMaster, 'userid');
//array_push($aFieldsSaveNotMaster, 'salutation');
//array_push($aFieldsSaveNotMaster, 'firstname');
//array_push($aFieldsSaveNotMaster, 'lastname');
//array_push($aFieldsSaveNotMaster, 'id_countid');
//array_push($aFieldsSaveNotMaster, 'id_langid');
//array_push($aFieldsSaveNotMaster, 'phone');
//array_push($aFieldsSaveNotMaster, 'mobile');
//array_push($aFieldsSaveNotMaster, 'email');
//
//$aArgsSavePP['aData']['id_count'] = 0;
//$aArgsSavePP['aData']['id_lang'] = 0;
//$aArgsSavePP['aData']['id_dev'] = 0;
//$aArgsSavePP['aData']['id_cl'] = 1;
//
//
//
//$qry = 'SELECT ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.id_ppid
//		 FROM ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni
//		 WHERE ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.id_pcid = (:id_pcid)
//			 AND ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_uni.userid = (:userid)
//		';
//$queryP = $CONFIG['dbconn'][0]->prepare($qry);
//$queryP->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
//$queryP->bindValue(':userid', $varSQL['USERID'], PDO::PARAM_STR);
//$queryP->execute();
//$rowsP = $queryP->fetchAll(PDO::FETCH_ASSOC);
//$numP = $queryP->rowCount();
//
//if($numP == 0){
//	$queryI = $CONFIG['dbconn'][0]->prepare('
//										INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_
//										(id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
//										VALUES
//										(:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
//										');
//	$queryI->bindValue(':nul', 0, PDO::PARAM_INT);
//	$queryI->bindValue(':id_cl', 1, PDO::PARAM_INT);
//	$queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
//	$queryI->bindValue(':create_from', 999999999, PDO::PARAM_INT);
//	$queryI->execute();
//	$CONFIG['user']['id_ppid'] = $CONFIG['dbconn'][0]->lastInsertId();
//}else{
//	$CONFIG['user']['id_ppid'] = $rowsP[0]['id_ppid'];
//}
//$aArgsSavePP['id_data'] = $CONFIG['user']['id_ppid'];
//
//
//$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_ext
//			(id_count, id_lang, id_dev, id_cl, restricted_all, id_ppid, id_pcid, userid, salutation, firstname, lastname, id_countid, id_langid, phone, mobile, email, create_at, create_from, change_from)
//		VALUES
//			(:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_ppid, :id_pcid, :userid, :salutation, :firstname, :lastname, :id_countid, :id_langid, :phone, :mobile, :email, :now, :create_from, :create_from)
//		ON DUPLICATE KEY UPDATE 
//			salutation = (:salutation),
//			firstname = (:firstname),
//			lastname = (:lastname),
//			id_countid = (:id_countid),
//			id_langid = (:id_langid),
//			phone = (:phone),
//			mobile = (:mobile),
//			email = (:email),
//			change_from = (:create_from),
//			del = (:nultime)
//		';
//$queryC = $CONFIG['dbconn'][0]->prepare($qry);
//$queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
//$queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
//$queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
//$queryC->bindValue(':id_cl', 1, PDO::PARAM_INT);
//$queryC->bindValue(':restricted_all', 0, PDO::PARAM_INT);
//$queryC->bindValue(':id_ppid', $CONFIG['user']['id_ppid'], PDO::PARAM_INT);
//$queryC->bindValue(':id_pcid', $CONFIG['user']['id_pcid'], PDO::PARAM_INT);
//$queryC->bindValue(':userid', $varSQL['USERID'], PDO::PARAM_STR);
//$queryC->bindValue(':salutation', $varSQL['SALUTATION'], PDO::PARAM_STR);
//$queryC->bindValue(':firstname', $varSQL['FIRST_NAME'], PDO::PARAM_STR);
//$queryC->bindValue(':lastname', $varSQL['LAST_NAME'], PDO::PARAM_STR);
//$queryC->bindValue(':id_countid', $CONFIG['user']['id_countid'], PDO::PARAM_STR);
//$queryC->bindValue(':id_langid', $CONFIG['user']['id_langid'], PDO::PARAM_STR);
//$queryC->bindValue(':phone', $varSQL['PHONE'], PDO::PARAM_STR);
//$queryC->bindValue(':mobile', $varSQL['MOBILE'], PDO::PARAM_STR);
//$queryC->bindValue(':email', $varSQL['EMAIL'], PDO::PARAM_STR);
//$queryC->bindValue(':now', $now, PDO::PARAM_STR);
//$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
//$queryC->bindValue(':create_from', 999999999, PDO::PARAM_INT); 
//$queryC->execute();
//$numC = $queryC->rowCount();
//
//
//
//
//
//
###########################################################################
// Token
createAccesstoken();

// Config Cookie
setcookie('activesettings', json_encode($CONFIG['activeSettings']), 0, $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathApp'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], false);
###########################################################################


if(isset($aArgsSave)){
	$aArgsSave['changedVersions'] = array(array(0,0,0));
	$aArgsSave['allVersions'] = array(array(0,0,0));
	insertAll($aArgsSave);
}

$aArgsSavePP['changedVersions'] = array(array(0,0,0));
$aArgsSavePP['allVersions'] = array(array(0,0,0));
insertAll($aArgsSavePP);



header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathApp'] . 'index.php?initLogin=1&newLogin=' . $newLogin);
exit();





			
?>