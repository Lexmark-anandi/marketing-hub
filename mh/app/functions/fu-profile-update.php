<?php 
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-app.php');
$varSQL = getPostData();

if(!isset($varSQL['hide_company_name'])) $varSQL['hide_company_name'] = 2;
if(!isset($varSQL['hide_address1'])) $varSQL['hide_address1'] = 2;
if(!isset($varSQL['hide_zipcode'])) $varSQL['hide_zipcode'] = 2;
if(!isset($varSQL['hide_city'])) $varSQL['hide_city'] = 2;
if(!isset($varSQL['hide_phone'])) $varSQL['hide_phone'] = 2;
if(!isset($varSQL['hide_email'])) $varSQL['hide_email'] = 2;
if(!isset($varSQL['hide_url'])) $varSQL['hide_url'] = 2;
if(!isset($varSQL['hide_contactname'])) $varSQL['hide_contactname'] = 2;

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$out = '';


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
$aArgsSave['columns']['hide_company_name'] = 'i';
$aArgsSave['columns']['hide_address1'] = 'i';
$aArgsSave['columns']['hide_zipcode'] = 'i';
$aArgsSave['columns']['hide_city'] = 'i';
$aArgsSave['columns']['hide_phone'] = 'i';
$aArgsSave['columns']['hide_email'] = 'i';
$aArgsSave['columns']['hide_url'] = 'i';

$aArgsSave['aFieldsNumbers'] = array();
array_push($aArgsSave['aFieldsNumbers'], 'id_pcid');
array_push($aArgsSave['aFieldsNumbers'], 'id_countid');
array_push($aArgsSave['aFieldsNumbers'], 'id_langid');
array_push($aArgsSave['aFieldsNumbers'], 'hide_company_name');
array_push($aArgsSave['aFieldsNumbers'], 'hide_address1');
array_push($aArgsSave['aFieldsNumbers'], 'hide_zipcode');
array_push($aArgsSave['aFieldsNumbers'], 'hide_city');
array_push($aArgsSave['aFieldsNumbers'], 'hide_phone');
array_push($aArgsSave['aFieldsNumbers'], 'hide_email');
array_push($aArgsSave['aFieldsNumbers'], 'hide_url');

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
$aArgsSave['excludeUpdateUni']['hide_company_name'] = array('',0);
$aArgsSave['excludeUpdateUni']['hide_address1'] = array('',0);
$aArgsSave['excludeUpdateUni']['hide_zipcode'] = array('',0);
$aArgsSave['excludeUpdateUni']['hide_city'] = array('',0);
$aArgsSave['excludeUpdateUni']['hide_phone'] = array('',0);
$aArgsSave['excludeUpdateUni']['hide_email'] = array('',0);
$aArgsSave['excludeUpdateUni']['hide_url'] = array('',0);

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
array_push($aFieldsSaveMaster, 'zipcode');
array_push($aFieldsSaveMaster, 'id_countid');
array_push($aFieldsSaveMaster, 'id_langid');
array_push($aFieldsSaveMaster, 'phone');
array_push($aFieldsSaveMaster, 'mobile');
array_push($aFieldsSaveMaster, 'email');
array_push($aFieldsSaveMaster, 'url');
array_push($aFieldsSaveMaster, 'hide_company_name');
array_push($aFieldsSaveMaster, 'hide_address1');
array_push($aFieldsSaveMaster, 'hide_zipcode');
array_push($aFieldsSaveMaster, 'hide_city');
array_push($aFieldsSaveMaster, 'hide_phone');
array_push($aFieldsSaveMaster, 'hide_email');
array_push($aFieldsSaveMaster, 'hide_url');
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
array_push($aFieldsSaveNotMaster, 'zipcode');
array_push($aFieldsSaveNotMaster, 'id_countid');
array_push($aFieldsSaveNotMaster, 'id_langid');
array_push($aFieldsSaveNotMaster, 'phone');
array_push($aFieldsSaveNotMaster, 'mobile');
array_push($aFieldsSaveNotMaster, 'email');
array_push($aFieldsSaveNotMaster, 'url');
array_push($aFieldsSaveNotMaster, 'hide_company_name');
array_push($aFieldsSaveNotMaster, 'hide_address1');
array_push($aFieldsSaveNotMaster, 'hide_zipcode');
array_push($aFieldsSaveNotMaster, 'hide_city');
array_push($aFieldsSaveNotMaster, 'hide_phone');
array_push($aFieldsSaveNotMaster, 'hide_email');
array_push($aFieldsSaveNotMaster, 'hide_url');

