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
}

function closeBreadcrumb(){
	$('#breadcrumb').removeClass('breadcrumbBlock');
}

function loadBreadcrumb(){
	var data = '';
	
	$.ajax({  
		url: '/admin/functions/fu_sys-breadcrumb.php',    
		type: 'post',          
		data: data,       
		cache: false ,
		headers: {
			csrfToken: Cookies.get('csrf'),
			page: JSON.stringify(aPage)
		},
		success: function(result){
			$('#breadcrumbInner').html(result);
		}
	});  
}

