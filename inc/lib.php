<?php

/**
 * Library of general helper functions
 *
 * @package	CLOSER-2022
 * @since	0.1
 */


/**
 * Get all taxonomy terms (cached)
 *
 * Cached because query-string-driven listing pages won't be cached by page caching
 *
 * @since	CLOSER 0.1
 * @return	array
 */
function closer_get_terms() {
	if ( false === ( $terms = get_transient( 'closer_terms' ) ) || isset( $_GET['refresh'] ) ) {
		$terms = array();

		// First get all terms
		$all_terms = get_terms( array_keys( get_taxonomies() ) );

		foreach ( $all_terms as $a_term ) {

			// Assign top-level terms
			if ( $a_term->parent == 0 ) {
				$terms[] = $a_term;

				// Line up any children of this term next
				foreach ( $all_terms as $b_term ) {
					if ( $b_term->parent == $a_term->term_id ) {
						$terms[] = $b_term;
					}
				}

			}

		}

		// Cache for 24 hours
		set_transient( 'closer_terms', $terms, 60*60*24 );

	}
	return $terms;
}


/**
 * Output button-based filters
 *
 * @since	CLOSER 0.1
 * @param	array	$taxonomies
 * @return	void
 */
function closer_button_filters( $taxonomies ) {
	global $closer_terms, $closer_filters;
	$something_selected = false;

	?>

	<form action="" method="get" class="button-filters">

		<div class="wrapper">

			<div class="button-lists clearfix">

				<?php $i = 1; ?>
				<?php foreach ( $taxonomies as $taxonomy ) { ?>
					<?php $selected_terms[ $taxonomy ] = array(); ?>

					<div class="button-list">

						<h2><?php echo ucfirst( str_replace( '_', ' ', $taxonomy ) ); ?>s</h2>

						<ul class="buttons clearfix">

							<?php foreach ( $closer_terms as $term ) { ?>

								<?php if ( $term->taxonomy == $taxonomy ) { ?>

									<?php

									$classes = array();
									$term_selected = is_array( $closer_filters[ $taxonomy ] ) && in_array( $term->term_id, $closer_filters[ $taxonomy ] );
									if ( $term_selected ) {
										$classes[] = 'selected';
										$selected_terms[ $taxonomy ][ $term->term_id ] = $term->name;
										$something_selected = true;
									}

									?>

									<li id="filter-<?php echo $term->term_id ?>" class="<?php echo implode( ' ', $classes ); ?>"><label for="<?php echo $taxonomy . '_' . $term->term_id; ?>"><span class="label"><?php echo $term->name; ?> </span><span class="checkbox"><input class="button" type="checkbox" value="<?php echo $term->term_id; ?>" name="<?php echo $taxonomy; ?>[]" id="<?php echo $taxonomy . '_' . $term->term_id; ?>"<?php checked( $term_selected ); ?>><span class="indicators"><span class="indicator-off">+</span><span class="indicator-on">&ndash;</span></span></span></label></li>

								<?php } ?>
							<?php } ?>
						</ul>

					</div>

					<?php $i++; ?>
				<?php } ?>

			</div>

			<div class="buttons">
				<input type="submit" class="form-button apply-filters" value="<?php _e( 'Apply filters' ); ?>">
				<?php if ( $something_selected ) { ?>
					<a href="<?php echo pilau_get_current_url( false, true ); ?>" class="form-button button"><?php _e( 'Clear filters' ); ?></a>
				<?php } ?>
			</div>

		</div>

	</form>

	<?php

}


/*
 * Output tabbed button-based filters
 *
 * @since	CLOSER 0.1
 * @param	array	$taxonomies
 * @return	void
function closer_button_filters( $taxonomies ) {
	global $closer_terms, $closer_filters;
	$selected_terms = array(); // Used to collect selected values to list at end
	$something_selected = false;

	// Initialize tab
	$current_tab = isset( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : $taxonomies[0];

	?>

	<form action="" method="get" class="button-filters">

		<ul class="hide-if-no-js tabs tabs-<?php echo count( $taxonomies ); ?> wrapper limit-width">
			<?php foreach ( $taxonomies as $taxonomy ) { ?>
				<li id="tab-<?php echo $taxonomy; ?>"<?php if ( $current_tab == $taxonomy ) echo ' class="current"'; ?>><a class="tab" data-tab="<?php echo $taxonomy; ?>" href="#panel-<?php echo $taxonomy; ?>"><span class="icon-tab-state"></span> <?php echo ucfirst( str_replace( '_', ' ', $taxonomy ) ); ?>s</a></li>
			<?php } ?>
		</ul>

		<div class="panels indent">
			<div class="wrapper">

				<?php $i = 1; ?>
				<?php foreach ( $taxonomies as $taxonomy ) { ?>
					<?php $selected_terms[ $taxonomy ] = array(); ?>

					<div class="panel <?php if ( $current_tab == $taxonomy ) echo ' current'; ?>" id="panel-<?php echo $taxonomy; ?>">

						<h2 class="hide-if-js"><?php echo ucfirst( str_replace( '_', ' ', $taxonomy ) ); ?>s</h2>

						<ul class="clearfix">
							<?php foreach ( $closer_terms as $term ) { ?>

								<?php if ( $term->taxonomy == $taxonomy ) { ?>

									<?php

									$classes = array();
									$term_selected = is_array( $closer_filters[ $taxonomy ] ) && in_array( $term->term_id, $closer_filters[ $taxonomy ] );
									if ( $term_selected ) {
										$classes[] = 'selected';
										$selected_terms[ $taxonomy ][ $term->term_id ] = $term->name;
										$something_selected = true;
									}

									?>

									<li id="filter-<?php echo $term->term_id ?>" class="<?php echo implode( ' ', $classes ); ?>"><label for="<?php echo $taxonomy . '_' . $term->term_id; ?>"><span class="label"><?php echo $term->name; ?> </span><span class="checkbox"><input class="button" type="checkbox" value="<?php echo $term->term_id; ?>" name="<?php echo $taxonomy; ?>[]" id="<?php echo $taxonomy . '_' . $term->term_id; ?>"<?php checked( $term_selected ); ?>><span class="indicators"><span class="indicator-off">+</span><span class="indicator-on">&ndash;</span></span></span></label></li>

								<?php } ?>
							<?php } ?>
						</ul>

					</div>

					<?php $i++; ?>
				<?php } ?>

			</div>

		</div>

		<div class="wrapper indent-small">

			<div class="selected-lists clearfix"<?php if ( ! $something_selected ) echo ' style="display:none"'; ?>>

				<h2><?php _e( 'Filter by:' ); ?></h2>

				<?php foreach ( $selected_terms as $taxonomy => $terms ) { ?>

					<div class="taxonomy <?php echo $taxonomy; ?>"<?php if ( ! $terms ) echo ' style="display:none"'; ?>>

						<h3><?php echo ucfirst( str_replace( '_', ' ', $taxonomy ) ); ?>s</h3>

						<ul>
							<?php foreach ( $terms as $term_id => $term_name ) { ?><li class="term-<?php echo $term_id; ?>"><?php echo $term_name; ?></li><?php } ?>
						</ul>

					</div>

				<?php } ?>

			</div>

			<div class="buttons">
				<input type="submit" class="form-button apply-filters<?php if ( ! $something_selected ) echo ' hide-if-js'; ?>" value="<?php _e( 'Apply filters' ); ?>">
				<?php if ( $something_selected ) { ?>
					<a href="<?php echo pilau_get_current_url( false, true ); ?>" class="form-button button"><?php _e( 'Clear filters' ); ?></a>
				<?php } ?>
				<input type="hidden" name="tab" value="<?php echo $current_tab; ?>">
			</div>

		</div>

	</form>

	<?php

}
 */


/**
 * Output filter drop-down
 *
 * @since	CLOSER 0.1
 * @param	mixed	$taxonomy	Name of taxonomy or array of options
 * @param	string	$filter_label
 * @param	string	$all_label
 * @return	void
 */
function closer_filter_select( $taxonomy, $filter_label, $all_label = false, $name = '' ) {
	global $closer_terms, $closer_filters;
	$options = array();
	$is_tax = true;

	if ( is_string( $taxonomy ) ) {
		$name = $taxonomy;
		foreach ( $closer_terms as $closer_term ) {
			if ( $closer_term->taxonomy == $taxonomy ) {
				$label = $closer_term->name;
				if ( $closer_term->parent != 0 ) {
					$label = '&mdash; ' . $label;
				}
				$options[ $closer_term->term_id ] = $label;
			}
		}
	} else if ( is_array( $taxonomy ) ) {
		$options = $taxonomy;
		$is_tax = false;
	}

	?>

	<div class="filter_row filter-select">
		<label for="<?php echo $name; ?>"><?php echo $filter_label; ?></label>
		<select id="<?php echo $name; ?>" name="<?php echo $name; ?>" class="input">
			<?php if ( $all_label ) { ?>
				<option value=""><?php echo $all_label; ?></option>
			<?php } ?>
			<?php foreach ( $options as $value => $label ) { ?>
				<option value="<?php echo $value; ?>"<?php
					if ( $is_tax && is_array( $closer_filters[ $taxonomy ] ) ) {
						selected( in_array( $value, $closer_filters[ $taxonomy ] ) );
					} else {
						selected( ( is_array( $closer_filters[ $name ] ) && in_array( $value, $closer_filters[ $name ] ) ) || $closer_filters[ $name ] == $value );
					}
				?>><?php echo $label; ?></option>
			<?php } ?>
		</select>
	</div>

	<?php
}

