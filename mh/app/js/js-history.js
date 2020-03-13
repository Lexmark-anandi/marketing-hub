function setHistory(obj){
	if(obj == undefined) var obj = {};
	if(obj.setHis  == undefined) obj.setHis = true; 
	if(obj.typeHis  == undefined) obj.typeHis = 'push';

	if(obj.setHis == true){
		var stateObj = { 
			pageId: $('.navActive').attr('data-caid'), 
			page: $('.navActive').text()
		};
	
		var title = document.title + ' - ' + stateObj.page;
		var url = stateObj.page;
		(obj.typeHis == 'push') ? history.pushState(stateObj, title, url) : history.replaceState(stateObj, title, url);
	}
}

$(window).on('popstate', function (e) {
    var state = e.originalEvent.state;
    if (state !== null) {
		if(Cookies.getJSON('activesettings').id_page != state.pageId){ 
			$('.navActive').removeClass('navActive');
			$('#navigationOuter ul > li[data-caid="' + state.pageId + '"]').addClass('navActive');

			var objHis = {setHis:false};
			loadPage(objHis);
		} 
    }else{
	}
});

