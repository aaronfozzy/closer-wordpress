<?php


/**
 * Configuration and functions
 *
 * As part of a child theme, this functions.php file will be loaded BEFORE the functions.php
 * file of the parent theme (Pilau Base). Any functions or constants defined in the parent
 * can be overridden in the child.
 * @link	http://codex.wordpress.org/Child_Themes#Using_functions.php
 *
 * @package	CLOSER-2022
 * @since	0.1
 *
 */


if (function_exists('add_theme_support')) {
	add_theme_support( 'menus' );

	add_theme_support ( 'themes.php' );
	add_theme_support ( 'plugins.php' );
}

function blogRegister(){
        
    $singular = 'Blog';
    $plural = 'Blogs';
    $posttype = 'blog';
    $labels = array(
        'name' => _x($plural, 'post type general name'),
        'singular_name' => _x($singular, 'post type singular name'),
        'add_new' => _x('Add New', strtolower($singular)),
        'add_new_item' => __('Add New '.$singular),
        'edit_item' => __('Edit '.$singular),
        'new_item' => __('New '.$singular),
        'view_item' => __('View '.$singular),
        'search_items' => __('Search '.$plural),
        'not_found' => __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'rewrite' => true,
        'show_in_nav_menus' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-star-filled'
    );
    
    register_post_type($posttype , $args);
    
}
add_action('init', 'blogRegister');
    // add custom taxonomy - recipes_type
    function blog_theme_taxonomies() {
        
        $singular = 'Theme';
        $plural = 'Themes';
        $taxterm = 'blog_theme';
        register_taxonomy($taxterm, 'blog', array(
            'hierarchical' => true,
            'labels' => array(
                'name' => _x( $plural, 'taxonomy general name' ),
                'singular_name' => _x( $singular, 'taxonomy singular name' ),
                'search_items' =>  __( 'Search '.$plural ),
                'all_items' => __( 'All '.$plural ),
                'parent_item' => __( 'Parent '.$singular ),
                'parent_item_colon' => __( 'Parent '.$singular.':' ),
                'edit_item' => __( 'Edit '.$singular ),
                'update_item' => __( 'Update '.$singular ),
                'add_new_item' => __( 'Add New '.$singular ),
                'new_item_name' => __( 'New '.$singular.' Name' ),
                'menu_name' => __( $plural ),
            ),
            'rewrite' => array(
                'slug' => $taxterm,
                'with_front' => true,
                'hierarchical' => true
            ),
        ));
    }
    add_action( 'init', 'blog_theme_taxonomies', 0 );

    function blog_subject_taxonomies() {
        
        $singular = 'Subject';
        $plural = 'Subjects';
        $taxterm = 'blog_subject';
        register_taxonomy($taxterm, 'blog', array(
            'hierarchical' => true,
            'labels' => array(
                'name' => _x( $plural, 'taxonomy general name' ),
                'singular_name' => _x( $singular, 'taxonomy singular name' ),
                'search_items' =>  __( 'Search '.$plural ),
                'all_items' => __( 'All '.$plural ),
                'parent_item' => __( 'Parent '.$singular ),
                'parent_item_colon' => __( 'Parent '.$singular.':' ),
                'edit_item' => __( 'Edit '.$singular ),
                'update_item' => __( 'Update '.$singular ),
                'add_new_item' => __( 'Add New '.$singular ),
                'new_item_name' => __( 'New '.$singular.' Name' ),
                'menu_name' => __( $plural ),
            ),
            'rewrite' => array(
                'slug' => $taxterm,
                'with_front' => true,
                'hierarchical' => true
            ),
        ));
    }
    add_action( 'init', 'blog_subject_taxonomies', 0 );

    function blog_areas_of_work_taxonomies() {
        
    $singular = 'Area of Work';
    $plural = 'Areas of Work';
    $taxterm = 'blog_areas_of_work';
    register_taxonomy($taxterm, 'blog', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => _x( $plural, 'taxonomy general name' ),
            'singular_name' => _x( $singular, 'taxonomy singular name' ),
            'search_items' =>  __( 'Search '.$plural ),
            'all_items' => __( 'All '.$plural ),
            'parent_item' => __( 'Parent '.$singular ),
            'parent_item_colon' => __( 'Parent '.$singular.':' ),
            'edit_item' => __( 'Edit '.$singular ),
            'update_item' => __( 'Update '.$singular ),
            'add_new_item' => __( 'Add New '.$singular ),
            'new_item_name' => __( 'New '.$singular.' Name' ),
            'menu_name' => __( $plural ),
        ),
        'rewrite' => array(
            'slug' => $taxterm,
            'with_front' => true,
            'hierarchical' => true
        ),
    ));
}
add_action( 'init', 'blog_areas_of_work_taxonomies', 0 );


    function newsRegister(){
        
    $singular = 'News';
    $plural = 'News';
    $posttype = 'news';
    $labels = array(
        'name' => _x($plural, 'post type general name'),
        'singular_name' => _x($singular, 'post type singular name'),
        'add_new' => _x('Add New', strtolower($singular)),
        'add_new_item' => __('Add New '.$singular),
        'edit_item' => __('Edit '.$singular),
        'new_item' => __('New '.$singular),
        'view_item' => __('View '.$singular),
        'search_items' => __('Search '.$plural),
        'not_found' => __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'rewrite' => true,
        'show_in_nav_menus' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-star-filled'
    );
    
    register_post_type($posttype , $args);
    
}
add_action('init', 'newsRegister');
    // add custom taxonomy - recipes_type
    function news_theme_taxonomies() {
        
        $singular = 'Theme';
        $plural = 'Themes';
        $taxterm = 'news_theme';
        register_taxonomy($taxterm, 'news', array(
            'hierarchical' => true,
            'labels' => array(
                'name' => _x( $plural, 'taxonomy general name' ),
                'singular_name' => _x( $singular, 'taxonomy singular name' ),
                'search_items' =>  __( 'Search '.$plural ),
                'all_items' => __( 'All '.$plural ),
                'parent_item' => __( 'Parent '.$singular ),
                'parent_item_colon' => __( 'Parent '.$singular.':' ),
                'edit_item' => __( 'Edit '.$singular ),
                'update_item' => __( 'Update '.$singular ),
                'add_new_item' => __( 'Add New '.$singular ),
                'new_item_name' => __( 'New '.$singular.' Name' ),
                'menu_name' => __( $plural ),
            ),
            'rewrite' => array(
                'slug' => $taxterm,
                'with_front' => true,
                'hierarchical' => true
            ),
        ));
    }
    add_action( 'init', 'news_theme_taxonomies', 0 );

    function news_subject_taxonomies() {
        
        $singular = 'Subject';
        $plural = 'Subjects';
        $taxterm = 'news_subject';
        register_taxonomy($taxterm, 'news', array(
            'hierarchical' => true,
            'labels' => array(
                'name' => _x( $plural, 'taxonomy general name' ),
                'singular_name' => _x( $singular, 'taxonomy singular name' ),
                'search_items' =>  __( 'Search '.$plural ),
                'all_items' => __( 'All '.$plural ),
                'parent_item' => __( 'Parent '.$singular ),
                'parent_item_colon' => __( 'Parent '.$singular.':' ),
                'edit_item' => __( 'Edit '.$singular ),
                'update_item' => __( 'Update '.$singular ),
                'add_new_item' => __( 'Add New '.$singular ),
                'new_item_name' => __( 'New '.$singular.' Name' ),
                'menu_name' => __( $plural ),
            ),
            'rewrite' => array(
                'slug' => $taxterm,
                'with_front' => true,
                'hierarchical' => true
            ),
        ));
    }
    add_action( 'init', 'news_subject_taxonomies', 0 );

    function news_areas_of_work_taxonomies() {
        
    $singular = 'Area of Work';
    $plural = 'Areas of Work';
    $taxterm = 'areas_of_work';
    register_taxonomy($taxterm, 'news', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => _x( $plural, 'taxonomy general name' ),
            'singular_name' => _x( $singular, 'taxonomy singular name' ),
            'search_items' =>  __( 'Search '.$plural ),
            'all_items' => __( 'All '.$plural ),
            'parent_item' => __( 'Parent '.$singular ),
            'parent_item_colon' => __( 'Parent '.$singular.':' ),
            'edit_item' => __( 'Edit '.$singular ),
            'update_item' => __( 'Update '.$singular ),
            'add_new_item' => __( 'Add New '.$singular ),
            'new_item_name' => __( 'New '.$singular.' Name' ),
            'menu_name' => __( $plural ),
        ),
        'rewrite' => array(
            'slug' => $taxterm,
            'with_front' => true,
            'hierarchical' => true
        ),
    ));
}
add_action( 'init', 'news_areas_of_work_taxonomies', 0 );


