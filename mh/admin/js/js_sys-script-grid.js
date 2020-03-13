function buildGrid(obj){
	waiting('body'); 
	
	if(obj == undefined){
		$('.modul').each(function(){
			var modulpath = $(this).attr('data-modulpath');
			var objM = splitModulpath(modulpath);
			var objModul = (objM.id_mod_parent == 0) ? objUser.pages2moduls[objM.id_page].moduls['i_' + objM.id_mod] : objUser.childmoduls[objM.id_mod_parent]['i_' + objM.id_mod];
			
			if(objModul.specifications[11] == 9) buildGrid(objM);
		});
	}else{
		// ## build object for single modul for short access ##
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		
		// ## add to modul object ##
		if(objModul.urlOverview == undefined) objModul.urlOverview = 'fu-' + obj.modul_name + '-overview.php';
		if(objModul.gridTable == undefined) objModul.gridTable = 'gridTable_' + obj.modulpath;
		if(objModul.gridPager == undefined) objModul.gridPager = 'gridPager_' + obj.modulpath;

		// ## add modul to user object (perant or child)
		(obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] = objModul : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod] = objModul;
		
		// ## create colnames and colmodel for grid
		var objColnames = [];
		for(var key in objModul.colnames){
			objColnames.push(objModul.colnames[key].colname);
		}
		var objColmodel = [];
		for(var key in objModul.colmodel){
			objColmodel.push(objModul.colmodel[key]);
		}
		
		var gridOptions = {
			postData: {
				mode: 'grid',
				pageConfig: JSON.stringify(obj) // weg? !!!!!!!!!!!!
			},
			ajaxSelectOptions: {
				type: 'post',
				headers: {
					csrfToken: Cookies.get('csrf'),
					page: JSON.stringify(obj),
					settings: JSON.stringify(objModul.activeSettings)
				},
				data: {
					mode: 'grid',
					pageConfig: JSON.stringify(obj) // weg? !!!!!!!!!!!!
				}
			},
			loadBeforeSend: function(jqXHR) {
				jqXHR.setRequestHeader('csrfToken', Cookies.get('csrf'));
				jqXHR.setRequestHeader('page', JSON.stringify(obj));
				jqXHR.setRequestHeader('settings', JSON.stringify(objModul.activeSettings));
			},
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-default-overview.php',  
			sortable: {
				update: function(){
					saveParam(obj);
				}
			},
			beforeRequest: function(){
				waiting('#modul_' + obj.modulpath);
			},
			loadui: 'disable',
			datatype: 'json',
			mtype: 'post',
			jsonReader: {repeatitems: false},
			caption: '',
			pager: false, 
			cellLayout: 8,
			forceFit: true,
			shrinkToFit: false,
			scrollOffset: 20,
			autowidth: true,
			hoverrows: true,
			height: 1500,
			viewsortcols: [true,'vertical',true],
			viewrecords: true, 
			toolbar : [true,'top'],
			altRows: true,
			altclass: 'altRow',
			scrollrows : true,
			gridview: true,
			resizeStop: function(){ 
				saveParam(obj);
			},
			loadComplete: function(data){
				gridSortable(obj, data);
				var objPageconfig = JSON.parse(data.pageconfig);
				if(objPageconfig.cb_gridLoadComplete){
					var func = objPageconfig.cb_gridLoadComplete;
					delete objPageconfig.cb_gridLoadComplete;
					window[func](objPageconfig);
				}
			},
			loadError: function (jqXHR, textStatus, errorThrown) {

				cancelTool(jqXHR.responseText, textStatus);
			},
			onSortCol: function(index, iCol, sortorder){
				//gridSortable(obj, index, iCol, sortorder);
			},
			rowList: objSystem.aGridNumRows, 
			rowNum: objModul.activeSettings.gridNumRows, 
			direction: Cookies.getJSON('activesettings').htmlDir,
			colNames: objColnames, 
			colModel: objColmodel, 
			sortorder: objModul.modul_sortorder,
			sortname: objModul.modul_sortname,  
			gridComplete: function(){
				if(obj.id_data != undefined) $('#' + objModul.gridTable + ' tr[id="' + obj.id_data + '"]').addClass('selectedDataset');
				
	//					if(idModulParent != '') window.parent.showAssigned();
				
				// ## set functions for dataset ##
				var ids = $('#' + objModul.gridTable).jqGrid('getDataIDs');
				for(var i=0; i<ids.length;i++){
					var id = ids[i];
					for(var key in objModul.rowFunctions){
						$('#' + objModul.gridTable + ' tr[id="' + id + '"] td[aria-describedby="' + objModul.gridTable + '_actions"]').append('<div class="modulIcon modulIconGridRow" title="' + objText[objModul.functions['i_' + objModul.rowFunctions[key]].title] + '"><i class="fa fa-fw ' + objModul.functions['i_' + objModul.rowFunctions[key]].icon + '"></i></div>');
						
						$('#' + objModul.gridTable + ' tr[id="' + id + '"] .modulIcon:last').off('click');
						$('#' + objModul.gridTable + ' tr[id="' + id + '"] .modulIcon:last').on('click', {key:key, id:id, obj:obj}, function(event){
							var data = event.data;
							data.obj.id_data = data.id;
							window['f_' + obj.modul_name][objModul.functions['i_' + objModul.rowFunctions[data.key]].function](data.obj, this);
						});
					}
				}


				unwaiting();



//////	$('#' + objModul.gridTable + ' tr[id]').swipe( {
////////		hold:function(event, direction, distance, duration, fingerCount, fingerData) {
////////			//e.preventDefault();
//////////			alert('AA');
////////        	$('tr.jqgrow').contextMenu();
////////		},
//////		longTap:function(event, direction, distance, duration, fingerCount, fingerData) {
//////			//e.preventDefault();
////////			alert('AA');
//////        	//$('tr.jqgrow').contextMenu();
//////			
//////			var $this = $(this);
//////        // store a callback on the trigger
//////        $this.data('runCallbackThingiex', createSomeMenu);
//////        var _offset = $this.offset(),
//////            position = {
//////                x: _offset.left + 10, 
//////                y: _offset.top + 10
//////            }
//////        // open the contextMenu asynchronously
//////        setTimeout(function(){ $this.contextMenu(position); }, 10);
//////		}
//////	});



////////	$(function() {
//////
////////        $.contextMenu({
////////            selector: 'tr.jqgrow', 
////////			trigger: 'none',
////////            callback: function(key, options) {
////////                var m = "clicked: " + key;
////////                window.console && console.log(m) || alert(m); 
////////            },
////////            items: {
////////                "edit": {name: "Edit", icon: "fa-trash-o"},
////////                "cut": {name: "Cut", icon: "cut"},
////////               copy: {name: "Copy", icon: "copy"},
////////                "paste": {name: "Paste", icon: "paste"},
////////                "delete": {name: "Delete", icon: "delete"},
////////                "sep1": "---------",
////////                "quit": {name: "Quit", icon: function(){
////////                    return 'context-menu-icon context-menu-icon-quit';
////////                }}
////////            }
////////        });
////////
//////////        $('.context-menu-one').on('click', function(e){
//////////            console.log('clicked', this);
//////////        })    
////////    });










					
				gridPager(obj);				
				gridResize(obj);
				initCellFunction(obj);
				saveParam(obj);
				
				// ## fix bug in jqgrid ##
	//			$('#' + objModul.gridPager + '_left').css('width', '');
				
	//			// select active row if in edit mode
	//			if(!$('#form_' + aPage.moduls[modul].modulname).hasClass('hidden')){
	//				var id = $('.modul[data-modul="' + modul + '"] .formContent .formLeft .field_id').val();
	//				$('#' + aGrids[modul].gridTable + ' tr.selectedDataset').removeClass('selectedDataset'); 
	//				$('#' + aGrids[modul].gridTable + ' tr[id="' + id + '"]').addClass('selectedDataset');
	//				//$('#' + aGrids[modul].gridTable).setSelection(id);
	//			}
				
				$('.jqgfirstrow td:first').css('height','1px'); 

				if(window['f_' + objModul.modul_name] != undefined) window['f_' + objModul.modul_name]['cbGridComplete'](obj);
			}
		};
		
		for(var key in objModul.addoptions){
			gridOptions[key] = objModul.addoptions[key]
		}

		$('#' + objModul.gridTable).jqGrid(gridOptions);
		
		$('#t_' + objModul.gridTable).addClass('gridBarFunctions');
		for(var key in objModul.barFunctions){
			$('#t_' + objModul.gridTable).append('<div class="ui-grid-icon-toolbar" title="' + objText[objModul.functions['i_' + objModul.barFunctions[key]].title] +'"><div class="modulIcon"><i class="fa ' + objModul.functions['i_' + objModul.barFunctions[key]].icon + '"></i></div>' + objText[objModul.functions['i_' + objModul.barFunctions[key]].title] + '</div>');
			
			$('#t_' + objModul.gridTable + ' .ui-grid-icon-toolbar:last').off('click');
			$('#t_' + objModul.gridTable + ' .ui-grid-icon-toolbar:last').on('click', {key:key, obj:obj}, function(event){
				var data = event.data;
				window['f_' + obj.modul_name][objModul.functions['i_' + objModul.barFunctions[data.key]].function](data.obj, this);
			});
		}
		
		// ## set gridExpandAll button
		if(obj.id_mod_parent != 0){
			$('#modul_' + obj.modulpath + ' .tabModulFilterButtonsRight').appendTo('#t_' + objModul.gridTable);
		}
	
		gridFilter(obj);
		gridPager(obj);
	}
}

