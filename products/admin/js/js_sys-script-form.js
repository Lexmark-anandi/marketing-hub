function loadForm(el, obj){
	if(obj == undefined) var obj = {};
	if(obj.modul  == undefined) obj.modul = $(el).closest('.modul').attr('data-modul');
	if(obj.urlForm  == undefined) obj.urlForm = '/' + aSystem.directorySystem + aSystem.pathFormsAdmin + 'fo-' + aPage.moduls[obj.modul].modulname + '.php';
	
	waiting('#' + $('.modul[data-modul="' + obj.modul + '"] .grid').attr('id'));
//	
//	if(idModulParent != ''){
//		window.parent.openAssignedWideDirect();
//		$('#' + aSpecsPage.idAssigned, parent.document).removeClass('containerAssignedClosing');
//	}
//

    $.ajax({  
		url: obj.urlForm,    
		data: 'data=' + JSON.stringify(obj),       
		type: 'post',          
		cache: false,  
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(aPage)
		},
		success: function (form) {
			$('.modul[data-modul="' + obj.modul + '"] .formContent').html(form);
			$('.modul[data-modul="' + obj.modul + '"] .form').removeClass('hidden');

			aPage.moduls[obj.modul].formCountry = $('.modul[data-modul="' + obj.modul + '"] .filterGridCountry option:selected').val();
			aPage.moduls[obj.modul].formLanguage = $('.modul[data-modul="' + obj.modul + '"] .filterGridLanguage option:selected').val();
			aPage.moduls[obj.modul].formDevice = $('.modul[data-modul="' + obj.modul + '"] .filterGridDevice option:selected').val();
			if(aPage.moduls[obj.modul].formCountry == undefined) aPage.moduls[obj.modul].formCountry = 0;
			if(aPage.moduls[obj.modul].formLanguage == undefined) aPage.moduls[obj.modul].formLanguage = 0;
			if(aPage.moduls[obj.modul].formDevice == undefined) aPage.moduls[obj.modul].formDevice = 0;
			$('.modul[data-modul="' + obj.modul + '"] .filterFormCountry option[value="' + aPage.moduls[obj.modul].formCountry + '"]').prop('selected', true);
			if(aPage.moduls[obj.modul].specifics.substr(3, 1) == 9) fillFilterLanguage(obj);
			$('.modul[data-modul="' + obj.modul + '"] .filterFormLanguage option[value="' + aPage.moduls[obj.modul].formLanguage + '"]').prop('selected', true);
			$('.modul[data-modul="' + obj.modul + '"] .filterFormDevice option[value="' + aPage.moduls[obj.modul].formDevice + '"]').prop('selected', true);
			
			loadData(obj);

			initForm(obj);
			initFormFilter(obj);
			initFields(obj);
//			
//			if(useMultiple == 0) $('#' + aSpecsPage.idFormOuter + ' .checkmaster').css('display', 'none');
//			
//			
//			createTabsFormRight(id);
//			var activeTabR = $('#' + aSpecsPage.idFormOuter + ' .formContent .formRight .formTabs ul li.active').attr('data-formtab');
//			
//			
//			$('#' + aSpecsPage.idGridOuter).parent().addClass('contentHidden');
//			$('#' + aSpecsPage.idGridOuter).parent().addClass('contentHeight0');
//			$('#' + aSpecsPage.idFormOuter).parent().removeClass('contentHidden');
//
//			// Load form data
//			// ......


//			showFormTab('formLeft', activeTab);
//			showFormTab('formRight', activeTabR);
		}
	});  
}


