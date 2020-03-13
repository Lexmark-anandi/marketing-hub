var slideSpacing = 60;

function loadGallery(objGallery) {
	$('body').append('<div class="overlay" onclick="removeGallery()"></div>');
	$('body').append('<div class="dialog"></div>');
	$('body').addClass('bodyFreeze');
	
	objGallery.width = $(window).innerWidth();
	objGallery.height = $(window).innerHeight();
	
	var data = 'data=' + JSON.stringify(objGallery);
	
	$.ajax({
		url: objGallery.url,
		data: data,
		type: 'POST',
		success: function(result){
			var resGallery = JSON.parse(result); 
			$('.overlay').css('background-image', 'none'); 
			
			$('.dialog').css('margin-top', resGallery.dialogMarginTop + 'px'); 
			$('.dialog').css('margin-left', resGallery.dialogMarginLeft + 'px');

			$('.dialog').html(resGallery.content);
			initGallerySlider();
		}
	});
}


function removeGallery(){
	$('body').removeClass('bodyFreeze');
	$('.dialog').remove();
	$('.overlay').remove();
}
	

function initGallerySlider(){
	var options = { 
		$ArrowNavigatorOptions: {
			$Class: $JssorArrowNavigator$,
			$ChanceToShow: 2,
			$AutoCenter: 0
		},
//		$BulletNavigatorOptions: {
//			$Class: $JssorBulletNavigator$,
//			$ChanceToShow: 2,
//			$AutoCenter: 1
//		},
		
		$AutoPlay: false,
		$Idle: 5000,
		$SlideSpacing: slideSpacing 
	};
	
	jssor_slider1 = new $JssorSlider$('galleryOuter', options);	
//	ScaleSlider();
}
	
	
function ScaleSlider() {
//	var parentWidth = $('#projectsOuter').parents('body').width();
//	if (parentWidth) {
//		jssor_slider1.$ScaleWidth(parentWidth);
//	}else{
//		window.setTimeout(ScaleSlider, 30);
//	}
}	



