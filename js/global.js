/**
 * Global JavaScript
 */


// Declare variables that need to be accessed in various contexts
var pilau_html;
var pilau_body;
var pilau_footer;
// Breakpoints
var pilau_bps = {
	'large':		1270, // This and above is "large"
	'side_nav':		1000, // Above this, there's a side nav, below is mobile nav
	'medium':		640 // This and above is "medium"; below is "small"
};
var pilau_vw; // Viewport width
var pilau_vh; // Viewport height
var pilau_vw_large;
var pilau_vw_side_nav;
var pilau_vw_medium;
var pilau_vw_small;
var pilau_scrolltop;
var pilau_overlay;
var pilau_jls; // Jump links sidebar
var pilau_is_safari = ! ( 'webkitRequestAnimationFrame' in window ); // Very hacky
var pilau_is_lt_ie9;
var pilau_is_lt_ie8;
var closer_menu; // Main menu
var closer_main; // Main content area
var closer_timelines;
var closer_timelines_multiple;
var closer_timelines_full;

/**
 * Flags for throttling window scroll and resize event functionality
 * @link	http://ejohn.org/blog/learning-from-twitter/
 */
var pilau_did_resize = false;
var pilau_did_scroll = false;


/** Trigger when DOM has loaded */
jQuery( document ).ready( function( $ ) {
	var placeholders = $( '[placeholder]' ),
		cn = $( '#cookie-notice' ),
		di = $( 'img[data-defer-src]' ),
		op = $( 'li#older-posts' ),
		tweets = $( '#tweets' ),
		bc = $( '.bc' ),
		bf = $( '.button-filters' ),
		fm = $( '#footer-main' ),
		fc = fm.find( '.footer-copyright' ),
		fmcb = fm.find( '.contact-box' ),
		ct = $( '.collapsible-text' ),
		mm = $( '.maybe-more' ),
		tabs = $( '.accordion-standard' ),
		timelineAcc = $( '.timeline-content' ),
		fv = $( '.fit-video' ),
		cth = 45; // Collapsible text collapsed height
	var back, nav, ss, fmcbc, fmcbcf;
	pilau_html = $( 'html' );
	pilau_body = $( 'body' );
	pilau_footer = $( '#footer' );
	closer_menu = $( '#menu' );
	closer_main = $( '#main' );
	closer_timelines = $( '.timeline' );
	closer_timelines_multiple = closer_timelines.filter( '.tl-multiple' );
	closer_timelines_full = closer_timelines_multiple.filter( '.tl-full' );
	closer_viewport_vars();


	/** IE hacks */
	pilau_is_lt_ie9 = pilau_html.hasClass( 'lt-ie9' );
	pilau_is_lt_ie8 = pilau_html.hasClass( 'lt-ie8' );

	// Hack for IE10+ styling (conditional comments not supported)
	if ( /MSIE 10\.\d+;/.test( navigator.userAgent ) || /Trident/.test( navigator.userAgent ) ) {
		pilau_html.addClass( 'ie' );
	}

	if ( pilau_html.hasClass( 'ie' ) ) {
		pilau_is_safari = false;
	}

	$('.filter_toggler').on('click',function(){
	  $(this).parent().children().toggle();  //swaps the display:none between the two spans
	  $(this).parent().parent().find('.news-blog-filter').slideToggle();  //swap the display of the main content with slide action

	});


	/** JS-dependent elements */
	$( '.remove-if-js' ).remove();


	/**
	 * Placeholder fallback
	 */
	if ( ! Modernizr.input.placeholder ) {

		// set placeholder values
		placeholders.each( function() {
			if ( $( this ).val() == '' ) {
				$( this ).val( $( this ).attr( 'placeholder' ) );
			}
		});

		// focus and blur of placeholders
		placeholders.on( 'focus', function() {
			if ( $( this ).val() == $( this ).attr( 'placeholder' ) ) {
				$( this ).val('');
				$( this ).removeClass( 'placeholder' );
			}
		}).on( 'blur', function() {
				if ( $( this ).val() == '' || $( this ).val() == $( this ).attr( 'placeholder' ) ) {
					$( this ).val( $( this ).attr( 'placeholder' ) );
					$( this ).addClass( 'placeholder' );
				}
			});

		// remove placeholders on submit
		placeholders.closest( 'form' ).on( 'submit', function() {
			$( this ).find( '[placeholder]' ).each( function() {
				if ( $( this ).val() == $( this ).attr( 'placeholder' ) ) {
					$( this ).val('');
				}
			})
		});

	}


	/** Cookie notice */
	if ( cn.length ) {
		cn.find( '.close' ).on( 'click', 'a', function () {
			cn.slideUp( 400, function() {
				$( this ).remove();
			});
			return false;
		});
	}


	/**
	 * Load deferred images
	 *
	 * You can trigger JS functions when the deferred image has loaded like this:
	 * <img src="<?php echo PILAU_PLACEHOLDER_GIF_URL; ?>" data-defer-src="<?php echo $image_id; ?>" data-defer-callback="my_callback_function" alt="">
	 *
	 * @link	http://24ways.org/2010/speed-up-your-site-with-delayed-content/
	 */
	if ( di.length ) {
		di.each( function() {
			var el = $( this );
			var cb = el.data( 'defer-callback' );
			if ( typeof cb !== 'undefined' ) {
				// Attach callback function to load event
				el.on( 'load', window[ cb ] );
			}
			el.attr( 'src', el.data( 'defer-src' ) );
		});
	}


	/**
	 * AJAX "more posts" loading
	 */
	if ( op.length ) {

		// Replace label
		op.find( 'a' ).html( pilau_ajax_more_data.show_more_label );

		op.on( 'click', 'a', function( e ) {
			var vars;
			e.preventDefault();

			// Initialize vars
			vars = pilau_ajax_more_data;
			vars['action'] = 'pilau_get_more_posts';
			vars['offset'] = $( this ).parent().siblings( 'li' ).length;
			//console.log( vars );

			// Get posts
			$.post(
				pilau_global.ajaxurl,
				vars,
				function( data ) {
					var i, first_post_id, iefix;
					//console.log( data );

					// Remove "last" class from current last post
					op.prev().removeClass( 'last' );

					// Insert and reveal posts
					i = 0;
					pilau_body.append( '<div id="_ieAjaxFix" style="display:none"></div>' );
					iefix = $( "#_ieAjaxFix" );

					iefix.html( data ).find( "li.item" ).each( function() {
						var el = $( this );
						if ( i == 0 ) {
							first_post_id = el.attr( "id" );
						}
						el.hide().insertBefore( 'li#older-posts' ).slideDown( 600 );
						i++;
					});
					iefix.remove();

					// Get rid of more posts link if there's no more
					if ( op.siblings( 'li' ).length >= pilau_ajax_more_data.found_posts ) {
						op.fadeOut( 1000 );
					}

					// Scroll to right place
					//$( 'html, body' ).delay( 1000 ).animate( { scrollTop: $( "#" + first_post_id ).offset().top }, 1000 );

				}
			);

		});

	}


	/**
	 * Main menu
	 */

     $('#menu-mobile a').click(function() {
        $('#menu').toggle();


    });
    $('.btn_close ').click(function() {
        $('.nav').removeClass('show');
        $('.btn_close').removeClass('show');
        $('.btn_menu').removeClass('hide');
    });
	if ( closer_menu.length ) {
		back = $( '#back-to-main' );
		sf = closer_main.find( '.search-form form' );

		// Back to main menu
		if ( back.length ) {
			back.on( 'click', 'a', function( e ) {
				e.preventDefault();
				$( '#nav-sub' ).hide();
				$( '#back-to-home' ).hide();
				back.hide();
				$( '#nav-main' ).show();
			});
		}

		// Initialise mobile menu and search whatever, ready for adjusted browser size
		closer_main.on( 'click', '#menu-mobile a', function( e ) {
			e.preventDefault();
			// closer_menu.slideToggle();
		}).on( 'click', '.search-mobile a', function( e ) {
			e.preventDefault();
			sf.toggle();
		});

		if ( pilau_vw_side_nav ) {

			// Full side nav version
			// nav = closer_menu.find( '.menu' );
			// var all_links = document.querySelectorAll(".menu li > a");

			// for(var i=0; i<all_links.length; i++){
			//     all_links[i].removeAttribute("href");
			// }

			// $('.menu li > .sub-menu-wrapper').parent().click(function() {
			// 	  var submenu = $(this).children('.sub-menu-wrapper');
			// 	  if ( $(submenu).is(':hidden') ) {
			// 	    $(submenu).slideDown(200);
			// 	  } else {
			// 	    $(submenu).slideUp(200);
			// 	  }
			// 	});

			/* Add close / menu icon
			 closer_menu.prepend( '<div id="nav-main-control"><a href="#" class="close img-rep">Close menu</a></div>' ).on( 'click', '#nav-main-control a', function( e ) {
			 e.preventDefault();
			 var el = $( this );

			 if ( el.hasClass( 'close' ) ) {

			 // Hide menus then close
			 nav.hide();
			 closer_menu.animate(
			 { width: '65px' },
			 500,
			 function() {
			 // Change icon
			 el.removeClass( 'close' ).addClass( 'menu' ).text( 'Open menu' );
			 // Add / show label
			 if ( ! el.hasClass( 'labelled' ) ) {
			 el.after( '<span class="label">Menu</span>' );
			 el.addClass( 'labelled' );
			 } else {
			 el.siblings( '.label' ).show();
			 }
			 }
			 );
			 closer_main.animate(
			 { 'margin-left': '65px' },
			 500
			 );

			 } else {

			 // Open, then show menus
			 closer_menu.animate(
			 { width: '200px' },
			 500,
			 function() {
			 nav.fadeIn( 300 );
			 // Change icon
			 el.removeClass( 'menu' ).addClass( 'close' ).text( 'Close menu' );
			 // Hide label
			 el.siblings( '.label' ).hide();
			 }
			 );
			 closer_main.animate(
			 { 'margin-left': '200px' },
			 500
			 );

			 }

			 });
			 */

			// Add top-level link to top of mega-menus
			// Also sort out height and scrolling
			// closer_menu.find( '.sub-menu-wrapper' ).each( function() {
			// 	var el = $( this );
			// 	var tlp_url = el.siblings( 'a' ).attr( 'href' );
			// 	var tlp_label = el.siblings( 'a' ).text();
			// 	var h = el.height();
			// 	el.prepend( '<div class="top-level-page"><a href="' + tlp_url + '" class="block-wrapper">' + tlp_label + '</a></div>' );
			// 	el.height( ( pilau_vh - 100 ) + 'px' );
			// 	if ( h > pilau_vh ) {
			// 		el.css( 'overflow', 'scroll' );
			// 	}
			// });

			// Top-level links may open mega-menus
			if ( ! pilau_is_lt_ie8 ) {


				// closer_menu.on( 'mouseenter', '#nav-main > li', function( e ) {
				// 	var el = $( this );
				// 	var sm = el.children( '.sub-menu-wrapper' );
				// 	el.toggleClass( 'hover' );

				// 	// Is there a sub-menu?
				// 	if ( sm.length ) {

				// 		// Show it
				// 		sm.show();

				// 	}

				// }).on( 'mouseleave', '#nav-main > li', function( e ) {
				// 	var el = $( this );
				// 	var sm = el.children( '.sub-menu-wrapper' );
				// 	el.toggleClass( 'hover' );

				// 	// Is there a sub-menu?
				// 	if ( sm.length ) {

				// 		// Hide it
				// 		sm.hide();

				// 	}

				// });

				// Clone the contact details from the footer
				fmcbc = fmcb.clone();
				fmcbc.find( '.mc_embed_signup' ).attr( 'id', fmcbc.find( '.mc_embed_signup' ).attr( 'id' ) + '-2' );
				fmcbcf = fmcbc.find( 'form' );
				fmcbcf.attr( 'id', fmcbcf.attr( 'id' ) + '-2' );
				fmcbcf.find( '[id]' ).each( function() {
					var el = $( this );
					el.attr( 'id', el.attr( 'id' ) + '-2' );
				});
				fmcbcf.find( 'label' ).each( function() {
					var el = $( this );
					el.attr( 'for', el.attr( 'for' ) + '-2' );
				});
				fmcbc
					.appendTo( closer_menu.find( '.nav-contact' ) )
					.wrap( '<div class="sub-menu-wrapper"></div>' ).height( pilau_vh + 'px' )
					.prepend( '<div class="top-level-page">Contact</div>' )
					.find( '.contact-details' )
					.addClass( 'clearfix' );

			}

		}

	}


	/**
	 * Jump links sidebar
	 */
	if ( pilau_body.hasClass( 'has-jls' ) && ! pilau_is_lt_ie9 ) {

		// Create container
		pilau_jls = $( '<div class="right-col jump-links"><h2>In this section</h2><ul class="buttons"></ul></div>' );
		pilau_jls.appendTo( '.has-aside-links' );

		// Add section links
		$( '.jump-section' ).each( function( i ) {
			var el = $( this );
			var l = '<li id="jls-' + i + '"';
			if ( i == 0 ) {
				l += ' class="current"';
			}
			l += '><a href="#' + el.attr( 'id' ) + '" class="button">' + el.find( 'h2.jump-title' ).text()  + '</a></li>';
			pilau_jls.find( 'ul' ).append( l );
		});

	}


	/**
	 * Collapsible text
	 */
	if ( ct.length && ! pilau_is_lt_ie8 ) {

		// Wrapper needed for positioning control
		ct.wrap( '<div class="collapsible-wrap"></div>' );
		ct.append( '<div class="fade"></div>' );

		ct.each( function() {
			var el = $( this );

			// Store auto height, then collapse
			el.data( 'height', el.outerHeight( pilau_is_safari ) );
			el.css( 'height', cth + 'px' );

			// Add controls
			$( '<a href="#" title="Click to open" class="collapsible-control closed no-button-effect"><span>+</span> ' + el.data( 'label-open' ) + '</a>' ).appendTo( el.parent() );

		});

		// Click event
		pilau_body.on( 'click', 'a.collapsible-control', function( e ) {
			e.preventDefault();
			var el = $( this );
			var ct = el.parent().find( '.collapsible-text' );
			if ( el.hasClass( 'closed' ) ) {
				ct.animate( { height: ct.data( 'height' ) }, 500 ).addClass( 'hide-fade' );
				el.removeClass( 'closed' );
				el.html( '<span>-</span> ' + ct.data( 'label-close' ) );
			} else {
				ct.animate( { height: cth + 'px' }, 300 ).removeClass( 'hide-fade' );
				el.addClass( 'closed' );
				el.html( '<span>+</span> ' + ct.data( 'label-open' ) );
			}
		});

	}


	/**
	 * Maybe more...
	 *
	 * Must come after collapsible text
	 */
	if ( mm.length && ( pilau_vw_medium || pilau_vw_large ) && ! pilau_is_lt_ie8 ) {
		var collapse_height_offset = 20;

		mm.each( function() {
			var el = $( this );
			var mmr = el.data( 'maybe-more-rows' );
			var h = 0;

			// Need to be sure images are loaded before making measurements
			imagesLoaded( this, function() {

				// Get list dimensions
				d = pilau_list_dimensions( el );

				// Check if there's more than the specified number of rows
				if ( d.row_count > mmr ) {

					// Calculate height of rows to be exposed
					for ( var r = 0; r < mmr; r++ ) {
						h += d.row_heights[ r ];
					}
					h -= collapse_height_offset;

					// Store heights, then collapse
					el.attr( 'data-height', el.outerHeight( true ) );
					el.attr( 'data-collapsed-height', h );
					el.css( 'height', h + 'px' );

					// Add more link
					el.after( '<p class="maybe-more-more"><a href="#" class="closed"><span class="icon icon-angle-circled-down"></span> <span class="label">More</span></a></p>' );

				}

			});

		});

		// Click event
		pilau_body.on( 'click', '.maybe-more-more a', function( e ) {
			e.preventDefault();
			var el = $( this );
			var el_mm = el.parent().prev();
			var mm_mmr = el_mm.data( 'maybe-more-rows' );
			if ( el.hasClass( 'closed' ) ) {
				el_mm.animate( { height: el_mm.attr( 'data-height' ) + 'px' }, 800 );
				el.removeClass( 'closed' );
				el.find( '.icon' ).removeClass( 'icon-angle-circled-down' ).addClass( 'icon-angle-circled-up' );
				el.find( '.label' ).text( 'Less' );
			} else {
				el_mm.animate( { height: el_mm.attr( 'data-collapsed-height' ) + 'px' }, 500 );
				el.addClass( 'closed' );
				el.find( '.icon' ).addClass( 'icon-angle-circled-down' ).removeClass( 'icon-angle-circled-up' );
				el.find( '.label' ).text( 'More' );
			}
		});

	}


	/**
	 * Tabs
	 */
	if ( tabs.length && ! pilau_is_lt_ie8 ) {

		tabs.on( 'click', 'a', function( e ) {
			e.preventDefault();
			var el = $( this );
			var li = el.parent( 'h3' );
			if ( ! li.hasClass( 'current' ) ) {
				var panels = li.next( '.panel' );

				// Switch panels
				// panels.hide();
				// panels.filter( '#panel-' + pilau_get_string_part( li.attr( 'id' ) ) ).show();

				// Switch tab state
				li.siblings( '.current' ).removeClass( 'current' );
				li.addClass( 'current' );

			}else{
				$('h3.current' ).removeClass('current');
			}
		});

	}
	timelineAcc.on( 'click', '.accordion-init', function( e ) {
		e.preventDefault();
		var ef = $( this );
		var ah = ef.parent( '.accordion-header' );
		var panels = ah.next( '.accordion-content' );
		if ( ! ah.hasClass( 'active' ) ) {

			// Switch panels
			// panels.hide();
			// panels.filter( '#panel-' + pilau_get_string_part( li.attr( 'id' ) ) ).show();
			panels.attr("aria-hidden" , "false");
			ef.attr("aria-expanded" , "true");
			// Switch tab state
			ah.siblings( '.active' ).removeClass( 'active' );
			ah.addClass( 'active' );

		}else{
			$('.accordion-header').removeClass('active');
			panels.attr("aria-hidden" , "true");
			ef.attr("aria-expanded" , "false");
		}
	});

	/**
	 * Timelines
	 */
	closer_timelines_adjust();

	if ( closer_timelines_full && pilau_vw_large && ! pilau_is_lt_ie9 ) {

		// Do sweep stuff
		var hs = 0; // Highest sweep
		closer_timelines_full.find( '.sweep' ).each( function() {
			var el = $( this );

			// Track the highest
			if ( el.outerHeight( true ) > hs ) {
				hs = el.outerHeight( true );
			}

			// Do styling
			el.css({
				'left':		el.data( 'left' ) + '%'
			});

			// Add indicator dot
			el.prepend( '<a href="#" class="indicator"><span class="year">' + el.data( 'year' ) + ' - </span><span class="age-range">' + el.data( 'age-range' ) + '</span></a>' );

		});

		// Dot events
		closer_timelines_full.on( 'mouseenter', 'a.indicator', function( e ) {
			var el = $( this );
			el.addClass( 'open' );
		} ).on( 'mouseleave', 'a.indicator', function( e ) {
			var el = $( this );
			el.removeClass( 'open' );
		});

		// Set height for details area

		// Close all studies, then add event to open them
		closer_timelines_full.find( '.tl-study' ).not( '#study-166' ).addClass( 'closed' );
		closer_timelines_full.on( 'click', 'a.tl-main-link', function( e ) {
			e.preventDefault();
			var el = $( this );
			var s = el.parents( '.tl-study' );
			var sd = el.siblings( '.tl-study-details' );
			if ( s.hasClass( 'closed' ) ) {
				sd.slideDown( 1000, function() {
					s.removeClass( 'closed' );
				});
			} else {
				sd.slideUp( 600, function() {
					s.addClass( 'closed' );
				});
			}
		});

	}


	/**
	 * Basic carousels (not handled by Slideshow plugin)
	 */
	if ( bc.length && pilau_vw_large && ! pilau_is_lt_ie9 ) {
		var ul = $( 'ul', bc );
		var li = ul.children( 'li' );
		var width = li.outerWidth();
		var margin = parseInt( li.css( 'marginRight' ) );
		var wm = width + margin;

		// Do we need scrolling?
		if ( li.length > ul.data( 'items-visible' ) ) {

			// Add scroll arrows
			$( '<a href="#" class="scroll left"><span>&laquo;</span></a>' ).prependTo( bc );
			$( '<a href="#" class="scroll right"><span>&raquo;</span></a>' ).appendTo( bc );

			// Set width
			ul.css( 'width', ( li.length * wm ) + 'px' );

			// Scrolling
			bc.on( 'click', 'a.scroll,a.scroll span', function() {
				var el = $( this );
				if ( el.prop( 'tagName' ) == 'SPAN' ) {
					el = $( this ).parent( 'a' );
				}
				var cur, curi, clo, iv, nlo;
				li = ul.children( 'li' ); // refresh

				if ( ! el.hasClass( 'disabled' ) ) {
					cur = li.filter( '.current' );
					curi = li.index( cur );
					clo = ul.css( 'left' );
					clo = ( clo == 'auto' ) ? 0 : parseInt( clo );
					iv = parseInt( ul.data( 'items-visible' ) );

					// Temporarily disable both scroll links
					bc.find( 'a.scroll' ).addClass( 'disabled' );

					// Determine scroll direction
					if ( el.hasClass( 'left' ) ) {

						// Scroll to the left?
						if ( ! cur.prev().length ) {

							// Move from the other end
							li.filter( ':last-child' ).insertBefore( cur );
							// Adjust positioning
							ul.css( 'left', '-' + wm + 'px' );
							// Set new left offset for animation
							nlo = 0;

						} else {

							// Set new left offset for animation
							nlo = clo + wm;

						}

						// Adjust current class
						cur.removeClass( 'current' ).prev().addClass( 'current' );

					} else {

						//console.log( curi + iv );
						//console.log( li.get( curi + iv ) );
						//console.log( li.filter( ':last-child' ) );

						// Scroll to the right?
						if ( typeof li.get( curi + iv ) == 'undefined' ) {

							// Move from the other end
							li.filter( ':first-child' ).insertAfter( li.filter( ':last-child' ) );
							// Adjust positioning
							ul.css( 'left', ( clo + wm ) + 'px' );
							// Set new left offset for animation
							nlo = 0 - wm;

						} else {

							// Set new left offset for animation
							nlo = clo - wm;

						}

						// Adjust current class
						cur.removeClass( 'current' ).next().addClass( 'current' );

					}

				}

				// Now animate
				if ( typeof nlo != 'undefined' ) {
					ul.animate({ 'left': nlo + 'px' }, 500 );
				}

				// Re-enable links
				bc.find( 'a.scroll' ).removeClass( 'disabled' );

				return false;
			});

		}

	}


	/**
	 * Close overlays
	 */
	pilau_body.on( 'click', 'a.overlay-close', function( e ) {
		e.preventDefault();
		el = $( this );
		el.parent( '.overlay' ).fadeOut( 500 );
	});


	/**
	 * Play video overlays
	 */
	pilau_body.on( 'click', 'a.play-video', function( e ) {
		e.preventDefault();
		el = $( this );

		// Do overlay
		pilau_do_overlay( 'video-embed-' + el.data( 'video-embed-id' ) );

	});


	/**
	 * Button filters
	 */
	if ( bf.length ) {

		bf.on( 'click', 'a.tab', function( e ) {
			e.preventDefault();
			var el = $( this );
			// Store tab to pass when form is submitted
			el.parents( '.button-filters' ).find( 'input[name="tab"]' ).val( el.data( 'tab' ) );
		}).on( 'change', 'input.button', function() {
			var el = $( this );
			//var pbf = el.parents( '.button-filters' );
			//var sl = pbf.find( '.selected-lists' );
			//var t = sl.find( '.taxonomy' ).filter( '.' + pilau_get_string_part( el.parents( '.panel' ).attr( 'id' ) ) );
			//var tl = t.find( 'ul' );
			el.parents( 'li' ).toggleClass( 'selected' );
			/*
			if ( el.is( ':checked' ) ) {
				sl.show();
				t.show();
				pbf.find( '.apply-filters' ).removeClass( 'hide-if-js' );
				// Add to taxonomy list
				tl.append( '<li class="term-' + el.val() + '">' + el.parents( 'label' ).children( '.label' ).text() + '</li>' )
			} else {
				// Remove from taxonomy list
				tl.find( '.term-' + el.val() ).remove();
				if ( ! tl.find( 'li' ).length ) {
					t.hide();
					if ( ! sl.find( 'li' ).length ) {
						sl.hide();
						pbf.find( '.apply-filters' ).addClass( 'hide-if-js' );
					}
				}
			}
			*/
		});

	}


	/**
	 * Home page
	 */
	if ( pilau_body.hasClass( 'home' ) ) {

		/*
		 * Carousel
		 */

		// Adjust positioning of indicator according to number of slides
		//ss = $( '.ps-slideshow' );
		//ss.find( '.ps-indicator' ).find( 'ul' ).css( 'margin-left', '-' + ( ( ss.find( '.slide' ).length * 26 ) / 2 ) + 'px' );

	}


	/**
	 * Tweets
	 */
	if ( tweets.length && ! pilau_vw_small ) {
		var tul = tweets.find( 'ul.tweets' );
		var tl = tul.find( 'li' );
		var ta;

		// Add nav arrows?
		if ( tl.length > 1 ) {
			tul.before( '<a href="#" class="img-rep arrow prev">Previous</a>' );
			tul.after( '<a href="#" class="img-rep arrow next" style="display: none">Next</a>' );
			ta = tweets.find( 'a.arrow' );
			tweets.on( 'click', 'a.arrow', function( e ) {
				e.preventDefault();
				var el = $( this );
				var cur, t;
				if ( ! el.hasClass( 'disabled' ) ) {
					ta.addClass( 'disabled' );
					cur = tl.filter( '.current' );
					t = el.hasClass( 'prev' ) ? cur.next() : cur.prev();
					t.css( 'visibility', 'hidden' ).show();
					cur.fadeOut( 300, function() {
						t.hide().css( 'visibility', 'visible' ).fadeIn( 200, function() {
							cur.removeClass( 'current' );
							t.addClass( 'current' );
						});
						ta.removeClass( 'disabled' );
						if ( t.is( ':last-child' ) ) {
							ta.filter( '.prev' ).hide();
						} else {
							ta.filter( '.prev' ).show();
						}
						if ( t.is( ':first-child' ) ) {
							ta.filter( '.next' ).hide();
						} else {
							ta.filter( '.next' ).show();
						}
					});
				}
			});
		}

	}


	/**
	 * Footer
	 */
	if ( ! pilau_vw_small && ! pilau_is_lt_ie9 ) {
		var fc_show = 'Show contact details';
		var fc_hide = 'Hide contact details';
		fmcb.hide();
		fc.prepend( '<li class="contact-control img-rep"><a href="#" title="' + fc_show + '" class="control">' + fc_show + '</a></li>' ).on( 'click', 'a.control', function( e ) {
			e.preventDefault();
			var el = $( this );
			if ( ! el.hasClass( 'disabled' ) ) {
				el.addClass( 'disabled' );
				fmcb.slideToggle( 500, function() {
					if ( el.hasClass( 'open' ) ) {
						el.removeClass( 'open' ).text( fc_show ).attr( 'title', fc_show );
					} else {
						el.addClass( 'open' ).text( fc_hide ).attr( 'title', fc_hide );
					}
				});
				el.removeClass( 'disabled' );
			}
		});
	}


});


