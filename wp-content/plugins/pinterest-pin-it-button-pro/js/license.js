(function ($) {
	"use strict";
	$(function () {
		
		$('.pib-license-wrap button').on( 'click', function(e) {
			
			var button = $(this);
			var licenseWrap = button.closest( '.pib-license-wrap' );
			var licenseInput = licenseWrap.find( 'input.pib-license-input' );
			
			e.preventDefault();
			
			if( licenseInput.val().length < 1 ) {
				
				button.html( 'Activate' );
				button.data( 'pib-action', 'activate_license' );
				licenseWrap.find( '.pib-license-message' ).html( 'License Inactive' ).removeClass( 'pib-valid pib-invalid' ).addClass( 'pib-inactive' );
				
			} else {
				
				// WP 4.2+ wants .is-active class added/removed for spinner
				licenseWrap.find( '.spinner' ).addClass( 'is-active' );
				
				var data = {
					action: 'pib_activate_license',
					license: licenseInput.val(),
					item: button.data( 'pib-item' ),
					pib_action: button.data( 'pib-action' ),
					id: licenseInput.attr('id')
				}

				$.post( ajaxurl, data, function(response) {
					
					console.log( response );
					
					// WP 4.2+ wants .is-active class added/removed for spinner
					licenseWrap.find( '.spinner' ).removeClass( 'is-active' );
					
					if( response == 'valid' ) {
						
						button.html( 'Deactivate' );
						button.data( 'pib-action', 'deactivate_license' );
						licenseWrap.find( '.pib-license-message' ).html( 'License is valid and active.' ).removeClass('pib-inactive pib-invalid').addClass( 'pib-valid' );
						
					} else if( response == 'deactivated' ) {
						
						button.html( 'Activate' );
						button.data( 'data-pib-action', 'activate_license' );
						licenseWrap.find( '.pib-license-message' ).html( 'License is inactive.' ).removeClass('pib-valid pib-invalid').addClass( 'pib-inactive' );
						
					} else if( response == 'invalid' ) {
						
						licenseWrap.find( '.pib-license-message' ).html( 'Sorry, but this license key is invalid.' ).removeClass('pib-inactive pib-valid').addClass( 'pib-invalid' );
						
					} else if( response == 'notfound' ) {
						
						licenseWrap.find( '.pib-license-message' ).html( 'License service could not be found. Please contact support for assistance.' ).removeClass('pib-inactive pib-valid').addClass( 'pib-invalid' );
					
					} else if ( response == 'error' ) {
						
						licenseWrap.find( '.pib-license-message' ).html( 'An error has occurred, please try again.' ).removeClass('pib-inactive pib-valid').addClass( 'pib-invalid' );
					
					}
				});
			}
		});
	});
}(jQuery));