// Public JS for Pro-only features

/* global jQuery, pibJsVars */

(function($) {
	'use strict';

	$(function() {
		
		transformDom();
		
		// Main DOM transformation function.
		function transformDom() {

			// First check if using hover feature at all.
			if( pibJsVars.useHoverButton !== 0 ) {
				// Then check if using old hover button option.
				if (pibJsVars.useOldHover == 1) {
					enableImageHoverButtonsWithOverlay();
				} else {
					enableImageHoverButtons();
				}
			}

			if ( pibJsVars.pageCustomBtnClass !== null ) {
				enablePageLevelCustomButtons();
			}

			// Check if sharebar is enabled from main settings or from shortcode
			// We need to check the typeof for scVars because if there are no shortcodes on the page scVars
			// will not exist and will throw an error
			if( (typeof scVars != 'undefined' && scVars.scEnableSharebar != 0) || pibJsVars.sharebarEnabled != 0) {
				enableSharebar();
			}
		}

		/*
		 * Image Hover Buttons
		 *
		 * @since 3.0.0
		 *
		 */
		function enableImageHoverButtons() {

			$(window).load(function() {
				addImageHoverButtons();

				// Check for existence of the jQuery.sonar library, which means
				// "Lazy Load" or "BJ Lazy Load" plugin is probably enabled.
				if (jQuery().sonar) {
					// Executes on image load after a lazy load of each image.
					$('img').load(function() {
						removeAndAddImageHoverButtons(null);
					});
				}
			});

			// Executes on window resize.
			$(window).resize(function() {
				removeAndAddImageHoverButtons(null);
			});
		}

		/*
		 * Function to add all hover buttons. Run after window load and resize.
		 * Only called after enableImageHoverButtons().
		 *
		 * @since 3.0.0
		 *
		 */
		function addImageHoverButtons( singleImage ) {

			var imagesToHover;

			if( singleImage == null ) {
				// Look for all images with custom class added through server-side.
				imagesToHover = $(".pib-hover-img");
			} else {
				imagesToHover = singleImage;
			}

			// Set variable to check for post meta overrides
			var usePostMeta = ( pibJsVars.pmOverride != 0 ? true : false );

			// Our string should come like this: class1,class2,class3
			// but we need to trim out any spaces first just in case
			var hoverIgnoreClasses = pibJsVars.hoverIgnoreClasses.replace(' ', '');

			// Make sure ignore classes isn't empty before running actions on it
			if(hoverIgnoreClasses != '') {
				// We need to convert it to a class list to use for jQuery so we use this line to do that
				// after conversion it will look like this: .class1, .class2, .class3
				hoverIgnoreClasses = '.' + hoverIgnoreClasses.replace(/,/g, ', .');
			}

			var minImgWidth = pibJsVars.hoverMinImgWidth;
			var minImgHeight = pibJsVars.hoverMinImgHeight;

			imagesToHover.each( function() {
				var hoverImg = $(this);

				// Check for nearby ignore classes and if found the return so we don't add to this one
				if( $(this).closest(hoverIgnoreClasses).length > 0 ) {
					return true;
				}

				// Use non-jQuery clientWidth/clientHeight.
				// Possibly use jQuery height/width functions?
				var imgHeight = $(this).get(0).clientHeight;
				var imgWidth = $(this).get(0).clientWidth;

				// Don't add to images less than specified height and width.
				if ( (imgHeight < minImgHeight) || (imgWidth < minImgWidth) ) {
					return true;
				}

				// Get URL from data-pin-url attribute. Fallback to current window location.
				var urlToPinRaw = hoverImg.data('pin-url') || window.location.href;
				var urlToPin = encodeURIComponent(urlToPinRaw);
				
				// Image/media URL comes from src attribute.
				var urlOfImage = encodeURIComponent( hoverImg.data('pin-media') || hoverImg.prop("src") || '' );
				
				// Set description to blank if no alt attribute.
				var descOfPin = "";
				if(usePostMeta) {
					descOfPin = pibJsVars.pmDescription;
				} else {
					if (hoverImg.prop("alt")) {
						descOfPin = encodeURIComponent(hoverImg.prop("alt"));
					}
				}

				// Change url of image to data-lazyload-src attribute if using Pro Photo 4 theme and lazy load feature.
				if (hoverImg.data("lazyload-src")) {
					urlOfImage = encodeURIComponent(hoverImg.data("lazyload-src"));
				}

				// Finalize Pin it (pop up) URL (protocol-less like official widget builder).
				var pinItUrl = "//www.pinterest.com/pin/create/button/" + "?url=" + urlToPin + "&media=" + urlOfImage + "&description=" + descOfPin;

				// Use jQuery offset() for reading/writing hover button coordinates relative to document.
				var hoverBtnOffset = hoverImg.offset();

				// Corner margins hardcoded for now.
				var hoverBtnMargin = 10;

				// Change hover button offset depending on corner placement.
				// Top left placement is the default.
				switch (pibJsVars.hoverBtnPlacement) {
					case 'bottom-left':
						hoverBtnOffset.top = hoverBtnOffset.top + imgHeight - hoverBtnMargin - pibJsVars.hoverBtnHeight;
						hoverBtnOffset.left += hoverBtnMargin;
						break;
					case 'bottom-right':
						hoverBtnOffset.top = hoverBtnOffset.top + imgHeight - hoverBtnMargin - pibJsVars.hoverBtnHeight;
						hoverBtnOffset.left = hoverBtnOffset.left + imgWidth - hoverBtnMargin - pibJsVars.hoverBtnWidth;
						break;
					case 'top-right':
						hoverBtnOffset.top += hoverBtnMargin;
						hoverBtnOffset.left = hoverBtnOffset.left + imgWidth - hoverBtnMargin - pibJsVars.hoverBtnWidth;
						break;
					case 'top-left':
					default:
						hoverBtnOffset.top += hoverBtnMargin;
						hoverBtnOffset.left += hoverBtnMargin;
						break;
				}

				// Create hover pin it link button element.
				// Modify class based on hover button placement css class from PHP.
				var hoverPinItBtn = $("<a/>", {
					"class": "pib-hover-btn-link",
					"href": pinItUrl
				});

				// Add hover button after image.
				// Need to do this before calling offset().
				hoverImg.after(hoverPinItBtn);

				// Set the final hover button offset placement top/left values.
				hoverPinItBtn.offset({
					left: hoverBtnOffset.left,
					top: hoverBtnOffset.top
				});

				// Initially hide hover button.
				hoverPinItBtn.hide();

				// Check for "always show hover" selected.
				// We need compare using == here and not ===
				if( pibJsVars.alwaysShowHover == 1 ) {
					hoverPinItBtn.show();
				} else {

					// Prevent hover effects on hover button itself.
					hoverPinItBtn
						.mouseenter(function() {
							clearTimeout(hoverPinItBtn.data('timeout-id'));
						})
						.mouseleave(function() {
							var timeoutId = setTimeout(function() {
								hoverPinItBtn.fadeOut(200);
							}, 100 );

							// Save timeout function ID to variable.
							hoverPinItBtn.data('timeout-id', timeoutId);
						});


					if( singleImage == null ) {
						// Fade link/button in and out on image hover.
						// mouseenter/mouseleave same as hover(function, function).
						hoverImg
							.mouseenter(function() {
								hoverPinItBtn.fadeIn(200);
								clearTimeout(hoverPinItBtn.data('timeout-id'));
							})
							.mouseleave(function() {
								var timeoutId = setTimeout(function() {
									hoverPinItBtn.fadeOut(200);
								}, 100 );

								// Save timeout function ID to variable.
								hoverPinItBtn.data('timeout-id', timeoutId);
							});
					}
				}

				// Hook up click event for this hover button.
				hoverPinItBtn.on("click", function(event) {
					event.preventDefault();

					var modal = window.open($(this).prop("href"), "pibModal", "width=760,height=370");
					if (window.focus) {
						modal.focus();
					}
					event.stopPropagation();
				});
			});
		}

		/*
		 * Remove then add hover buttons.
		 * Intended for window resize and image lazy loading events.
		 *
		 * @since 3.0.0
		 *
		 */
		function removeAndAddImageHoverButtons( singleImage ) {
			// Remove existing buttons first.
			$('.pib-hover-btn-link').remove();
			// Now add hover buttons again.
			addImageHoverButtons(singleImage);
		}

		/*
		 * Image Hover Buttons with Overlay ("old" option)
		 *
		 * @since 3.0.0
		 *
		 */
		function enableImageHoverButtonsWithOverlay() {

			// Look for all images with custom class added through server-side.
			var imagesToHover = $(".pib-hover-img");

			var minImgWidth		= pibJsVars.hoverMinImgWidth;
			var minImgHeight	= pibJsVars.hoverMinImgHeight;

			// Executes when complete page is fully loaded, including all frames, objects and images.
			$(window).load( function() {

				imagesToHover.each(function() {
					var hoverImg = $(this);
					var imgHeight = $(this).height();
					var imgWidth = $(this).width();

					// Don't add to images less than specified height and width.
					if ( ( imgHeight < minImgHeight ) || ( imgWidth < minImgWidth ) ) {
						return true;
					}

					// Create hover container element (will contain image to hover and hover mask).
					var hoverContainer = $("<div/>")
						.addClass("pib-hover-container");

					// Create hover mask/overlay element (will contain hover link button).
					var hoverMask = $("<div/>")
						.addClass("pib-hover-mask")
						.css({
							// Use native JS "this" (not $(this)) to get proper height & width.
							// All media uploaded WP images have height and width set, so imagesLoaded script shouldn't be needed.
							height          : imgHeight + "px",
							width           : imgWidth + "px",
							"margin-top"    : hoverImg.css("margin-top"),
							"margin-right"  : hoverImg.css("margin-right"),
							"margin-bottom" : hoverImg.css("margin-bottom"),
							"margin-left"   : hoverImg.css("margin-left")
						});

					// Get URL from data-pin-url attribute. Fallback to current window location.
					var urlToPinRaw = hoverImg.data('pin-url') || window.location.href;
					var urlToPin = encodeURIComponent(urlToPinRaw);

					// Image/media URL comes from src attribute.
					var urlOfImage = encodeURIComponent(hoverImg.prop("src"));

					// Set description to blank if no alt attribute.
					var descOfPin = "";
					if (hoverImg.prop("alt")) {
						descOfPin = encodeURIComponent(hoverImg.prop("alt"));
					}

					// Change url of image to data-lazyload-src attribute if using Pro Photo 4 theme and lazy load feature.
					if (hoverImg.data("lazyload-src")) {
						urlOfImage = encodeURIComponent(hoverImg.data("lazyload-src"));
					}

					// Finalize Pin it (pop up) URL (protocol-less like official widget builder).
					var pinItUrl = "//www.pinterest.com/pin/create/button/" + "?url=" + urlToPin + "&media=" + urlOfImage + "&description=" + descOfPin;

					// Create hover pin it link button element.
					// Modify class based on hover button placement css class from PHP.
					var hoverPinItBtn = $("<a/>", {
						"class": "pib-hover-btn-link pib-hover-" + pibJsVars.hoverBtnPlacement,
						"href": pinItUrl
					});

					// Get WP alignment class name from original image and add same to hover mask & hover container divs.
					var wpAlignClassNames = ["alignleft", "alignright", "aligncenter"];

					$.each(wpAlignClassNames, function(i, val) {

						// Check if image has built-in WP align left/right/center CSS class. Ignore "alignnone".
						if (hoverImg.hasClass(val)) {
							hoverContainer.addClass("pib-hover-container-" + val);
							hoverMask.addClass("pib-hover-mask-" + val);

							// Additional styles to help centered image mask and container.
							if (val === "aligncenter") {
								hoverContainer.css("clear", "both");
								hoverMask.css({
									"display":      "none",
									"margin-left":  "auto",
									"margin-right": "auto",
									"right":        0
								});
							}
						}
					});

					// Append/insert elements at appropriate DOM locations.
					hoverImg.wrap(hoverContainer);
					hoverMask.append(hoverPinItBtn);
					hoverMask.insertBefore(hoverImg);

					// Fade link/button in and out on image hover.
					// When hovering over outside container, display mask div.
					// mouseenter/mouseleave same as hover(function, function).

					if( pibJsVars.alwaysShowHover != 0 ) {
						$('.pib-hover-mask').show();
					} else {
						$(".pib-hover-container")
							.mouseenter(function() {
								$(this).children(".pib-hover-mask").fadeIn(200);
							})
							.mouseleave(function() {
								$(this).children(".pib-hover-mask").fadeOut(200);
							});
					}

					// Hook up click event for all hover buttons.
					hoverPinItBtn.on("click", function(event) {
						event.preventDefault();

						var modal = window.open($(this).prop("href"), "pibModal", "width=760,height=370");
						if (window.focus) {
							modal.focus();
						}
						event.stopPropagation();
					});
				});
			});
		}

		/*
		 * Page-Level Custom Buttons
		 * Add a specific class to the pin it button for overriding the Pinterest CSS.
		 *
		 * @since 3.0.0
		 *
		 */
		function enablePageLevelCustomButtons() {

			$("a[class*='pin_it_button']").each(function() {

				if($(this).parent().hasClass('pin-it-btn-wrapper')) {

					/*
					* Because we're using CSS !important declarations, we need to use
					* $.attr('style'), not $.prop('style') or $.css().
					* See http://stackoverflow.com/questions/5874652/prop-vs-attr
					*/

				   $(this).attr('style',
					   'background-image: url(' + pibJsVars.pageCustomBtnClass + ') !important; ' +
						   'width: ' +  pibJsVars.pageCustomBtnWidth + 'px !important; ' +
						   'height: ' + pibJsVars.pageCustomBtnHeight + 'px !important; ' +
						   'background-position: 0 0 !important; background-size: auto !important;'
				   );

				   var countLeft = parseInt( pibJsVars.pageCustomBtnWidth, null );
				   var countHeight = parseInt (pibJsVars.pageCustomBtnHeight, null );

				   if ( $(this).data('pin-config') === 'beside' ) {
					   // Vertical count bubble needs to overrid left attribute, but leave aligned to top.
					   // And add 1 pixel for spacing.
					   $(this).children('span').attr('style', 'left: ' + (countLeft + 1) + 'px !important');
				   } else {
					   // Horizontal count bubble adjustments.
					   var spanWidth = $(this).children('span:first').width();

					   // Override left attribute to center bubble above button.
					   // Override bottom attribute to center bubble above button by adding 7 pixels.
					   $(this).children('span').attr('style',
						   'left: ' + ( (countLeft / 2) - (spanWidth / 2) ) + 'px !important; ' +
							   'bottom: ' + (countHeight + 1) + 'px !important;'
					   );
				   }
				}
			});
		}

		/*
		 * Add Sharebar buttons only as needed.
		 *
		 * @since 3.1.3
		 *
		 */
		function enableSharebar() {

			(function(doc, script) {
				var js,
					fjs = doc.getElementsByTagName(script)[0],
					add = function(url, id) {
						if (doc.getElementById(id)) {return;}
						js = doc.createElement(script);
						js.src = url;
						id && (js.id = id);
						fjs.parentNode.insertBefore(js, fjs);
					};

				// Add checks to make sure the options are enabled so we only load the scripts we need

				// Google+ button
				if (
					( $.inArray('Google +1', pibJsVars.enabledSharebarButtons) != -1 ) ||
					( $.inArray('Google Share', pibJsVars.enabledSharebarButtons) != -1 )
				) {
					add('https://apis.google.com/js/platform.js');
				}

				// Facebook SDK
				// https://developers.facebook.com/docs/plugins/like-button/
				// https://developers.facebook.com/docs/plugins/share-button
				// https://developers.facebook.com/docs/sharing/reference/share-dialog
				if (
					( $.inArray('Facebook Like', pibJsVars.enabledSharebarButtons ) != -1 ) ||
					( $.inArray('Facebook Share', pibJsVars.enabledSharebarButtons ) != -1 )
				) {
					// Prepend "fb-root" div only once to top of body content.
					$( '<div id="fb-root"></div>' ).prependTo( $( 'body' ) );

					(function(d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) return;
						js = d.createElement(s); js.id = id;
						js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0" + ( pibJsVars.appId != '' ? '&appId=' + pibJsVars.appId : '' );
						fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
				}

				// Twitter SDK
				if ( $.inArray('Twitter', pibJsVars.enabledSharebarButtons) != -1 ) {
					add('//platform.twitter.com/widgets.js', 'twitter-wjs');
				}

				// Linked In
				if ( $.inArray('Linked In', pibJsVars.enabledSharebarButtons) != -1 ) {
					add('//platform.linkedin.com/in.js');
				}

			}(document, 'script'));
		}
	});
}(jQuery));

/*
 * JS pin count formatting function from pinit.js.
 * 
 * @since 3.0.0
 * 
 */
function prettyPinCount(n) {
	'use strict';

	if (n > 999) {
		if (n < 1000000) {
			n = parseInt(n / 1000, 10) + 'K+';
		} else {
			if (n < 1000000000) {
				n = parseInt(n / 1000000, 10) + 'M+';
			} else {
				n = '++';
			}
		}
	}
	return n;
}

