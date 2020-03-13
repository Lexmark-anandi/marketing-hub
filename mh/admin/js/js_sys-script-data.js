function loadData(obj){
	waiting('#modul_' + obj.modulpath);
	
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	if(objModul.urlRead == undefined) objModul.urlRead = 'fu-' + obj.modul_name + '-read.php';

	var data = '';
	
    $.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-default-read.php', 
		data: data,    
		type: 'post',          
		cache: false,  
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(obj),
			settings: JSON.stringify(objModul.activeSettings)
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);

			$('#modul_' + obj.modulpath + ' .formLeft input[name="formdata"]').val(result);
			var objFormData = JSON.parse(result);
			unwaiting('#modul_' + obj.modulpath);
			
			initFieldsVariation(obj);
			fillData(obj, objFormData);
			$('#modul_' + obj.modulpath + ' .formLeft input[name="id_data"]').val(obj.id_data);

			checkNavButton(obj);


////					window['f_' + aSpecsPage.idModul]['cbLoadFormStart'](id);
////
////					initFields();
////
////   					var selectCountryNew = $('#modul_' + obj.modulpath + ' .formLeft .tabFormFilter .filterMainCountry option:selected').val();
////   					var selectLanguageNew = $('#modul_' + obj.modulpath + ' .formLeft .tabFormFilter .filterMainLanguage option:selected').val();
////   					var selectDeviceNew = $('#modul_' + obj.modulpath + ' .formLeft .tabFormFilter .filterMainDevice option:selected').val();
////					$('#modul_' + obj.modulpath + ' .formLeft .tabFormFilter .formNavButtonWrite').css('display', 'inline');
////					$('#modul_' + obj.modulpath + ' .formLeft .tabFormFilter .formNavButtonRead').css('display', 'none');
////
////                    if(type == 'new'){
////                    	$('#modul_' + obj.modulpath + ' .formLeft .tabFormFilter .filterMainCountry').prop('disabled', true);
////                    	$('#modul_' + obj.modulpath + ' .formLeft .tabFormFilter .filterMainLanguage').prop('disabled', true);
////                    	$('#modul_' + obj.modulpath + ' .formLeft .tabFormFilter .filterMainDevice').prop('disabled', true);
////                    	$('#modul_' + obj.modulpath + ' .formLeft .tabFormFilter .formNavButtonWrite').css('display', 'none');
////                    	$('#modul_' + obj.modulpath + ' .formLeft .tabFormFilter .formNavButtonRead').css('display', 'inline');
////	   					selectCountryNew = 0;
////	   					selectLanguageNew = 0;
////    					selectDeviceNew = 0;
//////                   		$('#' + idDialog + ' .formNavDevice option[value=\"0\"]').prop('selected', true);
//////                    	switchMultiple(0, 1);
////					}
////                    switchMultiple(0, 1);
////
//////					setLanguages(idDialog, selectDeviceNew);
//////                    $('#' + idDialog + ' .formNavLanguage option[value=\"'+selectLanguageNew+'\"]').prop('selected', true);
////
////
////					if(type == 'read') setFormRead();

			$('#breadcrumbInner .breaddataset[data-modulpath="' + obj.modulpath + '"]').remove();
			if(obj.id_data != 0){
				$('#breadcrumbInner').append('<span class="breaddataset" data-modulpath="' + obj.modulpath + '">'+objFormData.identifier+'</span>');
				initBreadcrumbNavigation(obj);
			}else{
				$('#breadcrumbInner').append('<span class="breaddataset" data-modulpath="' + obj.modulpath + '">('+ objText.titleNewRow +')</span>');
				initBreadcrumbNavigation(obj);
			}
           			
////					if(idModulParent != ''){
////						$('#' + aSpecsPage.idGridTable + ' tr.selectedDataset').removeClass('selectedDataset');
////						$('#' + aSpecsPage.idGridTable + ' tr[id="'+id+'"]').addClass('selectedDataset');
////						$('#' + aSpecsPage.idGridTable).setSelection(id);
////
////						var tag = $('#modul_' + obj.modulpath + ' .formLeft [data-setparentfield="' + primeryfieldDataParent + '"]')[0].tagName;
////						
////						switch(tag){
////							case('INPUT'):
////								type = $('#modul_' + obj.modulpath + ' .formLeft input[data-setparentfield="' + primeryfieldDataParent + '"]').attr('type');
////
////								switch(type){
////									case('hidden'):
////										$('#modul_' + obj.modulpath + ' .formLeft input[data-setparentfield="' + primeryfieldDataParent + '"]').val(idDataParent);
////										
////										break;
////								}
////            	
////								break;
////					
////					
////							case('SELECT'):
////								$('#modul_' + obj.modulpath + ' .formLeft select[data-setparentfield="' + primeryfieldDataParent + '"]').prop('readonly', true);
////								$('#modul_' + obj.modulpath + ' .formLeft select[data-setparentfield="' + primeryfieldDataParent + '"] option[value="' + idDataParent + '"]').prop('selected', true);
////								var n = $('#modul_' + obj.modulpath + ' .formLeft select[data-setparentfield="' + primeryfieldDataParent + '"]').attr('name');
////								var v = $('#modul_' + obj.modulpath + ' .formLeft select[data-setparentfield="' + primeryfieldDataParent + '"] option[value="' + idDataParent + '"]').html();
////								$('#modul_' + obj.modulpath + ' .formLeft select[data-setparentfield="' + primeryfieldDataParent + '"]').addClass('textfieldRead');
////								$('#modul_' + obj.modulpath + ' .formLeft select[data-setparentfield="' + primeryfieldDataParent + '"]').parent().find('span[data-name="' + n + 'F"]').remove();
////								$('#modul_' + obj.modulpath + ' .formLeft select[data-setparentfield="' + primeryfieldDataParent + '"]').parent().append('<span data-name="' + n + 'F" class="textfield textfieldRead">' + v + '</span>');
////	
////								break;
////						} 
////					}
////						
////					//if(cb != undefined && typeof cb == 'function') cb();
////					window['f_' + aSpecsPage.idModul]['cbLoadFormComplete'](id);
//

			if(window['f_' + obj.modul_name].cbLoadDataSuccess && typeof(window['f_' + obj.modul_name].cbLoadDataSuccess) === 'function') window['f_' + obj.modul_name].cbLoadDataSuccess(obj);

