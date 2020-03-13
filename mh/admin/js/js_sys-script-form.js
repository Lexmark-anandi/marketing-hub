function loadForm(obj, el){
	waiting('#modul_' + obj.modulpath);
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	if(objModul.urlForm == undefined) objModul.urlForm = 'fo-' + obj.modul_name + '.php';

	objModul.activeSettings.formCountry = objModul.activeSettings.selectCountry;
	objModul.activeSettings.formLanguage = objModul.activeSettings.selectLanguage;
	objModul.activeSettings.formDevice = objModul.activeSettings.selectDevice;
	
	// ## add modul to user object (parent or child)
	(obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] = objModul : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod] = objModul;
	
	// ## open childmodul ##
	if(obj.id_mod_parent != 0){
		//obj.id_data_parent = obj.id_data;
		childmodulWideOpenDirect(obj);
		$('#modul_' + obj.modulpath + ' .formMiddleClose').addClass('formMiddleCloseHide');
	}


	var data = '';
	
    $.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFormsAdmin + 'fo_sys-default.php', 
		data: data,    
		type: 'post',          
		cache: false,  
		headers: {
			csrfToken: Cookies.get('csrf'), 
			page: JSON.stringify(obj),
			settings: JSON.stringify(objModul.activeSettings)
		},
		success: function (form, status, jqXHR) {
			actualizeStatus(form, status);

			$('#modul_' + obj.modulpath + ' > .form .formContent').html(form);
			$('#modul_' + obj.modulpath + ' > .form').removeClass('hidden');
			
			$('#modul_' + obj.modulpath + ' .filterFormCountry option[value="' + objModul.activeSettings.formCountry + '"]').prop('selected', true);
			if(objModul.specifications[7] == 9) changeFormFilterCountry(obj);
			$('#modul_' + obj.modulpath + ' .filterFormLanguage option[value="' + objModul.activeSettings.formLanguage + '"]').prop('selected', true);
			$('#modul_' + obj.modulpath + ' .filterFormDevice option[value="' + objModul.activeSettings.formDevice + '"]').prop('selected', true);
			initFormFilter(obj);
			
			unwaiting('#modul_' + obj.modulpath);
			
			initForm(obj);
			initFields(obj);
			initFieldsUpload(obj);
			createTabsFormRight(obj);

			var objCb = Object.assign({},obj);
			delete(objCb.cb_loadForm);
			if(window['f_' + obj.modul_name][obj.cb_loadForm] && typeof(window['f_' + obj.modul_name][obj.cb_loadForm]) === 'function'){
				window['f_' + obj.modul_name][obj.cb_loadForm](objCb, this);
			}else if(window[obj.cb_loadForm] && typeof(window[obj.cb_loadForm]) === 'function'){
				window[obj.cb_loadForm](objCb, this);
			}else if(obj.cb_loadForm && typeof(obj.cb_loadForm) === 'function'){
				obj.cb_loadForm(objCb, this);
			}
			
			if(window['f_' + obj.modul_name].cbLoadFormSuccess && typeof(window['f_' + obj.modul_name].cbLoadFormSuccess) === 'function') window['f_' + obj.modul_name].cbLoadFormSuccess(obj);
			
			
			loadData(obj);

//			if(obj.cb_loadForm && typeof(obj.cb_loadForm) === 'function') obj.cb_loadForm(obj);
//			
////			
////			if(useMultiple == 0) $('.modul[data-modul="' + modul + ' .form .checkmaster').css('display', 'none');
////			
////			
////			var activeTabR = $('.modul[data-modul="' + modul + ' .form .formContent .formRight .formTabs ul li.active').attr('data-formtab');
////			
////			
////			$('#' + aSpecsPage.idGridOuter).parent().addClass('contentHidden');
////			$('#' + aSpecsPage.idGridOuter).parent().addClass('contentHeight0');
////			$('#' + aSpecsPage.idFormOuter).parent().removeClass('contentHidden');
////
		}
	});  
}


