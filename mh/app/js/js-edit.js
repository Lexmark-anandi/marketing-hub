var deactivateCompbox = 1;


function showEdit(el){
	$('body').removeClass('promoMode');
	
	var obj = {};
	obj.id_page = $('.navActive').attr('data-caid');
	obj.id_asid = $(el).closest('.ovBoxOuter').attr('data-asid');
	obj.id_caid = $(el).closest('.ovBoxOuter').attr('data-caid');
	obj.id_promid = $(el).closest('.ovBoxOuter').attr('data-promid');
	obj.id_campid = $(el).closest('.ovBoxOuter').attr('data-campid'); 
	if(obj.id_promid != 0) obj.id_caid = 'promotions';
	if(obj.id_campid != 0) obj.id_caid = 'campaigns';
	
	if(obj.id_caid == 'promotions' || obj.id_caid == 'campaigns'){
		// promotion mode
		$('body').addClass('promoMode');
		obj.id_promid = $(el).closest('.ovBoxOuter').attr('data-promid');
		
		loadPromotion(obj);
	}else{
		// template mode
		obj.id_promid = 0;
		obj.id_tempid = $(el).closest('.ovBoxOuter').attr('data-tempid');
		
		loadTemplate(obj);
	}
	
}


function loadPromotion(obj){
	waiting('body');
	
	var data = 'id_page=' + obj.id_page;
	data += '&id_promid=' + obj.id_promid;
	data += '&id_campid=' + obj.id_campid;
	data += '&id_asid=' + obj.id_asid;
	data += '&id_caid=' + obj.id_caid;
	
	var url = objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-promotion-read.php' + initLogin;
	if(obj.id_campid != 0) url = objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-campaigns-read.php' + initLogin;
	
	$.ajax({  
		url: url,    
		type: 'post',          
		data: data,       
		cache: false,
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);
			
			var objResult = JSON.parse(result);

			$('.editPromotions').html('');
			for(var tempX in objResult.templates){
				var temp = tempX.replace('a#','');
				$('.editPromotions').append('<div class="editTemplatesOuter" data-tempid="' + temp + '"><div class="editTemplatesThumb"><img src="' + objResult.templates[tempX].src + '"></div><div class="editTemplatesPage">' + objResult.templates[tempX].pagelabel + '</div></div>');
			}
			$('.editTemplatesOuter:first').addClass('editTemplatesOuterActive');

			$('.editTemplatesOuter').off('mouseover mouseout click');
			$('.editTemplatesOuter').on('mouseout', function(){
				$(this).removeClass('editTemplatesOuterHover');
				//if($('#editOuter').hasClass('editBanner')) $(this).closest('.editTemplatesGroup').removeClass('editTemplatesOuterHover');
			});
			$('.editTemplatesOuter').on('mouseover', function(){
				$('.editTemplatesOuter').removeClass('editTemplatesOuterHover');
				$(this).addClass('editTemplatesOuterHover');
				//if($('#editOuter').hasClass('editBanner')) $(this).closest('.editTemplatesGroup').addClass('editTemplatesOuterHover');
			});
			
			$('.editTemplatesOuter').on('click', function(){
				$('.editTemplatesOuter').removeClass('editTemplatesOuterActive');
				//if($('#editOuter').hasClass('editBanner')) $('.editTemplatesGroup').removeClass('editTemplatesOuterActive');
				$(this).addClass('editTemplatesOuterActive');
				//if($('#editOuter').hasClass('editBanner')) $(this).closest('.editTemplatesGroup').addClass('editTemplatesOuterActive');
				//loadTemplatepage(this);
				var objNew = {};
				objNew.id_page = obj.id_page;
				objNew.id_promid = obj.id_promid;
				objNew.id_campid = obj.id_campid;
				objNew.id_tempid = $(this).closest('.editTemplatesOuter').attr('data-tempid');
				objNew.id_asid = objResult.id_asid;
				objNew.id_caid = obj.id_caid;
	
				loadTemplate(objNew);
			});

			
			var objNew = {};
			objNew.id_page = obj.id_page;
			objNew.id_promid = obj.id_promid;
			objNew.id_campid = obj.id_campid;
			objNew.id_tempid = $('.editPromotions .editTemplatesOuter:first').attr('data-tempid');
			objNew.id_asid = objResult.id_asid;
			objNew.id_caid = obj.id_caid;

			loadTemplate(objNew);
		}
	});
}


