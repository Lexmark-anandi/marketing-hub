function initGridFilter(modul){
	$('.modul[data-modul="' + modul + '"] .wGridFilterOuter').removeClass('wGridFilterDisabled');
	$('.modul[data-modul="' + modul + '"] .wGridFilterOuter select').readonly(false);
	$('.modul[data-modul="' + modul + '"] .wGridFilterOuter').each(function(){
		if($(this).find('option').length == 1){
			$(this).addClass('wGridFilterDisabled');
			$(this).find('select').readonly(true);
		}
	});
	
	if(aPage.moduls[modul].specifics.substr(6, 1) == 9){
		$('.filterGridCountry').off('change');
		$('.filterGridCountry').on('change', function(){
			changeGridFilterSysCountry(modul);
		});
		
		$('.filterGridLanguage').off('change');
		$('.filterGridLanguage').on('change', function(){
			changeGridFilterSysLanguage(modul);
		});
		
		$('.filterGridDevice').off('change');
		$('.filterGridDevice').on('change', function(){
			changeGridFilterSysDevice(modul);
		});
	}else{
		$('.filterGridCountry').off('change');
		$('.filterGridCountry').on('change', function(){
			changeGridFilterCountry(modul);
		});
		
		$('.filterGridLanguage').off('change');
		$('.filterGridLanguage').on('change', function(){
			changeGridFilterLanguage(modul);
		});
		
		$('.filterGridDevice').off('change');
		$('.filterGridDevice').on('change', function(){
			changeGridFilterDevice(modul);
		});
	}
}

function changeGridFilterCountry(modul){
	aPage.moduls[modul].activeCountry = $('.modul[data-modul="' + modul + '"] .filterGridCountry option:selected').val();
	
	$('.modul[data-modul="' + modul + '"] .filterGridLanguage').html('');
	
	if(aPage.moduls[modul].activeCountry == 0){
		var text = (aText.alllanguages != undefined) ? aText.alllanguages : 'alllanguages';
		$('.modul[data-modul="' + modul + '"] .filterGridLanguage').append('<option value="0">' + text + '</option>');
	}else{
		var aLanguages = aUser.countries[aPage.moduls[modul].activeCountry].languages;
		for (var i = 0; i < aLanguages.length; i++) {
			var key = aLanguages[i];
			var text = (aText[aUser.languages[key].language] != undefined) ? aText[aUser.languages[key].language] : aUser.languages[key].language;
			var sel = '';
			if(key == aPage.moduls[modul].activeLanguage) sel = 'selected';
			$('.modul[data-modul="' + modul + '"] .filterGridLanguage').append('<option value="' + key + '">' + text + '</option>');
		}
	}
	
	aPage.moduls[modul].activeLanguage = $('.filterGridLanguage option:selected').val();
	
	initGridFilter(modul);
	changeGridFilter(modul);
}

function changeGridFilterLanguage(modul){
	aPage.moduls[modul].activeLanguage = $('.modul[data-modul="' + modul + '"] .filterGridLanguage option:selected').val();
	
	changeGridFilter(modul);
}

function changeGridFilterDevice(modul){
	aPage.moduls[modul].activeDevice = $('.modul[data-modul="' + modul + '"] .filterGridDevice option:selected').val();
	
	changeGridFilter(modul);
}

function changeGridFilter(modul){
	if(aSystem.synchronizeGridFilter == 1){
		aUser.activeCountry = aPage.moduls[modul].activeCountry;
		aUser.activeLanguage = aPage.moduls[modul].activeLanguage;
		aUser.activeDevice = aPage.moduls[modul].activeDevice;
		
		for(key in aPage.moduls){
			aPage.moduls[key].activeCountry = aPage.moduls[modul].activeCountry;
			aPage.moduls[key].activeLanguage = aPage.moduls[modul].activeLanguage;
			aPage.moduls[key].activeDevice = aPage.moduls[modul].activeDevice;
		}
		
		var userconfig = buildUserconfig();
		$.ajax({  
			url: '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu_sys-user-save.php',    
			type: 'post',          
			data: '',       
			cache: false,
			headers: {
				csrfToken: Cookies.get('csrf'),
				user: JSON.stringify(userconfig)
			},
			success: function(result, status, jqXHR){
				var cb = $('.modul[data-modul="' + modul + '"] .tabGridFilter').attr('data-cb');
				window[cb](modul);
			}
		});
	}
}


// ##################################################################


function changeGridFilterSysCountry(modul){
	aPage.moduls[modul].activeSysCountry = $('.filterGridCountry option:selected').val();
	
	$('.modul[data-modul="' + modul + '"] .filterGridLanguage').html('');
	
	if(aPage.moduls[modul].activeSysCountry == 0){
		var text = (aText.alllanguages != undefined) ? aText.alllanguages : 'alllanguages';
		$('.modul[data-modul="' + modul + '"] .filterGridLanguage').append('<option value="0">' + text + '</option>');
	}else{
		var aLanguages = aUser.syscountries[aPage.moduls[modul].activeSysCountry].languages;
		for (var i = 0; i < aLanguages.length; i++) {
			var key = aLanguages[i];
			var text = (aText[aUser.syslanguages[key].language] != undefined) ? aText[aUser.syslanguages[key].language] : aUser.syslanguages[key].language;
			var sel = '';
			if(key == aPage.moduls[modul].activeSysLanguage) sel = 'selected';
			$('.modul[data-modul="' + modul + '"] .filterGridLanguage').append('<option value="' + key + '">' + text + '</option>');
		}
	}
	
	aPage.moduls[modul].activeSysLanguage = $('.filterGridLanguage option:selected').val();
	
	initGridFilter(modul);
	changeGridFilterSys(modul);
}

function changeGridFilterSysLanguage(modul){
	aPage.moduls[modul].activeSysLanguage = $('.filterGridLanguage option:selected').val();
	
	changeGridFilterSys(modul);
}

function changeGridFilterSysDevice(modul){
	aPage.moduls[modul].activeSysDevice = $('.filterGridDevice option:selected').val();
	
	changeGridFilterSys(modul);
}

function changeGridFilterSys(modul){
	if(aSystem.synchronizeGridFilter == 1){
		aUser.activeSysCountry = aPage.moduls[modul].activeSysCountry;
		aUser.activeSysLanguage = aPage.moduls[modul].activeSysLanguage;
		aUser.activeSysDevice = aPage.moduls[modul].activeSysDevice;
		
		for(key in aPage.moduls){
			aPage.moduls[key].activeSysCountry = aPage.moduls[modul].activeSysCountry;
			aPage.moduls[key].activeSysLanguage = aPage.moduls[modul].activeSysLanguage;
			aPage.moduls[key].activeSysDevice = aPage.moduls[modul].activeSysDevice;
		}

		var userconfig = buildUserconfig();
		$.ajax({  
			url: '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu_sys-user-save.php',    
			type: 'post',          
			data: '',       
			cache: false,
			headers: {
				csrfToken: Cookies.get('csrf'),
				user: JSON.stringify(userconfig)
			},
			success: function(result, status, jqXHR){
				var cb = $('.modul[data-modul="' + modul + '"] .tabGridFilter').attr('data-cb');
				window[cb](modul);
			}
		});
	}
}