function initForm(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	
	$('#modul_' + obj.modulpath + ' .formLeft .cancelForm').off('click');
	$('#modul_' + obj.modulpath + ' .formLeft .cancelForm').on('click', function(){
		cancelForm(obj);
	});
	
	$('#modul_' + obj.modulpath + ' .formLeft .saveForm').off('click');
	$('#modul_' + obj.modulpath + ' .formLeft .saveForm').on('click', function(){
		window['f_' + obj.modul_name]['sendForm'](obj, 'save');
	});
	
	$('#modul_' + obj.modulpath + ' .formLeft .closeForm').off('click');
	$('#modul_' + obj.modulpath + ' .formLeft .closeForm').on('click', function(){
		window['f_' + obj.modul_name]['sendForm'](obj, 'close');
	});
	
	$('#modul_' + obj.modulpath + ' .formMiddle').off('click');
	$('#modul_' + obj.modulpath + ' .formMiddle').on('click', function(){
		childmodulWideOpenManually(obj);
	});

	$('#modul_' + obj.modulpath + ' .childmodul').off('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend');
	$('#modul_' + obj.modulpath + ' .childmodul').on('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function () {
		countAnimation++;
		if(countAnimation == 2){
			var modulpath = $('#modul_' + obj.modulpath + ' .formRight .formTabs li.active').attr('data-modulpath');
			if(modulpath != undefined){
				var objModul = splitModulpath(modulpath);
				modulResize(objModul);
			}
			
			$('#modul_' + obj.modulpath + ' .childmodulUnvisible').removeClass('childmodulUnvisible');
			$('#modul_' + obj.modulpath + ' .childmodulOpenBG').removeClass('childmodulOpenBG');
		}
	});
	
	// ## Create tabs for form ##
	var numTabs = 0;
	$('#modul_' + obj.modulpath + ' .formContent .formLeft .fieldset').each(function(){
		numTabs++;
		var tab = $(this).attr('data-formtab');	
		var tabText = (objText[tab] == undefined) ? tab : objText[tab];
		$('#modul_' + obj.modulpath + ' .formContent .formLeft .formTabs ul').append('<li data-formtab="' +  tab + '"><span class="formTabIconError"><i class="fa" aria-hiddem="true"></i></span>' + tabText + '</li>');
	});
	$('#modul_' + obj.modulpath + ' .formContent .formLeft .formTabs ul li:first').addClass('active');
	$('#modul_' + obj.modulpath + ' .formContent .formLeft .fieldset:first').addClass('fieldsetActive');
			
	$('#modul_' + obj.modulpath + ' .formLeft .formTabs li').off('click');
	$('#modul_' + obj.modulpath + ' .formLeft .formTabs li').on('click', function(){
		showFormTab(obj, this);
	});

	initFormNav(obj);
	
//	$('#modul_' + obj.modulpath + ' .formNavButtonMax').off('click');
//	$('#modul_' + obj.modulpath + ' .formNavButtonMax ').on('click', function(){
//		$(this).closest('.modul').toggleClass('formMaximized');
//	});
	
	if($('.modul').length == 1) $('.formNavButtonMax').addClass('formNavButtonDisabled');

	resizeForm(obj);
}


function createTabsFormRight(obj){
	var numTabsR = 0;
//	$('#' + aSpecsPage.idFormOuter + ' .formContent .formRight .formTab').addClass('formTabHidden');
	if(obj.id_data > 0 && $('#modul_' + obj.modulpath + ' .formContent .formRight .formTabs li').length == 0){
		$('#modul_' + obj.modulpath + ' .childmodul').each(function(){
			numTabsR++;
			
			var modulpath = $(this).attr('data-modulpath');
			var objM = splitModulpath(modulpath);
			var objModul = objUser.childmoduls[objM.id_mod_parent]['i_' + objM.id_mod];
			if(objModul.modul_label == '') objModul.modul_label = objModul.modul_name;

			var tabText = (objText[objModul.modul_label] == undefined) ? objModul.modul_label : objText[objModul.modul_label];
			$('#modul_' + obj.modulpath + ' .formContent .formRight .formTabs ul').append('<li data-modulpath="' +  modulpath + '"><span class="formTabIconError"></span>' + tabText + '</li>');
		});
		
		var heightTabsR = $('#modul_' + obj.modulpath + ' .formRightInner .formTabs').height();
		$('#modul_' + obj.modulpath + ' .childmodul').css('top', heightTabsR + 'px');
		
		$('#modul_' + obj.modulpath + ' .formContent .formRight .formTabs ul li:first').addClass('active');
		$('#modul_' + obj.modulpath + ' .formContent .formRight .childmodul:first').removeClass('hidden');

		$('#modul_' + obj.modulpath + ' .formRight .formTabs li').off('click');
		$('#modul_' + obj.modulpath + ' .formRight .formTabs li').on('click', function(){
			$('#modul_' + obj.modulpath + ' .formRight .formTabs li').removeClass('active')
			$(this).addClass('active');
			showChildmodul(obj, this);
		});
		






//		$('#' + aSpecsPage.idFormOuter + ' .formContent .formRight .formTab').each(function(){
//			var idpage = $(this).attr('data-idpage');
//			var tab = $(this).attr('data-formtab');	
//			var tabText = aText[tab];
//			
//			if(aUserPages.indexOf(idpage) > -1){
//				numTabsR++;
//				$('#' + aSpecsPage.idFormOuter + ' .formContent .formRight .formTabs ul').append('<li data-formtab="' +  tab + '" onclick="showFormTab(\'formRight\', \'' + tab + '\')"><span class="formTabIconError"></span>' + tabText + '</li>');
//				
//				$(this).closest('fieldset').appendTo('#containerAssigned');
//			}
//		});
	}

//	$('.formMiddle').removeClass('formMiddleDeactive');
//	if(numTabsR == 0){
//		$('#' + aSpecsPage.idFormOuter + ' .formContent .formRight .formFooterInner').css('display', 'none');
//		$('#' + aSpecsPage.idAssigned).css('display', 'none');
//		$('.formMiddle').addClass('formMiddleDeactive');
//	}else{
//		$('#' + aSpecsPage.idFormOuter + ' .formContent .formRight .formFooterInner').css('display', 'block');
//		$('#' + aSpecsPage.idAssigned).css('display', 'block');
//		$('.formMiddle').removeClass('formMiddleDeactive');
//	}
	$('#modul_' + obj.modulpath + ' .formMiddle').removeClass('formMiddleDeactive');
	if(numTabsR == 0) $('#modul_' + obj.modulpath + ' .formMiddle').addClass('formMiddleDeactive');
}