function loadTemplate(obj){
	waiting('body');
	
	var data = 'id_page=' + obj.id_page;
	data += '&id_promid=' + obj.id_promid;
	data += '&id_campid=' + obj.id_campid;
	data += '&id_tempid=' + obj.id_tempid;
	data += '&id_asid=' + obj.id_asid;
	data += '&id_caid=' + obj.id_caid;

	$('#formAsset').ajaxSubmit({
		url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-asset-save-tmp.php',
		clearForm: false, 
		type: 'post', 
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function(result, status, jqXHR){
			$.ajax({  
				url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-template-read.php' + initLogin,    
				type: 'post',          
				data: data,       
				cache: false,
				headers: {
					csrfToken: Cookies.get('csrf')
				},
				success: function (result, status, jqXHR) {
					actualizeStatus(result, status);
					
					var objResult = JSON.parse(result);
					$('body').addClass('editMode');
					
					objComponents = objResult.components;
					$('.editForm input[name="components"]').val(JSON.stringify(objComponents));
					$('#formAsset input[name="assettitle"]').val(objResult.title);
					$('#formAsset input[name="id_asid"]').val(objResult.id_asid);
					$('#formAsset input[name="id_promid"]').val(obj.id_promid);
					$('#formAsset input[name="id_campid"]').val(obj.id_campid);
					$('#formAsset input[name="id_caid"]').val(obj.id_caid);
					
					$('#editOuter').removeClass('editBanner');
					$('#editOuter').removeClass('editEmail');
					if(objResult.type == 'banner') $('#editOuter').addClass('editBanner');
					if(objResult.type == 'email') $('#editOuter').addClass('editEmail');
					
					// thumbnails
					$('.editThumbnails').html('');
					for(var group in objResult.thumbnails){
						$('.editThumbnails').append('<div class="editThumbnailsGroup" data-group="' + group + '" data-bfid="" data-etid=""></div>');
						if(group != 'na'){
							$('.editThumbnailsGroup[data-group="' + group + '"]').html('<div class="editThumbnailsGroupHead">' + group + '</div>');
						}
						
						var i = 0;
						for(var page in objResult.thumbnails[group]){
							if(i == 0 && objResult.type == 'banner'){
								if(objResult.thumbnails[group][page].dimension != undefined){
									$('.editThumbnailsGroup[data-group="' + group + '"] .editThumbnailsGroupHead').append('<div class="bannerDimension">' + objResult.thumbnails[group][page].dimension + '</div>')
								}
							}

							if(i == 0 || objResult.type != 'banner'){
								$('.editThumbnailsGroup[data-group="' + group + '"]').attr('data-bfid', objResult.thumbnails[group][page].bfid);
								$('.editThumbnailsGroup[data-group="' + group + '"]').attr('data-etid', objResult.thumbnails[group][page].etid);
								$('.editThumbnailsGroup[data-group="' + group + '"]').append('<div class="editThumbnailsOuter" data-tempid="' + obj.id_tempid + '" data-page="' + page + '" data-tp="' + objResult.thumbnails[group][page].tp + '" data-pageid="' + objResult.thumbnails[group][page].pageid + '"><div class="editThumbnailsThumb"><img src="' + objResult.thumbnails[group][page].src + '"></div><div class="editThumbnailsPage">' + objResult.thumbnails[group][page].pagelabel + '</div></div>');
							}
							i++;
						}
					}
					$('.editThumbnailsOuter:first').addClass('editThumbnailsOuterActive');
					if($('#editOuter').hasClass('editBanner')) $('.editThumbnailsGroup:first').addClass('editThumbnailsOuterActive');
					if($('#editOuter').hasClass('editEmail')) $('.editThumbnailsGroup:first').addClass('editThumbnailsOuterActive');
					formatThumbEditable();
					
					$('.editThumbnailsOuter').off('mouseover mouseout click');
					$('.editThumbnailsOuter').on('mouseout', function(){
						$(this).removeClass('editThumbnailsOuterHover');
						if($('#editOuter').hasClass('editBanner')) $(this).closest('.editThumbnailsGroup').removeClass('editThumbnailsOuterHover');
					});
					$('.editThumbnailsOuter').on('mouseover', function(){
						$('.editThumbnailsOuter').removeClass('editThumbnailsOuterHover');
						$(this).addClass('editThumbnailsOuterHover');
						if($('#editOuter').hasClass('editBanner')) $(this).closest('.editThumbnailsGroup').addClass('editThumbnailsOuterHover');
					});
					
					$('.editThumbnailsOuter').on('click', function(){
						$('.editThumbnailsOuter').removeClass('editThumbnailsOuterActive');
						if($('#editOuter').hasClass('editBanner')) $('.editThumbnailsGroup').removeClass('editThumbnailsOuterActive');
						$(this).addClass('editThumbnailsOuterActive');
						if($('#editOuter').hasClass('editBanner')) $(this).closest('.editThumbnailsGroup').addClass('editThumbnailsOuterActive');
						loadTemplatepage(this);
					});
		
					
					// preview
					$('.editPreview').html('');
					for(var key in objResult.preview){
						if(objResult.preview[key].previewcode == undefined) objResult.preview[key].previewcode = '';
						if(objResult.preview[key].productcode == undefined) objResult.preview[key].productcode = '';
						if(objResult.preview[key].previewcss == undefined) objResult.preview[key].previewcss = '';
						if(objResult.preview[key].editcss == undefined) objResult.preview[key].editcss = '';
						
						if(objResult.preview[key].editcss != '') $('head').append('<style class="emailstyle">' + objResult.preview[key].editcss + '</style>');
						
						$('.editPreview').append('<div class="editPreviewInner" data-tempid="' + objResult.preview[key].temp + '" data-page="' + objResult.preview[key].page + '" data-tp="' + objResult.preview[key].tp + '" data-pageid="' + objResult.preview[key].pageid + '"><div class="editPreviewBackground">' + objResult.preview[key].src + '</div><div class="editPreviewComponents">' + objResult.preview[key].previewcode + '</div></div>');
						if($('.editPreviewInner[data-page="2"]').length > 1) $('.editPreviewInner[data-pageid="' + objResult.preview[key].tp + '_2_0"]').css('display', 'none');
					}
					$('.editPreviewInner[data-page="2"]').wrapAll('<div class="editPreviewSortable"></div>');
					if($('#editOuter').hasClass('editBanner')){
						$('.editPreviewInner').each(function(){
							var p = $(this).attr('data-page');
							if(p == 1) $(this).append('<div class="editFramename">' + objText.firstframe + '</div>');
							if(p == 2) $(this).append('<div class="editFramename">' + objText.productframe + '</div>');
							if(p == 3) $(this).append('<div class="editFramename">' + objText.lastframe + '</div>');
						});
					}
		
				
					$('.editPreviewComponents').off('click');
					$('.editPreviewComponents').on('click', function(){
						deactivatePlaceholder(this);
					});
					window.setTimeout(function(){loadComponents();}, 400);
					//loadComponents();
					
					
					// form
					$('.editFormComponent').html(objResult.toolsform);
					$('.editFormConfiguration').html(objResult.configurationform);
					$('.editFormConfiguration .fieldset').addClass('fieldsetActive');
		
					var tpFirst = $('.editPreviewInner[data-page="1"]').attr('data-tp');
					if(tpFirst != undefined){
						$('#formAsset input[name="durationFirstframe"]').val(objResult.configuration[tpFirst].duration);
						if($('#editOuter').hasClass('editBanner') && objResult.configuration[tpFirst].showframe == 0){
							$('.editPreviewInner[data-tp="' + tpFirst + '"]').addClass('editPreviewInnerHide');
							$('.textfieldButtonHideFirst .icon').addClass('icon-icon-eye_strike_thru');
						}
					}
					
					var tpLast = $('.editPreviewInner[data-page="3"]').attr('data-tp');
					if(tpLast != undefined){
						$('#formAsset input[name="durationLastframe"]').val(objResult.configuration[tpLast].duration);
						if($('#editOuter').hasClass('editBanner') && objResult.configuration[tpLast].showframe == 0){
							$('.editPreviewInner[data-tp="' + tpLast + '"]').addClass('editPreviewInnerHide');
							$('.textfieldButtonHideLast .icon').addClass('icon-icon-eye_strike_thru');
						}
					}
		
		
					initFormConfiguration(objResult.printer);
		
		
					$('.editForm .componentformfield').off('input');
					$('.editForm .componentformfield').on('input', function(){
						changeFormatComponent(this);
					});
		
					$('.editForm .buttonAll').off('click');
					$('.editForm .buttonAll[data-action="cancel"]').on('click', function(){
						$('body').removeClass('editMode');
						window.setTimeout(function(){clearEdit();}, 500);
					});
					$('.editForm .buttonAll[data-action="save"]').on('click', function(){
						assetSave();
					});
					$('.editForm .buttonAll[data-action="export"]').on('click', function(){
						assetExport();
					});
//					
					
					$('.editForm textarea.wysiwyg').each(function(){
						var wysiwygName = $(this).attr('name');
						
						var objConfig = JSON.parse($(this).attr('data-config'));
						if(objConfig.customConfig == undefined) objConfig.customConfig = objSystem.directoryInstallation + objSystem.pathAdmin + 'config-ckeditor.js';
						if(objConfig.toolbar == undefined) objConfig.toolbar = 'SYS';
						if(objConfig.height == undefined) objConfig.height = $(this).outerHeight(true);
				
						$(this).ckeditor(objConfig);
						// ## fill data if init is to slow ##
		//				CKEDITOR.instances[$('#modul_' + obj.modulpath + ' .formRight textarea[name="' + wysiwygName + '"]').attr('id')].on( 'instanceReady', function(evt) {
		//								var formData = $('#modul_' + obj.modulpath + ' .formRight [name="formdata"]').val();
		//								if(formData != '' && formData != undefined){
		//									objFormData = JSON.parse(formData);
		//									CKEDITOR.instances[$('#modul_' + obj.modulpath + ' .formRight textarea[name="' + wysiwygName + '"]').attr('id')].setData(objFormData[wysiwygName]);
		//								}
		//				} );
					});
	
					for (var i in CKEDITOR.instances) {
						CKEDITOR.instances[i].on('change', function(e) {
							var compPageId = $('.editPreviewInnerActive').attr('data-pageid');
							var compId = $('.compboxOuterActive').attr('id');

							var thisHTML = e.editor.getData();
							var outHTML = resizeFontsize(thisHTML);
							$('.compboxOuterActive .content').html('<div class="verticalalignbox">' + outHTML + '</div>');
							objComponents.pages['page_' + compPageId][compId]['content'] = thisHTML;		
							$('.editForm input[name="components"]').val(JSON.stringify(objComponents));
						});
					}	
		
					window.setTimeout(function(){resizeEdit();}, 320);
					
					unwaiting();
				}
			});
		}
	});
}


