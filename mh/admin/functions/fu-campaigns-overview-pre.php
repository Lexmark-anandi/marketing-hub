<?php
//var_dump(array_keys($CONFIG['user']['countries']));
$specialCondition = 'AND ((' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_countid IN (' . implode(',', array_keys($CONFIG['user']['countries'])) . ') AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.del = (:nultime)) OR ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_countid IS NULL) ';

if($CONFIG['activeSettings']['id_countid'] != 0){
	$specialCondition .= 'AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_countid = ' . $CONFIG['activeSettings']['id_countid'] . ' ';
	$specialCondition .= 'AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.del = "0000-00-00 00:00:00" ';
}
if($CONFIG['activeSettings']['id_langid'] != 0){
	$specialCondition .= 'AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.id_langid = ' . $CONFIG['activeSettings']['id_langid'] . ' ';
	$specialCondition .= 'AND ' . $CONFIG['db'][0]['prefix'] . '_campaigns2countries_.del = "0000-00-00 00:00:00" ';
}
if($CONFIG['user']['right'] == 3){
	$specialCondition .= 'AND (' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.published_at <> (:nultime) OR ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.transrequest_at <> (:nultime) OR ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.create_from = ' . $CONFIG['user']['id'] . ') ';
} 
if($CONFIG['user']['right'] == 4){
	$specialCondition .= 'AND (' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.published_at <> (:nultime) OR ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.transrequest_at <> (:nultime) OR ' . $CONFIG['db'][0]['prefix'] . '_campaigns_uni.create_from = ' . $CONFIG['user']['id'] . ') ';
}
?>