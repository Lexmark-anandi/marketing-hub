$(document).ready(function(){ 
	waiting('body', '&nbsp;');

	var objCookie = Cookies.getJSON('deeplink');
	if(objCookie == undefined){
		objCookie = {};
	}else{
		if(objCookie.page != undefined && objCookie.page != ''){
			if(objCookie.country != undefined && objCookie.country != ''){
				var objChange = {};
				objChange['id_countid'] = objCookie.country;
				objChange['id_langid'] = objCookie.language;
				changeCookie('activesettings', objChange);
			}


			var objChange = {};
			objChange['page'] = '';
			objChange['country'] = '';
			objChange['language'] = '';
			//objChange['data'] = '';
//			objChange['function'] = '';
			changeCookie('deeplink', objChange);
		}
	}




	$.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-jsvars-init.php?initLogin=1',    
		type: 'post',          
		data: '',       
		cache: false,
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify({})
		},
		success: function(result, status, jqXHR){
			var obj = JSON.parse(result);
			objUser = obj.user;
			objText = obj.text;
			var objSettings = Cookies.getJSON('activesettings');

			$.datepicker.setDefaults($.datepicker.regional[objSettings.systemLanguage]);
			$.datepicker.setDefaults({
				dateFormat: objUser.syscountries[objSettings.systemCountry].date_format.toLowerCase().replace('yyyy','yy')
			});
//			$.timepicker.setDefaults($.timepicker.regional[userLanguage]);
			
			sessionWarningSessionTimeout();
			unwaiting();

			if(objCookie.page == undefined || objCookie.page == ''){
				loadPage($('#navigation div[data-pageid="' + Cookies.getJSON('activesettings').id_page + '"]'));
			}else{
				loadPage($('#navigation div[data-pageid="' + objCookie.page + '"]'));
			}
		}
	});

	initScreen();
	initNavigation();
	initBreadcrumb();
});


$(window).resize(function() {
	initScreen();
	modulResize();
//	formResize();
});


function initScreen(){
	mode = ($('#navigation').css('position') == 'fixed') ? 'mobile' : 'desktop';

	screenWidth = $(window).width();
	screenHeight = $(window).height();
	
	objSystem.widthDialogForm = (mode == 'mobile') ? screenWidth : objSystem.widthDialogFormOrg;
	objSystem.widthDialogConfirm = (mode == 'mobile') ? screenWidth : objSystem.widthDialogConfirmOrg;
}