$aArgsSave['aData']['id_count'] = 0;
$aArgsSave['aData']['id_lang'] = 0;
$aArgsSave['aData']['id_dev'] = 0;
$aArgsSave['aData']['id_cl'] = 1;

$aArgsSave['id_data'] = $CONFIG['user']['id_pcid'];


$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_loc
			(id_count, id_lang, id_dev, id_cl, restricted_all, id_pcid, reseller_id, organisationid, company_name, address1, address2, address3, zipcode, city, id_countid, id_langid, phone, mobile, email, url, hide_company_name, hide_address1, hide_zipcode, hide_city, hide_phone, hide_email, hide_url, create_at, create_from, change_from)
		VALUES
			(:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_pcid, :reseller_id, :organisationid, :company_name, :address1, :address2, :address3, :zipcode, :city, :id_countid, :id_langid, :phone, :mobile, :email, :url, :hide_company_name, :hide_address1, :hide_zipcode, :hide_city, :hide_phone, :hide_email, :hide_url, :now, :create_from, :create_from)
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
			hide_company_name = (:hide_company_name),
			hide_address1 = (:hide_address1),
			hide_zipcode = (:hide_zipcode),
			hide_city = (:hide_city),
			hide_phone = (:hide_phone),
			hide_email = (:hide_email),
			hide_url = (:hide_url),
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
$queryC->bindValue(':reseller_id', '', PDO::PARAM_STR);
$queryC->bindValue(':organisationid', '', PDO::PARAM_STR);
$queryC->bindValue(':company_name', $varSQL['company_name'], PDO::PARAM_STR);
$queryC->bindValue(':address1', $varSQL['address1'], PDO::PARAM_STR);
$queryC->bindValue(':address2', '', PDO::PARAM_STR);
$queryC->bindValue(':address3', '', PDO::PARAM_STR);
$queryC->bindValue(':zipcode', $varSQL['zipcode'], PDO::PARAM_STR);
$queryC->bindValue(':city', $varSQL['city'], PDO::PARAM_STR);
$queryC->bindValue(':id_countid', $CONFIG['user']['id_countid'], PDO::PARAM_STR);
$queryC->bindValue(':id_langid', $CONFIG['user']['id_langid'], PDO::PARAM_STR);
$queryC->bindValue(':phone', $varSQL['phone'], PDO::PARAM_STR);
$queryC->bindValue(':mobile', '', PDO::PARAM_STR);
$queryC->bindValue(':email', $varSQL['email'], PDO::PARAM_STR);
$queryC->bindValue(':url', $varSQL['url'], PDO::PARAM_STR);
$queryC->bindValue(':hide_company_name', $varSQL['hide_company_name'], PDO::PARAM_INT);
$queryC->bindValue(':hide_address1', $varSQL['hide_address1'], PDO::PARAM_INT);
$queryC->bindValue(':hide_zipcode', $varSQL['hide_zipcode'], PDO::PARAM_INT);
$queryC->bindValue(':hide_city', $varSQL['hide_city'], PDO::PARAM_INT);
$queryC->bindValue(':hide_phone', $varSQL['hide_phone'], PDO::PARAM_INT);
$queryC->bindValue(':hide_email', $varSQL['hide_email'], PDO::PARAM_INT);
$queryC->bindValue(':hide_url', $varSQL['hide_url'], PDO::PARAM_INT);
$queryC->bindValue(':now', $now, PDO::PARAM_STR);
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':create_from', 999999999, PDO::PARAM_INT); 
$queryC->execute();
$numC = $queryC->rowCount();


$aArgsSave['changedVersions'] = array(array(0,0,0));
$aArgsSave['allVersions'] = array(array(0,0,0));
insertAll($aArgsSave);




######################################################################
######################################################################
######################################################################

$aArgsSave = array();
$aArgsSave['table'] = $CONFIG['db'][0]['prefix'] . '_partnerpersons_';
$aArgsSave['primarykey'] = 'id_ppid';
$aArgsSave['allVersions'] = array();
$aArgsSave['changedVersions'] = array();

$aArgsSave['columns'] = array();
$aArgsSave['columns']['id_ppid'] = 'i';
$aArgsSave['columns']['contactname'] = 's';
$aArgsSave['columns']['hide_contactname'] = 'i';
$aArgsSave['columns']['phone'] = 's';
$aArgsSave['columns']['hide_phone'] = 'i';
$aArgsSave['columns']['email'] = 's';
$aArgsSave['columns']['hide_email'] = 'i';