$current_user = wp_get_current_user();
if($current_user->user_login != 'ninjasforhire') {

add_action('admin_menu', 'remove_menus', 102);
    function remove_menus() {
        
        global $submenu;
  
        remove_menu_page ( 'edit.php' );
        
    }
}


/* Configurable constants */

/**
 * Global flag for activating comments
 *
 * @since	CLOSER 0.1
 */
define( 'PILAU_USE_COMMENTS', true );

/**
 * Global flag for activating links
 *
 * @since	CLOSER 0.1
 */
define( 'PILAU_USE_LINKS', false );

/**
 * Global flag for activating categories
 *
 * @since	CLOSER 0.1
 */
define( 'PILAU_USE_CATEGORIES', false );

/**
 * Global flag for activating tags
 *
 * @since	CLOSER 0.1
 */
define( 'PILAU_USE_TAGS', false );

/**
 * Ignore updates for inactive plugins?
 *
 * @since	CLOSER 0.1
 */
if ( ! defined( 'PILAU_IGNORE_UPDATES_FOR_INACTIVE_PLUGINS' ) ) {
	define( 'PILAU_IGNORE_UPDATES_FOR_INACTIVE_PLUGINS', true );
}

/**
 * Use the Pilau plugins page? (unfinished)
 *
 * @since	CLOSER 0.1
 */
