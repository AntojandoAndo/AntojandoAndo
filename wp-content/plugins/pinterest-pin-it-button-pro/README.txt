=== Pinterest "Pin It" Button Pro ===
Contributors: pderksen, nickyoung87
Requires at least: 3.9
Tested up to: 4.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Easily add a Pinterest "Pin It" Button to your site and encourage your visitors pin your awesome content! **Pro Version**

== Changelog ==

= 3.2.5.1 - May 8, 2015 =

* Fixed bug with some options getting reset to defaults after an upgrade.

= 3.2.5 - May 4, 2015 =

* Fixed plugin CSS not getting enqueued in some cases.
* Fixed plugin license check and activate for a small number of users.
* Improved plugin license check admin JS & CSS specificity.

= 3.2.4 - April 22, 2015 =

* Updated calls to add_query_arg to prevent any possible XSS attacks.
* Added option to always enqueue scripts & styles (enabled by default).
* Fixed bug where extra slash was added to featured image tags in some cases (affecting RSS feed output).
* Plugin updater performance improvements.
* Tested up to WordPress 4.2.

= 3.2.3 =

* Fixed sharebar LinkedIn button not aligning vertically.
* Other sharebar CSS improvements.

= 3.2.2 =

* Fixed a bug that was causing an issue with the WooCommerce API JSON return.

= 3.2.1 =

* Updated Google +1/Share script reference.
* Tested up to WordPress 4.1.
* Plugin updater performance improvements.
* Minor sharebar CSS improvements.
* Improved translation (i18n) readiness.
* Fixed bug with button showing up on WooCommerce Products.

= 3.2.0 =

* Fixed encoding for media URL on hover button.
* Below image button will now pin full size image instead of thumbnail.
* Tested up to WordPress 4.0.

= 3.1.9 =

* Fixed an error with shortcodes using the sharebar.

= 3.1.8 =

* More fixes to special characters rendering HTML codes incorrectly in pin descriptions.

= 3.1.7.1 =

* Missed a few file updates.

= 3.1.7 = 

* Added Facebook App ID setting for better compatibility with current Facebook Share and Like buttons.
* Fixed a bug with shortcode buttons not enqueueing CSS & JS correctly.
* Fixed a bug with some characters (such as single quotes) rendering HTML codes in the pin description.

= 3.1.6 =

* Still more improvements to the pin count bubble on home and list pages when "any image" is selected.

= 3.1.5 =

* Fixed image hover bug introduced in 3.1.4.
* Fixed script and styles not getting loaded when only shortcodes used and not post visibility.

= 3.1.4 = 

* Fixed pin count bubble not showing on home and list pages when "any image" is selected.
* Fixed bug with custom post types not displaying buttons correctly.

= 3.1.3 =

* Added major JavaScript file loading performance improvements. Now using LazyLoad.js to load almost all button and count bubble script asynchronously.
* Added support for Google Analytics UTM tracking variables on pin links (Advanced tab and post meta).
* Added option to override the pin description per individual post/page for all hover buttons and/or below image buttons on the post/page.
* Fixed a bug where custom button images were not rendered when using a shortcode.
* Fixed a bug when using image hover with NextGen galleries at the top of a post.
* Fixed a bug where below image buttons were still showing up when disabled in post meta.
* Tested up to WordPress 3.9.

= 3.1.2 =

* Removed PressTrends integration.
* Fixed bug with Amazon affiliate code displaying an error.
* Hide post meta on disabled custom post types.
* Added action and filter hooks for extensibility.

= 3.1.1 =

* Added options for colors, sizes and shapes for the below image button based on Pinterest's official widget builder.
* "CSS classes to ignore" on image hover and below image button features now work on container elements containing images.
* Added option to disable data-pin attributes from getting added to image tags.
* Improved rendering of below image buttons (now done primarily with server-side PHP instead of jQuery).
* Fixed a bug where "Always show image button" and "Enable image protection" would not work when both selected.
* Fixed a bug where embedded script within content was getting mangled, such as AdSense scripts.
* Removed an unnecessary write to the options table on each admin page load.

= 3.1.0 =