$aArgsSave['aFieldsNumbers'] = array();
array_push($aArgsSave['aFieldsNumbers'], 'id_ppid');
array_push($aArgsSave['aFieldsNumbers'], 'hide_contactname');
array_push($aArgsSave['aFieldsNumbers'], 'hide_phone');
array_push($aArgsSave['aFieldsNumbers'], 'hide_email');

$aArgsSave['excludeUpdateUni'] = array();
$aArgsSave['excludeUpdateUni']['id_ppid'] = array('',0);
$aArgsSave['excludeUpdateUni']['contactname'] = array('');
$aArgsSave['excludeUpdateUni']['hide_contactname'] = array('',0);
$aArgsSave['excludeUpdateUni']['phone'] = array('');
$aArgsSave['excludeUpdateUni']['hide_phone'] = array('',0);
$aArgsSave['excludeUpdateUni']['email'] = array('');
$aArgsSave['excludeUpdateUni']['hide_email'] = array('',0);

$aFieldsSaveMaster = array();
array_push($aFieldsSaveMaster, 'id_ppid');
array_push($aFieldsSaveMaster, 'contactname');
array_push($aFieldsSaveMaster, 'hide_contactname');
array_push($aFieldsSaveMaster, 'phone');
array_push($aFieldsSaveMaster, 'hide_phone');
array_push($aFieldsSaveMaster, 'email');
array_push($aFieldsSaveMaster, 'hide_email');
$aFieldsSaveNotMaster = array();
array_push($aFieldsSaveNotMaster, 'id_ppid');
array_push($aFieldsSaveNotMaster, 'contactname');
array_push($aFieldsSaveNotMaster, 'hide_contactname');
array_push($aFieldsSaveNotMaster, 'phone');
array_push($aFieldsSaveNotMaster, 'hide_phone');
array_push($aFieldsSaveNotMaster, 'email');
array_push($aFieldsSaveNotMaster, 'hide_email');

$aArgsSave['aData']['id_count'] = 0;
$aArgsSave['aData']['id_lang'] = 0;
$aArgsSave['aData']['id_dev'] = 0;
$aArgsSave['aData']['id_cl'] = 1;

$aArgsSave['id_data'] = $CONFIG['user']['id_ppid'];


$qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_partnerpersons_loc
			(id_count, id_lang, id_dev, id_cl, restricted_all, id_ppid, contactname, hide_contactname, phone, hide_phone, email, hide_email, create_at, create_from, change_from)
		VALUES
			(:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_ppid, :contactname, :hide_contactname, :phone, :hide_phone, :email, :hide_email, :now, :create_from, :create_from)
		ON DUPLICATE KEY UPDATE 
			contactname = (:contactname),
			hide_contactname = (:hide_contactname),
			phone = (:phone),
			hide_phone = (:hide_phone),
			email = (:email),
			hide_email = (:hide_email),
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
$queryC->bindValue(':contactname', $varSQL['contactname'], PDO::PARAM_STR);
$queryC->bindValue(':hide_contactname', $varSQL['hide_contactname'], PDO::PARAM_INT);
$queryC->bindValue(':phone', $varSQL['phone'], PDO::PARAM_STR);
$queryC->bindValue(':hide_phone', $varSQL['hide_phone'], PDO::PARAM_INT);
$queryC->bindValue(':email', $varSQL['email'], PDO::PARAM_STR);
$queryC->bindValue(':hide_email', $varSQL['hide_email'], PDO::PARAM_INT);
$queryC->bindValue(':now', $now, PDO::PARAM_STR);
$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryC->bindValue(':create_from', 999999999, PDO::PARAM_INT); 
$queryC->execute();
$numC = $queryC->rowCount();


$aArgsSave['changedVersions'] = array(array(0,0,0));
$aArgsSave['allVersions'] = array(array(0,0,0));
insertAll($aArgsSave);






//if($num > 0){
//	$out = '<div class="formmessageOK">' . $TEXT['messageOK'] . '</div>';
//}else{
//	$out = '<div class="formmessageError">' . $TEXT['messageError'] . '</div>';
//}

$out = '<div class="formmessageOK">' . $TEXT['messageOK'] . '</div>';

echo $out;

?>