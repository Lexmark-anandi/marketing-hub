function buildGrid(modul, obj){
	$.ajax({  
		url: '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu_sys-grids-create.php',    
		type: 'post',          
		data: 'modul=' + modul,       
		cache: false,
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(aPage)
		},
		success: function(result, status, jqXHR){
			var obj = JSON.parse(result);
			aPage = obj.page;
			aGrids[modul] = obj.grid;

			if(obj == undefined) var obj = {};
			if(obj.urlOverview  == undefined) obj.urlOverview = '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu-' + aPage.moduls[modul].modulname + '-overview.php';
			if(obj.gridTable  == undefined) obj.gridTable = 'gridTable_' + aPage.moduls[modul].modulname;
			if(obj.gridPager  == undefined) obj.gridPager = 'gridPager_' + aPage.moduls[modul].modulname;
			
			aPage.moduls[modul].urlOverview = obj.urlOverview;
			aGrids[modul].gridTable = obj.gridTable;
			aGrids[modul].gridPager = obj.gridPager;
			
			var gridOptions = {
				postData: {
					mode: 'grid',
					modul: modul,
					pageConfig: JSON.stringify(aPage)
				},
				ajaxSelectOptions: {
					type: 'post',
					data: {
						mode: 'grid',
						modul: modul,
						pageConfig: JSON.stringify(aPage)
					}
				},
				url: aPage.moduls[modul].urlOverview,  
				sortable: {
					update: function(){
						saveParam(modul);
					}
				},
				loadui: 'block',
				datatype: 'json',
				mtype: 'post',
				jsonReader: {repeatitems: false},
				caption: '',
				pager: $('#' + aGrids[modul].gridPager), 
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
					saveParam(modul);
				},
				loadComplete: function(data){
					aPage = JSON.parse(data.pageconfig);
					gridSortable(modul, data);
				},
				loadError: function (jqXHR, textStatus, errorThrown) {
					cancelTool(jqXHR.responseText, textStatus);
				},
				onSortCol: function(index, iCol, sortorder){
					//gridSortable(modul, index, iCol, sortorder);
				},
				rowList: aGrids[modul].gridNumRows, 
				rowNum: aGrids[modul].userNumRows, 
				direction: aGrids[modul].htmlDir,
				colNames: aGrids[modul].colnames, 
				colModel: aGrids[modul].colmodel, 
				sortorder: aGrids[modul].sortorder,
				sortname: aGrids[modul].sortname,  
				gridComplete: function(){
					unwaiting()
//					if(idModulParent != '') window.parent.showAssigned();
				
					var ids = $('#' + aGrids[modul].gridTable).jqGrid('getDataIDs');
					for(var i=0; i<ids.length;i++){
						var id = ids[i];
						
						var act = '';
						for(var action in aGrids[modul].rowFunctions){
							act += '<span class="ui-icon ' + aGrids[modul].rowFunctions[action].icon + ' ui-grid-icon" title="' + aText[aGrids[modul].rowFunctions[action].title] +'" onclick="f_' + modul + '.' + aGrids[modul].rowFunctions[action].function + '(\'' + modul + '\', ' + id + ', this)"></span>'
						}
						$('#' + aGrids[modul].gridTable).jqGrid('setRowData', id, {actions: act, choice: '<input type="radio" name="choice" value="' + id + '">'});
					}
						
					gridResize();
					initCellFunction(modul);
					saveParam(modul);
					
					// ## fix bug in jqgrid ##
					$('#' + aGrids[modul].gridPager + '_left').css('width', '');
					
					// select active row if in edit mode
					if(!$('#form_' + aPage.moduls[modul].modulname).hasClass('hidden')){
						var id = $('.modul[data-modul="' + modul + '"] .formContent .formLeft .field_id').val();
						$('#' + aGrids[modul].gridTable + ' tr.selectedDataset').removeClass('selectedDataset'); 
						$('#' + aGrids[modul].gridTable + ' tr[id="' + id + '"]').addClass('selectedDataset');
						//$('#' + aGrids[modul].gridTable).setSelection(id);
					}
					
					$('.jqgfirstrow td:first').css('height','1px');
		
					window['f_' + modul]['cbGridComplete']();
				}
			};
			for(key in aGrids[modul].addoptions){
				gridOptions[key] = aGrids[modul].addoptions[key]
			}
			
			$('#' + aGrids[modul].gridTable).jqGrid(gridOptions);
			for(var bars in aGrids[modul].barFunctions){
				$('#t_' + aGrids[modul].gridTable).append('<div class="ui-grid-icon-toolbar" onclick="f_' + aPage.moduls[modul].modul + '.' + aGrids[modul].barFunctions[bars].function + '(\'' + aPage.moduls[modul].modul + '\', this)" title="' + aText[aGrids[modul].barFunctions[bars].title] +'"><span class="ui-icon ' + aGrids[modul].barFunctions[bars].icon + ' ui-grid-icon"></span>' + aText[aGrids[modul].barFunctions[bars].title] + '</div>');
			}
		
			gridFilter(modul);
			gridPager(modul);
		}
	});
}