//			if(window['f_' + obj.modul_name].cbLoadDataSuccess && typeof(window['f_' + obj.modul_name].cbLoadDataSuccess) === 'function'){
//				window['f_' + obj.modul_name].cbLoadDataSuccess(obj, this);
//			}else if(window.cbLoadDataSuccess && typeof(window.cbLoadDataSuccess) === 'function'){
//				window.cbLoadDataSuccess(obj, this);
//			}else if(cbLoadDataSuccess && typeof(cbLoadDataSuccess) === 'function'){
//				cbLoadDataSuccess(obj, this);
//			}
		}
	});
	
	
	$('#modul_' + obj.modulpath + ' .formRight .loaded').removeClass('reload');
	showChildmodul(obj, $('#modul_' + obj.modulpath + ' .formContent .formRight .formTabs ul li.active'));



}


function changeData(obj){
	obj.cb_readData = sendTemp;
	obj.cb_sendTemp = loadData;
	readData(obj);
}


function clearData(obj, type){
	if(type == undefined) type = clearData.caller.name;
	
	if(obj == undefined){
		var obj = {};
		var objModul = {};
		objModul.activeSettings = {};
	}else{
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	}
	
	var data = 'type=' + type;

    $.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-tempdata-clean.php', 
		data: data,    
		type: 'post',          
		cache: false,  
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(obj),
			settings: JSON.stringify(objModul.activeSettings)
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);

		}
	});
}


