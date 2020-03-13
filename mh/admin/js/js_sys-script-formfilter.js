function initFormFilter(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

	$('#modul_' + obj.modulpath + ' .wFormFilterOuter').removeClass('wFormFilterDisabled');
	$('#modul_' + obj.modulpath + ' .wFormFilterOuter select').readonly(false);
	
	// ## disable filter if only one option is available ##
	$('#modul_' + obj.modulpath + ' .wFormFilterOuter').each(function(){
		if($(this).find('option').length == 1){
			$(this).find('select').readonly(true);
		}
	});

//	// ## disable filter if saving is only for one variation ##
//	if(aPage.moduls[obj.modul].specifics.substr(0, 1) == 0) $('#modul_' + obj.modulpath + ' .filterFormCountry').readonly(true);
//	if(aPage.moduls[obj.modul].specifics.substr(1, 1) == 0) $('#modul_' + obj.modulpath + ' .filterFormLanguage').readonly(true);
//	if(aPage.moduls[obj.modul].specifics.substr(2, 1) == 0) $('#modul_' + obj.modulpath + ' .filterFormDevice').readonly(true);
	
	// ## disable filter for insert new dataset ##
	if(obj.id_data == 0){
		$('#modul_' + obj.modulpath + ' .filterFormCountry').readonly(true);
		$('#modul_' + obj.modulpath + ' .filterFormLanguage').readonly(true);
		$('#modul_' + obj.modulpath + ' .filterFormDevice').readonly(true);
	}

	$('#modul_' + obj.modulpath + ' [readonly]').closest('.wFormFilterOuter').addClass('wFormFilterDisabled');


	$('#modul_' + obj.modulpath + ' .filterFormCountry').off('focus change');
	$('#modul_' + obj.modulpath + ' .filterFormCountry').on('focus', function(){
		objEl = {"oldValue":$(this).find('option:selected').val()}
		setElement($(this), objEl)
	});
	$('#modul_' + obj.modulpath + ' .filterFormCountry').on('change', function(){
		changeFormFilter(obj);
	});
	
	$('#modul_' + obj.modulpath + ' .filterFormLanguage').off('focus change');
	$('#modul_' + obj.modulpath + ' .filterFormLanguage').on('focus', function(){
		objEl = {"oldValue":$(this).find('option:selected').val()}
		setElement($(this), objEl)
	});
	$('#modul_' + obj.modulpath + ' .filterFormLanguage').on('change', function(){
		changeFormFilter(obj);
	});
	
	$('#modul_' + obj.modulpath + ' .filterModulDevice').off('focus change');
	$('#modul_' + obj.modulpath + ' .filterModulDevice').on('focus', function(){
		objEl = {"oldValue":$(this).find('option:selected').val()}
		setElement($(this), objEl)
	});
	$('#modul_' + obj.modulpath + ' .filterModulDevice').on('change', function(){
		changeFormFilter(obj);
	});
}


function changeFormFilterCountry(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	var country = $('#modul_' + obj.modulpath + ' .filterFormCountry option:selected').val();
	if(country == undefined) country = $('#modul_' + obj.modulpath).closest('.modul').find('.filterFormCountry option:selected').val();
	if(country == undefined) country = 0;

	// ## set cookie ## 
	var objChange = {};
	(objModul.specifications[9] == 9) ? objChange['id_sys_count_form'] = country : objChange['id_countid_form'] = country;
	changeCookie('activesettings', objChange);
	
	// ## set object activesettings ## 
	objModul.activeSettings.formCountry = country;
	(obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] = objModul : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod] = objModul;
	
	// ## set language selection according to selected country ##
	$('#modul_' + obj.modulpath + ' .filterFormLanguage').html('');
	var objLanguages = (objModul.specifications[9] == 9) ? objUser.syscountries[country].languages : objUser.countries[country].languages;
		
	for (var i = 0; i < objLanguages.length; i++) {
		var id_lang = objLanguages[i];
		var text = (objText[objUser.languages[id_lang].language] != undefined) ? objText[objUser.languages[id_lang].language] : objUser.languages[id_lang].language;
		var sel = '';
		if(id_lang == objModul.activeSettings.selectLanguage) sel = 'selected';
		$('#modul_' + obj.modulpath + ' .filterFormLanguage').append('<option value="' + id_lang + '">' + text + '</option>');
	}

	$('#modul_' + obj.modulpath + ' .filterFormLanguage option[value="' + objModul.activeSettings.formLanguage + '"]').prop('selected', true);

	//initFormFilter(obj);
	changeFormFilterLanguage(obj);
}


function changeFormFilterLanguage(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	var language = $('#modul_' + obj.modulpath + ' .filterFormLanguage option:selected').val();
	if(language == undefined) language = $('#modul_' + obj.modulpath).closest('.modul').find('.filterFormLanguage option:selected').val();
	if(language == undefined) language = 0;

	// ## set cookie ## 
	var objChange = {};
	(objModul.specifications[9] == 9) ? objChange['id_sys_lang_form'] = language : objChange['id_langid_form'] = language;
	changeCookie('activesettings', objChange);
	
	// ## set object activesettings ## 
	objModul.activeSettings.formLanguage = language;
	(obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] = objModul : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod] = objModul;
	
	initFormFilter(obj);
	//changeFormFilter(obj);
}


function changeFormFilterDevice(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	var device = $('#modul_' + obj.modulpath + ' .filterFormDevice option:selected').val();
	if(device == undefined) device = 0;

	// ## set cookie ## 
	var objChange = {};
	(objModul.specifications[9] == 9) ? objChange['id_sys_dev_form'] = device : objChange['id_devid_form'] = device;
	changeCookie('activesettings', objChange);
	
	// ## set object activesettings ## 
	objModul.activeSettings.formDevice = device;
	(obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] = objModul : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod] = objModul;
	
	//changeFormFilter(obj);
}

function changeFormFilter(obj){
	clearErrors(obj);
	changeData(obj);
}

