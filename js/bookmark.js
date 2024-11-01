function wpBookmarksClose() {
	if(window.wpBookmarksDebug) console.log('wpBookmarksClose');
	if($!='undefined') {
		if($('#wpBookmarks_box')!='undefined') $('#wpBookmarks_box').slideUp('fast', function(){
			if($('#wpBookmarks_iframe')!='undefined') $('#wpBookmarks_iframe').hide();
		});
	}
}

function wpBookmarksInit() {
	if(window.wpBookmarksDebug) {
        console.log('wpBookmarksInit');
    }
	if(window.wpBookmarksInitialized!=true) {
		if(window.wpBookmarksDebug) {
            console.log('doInit');
        }
		// load stylesheets
		$('head').append('<link rel="stylesheet" href="'+window.wpBookmarksPluginPath+'/css/bookmark.css?'+Math.random()+'" type="text/css" />');
		
		// build box-html
		var box = $('<div id="wpBookmarks_box" style="display:none;"><a id="wpBookmarks_titleBar" href="#" onclick="wpBookmarksClose();return false;">'+window.wpBookmarksCloseButtonLabel+'</a><iframe id="wpBookmarks_iframe" src="javascript:" style="display:none;" onreadystatechange="onWpBookmarkFrameLoaded();" onload="onWpBookmarkFrameLoaded();"></iframe></div>');
		$(document.body).append(box);
		
		// set init-status
		window.wpBookmarksInitialized=true;
	}
}

function getSelectionText() {
    var text = "";
    if (window.getSelection) {
        text = window.getSelection().toString();
    } else if (document.selection && document.selection.type != 'Control') {
        text = document.selection.createRange().text;
    }
    if(window.wpBookmarksDebug) console.log('getSelectionText: '+text);
    return text;
}

function showWpBookmarks_box() {
	if(window.wpBookmarksDebug) console.log('showWpBookmarks_box');
	
	// build url
	url = window.wpBookmarksAdminPath+'post-new.php?post_type=bookmarks&action=browserbookmark';
	url+='&post_title='+encodeURIComponent(jQuery(document).attr('title'));
	url+='&post_content='+encodeURIComponent(getSelectionText());
	url+='&post_url='+encodeURIComponent(jQuery(location).attr('href'));
	url = url.replace(/https:\/\/sslsites.de\/kb.conlabz.de/, 'http://kb.conlabz.de');
	
	// frameset-check
	if($("body").is("body")!=true) {
		if(confirm(unescape("Diese Seite benutzt Framesets. Soll die Seite in einem neuen Fenster ge%F6ffnet werden%3F \nFalls ein Popup-Blocker verwendet wird%2C muss das %D6ffnen des Fensters best%E4tigt werden."))) {
			var width = 300;
			var height = 250;
			var left = (screen.width/2)-(width/2);
			var top = (screen.height/2)-(height/2);
			window.open(url, '_blank', 'width='+width+',height='+height+',left='+left+',top='+top);
			window.focus();
		}
	}
	else {
		// close previously opened box(so it slides up)
		wpBookmarksClose();
		
		// build html-box
		wpBookmarksInit();
		
		// show box 
		$('#wpBookmarks_box').slideDown('fast');

		// set source
		$('#wpBookmarks_iframe').attr('src', url);
	}
};

function onBookmarkClicked() {
	if(window.wpBookmarksDebug) console.log('onBookmarkClicked');
	// load jquery
	if(window.wpBookmarksInitialized!=true) {
		// callback
		window.DOMLoaded = function() {
			showWpBookmarks_box();
		}
		loadScript('https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
	}
	else {
		showWpBookmarks_box();
	}
}

function onWpBookmarkFrameLoaded() {
	// show iframe
	$('#wpBookmarks_iframe').show();
}
