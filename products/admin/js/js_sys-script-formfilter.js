function initFormFilter(obj){
	$('.modul[data-modul="' + obj.modul + '"] .wFormFilterOuter').removeClass('wFormFilterDisabled');
	$('.modul[data-modul="' + obj.modul + '"] .wFormFilterOuter select').readonly(false);
	
	// ## disable filter if only one option is available ##
	$('.modul[data-modul="' + obj.modul + '"] .wFormFilterOuter').each(function(){
		if($(this).find('option').length == 1){
			$(this).find('select').readonly(true);
		}
	});
	
	// ## disable filter if saving is only for one variation ##
	if(aPage.moduls[obj.modul].specifics.substr(0, 1) == 0) $('.modul[data-modul="' + obj.modul + '"] .filterFormCountry').readonly(true);
	if(aPage.moduls[obj.modul].specifics.substr(1, 1) == 0) $('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage').readonly(true);
	if(aPage.moduls[obj.modul].specifics.substr(2, 1) == 0) $('.modul[data-modul="' + obj.modul + '"] .filterFormDevice').readonly(true);
	
	// ## disable filter for insert new dataset ##
	if(obj.id == 0){
		$('.modul[data-modul="' + obj.modul + '"] .filterFormCountry').readonly(true);
		$('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage').readonly(true);
		$('.modul[data-modul="' + obj.modul + '"] .filterFormDevice').readonly(true);
	}

	$('.modul[data-modul="' + obj.modul + '"] [readonly]').closest('.wFormFilterOuter').addClass('wFormFilterDisabled');

	
	if(aPage.moduls[obj.modul].specifics.substr(6, 1) == 9){
		$('.modul[data-modul="' + obj.modul + '"] .filterFormCountry').off('focus change');
		$('.modul[data-modul="' + obj.modul + '"] .filterFormCountry').on('focus', function(){
			elObj = {"oldValue":$(this).find('option:selected').val()}
			setElement($(this), elObj)
		});
		$('.modul[data-modul="' + obj.modul + '"] .filterFormCountry').on('change', function(){
			changeFormFilterSys(obj);
			//changeFormFilterSysCountry(obj);
		});
		
		$('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage').off('focus change');
		$('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage').on('focus', function(){
			elObj = {"oldValue":$(this).find('option:selected').val()}
			setElement($(this), elObj)
		});
		$('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage').on('change', function(){
			changeFormFilterSys(obj);
			//changeFormFilterSysLanguage(obj);
		});
		
		$('.modul[data-modul="' + obj.modul + '"] .filterFormDevice').off('focus change');
		$('.modul[data-modul="' + obj.modul + '"] .filterFormDevice').on('focus', function(){
			elObj = {"oldValue":$(this).find('option:selected').val()}
			setElement($(this), elObj)
		});
		$('.modul[data-modul="' + obj.modul + '"] .filterFormDevice').on('change', function(){
			changeFormFilterSys(obj);
			//changeFormFilterSysDevice(obj);
		});
	}else{
		$('.modul[data-modul="' + obj.modul + '"] .filterFormCountry').off('focus change');
		$('.modul[data-modul="' + obj.modul + '"] .filterFormCountry').on('focus', function(){
			elObj = {"oldValue":$(this).find('option:selected').val()}
			setElement($(this), elObj)
		});
		$('.modul[data-modul="' + obj.modul + '"] .filterFormCountry').on('change', function(){
			changeFormFilter(obj);
			//changeFormFilterCountry(obj);
		});
		
		$('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage').off('focus change');
		$('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage').on('focus', function(){
			elObj = {"oldValue":$(this).find('option:selected').val()}
			setElement($(this), elObj)
		});
		$('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage').on('change', function(){
			changeFormFilter(obj);
			//changeFormFilterLanguage(obj);
		});
		
		$('.modul[data-modul="' + obj.modul + '"] .filterFormDevice').off('focus change');
		$('.modul[data-modul="' + obj.modul + '"] .filterFormDevice').on('focus', function(){
			elObj = {"oldValue":$(this).find('option:selected').val()}
			setElement($(this), elObj)
		});
		$('.modul[data-modul="' + obj.modul + '"] .filterFormDevice').on('change', function(){
			changeFormFilter(obj);
			//changeFormFilterDevice(obj);
		});
	}
}

