function initBreadcrumb() {
	$('#breadcrumbbutton').off('click');
	$('#breadcrumbbutton').on('click', function(){
		toggleBreadcrumb();
	});
	
	$("#breadcrumb").swipe( {
		threshold: 10,
		swipeUp:function(event, direction, distance, duration, fingerCount, fingerData) {
			closeBreadcrumb();
		}
	});
}


function toggleBreadcrumb(){
	$('#breadcrumb').toggleClass('breadcrumbBlock');
	$('#breadcrumbbutton i.fa').toggleClass('fa-caret-down fa-caret-up');
}


function closeBreadcrumb(){
	$('#breadcrumb').removeClass('breadcrumbBlock');
	$('#breadcrumbbutton i.fa').switchClass('fa-caret-up', 'fa-caret-down');
}


function loadBreadcrumb(obj){ 
	var data = '';
	
	$.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-breadcrumb.php',    
		type: 'post',          
		data: data,       
		cache: false ,
		headers: {
			csrfToken: Cookies.get('csrf'),
			breadcrumb: JSON.stringify(obj)
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);

			$('#breadcrumbInner').html(result);
			initBreadcrumbNavigation(obj);
		}
	});  
}


function initBreadcrumbNavigation(obj){
	$('.breadmenue').unbind();
	$('.breadmenue').bind('click', function(){
		var modulpath = $(this).attr('data-modulpath');
		var objModulChild = splitModulpath(modulpath);
		navBreadcrumbMenue(objModulChild, this);
	});
	$('.breaddataset').unbind();
	$('.breaddataset').bind('click', function(){
		var modulpath = $(this).attr('data-modulpath');
		var objModulChild = splitModulpath(modulpath);
		navBreadcrumbDataset(objModulChild, this);
	});
}


function navBreadcrumbMenue(obj, el){
	if(obj.id_mod == 0){
		var modulpath = $('.modul:first').attr('data-modulpath');
		var objModul = splitModulpath(modulpath)
		navBreadcrumbMenue(objModul);
	}else{
		$('#modul_' + obj.modulpath).find('.childmodulOpen').each(function(){
			var modulpath = $(this).attr('data-modulpath');
			var objModulChild = splitModulpath(modulpath)
			
			if($('#breadcrumbInner span:last').hasClass('breaddataset')) cancelForm(objModulChild);
			childmodulWideClose(objModulChild);
		});
		
		if($('#modul_' + obj.modulpath).hasClass('childmodulWideOpenDirect')) $('#modul_' + obj.modulpath).switchClass('childmodulWideOpenDirect', 'childmodulWideOpenManually');
		cancelForm(obj);
	}
	closeBreadcrumb();
}


function navBreadcrumbDataset(obj, el){
	$('#modul_' + obj.modulpath).find('.childmodulOpen').each(function(){
		var modulpath = $(this).attr('data-modulpath');
		var objModulChild = splitModulpath(modulpath)
		
		if($('#breadcrumbInner span:last').hasClass('breaddataset')) cancelForm(objModulChild);
		childmodulWideClose(objModulChild);
	});
	closeBreadcrumb();
}