/** Trigger when window resizes */
jQuery( window ).resize( function( $ ) {
	pilau_did_resize = true;
});
setInterval( function() {
	if ( pilau_did_resize ) {
		pilau_did_resize = false;

		// Revise viewport stuff
		closer_viewport_vars();

		// Adjust timelines
		closer_timelines_adjust();

		// Make sure menu is visible
		// if ( pilau_vw_side_nav ) {
		// 	closer_menu.show();
		// } else {
		// 	closer_menu.hide();
		// }

	}
}, 250 );


/** Trigger when window scrolls */
jQuery( window ).scroll( function( $ ) {
	pilau_did_scroll = true;
});
setInterval( function() {
	if ( pilau_did_scroll ) {
		pilau_did_scroll = false;
		pilau_scrolltop = jQuery( window ).scrollTop();

		// Fix footer to bottom of viewport?
		if ( ! pilau_vw_small && pilau_scrolltop > 400 ) {
			pilau_footer.addClass( 'fixed' );
			if ( pilau_vw_side_nav ) {
				pilau_footer.find( '#footer-main' ).css( 'width', ( pilau_vw - 200 ) + 'px' );
			}
		} else {
			pilau_footer.removeClass( 'fixed' );
			if ( pilau_vw_side_nav ) {
				pilau_footer.find( '#footer-main' ).css( 'width', 'auto' );
			}
		}

		// Track jump links sidebar
		if ( typeof( pilau_jls ) == 'object' ) {
			var jls_ul = pilau_jls.find( 'ul' );

			// Clear the current class
			jls_ul.find( 'li' ).removeClass( 'current' );

			// Find the first visible jump section
			jQuery( '.jump-section' ).each( function( i ) {
				var el = jQuery( this );
				var offset = el.offset();
				if ( pilau_scrolltop <= ( offset.top + 100 ) ) {
					jQuery( '#jls-' + i ).addClass( 'current' );
					return false;
				}
			});

		}

	}
}, 250 );