function initForm(obj){
	// Create tabs for form
	var numTabs = 0;
	$('.modul[data-modul="' + obj.modul + '"] .formContent .formLeft .fieldset').each(function(){
		numTabs++;
		var tab = $(this).attr('data-formtab');	
		var tabText = (aText[tab] == undefined) ? tab : aText[tab];
		$('.modul[data-modul="' + obj.modul + '"] .formContent .formLeft .formTabs ul').append('<li data-formtab="' +  tab + '"><span class="formTabIconError"></span>' + tabText + '</li>');
	});
	$('.modul[data-modul="' + obj.modul + '"] .formContent .formLeft .formTabs ul li:first').addClass('active');
	$('.modul[data-modul="' + obj.modul + '"] .formContent .formLeft .fieldset:first').addClass('fieldsetActive');
			
	$('.modul[data-modul="' + obj.modul + '"] .formLeft .formTabs li').off('click');
	$('.modul[data-modul="' + obj.modul + '"] .formLeft .formTabs li').on('click', function(){
		showFormTab(obj.modul, this);
	});

	initFormNav(obj);
	
	$('.modul[data-modul="' + obj.modul + '"] .formNavButtonMax').off('click');
	$('.modul[data-modul="' + obj.modul + '"] .formNavButtonMax ').on('click', function(){
		$(this).closest('.modul').toggleClass('formMaximized');
	});
	
	if($('.modul').length == 1) $('.formNavButtonMax').addClass('formNavButtonDisabled');

	resizeForm(obj.modul);
}

function initFormNav(obj){
	$('.modul[data-modul="' + obj.modul + '"] .formNavButtonNext').off('click');
	$('.modul[data-modul="' + obj.modul + '"] .formNavButtonNext ').on('click', function(){
		nextRow(obj);
	});
	
	$('.modul[data-modul="' + obj.modul + '"] .formNavButtonPrev').off('click');
	$('.modul[data-modul="' + obj.modul + '"] .formNavButtonPrev ').on('click', function(){
		prevRow(obj);
	});
}

function formResize(){
	// ## for all forms ##
	$('.modul').each(function(){
		var modul = $(this).attr('data-modul');
		resizeForm(modul);
	});
}

function resizeForm(modul){
	var hTabs = $('.modul[data-modul="' + modul + '"] .formLeft .formTabs').outerHeight(true);
	var hFooters = $('.modul[data-modul="' + modul + '"] .formLeft .formFooter').outerHeight(true);
	$('.modul[data-modul="' + modul + '"] .formLeft .fieldset').css('top', hTabs + 'px');
	$('.modul[data-modul="' + modul + '"] .formLeft .fieldset').css('bottom', hFooters + 'px');
}

function showFormTab(modul, el){
	$('.modul[data-modul="' + modul + '"] .formLeft .formTabs li').removeClass('active')
	$(el).addClass('active')
	
	var activeTab = $(el).attr('data-formtab');
	$('.modul[data-modul="' + modul + '"] .formLeft .fieldset').removeClass('fieldsetActive');
	$('.modul[data-modul="' + modul + '"] .formLeft .fieldset[data-formtab="' + activeTab + '"]').addClass('fieldsetActive');
}



function initFields(obj){
	// ## bind fields for formchecking on blur ##
	$('.modul[data-modul="' + obj.modul + '"] .formLeft .checkDirect').off('blur');
	$('.modul[data-modul="' + obj.modul + '"] .formLeft .checkDirect').on('blur', function(){
		var func = $(this).attr('data-checkfunction');
		var aFunc = func.split(';');
		for(key in aFunc){
			if(window['f_' + obj.modul][aFunc[key]] && typeof(window['f_' + obj.modul][aFunc[key]]) === 'function'){
				window['f_' + obj.modul][aFunc[key]](this);
			}else if(window[aFunc[key]] && typeof(window[aFunc[key]]) === 'function'){
				window[aFunc[key]](this);
			}
		}
	});
	
//    // ##### Set WYSIWYG editor #####
//	$('#' + aSpecsPage.idFormOuter + ' textarea.wysiwyg').each(function(){
//		var toolbar = $(this).attr('data-editor-toolbar');
//		var height = $(this).outerHeight(true);
//		
//		$(this).ckeditor({
//			customConfig: '/' + pathDirectorySystem + pathAdmin + 'config-ckeditor.js',
//			toolbar: toolbar,
//			height: height
//		});
//	});
//	
////    $('#' + idDialog + ' textarea.wysiwyg').each(function() {
////    	var conf = new Object;
////        conf['customConfig'] = '<?php echo $CONF['hostdirectory']; ?>/admin/config_ckeditor.php';
////        conf['toolbar'] = 'SYS';
////        
////    	var dataConf = $(this).attr('data-wysiwyg-conf');
////        if(dataConf != '' && dataConf != undefined){
////            var aConf = dataConf.split(';');
////            for(var i = 0; i < aConf.length; i++)
////            {	
////                var aConf2 = aConf[i].split(':');
////                conf[aConf2[0]] = aConf2[1];
////            }
////        }
////		CKEDITOR.replace($(this).attr('id'), conf);
////    });
////
////    $('#' + idDialog + ' .radiocheck').bind('click', function(){
////		var el = ($(this).attr('name'));
////    	setRadioCheck(el, 1);
////    });
////    
//    
//	
//	$('#' + aSpecsPage.idFormOuter + ' .calendar').datepicker({
//		showButtonPanel: true
//	});
//	
//    $('#' + aSpecsPage.idFormOuter + ' .calendartime').datetimepicker({
//		showSecond: false,
//		timeFormat: 'HH:mm:ss'   
//	});
//    
//    initFieldsUpload();
//	initFieldsAutocomplete();
}    

