var gridster;
var gridDashboard;

(function () {
	// #####################################################
	// Ready
	// #####################################################
    window['f_##modul_name##']['ready'] = function (obj) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		
		var data = ''; 
		
		$.ajax({  
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-default-overview.php', 
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

				$('#modul_' + obj.modulpath).html(result);
				window['f_##modul_name##']['showDashboard'](obj);

				$('#dashboardPager .fa-sliders').off('click');
				$('#dashboardPager .fa-sliders').on('click', function(){
					window['f_##modul_name##']['selectWidgets'](obj);
				});
			}
		});
	};


    window['f_##modul_name##']['readyReload'] = function (obj) { 
		$('#modul_' + obj.modulpath).html('');
		window['f_##modul_name##']['ready'](obj);
	};



    window['f_##modul_name##']['cbDialogFormOpen'] = function (obj) { 
		initFieldsAssign(obj)
	};




		
	
    window['f_##modul_name##']['showDashboard'] = function (obj) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		var outerW = $('#modulOuter').innerWidth() - 20;
		var outerH = $('#modulOuter').innerHeight() - $('#dashboardPager').outerHeight(true) - 50;
		
		$('.dashboardOuter').width(outerW + 20);
		$('.dashboardOuter').height(outerH + 50);
		
		var data = '&outerW=' + outerW;
		data += '&outerH=' + outerH;

		$.ajax({   
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-dashboard-read.php', 
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
				
				var objResult = JSON.parse(result);
				gridDashboard = objResult.grid;
				gridDashboard = Gridster.sort_by_row_and_col_asc(gridDashboard);
				
				gridster = $('.dashboardOuter ul').gridster({
					widget_base_dimensions: [objResult.baseW, objResult.baseH],
					widget_margins: [objResult.spaceW, objResult.spaceH],
					auto_init: true,
					resize: {
						enabled: true,
						max_size: [objResult.maxCol, objResult.maxRow],
						stop: function() {
							window['f_##modul_name##']['saveDashboard'](obj);
						}
					},
					serialize_params: function($w, wgd) { 
						return { col: wgd.col, row: wgd.row, size_x: wgd.size_x, size_y: wgd.size_y, title:$w.attr('data-title'), id_dashid:$w.attr('data-id_dashid'), filename:$w.attr('data-filename') } 
					},
					draggable: {
						limit: true,
						handle: '.dashboardItemHeadline',
						stop: function() {
							window['f_##modul_name##']['saveDashboard'](obj);
						}
					}
				}).data('gridster');

				gridster.remove_all_widgets(); 
				$.each(gridDashboard, function() {
					var widgetCode = window['f_##modul_name##']['widgetGetCode'](obj, this);
					
					gridster.add_widget(widgetCode, this.size_x, this.size_y, this.col, this.row);
					
					if(this.functions != ''){ 
						$('.dashboardOuter li[data-id_dashid="' + this.id_dashid + '"]').append('<div class="dashboardItemFunctions"></div>');
						
						for(var dash2df in this.functions){
							$('.dashboardOuter li[data-id_dashid="' + this.id_dashid + '"] .dashboardItemFunctions').append('<div class="modulIcon" title="' + this.functions[dash2df].title + '"><i class="fa ' + this.functions[dash2df].icon + '"></i></div>');
							
							$('.dashboardOuter li[data-id_dashid="' + this.id_dashid + '"] .dashboardItemFunctions .modulIcon:last').on('click', {obj:obj, objThis:this}, function(event){
								var data = event.data;
								var iconfunction = data.objThis.filename.replace(/[-_]/g, '');
								iconfunction += '_' + data.objThis.functions[dash2df]['function'];
								if(window['f_##modul_name##'][iconfunction] && typeof(window['f_##modul_name##'][iconfunction]) === 'function') window['f_##modul_name##'][iconfunction](data.obj, this);
							});
						}
					}
					
					window['f_##modul_name##']['loadWidget'](obj, this.id_dashid, this.filename);
				});			
				
				$('.dashboardWidgetClose').off('click');
				$('.dashboardWidgetClose').on('click', function(){
					window['f_##modul_name##']['closeWidget'](obj, this);
				});
				
				$('.dashboardWidgetMaximize').off('click');
				$('.dashboardWidgetMaximize').on('click', function(){
					window['f_##modul_name##']['maximizeWidget'](obj, this);
				});
			}
		});
	}; 
	
	
		
	
    window['f_##modul_name##']['widgetGetCode'] = function (obj, el) {
		var code = '<li data-title="' + el.title + '" data-id_dashid="' + el.id_dashid + '" data-filename="' + el.filename + '"><div class="dashboardItemOuter"><div class="dashboardItemHeadline">' + objText[el.title] + '<span class="dashboardWidgetIcon dashboardWidgetClose" title="' + objText.Close + '"><i class="fa fa-window-close-o"></i></span><span class="dashboardWidgetIcon dashboardWidgetMaximize" title="' + objText.Maximize + '"><i class="fa fa-window-maximize"></i></span></div><div class="dashboardItemContent"></div></div></li>';
		return code;
	}; 
	
	
		
	
    window['f_##modul_name##']['saveDashboard'] = function (obj) {
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		var data = 'grid=' + JSON.stringify(gridster.serialize());
		
		$.ajax({   
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-dashboard-update.php', 
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
	}; 
		
	
    window['f_##modul_name##']['selectWidgets'] = function (obj) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

		var objDialog = {};
		objDialog.urlForm = objSystem.directoryInstallation + objSystem.pathFormsAdmin + 'fo-dashboard-select-widgets.php'
		objDialog.el = '';
		objDialog.title = objText.selectDashboardWindows;
		
		objDialog.objButtons = {};
		objDialog.objButtons[objText.Cancel] = function() { closeDialog(objDialog, this); }            
		objDialog.objButtons[objText.ConfigurationSave] = function() { window['f_##modul_name##']['sendSelectWidgets'](obj, this) }
		
		openDialogForm(obj, objDialog);
	}; 
		
	
    window['f_##modul_name##']['sendSelectWidgets'] = function (obj) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

		$('.assignForm').ajaxSubmit({
			clearForm: false, 
			type: 'post', 
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin +  'fu-dashboard-select-widgets.php',
			headers: {
				csrfToken: Cookies.get('csrf'),
				page: JSON.stringify(obj),
				settings: JSON.stringify(objModul.activeSettings)
			},
			success: function(result){
				var aResult = JSON.parse(result);
				
				for(key in aResult.removeWidgets){
					gridster.remove_widget($('li[data-id_dashid="'+aResult.removeWidgets[key]+'"]'));
				}


				$.each(aResult.addWidgetsGrid, function() {
					var widgetCode = window['f_##modul_name##']['widgetGetCode'](obj, this);
					
					gridster.add_widget(widgetCode, this.size_x, this.size_y, this.col, this.row);
					
					if(this.functions != ''){ 
						$('.dashboardOuter li[data-id_dashid="' + this.id_dashid + '"]').append('<div class="dashboardItemFunctions"></div>');
						
						for(var dash2df in this.functions){
							$('.dashboardOuter li[data-id_dashid="' + this.id_dashid + '"] .dashboardItemFunctions').append('<div class="modulIcon" title="' + this.functions[dash2df].title + '"><i class="fa ' + this.functions[dash2df].icon + '"></i></div>');
							
							$('.dashboardOuter li[data-id_dashid="' + this.id_dashid + '"] .dashboardItemFunctions .modulIcon:last').on('click', {obj:obj, objThis:this}, function(event){
								var data = event.data;
								var iconfunction = data.objThis.filename.replace(/[-_]/g, '');
								iconfunction += '_' + data.objThis.functions[dash2df]['function'];
								if(window['f_##modul_name##'][iconfunction] && typeof(window['f_##modul_name##'][iconfunction]) === 'function') window['f_##modul_name##'][iconfunction](data.obj, this);
							});
						}
					}
					
					window['f_##modul_name##']['loadWidget'](obj, this.id_dashid, this.filename);
				});			
				
				$('.dashboardWidgetClose').off('click');
				$('.dashboardWidgetClose').on('click', function(){
					window['f_##modul_name##']['closeWidget'](obj, this);
				});
				
				$('.dashboardWidgetMaximize').off('click');
				$('.dashboardWidgetMaximize').on('click', function(){
					window['f_##modul_name##']['maximizeWidget'](obj, this);
				});

				window['f_##modul_name##']['saveDashboard'](obj);
				closeDialog();
			}
		});
	}; 
		
	
    window['f_##modul_name##']['loadWidget'] = function (obj, dashid, filename, target) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
		
		if(target == undefined) target = 'li[data-id_dashid="' + dashid + '"]';
		
		var data = 'id_dashid=' + dashid;
		data += '&filename=fu-' + filename + '.php';
		data += '&target=' + target;

		$.ajax({   
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-' + filename + '.php', 
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
				
				
				var objResult = (result != '') ? JSON.parse(result) : {};
				
				$(target + ' .dashboardItemContent').html(objResult.con);
				window['f_##modul_name##']['resizeWidgetContent'](obj, dashid, target);


				var data = 'filespecial=js-' + filename + '.js';
				$.ajax({  
					url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu_sys-script-build.php',    
					type: 'post',          
					data: data,       
					cache: false,
					headers: {
						csrfToken: Cookies.get('csrf'),
						page: JSON.stringify(obj)
					},
					dataType: 'script',
					success: function (result, status, jqXHR) {
						actualizeStatus(result, status);
	
						var readyfunction = filename.replace(/[-_]/g, '');
						readyfunction += '_Ready';
						if(window['f_##modul_name##'][readyfunction] && typeof(window['f_##modul_name##'][readyfunction]) === 'function') window['f_##modul_name##'][readyfunction](obj, objResult.data, target);
					}
				});
			}
		});
	}; 
		
	
    window['f_##modul_name##']['resizeWidgetContent'] = function (obj, dashid, target) { 
		var t = $(target + ' .dashboardItemHeadline').outerHeight(true);
		$(target + ' .dashboardItemContent').css('top', (t + 1) + 'px');

		var b = $(target + ' .dashboardItemFunctions').outerHeight(true);
		$(target + ' .dashboardItemContent').css('bottom', (b + 1) + 'px');
	}; 
		
	
    window['f_##modul_name##']['maximizeWidget'] = function (obj, el) { 
		var dashid = $(el).closest('li').attr('data-id_dashid');
		var filename = $(el).closest('li').attr('data-filename');
		var title = $(el).closest('li').find('.dashboardItemHeadline').text(); 

		$('body').append('<div class="dialogOuter"><div class="dialogPadding" style="height:100%"><div class="dashboardItemContent" style="height:100%"></div></div></div>'); 

		var position = {
			my: "center", at: "center", of: window
		}

		$('.dialogOuter:last').dialog({ 
			modal: true,
			resizable: false,
			closeOnEscape: true,
			buttons: {}, 
			title: title,
			position: position,
			width: screenWidth - ((screenWidth / 100) * 15),
			height: screenHeight - ((screenHeight / 100) * 15),
			show: null,
			hide: null,
			dialogClass: 'dialogZindex',
			//maxHeight: screenHeight - 50,
			open: function(event, ui) {
				window['f_##modul_name##']['loadWidget'](obj, dashid, filename, '.dialogOuter');
			},
			close: function(event, ui) {
				$(this).remove();
			}
		});



	}; 
		
	
    window['f_##modul_name##']['closeWidget'] = function (obj, el) { 
		var dashid = $(el).closest('li').attr('data-id_dashid');

		gridster.remove_widget($('li[data-id_dashid="' + dashid + '"]'));
		window['f_##modul_name##']['saveDashboard'](obj);
	}; 
		
	
    window['f_##modul_name##']['directLink'] = function (obj) { 
		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];

