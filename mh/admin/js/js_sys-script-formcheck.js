function showErrors(obj, objResult){
	clearErrors(obj);
	for(key in objResult){
		$('#modul_' + obj.modulpath + ' .formLeft [name="' + objResult[key].field + '"]').closest('.formRow').addClass('rowError');
		$('#modul_' + obj.modulpath + ' .formLeft [name="' + objResult[key].field + '"]:last').closest('.formField').append('<div class="errorDiv">' + objResult[key].message + '</div>');
	}
    
    $('#modul_' + obj.modulpath + ' .formLeft .errorMess').html('<span><span class="errorIcon"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></span><span class="errorText">' + objText.errorText + '</span></span>');
    $('#modul_' + obj.modulpath + ' .formLeft .errorMess').addClass('messageError');
    $('#modul_' + obj.modulpath + ' .formLeft .errorMess').removeClass('messageOk');
	
	showErrorsTabs(obj);
}

function showErrorsTabs(obj){
	$('#modul_' + obj.modulpath + ' .formLeft .fieldset').each(function(){
		if($(this).find('.rowError').length > 0){
			var tabname = $(this).attr('data-formtab');
			$('#modul_' + obj.modulpath + ' .formLeft .formTabs li[data-formtab="' + tabname + '"]').addClass('errorIconTab');
			$('#modul_' + obj.modulpath + ' .formLeft .formTabs li[data-formtab="' + tabname + '"] span i').addClass('fa-exclamation-triangle');
		}
	});
}

function clearErrors(obj){
	$('#modul_' + obj.modulpath + ' .formLeft .formTabs li span i').removeClass('fa-exclamation-triangle');
    $('#modul_' + obj.modulpath + ' .formLeft .errorIconTab').removeClass('errorIconTab');
    $('#modul_' + obj.modulpath + ' .formLeft .rowError').removeClass('rowError');
    $('#modul_' + obj.modulpath + ' .formLeft div.errorDiv').remove(); 
    $('#modul_' + obj.modulpath + ' .formLeft .errorMess').removeClass('messageError');
    $('#modul_' + obj.modulpath + ' .formLeft .errorMess').removeClass('messageOk');
    $('#modul_' + obj.modulpath + ' .formLeft .errorMess').html('&nbsp;');
}

function clearErrorField(obj, field){
	$('#modul_' + obj.modulpath + ' .formLeft .formTabs li span i').removeClass('fa-exclamation-triangle');
    $('#modul_' + obj.modulpath + ' .formLeft .errorIconTab').removeClass('errorIconTab');

    $(field).closest('.formRow').removeClass('rowError');
    $(field).parents('.formField').find('div.errorDiv').remove(); 
}




function checkRequired(obj, field){
	clearErrorField(obj, field);
	if($(field).val() == ''){
		var mess = $(field).attr('data-checkmessage');
		var message = (mess != '' && objText[mess] != undefined) ? objText[mess] : objText.fieldRequired;
		
		$(field).closest('.formRow').addClass('rowError');
		$(field).parent().append('<div class="errorDiv">' + message + '</div>');
	}
	showErrorsTabs(obj);
}
 

function checkRadioRequired(obj, field){
	clearErrorField(obj, field);
	if($(field).parent().find(':checked').length == 0){
		var mess = $(field).attr('data-checkmessage');
		var message = (mess != '' && objText[mess] != undefined) ? objText[mess] : objText.fieldRequired;
		
		$(field).closest('.formRow').addClass('rowError');
		$(field).parents('.formField').append('<div class="errorDiv">' + message + '</div>');
	}
	showErrorsTabs(obj);
}
 

function checkArrayRequired(obj, field){
	clearErrorField(obj, field);
	if($(field).parents('.formField').find(':checked').length == 0){
		var mess = $(field).attr('data-checkmessage');
		var message = (mess != '' && objText[mess] != undefined) ? objText[mess] : objText.fieldRequired;
		
		$(field).closest('.formRow').addClass('rowError');
		$(field).parents('.formField').append('<div class="errorDiv">' + message + '</div>');
	}
	showErrorsTabs(obj);
}


function checkEmailSyntax(obj, field){
	clearErrorField(obj, field);
	if($(field).val() != undefined && $(field).val() != ''){
		var str = $(field).val();
		var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
		if(!re.test(str)){
			var mess = $(field).attr('data-checkmessage');
			var message = (mess != '' && objText[mess] != undefined) ? objText[mess] : objText.fieldCheckvalue;
		
			$(field).closest('.formRow').addClass('rowError');
			$(field).parents('.formField').append('<div class="errorDiv">' + message + '</div>');
		}
	}
	showErrorsTabs(obj);
}


function checkSelectNotEqual0(obj, field){
	clearErrorField(obj, field);
	if($(field).find('option:selected').val() == '0'){
		var mess = $(field).attr('data-checkmessage');
		var message = (mess != '' && objText[mess] != undefined) ? objText[mess] : objText.fieldRequired;
		
		$(field).closest('.formRow').addClass('rowError');
		$(field).parent().append('<div class="errorDiv">' + message + '</div>');
	}
	showErrorsTabs(obj);
}


//function checkSelectMultipleRequired(field, message){
//	if(message == undefined) message = objText.fieldRequired;
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
//	if(message == undefined) message = objText.fieldRequired;
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
//	if(message == undefined) message = objText.fieldCheckDate;
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
//	if(message == undefined) message = objText.fieldCheckDatetime;
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
//	if(message == undefined) message = objText.fieldCheckPhone;
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
//	if(message == undefined) message = objText.fieldCheckCurrency;
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
//	if(message == undefined) message = objText.fieldCheckZip;
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
//	if(message == undefined) message = objText.fieldCheckNumber;
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
//	if(message == undefined) message = objText.fieldRequired;
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

function formatNumber(objNum){
	var num = $.number(objNum, 0, ',', '.');
	return num
}


function parseFilesize(size) {
  var units = size.replace(/[^bkmgtpezy]/ig, '');
  units = units.toLowerCase();
  var size = size.replace(/[^0-9\.]/g, '');
  var strUnits = 'bkmgtpezy'
  
  if (units) {
    return Math.round(size * Math.pow(1024, strUnits.indexOf(units[0])));
  }
  else {
    return Math.round(size);
  }
}


function parseFilesizeR(size) {
	if(size < 1024){
		return size + ' B';
	}else if(size < Math.pow(1024, 2)){
		return Math.round(size / Math.pow(1024, 1)) + ' KB';
	}else if(size < Math.pow(1024, 3)){
		return Math.round(size / Math.pow(1024, 2)) + ' MB';
	}else if(size < Math.pow(1024, 4)){
		return Math.round(size / Math.pow(1024, 3)) + ' GB';
	}else if(size < Math.pow(1024, 5)){
		return Math.round(size / Math.pow(1024, 4)) + ' TB';
	}else if(size < Math.pow(1024, 6)){
		return Math.round(size / Math.pow(1024, 5)) + ' PB';
	}else{
		return size;
	}
}
