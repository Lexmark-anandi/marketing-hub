<?php
include_once(__DIR__ . '/config-app.php');

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');

$NAVIGATION = '';
$NAVIGATION .= '<ul>'; 

// search for active campaigns
$conBsdTemp = 'AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.bsd_only = 2 ';
if($CONFIG['user']['bsd'] == 1) $conBsdTemp = 'AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.bsd_only IN (2,1) ';
if($CONFIG['user']['distri'] == 1) $conBsdTemp = 'AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.bsd_only IN (2,3) ';

$queryProm = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid
										FROM ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_
											ON ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_countid = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_langid = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_campid = ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_campid
												AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.del = (:nultime)

										WHERE ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.published_at < (:now)
											AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.published_at <> (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.startdate < (:now)
											AND (' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.enddate > (:now)
												OR ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.enddate = (:nultime))
											' . $conBsdTemp . '
									');
$queryProm->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryProm->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$queryProm->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$queryProm->bindValue(':now', $now, PDO::PARAM_STR);
$queryProm->execute();
$rowsProm = $queryProm->fetchAll(PDO::FETCH_ASSOC);
$numProm = $queryProm->rowCount();

if($numProm > 0){
	$NAVIGATION .= '<li data-caid="campaigns">' . $TEXT['campaigns'] . '</li>';
}


###########################

// search for active promotions
$conBsdTemp = 'AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.bsd_only = 2 ';
if($CONFIG['user']['bsd'] == 1) $conBsdTemp = 'AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.bsd_only IN (2,1) ';
if($CONFIG['user']['distri'] == 1) $conBsdTemp = 'AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.bsd_only IN (2,3) ';

$queryProm = $CONFIG['dbconn'][0]->prepare('
										SELECT ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid,
											' . $CONFIG['db'][0]['prefix'] . '_promotions2partner_.id_pcid
										FROM ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni
										
										INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_
											ON ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_countid = (:id_count)
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_langid = (:id_lang)
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.id_promid = ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2countries_.del = (:nultime)

										LEFT JOIN ' . $CONFIG['db'][0]['prefix'] . '_promotions2partner_
											ON ' . $CONFIG['db'][0]['prefix'] . '_promotions2partner_.id_promid = ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_promid
												AND ' . $CONFIG['db'][0]['prefix'] . '_promotions2partner_.del = (:nultime)
										
										WHERE ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.published_at < (:now)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.published_at <> (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.startdate < (:now)
											AND ' . $CONFIG['db'][0]['prefix'] . '_promotions_uni.enddate > (:now)
											' . $conBsdTemp . '
									');
$queryProm->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$queryProm->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$queryProm->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$queryProm->bindValue(':now', $now, PDO::PARAM_STR);
$queryProm->execute();
$rowsProm = $queryProm->fetchAll(PDO::FETCH_ASSOC);
//$numProm = $queryProm->rowCount();
$numProm = 0;
foreach($rowsProm as $rowProm){
	if($rowProm['id_pcid'] == '' || $rowProm['id_pcid'] == $CONFIG['user']['id_pcid']){
		$numProm++;
	}
}

if($numProm > 0){
	$NAVIGATION .= '<li data-caid="promotions">' . $TEXT['promotions'] . '</li>';
}


###########################


$conBsdTemp = 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.bsd_only = 2 ';
if($CONFIG['user']['bsd'] == 1) $conBsdTemp = 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.bsd_only IN (2,1) ';
if($CONFIG['user']['distri'] == 1) $conBsdTemp = 'AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.bsd_only IN (2,3) ';

// search for templates
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_caid,
										' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.category
									FROM ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates_uni
										ON ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_count = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_lang = (:id_lang)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_caid = ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_caid
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.del = (:nultime)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.published_at < (:now)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.published_at <> (:nultime)
											' . $conBsdTemp . '
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_
										ON ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_countid = (:id_count)
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.id_tempid = ' . $CONFIG['db'][0]['prefix'] . '_templates_uni.id_tempid
											AND ' . $CONFIG['db'][0]['prefix'] . '_templates2countries_.del = (:nultime)
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_count = (:id_count)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_lang = (:id_lang)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.del = (:nultime)
									GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_caid
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.rank
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':id_count', $CONFIG['user']['id_countid'], PDO::PARAM_INT);
$query->bindValue(':id_lang', $CONFIG['user']['id_langid'], PDO::PARAM_INT);
$query->bindValue(':now', $now, PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

if($num > 0){
	foreach($rows as $row){
		$NAVIGATION .= '<li data-caid="' . $row['id_caid'] . '">' . $row['category'] . '</li>';
	}
}

$NAVIGATION .= '<li data-caid="myassets">' . $TEXT['myassets'] . '</li>';
$NAVIGATION .= '</ul>';

$NAVRIGHT = '<ul><li data-caid="profile">' . $TEXT['companyProfile'] . '</li></ul>';

			
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
<title><?php echo $TEXT['title'] ?></title>
<?php echo $CONFIG['favicon']['favicon'] ?>
<link href="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathApp'] ?>css/styles.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div id="page">
      <div id="headerOuter">
        <div class="pagewidth">
          <div id="topOuter"><div id="logo"></div><div id="toolname"><?php echo $TEXT['title'] ?></div></div>
          <div id="navigationOuter"><div class="navLeft"><?php echo $NAVIGATION ?></div><div class="navRight"><?php echo $NAVRIGHT ?></div></div>
        </div>
      </div>
      <div id="lineTop"></div>
      <div id="mainOuter">
        <div class="pagewidth">
        	<div class="mainLeft overviewGrid"></div>
        	<div class="mainRight"></div>
        </div>
      </div>
      <div id="editOuter">
        <div class="pagewidth">
        	<div class="mainLeft">
                <div class="editPromotions"></div>
                <div class="editThumbnails"></div>
                <div class="editPreview">
                    <div class="editPreviewInner">
                        <div class="editPreviewBackground"></div>
                        <div class="editPreviewComponents"></div>
                    </div>
                </div>
            </div>
        	<div class="mainRight">
            
            <form action="" method="" name="formAsset" id="formAsset" class="">
            	<div class="editForm">
                    <div class="formRow formRowSpace">
                        <div class="formLabel"><?php echo $TEXT['AssetTitle'] ?></div>
                        <div class="formField"><input type="text" class="textfield" name="assettitle" id="assettitle"></div>
                    </div>
    				
             		<div class="editFormComponent"></div>
             		<div class="editFormConfiguration"></div>
   
    
                    <div class="formRow formRowButton">
  					  <div class="formmessage"></div>
                      <div class="buttongroup buttongroupRight">
                            <button type="button" class="buttonAll buttonBig" data-action="cancel"><?php echo $TEXT['cancel'] ?></button>
                            <button type="button" class="buttonAll buttonBig buttonGreen" data-action="save"><?php echo $TEXT['save'] ?></button>
                            <button type="button" class="buttonAll buttonBig buttonBlue" data-action="export"><?php echo $TEXT['export'] ?></button>
                        </div>
                    </div>
    
                    <input type="hidden" name="id_caid" id="id_caid" value="0">
                    <input type="hidden" name="id_asid" id="id_asid" value="0">
                    <input type="hidden" name="id_promid" id="id_promid" value="0">
                    <input type="hidden" name="id_campid" id="id_campid" value="0">
                    <input type="hidden" name="components" id="components" value="">
                </div>
            </form>
            
            </div>
        </div>
      </div>
      <div id="footerOuter">
        <div class="pagewidth">
          <ul class="legal-menu-brand">
            <li class="legal-menu-brand-logo"><img src="<?php echo $CONFIG['system']['directoryInstallation'] ?>app/img/lxk-symbol-2x.svg" title="Lexmark symbol" alt="Lexmark symbol"></li>
            <li>
              <ul class="legal-menu-brand-copyright">
                <li><?php echo $TEXT['company'] ?></li>
                <li>&copy;<?php echo date('Y') ?> <?php echo $TEXT['copyright'] ?></li>
              </ul>
            </li>
          </ul>
          
          <div id="navFeedback">              
          	<ul>
            	<li><?php echo $TEXT['Feedback'] ?></li>
            </ul>
        </div>
      </div>
    </div>
    <div id="overlay"></div>
    <div id="sessionWarning"><?php echo $TEXT['sessionExpiredLogin'] ?></div>
</div>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jquery-3.1.1.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jquery-ui-1.12.1.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jquery-ui-i18n.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/js-cookie-master-2.1.3/js.cookie.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/loadmask-0.4/jquery.loadmask.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/dropzone-5.2.0/dropzone.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/form-3.51.0/jquery.form.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jssor/jssor.slider.min.js"></script>
<!--
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/timepicker-addon-1.5.3/jquery-ui-timepicker-addon.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/timepicker-addon-1.5.3/i18n/jquery-ui-timepicker-<?php echo $CONFIG['activeSettings']['systemLanguage'] ?>.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/localisation-1.1.0/jquery.localisation.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jqGrid-4.6.0/plugins/ui.multiselect.js"></script> 
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jqGrid-4.6.0/plugins/locale/ui-multiselect-<?php echo $CONFIG['activeSettings']['systemLanguage'] ?>.js"></script> 
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jqGrid-4.6.0/i18n/grid.locale-<?php echo $CONFIG['activeSettings']['systemLanguage'] ?>.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jqGrid-4.6.0/jquery.jqGrid.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/TouchSwipe-1.6/jquery.touchSwipe.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/readonly-1.0/readonly.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jQuery-File-Upload/js/jquery.iframe-transport.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jQuery-File-Upload/js/jquery.fileupload.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/number/jquery.number.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/contextMenu-2.5.0/jquery.contextMenu.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/spectrum-1.8.0/spectrum.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/gridster-0.5.6/jquery.gridster.min.js"></script>
-->

<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>ckeditor-4.7.1/ckeditor.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>ckeditor-4.7.1/adapters/jquery.js"></script>


<script language="javascript" type="text/javascript">
var objSystem = <?php echo json_encode($CONFIG['system']) ?>;
var objUser = {};
var objText = {};
var objComponents = {};
//var objElement = {};
////var filesUpload = {};
//var filesUpload = [];
//var objResultFiles = {};
//var numFiles = 0;

var mode = 'desktop';
var screenWidth;
var screenHeight;
var sessionLifetime = 36000 * 1000;
var sessionWarningTimeoutID;
var initLogin = '?initLogin=1';
var myDropzone = '';

var allowedTypesLogo = 'jpg,jpeg,png,gif';
var maxFilesizeLogo = '5M';
var minDimensionLogo = '500';
var componentFactor = 1;

<?php $newLogin = (isset($_GET['newLogin'])) ? $_GET['newLogin'] : 0 ?>
var newLogin = <?php echo $newLogin ?>;

<?php //echo $SCRIPT ?>
</script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsApp'] ?>js_sys-script.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsApp'] ?>js-helper.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsApp'] ?>js-history.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsApp'] ?>js-page.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsApp'] ?>js-edit.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsApp'] ?>js-form.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsApp'] ?>js-dialog.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsApp'] ?>js-slider.js"></script>

</body>
</html>