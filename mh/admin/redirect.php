<?php
include_once(__DIR__ . '/../config-all.php');

header('Location:' . $CONFIG['system']['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $CONFIG['system']['directoryInstallation'] . $CONFIG['system']['pathPagesAdmin'] . 'p_sys-default.php');
exit();
?>