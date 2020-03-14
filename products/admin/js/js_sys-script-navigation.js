function initNavigation() {
	$('#navigation li:has(ul)').addClass('iconSubmenue');
	$('#navigation li:has(ul)').hover(
		function(){
			if(mode == 'desktop') $(this).find('ul').slideDown(200);
		}, 
		function(){
			if(mode == 'desktop'){
				$(this).find('ul').hide();
				$(this).blur();
			}
		}
	);
	$('#navigation li div').hover(
		function(){
			$(this).addClass('navHover');
		}, 
		function(){
			$(this).removeClass('navHover');
		}
	);

	$('#navigation li.menueIntern > div').off('click');
	$('#navigation li.menueIntern > div[data-pageid]').on('click', function(){
		if(mode == 'desktop'){
			loadPage(this);
		}else{
			if($(this).closest('li').find('ul').length == 0){
				loadPage(this);
			}else{
				openSubmenue(this)
			}
		}
	});
	
	// ## only mobile ##
	$('#menuebutton').off('click');
	$('#menuebutton').on('click', function(){
		openMenue();
	});
	
	// ## only mobile ##
	$('#navigation li.menueCloseMain > div').off('click');
	$('#navigation li.menueCloseMain > div').on('click', function(){
		closeMenue();
	});
	$('#navigation li.menueClose > div').off('click');
	$('#navigation li.menueClose > div').on('click', function(){
		closeSubmenue();
	});
};


function openMenue(){
	// ## function only for mobile ##
	$('#navigation').addClass('openMenue');
	$('#overlay').addClass('overlayVisible');
	
	$('#overlay').on('click', function(){
		closeMenue();
	});
	
	// ## close menue ##
	$("#overlay, #navigation").swipe( {
		swipeLeft:function(event, direction, distance, duration, fingerCount, fingerData) {
			closeMenue();
		}
	});
	
	// ## close submenue ##
	$("#navigation li ul").swipe( {
		swipeRight:function(event, direction, distance, duration, fingerCount, fingerData) {
			closeSubmenue();
		}
	});
}

function closeMenue(){
	// ## function only for mobile ##
	$('#navigation').removeClass('openMenue');
	$('#overlay').removeClass('overlayVisible');
	$("#navigation").swipe("destroy");
	$('#overlay').off('click');
	$("#overlay").swipe("destroy");
}

function openSubmenue(el){
	// ## function only for mobile ##
	$(el).closest('li').find('ul').addClass('openSubmenue');
}

function closeSubmenue(){
	// ## function only for mobile ##
	$('.navHover').removeClass('navHover');
	$('.openSubmenue').removeClass('openSubmenue');
}

