<?php

/**
 * Initial theme setup
 *
 * @package	CLOSER-2022
 * @since	0.1
 */


/**
 * Set up theme
 *
 * @since	CLOSER 0.1
 */
add_action( 'after_setup_theme', 'pilau_setup', 10 );
function pilau_setup() {
	global $pilau_site_settings, $closer_related_content_post_types, $closer_video_embeds, $closer_download_mime_types, $closer_citation_formats, $closer_foonote_refs;

	/* Enable shortcodes in widgets */
	add_filter( 'widget_text', 'do_shortcode' );

	/*
	 * Override core automatic feed links
	 * @see inc/feeds.php
	 */
	remove_theme_support( 'automatic-feed-links' );

	/* Featured image */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 160, 130, true ); // Listing thumbnail

	/* Set custom image sizes */
	add_image_size( 'featured-item-thumb', 250, 130, true );
	add_image_size( 'related-content-small', 210, 110, true );
	add_image_size( 'content-box', 170, 90, true );
	add_image_size( 'new-content-box', 333, 176, true );
	add_image_size( 'content-image', 640, 0, false ); // Designed to just limit width for a generic content image
	add_image_size( 'contextual-data', 430, 220, true );
	add_image_size( 'hero-small', 418, 275, true );
	add_image_size( 'home-hero-large', 1024, 500, true );
	// Overwrite default large size to get hard cropping
	add_image_size( 'large', 690, 337, true );
	add_image_size( 'medium', 450, 255, true );

	/*
	 * Post formats - may be useful for some blog-heavy projects
	 * @link http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );


	 

	/* Site settings */
	$pilau_site_settings = get_option( 'pilau_site_settings' );

	// Related content post types
	$closer_related_content_post_types = array( 'post', 'publication', 'evidence', 'impact', 'blog', 'news' );

	// Gather video embed codes to store hidden in footer, ready for overlays
	$closer_video_embeds = array();

	// These are the MIME types used to get media files for download
	$closer_download_mime_types = array(
		'application/pdf',
		'vnd.openxmlformats-officedocument.wordprocessingml.document',
		'application/msword',
	);

	// Citation formats
	$closer_citation_formats = array(
		__( 'Journal article' )		=> 'article',
		__( 'Book' )				=> 'book',
		__( 'Book chapter' )		=> 'chapter',
		__( 'Working paper' )		=> 'paper'
	);

	// Keep track of whether footnote refs are being output
	$closer_foonote_refs = false;

}


/**
 * Cookie notice handling
 *
 * @since	CLOSER 0.1
 * @todo	Implement more sophisticated cookie handling (JS?) to hide notice for users who have disabled cookies
 */
if ( PILAU_USE_COOKIE_NOTICE )
	add_action( 'init', 'pilau_cookie_notice' );
function pilau_cookie_notice() {

	// Check for this domain in referrer
	if ( parse_url( $_SERVER['HTTP_REFERER'], PHP_URL_HOST ) == $_SERVER['SERVER_NAME'] ) {

		// Set cookie showing (implied) consent
		// Expires in 10 years
		setcookie( 'pilau_cookie_notice', 1, time() + ( 10 * 365 * 24 * 60 * 60 ), '/' );

	}

}


/**
 * Manage core taxonomies
 *
 * @since	CLOSER 0.1
 * @link	http://w4dev.com/wp/remove-taxonomy/
 */
add_action( 'init', 'pilau_core_taxonomies' );
function pilau_core_taxonomies() {
	global $wp_taxonomies;

	/* Disable categories? */
	if ( taxonomy_exists( 'category' ) && ! PILAU_USE_CATEGORIES )
		unset( $wp_taxonomies['category'] );

	/* Disable tags? */
	if ( taxonomy_exists( 'post_tag' ) && ! PILAU_USE_TAGS )
		unset( $wp_taxonomies['post_tag'] );

}


/**
 * Rename "Posts" in post type object to "News"
 *
 * @since	CLOSER 0.1
 */
// add_action( 'init', 'pilau_change_post_object_label' );
// function pilau_change_post_object_label() {
// 	global $wp_post_types;
// 	$wp_post_types['post']->label = 'News &amp; opinion';
// 	$labels = &$wp_post_types['post']->labels;
// 	$labels->name = 'News &amp; opinion';
// 	$labels->singular_name = 'News &amp; opinion';
// 	$labels->add_new = 'Add News &amp; opinion';
// 	$labels->add_new_item = 'Add News &amp; opinion';
// 	$labels->edit_item = 'Edit News &amp; opinion';
// 	$labels->new_item = 'News &amp; opinion';
// 	$labels->view_item = 'View News &amp; opinion';
// 	$labels->search_items = 'Search News &amp; opinion';
// 	$labels->not_found = 'No News &amp; opinion found';
// 	$labels->not_found_in_trash = 'No News &amp; opinion found in Trash';
// 	$labels->all_items = 'All News &amp; opinion';
// 	$labels->menu_name = 'News &amp; opinion';
// 	$labels->name_admin_bar = 'News &amp; opinion';
// }


