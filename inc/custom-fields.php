<?php


/**
 * Custom meta fields for posts and users
 *
 * Depends on the Developer's Custom Fields plugin
 * @link http://sltaylor.co.uk/wordpress/developers-custom-fields-docs/
 *
 * @package	CLOSER-2022
 * @since	0.1
 */


/**
 * Register custom fields
 *
 * @uses slt_cf_register_box()
 * @since	CLOSER 0.1
 */
if ( function_exists( 'slt_cf_register_box') ) {
	add_action( 'init', 'pilau_register_custom_fields', 11 );
}
function pilau_register_custom_fields() {
	global $closer_related_content_post_types, $closer_download_mime_types, $closer_citation_formats, $closer_terms;

	// Use custom fields for Admin Columns
	if ( class_exists( 'Codepress_Admin_Columns' ) ) {
		add_filter( 'cpac_use_hidden_custom_fields', '__return_true' );
	}

	// Mega menu options for top-level pages
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Mega menu options',
		'id'		=> 'mega-menu-options-box',
		'context'	=> 'normal',
		'fields'	=> array(
			array(
				'name'			=> 'mega-menu-strips',
				'label'			=> __( 'Use all strips?' ),
				'description'	=> __( 'If unchecked, boxes will be used in a 3-column grid, then strips.' ),
				'type'			=> 'checkbox',
				'scope'			=>	array( 'level-1' ),
				'capabilities'	=> array( 'manage_options' )
			),
			array(
				'name'			=> 'mega-menu-filler',
				'label'			=> __( 'Use "filler" menu content when there\'s no child pages?' ),
				'type'			=> 'checkbox',
				'scope'			=>	array( 'level-1' ),
				'capabilities'	=> array( 'manage_options' )
			),
			array(
				'name'			=> 'mega-menu-filler-text',
				'label'			=> __( 'Filler text' ),
				'type'			=> 'text',
				'scope'			=>	array( 'level-1' ),
				'capabilities'	=> array( 'manage_options' )
			)
		)
	));

	// Teaser text for 2nd-level pages
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Teaser text',
		'id'		=> 'teaser-text-box',
		'context'	=> 'normal',
		'fields'	=> array(
			array(
				'name'			=> 'teaser-text',
				'label'			=> __( 'Teaser text' ),
				'description'	=> __( 'Will be used in boxes on mega-menus.' ),
				'type'			=> 'text',
				'scope'			=>	array( 'level-2', 'posts' => array( CLOSER_EVENT_PAGE_ID ) ),
				'capabilities'	=> array( 'publish_pages' )
			)
		)
	));

	

	/* Events
	**************************************************************************/

	// Events details
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Event details',
		'id'		=> 'event-details-box',
		'context'	=> 'above-content',
		'fields'	=> array(
			array(
				'name'			=> 'event-date-notice',
				'label'			=> __( 'Event date' ),
				'type'			=> 'notice',
				'description'	=> __( 'Please set the event date to the right; all other details can be set below.' ),
				'scope'			=> array( 'event' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'event-intro',
				'label'			=> __( 'Intro text' ),
				'type'			=> 'textile',
				'scope'			=> array( 'event' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'event-time-start',
				'label'			=> __( 'Start time' ),
				'type'			=> 'time',
				'scope'			=> array( 'event' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'event-time-end',
				'label'			=> __( 'End time' ),
				'type'			=> 'time',
				'scope'			=> array( 'event' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'event-venue',
				'label'			=> __( 'Venue' ),
				'type'			=> 'text',
				'scope'			=> array( 'event' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'event-organiser',
				'label'			=> __( 'Organiser' ),
				'type'			=> 'text',
				'scope'			=> array( 'event' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'event-price',
				'label'			=> __( 'Price' ),
				'type'			=> 'text',
				'input_prefix'	=> '&pound;',
				'default'		=> 0,
				'width'			=> 5,
				'scope'			=> array( 'event' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'event-level',
				'label'			=> __( 'Expertise level' ),
				'type'			=> 'text',
				'scope'			=> array( 'event' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'event-chair',
				'label'			=> __( 'Chair' ),
				'type'			=> 'select',
				'options_type'	=> 'posts',
				'options_query'	=> array(
					'post_type'			=> 'event_speaker',
					'posts_per_page'	=> -1,
					'orderby'			=> 'title'
				),
				'description'	=> __( 'Include the chair as a speaker below as well. They should be selected here in the drop-down so they can be listed first.' ),
				'scope'			=> array( 'event' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'event-speakers',
				'label'			=> __( 'Speakers' ),
				'type'			=> 'checkboxes',
				'options_type'	=> 'posts',
				'options_query'	=> array(
					'post_type'			=> 'event_speaker',
					'posts_per_page'	=> -1,
					'orderby'			=> 'title'
				),
				'single'		=> false,
				'width'			=> 17,
				'scope'			=> array( 'event' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'event-booking-url',
				'label'			=> __( 'Booking URL' ),
				'type'			=> 'text',
				'scope'			=> array( 'event' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'event-show-map',
				'label'			=> __( 'Show map' ),
				'type'			=> 'checkbox',
				'scope'			=> array( 'event' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	// Event video resources
	slt_cf_register_box( array(
		'type'			=> 'post',
		'title'			=> 'Event video resources',
		'id'			=> 'event-video-resources-box',
		'context'		=> 'normal',
		'priority'		=> 'high',
		'description'	=> __( 'These resources will only be output (along with assigned downloads) for past events.' ),
		'fields'	=> array(
			array(
				'name'			=> 'event-videos',
				'label'			=> __( 'Event video resource' ),
				'type'			=> 'textarea',
				'description'	=> __( 'Enter one video per line. For each video, enter a URL (e.g. YouTube). If you want to enter a title, put it after the URL, separated by a space.' ),
				'scope'			=> array( 'event' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	/* Event speakers
	**************************************************************************/

	// Speaker details
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Speaker details',
		'id'		=> 'event-speaker-details-box',
		'context'	=> 'normal',
		'fields'	=> array(
			array(
				'name'			=> 'speaker-position',
				'label'			=> __( 'Position' ),
				'type'			=> 'text',
				'scope'			=> array( 'event_speaker' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'speaker-bio',
				'label'			=> __( 'Bio' ),
				'type'			=> 'textile',
				'scope'			=> array( 'event_speaker' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	/* Studies
	**************************************************************************/

	// Alternative title
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Alternative title',
		'id'		=> 'alt-title-box',
		'context'	=> 'above-content',
		'fields'	=> array(
			array(
				'name'			=> 'study-alt-title',
				'label'			=> __( 'Alternative title' ),
				'hide_label'	=> true,
				'type'			=> 'text',
				'scope'			=> array( 'study' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	// Study details
	$study_details_fields = array(
		array(
			'name'			=> 'study-overview-short',
			'label'			=> __( 'Short overview' ),
			'type'			=> 'text',
			'scope'			=> array( 'study' ),
			'capabilities'	=> array( 'publish_pages' )
		),
		array(
			'name'			=> 'study-overview-long',
			'label'			=> __( 'Longer overview' ),
			'type'			=> 'textarea',
			'scope'			=> array( 'study' ),
			'capabilities'	=> array( 'publish_pages' )
		),
		array(
			'name'			=> 'study-twitter',
			'label'			=> __( 'Twitter feed handle' ),
			'type'			=> 'text',
			'input_prefix'	=> '@',
			'scope'			=> array( 'study' ),
			'capabilities'	=> array( 'publish_pages' )
		),
		array(
			'name'			=> 'study-url',
			'label'			=> __( 'URL' ),
			'type'			=> 'text',
			'description'	=> __( 'Including http://' ),
			'scope'			=> array( 'study' ),
			'capabilities'	=> array( 'publish_pages' )
		),
		array(
			'name'			=> 'study-time-period',
			'label'			=> __( 'Time period(s)' ),
			'type'			=> 'text',
			'description'	=> __( 'E.g. 1971-1990. For multiple periods, enter them in order, comma-separated. If the study is ongoing, leave the last end date blank, e.g. 1971-' ),
			'scope'			=> array( 'study' ),
			'capabilities'	=> array( 'publish_pages' )
		),
		array(
			'name'			=> 'study-sample-size-units',
			'label'			=> __( 'Sample size units' ),
			'type'			=> 'select',
			'options'		=> array( __( 'Six' ) => 6, __( 'Five' ) => 5, __( 'Four' ) => 4, __( 'Three' ) => 3, __( 'Two' ) => 2, __( 'One' ) => 1 ),
			'description'	=> __( 'Pick a number for a graphic representation of the sample size.' ),
			'scope'			=> array( 'study' ),
			'capabilities'	=> array( 'publish_pages' )
		),
		array(
			'name'			=> 'study-sample-size-icon',
			'label'			=> __( 'Sample size icon' ),
			'type'			=> 'select',
			'options'		=> array( __( 'Head' ) => 'head', __( 'House' ) => 'house' ),
			'default'		=> 'head',
			'scope'			=> array( 'study' ),
			'capabilities'	=> array( 'publish_pages' )
		),
		array(
			'name'			=> 'study-sample-size',
			'label'			=> __( 'Actual sample size' ),
			'type'			=> 'text',
			'scope'			=> array( 'study' ),
			'capabilities'	=> array( 'publish_pages' )
		),
		array(
			'name'			=> 'study-sample-size-copy',
			'label'			=> __( 'Actual sample size text' ),
			'type'			=> 'textarea',
			'scope'			=> array( 'study' ),
			'capabilities'	=> array( 'publish_pages' )
		),
		array(
			'name'			=> 'study-data-access',
			'label'			=> __( 'Data access information' ),
			'type'			=> 'textile',
			'scope'			=> array( 'study' ),
			'capabilities'	=> array( 'publish_pages' )
		),
		array(
			'name'			=> 'study-total-years',
			'label'			=> __( 'Total years' ),
			'type'			=> 'text',
			'scope'			=> array( 'study' ),
			'capabilities'	=> array( 'publish_pages' )
		),
		array(
			'name'			=> 'study-related-video',
			'label'			=> __( 'Related video' ),
			'description'	=> __( 'Paste in iframe from YouTube. How to: Go to YouTube video page, click Share, then Embed, then copy the generated YouTube iframe into this field.' ),
			'type'			=> 'text',
			'scope'			=> array( 'study' ),
			'capabilities'	=> array( 'publish_pages' )
		),
	);
	// Add team members
	for ( $i = 1; $i <= CLOSER_STUDY_NUM_TEAM_MEMBERS; $i++ ) {
		$study_details_fields[] = array(
			'name'			=> 'study-team-member-' . $i,
			'label'			=> __( 'Team member' ) . ' ' . $i,
			'type'			=> 'select',
			'options_type'	=> 'posts',
			'options_query'	=> array(
				'post_type'			=> 'study_team_member',
				'posts_per_page'	=> -1,
				'orderby'			=> 'title',
				'order'				=> 'ASC'
			),
			'scope'			=> array( 'study' ),
			'capabilities'	=> array( 'publish_pages' )
		);
		$study_details_fields[] = array(
			'name'			=> 'study-team-member-' . $i . '-job-title',
			'label'			=> __( 'Team member' ) . ' ' . $i . ' ' . __( 'job title' ),
			'type'			=> 'text',
			'scope'			=> array( 'study' ),
			'capabilities'	=> array( 'publish_pages' )
		);
	}
	$study_details_fields[] = array(
		'name'			=> 'study-funders',
		'label'			=> __( 'Funders' ),
		'type'			=> 'checkboxes',
		'description'	=> __( 'To add new funders, save your work here if necessary, then go to <a href"' . admin_url( 'post-new.php?post_type=funder' ) . '"><b>Funders &gt; Add New</b></a>. Then you can return here to add the new funders.' ),
		'options_type'	=> 'posts',
		'options_query'	=> array(
			'post_type'			=> 'funder',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'ASC'
		),
		'single'		=> false,
		'scope'			=> array( 'study' ),
		'capabilities'	=> array( 'publish_pages' )
	);
	$study_details_fields[] = array(
		'name'			=> 'study-host',
		'label'			=> __( 'Host institution' ),
		'type'			=> 'text',
		'scope'			=> array( 'study' ),
		'capabilities'	=> array( 'publish_pages' )
	);
	$study_details_fields[] = array(
		'name'					=> 'study-host-logo',
		'label'					=> __( 'Host institution logo' ),
		'type'					=> 'file',
		'file_restrict_to_type' => 'image',
		'file_button_label' 	=> __( 'Select image' ),
		'file_dialog_title'		=> __( 'Select image' ),
		'description'			=> __( 'The image should be a 72 dpi, no wider than 450 px, preferably 200 px or under.' ),
		'scope'					=> array( 'study' ),
		'capabilities'			=> array( 'publish_pages' )
	);
	$study_details_fields[] = array(
		'name'			=> 'study-host-url',
		'label'			=> __( 'Host institution URL' ),
		'type'			=> 'text',
		'scope'			=> array( 'study' ),
		'capabilities'	=> array( 'publish_pages' )
	);
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Study details',
		'id'		=> 'study-details-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> $study_details_fields
	));

	// Related evidence
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Related evidence',
		'id'		=> 'related-evidence-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'description'	=> __( 'If nothing is selected here, this bit will output all evidence tagged with this study as relevant.' ),
		'fields'	=> array(
			array(
				'name'			=> 'related-evidence',
				'label'			=> __( 'Related evidence' ),
				'hide_label'	=> true,
				'type'			=> 'checkboxes',
				'options_type'	=> 'posts',
				'options_query'	=> array(
					'post_type'			=> 'evidence',
					'posts_per_page'	=> -1,
					'orderby'			=> 'title',
					'order'				=> 'ASC',
				),
				'single'		=> false,
				'scope'			=> array( 'study' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	/* Study team members
	**************************************************************************/

	// Team member details
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Team member details',
		'id'		=> 'team-member-details-box',
		'context'	=> 'normal',
		'fields'	=> array(
			array(
				'name'			=> 'team-member-position',
				'label'			=> __( 'Position' ),
				'type'			=> 'text',
				'scope'			=> array( 'study_team_member' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	/* Sweeps
	**************************************************************************/

	// Assign to study
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Study',
		'id'		=> 'sweep-study-box',
		'context'	=> 'side',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'sweep-study',
				'label'			=> __( 'Study' ),
				'hide_label'	=> true,
				'type'			=> 'select',
				'options_type'	=> 'posts',
				'options_query'	=> array(
					'post_type'			=> 'study',
					'posts_per_page'	=> -1,
					'orderby'			=> 'title',
					'order'				=> 'ASC'
				),
				'required'		=> true,
				'scope'			=> array( 'sweep' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	// Sweep details
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Sweep details',
		'id'		=> 'sweep-details-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'sweep-year',
				'label'			=> __( 'Year' ),
				'type'			=> 'text',
				'scope'			=> array( 'sweep' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'sweep-age-range',
				'label'			=> __( 'Age range' ),
				'type'			=> 'text',
				'scope'			=> array( 'sweep' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'sweep-description',
				'label'			=> __( 'Description' ),
				'type'			=> 'textile',
				'scope'			=> array( 'sweep' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'sweep-url',
				'label'			=> __( 'URL' ),
				'type'			=> 'text',
				'scope'			=> array( 'sweep' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	/* Funders library
	**************************************************************************/

	// Funder details
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Funder details',
		'id'		=> 'funder-details-box',
		'context'	=> 'normal',
		'fields'	=> array(
			array(
				'name'			=> 'funder-url',
				'label'			=> __( 'URL' ),
				'type'			=> 'text',
				'scope'			=> array( 'funder' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	/* Evidence
	**************************************************************************/

	// Last updated
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Last updated',
		'id'		=> 'evidence-last-updated-box',
		'context'	=> 'side',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'						=> 'last-updated',
				'label'						=> __( 'Last updated' ),
				'hide_label'				=> true,
				'type'						=> 'date',
				'datepicker_format'			=> 'mm/yy',
				'scope'						=>	array( 'evidence' ),
				'capabilities'				=> array( 'publish_pages' )
			),
		)
	));

	// Hero
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Hero',
		'id'		=> 'evidence-hero-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'					=> 'hero-image',
				'label'					=> __( 'Image' ),
				'description'			=> __( 'Optimum size 418 x 275 px' ),
				'type'					=> 'file',
				'file_restrict_to_type' => 'image',
				'file_button_label' 	=> __( 'Select image' ),
				'file_dialog_title'		=> __( 'Select image' ),
				'scope'					=>	array( 'evidence' ),
				'capabilities'			=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'hero-text',
				'label'			=> __( 'Text' ),
				'type'			=> 'textarea',
				'scope'			=>	array( 'evidence' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	// Teaser text
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Teaser text',
		'id'		=> 'teaser-text-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'teaser-text',
				'label'			=> __( 'Teaser text' ),
				'type'			=> 'textarea',
				'scope'			=>	array( 'evidence' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	// Summary tabs
	$evidence_summary_fields = array(
		array(
			'name'			=> 'evidence-summary-key-findings',
			'label'			=> __( 'Key findings text' ),
			'type'			=> 'wysiwyg',
			'scope'			=>	array( 'evidence' ),
			'capabilities'	=> array( 'publish_pages' )
		),
		array(
			'name'			=> 'evidence-summary-pdf',
			'label'			=> __( 'Key findings PDF' ),
			'type'			=> 'file',
			'scope'			=>	array( 'evidence' ),
			'capabilities'	=> array( 'publish_pages' )
		),
	);
	for ( $i = 1; $i <= 5; $i++ ) {
		$evidence_summary_fields[] = array(
			'name'			=> 'evidence-summary-heading-' . $i,
			'label'			=> __( 'Heading' ) . ' ' . $i,
			'type'			=> 'text',
			'scope'			=>	array( 'evidence' ),
			'capabilities'	=> array( 'publish_pages' )
		);
		$evidence_summary_fields[] = array(
			'name'			=> 'evidence-summary-text-' . $i,
			'label'			=> __( 'First column of text' ) . ' ' . $i,
			'type'			=> 'wysiwyg',
			'scope'			=>	array( 'evidence' ),
			'capabilities'	=> array( 'publish_pages' )
		);
		$evidence_summary_fields[] = array(
			'name'			=> 'evidence-summary-text-' . $i . '-col-2',
			'label'			=> __( 'Second column of text' ) . ' ' . $i,
			'type'			=> 'wysiwyg',
			'scope'			=>	array( 'evidence' ),
			'capabilities'	=> array( 'publish_pages' )
		);
		$evidence_summary_fields[] = array(
			'name'			=> 'evidence-summary-quote-' . $i,
			'label'			=> __( 'Quote' ) . ' ' . $i,
			'type'			=> 'textarea',
			'height'		=> 4,
			'scope'			=>	array( 'evidence' ),
			'capabilities'	=> array( 'publish_pages' )
		);
	}
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> __( 'Key findings' ),
		'id'		=> 'evidence-summary-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'description'	=> __( '<p>If the simple Key Findings text is present, the tabbed content will be suppressed.</p><p>To insert a styled footnote reference in the tab content below, wrap a number in a shortcode like this: <code>[ref]1[/ref]</code>. Then use a numbered list to list the references at the end of that tab.</p>' ),
		'fields'	=> $evidence_summary_fields
	));

	// Briefing paper
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Briefing paper',
		'id'		=> 'briefing-paper-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'briefing-paper-title',
				'label'			=> __( 'Title' ),
				'type'			=> 'text',
				'scope'			=>	array( 'evidence' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'briefing-paper',
				'label'			=> __( 'Download' ),
				'type'			=> 'file',
				'scope'			=>	array( 'evidence' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'					=> 'briefing-paper-image',
				'label'					=> __( 'Image' ),
				'type'					=> 'file',
				'file_restrict_to_type' => 'image',
				'file_button_label' 	=> __( 'Select image' ),
				'file_dialog_title'		=> __( 'Select image' ),
				'scope'					=>	array( 'evidence' ),
				'capabilities'			=> array( 'publish_pages' )
			),
		)
	));

	// Selected reading
	slt_cf_register_box( array(
		'type'			=> 'post',
		'title'			=> 'Selected reading list',
		'id'			=> 'selected-reading-box',
		'context'		=> 'normal',
		'priority'		=> 'high',
		'description'	=> __( 'See the <b>Assigned Citations</b> box for the citations assigned to this evidence.' ),
		'fields'	=> array(
			array(
				'name'			=> 'selected-reading-intro',
				'label'			=> __( 'Introduction' ),
				'type'			=> 'textarea',
				'scope'			=>	array( 'evidence' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'selected-reading-pdf',
				'label'			=> __( 'PDF' ),
				'type'			=> 'file',
				'scope'			=>	array( 'evidence' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	// Studies
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Relevant studies',
		'id'		=> 'evidence-studies-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'evidence-studies',
				'label'			=> __( 'Studies' ),
				'hide_label'	=> true,
				'type'			=> 'checkboxes',
				'sortable'		=> true,
				'single'		=> false,
				'options_type'	=> 'posts',
				'options_query'	=> array(
					'post_type'			=> 'study',
					'posts_per_page'	=> -1,
					'orderby'			=> 'title',
					'order'				=> 'ASC'
				),
				'scope'			=>	array( 'evidence' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	// Contextual data
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Relevant contextual data',
		'id'		=> 'contextual-data-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'evidence-contextual-data',
				'label'			=> __( 'Contextual data' ),
				'hide_label'	=> true,
				'type'			=> 'select',
				'options_type'	=> 'posts',
				'options_query'	=> array(
					'post_type'			=> 'contextual_data',
					'posts_per_page'	=> -1,
					'orderby'			=> 'title',
					'order'				=> 'ASC'
				),
				'scope'			=>	array( 'evidence' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	// External resource tabs
	$external_resources_fields = array(
		array(
			'name'			=> 'external-resources-featured-title',
			'label'			=> __( 'Featured resource title' ),
			'type'			=> 'text',
			'scope'			=>	array( 'evidence' ),
			'capabilities'	=> array( 'publish_pages' )
		),
		array(
			'name'			=> 'external-resources-featured-url',
			'label'			=> __( 'Featured resource URL' ),
			'type'			=> 'text',
			'scope'			=>	array( 'evidence' ),
			'capabilities'	=> array( 'publish_pages' )
		),
		array(
			'name'			=> 'external-resources-featured-text',
			'label'			=> __( 'Featured resource text' ),
			'type'			=> 'textarea',
			'scope'			=>	array( 'evidence' ),
			'capabilities'	=> array( 'publish_pages' )
		),
		array(
			'name'					=> 'external-resources-featured-image',
			'label'					=> __( 'Featured resource image' ),
			'type'					=> 'file',
			'file_restrict_to_type' => 'image',
			'file_button_label' 	=> __( 'Select image' ),
			'file_dialog_title'		=> __( 'Select image' ),
			'scope'					=>	array( 'evidence' ),
			'capabilities'			=> array( 'publish_pages' )
		),
	);
	for ( $i = 1; $i <= 4; $i++ ) {
		$external_resources_fields[] = array(
			'name'			=> 'external-resources-title-' . $i,
			'label'			=> __( 'Resource' ) . ' ' . $i . ' ' . __( 'title' ),
			'type'			=> 'text',
			'scope'			=>	array( 'evidence' ),
			'capabilities'	=> array( 'publish_pages' )
		);
		$external_resources_fields[] = array(
			'name'			=> 'external-resources-url-' . $i,
			'label'			=> __( 'Resource' ) . ' ' . $i . ' ' . __( 'URL' ),
			'type'			=> 'text',
			'scope'			=>	array( 'evidence' ),
			'capabilities'	=> array( 'publish_pages' )
		);
	}
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'External resource',
		'id'		=> 'external-resources-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> $external_resources_fields
	));

	// Related evidence
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Related evidence',
		'id'		=> 'related-evidence-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'description'	=> __( 'If nothing is selected here, this bit will output all other evidence sharing the same theme.' ),
		'fields'	=> array(
			array(
				'name'			=> 'related-evidence',
				'label'			=> __( 'Related evidence' ),
				'hide_label'	=> true,
				'type'			=> 'checkboxes',
				'options_type'	=> 'posts',
				'options_query'	=> array(
					'post_type'			=> 'evidence',
					'posts_per_page'	=> -1,
					'orderby'			=> 'title',
					'order'				=> 'ASC',
					'post__not_in'		=> array( '[OBJECT_ID]' )
				),
				'single'		=> false,
				'scope'			=> array( 'evidence' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	// Videos and podcasts
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> __( 'Videos and podcasts' ),
		'id'		=> 'videos-podcasts-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'videos-podcasts',
				'label'			=> __( 'Videos and podcasts' ),
				'hide_label'	=> true,
				'type'			=> 'textarea',
				'description'	=> __( 'Enter a title and URL on each line in this format: <code>A title for this video :: https://www.youtube.com/watch?v=pkq4HEZNPvE</code> (make sure to separate them with two colons)' ),
				'scope'			=>	array( 'evidence' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	/* Citations library
	**************************************************************************/

	// Citation details
	slt_cf_register_box( array(
		'type'			=> 'post',
		'title'			=> 'Citation details',
		'id'			=> 'citation-details-box',
		'context'		=> 'normal',
		'description'	=> __( 'Set the format first, then save to get the fields specific to the format.' ),
		'fields'	=> array(
			array(
				'name'			=> 'citation-format',
				'label'			=> __( 'Format' ),
				'type'			=> 'select',
				'options'		=> $closer_citation_formats,
				'scope'			=>	array( 'citation' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'citation-authors',
				'label'			=> __( 'Authors' ),
				'type'			=> 'textarea',
				'scope'			=>	array( 'citation' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'citation-date',
				'label'			=> __( 'Date' ),
				'type'			=> 'text',
				'scope'			=>	array( 'citation' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'citation-publication-title', // old name for backwards-compatibility
				'label'			=> __( '"Container" title' ),
				'type'			=> 'text',
				'description'	=> __( 'e.g. journal, book or series title' ),
				'scope'			=>	array( 'citation-article', 'citation-chapter', 'citation-paper' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'citation-publication-vol-issue', // old name for backwards-compatibility
				'label'			=> __( '"Container" details' ),
				'type'			=> 'text',
				'scope'			=>	array( 'citation-article', 'citation-chapter', 'citation-paper' ),
				'description'	=> __( 'e.g. volume, edition or series number' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'citation-publication-editors',
				'label'			=> __( 'Editors' ),
				'type'			=> 'textarea',
				'scope'			=>	array( 'citation-chapter' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'citation-publication-pages',
				'label'			=> __( 'Pages' ),
				'type'			=> 'text',
				'scope'			=> array( 'citation-article' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'citation-publisher',
				'label'			=> __( 'Publisher' ),
				'type'			=> 'text',
				'scope'			=>	array( 'citation-book', 'citation-chapter', 'citation-paper' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'citation-place-of-publication',
				'label'			=> __( 'Place of publication' ),
				'type'			=> 'text',
				'scope'			=>	array( 'citation-book', 'citation-chapter', 'citation-paper' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'citation-url',
				'label'			=> __( 'URL' ),
				'type'			=> 'text',
				'scope'			=> array( 'citation' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	// Assign to evidence
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Assign to evidence',
		'id'		=> 'assign-to-evidence-box',
		'context'	=> 'side',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'citation-evidence',
				'label'			=> __( 'Evidence' ),
				'hide_label'	=> true,
				'type'			=> 'checkboxes',
				'options_type'	=> 'posts',
				'options_query'	=> array(
					'post_type'			=> 'evidence',
					'posts_per_page'	=> -1,
					'post_status'		=> 'any',
					'orderby'			=> 'title',
					'order'				=> 'ASC'
				),
				'required'		=> false,
				'single'		=> false,
				'scope'			=> array( 'citation' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	/* News and opinion
	**************************************************************************/

	/* Assign to studies */
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Studies',
		'id'		=> 'post-study-box',
		'context'	=> 'side',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'post-study',
				'label'			=> __( 'Studies' ),
				'hide_label'	=> true,
				'type'			=> 'checkboxes',
				'options_type'	=> 'posts',
				'options_query'	=> array(
					'post_type'			=> 'study',
					'posts_per_page'	=> -1,
					'orderby'			=> 'title',
					'order'				=> 'ASC'
				),
				'required'		=> false,
				'single'		=> false,
				'scope'			=> array( 'post' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	// Opinion details
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Opinion details',
		'id'		=> 'Opinion-details-box',
		'context'	=> 'side',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'opinion',
				'label'			=> __( 'Opinion' ),
				'description'	=> __( 'By default, posts are news. Check this box to make it opinion.' ),
				'type'			=> 'checkbox',
				'scope'			=>	array( 'post' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'author',
				'label'			=> __( 'Author' ),
				'description'	=> __( 'This will be output with opinion posts.' ),
				'type'			=> 'text',
				'scope'			=>	array( 'post' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	/* Data resources landing (with some Evidence impact landing)
	**************************************************************************/

	// Featured content
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Featured content',
		'id'		=> 'featured-content-box',
		'context'	=> 'above-content',
		'fields'	=> array(
			array(
				'name'					=> 'featured-image',
				'label'					=> __( 'Image' ),
				'type'					=> 'file',
				'file_restrict_to_type' => 'image',
				'file_button_label' 	=> __( 'Select image' ),
				'file_dialog_title'		=> __( 'Select image' ),
				'scope'					=> array( 'template' => array( 'page_data-resources.php', 'page_evidence-impact.php' ) ),
				'capabilities'			=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'featured-video',
				'label'			=> __( 'Video URL' ),
				'type'			=> 'text',
				'description'	=> __( 'A video will be used only if there\'s no image given.' ),
				'scope'			=> array( 'template' => array( 'page_data-resources.php', 'page_evidence-impact.php' ) ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'featured-heading',
				'label'			=> __( 'Heading' ),
				'type'			=> 'text',
				'scope'			=> array( 'template' => array( 'page_data-resources.php', 'page_evidence-impact.php' ) ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'featured-text',
				'label'			=> __( 'Text' ),
				'type'			=> 'text',
				'scope'			=> array( 'template' => array( 'page_data-resources.php', 'page_evidence-impact.php' ) ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'featured-url',
				'label'			=> __( 'Link URL' ),
				'type'			=> 'text',
				'scope'			=> array( 'template' => array( 'page_data-resources.php', 'page_evidence-impact.php' ) ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'featured-download',
				'label'			=> __( 'Download' ),
				'type'			=> 'select',
				'options_type'	=> 'posts',
				'options_query'	=> array(
					'post_type'			=> 'attachment',
					'posts_per_page'	=> -1,
					'orderby'			=> 'title',
					'order'				=> 'ASC',
					'post_status'		=> 'all',
					'post_mime_type'	=> $closer_download_mime_types
				),
				'description'	=> __( 'A download will be used only if there\'s no link URL given.' ),
				'scope'			=> array( 'template' => array( 'page_data-resources.php', 'page_evidence-impact.php' ) ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	// Featured evidence
	$featured_evidence_fields = array();
	for ( $i = 1; $i <= 2; $i++ ) {
		$featured_evidence_fields[] = array(
			'name'			=> 'featured-evidence-' . $i,
			'label'			=> __( 'Featured evidence' ) . ' ' . $i,
			'type'			=> 'select',
			'options_type'	=> 'posts',
			'options_query'	=> array(
				'post_type'			=> 'evidence',
				'posts_per_page'	=> -1,
				'orderby'			=> 'title',
				'order'				=> 'ASC',
				'post_status'		=> 'publish',
			),
			'scope'			=> array( 'template' => array( 'page_evidence-impact.php' ) ),
			'capabilities'	=> array( 'publish_pages' )
		);
	}
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Featured evidence',
		'id'		=> 'featured-evidence-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> $featured_evidence_fields
	));

	// Why longitudinal data
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Why longitudinal data',
		'id'		=> 'why-longitudinal-data-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'why-longitudinal-data-text',
				'label'			=> __( 'Intro text' ),
				'type'			=> 'textarea',
				'height'		=> 4,
				'scope'			=> array( 'template' => array( 'page_data-resources.php' ) ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'					=> 'why-longitudinal-data-image',
				'label'					=> __( 'Image' ),
				'type'					=> 'file',
				'file_restrict_to_type' => 'image',
				'file_button_label' 	=> __( 'Select image' ),
				'file_dialog_title'		=> __( 'Select image' ),
				'scope'					=> array( 'template' => array( 'page_data-resources.php' ) ),
				'capabilities'			=> array( 'publish_pages' )
			),
		)
	));

	// Explore the studies
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Explore the studies',
		'id'		=> 'explore-studies-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'explore-text',
				'label'			=> __( 'Intro text' ),
				'type'			=> 'textile',
				'scope'			=> array( 'template' => array( 'page_data-resources.php' ) ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'					=> 'explore-timeline-image',
				'label'					=> __( 'Timeline image' ),
				'type'					=> 'file',
				'file_restrict_to_type' => 'image',
				'file_button_label' 	=> __( 'Select image' ),
				'file_dialog_title'		=> __( 'Select image' ),
				'description'			=> __( 'This image should be 690 x 337 px.' ),
				'scope'					=> array( 'template' => array( 'page_data-resources.php' ) ),
				'capabilities'			=> array( 'publish_pages' )
			),
		)
	));

	// Explore the evidence
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Explore the evidence',
		'id'		=> 'explore-evidence-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'explore-text',
				'label'			=> __( 'Intro text' ),
				'type'			=> 'textile',
				'scope'			=> array( 'template' => array( 'page_evidence-impact.php' ) ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'					=> 'explore-evidence-image',
				'label'					=> __( 'Evidence image' ),
				'type'					=> 'file',
				'file_restrict_to_type' => 'image',
				'file_button_label' 	=> __( 'Select image' ),
				'file_dialog_title'		=> __( 'Select image' ),
				'description'			=> __( 'This image should be 690 x 337 px.' ),
				'scope'					=> array( 'template' => array( 'page_evidence-impact.php' ) ),
				'capabilities'			=> array( 'publish_pages' )
			),
		)
	));

	// How to access the data
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'How to access the data',
		'id'		=> 'how-access-data-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'how-access-data-text',
				'label'			=> __( 'Intro text' ),
				'type'			=> 'textarea',
				'height'		=> 4,
				'scope'			=> array( 'template' => array( 'page_data-resources.php' ) ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'					=> 'how-access-data-image',
				'label'					=> __( 'Image' ),
				'type'					=> 'file',
				'file_restrict_to_type' => 'image',
				'file_button_label' 	=> __( 'Select image' ),
				'file_dialog_title'		=> __( 'Select image' ),
				'scope'					=> array( 'template' => array( 'page_data-resources.php' ) ),
				'capabilities'			=> array( 'publish_pages' )
			),
		)
	));

	// Contextual database
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Contextual database',
		'id'		=> 'contextual-database-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'contextual-database-text',
				'label'			=> __( 'Intro text' ),
				'type'			=> 'textarea',
				'height'		=> 4,
				'scope'			=> array( 'template' => array( 'page_data-resources.php' ) ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'					=> 'contextual-database-image',
				'label'					=> __( 'Image' ),
				'type'					=> 'file',
				'file_restrict_to_type' => 'image',
				'file_button_label' 	=> __( 'Select image' ),
				'file_dialog_title'		=> __( 'Select image' ),
				'scope'					=> array( 'template' => array( 'page_data-resources.php' ) ),
				'capabilities'			=> array( 'publish_pages' )
			),
		)
	));

	// Research
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Research opportunities',
		'id'		=> 'research-opportunities-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'research-text',
				'label'			=> __( 'Intro text' ),
				'type'			=> 'textarea',
				'height'		=> 4,
				'scope'			=> array( 'template' => array( 'page_data-resources.php' ) ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	// Featured events
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Featured events',
		'id'		=> 'featured-events-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'featured-event-1',
				'label'			=> __( 'Featured event 1' ),
				'type'			=> 'select',
				'options_type'	=> 'posts',
				'options_query'	=> array(
					'post_type'			=> 'event',
					'posts_per_page'	=> -1,
					'orderby'			=> 'title',
					'order'				=> 'ASC',
					'meta_query'		=> array(
						array(
							'key'		=> slt_cf_field_key( 'event_date' ),
							'value'		=> date( 'Y/m/d' ),
							'compare'	=> '>='
						)
					)
				),
				'scope'			=> array( 'template' => array( 'page_data-resources.php', 'page_evidence-impact.php' ) ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'featured-event-2',
				'label'			=> __( 'Featured event 2' ),
				'type'			=> 'select',
				'options_type'	=> 'posts',
				'options_query'	=> array(
					'post_type'			=> 'event',
					'posts_per_page'	=> -1,
					'orderby'			=> 'title',
					'order'				=> 'ASC',
					'meta_query'		=> array(
						array(
							'key'		=> slt_cf_field_key( 'event_date' ),
							'value'		=> date( 'Y/m/d' ),
							'compare'	=> '>='
						)
					)
				),
				'scope'			=> array( 'template' => array( 'page_data-resources.php', 'page_evidence-impact.php' ) ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	/* Contextual data
	**************************************************************************/

	// Intro and main graph
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Intro and interactive graph',
		'id'		=> 'intro-graph-box',
		'context'	=> 'above-content',
		'fields'	=> array(
			array(
				'name'			=> 'data-intro',
				'label'			=> __( 'Intro' ),
				'type'			=> 'textile',
				'scope'			=>	array( 'contextual_data' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'data-graph',
				'label'			=> __( 'Graph embed code' ),
				'type'			=> 'textarea',
				'scope'			=>	array( 'contextual_data' ),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'data-downloads-notice',
				'type'			=> 'notice',
				'scope'			=>	array( 'contextual_data' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	// Key dates
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Key dates',
		'id'		=> 'key-dates-box',
		'context'	=> 'side',
		'description'	=> '<p>Please enter dates in the following format, with a blank line between each key date\'s details:</p><p>Date<br>Title<br>Text<br>"Link text":Link URL</p><p>If you want to get the URL for a Media Library file, use the file selector at the bottom.</p>',
		'fields'	=> array(
			array(
				'name'			=> 'key-dates',
				'label'			=> __( 'Key dates' ),
				'hide_label'	=> true,
				'type'			=> 'textarea',
				'height'		=> 30,
				'scope'			=>	array( 'contextual_data' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	// Contextual data
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Related contextual data',
		'id'		=> 'contextual-data-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'related-contextual-data',
				'label'			=> __( 'Contextual data' ),
				'hide_label'	=> true,
				'type'			=> 'checkboxes',
				'options_type'	=> 'posts',
				'options_query'	=> array(
					'post_type'			=> 'contextual_data',
					'posts_per_page'	=> -1,
					'orderby'			=> 'title',
					'order'				=> 'ASC',
					'post__not_in'		=> array( '[OBJECT_ID]' )
				),
				'single'		=> false,
				'width'			=> 20,
				'scope'			=> array( 'contextual_data' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	/* Publications landing
	**************************************************************************/

	// Featured resource
	slt_cf_register_box( array(
		'type'			=> 'post',
		'title'			=> 'Featured resource',
		'id'			=> 'featured-resource-box',
		'context'		=> 'normal',
		'priority'		=> 'high',
		'description'	=> __( 'The intro text with this will be pulled from the text in the content editor for the selected publication, and will be cut off if longer than 60 words.' ),
		'fields'	=> array(
			array(
				'name'			=> 'featured-resource',
				'label'			=> __( 'Featured resource' ),
				'hide_label'	=> true,
				'type'			=> 'select',
				'options_type'	=> 'posts',
				'options_query'	=> array(
					'post_type'			=> 'publication',
					'posts_per_page'	=> -1,
					'orderby'			=> 'title',
					'order'				=> 'ASC',
				),
				'scope'			=> array( 'template' => array( 'page_publications-video.php' ) ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	// Other featured resources
	$featured_resources_fields = array();
	foreach ( $closer_terms as $closer_term ) {
		if ( $closer_term->taxonomy == 'publication_type' ) {
			$featured_resources_fields[] = array(
				'name'			=> 'featured-resource-' . $closer_term->slug,
				'label'			=> __( 'Featured' ) . ' ' . $closer_term->name,
				'hide_label'	=> false,
				'type'			=> 'select',
				'options_type'	=> 'posts',
				'options_query'	=> array(
					'post_type'			=> 'publication',
					'posts_per_page'	=> -1,
					'orderby'			=> 'date',
					'order'				=> 'DESC',
					'tax_query'			=> array(
						array(
							'taxonomy'		=> 'publication_type',
							'terms'			=> $closer_term->term_id
						)
					)
				),
				'required'		=> false,
				'scope'			=> array( 'template' => array( 'page_publications-video.php' ) ),
				'capabilities'	=> array( 'publish_pages' )
			);
		}
	}
	slt_cf_register_box( array(
		'type'			=> 'post',
		'title'			=> 'Other featured resources',
		'id'			=> 'other-featured-resources-box',
		'context'		=> 'normal',
		'priority'		=> 'high',
		'description'	=> __( 'If a resource is picked for a publication type, that resource will be at the top of the list displayed (unless the user is filtering the list).' ),
		'fields'		=> $featured_resources_fields
	));

	/* Publications
	**************************************************************************/

	// Video URL
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Video URL',
		'id'		=> 'video-url-box',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'video-url',
				'label'			=> __( 'URL' ),
				'hide_label'	=> true,
				'type'			=> 'text',
				'scope'			=> array( 'publication_video' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	/* Media
	**************************************************************************/

	// Assign to content
	slt_cf_register_box( array(
		'type'		=> 'attachment',
		'title'		=> 'Assign to content',
		'id'		=> 'assign-to-content-box',
		'context'	=> 'above-content',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'publication',
				'label'			=> __( 'Publication' ),
				'type'			=> 'select',
				'options_type'	=> 'posts',
				'options_query'	=> array(
					'post_type'			=> 'publication',
					'posts_per_page'	=> -1,
					'post_status'		=> 'any',
					'tax_query'		=> array(
						array(
							'taxonomy'	=> 'publication_type',
							'field'		=> 'slug',
							'terms'		=> 'video',
							'operator'	=> 'NOT IN'
						),
						'orderby'			=> 'title',
						'order'				=> 'ASC'
					)
				),
				'scope'			=> array(),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'contextual_data',
				'label'			=> __( 'Contextual data' ),
				'type'			=> 'select',
				'options_type'	=> 'posts',
				'options_query'	=> array(
					'post_type'			=> 'contextual_data',
					'posts_per_page'	=> -1,
					'post_status'		=> 'any',
					'orderby'			=> 'title',
					'order'				=> 'ASC'
				),
				'scope'			=> array(),
				'capabilities'	=> array( 'publish_pages' )
			),
			array(
				'name'			=> 'event',
				'label'			=> __( 'Past event' ),
				'type'			=> 'select',
				'options_type'	=> 'posts',
				'options_query'	=> array(
					'post_type'			=> 'event',
					'posts_per_page'	=> -1,
					'post_status'		=> 'any',
					'orderby'			=> 'title',
					'order'				=> 'ASC',
					'meta_query'		=> array(
						array(
							'key'		=> slt_cf_field_key( 'event_date' ),
							'value'		=> date( 'Y/m/d' ),
							'compare'	=> '<'
						)
					)
				),
				'scope'			=> array(),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

	/* Related content
	*****************************************************************/

	$related_content_fields = array();
	for ( $i = 1; $i <= 3; $i++ ) {
		$related_content_fields[] = array(
			'name'					=> 'related-content-' .$i,
			'label'					=> __( 'Related content' ) . ' ' . $i,
			'type'					=> 'select',
			'options_type'			=> 'posts',
			'options_query'			=> array(
				'post_type'				=> $closer_related_content_post_types,
				'posts_per_page'		=> -1,
				'orderby'				=> 'title',
				'order'					=> 'ASC'
			),
			'group_by_post_type'	=> true,
			'scope'					=> array( 'contextual_data', 'evidence', 'level-2', 'study' ),
			'capabilities'			=> array( 'publish_posts' )
		);
	}
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Related content',
		'id'		=> 'related-content-box',
		'context'	=> 'normal',
		'fields'	=> $related_content_fields
	));

}


/**
 * Any meta boxes that are too custom for DCF
 *
 * @since	CLOSER 0.1
 */
add_action( 'add_meta_boxes', 'closer_add_meta_boxes', 10, 2 );
function closer_add_meta_boxes( $post_type, $post ) {

	// List of files assigned to publications / contextual data / past events
	if ( $post_type == 'publication' && ! has_term( 'video', 'publication_type' ) ) {
		add_meta_box(
			'closer-assigned-publication-files',
			__( 'Assigned downloads' ),
			'closer_meta_box_assigned_content_files',
			'publication',
			'normal',
			'high'
		);
	}
	if ( $post_type == 'contextual_data' ) {
		add_meta_box(
			'closer-assigned-data-files',
			__( 'Assigned data downloads' ),
			'closer_meta_box_assigned_content_files',
			'contextual_data',
			'normal',
			'high'
		);
	}
	if ( $post_type == 'event' ) {
		add_meta_box(
			'closer-assigned-resource-files',
			__( 'Assigned download resources' ),
			'closer_meta_box_assigned_content_files',
			'event',
			'normal',
			'high'
		);
	}

	// List of citations assigned to evidence
	if ( $post_type == 'evidence' ) {
		add_meta_box(
			'closer-assigned-citations',
			__( 'Assigned citations' ),
			'closer_meta_box_assigned_citations',
			'evidence',
			'normal',
			'high'
		);
	}

}


/**
 * List of files assigned to content
 *
 * @since	CLOSER 0.1
 */
function closer_meta_box_assigned_content_files() {
	if ( function_exists( 'slt_cf_field_key' ) ) {
		global $post;

		// Get files
		$files = get_posts( array(
			'post_type'			=> 'attachment',
			'posts_per_page'	=> -1,
			'meta_query'		=> array(
				array(
					'key'			=> slt_cf_field_key( get_post_type( $post ) ),
					'value'			=> $post->ID
				)
			),
			'orderby'			=> 'title',
			'order'				=> 'ASC'
		));

		if ( $files ) {

			echo '<ul class="assigned-files">';

			foreach ( $files as $file ) {
				echo '<li><img src="' . pilau_file_type_icon( $file->post_mime_type ) . '"> &nbsp;<a title="Click to edit this file" href="' . get_edit_post_link( $file->ID ) . '">' . $file->post_title . '</a> (' . strtoupper( pathinfo( $file->guid, PATHINFO_EXTENSION ) ) . ')</li>';
			}

			echo '</ul>';


		} else {

			echo '<p><em>No files currently assigned.</em></p>';

		}

		echo '<p>To assign files, save this content (as a draft if necessary), then upload them through <a href="' . admin_url( 'media-new.php' ) . '"><b>Media Library &gt; Add New</b></a>. Edit each file straight after uploading, and select this content in the <b>Assign to content</b> box.</p>';

	}
}


/**
 * List of citations assigned to evidence
 *
 * @since	CLOSER 0.1
 */
function closer_meta_box_assigned_citations() {
	if ( function_exists( 'slt_cf_field_key' ) ) {
		global $post;

		// Get citations
		$citations = get_posts( array(
			'post_type'			=> 'citation',
			'posts_per_page'	=> -1,
			'post_status'		=> 'any',
			'meta_query'		=> array(
				array(
					'key'			=> slt_cf_field_key( 'citation-evidence' ),
					'value'			=> $post->ID
				)
			),
			'orderby'			=> 'title',
			'order'				=> 'ASC'
		));

		if ( $citations ) {

			echo '<ul class="assigned-citations">';

			foreach ( $citations as $citation ) {
				$citation_meta = slt_cf_all_field_values( 'post', $citation->ID );
				echo '<li><a href="' . get_edit_post_link( $citation->ID ) . '" title="Click to edit this citation">' .  $citation->post_title . '</a>. ' . $citation_meta['citation-authors'] .' (' . $citation_meta['citation-date'] . '). ';
				if ( $citation_meta['citation-url'] ) {
					echo '<a target="_blank" title="Click to visit this citation online" href="' . $citation_meta['citation-url'] . '">';
				}
				echo $citation_meta['citation-publication-title'];
				if ( $citation_meta['citation-url'] ) {
					echo '</a>';
				}
				echo ' ' . $citation_meta['citation-publication-vol-issue'] . ', ' . $citation_meta['citation-publication-pages'] . '.</li>';
			}

			echo '</ul>';


		} else {

			echo '<p><em>No citations currently assigned to this evidence.</em></p>';

		}

	}
}


/**
 * Filter DCF defaults
 *
 * @since	CLOSER 0.1
 */
add_filter( 'slt_cf_default_value', 'closer_slt_cf_default_value', 10, 5 );
function closer_slt_cf_default_value( $default, $request_type, $object_id, $object, $field ) {

	// Default author to current user name
	if ( $request_type == 'post' && get_post_type( $object ) == 'post' && $field['name'] == 'author' ) {
		$current_user = wp_get_current_user();
		$default = $current_user->display_name;
	}

	return $default;
}


/**
 * Custom scope checking
 *
 * @since	CLOSER 0.1
 */
add_filter( 'slt_cf_check_scope', 'closer_slt_cf_check_scope', 10, 7 );
function closer_slt_cf_check_scope( $scope_match, $request_type, $scope, $object_id, $scope_key, $scope_value, $field ) {
	global $closer_citation_formats;
	$scope_value_parts = explode( '-', $scope_value );

	if ( count( $scope_value_parts ) == 2 && $scope_value_parts[0] == 'level' && ctype_digit( $scope_value_parts[1] ) ) {

		// Page levels
		if ( $request_type == 'post' && get_post_type( $object_id ) == 'page' ) {
			$ancestors = get_post_ancestors( $object_id );
			if ( ( count( $ancestors ) + 1 ) == (int) $scope_value_parts[1] ) {
				$scope_match = true;
			}
		}

	} else if ( $request_type == 'post' && get_post_type( $object_id ) == 'publication'  ) {

		// Video / non-video
		$is_video = has_term( 'video', 'publication_type', $object_id );
		if ( ( $is_video && $scope_value == 'publication_video' ) || ( ! $is_video && $scope_value == 'publication_non_video' ) ) {
			$scope_match = true;
		}

	} else if ( $request_type == 'post' && get_post_type( $object_id ) == 'citation' && count( $scope_value_parts ) == 2 && $scope_value_parts[0] == 'citation' ) {

		// Citation formats
		$format = slt_cf_field_value( 'citation-format', 'post', $object_id );
		if ( ! $format && $scope_value_parts[1] == 'article' ) {
			// If no format set, default to article
			$scope_match = true;
		} else {
			// Check against each format
			foreach ( $closer_citation_formats as $label => $slug ) {
				if ( $scope_value_parts[1] == $slug && $format == $slug ) {
					$scope_match = true;
				}
			}
		}

	}

	return $scope_match;
}


/**
 * Custom field checker
 *
 * @since	CLOSER 0.1
 *
 * @param	string	$field	The field name to check
 * @param	mixed	$value	The value to check against (returns true if the field value is equivalent)
 * @param	boolean	$notset	The return value if the field isn't set at all (defaults to false)
 * @return	boolean
 */
function pilau_custom_field_check( $field, $value = true, $notset = false ) {
	global $pilau_custom_fields;
	$result = false;
	if ( isset( $pilau_custom_fields[ $field ] ) ) {
		$result = ( $pilau_custom_fields[ $field ] == $value );
	} else {
		$result = $notset;
	}
	return $result;
}


/**
 * Filter the DCF boxes
 *
 * @since	CLOSER 0.1
 */
add_filter( 'slt_cf_init_boxes', 'closer_slt_cf_init_boxes' );
function closer_slt_cf_init_boxes( $boxes ) {
	global $closer_download_mime_types;

	// Find the key dates box for contextual data
	foreach ( $boxes as &$box ) {
		if ( $box['id'] == 'key-dates-box' ) {
			$file_selector = '';

			// Get download files
			$downloads = get_posts( array(
				'post_type'			=> 'attachment',
				'posts_per_page'	=> -1,
				'post_status'		=> 'any',
				'post_mime_type'	=> $closer_download_mime_types,
				'orderby'			=> 'title',
				'order'				=> 'ASC'
			));

			if ( $downloads ) {

				foreach ( $downloads as $download ) {
					$file_selector .= '<option value="' . wp_get_attachment_url( $download->ID ) . '">' . $download->post_title . ' (' . pilau_format_filesize( get_attached_file( $download->ID ) ) . ' '  . strtoupper( pilau_simple_file_type( get_post_mime_type( $download ) ) ) . ')</option>';
				}
				$file_selector = '<div class="pilau-file-selector"><select name="file-selector" class="pfs-files" multiple>' . $file_selector . '</select><br><input type="button" value="' . __( 'Get file URL' ) . '" class="button"><br><input type="text" class="regular-text pfs-url"></div>';

			} else {

				$file_selector = __( 'No files to choose from.' );

			}

			// Add file selector to field description
			$box['fields'][0]['description'] = $file_selector;

		}
	}

	return $boxes;
}