////////$(function(){
////////    $.contextMenu({
////////        selector: '.context-menu-one', 
////////        build: function($trigger, e) {
////////            // this callback is executed every time the menu is to be shown
////////            // its results are destroyed every time the menu is hidden
////////            // e is the original contextmenu event, containing e.pageX and e.pageY (amongst other data)
////////            return {
////////                callback: function(key, options) {
////////                    var m = "clicked: " + key;
////////                    window.console && console.log(m) || alert(m); 
////////                },
////////                items: {
////////                    "edit": {name: "Edit", icon: "edit"},
////////                    "cut": {name: "Cut", icon: "cut"},
////////                    "copy": {name: "Copy", icon: "copy"},
////////                    "paste": {name: "Paste", icon: "paste"},
////////                    "delete": {name: "Delete", icon: "delete"},
////////                    "sep1": "---------",
////////                    "quit": {name: "Quit", icon: function($element, key, item){ return 'context-menu-icon context-menu-icon-quit'; }}
////////                }
////////            };
////////        }
////////    });
////////});
//////$.contextMenu({
//////        selector: 'tr.jqgrow',
//////        trigger: 'none',
//////        build: function($trigger, e) {
//////            e.preventDefault();
//////
//////            // pull a callback from the trigger
//////            return $trigger.data('runCallbackThingiex')();
//////        }
//////    });
//////	
//////	
//////function createSomeMenu() {
//////        return {
//////            callback: function(key, options) {
//////                var m = "clicked: " + key;
//////                window.console && console.log(m) || alert(m);
//////            },
//////            items: {
//////                "edit": {name: "Edit", icon: "edit"},
//////                "cut": {name: "Cut", icon: "cut"},
//////                "copy": {name: "Copy", icon: "copy"}
//////            }
//////        };
//////    }
	
	
//function initGrid(modul) {
////	initScreen();
////
////	if(idModulParent != '') $('.gridButtonAll').appendTo('#t_' + aGrids[modul].gridTable);
//
//}




