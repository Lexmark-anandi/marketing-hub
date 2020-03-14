function showErrors(obj){
	clearErrors(obj);
	for(key in obj.result){
		$('.modul[data-modul="' + obj.modul + '"] .formLeft [name="' + obj.result[key].field + '"]').closest('.formRow').addClass('rowError');
		$('.modul[data-modul="' + obj.modul + '"] .formLeft [name="' + obj.result[key].field + '"]').parent().append('<div class="errorDiv">' + obj.result[key].message + '</div>');
	}
    
    $('.modul[data-modul="' + obj.modul + '"] .formLeft .errorMess').html('<span><span class="errorIcon"></span><span class="errorText">' + aText.errorText + '</span></span>');
    $('.modul[data-modul="' + obj.modul + '"] .formLeft .errorMess').addClass('messageError');
    $('.modul[data-modul="' + obj.modul + '"] .formLeft .errorMess').removeClass('messageOk');
	
	$('.modul[data-modul="' + obj.modul + '"] .formLeft .fieldset').each(function(){
		if($(this).find('.rowError').length > 0){
			var tabname = $(this).attr('data-formtab');
			$('.modul[data-modul="' + obj.modul + '"] .formLeft .formTabs li[data-formtab="' + tabname + '"]').addClass('errorIconTab');
		}
	});
}

function clearErrors(obj){
    $('.modul[data-modul="' + obj.modul + '"] .formLeft .errorIconTab').removeClass('errorIconTab');
    $('.modul[data-modul="' + obj.modul + '"] .formLeft .rowError').removeClass('rowError');
    $('.modul[data-modul="' + obj.modul + '"] .formLeft div.errorDiv').remove(); 
    $('.modul[data-modul="' + obj.modul + '"] .formLeft .errorMess').removeClass('messageError');
    $('.modul[data-modul="' + obj.modul + '"] .formLeft .errorMess').removeClass('messageOk');
    $('.modul[data-modul="' + obj.modul + '"] .formLeft .errorMess').html('&nbsp;');
}

function clearErrorField(field){
    $(field).closest('.formRow').removeClass('rowError');
    $(field).parent().find('div.errorDiv').remove(); 
}




function checkRequired(field){
	clearErrorField(field);
	if($(field).val() == ''){
		var mess = $(field).attr('data-checkmessage');
		var message = (mess != '' && aText[mess] != undefined) ? aText[mess] : aText.fieldRequired;
		
		$(field).closest('.formRow').addClass('rowError');
		$(field).parent().append('<div class="errorDiv">' + message + '</div>');
	}
}
 

function checkCheckArrayRequired(field){
}


//function checkSelectNotEqual0(field, message){
//	if(message == undefined) message = aText.fieldRequired;
//	var error = 0;
//    if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != undefined){
//		if(formData[defaultCountry][defaultLanguage][defaultDevice][field] == 0){
//			error = 1;
//			$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').parents('.formRow').addClass('rowError');
//			$('#' + aSpecsPage.idFormOuter + ' [name="' + field + '"]').parent().append('<div class="errorDiv">' + message + '</div>');
//		}
//	}
//    return error;
//}
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
//
//
//function checkEmailSyntax(field, message){
//	if(message == undefined) message = aText.fieldCheck;
//	var error = 0;
//    if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != undefined){
//		if(formData[defaultCountry][defaultLanguage][defaultDevice][field] != ''){
//			var str = formData[defaultCountry][defaultLanguage][defaultDevice][field];
//			var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
//    		if(!re.test(str)){
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
