(function($) {
	'use strict';

	$(function() {
		var loadJS = '';

		// Load our custom JS and Purl JS (for URL parsing) no matter what.
		
		if( pibJsVars.disablePinitJS == 1 && pibJsVars.otherPlugins == false ) {

			loadJS = [ pibJsVars.scriptFolder + 'purl.min.js', pibJsVars.scriptFolder + 'public-pro.js' ];

		} else {

			// For now load pinit_main.js locally so pin count bubble working. PD 5/8/2014
			// Have an outstanding pull request, so hopefully this gets integrated into the official pinit_main.js eventually.
			// Previously was loading pinit_main.js from Pinterest's CDN.
			// Either way not using Pinterest's pinit.js script loader for now.
			// Also load purl.js for URL parsing.

			loadJS = [ pibJsVars.scriptFolder + 'pinit_main.js', pibJsVars.scriptFolder + 'purl.min.js', pibJsVars.scriptFolder + 'public-pro.js' ];

			// OLD: loadJS = [ '//assets.pinterest.com/js/pinit_main.js', pibJsVars.scriptFolder + 'purl.min.js', pibJsVars.scriptFolder + 'public-pro.js' ];
		}

		// Use LazyLoad JS to load all scripts needed before executed.
		// https://github.com/rgrove/lazyload/

		LazyLoad.js( loadJS, function() {
			// Done loading all JS.
		});
	});
}(jQuery));