function initFormNav(obj){
	$('#modul_' + obj.modulpath + ' .formNavButtonNext').off('click');
	$('#modul_' + obj.modulpath + ' .formNavButtonNext ').on('click', function(){
		nextRow(obj);
	});
	
	$('#modul_' + obj.modulpath + ' .formNavButtonPrev').off('click');
	$('#modul_' + obj.modulpath + ' .formNavButtonPrev ').on('click', function(){
		prevRow(obj);
	});
}


//function formResize(){
//	// ## for all forms ##
//	$('.modul').each(function(){
//		var modul = $(this).attr('data-modul');
//		resizeForm(modul);
//	});
//}


function resizeForm(obj){
	var hTabs = $('#modul_' + obj.modulpath + ' .formLeft .formTabs').outerHeight(true);
	var hFooters = $('#modul_' + obj.modulpath + ' .formLeft .formFooter').outerHeight(true);
	$('#modul_' + obj.modulpath + ' .formLeft .fieldset').css('top', hTabs + 'px');
	$('#modul_' + obj.modulpath + ' .formLeft .fieldset').css('bottom', hFooters + 'px');
}


function showFormTab(obj, el){
	$('#modul_' + obj.modulpath + ' .formLeft .formTabs li').removeClass('active')
	$(el).addClass('active')
	
	var activeTab = $(el).attr('data-formtab');
	$('#modul_' + obj.modulpath + ' .formLeft .fieldset').removeClass('fieldsetActive');
	$('#modul_' + obj.modulpath + ' .fieldset[data-formtab="' + activeTab + '"]').addClass('fieldsetActive');
}


function initFields(obj){
	// ## set row for readonly ##
	$('#modul_' + obj.modulpath + ' .formLeft [readonly]').closest('.formRow').addClass('formRowReadonly');
	$('#modul_' + obj.modulpath + ' .formLeft [readonly]').readonly(true);
	
	// ## bind fields for formchecking on blur ##
	$('#modul_' + obj.modulpath + ' .formLeft .checkDirect').off('blur');
	$('#modul_' + obj.modulpath + ' .formLeft .checkDirect').on('blur', function(){
		var func = $(this).attr('data-checkfunction');
		var aFunc = func.split(';');
		for(key in aFunc){
			if(window['f_' + obj.modul_name][aFunc[key]] && typeof(window['f_' + obj.modul_name][aFunc[key]]) === 'function'){
				window['f_' + obj.modul_name][aFunc[key]](obj, this);
			}else if(window[aFunc[key]] && typeof(window[aFunc[key]]) === 'function'){
				window[aFunc[key]](obj, this);
			}
		}
	});
	
    // ## set WYSIWYG editor ##
	$('#modul_' + obj.modulpath + ' .formLeft textarea.wysiwyg').each(function(){
		var wysiwygName = $(this).attr('name');
		
		var d = new Date();
		var n = d.getTime();

		var objConfig = JSON.parse($(this).attr('data-config'));
		if(objConfig.customConfig == undefined) objConfig.customConfig = objSystem.directoryInstallation + objSystem.pathAdmin + 'config-ckeditor.js?t=' + n;
		if(objConfig.toolbar == undefined) objConfig.toolbar = 'SYS';
		if(objConfig.height == undefined) objConfig.height = $(this).outerHeight(true);

		$(this).ckeditor(objConfig);
		
		// ## fill data if init is to slow ##
		CKEDITOR.instances[$('#modul_' + obj.modulpath + ' .formLeft textarea[name="' + wysiwygName + '"]').attr('id')].on( 'instanceReady', function(evt) {
			var formData = $('#modul_' + obj.modulpath + ' .formLeft [name="formdata"]').val();
			if(formData != '' && formData != undefined){
				objFormData = JSON.parse(formData);
				CKEDITOR.instances[$('#modul_' + obj.modulpath + ' .formLeft textarea[name="' + wysiwygName + '"]').attr('id')].setData(objFormData[wysiwygName]);
			}
		} );
	});

//////
//////    $('#' + idDialog + ' .radiocheck').bind('click', function(){
//////		var el = ($(this).attr('name'));
//////    	setRadioCheck(el, 1);
//////    });
////    
//    
	
	$('#modul_' + obj.modulpath + ' .form .calendar').datepicker({
		showButtonPanel: true
	});
	
//    $('#modul_' + obj.modulpath + ' .form .calendartime').datetimepicker({
//		showSecond: false,
//		timeFormat: 'HH:mm:ss'   
//	});
//	
//	$('#modul_' + obj.modulpath + ' .form [data-maxlength]').each(function(){
//		checkMaxChars(this);
//	});
//    
//    
////	initFieldsAutocomplete();

	

	var objCb = Object.assign({},obj);
	delete(objCb.cb_fillData);
	if(window['f_' + obj.modul_name][obj.cb_initFields] && typeof(window['f_' + obj.modul_name][obj.cb_initFields]) === 'function'){
		window['f_' + obj.modul_name][obj.cb_initFields](objCb, this);
	}else if(window[obj.cb_initFields] && typeof(window[obj.cb_initFields]) === 'function'){
		window[obj.cb_initFields](objCb, this);
	}else if(obj.cb_initFields && typeof(obj.cb_initFields) === 'function'){
		obj.cb_initFields(objCb, this);
	}
}   


