(function () {
    window['f_##modul_name##']['setCountryFilterOff'] = function (obj) { 
		$('#form_' + obj.modulpath + ' .filterFormCountry').parent('div').addClass('wFormFilterDisabled');
		$('#form_' + obj.modulpath + ' .filterFormCountry').readonly(true);
	};

	// #####################################################
	// New Form
	// #####################################################
    window['f_##modul_name##']['rowAdd'] = function (obj, el) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		obj.id_data = 0;
		obj.id_data_parent = 0;
		obj.cb_loadForm = window['f_##modul_name##']['setCountryFilterOff'];
		$('#' + objModul.gridTable + ' tr.selectedDataset').removeClass('selectedDataset'); 
		loadForm(obj, el);
	};

	// #####################################################
	// Edit Form
	// #####################################################
    window['f_##modul_name##']['rowEdit'] = function (obj, el) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		obj.id_data_parent = 0;
		obj.cb_loadForm = window['f_##modul_name##']['setCountryFilterOff'];
		$('#' + objModul.gridTable + ' tr.selectedDataset').removeClass('selectedDataset'); 
		$('#' + objModul.gridTable + ' tr[id="' + obj.id_data + '"]').addClass('selectedDataset');
		loadForm(obj, el);
	};
		

    window['f_##modul_name##']['cbSaveDataSubmitSuccess'] = function (obj) { 
		window['f_##modul_name##'].setCountryFilterOff(obj); 
	}; 
		
	
    window['f_##modul_name##']['cbLoadDataSuccess'] = function (obj) {
		window['f_##modul_name##'].setCountryFilterOff(obj); 
	};


		
		


})();


//
//window['f_##modul_name##'] = (function () {
//	return {
//		setCountrySelection : function (obj){
//			alert('AA');
//			
////			// ## build grid ##
////			var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
////			if(objModul.specifications[11] == 9) buildGrid(obj);
//		}
//	}
//})();


