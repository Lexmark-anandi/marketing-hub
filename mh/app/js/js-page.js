function loadPage(objHis){
	$('body').removeClass('editMode');

	clearEdit();
	var id_page = $('.navActive').attr('data-caid');
	$('.search #searchfield').val('');
	
	// ## set cookie ## 
	var objChange = {};
	objChange['id_page'] = $('.navActive').attr('data-caid');
	objChange['ovPage'] = 1;
	objChange['ovOrder'] = 'title';
	objChange['ovOrderDir'] = 'ASC';
	changeCookie('activesettings', objChange);

	// ## set entry for history ##
	setHistory(objHis);
			
	
	loadOverview();
}


function loadOverview(){
	waiting('body'); 
	clearAssetTmp(); 
	
	var id_page = $('.navActive').attr('data-caid');
	var asset_category = $('.search #asset_category option:selected').val();
	if(asset_category == undefined) asset_category = '';
	var searchfield = $('.search #searchfield').val();
	if(searchfield == undefined) searchfield = '';
	 
	var hasCampaign = ($('.navLeft li[data-caid="campaigns"]').length > 0) ? 1 : 0;
	var hasPromotion = ($('.navLeft li[data-caid="promotions"]').length > 0) ? 1 : 0;
	
	var data = 'asset_category=' + asset_category;
	data += '&searchfield=' + searchfield;
	data += '&hasCampaign=' + hasCampaign;
	data += '&hasPromotion=' + hasPromotion;

	$.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu_sys-pageload.php' + initLogin,    
		type: 'post',          
		data: data,       
		cache: false,
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);
			initLogin = '';
			
			var objSettings = Cookies.getJSON('activesettings');

			(id_page == 'profile') ? $('body').addClass('profileMode') : $('body').removeClass('profileMode');
			var objResult = JSON.parse(result);
			
			// ## load content ##
			$('#mainOuter .pagewidth .mainLeft').html(objResult.contentLeft);
			$('#mainOuter .pagewidth .mainRight').html(objResult.contentRight);
			if(objResult.ovNumPages != undefined) $('.ovNumPages').html(objResult.ovNumPages);
			
			$('.ovOrder i').removeClass('icon-order-asc');
			$('.ovOrder i').removeClass('icon-order-desc');
			if(objSettings.ovOrderDir == 'ASC') $('.ovOrder[data-order="' + objSettings.ovOrder + '"] i').addClass('icon-order-asc');
			if(objSettings.ovOrderDir == 'DESC') $('.ovOrder[data-order="' + objSettings.ovOrder + '"] i').addClass('icon-order-desc');

			$('.buttonAll').off('mouseover mouseout mousedown mouseup click blur');
			$('.ovBoxOuter button').off('mouseover mouseout mousedown mouseup click blur');
			
			$('.buttonAll').on('mouseover', function(){
				$('.buttonHover').removeClass('buttonHover');
				$(this).addClass('buttonHover');
			});
			$('.buttonAll').on('mouseout', function(){
				$('.buttonHover').removeClass('buttonHover');
			});
			$('.buttonAll').on('mousedown', function(){
				$('.buttonDown').removeClass('buttonDown');
				$(this).addClass('buttonDown');
			});
			$('.buttonAll').on('mouseup blur', function(){
				$('.buttonDown').removeClass('buttonDown');
			});
			

			$('.buttonSwitchOverview button').removeClass('buttonActive');
			$('.buttonSwitchOverview button[data-overview="' + objSettings.ovType + '"]').addClass('buttonActive');

			$('.buttonSwitchOverview button').off('click');
			$('.buttonSwitchOverview button').on('click', function(){
				$('.buttonSwitchOverview button').removeClass('buttonActive');
				$(this).addClass('buttonActive');
				initOverview();
			});
			
			$('.search #searchfield').val('');
			$('.search #searchfield').val(searchfield);
			$('.search #searchfield').off('keyup');
			$('.search #searchfield').on('keyup', function(e){
				if(e.keyCode == 13){
					var objChange = {};
					objChange['ovPage'] = 1;
					changeCookie('activesettings', objChange);
					
					loadOverview();
				}
			});
			
			$('.search .icon-search').off('click');
			$('.search .icon-search').on('click', function(){
				var objChange = {};
				objChange['ovPage'] = 1;
				changeCookie('activesettings', objChange);
					
				loadOverview();
			});
			
			$('.search #asset_category option').prop('selected', false);
			$('.search #asset_category option[value="' + asset_category + '"]').prop('selected', true);
			$('.search #asset_category').off('change');
			$('.search #asset_category').on('change', function(){
				var objChange = {};
				objChange['ovPage'] = 1;
				changeCookie('activesettings', objChange);
				
				loadOverview();
			});

			if($('.ovTab').length > 0){
				initOverview();
				initPager();
			}
			
			
			$('.ovBoxOuter button[data-action="create"]').on('click', function(){
				showEdit(this);
			});
			
			$('.ovBoxOuter button[data-action="delete"]').on('click', function(){
				checkDelete(this);
			});
			
			$('.ovBoxOuter button[data-action="download"]').on('click', function(){
				exportExCobranding(this)
			});
			
			$('.ovBoxOuter button[data-action="preview"]').on('click', function(){
				var tempid = $(this).closest('.ovBoxOuter').attr('data-tempid');
				var promid = $(this).closest('.ovBoxOuter').attr('data-promid');
				var campid = $(this).closest('.ovBoxOuter').attr('data-campid');
				var caid = $(this).closest('.ovBoxOuter').attr('data-caid');
				
				var objGallery = {};
				objGallery.tempid = tempid;
				objGallery.promid = promid;
				objGallery.campid = campid;
				objGallery.caid = caid;
				objGallery.url = objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-template-gallery.php';
				loadGallery(objGallery);
			});
			
			$('.ovBoxOuter button[data-action="export"]').on('click', function(){
				assetExportDirect(this);
			});
			
			$('.ovBoxOuter button[data-action="exportsplit"]').on('click', function(){
				showExportDropdown(this);
			});
			
			$('.ovOrder').off('click');
			$('.ovOrder').on('click', function(){
				changeOrder(this);
			});

			if(id_page == 'profile') initLogoUpload();

			unwaiting();
		}
	});  
}




