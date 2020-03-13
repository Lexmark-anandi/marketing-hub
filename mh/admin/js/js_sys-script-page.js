function loadPage(el, objHis){
//	waiting('body');

	var id_page = $(el).attr('data-pageid');
	
	// ## set cookie ##
	var objChange = {};
	objChange['id_page'] = id_page;
	changeCookie('activesettings', objChange);

	// ## clean tempdata ##
//	var objC = {};
//	objC.moduls = [];
//	objC.id_mod_parent = [];
//			var modulpath = $('[data-modulpath]:first').attr('data-modulpath');
//			alert(modulpath)
//			var objModul = splitModulpath(modulpath);
	clearData();

	$.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-pageload.php' + initLogin,    
		type: 'post',          
		data: '',       
		cache: false,
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);
			initLogin = '';


			var objResult = JSON.parse(result);
//			aPage = objResult.page;
			
			// ## load content ##
			$('#content').html(objResult.content);
			
			// ## set active menue item ##
			$('#navigation .navActive').removeClass('navActive');
			$('#navigation .navActiveMain').removeClass('navActiveMain');
			$('#navigation div[data-pageid="' + Cookies.getJSON('activesettings').id_page + '"]').addClass('navActive');
			$('#navigation > ul > li:has(div.navActive)').addClass('navActiveMain');
			if(mode == 'mobile') closeMenue();
			//if(mode == 'mobile') closeSubmenue();
			
			var objB = {};
			objB.id_page = id_page;
			objB.id_data = 0;
			objB.id_mod = 0;
			objB.id_mod_parent = 0;
			objB.modulpath = id_page + '-0-0';
			loadBreadcrumb(objB);
			
			// ## set entry for history ##
			setHistory(objHis);
			
			// ## load modul and scripts and call ready-function ##
			loadModul();
		}
	});  
}

