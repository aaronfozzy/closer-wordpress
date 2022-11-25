<?php

/**
 * Admin interface customization
 *
 * @package	CLOSER-2022
 * @since	0.1
 */


/**
 * Admin interface initialization
 *
 * @since	CLOSER 0.1
 */
add_action( 'admin_init', 'pilau_admin_interface_init' );
function pilau_admin_interface_init() {

	/* Enable HTML markup in user profiles */
	//remove_filter( 'pre_user_description', 'wp_filter_kses' );

	/* Disable captions */
	//add_filter( 'disable_captions', '__return_true' );

	/* Disable post format UI
	 * @link	http://wordpress.org/extend/plugins/disable-post-format-ui/
	 */
	add_filter( 'enable_post_format_ui', '__return_false' );

	/* Set up inline hints for image sizes */
	add_filter( 'admin_post_thumbnail_html', 'pilau_inline_image_size_featured', 10, 2 );

	/* Customize list columns
	 *
	 * For the most part these should be handled by the Codepress Admin Columns plugin.
	 * Include any necessary overrides here.
	 */
	add_filter( 'manage_edit-post_columns', 'pilau_admin_columns', 100000, 1 );
	add_filter( 'manage_edit-page_columns', 'pilau_admin_columns', 100000, 1 );
	foreach ( get_post_types( array( 'public' => true ), 'names' ) as $pt ) {
		add_filter( 'manage_' . $pt . '_posts_columns', 'pilau_admin_columns', 100000, 1 );
	}

}


/**
 * Admin scripts and styles
 *
 * @since	CLOSER 0.1
 */
add_action( 'admin_enqueue_scripts', 'pilau_admin_enqueue_scripts_styles', 10 );
function pilau_admin_enqueue_scripts_styles() {

	wp_enqueue_style( 'pilau-admin-css', get_stylesheet_directory_uri() . '/styles/wp-admin.css', array(), '1.0' );
	wp_enqueue_script( 'pilau-admin-js', get_stylesheet_directory_uri() . '/js/wp-admin.js', array( 'jquery' ), '1.0' );

}


/**
 * Admin notices
 *
 * @since	CLOSER 0.1
 */
//add_action( 'admin_notices', 'pilau_admin_notices', 10 );
function pilau_admin_notices() {

}

/**
 * Test output of $current_screen
 *
 * @since	CLOSER 0.1
 */
//add_action( 'admin_notices', 'pilau_check_current_screen' );
function pilau_check_current_screen() {
	if ( ! is_admin() || ! current_user_can( 'update_core' ) ) {
		return;
	}
	global $current_screen;
	echo '<pre>'; print_r( $current_screen ); echo '</pre>';
}


/**
 * Admin menus
 *
 * @since	CLOSER 0.1
 */
add_action( 'admin_menu', 'pilau_admin_menus', 10 );
function pilau_admin_menus() {

	/* Customize standard menus
	***************************************************************************/

	// Links
	if ( ! PILAU_USE_LINKS ) {
		remove_menu_page( 'link-manager.php' );
	}

	// Comments
	if ( ! PILAU_USE_COMMENTS ) {
		remove_menu_page( 'edit-comments.php' );
	}

	// Menu for all settings
	//add_options_page( __('All Settings'), __('All Settings'), 'manage_options', 'options.php' );

	/* Rename "Posts" in menu to "News"
	 *
	 * Most of our projects have "News", not "Blog"
	 * @link http://new2wp.com/snippet/change-wordpress-posts-post-type-news/
	 */
	global $menu, $submenu;
	// $menu[5][0] = 'News &amp; opinion';
	// $submenu['edit.php'][5][0] = 'News &amp; opinion';
	// $submenu['edit.php'][10][0] = 'Add News &amp; opinion';
	//$submenu['edit.php'][16][0] = 'News &amp; opinion Tags';

	/* Register new menus
	***************************************************************************/

	// Site settings
	add_options_page( get_bloginfo( 'name' ) . ' ' . __( 'settings' ), get_bloginfo( 'name' ) . ' ' . __( 'settings' ), 'manage_options', 'pilau-site-settings', 'pilau_site_settings_admin_page' );

}


/**
 * Remove meta boxes
 *
 * @since	CLOSER 0.1
 */
