jQuery(document).ready(function ($) {
	// Lightbox
	function kt_check_images( index, element ) {
		return /(png|jpg|jpeg|gif|tiff|bmp)$/.test(
			$( element ).attr( 'href' ).toLowerCase().split( '?' )[0].split( '#' )[0]
		);
	}

	function kt_find_images() {
		$( 'a[href]:not(".kt-no-lightbox")' ).filter( kt_check_images ).attr( 'data-rel', 'lightbox' );
	}
		
	// Theme Lightbox
	function kadence_lightbox_init() {
		$.extend(true, $.magnificPopup.defaults, {
			tClose: '',
			tLoading: virtue_lightbox.loading, // Text that is displayed during loading. Can contain %curr% and %total% keys
			gallery: {
				tPrev: '', // Alt text on left arrow
				tNext: '', // Alt text on right arrow
				tCounter: virtue_lightbox.of, // Markup for "1 of 7" counter
			},
			image: {
				tError: virtue_lightbox.error, // Error message when image could not be loaded
				titleSrc: function(item) {
					return item.el.find('img').attr('alt');
					}
				}
		});
		$("a[data-rel^='lightbox']:not('.kt-no-lightbox'):not('.custom-link')").magnificPopup({type:'image'});

		$('.kad-light-gallery').each(function(){
			$(this).find("a[data-rel^='lightbox']:not('.kt-no-lightbox'):not('.custom-link')").magnificPopup({
				type: 'image',
				gallery: {
					enabled:true
					},
					image: {
						titleSrc: 'title'
					}
				});
		});
		$('.kad-light-wp-gallery').each(function(){
			$(this).find('a[data-rel^="lightbox"]:not(".kt-no-lightbox"):not(".custom-link")').magnificPopup({
				type: 'image',
				gallery: {
					enabled:true
					},
					image: {
						titleSrc: function(item) {
							return item.el.find('img').attr('alt');
						}
					}
				});
		});
		 // Gutenberg Gallery
		$('.wp-block-gallery').each(function(){
			$(this).find('a[data-rel^="lightbox"]:not(".kt-no-lightbox"):not(".custom-link")').magnificPopup({
				type: 'image',
				gallery: {
					enabled:true
				},
				image: {
					titleSrc: function(item) {
						if ( item.el.parents('.blocks-gallery-item').find('figcaption').length ) {
							return item.el.parents('.blocks-gallery-item').find('figcaption').html();
						} else {
							return item.el.find('img').attr('alt');
						}
					}
				},
			});
		} );
		// WooCommerce Gallery with Zoom and slider 
		$('.woocommerce-product-gallery__wrapper.woo_product_slider_enabled.woo_product_zoom_enabled').each(function(){
			$(this).parents('.woocommerce-product-gallery').prepend( '<a href="#" class="woocommerce-product-gallery__trigger"></a>' );
			$(this).parents('.woocommerce-product-gallery').find('a.woocommerce-product-gallery__trigger').on( 'click', function( e ) {
				e.preventDefault();
				if ( 2 <= $(this).parents('.woocommerce-product-gallery').find( '.woocommerce-product-gallery__wrapper' ).children().length ) {
					$(this).parents('.woocommerce-product-gallery').find( ".flex-active-slide a[data-rel^='lightbox']:not('.kt-no-lightbox')" ).magnificPopup('open', $(this).parents('.woocommerce-product-gallery').find( ".flex-active-slide" ).index() );
				} else {
					$(this).parents('.woocommerce-product-gallery').find( "a[data-rel^='lightbox']:not('.kt-no-lightbox')" ).magnificPopup( 'open' );
				}
			});
		});
	}
	// Find images to open in lightbx
	kt_find_images();
	// init the lightbox
	kadence_lightbox_init();

	// re init lightbox on infiniate scroll
	$(window).on( 'infintescrollnewelements', function( event ) {
		kadence_lightbox_init();
	});
		
});

