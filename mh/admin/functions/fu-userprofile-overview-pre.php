<?php
$specialCondition = 'AND (' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid IN (' . implode(',', array_keys($CONFIG['user']['countries'])) . ') OR ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid IS NULL) '; 

?>