/**
 * WP admin scripts
 */


/* Trigger when DOM has loaded */
jQuery( document ).ready( function( $ ) {
	var body = $( 'body' );
	var theme_desc = 'Enter a short description, followed by a blank line, followed by a long description.';
	var pfs = $( '.pilau-file-selector' );

	// Extra description for theme description
	if ( body.hasClass( 'taxonomy-theme' ) ) {
		$( '#tag-description, #description' ).siblings( 'p,.description' ).text( theme_desc );
	}

	// File selector
	if ( pfs.length ) {
		pfs.on( 'click', 'input[type="button"]', function() {
			var f = $( this ).siblings( '.pfs-files' );
			var u = $( this ).siblings( '.pfs-url' );
			u.val( f.val() );
		});
	}

});