function fillData(obj, objFormData){
	waiting('#modul_' + obj.modulpath);
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	obj.target = '#modul_' + obj.modulpath + ' .formLeft';

   	var tagname = '';
	var type = '';
    var isArray = 0;
   	var isWysiwyg = 0;
    var classes = '';
    var keyField = '';
	
	// ## show upload fields ##
	$(obj.target + ' [data-fieldvariation]').addClass('formRowHidden');
	$(obj.target + ' .fileupload').each(function(){
		var sync = ($(this).attr('data-checksync') != undefined) ? $(this).attr('data-checksync') : '';
		var fieldname = $(this).attr('data-fieldname');
		switch(sync) {
			case 'all':
				var variation = 'x_x_x';
				break;
			case 'country':
				var variation = 'x_' + objModul.activeSettings.formLanguage + '_' + objModul.activeSettings.formDevice;
				break;
			case 'language':
				var variation = objModul.activeSettings.formCountry + '_x_' + objModul.activeSettings.formDevice;
				break;
			case 'device':
				var variation = objModul.activeSettings.formCountry + '_' + objModul.activeSettings.formLanguage + '_x';
				break;
			case 'countrylanguage':
				var variation = 'x_x_' + objModul.activeSettings.formDevice;
				break;
			case 'countrydevice':
				var variation = 'x_' + objModul.activeSettings.formLanguage + '_x';
				break;
			case 'languagedevice':
				var variation = objModul.activeSettings.formCountry + '_x_x';
				break;
			default:
				var variation = objModul.activeSettings.formCountry + '_' + objModul.activeSettings.formLanguage + '_' + objModul.activeSettings.formDevice;
		}
		
		$(obj.target + ' [data-fieldvariation="' + variation + '##' + fieldname + '"]').removeClass('formRowHidden');
	});

////	if(useMultipleCountry == 0) selectCountryNew = 0;
////	if(useMultipleLanguage == 0) selectLanguageNew = 0;
////	if(useMultipleDevice == 0) selectDeviceNew = 0;
////	if(useMultiple == 0) {
////		selectCountryNew = 0;
////		selectLanguageNew = 0;
////		selectDeviceNew = 0;
////	};
//
////    if(selectCountryNew == defaultCountry && selectLanguageNew == defaultLanguage && selectDeviceNew == defaultDevice){
//////        $('#' + idDialog + ' .onlyMaster').each(function(){
//////            var n = $(this).attr('name');
//////            n = n.replace('[','');
//////            n = n.replace(']','');
//////        	$(this).attr('disabled', false);
//////            $('#' + idDialog + ' tr.' + n).removeClass('deactive');
//////        });
////        
////        $('#modul_' + obj.modulpath + ' .formLeft .checkmaster').css('display', 'none');
////    }else{
//////        $('.onlyMaster').each(function(){
//////            var n = $(this).attr('name');
//////            n = n.replace('[','');
//////            n = n.replace(']','');
//////        	$(this).attr('disabled', true);
//////            $('#' + idDialog + ' tr.' + n).addClass('deactive');
//////        });
////        
////        $('#modul_' + obj.modulpath + ' .formLeft .checkmaster').css('display', 'inline');
////    }
////    if(selectDeviceNew == defaultDevice){
////        $('.noMasterCountry').each(function(){
////            var n = $(this).attr('name');
////        	$(this).attr('disabled', true);
////            $(this).parent('td').addClass('deactive');
////        });
////    }else{
////        $('.noMasterCountry').each(function(){
////            var n = $(this).attr('name');
////        	$(this).attr('disabled', false);
////            $(this).parent('td').removeClass('deactive');
////        });
////    }
////
////
////    
////	if(clearError == 1) clearErrors();
//

    for(key in objFormData){
    	tagname = '';
		type = '';
        isArray = 0;
    	isWysiwyg = 0;
        classes = '';
		keyField = key;
		
		
		// ## set key name to variation key for upload fields ##
		var keyVariation = $('#modul_' + obj.modulpath + ' .formLeft .formRow:not(.formRowHidden) input[data-fieldname="' + key + '"]').attr('name');
		if(keyVariation != undefined) key = keyVariation;

        
        if($('#modul_' + obj.modulpath + ' .formLeft [name="' + key + '"]')[0]){
        	tagname = $('#modul_' + obj.modulpath + ' .formLeft [name="' + key + '"]')[0].tagName;
        }
    	if($('#modul_' + obj.modulpath + ' .formLeft [name="' + key + '\[\]"]')[0]){
        	tagname = $('#modul_' + obj.modulpath + ' .formLeft [name="' + key + '\\[\\]"]')[0].tagName;
            isArray = 1;
        }
        if($('#modul_' + obj.modulpath + ' .formLeft [name="' + key + '"].isArray')[0]){
        	tagname = $('#modul_' + obj.modulpath + ' .formLeft [name="' + key + '"]')[0].tagName;
            isArray = 1;
        }
		if(tagname == ''){
			if($('#modul_' + obj.modulpath + ' .formLeft [data-name="' + key + '"]')[0]){
				tagname = $('#modul_' + obj.modulpath + ' .formLeft [data-name="' + key + '"]')[0].tagName;
			}
			if($('#modul_' + obj.modulpath + ' .formLeft [data-name="' + key + '\[\]"]')[0]){
				tagname = $('#modul_' + obj.modulpath + ' .formLeft [data-name="' + key + '\\[\\]"]')[0].tagName;
				isArray = 1;
			}
			if($('#modul_' + obj.modulpath + ' .formLeft [data-name="' + key + '"].isArray')[0]){
				tagname = $('#modul_' + obj.modulpath + ' .formLeft [data-name="' + key + '"]')[0].tagName;
				isArray = 1;
			}
		}

        switch(tagname){
        	case('INPUT'):
            	if(isArray == 0) type = $('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"]').attr('type');
            	if(isArray == 1) type = $('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '\[\]"]').attr('type');
                
                switch(type){
                	case('text'):
                        $('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"]').val(objFormData[keyField]);
                        break;

                	case('password'):
                        $('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"]').val(objFormData[keyField]);
                        break;

                	case('checkbox'):
                    	if(isArray == 0){
                        	if(objFormData[keyField] == $('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"]').val()){
                            	$('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"]').prop('checked', true);
                            }else{
                            	$('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"]').prop('checked', false);
                            }
                        }

                    	if(isArray == 1){
                         	$('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '\[\]"]').prop('checked', false);
                           	for(i=0;i < objFormData[keyField].length;i++){
                            	$('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '\[\]"][value="' + objFormData[keyField][i] + '"]').prop('checked', true);
                            }
                        }
                        break;

                	case('radio'):
                       	$('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"]').prop('checked', false);
                        $('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"][value="' + objFormData[keyField] + '"]').prop('checked', true);
						
						// ## set default value for boolean fields ##
						if($('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"]').hasClass('booleanfield')){
							$('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"][value="0"]').parent().find('.valuedefault').html('(' + objFormData[keyField + '_default'] + ')');
						}
                        break;
                
                	case('hidden'):
                        $('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"]').val(objFormData[keyField]);
                        break;

                	case('file'):
						$('#modul_' + obj.modulpath + ' .formLeft div[data-name="' + key + 'F"]').html(objFormData[keyField + 'F']);
						var wThumb = $('#modul_' + obj.modulpath + ' .formLeft div[data-name="' + key + 'F"] .fileUploadThumb img').outerWidth(true);
						var wFunc = 0;
						$('#modul_' + obj.modulpath + ' .formLeft div[data-name="' + key + 'F"] .fileUploadFunctions .modulIcon').each(function(){
							wFunc += $(this).outerWidth(true);

						});
						wThumb += 10;
						wFunc += 10;
						$('#modul_' + obj.modulpath + ' .formLeft div[data-name="' + key + 'F"] .fileUploadFilename').css('left', wThumb + 'px');
						$('#modul_' + obj.modulpath + ' .formLeft div[data-name="' + key + 'F"] .fileUploadFilename').css('right', wFunc + 'px');
                        break;
                
                }
                break;
        
        
        	case('TEXTAREA'):
				if($('#modul_' + obj.modulpath + ' .formLeft textarea[name="' + key + '"]').hasClass('wysiwyg')) isWysiwyg = 1;
                
                if(isWysiwyg == 0){
                    $('#modul_' + obj.modulpath + ' .formLeft textarea[name="' + key + '"]').val(objFormData[keyField]);
                }else{
                    CKEDITOR.instances[$('#modul_' + obj.modulpath + ' .formLeft textarea[name="' + key + '"]').attr('id')].setData(objFormData[keyField]);
                }
                break;
        
        
        	case('SELECT'):
                if(isArray == 0){
                    $('#modul_' + obj.modulpath + ' .formLeft select[name="' + key + '"] option').prop('selected', false);
                    $('#modul_' + obj.modulpath + ' .formLeft select[name="' + key + '"] option[value="' + objFormData[keyField] + '"]').prop('selected', true);
					
//					if($('#modul_' + obj.modulpath + ' .formLeft select[name="' + key + '"]').hasClass('autocomplete') == true){
//						var seltext = $('#modul_' + obj.modulpath + ' .formLeft select[name="' + key + '"] option:selected').text();
//						$('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + 'Tmp"]').val(seltext);
//					}
                }

                if(isArray == 1){
                    $('#modul_' + obj.modulpath + ' .formLeft select[name="' + key + '\[\]"] option').prop('selected', false);
                    for(i=0;i < objFormData[keyField].length;i++){
                        $('#modul_' + obj.modulpath + ' .formLeft select[name="' + key + '\[\]"] option[value="' + objFormData[keyField][i] + '"]').prop('selected', true);
                    }
                }
                break;
        	
//            case('DIV'):
//            	if(isArray == 0){
//                   $('#modul_' + obj.modulpath + ' .formLeft div[data-name="' + key + '"]').html(objFormData[keyField]);
//                }
//                
//                if(isArray == 1){
//                	var text = '';
//                    for(i=0; i < objFormData[keyField].length; i++){
//                        if(text != '') text += '<br/>';
//                        text += objFormData[keyField][i];
//                    }
//                    $('#modul_' + obj.modulpath + ' .formLeft div[data-name="' + key + '"]').html(text);
//                }
//                break;
//        	
//            case('SPAN'):
//            	if(isArray == 0){
//                   $('#modul_' + obj.modulpath + ' .formLeft span[data-name="' + key + '"]').html(objFormData[keyField]);
//                }
//                
//                if(isArray == 1){
//                	var text = '';
//                    for(i=0; i < objFormData[keyField].length; i++){
//                        if(text != '') text += '<br/>';
//                        text += objFormData[keyField][i];
//                    }
//                    $('#modul_' + obj.modulpath + ' .formLeft span[data-name="' + key + '"]').html(text);
//                }
//                break;
        }
    }

	unwaiting('#modul_' + obj.modulpath);

//	$('.modul[data-modul="' + obj.modul + '"] .form [data-maxlength]').each(function(){
//		checkMaxChars(this);
//	});
	
//	obj.objFormData = objFormData;

	var objCb = Object.assign({},obj);
	delete(objCb.cb_fillData);
	if(window['f_' + obj.modul_name][obj.cb_fillData] && typeof(window['f_' + obj.modul_name][obj.cb_fillData]) === 'function'){
		window['f_' + obj.modul_name][obj.cb_fillData](objCb, this);
	}else if(window[obj.cb_fillData] && typeof(window[obj.cb_fillData]) === 'function'){
		window[obj.cb_fillData](objCb, this);
	}else if(obj.cb_fillData && typeof(obj.cb_fillData) === 'function'){
		obj.cb_fillData(objCb, this);
	}


//	if(window['f_' + obj.modul] && window['f_' + obj.modul][obj.cb_fillData] && typeof(window['f_' + obj.modul][obj.cb_fillData]) === 'function'){
//		window['f_' + obj.modul][obj.cb_fillData](obj);
//	}else if(window[obj.cb_fillData] && typeof(window[obj.cb_fillData]) === 'function'){
//		window[obj.cb_fillData](obj);
//	}
}


function readData(obj){
	var formData = $('#modul_' + obj.modulpath + ' .formLeft [name="formdata"]').val();
	
	if(formData != ''){
		var objFormData = JSON.parse($('#modul_' + obj.modulpath + ' .formLeft [name="formdata"]').val());
		
		var tagname = '';
		var type = '';
		var disabled = 0;
		var isArray = 0;
		var isWysiwyg = 0;
		var classes = '';
		var keyField = '';

		for(key in objFormData){
			tagname = '';
			type = '';
			disabled = 0;
			isArray = 0;
			isWysiwyg = 0;
			classes = '';
			keyField = key;
			
			if($('#modul_' + obj.modulpath + ' .formLeft [name="' + key + '"]')[0]){
				tagname = $('#modul_' + obj.modulpath + ' .formLeft [name="' + key + '"]')[0].tagName;
			}
			if($('#modul_' + obj.modulpath + ' .formLeft [name="' + key + '\[\]"]')[0]){
				tagname = $('#modul_' + obj.modulpath + ' .formLeft [name="' + key + '\\[\\]"]')[0].tagName;
				isArray = 1;
			}
			if($('#modul_' + obj.modulpath + ' .formLeft [name="' + key + '"].isArray')[0]){
				tagname = $('#modul_' + obj.modulpath + ' .formLeft [name="' + key + '"]')[0].tagName;
				isArray = 1;
			}
			if(tagname == ''){
				if($('#modul_' + obj.modulpath + ' .formLeft [data-name="' + key + '"]')[0]){
					tagname = $('#modul_' + obj.modulpath + ' .formLeft [data-name="' + key + '"]')[0].tagName;
				}
				if($('#modul_' + obj.modulpath + ' .formLeft [data-name="' + key + '\[\]"]')[0]){
					tagname = $('#modul_' + obj.modulpath + ' .formLeft [data-name="' + key + '\\[\\]"]')[0].tagName;
					isArray = 1;
				}
				if($('#modul_' + obj.modulpath + ' .formLeft [data-name="' + key + '"].isArray')[0]){
					tagname = $('#modul_' + obj.modulpath + ' .formLeft [data-name="' + key + '"]')[0].tagName;
					isArray = 1;
				}
			}
			
			switch(tagname){
				case('INPUT'):
					if(isArray == 0) type = $('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"]').attr('type');
					if(isArray == 1) type = $('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '\[\]"]').attr('type');
					
					switch(type){
						case('text'):
							objFormData[keyField] = $('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"]').val();
							break;
					
						case('password'):
							objFormData[keyField] = $('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"]').val();
							break;
	
						case('checkbox'):
							if(isArray == 0){
								if($('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"]').prop('checked') == true){
									objFormData[keyField] = $('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"]').val();
								}else{
									objFormData[keyField] = '';
								}
							}
	
							if(isArray == 1){
								objFormData[keyField] = new Array();
								$('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '\[\]"]:checked').each(function(){
									disabled = ($(this).prop('disabled') == true) ? 1 : 0;
									if(disabled == 0) objFormData[keyField].push($(this).val())
								});
							}
							break;
	
						case('radio'):
							if($('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"]:checked').length == 0){
								objFormData[keyField] = '';
							}else{
								objFormData[keyField] = $('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"]:checked').val();
							}
							break;
	
						case('hidden'):
							objFormData[keyField] = $('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"]').val();
							break;
					
	
	//                	case('file'):
	//                    	$('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '"]').addClass('filefieldUnvisible');
	//                        $('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '_' + selectCountry + '_' + selectLanguage + '_' + selectDevice + '"]').parent().addClass('filefieldUnvisible');
	//                        $('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + '_' + selectCountryNew + '_' + selectLanguageNew + '_' + selectDeviceNew + '"]').parent().removeClass('filefieldUnvisible');
	//                   	
	//                        break;
				
					}
					break;
			
			
				case('TEXTAREA'):
					if($('#modul_' + obj.modulpath + ' .formLeft textarea[name="' + key + '"]').hasClass('wysiwyg')) isWysiwyg = 1;
					
					if(isWysiwyg == 0){
						objFormData[keyField] = $('#modul_' + obj.modulpath + ' .formLeft textarea[name="' + key + '"]').val();
					}else{
	                    objFormData[keyField] = CKEDITOR.instances[$('#modul_' + obj.modulpath + ' .formLeft textarea[name="' + key + '"]').attr('id')].getData();
	//                    CKEDITOR.instances[$('#modul_' + obj.modulpath + ' .formLeft textarea[name="' + key + '"]').attr('id')].setData(objFormData[keyField]);
					}
					break;
			
			
				case('SELECT'):
					if(isArray == 0){
						objFormData[keyField] = $('#modul_' + obj.modulpath + ' .formLeft select[name="' + key + '"] option:selected').val();
						
	//					if($('#modul_' + obj.modulpath + ' .formLeft select[name="' + key + '"]').hasClass('autocomplete') == true){
	//						var seltext = $('#modul_' + obj.modulpath + ' .formLeft select[name="' + key + '"] option:selected').text();
	//						$('#modul_' + obj.modulpath + ' .formLeft input[name="' + key + 'Tmp"]').val(seltext);
	//					}
					}
	
					if(isArray == 1){
						objFormData[keyField] = new Array();
						$('#modul_' + obj.modulpath + ' .formLeft select[name="' + key + '\[\]"] option:selected').each(function(){
							objFormData[keyField].push($(this).val())
						});
					}
					break;
			}
		}
		
		$('#modul_' + obj.modulpath + ' .formLeft [name="formdata"]').val(JSON.stringify(objFormData));
	
		if(obj.cb_readData && typeof(obj.cb_readData) === 'function') obj.cb_readData(obj);
	}
}


function sendTemp(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	clearErrors(obj);

	obj.id_data = $('#modul_' + obj.modulpath + ' .formLeft input[name="id_data"]').val();

	var objData = {};
	objData.formdata = JSON.parse($('#modul_' + obj.modulpath + ' .formLeft [name="formdata"]').val());
	
	var check = [];
	var checkfields = [];
	$('#modul_' + obj.modulpath + ' .formLeft [data-checkfunction]').each(function(){
		var checkObj = {};
		checkObj.field = $(this).attr('name');
		checkObj.function = $(this).attr('data-checkfunction');
		checkObj.message = $(this).attr('data-checkmessage');
		if(checkObj.function != '' && checkfields.indexOf(checkObj.field) == -1) check.push(checkObj);
		if(checkObj.function != '') checkfields.push(checkObj.field);
	});
	objData.check = check;
	
	var sync = {};
	$('#modul_' + obj.modulpath + ' .formLeft [data-checksync]').each(function(){
		var field = $(this).attr('name').replace('[]','');
		var synctype = $(this).attr('data-checksync');
		if(sync[synctype] == undefined) sync[synctype] = [];
		if(sync[synctype].indexOf(field) == -1) sync[synctype].push(field);
	});
	objData.sync = sync;
	

	var data = 'data=' + encodeURIComponent(JSON.stringify(objData))
	
    $.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-tempdata-save.php', 
		data: data,    
		type: 'post',          
		cache: false,  
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(obj),
			settings: JSON.stringify(objModul.activeSettings)
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);


			// ## sending data or show errors ##
			if(result == 'OK'){
				if($(objElement.field).hasClass('filterFormCountry'))	changeFormFilterCountry(obj);
				if($(objElement.field).hasClass('filterFormLanguage')) changeFormFilterLanguage(obj);
				if($(objElement.field).hasClass('filterFormDevice')) changeFormFilterDevice(obj);
					
				if(obj.cb_sendTemp && typeof(obj.cb_sendTemp) === 'function') obj.cb_sendTemp(obj);
			}else{
				if($(objElement.field).hasClass('filterFormCountry'))	$(objElement.field).find('option[value="' + objElement.oldValue + '"]').prop('selected', true);
				if($(objElement.field).hasClass('filterFormLanguage')) $(objElement.field).find('option[value="' + objElement.oldValue + '"]').prop('selected', true);
				if($(objElement.field).hasClass('filterFormDevice')) $(objElement.field).find('option[value="' + objElement.oldValue + '"]').prop('selected', true);

				var objResult = JSON.parse(result);
				showErrors(obj, objResult);
			}
		}
	});
}


