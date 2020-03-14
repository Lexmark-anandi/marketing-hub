function addHistory(obj){
    var stateObj = { 
    	pageId: obj.pageId,
        page: obj.page
    };
    var title = document.title;
	var url = obj.page;
    history.pushState(stateObj, title, url);
}

function replaceHistory(obj){
    var stateObj = { 
    	pageId: obj.pageId,
        page: obj.page
    };
    var title = document.title;
	var url = obj.page;
    history.replaceState(stateObj, title, url);
}


$(window).on('popstate', function (e) {
    var state = e.originalEvent.state;
    if (state !== null) {
		if(aPage.pageId != state.pageId){ 
			var obj = {addHis:false};
			loadPage($('#navigation div[data-pageid="'+state.pageId+'"]'), obj);
		} 
    }
});

