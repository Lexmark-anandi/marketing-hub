(function () {
	var deactivateCompbox = 1;
	 
    window['f_##modul_name##']['cbGridComplete'] = function (obj) { 
		var objCookie = Cookies.getJSON('deeplink');
		if(objCookie != undefined){
			if(objCookie['function'] != undefined && objCookie['function'] != ''){
				obj.id_data = objCookie.data
				window['f_##modul_name##'][objCookie['function']](obj, '');
			}
	
			var objChange = {};
			objChange['data'] = '';
			objChange['function'] = '';
			changeCookie('deeplink', objChange);
		}
		
		
		
	};

	// ##################################################### 
	// New Form
	// #####################################################
    window['f_##modul_name##']['rowAdd'] = function (obj, el) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		obj.id_data = 0;
		obj.id_data_parent = 0;
		obj.splitForm = 0;
		obj.cb_loadForm = window['f_##modul_name##']['setCountryFilterOff'];
		$('#' + objModul.gridTable + ' tr.selectedDataset').removeClass('selectedDataset'); 
		loadForm(obj, el);
	};

	// #####################################################
	// Edit Form
	// #####################################################
    window['f_##modul_name##']['rowEdit'] = function (obj, el) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		obj.id_data_parent = 0;
		obj.cb_loadForm = window['f_##modul_name##']['setCountryFilterOff'];
		$('#' + objModul.gridTable + ' tr.selectedDataset').removeClass('selectedDataset'); 
		$('#' + objModul.gridTable + ' tr[id="' + obj.id_data + '"]').addClass('selectedDataset');
		loadForm(obj, el);
	};


    window['f_##modul_name##']['setCountryFilterOff'] = function (obj) { 
		$('#form_' + obj.modulpath + ' .filterFormCountry').parent('div').addClass('wFormFilterDisabled');
		$('#form_' + obj.modulpath + ' .filterFormCountry').readonly(true);

		$('#form_' + obj.modulpath + ' .filterFormLanguage').parent('div').addClass('wFormFilterDisabled');
		$('#form_' + obj.modulpath + ' .filterFormLanguage').readonly(true);
	};


    window['f_##modul_name##']['goPreviousStep'] = function (obj) {
		window['f_##modul_name##']['deactivatePlaceholder'](obj);
		
		var actStep = $('#modul_' + obj.modulpath + ' .formLeft .fieldsetActive').attr('data-step') * 1;
		var newStep = actStep - 1;

		if($('#modul_' + obj.modulpath + ' .formLeft .fieldset[data-step="' + newStep + '"]').length > 0){ 
			if(newStep == 4){
				$('.childmodul').css('top', $('.modul .formLeft .formTabs').outerHeight(true) + 'px');
				$('.modul .formRight .formTabs').css('display', 'none');
				$('.modul .formRight').css('bottom', $('.modul .formLeft .formFooter').outerHeight(true) + 'px');
				$('.formTemplates').removeClass('formFullHide');
			}else{
				$('.formTemplates').addClass('formFullHide');
			}
	
			if($('#form_' + obj.modulpath + ' .formLeft .rowError').length == 0){
				window['f_##modul_name##']['sendForm'](obj, 'save');

				(newStep < 2) ? $('#form_' + obj.modulpath + ' .formLeft .previousStep').addClass('buttonHide') : $('#form_' + obj.modulpath + ' .formLeft .previousStep').removeClass('buttonHide');
				(newStep == $('#modul_' + obj.modulpath + ' .formLeft .fieldset[data-step]').length) ? $('#form_' + obj.modulpath + ' .formLeft .nextStep').addClass('buttonHide') : $('#form_' + obj.modulpath + ' .formLeft .nextStep').removeClass('buttonHide');
		
				$('#modul_' + obj.modulpath + ' .formLeft .fieldset').removeClass('fieldsetActive');
				$('#modul_' + obj.modulpath + ' .formLeft .fieldset[data-step="' + newStep + '"]').addClass('fieldsetActive');
				
				var formtab = $('#modul_' + obj.modulpath + ' .formLeft .fieldsetActive').attr('data-formtab');
				$('#form_' + obj.modulpath + ' .formLeft li[data-formtab]').removeClass('active');
				$('#form_' + obj.modulpath + ' .formLeft li[data-formtab="' + formtab + '"]').addClass('active');
				$('#form_' + obj.modulpath + ' .formLeft li[data-formtab]').css('display', 'none');
				$('#form_' + obj.modulpath + ' .formLeft li[data-formtab].active').css('display', '');
			}
		}
	}; 


    window['f_##modul_name##']['goNextStep'] = function (obj) {
		window['f_##modul_name##']['deactivatePlaceholder'](obj);

		var actStep = $('#modul_' + obj.modulpath + ' .formLeft .fieldsetActive').attr('data-step') * 1;
		var newStep = actStep + 1;
		

		if($('#modul_' + obj.modulpath + ' .formLeft .fieldset[data-step="' + newStep + '"]').length > 0){ 
			$('#modul_' + obj.modulpath + ' .formLeft .templatePublish').html('&nbsp;');
			
			// ##################################
			// check form
			clearErrors(obj);
			
			if(actStep == 1){
				checkRequired(obj, '.formpromotions .textfieldTitle');
			}
			// ##################################

			if(newStep == 4){
				$('.childmodul').css('top', $('.modul .formLeft .formTabs').outerHeight(true) + 'px');
				$('.modul .formRight .formTabs').css('display', 'none');
				$('.modul .formRight').css('bottom', $('.modul .formLeft .formFooter').outerHeight(true) + 'px');
				$('.formTemplates').removeClass('formFullHide');
			}else{
				$('.formTemplates').addClass('formFullHide');
			}
			
			if($('#form_' + obj.modulpath + ' .formLeft .rowError').length == 0){
				window['f_##modul_name##']['sendForm'](obj, 'save');
			
				(newStep == 1) ? $('#form_' + obj.modulpath + ' .formLeft .previousStep').addClass('buttonHide') : $('#form_' + obj.modulpath + ' .formLeft .previousStep').removeClass('buttonHide');
				(newStep >= $('#modul_' + obj.modulpath + ' .formLeft .fieldset[data-step]').length) ? $('#form_' + obj.modulpath + ' .formLeft .nextStep').addClass('buttonHide') : $('#form_' + obj.modulpath + ' .formLeft .nextStep').removeClass('buttonHide');
		
				$('#modul_' + obj.modulpath + ' .formLeft .fieldset').removeClass('fieldsetActive');
				$('#modul_' + obj.modulpath + ' .formLeft .fieldset[data-step="' + newStep + '"]').addClass('fieldsetActive');
				
				var formtab = $('#modul_' + obj.modulpath + ' .formLeft .fieldsetActive').attr('data-formtab');
				$('#form_' + obj.modulpath + ' .formLeft li[data-formtab]').removeClass('active');
				$('#form_' + obj.modulpath + ' .formLeft li[data-formtab="' + formtab + '"]').addClass('active');
				$('#form_' + obj.modulpath + ' .formLeft li[data-formtab]').css('display', 'none');
				$('#form_' + obj.modulpath + ' .formLeft li[data-formtab].active').css('display', '');
			}
		}
	}; 
		
	
    window['f_##modul_name##']['cbLoadFormSuccess'] = function (obj) { 
		$('#form_' + obj.modulpath + ' .formLeft li[data-formtab]').css('display', 'none');
		$('#form_' + obj.modulpath + ' .formLeft li[data-formtab].active').css('display', '');
		
		var actStep = $('#modul_' + obj.modulpath + ' .formLeft .fieldsetActive').attr('data-step') * 1;
		(actStep == 1) ? $('#form_' + obj.modulpath + ' .formLeft .previousStep').addClass('buttonHide') : $('#form_' + obj.modulpath + ' .formLeft .previousStep').removeClass('buttonHide');

		$('#form_' + obj.modulpath + ' .formLeft .previousStep').off('click');
		$('#form_' + obj.modulpath + ' .formLeft .previousStep').on('click', function(){
			window['f_##modul_name##'].goPreviousStep(obj);
		});
		
		$('#form_' + obj.modulpath + ' .formLeft .nextStep').off('click');
		$('#form_' + obj.modulpath + ' .formLeft .nextStep').on('click', function(){
			window['f_##modul_name##'].goNextStep(obj);
		});

		$('#form_' + obj.modulpath + ' .formLeft .checkfieldselectall').off('click');
		$('#form_' + obj.modulpath + ' .formLeft .checkfieldselectall').on('click', function(){
			window['f_##modul_name##'].selectCountriesByAll(obj, this);
		});

		$('#form_' + obj.modulpath + ' .formLeft .textfieldPartnersearch').off('keyup');
		$('#form_' + obj.modulpath + ' .formLeft .textfieldPartnersearch').on('keyup', function(){
			window['f_##modul_name##'].setPartnerFilter(obj);
		});

		$('#form_' + obj.modulpath + ' .formLeft .checkBsdonly').off('click');
		$('#form_' + obj.modulpath + ' .formLeft .checkBsdonly').on('click', function(){
			window['f_##modul_name##'].setPartnerFilter(obj);
		});
		
		$('#form_' + obj.modulpath + ' .formLeft .templatePublish').off('click');
		$('#form_' + obj.modulpath + ' .formLeft .templatePublish').on('click', function(){
			var type = $(this).attr('data-type');
			
			var objDialog = {};
			objDialog.el = this;
			if(type == 'translation'){
				objDialog.title = objText.requestTranslation;
				objDialog.formtext = objText.requestTranslationCheckPromo;
			
				objDialog.objButtons = {};
				objDialog.objButtons[objText.Cancel] = function() {closeDialog(obj, this);}            
				objDialog.objButtons[objText.requestTranslation] = function() { window['f_##modul_name##']['requestTranslationDo'](obj, this);}
			}else{
				objDialog.title = objText.TemplatePublish;
				objDialog.formtext = objText.TemplatePublishCheckCampaign;
			
				objDialog.objButtons = {};
				objDialog.objButtons[objText.Cancel] = function() {closeDialog(obj, this);}            
				objDialog.objButtons[objText.TemplatePublish] = function() { window['f_##modul_name##']['templatePublishDo'](obj, this);}
			}
			
			openDialogAlert(obj, objDialog);
		});
		
		initFieldsAssign(obj);
	}; 
		
	
    window['f_##modul_name##']['cbLoadDataSuccess'] = function (obj) {
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		var actStep = $('#modul_' + obj.modulpath + ' .formLeft .fieldsetActive').attr('data-step') * 1;

		window['f_##modul_name##'].setCountryFilterOff(obj); 
		
		$('.booleanfield[value="0"]').each(function(){
			if($(this).prop('checked') == true){
				var v = $(this).parent().find('.valuedefault').text();
				v = v.replace('(','');
				v = v.replace(')','');
				if(v == objText.yes) $(this).parents('.formField').find('.booleanfield[value="1"]').prop('checked', true);
				if(v == objText.no) $(this).parents('.formField').find('.booleanfield[value="2"]').prop('checked', true);
			}
		});
		
		var countries = $.map($('input[name="country[]"]:checked'), function(e,i) {
			return +e.value;
		});
		
		if(countries.length > 0){
//			var data = 'countries=' + countries;
//			
//			$.ajax({  
//				url: objSystem.directoryInstallation + objSystem.pathFormsAdmin + 'fo-campaigns-partner.php', 
//				data: data,    
//				type: 'post',          
//				cache: false,  
//				headers: {
//					csrfToken: Cookies.get('csrf'), 
//					page: JSON.stringify(obj),
//					settings: JSON.stringify(objModul.activeSettings)
//				},
//				success: function (result, status, jqXHR) {
//					actualizeStatus(result, status);
//					
//					$('[name="partnercompany"]').html(result);
//
//					var objFormData = JSON.parse($('#modul_' + obj.modulpath + ' .formLeft [name="formdata"]').val());
//					$('[name="partnercompany"] option[value="' + objFormData.partnercompany + '"]').prop('selected', true);
//					window['f_##modul_name##'].setPartnerFilter(obj);
//				}
//			});
		}
		
//		if(actStep == 4){
//			window['f_##modul_name##']['buildGridTemplates'](obj);
//		}

		if(actStep == 3){
			resizeFieldsAssign(obj);
		}else{
			$('.formRowSelectAssign .ui-widget-header').css('height', 'auto');
		}
		
		if(actStep == 5){
			var objFormData = JSON.parse($('#modul_' + obj.modulpath + ' .formLeft [name="formdata"]').val());
			window['f_##modul_name##']['checkPublish'](obj);

			$('.overviewTitle').html(objFormData.title);

			var countries = '';
			$('.formRowCountries .countryTableRow').each(function(){
				if($(this).find('.checkfield').prop('checked') == true){
					countries += '<div>' + $(this).find('.countryTableCellCountry').text() + ' / ' + $(this).find('.countryTableCellLanguage').text() + '</div>';
				}
			});
			$('.overviewCountries').html(countries);

			var products = '';
			$('#selectassign_products option:selected').each(function(){
				products += '<div>' + $(this).text() + '</div>';
			});
			$('.overviewProducts').html(products);
			
			var data = 'prom=' + $('#modul_' + obj.modulpath + ' .formLeft [name="id_data"]').val();

			$.ajax({  
				url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-campaigns-templateslist.php', 
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
					
					$('.overviewTemplates').html(result);

				}
			});

		}
	}; 
		
	
    window['f_##modul_name##']['setPartnerFilter'] = function (obj, el) { 
		var searchstr = $('[name="search"]').val().toLowerCase();
		var range = ($('[name="bsdonly"]').prop('checked') == true) ? 'bsd' : '';
		
		$('select[name="partnercompany"] option').removeAttr('disabled').show();
		if(range == 'bsd'){
			$('select[name="partnercompany"] option[data-range!="bsd"]').attr('disabled', 'disabled').hide();
		}
		if(searchstr != ''){
			$('select[name="partnercompany"] option:not([data-identifier*="' + searchstr + '"]').attr('disabled', 'disabled').hide();
		}
		$('select[name="partnercompany"] option[value="0"]').removeAttr('disabled').show();
	}; 
		
	
    window['f_##modul_name##']['deactivatePlaceholder'] = function (obj, el) { 
		if(deactivateCompbox == 1 && $('.compboxOuterActive').length > 0) {
			$('#modul_' + obj.modulpath + ' .formLeft input[name="activeComp"]').val(0);
		
			$('.compboxOuter').removeClass('compboxOuterActive');
			$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldset').removeClass('fieldsetActive');
			$('#modul_' + obj.modulpath + ' .formLeft .formComponentOuter').removeClass('formComponentOuterActive');
		}
	}; 
		
	
    window['f_##modul_name##']['selectCountriesByAll'] = function (obj, el) { 
		$('#form_' + obj.modulpath + ' .formLeft .countryTableRow .checkfieldselectgeo').prop('checked', $(el).prop('checked'));
		$('#form_' + obj.modulpath + ' .formLeft .countryTableRow .checkfield').prop('checked', $(el).prop('checked'));
	}; 
		
	
    window['f_##modul_name##']['checkPublish'] = function (obj) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		var trans = 0;

		if(objUser.right != 4){
			var objData = JSON.parse($('#form_' + obj.modulpath + ' .formLeft .field_formdata').val());
			if(objData.title_transrequired == 1 || objData.title_transrequired_default == objText.yes) trans = 1;
			
			var data = 'promo=' + objData.id_campid;
			
			$.ajax({  
				url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-campaigns-trans-check.php', 
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
					
					if(result == 1) trans = 1;
				}
			});
		}
		
		if(trans == 1){
			$('#modul_' + obj.modulpath + ' .formLeft .templatePublish').attr('data-type', 'translation');
			$('#modul_' + obj.modulpath + ' .formLeft .templatePublish').html(objText.requestTranslation);
		}else{
			$('#modul_' + obj.modulpath + ' .formLeft .templatePublish').attr('data-type', 'publish');
			$('#modul_' + obj.modulpath + ' .formLeft .templatePublish').html(objText.TemplatePublish);
		}
	}; 
		
	
    window['f_##modul_name##']['templatePublishDo'] = function (obj, el) { 
		waiting('.ui-dialog');
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

		var data = '';
		
		$.ajax({  
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-campaigns-publish.php', 
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

				reloadGrid(obj);
				closeDialog(obj);
				unwaiting('.ui-dialog');
				cancelForm(obj);

				$.ajax({  
					url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-campaigns-preview-create.php', 
					data: data,    
					type: 'post',          
					cache: false,  
					headers: {
						csrfToken: Cookies.get('csrf'),
						page: JSON.stringify(obj),
						settings: JSON.stringify(objModul.activeSettings)
					},
					success: function (result, status, jqXHR) {
					}
				});
			}
		});
	}; 
		
	
    window['f_##modul_name##']['requestTranslationDo'] = function (obj, el) { 
		waiting('.ui-dialog');
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

		var data = '';
		
		$.ajax({  
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-campaigns-transrequest.php', 
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

				reloadGrid(obj);
				closeDialog(obj)
				unwaiting('.ui-dialog');
				cancelForm(obj);
			}
		});
	}; 



})();




