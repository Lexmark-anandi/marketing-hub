(function () {
	// #####################################################
	// Callback fuction for misc functions (may be overwritten in special js-file)
	// #####################################################
	
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
	
    window['f_##modul_name##']['cbLoadFormSuccess'] = function (obj) { 
	};
	
    window['f_##modul_name##']['cbLoadDataSuccess'] = function (obj) { 
	};
	
    window['f_##modul_name##']['cbSaveDataSubmitSuccess'] = function (obj) { 
	};
	
    window['f_##modul_name##']['cbModulResize'] = function (obj) { 
	};
	
    window['f_##modul_name##']['cbDialogFormOpen'] = function (obj) { 
	};
		
		
		
		


})();

