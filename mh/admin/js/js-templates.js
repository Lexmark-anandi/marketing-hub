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


		
	
    window['f_##modul_name##']['cbModulResize'] = function (obj) { 
		window['f_##modul_name##']['resizeComponentsPage'](obj);
		
		$('#modul_' + obj.modulpath + ' .formLeft .compboxOuter').each(function(){
			var id_tpeid = $(this).attr('data-tpeid');
			var compPageId = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-pageid');
			var val = objComponents.pages['page_' + compPageId]['compboxOuter_' + id_tpeid]['fontsize'];
			
			$(this).css('font-size', (val * componentFactor) + 'pt');

		});
	}; 
		
	
    window['f_##modul_name##']['cbLoadFormSuccess'] = function (obj) { 
		$('#form_' + obj.modulpath + ' .formLeft li[data-formtab]').css('display', 'none');
		$('#form_' + obj.modulpath + ' .formLeft li[data-formtab].active').css('display', '');
		
		var actStep = $('#modul_' + obj.modulpath + ' .formLeft .fieldsetActive').attr('data-step') * 1;
		(actStep == 1) ? $('#form_' + obj.modulpath + ' .formLeft .previousStep').addClass('buttonHide') : $('#form_' + obj.modulpath + ' .formLeft .previousStep').removeClass('buttonHide');
		
		if(obj.id_data > 0){
			$('#modul_' + obj.modulpath + ' .formLeft .selectContentselect').closest('.formRow').addClass('formRowReadonly');
			$('#form_' + obj.modulpath + ' .formLeft .selectContentselect').readonly(true);
			$('#modul_' + obj.modulpath + ' .formLeft .radioContentselect').closest('.formRow').addClass('formRowReadonly');
			$('#form_' + obj.modulpath + ' .formLeft .radioContentselect').readonly(true);
		}

		$('#form_' + obj.modulpath + ' .formLeft .previousStep').off('click');
		$('#form_' + obj.modulpath + ' .formLeft .previousStep').on('click', function(){
			window['f_##modul_name##'].goPreviousStep(obj);
		});
		
		$('#form_' + obj.modulpath + ' .formLeft .nextStep').off('click');
		$('#form_' + obj.modulpath + ' .formLeft .nextStep').on('click', function(){
			window['f_##modul_name##'].goNextStep(obj);
		});
		
		$('#form_' + obj.modulpath + ' .formLeft .selectContentselect').off('change');
		$('#form_' + obj.modulpath + ' .formLeft .selectContentselect').on('change', function(){
			window['f_##modul_name##'].checkContentSpecsheet(obj);
		});
		
		
		$('#form_' + obj.modulpath + ' .formLeft .formButtonBannerAdd').off('click');
		$('#form_' + obj.modulpath + ' .formLeft .formButtonBannerAdd').on('click', function(){
			window['f_##modul_name##']['checkAddBanner'](obj);
		});
		
		$('#form_' + obj.modulpath + ' .formLeft .formButtonBannerEdit').off('click');
		$('#form_' + obj.modulpath + ' .formLeft .formButtonBannerEdit').on('click', function(){
			window['f_##modul_name##']['checkEditBanner'](obj, this);
		});
		
		$('#form_' + obj.modulpath + ' .formLeft .formButtonBannerCancel').off('click');
		$('#form_' + obj.modulpath + ' .formLeft .formButtonBannerCancel').on('click', function(){
			window['f_##modul_name##']['cancelEditBanner'](obj, this);
		});
	
	
	
	
	
	
	
	
	
	
	
	
////		$('#modul_' + obj.modulpath + ' .formLeft .formLeftInner').css('display', 'none');


//		$('#form_' + obj.modulpath + ' .formLeft #kiado_code_' + obj.modulpath + '').off('input');
//		$('#form_' + obj.modulpath + ' .formLeft #kiado_code_' + obj.modulpath + '').on('input', function(){
//			clearTimeout($.data(this, 'timer'));
//			var wait = setTimeout(function(){window['f_##modul_name##'].loadCountriesKiado(obj)}, 1000);
//			$(this).data('timer', wait);			
//		});

////		$('#form_' + obj.modulpath + ' .formLeft li[data-formtab="countries"]').off('mousedown');
////		$('#form_' + obj.modulpath + ' .formLeft li[data-formtab="countries"]').on('mousedown', function(){
////			window['f_##modul_name##'].switchCountriesTab(obj);
////			window['f_##modul_name##'].loadCountriesKiado(obj);
////		});

		$('#form_' + obj.modulpath + ' .formLeft .checkfieldselectall').off('click');
		$('#form_' + obj.modulpath + ' .formLeft .checkfieldselectall').on('click', function(){
			window['f_##modul_name##'].selectCountriesByAll(obj, this);
		});

		$('#form_' + obj.modulpath + ' .formLeft .checkfieldselectgeo').off('click');
		$('#form_' + obj.modulpath + ' .formLeft .checkfieldselectgeo').on('click', function(){
			window['f_##modul_name##'].selectCountriesByGeo(obj, this);
		});
		
		
		
		
		
		
		$('#form_' + obj.modulpath + ' .formLeft .templatePublish').off('click');
		$('#form_' + obj.modulpath + ' .formLeft .templatePublish').on('click', function(){
			var type = $(this).attr('data-type');
			
			var objDialog = {};
			objDialog.el = this;
			if(type == 'translation'){
				objDialog.title = objText.requestTranslation;
				objDialog.formtext = objText.requestTranslationCheck;
			
				objDialog.objButtons = {};
				objDialog.objButtons[objText.Cancel] = function() {closeDialog(obj, this);}            
				objDialog.objButtons[objText.requestTranslation] = function() { window['f_##modul_name##']['requestTranslationDo'](obj, this);}
			}else{
				objDialog.title = objText.TemplatePublish;
				objDialog.formtext = objText.TemplatePublishCheck;
			
				objDialog.objButtons = {};
				objDialog.objButtons[objText.Cancel] = function() {closeDialog(obj, this);}            
				objDialog.objButtons[objText.TemplatePublish] = function() { window['f_##modul_name##']['templatePublishDo'](obj, this);}
			}
			
			openDialogAlert(obj, objDialog);
		});
		
		window['f_##modul_name##']['initFormComponents'](obj);
	}; 
		
	
    window['f_##modul_name##']['cbLoadDataSuccess'] = function (obj) {
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

		$('#form_' + obj.modulpath + ' .formLeft [name="caid"]').val($('select[name="id_caid"] option:selected').val());
		$('#form_' + obj.modulpath + ' .formLeft [name="contentselection"]').val($('input[name="contentselect"]:checked').val());
		
		var actStep = $('#modul_' + obj.modulpath + ' .formLeft .fieldsetActive').attr('data-step') * 1;

		window['f_##modul_name##'].setCountryFilterOff(obj); 
		window['f_##modul_name##'].checkContentSpecsheet(obj);
		window['f_##modul_name##'].switchCountriesTab(obj);
		
		$('.booleanfield[value="0"]').each(function(){
			if($(this).prop('checked') == true){
				var v = $(this).parent().find('.valuedefault').text();
				v = v.replace('(','');
				v = v.replace(')','');
				if(v == objText.yes) $(this).parents('.formField').find('.booleanfield[value="1"]').prop('checked', true);
				if(v == objText.no) $(this).parents('.formField').find('.booleanfield[value="2"]').prop('checked', true);
			}
		});
		
		var selSrc = $('#form_' + obj.modulpath + ' .formLeft .radioContentselect:checked').val();
		
		if(actStep == 2){
			$('.fileupload').fileupload('destroy');
			initFieldsUpload(obj);
		}
		if(actStep == 3) window['f_##modul_name##'].loadCountriesKiado(obj);
		
		$('.overviewCountries').html('');
		if(actStep == 5){
			var objFormData = JSON.parse($('#modul_' + obj.modulpath + ' .formLeft [name="formdata"]').val());
			$('.overviewTitle').html(objFormData.title);
			
			var countries = '';
			var data = '';
			
			if(selSrc == 'kiado'){
				$.ajax({  
					url: objSystem.directoryInstallation + objSystem.pathFormsAdmin + 'fo-templates-step5-countries.php', 
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
						
						var objCount = JSON.parse(result);
						countries = objCount.list;
						$('.overviewCountries').html(countries);
					}
				});
			}else{
				$('.formRowCountriesDefault .countryTableRow').each(function(){
					if($(this).find('.checkfield').prop('checked') == true){
						countries += '<div>' + $(this).find('.countryTableCellCountry').text() + ' / ' + $(this).find('.countryTableCellLanguage').text() + '</div>';
					}
				});
				$('.overviewCountries').html(countries);
			}
		}

