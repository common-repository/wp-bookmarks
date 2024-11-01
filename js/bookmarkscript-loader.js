if(window.wpBookmarksInitialized!=true) window.wpBookmarksInitialized=false;
window.wpBookmarksDebug=false;
function loadScript(filename) {
	if(window.wpBookmarksDebug) console.log('loadScript: '+filename);
	var script = document.createElement('script');
	script.setAttribute('type','text/javascript');
	script.setAttribute('onreadystatechange', 'DOMLoaded()');
	script.setAttribute('onload', 'DOMLoaded()');
	script.setAttribute('src', filename);
	if (typeof script!='undefined')
	document.getElementsByTagName('head')[0].appendChild(script);
};

function DOMLoaded() {
	onBookmarkClicked();
}