function closer_filter_select_new( $taxonomy, $filter_label, $all_label = false, $name = '' ) {
	global $closer_terms, $closer_filters;
	$options = array();
	$is_tax = true;

	if ( is_string( $taxonomy ) ) {
		$name = $taxonomy;
		foreach ( $closer_terms as $closer_term ) {
			if ( $closer_term->taxonomy == $taxonomy ) {
				$label = $closer_term->name;
				if ( $closer_term->parent != 0 ) {
					$label = '&mdash; ' . $label;
				}
				$options[ $closer_term->slug ] = $label;
			}
		}
	} else if ( is_array( $taxonomy ) ) {
		$options = $taxonomy;
		$is_tax = false;
	}

	?>

	<div class="filter_row filter-select">
		<label for="<?php echo $name; ?>"><?php echo $filter_label; ?></label>
		<select id="<?php echo $name; ?>" name="<?php echo $name; ?>" class="input">
			<?php if ( $all_label ) { ?>
				<option value=""><?php echo $all_label; ?></option>
			<?php } ?>
			<?php foreach ( $options as $value => $label ) { ?>
				<option value="<?php echo $value; ?>"<?php
					if ( $is_tax && is_array( $closer_filters[ $taxonomy ] ) ) {
						selected( in_array( $value, $closer_filters[ $taxonomy ] ) );
					} else {
						selected( ( is_array( $closer_filters[ $name ] ) && in_array( $value, $closer_filters[ $name ] ) ) || $closer_filters[ $name ] == $value );
					}
				?>><?php echo $label; ?></option>
			<?php } ?>
		</select>
	</div>

	<?php
}


/**
 * Clear filters button
 *
 * @since	CLOSER 0.1
 * @return	void
 */
function closer_clear_filters_button() {
	global $closer_filters_applied;

	if ( $closer_filters_applied ) { ?>
		<a href="<?php echo pilau_get_current_url( false, true ); ?>" class="form-button button"><?php _e( 'Clear filters' ); ?></a>
	<?php }

}


/**
 * List studies
 *
 * @since	CLOSER 0.1
 * @param	array	$studies	Array of post objects or IDs
 * @param	bool	$maybe_more
 * @return	void
 */
function closer_list_studies( $studies, $maybe_more = false ) {

	// Need to get posts?
	if ( isset( $studies[0] ) && ctype_digit( $studies[0] ) ) {
		$studies = get_posts( array(
			'post_type'			=> 'study',
			'posts_per_page'	=> -1,
			'post__in'			=> $studies,
			'orderby'			=> 'post__in',
			//'pilau_multiply'	=> 4,
			//'suppress_filters'	=> false
		));
	}

	if ( $studies ) {

		foreach ( $studies as $study ) {

			$classes = array( 'study' );
			if ( $maybe_more ) {
				$classes[] = 'maybe-more-row';
			}

			// echo '<div class="teaser-study"><a href="' . get_permalink( $study->ID ) . '" class="block-wrapper clearfix">' . "\n";
			echo '<div class="teaser-study"><div class="top">' . "\n";

			$alt_title = null;
			if ( function_exists( 'slt_cf_field_value' ) ) {
				$alt_title = slt_cf_field_value( 'study-alt-title', 'post', $study->ID );
			}

			echo '<h2><a href="' . get_permalink( $study->ID ) . '" class="block-wrapper clearfix">' . get_the_title( $study ) . '</a></h2>' . "\n";

			echo '</div><div class="bottom"><div class="image-wrap"><img src="' . pilau_get_featured_image_url( $study->ID, 'post-thumbnail' ) . '" alt="' . get_the_title( $study ) . ' image"></div>' . "\n";

			echo '<div class="teaser-content">';
						if ( $alt_title ) {
				echo '<h3>' . $alt_title . '</h3>' . "\n";
			}
			
			echo '<p>' . pilau_teaser_text( $study->ID ) . '</p>';

			echo '</div></div></div>' . "\n";
					
		}

	}

}

function closer_list_studies_carousel( $studies, $maybe_more = false ) {

	// Need to get posts?
	if ( isset( $studies[0] ) && ctype_digit( $studies[0] ) ) {
		$studies = get_posts( array(
			'post_type'			=> 'study',
			'posts_per_page'	=> -1,
			'post__in'			=> $studies,
			'orderby'			=> 'post__in',
			//'pilau_multiply'	=> 4,
			//'suppress_filters'	=> false
		));
	}

	if ( $studies ) {

		foreach ( $studies as $study ) {

			$classes = array( 'study' );
			if ( $maybe_more ) {
				$classes[] = 'maybe-more-row';
			}
			// echo '<div class="teaser-study"><a href="' . get_permalink( $study->ID ) . '" class="block-wrapper clearfix">' . "\n";
			echo '<div class="swiper-slide"><div class="relevant-studies-teaser">';
			echo '<div class="image-wrap"><img src="' . pilau_get_featured_image_url( $study->ID, 'post-thumbnail' ) . '" alt="' . get_the_title( $study ) . ' image"></div>' . "\n";

			echo '<div class="left-col">' . "\n";

			$alt_title = null;
			if ( function_exists( 'slt_cf_field_value' ) ) {
				$alt_title = slt_cf_field_value( 'study-alt-title', 'post', $study->ID );
			}

			echo '<h3>' . get_the_title( $study ) . '</h3>' . "\n";
			echo '<a class="button" href"' . get_permalink( $study->ID ) . '">Read more</a>' . "\n";
			
			echo '</div>';
			echo '</div></div>' . "\n";
					
		}

	}

}


/**
 * Output "content boxes"
 *
 * @since	CLOSER 0.1
 * @param	WP_Query		$query
 * @param	int				$cols
 * @param	object			$extra_at_start	An extra post to include at the start
 * @return	void
 */
function closer_content_boxes( $query, $cols = null, $extra_at_start = null ) {

	if ( $query->have_posts() || $extra_at_start ) {

		$i = 1;

		// Extra at start?
		if ( is_object( $extra_at_start ) ) {
			closer_content_box( $extra_at_start, $cols, $i );
			$i++;
		}

		while ( $query->have_posts() ) {
			$query->the_post();
			global $post;
			closer_content_box( $post, $cols, $i );
			$i++;
		}
	}
}

function closer_content_boxes_flex( $query, $cols = null, $extra_at_start = null ) {

	if ( $query->have_posts() || $extra_at_start ) {

		$i = 1;

		// Extra at start?
		if ( is_object( $extra_at_start ) ) {
			closer_content_box_flex( $extra_at_start, $cols, $i );
			$i++;
		}

		while ( $query->have_posts() ) {
			$query->the_post();
			global $post;
			closer_content_box_flex( $post, $cols, $i );
			$i++;
		}
	}
}

function closer_resource_boxes( $query, $cols = null, $extra_at_start = null ) {

	if ( $query->have_posts() || $extra_at_start ) {

		$i = 1;

		while ( $query->have_posts() ) {
			$query->the_post();
			global $post;
			closer_resource_box( $post, $cols, $i );
			$i++;
		}
	}

}

/**
 * Helper to output one content box
 *
 * @param	object	$post
 * @param	int		$cols
 * @param	int		$i
 * @return	void
 */

function closer_content_box( $post, $cols, $i ) {
	$link_extra_attributes = '';
	$link_classes = array( 'block-wrapper' );
	$link_title = __( 'Click to read more' );
	$permalink = get_permalink( $post );
	$is_video = ( get_post_type( $post ) == 'publication' && has_term( 'video', 'publication_type', $post ) );
	$classes = array( 'box' );
	// var_dump($post->ID);
	if ( $cols && ! ( $i % $cols ) ) {
		$classes[] = 'last';
	}
	closer_publication_video_init( $permalink, $is_video, $classes, $link_classes, $link_title, $link_extra_attributes, $post );

	// Open
	echo '<div class="' . implode( ' ', $classes ) . ' teaser-resource grid-item">';
	
	echo '<h3>' . get_the_title( $post ) . '</h3>';
	
	// Image
	if ( has_post_thumbnail( $post ) ) {
		echo '<div class="image">';
		echo '<img src="' . pilau_get_featured_image_url( $post->ID, 'new-content-box' ) . '" alt="' . get_the_title( $post ) . ' image">';
		echo '</div>';
	} else if ( get_post_type( $post ) == 'publication' && has_term( 'resource-report', 'publication_type', $post ) ) {
		echo '<div class="image resource-report"></div>';
	}

	// Theme / topic tags
	if ( $themes = get_the_terms( $post, 'theme' ) ) {
		echo '<div class="bullet-tags">';
		echo '<ul>';

		// Gather top-level themes and topics
		$themes_top_level = array();
		$theme_topics = array();
		foreach ( $themes as $theme ) {
			if ( $theme->parent == 0 ) {
				$themes_top_level[] = $theme;
			} else {
				if ( ! isset( $theme_topics[ $theme->parent ] ) ) {
					$theme_topics[ $theme->parent ] = array();
				}
				$theme_topics[ $theme->parent ][] = $theme;
			}
		}
		foreach ( $themes_top_level as $theme ) {
			echo '<li class="' . $theme->slug . '">' . $theme->name;

			// 2nd-level topics?
			if ( $theme_topics[ $theme->term_id ] ) {
				echo '<ul class="topics">';
				foreach ( $theme_topics[ $theme->term_id ] as $topic ) {
					echo '<li class="' . $theme->slug . '">' . $topic->name . '</li>';
				}
				echo '</ul>';
			}

			echo '</li>';
		}

		echo '</ul>';
		echo '</div>';
	}

	echo '<a class="button button-full" href="' . esc_url( $permalink ) . '" title="' . $link_title . '"' . $link_extra_attributes . '>Read more</a>';

	// Close all
	echo '</div>';

}