function initCellFunction(obj) {
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

	$('#' + objModul.gridTable + ' .gridCellFoldAutoHeight').removeClass('gridCellFoldAutoHeight');
	$('#' + objModul.gridTable + ' .gridCellFold').each(function(){
		if($(this).find('.gridHeightComplete').length == 0){
			var cellHeightFold = $(this).innerHeight();
			var cellHeightContent = $(this)[0].scrollHeight;
			var rowId = $(this).parent().parent().attr('id');
	
			if(cellHeightContent > cellHeightFold){
				if($(this).parent().find('.gridExpand').length == 0){
					$(this).parent().append('<div class="modulIcon modulIconBox gridExpand " title="' + objText.expandRow + '"><i class="fa fa-chevron-down"></i></div>');
					$(this).parent().find('.gridExpand').on('click', function(){
						expandRow(obj, rowId);
					});
					$(this).removeClass('gridCellFoldAutoHeight');
				}
			}else{
				$(this).parent().find('.gridExpand').remove();
				$(this).addClass('gridCellFoldAutoHeight');
			}
			if($(this).html() == '') $(this).addClass('gridCellFoldAutoHeight');
		}else{
			$(this).addClass('gridCellFoldAutoHeight');
		}
	});
}




function gridFilter(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

	$('#' + objModul.gridTable).jqGrid('filterToolbar', {
		beforeSearch: function () {
			//gridSortable(obj);
		}
	}); 
}



