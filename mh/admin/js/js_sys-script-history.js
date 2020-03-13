function setHistory(obj){
	if(obj == undefined) var obj = {};
	if(obj.setHis  == undefined) obj.setHis = true;
	if(obj.typeHis  == undefined) obj.typeHis = 'push';

	if(obj.setHis == true){
		var stateObj = { 
			pageId: Cookies.getJSON('activesettings').id_page, 
			page: objUser.pages2moduls[Cookies.getJSON('activesettings').id_page].pagename
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
			var objHis = {setHis:false};
			loadPage($('#navigation div[data-pageid="'+state.pageId+'"]'), objHis);
		} 
    }else{
	}
});