function saveData(obj){
	waiting('#modul_' + obj.modulpath);

	obj.target = '#modul_' + obj.modulpath + ' .formLeft';
	
	if($(obj.target + ' .fileupload').length > 0 && filesUpload.length > 0){
		$(obj.target).append('<div class="uploadOverlay"></div>');
		$(obj.target + ' .fileUploadOuter').clone().appendTo(obj.target + ' .uploadOverlay');
		
		sendDataFiles(obj);
	}else{
		saveDataSubmit(obj);
	}
}


function saveDataSubmit(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	
	var urlSave = 'fu_sys-default-update.php';
	if(obj.id_data == 0) urlSave = 'fu_sys-default-insert.php';

//	delete obj.data;

var data = '';
//
    $.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + urlSave, 
		data: data,    
		type: 'post',          
		cache: false,  
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(obj),
			settings: JSON.stringify(objModul.activeSettings)
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);

			var res = JSON.parse(result); 
			obj.id_data = res.id_data;
			
			clearData(obj);
	
			$('#modul_' + obj.modulpath + ' .formLeft .errorMess').html('<span><span class="errorIcon"><i class="fa fa-check" aria-hidden="true"></i></span><span class="errorText">' + objText.saveText + '</span></span>');
			$('#modul_' + obj.modulpath + ' .formLeft .errorMess').removeClass('messageError');
			$('#modul_' + obj.modulpath + ' .formLeft .errorMess').addClass('messageOk');

			$('#' + objModul.gridTable).trigger('reloadGrid');
			var parentGrids = $('#' + objModul.gridTable).parents('.childmodul, .modul');
			while (parentGrids[0]){
				parentGrids.each(function(){
					if($.contains(document.body, this)){
						var idG = 'gridTable_' + $(this).attr('data-modulpath');
						$('#' + idG).trigger('reloadGrid');
					}
				});
				parentGrids = parentGrids.parents('.modul');
			}
			
			unwaiting();
			
			if(obj.action == 'close'){
				cancelForm(obj)
			}else{
				createTabsFormRight(obj);
				
				// ## splitForm to use different forms for insert and update (after first saving - set object in rowAdd) ##
				if(obj.splitForm != undefined && obj.splitForm == 1){
					delete(obj.splitForm);
					loadForm(obj);
				}else{
					loadData(obj);
				}
				
				initFormFilter(obj);
//				initFormNav(obj);
			}
			
			if(window['f_' + obj.modul_name].cbSaveDataSubmitSuccess && typeof(window['f_' + obj.modul_name].cbSaveDataSubmitSuccess) === 'function') window['f_' + obj.modul_name].cbSaveDataSubmitSuccess(obj);
			
		}
	});
}