function closer_content_box_flex( $post, $cols, $i ) {
	$link_extra_attributes = '';
	$link_classes = array( 'block-wrapper' );
	$link_title = __( 'Click to read more' );
	$permalink = get_permalink( $post );
	$is_video = ( get_post_type( $post ) == 'publication' && has_term( 'video', 'publication_type', $post ) );
	$classes = array( 'box' );
	// var_dump($post->ID);
	if ( $cols && ! ( $i % $cols ) ) {
		$classes[] = 'last';
	}
	closer_publication_video_init( $permalink, $is_video, $classes, $link_classes, $link_title, $link_extra_attributes, $post );

	// Open
	echo '<div class="' . implode( ' ', $classes ) . ' teaser-resource content-stretch">';
	
	echo '<h3>' . get_the_title( $post ) . '</h3>';
	
	// Image
	if ( has_post_thumbnail( $post ) ) {
		echo '<div class="image">';
		echo '<img src="' . pilau_get_featured_image_url( $post->ID, 'new-content-box' ) . '" alt="' . get_the_title( $post ) . ' image">';
		echo '</div>';
	} else if ( get_post_type( $post ) == 'publication' && has_term( 'resource-report', 'publication_type', $post ) ) {
		echo '<div class="image resource-report"></div>';
	}

	// Theme / topic tags
	if ( $themes = get_the_terms( $post, 'theme' ) ) {
		echo '<div class="bullet-tags">';
		echo '<ul>';

		// Gather top-level themes and topics
		$themes_top_level = array();
		$theme_topics = array();
		foreach ( $themes as $theme ) {
			if ( $theme->parent == 0 ) {
				$themes_top_level[] = $theme;
			} else {
				if ( ! isset( $theme_topics[ $theme->parent ] ) ) {
					$theme_topics[ $theme->parent ] = array();
				}
				$theme_topics[ $theme->parent ][] = $theme;
			}
		}
		foreach ( $themes_top_level as $theme ) {
			echo '<li class="' . $theme->slug . '">' . $theme->name;

			// 2nd-level topics?
			if ( $theme_topics[ $theme->term_id ] ) {
				echo '<ul class="topics">';
				foreach ( $theme_topics[ $theme->term_id ] as $topic ) {
					echo '<li class="' . $theme->slug . '">' . $topic->name . '</li>';
				}
				echo '</ul>';
			}

			echo '</li>';
		}

		echo '</ul>';
		echo '</div>';
	}

	echo '<a class="button button-full" href="' . esc_url( $permalink ) . '" title="' . $link_title . '"' . $link_extra_attributes . '>Read more</a>';

	// Close all
	echo '</div>';

}

function closer_resource_box( $post, $cols, $i ) {
	global $closer_video_embeds;
	$link_extra_attributes = '';
	$link_classes = array( 'block-wrapper' );
	$link_title = __( 'Click to read more' );
	$permalink = get_permalink( $post );
	$post_terms = get_the_terms( $post, 'publication_type' );
	$post_term_name = $post_terms[0]->name;
	$is_video = ( get_post_type( $post ) == 'publication' && has_term( 'video', 'publication_type', $post ) );
	$classes = array( 'box' );
	if ( $cols && ! ( $i % $cols ) ) {
		$classes[] = 'last';
	}
	closer_publication_video_init( $permalink, $is_video, $classes, $link_classes, $link_title, $link_extra_attributes, $post );
					
	// Open
	echo '<div class="' . implode( ' ', $classes ) . ' teaser-resource grid-item">';
	
	// Structural
	echo '<div class="float-label float-label-right">';
	echo $post_term_name;
	echo '</div>';
	echo '<h3>' . get_the_title( $post ) . '</h3>';
	
	// Image
	if ( has_post_thumbnail( $post ) ) {
		echo '<div class="image">';
		echo '<img src="' . pilau_get_featured_image_url( $post->ID, 'new-content-box' ) . '" alt="' . get_the_title( $post ) . ' image">';
		echo '</div>';
	} else if ( get_post_type( $post ) == 'publication' && has_term( 'resource-report', 'publication_type', $post ) ) {
		echo '<div class="image resource-report"></div>';
	}

	// Theme / topic tags
	if ( $themes = get_the_terms( $post, 'theme' ) ) {
		echo '<div class="bullet-tags">';
		echo '<ul>';

		// Gather top-level themes and topics
		$themes_top_level = array();
		$theme_topics = array();
		foreach ( $themes as $theme ) {
			if ( $theme->parent == 0 ) {
				$themes_top_level[] = $theme;
			} else {
				if ( ! isset( $theme_topics[ $theme->parent ] ) ) {
					$theme_topics[ $theme->parent ] = array();
				}
				$theme_topics[ $theme->parent ][] = $theme;
			}
		}
		foreach ( $themes_top_level as $theme ) {
			echo '<li class="' . $theme->slug . '">' . $theme->name;

			// 2nd-level topics?
			if ( $theme_topics[ $theme->term_id ] ) {
				echo '<ul class="topics">';
				foreach ( $theme_topics[ $theme->term_id ] as $topic ) {
					echo '<li class="' . $theme->slug . '">' . $topic->name . '</li>';
				}
				echo '</ul>';
			}

			echo '</li>';
		}

		echo '</ul>';
		echo '</div>';
	}

	// Open CTA
	echo '<div class="teaser-cta">';

	echo '<div class="no-float-label">';
	echo the_field('resource_type');
	echo '</div>';

	// Link
		$is_video = has_term( 'video', 'publication_type', $object_id );
  		if ( $is_video ) {
   			echo '<a class="button play-video" href="' . esc_url( $permalink ) . '" title="' . $link_title . '"' . $link_extra_attributes . '>Play video</a>';
   		}else{
			echo '<a class="button" href="' . esc_url( $permalink ) . '" title="' . $link_title . '"' . $link_extra_attributes . '>Download</a>'; 		
   		}

	// Link old
	// echo '<a class="' . implode( ' ', $link_classes ) . ' button" href="' . esc_url( $permalink ) . '" title="' . $link_title . '"' . $link_extra_attributes . '>' . get_the_title( $post ) . '</a>';

	// Close CTA
	echo '</div>';

	// Close all
	echo '</div>';

}

/**
 * Helper to initialise publication / download items
 *
 * @since	CLOSER 0.1
 * @param	string	$permalink
 * @param	bool	$is_video
 * @param	array	$classes
 * @param	array	$link_classes
 * @param	string	$link_title
 * @param	string	$link_extra_attributes
 * @param	object	$post
 * @return	void
 */
function closer_publication_video_init( &$permalink, &$is_video, &$classes, &$link_classes, &$link_title, &$link_extra_attributes, $post = null ) {

	if ( is_null( $post ) ) {
		global $post;
	}

	if ( get_post_type( $post ) == 'publication' ) {
		$is_video = has_term( 'video', 'publication_type', $post );

		if ( ! $is_video ) {

			// Publication download
			$link_title = __( 'Click to download' );
			if ( $permalink = closer_get_publication_file_url( $post->ID ) ) {
				$link_extra_attributes .= ' target="_blank"';
			}

		} else {

			// Video
			$classes[] = 'is-video';
			if ( function_exists( 'slt_cf_field_value' ) ) {
				$permalink = slt_cf_field_value( 'video-url', 'post', $post->ID );
			}
			$link_extra_attributes .= ' target="_blank"'; // This is for the non-JS fallback link
			$link_classes[] = 'play-video';
			$link_classes[] = 'no-icon';
			$link_title = __( 'Click to watch this video' );
			// Store the embed code for output in the footer
			$video_embed_id = closer_video_embed_init( $permalink, apply_filters( 'the_content', trim( get_post_field( 'the_content', $post ) ) ) );
			$link_extra_attributes .= ' data-video-embed-id="' . $video_embed_id . '"';

		}

	}

}


/**
 * Initialise video embeds
 *
 * @param	string	$permalink
 * @param	string	$description
 * @return	string
 */
function closer_video_embed_init( $permalink, $description = '' ) {
	global $closer_video_embeds;
	$video_embed_id = null;

	// Make ID from permalink
	$video_embed_id = preg_replace( '/[^A-Za-z0-9]/', '', $permalink );

	// Add to global array?
	if ( ! array_key_exists( $video_embed_id, $closer_video_embeds ) ) {
		$closer_video_embeds[ $video_embed_id ] = wp_oembed_get( $permalink );

		// Description?
		if ( $description ) {
			$closer_video_embeds[ $video_embed_id ] .= '<div class="description post-content first-para-normal">' . $description . '</div>';
		}

	}

	return $video_embed_id;
}


/**
 * Output theme blocks
 *
 * @since	CLOSER 0.1
 * @param	string		$context	'main' | 'menu'
 * @param	string		$post_type
 * @return	int						Number of blocks output
 */
function closer_theme_blocks( $context = 'main', $post_type = null ) {
	// Cache to keep track of which themes have something for particular post types
	static $themes_with_post_types = array();

	// Get top-level themes
	$themes = get_terms( 'theme', array(
		'hide_empty'		=> true,
		'parent'			=> 0
	));

	// Output
	$i = 1;
	foreach ( $themes as $theme ) {
		$include_theme = true;

		// Check that the theme is associated with something in the $post_type
		if ( $post_type ) {

			// Do we have this check in the cache
			if ( ! isset( $themes_with_post_types[ $theme->slug ][ $post_type ] ) ) {

				// Check if there's anything for this theme
				$check_for_posts = get_posts( array(
					'post_type'		=> $post_type,
					'tax_query'		=> array(
						array(
							'taxonomy'		=> 'theme',
							'field'			=> 'id',
							'terms'			=> $theme->term_id
						)
					)
				));

				// Store in cache
				$themes_with_post_types[ $theme->slug ][ $post_type ] = count( $check_for_posts );

			}

			// Include?
			$include_theme = $themes_with_post_types[ $theme->slug ][ $post_type ];

		}

		if ( $include_theme ) {

			// Classes
			$classes = array( 'box', 'theme', 'theme-' . $i, $theme->slug );
			if ( $i == 1 ) {
				$classes[] = 'current';
			} else if ( ! ( $i % 3 ) ) {
				$classes[] = 'last';
			}

			// Description according to context
			$desc = '';
			if ( $context != 'menu' ) {
				$desc_parts = explode( "\n", $theme->description );
				$desc = $desc_parts[0]; // This will be the short version if there's two
				if ( $context == 'main' && count( $desc_parts ) > 1 ) {
					// Find next part, separated by one or more line breaks
					for ( $i = 1; $i < count( $desc_parts ); $i++ ) {
						if ( trim( $desc_parts[ $i ] ) ) {
							$desc = $desc_parts[ $i ];
							break;
						}
					}
				}
			}

			// Output
			echo '<li class="' . implode( ' ', $classes ) . '">';
			echo '<a href="' . add_query_arg( 'theme', $theme->term_id, get_permalink( CLOSER_EVIDENCE_PAGE_ID ) ) . '" class="block-wrapper">';
			echo '<div class="icon"><div class="theme-icon ' . $theme->slug . '"></div></div>';
			echo '<h2 class="tdh"><span class="hu">' . $theme->name . '</span></h2>';
			if ( $context != 'menu' ) {
				echo '<p>' . $desc . '</p>';
			}
			echo '</a>';
			echo '</li>';

			$i++;

		}

	}

	return count( $themes );
}