/*
 * Helper functions
 */


/**
 * Do any responsive adjustments for timelines
 * @returns void
 */
function closer_timelines_adjust() {
	if ( closer_timelines_multiple.length && ! pilau_vw_small ) {

		// Simple multiple timelines
		closer_timelines_multiple.filter( '.tl-simple' ).each( function() {
			jQuery( this ).find( '.tl-study' ).each( function() {
				var el = jQuery( this );
				var st = el.find( '.tl-study-title' );
				var pb = el.find( '.tl-periods-bar' );
				var sd = el.find( '.tl-study-details' );
				var sdo;

				// Adjust position of title?
				if ( pb.width() < st.outerWidth( true ) ) {
					st.css({
						'left': 	'auto',
						'right':	'.5em'
					});
				} else {
					st.css({
						'left':		st.data( 'left' ) + '%'
					})
				}

				/* Adjust position of pop-up details?
				sd.addClass( 'invisible' );
				sdo = sd.offset();
				if ( sdo.left + sd.outerWidth( true ) > pilau_vw ) {
					sd.addClass( 'inside' );
				} else {
					sd.removeClass( 'inside' );
				}
				sd.removeClass( 'invisible' );*/

			});
		});
	}
}

/**
 * For a list, calculate how many rows, height of each row
 *
 * @param	{object}	l	The list object
 * @return	{object}
 */