function gridSortable(obj, data){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	var aFunctions = [];
	for(var key in objModul.functions){
		aFunctions.push(objModul.functions[key].id_f);
	}

	var postdata = $('#' + objModul.gridTable).jqGrid('getGridParam', 'postData');
	
	var sortable = 0;
	($.inArray('10', aFunctions)) ? sortable = 1 : $('#' + objModul.gridTable + ' .rowsortable').addClass('rowsortableRemove');
	if(objModul.modul_sortable_rows == 'disable') sortable = 0;
	if(objModul.modul_sortable_rows != postdata.sidx) sortable = 0;
	if(data.rowsortable != 1) sortable = 0;
	if(sortable == 1){
		$('#' + objModul.gridTable + ' .fa-sort').removeClass('rowsortableDeactive'); 
		$('#' + objModul.gridTable).jqGrid('sortableRows', {
			opacity: 0.5,
			revert: 100,
			tolerance: 'pointer',
			disabled: false,
			// ## handle: '.fa-sort', ##
			update: function(event, ui){
				waiting('#modul_' + obj.modulpath);
				
				var data = 'id=' + $('#' + objModul.gridTable + ' tr.ui-state-hover').attr('id');
				data += '&idPrev=' + $('#' + objModul.gridTable + ' tr.ui-state-hover').prev().attr('id');
				data += '&idNext=' + $('#' + objModul.gridTable + ' tr.ui-state-hover').next().attr('id');
				data += '&dir=' + postdata.sord;
				
				$.ajax({  
					url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-default-ranking.php',    
					data: data,       
					type: 'post',          
					cache: false,  
					headers: {
						csrfToken: Cookies.get('csrf'),
						page: JSON.stringify(obj),
						settings: JSON.stringify(objModul.activeSettings)
					},
					success: function (result, status, jqXHR) {
						actualizeStatus(result, status);
		
            			$('#' + objModul.gridTable).trigger('reloadGrid');
						unwaiting();
					}
				})
			}
		});
	}else{
		$('#' + objModul.gridTable + ' .fa-sort').addClass('rowsortableDeactive');
		$('#' + objModul.gridTable).jqGrid('sortableRows', {
			disabled: true
		});
	}
}