/**
 * Related content section
 *
 * @since	CLOSER 0.1
 * @return	void
 */
function closer_related_content_section( $cols = 2 ) {
	global $pilau_custom_fields, $closer_related_content_post_types;

	// Gather related content
	$closer_related_ids = array();
	for ( $i = 1; $i <= 3; $i++ ) {
		if ( isset( $pilau_custom_fields[ 'related-content-' . $i ] ) ) {
			$closer_related_ids[] = $pilau_custom_fields[ 'related-content-' . $i ];
		}
	}
	//echo '<pre>'; print_r( $closer_related_ids ); echo '</pre>'; exit;

	if ( $closer_related_ids ) { ?>
	<hr>
    <section id="related-content" class="indent underline heading-left jump-section">
      <div class="inner">
        <h2 class="jump-title"><?php _e( 'Related content' ); ?></h2>
        <div class="flex col-3">
  			<?php closer_content_items_related_nocarousel( $closer_related_ids, $cols, true, 'yes', false, false, $closer_related_content_post_types ); ?>
      	</div>
      </div>
    </section>

	<?php }else{ ?>
	<hr>
	<section id="related-content" class="indent underline heading-left jump-section">
      <div class="inner">
        <h2 class="jump-title"><?php _e( 'Related content' ); ?></h2>
        <div class="carousel-related carousel-related-init">
          <div class="carousel-prev swiper-button-prev"></div>
          <div class="carousel-next swiper-button-next"></div>
          <div class="swiper">
            <div class="swiper-wrapper">
          		<?php closer_content_items_related( $closer_related_ids, $cols, true, 'yes', false, false, $closer_related_content_post_types ); ?>
            </div>
          </div>
        </div>
      </div>
    </section>
	<?php }
}

function closer_related_content_section_detail( $cols = 2 ) {
	global $pilau_custom_fields, $closer_related_content_post_types;

	// Gather related content
	$closer_related_ids = array();
	for ( $i = 1; $i <= 3; $i++ ) {
		if ( isset( $pilau_custom_fields[ 'related-content-' . $i ] ) ) {
			$closer_related_ids[] = $pilau_custom_fields[ 'related-content-' . $i ];
		}
	}
	//echo '<pre>'; print_r( $closer_related_ids ); echo '</pre>'; exit;

	if ( $closer_related_ids ) { ?>
	<section class="border-bottom">
		<h2 class="jump-title">Related content</h2>
		<div class="carousel-related-init-2 carousel-related">
        	<div class="carousel-prev swiper-button-prev"></div>
         	<div class="carousel-next swiper-button-next"></div>
         	<div class="swiper">
            	<div class="swiper-wrapper">
          		<?php closer_content_items_related( $closer_related_ids, $cols, true, 'yes', false, false, $closer_related_content_post_types ); ?>
            	</div>
          	</div>
        </div>
    </section>

	<?php }
}


/**
 * Output related evidence
 *
 * @since	CLOSER 0.1
 * @return	void
 */
function closer_related_evidence_section() {
	global $pilau_custom_fields;

	// Check if there's any evidence selected
	$related_evidence_count = 0;
	$related_evidence = null;
	if ( empty( $pilau_custom_fields['related-evidence'] ) ) {

		if ( get_post_type() == 'evidence' ) {

			// Try to get evidence related to any of the same themes
			if ( $themes = get_the_terms( get_queried_object_id(), 'theme' ) ) {
				$related_evidence = new WP_Query( array(
					'posts_per_page'	=> -1,
					'post_type'			=> 'evidence',
					'orderby'			=> 'title',
					'order'				=> 'ASC',
					'post__not_in'		=> array( get_queried_object_id() ),
					'tax_query'			=> array(
						array(
							'taxonomy'	=> 'theme',
							'terms'		=> array_keys( $themes ),
							'operator'	=> 'IN'
						)
					),
					//'pilau_multiply'	=> 5
				));
				$related_evidence_count = $related_evidence->found_posts;
			}

		} else if ( get_post_type() == 'study' ) {

			// Try to get evidence tagged as related to the study
			if ( function_exists( 'slt_cf_field_key' ) ) {
				$related_evidence = new WP_Query( array(
					'posts_per_page'	=> -1,
					'post_type'			=> 'evidence',
					'orderby'			=> 'title',
					'order'				=> 'ASC',
					'meta_query'		=> array(
						array(
							'key'		=> slt_cf_field_key( 'evidence-studies' ),
							'value'		=> get_queried_object_id()
						)
					),
					//'pilau_multiply'	=> 5
				));
				$related_evidence_count = $related_evidence->found_posts;
			}

		}

	} else {

		// Pass manually-set IDs through
		$related_evidence = $pilau_custom_fields['related-evidence'];
		$related_evidence_count = count( $related_evidence );

	}

	// Any related evidence?
	if ( $related_evidence_count ) { ?>
		<section id="related-contextual-data" class="indent underline heading-left jump-section">
			<div class="inner">
				<h2>Related Evidence</h2>
				<div class="carousel-related-init-2-2 carousel-related">
					<div class="carousel-prev swiper-button-prev"></div>
	  				<div class="carousel-next swiper-button-next"></div>
					<div class="swiper">
					  	<div class="swiper-wrapper">
							<?php closer_content_items_evidence( $related_evidence, 2, false, 'no', false, false, 'evidence', false, 1 ); ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php }

}


/**
 * Output featured / related content items
 *
 * @since	CLOSER 0.1
 * @param	mixed	$items				An array of IDs or a query object
 * @param	int		$cols
 * @param	bool	$highlight_first
 * @param	string	$flag_post_type			'yes' | 'no' | 'news-opinion'
 * @param	bool	$thumbnails				Use small thumbnails? If not, image sizes will be based on number of columns
 * @param	bool	$all_link
 * @param	mixed	$post_types
 * @param	bool	$image_rounded_corners
 * @param	bool	$maybe_more_limit
 * @param	bool	$show_themes
 * @param	bool	$event_all_details
 * @param	bool	$more_link
 * @param	bool	$more_posts_link
 * @param	bool	$teaser
 * @return	void
 */