function resizeFontsize(html){
	var outHTML = html;
	var pattern = /font-size:( )*(\d)*pt/gi;
	var res = outHTML.match(pattern);
	
	for(var key in res){
		var size = res[key];
		size = size.replace('font-size:', '');
		size = size.replace(' ', '');
		size = size.replace('pt', '');
		size = size * componentFactor;
		
		outHTML = outHTML.replace(res[key], 'font-size:' + size + 'pt');
	}
	
	return outHTML;	
}


function resizeEdit(){
	var imgWidthOrg = ($('.editPreviewBackground img').length > 0) ? $('.editPreviewBackground img').attr('data-width') : $('.editPreview').width();
	var imgHeightOrg = ($('.editPreviewBackground img').length > 0) ? $('.editPreviewBackground img').attr('data-height') : $('.editPreview').height();
	var previewWidth = $('.editPreview').width();
	var previewHeight = $('.editPreview').height();
	
	var imgWidthFac = previewWidth / imgWidthOrg;
	var imgHeightFac = previewHeight / imgHeightOrg;
	var factor = (imgWidthFac > imgHeightFac) ? imgHeightFac : imgWidthFac;
	componentFactor = factor;
	
	if(factor < 1){
		var imgWidthReal = imgWidthOrg * factor;
		var imgHeightReal = imgHeightOrg * factor;
	}else{
		var imgWidthReal = imgWidthOrg;
		var imgHeightReal = imgHeightOrg;
		componentFactor = 1;
	}
	
	$('.editPreviewBackground').css('width', imgWidthReal + 'px');
	$('.editPreviewBackground').css('height', imgHeightReal + 'px');
	$('.editPreviewComponents').css('width', imgWidthReal + 'px');
	$('.editPreviewComponents').css('height', imgHeightReal + 'px');

//	$('.editPreviewInner').filter(':visible').each(function(){
//		var compPageId = $(this).attr('data-pageid');
//		$(this).find('.compboxOuter').each(function(){
//			if($(this).parents('.editPreviewInner').length == 0){
//				var compboxid = $(this).attr('id');
//				var val = objComponents.pages['page_' + compPageId][compboxid]['fontsize'];
//				$(this).css('font-size', (val * componentFactor) + 'pt');
//			}
//		});
//	});
	$('.compboxOuter').each(function(){
		var compPageId = $(this).closest('.editPreviewInner').attr('data-pageid');
		var compboxid = $(this).attr('id');
		var val = objComponents.pages['page_' + compPageId][compboxid]['fontsize'];
		if($(this).css('font-size') != '0px'){
			$(this).css('font-size', (val * componentFactor) + 'pt');
		}else{
			$(this).css('font-size', '');
		}
	});



	
	var i = 0;
	$('.editPreviewInner').filter(':visible').each(function(){
		if($(this).parents('.editPreviewInner').length == 0){
			$(this).css('width', imgWidthReal + 'px');
			$(this).css('height', imgHeightReal + 'px');
			
			if(imgWidthReal > imgHeightReal){
				$('#editOuter').removeClass('editPortrait');
				$('#editOuter').addClass('editLandscape');
				if($('#editOuter').hasClass('editBanner')) $(this).css('height', (imgHeightReal*1 + 10 + 30) + 'px');
				$(this).css('top', (i * (imgHeightReal*1 + 20 + 30)) + 'px');
				$(this).css('left', '0px');
			}else{
				$('#editOuter').removeClass('editLandscape');
				$('#editOuter').addClass('editPortrait');
				if($('#editOuter').hasClass('editBanner')) $(this).css('width', (imgWidthReal*1 + 10) + 'px');
				$(this).css('left', (i * (imgWidthReal*1 + 20)) + 'px');
				$(this).css('top', '0px');
			}
			i++;
		}
	});
}



//function size(obj) { 
//	window['f_##modul_name##']['resizeComponentsPage'](obj);
//	
//	$('#modul_' + obj.modulpath + ' .formRight .compboxOuter').each(function(){
//		var id_tpeid = $(this).attr('data-tpeid');
//		var compPageId = $('#modul_' + obj.modulpath + ' .formRight .formComponentThumbOuterActive').attr('data-pageid');
//		var val = objComponents.pages['page_' + compPageId]['compboxOuter_' + id_tpeid]['fontsize'];
//		
//		$(this).css('font-size', (val * componentFactor) + 'pt');
//
//	});
//}; 


function loadTemplatepage(el){
	waiting('body');
	$('#formAsset .formmessage').html('');
	deactivatePlaceholder();
	
	var id_page = $('.navActive').attr('data-caid');
	var id_promid = $('.editForm input[name="id_promid"]').val();
	var id_campid = $('.editForm input[name="id_campid"]').val();
	var id_tempid = $(el).attr('data-tempid');
	var id_tpid = $(el).attr('data-tpid');
	var id_bfid = $(el).closest('.editThumbnailsGroup').attr('data-bfid');
	var page = $(el).attr('data-page');
	var pageid = $(el).attr('data-pageid');
	var id_asid = $('.editForm input[name="id_asid"]').val();
	var id_caid = $('.editForm input[name="id_caid"]').val();
	
	var data = 'id_page=' + id_page;
	data += '&id_promid=' + id_promid;
	data += '&id_campid=' + id_campid;
	data += '&id_tempid=' + id_tempid;
	data += '&id_tpid=' + id_tpid;
	data += '&id_bfid=' + id_bfid;
	data += '&page=' + page;
	data += '&pageid=' + pageid;
	data += '&id_asid=' + id_asid;
	data += '&id_caid=' + id_caid;

	$('#formAsset').ajaxSubmit({
		url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-asset-save-tmp.php',
		clearForm: false, 
		type: 'post', 
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function(result, status, jqXHR){
			$.ajax({  
				url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-templatepage-read.php' + initLogin,    
				type: 'post',          
				data: data,       
				cache: false,
				headers: {
					csrfToken: Cookies.get('csrf')
				},
				success: function (result, status, jqXHR) {
					actualizeStatus(result, status);
					
					var objResult = JSON.parse(result);
					$('.editPreview').html('');
					for(var key in objResult.preview){
						$('.editPreview').append('<div class="editPreviewInner" data-tempid="' + objResult.preview[key].temp + '" data-page="' + objResult.preview[key].page + '" data-tp="' + objResult.preview[key].tp + '" data-pageid="' + objResult.preview[key].pageid + '"><div class="editPreviewBackground">' + objResult.preview[key].src + '</div><div class="editPreviewComponents"></div></div>');
						if($('.editPreviewInner[data-page="2"]').length > 1) $('.editPreviewInner[data-pageid="' + objResult.preview[key].tp + '_2_0"]').css('display', 'none');
					}
					$('.editPreviewInner[data-page="2"]').wrapAll('<div class="editPreviewSortable"></div>');
					if($('#editOuter').hasClass('editBanner')){
						$('.editPreviewInner').each(function(){
							var p = $(this).attr('data-page');
							if(p == 1) $(this).append('<div class="editFramename">' + objText.firstframe + '</div>');
							if(p == 2) $(this).append('<div class="editFramename">' + objText.productframe + '</div>');
							if(p == 3) $(this).append('<div class="editFramename">' + objText.lastframe + '</div>');
						});
					}
				
					$('.editPreviewComponents').off('click');
					$('.editPreviewComponents').on('click', function(){
						deactivatePlaceholder(this);
					});
		
					$('.editFormConfiguration').html(objResult.configurationform);
					$('.editFormConfiguration .fieldset').addClass('fieldsetActive');

					var tpFirst = $('.editPreviewInner[data-page="1"]').attr('data-tp');
					if(tpFirst != undefined){
						$('#formAsset input[name="durationFirstframe"]').val(objResult.configuration[tpFirst].duration);
						if($('#editOuter').hasClass('editBanner') && objResult.configuration[tpFirst].showframe == 0){
							$('.editPreviewInner[data-tp="' + tpFirst + '"]').addClass('editPreviewInnerHide');
							$('.textfieldButtonHideFirst .icon').addClass('icon-icon-eye_strike_thru');
						}
					}
					
					var tpLast = $('.editPreviewInner[data-page="3"]').attr('data-tp');
					if(tpLast != undefined){
						$('#formAsset input[name="durationLastframe"]').val(objResult.configuration[tpLast].duration);
						if($('#editOuter').hasClass('editBanner') && objResult.configuration[tpLast].showframe == 0){
							$('.editPreviewInner[data-tp="' + tpLast + '"]').addClass('editPreviewInnerHide');
							$('.textfieldButtonHideLast .icon').addClass('icon-icon-eye_strike_thru');
						}
					}

					initFormConfiguration(objResult.printer);
		
					//window.setTimeout(function(){resizeEdit();}, 320);
					resizeEdit();
					loadComponents();
					
					unwaiting();
				}
			});
		}
	});
}