define( 'PILAU_USE_PLUGINS_PAGE', false );

/**
 * Include the Pilau settings script? (unfinished)
 *
 * @since	CLOSER 0.1
 */
define( 'PILAU_USE_SETTINGS_SCRIPT', false );

/**
 * Use the cookie notice?
 *
 * @since	CLOSER 0.1
 */
define( 'PILAU_USE_COOKIE_NOTICE', false );

/**
 * Picturefill for responsive image sizes?
 *
 * @since	CLOSER 0.1
 */
define( 'PILAU_USE_PICTUREFILL', true );

/**
 * Twitter screen name
 *
 * @since	CLOSER 0.1
 */
define( 'PILAU_TWITTER_SCREEN_NAME', 'CLOSER_UK' );

/**
 * Maximum length of slugs in words
 *
 * @since	CLOSER 0.1
 */
define( 'PILAU_SLUG_LENGTH', 8 );

/**
 * News page ID
 *
 * @since	CLOSER 0.1
 */
define( 'CLOSER_POST_PAGE_ID', get_option( 'page_for_posts' ) );

/**
 * Number of slides for the home page carousel
 *
 * @since	CLOSER 0.1
 */
define( 'CLOSER_HOME_CAROUSEL_NUM_SLIDES', 6 );

/**
 * Number of team members for studies
 *
 * @since	CLOSER 0.1
 */
define( 'CLOSER_STUDY_NUM_TEAM_MEMBERS', 4 );

/**
 * IDs of pages with pre-designed icons
 *
 * @since	CLOSER 0.1
 */
define( 'CLOSER_PAGES_WITH_ICONS', '7,21,22,24,27,28,29,30,31,32,34,132' );


/*
 * Constants not intended for configuration
 *
 * These are defined here, because this functions.php is loaded before the parent's
 * functions.php. Even though they are not intended to be changed, and rightfully live
 * in the parent theme (where they are defined as a fall-back), they may get used before
 * the parent's functions.php is loaded.
 */

/**
 * Flag for requests from front, or AJAX - is_admin() returns true for AJAX
 * because the AJAX script is in /wp-admin/
 *
 * @since	CLOSER 0.1
 */