function initFieldsVariation(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	
	// ## set default selection for booleanfields to notMaster
	if($('#modul_' + obj.modulpath + ' .wFormFilter').length == 0){
		$('#modul_' + obj.modulpath + ' .formLeft .booleanfield[value="0"]').parent().css('display', 'none');
	}else{
		if(obj.id_data == 0 || (objModul.activeSettings.formCountry == 0 && objModul.activeSettings.formLanguage == 0 && objModul.activeSettings.formDevice == 0)){
			$('#modul_' + obj.modulpath + ' .formLeft .booleanfield[value="0"]').parent().css('display', 'none');
		}else{
			$('#modul_' + obj.modulpath + ' .formLeft .booleanfield[value="0"]').parent().css('display', '');
		}
	}
	
	// ##set readonly for onlyMaster / notMaster ##
	$('#modul_' + obj.modulpath + ' .formLeft [data-editspecs]').closest('.formRow').removeClass('formRowReadonly');
	$('#modul_' + obj.modulpath + ' .formLeft [data-editspecs]').readonly(false);
	if(obj.id_data != 0){
		if(objModul.activeSettings.formCountry == 0 && objModul.activeSettings.formLanguage == 0 && objModul.activeSettings.formDevice == 0){
			$('#modul_' + obj.modulpath + ' .formLeft [data-editspecs="2"]').closest('.formRow').addClass('formRowReadonly');
			$('#modul_' + obj.modulpath + ' .formLeft [data-editspecs="2"]').readonly(true);
		}else{
			$('#modul_' + obj.modulpath + ' .formLeft [data-editspecs="1"]').closest('.formRow').addClass('formRowReadonly');
			$('#modul_' + obj.modulpath + ' .formLeft [data-editspecs="1"]').readonly(true);
		}
	}
}


