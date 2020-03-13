window['f_##modul_name##'] = (function () {
	return {
		ready : function (obj){
			// ## build grid ##
			var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
			if(objModul.specifications[11] == 9) buildGrid(obj);
		},
		
		readyReload : function (obj){
			// ## build grid ##
			var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
			if(objModul.specifications[11] == 9) reloadGrid(obj);
		},
		
		
		// #####################################################
		// New Form
		// #####################################################
		rowAdd : function (obj, el){
			var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

			obj.id_data = 0;
			obj.id_data_parent = 0; 
			if(obj.id_mod_parent != 0){
				var objModulParent = splitModulpathParent(obj.modulpath);
				obj.id_data_parent = $('#gridTable_' + objModulParent.modulpath + ' tr.selectedDataset').attr('id');
			}
			
			$('#' + objModul.gridTable + ' tr.selectedDataset').removeClass('selectedDataset'); 
			loadForm(obj, el);
		},
		
		
		// #####################################################
		// Edit Form
		// #####################################################
		rowEdit : function (obj, el){
			var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

			obj.id_data_parent = 0;
			if(obj.id_mod_parent != 0){
				var objModulParent = splitModulpathParent(obj.modulpath);
				obj.id_data_parent = $('#gridTable_' + objModulParent.modulpath + ' tr.selectedDataset').attr('id');
			}

			$('#' + objModul.gridTable + ' tr.selectedDataset').removeClass('selectedDataset'); 
			$('#' + objModul.gridTable + ' tr[id="' + obj.id_data + '"]').addClass('selectedDataset');
			loadForm(obj, el);
		},
		
		
		// #####################################################
		// Copy Form
		// #####################################################
		rowCopy : function (obj, el){ 
			waiting('#modul_' + obj.modulpath);
			var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

			var objDialog = {};
			objDialog.el = el;
			objDialog.title = objText.Copy;
			objDialog.formtext = objText.copyCheck;
			
			objDialog.objButtons = {};
			objDialog.objButtons[objText.Cancel] = function() {closeDialog(obj, this);}            
			objDialog.objButtons[objText.Copy] = function() {window['f_##modul_name##'].rowCopyDo(obj, this);}
			
			openDialogAlert(obj, objDialog);
		},
		
		
		rowCopyDo : function (obj, el){
            closeDialog(obj, el); 
			waiting('#modul_' + obj.modulpath);
			var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

			obj.id_data_parent = 0;
			if(obj.id_mod_parent != 0){
				var objModulParent = splitModulpathParent(obj.modulpath);
				obj.id_data_parent = $('#gridTable_' + objModulParent.modulpath + ' tr.selectedDataset').attr('id');
			}

			$('#' + objModul.gridTable + ' tr.selectedDataset').removeClass('selectedDataset'); 
			
			var data = '';
	
			$.ajax({  
				url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-default-copy.php', 
				type: 'post',          
				data:  data,       
				cache: false,  
				headers: {
					csrfToken: Cookies.get('csrf'),
					page: JSON.stringify(obj),
					settings: JSON.stringify(objModul.activeSettings)
				},
				success: function (result, status, jqXHR) {
					actualizeStatus(result, status);
					reloadGrid(obj);
					unwaiting('#modul_' + obj.modulpath);
					
					var objC = Object.assign({},obj);
					objC.id_data = result;
	
					$('#' + objModul.gridTable + ' tr[id="' + obj.id_data + '"]').addClass('selectedDataset');
					loadForm(objC, el);
				}
			});
		},


////		// #####################################################
////		// Read Form
////		// #####################################################
////		rowRead : function (id, el){
////			systemmode = 'read';
////			var data = 'id=' + id;
////			data += '&idModul=' + aSpecsPage.idModul;
////			data += '&type=read';
////			data += '&idPageParent=' + idPageParent;
////			data += '&idDataParent=' + idDataParent;
////			data += '&idModulParent=' + idModulParent;
////			data += '&primeryfieldDataParent=' + primeryfieldDataParent;
////			
////			$('#' + aSpecsPage.idGridTable + ' tr[id="'+id+'"]').addClass('selectedDataset');
////			loadForm(id, aSpecsPage.urlForm, aSpecsPage.urlRead, data, 'read');
////		},
//		
//		
//		// #####################################################
//		// Export Dataset
//		// #####################################################
//		rowExport : function (modul, id, el){
//			var obj = {};
//			obj.modul = modul;
//			obj.id = id;
//			obj.el = '';
//			obj.title = aText.exportExport;
//			obj.urlForm = '/' + aSystem.directorySystem + aSystem.pathFormsAdmin + 'fo-' + aPage['moduls'][modul].modulname + '-export.php';
//	//		obj.cb = 'initFieldsUpload';
//	//		obj.target = '.dialogOuter:last';
//	//		obj.cbSendFiles = 'doImport';
//			
//			aButtons = {};
//			aButtons[aText.Cancel] = function() { closeDialog(obj, this); }            
//			aButtons[aText.Export] = function() { window['f_aPage.modul']['doRowExport'](obj, this); }
//			
//			openDialogForm(obj, aButtons);
//		},
//
//
//		doRowExport : function (obj, el){
//			waiting('body');
//			$('#tmpErrorExport').remove();
//			
//			var error = 0;
//			error = window['f_aPage.modul']['checkRowExport'](obj, el);
//			
//			if(error == 0){
//				$('.exportForm').ajaxSubmit({
//					url: '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu-' + aPage['moduls'][obj.modul].modulname + '-export.php',       
//					type: 'post',          
//					data: obj,       
//					cache: false,  
//					headers: {
//						csrfToken: Cookies.get('csrf'),
//						page: JSON.stringify(aPage)
//					},
				//success: function (result, status, jqXHR) {
//					actualizeStatus(result, status);
//	
//					}
//				});
//				closeDialog(obj);
//				unwaiting();
//		
//		
//				var modul = obj.modul;
//				var obj = {};
//				obj.modul = modul;
//				obj.title = aText.Export;
//				obj.message = aText.exportMessage;
//		
//				aButtons = {};
//				aButtons[aText.Close] = function() { closeDialog(obj, this); }            
//				
//				openDialogMessage(obj, aButtons);
//			}else{
//				$(el).closest('.ui-dialog').find('.ui-dialog-buttonpane').append('<div style="clear:both" id="tmpErrorExport"><div class="messageError" style="font-weight:bold; float:right"><span><span class="errorIcon"></span><span class="errorText">' + aText.errorText + '</span></span></div></div>');
//				
//				unwaiting();
//			}
//		},
//
//
//		checkRowExport : function (obj, el){
//			return '0';
//		},
//		
////		// #####################################################
////		// CB Load Form Start
////		// #####################################################
////		cbLoadFormStart : function (id){
////		},
////
////
////		// #####################################################
////		// CB Load Form Complete
////		// #####################################################
////		cbLoadFormComplete : function (id){
////			unwaiting('#' + aSpecsPage.idContent);
////		},
//
//
		// #####################################################
		// Delete row
		// #####################################################
		rowDelete : function (obj, el){ 
			var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];


////			systemmode = 'edit';
////			var data = 'id=' + id;
////			data += '&idModul=' + aSpecsPage.idModul;
////			data += '&type=delete';
////			data += '&idPageParent=' + idPageParent;
////			data += '&idDataParent=' + idDataParent;
////			data += '&idModulParent=' + idModulParent;
////			data += '&primeryfieldDataParent=' + primeryfieldDataParent;
////			
////			
////			$('#' + aSpecsPage.idGridTable + ' tr.selectedDataset').removeClass('selectedDataset');
////			openAlertDialog(title, aSpecsPage.urlRead, data, aTextScript.tDeleteCheck, aTextScript.fDeleteField, aButtons, 'delete', el);
//
//
			var objDialog = {};
//			obj.mode = 'varGrid';
//			obj.modul = modul;
//			obj.id = id;
			objDialog.el = el;
			objDialog.title = objText.Delete;
			objDialog.formtext = objText.deleteCheck;
//			obj.urlRead = '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu-' + aPage['moduls'][modul].modulname + '-read.php';
//			obj.urlDelete = '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu-' + aPage['moduls'][modul].modulname + '-delete.php';
			
			objDialog.objButtons = {};
			objDialog.objButtons[objText.Cancel] = function() {closeDialog(obj, this);}            
			objDialog.objButtons[objText.Delete] = function() {sendDelete(obj, this);}
			
			$('#' + objModul.gridTable + ' tr.selectedDataset').removeClass('selectedDataset');  
			openDialogAlert(obj, objDialog);
		},


		// #####################################################
		// Delete row
		// #####################################################
		rowRanking : function (obj, el){ 
		},


		// #####################################################
		// Save 
		// #####################################################
		sendForm : function (obj, action){
//			var obj = {};
//			obj.modul = modul;
			obj.action = action;
//			obj.id = $('.modul[data-modul="' + obj.modul + '"] .formLeft .field_id').val();
//			obj.formCountry = $('.modul[data-modul="' + obj.modul + '"] .filterFormCountry option:selected').val();
//			obj.formLanguage = $('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage option:selected').val();
//			obj.formDevice = $('.modul[data-modul="' + obj.modul + '"] .filterFormDevice option:selected').val();
//			if(obj.formCountry == undefined) obj.formCountry = 0;
//			if(obj.formLanguage == undefined) obj.formLanguage = 0;
//			if(obj.formDevice == undefined) obj.formDevice = 0;
			obj.cb_readData = sendTemp;
			obj.cb_sendTemp = saveData;
			
			readData(obj);
	
////			// ### Check form
////			var error = 0;
////			// #####################################################
////			
////			if(error == 0){
////				$('#' + aSpecsPage.idFormOuter + ' .fieldData').val(JSON.stringify(formData));
////				sendData(action);
////			}else{ 
////				showErrors(); 
////			}
		},


		// #####################################################
		// Edit File 
		// #####################################################
		fileEdit : function (idFile, el){
			alert('edit');
		},


		// #####################################################
		// Crop File 
		// #####################################################
		fileCrop : function (idFile, el){
			alert('crop');
		},


		// #####################################################
		// Delete File 
		// #####################################################
		fileDelete : function (idFile, el){
			alert('löschen');
////			var data =  'id=' + id;
////			data += '&idModul=' + aSpecsPage.idModul;
////			data += '&url=' + aSpecsPage.urlUpdate;
//			
//			$.ajax({  
//				url: '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu-' + aPage['moduls'][obj.modul].modulname + '-delete-media.php',    
//				type: 'post',          
//				data:  'data=' + JSON.stringify(obj),       
//				cache: false,  
//				headers: {
//					csrfToken: Cookies.get('csrf'),
//					page: JSON.stringify(aPage)
//				},
				//success: function (result, status, jqXHR) {
//					actualizeStatus(result, status);
//	
//				}
//			});
		},


//		// #####################################################
//		// Export Form
//		// #####################################################
//		dataExport : function (modul, el){
////			var title = aTextScript.tExport;
////			
////			var data = 'id_grid_d=' + aGrid.id_grid_d;
////			data += '&idModul=' + aSpecsPage.idModul;
////			data += '&idModulParent=' + idModulParent;
////			data += '&idPageParent=' + idPageParent;
////			data += '&idDataParent=' + idDataParent;
////			data += '&type=export';
////			data += '&primeryfieldDataParent=' + primeryfieldDataParent;
//			
//			var obj = {};
//			obj.modul = modul;
//			obj.el = el;
////			obj.title = aText.gridTitleExportData;
////			obj.urlForm = '/' + aSystem.directorySystem + aSystem.pathFormsAdmin + 'fo_sys-export.php';
//			
//			sendExport(obj, el);
//			
////			obj.urlDelete = '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu-' + aPage['moduls'][modul].modulname + '-delete.php';
////			obj.action = action;
////			obj.id = $('.modul[data-modul="' + obj.modul + '"] .formLeft .field_id').val();
////			obj.formCountry = $('.modul[data-modul="' + obj.modul + '"] .filterFormCountry option:selected').val();
////			obj.formLanguage = $('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage option:selected').val();
////			obj.formDevice = $('.modul[data-modul="' + obj.modul + '"] .filterFormDevice option:selected').val();
////			if(obj.formCountry == undefined) obj.formCountry = 0;
////			if(obj.formLanguage == undefined) obj.formLanguage = 0;
////			if(obj.formDevice == undefined) obj.formDevice = 0;
////			obj.cb_readData = sendTemp;
////			obj.cb_sendTemp = saveData;
//
////			var aButtons = {};
////			aButtons[aText.Cancel] = function() { closeDialog(obj, this); }            
////			aButtons[aText.Export] = function() { window['f_' + aPage['moduls'][obj.modul]]['checkExport'](obj, this); }
////			
////			openDialogForm(obj, aButtons);
//		},
//
//
//		// #####################################################
//		// Export Do
//		// #####################################################
//		checkExport : function (obj, el){
////			sendExport(idPageParent, idDataParent);
//		},
//
//
//		// #####################################################
//		// Import Form
//		// #####################################################
//		dataImport : function (modul, el){
//			var obj = {};
//			obj.modul = modul;
//			obj.el = el;
//			obj.title = aText.Import;
//			obj.urlForm = '/' + aSystem.directorySystem + aSystem.pathFormsAdmin + 'fo-' + aPage['moduls'][modul].modulname + '-import.php';
//			obj.urlRead = '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu-' + aPage['moduls'][modul].modulname + '-read.php';
//			obj.url = '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu-' + aPage['moduls'][modul].modulname + '-import.php';
//			obj.cb = 'initFieldsUpload';
//			obj.target = '.dialogOuter:last';
//			obj.cbSendFiles = 'doImport';
//			
//			aButtons = {};
//			aButtons[aText.Cancel] = function() { closeDialog(obj, this); }            
//			aButtons[aText.Import] = function() { window['f_aPage.modul']['sendImport'](obj, this); }
//			
//			openDialogForm(obj, aButtons);
//
//
//
//
////			var title = aTextScript.tImport;
////			
////			var data = 'id_grid_d=' + aGrid.id_grid_d;
////			data += '&idModul=' + aSpecsPage.idModul;
////			data += '&idModulParent=' + idModulParent;
////			data += '&idPageParent=' + idPageParent;
////			data += '&idDataParent=' + idDataParent;
////			data += '&type=import';
////			data += '&primeryfieldDataParent=' + primeryfieldDataParent;
////			
////			var aButtons = {};
////			aButtons[aText.Cancel] = function() { closeDialog(); }            
////			aButtons[aText.Import] = function() { sendDataFiles(aSpecsPage.urlInsert, aSpecsPage.urlRead, 'matchImport') }
////			
////			openFormDialog(title, data, aSpecsPage.urlImportForm, aButtons, el, function(){initFieldsUploadTmp(el, '#diaForm');});
//		},
//		
//		
//		sendImport : function (obj, el) { 
//			waiting('body');
//			sendDataFiles(obj);
//		},
//		
//		
//		doImport : function (obj) { 
//			var data = 'files=' + $('.dialogOuter:last [name="formdata"]').val()
//			data += '&modul=' + obj.modul;
//		
//			$.ajax({
//				url: '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu-' + aPage['moduls'][obj.modul].modulname + '-import.php',       
//				type: 'post',          
//				data: data,       
//				cache: false,  
//				headers: {
//					csrfToken: Cookies.get('csrf'),
//					page: JSON.stringify(aPage)
//				},
				//success: function (result, status, jqXHR) {
//					actualizeStatus(result, status);
//		
//				}
//			});
//			closeDialog(obj);
//			unwaiting();
//	
//	
//			var modul = obj.modul;
//			var obj = {};
//			obj.modul = modul;
//			obj.title = aText.Import;
//			obj.message = aText.importPricefilesMessage;
//	
//			aButtons = {};
//			aButtons[aText.Close] = function() { closeDialog(obj, this); }            
//			
//			openDialogMessage(obj, aButtons);
//		}
//
//
////
////
////		// #####################################################
////		// Import Matching Form
////		// #####################################################
////		matchImport : function (el, objFiles){
////			closeDialog();
////
////			var title = aTextScript.tImport;
////			
////			var data = 'id_grid_d=' + aGrid.id_grid_d;
////			data += '&idModul=' + aSpecsPage.idModul;
////			data += '&idModulParent=' + idModulParent;
////			data += '&idPageParent=' + idPageParent;
////			data += '&idDataParent=' + idDataParent;
////			data += '&type=import';
////			data += '&primeryfieldDataParent=' + primeryfieldDataParent;
////			data += '&objFiles=' + JSON.stringify(objFiles);
////			
////			var aButtons = {};
////			aButtons[aText.Cancel] = function() { closeDialog(); }            
////			aButtons[aText.Import] = function() { sendImport(idPageParent, idDataParent); }
////			
////			openFormDialog(title, data, pathInclude+pathFormsAdmin+'fo_sys-import-match.php', aButtons, el, function(){initFieldsUploadTmp('#diaForm');});
////
////
////			//alert(objFiles['filename']['filename']);
////		},
////
////
////		// #####################################################
////		// Import Do
////		// #####################################################
////		checkImport : function (idPageParent, idDataParent){
////			sendImport(idPageParent, idDataParent);
////		},
////
////
////		// #####################################################
////		// Download files
////		// #####################################################
////		filesExport : function (el){
////			var data = 'id_grid_d=' + aGrid.id_grid_d;
////			data += '&idModul=' + aSpecsPage.idModul;
////			data += '&idPageParent=' + idPageParent;
////			data += '&type=export';
////			data += '&idDataParent=' + idDataParent;
////			data += '&idModulParent=' + idModulParent;
////			data += '&primeryfieldDataParent=' + primeryfieldDataParent;
////
////			sendExportFiles(aSpecsPage.urlExportFiles, data);
////		},
////
////
////		// #####################################################
////		// Direct link to dataset
////		// #####################################################
////		directLink : function (id, url, func, el){
////			openDirectLink(id, url, func, el, '', '');
////		}
	}
})();