function pilau_list_dimensions( l ) {
	var d = {
		row_heights: []
	};
	var lis = l.children( 'li' );
	var r = 1; // row
	var llio; // last li offset
	var lih; // li height
	var rh; // row height (highest li)

	// Go through every li
	lis.each( function( i ) {
		var el = jQuery( this );
		var elo = el.offset();
		var lih = el.outerHeight( true );

		if ( typeof llio == 'undefined' ) {

			// First iteration
			rh = lih;

		} else if ( elo.top > llio.top ) {

			// This element is lower than the last one - we're on a new row
			r++;
			d.row_heights.push( rh );

		} else {

			// Pass li height through as new highest li in row?
			if ( lih > rh ) {
				rh = lih;
			}

		}

		// Pass this element's offset through
		llio = elo;

	});

	// And the last row...
	d.row_heights.push( rh );

	// Add total number of rows
	d.row_count = r;

	return d;
}


/**
 * Set viewport variables
 */
function closer_viewport_vars() {
	pilau_vw = jQuery( window ).width();
	pilau_vh = jQuery( window ).height();
	pilau_vw_large = ( pilau_vw >= pilau_bps.large );
	pilau_vw_side_nav = ( pilau_vw >= pilau_bps.side_nav );
	pilau_vw_medium = ( pilau_vw >= pilau_bps.medium && pilau_vw < pilau_bps.large );
	pilau_vw_small = ( pilau_vw < pilau_bps.medium );
}