function initFieldsUpload(obj, target){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	obj.target = (target == undefined) ? '#modul_' + obj.modulpath : target;
	//obj.target = '#modul_' + obj.modulpath + ' .formLeft';
	//obj.target = '#modul_' + obj.modulpath + '';

//	$(obj.target + ' .fileupload').each(function(){
//		var fieldid = $(this).attr('id');
//		var fieldname = $(this).attr('name');
//		var label = objText.selectFile;
//		if($(obj.target + ' #' + fieldid + '[multiple]').length > 0) label = objText.selectFiles;
//		
//		// ## DIV for existing files ##
////		$(this).before('<div data-name="' + fieldname + 'F" class=""></div>'); 
////
////		$(this).before('<div class="textfield textfieldUpload" onclick="uploadSelection(\'' + obj.target + '\', \'' + fieldid + '\')"><input type="button" class="formButton formButtonUpload" value="' + label + '"></div>'); 
//	});
	$(obj.target + ' .textfieldUpload').off();
	$(obj.target + ' .textfieldUpload').on('click', function(){
		uploadSelection(obj.target, this);
	});
	$(obj.target + ' .fileupload').hide();

	
	$(obj.target + ' .fileupload').fileupload({
		url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-media-upload.php',
        dataType: 'json',
		autoUpload: false,
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(obj),
			settings: JSON.stringify(objModul.activeSettings)
		},
        add: function (e, data) {
        },
        done: function (e, data) {
			objResultFiles[data.result.files.fieldname] = data.result.files;
			
			var field = data.result.files.fieldname;
			var orgfieldname = data.result.files.orgfieldname;
			var filename = data.result.files.name;
			var sysname = data.result.files.sysname;
			var fileid = data.result.files.idfile;
			var multiple = data.result.files.multiple;
			var fieldUploaded = (multiple == 'multiple') ? field + 'F' : field + 'F';
////			url_Up = data.result.files.url;
////			urlRead_Up = data.result.files.urlRead;
////			cb_Up = data.result.files.cb;
			
			var formdata = $(obj.target + ' [name="formdata"]').val();
			if(formdata == undefined) formdata = $('[name="formdata"]').val();
			var formData = (formdata == '') ? {} : JSON.parse(formdata);
			if($(obj.target + ' #' + field + '_' + obj.modulpath + '[multiple]').length > 0){
				if(formData[orgfieldname] == undefined) formData[orgfieldname] = [];
				formData[orgfieldname].push(data.result.files.idfile);
			}else{
				if(formData[orgfieldname] == undefined) formData[orgfieldname] = '';
				formData[orgfieldname] = data.result.files.idfile;
			}
//			if(formData['uploadedFilesId'] == undefined) formData['uploadedFilesId'] = [];
//			formData['uploadedFilesId'].push(data.result.files.idfile);

			$(obj.target + ' input[name="formdata"]').val(JSON.stringify(formData));
			$(obj.target + ' .fileUploadOuter[data-filename="' + filename + '"]').remove();
        },
        start: function (e, data) {
			numFiles++;
			$(obj.target + ' .fileUploadOuter .fileUploadDelete').css('display', 'none');
		},
        stop: function (e, data) {
			numFiles--;
			if(numFiles == 0){
				filesUpload = new Array();
				$('.uploadOverlay').remove();
				
				var cbSendFiles = '';
				for(var fieldname in objResultFiles){
					cbSendFiles = objResultFiles[fieldname].cbSendFiles;
					break;
				}
				
				if(cbSendFiles == 'undefined' || cbSendFiles == ''){
					saveDataSubmit(obj);
				}else{
					var objCb = Object.assign({},obj);  
					delete(objCb.cb_fillData);
					//delete(objCb.cbSendFiles);
					if(window['f_' + obj.modul_name][cbSendFiles] && typeof(window['f_' + obj.modul_name][cbSendFiles]) === 'function'){
						window['f_' + obj.modul_name][cbSendFiles](objCb, objResultFiles);
					}else if(window[cbSendFiles] && typeof(window[cbSendFiles]) === 'function'){
						window[cbSendFiles](objCb, objResultFiles);
					}else if(cbSendFiles && typeof(cbSendFiles) === 'function'){
						cbSendFiles(objCb, objResultFiles);
					}
					delete(obj.cb_fillData);
					delete(obj.cbSendFiles);
				}
				objResultFiles = {};
			}
		},
		progress: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$(obj.target + ' .uploadOverlay .fileUploadOuter[data-filename="' + data.files[0].name + '"] .fileUploadProgressbar').css('width', progress + '%');
		}
	});

	$(obj.target + ' .fileupload').off('change');
	$(obj.target + ' .fileupload').on('change', function(e) {
        var filesTmp = e.target.files || [{name: this.value}];
        //var filesTmp = this.files;
		for(var key in filesTmp){
			if(filesTmp[key].name != undefined && filesTmp[key].name != 'item'){
				var fieldid = $(this).attr('id');
				filesTmp[key]['field'] = $(this).attr('name');
				filesTmp[key]['orgfieldname'] = $(this).attr('data-fieldname');
				filesTmp[key]['multiple'] = ($(this).attr('multiple') != undefined) ? 'multiple' : '';
				filesTmp[key]['filename'] = filesTmp[key].name;
				filesTmp[key]['filekey'] = filesTmp[key].name + '_' + Math.random().toString(36).substr(2, 9);
				filesTmp[key]['forceupload'] = ($(this).attr('data-forceupload') != undefined) ? $(this).attr('data-forceupload') : 'no';
				filesTmp[key]['target'] = '';
				if($(this).attr('data-target') != undefined) filesTmp[key]['target'] = $(this).attr('data-target');
		
				if(filesTmp[key]['multiple'] == ''){
					var filedelete = $(obj.target + ' #' + fieldid).closest('.formField').find('div[data-filekey]').attr('data-filekey');
					if(filedelete != '' && filedelete != undefined){
						uploadDeleteFile(obj.target, filedelete);
					}
				}
				
				if(filesTmp[key]['field'] == undefined){
					$(this).val('');
				}else{
					filesUpload.push(filesTmp[key]);
					
					$(obj.target + ' #' + fieldid).closest('.formField').append('<div class="fileUploadOuter" data-filename="' + filesTmp[key].name + '" data-filekey="' + filesTmp[key].filekey + '"><div class="fileUploadProgressbar"></div><div class="fileUploadFilename">' + filesTmp[key].name + '</div><div class="fileUploadDelete"><div class="modulIcon modulIconForm" title="' + objText.uploadFileDelete + '" onclick="uploadDeleteFile(\'' + obj.target + '\', \'' + filesTmp[key].filekey + '\')"><i class="fa fa-fw fa-trash-o"></i></div></div></div>');
					
					// ## check filesize ##
					var maxsize = objSystem.allowedUploadSize;
					if($(this).attr('data-maxsize') != undefined && $(this).attr('data-maxsize') !=''){
						var dataMaxSize = parseFilesize($(this).attr('data-maxsize'));
						if(dataMaxSize < objSystem.allowedUploadSize) maxsize = dataMaxSize;
					}
					if(maxsize < filesTmp[key].size) $(obj.target + ' #' + fieldid).closest('.formField').find('.fileUploadOuter:last').addClass('rowErrorFile rowErrorFilesize');
					
					// ## check filetype ##
					var aAllowedTypes;
					if($(this).attr('data-allowedtypes') != undefined && $(this).attr('data-allowedtypes') !=''){
						aAllowedTypes = $(this).attr('data-allowedtypes').split(',');
						if(aAllowedTypes.indexOf('jpg') > -1) aAllowedTypes.push('jpeg');
						var errorType = 1;
						//var filetype = filesTmp[key].type.toLowerCase();
						var filetype = filesTmp[key].name.split('.').pop();
						for(key in aAllowedTypes){
							//if(filetype.indexOf(aAllowedTypes[key].toLowerCase()) > -1) errorType = 0;
							if(filetype.toLowerCase() == aAllowedTypes[key].toLowerCase()) errorType = 0;
						}
						if(errorType == 1) $(obj.target + ' #' + fieldid).closest('.formField').find('.fileUploadOuter:last').addClass('rowErrorFile rowErrorFiletype');
					}
					
					checkFileError(obj.target, fieldid, maxsize, aAllowedTypes);
				}
			}
		}
		
		// reset uploadfield (for next change event)
		$(this).wrap('<form>').closest('form').get(0).reset();
		$(this).unwrap();
    });
}
 

