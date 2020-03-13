function loadModul(obj){
	if(obj == undefined){
		// ## load all moduls ##
		$('.modul').each(function(){
			var modulpath = $(this).attr('data-modulpath');
			var objModul = splitModulpath(modulpath);

			loadModul(objModul);
		});
	}else{
		// ## load special modul ##
		modulResize(obj);


////				aPage.moduls[modul].activeCountry = aUser.activeCountry;
////				aPage.moduls[modul].activeLanguage = aUser.activeLanguage; 
////				aPage.moduls[modul].activeDevice = aUser.activeDevice;
////				aPage.moduls[modul].activeSysCountry = aUser.activeSysCountry;
////				aPage.moduls[modul].activeSysLanguage = aUser.activeSysLanguage;
////				aPage.moduls[modul].activeSysDevice = aUser.activeSysDevice;
		
		initModulFilter(obj);
		$('#modul_' + obj.modulpath + ' .gridExpandAll').off('click');
		$('#modul_' + obj.modulpath + ' .gridExpandAll').on('click', function(){
			expandRowAll(obj, this);
		});
		
		$('#grid_' + obj.modulpath + ' .gridMenueFunctions').off('click');
		$('#grid_' + obj.modulpath + ' .gridMenueFunctions').on('click', function(){
			gridMenueFunctionsMobile(obj, this);
		});
		
		$('#grid_' + obj.modulpath + ' .gridMenueFilter').off('click');
		$('#grid_' + obj.modulpath + ' .gridMenueFilter').on('click', function(){
			gridMenueFilterMobile(obj, this);
		});


		
		var data = '';

		if(!window['f_' + obj.modul_name]){
			$.ajax({  
				url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-script-build.php',    
				type: 'post',          
				data: data,       
				cache: false,
				headers: {
					csrfToken: Cookies.get('csrf'),
					page: JSON.stringify(obj)
				},
				dataType: 'script',
				success: function (result, status, jqXHR) {
					actualizeStatus(result, status);
					unwaiting();

					window['f_' + obj.modul_name]['ready'](obj);
				}
			});
		}else{
			unwaiting();
			window['f_' + obj.modul_name]['ready'](obj);
		}
	}
}


function modulResize(obj){
	if(obj == undefined){
		// ## resize all moduls ##
		$('.modul, .childmodul').each(function(){
			var modulpath = $(this).attr('data-modulpath');
			var objModul = splitModulpath(modulpath);

			if($.isNumeric(objModul.id_mod)) modulResize(objModul);
		});
	}else{
		// ## resize special modul ##
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		
		if(obj.id_mod_parent == 0){
			var contentWidth = (obj.id_mod_parent == 0) ? $('#content').width() : $('#modul_' + obj.modulpath).closest('.formRightInner').width();
			var contentHeight = (obj.id_mod_parent == 0) ? $('#content').height() : $('#modul_' + obj.modulpath).closest('.formRightInner').height() - $('#modul_' + obj.modulpath).closest('.formRightInner').find('.formTabs').outerHeight(true);
			
			var modulWidth = Math.floor((contentWidth / 100) * objModul.modul_width);
			var modulHeight = Math.floor((contentHeight / 100) * objModul.modul_height);
			$('#modul_' + obj.modulpath).css('width', modulWidth + 'px');
			$('#modul_' + obj.modulpath).css('height', modulHeight + 'px');
		}
		
		// ## resize grid ##
		if(objModul.specifications[11] == 9) gridResize(obj);
		
		if(window['f_' + obj.modul_name] != undefined) if(window['f_' + obj.modul_name].cbModulResize && typeof(window['f_' + obj.modul_name].cbModulResize) === 'function') window['f_' + obj.modul_name].cbModulResize(obj);
	}
}


