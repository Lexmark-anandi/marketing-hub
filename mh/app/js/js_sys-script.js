$(document).ready(function(){ 
	waiting('body', '&nbsp;');

	$('#navigationOuter ul > li').off('mouseover mouseout click');
	$('#navigationOuter ul > li').on('mouseover', function(){
		$('.navHover').removeClass('navHover');
		$(this).addClass('navHover');
	});
	$('#navigationOuter ul > li').on('mouseout', function(){
		$('.navHover').removeClass('navHover');
	});
	$('#navigationOuter ul > li').on('click', function(){
		$('.navActive').removeClass('navActive');
		$(this).addClass('navActive');
		loadPage();
	});


	$('#navFeedback ul > li').off('mouseover mouseout click');
	$('#navFeedback ul > li').on('mouseover', function(){
		$('.navHover').removeClass('navHover');
		$(this).addClass('navHover');
	});
	$('#navFeedback ul > li').on('mouseout', function(){
		$('.navHover').removeClass('navHover');
	});
	$('#navFeedback ul > li').on('click', function(){
		$('.navHover').removeClass('navHover');
		openFeedback();
	});


	$.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu_sys-jsvars-init.php?initLogin=1',    
		type: 'post',          
		data: '',       
		cache: false,
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify({})
		},
		success: function(result, status, jqXHR){
			var obj = JSON.parse(result);
			objUser = obj.user;
			objText = obj.text;
			var objSettings = Cookies.getJSON('activesettings');
			
			if(newLogin == 1){
				dialogFirstLogin();
				newLogin = 0;
			}

			if(objSettings.id_page != undefined){
				$('#navigationOuter ul > li[data-caid="' + objSettings.id_page + '"]').addClass('navActive');
			}else{
				$('#navigationOuter ul > li:first').addClass('navActive');
			}
//
//			$.datepicker.setDefaults($.datepicker.regional[objSettings.systemLanguage]);
//			$.datepicker.setDefaults({
//				dateFormat: objUser.syscountries[objSettings.systemCountry].date_code.toLowerCase()
//			});
////			$.timepicker.setDefaults($.timepicker.regional[userLanguage]);
//			
			sessionWarningSessionTimeout();
			unwaiting();
//
//			var objCookie = Cookies.getJSON('deeplink');
//			if(objCookie == undefined){
//				loadPage($('#navigation div[data-pageid="' + Cookies.getJSON('activesettings').id_page + '"]'));
//			}else{
//				if(objCookie.page == undefined || objCookie.page == ''){
//					if(objCookie.country != undefined && objCookie.country != ''){
//						var objChange = {};
//						objChange['id_countid'] = objCookie.country;
//						changeCookie('activesettings', objChange);
//					}
//	
					loadPage();
//	
//					var objChange = {};
//					objChange['page'] = '';
//					objChange['country'] = '';
//	//				objChange['data'] = '';
//	//				objChange['function'] = '';
//					changeCookie('deeplink', objChange);
//				}
//			}
		}
	});
//
//	initScreen();
//	initNavigation();
//	initBreadcrumb();
});


$(window).resize(function() {
	resizeEdit();
	initScreen();
});


function initScreen(){
//	mode = ($('#navigation').css('position') == 'fixed') ? 'mobile' : 'desktop';
//
//	screenWidth = $(window).width();
//	screenHeight = $(window).height();
//	
//	objSystem.widthDialogForm = (mode == 'mobile') ? screenWidth : objSystem.widthDialogFormOrg;
//	objSystem.widthDialogConfirm = (mode == 'mobile') ? screenWidth : objSystem.widthDialogConfirmOrg;
}


function openFeedback(){
	var objDialog = {};
	objDialog.el = '';
	objDialog.title = objText.Feedback;
	objDialog.objButtons = {};
	objDialog.objButtons[objText.cancel] = function() { closeDialog(this); }            
	objDialog.objButtons[objText.Send] = function() {sendFeedback();}

	$.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFormsApp + 'fo-feedback.php?initLogin=1',      
		data: '',       
		type: 'post',          
		cache: false,  
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);

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
				width: objSystem.widthDialogForm,
				show: null,
				hide: null,
				dialogClass: 'dialogZindex',
				maxHeight: screenHeight - 50,
				close: function(event, ui) {
					$(this).remove();
				}
			});
		}    
	});  
}


function sendFeedback(){
	waiting('body');
	
	$('#formFeedback').ajaxSubmit({
		url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-feedback.php',
		clearForm: false, 
		type: 'post', 
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function(result, status, jqXHR){
			actualizeStatus(result, status);
			closeDialog();
			unwaiting();
		}
	});
}

