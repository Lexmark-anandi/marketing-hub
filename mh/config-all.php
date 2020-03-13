<?php
## Install Directory ##
$CONFIG['system']['directoryRoot'] = __DIR__ . '/';
$CONFIG['system']['directoryInstallation'] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $CONFIG['system']['directoryRoot']);

## Version ##
$CONFIG['system']['versionadmin'] = '5.0';
$CONFIG['system']['versionapp'] = '0';

## Countries default ##
$CONFIG['system']['countDefaultAdmin'] = 'all';
$CONFIG['system']['countDefaultApp'] = 'all';
$CONFIG['system']['countDefaultAdminId'] = 0;
$CONFIG['system']['countDefaultAppId'] = 0;

## Languages default ## 
$CONFIG['system']['langDefaultAdmin'] = 'all';
$CONFIG['system']['langDefaultApp'] = 'all';

## Path for pages ##
$CONFIG['system']['pathInclude'] = "../../";
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
$CONFIG['system']['pathTools'] = 'includes/';
$CONFIG['system']['pathCustom'] = 'custom/';

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
$CONFIG['system']['protocol'] = 'https';
$CONFIG['system']['cookie_secure'] = false;
$CONFIG['system']['pass_iteration'] = 8;
$CONFIG['system']['pass_portable'] = false;
$CONFIG['system']['useSystemConfiguration'] = 1; 
$CONFIG['system']['useChangeLanguage'] = 0; 
$CONFIG['system']['useChangeUser'] = 0; 
$CONFIG['system']['useChangeClient'] = 0; 
$CONFIG['system']['useMultiple'] = 1;
$CONFIG['system']['useSysMultiple'] = 1;
$CONFIG['system']['synchronizeModulFilter'] = 1;
$CONFIG['system']['synchronizeGridNumRow'] = 1;
$CONFIG['system']['showAllFunctions'] = 0;
$CONFIG['system']['widthDialogFormOrg'] = 900;
$CONFIG['system']['widthDialogForm'] = $CONFIG['system']['widthDialogFormOrg'];
$CONFIG['system']['widthDialogConfirmOrg'] = 600;
$CONFIG['system']['widthDialogConfirm'] = $CONFIG['system']['widthDialogConfirmOrg'];
$CONFIG['system']['mail_placeholders'] = array();
$CONFIG['system']['months2num'] = array("Jan" => '01', "Feb" => '02', "Mar" => '03', "Apr" => '04', "May" => '05', "Jun" => '06', "Jul" => '07', "Aug" => '08', "Sep" => '09', "Oct" => '10', "Nov" => '11', "Dec" => '12');
$CONFIG['system']['monthslong2num'] = array("January" => '01', "February" => '02', "March" => '03', "April" => '04', "May" => '05', "June" => '06', "July" => '07', "August" => '08', "September" => '09', "October" => '10', "November" => '11', "December" => '12');
$CONFIG['system']['delimiterPathAttr'] = '-';
$CONFIG['system']['sep_decimal'] = ',';
$CONFIG['system']['sep_thousand'] = '.';
$CONFIG['system']['currency'] = '';
$CONFIG['system']['date_format'] = 'DD.MM.YY';
$CONFIG['system']['date_code'] = 'd.m.y';
$CONFIG['system']['time_format'] = '24h';
$CONFIG['system']['time_code'] = 'H:i:s';