* Image hover now allows you to right-click and save images by default (no overlay).
* Old image hover functionality with overlay and right-click image protection is now an option.
* When "any image" button type is enabled, pinning of thumbnails should instead use the full-size linked image if found. This feature should also work inside regular, Jetpack and NextGen galleries. This is done by injecting the data-pin-media attribute for each image.
* When "any image" button type is enabled, pinning of images from home, category, and other post listing pages should link to their original post URLs. This is done by injecting the data-pin-url attribute for each image.
* Image hover now works with two major lazy loading plugins: Lazy Load (http://wordpress.org/plugins/lazy-load/) and BJ Lazy Load (http://wordpress.org/plugins/bj-lazy-load/), and probably other lazy loading functionality using jQuery.sonar.
* Image hover now works with the ProPhoto 4 theme lazy loading and image protection features turned on.
* Fixed a bug with the "always show hover button" option.
* Fixed non-Pinterest share buttons not showing up in Internet Explorer.
* Fixed non-Pinterest share buttons not working with shortcode when not enabled in main settings.

= 3.0.7 =

* Fixed pin count bubble and URL to pin when both hover and below image features were disabled.
* Other minor bug and style updates.

= 3.0.6 =

* Added options for colors, sizes and shapes based on Pinterest's official widget builder.
* Added an option to show count bubble even for zero count pins.
* Minor bug fixes.
* Tested up to WordPress 3.8.

= 3.0.5 =

* Simplified and fixed license key activation issues.

= 3.0.4 =

* Updated to use new Facebook Like and Share buttons (and removed unsupported options).
* Added advanced option to use a local version of pinit.js for compatibility.
* Fixed some license key activation messaging.

= 3.0.3 =

* License keys can now be activated and validated before permanently saving.
* License keys now use password characters for better security.
* Fixed bug with sharebar not displaying correctly on post excerpts.
* Fixed bug with button and sharebar still showing on custom post types even when disabled.

= 3.0.2 =

* Added an option to disable Pinterest's pinit.js JavaScript to avoid conflicts with other Pinterest plugins, themes and custom code (on new "Advanced" tab).
* Added an option to remove the "Tweet" text from the sharebar Twitter button.
* Fixed bug with below image button.
* Tested up to WordPress 3.7.

= 3.0.1 =

* Share bar: Fixed bug with center and right alignment.
* Below image button: Fixed bug where it was displaying on image outside of content area (header, sidebar, etc.).
* WooCommerce: Fixed shortcode not working in short description area.

= 3.0.0 =

**Count bubble update**

We've improved how the count bubble behaves on listing pages. When "user selects image" is selected along with a custom "Pin It" button image, on post listing pages (home, categories, archives, etc), pins now point to the original post URL the button resides on, not the current page the pinner is viewing.

**Below image button (New)**

* Added option to show a "Pin It" button under each image.
* Allow stock "Pin It" button or use of custom button image (included in plugin or uploaded by user).
* Allow custom button height and width.
* Allow minimum image height and width to display "Pin It" buttons under.
* Can specify CSS classes that will prevent adding a "Pin It" button below an image.

**Image hover button**

* Fixed so now pins the URL the image originates from, not the URL the reader is on. Useful when reader is on a page that displays multiple posts, such as the home page.
* Added new option to always show image hover buttons (even when not hovering).
* Fixed issue that caused NextGen gallery thumbnails not to work.

**Social share bar**

* Added drag and drop interface for easier button arrangement.
* Better responsive styling -- social buttons now stack appropriately when viewing site on smaller devices.
* Improved performance of how third-party JavaScript was loading.
* Added Tweet hashtag (#) option.
* Added Google+ Share button.
* Added Facebook Like HTML5 button. IFRAME option left in.
* Added Facebook Share image button. Old Facebook Share embed code option left in but not supported by Facebook anymore.

**WooCommerce-specific features**

Now checks for WooCommerce and adds 4 more options under Post Visibility to display the "Pin It" button.

* Above Short Description
* Below Short Description
* Above Add to Cart Button
* Below Add to Cart Button

**Misc**

* Improved auto-update license key activation and checking.
* Tested up to WordPress 3.6.1.
* Now in full compliance with current "Pin It" button developer guidelines at http://developers.pinterest.com/pin_it/.
* More extensive Help section, which was moved to a separate submenu item.
* Updated CSS & JS output so they're much more "light weight" which should improve performance.
* Removed all references to "!important" in the public CSS to allow for more control of styles.
* Implemented more standards from the WordPress Settings API. Settings pages should be more maintainable going forward.
* Settings pages now using tabs to break up functionality.
* Can now specify button type for shortcode and widget. No longer inherits from main settings.
* Fixed so button now shows up on category pages.
* Added is_main_query() check for "the_content" filter.
* Removed show/hide button options on category edit screen (conflicted with post/page visibility changes).
* Optional Presstrends anonymous usage tracking.

= 2.2.6 =
* Social share bar: Facebook Share button JavaScript now referenced so can be used by secure (SSL) or non-secure websites.

= 2.2.5 =
* Image hover button: Image description on popup will now default to blank instead of the text "undefined" when the pinned image has no alt tag.
* Image hover button: Added minimum height and width option so hover button can easily be disabled on button and thumbnail images globally.
* Social share bar: Added Facebook Share button option (in addition to Facebook Like).
* Social share bar: Added Twitter username reference option (via @username).
* Now includes "http:" in create pin link to improve theme compatibility.
* Added PressTrends analytics code.

= 2.2.4 =
* Image hover button (Pro Photo 4 theme owners only): When lazy load images is turned on, it should now pull correct image instead of a blank one when pinning.

= 2.2.3 =
* Added ability to show/hide buttons on custom post types as groups.
* Individual custom post types now have show/hide button options.
* Image hover button: Now follows same Post/Page visibility rules that the Page-level button does.
* Social share bar: Now follows same Post/Page visibility rules that the Page-level button does.
* Removed show/hide button options on category edit screen (conflicted with new post/page visibility changes).
* Image hover button: Can specify additional CSS classes to prevent hovering on (besides "pib-nohover").

= 2.2.2 =
* Image hover button: Fixed flickering issue present on ProPhoto themes (www.prophotoblogs.com). Renamed hover CSS classes used to prevent future theme compatibility issues.

= 2.2.1 =
* Fixed error in settings page when using a version of WordPress less than 3.5 (call to wp_enqueue_media).

= 2.2.0 =
* Image hover button: Added hover button placement option.
* Image hover button & Page-level custom button: Added custom button image upload/select from media library ability.
* Image hover button: Default is now larger red button and in top left corner. This is in line with new image hover style on Pinterest ("new look").
* Image hover button: Improved theme compatibilities.
* Image hover button & "image selected" option: Increased height and width of modal popup window to work with Pinterest's "new look."

= 2.1.3 =
* Image hover button: Now works with featured images/post thumbnails.
* Image hover button: Improved theme compatibilities.

= 2.1.2 =
* Image hover button: More theme and browser compatibilities for hover "Pin It" button feature.

= 2.1.1 =
* Image hover button: Improved theme and browser compatibilities for hover "Pin It" button feature.

= 2.1.0 =
* Added ability to overlay a "Pin It" button on top of each image when hovering over it ("image hover button").

= 2.0.6.1 =
* Reverted embed code to back to fix issue where "user selects image" with original button was not showing pin count correctly on front page and individual posts. Embed code now from http://pinterest.com/about/goodies/#button_for_websites. Was from http://business.pinterest.com/widget-builder/#do_pin_it_button.

= 2.0.6 =
* Center align option added (single button, share bar, widget and shortcode).
* Now hides pin count bubble if a dash ("-") is returned from Pinterest when case their service is down.
* When using original button image, will now use new embed code from http://business.pinterest.com/widget-builder/#do_pin_it_button.
* Various shortcode fixes.

= 2.0.5 =
* Added LinkedIn share button.

= 2.0.4 =
* Added ability for shortcode to use the current post's featured image.
* Fixed all debug warnings logged by WP_DEBUG.

= 2.0.3 =
* Added option to specify common width in pixels between buttons on social share bar.
* Added option to hide the count bubble for the non-Pinterest buttons on social share bar.
* Fixed bug where Create Pin popup wasn't working in some cases.

= 2.0.2 =
* Tested with WordPress 3.5.
* Added: Option to save settings upon plugin uninstall.
* Changed: Removed "Always show pin count" option as it's no longer supported by Pinterest.
* Changed: Iframe option removed as it's no longer supported by Pinterest.
* Changed: Moved some JavaScript files to load in the footer rather than the header to improve page speed load and compatibility with Pinterest code. Theme must implement wp_footer() to function properly.
* Fixed: Count="vertical" shortcode fixed.
* Fixed: Updated button CSS/styles to improve compatibility with more themes.
* Fixed: Checks theme support for post thumbnails and adds if needed.
* Fixed: Various minor bug fixes.

= 2.0.1 =
* Fixed: Shortcode fixes including the option added to display other social sharing.
* Fixed: Moved some JavaScript files that were loaded in the footer to now load in the header to improve compatibility with themes not implementing wp_footer().
* Fixed: Updated button CSS/styles to improve compatibility with more themes.

= 2.0.0 =
* Initial plugin released.