function gridPager(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

	var rowNum = $('#' + objModul.gridTable).getGridParam('rowNum');
	var page = $('#' + objModul.gridTable).getGridParam('page');
	var total = $('#' + objModul.gridTable).getGridParam('lastpage');
	var recordsAll = $('#' + objModul.gridTable).getGridParam('records');
	var recordsStart = rowNum * page - rowNum + 1;
	if(recordsStart < 0) recordsStart = 0;
	var recordsEnd = rowNum * page;
	if(recordsEnd > recordsAll) recordsEnd = recordsAll;

	$('#gridPager_' + obj.modulpath + ' .pagerRefresh').off('click');
	$('#gridPager_' + obj.modulpath + ' .pagerRefresh').on('click', {obj:obj}, function(event){
		var data = event.data;
		reloadGrid(data.obj);
	});
	$('#gridPager_' + obj.modulpath + ' .pagerSettings').off('click');
	$('#gridPager_' + obj.modulpath + ' .pagerSettings').on('click', {obj:obj}, function(event){
		var data = event.data;
		gridColumnChooser(data.obj, this);
	});
	
	$('#gridPager_' + obj.modulpath + ' .pagerFirstPage').off('click');
	$('#gridPager_' + obj.modulpath + ' .pagerFirstPage').addClass('modulIconDisabled');
	if(page > 1){
		$('#gridPager_' + obj.modulpath + ' .pagerFirstPage').removeClass('modulIconDisabled');
		$('#gridPager_' + obj.modulpath + ' .pagerFirstPage').on('click', {obj:obj}, function(event){
			var data = event.data;
			gridFirstPage(data.obj, this);
		});
	}
	$('#gridPager_' + obj.modulpath + ' .pagerPrevPage').off('click');
	$('#gridPager_' + obj.modulpath + ' .pagerPrevPage').addClass('modulIconDisabled');
	if(page > 1){
		$('#gridPager_' + obj.modulpath + ' .pagerPrevPage').removeClass('modulIconDisabled');
		$('#gridPager_' + obj.modulpath + ' .pagerPrevPage').on('click', {obj:obj}, function(event){
			var data = event.data;
			gridPrevPage(data.obj, this);
		});
	}
	$('#gridPager_' + obj.modulpath + ' .pagerNextPage').off('click');
	$('#gridPager_' + obj.modulpath + ' .pagerNextPage').addClass('modulIconDisabled');
	if(page < total){
		$('#gridPager_' + obj.modulpath + ' .pagerNextPage').removeClass('modulIconDisabled');
		$('#gridPager_' + obj.modulpath + ' .pagerNextPage').on('click', {obj:obj}, function(event){
			var data = event.data;
			gridNextPage(data.obj, this);
		});
	}
	$('#gridPager_' + obj.modulpath + ' .pagerLastPage').off('click');
	$('#gridPager_' + obj.modulpath + ' .pagerLastPage').addClass('modulIconDisabled');
	if(page < total){
		$('#gridPager_' + obj.modulpath + ' .pagerLastPage').removeClass('modulIconDisabled');
		$('#gridPager_' + obj.modulpath + ' .pagerLastPage').on('click', {obj:obj}, function(event){
			var data = event.data;
			gridLastPage(data.obj, this);
		});
	}
	$('#gridPager_' + obj.modulpath + ' .pagerActPage').off('keypress');
	$('#gridPager_' + obj.modulpath + ' .pagerActPage').on('keypress', {obj:obj}, function(event){
		var data = event.data;
		if(event.which == 13) {
			var pageNew = $(this).val();
			gridPage(data.obj, pageNew, this);
		}
	});
	$('#gridPager_' + obj.modulpath + ' .pagerSelectRows').off('change');
	$('#gridPager_' + obj.modulpath + ' .pagerSelectRows').on('change', {obj:obj}, function(event){
		var data = event.data;
		gridRowNum(data.obj, this);
	});
	

	$('#gridPager_' + obj.modulpath + ' .pagerRecords').html(recordsStart + ' - ' + recordsEnd + ' / ' + formatNumber(recordsAll));
	if(recordsAll == 0) $('#gridPager_' + obj.modulpath + ' .pagerRecords').html(objText.noRecords);
	$('#gridPager_' + obj.modulpath + ' .pagerTotalPages').html(total);
	$('#gridPager_' + obj.modulpath + ' .pagerActPage').val(page);
	$('#gridPager_' + obj.modulpath + ' .pagerSelectRows option').prop('selected', false);
	$('#gridPager_' + obj.modulpath + ' .pagerSelectRows option[value="' + rowNum + '"]').prop('selected', true);
	$('#gridPager_' + obj.modulpath + ' .pagerSelectRows option[value="9999999999"]').text(objText.All);
}



function gridResize(obj){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	
	if($('#modul_' + obj.modulpath + ' .ui-jqgrid-bdiv').length > 0){
		if(mode == 'mobile'){
			$('#' + objModul.gridTable).jqGrid('hideCol',['actions']);
		}else{
			$('#' + objModul.gridTable).jqGrid('showCol',['actions']);
		}
	}
	
	var gridWidth = $('#modul_' + obj.modulpath).width();
	var gridHeight = $('#modul_' + obj.modulpath).innerHeight();
	
	if(obj.id_mod_parent == 0 && mode == 'desktop') gridHeight -= $('.tabModulFilter').outerHeight();
	gridHeight -= $('#t_' + objModul.gridTable).outerHeight();
	gridHeight -= $('#' + objModul.gridTable).closest('#gview_' + objModul.gridTable).find('.ui-jqgrid-hdiv').outerHeight();
	gridHeight -= $('#' + objModul.gridPager).outerHeight();
	if(obj.id_mod_parent == 0) gridHeight -= 2;  // ## because of border top/bottom ##

	if(!isNaN(gridWidth)) $('#' + objModul.gridTable).setGridWidth(gridWidth);
	if(!isNaN(gridHeight)) $('#' + objModul.gridTable).setGridHeight((gridHeight));
}