function closer_content_items( $items, $cols = 2, $highlight_first = false, $flag_post_type = 'yes', $thumbnails = false, $all_link = false, $post_types = 'any', $image_rounded_corners = false, $maybe_more_limit = null, $show_themes = true, $event_all_details = true, $more_link = false, $more_posts_link = false, $teaser = true ) {
	global $closer_video_embeds;

	// Check items
	if ( ! is_object( $items ) ) {

		// Get the items
		$items_query = new WP_Query( array(
			'post_type'			=> $post_types,
			'posts_per_page'	=> -1,
			'post__in'			=> $items,
			//'pilau_multiply'	=> 5
		));
		//echo '<pre>'; print_r( $items_query ); echo '</pre>'; exit;

	} else {
		$items_query = $items;
	}

	if ( $items_query->have_posts() ) {
		$i = 1;
		$col = 1;

		$classes = array( 'content-items', 'clearfix', 'cols-' . $cols );
		if ( $maybe_more_limit ) {
			$classes[] = 'maybe-more';
		}

		echo '<div class="teasers flex col-3 ' . implode( ' ', $classes ) . '"';
		if ( $maybe_more_limit ) {
			echo ' data-maybe-more-rows="' . $maybe_more_limit . '"';
		}
		echo '>' . "\n";

		while ( $items_query->have_posts() ) {
			$items_query->the_post();

			// Initialize
			$post_type = get_post_type();
			$classes = array( 'item', 'item-' . $i, 'clearfix', 'post-type-' . $post_type );
			if ( $i == 1 && $highlight_first ) {
				$classes[] = 'highlight';
			} else {
				$classes[] = '';
				$current = $highlight_first ? $i + 1 : $i;
				if ( ! ( $current % $cols ) ) {
					$classes[] = 'last';
				}
				if ( $maybe_more_limit ) {
					$classes[] = 'maybe-more-row';
				}
			}
			$post_type_object = null;
			if ( $flag_post_type == 'yes' || $all_link ) {
				$post_type_object = get_post_type_object( $post_type );
			}
			$schema = false;
			$custom_fields = array();
			if ( function_exists( 'slt_cf_all_field_values' ) && in_array( $post_type, array( 'event', 'post', 'evidence' ) ) ) {
				$custom_fields = slt_cf_all_field_values();
			}
			$is_video = false;
			$permalink = get_permalink();
			$link_extra_attributes = '';
			$link_classes = array();
			$link_title = __( 'Click to read more' );
			closer_publication_video_init( $permalink, $is_video, $classes, $link_classes, $link_title, $link_extra_attributes );
			$image = ( $post_type != 'event' && has_post_thumbnail() );
			if ( $image ) {
				$classes[] = 'has-image';
				$link_classes[] = 'image-wrapper';
			}
			$themes = null;
			if ( $show_themes ) {
				$themes = pilau_objects_array_values( 'name', get_the_terms( get_the_ID(), 'theme' ) );
				if ( $themes ) {
					$classes[] = 'has-themes';
				}
			}
			if ( $flag_post_type != 'no' || $themes ) {
				$classes[] = 'has-post-type-themes';
			}
			if ( $post_type == 'event' && ! $event_all_details ) {
				$classes[] = 'event-minimum-details';
			}

			// Output item
			echo '<div class="teaser ' . implode( ' ', $classes ) . '"';
			// Schema?
			if ( $post_type == 'event' ) {
				echo ' itemscope itemtype="http://schema.org/Event"';
				$schema = true;
			}
			echo '>' . "\n";

			// Item body
			echo '<div class="body clearfix">' . "\n";

			// Image?
			if ( $image ) {

				// Image size
				$image_size = 'thumbnail';
				if ( ! $thumbnails ) {
					$image_size = 'featured-item-thumb';
					if ( ! in_array( 'highlight', $classes ) && $cols == 3 ) {
						$image_size = 'related-content-small';
					}
				}

				// Make sure there's no external link icon for the image link
				$image_link_classes = $link_classes;
				array_push( $image_link_classes, 'no-icon' );

				echo '<figure class="image"><a class="' . implode( ' ', $image_link_classes ) . '" title="' . $link_title . '" href="' . $permalink . '"' . $link_extra_attributes . '><img';
				if ( $image_rounded_corners ) {
					echo ' class="rounded-corners"';
				}
				echo ' src="' . pilau_get_featured_image_url( get_the_ID(), $image_size ) . '" alt="' . get_the_title() . ' image">';
				if ( $is_video ) {
					echo '<span class="play"></span>';
				}
				echo '</a></figure>';

			}

			// The rest is text
			echo '<div class="text">' . "\n";

			// News / opinion date
			if ( $post_type == 'post' ) {
				echo '<p class="date">';
				the_time( get_option( 'date_format' ) );
				echo '</p>';
			}

			// Construct title and maybe output here
			$title = '<h3';
			if ( $schema ) {
				$title .= ' itemprop="name"';
			}
			$title .= '><a class="' . implode( ' ', $link_classes ) . '" title="' . $link_title . '" href="' . $permalink . '" rel="permalink"';
			if ( $schema ) {
				$title .= ' itemprop="url"';
			}
			$title .= $link_extra_attributes . '>' . get_the_title() . '</a></h3>';
			if ( $post_type != 'event' || $event_all_details ) {
				echo $title;
			}

			// Post type / theme
			if ( in_array( 'has-post-type-themes', $classes ) ) {
				echo '<p class="post-type-themes">';

				// Post type?
				if ( $flag_post_type != 'no' ) {
					echo '<span class="post-type">';
					if ( $flag_post_type == 'news-opinion' ) {
						echo $custom_fields['opinion'] ? __( 'Opinion' ) : __( 'News' );
					} else {
						echo $post_type_object->labels->singular_name;
					}
					if ( $themes ) {
						echo ' ';
					}
					echo '</span>';
				}

				// Themes?
				// if ( $themes ) {
				// 	echo '<span class="themes"><span class="icon icon-tag';
				// 	if ( count( $themes ) > 1 ) {
				// 		echo 's';
				// 	}
				// 	echo '"></span> ' . implode( ', ', $themes ) . '</span>' . "\n";
				// }

				echo '</p>';
			}

			// Event details
			if ( $post_type == 'event' && function_exists( 'slt_cf_reverse_date' ) ) {
				echo '<div class="event-details clearfix">' . "\n";

				echo '<a class="no-underline date block-wrapper' . implode( ' ', $link_classes ) . '" title="' . __( 'Click to read more' ) . '" href="' . $permalink . '"'. $link_extra_attributes . '>';
				closer_event_date( $custom_fields );
				echo '</a>';

				if ( $event_all_details ) {
					echo '<div class="details">' . "\n";

					if ( $custom_fields['event-venue'] ) {
						echo '<h3>' . __( 'Venue' ) . '</h3>' . "\n";
						echo '<p itemprop="location" class="detail location"><a title="' . __( 'View map' ) . '" target="_blank" href="https://maps.google.co.uk/?q=' . urlencode( $custom_fields['event-venue'] ) . '">' . $custom_fields['event-venue'] . '</a></p>' . "\n";
					}

					echo '<h3>' . __( 'Time' ) . '</h3>' . "\n";
					echo '<p class="detail">' . $custom_fields['event-time-start'] . ' &ndash; ' . $custom_fields['event-time-end'] . '</p>' . "\n";
					echo closer_event_booking_link( $custom_fields );
					echo '</div>' . "\n";
				} else {
					echo $title;
				}

				echo '</div>' . "\n";
			}

			// Content teaser
			if ( $teaser ) {
				echo '<div class="teaser">' . pilau_teaser_text( get_the_ID(), 30, 0, true, $custom_fields ) . '</div>';
			}

			// Byline?
			if ( $post_type == 'post' && $custom_fields['opinion'] ) {
				echo '<p class="byline">' . __( 'By' ) . ' <span class="name">' . $custom_fields['author'] . '</span></p>';
			}

			// Event booking?
			if ( $post_type == 'event' ) {
				closer_event_booking_link( $custom_fields );
			}

			// Close text
			echo '</div>' . "\n";

			// Close item body
			echo '</div>' . "\n";
			echo '<a href="' . $permalink . '" class="button">Read more</a>';
			// More link?
			if ( $more_link ) {
				echo '<p class="more-link"><a href="' . $permalink . '"><span class="icon icon-angle-circled-right"></span> ' . __( 'Read more' ) . '</a></p>' . "\n";
			}

			// All link
			if ( $all_link && defined( 'CLOSER_' . strtoupper( $post_type ) . '_PAGE_ID' ) ) {
				echo '<p class="all-link"><a href="' . get_permalink( constant( 'CLOSER_' . strtoupper( $post_type ) . '_PAGE_ID' ) ) . '"><span class="icon icon-right-circled"></span> ' . __( 'All' ) . ' ' . strtolower( $post_type_object->labels->name ) . '</a></p>';
			}

			// Close item
			echo '</div>' . "\n";

			// Increments
			$i++;
			if ( ! $highlight_first || $i > 2 ) {
				$col++;
				if ( $col > $cols ) {
					$col = 1;
				}
			}

		}

		// More posts AJAX link?
		if ( $more_posts_link ) {
			pilau_more_posts_link( array(
				'query'				=> $items_query,
				'show_more_label'	=> '<span class="icon icon-angle-circled-down"></span> ' . __( 'Show more' )
			));
		}

		echo '</div>' . "\n";

	} else {

		echo '<p><em>' . __( 'No posts found.' ) . '</em></p>';

	}

	// Reset query
	wp_reset_postdata();

}
function closer_search_items( $items, $cols = 2, $highlight_first = false, $flag_post_type = 'yes', $thumbnails = false, $all_link = false, $post_types = 'any', $image_rounded_corners = false, $maybe_more_limit = null, $show_themes = true, $event_all_details = true, $more_link = false, $more_posts_link = false, $teaser = true ) {
	global $closer_video_embeds;

	// Check items
	if ( ! is_object( $items ) ) {

		// Get the items
		$items_query = new WP_Query( array(
			'post_type'			=> $post_types,
			'posts_per_page'	=> -1,
			'post__in'			=> $items,
			//'pilau_multiply'	=> 5
		));
		//echo '<pre>'; print_r( $items_query ); echo '</pre>'; exit;

	} else {
		$items_query = $items;
	}

	if ( $items_query->have_posts() ) {
		$i = 1;
		$col = 1;

		$classes = array( 'content-items', 'clearfix', 'cols-' . $cols );
		if ( $maybe_more_limit ) {
			$classes[] = 'maybe-more';
		}

		echo '<div class="teasers flex col-3 ' . implode( ' ', $classes ) . '"';
		if ( $maybe_more_limit ) {
			echo ' data-maybe-more-rows="' . $maybe_more_limit . '"';
		}
		echo '>' . "\n";

		while ( $items_query->have_posts() ) {
			$items_query->the_post();

			// Initialize
			$post_type = get_post_type();
			$classes = array( 'item', 'item-' . $i, 'clearfix', 'post-type-' . $post_type );
			if ( $i == 1 && $highlight_first ) {
				$classes[] = 'highlight';
			} else {
				$classes[] = '';
				$current = $highlight_first ? $i + 1 : $i;
				if ( ! ( $current % $cols ) ) {
					$classes[] = 'last';
				}
				if ( $maybe_more_limit ) {
					$classes[] = 'maybe-more-row';
				}
			}
			$post_type_object = null;
			if ( $flag_post_type == 'yes' || $all_link ) {
				$post_type_object = get_post_type_object( $post_type );
			}
			$schema = false;
			$custom_fields = array();
			if ( function_exists( 'slt_cf_all_field_values' ) && in_array( $post_type, array( 'event', 'post', 'evidence' ) ) ) {
				$custom_fields = slt_cf_all_field_values();
			}
			$is_video = false;
			$permalink = get_permalink();
			$link_extra_attributes = '';
			$link_classes = array();
			$link_title = __( 'Click to read more' );
			closer_publication_video_init( $permalink, $is_video, $classes, $link_classes, $link_title, $link_extra_attributes );
			$image = ( $post_type != 'event' && has_post_thumbnail() );
			if ( $image ) {
				$classes[] = 'has-image';
				$link_classes[] = 'image-wrapper';
			}
			$themes = null;
			if ( $show_themes ) {
				$themes = pilau_objects_array_values( 'name', get_the_terms( get_the_ID(), 'theme' ) );
				if ( $themes ) {
					$classes[] = 'has-themes';
				}
			}
			if ( $flag_post_type != 'no' || $themes ) {
				$classes[] = 'has-post-type-themes';
			}
			if ( $post_type == 'event' && ! $event_all_details ) {
				$classes[] = 'event-minimum-details';
			}

			// Output item
			echo '<div class="teaser ' . implode( ' ', $classes ) . '"';
			// Schema?
			if ( $post_type == 'event' ) {
				echo ' itemscope itemtype="http://schema.org/Event"';
				$schema = true;
			}
			echo '>' . "\n";

			// Item body
			echo '<div class="body clearfix">' . "\n";

			// Image?
			if ( $image ) {

				// Image size
				$image_size = 'thumbnail';
				if ( ! $thumbnails ) {
					$image_size = 'featured-item-thumb';
					if ( ! in_array( 'highlight', $classes ) && $cols == 3 ) {
						$image_size = 'related-content-small';
					}
				}

				// Make sure there's no external link icon for the image link
				$image_link_classes = $link_classes;
				array_push( $image_link_classes, 'no-icon' );

				echo '<figure class="image"><a class="' . implode( ' ', $image_link_classes ) . '" title="' . $link_title . '" href="' . $permalink . '"' . $link_extra_attributes . '><img';
				if ( $image_rounded_corners ) {
					echo ' class="rounded-corners"';
				}
				echo ' src="' . pilau_get_featured_image_url( get_the_ID(), $image_size ) . '" alt="' . get_the_title() . ' image">';
				if ( $is_video ) {
					echo '<span class="play"></span>';
				}
				echo '</a></figure>';

			}

			// The rest is text
			echo '<div class="text">' . "\n";

			// News / opinion date
			if ( $post_type == 'post' ) {
				echo '<p class="date">';
				the_time( get_option( 'date_format' ) );
				echo '</p>';
			}

			// Construct title and maybe output here
			$title = '<h3';
			if ( $schema ) {
				$title .= ' itemprop="name"';
			}
			$title .= '><a class="' . implode( ' ', $link_classes ) . '" title="' . $link_title . '" href="' . $permalink . '" rel="permalink"';
			if ( $schema ) {
				$title .= ' itemprop="url"';
			}
			$title .= $link_extra_attributes . '>' . get_the_title() . '</a></h3>';
			if ( $post_type != 'event' || $event_all_details ) {
				echo $title;
			}

			// Post type / theme
			if ( in_array( 'has-post-type-themes', $classes ) ) {
				echo '<p class="post-type-themes">';

				// Post type?
				if ( $flag_post_type != 'no' ) {
					echo '<span class="post-type">';
					if ( $flag_post_type == 'news-opinion' ) {
						echo $custom_fields['opinion'] ? __( 'Opinion' ) : __( 'News' );
					} else {
						echo $post_type_object->labels->singular_name;
					}
					if ( $themes ) {
						echo ' ';
					}
					echo '</span>';
				}

				// Themes?
				// if ( $themes ) {
				// 	echo '<span class="themes"><span class="icon icon-tag';
				// 	if ( count( $themes ) > 1 ) {
				// 		echo 's';
				// 	}
				// 	echo '"></span> ' . implode( ', ', $themes ) . '</span>' . "\n";
				// }

				echo '</p>';
			}

			// Event details
			if ( $post_type == 'event' && function_exists( 'slt_cf_reverse_date' ) ) {
				echo '<div class="event-details clearfix">' . "\n";

				echo '<a class="no-underline date block-wrapper' . implode( ' ', $link_classes ) . '" title="' . __( 'Click to read more' ) . '" href="' . $permalink . '"'. $link_extra_attributes . '>';
				closer_event_date( $custom_fields );
				echo '</a>';

				if ( $event_all_details ) {
					echo '<div class="details">' . "\n";

					if ( $custom_fields['event-venue'] ) {
						echo '<h3>' . __( 'Venue' ) . '</h3>' . "\n";
						echo '<p itemprop="location" class="detail location"><a title="' . __( 'View map' ) . '" target="_blank" href="https://maps.google.co.uk/?q=' . urlencode( $custom_fields['event-venue'] ) . '">' . $custom_fields['event-venue'] . '</a></p>' . "\n";
					}

					echo '<h3>' . __( 'Time' ) . '</h3>' . "\n";
					echo '<p class="detail">' . $custom_fields['event-time-start'] . ' &ndash; ' . $custom_fields['event-time-end'] . '</p>' . "\n";
					echo closer_event_booking_link( $custom_fields );
					echo '</div>' . "\n";
				} else {
					echo $title;
				}

				echo '</div>' . "\n";
			}

			// Content teaser
			if ( $teaser ) {
				echo '<div class="teaser">' . pilau_teaser_text( get_the_ID(), 30, 0, true, $custom_fields ) . '</div>';
			}

			// Byline?
			if ( $post_type == 'post' && $custom_fields['opinion'] ) {
				echo '<p class="byline">' . __( 'By' ) . ' <span class="name">' . $custom_fields['author'] . '</span></p>';
			}

			// Event booking?
			if ( $post_type == 'event' ) {
				closer_event_booking_link( $custom_fields );
			}

			// Close text
			echo '</div>' . "\n";

			// Close item body
			echo '</div>' . "\n";
			echo '<a href="' . $permalink . '" class="button">Read more</a>';
			// More link?
			if ( $more_link ) {
				echo '<p class="more-link"><a href="' . $permalink . '"><span class="icon icon-angle-circled-right"></span> ' . __( 'Read more' ) . '</a></p>' . "\n";
			}

			// All link
			if ( $all_link && defined( 'CLOSER_' . strtoupper( $post_type ) . '_PAGE_ID' ) ) {
				echo '<p class="all-link"><a href="' . get_permalink( constant( 'CLOSER_' . strtoupper( $post_type ) . '_PAGE_ID' ) ) . '"><span class="icon icon-right-circled"></span> ' . __( 'All' ) . ' ' . strtolower( $post_type_object->labels->name ) . '</a></p>';
			}

			// Close item
			echo '</div>' . "\n";

			// Increments
			$i++;
			if ( ! $highlight_first || $i > 2 ) {
				$col++;
				if ( $col > $cols ) {
					$col = 1;
				}
			}

		}

		// More posts AJAX link?
		if ( $more_posts_link ) {
			pilau_more_posts_link( array(
				'query'				=> $items_query,
				'show_more_label'	=> '<span class="icon icon-angle-circled-down"></span> ' . __( 'Show more' )
			));
		}

		echo '</div>' . "\n";

	} else {

		echo '<p><em>' . __( 'No posts found.' ) . '</em></p>';

	}

	// Reset query
	wp_reset_postdata();

}

