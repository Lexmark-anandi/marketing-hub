<?php
// unescape characters if magicquotes is ON

function getPostData($data = NULL)
{
	if($data == NULL) $data = $_POST;
	$postData = array();
	
	foreach($data as $key=>$val)
	{
		if(is_array($val))	$postData[$key] = getPostData($val);
		else				$postData[$key] = magicquotes($val);
	}
	
	return $postData;
}

function magicquotes($var){
	if(get_magic_quotes_gpc()){
		$mgVAR = "";
		if(!is_array($var))
		{
			$mgVAR = stripslashes($var);
		}else{ 
			$varTmp = array();
			foreach($var as $val)
			{
				array_push($varTmp, stripslashes($val));
			}
			$mgVAR = $varTmp;
		}
		return $mgVAR;
	}else{
		$mgVAR = $var;
		return $mgVAR;
	}
}


function quote($value) {
	return !is_numeric($value) ? "'" . $value . "'" : $value;
}

function quoteDouble($value) {
	return !is_numeric($value) ? '"' . $value . '"' : $value;
}

?>