function showChildmodul(obj, el){
	if($(el).is('[data-modulpath]')){
		if($('#modul_' + obj.modulpath + ' .formRight .formTabs li').length > 0){
			var modulpath = $(el).attr('data-modulpath');
			var objM = splitModulpath(modulpath);
			var objP = splitModulpathParent(modulpath);
		
			var objChild = Object.assign({},obj);
			objChild.id_mod = objM.id_mod;
			objChild.id_mod_parent = objM.id_mod_parent;
			objChild.id_data_parent = obj.id_data;
			objChild.modul_name = objM.modul_name;
			objChild.modulpath = modulpath;
			
			$('#modul_' + objP.modulpath + ' .childmodul').addClass('hidden');
			$('#modul_' + modulpath).removeClass('hidden');
			
			// ## synchronize filter settings for childmoduls ##
			var objModulSrc = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		
			for(var keyModul in objUser.childmoduls[obj.id_mod]){
				var objModul = objUser.childmoduls[obj.id_mod][keyModul];
				if((objSystem.synchronizeModulFilter == 1 && objModul.specifications[12] == 9 && objModul.specifications[9] == objModulSrc.specifications[9]) || keyModul == 'i_' + obj.id_mod){
					objModul.activeSettings.selectCountry = objModulSrc.activeSettings.formCountry;
					objModul.activeSettings.selectLanguage = objModulSrc.activeSettings.formLanguage;
					objModul.activeSettings.selectDevice = objModulSrc.activeSettings.formDevice;
					objModul.activeSettings.formCountry = objModulSrc.activeSettings.formCountry;
					objModul.activeSettings.formLanguage = objModulSrc.activeSettings.formLanguage;
					objModul.activeSettings.formDevice = objModulSrc.activeSettings.formDevice;
					objUser.childmoduls[obj.id_mod][keyModul] = objModul;
				}
			}
				
			if(!$('#modul_' + modulpath).hasClass('loaded')){
				loadModul(objChild);
				$('#modul_' + modulpath).addClass('loaded');
				$('#modul_' + modulpath).addClass('reload');
			}else{
				unwaiting();
				if(!$('#modul_' + modulpath).hasClass('reload') && window['f_' + objChild.modul_name] != undefined){
					if(window['f_' + objChild.modul_name]['readyReload'] != undefined && typeof(window['f_' + objChild.modul_name]['readyReload']) === 'function') window['f_' + objChild.modul_name]['readyReload'](objChild);
				}
			}
		}
	}
}


function splitModulpath(path){
//	alert(splitModulpath.caller.name);
	var aModulpath = path.split(objSystem.delimiterPathAttr);
	var objModul = {};
	
	objModul.id_page = aModulpath[0];
	objModul.id_mod = aModulpath[(aModulpath.length - 1)];
	objModul.id_mod_parent = aModulpath[(aModulpath.length - 2)];
	objModul.modul_name = '';
	if(objModul.id_mod != 0 && $.isNumeric(objModul.id_mod)){
		if(objUser.pages2moduls[objModul.id_page].moduls['i_' + objModul.id_mod] != undefined || objUser.childmoduls[objModul.id_mod_parent]['i_' + objModul.id_mod] != undefined){
			objModul.modul_name = (objModul.id_mod_parent == 0) ? objUser.pages2moduls[objModul.id_page].moduls['i_' + objModul.id_mod].modul_name : objUser.childmoduls[objModul.id_mod_parent]['i_' + objModul.id_mod].modul_name;
		}else{
			objModul.modul_name = objModul.id_mod;
		}
	}
	objModul.modulpath = path;
	
	return objModul;
}


function splitModulpathParent(path){
	var aModulpath = path.split(objSystem.delimiterPathAttr);
	aModulpath.pop();
	aModulpath.pop();
	var objModul = {};
	
	objModul.id_page = aModulpath[0];
	objModul.id_mod = aModulpath[(aModulpath.length - 1)];
	objModul.id_mod_parent = aModulpath[(aModulpath.length - 2)];
	objModul.modul_name = '';
	if(objModul.id_mod != 0) objModul.modul_name = (objModul.id_mod_parent == 0) ? objUser.pages2moduls[objModul.id_page].moduls['i_' + objModul.id_mod].modul_name : objUser.childmoduls[objModul.id_mod_parent]['i_' + objModul.id_mod].modul_name;
	objModul.modulpath = aModulpath.join('-');
	
	return objModul;
}