function closer_content_items_related( $items, $cols = 2, $highlight_first = false, $flag_post_type = 'yes', $thumbnails = false, $all_link = false, $post_types = 'any', $image_rounded_corners = false, $maybe_more_limit = null, $show_themes = true, $event_all_details = true, $more_link = false, $more_posts_link = false, $teaser = true ) {
	global $closer_video_embeds;

	// Check items
	if ( ! is_object( $items ) ) {

		// Get the items
		$items_query = new WP_Query( array(
			'post_type'			=> $post_types,
			'posts_per_page'	=> -1,
			'post__in'			=> $items,
			//'pilau_multiply'	=> 5
		));
		//echo '<pre>'; print_r( $items_query ); echo '</pre>'; exit;

	} else {
		$items_query = $items;
	}

	if ( $items_query->have_posts() ) {
		$i = 1;
		$col = 1;

		$classes = array( 'content-items', 'clearfix', 'cols-' . $cols );
		if ( $maybe_more_limit ) {
			$classes[] = 'maybe-more';
		}
		while ( $items_query->have_posts() ) {
			$items_query->the_post();

			// Initialize
			$post_type = get_post_type();
			$classes = array( 'item', 'item-' . $i, 'clearfix', 'post-type-' . $post_type );
			if ( $i == 1 && $highlight_first ) {
				$classes[] = 'highlight';
			} else {
				$classes[] = 'col';
				$classes[] = 'col-' . $col;
				$current = $highlight_first ? $i + 1 : $i;
				if ( ! ( $current % $cols ) ) {
					$classes[] = 'last';
				}
				if ( $maybe_more_limit ) {
					$classes[] = 'maybe-more-row';
				}
			}
			$post_type_object = null;
			if ( $flag_post_type == 'yes' || $all_link ) {
				$post_type_object = get_post_type_object( $post_type );
			}
			$schema = false;
			$custom_fields = array();
			if ( function_exists( 'slt_cf_all_field_values' ) && in_array( $post_type, array( 'event', 'post', 'evidence' ) ) ) {
				$custom_fields = slt_cf_all_field_values();
			}
			$is_video = false;
			$permalink = get_permalink();
			$link_extra_attributes = '';
			$link_classes = array();
			$link_title = __( 'Click to read more' );
			closer_publication_video_init( $permalink, $is_video, $classes, $link_classes, $link_title, $link_extra_attributes );
			$image = ( $post_type != 'event' && has_post_thumbnail() );
			if ( $image ) {
				$classes[] = 'has-image';
				$link_classes[] = 'image-wrapper';
			}
			$themes = null;
			if ( $show_themes ) {
				$themes = pilau_objects_array_values( 'name', get_the_terms( get_the_ID(), 'theme' ) );
				if ( $themes ) {
					$classes[] = 'has-themes';
				}
			}
			if ( $flag_post_type != 'no' || $themes ) {
				$classes[] = 'has-post-type-themes';
			}
			if ( $post_type == 'event' && ! $event_all_details ) {
				$classes[] = 'event-minimum-details';
			}

			// Output item
			echo '<div class="swiper-slide"><div class="teaser">';
			
			echo '<h3>';

			the_title();

			echo '</h3>';
	
			// More link?
			echo '<a class="button" href="' . $permalink . '">' . __( 'Read more' ) . '</a>' . "\n";

			// Close item
			echo '</div></div>' . "\n";

			// Increments
			$i++;
			if ( ! $highlight_first || $i > 2 ) {
				$col++;
				if ( $col > $cols ) {
					$col = 1;
				}
			}

		}

	} else {

		echo '<p><em>' . __( 'No posts found.' ) . '</em></p>';

	}

	// Reset query
	wp_reset_postdata();

}