//		$('#form_' + obj.modulpath + ' .formLeft .configComponentSave').off('click');
//		$('#form_' + obj.modulpath + ' .formLeft .configComponentSave').addClass('buttonDeactivate');
//		if($('#form_' + obj.modulpath + ' .formLeft input[name="caid"]').val() != 1){
//			$('#form_' + obj.modulpath + ' .formLeft .configComponentSave').removeClass('buttonDeactivate');
//			$('#form_' + obj.modulpath + ' .formLeft .configComponentSave').on('click', function(){
//				var objDialog = {};
//				objDialog.urlForm = objSystem.directoryInstallation + objSystem.pathFormsAdmin + 'fo-templates-configuration-save.php';
//				objDialog.el = this;
//				objDialog.title = objText.ConfigurationSave;
//				objDialog.template = $('#form_' + obj.modulpath + ' .formLeft input[name="title"').val();
//				
//				objDialog.objButtons = {};
//				objDialog.objButtons[objText.Cancel] = function() { closeDialog(objDialog, this); }            
//				objDialog.objButtons[objText.ConfigurationSave] = function() { window['f_##modul_name##']['configComponentSaveDo'](obj, this) }
//				
//				openDialogForm(obj, objDialog);
//			});
//		}
//		
//		$('#form_' + obj.modulpath + ' .formLeft .configComponentLoad').off('click');
//		$('#form_' + obj.modulpath + ' .formLeft .configComponentLoad').addClass('buttonDeactivate');
//		if($('#form_' + obj.modulpath + ' .formLeft input[name="caid"]').val() != 1){
//			$('#form_' + obj.modulpath + ' .formLeft .configComponentLoad').removeClass('buttonDeactivate');
//			$('#form_' + obj.modulpath + ' .formLeft .configComponentLoad').on('click', function(){
//				var objDialog = {};
//				objDialog.urlForm = objSystem.directoryInstallation + objSystem.pathFormsAdmin + 'fo-templates-configuration-load.php';
//				objDialog.el = this;
//				objDialog.title = objText.ConfigurationLoad;
//				objDialog.caid = $('#modul_' + obj.modulpath + ' .formLeft input[name="caid"]').val();
//				
//				objDialog.objButtons = {};
//				objDialog.objButtons[objText.Cancel] = function() { closeDialog(objDialog, this); }            
//				objDialog.objButtons[objText.ConfigurationLoad] = function() { window['f_##modul_name##']['configComponentLoadDo'](obj, this) }
//				
//				openDialogForm(obj, objDialog);
//			});
//		}
		
		var objData = JSON.parse($('#form_' + obj.modulpath + ' .formLeft .field_formdata').val());
		$('#form_' + obj.modulpath + ' .formLeft .formTemplateCategory').css('display', 'none');
		if(objData.id_caid == 1) $('#form_' + obj.modulpath + ' .formLeft #formBanner').css('display', 'block');
		if(objData.id_caid == 2) $('#form_' + obj.modulpath + ' .formLeft #formPrintad').css('display', 'block');
		if(objData.id_caid == 3) $('#form_' + obj.modulpath + ' .formLeft #formEmail').css('display', 'block');
		if(objData.id_caid == 4){
			$('#form_' + obj.modulpath + ' .formLeft .formRowSubBrochure').css('display', 'none');
			$('#form_' + obj.modulpath + ' .formLeft .formRowSubSpecsheet').css('display', 'block');
			$('#form_' + obj.modulpath + ' .formLeft #formSpecsheet').css('display', 'block');
		}
		if(objData.id_caid == 5){
			$('#form_' + obj.modulpath + ' .formLeft .formRowSubSpecsheet').css('display', 'none');
			$('#form_' + obj.modulpath + ' .formLeft .formRowSubBrochure').css('display', 'block');
			$('#form_' + obj.modulpath + ' .formLeft #formSpecsheet').css('display', 'block');
			
		}
		if(objData.id_caid == 10) $('#form_' + obj.modulpath + ' .formLeft #formRollup').css('display', 'block');

		$('#form_' + obj.modulpath + ' .formLeft #formSpecsheet .formSpecsheetSourceKiado').css('display', 'none');
		$('#form_' + obj.modulpath + ' .formLeft #formSpecsheet .formSpecsheetSourcePdf').css('display', 'none');
		if(selSrc == 'kiado'){
			$('#form_' + obj.modulpath + ' .formLeft #formSpecsheet .formSpecsheetSourceKiado').css('display', 'block');
		}else{
			$('#form_' + obj.modulpath + ' .formLeft #formSpecsheet .formSpecsheetSourcePdf').css('display', 'block');
		}
		
		if(actStep != 3) window['f_##modul_name##']['loadComponents'](obj);
	};
		

		
	
    window['f_##modul_name##']['switchCountriesTab'] = function (obj) { 
		$('#form_' + obj.modulpath + ' .formLeft .formRowCountriesKiado').css('display', 'none');
		$('#form_' + obj.modulpath + ' .formLeft .formRowCountriesDefault').css('display', 'none');
		$('#form_' + obj.modulpath + ' .formLeft .formRowCountriesKiado input').prop('disabled', true);
		$('#form_' + obj.modulpath + ' .formLeft .formRowCountriesDefault input').prop('disabled', true);

		var selSrc = $('#form_' + obj.modulpath + ' .formLeft input[name="contentselect"]:checked').val();
		if(selSrc == 'kiado'){
			$('#form_' + obj.modulpath + ' .formLeft .formRowCountriesKiado').css('display', 'block');
			$('#form_' + obj.modulpath + ' .formLeft .formRowCountriesKiado input').prop('disabled', false);
		}else{
			$('#form_' + obj.modulpath + ' .formLeft .formRowCountriesDefault').css('display', 'block');
			$('#form_' + obj.modulpath + ' .formLeft .formRowCountriesDefault input').prop('disabled', false);
		}
	};
		
	
    window['f_##modul_name##']['loadCountriesKiado'] = function (obj) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		waiting('#modul_' + obj.modulpath);
		
		$('#form_' + obj.modulpath + ' .formLeft .formRowCountriesKiado .countryTableRow .countryTableCellPreview').html('');
		$('#form_' + obj.modulpath + ' .formLeft .formRowCountriesKiado .countryTableRow .countryTableCellMaster').html('');
		
		var kiado = $('#form_' + obj.modulpath + ' .formLeft #kiado_code_' + obj.modulpath + '').val();
		
		if(kiado != ''){
			var data = 'tempid=' + obj.id_data;
			data += '&kiado=' + kiado;
			data += '&targetpath=specsheets';
			data += '&orgfieldname=specsheet_original';
			data += '&multiple=';
			
			// load master file Kiado
			$.ajax({  
				url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-kiado.php', 
				data: data,    
				type: 'post',          
				cache: false,  
				beforeSend: function(){
					waiting('#modul_' + obj.modulpath)
				},
				headers: {
					csrfToken: Cookies.get('csrf'),
					page: JSON.stringify(obj),
					settings: JSON.stringify(objModul.activeSettings)
				},
				success: function (result, status, jqXHR) {
					actualizeStatus(result, status);

					var numKiado = $('#form_' + obj.modulpath + ' .formLeft .formRowCountriesKiado .countryTableRow').length;
					var countKiado = 0;

					// load local version of Kiado
					$('#form_' + obj.modulpath + ' .formLeft .formRowCountriesKiado .countryTableRow').each(function(){
						
						var count2lang = $(this).attr('data-count2lang');
						var dataL = data;
						dataL += '&id_kcid=' + result;
						dataL += '&count2lang=' + count2lang;
						
						$.ajax({  
							url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-countries-kiado.php', 
							data: dataL,    
							type: 'post',          
							cache: false,  
							beforeSend: function(){
								waiting('#modul_' + obj.modulpath)
							},
							headers: {
								csrfToken: Cookies.get('csrf'),
								page: JSON.stringify(obj),
								settings: JSON.stringify(objModul.activeSettings)
							},
							success: function (result, status, jqXHR) {
								actualizeStatus(result, status);
								countKiado++;
		
								var objRes = JSON.parse(result);
		
								$('#form_' + obj.modulpath + ' .formLeft .formRowCountriesKiado .countryTableRow[data-count2lang="' + count2lang + '"] .countryTableCellPages').html(objRes.pages);
		
								if(objRes.preview == ''){
									$('#form_' + obj.modulpath + ' .formLeft .formRowCountriesKiado .countryTableRow[data-count2lang="' + count2lang + '"] .countryTableCellPreview').html('<span class="countryTableMaster">' + objText.na + '</span>');
								}else{
									$('#form_' + obj.modulpath + ' .formLeft .formRowCountriesKiado .countryTableRow[data-count2lang="' + count2lang + '"] .countryTableCellPreview').html('<a href="' + objRes.preview + '" target="_blank">' + objText.preview + '</a>');
								}
								
								if(objRes.master == 1){
									$('#form_' + obj.modulpath + ' .formLeft .formRowCountriesKiado .countryTableRow[data-count2lang="' + count2lang + '"] .countryTableCellMaster').html(objText.master);
								}else{
									$('#form_' + obj.modulpath + ' .formLeft .formRowCountriesKiado .countryTableRow[data-count2lang="' + count2lang + '"] .countryTableCellMaster').html('');
								}
								
								
								// check for all countries/languages (for restricted all access)
								if(countKiado == numKiado){
									unwaiting();
									
									$.ajax({  
										url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-kiado-local.php', 
										data: dataL,    
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
							}
						});
					});
				}
			});
		}else{
			unwaiting();
		}
	};
		

    window['f_##modul_name##']['checkContentSpecsheet'] = function (obj) { 
		var selSrc = $('#form_' + obj.modulpath + ' .formLeft .selectContentselect option:selected').val();
		$('#form_' + obj.modulpath + ' .formLeft .formRowContentSource').css('display', 'none');
		
		if(selSrc == 4 || selSrc == 5){
			$('#form_' + obj.modulpath + ' .formLeft .formRowContentSource').css('display', 'block');
			
			if($('#form_' + obj.modulpath + ' .formLeft .radioContentselect:checked').length == 0){
				$('#form_' + obj.modulpath + ' .formLeft .radioContentselect[value="kiado"]').prop('checked', true);
			}
		}else{
			$('#form_' + obj.modulpath + ' .formLeft .radioContentselect').prop('checked', false);
		}
	};
		

    window['f_##modul_name##']['cbSaveDataSubmitSuccess'] = function (obj) { 
		var actStep = $('#modul_' + obj.modulpath + ' .formLeft .fieldsetActive').attr('data-step') * 1;
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		window['f_##modul_name##'].setCountryFilterOff(obj); 
		delete(obj.cbSendFiles);

		var data = 'tempid=' + obj.id_data;
		data += '&targetpath=specsheets';
		data += '&orgfieldname=specsheet_original';
		data += '&multiple=';

		if(actStep == 3){
//			if($('#modul_' + obj.modulpath + ' .formLeft input[name="contentselect"]:checked').val() == 'kiado'){
//				for(var i = 50; i < 1000; i += 150){
//					window.setTimeout(function(){waiting('#modul_' + obj.modulpath)}, i);
//				}
//				
//				$.ajax({  
//					url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-kiado.php', 
//					data: data,    
//					type: 'post',          
//					cache: false,  
//					headers: {
//						csrfToken: Cookies.get('csrf'),
//						page: JSON.stringify(obj),
//						settings: JSON.stringify(objModul.activeSettings)
//					},
//					beforeSend: function(){
//						//waiting('#modul_' + obj.modulpath)
//					},
//					success: function (result, status, jqXHR) {
//						actualizeStatus(result, status);
//						
//						waiting('#modul_' + obj.modulpath);
//						
//						$.ajax({  
//							url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-kiado-local.php', 
//							data: data,    
//							type: 'post',          
//							cache: false,  
//							headers: {
//								csrfToken: Cookies.get('csrf'),
//								page: JSON.stringify(obj),
//								settings: JSON.stringify(objModul.activeSettings)
//							},
//							success: function (result, status, jqXHR) {
//								actualizeStatus(result, status);
//		
//								window['f_##modul_name##']['loadComponents'](obj);
//								
//								for(var i = 50; i < 1000; i += 100){
//									window.setTimeout(function(){unwaiting()}, i);
//								}
//								unwaiting();
//							}
//						});
//					}
//				});
//			}
		}else{
			unwaiting();
		}
	}; 
		
	
    window['f_##modul_name##']['initFormComponents'] = function (obj) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		
		var hTab = $('#modul_' + obj.modulpath + ' .formLeft .formTabs').outerHeight();
		var hFooter = $('#modul_' + obj.modulpath + ' .formLeft .formFooter').outerHeight();
	
////		$('#modul_' + obj.modulpath + ' .formLeft .formComponentsTop').css('top', (hTab - 1) + 'px');
////		$('#modul_' + obj.modulpath + ' .formLeft .formComponentsTop').css('bottom', hFooter + 'px');
////		$('#modul_' + obj.modulpath + ' .formLeft .formComponentsBottom').css('height', hFooter + 'px');
	
////		$('#modul_112-0-11-components').off('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend');
////		$('#modul_112-0-11-components').on('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function () {
////			countAnimation++;
////			if(countAnimation == 2){
////				$('#modul_112-0-11-components .childmodulUnvisible').removeClass('childmodulUnvisible');
////				$('#modul_112-0-11-components .childmodulOpenBG').removeClass('childmodulOpenBG');
////				window['f_##modul_name##']['cbModulResize'](obj);
////			}
////		});
		
		objComponents = {};
		objComponents.id_temp = '';
		objComponents.pages = {};
	}; 
		
	
    window['f_##modul_name##']['loadComponents'] = function (obj) { 
		waiting('#modul_' + obj.modulpath);
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		var data = '';

		if(objComponents.id_temp != obj.id_data){
			objComponents = {};
			objComponents.id_temp = obj.id_data;
			objComponents.pages = {};

			$.ajax({  
				url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-components-read.php', 
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

					var objResult = JSON.parse(result);
					
					objConfigComponents = objResult.aComponents;
					objComponentsTextmoduls = objResult.aComponentsTextmoduls;
					
					if(objResult.preview == ''){
//						$('#modul_' + obj.modulpath + ' .formLeft .formLeftInner').css('display', 'none');
//						$('#modul_' + obj.modulpath + ' .formMiddle').addClass('formMiddleDeactive');
//						$('#modul_' + obj.modulpath + ' .formMiddle').off('click');
					}else{
//						$('#modul_' + obj.modulpath + ' .formLeft .formLeftInner').css('display', '');
//						$('#modul_' + obj.modulpath + ' .formMiddle').removeClass('formMiddleDeactive');
//
//						$('#modul_' + obj.modulpath + ' .formMiddle').off('click');
//						$('#modul_' + obj.modulpath + ' .formMiddle').on('click', function(){
//							 window['f_##modul_name##']['childmodulWideOpen'](obj);
//						});
//						
						var thumbnails = '';
						for(var group in objResult.thumbnails){
							thumbnails += '<div class="formComponentThumbgroup" data-group="' + group + '">';
							if(group != 'na') thumbnails += '<div class="formComponentThumbgroupHead">' + group + '</div>';
							
							for(var page in objResult.thumbnails[group]){
								thumbnails += '<div class="formComponentThumbOuter" data-page="' + page + '" data-bfid="' + objResult.thumbnails[group][page].bfid + '" data-tp="' + objResult.thumbnails[group][page].tp + '" data-pageid="' + objResult.thumbnails[group][page].pageid + '"><div class="formComponentThumb"><img src="' + objResult.thumbnails[group][page].src + '"></div><div class="formComponentPage">' + objResult.thumbnails[group][page].pagelabel + '</div></div>';
							}
							
							thumbnails += '</div>';
						}
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsThumbnails').html(thumbnails);
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsThumbnails .formComponentThumbOuter:first').addClass('formComponentThumbOuterActive');
						
						$('#modul_' + obj.modulpath + ' .formLeft .formBannerformats').html(objResult.bannerformats);
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewBackground').html(objResult.preview);
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsComponents').html(objResult.components);
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm').html(objResult.toolsform);
	
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .colorpicker').spectrum({
							preferredFormat: 'hex',
							showPaletteOnly: true,
							showPalette: true,
							hideAfterPaletteSelect:true,
							palette: [
								['#000000', '#32323c', '#5a5a64', '#a5a5aa', '#c9c9d1', '#e6e6f0', '#eff0f6', '#ffffff'],
								['#006446', '#008945', '#00ad21', '#00c425', '#3af23a'],
								['#1c64b4', '#faa519'],
							],
							change: function(color) {
								window['f_##modul_name##']['changeFormatComponent'](obj, this);
							}
						});
		
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .componentformfield').off('input');
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .componentformfield').on('input', function(){
							window['f_##modul_name##']['changeFormatComponent'](obj, this);
						});
		
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .booleanfield').off('change');
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .booleanfield').on('change', function(){
							window['f_##modul_name##']['changeFormatComponent'](obj, this);
						});
		
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .buttonRemoveComponent').off('click');
						if($('.formLeft input[name="caid"]').val() != 3){
							$('.formComponentsForm .buttonRemoveComponent').css('opacity', '');
							$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .buttonRemoveComponent').on('click', function(){
								window['f_##modul_name##']['checkRemoveComponent'](obj, this);
							});
						}else{
							$('.formComponentsForm .buttonRemoveComponent').css('opacity', '0.5');
						}
		
						initFieldsUpload(obj, '#modul_' + obj.modulpath + ' .formLeft');
						window['f_##modul_name##']['initConfigurationIconPaste'](obj);
								
						$('#form_' + obj.modulpath + ' .formLeft .formButtonCompFileupload').off('click');
						$('#form_' + obj.modulpath + ' .formLeft .formButtonCompFileupload').on('click', function(){
							window['f_##modul_name##']['checkComponentFileupload'](obj, this);
						});


						$('#form_' + obj.modulpath + ' .formLeft textarea.wysiwyg').each(function(){
							var wysiwygName = $(this).attr('name');
							
							var d = new Date();
							var n = d.getTime();

							var objConfig = JSON.parse($(this).attr('data-config'));
							if(objConfig.customConfig == undefined) objConfig.customConfig = objSystem.directoryInstallation + objSystem.pathAdmin + 'config-ckeditor.js?t=' + n;
							if(objConfig.toolbar == undefined) objConfig.toolbar = 'SYS';
							if(objConfig.height == undefined) objConfig.height = $(this).outerHeight(true);
					
							$(this).ckeditor(objConfig);

							for (var i in CKEDITOR.instances) {
								CKEDITOR.instances[i].on('change', function(e) {
									var compPageId = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-pageid');
									var compId = $('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').attr('id');
									var thisHTML = e.editor.getData();
									var outHTML = window['f_##modul_name##']['resizeFontsize'](thisHTML);
									$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive .content').html(outHTML);
									objComponents.pages['page_' + compPageId][compId]['content'] = thisHTML;		
									$('#modul_' + obj.modulpath + ' .formLeft input[name="components"]').val(JSON.stringify(objComponents));
								});
							}	
							// ## fill data if init is to slow ##
							CKEDITOR.instances[$('#modul_' + obj.modulpath + ' .formLeft textarea[name="' + wysiwygName + '"]').attr('id')].on( 'instanceReady', function(evt) {
//								var formData = $('#modul_' + obj.modulpath + ' .formLeft [name="formdata"]').val();
//								if(formData != '' && formData != undefined){
//									objFormData = JSON.parse(formData);
//									CKEDITOR.instances[$('#modul_' + obj.modulpath + ' .formLeft textarea[name="' + wysiwygName + '"]').attr('id')].setData(objFormData[wysiwygName]);
//								}
							} );
						});

						$('#form_' + obj.modulpath + ' .formLeft .configComponentSave').off('click');
						$('#form_' + obj.modulpath + ' .formLeft .configComponentSave').addClass('buttonDeactivate');
						if($('#form_' + obj.modulpath + ' .formLeft input[name="caid"]').val() != 0){
							$('#form_' + obj.modulpath + ' .formLeft .configComponentSave').removeClass('buttonDeactivate');
							$('#form_' + obj.modulpath + ' .formLeft .configComponentSave').on('click', function(){
								var objDialog = {};
								objDialog.urlForm = objSystem.directoryInstallation + objSystem.pathFormsAdmin + 'fo-templates-configuration-save.php';
								objDialog.el = this;
								objDialog.title = objText.ConfigurationSave;
								objDialog.template = $('#form_' + obj.modulpath + ' .formLeft input[name="title"').val();
								var templatebanner = $('.formComponentThumbOuterActive').closest('.formComponentThumbgroup').find('.formComponentThumbgroupHead').text();
								if(templatebanner != '') objDialog.template += '-' + templatebanner;
								objDialog.page = $('.formComponentThumbOuterActive').attr('data-page');
								objDialog.tpid = $('.formComponentThumbOuterActive').attr('data-tp');
								
								objDialog.objButtons = {};
								objDialog.objButtons[objText.Cancel] = function() { closeDialog(objDialog, this); }            
								objDialog.objButtons[objText.ConfigurationSave] = function() { window['f_##modul_name##']['configComponentSaveDo'](obj, this) }
								
								openDialogForm(obj, objDialog);
							});
						}
						
						$('#form_' + obj.modulpath + ' .formLeft .configComponentLoad').off('click');
						$('#form_' + obj.modulpath + ' .formLeft .configComponentLoad').addClass('buttonDeactivate');
						if($('#form_' + obj.modulpath + ' .formLeft input[name="caid"]').val() != 0){
							$('#form_' + obj.modulpath + ' .formLeft .configComponentLoad').removeClass('buttonDeactivate');
							$('#form_' + obj.modulpath + ' .formLeft .configComponentLoad').on('click', function(){
								var objDialog = {};
								objDialog.urlForm = objSystem.directoryInstallation + objSystem.pathFormsAdmin + 'fo-templates-configuration-load.php';
								objDialog.el = this;
								objDialog.title = objText.ConfigurationLoad;
								objDialog.caid = $('#modul_' + obj.modulpath + ' .formLeft input[name="caid"]').val();
								objDialog.page = $('.formComponentThumbOuterActive').attr('data-page');
								objDialog.tpid = $('.formComponentThumbOuterActive').attr('data-tp');
								
								objDialog.objButtons = {};
								objDialog.objButtons[objText.Cancel] = function() { closeDialog(objDialog, this); }            
								objDialog.objButtons[objText.ConfigurationLoad] = function() { window['f_##modul_name##']['configComponentLoadDo'](obj, this) }
								
								openDialogForm(obj, objDialog);
							});
						}
				
						window['f_##modul_name##']['resizeComponentsPage'](obj);
						
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewComponents .compboxOuter').remove();
						var valComp = $('#modul_' + obj.modulpath + ' .formLeft input[name="components"]').val();
						if(valComp != '') objComponents = JSON.parse(valComp);
						window['f_##modul_name##']['loadPageComponents'](obj);
		
						window['f_##modul_name##']['initFormComponentThumbs'](obj);
		
						
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentOuter').off('mouseover');
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentOuter').on('mouseover', function(){
							$('#modul_' + obj.modulpath + ' .formLeft .formComponentOuter').removeClass('formComponentOuterHover');
							$(this).addClass('formComponentOuterHover');
						});
						
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentOuter').off('mouseout');
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentOuter').on('mouseout', function(){
							$(this).removeClass('formComponentOuterHover');
						});
						
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentOuter').off('click');
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentOuter').on('click', function(){
							window['f_##modul_name##']['deactivatePlaceholder'](obj);
	
							$('#modul_' + obj.modulpath + ' .formLeft .formComponentOuter').removeClass('formComponentOuterActive');
							$(this).addClass('formComponentOuterActive');
							
							if($('.formLeft input[name="caid"]').val() != 3){
								window['f_##modul_name##']['createPlaceholder'](obj);
							}
							//window['f_##modul_name##']['loadComponentsPage'](obj);
						});
						
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewComponents').on('click', function(){
							window['f_##modul_name##']['deactivatePlaceholder'](obj, this);
						});
						
						$('#modul_' + obj.modulpath + ' .formLeft .modulIconDelete').off('click');
						$('#modul_' + obj.modulpath + ' .formLeft .modulIconDelete').on('click', function(){
							window['f_##modul_name##']['deleteBannerformat'](obj, this);
						});
						
						$('#modul_' + obj.modulpath + ' .formLeft .modulIconEdit').off('click');
						$('#modul_' + obj.modulpath + ' .formLeft .modulIconEdit').on('click', function(){
							window['f_##modul_name##']['editBannerformat'](obj, this);
						});
					}
					
					unwaiting();
				}
			});
		}else{
			$('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewComponents .compboxOuter').remove();
			var valComp = $('#modul_' + obj.modulpath + ' .formLeft input[name="components"]').val();
			if(valComp != undefined && valComp != '') objComponents = JSON.parse(valComp);
			//window['f_##modul_name##']['loadPageComponents'](obj);
			window['f_##modul_name##']['loadComponentsPage'](obj);
		}
	}; 
		

    window['f_##modul_name##']['initFormComponentThumbs'] = function (obj) { 
		$('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuter').off('mouseover');
		$('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuter').on('mouseover', function(){
			$('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuter').removeClass('formComponentThumbOuterHover');
			$(this).addClass('formComponentThumbOuterHover');
		});
		
		$('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuter').off('mouseout');
		$('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuter').on('mouseout', function(){
			$(this).removeClass('formComponentThumbOuterHover');
		});
		
		$('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuter').off('click');
		$('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuter').on('click', function(){
			$('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuter').removeClass('formComponentThumbOuterActive');
			$(this).addClass('formComponentThumbOuterActive');
			
			window['f_##modul_name##']['loadComponentsPage'](obj);
		});
	}; 


	
    window['f_##modul_name##']['loadComponentsPage'] = function (obj) { 
		waiting('#modul_' + obj.modulpath);

		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		var page = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-page');
		var tp = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-tp');
		var data = 'page=' + page;
		data += '&tp=' + tp;
		
		if(tp == undefined){
			objComponents = {};
			window['f_##modul_name##']['loadComponents'](obj);
		}else{
			$.ajax({  
				url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-componentspage-read.php', 
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
	
					var objResult = JSON.parse(result);
					
					window['f_##modul_name##']['deactivatePlaceholder'](obj);
	
					for(var group in objResult.thumbnails){
						if($('#modul_' + obj.modulpath + ' .formLeft .formComponentsThumbnails .formComponentThumbgroup[data-group="' + group + '"]').length > 0){
							var numPages = 0;
							
							for(var page in objResult.thumbnails[group]){
								numPages++;
							
								if($('#modul_' + obj.modulpath + ' .formLeft .formComponentsThumbnails .formComponentThumbgroup[data-group="' + group + '"] .formComponentThumbOuter[data-page="' + page + '"]').length > 0){
									$('#modul_' + obj.modulpath + ' .formLeft .formComponentsThumbnails .formComponentThumbgroup[data-group="' + group + '"] .formComponentThumbOuter[data-page="' + page + '"] .formComponentThumb img').attr('src', objResult.thumbnails[group][page].src);
								}else{
									$('#modul_' + obj.modulpath + ' .formLeft .formComponentsThumbnails .formComponentThumbgroup[data-group="' + group + '"]').append('<div class="formComponentThumbOuter" data-page="' + page + '" data-tp="' + objResult.thumbnails[group][page].tp + '" data-pageid="' + objResult.thumbnails[group][page].pageid + '"><div class="formComponentThumb"><img src="' + objResult.thumbnails[group][page].src + '"></div><div class="formComponentPage">' + objResult.thumbnails[group][page].pagelabel + '</div></div>');
								}
							}
							$('#modul_' + obj.modulpath + ' .formLeft .formComponentsThumbnails .formComponentThumbgroup[data-group="' + group + '"] .formComponentThumbOuter').each(function(){
								var p = $(this).attr('data-page');
								if(objResult.thumbnails[group][p] == undefined) $(this).remove();
							});
						}else{
							
						}
					}
	
					$('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewBackground').html(objResult.preview);
					$('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewComponents .compboxOuter').remove();
					
					window['f_##modul_name##']['resizeComponentsPage'](obj);
					
					window['f_##modul_name##']['initFormComponentThumbs'](obj);
					window['f_##modul_name##']['loadPageComponents'](obj);

					unwaiting();
				}
			});
		}
	};
		
	
    window['f_##modul_name##']['loadPageComponents'] = function (obj) { 
		waiting('#modul_' + obj.modulpath);

		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		var compPageId = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-pageid');
		
		window['f_##modul_name##']['formatThumbEditable'](obj);


		if($('#modul_' + obj.modulpath + ' .wFormFilter').length == 0){
			$('#modul_' + obj.modulpath + ' .formLeft .booleanfield[value="0"]').parent().css('display', 'none');
		}else{
			if(obj.id_data == 0 || (objModul.activeSettings.formCountry == 0 && objModul.activeSettings.formLanguage == 0 && objModul.activeSettings.formDevice == 0)){
				$('#modul_' + obj.modulpath + ' .formLeft .booleanfield[value="0"]').parent().css('display', 'none');
			}else{
				$('#modul_' + obj.modulpath + ' .formLeft .booleanfield[value="0"]').parent().css('display', '');
			}
		}

		
		if(objComponents.pages['page_' + compPageId] != undefined){
			for(key in objComponents.pages['page_' + compPageId]){
				var content = objComponents.pages['page_' + compPageId][key]['content'];

				// wysiwyg
				if(objComponents.pages['page_' + compPageId][key].id_tcid == 14){
					content =  window['f_##modul_name##']['resizeFontsize'](content);
				}
				// textmodul
				if(objComponents.pages['page_' + compPageId][key].id_tcid == 17){
					content = objComponentsTextmoduls[objComponents.pages['page_' + compPageId][key]['content']]
				}
				
				$('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewComponents').append('<div class="compboxOuter comboxOuter_' + objComponents.pages['page_' + compPageId][key].id_caid + '_' + objComponents.pages['page_' + compPageId][key].id_tcid + '" id="' + key + '" data-tcid="' + objComponents.pages['page_' + compPageId][key].id_tcid + '" data-tpeid="' + objComponents.pages['page_' + compPageId][key].id_tpeid + '"><div class="content">' + content + '</div></div>');

				//add css to generated div and make it resizable & draggable
				switch(objComponents.pages['page_' + compPageId][key]['fontstyle']){
					case('0'):
						$('#' + key).css('font-weight', 'normal');
						$('#' + key).css('font-style', 'normal');
						break;
					
					case('1'):
						$('#' + key).css('font-weight', 'bold');
						$('#' + key).css('font-style', 'normal');
						break;
					
					case('2'):
						$('#' + key).css('font-weight', 'normal');
						$('#' + key).css('font-style', 'italic');
						break;
					
					case('3'):
						$('#' + key).css('font-weight', 'bold');
						$('#' + key).css('font-style', 'italic');
						break;
				};

				if(objComponents.pages['page_' + compPageId][key].id_tcid == 10){
					$('#' + key).removeClass('contactalignleft');
					$('#' + key).removeClass('contactaligncenter');
					$('#' + key).removeClass('contactalignright');
					$('#' + key).addClass('contactalign' + objComponents.pages['page_' + compPageId][key]['alignment']);
				}

				if(objComponents.pages['page_' + compPageId][key].id_tcid == 11 || objComponents.pages['page_' + compPageId][key].id_tcid == 12 || objComponents.pages['page_' + compPageId][key].id_tcid == 15){
					$('#' + key).removeClass('alignleft');
					$('#' + key).removeClass('aligncenter');
					$('#' + key).removeClass('alignright');
					$('#' + key).addClass('align' + objComponents.pages['page_' + compPageId][key]['alignment']);
				}

				$('#' + key).removeClass('verticalaligntop');
				$('#' + key).removeClass('verticalalignmiddle');
				$('#' + key).removeClass('verticalalignbottom');
				$('#' + key).addClass('verticalalign' + objComponents.pages['page_' + compPageId][key]['verticalalignment']);

				if(objComponents.pages['page_' + compPageId][key].id_tcid == 17){
					// textmodul
					$('#' + key + ' .content').attr('data-textmodul', objComponents.pages['page_' + compPageId][key]['content']);
				}
				
				// color area
				if(objComponents.pages['page_' + compPageId][key].id_tcid == 18){
					$('#' + key).css('z-index', 1);
				}
				
				$('#' + key).css({
					 'width'     : objComponents.pages['page_' + compPageId][key].width + '%',
					 'height'    : objComponents.pages['page_' + compPageId][key].height + '%',
					 'left'      : objComponents.pages['page_' + compPageId][key].left + '%',
					 'top'       : objComponents.pages['page_' + compPageId][key].top + '%',
					 'font-size' : (objComponents.pages['page_' + compPageId][key]['fontsize'] * componentFactor) + 'pt',
					 'color'     : objComponents.pages['page_' + compPageId][key]['fontcolor'],
					 'text-align': objComponents.pages['page_' + compPageId][key]['alignment'],
					 'background-color': (objComponents.pages['page_' + compPageId][key]['background_color'] != '') ? objComponents.pages['page_' + compPageId][key]['background_color'] : 'transparent'					 
				})
				.draggable({
					//containment: '.formComponentPreviewComponents',
					delay: 300,
					start: function(event, ui) {
						objPlaceHolderStart = {"left":ui.helper.position().left, "top":ui.helper.position().top, "width":ui.helper.width(), "height":ui.helper.height()}
					},
					stop: function(event, ui) {
						window['f_##modul_name##']['checkBleeding'](obj, event, ui, this, 'drag');
					}
				})
				.resizable({
					//containment: '.formComponentPreviewComponents',
					handles: 'all',
					start: function(event, ui) {
						objPlaceHolderStart = {"left":ui.helper.position().left, "top":ui.helper.position().top, "width":ui.helper.width(), "height":ui.helper.height()}
					},
					stop: function(event, ui) {
						window['f_##modul_name##']['checkBleeding'](obj, event, ui, this, 'resize');
					}
				})
				.on('mouseover', function(){
					deactivateCompbox = 0;
				})
				.on('mouseout', function(){
					deactivateCompbox = 1;
				})
				.on('mousedown', function(){
					window['f_##modul_name##']['activatePlaceholder'](obj, this);
				});
							
				if(objComponents.pages['page_' + compPageId][key].id_tcid != 12 && objComponents.pages['page_' + compPageId][key].id_tcid != 15){
					//$('#' + compId).draggable('option', 'containment', 'false');
					$('#' + key).draggable('option', 'containment', '.formComponentPreviewComponents');
					$('#' + key).resizable('option', 'containment', '.formComponentPreviewComponents');
				}

				if(objComponents.pages['page_' + compPageId][key]['fixed'] == 1 || $('.formLeft input[name="caid"]').val() == 3){
					$('#' + key).draggable('disable');
					$('#' + key).resizable('disable');
				}
			}
		}
		
		
		var activeComp = $('#modul_' + obj.modulpath + ' .formLeft input[name="activeComp"]').val();
		if(activeComp > 0) window['f_##modul_name##']['activatePlaceholder'](obj, $('.compboxOuter[data-tpeid="' + activeComp + '"]'));
		
		window['f_##modul_name##']['checkPublish'](obj);
		
		unwaiting();
	};
		
	
    window['f_##modul_name##']['resizeComponentsPage'] = function (obj) { 
		var actStep = $('#modul_' + obj.modulpath + ' .formLeft .fieldsetActive').attr('data-step') * 1;
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		
		$('#modul_' + obj.modulpath + ' .formLeft .formComponentsTools').css('width', '');

		var imgWidthOrg = $('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewBackground img').attr('data-width');
		var imgHeightOrg = $('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewBackground img').attr('data-height');
		var previewWidthAll = $('#modul_' + obj.modulpath + ' .formLeft .formComponentsPreview').outerWidth();
		var previewWidth = $('#modul_' + obj.modulpath + ' .formLeft .formComponentsPreview').outerWidth() - $('#modul_' + obj.modulpath + ' .formLeft .formComponentsTools').outerWidth();
		var previewHeight = $('#modul_' + obj.modulpath + ' .formLeft .formComponentsPreview').outerHeight();
		
		
		var imgWidthFac = previewWidth / imgWidthOrg;
		var imgHeightFac = previewHeight / imgHeightOrg;
		var factor = (imgWidthFac > imgHeightFac) ? imgHeightFac : imgWidthFac;
		componentFactor = factor;
		
		if(factor < 1){
			var imgWidthReal = imgWidthOrg * factor;
			var imgHeightReal = imgHeightOrg * factor;
		}else{
			var imgWidthReal = imgWidthOrg;
			var imgHeightReal = imgHeightOrg;
			componentFactor = 1;
		}
		
		var wTools = previewWidthAll - imgWidthReal;
		$('#modul_' + obj.modulpath + ' .formLeft .formComponentsTools').css('width', wTools + 'px');
		$('#modul_' + obj.modulpath + ' .formLeft .formComponentsTools').css('display', 'block');

		$('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewBackground').css('width', imgWidthReal + 'px');
		$('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewBackground').css('height', imgHeightReal + 'px');
		$('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewComponents').css('width', imgWidthReal + 'px');
		$('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewComponents').css('height', imgHeightReal + 'px');
	}; 


    window['f_##modul_name##']['createPlaceholder'] = function (obj) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

		if($('#modul_' + obj.modulpath + ' .formLeft .formComponentOuterActive').length > 0){
			$('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewComponents').selectable({
				distance: 20,
				start: function(e) {
					//## get the mouse position in div on start ##
					x_beginComp = e.pageX - $(this).offset().left;
					y_beginComp = e.pageY - $(this).offset().top;
					
					deactivateCompbox = 0;
				},
				stop: function(e) {
					//## get the mouse position in div on stop ##
					var x_endComp = e.pageX - $(this).offset().left;
					var y_endComp = e.pageY - $(this).offset().top;
					
					var id_tcid = $('#modul_' + obj.modulpath + ' .formLeft .formComponentOuterActive').attr('data-id');
					var compPageId = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-pageid');
					
					//## calculate width/height/left/top depending on mouse direction ##
					var objComponentNew = {};
					objComponentNew.compWidth = (x_endComp > x_beginComp) ? x_endComp - x_beginComp : x_beginComp - x_endComp;
					objComponentNew.compHeight = (y_endComp > y_beginComp) ? y_endComp - y_beginComp : y_beginComp - y_endComp;
					objComponentNew.compLeft = (x_endComp > x_beginComp) ? x_beginComp : x_endComp;
					objComponentNew.compTop = (y_endComp > y_beginComp) ? y_beginComp : y_endComp;
					
					//## recalculate position and size in percent
					objComponentNew.docWidth = $('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewBackground img').innerWidth();
					objComponentNew.docHeight = $('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewBackground img').innerHeight();
					objComponentNew.compWidthPerc = (100 / objComponentNew.docWidth) * objComponentNew.compWidth;
					objComponentNew.compHeightPerc = (100 / objComponentNew.docHeight) * objComponentNew.compHeight;
					objComponentNew.compLeftPerc = (100 / objComponentNew.docWidth) * objComponentNew.compLeft;
					objComponentNew.compTopPerc = (100 / objComponentNew.docHeight) * objComponentNew.compTop;

					objComponentNew.id_tempid = obj.id_data;
					objComponentNew.id_tpid = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-tp');
					objComponentNew.page = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-page');
					objComponentNew.id_tcid = id_tcid;
					objComponentNew.id_caid = $('#modul_' + obj.modulpath + ' .formLeft input[name="caid"]').val();
					
					
					var data = 'comp=' + JSON.stringify(objComponentNew);;
					
					$.ajax({  
						url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-components-insert.php', 
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
			
							var id_tpeid = result;
							var compId = 'compboxOuter_' + id_tpeid;
							
							//## append a new div ##
							$('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewComponents').append('<div class="compboxOuter comboxOuter_' + objComponentNew.id_caid + '_' + objComponentNew.id_tcid + '" id="' + compId + '" data-tcid="' + id_tcid + '" data-tpeid="' + id_tpeid + '"><div class="content">' + objConfigComponents[id_tcid].default_content + '</div></div>');

							//add css to generated div and make it resizable & draggable
							switch(objConfigComponents[id_tcid].fields['fontstyle'].default){
								case('0'):
									$('#' + compId).css('font-weight', 'normal');
									$('#' + compId).css('font-style', 'normal');
									break;
								
								case('1'):
									$('#' + compId).css('font-weight', 'bold');
									$('#' + compId).css('font-style', 'normal');
									break;
								
								case('2'):
									$('#' + compId).css('font-weight', 'normal');
									$('#' + compId).css('font-style', 'italic');
									break;
								
								case('3'):
									$('#' + compId).css('font-weight', 'bold');
									$('#' + compId).css('font-style', 'italic');
									break;
							};
							
							if(id_tcid == 10){
								$('#' + compId).removeClass('contactalignleft');
								$('#' + compId).removeClass('contactaligncenter');
								$('#' + compId).removeClass('contactalignright');
								$('#' + compId).addClass('contactalign' + objConfigComponents[id_tcid].fields['alignment'].default);
							}
							
							if(id_tcid == 11 || id_tcid == 12 || id_tcid == 15){
								$('#' + compId).removeClass('alignleft');
								$('#' + compId).removeClass('aligncenter');
								$('#' + compId).removeClass('alignright');
								$('#' + compId).addClass('align' + objConfigComponents[id_tcid].fields['alignment'].default);
							}

							$('#' + compId).removeClass('verticalaligntop');
							$('#' + compId).removeClass('verticalalignmiddle');
							$('#' + compId).removeClass('verticalalignbottom');
							$('#' + compId).addClass('verticalalign' + objConfigComponents[id_tcid].fields['verticalalignment'].default);
							
							if(id_tcid == 17){
								// textmodul
								$('#' + compId + ' .content').attr('data-textmodul', 0);
							}
				
							// color area
							if(id_tcid == 18){
								$('#' + compId + '').css('z-index', 1);
							}
				
							$('#' + compId).css({
								 'width'     : objComponentNew.compWidthPerc + '%',
								 'height'    : objComponentNew.compHeightPerc + '%',
								 'left'      : objComponentNew.compLeftPerc + '%',
								 'top'       : objComponentNew.compTopPerc + '%',
								 'font-size' : (objConfigComponents[id_tcid].fields['fontsize'].default * componentFactor) + 'pt',
								 'color'     : objConfigComponents[id_tcid].fields['fontcolor'].default,
								 'text-align': objConfigComponents[id_tcid].fields['alignment'].default,
								 'background-color': (objConfigComponents[id_tcid].fields['background_color'].default != '') ? objConfigComponents[id_tcid].fields['background_color'].default : 'transparent'
							})
							.draggable({
								//containment: '.formComponentPreviewComponents',
								delay: 300,
								start: function(event, ui) {
									objPlaceHolderStart = {"left":ui.helper.position().left, "top":ui.helper.position().top, "width":ui.helper.width(), "height":ui.helper.height()}
								},
								stop: function(event, ui) {
									window['f_##modul_name##']['checkBleeding'](obj, event, ui, this, 'drag');
								}
							})
							.resizable({
								//containment: '.formComponentPreviewComponents',
								handles: 'all',
								start: function(event, ui) {
									objPlaceHolderStart = {"left":ui.helper.position().left, "top":ui.helper.position().top, "width":ui.helper.width(), "height":ui.helper.height()}
								},
								stop: function(event, ui) {
									window['f_##modul_name##']['checkBleeding'](obj, event, ui, this, 'resize');
								}
							})
							.on('mouseover', function(){
								deactivateCompbox = 0;
							})
							.on('mouseout', function(){
								deactivateCompbox = 1;
							})
							.on('mousedown', function(){
								window['f_##modul_name##']['activatePlaceholder'](obj, this);
							});
							
							if(id_tcid != 12 && id_tcid != 15){
								//$('#' + compId).draggable('option', 'containment', 'false');
								$('#' + compId).draggable('option', 'containment', '.formComponentPreviewComponents');
								$('#' + compId).resizable('option', 'containment', '.formComponentPreviewComponents');
							}

							//## save component in object
							if(objComponents.pages['page_' + compPageId] == undefined) objComponents.pages['page_' + compPageId] = {};
							objComponents.pages['page_' + compPageId][compId] = {};
							objComponents.pages['page_' + compPageId][compId].id_tpeid = id_tpeid;
							objComponents.pages['page_' + compPageId][compId].id_caid = objComponentNew.id_caid;
							objComponents.pages['page_' + compPageId][compId].id_tpid = objComponentNew.id_tpid;
							objComponents.pages['page_' + compPageId][compId].id_tcid = objComponentNew.id_tcid;
							objComponents.pages['page_' + compPageId][compId].pageid = compPageId;
							objComponents.pages['page_' + compPageId][compId].page = objComponentNew.page;
							objComponents.pages['page_' + compPageId][compId].width = objComponentNew.compWidthPerc;
							objComponents.pages['page_' + compPageId][compId].height = objComponentNew.compHeightPerc;
							objComponents.pages['page_' + compPageId][compId].left = objComponentNew.compLeftPerc;
							objComponents.pages['page_' + compPageId][compId].top = objComponentNew.compTopPerc;
							objComponents.pages['page_' + compPageId][compId]['content'] = objConfigComponents[id_tcid].default_content;
							objComponents.pages['page_' + compPageId][compId]['fontsize'] = objConfigComponents[id_tcid].fields['fontsize'].default;
							objComponents.pages['page_' + compPageId][compId]['fontcolor'] = objConfigComponents[id_tcid].fields['fontcolor'].default;
							objComponents.pages['page_' + compPageId][compId]['fontstyle'] = objConfigComponents[id_tcid].fields['fontstyle'].default;
							objComponents.pages['page_' + compPageId][compId]['transrequired'] = objConfigComponents[id_tcid].fields['transrequired'].default;
							objComponents.pages['page_' + compPageId][compId]['maxchars'] = objConfigComponents[id_tcid].fields['maxchars'].default;
							objComponents.pages['page_' + compPageId][compId]['alignment'] = objConfigComponents[id_tcid].fields['alignment'].default;
							objComponents.pages['page_' + compPageId][compId]['verticalalignment'] = objConfigComponents[id_tcid].fields['verticalalignment'].default;
							objComponents.pages['page_' + compPageId][compId]['editable'] = objConfigComponents[id_tcid].fields['editable'].default;
							objComponents.pages['page_' + compPageId][compId]['active'] = objConfigComponents[id_tcid].fields['active'].default;
							objComponents.pages['page_' + compPageId][compId]['elementtitle'] = '';
							objComponents.pages['page_' + compPageId][compId]['background_color'] = objConfigComponents[id_tcid].fields['background_color'].default;
		
							$('#modul_' + obj.modulpath + ' .formLeft input[name="components"]').val(JSON.stringify(objComponents));
							
							window['f_##modul_name##']['formatThumbEditable'](obj);
							window['f_##modul_name##']['activatePlaceholder'](obj, $('#' + compId));
							window.setTimeout(function(){deactivateCompbox = 1}, 500);
						}
					});
				}
			});
		}
	};
		
	
    window['f_##modul_name##']['checkBleeding'] = function (obj, event, ui, el, type) { 
		var vis = isVisible('.formComponentPreviewComponents', el, 20, 'px');
		if(vis.topMin == true && vis.rightMin == true && vis.bottomMin == true && vis.leftMin == true){
			if(type == 'drag') window['f_##modul_name##']['dragStopPlaceholder'](obj, event, ui, el);
			if(type == 'resize') window['f_##modul_name##']['resizeStopPlaceholder'](obj, event, ui, el);
		}else{
			window['f_##modul_name##']['resetPlaceholder'](obj, event, ui, el);
		}
	};
		
	
    window['f_##modul_name##']['resetPlaceholder'] = function (obj, event, ui, el) { 
		$(el).css('top', objPlaceHolderStart.top + 'px');
		$(el).css('left', objPlaceHolderStart.left + 'px');
		$(el).width(objPlaceHolderStart.width);
		$(el).height(objPlaceHolderStart.height);
	};
		
	
    window['f_##modul_name##']['dragStopPlaceholder'] = function (obj, event, ui, el) { 
		var compPageId = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-pageid');
		var compLeft = ui.helper.position().left;
		var compTop = ui.helper.position().top;
		var compId = $(el).attr('id');
		
		var docWidth = $('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewBackground img').innerWidth();
		var docHeight = $('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewBackground img').innerHeight();
		var compLeftPerc = (100 / docWidth) * compLeft;
		var compTopPerc = (100 / docHeight) * compTop;
		
		$('#modul_' + obj.modulpath + ' .formLeft #' + compId).css('left', compLeftPerc + '%');
		$('#modul_' + obj.modulpath + ' .formLeft #' + compId).css('top', compTopPerc + '%');
		objComponents.pages['page_' + compPageId][compId].left = compLeftPerc;
		objComponents.pages['page_' + compPageId][compId].top = compTopPerc;

		$('#modul_' + obj.modulpath + ' .formLeft input[name="components"]').val(JSON.stringify(objComponents));
	};
		
	
    window['f_##modul_name##']['resizeStopPlaceholder'] = function (obj, event, ui, el) { 
		var compPageId = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-pageid');
		var compId = $(el).attr('id');
		var compWidth = $(el).outerWidth();
		var compHeight = $(el).outerHeight();
		
		var docWidth = $('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewBackground img').innerWidth();
		var docHeight = $('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewBackground img').innerHeight();
		var compWidthPerc = (100 / docWidth) * compWidth;
		var compHeightPerc = (100 / docHeight) * compHeight;
		
		$('#modul_' + obj.modulpath + ' .formLeft #' + compId).css('width', compWidthPerc + '%');
		$('#modul_' + obj.modulpath + ' .formLeft #' + compId).css('height', compHeightPerc + '%');
		objComponents.pages['page_' + compPageId][compId].width = compWidthPerc;
		objComponents.pages['page_' + compPageId][compId].height = compHeightPerc;
		
		
		var compLeft = $(el).position().left;
		var compTop = $(el).position().top;
		var compLeftPerc = (100 / docWidth) * compLeft;
		var compTopPerc = (100 / docHeight) * compTop;
		$('#modul_' + obj.modulpath + ' .formRight #' + compId).css('left', compLeftPerc + '%');
		$('#modul_' + obj.modulpath + ' .formRight #' + compId).css('top', compTopPerc + '%');
		objComponents.pages['page_' + compPageId][compId].left = compLeftPerc;
		objComponents.pages['page_' + compPageId][compId].top = compTopPerc;

		$('#modul_' + obj.modulpath + ' .formLeft input[name="components"]').val(JSON.stringify(objComponents));
	};
		
	
    window['f_##modul_name##']['activatePlaceholder'] = function (obj, el) { 
		var id_tcid = $(el).attr('data-tcid');
		var id_tpeid = $(el).attr('data-tpeid');
		$('#modul_' + obj.modulpath + ' .formLeft input[name="activeComp"]').val(id_tpeid);

		$('.compboxOuter').removeClass('compboxOuterActive');
		$(el).addClass('compboxOuterActive');

		$('#modul_' + obj.modulpath + ' .formLeft .formComponentOuter').removeClass('formComponentOuterActive');
		$('#modul_' + obj.modulpath + ' .formLeft .formComponentOuter[data-id="' + id_tcid + '"]').addClass('formComponentOuterActive');
		
		
		$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldset').removeClass('fieldsetActive');
		if(objConfigComponents[id_tcid] != undefined) $('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldset[data-tcid="' + id_tcid + '"] .formComponentHeadline').html(objConfigComponents[id_tcid].name);
		$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldset[data-tcid="' + id_tcid + '"]').addClass('fieldsetActive');

		$('#modul_' + obj.modulpath + ' .formLeft .componentsConfigurationIconCopy').off('click');
		$('#modul_' + obj.modulpath + ' .formLeft .componentsConfigurationIconCopy').on('click', function(){
			window['f_##modul_name##']['copyPlaceholder'](obj);
		});
		$('#modul_' + obj.modulpath + ' .formLeft .componentsConfigurationIconCopy').addClass('componentsConfigurationIconActive');
		
		window['f_##modul_name##']['fillFormComponent'](obj);
	}; 
		
	
    window['f_##modul_name##']['deactivatePlaceholder'] = function (obj, el) { 
		if(deactivateCompbox == 1 && $('.compboxOuterActive').length > 0) {
			$('#modul_' + obj.modulpath + ' .formLeft input[name="activeComp"]').val(0);
		
			$('.compboxOuter').removeClass('compboxOuterActive');
			$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldset').removeClass('fieldsetActive');
			$('#modul_' + obj.modulpath + ' .formLeft .formComponentOuter').removeClass('formComponentOuterActive');

			$('#modul_' + obj.modulpath + ' .formLeft .componentsConfigurationIconCopy').off('click');
			$('#modul_' + obj.modulpath + ' .formLeft .componentsConfigurationIconCopy').removeClass('componentsConfigurationIconActive');
		}

	}; 
		
	
    window['f_##modul_name##']['copyPlaceholder'] = function (obj) { 
		var pageid = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-pageid');
		var compid = $('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').attr('id');

		if(objComponents.pages['page_' + pageid][compid] != undefined) objClipboard = Object.assign({}, objComponents.pages['page_' + pageid][compid]);
		
		window['f_##modul_name##']['initConfigurationIconPaste'](obj);
	}; 
		
	
    window['f_##modul_name##']['pastePlaceholder'] = function (obj, el) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		
		var tcidClipboard = objClipboard.id_tcid;
		
		if($('.formComponentOuter[data-id="' + tcidClipboard + '"]').length > 0){
			var tempid = obj.id_data;
			var page = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-page');
			var bfid = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-bfid');
			var tpid = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-tp');
			var pageid = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-pageid');
			var caid = $('#modul_' + obj.modulpath + ' .formLeft input[name="id_caid"]').val();
			var component = JSON.stringify(objClipboard);
			
			var data = 'tempid=' + tempid;
			data += '&page=' + page;
			data += '&bfid=' + bfid;
			data += '&tpid=' + tpid;
			data += '&pageid=' + pageid;
			data += '&caid=' + caid;
			data += '&component=' + component;
			
			$.ajax({  
				url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-components-paste.php', 
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
					var objTemp = JSON.parse(result);
	
					if(objComponents['pages']['page_' + objTemp['pageid']] == undefined) objComponents['pages']['page_' + objTemp['pageid']] = {};
					objComponents.pages['page_' + objTemp.pageid]['compboxOuter_' + objTemp.id_tpeid] = objTemp; 
					
					var elem = $('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewComponents .compboxOuterActive');
					window['f_##modul_name##']['deactivatePlaceholder'](obj,elem);
					
					$('#modul_' + obj.modulpath + ' .formLeft input[name="components"]').val(JSON.stringify(objComponents));
					$('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewComponents .compboxOuter').remove();
					
					window['f_##modul_name##']['loadPageComponents'](obj);
				}
			});
		}else{
			var objDialog = {};
			objDialog.el = el;
			objDialog.title = objText.errorPastePlaceholder;
			objDialog.formtext = objText.textErrorPastePlaceholder;
		
			objDialog.objButtons = {};
			objDialog.objButtons[objText.Cancel] = function() {closeDialog(obj, this);}            
			openDialogMessage(obj, objDialog);
		}
	}; 
		
	
    window['f_##modul_name##']['initConfigurationIconPaste'] = function (obj) { 
		if(jQuery.isEmptyObject(objClipboard)){
			$('#modul_' + obj.modulpath + ' .formLeft .componentsConfigurationIconPaste').off('click');
			$('#modul_' + obj.modulpath + ' .formLeft .componentsConfigurationIconPaste').removeClass('componentsConfigurationIconActive');
		}else{
			$('#modul_' + obj.modulpath + ' .formLeft .componentsConfigurationIconPaste').off('click');
			$('#modul_' + obj.modulpath + ' .formLeft .componentsConfigurationIconPaste').on('click', function(){
				window['f_##modul_name##']['pastePlaceholder'](obj, this);
			});
	
			$('#modul_' + obj.modulpath + ' .formLeft .componentsConfigurationIconPaste').addClass('componentsConfigurationIconActive');
		}
	}; 
		
	
    window['f_##modul_name##']['formatThumbEditable'] = function (obj, el) { 
		$('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuter').removeClass('formComponentThumbOuterEditable');
		for(key in objComponents.pages){
			for(comp in objComponents.pages[key]){
				var page = objComponents.pages[key][comp].pageid;
				$('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuter[data-pageid="' + page + '"]').addClass('formComponentThumbOuterEditable');
			}
		}
	}; 
		
	
    window['f_##modul_name##']['changeFormatComponent'] = function (obj, el) { 
		var compPageId = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-pageid');
		var compId = $('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').attr('id');
		var id_tcid = $('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').attr('data-tcid');
		
		var fieldname = $(el).attr('name');
		switch(fieldname){
			case('content'):
				if(objConfigComponents[id_tcid].fields['content'].type == 'text'){
					var val = $(el).val();
					objComponents.pages['page_' + compPageId][compId][fieldname + 'Org'] = val;
					$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive .content').html(val);
					objComponents.pages['page_' + compPageId][compId][fieldname] = val;
				}
				if(objConfigComponents[id_tcid].fields['content'].type == 'textarea'){
					var val = $(el).val();
					objComponents.pages['page_' + compPageId][compId][fieldname + 'Org'] = val;
					val = val.replace(/(?:\r\n|\r|\n)/g, '<br>');
					$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive .content').html(val);
					objComponents.pages['page_' + compPageId][compId][fieldname] = val;
					window['f_##modul_name##']['checkMaxChars'](obj);
				}	
				if(objConfigComponents[id_tcid].fields['content'].type == 'wysiwyg'){
				}	
				if(objConfigComponents[id_tcid].fields['content'].type == 'file'){
				}
				if(id_tcid == 17){
					var val = $(el).find('option:selected').val();
					$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive .content').html(objComponentsTextmoduls[val]);
					objComponents.pages['page_' + compPageId][compId][fieldname] = val;
				}
				break;
			
			case('transrequired'):
				var val = $(el).parent().find(':checked').val();
				objComponents.pages['page_' + compPageId][compId][fieldname] = val;
				break;
			
			case('maxchars'):
				var val = $(el).val();
				objComponents.pages['page_' + compPageId][compId][fieldname] = val;
				window['f_##modul_name##']['checkMaxChars'](obj, el);
				break;
			
			case('fontsize'):
				var val = $(el).val();
				objComponents.pages['page_' + compPageId][compId][fieldname] = val;
				$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').css('font-size', (val * componentFactor) + 'pt');
				break;
			
			case('fontcolor'):
				var val = $(el).val();
				objComponents.pages['page_' + compPageId][compId][fieldname] = val;
				$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').css('color', val);
				break;
			
			case('background_color'):
				var val = $(el).val();
				objComponents.pages['page_' + compPageId][compId][fieldname] = val;
				$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').css('background-color', val);
				break;
			
			case('fontstyle'):
				var val = $(el).find('option:selected').val();
				objComponents.pages['page_' + compPageId][compId][fieldname] = val;
				switch(val){
					case('0'):
						$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').css('font-weight', 'normal');
						$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').css('font-style', 'normal');
						break;
					
					case('1'):
						$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').css('font-weight', 'bold');
						$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').css('font-style', 'normal');
						break;
					
					case('2'):
						$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').css('font-weight', 'normal');
						$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').css('font-style', 'italic');
						break;
					
					case('3'):
						$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').css('font-weight', 'bold');
						$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').css('font-style', 'italic');
						break;
				}
				break;
			
			case('alignment'):
				var val = $(el).val();
				objComponents.pages['page_' + compPageId][compId][fieldname] = val;
				$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').css('text-align', val);
				if(id_tcid == 10){
					$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').removeClass('contactalignleft');
					$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').removeClass('contactaligncenter');
					$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').removeClass('contactalignright');
					$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').addClass('contactalign' + val);
				}
				if(id_tcid == 11 || id_tcid == 12 || id_tcid == 15){
					$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').removeClass('alignleft');
					$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').removeClass('aligncenter');
					$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').removeClass('alignright');
					$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').addClass('align' + val);
				}
				break;
			
			case('verticalalignment'):
				var val = $(el).val();
				objComponents.pages['page_' + compPageId][compId][fieldname] = val;
				$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').removeClass('verticalaligntop');
				$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').removeClass('verticalalignmiddle');
				$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').removeClass('verticalalignbottom');
				$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').addClass('verticalalign' + val);
				break;
			
			case('editable'):
				var val = $(el).parent().find(':checked').val();
				objComponents.pages['page_' + compPageId][compId][fieldname] = val;
				break;
			
			case('active'):
				var val = $(el).parent().find(':checked').val();
				objComponents.pages['page_' + compPageId][compId][fieldname] = val;
				break;
			
		}
		
		$('#modul_' + obj.modulpath + ' .formLeft input[name="components"]').val(JSON.stringify(objComponents));
		
		window['f_##modul_name##']['resizeStopPlaceholder'](obj, '', '', $('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive'));
	}; 
		
	
    window['f_##modul_name##']['fillFormComponent'] = function (obj) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

		var compPageId = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-pageid');
		var compId = $('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').attr('id');
		var id_tcid = $('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').attr('data-tcid');
		
		if(objConfigComponents[id_tcid] != undefined) {
			for(key in objConfigComponents[id_tcid].fields){
				var fieldname = objConfigComponents[id_tcid].fields[key].name;
				if(objComponents.pages['page_' + compPageId][compId][fieldname] == undefined) objComponents.pages['page_' + compPageId][compId][fieldname] = objConfigComponents[id_tcid].fields[fieldname]['default'];
				
				switch(fieldname){
					case('content'):
						var val = objComponents.pages['page_' + compPageId][compId][fieldname];

						switch(id_tcid){
							case('1'): // textfield
								val = val.replace(/(<br>)/g, '\r\n');
								val = val.replace(/(<br \/>)/g, '\r\n');
								break
						}

						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="' + fieldname + '"]').val(val);
						break;
	
					case('transrequired'):
						var val = objComponents.pages['page_' + compPageId][compId][fieldname];
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="' + fieldname + '"][value="' + val + '"]').prop('checked', true);
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="' + fieldname + '"][value="0"]').parent().find('.valuedefault').html('(' + objComponents.pages['page_' + compPageId][compId][fieldname + '_default'] + ')');
						break;
	
					case('maxchars'):
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="' + fieldname + '"]').val(objComponents.pages['page_' + compPageId][compId][fieldname]);
						window['f_##modul_name##']['checkMaxChars'](obj);
						break;
	
					case('fontsize'):
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="' + fieldname + '"]').val(objComponents.pages['page_' + compPageId][compId][fieldname]);
						break;
	
					case('fontcolor'):
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="' + fieldname + '"]').spectrum('set', objComponents.pages['page_' + compPageId][compId][fieldname]);
						break;
	
					case('background_color'):
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="' + fieldname + '"]').spectrum('set', objComponents.pages['page_' + compPageId][compId][fieldname]);
						break;
	
					case('fontstyle'):
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="' + fieldname + ' option').prop('selected', false);
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="' + fieldname + '"] option[value="' + objComponents.pages['page_' + compPageId][compId][fieldname] + '"]').prop('selected', true);
						break;
	
					case('alignment'):
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="' + fieldname + '"]').val(objComponents.pages['page_' + compPageId][compId][fieldname]);
						break;
			
					case('verticalalignment'):
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="' + fieldname + '"]').val(objComponents.pages['page_' + compPageId][compId][fieldname]);
						break;
	
					case('editable'):
						var val = objComponents.pages['page_' + compPageId][compId][fieldname];
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="' + fieldname + '"][value="' + val + '"]').prop('checked', true);
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="' + fieldname + '"][value="0"]').parent().find('.valuedefault').html('(' + objComponents.pages['page_' + compPageId][compId][fieldname + '_default'] + ')');
						break;
	
					case('active'):
						var val = objComponents.pages['page_' + compPageId][compId][fieldname];
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="' + fieldname + '"][value="' + val + '"]').prop('checked', true);
						$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="' + fieldname + '"][value="0"]').parent().find('.valuedefault').html('(' + objComponents.pages['page_' + compPageId][compId][fieldname + '_default'] + ')');
						break;
				}
			}
		}
		
		$('.formComponentsForm .booleanfield[value="0"]').each(function(){
			if($(this).prop('checked') == true){
				var v = $(this).parent().find('.valuedefault').text();
				v = v.replace('(','');
				v = v.replace(')','');
				if(v == objText.yes) $(this).parents('.formField').find('.booleanfield[value="1"]').prop('checked', true);
				if(v == objText.no) $(this).parents('.formField').find('.booleanfield[value="2"]').prop('checked', true);
			}
		});

	}; 
		
	
    window['f_##modul_name##']['checkRemoveComponent'] = function (obj, el) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		
		var objDialog = {};
		objDialog.el = el;
		objDialog.title = objText.removeComponent;
		objDialog.formtext = objText.removeComponentCheck;
		objDialog.identifier = '';
		
		objDialog.objButtons = {};
		objDialog.objButtons[objText.Cancel] = function() {closeDialog(obj, this);}            
		objDialog.objButtons[objText.Delete] = function() {window['f_##modul_name##']['removeComponent'](obj, this);}
		
		openDialogAlert(obj, objDialog);
	}; 
		
	
    window['f_##modul_name##']['removeComponent'] = function (obj, el) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

		var compPageId = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-pageid');
		var compId = $('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').attr('id');
		var id_tcid = $('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').attr('data-tcid');
		var id_tpeid = $('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').attr('data-tpeid');
		
		var data = 'tpeid=' + id_tpeid;
		
		$.ajax({  
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-components-delete.php', 
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

				window['f_##modul_name##']['deactivatePlaceholder'](obj);
				$('#modul_' + obj.modulpath + ' .formLeft #' + compId + '').remove();
				delete objComponents.pages['page_' + compPageId][compId];
				$('#modul_' + obj.modulpath + ' .formLeft input[name="components"]').val(JSON.stringify(objComponents));
				window['f_##modul_name##']['formatThumbEditable'](obj);
				closeDialog(obj)
			}
		});
	}; 
		
	
    window['f_##modul_name##']['checkMaxChars'] = function (obj, el) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

		var compPageId = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-pageid');
		var compId = $('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').attr('id');
		var id_tcid = $('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').attr('data-tcid');
		var id_tpeid = $('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').attr('data-tpeid');
		
		if(objConfigComponents[id_tcid].fields['content'].type == 'textarea'){
			if(objComponents.pages['page_' + compPageId][compId]['contentOrg'] == undefined) objComponents.pages['page_' + compPageId][compId]['contentOrg'] = objComponents.pages['page_' + compPageId][compId]['content'];
			var str = objComponents.pages['page_' + compPageId][compId]['contentOrg'];
			str = str.replace(/(<br>)/g, '\r\n');
			str = str.replace(/(<br \/>)/g, '\r\n');
			var maxchar = $('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="maxchars"]').val();
			var strShort = (maxchar > 0) ? str.substr(0, maxchar) : str;
			
			$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="content"]').val(strShort);
			strShort = strShort.replace(/(?:\r\n|\r|\n)/g, '<br>');
			$('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive .content').html(strShort);
			objComponents.pages['page_' + compPageId][compId]['content'] = strShort;
	
	
			$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="content"]').closest('.formField').find('.descmaxchars').remove();
			if(maxchar > 0){
				var strlen = $('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="content"]').val().length;
				var rest = (maxchar * 1) - strlen;
				
				$('#modul_' + obj.modulpath + ' .formLeft .formComponentsForm .fieldsetActive [name="content"]').closest('.formField').append('<div class="descmaxchars"><span class="restmaxchars">' + rest + '</span> ' + objText.restMaxChars + '</div>');
			}
		}

	}; 
		
	
    window['f_##modul_name##']['childmodulWideOpen'] = function (obj) { 
		resetCountAnimation();
		var objModulParent = splitModulpath(obj.modulpath);
		
		$('#modul_112-0-11-components > div').addClass('childmodulUnvisible');
	
		$('#modul_112-0-11-components').prepend('<div class="formChildmodulClose"><div class="formMiddleClose"></div></div>');
		$('#modul_' + obj.modulpath + ' .formMiddleClose').css('background-position', '5px 50px');
		$('#modul_' + obj.modulpath + ' .formMiddleClose').off('click');
		$('#modul_' + obj.modulpath + ' .formMiddleClose').on('click', function(){
			window['f_##modul_name##']['childmodulWideClose'](obj);
		});
	
		$('#modul_112-0-11-components').addClass('childmodulOpen childmodulOpenZ childmodulOpenBG');
		var childposTopOld = $('#modul_112-0-11-components').css('top').replace('px','');
		$('#modul_112-0-11-components').attr('data-styletop', childposTopOld);
		var childposLeftOld = $('#modul_112-0-11-components').css('left').replace('px','');
		$('#modul_112-0-11-components').attr('data-styleleft', childposLeftOld);
	
		var childposTop = childposTopOld;
		childposTop -= $('#modul_' + objModulParent.modulpath + ' .formLeft .formTabs').outerHeight(true);
		childposTop -= $('#modul_' + objModulParent.modulpath + ' .form .tabFormFilter').outerHeight(true);
		var childposLeft = childposLeftOld;
		childposLeft -= $('#modul_' + objModulParent.modulpath + ' .formLeft').outerWidth(true);
		childposLeft -= $('#modul_' + objModulParent.modulpath + ' .formLeft').css('margin-left').replace('px','');
		$('#modul_112-0-11-components').css('top', childposTop + 'px');
		$('#modul_112-0-11-components').css('left', childposLeft + 'px');
		$('#modul_112-0-11-components .formComponentsTop').css('left', '50px');
	
//		var tabText = (objText[objModul.modul_label] == undefined) ? objModul.modul_label : objText[objModul.modul_label];
//		$('#breadcrumbInner').append('<span class="breadmenue" data-modulpath="' + obj.modulpath + '">'+tabText+'</span>');
//		initBreadcrumbNavigation(obj);
	}; 
		
	
    window['f_##modul_name##']['childmodulWideClose'] = function (obj) { 
		resetCountAnimation();
		
		$('#modul_112-0-11-components > div').addClass('childmodulUnvisible');
		$('#modul_112-0-11-components').addClass('childmodulOpenBG');
		$('#modul_112-0-11-components .formChildmodulClose').remove();
	
		var childposTop = $('#modul_112-0-11-components').attr('data-styletop');
		var childposLeft = $('#modul_112-0-11-components').attr('data-styleleft');
		$('#modul_112-0-11-components').css('top', childposTop + 'px');
		$('#modul_112-0-11-components').css('left', childposLeft + 'px');
		$('#modul_112-0-11-components .formComponentsTop').css('left', '0px');
		
		$('#modul_112-0-11-components').removeClass('childmodulOpen');
		$('#modul_112-0-11-components').removeClass('childmodulWideOpenManually');
		$('#modul_112-0-11-components').removeClass('childmodulWideOpenDirect');
		window.setTimeout(function(){$('#modul_112-0-11-components').removeClass('childmodulOpenZ')}, 600);
		
		
//		$('#breadcrumbInner .breadmenue[data-modulpath="' + obj.modulpath + '"]').remove();
//		initBreadcrumbNavigation(obj);
	}; 
		
	
    window['f_##modul_name##']['configComponentSaveDo'] = function (obj, el) { 
		waiting('.ui-dialog');
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

		var components = $('#modul_' + obj.modulpath + ' .formLeft input[name="components"]').val();
		var caid = $('#modul_' + obj.modulpath + ' .formLeft input[name="caid"]').val();
		var page = $('.formComponentThumbOuterActive').attr('data-page');
		var tpid = $('.formComponentThumbOuterActive').attr('data-tp');
		var configurationname = $('.dialogOuter input[name="configurationname"]').val();


		var data = 'components=' + encodeURIComponent(components);
		data += '&caid=' + caid;
		data += '&page=' + page;
		data += '&tpid=' + tpid;
		data += '&configurationname=' + configurationname;
		
		$.ajax({  
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-configurations-save.php', 
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

				unwaiting('.ui-dialog');
				closeDialog(obj)
			}
		});
	}; 
		
	
    window['f_##modul_name##']['configComponentLoadDo'] = function (obj, el) { 
		waiting('.ui-dialog');
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

		var caid = $('#modul_' + obj.modulpath + ' .formLeft input[name="caid"]').val();
		var page = $('.formComponentThumbOuterActive').attr('data-page');
		var tpid = $('.formComponentThumbOuterActive').attr('data-tp');
		var configuration = $('.dialogOuter select[name="configurationnameLoad"] option:selected').val();

		var data = '&caid=' + caid;
		data += '&configuration=' + configuration;
		data += '&page=' + page;
		data += '&tpid=' + tpid;
		data += '&components=' + JSON.stringify(objComponents);
		
		$.ajax({  
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-configurations-load.php', 
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

				$('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewComponents .compboxOuter').removeClass('compboxOuterActive');
				$('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewComponents .compboxOuter').remove();
				
				objComponents = JSON.parse(result); 
				$('#modul_' + obj.modulpath + ' .formLeft input[name="components"]').val(JSON.stringify(objComponents));
				window['f_##modul_name##']['loadPageComponents'](obj);
				
				unwaiting('.ui-dialog');
				closeDialog(obj);
			}
		});
	}; 
		
	
    window['f_##modul_name##']['templatePublishDo'] = function (obj, el) { 
		waiting('.ui-dialog');
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

		var data = '';
		
		$.ajax({  
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-publish.php', 
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
					url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-preview-create.php', 
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
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-transrequest.php', 
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
		
	

    window['f_##modul_name##']['selectCountriesByAll'] = function (obj, el) { 
		$('#form_' + obj.modulpath + ' .formLeft .countryTableRow .checkfieldselectgeo').prop('checked', $(el).prop('checked'));
		$('#form_' + obj.modulpath + ' .formLeft .countryTableRow .checkfield').prop('checked', $(el).prop('checked'));
	}; 
		
	
    window['f_##modul_name##']['selectCountriesByGeo'] = function (obj, el) { 
		var geo = $(el).val();
		$('#form_' + obj.modulpath + ' .formLeft .countryTableRow[data-geo="' + geo + '"] .checkfield').prop('checked', $(el).prop('checked'));
	}; 
		
	
    window['f_##modul_name##']['checkAddBanner'] = function (obj, el) { 
		checkRequired(obj, '#bannername_' + obj.modulpath);
		if($('#form_' + obj.modulpath + ' .formLeft .formBannerAdd .rowError').length == 0){
			window['f_##modul_name##']['addBanner'](obj);
		}
	}; 
		
	
    window['f_##modul_name##']['addBanner'] = function (obj, el) { 
		obj.cbSendFiles = 'addBannerformat';

		if($('#form_' + obj.modulpath + ' .formLeft .fileupload').length > 0 && filesUpload.length > 0){
			$('#form_' + obj.modulpath + ' .formLeft').append('<div class="uploadOverlay"></div>');
			$('#form_' + obj.modulpath + ' .formLeft .fileUploadOuter').clone().appendTo('#form_' + obj.modulpath + ' .formLeft .uploadOverlay');
			sendDataFiles(obj);
		}else{
			window['f_##modul_name##']['addBannerformat'](obj, {});
		}
	}; 
		
	
    window['f_##modul_name##']['addBannerformat'] = function (obj, files) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

		var objFormData = JSON.parse($('#modul_' + obj.modulpath + ' .formLeft .field_formdata').val());
		var banner_original_1 = objFormData.banner_original_1;
		var banner_original_2 = objFormData.banner_original_2;
		var banner_original_3 = objFormData.banner_original_3;
		
		var data = 'bannername=' + $('#form_' + obj.modulpath + ' .formLeft input[name="bannername"]').val();
		data += '&first=' + banner_original_1;
		data += '&product=' + banner_original_2;
		data += '&last=' + banner_original_3;
		data += '&files=' + JSON.stringify(files);
		objResultFiles = {};
		
		$.ajax({  
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-bannerformat-insert.php', 
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

				var objResult = JSON.parse(result);
				$('#form_' + obj.modulpath + ' .formLeft .formBannerformats').append(objResult.bannerformats);

				var thumbnails = '';
				for(var group in objResult.thumbnails){
					thumbnails += '<div class="formComponentThumbgroup" data-group="' + group + '">';
					if(group != 'na') thumbnails += '<div class="formComponentThumbgroupHead">' + group + '</div>';
					
					for(var page in objResult.thumbnails[group]){
						thumbnails += '<div class="formComponentThumbOuter" data-page="' + page + '" data-bfid="' + objResult.thumbnails[group][page].bfid + '" data-tp="' + objResult.thumbnails[group][page].tp + '" data-pageid="' + objResult.thumbnails[group][page].pageid + '"><div class="formComponentThumb"><img src="' + objResult.thumbnails[group][page].src + '"></div><div class="formComponentPage">' + objResult.thumbnails[group][page].pagelabel + '</div></div>';
					}
					
					thumbnails += '</div>';
				}
				$('#bannername_' + obj.modulpath).val('');
				$('#modul_' + obj.modulpath + ' .formLeft .formComponentsThumbnails').append(thumbnails);
				window['f_##modul_name##']['initFormComponentThumbs'](obj);
				
				$('#modul_' + obj.modulpath + ' .formLeftInner').css('display', 'block');
				if($('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').length == 0){
					$('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuter:first').addClass('formComponentThumbOuterActive');
					objComponents.id_temp = '';
					window['f_##modul_name##']['loadComponents'](obj);
				}

						
				$('#modul_' + obj.modulpath + ' .formLeft .modulIconDelete').off('click');
				$('#modul_' + obj.modulpath + ' .formLeft .modulIconDelete').on('click', function(){
					window['f_##modul_name##']['deleteBannerformat'](obj, this);
				});
				
				$('#modul_' + obj.modulpath + ' .formLeft .modulIconEdit').off('click');
				$('#modul_' + obj.modulpath + ' .formLeft .modulIconEdit').on('click', function(){
					window['f_##modul_name##']['editBannerformat'](obj, this);
				});
			}
		});
	}; 
		
	
    window['f_##modul_name##']['deleteBannerformat'] = function (obj, el) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		var bfid = $(el).parents('.formBannerformatOuter').attr('data-bfid');
		
		var objDialog = {};
		objDialog.el = el;
		objDialog.title = objText.removeBannerformat;
		objDialog.formtext = objText.removeBannerformatCheck;
		objDialog.identifier = '';
		
		objDialog.objButtons = {};
		objDialog.objButtons[objText.Cancel] = function() {closeDialog(obj, this);}            
		objDialog.objButtons[objText.Delete] = function() {window['f_##modul_name##']['deleteBannerformatDo'](obj, bfid, this);}
		
		openDialogAlert(obj, objDialog);
	}; 
		
	
    window['f_##modul_name##']['deleteBannerformatDo'] = function (obj, bfid, el) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		
		var data = 'bfid=' + bfid;

		$.ajax({  
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-bannerformat-delete.php', 
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

				var objP = JSON.parse(result);
				$('#form_' + obj.modulpath + ' .formLeft .formBannerformatOuter[data-bfid="' + bfid + '"]').remove();
				$('#form_' + obj.modulpath + ' .formLeft .formComponentThumbOuter[data-bfid="' + bfid + '"]').closest('.formComponentThumbgroup').remove();
				for(var key in objP){
					for(var i = 1; i <= 3; i++){
						var keyPage = 'page_' + objP[key] + '_' + i;
						if(objComponents.pages[keyPage] != undefined) delete(objComponents.pages[keyPage]);
					}
				}
				 
				objResultFiles = {};
				closeDialog(obj, el);
			}
		});
	}; 
		
	
    window['f_##modul_name##']['editBannerformat'] = function (obj, el) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		var bfid = $(el).closest('.formBannerformatOuter').attr('data-bfid');
		$('#bannerformat_id_bfid_' + obj.modulpath).val(bfid);
		
		var bannername = $(el).parents('.formBannerformatOuter').find('.formBannerformatName').text();
		$('#bannername_' + obj.modulpath).val(bannername);
		
		var i = 0;
		$(el).closest('.formBannerformatOuter').find('.formRow').each(function(){
			$('#form_' + obj.modulpath + ' .formLeft .formRow[data-fieldname="banner_original_' + i + '"] .formField div:first').html('');
			if($(this).find('.formBannerFile').length > 0){
				var tpid = $(this).find('.formBannerFile').attr('data-tpid');
				var mid = $(this).find('.formBannerFile').attr('data-mid');
				var file = $(this).find('.formBannerFile').html();
				var fileTmp = '<div class="fileUploadedOuter" data-mid="' + mid + '" data-tpid="' + tpid + '"><div class="fileUploadFunctions"> <div class="modulIcon modulIconForm modulIconDelete" title="' + objText.uploadFileDelete + '"><i class="fa fa-trash-o"></i></div></div><div class="fileUploadFilename" style="left: 0px; right: 24.4375px;"><div>' + file + '</div></div></div>';
				
				$('#form_' + obj.modulpath + ' .formLeft .formRow[data-fieldname="banner_original_' + i + '"] .formField div:first').html(fileTmp);
				
			}
			i++;
		});
		
		$('#modul_' + obj.modulpath + ' .formLeft .fileUploadedOuter .modulIconDelete').off('click');
		$('#modul_' + obj.modulpath + ' .formLeft .fileUploadedOuter .modulIconDelete').on('click', function(){
			window['f_##modul_name##']['fileBannerformatDelete'](obj, this);
		});
		
		$('#form_' + obj.modulpath + ' .formLeft .formRowBannerAdd').css('display', 'none');
		$('#form_' + obj.modulpath + ' .formLeft .formRowBannerEdit').css('display', 'block');
		
		$('#form_' + obj.modulpath + ' .formLeft .fieldsetActive').scrollTop(0);
	}; 
		
	
    window['f_##modul_name##']['checkEditBanner'] = function (obj, el) { 
		checkRequired(obj, '#bannername_' + obj.modulpath);
		if($('#form_' + obj.modulpath + ' .formLeft .formBannerAdd .rowError').length == 0){
			window['f_##modul_name##']['editBanner'](obj);
		}
	}; 
		
	
    window['f_##modul_name##']['editBanner'] = function (obj, el) { 
		obj.cbSendFiles = 'editBannerDo';

		if($('#form_' + obj.modulpath + ' .formLeft .fileupload').length > 0 && filesUpload.length > 0){
			$('#form_' + obj.modulpath + ' .formLeft').append('<div class="uploadOverlay"></div>');
			$('#form_' + obj.modulpath + ' .formLeft .fileUploadOuter').clone().appendTo('#form_' + obj.modulpath + ' .formLeft .uploadOverlay');

			sendDataFiles(obj);
		}else{
			window['f_##modul_name##']['editBannerDo'](obj, {});
		}
	}; 
		
	
    window['f_##modul_name##']['editBannerDo'] = function (obj, files) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

		var objFormData = JSON.parse($('#modul_' + obj.modulpath + ' .formLeft .field_formdata').val());
		var bfid = $('#modul_' + obj.modulpath + ' .formLeft input[name="bannerformat_id_bfid"]').val();
		var banner_original_1 = objFormData.banner_original_1;
		var banner_original_2 = objFormData.banner_original_2;
		var banner_original_3 = objFormData.banner_original_3;
		
		var data = 'bannername=' + $('#form_' + obj.modulpath + ' .formLeft input[name="bannername"]').val();
		data += '&bfid=' + bfid;
		data += '&first=' + banner_original_1;
		data += '&product=' + banner_original_2;
		data += '&last=' + banner_original_3;
		data += '&files=' + JSON.stringify(files);
		objResultFiles = {};
		
		$.ajax({  
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-bannerformat-update.php', 
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

				var objResult = JSON.parse(result);
				$('#form_' + obj.modulpath + ' .formLeft .formBannerformats .formBannerformatOuter[data-bfid="' + bfid + '"]').html(objResult.bannerformats);

				var thumbnails = '';
				for(var group in objResult.thumbnails){
					//thumbnails += '<div class="formComponentThumbgroup" data-group="' + group + '">';
					if(group != 'na') thumbnails += '<div class="formComponentThumbgroupHead">' + group + '</div>';
					
					for(var page in objResult.thumbnails[group]){
						thumbnails += '<div class="formComponentThumbOuter" data-page="' + page + '" data-bfid="' + objResult.thumbnails[group][page].bfid + '" data-tp="' + objResult.thumbnails[group][page].tp + '" data-pageid="' + objResult.thumbnails[group][page].pageid + '"><div class="formComponentThumb"><img src="' + objResult.thumbnails[group][page].src + '"></div><div class="formComponentPage">' + objResult.thumbnails[group][page].pagelabel + '</div></div>';
					}
					
					//thumbnails += '</div>';
				}
				$('#form_' + obj.modulpath + ' .formLeft .formComponentThumbOuter[data-bfid="' + bfid + '"]').closest('.formComponentThumbgroup').html(thumbnails);
				
				window['f_##modul_name##']['loadComponents'](obj);
				window['f_##modul_name##']['cancelEditBanner'](obj);
				
				window['f_##modul_name##']['initFormComponentThumbs'](obj);
						
				$('#modul_' + obj.modulpath + ' .formLeft .modulIconDelete').off('click');
				$('#modul_' + obj.modulpath + ' .formLeft .modulIconDelete').on('click', function(){
					window['f_##modul_name##']['deleteBannerformat'](obj, this);
				});
				
				$('#modul_' + obj.modulpath + ' .formLeft .modulIconEdit').off('click');
				$('#modul_' + obj.modulpath + ' .formLeft .modulIconEdit').on('click', function(){
					window['f_##modul_name##']['editBannerformat'](obj, this);
				});
			}
		});
	}; 
		
	
    window['f_##modul_name##']['fileBannerformatDelete'] = function (obj, el) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		var tpid = $(el).closest('.fileUploadedOuter').attr('data-tpid');
		var mid = $(el).closest('.fileUploadedOuter').attr('data-mid');
		
		var objDialog = {};
		objDialog.el = el;
		objDialog.title = objText.removeBannerformatPage;
		objDialog.formtext = objText.removeBannerformatPageCheck;
		objDialog.identifier = '';
		
		objDialog.objButtons = {};
		objDialog.objButtons[objText.Cancel] = function() {closeDialog(obj, this);}            
		objDialog.objButtons[objText.Delete] = function() {window['f_##modul_name##']['fileBannerformatDeleteDo'](obj, tpid, mid, this);}
		
		openDialogAlert(obj, objDialog);
	}; 
		
	
    window['f_##modul_name##']['fileBannerformatDeleteDo'] = function (obj, tpid, mid, el) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	
		var data = 'tpid=' + tpid;
		data += '&mid=' + mid;

		$.ajax({  
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-bannerformat-page-delete.php', 
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

				var keyPage = 'page_' + $('#form_' + obj.modulpath + ' .formLeft .formComponentThumbOuter[data-tp="' + tpid + '"]').attr('data-pageid');
				if(objComponents.pages[keyPage] != undefined) delete(objComponents.pages[keyPage]);
				$('#form_' + obj.modulpath + ' .formLeft .formComponentThumbOuter[data-tp="' + tpid + '"]').remove();
				$('#form_' + obj.modulpath + ' .formLeft .fileUploadedOuter[data-tpid="' + tpid + '"]').remove();
				$('#form_' + obj.modulpath + ' .formLeft .formBannerFile[data-tpid="' + tpid + '"]').remove();
				 
				objResultFiles = {};
				closeDialog(obj, el);
				window['f_##modul_name##']['cancelEditBanner'](obj);
			}
		});

	}; 
		
	
    window['f_##modul_name##']['cancelEditBanner'] = function (obj, el) { 
		$('#bannerformat_id_bfid_' + obj.modulpath).val(0);
		$('#bannername_' + obj.modulpath).val('');

		for(var i = 1; i <= 3; i++){
			$('#form_' + obj.modulpath + ' .formLeft .formRow[data-fieldname="banner_original_' + i + '"] .formField div:first').html('');
			$('#form_' + obj.modulpath + ' .formLeft .formRow[data-fieldname="banner_original_' + i + '"] .formField .fileUploadOuter').remove();
		}
		
		$('#form_' + obj.modulpath + ' .formLeft .formRowBannerAdd').css('display', 'block');
		$('#form_' + obj.modulpath + ' .formLeft .formRowBannerEdit').css('display', 'none');
	}; 


		
	
    window['f_##modul_name##']['checkComponentFileupload'] = function (obj, el) { 
		window['f_##modul_name##']['addComponentFileupload'](obj);
	}; 
		
	
    window['f_##modul_name##']['addComponentFileupload'] = function (obj, el) { 

		obj.cbSendFiles = 'saveComponentFileupload';

		if($('#form_' + obj.modulpath + ' .formLeft .fileupload').length > 0 && filesUpload.length > 0){
			$('#form_' + obj.modulpath + ' .formLeft .formComponentsTools').append('<div class="uploadOverlay"></div>');
			$('#form_' + obj.modulpath + ' .formLeft .formComponentsTools .fileUploadOuter').clone().appendTo('#form_' + obj.modulpath + ' .formLeft .formComponentsTools .uploadOverlay');

			sendDataFiles(obj);
		}
	}; 
		
	
    window['f_##modul_name##']['saveComponentFileupload'] = function (obj, files) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

		var data = 'tpe=' + $('#form_' + obj.modulpath + ' .formLeft .compboxOuterActive').attr('id');
		data += '&files=' + JSON.stringify(files);
		objResultFiles = {};
		
		$.ajax({  
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-components-fileupload-insert.php', 
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

				var compPageId = $('#modul_' + obj.modulpath + ' .formLeft .formComponentThumbOuterActive').attr('data-pageid');
				var id_tpeid = $('#modul_' + obj.modulpath + ' .formLeft .compboxOuterActive').attr('data-tpeid');
				var compId = 'compboxOuter_' + id_tpeid;

				//$('#form_' + obj.modulpath + ' .formLeft .compboxOuterActive .componentUploadfile').css('background-image', 'url(' + objSystem.directoryInstallation + objSystem.pathMedia + files['x_x_x_content'].sysname + ')');
				$('#form_' + obj.modulpath + ' .formLeft .compboxOuterActive .componentUploadfile').css('background-image', 'none');
				$('#form_' + obj.modulpath + ' .formLeft .compboxOuterActive .componentUploadfile').html('<img src="' + objSystem.directoryInstallation + objSystem.pathMedia + files['x_x_x_content'].sysname + '">');

				objComponents.pages['page_' + compPageId][compId]['content'] = $('#form_' + obj.modulpath + ' .formLeft .compboxOuterActive .content').html();
				$('#modul_' + obj.modulpath + ' .formLeft input[name="components"]').val(JSON.stringify(objComponents));
			}
		});
	}; 
		
	
    window['f_##modul_name##']['checkPublish'] = function (obj) { 
		var trans = 0;

		if(objUser.right != 4){
			var objData = JSON.parse($('#form_' + obj.modulpath + ' .formLeft .field_formdata').val());
			var objDataComp = (objData.components != '') ? JSON.parse(objData.components) : {};
			
			if(objData.title_transrequired == 1 || objData.title_transrequired_default == objText.yes) trans = 1;
			
			for(var pageid in objDataComp.pages){
				for(var compid in objDataComp.pages[pageid]){
					if(objDataComp.pages[pageid][compid].transrequired == 1) trans = 1;
				}
			}
		}
		
		if(trans == 1){
			$('#modul_' + obj.modulpath + ' .formLeft .templatePublish').attr('data-type', 'translation');
			$('#modul_' + obj.modulpath + ' .formLeft .templatePublish').html(objText.requestTranslation);
		}else{
			$('#modul_' + obj.modulpath + ' .formLeft .templatePublish').attr('data-type', 'publish');
			$('#modul_' + obj.modulpath + ' .formLeft .templatePublish').html(objText.TemplatePublish);
		}
	}; 


    window['f_##modul_name##']['goPreviousStep'] = function (obj) {
		window['f_##modul_name##']['deactivatePlaceholder'](obj);
		
		var actStep = $('#modul_' + obj.modulpath + ' .formLeft .fieldsetActive').attr('data-step') * 1;
		var newStep = actStep - 1;
		
		(newStep < 2) ? $('#form_' + obj.modulpath + ' .formLeft .previousStep').addClass('buttonHide') : $('#form_' + obj.modulpath + ' .formLeft .previousStep').removeClass('buttonHide');
		(newStep == $('#modul_' + obj.modulpath + ' .formLeft .fieldset[data-step]').length) ? $('#form_' + obj.modulpath + ' .formLeft .nextStep').addClass('buttonHide') : $('#form_' + obj.modulpath + ' .formLeft .nextStep').removeClass('buttonHide');


		if(obj.id_data > 0){
			$('#modul_' + obj.modulpath + ' .formLeft .selectContentselect').closest('.formRow').addClass('formRowReadonly');
			$('#form_' + obj.modulpath + ' .formLeft .selectContentselect').readonly(true);
			$('#modul_' + obj.modulpath + ' .formLeft .radioContentselect').closest('.formRow').addClass('formRowReadonly');
			$('#form_' + obj.modulpath + ' .formLeft .radioContentselect').readonly(true);
		}



		if($('#modul_' + obj.modulpath + ' .formLeft .fieldset[data-step="' + newStep + '"]').length > 0){ 
			$('#modul_' + obj.modulpath + ' .formLeft .fieldset').removeClass('fieldsetActive');
			$('#modul_' + obj.modulpath + ' .formLeft .fieldset[data-step="' + newStep + '"]').addClass('fieldsetActive');
			
			var formtab = $('#modul_' + obj.modulpath + ' .formLeft .fieldsetActive').attr('data-formtab');
			$('#form_' + obj.modulpath + ' .formLeft li[data-formtab]').removeClass('active');
			$('#form_' + obj.modulpath + ' .formLeft li[data-formtab="' + formtab + '"]').addClass('active');
			$('#form_' + obj.modulpath + ' .formLeft li[data-formtab]').css('display', 'none');
			$('#form_' + obj.modulpath + ' .formLeft li[data-formtab].active').css('display', '');
			
				window['f_##modul_name##']['sendForm'](obj, 'save');
//			if(newStep == 4){
//				window['f_##modul_name##']['resizeComponentsPage'](obj);
//				$('#modul_' + obj.modulpath + ' .formLeft .formComponentPreviewComponents .compboxOuter').remove();
//				window['f_##modul_name##']['loadPageComponents'](obj);
//			}
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
				checkRequired(obj, '.textfieldTitle');
				checkSelectNotEqual0(obj, 'select[name="id_caid"]');
				
				var cat = $('select[name="id_caid"] option:selected').val();
				if(cat == 4 || cat == 5){
					checkRadioRequired(obj, 'input[name="contentselect"]');
				}
			}
			// ##################################
			
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


    window['f_##modul_name##']['resizeFontsize'] = function (html) {
		var outHTML = html;
		var pattern = /font-size:( )*(\d)*pt/gi;
		var res = outHTML.match(pattern);
		
		for(var key in res){
			var size = res[key];
			size = size.replace('font-size:', '');
			size = size.replace(' ', '');
			size = size.replace('pt', '');
			size = size * componentFactor;
			
			outHTML = outHTML.replace(res[key], 'font-size:' + size + 'pt');
		}
		
		return outHTML;	
	}; 






})();