function sendDataFiles(obj, target){
	if(target != undefined && target != '') obj.target = target;

	if($(obj.target + ' .rowErrorFile').length == 0){
		for(key in filesUpload){
			opt = {};
			opt.files = filesUpload[key];
			opt.paramName = filesUpload[key]['field'];
			opt.formData = {
				targetpath: filesUpload[key]['target'],

				orgfieldname: filesUpload[key]['orgfieldname'],
				multiple: filesUpload[key]['multiple'],
				forceupload: filesUpload[key]['forceupload'],
				cbSendFiles: obj.cbSendFiles
			};
			$(obj.target + ' .fileupload').fileupload('send', opt);
		}
	}else{
		unwaiting('body');
	}
}


function nextRow(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

	clearErrors(obj);

	var idNew = $('#' + objModul.gridTable + ' tr.selectedDataset').next('tr').attr('id');
	$('#' + objModul.gridTable + ' tr.selectedDataset').removeClass('selectedDataset'); 
	if(idNew == undefined){
		obj.cb_gridLoadComplete = 'nextRowPage';
		gridNextPage(obj);
	}
	
	if(idNew != undefined){
		$('#' + objModul.gridTable + ' tr[id="' + idNew + '"]').addClass('selectedDataset');
		$('#' + objModul.gridTable).setSelection(idNew);
		
		$('#breadcrumbInner .breaddataset[data-modulpath="' + obj.modulpath + '"]').remove();
		
		clearData(obj);
		
		obj.id_data = idNew;

		$('#form_' + obj.modulpath + ' .fileUploadOuter').remove();
		filesUpload = new Array();

		loadData(obj);
		
		
////				window['f_' + aSpecsPage.idModul]['cbLoadFormStart'](idNew);
////
////		        if(mode == 'read') setFormRead();
////
////				window['f_' + aSpecsPage.idModul]['cbLoadFormComplete'](idNew);
////
    }
}


