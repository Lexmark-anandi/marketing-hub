<?php
$CONFIG['system']['pathInclude'] = "../";
$CONFIG['noCheck'] = true;
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

setcookie('access', '', time() - 1000, '/'.$CONFIG['system']['directorySystem'].$CONFIG['system']['pathAdmin'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], true);
setcookie('userconfig', '', time() - 1000, '/'.$CONFIG['system']['directorySystem'].$CONFIG['system']['pathAdmin'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], false);
setcookie('csrf', '', time() - 1000, '/'.$CONFIG['system']['directorySystem'].$CONFIG['system']['pathAdmin'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], false);
setcookie('refreshpage', '', time() - 1000, '/'.$CONFIG['system']['directorySystem'].$CONFIG['system']['pathAdmin'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], false);

if(isset($_GET['logout'])){
	$errorLogin = $TEXT['logoutMess']; 
}


$errorLogin = '';
if(isset($_POST['send'])) {
	$CONFIG['USER'] = array();
	$CONFIG['USER_CONF'] = array();
	$CONFIG['USER_TMP'] = array();
	$aFormValues = getPostData($_POST);

	$query = $CONFIG['dbconn']->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.firstname,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.lastname,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.email,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.id_r,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.password,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.langsys,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.active_country,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.active_language,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.active_device,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.active_syscountry,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.active_syslanguage,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.active_sysdevice,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.num_row,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.active_client,
											' . $CONFIG['db'][0]['prefix'] . 'system_roles.local_specific
										FROM ' . $CONFIG['db'][0]['prefix'] . 'system_user 
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'system_roles
											ON ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_r = ' . $CONFIG['db'][0]['prefix'] . 'system_roles.id_r

										WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_user.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.username = (:username)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.username <> (:empty)
											AND ' . $CONFIG['db'][0]['prefix'] . 'system_user.password <> (:empty)
										');
	$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
	$query->bindValue(':username', $aFormValues['username'], PDO::PARAM_STR);
	$query->bindValue(':empty', '', PDO::PARAM_STR);
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);
	$num = $query->rowCount();

	if($num == 1){
		$t_hasher = new PasswordHash(8, false);
		$check = $t_hasher->CheckPassword($aFormValues['password'], $rows[0]['password']);
		
		if($check == 1){
			$CONFIG['USER']['id'] = $rows[0]['id_uid'];
			$CONFIG['USER']['right'] = $rows[0]['id_r'];
			$CONFIG['USER']['rights_specific'] = $rows[0]['local_specific'];
			$CONFIG['USER']['right_country'] = substr($rows[0]['local_specific'],0,1);
			$CONFIG['USER']['right_language'] = substr($rows[0]['local_specific'],1,1);
			$CONFIG['USER']['right_device'] = substr($rows[0]['local_specific'],2,1);
			$CONFIG['USER']['right_client'] = substr($rows[0]['local_specific'],3,1);
			$CONFIG['USER']['right_admin'] = substr($rows[0]['local_specific'],4,1);
			$CONFIG['USER']['right_systemadmin'] = substr($rows[0]['local_specific'],5,1);
			$CONFIG['USER']['right_changeclient'] = substr($rows[0]['local_specific'],6,1);
			$CONFIG['USER']['right_changeuser'] = substr($rows[0]['local_specific'],7,1);
			$CONFIG['USER']['right_changelanguage'] = substr($rows[0]['local_specific'],8,1);
			$CONFIG['USER']['right_editallcountries'] = substr($rows[0]['local_specific'],9,1);
			$CONFIG['USER']['right_editalllanguages'] = substr($rows[0]['local_specific'],10,1);
			$CONFIG['USER']['right_editalldevices'] = substr($rows[0]['local_specific'],11,1);
			$CONFIG['USER']['name'] = $rows[0]['firstname'] ." " . $rows[0]['lastname'];
			$CONFIG['USER']['email'] = $rows[0]['email'];
			$CONFIG['USER']['id_real'] = $rows[0]['id_uid'];
			$CONFIG['USER']['right_real'] = $rows[0]['id_r'];
			$CONFIG['USER']['rights_specific_real'] = $rows[0]['local_specific'];
			$CONFIG['USER']['right_country_real'] = substr($rows[0]['local_specific'],0,1);
			$CONFIG['USER']['right_language_real'] = substr($rows[0]['local_specific'],1,1);
			$CONFIG['USER']['right_device_real'] = substr($rows[0]['local_specific'],2,1);
			$CONFIG['USER']['right_client_real'] = substr($rows[0]['local_specific'],3,1);
			$CONFIG['USER']['right_admin_real'] = substr($rows[0]['local_specific'],4,1);
			$CONFIG['USER']['right_systemadmin_real'] = substr($rows[0]['local_specific'],5,1);
			$CONFIG['USER']['right_changeclient_real'] = substr($rows[0]['local_specific'],6,1);
			$CONFIG['USER']['right_changeuser_real'] = substr($rows[0]['local_specific'],7,1);
			$CONFIG['USER']['right_changelanguage_real'] = substr($rows[0]['local_specific'],8,1);
			$CONFIG['USER']['right_editallcountries_real'] = substr($rows[0]['local_specific'],9,1);
			$CONFIG['USER']['right_editalllanguages_real'] = substr($rows[0]['local_specific'],10,1);
			$CONFIG['USER']['right_editalldevices_real'] = substr($rows[0]['local_specific'],11,1);
			$CONFIG['USER']['name_real'] = $rows[0]['firstname'] ." " . $rows[0]['lastname'];
			$CONFIG['USER']['email_real'] = $rows[0]['email'];

			$CONFIG['USER_CONF']['activeCountry'] = $rows[0]['active_country'];
			$CONFIG['USER_CONF']['activeLanguage'] = $rows[0]['active_language'];
			$CONFIG['USER_CONF']['activeDevice'] = $rows[0]['active_device'];
			$CONFIG['USER_CONF']['activeSysCountry'] = $rows[0]['active_syscountry'];
			$CONFIG['USER_CONF']['activeSysLanguage'] = $rows[0]['active_syslanguage'];
			$CONFIG['USER_CONF']['activeSysDevice'] = $rows[0]['active_sysdevice'];
			$CONFIG['USER_CONF']['activeClient'] = $rows[0]['active_client'];
			$CONFIG['USER_CONF']['gridNumRows'] = $rows[0]['num_row'];
			$CONFIG['USER_CONF']['htmlDir'] = 'ltr';
			
			if(file_exists($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathAdmin'] . 'i18n/' . $rows[0]['langsys'] . '.lang')){
				$CONFIG['USER_CONF']['systemlang'] = $rows[0]['langsys'];
			}					

			###########################################################################
			// Active client
			$clients = array();
			$queryC = $CONFIG['dbconn']->prepare('
												SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid, 
													' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
												FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni
												
												INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients
													ON ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_clid = ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid
													
												WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.del = (:nultime)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_count = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_lang = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_dev = (:nul)
													AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.active = (:active)
													AND ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_uid = (:id_uid)
												ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
												');
			$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
			$queryC->bindValue(':active', 1, PDO::PARAM_INT);
			$queryC->bindValue(':id_uid', $CONFIG['USER']['id'], PDO::PARAM_INT);

			if($CONFIG['USER']['right_client'] == 1){
				$queryC = $CONFIG['dbconn']->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid, 
														' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
													FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni
													
													INNER JOIN  ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients
														ON ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_clid = ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid
														
													WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_count = (:nul)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_lang = (:nul)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_dev = (:nul)
														AND ' . $CONFIG['db'][0]['prefix'] . 'system_user2clients.id_uid = (:id_uid)
													ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
													');
				$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
				$queryC->bindValue(':id_uid', $CONFIG['USER']['id'], PDO::PARAM_INT);
			}

			if($CONFIG['USER']['right_client'] == 2){
				$queryC = $CONFIG['dbconn']->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid, 
														' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
													FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni
													WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_count = (:nul)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_lang = (:nul)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_dev = (:nul)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.active = (:active)
													ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
													');
				$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
				$queryC->bindValue(':active', 1, PDO::PARAM_INT);
			}

			if($CONFIG['USER']['right_client'] == 9){
				$queryC = $CONFIG['dbconn']->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_clid, 
														' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
													FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni
													WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_count = (:nul)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_lang = (:nul)
														AND ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.id_dev = (:nul)
													ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_clients_uni.client
													');
				$queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$queryC->bindValue(':nul', 0, PDO::PARAM_INT);
			}
			
			$queryC->execute();
			$rowsC = $queryC->fetchAll(PDO::FETCH_ASSOC);
			$numC = $queryC->rowCount();
			$nC = 0;

			foreach($rowsC as $datC){
				if($nC == 0 && $CONFIG['USER_CONF']['activeClient'] == 0){
					$CONFIG['USER_CONF']['activeClient'] = $datC['id_clid'];
				}
				array_push($clients, $datC['id_clid']);
				$nC++;
			}
			if(!in_array($CONFIG['USER_CONF']['activeClient'], $clients)){
				$CONFIG['USER_CONF']['activeClient'] = $clients[0];
			}
			###########################################################################
			


			###########################################################################
			// Token
			createAccesstoken();
			
			// Config Cookie
			setcookie('userconfig', json_encode($CONFIG['USER_CONF']), 0, '/'.$CONFIG['system']['directorySystem'].$CONFIG['system']['pathAdmin'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], false);

			header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . '/' . $CONFIG['system']['directorySystem'] . $CONFIG['system']['pathPagesAdmin'] . 'p_sys-default.php');
			exit();

		}else{
    		$errorLogin = $TEXT['loginError'];
		} 
		
	}else{
    	$errorLogin = $TEXT['loginError'];
	}
}





			
?>
<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<meta name="mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta charset="utf-8">
<meta name="robots" content="noindex">
<title><?php echo $TEXT['browsertitle'] ?></title>
<?php echo $CONFIG['system']['favicon'] ?>
<link href="<?php echo $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathAdmin'] ?>css/styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="page">
    <div id="loginOuter">
    <div id="loginLogo"></div>
    <div id="loginToolname"><?php echo $TEXT['apptitle'] ?> <span class="version"><?php echo $TEXT['version'] . ' ' . $CONFIG['system']['versionapp'].'-'.$CONFIG['system']['versionadmin'].'' ?> </span></div>
				<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" name="formLogin" id="formLogin" class="formForm">
						<div class="formTab">
							<div class="formRow">
								<div class="formLabel">
									<label for="username"><?php echo $TEXT['username'] ?>:</label>
								</div>
								<div class="formField">
									<input type="text" name="username" id="username" class="textfield" value="">
								</div>
							</div>
							<div class="formRow">
								<div class="formLabel">
									<label for="password"><?php echo $TEXT['password'] ?>:</label>
								</div>
								<div class="formField">
									<input type="password" name="password" id="password" class="textfield" value="">
								</div>
							</div>
							<div class="formRow">
								<div class="formLabel">
								</div>
								<div class="formField">
									<div class="errormessage"><?php echo $errorLogin ?></div>
									<input type="submit" name="send" id="send" class="formButton" value="<?php echo $TEXT['login'] ?>">
								</div>
							</div>
						</div>
				</form>
			</div>
  </div>
  </body>
</html>