function cancelForm(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	
	// ## close childmodul ##
	if(obj.id_mod_parent != 0){
		childmodulWideCloseDirect(obj);
		$('#modul_' + obj.modulpath + ' .formMiddleClose').removeClass('formMiddleCloseHide');
	}

	$('#modul_' + obj.modulpath + ' tr.selectedDataset').removeClass('selectedDataset'); 
	$('#modul_' + obj.modulpath + ' .form').addClass('hidden');
	$('#modul_' + obj.modulpath + ' .formContent').html('');
	
//	var obj = {};
//	obj.moduls = [];
//	obj.moduls.push(modul);
////	77 hier müssen noch Kindermodule abgefragt werden
	filesUpload = new Array();
	clearData(obj);
//
//
////	$('#' + aSpecsPage.idGridOuter).parent().removeClass('contentHidden');
////	$('#' + aSpecsPage.idGridOuter).parent().removeClass('contentHeight0');
////	$('#' + aSpecsPage.idFormOuter).parent().addClass('contentHidden');
////	$('.modul[data-modul="' + modul + ' .form .formContent').html('');
////	
	$('#breadcrumbInner .breaddataset[data-modulpath="' + obj.modulpath + '"]').remove();
////	initBreadcrumbNavigation(obj);
////
////	if(directlink == 1){
////		window.parent.closeDirectLink(cbClose, cbArgsClose);
////	}
//	
}


function checkNavButton(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

	var totalPageGrid = $('#' + objModul.gridPager + ' .pagerTotalPages').text() * 1;
	var actPageGrid = $('#' + objModul.gridPager + ' .pagerActPage').val();
	
	$('#modul_' + obj.modulpath + ' .formNavButtonPrev').addClass('formNavButtonDisabled');
	if(actPageGrid == 1 && $('#' + objModul.gridTable + ' tr:nth-child(2)').hasClass('selectedDataset')){
		$('#modul_' + obj.modulpath + ' .formNavButtonPrev').off('click');
	}else{
		$('#modul_' + obj.modulpath + ' .formNavButtonPrev').off('click');
		if(obj.id_data > 0){
			$('#modul_' + obj.modulpath + ' .formNavButtonPrev').removeClass('formNavButtonDisabled');
			$('#modul_' + obj.modulpath + ' .formNavButtonPrev ').on('click', function(){
				prevRow(obj);
			});
		}
	}
	
	$('#modul_' + obj.modulpath + ' .formNavButtonNext').addClass('formNavButtonDisabled');
	if(actPageGrid == totalPageGrid && $('#' + objModul.gridTable + ' tr:last').hasClass('selectedDataset')){
		$('#modul_' + obj.modulpath + ' .formNavButtonNext').off('click');
	}else{
		$('#modul_' + obj.modulpath + ' .formNavButtonNext').off('click');
		if(obj.id_data > 0){
			$('#modul_' + obj.modulpath + ' .formNavButtonNext').removeClass('formNavButtonDisabled');
			$('#modul_' + obj.modulpath + ' .formNavButtonNext ').on('click', function(){
				nextRow(obj);
			});
		}
	}
}


function checkFileError(target, fieldid, maxsize, aAllowedTypes){
	$(target + ' #' + fieldid).closest('.formField').find('.fileerrorMessage').remove();
	
	if($(target + ' #' + fieldid).closest('.formField').find('.rowErrorFile').length > 0){
		if($(target + ' #' + fieldid).closest('.formField').find('.rowErrorFilesize').length > 0){
			$(target + ' #' + fieldid).closest('.formField').find('.fileUploadOuter:first').before('<div class="fileerrorMessage">' + objText.messMaxFilesize + ' ' + parseFilesizeR(maxsize) + '</div>');
		}
		if($(target + ' #' + fieldid).closest('.formField').find('.rowErrorFiletype').length > 0){
			$(target + ' #' + fieldid).closest('.formField').find('.fileUploadOuter:first').before('<div class="fileerrorMessage">' + objText.messAllowFiletypes + ' ' + aAllowedTypes.join(", ") + '</div>');
		}
	}
}


