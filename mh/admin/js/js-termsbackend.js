(function () {
	// #####################################################
	// Export
	// #####################################################
    window['f_##modul_name##']['rowExport'] = function (obj, el) { 
		waiting('body');
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		
		var data = '';
		
		$.ajax({  
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-termsbackend-export.php',    
			type: 'post',          
			data: data,       
			cache: false,
			headers: {
				csrfToken: Cookies.get('csrf'),
				page: JSON.stringify(obj),
				settings: JSON.stringify(objModul.activeSettings)
			},
			success: function (result, status, jqXHR) {
				actualizeStatus(result, status);
	
				var objResult = JSON.parse(result);
				
				downloadMedia(objResult.filesys_filename, objResult.filename, objResult.folder, 'export');
				unwaiting();
			}
		});
	};

		
	// #####################################################
	// Export
	// #####################################################
    window['f_##modul_name##']['rowImport'] = function (obj, el) { 
		var objDialog = {};
		objDialog.el = el;
		objDialog.title = objText.Import;
		objDialog.urlForm = objSystem.directoryInstallation + objSystem.pathFormsAdmin + 'fo-termsbackend-import.php';
	
		objDialog.objButtons = {};
		objDialog.objButtons[objText.Cancel] = function() {closeDialog(obj, this);}            
		objDialog.objButtons[objText.Import] = function() { window['f_##modul_name##']['sendImport'](obj, this);}
	
		openDialogForm(obj, objDialog);
	};

    window['f_##modul_name##']['cbDialogFormOpen'] = function (obj) { 
		var target = '.dialogOuter:last';
		
		$(target + ' .fileupload').each(function(){
			var fieldid = $(this).attr('id');
			var fieldname = $(this).attr('name');
			var label = objText.selectFile;
			if($(target + ' #' + fieldid + '[multiple]').length > 0) label = objText.selectFiles;
			
			// DIV for existing files
			$(this).before('<div data-name="' + fieldname + 'T" class=""></div>'); 
	
			$(this).before('<div class="textfield textfieldUpload" onclick="uploadSelection(\'' + target + '\', \'' + fieldid + '\')"><input type="button" class="formButton formButtonUpload" value="' + label + '" /></div>'); 
		});
		
		obj.cbSendFiles = 'doImport';
		initFieldsUpload(obj, '.dialogOuter:last')		
	};
	

    window['f_##modul_name##']['sendImport'] = function (obj, el) { 
		waiting('body');
		var target = '.dialogOuter:last';
		$(target).append('<div class="uploadOverlay"></div>');
		$(target + ' .fileUploadOuter').clone().appendTo(target + ' .uploadOverlay');

		sendDataFiles(obj, target);
	};
	

    window['f_##modul_name##']['doImport'] = function (obj, objResultFiles) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		var data = 'files=' + JSON.stringify(objResultFiles);
	
		$.ajax({
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-termsbackend-import.php',       
			type: 'post',          
			data: data,       
			cache: false,  
			headers: {
				csrfToken: Cookies.get('csrf'),
				page: JSON.stringify(obj),
				settings: JSON.stringify(objModul.activeSettings)
			},
			success: function (result) {
				closeDialog(obj);
				unwaiting();
			}
		});
	};


		


		
		


})();