function initFormConfiguration(objResult){
	for(var key in objResult){
		$('.sortablePrinter').append('<div class="formRow formRowNoBorder" data-apid="' + objResult[key].apid + '" data-pid="' + objResult[key].id_pid + '"><div class="textfield textfieldPrinter"><span class="icon icon-move"></span><span class="printername"></span></div> <div class="textfield textfieldSec"><input type="text" class="textfield textfieldDuration" name="duration" value=""></div> <div class="textfield textfieldButton textfieldButtonHide"><span class="icon icon-eye" title="' + objText['hidePrinter'] + '"></span></div> <div class="textfield textfieldButton textfieldButtonDelete"><span class="icon icon-delete" title="' + objText['removePrinter'] + '"></span></div></div>');
		$('.sortablePrinter .formRow[data-apid="' + objResult[key].apid + '"] .textfieldPrinter .printername').html(objResult[key].mkt_name);
		$('.sortablePrinter .formRow[data-apid="' + objResult[key].apid + '"] .textfieldDuration').val(objResult[key].duration);
		if(objResult[key].showframe == 0){
			$('.editPreviewInner[data-pageid="' + objResult[key].id_tpid + '_2_' + objResult[key].apid + '"]').addClass('editPreviewInnerHide');
			$('.sortablePrinter .formRow[data-apid="' + objResult[key].apid + '"] .textfieldButtonHide .icon').addClass('icon-icon-eye_strike_thru');
		}
	}
	
	$('.sortablePrinter').sortable({
		axis: 'y',
		containment: '.sortablePrinter',
		cursor: 'move',
		forcePlaceholderSize: true,
		opacity: 0.5,
		revert: 50,
		tolerance: 'pointer',
		distance: 5,
		stop: function( event, ui ) {
			orderProductframes();
		}
	})
	
	$('.textfieldDurationFirst').off('input');
	$('.textfieldDurationFirst').on('input', function(){
		setDuration('first');
	});
	
	$('.textfieldDurationLast').off('input');
	$('.textfieldDurationLast').on('input', function(){
		setDuration('last');
	});
	
	$('.textfieldDuration').off('input');
	$('.textfieldDuration').on('input', function(){
		setDuration(this);
	});
	
	$('.textfieldButtonHideFirst').off('click');
	$('.textfieldButtonHideFirst').on('click', function(){
		hidePrinter('first');
	});
	
	$('.textfieldButtonHideLast').off('click');
	$('.textfieldButtonHideLast').on('click', function(){
		hidePrinter('last');
	});
	
	$('.textfieldButtonHide').off('click');
	$('.textfieldButtonHide').on('click', function(){
		hidePrinter(this);
	});
	
	$('.textfieldButtonDelete').off('click');
	$('.textfieldButtonDelete').on('click', function(){
		checkDeletePrinter(this);
	});
	
	$('.textfieldAddButton').off('click');
	$('.textfieldAddButton').on('click', function(){
		$('.textfieldAddOuterHide').removeClass('textfieldAddOuterHide');
		$(this).closest('.textfieldAddOuter').addClass('textfieldAddOuterHide');
		$('#selectAddPrinter').focus();
	});
	
	$('#selectAddPrinter').off('blur change');
	$('#selectAddPrinter').on('blur', function(){
		$('.textfieldAddOuterHide').removeClass('textfieldAddOuterHide');
		$(this).closest('.textfieldAddOuter').addClass('textfieldAddOuterHide');
		$('#selectAddPrinter option').prop('selected', false);
	});
	$('#selectAddPrinter').on('change', function(){
		addPrinter();
	});

	$('.selectPrinter').off('change');
	$('.selectPrinter').on('change', function(){
		selectPrinter(this);
	});
}


function setDuration(el){
	var asid = $('.editForm input[name="id_asid"]').val();

	if(el == 'first'){
		var apid = $(el).closest('.formRow').attr('data-apid');
		var bfid = $('.editThumbnailsGroup.editThumbnailsOuterActive').attr('data-bfid');
		var tpid = $('.editPreviewInner[data-page="1"]').attr('data-tp');
		var duration = $('.textfieldDurationFirst').val();
		
		var data = 'bfid=' + bfid;
		data += '&tpid=' + tpid;
		data += '&asid=' + asid;
		data += '&duration=' + duration;
		data += '&frame=first';
	}else if(el == 'last'){
		var apid = $(el).closest('.formRow').attr('data-apid');
		var bfid = $('.editThumbnailsGroup.editThumbnailsOuterActive').attr('data-bfid');
		var tpid = $('.editPreviewInner[data-page="3"]').attr('data-tp');
		var duration = $('.textfieldDurationLast').val();
		
		var data = 'bfid=' + bfid;
		data += '&tpid=' + tpid;
		data += '&asid=' + asid;
		data += '&duration=' + duration;
		data += '&frame=last';
	}else{
		var apid = $(el).closest('.formRow').attr('data-apid');
		var tpid = $('.editPreviewInner[data-page="2"]').attr('data-tp');
		var duration = $(el).val();
		
		var data = 'apid=' + apid;
		data += '&tpid=' + tpid;
		data += '&asid=' + asid;
		data += '&duration=' + duration;
		data += '&frame=product';
	}

	$.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-asset-printer-duration.php',    
		type: 'post',          
		data: data,       
		cache: false,
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);
		}
	});
}