function initGrid(modul) {
//	initScreen();
//
//	if(idModulParent != '') $('.gridButtonAll').appendTo('#t_' + aGrids[modul].gridTable);

}




function initCellFunction(modul) {
	$('#' + aGrids[modul].gridTable + ' .gridCellFold').each(function(){
		var cellHeightFold = $(this).innerHeight();
		var cellHeightContent = $(this)[0].scrollHeight;
		var rowId = $(this).parent().parent().attr('id');
		
		if(cellHeightContent > cellHeightFold){
			if($(this).parent().find('.gridExpand').length == 0){
            	$(this).parent().append('<span class="gridButton gridExpand ui-icon-arrowthick-2-n-s" onclick="expandRow(\'' + modul + '\', ' + rowId + ')" title="' + aText.expandRow + '"></span>');
            }
        }else{
            $(this).parent().find('.gridExpand').remove();
        }
        if($(this).html() == '')$(this).css('height', 'auto');
	});
}




function gridFilter(modul){
	$('#' + aGrids[modul].gridTable).jqGrid('filterToolbar', {
		beforeSearch: function () {
			//gridSortable(modul);
		}
	}); 
}



function gridSortable(modul, data){
//	var sortable = 1;
//	var postdata = $('#' + aGrids[modul].gridTable).jqGrid('getGridParam', 'postData');
//	
//	if(aGrids[modul].sortable_rows == 'disable') sortable = 0;
//	if(aGrids[modul].sortable_rows != postdata.sidx) sortable = 0;
//	if(data.rowsortable != 1) sortable = 0;
//	
//	if(aUserPagefunctions[aSpecsPage.idPage].indexOf('10') == -1){
//		$('.rowsortable').addClass('rowsortableRemove');
//		sortable = 0;
//	}
//		
//	if(sortable == 1){
//		$('.rowsortable').removeClass('rowsortableDeactive'); 
//		$('#' + aGrids[modul].gridTable).jqGrid('sortableRows', {
//			opacity: 0.5,
//			revert: 100,
//			tolerance: "pointer",
//			disabled: false,
//			update: function(event, ui){
//				waiting('#' + aSpecsPage.idContent);
//				var data = 'id=' + $('tr.ui-state-hover').attr('id');
//				data += '&idPrev=' + $('tr.ui-state-hover').prev().attr('id');
//				data += '&idNext=' + $('tr.ui-state-hover').next().attr('id');
//				data += '&dir=' + postdata.sord;
//				data += '&idPageParent=' + idPageParent;
//				data += '&idDataParent=' + idDataParent;
//				data += '&idModulParent=' + idModulParent;
//				data += '&primeryfieldDataParent=' + primeryfieldDataParent;
//				
//				$.ajax({  
//					url: aSpecsPage.urlRanking,    
//					data: data,       
//					type: 'post',          
//					cache: false,  
//					success: function (result) {
//            			$('#' + aGrids[modul].gridTable).trigger('reloadGrid');
//					}
//				})
//				unwaiting('#' + aSpecsPage.idContent);
//			}
//		});
//	}else{
//		$('.rowsortable').addClass('rowsortableDeactive');
//		$('#' + aGrids[modul].gridTable).jqGrid('sortableRows', {
//			disabled: true
//		});
//	}
}




function gridPager(modul){
	$('#' + aGrids[modul].gridPager + ' option[value=9999999999]').text(aText.All);
	$('#' + aGrids[modul].gridPager + ' option[value="' + aGrids[modul].userNumRows + '"]').prop('selected', true);

	$('#' + aGrids[modul].gridTable).jqGrid('navGrid', '#' + aGrids[modul].gridPager,
		{
			add:false,
			edit:false,
			del:false,
			search:false,
			refresh:true
		},
		{},
		{},
		{},
		{}
	);
	$('#' + aGrids[modul].gridTable).jqGrid('navButtonAdd', '#' + aGrids[modul].gridPager,{
		caption: aText.gridPagerCapCol,
		title: aText.gridPagerTitleCol,
		onClickButton : function (){
			$('#' + aGrids[modul].gridTable).jqGrid('columnChooser', {
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
					saveParam(modul);
				}
			});
		}
	});
}



