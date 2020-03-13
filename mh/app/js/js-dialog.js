function openDialogAlert(objDialog){
	$('body').append('<div class="dialogOuter"><div class="dialogPadding"><p>' + objDialog.formtext + '</p></div></div>'); 
	
	var position = {
		my: "center", at: "center", of: window
	}
	if(objDialog.el != ''){
		position = {
			my: "left top", at: "left+20px bottom+20px", of: objDialog.el
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
		},
		open: function(){
			$('#progressbar').progressbar({
				value: false
			});
		}
	});
 
 
 
 
}



function openDialogConfirm(objDialog){
	$('body').append('<div class="dialogOuter"><div class="dialogPadding"><p>' + objDialog.formtext + '</p></div></div>'); 
	
	var position = {
		my: "center", at: "center", of: window
	}
	if(objDialog.el != ''){
		position = {
			my: "left top", at: "left+20px bottom+20px", of: objDialog.el
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
		},
		open: function(){
		}
	});
 
 
 
 
}



function dialogFirstLogin(){
	var objDialog = {};
	objDialog.el = '';
	objDialog.title = objText.title;
	objDialog.formtext = objText.welcomePopup;
	objDialog.objButtons = {};
	objDialog.objButtons[objText.cancel] = function() { closeDialog(this); }            
	objDialog.objButtons[objText['companyProfile']] = function() { 
		$('.navActive').removeClass('navActive');
		$('.navRight li[data-caid="profile"]').addClass('navActive');
		loadPage();
		closeDialog();
	}
	
	$('body').append('<div class="dialogOuter"><div class="dialogPadding dialogFirstLogin"><p>' + objDialog.formtext + '</p></div></div>'); 
	
	var position = {
		my: "center", at: "center", of: window
	}
	if(objDialog.el != ''){
		position = {
			my: "left top", at: "left+20px bottom+20px", of: objDialog.el
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
		},
		open: function(){
		}
	});
}



//function openDialogMessage(obj, aButtons){
//	if(obj == undefined) var obj = {};
//	if(obj.title == undefined) obj.title = '';
//	if(obj.message == undefined) obj.message = '';
//	if(obj.el == undefined) obj.el = '';
//	
//	$('body').append('<div class="dialogOuter"><div id="dialogContent" class="dialogPadding">' + obj.message + '</div></div>'); 
//
//	var position = {
//		my: "center", at: "center", of: window
//	}
//	if(obj.el != ''){
//		position = {
//			my: "left top", at: "left+20px bottom+20px", of: obj.el
//		}
//	}
//
//	$('.dialogOuter:last').dialog({ 
//		modal: true,
//		resizable: false,
//		closeOnEscape: true,
//		buttons: aButtons, 
//		title: obj.title,
//		position: position,
//		width: aSystem.widthDialogConfirm,
//		show: null,
//		hide: null,
//		dialogClass: 'dialogZindex',
//		maxHeight: screenHeight - 50,
//		close: function(event, ui) {
//			$(this).remove();
////					if(idModulParent != ''){
////						$('#contentOverlay', parent.document).removeClass('contentOverlayVisible');
////						$('.assignedWideClose', parent.document).removeClass('assignedWideCloseHidden');
////					}
//		}
//	});
//}
//
//
//
//
//
function openDialogForm(obj, objDialog){
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
					my: "left top", at: "left+20px bottom+20px", of: objDialog.el
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
//				},
//				open: function(event, ui) {
//					if(window['f_' + obj.modul] && window['f_' + obj.modul][obj.cb] && typeof(window['f_' + obj.modul][obj.cb]) === 'function'){
//						window['f_' + obj.modul][obj.cb](obj);
//					}else if(window[obj.cb] && typeof(window[obj.cb]) === 'function'){
//						window[obj.cb](obj);
//					}
				}
			});
		}    
	});  
}



function closeDialog(el){
	if(el != undefined){
		$(el).closest('.dialogOuter').dialog('close');  
		$(el).closest('.dialogOuter').remove(); 
	}else{
		$('.dialogOuter:last').dialog('close');  
		$('.dialogOuter:last').remove(); 
	}
}