if ( ! defined( 'PILAU_FRONT_OR_AJAX' ) ) {
	define( 'PILAU_FRONT_OR_AJAX', ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) );
}

/**
 * Store the protocol of the current request
 *
 * @since	CLOSER 0.1
 */
if ( ! defined( 'PILAU_REQUEST_PROTOCOL' ) ) {
	define( 'PILAU_REQUEST_PROTOCOL', isset( $_SERVER[ 'HTTPS' ] ) ? 'https' : 'http' );
}

/**
 * Store the top-level slug
 *
 * @since	CLOSER 0.1
 */
if ( ! defined( 'PILAU_TOP_LEVEL_SLUG' ) ) {
	$pilau_top_level_slug = explode( '/', trim( $_SERVER['REQUEST_URI'], '/' ) );
	define( 'PILAU_TOP_LEVEL_SLUG', reset( $pilau_top_level_slug ) );
}

/**
 * Placeholder GIF URL (used for deferred loading of images)
 *
 * @since	CLOSER 0.1
 */
if ( ! defined( 'PILAU_PLACEHOLDER_GIF_URL' ) ) {
	define( 'PILAU_PLACEHOLDER_GIF_URL', get_template_directory_uri() . '/img/placeholder.gif' );
}


/**
 * PHP settings
 */

/** Default timezone */
date_default_timezone_set( 'Europe/London' );


/**
 * Set up theme
 *
 * - Set up theme features, nav menus
 * - $post-based initialization
 * - Enqueue scripts and styles for front-end and login
 *
 * @since	CLOSER 0.1
 */
require( dirname( __FILE__ ) . '/inc/setup.php' );

/**
 * Functions library
 *
 * @since	CLOSER 0.1
 */
require( dirname( __FILE__ ) . '/inc/lib.php' );

/**
 * AJAX functionality
 *
 * @since	CLOSER 0.1
 */
if ( PILAU_FRONT_OR_AJAX ) {
	require( dirname( __FILE__ ) . '/inc/ajax.php' );
}

/**
 * Header modifications
 *
 * - Clean up core stuff
 * - HTML title
 * - Meta tags
 * - body_class (inactive)
 *
 * @since	CLOSER 0.1
 */
require( dirname( __FILE__ ) . '/inc/header.php');

/**
 * Media functionality
 *
 * @since	CLOSER 0.1
 */
require( dirname( __FILE__ ) . '/inc/media.php');

/**
 * Timelines functionality
 *
 * @since	CLOSER 0.1
 */
require( dirname( __FILE__ ) . '/inc/timelines.php');

/**
 * Custom management of feeds
 *
 * @since	CLOSER 0.1
 */
require( dirname( __FILE__ ) . '/inc/feeds.php');

/**
 * Custom post types
 *
 * @since	CLOSER 0.1
 */
require( dirname( __FILE__ ) . '/inc/custom-post-types.php' );

/**
 * Custom taxonomies
 *
 * @since	CLOSER 0.1
 */
require( dirname( __FILE__ ) . '/inc/custom-taxonomies.php' );

/**
 * Custom meta fields
 *
 * Depends on Developer's Custom Fields plugin
 *
 * @since	CLOSER 0.1
 */
require( dirname( __FILE__ ) . '/inc/custom-fields.php' );

/**
 * Shortcodes
 *
 * @since	CLOSER 0.1
 */
require( dirname( __FILE__ ) . '/inc/shortcodes.php' );

/**
 * Widgets and sidebars
 *
 * @since	CLOSER 0.1
 */
require( dirname( __FILE__ ) . '/inc/widgets.php' );

/**
 * WordPress toolbar customization (formerly admin bar)
 *
 * @since	CLOSER 0.1
 */
require( dirname( __FILE__ ) . '/inc/wp-toolbar.php' );

/**
 * Admin stuff
 *
 * All other admin-*.php files are included within admin.php
 *
 * @since	CLOSER 0.1
 */
if ( ! PILAU_FRONT_OR_AJAX ) {
	require( dirname( __FILE__ ) . '/inc/admin.php' );
}

if( function_exists('acf_add_options_page') ) {
	acf_add_options_page();
}
add_filter('nav_menu_css_class', 'add_active_class', 10, 2 );