function hidePrinter(el){
	var asid = $('.editForm input[name="id_asid"]').val();
	if(el == 'first'){
		var tpid = $('.editPreviewInner[data-page="1"]').attr('data-tp');
		var bfid = $('.editThumbnailsGroup.editThumbnailsOuterActive').attr('data-bfid');
		var etid = $('.editThumbnailsGroup.editThumbnailsOuterActive').attr('data-etid');
		$('.editPreviewInner[data-pageid="' + tpid + '_1"]').toggleClass('editPreviewInnerHide');
		$('.textfieldButtonHideFirst .icon').toggleClass('icon-icon-eye_strike_thru');
		var showframe = ($('.editPreviewInner[data-pageid="' + tpid + '_1"]').hasClass('editPreviewInnerHide')) ? 0 : 1;
		$('#showframeFirstframe').val(showframe);
		
		var data = 'bfid=' + bfid;
		data += '&etid=' + etid;
		data += '&tpid=' + tpid;
		data += '&asid=' + asid;
		data += '&showframe=' + showframe;
		data += '&frame=first';
	}else if(el == 'last'){
		var tpid = $('.editPreviewInner[data-page="3"]').attr('data-tp');
		var bfid = $('.editThumbnailsGroup.editThumbnailsOuterActive').attr('data-bfid');
		var etid = $('.editThumbnailsGroup.editThumbnailsOuterActive').attr('data-etid');
		$('.editPreviewInner[data-pageid="' + tpid + '_3"]').toggleClass('editPreviewInnerHide');
		$('.textfieldButtonHideLast .icon').toggleClass('icon-icon-eye_strike_thru');
		var showframe = ($('.editPreviewInner[data-pageid="' + tpid + '_3"]').hasClass('editPreviewInnerHide')) ? 0 : 1;
		$('#showframeLastframe').val(showframe);
		
		var data = 'bfid=' + bfid;
		data += '&etid=' + etid;
		data += '&tpid=' + tpid;
		data += '&asid=' + asid;
		data += '&showframe=' + showframe;
		data += '&frame=last';
	}else{
		var apid = $(el).closest('.formRow').attr('data-apid');
		var tpid = $('.editPreviewInner[data-page="2"]').attr('data-tp');
		$('.editPreviewInner[data-pageid="' + tpid + '_2_' + apid + '"]').toggleClass('editPreviewInnerHide');
		$(el).find('.icon').toggleClass('icon-icon-eye_strike_thru');
		var showframe = ($('.editPreviewInner[data-pageid="' + tpid + '_2_' + apid + '"]').hasClass('editPreviewInnerHide')) ? 0 : 1;
		
		var data = 'apid=' + apid;
		data += '&asid=' + asid;
		data += '&showframe=' + showframe;
		data += '&frame=product';
	}

	$.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-asset-printer-hide.php' + initLogin,    
		type: 'post',          
		data: data,       
		cache: false,
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);
		}
	});
}


function checkDeletePrinter(el){
	var objDialog = {};
	objDialog.el = el;
	objDialog.title = objText.removePrinter;
	objDialog.formtext = objText.checkRemoveprinter;
	objDialog.objButtons = {};
	objDialog.objButtons[objText.cancel] = function() { closeDialog(this); }            
	objDialog.objButtons[objText.Remove] = function() { deletePrinterDo(el) }
	
	openDialogConfirm(objDialog);
}


function deletePrinterDo(el){
	var apid = $(el).closest('.formRow').attr('data-apid');
	var tpid = $('.editPreviewInner[data-page="2"]').attr('data-tp');
	$('.editPreviewInner[data-pageid="' + tpid + '_2_' + apid + '"]').remove();
	$('.sortablePrinter .formRow[data-apid="' + apid + '"]').remove();

	var data = 'apid=' + apid;

	$.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-asset-printer-delete.php' + initLogin,    
		type: 'post',          
		data: data,       
		cache: false,
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);
			
			if($('.editPreviewInner[data-page="2"]').length == 1) $('.editPreviewInner[data-pageid="' + tpid + '_2_0"]').css('display', 'block');
			resizeEdit();

		}
	});

	resizeEdit();
	closeDialog();
	
}


function orderProductframes(){
	var tpid = $('.editPreviewInner[data-page="2"]').attr('data-tp');
	var aAp = [];
	var aFrames = [];

	$('.sortablePrinter .formRow[data-apid]').each(function(){
		var apid = $(this).attr('data-apid');
		aAp.push(apid);
		aFrames.push(tpid + '_2_' + apid);
	});
	
	var data = 'order=' + aAp;
	
	$.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-asset-printer-order.php' + initLogin,    
		type: 'post',          
		data: data,       
		cache: false,
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);
		}
	});
	
	for(var key in aFrames){
		var frame = $('.editPreviewInner[data-pageid="' + aFrames[key] + '"]').prop('outerHTML');
		$('.editPreviewInner[data-pageid="' + aFrames[key] + '"]').remove();
		$('.editPreviewSortable').append(frame);
		resizeEdit();
	}
	
	loadComponents();
}