function cancelForm(modul){
	$('.modul[data-modul="' + modul + '"] .form').addClass('hidden');
	$('.modul[data-modul="' + modul + '"] .formContent').html('');
	
	var obj = {};
	obj.moduls = [];
	obj.moduls.push(modul);
//	77 hier müssen noch Kindermodule abgefragt werden
	clearData(obj);


//	$('#' + aSpecsPage.idGridOuter).parent().removeClass('contentHidden');
//	$('#' + aSpecsPage.idGridOuter).parent().removeClass('contentHeight0');
//	$('#' + aSpecsPage.idFormOuter).parent().addClass('contentHidden');
//	$('#' + aSpecsPage.idFormOuter + ' .formContent').html('');
//	
//	$('#breadcrumb .breaddataset:last', top.document).remove();
//	var bread = $('#breadcrumb', top.document).html();
//	$('#breadcrumb', top.document).html(bread);
//	window.top.navBreadcrumbInit();
//
//	$('#' + aSpecsPage.idAssigned).removeAttr('style');
//	$('#containerAssigned').html('');
//
//	if(idModulParent != ''){
//		$('#' + aSpecsPage.idAssigned, parent.document).addClass('containerAssignedClosing');
//		window.parent.closeAssignedWideDirect();
//	}
//	if(directlink == 1){
//		window.parent.closeDirectLink(cbClose, cbArgsClose);
//	}
	
}

function checkNavButton(obj){
	var idNewTmp = $('#gridTable_' + aPage.moduls[obj.modul].modulname + ' tr.selectedDataset').next('tr').attr('id');	
	if(idNewTmp == undefined){
		$('.modul[data-modul="' + obj.modul + '"] .formNavButtonNext').off('click');
		$('.modul[data-modul="' + obj.modul + '"] .formNavButtonNext').addClass('formNavButtonDisabled');
	}else{
		$('.modul[data-modul="' + obj.modul + '"] .formNavButtonNext').off('click');
		$('.modul[data-modul="' + obj.modul + '"] .formNavButtonNext ').on('click', function(){
			nextRow(obj);
		});
		$('.modul[data-modul="' + obj.modul + '"] .formNavButtonNext').removeClass('formNavButtonDisabled');
	}
	
	idNewTmp = $('#gridTable_' + aPage.moduls[obj.modul].modulname + ' tr.selectedDataset').prev('tr').attr('id');	
	if(idNewTmp == undefined){
		$('.modul[data-modul="' + obj.modul + '"] .formNavButtonPrev').off('click');
		$('.modul[data-modul="' + obj.modul + '"] .formNavButtonPrev').addClass('formNavButtonDisabled');
	}else{
		$('.modul[data-modul="' + obj.modul + '"] .formNavButtonPrev').off('click');
		$('.modul[data-modul="' + obj.modul + '"] .formNavButtonPrev ').on('click', function(){
			prevRow(obj);
		});
		$('.modul[data-modul="' + obj.modul + '"] .formNavButtonPrev').removeClass('formNavButtonDisabled');
	}
}


