function openDialogAlert(obj, aButtons){
	if(obj == undefined) var obj = {};
	if(obj.urlRead  == undefined) obj.urlRead = '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu-' + aPage.moduls[obj.modul].modulname + '-read.php';
	$.ajax({
		url: obj.urlRead,      
		type: 'post',          
		data: 'data=' + JSON.stringify(obj),       
		cache: false,  
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(aPage)
		},
		success: function (result) {
			var formData = JSON.parse(result);
			$('#dialog').html('<div id="dialogContent" class="dialogPadding"><p>' + obj.formtext + '</p><p class="">' + formData.identifier + '</p></div>'); 

			var position = {
				my: "center", at: "center", of: window
			}
			if(obj.el != ''){
				position = {
					my: "left top", at: "left+20px bottom+20px", of: obj.el
				}
			}

			$('#dialog').dialog({ 
				modal: true,
				resizable: false,
				closeOnEscape: true,
				buttons: aButtons, 
				title: obj.title,
				position: position,
				width: aSystem.widthDialogConfirm,
				show: null,
				hide: null,
				dialogClass: 'dialogZindex',
				//maxHeight: gridHeight,
				close: function(event, ui) {
					closeDialog(obj);
//					if(idModulParent != ''){
//						$('#contentOverlay', parent.document).removeClass('contentOverlayVisible');
//						$('.assignedWideClose', parent.document).removeClass('assignedWideCloseHidden');
//					}
				}
			});
		}
	});
}



function closeDialog(obj){
    $('#dialog').dialog('close');  
    $('#dialog').html(''); 

	var objC = {};
	objC.moduls = [];
	objC.moduls.push(obj.modul);
	clearData(objC);
	
//	if(idModulParent != ''){
//		$('#contentOverlay', parent.document).removeClass('contentOverlayVisible');
//	}
}

