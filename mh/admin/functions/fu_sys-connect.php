<?php

function getConnection($n=0){
	global $CONFIG;
	
	if($CONFIG['db'][$n]['utf8'] == true){
		$CONFIG['dbconn'][$n] = new PDO(
									'mysql:host='.$CONFIG['db'][$n]['host'].';dbname='.$CONFIG['db'][$n]['database'], 
									$CONFIG['db'][$n]['user'], 
									$CONFIG['db'][$n]['password'],
									array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'")
									);
	}else{
		$CONFIG['dbconn'][$n] = new PDO(
									'mysql:host='.$CONFIG['db'][$n]['host'].';dbname='.$CONFIG['db'][$n]['database'], 
									$CONFIG['db'][$n]['user'], 
									$CONFIG['db'][$n]['password'] 
									);
	}
}



?>