function closer_content_items_related_nocarousel( $items, $cols = 2, $highlight_first = false, $flag_post_type = 'yes', $thumbnails = false, $all_link = false, $post_types = 'any', $image_rounded_corners = false, $maybe_more_limit = null, $show_themes = true, $event_all_details = true, $more_link = false, $more_posts_link = false, $teaser = true ) {
	global $closer_video_embeds;

	// Check items
	if ( ! is_object( $items ) ) {

		// Get the items
		$items_query = new WP_Query( array(
			'post_type'			=> $post_types,
			'posts_per_page'	=> -1,
			'post__in'			=> $items,
			//'pilau_multiply'	=> 5
		));
		//echo '<pre>'; print_r( $items_query ); echo '</pre>'; exit;

	} else {
		$items_query = $items;
	}

	if ( $items_query->have_posts() ) {
		$i = 1;
		$col = 1;

		$classes = array( 'content-items', 'clearfix', 'cols-' . $cols );
		if ( $maybe_more_limit ) {
			$classes[] = 'maybe-more';
		}
		while ( $items_query->have_posts() ) {
			$items_query->the_post();

			// Initialize
			$post_type = get_post_type();
			$classes = array( 'item', 'item-' . $i, 'clearfix', 'post-type-' . $post_type );
			if ( $i == 1 && $highlight_first ) {
				$classes[] = 'highlight';
			} else {
				$classes[] = 'col';
				$classes[] = 'col-' . $col;
				$current = $highlight_first ? $i + 1 : $i;
				if ( ! ( $current % $cols ) ) {
					$classes[] = 'last';
				}
				if ( $maybe_more_limit ) {
					$classes[] = 'maybe-more-row';
				}
			}
			$post_type_object = null;
			if ( $flag_post_type == 'yes' || $all_link ) {
				$post_type_object = get_post_type_object( $post_type );
			}
			$schema = false;
			$custom_fields = array();
			if ( function_exists( 'slt_cf_all_field_values' ) && in_array( $post_type, array( 'event', 'post', 'evidence' ) ) ) {
				$custom_fields = slt_cf_all_field_values();
			}
			$is_video = false;
			$permalink = get_permalink();
			$link_extra_attributes = '';
			$link_classes = array();
			$link_title = __( 'Click to read more' );
			closer_publication_video_init( $permalink, $is_video, $classes, $link_classes, $link_title, $link_extra_attributes );
			$image = ( $post_type != 'event' && has_post_thumbnail() );
			if ( $image ) {
				$classes[] = 'has-image';
				$link_classes[] = 'image-wrapper';
			}
			$themes = null;
			if ( $show_themes ) {
				$themes = pilau_objects_array_values( 'name', get_the_terms( get_the_ID(), 'theme' ) );
				if ( $themes ) {
					$classes[] = 'has-themes';
				}
			}
			if ( $flag_post_type != 'no' || $themes ) {
				$classes[] = 'has-post-type-themes';
			}
			if ( $post_type == 'event' && ! $event_all_details ) {
				$classes[] = 'event-minimum-details';
			}

			// Output item
			echo '<div class="teaser">';
			
			echo '<h3>';

			the_title();

			echo '</h3>';
	
			// More link?
			echo '<a class="button" href="' . $permalink . '">' . __( 'Read more' ) . '</a>' . "\n";

			// Close item
			echo '</div>' . "\n";

			// Increments
			$i++;
			if ( ! $highlight_first || $i > 2 ) {
				$col++;
				if ( $col > $cols ) {
					$col = 1;
				}
			}

		}

	} else {

		echo '<p><em>' . __( 'No posts found.' ) . '</em></p>';

	}

	// Reset query
	wp_reset_postdata();

}

function closer_content_items_evidence( $items, $cols = 2, $highlight_first = false, $flag_post_type = 'yes', $thumbnails = false, $all_link = false, $post_types = 'any', $image_rounded_corners = false, $maybe_more_limit = null, $show_themes = true, $event_all_details = true, $more_link = false, $more_posts_link = false, $teaser = true ) {
	global $closer_video_embeds;

	// Check items
	if ( ! is_object( $items ) ) {

		// Get the items
		$items_query = new WP_Query( array(
			'post_type'			=> $post_types,
			'posts_per_page'	=> -1,
			'post__in'			=> $items,
			//'pilau_multiply'	=> 5
		));
		//echo '<pre>'; print_r( $items_query ); echo '</pre>'; exit;

	} else {
		$items_query = $items;
	}

	if ( $items_query->have_posts() ) {
		$i = 1;
		$col = 1;

		$classes = array( 'content-items', 'clearfix', 'cols-' . $cols );
		if ( $maybe_more_limit ) {
			$classes[] = 'maybe-more';
		}
		while ( $items_query->have_posts() ) {
			$items_query->the_post();

			// Initialize
			$post_type = get_post_type();
			$classes = array( 'item', 'item-' . $i, 'clearfix', 'post-type-' . $post_type );
			if ( $i == 1 && $highlight_first ) {
				$classes[] = 'highlight';
			} else {
				$classes[] = 'col';
				$classes[] = 'col-' . $col;
				$current = $highlight_first ? $i + 1 : $i;
				if ( ! ( $current % $cols ) ) {
					$classes[] = 'last';
				}
				if ( $maybe_more_limit ) {
					$classes[] = 'maybe-more-row';
				}
			}
			$post_type_object = null;
			if ( $flag_post_type == 'yes' || $all_link ) {
				$post_type_object = get_post_type_object( $post_type );
			}
			$schema = false;
			$custom_fields = array();
			if ( function_exists( 'slt_cf_all_field_values' ) && in_array( $post_type, array( 'event', 'post', 'evidence' ) ) ) {
				$custom_fields = slt_cf_all_field_values();
			}
			$is_video = false;
			$permalink = get_permalink();
			$link_extra_attributes = '';
			$link_classes = array();
			$link_title = __( 'Click to read more' );
			closer_publication_video_init( $permalink, $is_video, $classes, $link_classes, $link_title, $link_extra_attributes );
			$image = ( $post_type != 'event' && has_post_thumbnail() );
			if ( $image ) {
				$classes[] = 'has-image';
				$link_classes[] = 'image-wrapper';
			}
			$themes = null;
			if ( $show_themes ) {
				$themes = pilau_objects_array_values( 'name', get_the_terms( get_the_ID(), 'theme' ) );
				if ( $themes ) {
					$classes[] = 'has-themes';
				}
			}
			if ( $flag_post_type != 'no' || $themes ) {
				$classes[] = 'has-post-type-themes';
			}
			if ( $post_type == 'event' && ! $event_all_details ) {
				$classes[] = 'event-minimum-details';
			}

			// Output item
			echo '<div class="swiper-slide"><div class="teaser">';
			
			echo '<h3>';

			the_title();

			echo '</h3>';
	
			// More link?
			echo '<a class="button" href="' . $permalink . '">' . __( 'Read more' ) . '</a>' . "\n";

			// Close item
			echo '</div></div>' . "\n";

			// Increments
			$i++;
			if ( ! $highlight_first || $i > 2 ) {
				$col++;
				if ( $col > $cols ) {
					$col = 1;
				}
			}

		}

	} else {

		echo '<p><em>' . __( 'No posts found.' ) . '</em></p>';

	}

	// Reset query
	wp_reset_postdata();

}


/**
 * Output event booking link button
 *
 * @since	CLOSER 0.1
 * @param	int		$publication_id
 * @return	string
 */
function closer_event_booking_link( $custom_fields = null ) {
	$link = '';

	if ( ! $custom_fields ) {
		global $pilau_custom_fields;
		$custom_fields = $pilau_custom_fields;
	}

	if ( isset( $custom_fields['event-booking-url'] ) && $custom_fields['event-booking-url'] ) {
		$link = '<p class="book-link"><a class="button no-icon" target="_blank" href="' . esc_url( $custom_fields['event-booking-url'] ) . '" title="' . __( 'Click to book this event' ) . '">' . __( 'Book' ) . '</a></p>' . "\n";
	}

	return $link;
}


/**
 * Get URL of publication download file
 *
 * @since	CLOSER 0.1
 * @param	int		$publication_id
 * @return	string
 */
function closer_get_publication_file_url( $publication_id = null ) {
	$url = '';

	// Initialize ID
	if ( ! $publication_id ) {
		$publication_id = get_the_ID();
	}

	// Try to get downloads
	if ( function_exists( 'slt_cf_field_key' ) ) {
		$downloads = get_posts( array(
			'post_type'			=> 'attachment',
			'posts_per_page'	=> 1,
			'meta_key'			=> slt_cf_field_key( 'publication' ),
			'meta_value'		=> $publication_id
		));
		$url = wp_get_attachment_url( $downloads[0]->ID );
	}

	return $url;
}

/**
 * Filter Yoast breadcrumbs to do custom stuff
 *
 * @since	CLOSER 0.1
 */
add_filter( 'wpseo_breadcrumb_links', 'closer_wpseo_breadcrumb_links' );
function closer_wpseo_breadcrumb_links( $links ) {
	global $post;

	// Check for single CPT posts and add landing page
	if ( is_single() && get_post_type() != 'post' && $landing_page = closer_get_post_landing_page( $post ) ) {

		// Is there a parent to put in?
		if ( $landing_page->post_parent ) {
			array_splice( $links, -1, 0, array(
				array(
					'id'	=> $landing_page->post_parent
				)
			));
		}

		// Put the actual post link in
		array_splice( $links, -1, 0, array(
			array(
				'id'	=> $landing_page->ID
			)
		));
	}

	return $links;
}


/**
 * Get landing page for single post
 *
 * @since	CLOSER 0.1
 * @param	object		$post_type
 * @return	object
 */
function closer_get_post_landing_page( $post ) {
	global $closer_event_date;
	$landing_page = null;

	switch ( get_post_type( $post ) ) {
		case 'post':
			$landing_page = get_post( CLOSER_POST_PAGE_ID );
			break;
		case 'evidence':
			$landing_page = get_post( CLOSER_EVIDENCE_PAGE_ID );
			break;
		case 'study':
			$landing_page = get_post( CLOSER_STUDIES_PAGE_ID );
			break;
		case 'event':
			if ( $closer_event_date < mktime() ) {
				$landing_page = get_post( CLOSER_PREVIOUS_EVENTS_PAGE_ID );
			} else {
				$landing_page = get_post( CLOSER_EVENT_PAGE_ID );
			}
			break;
		case 'contextual_data':
			$landing_page = get_post( CLOSER_CONTEXTUAL_DATABASE_PAGE_ID );
			break;
	}

	return $landing_page;
}


/**
 * Output an event's date
 *
 * @since	CLOSER 0.1
 * @param	array		$custom_fields
 * @return	void
 */
function closer_event_date( $custom_fields = null ) {

	// Initialize custom fields
	if ( ! $custom_fields && function_exists( 'slt_cf_all_field_values' ) ) {
		$custom_fields = slt_cf_all_field_values();
	}

	// Build date
	$date_parts = explode( '/', $custom_fields['event_date'] );
	if ( $custom_fields['event-time-start'] ) {
		$time_parts = explode( ':', $custom_fields['event-time-start'] );
	} else {
		$time_parts = array( 0, 0 );
	}
	$date = mktime( $time_parts[0], $time_parts[1], 0, $date_parts[1], $date_parts[2], $date_parts[0] );

	// Output
	echo '<time datetime="' . date( DATE_W3C, $date ) . '" class="date" itemprop="startDate" content="' . date( DATE_W3C, $date ) . '"><span class="month">' . date( 'M', $date ) . '</span> <span class="day">' . date( 'j', $date ) . '</span> <span class="year">' . date( 'Y', $date ) . '</span></time>' . "\n";

}