function loadComponents(){
	deactivatePlaceholder();
	$('.compboxOuter').remove();

	
	$('.editPreviewInner').each(function(){
		var compPageId = $(this).attr('data-pageid');

		if(objComponents.pages['page_' + compPageId] != undefined){
			for(key in objComponents.pages['page_' + compPageId]){
				var content = objComponents.pages['page_' + compPageId][key]['content'];
				// wysiwyg
				if(objComponents.pages['page_' + compPageId][key].id_tcid == 14){
					content = '<div class="verticalalignbox">' + resizeFontsize(content) + '</div>';
				}
				// pricefield
				if(objComponents.pages['page_' + compPageId][key].id_tcid == 2){
//					var val = content.match(/[0-9]*[.,]*[0-9]*[.,]*[0-9]*/g)[0];
//					var cur = $('.editFormComponent .fieldset[data-tcid="2"] .formCurrency').html();
//					content = val + ' ' + cur;
				}

				if($(this).find('.editPreviewComponents').length == 0){
					$('.editPreviewComponents').append('<div class="compboxOuter compboxOuter_' + objComponents.pages['page_' + compPageId][key].id_caid + '_' + objComponents.pages['page_' + compPageId][key].id_tcid + '" id="' + key + '" data-tcid="' + objComponents.pages['page_' + compPageId][key].id_tcid + '" data-tpeid="' + objComponents.pages['page_' + compPageId][key].id_tpeid + '" data-apid="' + objComponents.pages['page_' + compPageId][key].id_apid + '" data-pid="' + objComponents.pages['page_' + compPageId][key].id_pid + '"><div class="content">' + content + '</div></div>');
				}else{
					$(this).find('.editPreviewComponents').append('<div class="compboxOuter compboxOuter_' + objComponents.pages['page_' + compPageId][key].id_caid + '_' + objComponents.pages['page_' + compPageId][key].id_tcid + '" id="' + key + '" data-tcid="' + objComponents.pages['page_' + compPageId][key].id_tcid + '" data-tpeid="' + objComponents.pages['page_' + compPageId][key].id_tpeid + '" data-apid="' + objComponents.pages['page_' + compPageId][key].id_apid + '" data-pid="' + objComponents.pages['page_' + compPageId][key].id_pid + '"><div class="content">' + content + '</div></div>');
				}
	
				//add css to generated div and make it resizable & draggable
				switch(objComponents.pages['page_' + compPageId][key]['fontstyle']){
					case('0'):
						$('#' + key).css('font-weight', 'normal');
						$('#' + key).css('font-style', 'normal');
						break;
					
					case('1'):
						$('#' + key).css('font-weight', 'bold');
						$('#' + key).css('font-style', 'normal');
						break;
					
					case('2'):
						$('#' + key).css('font-weight', 'normal');
						$('#' + key).css('font-style', 'italic');
						break;
					
					case('3'):
						$('#' + key).css('font-weight', 'bold');
						$('#' + key).css('font-style', 'italic');
						break;
				};
	
				if(objComponents.pages['page_' + compPageId][key].id_tcid == 10){
					$('#' + key).removeClass('contactalignleft');
					$('#' + key).removeClass('contactaligncenter');
					$('#' + key).removeClass('contactalignright');
					$('#' + key).addClass('contactalign' + objComponents.pages['page_' + compPageId][key]['alignment']);
				}
	
				if(objComponents.pages['page_' + compPageId][key].id_tcid == 11 || objComponents.pages['page_' + compPageId][key].id_tcid == 12 || objComponents.pages['page_' + compPageId][key].id_tcid == 15){
					$('#' + key).removeClass('alignleft');
					$('#' + key).removeClass('aligncenter');
					$('#' + key).removeClass('alignright');
					$('#' + key).addClass('align' + objComponents.pages['page_' + compPageId][key]['alignment']);
				}

				$('#' + key).removeClass('verticalaligntop');
				$('#' + key).removeClass('verticalalignmiddle');
				$('#' + key).removeClass('verticalalignbottom');
				$('#' + key).addClass('verticalalign' + objComponents.pages['page_' + compPageId][key]['verticalalignment']);

				if(objComponents.pages['page_' + compPageId][key].id_tcid == 11 || objComponents.pages['page_' + compPageId][key].id_tcid == 12 || objComponents.pages['page_' + compPageId][key].id_tcid == 15){
					if(objComponents.pages['page_' + compPageId][key].id_pid != undefined && objComponents.pages['page_' + compPageId][key].id_pid > 0){
						$('.editForm .selectPrinter').each(function(){
							var tpe = JSON.parse($(this).attr('data-tpe'));
							if(tpe.indexOf(objComponents.pages['page_' + compPageId][key].id_tpeid) > -1){
								$(this).find('option[value="' + objComponents.pages['page_' + compPageId][key].id_pid + '"]').prop('selected', true);
							}
						});
					}
				}
				
				$('#' + key).css({
					 'width'     : objComponents.pages['page_' + compPageId][key].width + '%',
					 'height'    : objComponents.pages['page_' + compPageId][key].height + '%',
					 'left'      : objComponents.pages['page_' + compPageId][key].left + '%',
					 'top'       : objComponents.pages['page_' + compPageId][key].top + '%',
					 'font-size' : (objComponents.pages['page_' + compPageId][key]['fontsize'] * componentFactor) + 'pt',
					 'color'     : objComponents.pages['page_' + compPageId][key]['fontcolor'],
					 'text-align': objComponents.pages['page_' + compPageId][key]['alignment'],
					 'background-color': (objComponents.pages['page_' + compPageId][key]['background_color'] != '') ? objComponents.pages['page_' + compPageId][key]['background_color'] : 'transparent'					 
				});
				
				$('#' + key).off('mouseover mouseout mousedown');
				if(objComponents.pages['page_' + compPageId][key].editable == 1){
					$('#' + key)
						.on('mouseover', function(){
							deactivateCompbox = 0;
						})
						.on('mouseout', function(){
							deactivateCompbox = 1;
						})
						.on('mousedown', function(){
							activatePlaceholder(this);
						});
						
					// Product image
					if(objComponents.pages['page_' + compPageId][key].id_tcid == 12){
						$('#' + key).on('mousedown', function(){
							loadPictures(this);
						});
					}
				}else{
					$('#' + key).addClass('comboxOuterNotEditable');
				}
			
			
				// #################################################
				// for email templates
				if($('#editOuter').hasClass('editEmail')){
					$('#' + key).css({
						 'width'     : '100%',
						 'height'    : 'auto',
						 'left'      : '0%',
						 'top'       : '0%' ,
						 'line-height': ''
						 //'color' 	 : '',
						 //'font-size' 	 : '0',
						 //'font-weight' 	 : '',
						 //'font-style' 	 : '',
						 //'background-color' 	 : '',
						 //'text-align': ''			 
					});
					
					if(objComponents.pages['page_' + compPageId][key]['elementtitle'] != ''){
						if(objComponents.pages['page_' + compPageId][key]['page'] == '2'){
							$('.editPreviewInner[data-pageid="' + compPageId + '"] .prod_' + objComponents.pages['page_' + compPageId][key]['elementtitle']).html('');
							$('#' + key).appendTo('.editPreviewInner[data-pageid="' + compPageId + '"] .prod_' + objComponents.pages['page_' + compPageId][key]['elementtitle']);
						}else{
							$('.' + objComponents.pages['page_' + compPageId][key]['elementtitle']).html('');
							$('#' + key).appendTo('.' + objComponents.pages['page_' + compPageId][key]['elementtitle']);
						}
					}
				}
			
				// color area
				if(objComponents.pages['page_' + compPageId][key].id_tcid == 18){
					$('.color_' + objComponents.pages['page_' + compPageId][key]['elementtitle']).css('background-color', (objComponents.pages['page_' + compPageId][key]['background_color'] != '') ? objComponents.pages['page_' + compPageId][key]['background_color'] : 'transparent');
					$('#' + key).remove();
				}
			
			}
		}
	});

	if($('#editOuter').hasClass('editEmail')){
		$('.editPreviewInner[data-page="2"] .compboxOuter').css({
			 'color' 	 : '',
			 'font-size' 	 : '0',
			 'font-weight' 	 : '',
			 'font-style' 	 : '',
			 'background-color' 	 : '',
			 'text-align': ''			 
		});
	}

				
	$('.editPreviewComponents').off('click');
	$('.editPreviewComponents').on('click', function(){
		deactivatePlaceholder(this);
	});

	resizeEdit();
}


function formatThumbEditable(){ 
	$('.editThumbnailsOuter').removeClass('editThumbnailsOuterEditable');
	for(key in objComponents.pages){
		for(comp in objComponents.pages[key]){
			if(objComponents.pages[key][comp].editable == 1){
				var page = objComponents.pages[key][comp].pageid;
				$('.editThumbnailsOuter[data-pageid="' + page + '"]').addClass('editThumbnailsOuterEditable');
			}
		}
	}
}; 


function clearEdit(){
	if(CKEDITOR.instances['content']) CKEDITOR.instances['content'].destroy();
	clearAssetTmp();

	$('head .emailstyle').remove('');

	$('.editThumbnailsOuter').removeClass('editThumbnailsOuterActive');
	if($('#editOuter').hasClass('editBanner')) $('.editThumbnailsGroup').removeClass('editThumbnailsOuterActive');
	$('.editThumbnails').html('');
	$('.editFormComponent').html('');
	$('.compboxOuter').remove('');
	$('#formAsset .formmessage').html('');
	$('#formAsset input[name="id_asid"]').val(0);
	objComponents = {};
}


function activatePlaceholder(el){
	var id_tcid = $(el).attr('data-tcid');
	var id_tpeid = $(el).attr('data-tpeid');

	$('.editFormConfiguration .fieldset').removeClass('fieldsetActive');
	
	$('.compboxOuter').removeClass('compboxOuterActive');
	$(el).addClass('compboxOuterActive');
	
	$('.editPreviewInner').removeClass('editPreviewInnerActive');
	$(el).closest('.editPreviewInner').addClass('editPreviewInnerActive');
	
	
	
//	$('#modul_' + obj.modulpath + ' .formLeft input[name="activeComp"]').val(id_tpeid);
//
//
//	
//	
	$('.editFormComponent .fieldset').removeClass('fieldsetActive');
//	if(objConfigComponents[id_tcid] != undefined) $('#modul_' + obj.modulpath + ' .formRight .formComponentsForm .fieldset[data-tcid="' + id_tcid + '"] .formComponentHeadline').html(objConfigComponents[id_tcid].name);
	$('.editFormComponent .fieldset[data-tcid="' + id_tcid + '"]').addClass('fieldsetActive');
//	
	fillFormComponent();
}; 

		
	
function deactivatePlaceholder(){ 
	if(deactivateCompbox == 1 && $('.compboxOuterActive').length > 0) {
		$('.compboxOuter').removeClass('compboxOuterActive');
		$('#formAsset .formmessage').html('');

//		$('#modul_' + obj.modulpath + ' .formLeft input[name="activeComp"]').val(0);
	
		$('.editFormComponent .fieldset').removeClass('fieldsetActive');
//		$('#modul_' + obj.modulpath + ' .formRight .formComponentOuter').removeClass('formComponentOuterActive');
		
		$('.editFormConfiguration .fieldset').addClass('fieldsetActive');
	}
}; 