function reloadGrid(obj){
	waiting('#modul_' + obj.modulpath);
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

	$('#' + objModul.gridTable).setGridParam({
		postData: {
			mode: 'grid',
			pageConfig: JSON.stringify(obj) // weg? !!!!!!!!!!!!
		},
		ajaxSelectOptions: {
			type: 'post',
			headers: {
				csrfToken: Cookies.get('csrf'),
				page: JSON.stringify(obj),
				settings: JSON.stringify(objModul.activeSettings)
			},
			data: {
				mode: 'grid',
				pageConfig: JSON.stringify(obj) // weg? !!!!!!!!!!!!
			}
		},
		loadBeforeSend: function(jqXHR) {
			jqXHR.setRequestHeader('csrfToken', Cookies.get('csrf'));
			jqXHR.setRequestHeader('page', JSON.stringify(obj));
			jqXHR.setRequestHeader('settings', JSON.stringify(objModul.activeSettings));
		}
	});
	$('#' + objModul.gridTable).trigger('reloadGrid');
}


function saveParam(obj) {
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

	var objParam = {};
	objParam.sortorder = $('#' + objModul.gridTable).getGridParam("sortorder");
	objParam.sortname = $('#' + objModul.gridTable).getGridParam("sortname");
	objParam.rowNum = $('#' + objModul.gridTable).getGridParam("rowNum");
	objParam.colModel = $('#' + objModul.gridTable).getGridParam("colModel");

	// ## set cookie ## 
	var objChange = {};
	objChange['gridNumRows'] = objParam.rowNum;
	changeCookie('activesettings', objChange); 
	
	// ## set object settings ## 
	objModul.activeSettings.gridNumRows = objParam.rowNum;
	objModul.modul_sortname = objParam.sortname;
	objModul.modul_sortorder = objParam.sortorder;
	
	var rank = 0;
	for(var key in objParam.colModel){
		rank += 10;
		for(var key2 in objModul.colmodel){
			if(objParam.colModel[key]['index'] == objModul.colmodel[key2]['index']){
				objModul.colmodel[key2].width = objParam.colModel[key]['width'];
				objModul.colmodel[key2].hidden = objParam.colModel[key]['hidden'];
				objModul.colmodel[key2].rank = rank;
				objModul.colnames[key2].rank = rank;
				break;
			}
		}
	}
	var keysSorted = Object.keys(objModul.colmodel).sort(function(a,b){return objModul.colmodel[a].rank-objModul.colmodel[b].rank});
	var colnamesTmp = {};
	var colmodelTmp = {};
	for(var key in keysSorted){
		colnamesTmp[keysSorted[key]] = objModul.colnames[keysSorted[key]];
		colmodelTmp[keysSorted[key]] = objModul.colmodel[keysSorted[key]];
	}
	objModul.colnames = colnamesTmp;
	objModul.colmodel = colmodelTmp;
	
	(obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] = objModul : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod] = objModul;
	
	// ## synchronize num rows ##
	// ## parent moduls ##
	for(var id_page in objUser.pages2moduls){
		for(var keyModul in objUser.pages2moduls[id_page].moduls){
			if((objSystem.synchronizeGridNumRow == 1 && objUser.pages2moduls[id_page].moduls[keyModul].specifications[12] == 9) || keyModul == 'i_' + obj.id_mod){
				objUser.pages2moduls[id_page].moduls[keyModul].activeSettings.gridNumRows = objModul.activeSettings.gridNumRows;
			}
		}
	}
	// ## child moduls ##
	for(var id_mod in objUser.childmoduls){
		for(var keyModul in objUser.childmoduls[id_mod]){
			if((objSystem.synchronizeGridNumRow == 1 && objUser.childmoduls[id_mod][keyModul].specifications[12] == 9) || keyModul == 'i_' + obj.id_mod){
				objUser.childmoduls[id_mod][keyModul].activeSettings.gridNumRows = objModul.activeSettings.gridNumRows;
			}
		}
	}
	

	var data = 'param=' + JSON.stringify(objParam);
	
	$.ajax({  
		url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-grids-save.php',    
		type: 'post',          
		data: data,       
		cache: false,
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(obj),
			settings: JSON.stringify(objModul.activeSettings)
		},
		success: function (result, status, jqXHR) {
			actualizeStatus(result, status);
		}
	});  
	