$CONFIG['favicon']['pathFavicon'] = $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathAdmin'] .'img/icons/';
$CONFIG['favicon']['timeFavicon'] = '?'.time();
$CONFIG['favicon']['favicon'] = '
<link rel="apple-touch-icon" sizes="57x57" href="' . $CONFIG['favicon']['pathFavicon'] . 'apple-icon-57x57.png' . $CONFIG['favicon']['timeFavicon'] . '">
<link rel="apple-touch-icon" sizes="60x60" href="' . $CONFIG['favicon']['pathFavicon'] . 'apple-icon-60x60.png' . $CONFIG['favicon']['timeFavicon'] . '">
<link rel="apple-touch-icon" sizes="72x72" href="' . $CONFIG['favicon']['pathFavicon'] . 'apple-icon-72x72.png' . $CONFIG['favicon']['timeFavicon'] . '">
<link rel="apple-touch-icon" sizes="76x76" href="' . $CONFIG['favicon']['pathFavicon'] . 'apple-icon-76x76.png' . $CONFIG['favicon']['timeFavicon'] . '">
<link rel="apple-touch-icon" sizes="114x114" href="' . $CONFIG['favicon']['pathFavicon'] . 'apple-icon-114x114.png' . $CONFIG['favicon']['timeFavicon'] . '">
<link rel="apple-touch-icon" sizes="120x120" href="' . $CONFIG['favicon']['pathFavicon'] . 'apple-icon-120x120.png' . $CONFIG['favicon']['timeFavicon'] . '">
<link rel="apple-touch-icon" sizes="144x144" href="' . $CONFIG['favicon']['pathFavicon'] . 'apple-icon-144x144.png' . $CONFIG['favicon']['timeFavicon'] . '">
<link rel="apple-touch-icon" sizes="152x152" href="' . $CONFIG['favicon']['pathFavicon'] . 'apple-icon-152x152.png' . $CONFIG['favicon']['timeFavicon'] . '">
<link rel="apple-touch-icon" sizes="180x180" href="' . $CONFIG['favicon']['pathFavicon'] . 'apple-icon-180x180.png' . $CONFIG['favicon']['timeFavicon'] . '">
<link rel="icon" type="image/png" sizes="192x192"  href="' . $CONFIG['favicon']['pathFavicon'] . 'android-icon-192x192.png' . $CONFIG['favicon']['timeFavicon'] . '">
<link rel="icon" type="image/png" sizes="32x32" href="' . $CONFIG['favicon']['pathFavicon'] . 'favicon-32x32.png' . $CONFIG['favicon']['timeFavicon'] . '">
<link rel="icon" type="image/png" sizes="96x96" href="' . $CONFIG['favicon']['pathFavicon'] . 'favicon-96x96.png' . $CONFIG['favicon']['timeFavicon'] . '">
<link rel="icon" type="image/png" sizes="16x16" href="' . $CONFIG['favicon']['pathFavicon'] . 'favicon-16x16.png' . $CONFIG['favicon']['timeFavicon'] . '">
<link rel="manifest" href="' . $CONFIG['favicon']['pathFavicon'] . 'manifest.json' . $CONFIG['favicon']['timeFavicon'] . '">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="' . $CONFIG['favicon']['pathFavicon'] . 'ms-icon-144x144.png' . $CONFIG['favicon']['timeFavicon'] . '">
<meta name="theme-color" content="#ffffff">
';


//$CONFIG['page']['modulParent'] = (isset($_GET['modulParent'])) ? $_GET['modulParent'] : '';
//$CONFIG['page']['pageIdParent'] = (isset($_GET['pageIdParent'])) ? $_GET['pageIdParent'] : 0;
//$CONFIG['page']['grids'] = array();


















$CONFIG['system']['aGridConfig'] = array('name', 'index', 'frozen', 'width', 'sortable', 'search', 'title', 'hidden', 'resizable', 'stype', 'align', 'editable', 'edittype', 'classes');
$CONFIG['system']['aGridConfigOpt'] = array('searchoptions', 'editoptions', 'editrules');





//$CONFIG['system']['aGridExc'] = array("id_col", "id_grid", "id_col_d", "id_grid_d", "g_colname", "g_rank", "g_active", "id_uid", "g_not_idpageparent", "tab_colname", "format", "tab_searchoptions");
//$CONFIG['system']['gridNumRows'] = array(10, 20, 50, 100, 9999999999);
$CONFIG['system']['aGridNumRows'] = array(10, 20, 50, 100);


$CONFIG['system']['allowedUploadSize'] = file_upload_max_size();
function file_upload_max_size() {
  static $max_size = -1;

  if ($max_size < 0) {
    // Start with post_max_size.
    $max_size = parse_size(ini_get('post_max_size'));

    // If upload_max_size is less, then reduce. Except if upload_max_size is
    // zero, which indicates no limit.
    $upload_max = parse_size(ini_get('upload_max_filesize'));
    if ($upload_max > 0 && $upload_max < $max_size) {
      $max_size = $upload_max;
    }
  }
  return $max_size;
}

function parse_size($size) {
  $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
  $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
  if ($unit) {
    // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
    return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
  }
  else {
    return round($size);
  }
}


?>