function uploadDeleteFile(target, filekey){
	var fieldid = $(target + ' div[data-filekey="' + filekey + '"]').closest('.formField').children('input[type="file"]').attr('id');
	$(target + ' div[data-filekey="' + filekey + '"]').remove();
	
	var i = 0;
	for(var key in filesUpload){
		if(filesUpload[key].filekey == filekey) break;
		i++;
	}
	filesUpload.splice(i,1);

	var maxsize = objSystem.allowedUploadSize;
	if($(target + ' #' + fieldid).attr('data-maxsize') != undefined && $(target + ' #' + fieldid).attr('data-maxsize') !=''){
		var dataMaxSize = parseFilesize($(target + ' #' + fieldid).attr('data-maxsize'));
		if(dataMaxSize < objSystem.allowedUploadSize) maxsize = dataMaxSize;
	}

	var aAllowedTypes;
	if($(target + ' #' + fieldid).attr('data-allowedtypes') != undefined && $(target + ' #' + fieldid).attr('data-allowedtypes') !=''){
		aAllowedTypes = $(target + ' #' + fieldid).attr('data-allowedtypes').split(',');
		if(aAllowedTypes.indexOf('jpg') > -1) aAllowedTypes.push('jpeg');
	}

	checkFileError(target, fieldid, maxsize, aAllowedTypes);
}


function uploadSelection(target, el){
	var fieldid = $(el).parents('.formField').find('input[type="file"]').attr('id');
	$(el).parents('.formField').find('input[type="file"]').trigger('click');
}




	
function initFieldsAssign(obj){
////	$('#' + aSpecsPage.idDialogForm).dialog( "option", "minHeight", (screenHeight / 100 * 50) );

	$('.selectassign').each(function(){
		$(this).closest('.formRow').find('ul.available li').draggable({
			zIndex: 1000,
			containment: $(this).closest('.formRow'),
			appendTo: '.ui-multiselect',
			revert: 'invalid',
			revertDuration: 100,
			helper: function(){
				var val = $(this).attr('data-value');
				var w = $(this).width();
				var h = $(this).height();
				var html = $(this).html();
				return $('<li class="ui-state-default ui-element ui-draggable ui-draggable-handle" style="width:'+w+'px" data-value="'+val+'">'+html+'</li>');
			}
		});
		
		$(this).closest('.formRow').find('div.selected ul.selected').droppable({
			drop: function(event, ui) {
				var val = ui.helper.attr('data-value');
				var assign = $(this).attr('data-assign');
				assignAdd(this, val, assign);
			}
		});
		
		
		$(this).closest('.formRow').find('.ui-icon-plus').bind('click',function(){ 
			var val = $(this).closest('li').attr('data-value');
			var assign = $(this).closest('.ui-multiselect').find('ul[data-assign]').attr('data-assign');
			assignAdd(this, val, assign);
		});
		
		$(this).closest('.formRow').find('.ui-icon-minus').bind('click',function(){
			var val = $(this).closest('li').attr('data-value');
			var assign = $(this).closest('[data-assign]').attr('data-assign');
			$(this).closest('.formRow').find('.selectassign_' + assign + ' option[value="'+val+'"]').prop('selected', false); 
			$(this).closest('.formRow').find('.available li[data-value="'+val+'"]').removeClass('listassignhidden');
			$(this).closest('.formRow').find('.available li[data-value="'+val+'"]').addClass('listassignvisible');
			$(this).closest('li').removeClass('listassignvisible');
			$(this).closest('li').addClass('listassignhidden');
			assignSearch(this);
			 
			var num = $(this).closest('.formRow').find('.selectassign option:selected').length;
			$(this).closest('.formRow').find('.counter').html(num);
			assignSync(this);
		});
		
		$(this).closest('.formRow').find('.remove-all').bind('click',function(){
			$(this).closest('.formRow').find('.selectassign option').prop('selected', false);
			$(this).closest('.formRow').find('.selected li').removeClass('listassignvisible');
			$(this).closest('.formRow').find('.selected li').addClass('listassignhidden');
			$(this).closest('.formRow').find('.available li').removeClass('listassignhidden');
			$(this).closest('.formRow').find('.available li').addClass('listassignvisible');
			assignSearch(this);
			
			var num = $(this).closest('.formRow').find('.selectassign option:selected').length;
			$(this).closest('.formRow').find('.counter').html(num);
			assignSync(this);
		});
		
		$(this).closest('.formRow').find('.add-all').bind('click',function(){
			$(this).closest('.formRow').find('.available li.searchvisible').each(function(){
				if($(this).is(':visible')){
					var val = $(this).attr('data-value');
					var assign = $(this).closest('.ui-multiselect').find('ul[data-assign]').attr('data-assign');
					$(this).closest('.formRow').find('.selectassign_' + assign + ' option[value="'+val+'"]').prop('selected', true);
					$(this).closest('.formRow').find('.selected[data-assign="'+assign+'"] li[data-value="'+val+'"]').removeClass('listassignhidden');
					$(this).closest('.formRow').find('.selected[data-assign="'+assign+'"] li[data-value="'+val+'"]').addClass('listassignvisible');
					$(this).removeClass('listassignvisible');
					$(this).addClass('listassignhidden');
					$(this).removeClass('searchvisible searchhidden');
				}
			});
			var num = $(this).closest('.formRow').find('.selectassign option:selected').length;
			$(this).closest('.formRow').find('.counter').html(num);
			assignSync(this);
		});
		
		$(this).parent().find('.search').bind('input',function(){
			assignSearch(this);
		});
	});
	
	resizeFieldsAssign(obj);
////	unwaiting('#' + aSpecsPage.idContent);
}

