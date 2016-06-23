// Admin JS for Pro-only features

/* global jQuery */
/* global ajaxurl */

(function($) {
    'use strict';

    $(function() {
		
		/** Make share buttons sortable **/
		$( '#pib-share-available ul' ).sortable({
			connectWith: '#pib-share-enabled ul',
			items: 'li',
			cursor: 'move',
			receive: function(e, ui) {
				pib_save_sharebar(e, ui);
			},
			stop: function(e, ui) {
				pib_save_sharebar(e, ui);
			}
		});
		$( '.pib-sortable' ).disableSelection();
		
		$( '#pib-share-enabled ul' ).sortable({
			connectWith: '#pib-share-available ul',
			items: 'li',
			cursor: 'move',
			receive: function(e, ui) {
				pib_save_sharebar(e, ui);
			},
			stop: function(e, ui) {
				pib_save_sharebar(e, ui);
			}
		});
		$( '#pib-share-order' ).disableSelection();
		
		function pib_save_sharebar() {
			var social_items = {}, pibData;
    	    $('#pib-share-enabled ul li').each(function(i, el){
        	    social_items[i] = $(this).data('id');
    	    });
			
			
			
			pibData = {
    	        action: 'pib_save_sharebar',
    	        items: social_items
	        };
			
	        $.post( ajaxurl, pibData, function( response ) { } );
		}
		
		
		/*****************************
		** Admin JS for PIB Pro
		**********************************/

        /*** Custom Button Image ***/

        var mediaFrame; //hold WP 3.5+ media manager frame

        //Thickbox media upload window
        $(".pib-cb-admin a[id*='_media_library_link']").click(function(e) {
            e.preventDefault();
            launchMediaManager();
            return false;
        });

		/**
		* Process button image selection from WP Media Manager (WP 3.5+)
        * See https://github.com/thomasgriffin/New-Media-Image-Uploader
		*
		* @since   3.0.0
		*
		*/
        function launchMediaManager() {
            //If the frame already exists, re-open it.
            if (mediaFrame) {
                mediaFrame.open();
                return;
            }

            mediaFrame = wp.media.frames.tgm_media_frame = wp.media({
                frame: "select",
                multiple: false,
                title: "Upload or select a custom \"Pin It\" button",
                library: {
                    type: "image"
                },
                button: {
                    text:  "Use selected \"Pin It\" button"
                }
            });

            mediaFrame.on("select", function() {
                //Grab our attachment selection and construct a JSON representation of the model.
                var imgObj = mediaFrame.state().get("selection").first().toJSON();

                //Send the attachment URL to our custom input field via jQuery.
                //Also set the image source (and dimensions if hover button).
                //This depends on button type.
				
                $(".pib-cb-admin img[id*='_source']").prop("src", imgObj.url);
                $(".pib-cb-admin input[id*='_url']").val(imgObj.url);
				
				//Set hover button dimension textboxes
			$("input[id*='hover_btn_img_width'], input[id*='custom_btn_width']").val(imgObj.width);
			$("input[id*='hover_btn_img_height'], input[id*='custom_btn_height']").val(imgObj.height);

            });

            //Now that everything has been set, let's open up the frame.
            mediaFrame.open();
        }

        //Process custom button image selection from thickbox popup (Pro)
        //Making table cell clickable instead of inside link tag
        //Now for page-level custom button AND image hover button
        $("div[id*='_selector'].pib-cb-admin .custom-btn-img-cell").click(function(e) {
            e.preventDefault();

            var imgUrl = $(this).data("img-url");
			var imgW = $(this).find('img').width();
			var imgH = $(this).find('img').height();

            //Update settings screen current image under modal box
            //Just fill in hidden field on parent form. No need to post via ajax.
            //Now depends on button type
                
			$(".pib-cb-admin img[id*='_source']").prop("src", imgUrl);
            $(".pib-cb-admin input[id*='_url']").val(imgUrl);
			
			//Set hover button dimension textboxes
			$("input[id*='hover_btn_img_width'], input[id*='custom_btn_width']").val(imgW);
			$("input[id*='hover_btn_img_height'], input[id*='custom_btn_height']").val(imgH);
			
            //Close thickbox popup right after click
            tb_remove();
        });

        //Close custom button image examples thickbox popup (Lite)
        $("#custom_img_btn_examples_container .upgrade-text a.close").click(function() {
            tb_remove();
        });

    });

}(jQuery));