/**
 * Get the first term
 * For use when a taxonomy is only used for one term
 *
 * @since	CLOSER 0.1
 * @param	string		$taxonomy
 * @param	int			$post_id
 * @return	object
 */
function pilau_get_first_term( $taxonomy, $post_id = null ) {
	$term = null;

	// Initialize
	if ( ! $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	// Get all terms
	$terms = get_the_terms( $post_id, $taxonomy );

	// Get first term?
	if ( $terms ) {
		$term = array_shift( $terms );
	}

	return $term;
}


/**
 * Output post date
 *
 * @since	CLOSER 0.1
 * @uses	the_time()
 * @return	void
 */
function pilau_post_date() { ?>
	<time datetime="<?php the_time( DATE_W3C ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></time>
<?php }


/**
 * Output tweets
 *
 * @since	CLOSER 0.1
 *
 * @param	int		$max
 * @return	void
 */
function pilau_tweets( $max = 3 ) {
	if ( function_exists( 'getTweets' ) && defined( 'PILAU_TWITTER_SCREEN_NAME' ) && PILAU_TWITTER_SCREEN_NAME ) {

		// Get tweets
		$tweets = getTweets( $max, false, array( 'include_rts' => true ) );
		if ( $tweets && ! array_key_exists( 'error', $tweets ) ) {

			echo '<ul class="tweets">';
			$i = 1;
			foreach ( $tweets as $tweet ) {
				//echo '<pre>'; print_r( $tweet ); echo '</pre>';
				$tweet_date = explode( ' ', $tweet['created_at'] );
				//$tweet_time = implode( ':', array_slice( explode( ':', $tweet_date[3] ), 0, 2 ) );
				$tweet_text = $tweet['text'];
				if ( isset( $tweet['retweeted_status']['text'] ) ) {
					$tweet_text = 'RT: ' . $tweet['retweeted_status']['text'];
					//$screen_name = $tweet['entities']['user_mentions'][0]['screen_name'];
				}
				$classes = array( 'clearfix', 'tweet-' . $i );
				if ( $i == 1 ) {
					$classes[] = 'current';
				}
				echo '<li class="' . implode( ' ', $classes ) . '">';
				echo '<div class="logo"></div>';
				echo '<div class="tweet">';
				echo '<p class="tweet-meta"><span class="name">CLOSER</span> &nbsp;<a class="user-link" href="' . pilau_construct_website_url( 'twitter', PILAU_TWITTER_SCREEN_NAME ) . '">@' . PILAU_TWITTER_SCREEN_NAME . '</a> &nbsp;&middot; <a href="' . pilau_construct_website_url( 'twitter', PILAU_TWITTER_SCREEN_NAME ) . '/status/' . $tweet['id_str'] . '" title="Link to this tweet">' . $tweet_date[1] . ' ' . $tweet_date[2] . '</a></p>';
				echo '<p class="tweet-text">' . pilau_link_urls( esc_html( $tweet_text ) ) . '</p>';
				echo '</div>';
				echo '</li>';
				$i++;
			}

			echo '</ul>';

		} else {

			echo '<p><em>Tweets coming soon...</em></p>';

		}

	} else {

		echo '<p><em>Tweets coming soon...</em></p>';

	}
}


/**
 * Create Twitter follow link
 *
 * @since	CLOSER 0.1
 * @param	string		$user
 * @return	string
 */
function pilau_twitter_follow_link( $user = null ) {

	if ( ! $user && defined( 'PILAU_TWITTER_SCREEN_NAME' ) && PILAU_TWITTER_SCREEN_NAME ) {
		$user = PILAU_TWITTER_SCREEN_NAME;
	}

	return 'https://twitter.com/intent/user?screen_name=' . $user;

}


/**
 * Output social media icons
 *
 * @since	CLOSER 0.1
 *
 * @param	bool	$global		Global style?
 * @return	void
 */
function pilau_share_icons( $global = false ) {
	$url = '';
	if ( $global ) {
		$url = ' st_url="' . home_url() . '"';
	}
	?>
	<ul>
		<li class="facebook img-rep"><span class="st_facebook_custom" tabindex="0" title="<?php _e( 'Share on Facebook' ); ?>"<?php echo $url; ?>>Facebook</span></li>
		<li class="twitter img-rep"><span class="st_twitter_custom" st_via="[Twitter username]" tabindex="0" title="<?php _e( 'Share on Twitter' ); ?>"<?php echo $url; ?>>Twitter</span></li>
		<li class="google img-rep"><span class="st_plusone_custom" tabindex="0" title="<?php _e( 'Share on Google Plus' ); ?>"<?php echo $url; ?>>Google+</span></li>
		<?php if ( ! $global ) { ?>
			<li class="email img-rep"><span class="st_email_custom" tabindex="0" title="<?php _e( 'Share by email' ); ?>"<?php echo $url; ?>>Email</span></li>
		<?php } ?>
		<li class="share img-rep"><span class="st_sharethis_custom" st_via="[Twitter username]" tabindex="0" title="<?php _e( 'More sharing options...' ); ?>"<?php echo $url; ?>>More sharing...</span></li>
	</ul>
<?php
}


/**
 * Output basic ShareThis button
 *
 * @since	CLOSER 0.1
 * @return	void
 */
function pilau_sharethis() {
	?><p class="sharethis"><a href="http://www.scoop.it" class="scoopit-button" scit-position="none" >Scoop.it</a><script type="text/javascript" src="//www.scoop.it/button/scit.js"></script> <span class="st_sharethis_custom" st_via="<?php echo PILAU_TWITTER_SCREEN_NAME; ?>" tabindex="0" title="<?php _e( 'Share this page...' ); ?>"><span class="icon icon-forward"></span> <?php _e( 'ShareThis' ); ?></span></p><?php
}


/**
 * Create Google map link
 *
 * @since	CLOSER 0.1
 * @param	string		$location
 * @return	string
 */
function pilau_google_map_link( $location ) {
	$link = '';

	if ( $location && is_string( $location ) ) {
		$link = 'https://maps.google.co.uk/?q=' . urlencode( $location );
	}

	return $link;
}


/**
 * Generate teaser text
 *
 * Tries to get WP SEO meta description; uses automated extract as fallback
 *
 * @since	CLOSER 0.1
 *
 * @param	int		$post_id	Defaults to ID of post in current loop
 * @param	int		$max_words	For extract, maximum words
 * @param	int		$max_paras	For extract, maximum paragraphs
 * @param	bool	$strip_tags	For extract, strip tags?
 * @param	array	$custom_fields
 * @return	string
 */
function pilau_teaser_text( $post_id = null, $max_words = 30, $max_paras = 0, $strip_tags = true, $custom_fields = array() ) {
	$teaser = '';

	// Default post ID
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	if ( get_post_type( $post_id ) == 'event' && isset( $custom_fields['event-intro'] ) && $custom_fields['event-intro'] ) {

		// Use intro for events if there is one
		$teaser = pilau_extract( $custom_fields['event-intro'] );

	} else if ( in_array( get_post_type( $post_id ), array( 'evidence' ) ) && ! empty( $custom_fields['teaser-text'] ) ) {

		// Use specified teaser text
		$teaser = $custom_fields['teaser-text'];

	} else if ( ( ! class_exists( 'WPSEO_Meta' ) || ! ( $teaser = trim( WPSEO_Meta::get_value( 'metadesc' ) ) ) ) && function_exists( 'pilau_extract' ) ) {

		// If there's no meta description, get content
		$teaser = pilau_extract( get_post_field( 'post_content', $post_id ), $max_words, $max_paras, $strip_tags );

	}

	return $teaser;
}


/**
 * Convert mime type to icon URL
 *
 * @since	CLOSER 0.1
 *
 * @param	string	$type
 * @return	string
 */
function pilau_file_type_icon( $type ) {
	$icon_file_suffix = 'unknown';

	// Make the type simple
	$type = pilau_simple_file_type( $type );

	// Determine the icon
	switch ( strtolower( $type ) ) {
		case 'doc':
		case 'pdf':
		case 'txt':
		case 'xls':
		case 'zip':
			$icon_file_suffix = $type;
			break;
		case 'mp3':
		case 'wav':
			$icon_file_suffix = 'audio';
			break;
		case 'mp4':
		case 'mov':
		case 'avi':
		case 'mkv':
			$icon_file_suffix = 'video';
			break;
	}

	// Return full URL
	return trailingslashit( get_stylesheet_directory_uri() ) . 'img/icons/file-' . $icon_file_suffix . '.png';
}


/**
 * Allow the multiplication of posts in query results for testing purposes
 *
 * @since	CLOSER 0.1
 */
add_filter( 'the_posts', 'pilau_multiply_posts', 10, 2 );
function pilau_multiply_posts( $posts, $query ) {

	// Is the query set to multiply
	if ( isset( $query->query['pilau_multiply'] ) && $query->query['pilau_multiply'] ) {

		// Store original set of posts
		$posts_original = $posts;

		// Multiply
		for ( $i = 1; $i < $query->query['pilau_multiply']; $i++ ) {
			$posts = array_merge( $posts, $posts_original );
		}

		// Adjust count
		$query->found_posts = count( $posts );

	}

	return $posts;
}


/**
 * "Not found" fragment
 *
 * @since	0.1
 *
 * @param	string	$title
 * @return	void
 */
function pilau_not_found( $title = null ) {
	if ( ! $title )
		$title = __( 'Content not found' );
	?>

	<article id="post-0" class="post error404 not-found" role="article">

		<section class="page-title">
			<div class="inner">
				<h1><?php the_title(); ?></h1>
			</div>
		</section>

		<div class="underline">
			<div class="post-content indent">
				<div class="wrapper limit-width-narrow">
					<p><?php _e( "The content you're looking for could not be found. Please try navigating somewhere else, or try searching." ); ?></p>
				</div>
			</div>
		</div>

	</article>

	<?php
}
