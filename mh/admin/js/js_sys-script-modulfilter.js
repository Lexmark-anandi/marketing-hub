function initModulFilter(obj){ 
	$('#modul_' + obj.modulpath + ' .wModulFilterOuter').removeClass('wModulFilterDisabled');
	$('#modul_' + obj.modulpath + ' .wModulFilterOuter select').readonly(false);
	$('#modul_' + obj.modulpath + ' .wModulFilterOuter').each(function(){
		if($(this).find('option').length == 1){
			$(this).addClass('wModulFilterDisabled');
			$(this).find('select').readonly(true);
		}
	});

	$('#modul_' + obj.modulpath + ' .filterModulCountry').off('change');
	$('#modul_' + obj.modulpath + ' .filterModulCountry').on('change', function(){
		changeModulFilterCountry(obj);
	});
	
	$('#modul_' + obj.modulpath + ' .filterModulLanguage').off('change');
	$('#modul_' + obj.modulpath + ' .filterModulLanguage').on('change', function(){
		changeModulFilterLanguage(obj);
	});
	
	$('#modul_' + obj.modulpath + ' .filterModulDevice').off('change');
	$('#modul_' + obj.modulpath + ' .filterModulDevice').on('change', function(){
		changeModulFilterDevice(obj);
	});
}


function changeModulFilterCountry(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	var country = $('#modul_' + obj.modulpath + ' .filterModulCountry option:selected').val();

	// ## set cookie ## 
	var objChange = {};
	(objModul.specifications[9] == 9) ? objChange['id_sys_count'] = country : objChange['id_countid'] = country;
	changeCookie('activesettings', objChange);
	
	// ## set object activesettings ## 
	objModul.activeSettings.selectCountry = country;
	objModul.activeSettings.formCountry = country;
	(obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] = objModul : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod] = objModul;

	// ## set language selection according to selected country ##
	$('#modul_' + obj.modulpath + ' .filterModulLanguage').html('');
	var objLanguages = (objModul.specifications[9] == 9) ? objUser.syscountries[country].languages : objUser.countries[country].languages;
		
	for (var i = 0; i < objLanguages.length; i++) {
		var id_lang = objLanguages[i];
		var text = (objText[objUser.languages[id_lang].language] != undefined) ? objText[objUser.languages[id_lang].language] : objUser.languages[id_lang].language;
		var sel = '';
		if(id_lang == objModul.activeSettings.selectLanguage) sel = 'selected="selected"';
		$('#modul_' + obj.modulpath + ' .filterModulLanguage').append('<option value="' + id_lang + '" ' + sel + '>' + text + '</option>');
	}
	
	initModulFilter(obj);
	changeModulFilterLanguage(obj);
}


function changeModulFilterLanguage(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	var language = $('#modul_' + obj.modulpath + ' .filterModulLanguage option:selected').val();

	// ## set cookie ## 
	var objChange = {};
	(objModul.specifications[9] == 9) ? objChange['id_sys_lang'] = language : objChange['id_langid'] = language;
	changeCookie('activesettings', objChange);
	
	// ## set object activesettings ## 
	objModul.activeSettings.selectLanguage = language;
	objModul.activeSettings.formLanguage = language;
	(obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] = objModul : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod] = objModul;
	
	changeModulFilter(obj);
}


function changeModulFilterDevice(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	var device = $('#modul_' + obj.modulpath + ' .filterModulDevice option:selected').val();

	// ## set cookie ## 
	var objChange = {};
	(objModul.specifications[9] == 9) ? objChange['id_sys_dev'] = device : objChange['id_devid'] = device;
	changeCookie('activesettings', objChange);
	
	// ## set object activesettings ## 
	objModul.activeSettings.selectDevice = device;
	objModul.activeSettings.formDevice = device;
	(obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] = objModul : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod] = objModul;
	
	changeModulFilter(obj);
}


function changeModulFilter(obj){
	// ## synchronize Filter setting ##
	var objModulSrc = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

	// ## parent moduls ##
	for(var id_page in objUser.pages2moduls){
		for(var keyModul in objUser.pages2moduls[id_page].moduls){
			var objModul = objUser.pages2moduls[id_page].moduls[keyModul];
			if((objSystem.synchronizeModulFilter == 1 && objModul.specifications[12] == 9 && objModul.specifications[9] == objModulSrc.specifications[9]) || keyModul == 'i_' + obj.id_mod){
				objModul.activeSettings.selectCountry = objModulSrc.activeSettings.selectCountry;
				objModul.activeSettings.selectLanguage = objModulSrc.activeSettings.selectLanguage;
				objModul.activeSettings.selectDevice = objModulSrc.activeSettings.selectDevice;
				objModul.activeSettings.formCountry = objModulSrc.activeSettings.formCountry;
				objModul.activeSettings.formLanguage = objModulSrc.activeSettings.formLanguage;
				objModul.activeSettings.formDevice = objModulSrc.activeSettings.formDevice;
				objUser.pages2moduls[id_page].moduls[keyModul] = objModul;
			}
		}
	}

	// ## child moduls ##
	for(var id_mod in objUser.childmoduls){
		for(var keyModul in objUser.childmoduls[id_mod]){
			var objModul = objUser.childmoduls[id_mod][keyModul];
			if((objSystem.synchronizeModulFilter == 1 && objModul.specifications[12] == 9 && objModul.specifications[9] == objModulSrc.specifications[9]) || keyModul == 'i_' + obj.id_mod){
				objModul.activeSettings.selectCountry = objModulSrc.activeSettings.selectCountry;
				objModul.activeSettings.selectLanguage = objModulSrc.activeSettings.selectLanguage;
				objModul.activeSettings.selectDevice = objModulSrc.activeSettings.selectDevice;
				objModul.activeSettings.formCountry = objModulSrc.activeSettings.formCountry;
				objModul.activeSettings.formLanguage = objModulSrc.activeSettings.formLanguage;
				objModul.activeSettings.formDevice = objModulSrc.activeSettings.formDevice;
				objUser.childmoduls[id_mod][keyModul] = objModul;
			}
		}
	}
	
	$.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-user-save.php',    
		type: 'post',          
		data: '',       
		cache: false,
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(obj),
			settings: JSON.stringify(objModulSrc.activeSettings)
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);
			
			window['f_' + obj.modul_name]['readyReload'](obj);
		}
	});
}