/**
 * Initialization
 *
 * @since	CLOSER 0.1
 */
add_action( 'init', 'closer_init' );
function closer_init() {
	global $closer_terms, $closer_filters, $closer_filters_applied;

	// Initialize filters
	$closer_filters = array();
	$closer_filters_applied = false;
	foreach ( get_taxonomies( array( '_builtin' => false ) ) as $taxonomy ) {
		if ( ! empty( $_GET[ $taxonomy ] ) ) {
			$closer_filters[ $taxonomy ] = is_array( $_GET[ $taxonomy ] ) ? $_GET[ $taxonomy ] : explode( '|', $_GET[ $taxonomy ] );
			$closer_filters_applied = true;
		} else {
			$closer_filters[ $taxonomy ] = array();
		}
	}
	$closer_filters['rel-study'] = isset( $_GET['rel-study'] ) && $_GET['rel-study'] ? array( $_GET['rel-study'] ) : array();
	$closer_filters['news-opinion'] = 'both';
	if ( ! isset( $_GET['news'] ) && isset( $_GET['opinion'] ) ) {
		$closer_filters['news-opinion'] = 'opinion';
		$closer_filters_applied = true;
	} else if ( ! isset( $_GET['opinion'] ) && isset( $_GET['news'] ) ) {
		$closer_filters['news-opinion'] = 'news';
		$closer_filters_applied = true;
	}

	// From - to date
	if ( ! empty( $_GET['mf'] ) || ! empty( $_GET['mt'] ) ) {
		$closer_filters_applied = true;
		$closer_filters['mf'] = ! empty( $_GET['mf'] ) ? $_GET['mf'] : null;
		$closer_filters['mt'] = ! empty( $_GET['mt'] ) ? $_GET['mt'] : null;

		// Swap dates if they're invalid
		if ( $closer_filters['mf'] && $closer_filters['mt'] && $closer_filters['mf'] > $closer_filters['mt'] ) {
			$temp = $closer_filters['mf'];
			$closer_filters['mf'] = $closer_filters['mt'];
			$closer_filters['mt'] = $temp;
		}

	}

	// This year-month filter is applied automatically by WP core, this is just for the filter output
	//$closer_filters['m'] = isset( $_GET['m'] ) && $_GET['m'] ? array( $_GET['m'] ) : array();

	// Get terms
	$closer_terms = closer_get_terms();

}


/**
 * Filtering for news / opinion & explore evidence
 *
 * This should be done better using standard WP query vars and the new Developer's Custom
 * Fields filtering / query var functionality. Not quite sure why it was originally done
 * like this, but it works for now...
 *
 * @since	CLOSER 0.1
 */



/**
 * Set up that needs to happen when $post object is ready
 *
 * @since	CLOSER 0.1
 */
add_action( 'template_redirect', 'pilau_setup_after_post' );
function pilau_setup_after_post() {
	global $pilau_custom_fields, $post, $pilau_post_ancestors, $closer_event_date;
	$pilau_custom_fields = array();

	/*
	 * Determine current page ID, if we're on a page
	 *
	 * This may not be $post->ID, if we're on the blog home page.
	 * Set to false if the current view isn't related to a singular post or page.
	 */
	$current_page_id = false;
	if ( is_home() && ! is_front_page() ) {
		$current_page_id = get_option( 'page_for_posts' );
	} else if ( is_singular() ) {
		$current_page_id = $post->ID;
	}
	define( 'PILAU_CURRENT_PAGE_ID', $current_page_id );
	$pilau_post_ancestors = get_post_ancestors( $post );

	/*
	 * Get all custom fields for current post
	 */
	if ( PILAU_CURRENT_PAGE_ID && function_exists( 'slt_cf_all_field_values' ) ) {
		// The array is fields with `single` set to false
		$pilau_custom_fields = slt_cf_all_field_values( 'post', $current_page_id, array(
			'related-evidence',
			'event-speakers',
			'study-funders',
			'evidence-studies',
			'related-contextual-data',
			'citation-evidence',
			'post-study'
		));
	}

	// De-activate removal of menu item IDs from Pilau Base
	//remove_filter( 'nav_menu_item_id', '__return_empty_array', 10000 );

	// Event date used in different places
	$closer_event_date = null;
	if ( is_singular( 'event' ) && function_exists( 'slt_se_get_date' ) ) {
		$closer_event_date = slt_se_get_date();
		// Add start time if present
		if ( $pilau_custom_fields['event-time-start'] ) {
			$time_parts = explode( ':', $pilau_custom_fields['event-time-start'] );
			$closer_event_date = mktime( $time_parts[0], $time_parts[1], 0, date( 'n', $closer_event_date ), date( 'j', $closer_event_date ), date( 'Y', $closer_event_date ) );
		}
	}

}


