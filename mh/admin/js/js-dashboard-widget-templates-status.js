(function () {
    window['f_##modul_name##']['dashboardwidgettemplatesstatus_Ready'] = function (obj, data, el) { 
		// request translations (templates)
		$('.dashboardListitemButton .fa').off('click');
		$('.dashboardListitemButton .fa').on('click', function(){
			window['f_##modul_name##']['dashboardwidgettemplatesstatus_SwitchListTransRegCount'](obj, this);
		});

		$('.dashboardListitemCountriesButton button').off('click');
		$('.dashboardListitemCountriesButton button').on('click', function(){
			window['f_##modul_name##']['dashboardwidgettemplatesstatus_RequestTranslationReminder'](obj, this);
		});
 	}; 



   window['f_##modul_name##']['dashboardwidgettemplatesstatus_SwitchListTransRegCount'] = function (obj, el) { 
		$(el).parents('.dashboardListitemRow').find('.listTransReqCount').toggleClass('listTransReqCountFull');
		if($(el).hasClass('fa-caret-down')){
			$(el).removeClass('fa-caret-down');
			$(el).addClass('fa-caret-up');
		}else{
			$(el).removeClass('fa-caret-up');
			$(el).addClass('fa-caret-down');
		}
	}; 



    window['f_##modul_name##']['dashboardwidgettemplatesstatus_RequestTranslationReminder'] = function (obj, el) { 
		var objDialog = {};
		objDialog.el = el;
		objDialog.title = objText.requestTranslation;
		objDialog.formtext = objText.requestTranslationCheck;
	
		objDialog.objButtons = {};
		objDialog.objButtons[objText.Cancel] = function() {closeDialog(obj, this);}            
		objDialog.objButtons[objText.requestTranslation] = function() { window['f_##modul_name##']['dashboardwidgettemplatesstatus_RequestTranslationReminderDo'](obj, el);}
		
		openDialogMessage(obj, objDialog);
	};
	

    window['f_##modul_name##']['dashboardwidgettemplatesstatus_RequestTranslationReminderDo'] = function (obj, el) { 
		waiting('.ui-dialog');
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		var temp = $(el).closest('.dashboardListitemRow').attr('data-temp');
		var prom = $(el).closest('.dashboardListitemRow').attr('data-prom');
		
		var url = objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-transrequest.php';
		var data = 'reminder=' + temp;
		
		if(prom != 0){
			url = objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-promotions-transrequest.php';
			data = 'reminder=' + prom;
		}
		
		$.ajax({  
			url: url, 
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

				closeDialog(obj)
				unwaiting('.ui-dialog');
			}
		});
	};
})();