function nextRowPage(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

	$('#' + objModul.gridTable + ' tr:first').addClass('selectedDataset');
	nextRow(obj);
}


function prevRow(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

	clearErrors(obj);

	if(obj.prevpage == 1){
		delete obj.prevpage;
		var idNew = $('#' + objModul.gridTable + ' tr:last').attr('id');
	}else{	
		var idNew = $('#' + objModul.gridTable + ' tr.selectedDataset').prev('tr').attr('id');
	}
	$('#' + objModul.gridTable + ' tr.selectedDataset').removeClass('selectedDataset'); 
	if(idNew == undefined){
		obj.cb_gridLoadComplete = 'prevRowPage';
		gridPrevPage(obj);
	}
	
	if(idNew != undefined){
		$('#' + objModul.gridTable + ' tr[id="' + idNew + '"]').addClass('selectedDataset');
		$('#' + objModul.gridTable).setSelection(idNew);
		
		$('#breadcrumbInner .breaddataset[data-modulpath="' + obj.modulpath + '"]').remove();
		
		clearData(obj);
		
		obj.id_data = idNew;

		$('#form_' + obj.modulpath + ' .fileUploadOuter').remove();
		filesUpload = new Array();

		loadData(obj);
	}
}


function prevRowPage(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

	obj.prevpage = 1;
	prevRow(obj);
}



