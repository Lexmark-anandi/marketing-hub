<?php
$CONFIG['noCheck'] = true;
include_once(__DIR__ . '/config-admin.php');

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');





if($_GET['dl'] != '' && isset($_COOKIE['access'])){
	//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-access.php'); 
	//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-csrf.php'); 

	$aDl = array();
	$aDl = json_decode(base64_decode($_GET['dl']), true);
	setcookie('deeplink', json_encode($aDl), 0, $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], false);
	
	header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathPagesAdmin'] . 'p_sys-default.php?initLogin=1');
	exit();
}






setcookie('access', '', time() - 1000, $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], true);
setcookie('activesettings', '', time() - 1000, $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], false);
setcookie('csrf', '', time() - 1000, $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], false);
setcookie('deeplink', '', time() - 1000, $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], false);

if(isset($_GET['logout'])){
	$errorLogin = $TEXT['logoutMess']; 
}


$errorLogin = '';
if(isset($_POST['send'])) {
	$CONFIG['user'] = array();
	$CONFIG['activeSettings'] = array();
	$CONFIG['USER_TMP'] = array();
	$aFormValues = getPostData($_POST);

	$query = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_user.id_uid,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.firstname,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.lastname,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.email,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.password,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.id_r,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.active_country,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.active_language,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.active_device,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.active_client,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.active_syscountry,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.active_syslanguage,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.active_sysdevice,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.system_country,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.system_language,
											' . $CONFIG['db'][0]['prefix'] . 'system_user.grid_num_rows,
											' . $CONFIG['db'][0]['prefix'] . 'system_roles.specifications
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
			$CONFIG['user']['id'] = $rows[0]['id_uid'];
			$CONFIG['user']['right'] = $rows[0]['id_r'];
			$CONFIG['user']['specifications'] = str_split($rows[0]['specifications']);
			$CONFIG['user']['type'] = 'user';
			if($CONFIG['user']['specifications'][0] != 0) $CONFIG['user']['type'] = 'admin';
			if($CONFIG['user']['specifications'][1] != 0) $CONFIG['user']['type'] = 'systemadmin';
			$CONFIG['user']['name'] = $rows[0]['firstname'] ." " . $rows[0]['lastname'];
			$CONFIG['user']['email'] = $rows[0]['email'];
			$CONFIG['user']['id_real'] = $rows[0]['id_uid'];
			$CONFIG['user']['right_real'] = $rows[0]['id_r'];
			$CONFIG['user']['specifications_real'] = str_split($rows[0]['specifications']);
			$CONFIG['user']['type_real'] = 'user'; 
			if($CONFIG['user']['specifications_real'][0] != 0) $CONFIG['user']['type_real'] = 'admin';
			if($CONFIG['user']['specifications_real'][1] != 0) $CONFIG['user']['type_real'] = 'systemadmin';
			$CONFIG['user']['name_real'] = $rows[0]['firstname'] ." " . $rows[0]['lastname'];
			$CONFIG['user']['email_real'] = $rows[0]['email'];

			$CONFIG['activeSettings']['id_countid'] = $rows[0]['active_country'];
			$CONFIG['activeSettings']['id_langid'] = $rows[0]['active_language'];
			$CONFIG['activeSettings']['id_devid'] = $rows[0]['active_device'];
			$CONFIG['activeSettings']['id_sys_count'] = $rows[0]['active_syscountry'];
			$CONFIG['activeSettings']['id_sys_lang'] = $rows[0]['active_syslanguage'];
			$CONFIG['activeSettings']['id_sys_dev'] = $rows[0]['active_sysdevice'];
			$CONFIG['activeSettings']['id_countid_form'] = $rows[0]['active_country'];
			$CONFIG['activeSettings']['id_langid_form'] = $rows[0]['active_language'];
			$CONFIG['activeSettings']['id_devid_form'] = $rows[0]['active_device'];
			$CONFIG['activeSettings']['id_sys_count_form'] = $rows[0]['active_syscountry'];
			$CONFIG['activeSettings']['id_sys_lang_form'] = $rows[0]['active_syslanguage'];
			$CONFIG['activeSettings']['id_sys_dev_form'] = $rows[0]['active_sysdevice'];
			$CONFIG['activeSettings']['id_clid'] = $rows[0]['active_client'];
			$CONFIG['activeSettings']['gridNumRows'] = $rows[0]['grid_num_rows'];
			$CONFIG['activeSettings']['htmlDir'] = 'ltr';
			
			$CONFIG['activeSettings']['systemCountry'] = $rows[0]['system_country'];
			$CONFIG['activeSettings']['systemLanguage'] = $rows[0]['system_language'];
			if(!file_exists($CONFIG['system']['pathAdmin'] . 'i18n/' . $rows[0]['system_language'] . '.lang')){
				$CONFIG['activeSettings']['systemLanguage'] = strtolower($CONFIG['system']['langDefaultAdmin']);
			}					

			


			###########################################################################
			// Token
			createAccesstoken();
			
			// Config Cookie
			setcookie('activesettings', json_encode($CONFIG['activeSettings']), 0, $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], false);
			
			// Deeplink Cookie
			$aDl = array();
			if($_POST['deeplink'] != ''){
				$aDl = json_decode(base64_decode($_POST['deeplink']), true);
				setcookie('deeplink', json_encode($aDl), 0, $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], false);
			}
			
			header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathPagesAdmin'] . 'p_sys-default.php?initLogin=1');
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
<?php echo $CONFIG['favicon']['favicon'] ?>
<link href="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] ?>css/styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="page">
    <div id="loginOuter">
    <div id="loginLogo"></div>
    <div id="loginToolname"><?php echo $TEXT['apptitle'] ?> <span class="version"><?php echo $TEXT['version'] . ' ' . $CONFIG['system']['versionapp'].'-'.$CONFIG['system']['versionadmin'].'' ?> </span></div>
				<form method="post" action="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] . 'index.php' ?>" name="formLogin" id="formLogin" class="formForm">
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
									<input type="hidden" name="deeplink" id="deeplink" class="" value="<?php if(isset($_GET['dl'])) echo $_GET['dl'] ?>">
									<input type="submit" name="send" id="send" class="formButton" value="<?php echo $TEXT['login'] ?>">
								</div>
							</div>
						</div>
				</form>
			</div>
  </div>
  </body>
</html>