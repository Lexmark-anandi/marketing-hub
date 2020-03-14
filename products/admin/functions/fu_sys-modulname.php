<?php
function modulname($file){
	$nameModul = $file;
	$nameModul = str_replace("/","",$nameModul);
	$nameModul = str_replace("-","",$nameModul);
	$nameModul = str_replace("_","",$nameModul); 
	$nameModul = str_replace(".","",$nameModul);
	$nameModul = md5($nameModul);
	
	return $nameModul;
} 
?>