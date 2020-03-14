<?php
$CONFIG['system']['pathInclude'] = "../../";
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');
if(isset($_GET['p'])) $CONFIG['page']['pageId'] = $_GET['p'];

include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-navigation.php');
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-jsvars.php');

// minify js and css
//include_once($CONFIG['system']['pathInclude'] . 'includes/minify/src/Minify.php');
//include_once($CONFIG['system']['pathInclude'] . 'includes/minify/src/CSS.php');
//include_once($CONFIG['system']['pathInclude'] . 'includes/minify/src/JS.php');
//include_once($CONFIG['system']['pathInclude'] . 'includes/minify/src/Exception.php');
//include_once($CONFIG['system']['pathInclude'] . 'includes/path-converter/src/Converter.php');
//
//use MatthiasMullie\Minify;
//$minifier = new Minify\JS($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathJsAdmin'] . 'js_sys-script-navigation.js');
//$minifier->add($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathJsAdmin'] . 'js_sys-script-breadcrumb.js');
//// save minified file to disk
//$minifiedPath = '/path/to/minified/css/file.css';
//$minifier->minify($minifiedPath);
//// or just output the content
//echo $minifier->minify();

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
        <div id="header">
          <div id="apptitle"><?php echo $CONFIG['USER']['clients'][$CONFIG['USER']['activeClient']]['client'] ?> - <?php echo $TEXT['apptitle'] ?></div>
          <div id="menuebutton"></div>
        </div> 
        
        <div id="navigation"><?php echo $NAVIGATION ?></div>
        
        <div id="breadcrumb">
            <div id="breadcrumbInner"></div>
            <div id="breadcrumbbutton"></div>
        </div>
        
        <div id="content"></div>
    </div> 
    <div id="overlay"></div>
	<div id="dialog"></div>

<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] ?>includes/jquery/jquery-3.1.1.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] ?>includes/jquery/jquery-ui-1.12.1.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] ?>includes/jquery/jquery-ui-i18n.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] ?>includes/jquery/jqGrid-4.6.0/plugins/ui.multiselect.js"></script> 
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] ?>includes/jquery/jqGrid-4.6.0/plugins/locale/ui-multiselect-de.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] ?>includes/jquery/jqGrid-4.6.0/i18n/grid.locale-de.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] ?>includes/jquery/jqGrid-4.6.0/jquery.jqGrid.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] ?>includes/jquery/js-cookie-master-2.1.3/js.cookie.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] ?>includes/jquery/TouchSwipe-1.6/jquery.touchSwipe.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] ?>includes/jquery/loadmask-0.4/jquery.loadmask.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] ?>includes/jquery/readonly-1.0/readonly.js"></script>

<script language="javascript" type="text/javascript">
<?php echo $SCRIPT ?>
</script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-history.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-navigation.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-breadcrumb.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-grid.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-gridfilter.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-form.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-formfilter.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-formcheck.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-data.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathJsAdmin'] ?>js_sys-script-dialog.js"></script>
</body>
</html>