function add_active_class($classes, $item) {

  if( $item->menu_item_parent == 0 && 
    in_array( 'current-menu-item', $classes ) ||
    in_array( 'current-menu-ancestor', $classes ) ||
    in_array( 'current-menu-parent', $classes ) ||
    in_array( 'current_page_parent', $classes ) ||
    in_array( 'current_page_ancestor', $classes )
    ) {

    $classes[] = "active";
  }

  return $classes;
}

add_filter('wp_nav_menu_args', 'add_filter_to_menus');
function add_filter_to_menus($args) {

    // You can test agasint things like $args['menu'], $args['menu_id'] or $args['theme_location']
    if( $args['theme_location'] == 'header-menu') {
        add_filter( 'wp_setup_nav_menu_item', 'filter_menu_items' );
    }

    return $args;
}


function cs_header_nav() {
  register_nav_menu('header_nav',__( 'Header nav' ));
}
add_action( 'init', 'cs_header_nav' );

// Filter menu
function filter_menu_items($item) {

        // Get post and image ID
        $post_id = url_to_postid( $item->url );
        $term = get_queried_object();
    
        // Make the title just be the featured image.
        //$item->title;
        if (get_field('sub_nav_icon', $post_id)) {
        	$menu_image = "<img src=" . get_field('sub_nav_icon', $post_id) .">";
        }
    

    return $item;
}
// class Menu_With_Description extends Walker_Nav_Menu {
//     function start_el(&$output, $item, $depth, $args) {
//         global $wp_query;
//         $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
         
//         $class_names = $value = '';
 
//         $classes = empty( $item->classes ) ? array() : (array) $item->classes;
 
//         $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
//         $class_names = ' class="' . esc_attr( $class_names ) . '"';
 
//         $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
 
//         $attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) .'"' : '';
//         $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) .'"' : '';
//         $attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) .'"' : '';
//         $attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) .'"' : '';
 
//         $item_output = $args->before;
//         $item_output .= '<a'. $attributes .'>';
//         $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
//         $item_output .= '<br /><span class="sub">' . $item->description . '</span>';
//         $item_output .= '</a>';
//         $item_output .= $args->after;
 
//         $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
//     }
// }
add_filter('frm_replace_shortcodes', 'frm_change_my_html', 10, 3);
function frm_change_my_html($html, $field, $args){
    if ( in_array ( $field['type'], array( 'radio', 'checkbox' ) ) ) {
        $temp_array = explode('/>', $html);
        $new_html = '';
        foreach ( $temp_array as $key => $piece ) {
            // Get current for attribute
            if ( ( $pos = strpos( $piece, 'field_' . $field['field_key'] . '-' ) ) !== FALSE ) { 
                $new_key = substr( $piece, $pos );
                $key_parts = explode( '"', $new_key, 2);
                $new_key = reset( $key_parts );
            } else {
                $new_html .= $piece;
                continue;
            }
            // Move opening label tag
            $label = '<label for="' . $new_key . '">';
            $new_html .= str_replace( $label, '', $piece );
            $new_html .= '/>' . $label;
        }
      $html = $new_html;
    }
  return $html;
}

add_filter('frm_validate_field_entry', 'your_custom_validation', 20, 3);
 function your_custom_validation($errors, $field, $value){
   if ($field->id == 72){ //change 31 to the ID of the confirmation field (second field)
    $first_value = $_POST['item_meta'][71]; //change 30 to the ID of the first field
   
    if ( $first_value != $value && !empty($value) ) {
      $errors['field'. $field->id] = 'The emails you have provided do not match. Please re-enter them.';//Customize your error message
    }else{
      $_POST['item_meta'][$field->id] = ''; //if it matches, this clears the second field so it won't be saved
    }
 }
 return $errors;
 }


function clean_array($arr, $valid = null) {
            
    $return = array();
    
    foreach ( $arr as $key => $value ) {
        if ( !is_null($valid) && is_array($valid) && !in_array($key, $valid) ) {
            continue;
        }
        $return[$key] = addslashes(trim(strip_tags(stripslashes($value))));
    }
    return $return;

}

 // custom search results - Blog
