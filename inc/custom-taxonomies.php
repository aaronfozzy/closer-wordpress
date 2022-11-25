<?php

/**
 * Custom taxonomies
 *
 * @package	CLOSER-2022
 * @since	0.1
 */

add_action( 'init', 'pilau_register_taxonomies', 0 );
function pilau_register_taxonomies() {

	// Themes
	register_taxonomy(
		'theme', array( 'event', 'post', 'evidence', 'publication', 'contextual_data' ),
		array(
			'hierarchical'		=> true, // 2nd-level themes are "topics"
			'query_var'			=> false,
			'rewrite'			=> false,
			'labels'			=> array(
				'name'				=> __( 'Themes' ),
				'singular_name'		=> __( 'Theme' ),
				'search_items'		=> __( 'Search Themes' ),
				'all_items'			=> __( 'All Themes' ),
				'edit_item'			=> __( 'Edit Theme' ),
				'update_item'		=> __( 'Update Theme' ),
				'add_new_item'		=> __( 'Add New Theme' ),
				'new_item_name'		=> __( 'New Theme Name' ),
			)
		)
	);

	// Lifestages
	register_taxonomy(
		'lifestage', array( 'study', 'evidence' ),
		array(
			'hierarchical'		=> true,
			'query_var'			=> false,
			'rewrite'			=> false,
			'labels'			=> array(
				'name'				=> __( 'Lifestages' ),
				'singular_name'		=> __( 'Lifestage' ),
				'search_items'		=> __( 'Search Lifestages' ),
				'all_items'			=> __( 'All Lifestages' ),
				'edit_item'			=> __( 'Edit Lifestage' ),
				'update_item'		=> __( 'Update Lifestage' ),
				'add_new_item'		=> __( 'Add New Lifestage' ),
				'new_item_name'		=> __( 'New Theme Lifestage' ),
			)
		)
	);

	// Dataset features
	register_taxonomy(
		'dataset_feature', array( 'study' ),
		array(
			'hierarchical'		=> true,
			'query_var'			=> false,
			'rewrite'			=> false,
			'labels'			=> array(
				'name'				=> __( 'Dataset features' ),
				'singular_name'		=> __( 'Dataset feature' ),
				'search_items'		=> __( 'Search Dataset features' ),
				'all_items'			=> __( 'All Dataset features' ),
				'edit_item'			=> __( 'Edit Dataset feature' ),
				'update_item'		=> __( 'Update Dataset feature' ),
				'add_new_item'		=> __( 'Add New Dataset feature' ),
				'new_item_name'		=> __( 'New Theme Dataset feature' ),
			)
		)
	);

	// News subjects
	register_taxonomy(
		'subject', array( 'post' ),
		array(
			'hierarchical'		=> true,
			'query_var'			=> false,
			'rewrite'			=> false,
			'labels'			=> array(
				'name'				=> __( 'Subjects' ),
				'singular_name'		=> __( 'Subject' ),
				'search_items'		=> __( 'Search Subjects' ),
				'all_items'			=> __( 'All Subjects' ),
				'edit_item'			=> __( 'Edit Subject' ),
				'update_item'		=> __( 'Update Subject' ),
				'add_new_item'		=> __( 'Add New Subject' ),
				'new_item_name'		=> __( 'New Theme Subject' ),
			)
		)
	);

	// Publication type
	register_taxonomy(
		'publication_type', array( 'publication' ),
		array(
			'hierarchical'		=> true,
			'query_var'			=> false,
			'rewrite'			=> false,
			'labels'			=> array(
				'name'				=> __( 'Publication types' ),
				'singular_name'		=> __( 'Publication type' ),
				'search_items'		=> __( 'Search Publication types' ),
				'all_items'			=> __( 'All Publication types' ),
				'edit_item'			=> __( 'Edit Publication type' ),
				'update_item'		=> __( 'Update Publication type' ),
				'add_new_item'		=> __( 'Add New Publication type' ),
				'new_item_name'		=> __( 'New Theme Publication type' ),
			)
		)
	);

	// Citation type
	register_taxonomy(
		'citation_type', array( 'citation' ),
		array(
			'hierarchical'		=> true,
			'query_var'			=> false,
			'rewrite'			=> false,
			'labels'			=> array(
				'name'				=> __( 'Citation types' ),
				'singular_name'		=> __( 'Citation type' ),
				'search_items'		=> __( 'Search Citation types' ),
				'all_items'			=> __( 'All Citation types' ),
				'edit_item'			=> __( 'Edit Citation type' ),
				'update_item'		=> __( 'Update Citation type' ),
				'add_new_item'		=> __( 'Add New Citation type' ),
				'new_item_name'		=> __( 'New Theme Citation type' ),
			)
		)
	);

	// Event type
	register_taxonomy(
		'event_type', array( 'event' ),
		array(
			'hierarchical'		=> true,
			'query_var'			=> false,
			'rewrite'			=> false,
			'labels'			=> array(
				'name'				=> __( 'Event types' ),
				'singular_name'		=> __( 'Event type' ),
				'search_items'		=> __( 'Search Event types' ),
				'all_items'			=> __( 'All Event types' ),
				'edit_item'			=> __( 'Edit Event type' ),
				'update_item'		=> __( 'Update Event type' ),
				'add_new_item'		=> __( 'Add New Event type' ),
				'new_item_name'		=> __( 'New Theme Event type' ),
			)
		)
	);

	// Colour
	register_taxonomy(
		'colours', array( 'page' ),
		array(
			'hierarchical'		=> true,
			'query_var'			=> false,
			'rewrite'			=> false,
			'labels'			=> array(
				'name'				=> __( 'Page colours' ),
				'singular_name'		=> __( 'Page colour' ),
				'search_items'		=> __( 'Search Page colours' ),
				'all_items'			=> __( 'All Page colours' ),
				'edit_item'			=> __( 'Edit Page colours' ),
				'update_item'		=> __( 'Update Page colours' ),
				'add_new_item'		=> __( 'Add New Page colour' ),
				'new_item_name'		=> __( 'New Theme Page colour' ),
			)
		)
	);

}