function fillFormComponent() { 
	var compPageId = $('.editPreviewInnerActive').attr('data-pageid');
	var compId = $('.compboxOuterActive').attr('id');
	var id_tcid = $('.compboxOuterActive').attr('data-tcid');

	var val = objComponents.pages['page_' + compPageId][compId]['content'];
	switch(id_tcid){
		case('1'): // textfield
			val = val.replace(/(<br>)/g, '\r\n');
			val = val.replace(/(<br \/>)/g, '\r\n');
			break
			
		case('2'): // pricefield
//			val = val.match(/[0-9]*[.,]*[0-9]*[.,]*[0-9]*/g)[0];
			break
			
	}
	$('.editFormComponent .fieldsetActive [name="content"]').val(val);
	
	for(var key in objComponents.pages['page_' + compPageId][compId]['content_add']){
		var val_add = objComponents.pages['page_' + compPageId][compId]['content_add'][key];
		$('.editFormComponent .fieldsetActive [name="' + key + '"]').val(val_add);
	}
	
	checkMaxChars('.editFormComponent .fieldsetActive [name="content"]');
}; 


function changeFormatComponent(el){ 
	var compPageId = $('.editPreviewInnerActive').attr('data-pageid');
	var compId = $('.compboxOuterActive').attr('id');
	var id_tcid = $('.compboxOuterActive').attr('data-tcid');
	var fieldname = $(el).attr('name');
	var type = $(el).closest('.formRow').attr('data-type');
	
	switch(fieldname){
		case('content'):
			switch(type){
				case('textfield'):
					var val = $(el).val()
					objComponents.pages['page_' + compPageId][compId][fieldname + 'Org'] = val;
					$('.compboxOuterActive .content').html(val);
					objComponents.pages['page_' + compPageId][compId][fieldname] = val;
					break;

				case('pricefield'):
//					var val = $(el).val().match(/[0-9]*[.,]*[0-9]*[.,]*[0-9]*/g)[0];
//					var cur = $(el).closest('.formField').find('.formCurrency').html();
//					objComponents.pages['page_' + compPageId][compId][fieldname + 'Org'] = val;
//					$('.compboxOuterActive .content').html(val + ' ' + cur);
//					objComponents.pages['page_' + compPageId][compId][fieldname] = val;

					var val = $(el).val();
					objComponents.pages['page_' + compPageId][compId][fieldname + 'Org'] = val;
					$('.compboxOuterActive .content').html(val);
					objComponents.pages['page_' + compPageId][compId][fieldname] = val;
					break;

				case('textarea'):
					var val = $(el).val();
					objComponents.pages['page_' + compPageId][compId][fieldname + 'Org'] = val;
					val = val.replace(/(?:\r\n|\r|\n)/g, '<br>');
					$('.compboxOuterActive .content').html(val);
					objComponents.pages['page_' + compPageId][compId][fieldname] = val;
					checkMaxChars(el);
					break;

				case('wysiwyg'):
					break;
			}
			break;
			
			default:
				var val = $(el).val()
				objComponents.pages['page_' + compPageId][compId]['content_add'][fieldname] = val;
				break;
		
	}
	$('.editForm input[name="components"]').val(JSON.stringify(objComponents));
}; 


function checkMaxChars(el) { 
	var compPageId = $('.editPreviewInnerActive').attr('data-pageid');
	var compId = $('.compboxOuterActive').attr('id');
	var id_tcid = $('.compboxOuterActive').attr('data-tcid');
	var id_tpeid = $('.compboxOuterActive').attr('data-tpeid');
	var fieldname = $(el).attr('name');
	var type = $(el).closest('.formRow').attr('data-type');

	if(type == 'textarea'){
		if(objComponents.pages['page_' + compPageId][compId]['contentOrg'] == undefined) objComponents.pages['page_' + compPageId][compId]['contentOrg'] = objComponents.pages['page_' + compPageId][compId]['content'];
		var str = objComponents.pages['page_' + compPageId][compId]['contentOrg'];
		str = str.replace(/(<br>)/g, '\r\n');
		str = str.replace(/(<br \/>)/g, '\r\n');
		var maxchar = objComponents.pages['page_' + compPageId][compId]['maxchars'];
		var strShort = (maxchar > 0) ? str.substr(0, maxchar) : str;
		
		$('.editFormComponent .fieldsetActive [name="content"]').val(strShort);
		strShort = strShort.replace(/(?:\r\n|\r|\n)/g, '<br>');
		$('.compboxOuterActive .content').html(strShort);
		objComponents.pages['page_' + compPageId][compId]['content'] = strShort;

		$('.editFormComponent .fieldsetActive [name="content"]').closest('.formField').find('.descmaxchars').remove();
		if(maxchar > 0){
			var strlen = $('.editFormComponent .fieldsetActive [name="content"]').val().length;
			var rest = (maxchar * 1) - strlen;
			
			$('.editFormComponent .fieldsetActive [name="content"]').closest('.formField').append('<div class="descmaxchars"><span class="restmaxchars">' + rest + '</span> ' + objText.restMaxChars + '</div>');
		}
	}

}; 


function assetSave() { 
	waiting('body');

	if($('#editOuter').hasClass('editEmail')){
		for(var compPageId in objComponents.pages){
			if(objComponents.pages[compPageId] != undefined){
				for(var key in objComponents.pages[compPageId]){
					if(objComponents.pages[compPageId][key].id_tcid == 12){
						var imgW = Math.round($('#' + key + ' img').width());
						$('#' + key + ' img').attr('width', imgW);
						var contentNew = $('#' + key + ' .content').html();
						if(contentNew != undefined) objComponents.pages[compPageId][key]['content'] = contentNew;
					}
					if(objComponents.pages[compPageId][key].id_tcid == 11){
						var imgW = Math.round($('#' + key + ' img').width());
						$('#' + key + ' img').attr('width', imgW);
						var contentNew = $('#' + key + ' .content').html();
						if(contentNew != undefined) objComponents.pages[compPageId][key]['content'] = contentNew;
					}
				}
			}
		}
		$('.editForm input[name="components"]').val(JSON.stringify(objComponents));
	}
	
	$('#formAsset').ajaxSubmit({
		url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-asset-save.php',
		clearForm: false, 
		type: 'post', 
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function(result, status, jqXHR){
			actualizeStatus(result, status);
			
			$('#formAsset .formmessage').html(result);
			
			unwaiting();
		}
	});
	
	var id_asid = $('#formAsset input[name="id_asid"]').val();
	var title = $('#formAsset input[name="assettitle"]').val();
	$('.ovBoxOuter[data-asid="' + id_asid + '"] .ovBoxHead').html(title);
}; 


function assetExport() { 
	var objDialog = {};
	objDialog.el = '';
	objDialog.title = objText.generatingExportTitle;;
	objDialog.formtext = objText.generatingExport + '<div id="progressbar"></div>';
	objDialog.objButtons = {};
	
	openDialogAlert(objDialog);
	
	$('#formAsset').ajaxSubmit({
		url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-asset-save.php',
		clearForm: false, 
		type: 'post', 
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function(result, status, jqXHR){
			actualizeStatus(result, status);
			
			var id_asid = $('#formAsset input[name="id_asid"]').val();
			var id_promid = $('#formAsset input[name="id_promid"]').val();
			var id_campid = $('#formAsset input[name="id_campid"]').val();
			assetExportDo(id_asid, id_promid, id_campid)
		}
	});
}; 