function sendDelete(obj, el){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	var data = '';
	
    $.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-default-delete.php', 
		data: data,    
		type: 'post',          
		cache: false,  
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(obj),
			settings: JSON.stringify(objModul.activeSettings)
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);

			$('#' + objModul.gridTable).trigger('reloadGrid');
			var parentGrids = $('#' + objModul.gridTable).parents('.childmodul, .modul');
			while (parentGrids[0]){
				parentGrids.each(function(){
					if($.contains(document.body, this)){
						var idG = 'gridTable_' + $(this).attr('data-modulpath');
						$('#' + idG).trigger('reloadGrid');
					}
				});
				parentGrids = parentGrids.parents('.modul');
			}
////			if(cb != undefined) cb();
            closeDialog(obj, el); 
			clearData(obj);
        }    
    });  
}


function downloadMedia(filesys_filename, filename, folder, type){
	$('<form action="' + objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-download.php" method="post" class="formDownload"><input type="hidden" value="' + filesys_filename + '" name="filesys_filename" id="filesys_filename" ><input type="hidden" value="' + filename + '" name="filename" id="filename" ><input type="hidden" value="' + folder + '" name="folder" id="folder" ><input type="hidden" value="' + type + '" name="type" id="type" ></form>').appendTo('body').submit();

	window.setTimeout(function(){$('.formDownload').remove()},1000); 
}


function checkDeleteFile(target, el){
	alert(target)
//	var field = $(el).closest('.formField').children('input[type="file"]').attr('name');
//	
////	var data = 'id=' + id;
////	data += '&idModul=' + aSpecsPage.idModul;
////	data += '&type=deletefile';
////	data += '&url=' + aSpecsPage.urlUpdate;
////	data += '&fieldname=' + field;
//
//	obj.id = $(el).closest('.fileUploadedOuter').attr('data-id');;
//	obj.el = el;
//	obj.fieldname = $(el).closest('.formField').children('input[type="file"]').attr('name');
//	obj.title = aText.fileDelete;
//	obj.formtext = aText.checkFileDelete;
//	obj.identifier = $(el).closest('.fileUploadedOuter').find('.fileUploadFilename').text();
//	
//	aButtons = {};
//	aButtons[aText.Cancel] = function() { closeDialog(obj, this); }            
//	aButtons[aText.Delete] = function() { sendDeleteFile(obj, this); }
//	
//	openDialogAlert(obj, aButtons);
}


//
//function sendDeleteFile(obj, el){
//	if(obj == undefined) var obj = {};
//	if(obj.urlDeleteMedia  == undefined) obj.urlDeleteMedia = '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu_sys-media-delete.php';
//
//    $.ajax({  
//        url: obj.urlDeleteMedia,    
//        type: 'post',          
//        data: 'data=' + JSON.stringify(obj),       
//        cache: false,  
//		headers: {
//			csrfToken: Cookies.get('csrf'), 
//			page: JSON.stringify(aPage)
//		},
		//success: function (result, status, jqXHR) {
//			actualizeStatus(result, status);
//
//			var resultData = JSON.parse(result);
//        	$('.fileUploadedOuter[data-id="' + resultData.id + '"]').remove();
//			
//
////			var str = '<div class="fileUploadedOuter" data-id="' + resultData.id + '"><div class="fileUploadDelete"><span class="ui-icon ui-icon-trash" title="' + aText.fileDelete + '" onclick="checkDeleteFile('+id+', this)"></span></div><div class="fileUploadFilename"><a href="' + pathInclude + pathMedia + resultData.filesys_filename + '" target="_blank">'+resultData.filename+'</a></div></div>';
////			objFormData[selectCountry][selectLanguage][selectDevice][field + 'F'] = objFormData[selectCountry][selectLanguage][selectDevice][field + 'F'].replace(str,'');
//			
////			var aArgs = new Array(resultData.id, resultData.fieldname)
//
//			var aArgs = new Array(obj)
//			window['f_' + obj.modul]['fileDelete'].apply(window, aArgs);
//			
//            closeDialog(obj, el); 
//
//			if($(obj.target + ' #' + resultData.fieldname + '[multiple]').length > 0){
//				var formdata = $(obj.target + ' [name="formdata"]').val();
//				var objFormData = (formdata == '') ? {} : JSON.parse(formdata);
//				if(objFormData[resultData.fieldname] == undefined) objFormData[resultData.fieldname] = [];
//				var index = objFormData[resultData.fieldname].indexOf(resultData.id);
//				if(index > -1) objFormData[resultData.fieldname].splice(index, 1);
//				$(obj.target + ' .field_formdata').val(JSON.stringify(objFormData));
//			}else{
//				var formdata = $(obj.target + ' [name="formdata"]').val();
//				var objFormData = (formdata == '') ? {} : JSON.parse(formdata);
//				if(objFormData[resultData.fieldname] == undefined) objFormData[resultData.fieldname] = '';
//				objFormData[resultData.fieldname] = '';
//				$(obj.target + ' .field_formdata').val(JSON.stringify(objFormData));

//			}
//        }    
//    });  
//}
//
//
//
//
//function selectAll(type, el){
//	if(type == 0){
//		$(el).closest('.formRow').find('input[type="checkbox"]').prop('checked', false);
//	}
//	if(type == 1){
//		$(el).closest('.formRow').find('input[type="checkbox"]').prop('checked', true);
//	}
//}
//
//function checkMaxChars(el){
//	var maxChars = $(el).attr('data-maxlength');
//	var actContent = $(el).val();
//	var actChars = actContent.length;
//	var availChars = maxChars - actChars;
//	
//	if(availChars < 0){
//		availChars = 0;
//		$(el).val(actContent.substr(0, maxChars));
//	}
//	
//	$(el).closest('.formField').find('.available').html(availChars);
//	
//}
//