/**
 * Make an overlay with content
 * @param	{string}	content_id
 * @return	void
 */
function pilau_do_overlay( content_id ) {

	// Create the overlay if it hasn't already been created
	if ( typeof pilau_do_overlay.overlay_created == 'undefined' ) {
		pilau_overlay = jQuery( '<div id="pilau-overlay" class="overlay"><a href="#" class="overlay-close"><img src="/wp-content/themes/closer-2022/www/images/icons/icon-close.svg"></a><div class="content"></div></div>' );
		pilau_body.append( pilau_overlay );
		pilau_do_overlay.overlay_created = true;
	}

	// Insert the content, overwriting previous content, then fade in
	pilau_overlay.find( '.content' ).html( jQuery( '#' + content_id ).html() );
	pilau_overlay.fadeIn( 500, function() {
		var el = jQuery( this );
		var c = el.find( '.content' );
		// Position vertically in centre
		c.css( 'margin-top', ( ( pilau_vh - c.height() ) / 2 ) + 'px' );
	});

}


/**
 * Place preloader image
 *
 * @param	{string}	e	Selector for element to place preloader in the centre of
 * @return	void
 */
function pilau_preloader_place( e ) {
	var size = 35,
		p = jQuery( '<img src="' + pilau_global.themeurl + '/img/preloader.gif" width="' + size + '" height="' + size + '" alt="" class="preloader">' ),
		t, l;

	if ( typeof e != 'undefined' ) {

		// Position within an element
		e = jQuery( e );

		// Make sure the container is positioned right
		if ( e.css( 'position' ) == 'static' )
			e.css( 'position', 'relative' );

		// Place the preloader
		p.appendTo( e );

	} else {

		// Position centered in viewport
		// Override default styles
		t = ( jQuery( window ).height() - size ) / 2;
		w = ( jQuery( window ).width() - size ) / 2;
		p.css({
			'top':			t,
			'left':			l,
			'margin-left':	0,
			'margin-right':	0
		}).appendTo( pilau_body );

	}

}


