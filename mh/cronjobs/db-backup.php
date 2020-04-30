<?php
echo "MH DB BACKUP Started\n";
$date = new DateTime();
$jetzt = $date->format('Y-m-d-H-i-s-');

$file = $jetzt . 'mh.sql';

system('mysqldump -h ' . $CONFIG['db'][0]['host'] . ' -u ' . $CONFIG['db'][0]['user'] . ' -p' . $CONFIG['db'][0]['password'] . ' ' . $CONFIG['db'][0]['database'] . ' > ../backup/db/' . $file);


system('gzip ' . $CONFIG['system']['directoryRoot'] . 'backup/db/' . $file);
//system('rm ' . $CONFIG['system']['directoryRoot'] . 'backup/db/' . $file);


$aFiles = array_diff(scandir($CONFIG['system']['pathInclude'] . 'backup/db/'), array('..', '.','.gitignore'));
sort($aFiles);
for($i = 0; $i < (count($aFiles) - 5); $i++) {
	$file = $aFiles[$i];
	if(is_file($CONFIG['system']['directoryRoot'] . 'backup/db/' . $file)){
		unlink($CONFIG['system']['directoryRoot'] . 'backup/db/' . $file);
	}
};
echo "MH DB BACKUP Ended\n";
?>