add_action( 'add_meta_boxes', 'pilau_remove_meta_boxes', 10 );
function pilau_remove_meta_boxes() {

	/* Publish */
	//remove_meta_box( 'submitdiv', 'post', 'normal' );

	/* Revisions */
	//remove_meta_box( 'revisionsdiv', 'post', 'normal' );

	/* Author */
	//remove_meta_box( 'authordiv', 'post', 'normal' );

	/* Slug */
	//remove_meta_box( 'slugdiv', 'post', 'normal' );

	/* Excerpt */
	remove_meta_box( 'postexcerpt', 'post', 'normal' );

	/* Post format */
	//remove_meta_box( 'formatdiv', 'post', 'normal' );

	/* Trackbacks */
	remove_meta_box( 'trackbacksdiv', 'post', 'normal' );

	/* Featured image */
	//remove_meta_box( 'postimagediv', 'post', 'side' );

	/* Page attributes */
	//remove_meta_box( 'pageparentdiv', 'page', 'side' );

	/* Comments - remove unless it's opinion */
	if ( ! ( get_post_type() == 'post' && function_exists( 'slt_cf_field_value' ) && slt_cf_field_value( 'opinion' ) ) ) {
		remove_meta_box( 'commentsdiv', 'post', 'normal' );
		remove_meta_box( 'commentsdiv', 'page', 'normal' );
		remove_meta_box( 'commentstatusdiv', 'post', 'normal' );
		remove_meta_box( 'commentstatusdiv', 'page', 'normal' );
	}

}


/**
 * Inline image hints for Featured Image box
 *
 * @since	CLOSER 0.1
 * @param	string	$content
 * @return	string
 */
function pilau_inline_image_size_featured( $content ) {
	global $post;
	$hint = null;

	switch ( get_post_type() ) {
		case 'study_team_member':
			$hint = 'Please upload the correct size for display: 72 dpi, exactly 160 x 130 px.';
			break;
		case 'funder':
			$hint = 'Please upload the correct size for display: 72 dpi, 120 x 50 px is a good size.';
			break;
		case 'event_speaker':
			$hint = 'Please upload the correct size for display: 72 dpi, 110 x 88 px is a good size.';
			break;
		case 'contextual_data':
			$hint = 'Please upload the correct size for display: 72 dpi, 430 x 220 px is a good size.';
			break;
		case 'page':
			if ( $post->post_parent ) {
				$hint = 'If this page is listed on a landing page template by its parent, this image will be used at a size of 250 x 130 px.';
			}
			break;
	}

	if ( $hint )
		$content = '<p><i>' . $hint . '</i></p>' . $content;

	return $content;
}


/**
 * Global handler for all post type columns
 *
 * @param	array $cols
 * @return	array
 */
function pilau_admin_columns( $cols ) {

	// Override WordPress SEO plugin stuff
	if ( defined( 'WPSEO_VERSION' ) ) {
		foreach ( array( /*'wpseo-score',*/ 'wpseo-title', 'wpseo-metadesc', 'wpseo-focuskw' ) as $wp_seo_col ) {
			if ( isset( $cols[ $wp_seo_col ] ) ) {
				unset( $cols[ $wp_seo_col ] );
			}
		}
	}

	return $cols;
}


/**
 * Customize default tiny MCE buttons
 *
 * @since	CLOSER 0.1
 * @link	http://wordpress.stackexchange.com/questions/141534/how-to-customize-tinymce4-in-wp-3-9-the-old-way-for-styles-and-formats-doesnt
 */
