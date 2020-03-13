<?php
include_once(__DIR__ . '/../config-all.php');
include_once(__DIR__ . '/../custom/config-all-custom.php');

include_once($CONFIG['system']['directoryRoot'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-connect.php');

getConnection(0); 

$db = 'cc';
 

$query = $CONFIG['dbconn'][0]->prepare('SHOW TABLES FROM '.$db.'');
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	echo $row['Tables_in_'.$db.''].'<br>';
	
	$query2 = $CONFIG['dbconn'][0]->prepare('ALTER TABLE '.$row['Tables_in_'.$db.''].' ENGINE=InnoDB');
	//$query2->execute();
}

?>