//	initGrid(modul);
	initCellFunction(obj);
}


function expandRow(obj, id) {
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

	if($('#' + objModul.gridTable + ' tr#' + id + ' .gridCellFold').length == 0){
		$('#grid_' + obj.modulpath + ' .gridExpandAll i').addClass('fa-chevron-down');
		$('#grid_' + obj.modulpath + ' .gridExpandAll i').removeClass('fa-chevron-up');
		$('#' + objModul.gridTable + ' tr#' + id + ' .gridExpand i').addClass('fa-chevron-down');
		$('#' + objModul.gridTable + ' tr#' + id + ' .gridExpand i').removeClass('fa-chevron-up');

		$('#' + objModul.gridTable + ' tr#' + id + ' .gridCellExpand').addClass('gridCellFold');
		$('#' + objModul.gridTable + ' tr#' + id + ' .gridCellExpand').removeClass('gridCellExpand');
	}else{
		$('#grid_' + obj.modulpath + ' .gridExpandAll i').removeClass('fa-chevron-down');
		$('#grid_' + obj.modulpath + ' .gridExpandAll i').addClass('fa-chevron-up');
		$('#' + objModul.gridTable + ' tr#' + id + ' .gridExpand i').removeClass('fa-chevron-down');
		$('#' + objModul.gridTable + ' tr#' + id + ' .gridExpand i').addClass('fa-chevron-up');

		$('#' + objModul.gridTable + ' tr#' + id + ' .gridCellFold').addClass('gridCellExpand');
		$('#' + objModul.gridTable + ' tr#' + id + ' .gridCellFold').removeClass('gridCellFold');
	}
}
 

function expandRowAll(obj, el) {
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	
	if($('#grid_' + obj.modulpath + ' .gridExpandAll i').hasClass('fa-chevron-up')){
		$('#grid_' + obj.modulpath + ' .gridExpandAll i').addClass('fa-chevron-down');
		$('#grid_' + obj.modulpath + ' .gridExpandAll i').removeClass('fa-chevron-up');
		$('#' + objModul.gridTable + ' tr .gridExpand i').addClass('fa-chevron-down');
		$('#' + objModul.gridTable + ' tr .gridExpand i').removeClass('fa-chevron-up');

		$('#' + objModul.gridTable + ' .gridCellExpand').addClass('gridCellFold');
		$('#' + objModul.gridTable + ' .gridCellExpand').removeClass('gridCellExpand');
	}else{
		$('#grid_' + obj.modulpath + ' .gridExpandAll i').removeClass('fa-chevron-down');
		$('#grid_' + obj.modulpath + ' .gridExpandAll i').addClass('fa-chevron-up');
		$('#' + objModul.gridTable + ' tr .gridExpand i').removeClass('fa-chevron-down');
		$('#' + objModul.gridTable + ' tr .gridExpand i').addClass('fa-chevron-up');

		$('#' + objModul.gridTable + ' .gridCellFold').addClass('gridCellExpand');
		$('#' + objModul.gridTable + ' .gridCellFold').removeClass('gridCellFold');
	}
}


function gridMenueFunctionsMobile(obj, el){
	if($('#grid_' + obj.modulpath + ' .gridBarFunctions').hasClass('gridBarFunctionsMobile')){
		$('#grid_' + obj.modulpath + ' .gridBarFunctions').removeClass('gridBarFunctionsMobile');
	}else{
		$('#grid_' + obj.modulpath + ' .gridBarFunctions').addClass('gridBarFunctionsMobile');
	}
}


function gridMenueFilterMobile(obj, el){
	if($('#grid_' + obj.modulpath + ' .tabModulFilterInner').hasClass('tabModulFilterMobile')){
		$('#grid_' + obj.modulpath + ' .tabModulFilterInner').removeClass('tabModulFilterMobile');
	}else{
		$('#grid_' + obj.modulpath + ' .tabModulFilterInner').addClass('tabModulFilterMobile');
	}
}

function gridFirstPage(obj, el) {
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	
	var pageNew = 1;
	$('#' + objModul.gridTable).trigger('reloadGrid', { page: pageNew});
}

