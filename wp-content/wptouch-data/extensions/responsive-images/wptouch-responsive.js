function wptouchResponsiveImagesAjax( actionName, actionParams, callback ) {
	var ajaxData = {
		action: 'wptouch_media_ajax',
		wptouch_media_action: actionName,
		wptouch_media_nonce: WPtouchResponsiveImages.nonce
	};

	for ( name in actionParams ) { ajaxData[name] = actionParams[name]; }

	jQuery.post( WPtouchResponsiveImages.ajaxurl, ajaxData, function( result ) {
		callback( result );
	});
}

function doResponsiveImagesReady() {
	jQuery.ajaxSetup ({
	    cache: false
	});

	// Set cookie for size
	var minSize = window.screen.width;
	if ( minSize > window.screen.height ) {
		minSize = window.screen.height;
	}
	// wptouchCreateCookie( 'wptouch-media-optimize-size', minSize );

	jQuery( 'span.wptouch-responsive-image' ).each( function() {
		var currentElement = jQuery( this );
		var originalURL = jQuery( this ).attr( 'data-url' );
		var originalClasses = jQuery( this ).attr( 'data-classes' );

		var modifier = 1;
		if ( window.devicePixelRatio > 1 ) {
			modifier = window.devicePixelRatio;
		}

		var orientation = 0;
		if ( window.orientation == 90 || window.orientation == -90 ) {
			orientation = 1; // landscape
		} else if ( window.orientation == 180 || window.orientation == 0 ) {
			orientation = 2; // portrait
		}

		var data = {
			media_url: originalURL,
			device_width: window.screen.width*modifier,
			device_height: window.screen.height*modifier,
			device_orientation: orientation
		};

		wptouchResponsiveImagesAjax( 'get_cached_image', data, function( result ) {
			currentElement.replaceWith( '<img src="' + result + '" class="' + originalClasses + '"/>' );
		});
	});


}

jQuery( document ).ready( function() { doResponsiveImagesReady(); });
jQuery( document ).on( 'wptouch_ajax_content_loaded', 'doResponsiveImagesReady' );