/**
 * Manage scripts for the front-end
 *
 * Always use the $ver parameter when registering or enqueuing styles or scripts, and
 * update it when deploying a new version - this helps prevent browser caching issues.
 * (Actually this is made redundant by using Better WordPress Minify, with its
 * appended parameter - but this is a good habit to get into ;-)
 *
 * The Modernizr script has to be included in the header, so in case pilau_scripts_to_footer()
 * is used to move scripts to the footer, Modernizr is hard-coded into header.php
 *
 * @since	CLOSER 0.1
 */
add_action( 'wp_enqueue_scripts', 'pilau_enqueue_scripts', 10 );
function pilau_enqueue_scripts() {
	// This test is done here because applying the test to the hook breaks due to pilau_is_login_page() not being defined yet...
	if ( ! is_admin() && ! pilau_is_login_page() ) {

		/*
		 * Note: All scripts are set to enqueue in footer, but jQuery would require some de-registering
		 * and re-registering trickery to get that in the footer safely. For now, Better WordPress Minify
		 * is used to manage putting scripts in the footer.
		 */
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'swiper-js', get_stylesheet_directory_uri() . '/www/js/swiper-bundle.min.js', array(), '1.2', true );
		wp_enqueue_script( 'masonry', get_stylesheet_directory_uri() . '/www/js/masonry.pkgd.min.js', array(), '1.2', true );
		wp_enqueue_script( 'imagesloaded', get_stylesheet_directory_uri() . '/www/js/imagesloaded.pkgd.min.js', array(), '1.2', true );
		wp_enqueue_script( 'pilau-global', get_stylesheet_directory_uri() . '/js/global.js', array( 'jquery' ), '1.2', true );
		wp_enqueue_script( 'all', get_stylesheet_directory_uri() . '/www/js/all.min.js', array( 'jquery' ), '1.2', true );

		if ( is_front_page() ) {
			wp_enqueue_script( 'flickity', get_stylesheet_directory_uri() . '/js/flickity.js', array(), '1.0.0', true );
		}

		/*
		 * Comment reply script - adjust the conditional if you need comments on post types other than 'post'
		 */
		if ( defined( 'PILAU_USE_COMMENTS' ) && PILAU_USE_COMMENTS && is_single() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply', false, array(), false, true );
		}

		/*
		 * Use this to pass the AJAX URL to the client when using AJAX
		 * @link	http://wp.smashingmagazine.com/2011/10/18/how-to-use-ajax-in-wordpress/
		 */
		wp_localize_script( 'pilau-global', 'pilau_global', array( 'ajaxurl' => admin_url( 'admin-ajax.php', PILAU_REQUEST_PROTOCOL ) ) );

	}
}


/**
 * Manage styles for the front-end
 *
 * Always use the $ver parameter when registering or enqueuing styles or scripts, and
 * update it when deploying a new version - this helps prevent browser caching issues.
 * (Actually this is made redundant by using Better WordPress Minify, with its
 * appended parameter - but this is a good habit to get into ;-)
 *
 * @since	CLOSER 0.1
 */
add_action( 'wp_enqueue_scripts', 'pilau_enqueue_styles', 10 );
function pilau_enqueue_styles() {
	// This test is done here because applying the test to the hook breaks due to pilau_is_login_page() not being defined yet...
	if ( ! is_admin() && ! pilau_is_login_page() ) {
		global $wp_styles; // In case we need IE-only styles with conditional wrapper

		if ( is_front_page() ) {
			wp_enqueue_style( 'flickity', get_stylesheet_directory_uri() . '/styles/flickity.css', array(), '1.0.0' );
		}
		wp_enqueue_style( 'swiper-css', get_stylesheet_directory_uri() . '/www/css/swiper-bundle.min.css', array( 'html5-reset', 'wp-core', 'pilau-classes' ), '1.4.2' );
		wp_enqueue_style( 'pilau-main', get_stylesheet_directory_uri() . '/www/css/styles.css', array( 'html5-reset', 'wp-core', 'pilau-classes' ), '1.4.2' );
		wp_enqueue_style( 'pilau-print', get_stylesheet_directory_uri() . '/styles/print.css', array( 'html5-reset', 'wp-core', 'pilau-classes' ), '1.1' );

		// IE-only styles
		// BEWARE: When using Better WordPress Minify plugin, these appear before the other CSS files in the header
		//wp_enqueue_style( 'pilau-ie', get_stylesheet_directory_uri() . '/styles/ie.css', array( 'html5-reset', 'wp-core', 'pilau-classes' ), '1.0' );
		//$wp_styles->add_data( 'pilau-ie', 'conditional', 'lt IE 9' );

	}
}


/**
 * Login styles and scripts
 *
 * @since	CLOSER 0.1
 */
//add_action( 'login_head', 'pilau_login_styles_scripts', 10000 );
function pilau_login_styles_scripts() { ?>
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() . '/styles/wp-login.css'; ?>">
<?php }