function fillFilterLanguage(obj){
	$('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage').html('');
	
	if(aPage.moduls[obj.modul].formCountry == 0){
		var text = (aText.alllanguages != undefined) ? aText.alllanguages : 'alllanguages';
		$('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage').append('<option value="0">' + text + '</option>');
	}else{
		var aLanguages = aUser.countries[aPage.moduls[obj.modul].formCountry].languages;
		if(aPage.moduls[obj.modul].specifics.substr(6, 1) == 9) aLanguages = aUser.syscountries[aPage.moduls[obj.modul].formCountry].languages;
		for (var i = 0; i < aLanguages.length; i++) {
			var key = aLanguages[i];
			var text = (aText[aUser.languages[key].language] != undefined) ? aText[aUser.languages[key].language] : aUser.languages[key].language;
			if(aPage.moduls[obj.modul].specifics.substr(6, 1) == 9) text = (aText[aUser.syslanguages[key].language] != undefined) ? aText[aUser.syslanguages[key].language] : aUser.syslanguages[key].language;
			var sel = '';
			if(key == aPage.moduls[obj.modul].formLanguage) sel = 'selected';
			$('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage').append('<option value="' + key + '">' + text + '</option>');
		}
	}
}
	
// ##################################################################

function changeFormFilterCountry(obj){
	aPage.moduls[obj.modul].formCountry = $('.modul[data-modul="' + obj.modul + '"] .filterFormCountry option:selected').val();
	if(aPage.moduls[obj.modul].formCountry == undefined) aPage.moduls[obj.modul].formCountry = 0;
	
	fillFilterLanguage(obj);
	
	aPage.moduls[obj.modul].formLanguage = $('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage option:selected').val();
	if(aPage.moduls[obj.modul].formLanguage == undefined) aPage.moduls[obj.modul].formLanguage = 0;
	
	initFormFilter(obj);
	//changeFormFilter(obj);
}

function changeFormFilterLanguage(obj){
	aPage.moduls[obj.modul].formLanguage = $('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage option:selected').val();
	if(aPage.moduls[obj.modul].formLanguage == undefined) aPage.moduls[obj.modul].formLanguage = 0;
	
	//changeFormFilter(obj);
}

function changeFormFilterDevice(obj){
	aPage.moduls[obj.modul].formDevice = $('.filterFormDevice option:selected').val();
	if(aPage.moduls[obj.modul].formDevice == undefined) aPage.moduls[obj.modul].formDevice = 0;
	
	//changeFormFilter(obj);
}

function changeFormFilter(obj){
	clearErrors(obj);
	changeData(obj);
}


// ##################################################################

function changeFormFilterSysCountry(obj){
	aPage.moduls[obj.modul].activeSysCountry = $('.modul[data-modul="' + obj.modul + '"] .filterFormCountry option:selected').val();
	if(aPage.moduls[obj.modul].formCountry == undefined) aPage.moduls[obj.modul].formCountry = 0;
	
	fillFilterLanguage(obj);
	
	aPage.moduls[obj.modul].activeSysLanguage = $('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage option:selected').val();
	if(aPage.moduls[obj.modul].formLanguage == undefined) aPage.moduls[obj.modul].formLanguage = 0;
	
	initFormFilter(obj);
	//changeFormFilterSys(obj);
}

function changeFormFilterSysLanguage(obj){
	aPage.moduls[obj.modul].activeSysLanguage = $('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage option:selected').val();
	
	//changeFormFilterSys(obj);
}

function changeFormFilterSysDevice(obj){
	aPage.moduls[obj.modul].activeSysDevice = $('.modul[data-modul="' + obj.modul + '"] .filterFormDevice option:selected').val();
	
	//changeFormFilterSys(obj);
}

function changeFormFilterSys(obj){
	clearErrors(obj);
	changeData(obj);
}

