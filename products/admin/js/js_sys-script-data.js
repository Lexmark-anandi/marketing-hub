function loadData(obj){
	if(obj == undefined) var obj = {};
	if(obj.urlRead  == undefined) obj.urlRead = '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu-' + aPage.moduls[obj.modul].modulname + '-read.php';
	obj.formCountry = $('.modul[data-modul="' + obj.modul + '"] .filterFormCountry option:selected').val();
	obj.formLanguage = $('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage option:selected').val();
	obj.formDevice = $('.modul[data-modul="' + obj.modul + '"] .filterFormDevice option:selected').val();
	if(obj.formCountry == undefined) obj.formCountry = 0;
	if(obj.formLanguage == undefined) obj.formLanguage = 0;
	if(obj.formDevice == undefined) obj.formDevice = 0;

	
	$.ajax({
		url: obj.urlRead,      
		type: 'post',          
		data: 'data=' + JSON.stringify(obj),       
		cache: false,  
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(aPage)
		},
		success: function (result) {
			$('.modul[data-modul="' + obj.modul + '"] .formLeft [name="formdata"]').val(result);
			var formData = JSON.parse(result);
			
			checkNavButton(obj);
			fillData(formData, obj);
			


			unwaiting();



//					window['f_' + aSpecsPage.idModul]['cbLoadFormStart'](id);
//
//					initFields();
//
//   					var selectCountryNew = $('.modul[data-modul="' + obj.modul + '"] .formLeft .tabFormFilter .filterMainCountry option:selected').val();
//   					var selectLanguageNew = $('.modul[data-modul="' + obj.modul + '"] .formLeft .tabFormFilter .filterMainLanguage option:selected').val();
//   					var selectDeviceNew = $('.modul[data-modul="' + obj.modul + '"] .formLeft .tabFormFilter .filterMainDevice option:selected').val();
//					$('.modul[data-modul="' + obj.modul + '"] .formLeft .tabFormFilter .formNavButtonWrite').css('display', 'inline');
//					$('.modul[data-modul="' + obj.modul + '"] .formLeft .tabFormFilter .formNavButtonRead').css('display', 'none');
//
//                    if(type == 'new'){
//                    	$('.modul[data-modul="' + obj.modul + '"] .formLeft .tabFormFilter .filterMainCountry').prop('disabled', true);
//                    	$('.modul[data-modul="' + obj.modul + '"] .formLeft .tabFormFilter .filterMainLanguage').prop('disabled', true);
//                    	$('.modul[data-modul="' + obj.modul + '"] .formLeft .tabFormFilter .filterMainDevice').prop('disabled', true);
//                    	$('.modul[data-modul="' + obj.modul + '"] .formLeft .tabFormFilter .formNavButtonWrite').css('display', 'none');
//                    	$('.modul[data-modul="' + obj.modul + '"] .formLeft .tabFormFilter .formNavButtonRead').css('display', 'inline');
//	   					selectCountryNew = 0;
//	   					selectLanguageNew = 0;
//    					selectDeviceNew = 0;
////                   		$('#' + idDialog + ' .formNavDevice option[value=\"0\"]').prop('selected', true);
////                    	switchMultiple(0, 1);
//                    }else{
//						checkNavButton();
//					}
//                    switchMultiple(0, 1);
//
////					setLanguages(idDialog, selectDeviceNew);
////                    $('#' + idDialog + ' .formNavLanguage option[value=\"'+selectLanguageNew+'\"]').prop('selected', true);
//
//
//					if(type == 'read') setFormRead();
//
//					if(type != 'new'){
//						$('#breadcrumb', top.document).append('<span class="breaddataset">'+formData[aTextScript.fDeleteField]+'</span>');
//						window.top.navBreadcrumbInit();
//					}else{
//						$('#breadcrumb', top.document).append('<span class="breaddataset">('+ aText.gridTitleNewRowBar +')</span>');
//						window.top.navBreadcrumbInit();
//					}
//           			
//					if(idModulParent != ''){
//						$('#' + aSpecsPage.idGridTable + ' tr.selectedDataset').removeClass('selectedDataset');
//						$('#' + aSpecsPage.idGridTable + ' tr[id="'+id+'"]').addClass('selectedDataset');
//						$('#' + aSpecsPage.idGridTable).setSelection(id);
//						checkNavButton();
//
//						var tag = $('.modul[data-modul="' + obj.modul + '"] .formLeft [data-setparentfield="' + primeryfieldDataParent + '"]')[0].tagName;
//						
//						switch(tag){
//							case('INPUT'):
//								type = $('.modul[data-modul="' + obj.modul + '"] .formLeft input[data-setparentfield="' + primeryfieldDataParent + '"]').attr('type');
//
//								switch(type){
//									case('hidden'):
//										$('.modul[data-modul="' + obj.modul + '"] .formLeft input[data-setparentfield="' + primeryfieldDataParent + '"]').val(idDataParent);
//										
//										break;
//								}
//            	
//								break;
//					
//					
//							case('SELECT'):
//								$('.modul[data-modul="' + obj.modul + '"] .formLeft select[data-setparentfield="' + primeryfieldDataParent + '"]').prop('readonly', true);
//								$('.modul[data-modul="' + obj.modul + '"] .formLeft select[data-setparentfield="' + primeryfieldDataParent + '"] option[value="' + idDataParent + '"]').prop('selected', true);
//								var n = $('.modul[data-modul="' + obj.modul + '"] .formLeft select[data-setparentfield="' + primeryfieldDataParent + '"]').attr('name');
//								var v = $('.modul[data-modul="' + obj.modul + '"] .formLeft select[data-setparentfield="' + primeryfieldDataParent + '"] option[value="' + idDataParent + '"]').html();
//								$('.modul[data-modul="' + obj.modul + '"] .formLeft select[data-setparentfield="' + primeryfieldDataParent + '"]').addClass('textfieldRead');
//								$('.modul[data-modul="' + obj.modul + '"] .formLeft select[data-setparentfield="' + primeryfieldDataParent + '"]').parent().find('span[data-name="' + n + 'T"]').remove();
//								$('.modul[data-modul="' + obj.modul + '"] .formLeft select[data-setparentfield="' + primeryfieldDataParent + '"]').parent().append('<span data-name="' + n + 'T" class="textfield textfieldRead">' + v + '</span>');
//	
//								break;
//						} 
//					}
//						
//					//if(cb != undefined && typeof cb == 'function') cb();
//					window['f_' + aSpecsPage.idModul]['cbLoadFormComplete'](id);
		}
	});
}


