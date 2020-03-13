(function () {
    window['f_##modul_name##']['setCountrySelection'] = function (obj) { 
		$('#form_' + obj.modulpath + ' li[data-formtab="countries"]').css('display', 'none');
		$('#form_' + obj.modulpath + ' li[data-formtab="geographies"]').css('display', 'none');
		var id_r = $('#id_r_' + obj.modulpath + ' option:selected').val(); 
		if(id_r != 3) $('#id_geoid_' + obj.modulpath + ' option').prop('selected', false);
		if(id_r == 3) $('#form_' + obj.modulpath + ' li[data-formtab="geographies"]').css('display', '');
		if(id_r == 4 || id_r == 5) $('#form_' + obj.modulpath + ' li[data-formtab="countries"]').css('display', '');
	};

	// #####################################################
	// New Form
	// #####################################################
    window['f_##modul_name##']['rowAdd'] = function (obj, el) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		obj.id_data = 0;
		obj.id_data_parent = 0;
		obj.cb_fillData = window['f_##modul_name##']['setCountrySelection'];
		$('#' + objModul.gridTable + ' tr.selectedDataset').removeClass('selectedDataset'); 
		loadForm(obj, el);
	};

	// #####################################################
	// Edit Form
	// #####################################################
    window['f_##modul_name##']['rowEdit'] = function (obj, el) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		obj.id_data_parent = 0;
		obj.cb_fillData = window['f_##modul_name##']['setCountrySelection'];
		$('#' + objModul.gridTable + ' tr.selectedDataset').removeClass('selectedDataset'); 
		$('#' + objModul.gridTable + ' tr[id="' + obj.id_data + '"]').addClass('selectedDataset');
		loadForm(obj, el);
	};


		
		


})();