add_filter( 'tiny_mce_before_init', 'pilau_tinymce_buttons' );
function pilau_tinymce_buttons( $init_array ) {
	//echo '<pre>'; print_r( $init_array ); echo '</pre>'; exit;

	// Limit block-level formats
	$init_array['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4';

	// Redo toolbars
	$init_array['toolbar1'] = 'formatselect,bullist,numlist,blockquote,hr,wp_more,bold,italic,link,unlink,pastetext,removeformat,charmap,spellchecker,wp_fullscreen,undo,redo,wp_help';
	$init_array['toolbar2'] = '';

	return $init_array;
}


/**
 * Disable editor for some pages
 *
 * @since	CLOSER 0.1
 */
add_action( 'admin_head', 'pilau_disable_editor' );
function pilau_disable_editor() {
	global $post;
	if ( get_post_type() == 'evidence' || in_array( get_page_template_slug(), array( 'page_data-resources.php', 'page_evidence-impact.php' ) ) ) {
		remove_post_type_support( 'page', 'editor' );
	}
}


/**
 * Move TablePress down, it's overriding a CPT menu!
 *
 * @since	CLOSER 0.1
 */
add_filter( 'tablepress_admin_menu_parent_page', 'closer_tablepress_admin_menu_parent_page' );
function closer_tablepress_admin_menu_parent_page( $parent ) {
	return 'bottom';
}


/**
 * Disable default dashboard widgets
 *
 * @since	CLOSER 0.1
 * @link	http://codex.wordpress.org/Dashboard_Widgets_API
 */
add_action( 'wp_dashboard_setup', 'pilau_disable_default_dashboard_widgets', 10 );
function pilau_disable_default_dashboard_widgets() {

	remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' ); /* WordPress blog */
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' ); /* Other WordPress News */
	/**
	 * NOTE: Right now is removed largely because this theme can be set to disable
	 * core taxonomies (i.e. categories or tags) - and the core Right now widget
	 * doesn't test to see if the taxonomies exist before outputting their details.
	 * @todo Come up with a good replacement "overview" widget
	 */
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	//remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	//remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'normal' );

}


/**
 * Menus hidden columns
 *
 * @since	CLOSER 0.1
 */
add_filter( 'get_user_option_managenav-menuscolumnshidden', 'pilau_nav_menus_columns_hidden' );
function pilau_nav_menus_columns_hidden( $result ) {

	/** Description always on */
	if ( is_array( $result ) && in_array( 'description', $result ) ) {
		unset( $result[ array_search( 'description', $result ) ] );
	}

	/** Classes always on
	if ( is_array( $result ) && in_array( 'styles-classes', $result ) ) {
		unset( $result[ array_search( 'styles-classes', $result ) ] );
	}
	*/

	return $result;
}


/**
 * Site settings admin page
 *
 * @since	CLOSER 0.1
 */
function pilau_site_settings_admin_page() {
	$settings = get_option( 'pilau_site_settings' );

	// Output
	?>

	<div class="wrap">

		<h2><?php echo get_admin_page_title(); ?></h2>

		<?php if ( isset( $_GET['done'] ) ) { ?>
			<div class="updated"><p><strong><?php _e( 'Settings updated successfully.' ); ?></strong></p></div>
		<?php } ?>

		<form method="post" action="">

			<?php wp_nonce_field( 'pilau-site-settings', 'pilau_site_settings_admin_nonce' ); ?>

			<h3><?php _e( 'Contact details' ); ?></h3>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="pilau-address"><?php _e( 'Address' ); ?></label></th>
						<td><textarea cols="40" rows="6" name="address" id="pilau-address" class="regular-text"><?php echo esc_textarea( $settings['address'] ); ?></textarea></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="pilau-tel"><?php _e( 'Telephone' ); ?></label></th>
						<td><input type="text" name="tel" id="pilau-tel" value="<?php esc_attr_e( $settings['tel'] ); ?>" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="pilau-email"><?php _e( 'Email' ); ?></label></th>
						<td><input type="text" name="email" id="pilau-email" value="<?php esc_attr_e( $settings['email'] ); ?>" class="regular-text"></td>
					</tr>
				</tbody>
			</table>

			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>

		</form>

	</div>

<?php

}


/**
 * Process site settings
 *
 * @since	CLOSER 0.1
 */
add_action( 'admin_init', 'pilau_site_settings_admin_page_process' );
function pilau_site_settings_admin_page_process() {

	// Submitted?
	if ( isset( $_POST['pilau_site_settings_admin_nonce'] ) && check_admin_referer( 'pilau-site-settings', 'pilau_site_settings_admin_nonce' ) ) {

		// Gather into array
		$settings = array();
		$settings['address'] = $_POST['address'];
		$settings['tel'] = sanitize_text_field( $_POST['tel'] );
		$settings['email'] = sanitize_email( $_POST['email'] );

		// Save as option
		update_option( 'pilau_site_settings', $settings );

		// Redirect
		wp_redirect( admin_url( 'options-general.php?page=pilau-site-settings&done=1' ) );

	}

}