//			var dashid = $(el).closest('li').attr('data-id_dashid');
//			var dashurl = $(el).closest('li').attr('data-filename');
//			var cbopt = dashid+','+dashurl;
//			
//			openDirectLink(id, url, func, el, 'loadWidget', cbopt);
	}; 










////###################################################
//
//    window['f_##modul_name##']['switchListTransRegCount'] = function (obj, el) { 
//		$(el).parents('.dashboardListitemRow').find('.listTransReqCount').toggleClass('listTransReqCountFull');
//		if($(el).hasClass('fa-caret-down')){
//			$(el).removeClass('fa-caret-down');
//			$(el).addClass('fa-caret-up');
//		}else{
//			$(el).removeClass('fa-caret-up');
//			$(el).addClass('fa-caret-down');
//		}
//	}; 
//
//
//
//    window['f_##modul_name##']['requestTranslationReminder'] = function (obj, el) { 
//		var objDialog = {};
//		objDialog.el = el;
//		objDialog.title = objText.requestTranslation;
//		objDialog.formtext = objText.requestTranslationCheck;
//	
//		objDialog.objButtons = {};
//		objDialog.objButtons[objText.Cancel] = function() {closeDialog(obj, this);}            
//		objDialog.objButtons[objText.requestTranslation] = function() { window['f_##modul_name##']['requestTranslationReminderDo'](obj, el);}
//		
//		openDialogMessage(obj, objDialog);
//	};
//	
//
//    window['f_##modul_name##']['requestTranslationReminderDo'] = function (obj, el) { 
//		waiting('.ui-dialog');
//		var objModul = (obj.id_mod_parent == 0) ? objUser.pages2moduls[obj.id_page].moduls['i_' + obj.id_mod] : objUser.childmoduls[obj.id_mod_parent]['i_' + obj.id_mod];
//		var temp = $(el).closest('.dashboardListitemRow').attr('data-temp');
//		var prom = $(el).closest('.dashboardListitemRow').attr('data-prom');
//		
//		var url = objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-templates-transrequest.php';
//		var data = 'reminder=' + temp;
//		
//		if(prom != 0){
//			url = objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-promotions-transrequest.php';
//			data = 'reminder=' + prom;
//		}
//		
//		$.ajax({  
//			url: url, 
//			data: data,    
//			type: 'post',          
//			cache: false,  
//			headers: {
//				csrfToken: Cookies.get('csrf'),
//				page: JSON.stringify(obj),
//				settings: JSON.stringify(objModul.activeSettings)
//			},
//			success: function (result, status, jqXHR) {
//				actualizeStatus(result, status);
//
//				closeDialog(obj)
//				unwaiting('.ui-dialog');
//			}
//		});
//	};
//	
//	
//	
////#######################################	
//
//    window['f_##modul_name##']['showTemplatesPerCountry'] = function (obj, data, el) { 
//		var labels = [];
//		for(var key in data){
//			labels.push(data[key].label);
//		}
//		
//		var datasets = [];
//		for(var category in data[0].values){
//			var objTmp = {};
//			objTmp.label = category;
//			objTmp.data = [];
//			for(var key in data){
//				objTmp.data.push(data[key].values[objTmp.label])
//			}
//			objTmp.backgroundColor = data[0].colors[objTmp.label];
//			
//			datasets.push(objTmp);
//		}
//		
//		var ctx = canvas.getContext('2d');
//		
//		var config = {
//			  type: 'bar',
//			  data: {
//				  labels: labels,
//				  datasets: datasets
//			   },
//			  options: {
//					layout: {
//						padding: {
//							left: 0,
//							right: 0,
//							top: 35,
//							bottom: 0
//						}
//					},
//					title:{
//						display: false,
//						text: ''
//					},
//					legend: {
//						position: 'bottom',
//						labels: {
//							boxWidth: 15,
//							fontSize: 11
//						}
//					},
//					scales: {
//						scaleLabel: {
//							fontSize: 11
//						},
//						xAxes: [{
//							stacked: true,
//							ticks: {
//								minRotation: 90,
//								fontSize: 11
//							}
//						}],
//						yAxes: [{
//							stacked: true
//						}]
//					}},
//		
//		};
//		
//		var myChart = new Chart(ctx, config);
//		
//		
//		
////		var jsonfile = {
////		   "jsonarray": [{
////			  "label": "Deutschland",
////			  "banner": 5,
////			  "spec sheets": 15,
////			  "brochure": 4,
////			  "color1": "#cc0000",
////			  "color2": "rgba(54, 162, 235, 0.2)",
////			  "color3": "rgba(58, 0, 100, 6)"
////		   },{
////			  "label": "Frankreich",
////			  "banner": 10,
////			  "spec sheets": 7,
////			  "brochure": 10,
////			  "color1": "rgba(255, 206, 86, 1)",
////			  "color2": "rgba(54, 162, 235, 0.2)",
////			  "color3": "rgba(58, 0, 100, 6)"
////		   },{
////			  "label": "Italien",
////			  "banner": 2,
////			  "spec sheets": 0,
////			  "brochure": 6,
////			  "color1": "rgba(255, 206, 86, 1)",
////			  "color2": "rgba(54, 162, 235, 0.2)",
////			  "color3": "rgba(58, 0, 100, 6)"
////		   },{
////			  "label": "Spanien",
////			  "banner": 3,
////			  "spec sheets": 8,
////			  "brochure": 3,
////			  "color1": "rgba(255, 206, 86, 1)",
////			  "color2": "rgba(54, 162, 235, 0.2)",
////			  "color3": "rgba(58, 0, 100, 6)"
////			}]
////		};
////		
////		//var dataPack1 = [21000, 22000, 26000, 35000, 55000, 55000, 56000, 59000, 60000, 61000, 60100, 62000];
////		//var dataPack2 = [1000, 1200, 1300, 1400, 1060, 2030, 2070, 4000, 4100, 4020, 4030, 4050];
////		
////		
////		var labels = jsonfile.jsonarray.map(function(e) {
////		   return e.label;
////		});
////		
////		var banner = jsonfile.jsonarray.map(function(e) {
////		   return e.banner;
////		});
////		
////		var specsheets = jsonfile.jsonarray.map(function(e) {
////		   return e['spec sheets'];
////		});
////		
////		var brochure = jsonfile.jsonarray.map(function(e) {
////		   return e.brochure;
////		});
////		
////		var color1 = jsonfile.jsonarray.map(function(e) {
////		   return e.color1;
////		});
////		
////		var color2 = jsonfile.jsonarray.map(function(e) {
////		   return e.color2;
////		});
////		
////		var color3 = jsonfile.jsonarray.map(function(e) {
////		   return e.color3;
////		});
////		
////		//alert (data);
////		
////		var ctx = canvas.getContext('2d');
////		var config = {
////			  type: 'bar',
////			  data: {
////				  labels: labels,
////				  datasets: [{
////					 label: 'Banner',
////					 data: banner,
////					 backgroundColor: color1
////				  
////				  }, {
////					 label: 'Spec sheets',
////					 data: specsheets,
////					 backgroundColor: color2
////				  },{
////					 label: 'Brochure',
////					 data: brochure,
////					 backgroundColor: color3
////				  }]
////			   },
////			  options: {
////					title:{
////						display:true,
////						text:"Balken über Balken...Options geht"
////					},
////					scales: {
////						xAxes: [{
////							stacked: true,
////						}],
////						yAxes: [{
////							stacked: true
////						}]
////					}},
////		
////		};
////		
////		var myChart = new Chart(ctx, config);
//	}; 
//


})();

