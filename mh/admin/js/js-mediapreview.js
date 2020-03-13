(function () {
	// #####################################################
	// Ready
	// #####################################################
    window['f_##modul_name##']['ready'] = function (obj) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		var objP = splitModulpathParent(obj.modulpath);
		
		var data = '';
		data += 'filename=' + $('#grid_' + objP.modulpath + ' tr[id="' + obj.id_data_parent + '"] td[aria-describedby="gridTable_' + objP.modulpath + '_filesys_filename"]').text();
		
		$.ajax({  
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-default-overview.php', 
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

				$('#modul_' + obj.modulpath).addClass('modulFillBG');
				window.setTimeout(function(){
					$('#modul_' + obj.modulpath).html(result);
				}, 500);
			}
		});
	};


    window['f_##modul_name##']['readyReload'] = function (obj) { 
		$('#modul_' + obj.modulpath).html('');
		window['f_##modul_name##']['ready'](obj);
	};
})();

