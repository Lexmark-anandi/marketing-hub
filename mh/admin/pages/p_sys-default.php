<?php
if(isset($_GET['initLogin'])) $CONFIG['initLogin'] = true;
include_once(__DIR__ . '/../config-admin.php');

include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-navigation.php');
//include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-jsvars.php');

// minify js and css
//include_once($CONFIG['system']['directoryRoot'] . '../externaltools/minify/src/Minify.php');
//include_once($CONFIG['system']['directoryRoot'] . '../externaltools/minify/src/CSS.php');
//include_once($CONFIG['system']['directoryRoot'] . '../externaltools/minify/src/JS.php');
//include_once($CONFIG['system']['directoryRoot'] . '../externaltools/minify/src/Exception.php');
//include_once($CONFIG['system']['directoryRoot'] . '../externaltools/path-converter/src/Converter.php');
//
//use MatthiasMullie\Minify;
//$minifier = new Minify\JS($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathJsAdmin'] . 'js_sys-script-navigation.js');
//$minifier->add($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathJsAdmin'] . 'js_sys-script-breadcrumb.js');
//// save minified file to disk
//$minifiedPath = '/path/to/minified/css/file.css';
//$minifier->minify($minifiedPath);
//// or just output the content
//echo $minifier->minify();



$SCRIPT = '';
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
        <div id="header">
          <div id="apptitle"><?php echo $CONFIG['user']['clients'][$CONFIG['activeSettings']['id_clid']]['client'] ?> - <?php echo $TEXT['apptitle'] ?></div>
          <div id="menuebutton"></div>
        </div> 
        
        <div id="navigation"><?php echo $NAVIGATION ?></div>
        
        <div id="breadcrumb">
            <div id="breadcrumbInner"></div>
            <div id="breadcrumbbutton"><i class="fa fa-caret-down"></i></div>
        </div>
        
        <div id="content"></div>
    </div> 
    <div id="overlay"></div>
	<div id="sessionWarning"><?php echo $TEXT['sessionExpiredLogin'] ?></div>

<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jquery-3.1.1.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jquery-ui-1.12.1.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jquery-ui-i18n.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/timepicker-addon-1.5.3/jquery-ui-timepicker-addon.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/timepicker-addon-1.5.3/i18n/jquery-ui-timepicker-<?php echo $CONFIG['activeSettings']['systemLanguage'] ?>.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/localisation-1.1.0/jquery.localisation.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jqGrid-4.6.0/plugins/ui.multiselect.js"></script> 
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jqGrid-4.6.0/plugins/locale/ui-multiselect-<?php echo $CONFIG['activeSettings']['systemLanguage'] ?>.js"></script> 
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jqGrid-4.6.0/i18n/grid.locale-<?php echo $CONFIG['activeSettings']['systemLanguage'] ?>.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jqGrid-4.6.0/jquery.jqGrid.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/js-cookie-master-2.1.3/js.cookie.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/TouchSwipe-1.6/jquery.touchSwipe.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/loadmask-0.4/jquery.loadmask.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/readonly-1.0/readonly.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jQuery-File-Upload/js/jquery.iframe-transport.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/jQuery-File-Upload/js/jquery.fileupload.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/form-3.51.0/jquery.form.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/number/jquery.number.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/contextMenu-2.5.0/jquery.contextMenu.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/spectrum-1.8.0/spectrum.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/gridster-0.5.6/jquery.gridster.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/chartjs-2.7.1/Chart.bundle.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/chartjs-plugin-stacked100/src/index.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>jquery/chartjs-plugin-datalabels/src/chartjs-plugin-datalabels.min.js"></script>

<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>ckeditor-4.7.1/ckeditor.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathTools'] ?>ckeditor-4.7.1/adapters/jquery.js"></script>



<script language="javascript" type="text/javascript">
var objSystem = <?php echo json_encode($CONFIG['system']) ?>;
var objUser = {};
var objText = {};
var objElement = {};
var objClipboard = {};

//var filesUpload = {};
var filesUpload = [];
var objResultFiles = {};
var numFiles = 0;

var mode = 'desktop';
var screenWidth;
var screenHeight;
var sessionLifetime = 3600 * 1000;
var sessionWarningTimeoutID;
var initLogin = '?initLogin=1';

<?php echo $SCRIPT ?>
</script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-helper.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-navigation.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-history.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-page.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-modul.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-modulfilter.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-grid.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-form.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-formfilter.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-formcheck.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-data.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-dialog.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-assigned.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-breadcrumb.js"></script>
<!--
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-export.js"></script>-->
</body>
</html>