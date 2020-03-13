(function () {
    window['f_##modul_name##']['dashboardwidgetassetspercountry_Ready'] = function (obj, data, el) { 
		var labels = [];
		for(var key in data){
			labels.push(data[key].label);
		}
		
		var datasets = [];
		for(var category in data[0].values){
			var objTmp = {};
			objTmp.label = category;
			objTmp.data = [];
			for(var key in data){
				objTmp.data.push(data[key].values[objTmp.label])
			}
			objTmp.backgroundColor = data[0].colors[objTmp.label]; 
			
			datasets.push(objTmp);
		}
		
		var widgetcanvas = $(el).find('canvas').attr('id');
		var ctx = document.getElementById(widgetcanvas).getContext('2d');
		
		var config = {
			  type: 'bar',
			  data: {
				  labels: labels,
				  datasets: datasets
			   },
			  options: {
					plugins: {
						datalabels: {
							color: 'white',
							display: function(context) {
								return false
//								if(el.indexOf('li') > -1){
//									return false
//								}else{
//									return context.dataset.data[context.dataIndex] > 3;
//								}
							},
							font: {
								weight: 'bold',
								size: 11
							},
							formatter: Math.round
						}
//						stacked100: { 
//							enable: true, 
//							replaceTooltipLabel: true 
//						}
					},
					maintainAspectRatio: false,
					layout: {
						padding: {
							left: 0,
							right: 0,
							top: 35,
							bottom: 0
						}
					},
					title:{
						display: false,
						text: ''
					},
					legend: {
						position: 'bottom',
						labels: {
							boxWidth: 15,
							fontSize: 11
						}
					},
					scales: {
						scaleLabel: {
							fontSize: 11
						},
						xAxes: [{
							stacked: true,
							ticks: {
								minRotation: 90,
								fontSize: 11
							}
						}],
						yAxes: [{
							stacked: true
						}]
					}
				}
		
		};
		
		var myChart = new Chart(ctx, config);
	}; 
	

    window['f_##modul_name##']['dashboardwidgetassetspercountry_ExportExcel'] = function (obj, el) { 
		waiting('body');
		var data = '';
		
		$.ajax({  
			url: objSystem.directoryInstallation + objSystem.pathFunctionsAdmin + 'fu-dashboard-widget-assets-per-country-export.php',    
			type: 'post',          
			data: data,       
			cache: false,
			headers: {
				csrfToken: Cookies.get('csrf')
			},
			success: function (result, status, jqXHR) {
				actualizeStatus(result, status);
	
				var objResult = JSON.parse(result);
				downloadMedia(objResult.folder + '/' + objResult.filesys_filename, objResult.filename, objResult.path + objResult.folder, 'export');
				unwaiting();
			}
		});
	}; 


})();

