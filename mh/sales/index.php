<?php
$CONFIG['noCheck'] = true;
include_once(__DIR__ . '/../app/config-app.php');

setcookie('access', '', time() - 1000, $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathApp'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], true);
setcookie('activesettings', '', time() - 1000, $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathApp'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], false);
setcookie('csrf', '', time() - 1000, $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathApp'], $_SERVER['HTTP_HOST'], $CONFIG['system']['cookie_secure'], false);


$date = new DateTime(); 
$now = $date->format('Y-m-d H:i:s');

$errorLogin = '';
$listPartner = '<option value="0"></option>';

$listCountry = '<option value="0"></option>';
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid,
										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language,
										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni 
								
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country, ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
									');
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	$listCountry .= '<option value="' . $row['id_count2lang'] . '">' . $row['country'] . ' / ' . $row['language'] . '</option>';
}



#######################################################
if(isset($_POST['send'])) {
	include_once(__DIR__ . '/login.php');
}
#######################################################




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
<title><?php echo $TEXT['title'] ?> / Sales</title>
<?php echo $CONFIG['favicon']['favicon'] ?>
<link href="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] ?>css/styles.css" rel="stylesheet" type="text/css">
<style>
#loginOuter .formLabel {
	width: 150px;
}
#loginOuter .formField {
    margin-left: 160px;
}
#loginOuter .formRowSpace {
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #ccc !important;
}
</style>
</head>

<body>
<div id="page">
    <div id="loginOuter">
    <div id="loginLogo"></div>
    <div id="loginToolname"><?php echo $TEXT['title'] ?> / Sales </span></div>
				<form method="post" action="index.php" name="formLogin" id="formLogin" class="formForm" onsubmit="return checkForm()">
						<div class="formTab">
							<div class="formRow">
								<div class="formLabel">
									<label for="country">Country / Language:</label>
								</div>
								<div class="formField">
									<select name="country" id="country" class="textfield" onchange="selectPartner()">
                                    	<?php echo $listCountry ?>
                                    </select>
								</div>
							</div>
							<div class="formRow formRowSpace">
								<div class="formLabel">
									<label for="range">Targetgroup:</label>
								</div>
								<div class="formField">
                                                                    <input type="radio" name="range" id="rangeAll" class="radioRange" value="all" checked onclick="selectPartner()"> <label for="rangeAll">All Partners</label><br>
                                                                    <input type="radio" name="range" id="rangeBSD" class="radioRange" value="bsd" onclick="selectPartner()"> <label for="rangeBSD">Business Solutions only</label><br>
                                                                        <input type="radio" name="range" id="rangeDistribution" class="radioRange" value="distribution" onclick="selectPartner()"> <label for="rangeDistribution">Distribution only</label>
								</div>
							</div>
                            
                            
							<div class="formRow">
								<div class="formLabel">
									<label for="partner">Partner (optional):</label>
								</div>
								<div class="formField">
									<select name="partner" id="partner" class="textfield">
                                    	<?php echo $listPartner ?>
                                    </select>
								</div>
							</div>
                            
                            
							<div class="formRow">
								<div class="formLabel">
								</div>
								<div class="formField">
									<div class="errormessage"><?php echo $errorLogin ?></div>
									<input type="submit" name="send" id="send" class="formButton" value="Login">
								</div>
							</div>
						</div>
				</form>
			</div>
  </div>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jquery-3.1.1.min.js"></script>

<script language="javascript" type="text/javascript">
var objSystem = <?php echo json_encode($CONFIG['system']) ?>;
var initLogin = '?initLogin=1';

function selectPartner() {
	var data = 'count2lang=' + $('#country option:selected').val();
	data += '&range=' + $('.radioRange:checked').val();
	
	$.ajax({  
		url: objSystem.directoryInstallation + 'sales/fu-partner-select.php' + initLogin,    
		type: 'post',          
		data: data,       
		cache: false,
		headers: {
//			csrfToken: Cookies.get('csrf')
		},
		success: function (result, status, jqXHR) {
			$('#partner').html(result);
		}
	});
}

function checkForm() {
	var error = 0;
	$('.formRow').removeClass('rowError');
	$('.errormessage').html('');
	
	if($('#country option:selected').val() == 0){
		error = 1;
		$('#country').closest('.formRow').addClass('rowError');
	}
	
	if(error == 0){
		return true;
	}else{
		$('.errormessage').html('ERROR: Please check the highlighted fields!')
		return false;
	}
	
	
}
</script>
  </body>
</html>