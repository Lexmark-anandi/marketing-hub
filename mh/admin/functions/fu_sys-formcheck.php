<?php
function checkRequired($field, $aData){
	global $CONFIG, $TEXT;
	
	$error = '';
	if(isset($aData[$field]) && $aData[$field] == ''){
		$error = $TEXT['fieldRequired'];
	}
    return $error;
}
 

function checkArrayRequired($field, $aData){
	global $CONFIG, $TEXT;
	
	$field = str_replace('[]', '', $field);
	$error = '';
	if(isset($aData[$field]) && count($aData[$field]) == ''){
		$error = $TEXT['fieldRequired'];
	}
    return $error;
}


function checkEmailSyntax($field, $aData){
	global $CONFIG, $TEXT;
	
	$error = '';
	if(isset($aData[$field]) && $aData[$field] != ''){
		if(!checkEmailAdress($aData[$field])){
			$error = $TEXT['fieldCheckvalue'];
		}
	}
    return $error;
}


function checkEmailAdress($email){
	$s = '/^[A-Z0-9._-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z.]{2,6}$/i';
	if(preg_match($s, $email)){
		return true;
	}
	return false;
}


function checkSelectNotEqual0($field, $aData){
	global $CONFIG, $TEXT;

	$error = '';
	if(isset($aData[$field]) && $aData[$field] == '0'){
		$error = $TEXT['fieldRequired'];
	}
    return $error;
}



 



//
//
//function checkSelectMultipleRequired(field, message){
//	if(message == undefined) message = aText.fieldRequired;
//	var error = 0;
//    if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != undefined){
//		if(formData[defaultCountry][defaultLanguage][defaultDevice][field] == ''){
//			error = 1;
//			$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '\[\]"]').parents('.formRow').addClass('rowError');
//			$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '\[\]"]').parent().append('<div class="errorDiv">' + message + '</div>');
//		}
//	}
//    return error;
//}
//
//
//
//function checkFilefieldRequired(field, message){
//	if(message == undefined) message = aText.fieldRequired;
//	var error = 0;
//    if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != undefined){
//		if($('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').closest('.formField').find('.fileUploadOuter').length == 0){
//			if($('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').closest('.formField').find('.fileUploadedOuter').length == 0){
//				error = 1;
//				$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').parents('.formRow').addClass('rowError');
//				$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').parent().append('<div class="errorDiv">' + message + '</div>');
//			}
//		} 
//	}
//    return error;
//}




//function checkDateSyntax(field, message){
//	if(message == undefined) message = aText.fieldCheckDate;
//	var error = 0;
//    if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != undefined){
//		if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != ''){
//			var str = formData[defaultCountry][defaultLanguage][defaultDevice][field];
//			var re = /^(\d{2}).(\d{2}).(\d{4})$/;
//    		if(!str.match(re)){
//				error = 1;
//				$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').parents('.formRow').addClass('rowError');
//				$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').parent().append('<div class="errorDiv">' + message + '</div>');
//			}
//		} 
//	}
//    return error;
//}
//
//
//function checkDatetimeSyntax(field, message){
//	if(message == undefined) message = aText.fieldCheckDatetime;
//	var error = 0;
//    if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != undefined){
//		if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != ''){
//			var str = formData[defaultCountry][defaultLanguage][defaultDevice][field];
//			var re = /^(\d{2}).(\d{2}).(\d{4}) (\d{2}):(\d{2}):(\d{2})$/;
//    		if(!str.match(re)){
//				error = 1;
//				$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').parents('.formRow').addClass('rowError');
//				$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').parent().append('<div class="errorDiv">' + message + '</div>');
//			}
//		} 
//	}
//    return error;
//}
//
//
//function checkPhoneSyntax(field, message){
//	if(message == undefined) message = aText.fieldCheckPhone;
//	var error = 0;
//    if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != undefined){
//		if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != ''){
//			var str = formData[defaultCountry][defaultLanguage][defaultDevice][field];
//			var re = /^([0-9\-\/\+ ])*$/i;
//    		if(!str.match(re)){
//				error = 1;
//				$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').parents('.formRow').addClass('rowError');
//				$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').parent().append('<div class="errorDiv">' + message + '</div>');
//			}
//		} 
//	}
//    return error;
//}
//
//
//function checkCurrencySyntax(field, message){
//	if(message == undefined) message = aText.fieldCheckCurrency;
//	var error = 0;
//    if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != undefined){
//		if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != ''){
//			var str = formData[defaultCountry][defaultLanguage][defaultDevice][field];
//			var re = /^([0-9,\.])*$/i;
//    		if(!str.match(re)){
//				error = 1;
//				$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').parents('.formRow').addClass('rowError');
//				$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').parent().append('<div class="errorDiv">' + message + '</div>');
//			}
//		} 
//	}
//    return error;
//}
//
//
//function checkZipSyntax(field, message){
//	if(message == undefined) message = aText.fieldCheckZip;
//	var error = 0;
//    if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != undefined){
//		if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != ''){
//			var str = formData[defaultCountry][defaultLanguage][defaultDevice][field];
//			var re = /^([0-9]){4,5}$/i;
//    		if(!str.match(re)){
//				error = 1;
//				$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').parents('.formRow').addClass('rowError');
//				$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').parent().append('<div class="errorDiv">' + message + '</div>');
//			}
//		} 
//	}
//    return error;
//}
//
//
//function checkNumberSyntax(field, message){
//	if(message == undefined) message = aText.fieldCheckNumber;
//	var error = 0;
//    if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != undefined){
//		if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != ''){
//			var str = formData[defaultCountry][defaultLanguage][defaultDevice][field];
//			var re = /^([0-9])*$/i;
//    		if(!str.match(re)){
//				error = 1;
//				$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').parents('.formRow').addClass('rowError');
//				$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').parent().append('<div class="errorDiv">' + message + '</div>');
//			}
//		} 
//	}
//    return error;
//}
//
//
//function checkFileAllowed(field, message){
//	if(message == undefined) message = '';
//	var error = 0;
//    if($('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').closest('.formField').find('.rowErrorFile').length > 0){
//		$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').closest('.formField').find('.rowErrorFile').addClass('rowError')
//		error = 1;
//	}
//    return error;
//}
//
//
//function setError(field, message){
//	if(message == undefined) message = aText.fieldRequired;
//	error = 1;
//	$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').parents('.formRow').addClass('rowError');
//	$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').parent().append('<div class="errorDiv">' + message + '</div>');
//    return error;
//}
//
//
//function formatDate2Input(field){
//    var fD = '';
//	if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != undefined){
//		if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != ''){
//			var str = formData[defaultCountry][defaultLanguage][defaultDevice][field];
//			var re = str.match(/^(\d{2}).(\d{2}).(\d{4})$/);
//			if(re) fD = re[3] + '-' + re[2] + '-' + re[1];
//		} 
//	}
//    return fD;
//}
//
//
//function formatDatetime2Input(field){
//    var fD = '';
//	if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != undefined){
//		if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != ''){
//			var str = formData[defaultCountry][defaultLanguage][defaultDevice][field];
//			var re = str.match(/^(\d{2}).(\d{2}).(\d{4}) (\d{2}):(\d{2}):(\d{2})$/);
//			if(re) fD = re[3] + '-' + re[2] + '-' + re[1] + ' ' +  re[4] + ':' + re[5] + ':' + re[6];
//		} 
//	}
//    return fD;
//}
//// ##### Form Check ##### 
//// ################################################

?>