/**
 * Remove preloader image
 *
 * @param	{string}	e	Selector for element to remove preloader from
 * @return	void
 */
function pilau_preloader_remove( e ) {
	if ( typeof e == 'undefined' )
		e = pilau_body;
	jQuery( 'img.preloader', e ).remove();
}


/**
 * Generic AJAX error message
 *
 * Add to AJAX calls like this:
 * <code>
 * jQuery.get( pilau_global.ajax_url, { action: 'pilau_ajax_action' }, function( r ) { ... }).error( function( e ) { pilau_ajax_error( e ); });
 * </code>
 *
 * @since	CLOSER 0.1
 * @param	{object}	e	The error response
 * @return	void
 */
function pilau_ajax_error( e ) {
	alert( 'Sorry, there was a problem contacting the server.\n\nPlease try again!' );
}


/**
 * Fit videos
 *
 * @link http://toddmotto.com/fluid-and-responsive-youtube-and-vimeo-videos-with-fluidvids-js/
 */
(function ( window, document, undefined ) {

	/*
	 * Grab all iframes on the page or return
	 */
	var iframes = document.getElementsByTagName( 'iframe' );

	/*
	 * Loop through the iframes array
	 */
	for ( var i = 0; i < iframes.length; i++ ) {

		var iframe = iframes[i],

		/*
		 * RegExp, extend this if you need more players
		 */
			players = /www.youtube.com|player.vimeo.com/;

		/*
		 * If the RegExp pattern exists within the current iframe
		 */
		if ( iframe.src.search( players ) > 0 ) {

			/*
			 * Calculate the video ratio based on the iframe's w/h dimensions
			 */
			var videoRatio        = ( iframe.height / iframe.width ) * 100;

			/*
			 * Replace the iframe's dimensions and position
			 * the iframe absolute, this is the trick to emulate
			 * the video ratio
			 */
			iframe.style.position = 'absolute';
			iframe.style.top      = '0';
			iframe.style.left     = '0';
			iframe.width          = '100%';
			iframe.height         = '100%';

			/*
			 * Wrap the iframe in a new <div> which uses a
			 * dynamically fetched padding-top property based
			 * on the video's w/h dimensions
			 */


			var wrapin              = document.createElement( 'div' );
			wrapin.className        = 'fluid-vids-wrap';


			var wrap              = document.createElement( 'div' );
			wrap.className        = 'fluid-vids';
			wrap.style.width      = '100%';
			wrap.style.position   = 'relative';
			wrap.style.paddingTop = videoRatio + '%';


			/*
			 * Add the iframe inside our newly created <div>
			 */
			var iframeParent      = iframe.parentNode;
			iframeParent.insertBefore( wrapin, iframe);
			iframeParent.insertBefore( wrap, iframe );
			wrapin.appendChild( wrap );
			wrap.appendChild( iframe );

		}

	}

})( window, document );




