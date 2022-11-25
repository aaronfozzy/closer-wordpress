<?php

/**
 * Custom Post Types
 *
 * @package	CLOSER-2022
 * @since	0.1
 */

add_action( 'init', 'pilau_register_post_types', 0 );
function pilau_register_post_types() {

	// Events
	register_post_type(
		'event', array(
			'labels'				=> array(
				'name'					=> __( 'Events &amp; training' ),
				'singular_name'			=> __( 'Event / training' ),
				'add_new'				=> __( 'Add New' ),
				'add_new_item'			=> __( 'Add New Event / training' ),
				'edit'					=> __( 'Edit' ),
				'edit_item'				=> __( 'Edit Event / training' ),
				'new_item'				=> __( 'New Event / training' ),
				'view'					=> __( 'View Event / training' ),
				'view_item'				=> __( 'View Event / training' ),
				'search_items'			=> __( 'Search Events &amp; training' ),
				'not_found'				=> __( 'No Events &amp; training found' ),
				'not_found_in_trash'	=> __( 'No Events &amp; training found in Trash' )
			),
			'public'			=> true,
			'menu_position'		=> 25,
			'menu_icon'			=> 'dashicons-calendar', // @link http://melchoyce.github.io/dashicons/
			'supports'			=> array( 'title', 'editor', 'custom-fields', 'thumbnail', 'revisions' ),
			'taxonomies'		=> array( 'theme', 'event_type' ),
			'rewrite'			=> array( 'slug' => 'event', 'with_front' => false )
		)
	);

	// Event speakers
	register_post_type(
		'event_speaker', array(
			'labels'				=> array(
				'name'					=> __( 'Event speakers' ),
				'singular_name'			=> __( 'Event speaker' ),
				'add_new'				=> __( 'Add New' ),
				'add_new_item'			=> __( 'Add New Event speaker' ),
				'edit'					=> __( 'Edit' ),
				'edit_item'				=> __( 'Edit Event speaker' ),
				'new_item'				=> __( 'New Event speaker' ),
				'view'					=> __( 'View Event speaker' ),
				'view_item'				=> __( 'View Event speaker' ),
				'search_items'			=> __( 'Search Event speakers' ),
				'not_found'				=> __( 'No Event speakers found' ),
				'not_found_in_trash'	=> __( 'No Event speakers found in Trash' )
			),
			'public'			=> false,
			'show_ui'			=> true,
			'menu_position'		=> 25,
			'menu_icon'			=> 'dashicons-businessman', // @link http://melchoyce.github.io/dashicons/
			'supports'			=> array( 'title', 'custom-fields', 'thumbnail', 'revisions' ),
			'rewrite'			=> false
		)
	);

	// Studies
	register_post_type(
		'study', array(
			'labels'				=> array(
				'name'					=> __( 'Studies' ),
				'singular_name'			=> __( 'Study' ),
				'add_new'				=> __( 'Add New' ),
				'add_new_item'			=> __( 'Add New Study' ),
				'edit'					=> __( 'Edit' ),
				'edit_item'				=> __( 'Edit Study' ),
				'new_item'				=> __( 'New Study' ),
				'view'					=> __( 'View Study' ),
				'view_item'				=> __( 'View Study' ),
				'search_items'			=> __( 'Search Studies' ),
				'not_found'				=> __( 'No Studies found' ),
				'not_found_in_trash'	=> __( 'No Studies found in Trash' )
			),
			'public'			=> true,
			'menu_position'		=> 25,
			'menu_icon'			=> 'dashicons-clipboard', // @link http://melchoyce.github.io/dashicons/
			'supports'			=> array( 'title', 'editor', 'custom-fields', 'thumbnail', 'revisions' ),
			'taxonomies'		=> array( 'lifestage', 'dataset_feature' ),
			'rewrite'			=> array( 'slug' => 'study', 'with_front' => false )
		)
	);

	// Sweeps
	register_post_type(
		'sweep', array(
			'labels'				=> array(
				'name'					=> __( 'Sweeps' ),
				'singular_name'			=> __( 'Sweep' ),
				'add_new'				=> __( 'Add New' ),
				'add_new_item'			=> __( 'Add New Sweep' ),
				'edit'					=> __( 'Edit' ),
				'edit_item'				=> __( 'Edit Sweep' ),
				'new_item'				=> __( 'New Sweep' ),
				'view'					=> __( 'View Sweep' ),
				'view_item'				=> __( 'View Sweep' ),
				'search_items'			=> __( 'Search Sweeps' ),
				'not_found'				=> __( 'No Sweeps found' ),
				'not_found_in_trash'	=> __( 'No Sweeps found in Trash' )
			),
			'public'			=> false,
			'show_ui'			=> true,
			'menu_position'		=> 25,
			'menu_icon'			=> 'dashicons-download', // @link http://melchoyce.github.io/dashicons/
			'supports'			=> array( 'title', 'custom-fields' ),
			'rewrite'			=> false
		)
	);

	// Study team members library
	register_post_type(
		'study_team_member', array(
			'labels'				=> array(
				'name'					=> __( 'Study team members' ),
				'singular_name'			=> __( 'Study team member' ),
				'add_new'				=> __( 'Add New' ),
				'add_new_item'			=> __( 'Add New Study team member' ),
				'edit'					=> __( 'Edit' ),
				'edit_item'				=> __( 'Edit Study team member' ),
				'new_item'				=> __( 'New Study team member' ),
				'view'					=> __( 'View Study team member' ),
				'view_item'				=> __( 'View Study team member' ),
				'search_items'			=> __( 'Search Study team members' ),
				'not_found'				=> __( 'No Study team members found' ),
				'not_found_in_trash'	=> __( 'No Study team members found in Trash' )
			),
			'public'			=> false,
			'show_ui'			=> true,
			'menu_position'		=> 25,
			'menu_icon'			=> 'dashicons-businessman', // @link http://melchoyce.github.io/dashicons/
			'supports'			=> array( 'title', 'custom-fields', 'thumbnail' ),
			'rewrite'			=> false
		)
	);

	// Funders library
	register_post_type(
		'funder', array(
			'labels'				=> array(
				'name'					=> __( 'Funders' ),
				'singular_name'			=> __( 'Funder' ),
				'add_new'				=> __( 'Add New' ),
				'add_new_item'			=> __( 'Add New Funder' ),
				'edit'					=> __( 'Edit' ),
				'edit_item'				=> __( 'Edit Funder' ),
				'new_item'				=> __( 'New Funder' ),
				'view'					=> __( 'View Funder' ),
				'view_item'				=> __( 'View Funder' ),
				'search_items'			=> __( 'Search Funders' ),
				'not_found'				=> __( 'No Funders found' ),
				'not_found_in_trash'	=> __( 'No Funders found in Trash' )
			),
			'public'			=> false,
			'show_ui'			=> true,
			'menu_position'		=> 25,
			'menu_icon'			=> 'dashicons-groups', // @link http://melchoyce.github.io/dashicons/
			'supports'			=> array( 'title', 'custom-fields', 'thumbnail' ),
			'rewrite'			=> false
		)
	);

	// Evidence
	register_post_type(
		'evidence', array(
			'labels'				=> array(
				'name'					=> __( 'Evidence' ),
				'singular_name'			=> __( 'Evidence' ),
				'add_new'				=> __( 'Add New' ),
				'add_new_item'			=> __( 'Add New Evidence' ),
				'edit'					=> __( 'Edit' ),
				'edit_item'				=> __( 'Edit Evidence' ),
				'new_item'				=> __( 'New Evidence' ),
				'view'					=> __( 'View Evidence' ),
				'view_item'				=> __( 'View Evidence' ),
				'search_items'			=> __( 'Search Evidence' ),
				'not_found'				=> __( 'No Evidence found' ),
				'not_found_in_trash'	=> __( 'No Evidence found in Trash' )
			),
			'public'			=> true,
			'menu_position'		=> 25,
			'menu_icon'			=> 'dashicons-portfolio', // @link http://melchoyce.github.io/dashicons/
			'supports'			=> array( 'title', 'custom-fields', 'thumbnail', 'revisions' ),
			'taxonomies'		=> array( 'theme', 'lifestage' ),
			'rewrite'			=> array( 'slug' => 'evidence', 'with_front' => false )
		)
	);

	// Citations library
	register_post_type(
		'citation', array(
			'labels'				=> array(
				'name'					=> __( 'Citations' ),
				'singular_name'			=> __( 'Citation' ),
				'add_new'				=> __( 'Add New' ),
				'add_new_item'			=> __( 'Add New Citation' ),
				'edit'					=> __( 'Edit' ),
				'edit_item'				=> __( 'Edit Citation' ),
				'new_item'				=> __( 'New Citation' ),
				'view'					=> __( 'View Citation' ),
				'view_item'				=> __( 'View Citation' ),
				'search_items'			=> __( 'Search Citations' ),
				'not_found'				=> __( 'No Citations found' ),
				'not_found_in_trash'	=> __( 'No Citations found in Trash' )
			),
			'public'			=> false,
			'show_ui'			=> true,
			'menu_position'		=> 25,
			'menu_icon'			=> 'dashicons-format-quote', // @link http://melchoyce.github.io/dashicons/
			'supports'			=> array( 'title', 'custom-fields' ),
			'taxonomies'		=> array( 'citation_type' ),
			'rewrite'			=> false
		)
	);

	// Impact
	register_post_type(
		'impact', array(
			'labels'				=> array(
				'name'					=> __( 'Impacts' ),
				'singular_name'			=> __( 'Impact' ),
				'add_new'				=> __( 'Add New' ),
				'add_new_item'			=> __( 'Add New Impact' ),
				'edit'					=> __( 'Edit' ),
				'edit_item'				=> __( 'Edit Impact' ),
				'new_item'				=> __( 'New Impact' ),
				'view'					=> __( 'View Impact' ),
				'view_item'				=> __( 'View Impact' ),
				'search_items'			=> __( 'Search Impacts' ),
				'not_found'				=> __( 'No Impacts found' ),
				'not_found_in_trash'	=> __( 'No Impacts found in Trash' )
			),
			'public'			=> true,
			'menu_position'		=> 25,
			'menu_icon'			=> 'dashicons-chart-bar', // @link http://melchoyce.github.io/dashicons/
			'supports'			=> array( 'title', 'editor', 'custom-fields', 'revisions' ),
			'taxonomies'		=> array( 'theme' ),
			'rewrite'			=> array( 'slug' => 'impact', 'with_front' => false )
		)
	);

	// Contextual data
	register_post_type(
		'contextual_data', array(
			'labels'				=> array(
				'name'					=> __( 'Contextual data' ),
				'singular_name'			=> __( 'Contextual data' ),
				'add_new'				=> __( 'Add New' ),
				'add_new_item'			=> __( 'Add New Contextual data' ),
				'edit'					=> __( 'Edit' ),
				'edit_item'				=> __( 'Edit Contextual data' ),
				'new_item'				=> __( 'New Contextual data' ),
				'view'					=> __( 'View Contextual data' ),
				'view_item'				=> __( 'View Contextual data' ),
				'search_items'			=> __( 'Search Contextual data' ),
				'not_found'				=> __( 'No Contextual data found' ),
				'not_found_in_trash'	=> __( 'No Contextual data found in Trash' )
			),
			'public'			=> true,
			'menu_position'		=> 25,
			'menu_icon'			=> 'dashicons-chart-line', // @link http://melchoyce.github.io/dashicons/
			'supports'			=> array( 'title', 'editor', 'custom-fields', 'thumbnail', 'revisions' ),
			'taxonomies'		=> array( 'theme' ),
			'rewrite'			=> array( 'slug' => 'data', 'with_front' => false )
		)
	);

	// Publications / videos
	register_post_type(
		'publication', array(
			'labels'				=> array(
				'name'					=> __( 'Publications / videos' ),
				'singular_name'			=> __( 'Publication / video' ),
				'add_new'				=> __( 'Add New' ),
				'add_new_item'			=> __( 'Add New Publication / video' ),
				'edit'					=> __( 'Edit' ),
				'edit_item'				=> __( 'Edit Publication / video' ),
				'new_item'				=> __( 'New Publication / video' ),
				'view'					=> __( 'View Publication / video' ),
				'view_item'				=> __( 'View Publication / video' ),
				'search_items'			=> __( 'Search Publications / videos' ),
				'not_found'				=> __( 'No Publications / videos found' ),
				'not_found_in_trash'	=> __( 'No Publications / videos found in Trash' )
			),
			'public'			=> false,
			'show_ui'			=> true,
			'menu_position'		=> 25,
			'menu_icon'			=> 'dashicons-media-video', // @link http://melchoyce.github.io/dashicons/
			'supports'			=> array( 'title', 'editor', 'custom-fields', 'thumbnail', 'revisions' ),
			'taxonomies'		=> array( 'theme', 'publication_type' ),
			'rewrite'			=> false
		)
	);

}