function resizeFieldsAssign(obj){
	$('.selectassign').each(function(){
		var hHead = 0;
		$(this).closest('.formRow').find('.ui-widget-header').css('height', 'auto');
		$(this).closest('.formRow').find('.ui-widget-header').each(function(el){
			if($(this).height() > hHead) hHead = $(this).height();
		});
		$(this).closest('.formRow').find('.ui-widget-header').height(hHead);

		var hNote = 0;
		$(this).closest('.formRow').find('.selectassignNote').each(function(el){
			hNote += $(this).outerHeight(true)
		});
		
		var h = $(this).closest('.formRow').height();
		h -= hHead;
		h -= hNote;
		h -= ($(this).closest('.formRow').find('.ui-multiselect').css('margin-top').replace('px', '') * 1);
		h -= 2;

		$(this).parent().find('.available ul').height(h);
		var numSelDiv = $(this).parent().find('.selected ul').length;
		$(this).parent().find('.selected ul').height((h / numSelDiv));
	});
}

function assignAdd(el, val, assign){
	$(el).closest('.formRow').find('.selectassign_' + assign + ' option[value="'+val+'"]').prop('selected', true);
	$(el).closest('.formRow').find('.selected[data-assign="'+assign+'"] li[data-value="'+val+'"]').removeClass('listassignhidden');
	$(el).closest('.formRow').find('.selected[data-assign="'+assign+'"] li[data-value="'+val+'"]').addClass('listassignvisible');
	$(el).closest('.formRow').find('.available li[data-value="'+val+'"]').removeClass('listassignvisible');
	$(el).closest('.formRow').find('.available li[data-value="'+val+'"]').addClass('listassignhidden');
	$(el).closest('.formRow').find('.available li[data-value="'+val+'"]').removeClass('searchvisible searchhidden');
	
	var num = $(el).closest('.formRow').find('.selectassign option:selected').length;
	$(el).closest('.formRow').find('.counter').html(num);
	assignSync(el);
}

function assignSearch(el){
	var searchTerm = $(el).closest('.formRow').find('.searchText').val().toLowerCase();
	var searchTermAdd = 0;
	if($(el).closest('.formRow').find('.searchSelect').length > 0) searchTermAdd = $(el).closest('.formRow').find('.searchSelect option:selected').val().toLowerCase();
	if(searchTerm.length < 1 && searchTermAdd == 0){
		$(el).closest('.formRow').find('ul.available li.listassignvisible').removeClass('searchhidden');
		$(el).closest('.formRow').find('ul.available li.listassignvisible').addClass('searchvisible');
	}else{
		var cond1 = '';
		if(searchTerm.length > 0) cond1 = '[data-search*="' + searchTerm + '"]';
		var cond2 = '';
		if(searchTermAdd != 0) cond2 = '[data-search*="' + searchTermAdd + '"]';
		
		
		$(el).closest('.formRow').find('ul.available li.listassignvisible:not(' + cond1 + cond2 + ')').addClass('searchhidden');
		$(el).closest('.formRow').find('ul.available li.listassignvisible:not(' + cond1 + cond2 + ')').removeClass('searchvisible');
		$(el).closest('.formRow').find('ul.available li.listassignvisible' + cond1 + cond2 + '').removeClass('searchhidden');
		$(el).closest('.formRow').find('ul.available li.listassignvisible' + cond1 + cond2 + '').addClass('searchvisible');
	}
}

function assignSync(el){
	$(el).closest('.formRow').find('.selectassign[data-sync]').each(function(){
		var field = $(this).attr('data-sync');
		var aAssigned = [];
		
		if(field != ''){
			$(el).closest('.formRow').find('.selectassign[data-sync="' + field + '"] option:selected').each(function(){
				var val = $(this).val();
				aAssigned.push(val);
			});
			$('#' + field).val(aAssigned.toString());
		}
	});
}

