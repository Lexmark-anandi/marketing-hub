function openDialogAlert(obj, objDialog){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	var data = '';
	
    $.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-default-read.php', 
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

			var objFormData = JSON.parse(result);
			if(objDialog.identifier  == undefined) objDialog.identifier = objFormData.identifier;

			$('body').append('<div class="dialogOuter"><div class="dialogPadding"><p>' + objDialog.formtext + '</p><p class="">' + objDialog.identifier + '</p></div></div>'); 

			var position = {
				my: "center", at: "center", of: window
			}
			if(objDialog.el != ''){
				position = {
					my: "left top", at: "left+20px bottom+20px", of: objDialog.el, collision: 'flipfit'
				}
			}

			$('.dialogOuter:last').dialog({ 
				modal: true,
				resizable: false,
				closeOnEscape: true,
				buttons: objDialog.objButtons, 
				title: objDialog.title,
				position: position,
				width: objSystem.widthDialogConfirm,
				show: null,
				hide: null,
				dialogClass: 'dialogZindex',
				maxHeight: screenHeight - 50,
				close: function(event, ui) {
					$(this).remove();
////					if(idModulParent != ''){
////						$('#contentOverlay', parent.document).removeClass('contentOverlayVisible');
////						$('.assignedWideClose', parent.document).removeClass('assignedWideCloseHidden');
////					}
				}
			});
		}
	});
}



function openDialogMessage(obj, objDialog){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

	if(objDialog == undefined) var objDialog = {};
	if(objDialog.title == undefined) objDialog.title = '';
	if(objDialog.formtext == undefined) objDialog.formtext = '';
	if(objDialog.el == undefined) objDialog.el = '';
	
	$('body').append('<div class="dialogOuter"><div id="dialogContent" class="dialogPadding">' + objDialog.formtext + '</div></div>'); 

	var position = {
		my: "center", at: "center", of: window
	}
	if(objDialog.el != ''){
		position = {
			my: "left top", at: "left+20px bottom+20px", of: objDialog.el, collision: 'flipfit'
		}
	}

	$('.dialogOuter:last').dialog({ 
		modal: true,
		resizable: false,
		closeOnEscape: true,
		buttons: objDialog.objButtons, 
		title: objDialog.title,
		position: position,
		width: objSystem.widthDialogConfirm,
		show: null,
		hide: null,
		dialogClass: 'dialogZindex',
		maxHeight: screenHeight - 50,
		close: function(event, ui) {
			$(this).remove();
//					if(idModulParent != ''){
//						$('#contentOverlay', parent.document).removeClass('contentOverlayVisible');
//						$('.assignedWideClose', parent.document).removeClass('assignedWideCloseHidden');
//					}
		}
	});
}





function openDialogForm(obj, objDialog){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

	$.ajax({  
		url: objDialog.urlForm,    
		data: 'data=' + JSON.stringify(objDialog),       
		type: 'post',          
		cache: false,  
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(obj),
			settings: JSON.stringify(objModul.activeSettings)
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);

//			//var formData = JSON.parse(result);
//			$('body').append('<div class="dialogOuter"><div id="dialogContent" class="dialogPadding">' + result + '</div></div>'); 
//
////			if(idModulParent != ''){
////				$('#contentOverlay', parent.document).addClass('contentOverlayVisible');
////				$('.assignedWideClose', parent.document).addClass('assignedWideCloseHidden');
////			}
////            
////			//formData = JSON.parse(result);
//			
			$('body').append('<div class="dialogOuter"><div class="dialogPadding">' + result + '</div></div>');

			var position = {
				my: "center", at: "center", of: window
			}
			if(objDialog.el != ''){
				position = {
					my: "left top", at: "left+20px bottom+20px", of: objDialog.el, collision: 'flipfit'
				}
			}

			$('.dialogOuter:last').dialog({ 
				modal: true,
				resizable: false,
				closeOnEscape: true,
				buttons: objDialog.objButtons, 
				title: objDialog.title,
				position: position,
				width: objSystem.widthDialogConfirm,
				show: null,
				hide: null,
				dialogClass: 'dialogZindex',
				maxHeight: screenHeight - 50,
				close: function(event, ui) {
					$(this).remove();
////					if(idModulParent != ''){
////						$('#contentOverlay', parent.document).removeClass('contentOverlayVisible');
////						$('.assignedWideClose', parent.document).removeClass('assignedWideCloseHidden');
////					}
				},
				open: function(event, ui) {
					if(window['f_' + obj.modul_name].cbDialogFormOpen && typeof(window['f_' + obj.modul_name].cbDialogFormOpen) === 'function') window['f_' + obj.modul_name].cbDialogFormOpen(obj);
				}
			});
		}    
	});  
}





function openDialogConfig(obj, objDialog){
	$.ajax({  
		url: objDialog.urlForm,    
		data: 'data=' + JSON.stringify(objDialog),       
		type: 'post',          
		cache: false,  
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);

//			//var formData = JSON.parse(result);
//			$('body').append('<div class="dialogOuter"><div id="dialogContent" class="dialogPadding">' + result + '</div></div>'); 
//
////			if(idModulParent != ''){
////				$('#contentOverlay', parent.document).addClass('contentOverlayVisible');
////				$('.assignedWideClose', parent.document).addClass('assignedWideCloseHidden');
////			}
////            
////			//formData = JSON.parse(result);
//			
			$('body').append('<div class="dialogOuter"><div class="dialogPadding">' + result + '</div></div>');

			var position = {
				my: "center", at: "center", of: window
			}
			if(objDialog.el != ''){
				position = {
					my: "left top", at: "left+20px bottom+20px", of: objDialog.el, collision: 'flipfit'
				}
			}

			$('.dialogOuter:last').dialog({ 
				modal: true,
				resizable: false,
				closeOnEscape: true,
				buttons: objDialog.objButtons, 
				title: objDialog.title,
				position: position,
				width: objSystem.widthDialogConfirm,
				show: null,
				hide: null,
				dialogClass: 'dialogZindex',
				maxHeight: screenHeight - 50,
				close: function(event, ui) {
					$(this).remove();
////					if(idModulParent != ''){
////						$('#contentOverlay', parent.document).removeClass('contentOverlayVisible');
////						$('.assignedWideClose', parent.document).removeClass('assignedWideCloseHidden');
////					}
				},
				open: function(event, ui) {
					if(window['f_' + obj.modul_name].cbDialogFormOpen && typeof(window['f_' + obj.modul_name].cbDialogFormOpen) === 'function') window['f_' + obj.modul_name].cbDialogFormOpen(obj);
				}
			});
		}    
	});  
}



function closeDialog(obj, el){
	unwaiting('#modul_' + obj.modulpath);
//	filesUpload = new Array();
	if(el != undefined){
		$(el).closest('.dialogOuter').dialog('close');  
		$(el).closest('.dialogOuter').remove(); 

//		
////		if($('.dialogOuter').length == 0){
////			var objC = {};
////			objC.moduls = [];
////			objC.moduls.push(obj.modul);
////			clearData(objC);
////		}
//		
//	//	if(idModulParent != ''){
//	//		$('#contentOverlay', parent.document).removeClass('contentOverlayVisible');
//	//	}
	}else{
		$('.dialogOuter:last').dialog('close');  
		$('.dialogOuter:last').remove(); 
	}
}