jQuery(function($){
	 // $('#main-menu > li').click(function(e) {
  //       e.stopPropagation();
  //       var $el = $('ul',this);
  //       $('#main-menu > li > ul').not($el).slideUp();
  //       $('#menu_activator').not($el).slideUp();
  //       //$('html').not($el).slideUp();
  //       $el.stop(true, true).slideToggle(0);
  //       $('#menu_activator').stop(true, true).slideToggle(0);
  //   });

	 $('#menu_activator').click(function(e) {
	 	e.stopPropagation();
	 	var $el = $('ul',this);
	 	$('#main-menu > li > ul').not($el).slideUp();
	 	$('#menu_activator').not($el).slideUp();
	 });
    $('#main-menu > li > ul > li').click(function(e) {
        e.stopImmediatePropagation();  
        $('#menu_activator').not($el).slideUp();
    });


    $('.more_rec').click(function(){
 
        var button = $(this),
            data = {
            'action': 'loadmore',
            'query': NFH_loadmore_params.posts, // that's how we get params from wp_localize_script() function
            'page' : NFH_loadmore_params.current_page
        };
 
        $.ajax({
            url : NFH_loadmore_params.ajaxurl, // AJAX handler
            data : data,
            type : 'POST',
            beforeSend : function ( xhr ) {
                button.text('Loading More'); // change the button text, you can also add a preloader image
            },
            success : function( data ){
                if( data ) { 
                    button.text( 'Load more' ).prev().after(data); // insert new posts
                    NFH_loadmore_params.current_page++;
 
                    if ( NFH_loadmore_params.current_page == NFH_loadmore_params.max_page ) 
                        button.remove(); // if last page, remove the button
                } else {
                    button.remove(); // if no data, remove the button as well
                }
            }
        });
    });
});

jQuery( document ).ready( function( $ ) {

const readingSwiper = new Swiper('.carousel-reading-init .swiper', {
loop: true,
navigation: {
  nextEl: '.carousel-reading-init .swiper-button-next',
  prevEl: '.carousel-reading-init .swiper-button-prev',
},
});
const studiesSwiper = new Swiper('.carousel-relevant-studies-init .swiper', {
loop: true,
slidesPerView: 1,
  breakpoints: {
  1024: {
    slidesPerView: 2,
    spaceBetween: 30
  }
},
navigation: {
  nextEl: '.carousel-relevant-studies-init .swiper-button-next',
  prevEl: '.carousel-relevant-studies-init .swiper-button-prev',
},
});
const relatedSwiper = new Swiper('.carousel-related-init .swiper', {
loop: true,
slidesPerView: 1,
  breakpoints: {
  1024: {
    slidesPerView: 3,
    spaceBetween: 30
  }
},
navigation: {
  nextEl: '.carousel-related-init .swiper-button-next',
  prevEl: '.carousel-related-init .swiper-button-prev',
},
});
const related2Swiper = new Swiper('.carousel-related-init-2 .swiper', {
loop: true,
slidesPerView: 1,
  breakpoints: {
  1024: {
    slidesPerView: 2,
    spaceBetween: 30
  }
},
navigation: {
  nextEl: '.carousel-related-init-2 .swiper-button-next',
  prevEl: '.carousel-related-init-2 .swiper-button-prev',
},
});
const related2Swiper2 = new Swiper('.carousel-related-init-2-2 .swiper', {
  loop: true,
  slidesPerView: 1,
	  breakpoints: {
    640: {
      slidesPerView: 2,
      spaceBetween: 30
    }
  },
  navigation: {
    nextEl: '.carousel-related-init-2-2 .swiper-button-next',
    prevEl: '.carousel-related-init-2-2 .swiper-button-prev',
  },
});
jQuery(function() {
jQuery('.js-accordion').accordion({
   headersSelector: '> .js-accordion__header', // relative to panel
   panelsSelector: '> .js-accordion__panel', // relative to wrapper
   buttonsSelector: '> button.js-accordion__header' // relative to wrapper
});
});
});


/**
 * imagesLoaded PACKAGED v3.1.8
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 * @link https://github.com/desandro/imagesloaded
 */
