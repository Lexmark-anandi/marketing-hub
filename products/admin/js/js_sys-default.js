window['f_aPage.modul'] = (function () {
	return {
		ready : function (){
			buildGrid('aPage.modul');
		},
		
		
		// #####################################################
		// Callback fuction after complete grid (may be overwritten in special js-file)
		// #####################################################
		cbGridComplete : function (){
			//if(cbMain != '') window['f_' + aSpecsPage.idModul][cbMain].apply(window, cbArgsMain.split(','));
		},
		
		
		// #####################################################
		// New Form
		// #####################################################
		rowAdd : function (modul, el){
//			systemmode = 'edit';
//			var data = 'id=0';
//			data += '&idModul=' + aSpecsPage.idModul;
//			data += '&type=new';
//			data += '&idPageParent=' + idPageParent;
//			data += '&idDataParent=' + idDataParent;
//			data += '&idModulParent=' + idModulParent;
//			data += '&primeryfieldDataParent=' + primeryfieldDataParent;
//
			$('#gridTable_' + aPage['moduls'][modul].modulname + ' tr.selectedDataset').removeClass('selectedDataset'); 
//			loadForm(0, aSpecsPage.urlForm, aSpecsPage.urlRead, data, 'new');
			var obj = {
				modul: modul,
				id: 0
			};
			loadForm(el, obj);
		},
		
		
		// #####################################################
		// Edit Form
		// #####################################################
		rowEdit : function (modul, id, el){
//			systemmode = 'edit';
//			var data = 'id=' + id;
//			data += '&idModul=' + aSpecsPage.idModul;
//			data += '&type=edit';
//			data += '&idPageParent=' + idPageParent;
//			data += '&idDataParent=' + idDataParent;
//			data += '&idModulParent=' + idModulParent;
//			data += '&primeryfieldDataParent=' + primeryfieldDataParent;
			
			$('#gridTable_' + aPage['moduls'][modul].modulname + ' tr.selectedDataset').removeClass('selectedDataset'); 
			$('#gridTable_' + aPage['moduls'][modul].modulname + ' tr[id="'+id+'"]').addClass('selectedDataset');
			var obj = {
				modul: modul,
				id: id
			};
			loadForm(el, obj);
		},
//		
//		
//		// #####################################################
//		// Copy Form
//		// #####################################################
//		rowCopy : function (id, el){
//			systemmode = 'edit';
//			var data = 'id=' + id;
//			data += '&idModul=' + aSpecsPage.idModul;
//			data += '&type=copy';
//			data += '&idPageParent=' + idPageParent;
//			data += '&idDataParent=' + idDataParent;
//			data += '&idModulParent=' + idModulParent;
//			data += '&primeryfieldDataParent=' + primeryfieldDataParent;
//			
//			$('#' + aSpecsPage.idGridTable + ' tr[id="'+id+'"]').addClass('selectedDataset');
//			loadForm(0, aSpecsPage.urlForm, aSpecsPage.urlRead, data, 'copy');
//		},
//
//
//		// #####################################################
//		// Read Form
//		// #####################################################
//		rowRead : function (id, el){
//			systemmode = 'read';
//			var data = 'id=' + id;
//			data += '&idModul=' + aSpecsPage.idModul;
//			data += '&type=read';
//			data += '&idPageParent=' + idPageParent;
//			data += '&idDataParent=' + idDataParent;
//			data += '&idModulParent=' + idModulParent;
//			data += '&primeryfieldDataParent=' + primeryfieldDataParent;
//			
//			$('#' + aSpecsPage.idGridTable + ' tr[id="'+id+'"]').addClass('selectedDataset');
//			loadForm(id, aSpecsPage.urlForm, aSpecsPage.urlRead, data, 'read');
//		},
//
//
//		// #####################################################
//		// CB Load Form Start
//		// #####################################################
//		cbLoadFormStart : function (id){
//		},
//
//
//		// #####################################################
//		// CB Load Form Complete
//		// #####################################################
//		cbLoadFormComplete : function (id){
//			unwaiting('#' + aSpecsPage.idContent);
//		},


		// #####################################################
		// Delete row
		// #####################################################
		rowDelete : function (modul, id, el){ 
//			systemmode = 'edit';
//			var data = 'id=' + id;
//			data += '&idModul=' + aSpecsPage.idModul;
//			data += '&type=delete';
//			data += '&idPageParent=' + idPageParent;
//			data += '&idDataParent=' + idDataParent;
//			data += '&idModulParent=' + idModulParent;
//			data += '&primeryfieldDataParent=' + primeryfieldDataParent;
//			
//			
//			$('#' + aSpecsPage.idGridTable + ' tr.selectedDataset').removeClass('selectedDataset');
//			openAlertDialog(title, aSpecsPage.urlRead, data, aTextScript.tDeleteCheck, aTextScript.fDeleteField, aButtons, 'delete', el);


			var obj = {};
			obj.modul = modul;
			obj.id = id;
			obj.el = el;
			obj.title = aText.Delete;
			obj.formtext = aText.deleteCheck;
			obj.urlRead = '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu-' + aPage['moduls'][modul].modulname + '-read.php';
			obj.urlDelete = '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu-' + aPage['moduls'][modul].modulname + '-delete.php';
			
			aButtons = {};
			aButtons[aText.Cancel] = function() { closeDialog(obj); }            
			aButtons[aText.Delete] = function() { sendDelete(obj); }
			
			$('#gridTable_' + aPage['moduls'][modul].modulname + ' tr.selectedDataset').removeClass('selectedDataset'); 
			openDialogAlert(obj, aButtons);
		},


//		// #####################################################
//		// Delete attached file
//		// #####################################################
//		fileDelete : function (id, fieldname){   
//		},


		// #####################################################
		// Save 
		// #####################################################
		sendForm : function (modul, action){
			var obj = {};
			obj.modul = modul;
			obj.action = action;
			obj.id = $('.modul[data-modul="' + obj.modul + '"] .formLeft .field_id').val();
			obj.formCountry = $('.modul[data-modul="' + obj.modul + '"] .filterFormCountry option:selected').val();
			obj.formLanguage = $('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage option:selected').val();
			obj.formDevice = $('.modul[data-modul="' + obj.modul + '"] .filterFormDevice option:selected').val();
			if(obj.formCountry == undefined) obj.formCountry = 0;
			if(obj.formLanguage == undefined) obj.formLanguage = 0;
			if(obj.formDevice == undefined) obj.formDevice = 0;
			obj.cb_readData = sendTemp;
			obj.cb_sendTemp = saveData;
			
			readData(obj);
	
//			// ### Check form
//			var error = 0;
//			// #####################################################
//			
//			if(error == 0){
//				$('#' + aSpecsPage.idFormOuter + ' .fieldData').val(JSON.stringify(formData));
//				sendData(action);
//			}else{ 
//				showErrors(); 
//			}
		}


//		// #####################################################
//		// Export Form
//		// #####################################################
//		dataExport : function (el){
//			var title = aTextScript.tExport;
//			
//			var data = 'id_grid_d=' + aGrid.id_grid_d;
//			data += '&idModul=' + aSpecsPage.idModul;
//			data += '&idModulParent=' + idModulParent;
//			data += '&idPageParent=' + idPageParent;
//			data += '&idDataParent=' + idDataParent;
//			data += '&type=export';
//			data += '&primeryfieldDataParent=' + primeryfieldDataParent;
//			
//			var aButtons = {};
//			aButtons[aText.Cancel] = function() { closeDialog(); }            
//			aButtons[aText.Export] = function() { window['f_' + aSpecsPage.idModul]['checkExport'](idPageParent, idDataParent)}
//			
//			openFormDialog(title, data, aSpecsPage.urlExportForm, aButtons, el);
//		},
//
//
//		// #####################################################
//		// Export Do
//		// #####################################################
//		checkExport : function (idPageParent, idDataParent){
//			sendExport(idPageParent, idDataParent);
//		},
//
//
//		// #####################################################
//		// Import Form
//		// #####################################################
//		dataImport : function (el){
//			var title = aTextScript.tImport;
//			
//			var data = 'id_grid_d=' + aGrid.id_grid_d;
//			data += '&idModul=' + aSpecsPage.idModul;
//			data += '&idModulParent=' + idModulParent;
//			data += '&idPageParent=' + idPageParent;
//			data += '&idDataParent=' + idDataParent;
//			data += '&type=import';
//			data += '&primeryfieldDataParent=' + primeryfieldDataParent;
//			
//			var aButtons = {};
//			aButtons[aText.Cancel] = function() { closeDialog(); }            
//			aButtons[aText.Import] = function() { sendDataFiles(aSpecsPage.urlInsert, aSpecsPage.urlRead, 'matchImport') }
//			
//			openFormDialog(title, data, aSpecsPage.urlImportForm, aButtons, el, function(){initFieldsUploadTmp(el, '#diaForm');});
//		},
//
//
//		// #####################################################
//		// Import Matching Form
//		// #####################################################
//		matchImport : function (el, objFiles){
//			closeDialog();
//
//			var title = aTextScript.tImport;
//			
//			var data = 'id_grid_d=' + aGrid.id_grid_d;
//			data += '&idModul=' + aSpecsPage.idModul;
//			data += '&idModulParent=' + idModulParent;
//			data += '&idPageParent=' + idPageParent;
//			data += '&idDataParent=' + idDataParent;
//			data += '&type=import';
//			data += '&primeryfieldDataParent=' + primeryfieldDataParent;
//			data += '&objFiles=' + JSON.stringify(objFiles);
//			
//			var aButtons = {};
//			aButtons[aText.Cancel] = function() { closeDialog(); }            
//			aButtons[aText.Import] = function() { sendImport(idPageParent, idDataParent); }
//			
//			openFormDialog(title, data, pathInclude+pathFormsAdmin+'fo_sys-import-match.php', aButtons, el, function(){initFieldsUploadTmp('#diaForm');});
//
//
//			//alert(objFiles['filename']['filename']);
//		},
//
//
//		// #####################################################
//		// Import Do
//		// #####################################################
//		checkImport : function (idPageParent, idDataParent){
//			sendImport(idPageParent, idDataParent);
//		},
//
//
//		// #####################################################
//		// Download files
//		// #####################################################
//		filesExport : function (el){
//			var data = 'id_grid_d=' + aGrid.id_grid_d;
//			data += '&idModul=' + aSpecsPage.idModul;
//			data += '&idPageParent=' + idPageParent;
//			data += '&type=export';
//			data += '&idDataParent=' + idDataParent;
//			data += '&idModulParent=' + idModulParent;
//			data += '&primeryfieldDataParent=' + primeryfieldDataParent;
//
//			sendExportFiles(aSpecsPage.urlExportFiles, data);
//		},
//
//
//		// #####################################################
//		// Direct link to dataset
//		// #####################################################
//		directLink : function (id, url, func, el){
//			openDirectLink(id, url, func, el, '', '');
//		}
	}
})();


