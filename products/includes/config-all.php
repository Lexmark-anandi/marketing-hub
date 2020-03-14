<?php
## Install Directory ##
$CONFIG['system']['directorySystem'] = "";

## Version ##
$CONFIG['system']['versionadmin'] = '4.0';
$CONFIG['system']['versionapp'] = '0';

## Countries default ##
$CONFIG['system']['countDefaultAdmin'] = 'all';
$CONFIG['system']['countDefaultApp'] = 'all';

## Languages default ## 
$CONFIG['system']['langDefaultAdmin'] = 'all';
$CONFIG['system']['langDefaultApp'] = 'all';

## Path for pages ##
$CONFIG['system']['pathAdmin'] = 'admin/';
$CONFIG['system']['pathApp'] = 'app/';
$CONFIG['system']['pathPagesAdmin'] = 'admin/pages/';
$CONFIG['system']['pathPagesApp'] = 'app/pages/';
$CONFIG['system']['pathFunctionsAdmin'] = 'admin/functions/';
$CONFIG['system']['pathFunctionsApp'] = 'app/functions/';
$CONFIG['system']['pathFormsAdmin'] = 'admin/forms/';
$CONFIG['system']['pathFormsApp'] = 'app/forms/';
$CONFIG['system']['pathJsAdmin'] = 'admin/js/';
$CONFIG['system']['pathJsApp'] = 'app/js/';
$CONFIG['system']['pathMedia'] = 'media/';
$CONFIG['system']['pathAssets'] = 'assets/';

## E-Mail PHPMailer ##
$CONFIG['mail'][0]['pop_server'] = '';
$CONFIG['mail'][0]['smtp_user'] = '';
$CONFIG['mail'][0]['smtp_password'] = '';
$CONFIG['mail'][0]['smtp_server'] = '';
$CONFIG['mail'][0]['smtp_auth'] = true;
$CONFIG['mail'][0]['smtp_port'] = '';
$CONFIG['mail'][0]['smtp_secure'] = '';
$CONFIG['mail'][0]['sender_name'] = '';
$CONFIG['mail'][0]['sender_name'] = '';

## DB ##
$CONFIG['db'][0]['utf8'] = true;
$CONFIG['db'][0]['prefix'] = '';
$CONFIG['db'][0]['host'] = '';
$CONFIG['db'][0]['user'] = '';
$CONFIG['db'][0]['password'] = '';
$CONFIG['db'][0]['database'] = '';

## System ##
$CONFIG['system']['protocol'] = 'http';
$CONFIG['system']['cookie_secure'] = false;
$CONFIG['system']['pass_iteration'] = 8;
$CONFIG['system']['pass_portable'] = false;
$CONFIG['system']['useChangeLanguage'] = 0; 
$CONFIG['system']['useChangeUser'] = 0; 
$CONFIG['system']['useChangeClient'] = 1; 
$CONFIG['system']['useMultiple'] = 1;
$CONFIG['system']['useSysMultiple'] = 1;
$CONFIG['system']['synchronizeGridFilter'] = 1;
$CONFIG['system']['showAllFunctions'] = 0;
$CONFIG['system']['widthDialogForm'] = 900;
$CONFIG['system']['widthDialogConfirm'] = 600;
$CONFIG['system']['mail_placeholders'] = array();
$CONFIG['system']['months2num'] = array("Jan" => '01', "Feb" => '02', "Mar" => '03', "Apr" => '04', "May" => '05', "Jun" => '06', "Jul" => '07', "Aug" => '08', "Sep" => '09', "Oct" => '10', "Nov" => '11', "Dec" => '12');



$CONFIG['system']['pathFavicon'] = '/admin/img/icons/';
$CONFIG['system']['timeFavicon'] = '?'.time();
$CONFIG['system']['favicon'] = '
<link rel="apple-touch-icon" sizes="57x57" href="' . $CONFIG['system']['pathFavicon'] . 'apple-icon-57x57.png' . $CONFIG['system']['timeFavicon'] . '">
<link rel="apple-touch-icon" sizes="60x60" href="' . $CONFIG['system']['pathFavicon'] . 'apple-icon-60x60.png' . $CONFIG['system']['timeFavicon'] . '">
<link rel="apple-touch-icon" sizes="72x72" href="' . $CONFIG['system']['pathFavicon'] . 'apple-icon-72x72.png' . $CONFIG['system']['timeFavicon'] . '">
<link rel="apple-touch-icon" sizes="76x76" href="' . $CONFIG['system']['pathFavicon'] . 'apple-icon-76x76.png' . $CONFIG['system']['timeFavicon'] . '">
<link rel="apple-touch-icon" sizes="114x114" href="' . $CONFIG['system']['pathFavicon'] . 'apple-icon-114x114.png' . $CONFIG['system']['timeFavicon'] . '">
<link rel="apple-touch-icon" sizes="120x120" href="' . $CONFIG['system']['pathFavicon'] . 'apple-icon-120x120.png' . $CONFIG['system']['timeFavicon'] . '">
<link rel="apple-touch-icon" sizes="144x144" href="' . $CONFIG['system']['pathFavicon'] . 'apple-icon-144x144.png' . $CONFIG['system']['timeFavicon'] . '">
<link rel="apple-touch-icon" sizes="152x152" href="' . $CONFIG['system']['pathFavicon'] . 'apple-icon-152x152.png' . $CONFIG['system']['timeFavicon'] . '">
<link rel="apple-touch-icon" sizes="180x180" href="' . $CONFIG['system']['pathFavicon'] . 'apple-icon-180x180.png' . $CONFIG['system']['timeFavicon'] . '">
<link rel="icon" type="image/png" sizes="192x192"  href="' . $CONFIG['system']['pathFavicon'] . 'android-icon-192x192.png' . $CONFIG['system']['timeFavicon'] . '">
<link rel="icon" type="image/png" sizes="32x32" href="' . $CONFIG['system']['pathFavicon'] . 'favicon-32x32.png' . $CONFIG['system']['timeFavicon'] . '">
<link rel="icon" type="image/png" sizes="96x96" href="' . $CONFIG['system']['pathFavicon'] . 'favicon-96x96.png' . $CONFIG['system']['timeFavicon'] . '">
<link rel="icon" type="image/png" sizes="16x16" href="' . $CONFIG['system']['pathFavicon'] . 'favicon-16x16.png' . $CONFIG['system']['timeFavicon'] . '">
<link rel="manifest" href="' . $CONFIG['system']['pathFavicon'] . 'manifest.json' . $CONFIG['system']['timeFavicon'] . '">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="' . $CONFIG['system']['pathFavicon'] . 'ms-icon-144x144.png' . $CONFIG['system']['timeFavicon'] . '">
<meta name="theme-color" content="#ffffff">
';


//$CONFIG['page']['modulParent'] = (isset($_GET['modulParent'])) ? $_GET['modulParent'] : '';
//$CONFIG['page']['pageIdParent'] = (isset($_GET['pageIdParent'])) ? $_GET['pageIdParent'] : 0;
//$CONFIG['page']['grids'] = array();























$CONFIG['system']['aGridExc'] = array("id_col", "id_grid", "id_col_d", "id_grid_d", "g_colname", "g_rank", "g_active", "id_uid", "g_not_idpageparent", "tab_colname", "format", "tab_searchoptions");
//$CONFIG['system']['gridNumRows'] = array(10, 20, 50, 100, 9999999999);
$CONFIG['system']['gridNumRows'] = array(10, 20, 50, 100);




?>