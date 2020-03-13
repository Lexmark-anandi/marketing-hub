function changeLanguage(el){
	var objDialog = {};
	objDialog.urlForm = objSystem.directoryInstallation + objSystem.pathFormsAdmin + 'fo_sys-change-language.php';
	objDialog.el = el;
	objDialog.title = objText.changeLanguage;
	
	objDialog.objButtons = {};
	objDialog.objButtons[objText.Cancel] = function() { closeDialog(objDialog, this); }            
	objDialog.objButtons[objText.changeLanguage] = function() { changeLanguageDo() }
	
	openDialogForm({}, objDialog);
}

function changeLanguageDo(){
	var lang = $('.dialogOuter:last #newlanguage option:selected').val();
	var data = 'lang=' + lang;

	$.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-change-language.php',    
		type: 'post',           
		data: data,       
		cache: false ,
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);

			location.reload();
		}
	});  
}


// #############################################################


function changeConfiguration(el){
	var objDialog = {};
	objDialog.urlForm = objSystem.directoryInstallation + objSystem.pathFormsAdmin + 'fo_sys-change-configuration.php';
	objDialog.el = el;
	objDialog.title = objText.systemConfiguration;
	
	objDialog.objButtons = {};
	objDialog.objButtons[objText.Cancel] = function() { closeDialog(objDialog, this); }            
	objDialog.objButtons[objText.changeConfiguration] = function() { changeConfigurationDo() }
	
	openDialogConfig({}, objDialog);
}

function changeConfigurationDo(){
	var lang = $('.dialogOuter:last #newlanguage option:selected').val();
	var count = $('.dialogOuter:last #system_country option:selected').val();
	var data = 'lang=' + lang;
	data += '&count=' + count;

	$.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-change-configuration.php',    
		type: 'post',          
		data: data,       
		cache: false ,
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);

			location.reload();
		}
	});  
}


// #############################################################


function cancelTool(result, status){
	// ## cancel script if no access ##
	if(result.indexOf('<!doctype') > -1 || status != 'success'){
		window.top.location = objSystem.directoryInstallation + objSystem.pathAdmin + 'index.php?logout=x'; 
		return false;
	}
}

function actualizeStatus(result, status){
	sessionWarningResetTimeout();
	cancelTool(result, status);
}
 

// #############################################################


function sessionWarningSessionTimeout(){
	$('#sessionWarning').dialog({
		autoOpen: false,
		dialogClass: 'no-close',
		position: {my: "center", at: "center", of: window},
		title: objText.sessionExpired,
		draggable: false,
		width: objSystem.widthDialogConfirm,
		height: 150,
		resizable: false,
		modal: true,
		closeOnEscape: false,
		buttons: [{
			text: objText.ok,
			click: function() {
				//sessionWarningShowTimeoutWarning();
				$(this).dialog('close'); 
				location.href = objSystem.directoryInstallation + objSystem.pathAdmin + 'index.php?logout=1';
			}
		}]
	});
	
	sessionWarningResetTimeout();
}

function sessionWarningResetTimeout(){
    if(sessionWarningTimeoutID ) clearTimeout(sessionWarningTimeoutID);
    sessionWarningTimeoutID = setTimeout(sessionWarningShowTimeoutWarning, sessionLifetime);
}

function sessionWarningShowTimeoutWarning() {
    $('#sessionWarning').dialog('open');
    return false;
}


// #############################################################


function waiting(el, text){
	$('.loadmask').parent().unmask();
	if(text == undefined) text = objText.loading;
	$(el).mask(text);
}

function unwaiting(el){
	if(el == undefined){
		$('.loadmask').parent().unmask();
	}else{
		$(el).unmask();
	}
} 


// #############################################################


function changeCookie(name, objChange, objConfigCookie){
	var domain = window.location.hostname;
	
	if(objConfigCookie == undefined) objConfigCookie = {};
	if(objConfigCookie.expires == undefined) objConfigCookie.expires = 0;
	if(objConfigCookie.path == undefined) objConfigCookie.path = objSystem.directoryInstallation + objSystem.pathAdmin;
	if(objConfigCookie.domain == undefined) objConfigCookie.domain = (validateIPaddress(domain) == true) ? domain : '.' + domain;
	if(objConfigCookie.secure == undefined) objConfigCookie.secure = objSystem.cookie_secure;
	if(objConfigCookie.httponly == undefined) objConfigCookie.httponly = false;
	
	var objCookie = Cookies.getJSON(name);
	for(var key in objChange){
		objCookie[key] = objChange[key];
	}

	Cookies.set(name, JSON.stringify(objCookie), {path: objConfigCookie.path, domain: objConfigCookie.domain, secure: objConfigCookie.secure});
}


function validateIPaddress(ipaddress) {  
  if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipaddress)) {  
    return (true)  
  }  
  return (false)  
}  

// #############################################################


function callFunction(funcs, el){
	var path = $(el).closest('.modul').attr('data-modulpath');
	var obj = splitModulpath(path);
	
	var aFunc = funcs.split(';');
	for(key in aFunc){
		if(window['f_' + obj.modul_name][aFunc[key]] && typeof(window['f_' + obj.modul_name][aFunc[key]]) === 'function'){
			window['f_' + obj.modul_name][aFunc[key]](obj, this);
		}else if(window[aFunc[key]] && typeof(window[aFunc[key]]) === 'function'){
			window[aFunc[key]](obj, this);
		}
	}
}


// #############################################################


function setElement(field, obj){
	objElement = {};
	objElement.field = field;
	for(key in obj){
		objElement[key] = obj[key]; 
	}
}


// #############################################################


function waitForElement(el, cb){
	if($(el).length){
		cb();
	}else{
		setTimeout(function() {
			waitForElement(el, cb);
		}, 50);
	}
}


// #############################################################


function isVisible(win, el, distance, distance_unit){
	if(distance == undefined) distance = 0;
	if(distance_unit == undefined) distance_unit = 'px';

    var bounds = $(el).offset();
    bounds.right = bounds.left + $(el).outerWidth();
    bounds.bottom = bounds.top + $(el).outerHeight();
	
    var viewport = $(win).offset();
    viewport.right = viewport.left + $(win).width();
    viewport.bottom = viewport.top + $(win).height();
	
	// define a minimum visibility of the element
	var viewportMin = Object.assign({}, viewport);
	if(distance > 0){
		if(distance_unit == 'px'){
			viewportMin.top += distance;
			viewportMin.right -= distance;
			viewportMin.bottom -= distance;
			viewportMin.left += distance;
		}
		if(distance_unit == 'percent'){
			viewportMin.top += (($(el).outerHeight() / 100) * distance);
			viewportMin.right -= (($(el).outerWidth() / 100) * distance);
			viewportMin.bottom -= (($(el).outerHeight() / 100) * distance);
			viewportMin.left += (($(el).outerWidth() / 100) * distance);
		}
	}
	
	var objVis = {};
	objVis.top = (viewport.top > bounds.top) ? false : true;
	objVis.right = (viewport.right < bounds.right) ? false : true;
	objVis.bottom = (viewport.bottom < bounds.bottom) ? false : true;
	objVis.left = (viewport.left > bounds.left) ? false : true;
	// check Min for the opposite site
	objVis.topMin = (viewportMin.bottom < bounds.top) ? false : true;
	objVis.rightMin = (viewportMin.left > bounds.right) ? false : true;
	objVis.bottomMin = (viewportMin.top > bounds.bottom) ? false : true;
	objVis.leftMin = (viewportMin.right < bounds.left) ? false : true;
	return objVis;
};
