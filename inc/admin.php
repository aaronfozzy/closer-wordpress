<?php

/**
 * General admin stuff
 *
 * @package	CLOSER-2022
 * @since	0.1
 */


/* Any admin-specific includes */

/**
 * Admin interface customization
 *
 * @since	CLOSER 0.1
 */
require( dirname( __FILE__ ) . '/admin-interface.php' );


/**
 * Admin initialization
 *
 * @since	CLOSER 0.1
 */
//add_action( 'admin_init', 'pilau_admin_init', 10 );
function pilau_admin_init() {


}


/**
 * Anything for when posts are published
 *
 * @since	CLOSER 0.1
 */
add_action( 'publish_post', 'closer_publish_post', 10, 2 );
function closer_publish_post( $post_id, $post ) {

	// Opinion?
	if ( ( function_exists( 'slt_cf_field_value' ) && slt_cf_field_value( 'opinion', 'post', $post_id ) ) ) {

		// Remove action to prevent infinite loop
		remove_action( 'publish_post', 'closer_publish_post', 10 );

		// Set comment status
		wp_update_post( array(
			'ID'				=> $post_id,
			'comment_status'	=> 'open'
		));

		// Re-add action
		add_action( 'publish_post', 'closer_publish_post', 10, 2 );

	}

}


/*
 * Anything for when posts are created or updated (retired for now in favour of using publish transition)
 *
 * @since	CLOSER 0.1
add_action( 'save_post', 'closer_save_post', 10, 3 );
function closer_save_post( $post_id, $post, $update ) {

	// Make sure it's not a revision or auto-draft
	if ( ! wp_is_post_revision( $post_id ) && $post->post_status != 'auto-draft' ) {

		// Remove this action in case wp_update_post is used, to avoid infinite loop
		remove_action( 'save_post', 'closer_save_post' );

		// Make sure comments are on for opinion
		if ( ( get_post_type( $post_id ) == 'post' && function_exists( 'slt_cf_field_value' ) && slt_cf_field_value( 'opinion', 'post', $post_id ) ) ) {
			//echo '<pre>'; print_r( $update ); echo '</pre>';
			//echo '<pre>'; print_r( $post ); echo '</pre>'; exit;
			wp_update_post( array(
				'ID'				=> $post_id,
				'comment_status'	=> 'open'
			));
		}

		// Re-add action
		add_action( 'save_post', 'closer_save_post', 10, 3 );

	}

}
*/
