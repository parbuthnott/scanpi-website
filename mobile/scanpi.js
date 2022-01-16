function iPhoneToURL(urlforward) {
	// if pic2shop not installed yet, go to App Store
	// Query - can we make this go straight to the appstore? (rather than itunes)
	setTimeout(function() {
		window.location = "http://itunes.com/apps/pic2shop";
		}, 25);

	// launch pic2shop and tell it to open Google Products with scan results
	// n.b. need to change window.location so that the itunes application is launched from browser
	// alert("here:"+urlforward+":iphone");
	window.location = urlforward;
	// window.location="pic2shop://scan?callback=http%3A%2F%2Fwww.glasto.org%2Fglasto%2Fscanpi%2Fbar2brainz2tracks.php";
	//  n.b. if application exists, need to cancel the browser redirect
	return true;
}

function androidToURL(urlforward) {
	// if pic2shop not installed yet, go to Google Play
	setTimeout(function() {
		window.location="http://play.google.com/store/apps/details?id=com.visionsmarts.pic2shop";
		}, 25);
	// alert("here:"+urlforward+":android");
	window.location = urlforward;
	// window.location="pic2shop://scan?callback=http%3A%2F%2Fwww.glasto.org%2Fglasto%2Fscanpi%2Fbar2brainz2tracks.php";
	//  n.b. if application exists, need to cancel the browser redirect
	return true;
}