(function(){function e(){}function t(e,t){for(var n=e.length;n--;)if(e[n].listener===t)return n;return-1}function n(e){return function(){return this[e].apply(this,arguments)}}var i=e.prototype,r=this,o=r.EventEmitter;i.getListeners=function(e){var t,n,i=this._getEvents();if("object"==typeof e){t={};for(n in i)i.hasOwnProperty(n)&&e.test(n)&&(t[n]=i[n])}else t=i[e]||(i[e]=[]);return t},i.flattenListeners=function(e){var t,n=[];for(t=0;e.length>t;t+=1)n.push(e[t].listener);return n},i.getListenersAsObject=function(e){var t,n=this.getListeners(e);return n instanceof Array&&(t={},t[e]=n),t||n},i.addListener=function(e,n){var i,r=this.getListenersAsObject(e),o="object"==typeof n;for(i in r)r.hasOwnProperty(i)&&-1===t(r[i],n)&&r[i].push(o?n:{listener:n,once:!1});return this},i.on=n("addListener"),i.addOnceListener=function(e,t){return this.addListener(e,{listener:t,once:!0})},i.once=n("addOnceListener"),i.defineEvent=function(e){return this.getListeners(e),this},i.defineEvents=function(e){for(var t=0;e.length>t;t+=1)this.defineEvent(e[t]);return this},i.removeListener=function(e,n){var i,r,o=this.getListenersAsObject(e);for(r in o)o.hasOwnProperty(r)&&(i=t(o[r],n),-1!==i&&o[r].splice(i,1));return this},i.off=n("removeListener"),i.addListeners=function(e,t){return this.manipulateListeners(!1,e,t)},i.removeListeners=function(e,t){return this.manipulateListeners(!0,e,t)},i.manipulateListeners=function(e,t,n){var i,r,o=e?this.removeListener:this.addListener,s=e?this.removeListeners:this.addListeners;if("object"!=typeof t||t instanceof RegExp)for(i=n.length;i--;)o.call(this,t,n[i]);else for(i in t)t.hasOwnProperty(i)&&(r=t[i])&&("function"==typeof r?o.call(this,i,r):s.call(this,i,r));return this},i.removeEvent=function(e){var t,n=typeof e,i=this._getEvents();if("string"===n)delete i[e];else if("object"===n)for(t in i)i.hasOwnProperty(t)&&e.test(t)&&delete i[t];else delete this._events;return this},i.removeAllListeners=n("removeEvent"),i.emitEvent=function(e,t){var n,i,r,o,s=this.getListenersAsObject(e);for(r in s)if(s.hasOwnProperty(r))for(i=s[r].length;i--;)n=s[r][i],n.once===!0&&this.removeListener(e,n.listener),o=n.listener.apply(this,t||[]),o===this._getOnceReturnValue()&&this.removeListener(e,n.listener);return this},i.trigger=n("emitEvent"),i.emit=function(e){var t=Array.prototype.slice.call(arguments,1);return this.emitEvent(e,t)},i.setOnceReturnValue=function(e){return this._onceReturnValue=e,this},i._getOnceReturnValue=function(){return this.hasOwnProperty("_onceReturnValue")?this._onceReturnValue:!0},i._getEvents=function(){return this._events||(this._events={})},e.noConflict=function(){return r.EventEmitter=o,e},"function"==typeof define&&define.amd?define("eventEmitter/EventEmitter",[],function(){return e}):"object"==typeof module&&module.exports?module.exports=e:this.EventEmitter=e}).call(this),function(e){function t(t){var n=e.event;return n.target=n.target||n.srcElement||t,n}var n=document.documentElement,i=function(){};n.addEventListener?i=function(e,t,n){e.addEventListener(t,n,!1)}:n.attachEvent&&(i=function(e,n,i){e[n+i]=i.handleEvent?function(){var n=t(e);i.handleEvent.call(i,n)}:function(){var n=t(e);i.call(e,n)},e.attachEvent("on"+n,e[n+i])});var r=function(){};n.removeEventListener?r=function(e,t,n){e.removeEventListener(t,n,!1)}:n.detachEvent&&(r=function(e,t,n){e.detachEvent("on"+t,e[t+n]);try{delete e[t+n]}catch(i){e[t+n]=void 0}});var o={bind:i,unbind:r};"function"==typeof define&&define.amd?define("eventie/eventie",o):e.eventie=o}(this),function(e,t){"function"==typeof define&&define.amd?define(["eventEmitter/EventEmitter","eventie/eventie"],function(n,i){return t(e,n,i)}):"object"==typeof exports?module.exports=t(e,require("wolfy87-eventemitter"),require("eventie")):e.imagesLoaded=t(e,e.EventEmitter,e.eventie)}(window,function(e,t,n){function i(e,t){for(var n in t)e[n]=t[n];return e}function r(e){return"[object Array]"===d.call(e)}function o(e){var t=[];if(r(e))t=e;else if("number"==typeof e.length)for(var n=0,i=e.length;i>n;n++)t.push(e[n]);else t.push(e);return t}function s(e,t,n){if(!(this instanceof s))return new s(e,t);"string"==typeof e&&(e=document.querySelectorAll(e)),this.elements=o(e),this.options=i({},this.options),"function"==typeof t?n=t:i(this.options,t),n&&this.on("always",n),this.getImages(),a&&(this.jqDeferred=new a.Deferred);var r=this;setTimeout(function(){r.check()})}function f(e){this.img=e}function c(e){this.src=e,v[e]=this}var a=e.jQuery,u=e.console,h=u!==void 0,d=Object.prototype.toString;s.prototype=new t,s.prototype.options={},s.prototype.getImages=function(){this.images=[];for(var e=0,t=this.elements.length;t>e;e++){var n=this.elements[e];"IMG"===n.nodeName&&this.addImage(n);var i=n.nodeType;if(i&&(1===i||9===i||11===i))for(var r=n.querySelectorAll("img"),o=0,s=r.length;s>o;o++){var f=r[o];this.addImage(f)}}},s.prototype.addImage=function(e){var t=new f(e);this.images.push(t)},s.prototype.check=function(){function e(e,r){return t.options.debug&&h&&u.log("confirm",e,r),t.progress(e),n++,n===i&&t.complete(),!0}var t=this,n=0,i=this.images.length;if(this.hasAnyBroken=!1,!i)return this.complete(),void 0;for(var r=0;i>r;r++){var o=this.images[r];o.on("confirm",e),o.check()}},s.prototype.progress=function(e){this.hasAnyBroken=this.hasAnyBroken||!e.isLoaded;var t=this;setTimeout(function(){t.emit("progress",t,e),t.jqDeferred&&t.jqDeferred.notify&&t.jqDeferred.notify(t,e)})},s.prototype.complete=function(){var e=this.hasAnyBroken?"fail":"done";this.isComplete=!0;var t=this;setTimeout(function(){if(t.emit(e,t),t.emit("always",t),t.jqDeferred){var n=t.hasAnyBroken?"reject":"resolve";t.jqDeferred[n](t)}})},a&&(a.fn.imagesLoaded=function(e,t){var n=new s(this,e,t);return n.jqDeferred.promise(a(this))}),f.prototype=new t,f.prototype.check=function(){var e=v[this.img.src]||new c(this.img.src);if(e.isConfirmed)return this.confirm(e.isLoaded,"cached was confirmed"),void 0;if(this.img.complete&&void 0!==this.img.naturalWidth)return this.confirm(0!==this.img.naturalWidth,"naturalWidth"),void 0;var t=this;e.on("confirm",function(e,n){return t.confirm(e.isLoaded,n),!0}),e.check()},f.prototype.confirm=function(e,t){this.isLoaded=e,this.emit("confirm",this,t)};var v={};return c.prototype=new t,c.prototype.check=function(){if(!this.isChecked){var e=new Image;n.bind(e,"load",this),n.bind(e,"error",this),e.src=this.src,this.isChecked=!0}},c.prototype.handleEvent=function(e){var t="on"+e.type;this[t]&&this[t](e)},c.prototype.onload=function(e){this.confirm(!0,"onload"),this.unbindProxyEvents(e)},c.prototype.onerror=function(e){this.confirm(!1,"onerror"),this.unbindProxyEvents(e)},c.prototype.confirm=function(e,t){this.isConfirmed=!0,this.isLoaded=e,this.emit("confirm",this,t)},c.prototype.unbindProxyEvents=function(e){n.unbind(e.target,"load",this),n.unbind(e.target,"error",this)},s});