function assetExportDirect(el) { 
	var objDialog = {};
	objDialog.el = '';
	objDialog.title = objText.generatingExportTitle;;
	objDialog.formtext = objText.generatingExport + '<div id="progressbar"></div>';
	objDialog.objButtons = {};
	
	openDialogAlert(objDialog);

	var id_asid = $(el).closest('.ovBoxOuter').attr('data-asid');
	var id_promid = $(el).closest('.ovBoxOuter').attr('data-promid');
	var id_campid = $(el).closest('.ovBoxOuter').attr('data-campid');
	assetExportDo(id_asid, id_promid, id_campid)
	
}; 


function assetExportDo(id_asid, id_promid, id_campid) { 
	var data = 'id_asid=' + id_asid;
	data += '&id_promid=' + id_promid;
	data += '&id_campid=' + id_campid;
	
	$.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-asset-export.php',    
		type: 'post',          
		data: data,       
		cache: false,
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);

			var objResult = JSON.parse(result);
			
			var thumb = '<img src="' + objSystem.directoryInstallation + 'assetimages/assets_thumbnails/' + objResult.thumbnail + '">';
			window.setTimeout(function(){$('.ovBoxOuter[data-asid="' + id_asid + '"] .ovImg').html(thumb)},1000);
			

			downloadMedia(objResult.filesys_filename, objResult.filename, objResult.folder, 'export');
			closeDialog();
		}
	});
}; 


function addPrinter() { 
	var id = $('#selectAddPrinter option:selected').val();
	var asid = $('.editForm input[name="id_asid"]').val();
	var tempid = $('.editThumbnailsOuter.editThumbnailsOuterActive').attr('data-tempid');
	var bfid = $('.editThumbnailsGroup.editThumbnailsOuterActive').attr('data-bfid');
	var etid = $('.editThumbnailsGroup.editThumbnailsOuterActive').attr('data-etid');
	var tpid = $('.editPreviewInner[data-page="2"]').attr('data-tp');
	
	if(id != 0){
		var data = 'pid=' + id;
		data += '&asid=' + asid;
		data += '&tempid=' + tempid;
		data += '&bfid=' + bfid;
		data += '&etid=' + etid;
		data += '&tpid=' + tpid;
		
		$.ajax({  
			url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-asset-printer-add.php',    
			type: 'post',          
			data: data,       
			cache: false,
			headers: {
				csrfToken: Cookies.get('csrf')
			},
			success: function (result, status, jqXHR) {
				actualizeStatus(result, status);
				
				var objResult = JSON.parse(result);
				
				// configuration form
				initFormConfiguration(objResult.printer);
				$('#selectAddPrinter').blur();
				
				// preview
				var temp = $('.editPreviewInner[data-page="2"]:first').attr('data-tempid');
				var tp = $('.editPreviewInner[data-page="2"]:first').attr('data-tp');
				
				if($('#editOuter').hasClass('editBanner')){
					var src = $('.editPreviewInner[data-page="2"]:first .editPreviewBackground').html();
					$('.editPreviewInner[data-page="2"]:last').after('<div class="editPreviewInner" data-tempid="' + temp + '" data-page="2" data-tp="' + tp + '" data-pageid="' + tp + '_2_' + objResult.apid + '"><div class="editPreviewBackground">' + src + '</div><div class="editPreviewComponents"></div><div class="editFramename">' + objText.productframe + '</div></div>');
				}

				if($('#editOuter').hasClass('editEmail')){
					var src = $('.editPreviewInner[data-page="2"]:first').html();
					$('.editPreviewInner[data-page="2"]:last').after('<div class="editPreviewInner" data-tempid="' + temp + '" data-page="2" data-tp="' + tp + '" data-pageid="' + tp + '_2_' + objResult.apid + '">' + src + '</div>');
				}
				
				$('.editPreviewInner[data-pageid="' + tp + '_2_0"]').css('display', 'none');


				for(var pageid in objResult.components){
					objComponents.pages[pageid] = objResult.components[pageid];
				}

				
				loadComponents();
				//resizeEdit();
	
			}
		});
		
	}

}; 


function selectPrinter(el) { 
	var id = $(el).find('option:selected').val();
	var asid = $('.editForm input[name="id_asid"]').val();
	var tempid = $('.editThumbnailsOuter.editThumbnailsOuterActive').attr('data-tempid');
	var bfid = $('.editThumbnailsGroup.editThumbnailsOuterActive').attr('data-bfid');
	var etid = $('.editThumbnailsGroup.editThumbnailsOuterActive').attr('data-etid');
	var tpeid = $(el).attr('data-tpe');
	
	if(id != 0){
		var data = 'pid=' + id;
		data += '&asid=' + asid;
		data += '&tempid=' + tempid;
		data += '&bfid=' + bfid;
		data += '&etid=' + etid;
		data += '&tpeid=' + tpeid;
		
		$.ajax({  
			url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-asset-printer-select.php',    
			type: 'post',          
			data: data,       
			cache: false,
			headers: {
				csrfToken: Cookies.get('csrf')
			},
			success: function (result, status, jqXHR) {
				actualizeStatus(result, status);
				
				var objResult = JSON.parse(result);
				
//				// configuration form
//				initFormConfiguration(objResult.printer);
//				$('#selectAddPrinter').blur();
//				
//				// preview
//				var temp = $('.editPreviewInner[data-page="2"]:first').attr('data-tempid');
//				var tp = $('.editPreviewInner[data-page="2"]:first').attr('data-tp');
//				var src = $('.editPreviewInner[data-page="2"]:first .editPreviewBackground').html();
//				$('.editPreviewInner[data-page="2"]:last').after('<div class="editPreviewInner" data-tempid="' + temp + '" data-page="2" data-tp="' + tp + '" data-pageid="' + tp + '_2_' + objResult.apid + '"><div class="editPreviewBackground">' + src + '</div><div class="editPreviewComponents"></div><div class="editFramename">' + objText.productframe + '</div></div>');
//				
//				$('.editPreviewInner[data-pageid="' + tp + '_2_0"]').css('display', 'none');
//				
				for(var pageid in objResult.components){
					for(var compid in objResult.components[pageid]){
						objComponents.pages[pageid][compid] = objResult.components[pageid][compid];
					}
				}
				$('.editForm input[name="components"]').val(JSON.stringify(objComponents));

				
				resizeEdit();
				loadComponents();
	
			}
		});
	}
}; 


function clearAssetTmp(){
	$.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-asset-tmp-clear.php',    
		type: 'post',          
		data: '',       
		cache: false,
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);
		}
	});
}; 


function loadPictures(el){
	var compPageId = $('.editPreviewInnerActive').attr('data-pageid');
	var compId = $('.compboxOuterActive').attr('id');
	var id_tcid = $('.compboxOuterActive').attr('data-tcid');
	var apid = $(el).attr('data-apid');
	var pid = $(el).attr('data-pid');
	var piid = $(el).find('img').attr('data-piid');

	var data = 'pid=' + pid;
	data += '&piid=' + piid;
	data += '&apid=' + apid;
	
	$.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-asset-printer-images-load.php',    
		type: 'post',          
		data: data,       
		cache: false,
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);
			
			$('.formFieldPictures').html(result);
			
			$('.selectImageOuter').off('click');
			$('.selectImageOuter').on('click', function(){
				var pic = '<div class="componentProductimage">' + $(this).html() + '</div>';
				objComponents.pages['page_' + compPageId][compId]['content'] = pic;
				$('.compboxOuterActive .content').html(pic);
				$('.editForm input[name="components"]').val(JSON.stringify(objComponents));
			});
		}
	});
}