function initOverview(){
	var type = $('.buttonSwitchOverview .buttonActive').attr('data-overview');

	var objChange = {};
	objChange['ovType'] = type;
	changeCookie('activesettings', objChange);

	if(type == 'grid'){
		$('#mainOuter .mainLeft').removeClass('overviewTable');
		$('#mainOuter .mainLeft').addClass('overviewGrid');
	}else{
		$('#mainOuter .mainLeft').removeClass('overviewGrid');
		$('#mainOuter .mainLeft').addClass('overviewTable');
	}

	// height for table body
	posOverviewTop = $('.ovTab .ovTbody').offset().top;
	posOverviewFooterTop = $('#ovPagerOuter').offset().top;
	var heightOverviewBodyOrg = $('.ovTab .ovTbody').height();
	var heightOverviewBody = posOverviewFooterTop - posOverviewTop;
	$('.ovTab .ovTbody').css('height', heightOverviewBody+'px');
	
	// correction for table head if scrollbar is visible
	if($('.ovTbody').hasScrollBar() == true){
		$('.ovThead .ovBoxScrolldiff').addClass('addPaddingScroll');
	}else{
		$('.ovThead .ovBoxScrolldiff').removeClass('addPaddingScroll');
	}
	
	$('#mainOuter .mainLeft .ovTbody').scrollTop(0);
}


function initPager(){
	var objSettings = Cookies.getJSON('activesettings');
	var ovPage = objSettings.ovPage;
	var ovRange = objSettings.ovRange;
	
	$('#pagingPage option').prop('selected', false);
	$('#pagingPage option[value="' + ovPage + '"]').prop('selected', true);

	$('#pagingRange option').prop('selected', false);
	$('#pagingRange option[value="' + ovRange + '"]').prop('selected', true);
	
	(ovPage == 1) ? $('.pagingPrev').addClass('ovPagerButtonDisabled') : $('.pagingPrev').removeClass('ovPagerButtonDisabled');
	(ovPage == $('#pagingPage option').length) ? $('.pagingNext').addClass('ovPagerButtonDisabled') : $('.pagingNext').removeClass('ovPagerButtonDisabled');
}


