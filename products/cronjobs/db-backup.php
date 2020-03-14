<?php

$date = new DateTime();
$jetzt = $date->format('Y-m-d-H-i-s-');

$file = $jetzt . 'plg.sql';


system('mysqldump -u ' . $CONFIG['db'][0]['user'] . ' -p' . $CONFIG['db'][0]['password'] . ' ' . $CONFIG['db'][0]['database'] . ' > ' . $CONFIG['system']['pathInclude'] . 'backup/db/' . $file);

system('gzip ' . $CONFIG['system']['pathInclude'] . 'backup/db/' . $file);
//system('rm ' . $CONFIG['system']['pathInclude'] . 'backup/db/' . $file);


$aFiles = scandir($CONFIG['system']['pathInclude'] . 'backup/db/');
sort($aFiles); 
for($i = 0; $i < (count($aFiles) - 5); $i++) {
	$file = $aFiles[$i];
	if(is_file($CONFIG['system']['pathInclude'] . 'backup/db/' . $file)){
		unlink($CONFIG['system']['pathInclude'] . 'backup/db/' . $file);
	} 
};

?>