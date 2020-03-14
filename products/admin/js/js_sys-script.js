var aPage = {};
var aGrids = {};
var mode = 'desktop';
var screenWidth;
var screenHeight;
var aElement = {};

$(document).ready(function(){ 
	initScreen();
	initNavigation();
	initBreadcrumb();

	var refreshpage = Cookies.get('refreshpage');
	if(refreshpage != undefined && refreshpage != '0'){
		// ## if page was refreshed ##
		var historyObj = {
							pageId: $('#navigation .navLeft div[data-pageid="' + refreshpage + '"]').attr('data-pageid'), 
							page: $('#navigation .navLeft div[data-pageid="' + refreshpage + '"]').attr('data-link')
						 }
		replaceHistory(historyObj);

		var obj = {addHis:false};
		loadPage($('#navigation .navLeft div[data-pageid="' + refreshpage + '"]'), obj);
	}else{
		var historyObj = {
							pageId: $('#navigation .navLeft div[data-link]:first').attr('data-pageid'), 
							page: $('#navigation .navLeft div[data-link]:first').attr('data-link')
						 }
		replaceHistory(historyObj);
		
		var obj = {addHis:false};
		loadPage($('#navigation .navLeft div[data-link]:first'), obj);
	}
});


$(window).resize(function() {
	initScreen();
	gridResize();
	formResize();
});


function initScreen(){
	($('#navigation').css('position') == 'fixed') ? mode = 'mobile' : mode = 'desktop';

	screenWidth = $(window).width();
	screenHeight = $(window).height();
}





function waiting(el, text){
	if(text == undefined) text = aText.loading;
	$(el).mask(text);
}


function unwaiting(el){
	if(el == undefined){
		$('.loadmask').parent().unmask();
	}else{
		$(el).unmask();
	}
} 





function cancelTool(result, status){
	// ## cancel script if no access ##
	if(result.indexOf('<!doctype') > -1 || status != 'success'){
		window.top.location = '/' + aSystem.directorySystem + aSystem.pathAdmin + 'index.php?logout=x';
		return false;
	}
}



function buildUserconfig(){
	var userconfig = {};
	userconfig.systemlang = aUser.systemlang;
	userconfig.activeCountry = aUser.activeCountry;
	userconfig.activeLanguage = aUser.activeLanguage;
	userconfig.activeDevice = aUser.activeDevice;
	userconfig.activeSysCountry = aUser.activeSysCountry;
	userconfig.activeSysLanguage = aUser.activeSysLanguage;
	userconfig.activeSysDevice = aUser.activeSysDevice;
	userconfig.activeClient = aUser.activeClient;
	
	return userconfig;
}


function setElement(field, obj){
	aElement = {};
	aElement.field = field;
	for(key in obj){
		aElement[key] = obj[key]; 
	}
}



//function sessionWarningSessionTimeout(){
//	$("#sessionWarning").dialog({
//		autoOpen: false,
//		dialogClass: 'no-close',
//		position: {my: "center", at: "center", of: window} ,
//		title: aText.sessionExpired,
//		draggable: false,
//		width : widthDialogConfirm,
//		height : 150,
//		resizable : false,
//		modal : true,
//		closeOnEscape: false,
//		buttons: [{
//			text: aText.ok,
//			click: function() {
//				window.top.sessionWarningShowTimeoutWarning();
//				$(this).dialog( "close" ); 
//				location.href = pathInclude + pathAdmin + 'index.php?logout=1';
//			}
//		}]
//	});
//	document.onkeyup   = window.top.sessionWarningResetTimeout;
//	document.onkeydown = window.top.sessionWarningResetTimeout;
//	document.onclick   = window.top.sessionWarningResetTimeout;
//	
//	window.top.sessionWarningResetTimeout();
//}
//
//function sessionWarningResetTimeout(){
//    if( window.top.sessionWarningTimeoutID ) clearTimeout( window.top.sessionWarningTimeoutID );
//    window.top.sessionWarningTimeoutID = setTimeout( window.top.sessionWarningShowTimeoutWarning, sessionLifetime );
//}
//
//function sessionWarningShowTimeoutWarning() {
//    $( "#sessionWarning" ).dialog('open');
//    return false;
//}












function loadPage(el, obj){
	waiting('body');

	if(obj == undefined) var obj = {};
	if(obj.addHis  == undefined) obj.addHis = true;
	
	aPage.pageId = $(el).attr('data-pageid');
	aPage.page = $(el).attr('data-link');

	// ## clean tempdata ##
	var objC = {};
	objC.moduls = [];
	clearData(objC);


	$.ajax({  
		url: '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu_sys-pageload.php',    
		type: 'post',          
		data: '',       
		cache: false,
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(aPage)
		},
		success: function(result, status, jqXHR){
			cancelTool(result, status);

			var objResult = JSON.parse(result);
			aPage = objResult.page;
			
			// ## load content ##
			$('#content').html(objResult.content);
			
			// ## set active menue item ##
			$('#navigation .navActive').removeClass('navActive');
			$('#navigation .navActiveMain').removeClass('navActiveMain');
			$('#navigation div[data-pageid="' + aPage.pageId + '"]').addClass('navActive');
			$('#navigation > ul > li:has(div.navActive)').addClass('navActiveMain');
			if(mode == 'mobile') closeMenue();
			//if(mode == 'mobile') closeSubmenue();
			
			loadBreadcrumb();
			
			// ## set entry for history ##
			if(obj.addHis == true){
				var historyObj = {
									pageId: aPage.pageId, 
									page: aPage.page
								 }
				addHistory(historyObj);
			}
			
			// ## load all scripts and call ready-function ##
			$('.modul').each(function(){
				var modul = $(this).attr('data-modul');
//				aPage.moduls[modul].activeCountry = aUser.activeCountry;
//				aPage.moduls[modul].activeLanguage = aUser.activeLanguage;
//				aPage.moduls[modul].activeDevice = aUser.activeDevice;
//				aPage.moduls[modul].activeSysCountry = aUser.activeSysCountry;
//				aPage.moduls[modul].activeSysLanguage = aUser.activeSysLanguage;
//				aPage.moduls[modul].activeSysDevice = aUser.activeSysDevice;
				
				initGridFilter(modul);
				
				if(!window['f_' + modul]){
					$.ajax({  
						url: '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu_sys-script-build.php',    
						type: 'post',          
						data: 'modul=' + modul,       
						cache: false,
						headers: {
							csrfToken: Cookies.get('csrf'),
							page: JSON.stringify(aPage)
						},
						dataType: 'script',
						success: function(result, status, jqXHR){
							window['f_' + modul]['ready']();
						}
					});
				}else{
					window['f_' + modul]['ready']();
				}
			});
		}
	});  
}