function changeData(obj){
	obj.cb_readData = sendTemp;
	obj.cb_sendTemp = loadData;
	readData(obj);
}


function clearData(obj){
	if(obj == undefined) var obj = {};
	if(obj.type  == undefined) obj.type = clearData.caller.name;

    $.ajax({  
		url: '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu_sys-tempdata-clean.php',    
		data: 'data=' + JSON.stringify(obj),       
		type: 'post',          
		cache: false,  
		async: false,
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(aPage)
		},
		success: function (form) {
		}
	});
}


function fillData(formData, obj){
	// ## read values from fields (for sending data) ##
	var tagname = '';
	var type = '';
	var isArray = 0;
	var isWysiwyg = 0;
	var classes = '';

//	if(useMultipleCountry == 0) selectCountryNew = 0;
//	if(useMultipleLanguage == 0) selectLanguageNew = 0;
//	if(useMultipleDevice == 0) selectDeviceNew = 0;
//	if(useMultiple == 0) {
//		selectCountryNew = 0;
//		selectLanguageNew = 0;
//		selectDeviceNew = 0;
//	};

//    if(selectCountryNew == defaultCountry && selectLanguageNew == defaultLanguage && selectDeviceNew == defaultDevice){
////        $('#' + idDialog + ' .onlyMaster').each(function(){
////            var n = $(this).attr('name');
////            n = n.replace('[','');
////            n = n.replace(']','');
////        	$(this).attr('disabled', false);
////            $('#' + idDialog + ' tr.' + n).removeClass('deactive');
////        });
//        
//        $('.modul[data-modul="' + obj.modul + '"] .formLeft .checkmaster').css('display', 'none');
//    }else{
////        $('.onlyMaster').each(function(){
////            var n = $(this).attr('name');
////            n = n.replace('[','');
////            n = n.replace(']','');
////        	$(this).attr('disabled', true);
////            $('#' + idDialog + ' tr.' + n).addClass('deactive');
////        });
//        
//        $('.modul[data-modul="' + obj.modul + '"] .formLeft .checkmaster').css('display', 'inline');
//    }
//    if(selectDeviceNew == defaultDevice){
//        $('.noMasterCountry').each(function(){
//            var n = $(this).attr('name');
//        	$(this).attr('disabled', true);
//            $(this).parent('td').addClass('deactive');
//        });
//    }else{
//        $('.noMasterCountry').each(function(){
//            var n = $(this).attr('name');
//        	$(this).attr('disabled', false);
//            $(this).parent('td').removeClass('deactive');
//        });
//    }
//
//
//    
//	if(clearError == 1) clearErrors();








    

	

    





    for(key in formData){
    	tagname = '';
		type = '';
        isArray = 0;
    	isWysiwyg = 0;
        classes = '';
        
        if($('.modul[data-modul="' + obj.modul + '"] .formLeft [name="' + key + '"]')[0]){
        	tagname = $('.modul[data-modul="' + obj.modul + '"] .formLeft [name="' + key + '"]')[0].tagName;
        }
    	if($('.modul[data-modul="' + obj.modul + '"] .formLeft [name="' + key + '\[\]"]')[0]){
        	tagname = $('.modul[data-modul="' + obj.modul + '"] .formLeft [name="' + key + '\\[\\]"]')[0].tagName;
            isArray = 1;
        }
        if($('.modul[data-modul="' + obj.modul + '"] .formLeft [name="' + key + '"].isArray')[0]){
        	tagname = $('.modul[data-modul="' + obj.modul + '"] .formLeft [name="' + key + '"]')[0].tagName;
            isArray = 1;
        }
		if(tagname == ''){
			if($('.modul[data-modul="' + obj.modul + '"] .formLeft [data-name="' + key + '"]')[0]){
				tagname = $('.modul[data-modul="' + obj.modul + '"] .formLeft [data-name="' + key + '"]')[0].tagName;
			}
			if($('.modul[data-modul="' + obj.modul + '"] .formLeft [data-name="' + key + '\[\]"]')[0]){
				tagname = $('.modul[data-modul="' + obj.modul + '"] .formLeft [data-name="' + key + '\\[\\]"]')[0].tagName;
				isArray = 1;
			}
			if($('.modul[data-modul="' + obj.modul + '"] .formLeft [data-name="' + key + '"].isArray')[0]){
				tagname = $('.modul[data-modul="' + obj.modul + '"] .formLeft [data-name="' + key + '"]')[0].tagName;
				isArray = 1;
			}
		}
		
        switch(tagname){
        	case('INPUT'):
            	if(isArray == 0) type = $('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]').attr('type');
            	if(isArray == 1) type = $('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '\[\]"]').attr('type');
                
                switch(type){
                	case('text'):
//                    	if($('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]').hasClass('onlyMaster') == true) setOnlyMaster(key);
                        $('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]').val(formData[key]);
                        break;
                

                	case('checkbox'):
                    	if(isArray == 0){
//                   			if($('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]').hasClass('onlyMaster') == true) setOnlyMaster(key);
                        	if(formData[key] == $('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]').val()){
                            	$('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]').prop('checked', true);
                            }else{
                            	$('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]').prop('checked', false);
                            }
                        }

                    	if(isArray == 1){
                         	$('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '\[\]"]').prop('checked', false);
                           	for(i=0;i < formData[key].length;i++){
                            	$('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '\[\]"][value="' + formData[key][i] + '"]').prop('checked', true);
                            }
                        }
                        break;


                	case('radio'):
//                     	if($('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]').hasClass('onlyMaster') == true) setOnlyMaster(key);
                       	$('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]').prop('checked', false);
                        $('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"][value="' + formData[key] + '"]').prop('checked', true);
                        
//						setRadioCheck(key, 0)
                        break;
                

                	case('hidden'):
                        $('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]').val(formData[key]);
                        break;
                

//                	case('file'):
//                    	$('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]').addClass('filefieldUnvisible');
//                        $('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '_' + selectCountry + '_' + selectLanguage + '_' + selectDevice + '"]').parent().addClass('filefieldUnvisible');
//                        $('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '_' + selectCountryNew + '_' + selectLanguageNew + '_' + selectDeviceNew + '"]').parent().removeClass('filefieldUnvisible');
//                   	
//                        break;
                
                }
                break;
        
        
        	case('TEXTAREA'):
				if($('.modul[data-modul="' + obj.modul + '"] .formLeft textarea[name="' + key + '"]').hasClass('wysiwyg')) isWysiwyg = 1;
                
                if(isWysiwyg == 0){
                    $('.modul[data-modul="' + obj.modul + '"] .formLeft textarea[name="' + key + '"]').val(formData[key]);
                }else{
//                    CKEDITOR.instances[$('.modul[data-modul="' + obj.modul + '"] .formLeft textarea[name="' + key + '"]').attr('id')].setData(formData[key]);
                }
                break;
        
        
        	case('SELECT'):
                if(isArray == 0){
//                    if($('.modul[data-modul="' + obj.modul + '"] .formLeft select[name="' + key + '"]').hasClass('onlyMaster') == true) setOnlyMaster(key);
                    $('.modul[data-modul="' + obj.modul + '"] .formLeft select[name="' + key + '"] option').prop('selected', false);
                    $('.modul[data-modul="' + obj.modul + '"] .formLeft select[name="' + key + '"] option[value="' + formData[key] + '"]').prop('selected', true);
					
//					if($('.modul[data-modul="' + obj.modul + '"] .formLeft select[name="' + key + '"]').hasClass('autocomplete') == true){
//						var seltext = $('.modul[data-modul="' + obj.modul + '"] .formLeft select[name="' + key + '"] option:selected').text();
//						$('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + 'Tmp"]').val(seltext);
//					}
                }

                if(isArray == 1){
                    $('.modul[data-modul="' + obj.modul + '"] .formLeft select[name="' + key + '\[\]"] option').prop('selected', false);
                    for(i=0;i < formData[key].length;i++){
                        $('.modul[data-modul="' + obj.modul + '"] .formLeft select[name="' + key + '\[\]"] option[value="' + formData[key][i] + '"]').prop('selected', true);
                    }
                }
                break;
        	
//            case('DIV'):
//            	if(isArray == 0){
//                   $('.modul[data-modul="' + obj.modul + '"] .formLeft div[data-name="' + key + '"]').html(formData[key]);
//                }
//                
//                if(isArray == 1){
//                	var text = '';
//                    for(i=0; i < formData[key].length; i++){
//                        if(text != '') text += '<br/>';
//                        text += formData[key][i];
//                    }
//                    $('.modul[data-modul="' + obj.modul + '"] .formLeft div[data-name="' + key + '"]').html(text);
//                }
//                
//                break;
//        	
//            case('SPAN'):
//            	if(isArray == 0){
//                   $('.modul[data-modul="' + obj.modul + '"] .formLeft span[data-name="' + key + '"]').html(formData[key]);
//                }
//                
//                if(isArray == 1){
//                	var text = '';
//                    for(i=0; i < formData[key].length; i++){
//                        if(text != '') text += '<br/>';
//                        text += formData[key][i];
//                    }
//                    $('.modul[data-modul="' + obj.modul + '"] .formLeft span[data-name="' + key + '"]').html(text);
//                }
//                
//                break;
        }
    }
}


function readData(obj){
	var formData = JSON.parse($('.modul[data-modul="' + obj.modul + '"] .formLeft [name="formdata"]').val());
	
	var tagname = '';
	var type = '';
	var isArray = 0;
	var isWysiwyg = 0;
	var classes = '';

    for(key in formData){
    	tagname = '';
		type = '';
        isArray = 0;
    	isWysiwyg = 0;
        classes = '';
        
        if($('.modul[data-modul="' + obj.modul + '"] .formLeft [name="' + key + '"]')[0]){
        	tagname = $('.modul[data-modul="' + obj.modul + '"] .formLeft [name="' + key + '"]')[0].tagName;
        }
    	if($('.modul[data-modul="' + obj.modul + '"] .formLeft [name="' + key + '\[\]"]')[0]){
        	tagname = $('.modul[data-modul="' + obj.modul + '"] .formLeft [name="' + key + '\\[\\]"]')[0].tagName;
            isArray = 1;
        }
        if($('.modul[data-modul="' + obj.modul + '"] .formLeft [name="' + key + '"].isArray')[0]){
        	tagname = $('.modul[data-modul="' + obj.modul + '"] .formLeft [name="' + key + '"]')[0].tagName;
            isArray = 1;
        }
		if(tagname == ''){
			if($('.modul[data-modul="' + obj.modul + '"] .formLeft [data-name="' + key + '"]')[0]){
				tagname = $('.modul[data-modul="' + obj.modul + '"] .formLeft [data-name="' + key + '"]')[0].tagName;
			}
			if($('.modul[data-modul="' + obj.modul + '"] .formLeft [data-name="' + key + '\[\]"]')[0]){
				tagname = $('.modul[data-modul="' + obj.modul + '"] .formLeft [data-name="' + key + '\\[\\]"]')[0].tagName;
				isArray = 1;
			}
			if($('.modul[data-modul="' + obj.modul + '"] .formLeft [data-name="' + key + '"].isArray')[0]){
				tagname = $('.modul[data-modul="' + obj.modul + '"] .formLeft [data-name="' + key + '"]')[0].tagName;
				isArray = 1;
			}
		}
		
        switch(tagname){
        	case('INPUT'):
            	if(isArray == 0) type = $('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]').attr('type');
            	if(isArray == 1) type = $('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '\[\]"]').attr('type');
                
                switch(type){
                	case('text'):
                        formData[key] = $('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]').val();
                        break;
                

                	case('checkbox'):
                    	if(isArray == 0){
							if($('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]').prop('checked') == true){
								formData[key] = $('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]').val();
							}else{
								formData[key] = '';
							}
//                   			if($('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]').hasClass('onlyMaster') == true) setOnlyMaster(key);
                        }

                    	if(isArray == 1){
							formData[key] = new Array();
							$('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '\[\]"]:checked').each(function(){
								formData[key].push($(this).val())
							});
                        }
                        break;


                	case('radio'):
						if($('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]:checked').length == 0){
							formData[key] = '';
						}else{
							formData[key] = $('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]:checked').val();
						}
//						setRadioCheck(key, 0)
                        break;
                

                	case('hidden'):
                        formData[key] = $('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]').val();
                        break;
                

//                	case('file'):
//                    	$('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '"]').addClass('filefieldUnvisible');
//                        $('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '_' + selectCountry + '_' + selectLanguage + '_' + selectDevice + '"]').parent().addClass('filefieldUnvisible');
//                        $('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + '_' + selectCountryNew + '_' + selectLanguageNew + '_' + selectDeviceNew + '"]').parent().removeClass('filefieldUnvisible');
//                   	
//                        break;
                
                }
                break;
        
        
        	case('TEXTAREA'):
				if($('.modul[data-modul="' + obj.modul + '"] .formLeft textarea[name="' + key + '"]').hasClass('wysiwyg')) isWysiwyg = 1;
                
                if(isWysiwyg == 0){
                    formData[key] = $('.modul[data-modul="' + obj.modul + '"] .formLeft textarea[name="' + key + '"]').val();
                }else{
//                    formData[key] = CKEDITOR.instances[$('.modul[data-modul="' + obj.modul + '"] .formLeft textarea[name="' + key + '"]').attr('id')].getData();
//                    CKEDITOR.instances[$('.modul[data-modul="' + obj.modul + '"] .formLeft textarea[name="' + key + '"]').attr('id')].setData(formData[key]);
                }
                break;
        
        
        	case('SELECT'):
                if(isArray == 0){
                    formData[key] = $('.modul[data-modul="' + obj.modul + '"] .formLeft select[name="' + key + '"] option:selected').val();
//                    if($('.modul[data-modul="' + obj.modul + '"] .formLeft select[name="' + key + '"]').hasClass('onlyMaster') == true) setOnlyMaster(key);
					
//					if($('.modul[data-modul="' + obj.modul + '"] .formLeft select[name="' + key + '"]').hasClass('autocomplete') == true){
//						var seltext = $('.modul[data-modul="' + obj.modul + '"] .formLeft select[name="' + key + '"] option:selected').text();
//						$('.modul[data-modul="' + obj.modul + '"] .formLeft input[name="' + key + 'Tmp"]').val(seltext);
//					}
                }

                if(isArray == 1){
					formData[key] = new Array();
					$('.modul[data-modul="' + obj.modul + '"] .formLeft select[name="' + key + '\[\]"] option:selected').each(function(){
						formData[key].push($(this).val())
					});
                }
                break;
        	
//            case('DIV'):
//            	if(isArray == 0){
//                   $('.modul[data-modul="' + obj.modul + '"] .formLeft div[data-name="' + key + '"]').html(formData[key]);
//                }
//                
//                if(isArray == 1){
//                	var text = '';
//                    for(i=0; i < formData[key].length; i++){
//                        if(text != '') text += '<br/>';
//                        text += formData[key][i];
//                    }
//                    $('.modul[data-modul="' + obj.modul + '"] .formLeft div[data-name="' + key + '"]').html(text);
//                }
//                
//                break;
//        	
//            case('SPAN'):
//            	if(isArray == 0){
//                   $('.modul[data-modul="' + obj.modul + '"] .formLeft span[data-name="' + key + '"]').html(formData[key]);
//                }
//                
//                if(isArray == 1){
//                	var text = '';
//                    for(i=0; i < formData[key].length; i++){
//                        if(text != '') text += '<br/>';
//                        text += formData[key][i];
//                    }
//                    $('.modul[data-modul="' + obj.modul + '"] .formLeft span[data-name="' + key + '"]').html(text);
//                }
//                
//                break;
        }
    }
	
	$('.modul[data-modul="' + obj.modul + '"] .formLeft [name="formdata"]').val(JSON.stringify(formData));
	
	if(obj.cb_readData && typeof(obj.cb_readData) === 'function') obj.cb_readData(obj);
}


function sendTemp(obj){
	obj.data = $('.modul[data-modul="' + obj.modul + '"] .formLeft [name="formdata"]').val();
	
	var check = [];
	$('.modul[data-modul="' + obj.modul + '"] .formLeft [data-checkfunction]').each(function(){
		var checkObj = {};
		checkObj.field = $(this).attr('name');
		checkObj.function = $(this).attr('data-checkfunction');
		checkObj.message = $(this).attr('data-checkmessage');
		check.push(checkObj);
	});
	obj.check = check;
	
	var sync = {};
	$('.modul[data-modul="' + obj.modul + '"] .formLeft [data-checksync]').each(function(){
		var field = $(this).attr('name');
		var synctype = $(this).attr('data-checksync');
		if(sync[synctype] == undefined) sync[synctype] = [];
		sync[synctype].push(field);
	});
	obj.sync = sync;
	

	$.ajax({
		url: '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu_sys-tempdata-save.php',       
		type: 'post',          
		data: 'data=' + JSON.stringify(obj),       
		cache: false,  
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(aPage)
		},
		success: function (result) {
			// ## sending data or show errors ##
			if(result == 'OK'){
				if(aPage.moduls[obj.modul].specifics.substr(6, 1) == 9){
					if($(aElement.field).hasClass('filterFormCountry'))	changeFormFilterSysCountry(obj);
					if($(aElement.field).hasClass('filterFormLanguage')) changeFormFilterSysLanguage(obj);
					if($(aElement.field).hasClass('filterFormDevice')) changeFormFilterSysDevice(obj);
				}else{
					if($(aElement.field).hasClass('filterFormCountry'))	changeFormFilterCountry(obj);
					if($(aElement.field).hasClass('filterFormLanguage')) changeFormFilterLanguage(obj);
					if($(aElement.field).hasClass('filterFormDevice')) changeFormFilterDevice(obj);
				}
					
				if(obj.cb_sendTemp && typeof(obj.cb_sendTemp) === 'function') obj.cb_sendTemp(obj);
			}else{
				if($(aElement.field).hasClass('filterFormCountry'))	$(aElement.field).find('option[value="' + aElement.oldValue + '"]').prop('selected', true);
				if($(aElement.field).hasClass('filterFormLanguage')) $(aElement.field).find('option[value="' + aElement.oldValue + '"]').prop('selected', true);
				if($(aElement.field).hasClass('filterFormDevice')) $(aElement.field).find('option[value="' + aElement.oldValue + '"]').prop('selected', true);
					
				obj.result = JSON.parse(result);
				showErrors(obj);
			}
		}
	});
}


function saveData(obj){
	if(obj.urlSave == undefined){
		obj.urlSave = '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu-' + aPage.moduls[obj.modul].modulname + '-update.php';
		if(obj.id == 0) obj.urlSave = '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu-' + aPage.moduls[obj.modul].modulname + '-insert.php';
	}
	delete obj.data;

	$.ajax({
		url: obj.urlSave,       
		type: 'post',          
		data: 'data=' + JSON.stringify(obj),       
		cache: false,  
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(aPage)
		},
		success: function (result) {
			var res = JSON.parse(result); 
			obj.id = res.id;
	
			$('.modul[data-modul="' + obj.modul + '"] .formLeft .field_id').val(obj.id);

			$('.modul[data-modul="' + obj.modul + '"] .formLeft .errorMess').html('<span><span class="errorText">' + aText.saveText + '</span><span class="errorIcon"></span></span>');
			$('.modul[data-modul="' + obj.modul + '"] .formLeft .errorMess').removeClass('messageError');
			$('.modul[data-modul="' + obj.modul + '"] .formLeft .errorMess').addClass('messageOk');
			
			$('#gridTable_' + aPage.moduls[obj.modul].modulname).trigger('reloadGrid');
			
			if(obj.action == 'close'){
				cancelForm(obj.modul)
			}else{
				loadData(obj);
				initFormFilter(obj);
				initFormNav(obj);
			}
		}
	});
}


function nextRow(obj){
	waiting('#' + $('.modul[data-modul="' + obj.modul + '"] .grid').attr('id'));

	var idNew = $('#gridTable_' + aPage.moduls[obj.modul].modulname + ' tr.selectedDataset').next('tr').attr('id');
//	var idModul = $('#' + aSpecsPage.idFormOuter + ' .fieldFModul').val();	
//	var mode = $('#' + aSpecsPage.idFormOuter + ' .fieldMode').val();
	
	if(idNew != undefined){
		obj.id = idNew;
//		$('#breadcrumb .breaddataset:last', top.document).remove();
//		var bread = $('#breadcrumb', top.document).html();
//		$('#breadcrumb', top.document).html(bread);



		var objC = {};
		objC.moduls = [];
		objC.moduls.push(obj.modul);
	//	77 hier müssen noch Kindermodule abgefragt werden
		clearData(objC);


		
		loadData(obj);
		
		$('#gridTable_' + aPage.moduls[obj.modul].modulname + ' tr.selectedDataset').removeClass('selectedDataset'); 
		$('#gridTable_' + aPage.moduls[obj.modul].modulname + ' tr[id="' + idNew + '"]').addClass('selectedDataset');
		//$('#' + aSpecsPage.idGridTable).setSelection(idNew);
		
		
//
//		$.ajax({  
//			url: aSpecsPage.urlRead,    
//			type: 'post',          
//			data: 'id=' + idNew + '&idModul=' + idModul,       
//			cache: false,  
//			success: function (result) {
//				formData = JSON.parse(result);
//
//				window['f_' + aSpecsPage.idModul]['cbLoadFormStart'](idNew);
//
//				$('#' + aSpecsPage.idFormOuter + ' .fieldID').val(formData[0][0][0]['id']);
//				//initFieldsUpload();
//				switchMultiple(0, 1)
//
//				$('#' + aSpecsPage.idFormOuter + ' .formContent .formRight .loaded').removeClass('loaded');
//				var tab = $('#' + aSpecsPage.idFormOuter + ' .formContent .formRight .formTabs li.active').attr('data-formtab');
//				loadDataAssigned(tab);
//				
//		        if(mode == 'read') setFormRead();
//				checkNavButton();
//
//				window['f_' + aSpecsPage.idModul]['cbLoadFormComplete'](idNew);
//
//				$('#breadcrumb', top.document).append('<span class="breaddataset">'+formData[selectCountry][selectLanguage][selectDevice][aTextScript.fDeleteField]+'</span>');
//				window.top.navBreadcrumbInit();
//			}
//		});
    }else{
    	unwaiting();
    }
}


function prevRow(obj){
	waiting('#' + $('.modul[data-modul="' + obj.modul + '"] .grid').attr('id'));

	var idNew = $('#gridTable_' + aPage.moduls[obj.modul].modulname + ' tr.selectedDataset').prev('tr').attr('id');

	if(idNew != undefined){
		obj.id = idNew;

		var objC = {};
		objC.moduls = [];
		objC.moduls.push(obj.modul);
	//	77 hier müssen noch Kindermodule abgefragt werden
		clearData(objC);
		
		loadData(obj);
		
		$('#gridTable_' + aPage.moduls[obj.modul].modulname + ' tr.selectedDataset').removeClass('selectedDataset'); 
		$('#gridTable_' + aPage.moduls[obj.modul].modulname + ' tr[id="' + idNew + '"]').addClass('selectedDataset');
    }else{
    	unwaiting();
    }
}



function sendDelete(obj){
    $.ajax({  
        url: obj.urlDelete,    
        type: 'post',          
        data: 'data=' + JSON.stringify(obj),       
        cache: false,  
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(aPage)
		},
        success: function (result) {
			$('#gridTable_' + aPage.moduls[obj.modul].modulname).trigger('reloadGrid');
//			if(cb != undefined) cb();

            closeDialog(obj); 
        }    
    });  
}