function gridPrevPage(obj, el) {
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	
	var pageAct = $('#' + objModul.gridTable).getGridParam('page');
	var pageNew = pageAct - 1;
	
	$('#' + objModul.gridTable).setGridParam({
		postData: {
			mode: 'grid',
			pageConfig: JSON.stringify(obj) // weg? !!!!!!!!!!!!
		},
		ajaxSelectOptions: {
			type: 'post',
			headers: {
				csrfToken: Cookies.get('csrf'),
				page: JSON.stringify(obj),
				settings: JSON.stringify(objModul.activeSettings)
			},
			data: {
				mode: 'grid',
				pageConfig: JSON.stringify(obj) // weg? !!!!!!!!!!!!
			}
		},
		loadBeforeSend: function(jqXHR) {
			jqXHR.setRequestHeader('csrfToken', Cookies.get('csrf'));
			jqXHR.setRequestHeader('page', JSON.stringify(obj));
			jqXHR.setRequestHeader('settings', JSON.stringify(objModul.activeSettings));
		}
	}).trigger('reloadGrid', { page: pageNew});
	
	delete obj.cb_gridLoadComplete;
}

function gridNextPage(obj, el) {
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	
	var pageAct = $('#' + objModul.gridTable).getGridParam('page');
	var pageNew = pageAct + 1;

	$('#' + objModul.gridTable).setGridParam({
		postData: {
			mode: 'grid',
			pageConfig: JSON.stringify(obj) // weg? !!!!!!!!!!!!
		},
		ajaxSelectOptions: {
			type: 'post',
			headers: {
				csrfToken: Cookies.get('csrf'),
				page: JSON.stringify(obj),
				settings: JSON.stringify(objModul.activeSettings)
			},
			data: {
				mode: 'grid',
				pageConfig: JSON.stringify(obj) // weg? !!!!!!!!!!!!
			}
		},
		loadBeforeSend: function(jqXHR) {
			jqXHR.setRequestHeader('csrfToken', Cookies.get('csrf'));
			jqXHR.setRequestHeader('page', JSON.stringify(obj));
			jqXHR.setRequestHeader('settings', JSON.stringify(objModul.activeSettings));
		}
	}).trigger('reloadGrid', {page:pageNew});
	
	delete obj.cb_gridLoadComplete;
}

function gridLastPage(obj, el) {
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	
	var pageNew = $('#' + objModul.gridTable).getGridParam('lastpage');
	$('#' + objModul.gridTable).trigger('reloadGrid', { page: pageNew});
}

function gridPage(obj, page, el) {
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	
	$('#' + objModul.gridTable).trigger('reloadGrid', { page: page});
}

function gridRowNum(obj, el) {
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	
	var rowNumNew = $('#gridPager_' + obj.modulpath + ' .pagerSelectRows option:selected').val();
	
	$('#' + objModul.gridTable).setGridParam({
		postData: {
			mode: 'grid',
			pageConfig: JSON.stringify(obj) // weg? !!!!!!!!!!!!
		},
		ajaxSelectOptions: {
			type: 'post',
			headers: {
				csrfToken: Cookies.get('csrf'),
				page: JSON.stringify(obj),
				settings: JSON.stringify(objModul.activeSettings)
			},
			data: {
				mode: 'grid',
				pageConfig: JSON.stringify(obj) // weg? !!!!!!!!!!!!
			}
		},
		loadBeforeSend: function(jqXHR) {
			jqXHR.setRequestHeader('csrfToken', Cookies.get('csrf'));
			jqXHR.setRequestHeader('page', JSON.stringify(obj));
			jqXHR.setRequestHeader('settings', JSON.stringify(objModul.activeSettings));
		},
		rowNum: rowNumNew
	});
	
	$('#' + objModul.gridTable).trigger('reloadGrid', { page: 1});
}

function gridColumnChooser(obj, el){
	var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
	
	$('#' + objModul.gridTable).jqGrid('columnChooser', {
		width: 600,
		msel_opts: {
			sortable:true
		},
		done : function (perm) {
			if (perm)  {
				this.jqGrid('remapColumns', perm, true);
				var gwdth = this.jqGrid('getGridParam','width');
				this.jqGrid('setGridWidth',gwdth);
			}
			saveParam(obj);
		}
	});
}