function template_chooser_post($template) {
    global $wp_query;
    $post_type = get_query_var('post_type');
    if( $wp_query->is_search && $post_type == 'blog' ) {
        return locate_template('search-blog.php');
    }
    return $template;
}
add_filter('template_include', 'template_chooser_post');

 // custom search results - Blog
function template_chooser_news($template) {
    global $wp_query;
    $post_type = get_query_var('post_type');
    if( $wp_query->is_search && $post_type == 'news' ) {
        return locate_template('search-news.php');
    }
    return $template;
}
add_filter('template_include', 'template_chooser_news');


function NFH_my_load_more_scripts() {
 
    global $wp_query; 
 
    // In most cases it is already included on the page and this line can be removed
    wp_enqueue_script('jquery');
 
    // register our main script but do not enqueue it yet
    wp_register_script( 'my_loadmore', get_stylesheet_directory_uri() . '/js/loadmore.js', array('jquery') );
 
    // now the most interesting part
    // we have to pass parameters to myloadmore.js script but we can get the parameters values only in PHP
    // you can define variables directly in your HTML but I decided that the most proper way is wp_localize_script()
    wp_localize_script( 'my_loadmore', 'NFH_loadmore_params', array(
        'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
        'posts' => serialize( $wp_query->query_vars ), // everything about your loop is here
        'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
        'max_page' => $wp_query->max_num_pages
    ) );
 
    wp_enqueue_script( 'my_loadmore' );
}
 
add_action( 'wp_enqueue_scripts', 'NFH_my_load_more_scripts' );

function NFH_loadmore_ajax_handler(){
 
    // prepare our arguments for the query
    $args = unserialize( stripslashes( $_POST['query'] ) );
    $args['paged'] = $_POST['page'] + 1; // we need next page to be loaded
    $args['post_status'] = 'publish';
 
    // it is always better to use WP_Query but not here
    query_posts( $args );
 
    if( have_posts() ) :
 
        // run the loop
        while( have_posts() ): the_post();
            ?> 

            <?php 
                $post_type = get_post_type();

                if ( $post_type == 'blog' ) {
                    $post_id = get_the_ID();
                    $area_terms = get_the_terms( $post_id, 'blog_areas_of_work' );
                    if (empty($area_terms)) {
                      $area_terms = 'Blog';
                    }
                }

                if ( $post_type == 'news' ) {
                    $post_id = get_the_ID();
                    $area_terms = get_the_terms( $post_id, 'areas_of_work' );
                    if (empty($area_terms)) {
                        $area_terms = 'News';
                    }
                }

                if($area_terms !== 'Blog' and $area_terms !== 'News'){
                    $area_terms = $area_terms[0]->name;
                }

            ?>
                <div class="teaser-news radius-big boxed boxed-small with-label flex-mob">
                    <div class="float-label float-label-left">
                        <?php echo $area_terms; ?>
                    </div>
                    <div class="float-label float-label-right icon calendar">
                        <?php echo get_the_date( 'd F Y' ); ?>
                    </div>
                    <div class="image-wrap">
                        <?php the_post_thumbnail('thumbnail'); ?>
                    </div>
                    <div class="teaser-content">
                        <h3><?php the_title(); ?></h3>
                        <?php the_excerpt(); ?>
                        <a href="<?php the_permalink(); ?>" class="linked arrow">Read more</a>
                    </div>
                </div>
            <?php
        endwhile;
 
    endif;
    die; // here we exit the script and even no wp_reset_query() required!
}

function searchform( $form ) {
   
    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    <div><label class="screen-reader-text" for="s">' . __('Search for:') . '</label>
    <input type="text" value="' . get_search_query() . '" name="s" id="s" />
    <input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
    </div>
    </form>';
   
    return $form;
}
 
add_action('wp_ajax_loadmore', 'NFH_loadmore_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_loadmore', 'NFH_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}

function register_my_menu() {
  register_nav_menu('header-menu',__( 'Header Menu' ));
  register_nav_menu('footer-menu',__( 'Footer Menu' ));
}
add_action( 'init', 'register_my_menu' );