function changePager(type){
	var ovPage = $('#pagingPage option:selected').val();
	if(type == 'next') ovPage++;
	if(type == 'prev') ovPage--;
	if(ovPage < 1) ovPage = 1;
	if(ovPage > $('#pagingPage option').length) ovPage = $('#pagingPage option').length;
	if(type == 'range') ovPage = 1;
	
	var objChange = {};
	objChange['ovRange'] = $('#pagingRange option:selected').val();
	objChange['ovPage'] = ovPage;
	changeCookie('activesettings', objChange);
	
	initPager();
	loadOverview();
}


function changeOrder(el){
	var order = $(el).attr('data-order');
	var objSettings = Cookies.getJSON('activesettings');
	
	var objChange = {};
	objChange['ovPage'] = 1;
	objChange['ovOrder'] = order;
	objChange['ovOrderDir'] = 'ASC';
	if(order == objSettings.ovOrder){
		objChange['ovOrderDir'] = (objSettings.ovOrderDir == 'ASC') ? 'DESC' : 'ASC';
	}
	changeCookie('activesettings', objChange);
	
	initPager();
	loadOverview();
}


function checkDelete(el){
	var objDialog = {};
	objDialog.el = el;
	objDialog.title = objText.deleteAsset;
	objDialog.formtext = objText.checkDeleteAsset;
	objDialog.objButtons = {};
	objDialog.objButtons[objText.cancel] = function() { closeDialog(this); }            
	objDialog.objButtons[objText['delete']] = function() { deleteAssetDo(el) }
	
	openDialogConfirm(objDialog);
}



function deleteAssetDo(el){
	var asid = $(el).closest('.ovBoxOuter').attr('data-asid');
	console.log(asid)

	var data = 'asid=' + asid;

	$.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-asset-delete.php',    
		type: 'post',          
		data: data,       
		cache: false,
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);
			
			loadOverview();
		}
	});

	closeDialog();
	
}



function showExportDropdown(el){
	$('.buttonDropdownMenu').removeClass('buttonDropdownMenuTop');
	$('.buttonDropdownMenu').removeClass('buttonDropdownMenuLeft');
	$('.buttonDropdownMenu').hide();
	$(el).closest('.buttongroup').find('.buttonDropdownMenu').show();

	var objVis = isVisible('.ovTbody', '.buttonDropdownMenu:visible');
	if(objVis.bottom == false){
		$('.buttonDropdownMenu:visible').addClass('buttonDropdownMenuTop');
	}
	if(objVis.left == false){
		$('.buttonDropdownMenu:visible').addClass('buttonDropdownMenuLeft');
	}
}


$(document).click(function(event) { 
    if(!$(event.target).closest('.buttonDropdown').length) {
		$('.buttonDropdownMenu').removeClass('buttonDropdownMenuTop');
		$('.buttonDropdownMenu').removeClass('buttonDropdownMenuLeft');
		$('.buttonDropdownMenu').hide();
    }        
});






function isVisible(win, el){
    var viewport = $(win).offset();
    viewport.right = viewport.left + $(win).width();
    viewport.bottom = viewport.top + $(win).height();

    var bounds = $(el).offset();
    bounds.right = bounds.left + $(el).outerWidth();
    bounds.bottom = bounds.top + $(el).outerHeight();
	
	var objVis = {};
	objVis.top = (viewport.top > bounds.top) ? false : true;
	objVis.right = (viewport.right < bounds.right) ? false : true;
	objVis.bottom = (viewport.bottom < bounds.bottom) ? false : true;
	objVis.left = (viewport.left > bounds.left) ? false : true;
	return objVis;
};



function exportExCobranding(el){
	var asid = $(el).closest('.ovBoxOuter').attr('data-asid');
	var tempid = $(el).closest('.ovBoxOuter').attr('data-tempid');
	var promid = $(el).closest('.ovBoxOuter').attr('data-promid');
	var campid = $(el).closest('.ovBoxOuter').attr('data-campid');
	
	var data = 'asid=' + asid;
	data += '&tempid=' + tempid;
	data += '&promid=' + promid;
	data += '&campid=' + campid;
	
	$.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-asset-export-excobranding.php',    
		type: 'post',          
		data: data,       
		cache: false,
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);

			var objResult = JSON.parse(result);

			downloadMedia(objResult.filesys_filename, objResult.filename, objResult.folder, 'export');
		}
	});
	
	
	console.log(asid)
}
