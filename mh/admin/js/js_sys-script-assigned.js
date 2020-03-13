var countAnimation = 0;

function resetCountAnimation(){
	countAnimation = 0;
}

function childmodulWideOpenManually(obj){
	if($('#modul_' + obj.modulpath + ' .formRight .formTabs li.active').length == 1){
		var modulpath = $('#modul_' + obj.modulpath + ' .formRight .formTabs li.active').attr('data-modulpath');
		var objModulChild = splitModulpath(modulpath);

		$('#modul_' + objModulChild.modulpath).addClass('childmodulWideOpenManually');
		childmodulWideOpen(objModulChild);
	}
}

function childmodulWideOpenDirect(obj){
	if(!$('#modul_' + obj.modulpath).hasClass('childmodulWideOpenManually')){
		$('#modul_' + obj.modulpath).addClass('childmodulWideOpenDirect');
		childmodulWideOpen(obj);
	}
}

function childmodulWideOpen(obj){
	resetCountAnimation();
	var objModulParent = splitModulpathParent(obj.modulpath);
	var objModul = objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	
	$('#modul_' + obj.modulpath + ' > div').addClass('childmodulUnvisible');

	$('#modul_' + obj.modulpath).prepend('<div class="formChildmodulClose"><div class="formMiddleClose"></div></div>');
	$('#modul_' + obj.modulpath + ' .formMiddleClose').off('click');
	$('#modul_' + obj.modulpath + ' .formMiddleClose').on('click', function(){
		childmodulWideCloseManually(obj);
	});

	$('#modul_' + obj.modulpath).addClass('childmodulOpen childmodulOpenZ childmodulOpenBG');
	var childposTopOld = $('#modul_' + obj.modulpath).css('top').replace('px','');
	$('#modul_' + obj.modulpath).attr('data-styletop', childposTopOld);
	var childposLeftOld = $('#modul_' + obj.modulpath).css('left').replace('px','');
	$('#modul_' + obj.modulpath).attr('data-styleleft', childposLeftOld);

	var childposTop = childposTopOld;
	childposTop -= $('#modul_' + objModulParent.modulpath + ' .formRight .formTabs').outerHeight(true);
	childposTop -= $('#modul_' + objModulParent.modulpath + ' .form .tabFormFilter').outerHeight(true);
	var childposLeft = childposLeftOld;
	childposLeft -= $('#modul_' + objModulParent.modulpath + ' .formLeft').outerWidth(true);
	if($('#modul_' + objModulParent.modulpath + ' .formRight').length > 0) childposLeft -= $('#modul_' + objModulParent.modulpath + ' .formRight').css('margin-left').replace('px','');
	$('#modul_' + obj.modulpath).css('top', childposTop + 'px');
	$('#modul_' + obj.modulpath).css('left', childposLeft + 'px');

	var tabText = (objText[objModul.modul_label] == undefined) ? objModul.modul_label : objText[objModul.modul_label];
	$('#breadcrumbInner').append('<span class="breadmenue" data-modulpath="' + obj.modulpath + '">'+tabText+'</span>');
	initBreadcrumbNavigation(obj);
}

function childmodulWideCloseManually(obj){
	if($('#modul_' + obj.modulpath).hasClass('childmodulWideOpenManually')) childmodulWideClose(obj);
}

function childmodulWideCloseDirect(obj){
	if($('#modul_' + obj.modulpath).hasClass('childmodulWideOpenDirect')) childmodulWideClose(obj);
}

function childmodulWideClose(obj){
	resetCountAnimation();
	
	$('#modul_' + obj.modulpath + ' > div').addClass('childmodulUnvisible');
	$('#modul_' + obj.modulpath).addClass('childmodulOpenBG');
	$('#modul_' + obj.modulpath + ' .formChildmodulClose').remove();

	var childposTop = $('#modul_' + obj.modulpath).attr('data-styletop');
	var childposLeft = $('#modul_' + obj.modulpath).attr('data-styleleft');
	$('#modul_' + obj.modulpath).css('top', childposTop + 'px');
	$('#modul_' + obj.modulpath).css('left', childposLeft + 'px');
	
	$('#modul_' + obj.modulpath).removeClass('childmodulOpen');
	$('#modul_' + obj.modulpath).removeClass('childmodulWideOpenManually');
	$('#modul_' + obj.modulpath).removeClass('childmodulWideOpenDirect');
	window.setTimeout(function(){$('#modul_' + obj.modulpath).removeClass('childmodulOpenZ')}, 600);
	
	
	$('#breadcrumbInner .breadmenue[data-modulpath="' + obj.modulpath + '"]').remove();
	initBreadcrumbNavigation(obj);
}