function gridResize(){
	// ## resize all grids ##
	var contentWidth = $('#content').width();
	//if($('#' + aSpecsPage.idGridOuter).hasClass('splitScreenRight')) contentWidth = $('.splitScreenRight').width();
	var contentHeight = $('#content').height();
	
	$('.modul').each(function(){
		var modul = $(this).attr('data-modul');
		var modulWidth = Math.round((contentWidth / 100) * aPage.moduls[modul].width);
		var modulHeight = Math.round((contentHeight / 100) * aPage.moduls[modul].height);
		$(this).css('width', modulWidth + 'px');
		$(this).css('height', modulHeight + 'px');
		
		var gridWidth = modulWidth;
		var gridHeight = modulHeight;
		if(aPage.moduls[modul].modulParent == '') gridHeight -= $('.tabGridFilter').outerHeight();
		gridHeight -= $('#t_' + aGrids[modul].gridTable).outerHeight();
		gridHeight -= $('#' + aGrids[modul].gridTable).closest('#gview_gridTable_' + aPage.moduls[modul].modulname).find('.ui-jqgrid-hdiv').outerHeight();
		gridHeight -= $('#' + aGrids[modul].gridPager).outerHeight();
		if(aPage.moduls[modul].modulParent == '') gridHeight -= 2;  // ## because of border top/bottom ##
		
		$('#' + aGrids[modul].gridTable).setGridWidth(gridWidth);
		$('#' + aGrids[modul].gridTable).setGridHeight(gridHeight);
	});
}



function gridReload(modul){
	$('#' + aGrids[modul].gridTable).setGridParam({
		postData: {
			mode: 'grid',
			modul: modul,
			pageConfig: JSON.stringify(aPage)
		},
		ajaxSelectOptions: {
			type: 'post',
			data: {
				mode: 'grid',
				modul: modul,
				pageConfig: JSON.stringify(aPage)
			}
		}
	});
	$('#' + aGrids[modul].gridTable).trigger('reloadGrid');
}


function saveParam(modul) {
	var objParam = {};
	objParam.sortorder = $('#' + aGrids[modul].gridTable).getGridParam("sortorder");
	objParam.sortname = $('#' + aGrids[modul].gridTable).getGridParam("sortname");
	objParam.rowNum = $('#' + aGrids[modul].gridTable).getGridParam("rowNum");
	objParam.colModel = $('#' + aGrids[modul].gridTable).getGridParam("colModel");

	var data = 'modul=' + modul;
	data += '&param=' + JSON.stringify(objParam);
	
	$.ajax({  
		url: '/' + aSystem.directorySystem + aSystem.pathFunctionsAdmin + 'fu_sys-grids-save.php',    
		type: 'post',          
		data: data,       
		cache: false,
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(aPage)
		},
		success: function(){
		}
	});  
	
	initGrid(modul);
	initCellFunction(modul);
}


function expandRow(modul, id) {
	if($('#' + aGrids[modul].gridTable + ' tr#' + id + ' .gridCellFold').length == 0){
		$('#' + aGrids[modul].gridTable + ' tr#' + id + ' .gridCellExpand').addClass('gridCellFold');
		$('#' + aGrids[modul].gridTable + ' tr#' + id + ' .gridCellExpand').removeClass('gridCellExpand');
	}else{
		$('#' + aGrids[modul].gridTable + ' tr#' + id + ' .gridCellFold').addClass('gridCellExpand');
		$('#' + aGrids[modul].gridTable + ' tr#' + id + ' .gridCellFold').removeClass('gridCellFold');
	}
}
 

function expandRowAll(modul) {
	if($('#' + aGrids[modul].gridTable + ' .gridCellFold').length == 0){
		$('#' + aGrids[modul].gridTable + ' .gridCellExpand').addClass('gridCellFold');
		$('#' + aGrids[modul].gridTable + ' .gridCellExpand').removeClass('gridCellExpand');
	}else{
		$('#' + aGrids[modul].gridTable + ' .gridCellFold').addClass('gridCellExpand');
		$('#' + aGrids[modul].gridTable + ' .gridCellFold').removeClass('gridCellFold');
	}
}

