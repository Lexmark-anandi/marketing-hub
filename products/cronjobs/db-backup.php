<?php
echo "Product DB BACKUP Started\n";
$date = new DateTime();
$jetzt = $date->format('Y-m-d-H-i-s-');

$file = $jetzt . 'products.sql';


system('mysqldump -h ' . $CONFIG['db'][0]['host'] . ' -u ' . $CONFIG['db'][0]['user'] . ' -p' . $CONFIG['db'][0]['password'] . ' ' . $CONFIG['db'][0]['database'] . ' > ' . $CONFIG['system']['pathInclude'] . 'backup/db/' . $file);

system('gzip ' . $CONFIG['system']['pathInclude'] . 'backup/db/' . $file);
//system('rm ' . $CONFIG['system']['pathInclude'] . 'backup/db/' . $file);

$aFiles = array_diff(scandir($CONFIG['system']['pathInclude'] . 'backup/db/'), array('..', '.','.gitignore'));
sort($aFiles); 
for($i = 0; $i < (count($aFiles) - 5); $i++) {
	$file = $aFiles[$i];
	if(is_file($CONFIG['system']['pathInclude'] . 'backup/db/' . $file)){
		unlink($CONFIG['system']['pathInclude'] . 'backup/db/' . $file);
	} 
};
echo "Product DB BACKUP Ended\n";
?>