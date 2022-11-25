<?php

/**
 * Media functions
 *
 * @package	CLOSER-2022
 * @since	0.1
 */



/**
 * Add classes to post images
 *
 * @since	CLOSER 0.1
 */
add_filter( 'get_image_tag_class', 'pilau_post_image_classes' );
function pilau_post_image_classes( $class ) {
	$class .= ' wp-image';
	return $class;
}


/**
 * Manage default WP image attributes
 *
 * @since	CLOSER 0.1
 */
add_filter( 'wp_get_attachment_image_attributes', 'pilau_image_attributes', 10, 2 );
function pilau_image_attributes( $attr, $attachment ) {
	unset( $attr['title'] );
	return $attr;
}


/**
 * Output gathered video embed codes in footer
 *
 * @since	CLOSER 0.1
 */
add_action( 'wp_footer', 'closer_wp_footer_video_embeds' );
function closer_wp_footer_video_embeds() {
	global $closer_video_embeds;

	if ( $closer_video_embeds ) {

		foreach ( $closer_video_embeds as $closer_video_embed_id => $closer_video_embed_code ) {
			echo '<div id="video-embed-' . $closer_video_embed_id . '" class="video-embed-code">' . $closer_video_embed_code . '</div>' . "\n";
		}

	}

}


/**
 * Place a slideshow
 *
 * Adjust data-flickity-options as necessary, possibly add a custom field alongside
 * 'slideshow-autoplay' for editorial control. Note that setGallerySize should be set
 * to 'true' to base the slideshow's height on the cell height. Currently this is
 * controlled via CSS && JS to be proportional to the width.
 * @link	http://flickity.metafizzy.co/options.html
 *
 * @since	0.1
 */
function pilau_slideshow() {
	global $pilau_custom_fields, $pilau_breakpoints;

	// Gather items from custom fields
	$items = array();
	if ( function_exists( 'slt_cf_all_field_values' ) ) {
		for ( $i = 1; $i <= CLOSER_HOME_CAROUSEL_NUM_SLIDES; $i++ ) {
			if ( ! empty( $pilau_custom_fields['home-carousel-slide-' . $i ] ) ) {
				$items[ $pilau_custom_fields['home-carousel-slide-' . $i ] ] = slt_cf_all_field_values( 'post', $pilau_custom_fields['home-carousel-slide-' . $i ] );
			}
		}
	}
	//echo '<pre>'; print_r( $items ); echo '</pre>'; exit;

	// Do we have anything?
	if ( ! empty( $items ) ) {

		// Init
		$autoplay = 4000;

		?>

		<div class="slideshow js-flickity" data-flickity-options='{ "wrapAround": true, "setGallerySize": false, "pageDots": true, "prevNextButtons": false, "autoPlay": <?php echo $autoplay; ?> }'>

			<?php foreach ( $items as $item_id => $item_custom_fields ) { ?>

				<?php

				// Do we have enough for a slide here?
				if ( ! empty( $item_custom_fields['carousel-image'] ) ) {

					// Init
					$image_alt = __( 'image' );
					$slide_style = '';
					if ( isset( $item_custom_fields['carousel-colour'] ) ) {
						$slide_style = ' style="background-color: #' . $item_custom_fields['carousel-colour'] . '"';
					}

					?>

					<div class="gallery-cell"<?php echo $slide_style; ?>>
						<a class="block-wrapper clearfix" href="<?php echo get_permalink( $item_id ); ?>" title="<?php echo __( 'Click to read more' ); ?>">

							<div class="text">
								<?php if ( ! empty( $item_custom_fields['carousel-heading'] ) ) { ?>
									<h2 class="fw-light"><?php echo $item_custom_fields['carousel-heading']; ?></h2>
									<?php
									$image_alt = $item_custom_fields['carousel-heading'] . ' ' . $image_alt;
									?>
								<?php } ?>
								<?php if ( ! empty( $item_custom_fields['carousel-text'] ) ) { ?>
									<p class="ff-serif fw-light"><?php echo $item_custom_fields['carousel-text']; ?></p>
								<?php } ?>
								<?php
								$post_type_object = get_post_type_object( get_post_type( $item_id ) );
								?>
								<p class="post-type ff-serif"><?php echo $post_type_object->labels->singular_name; ?></p>
							</div>

							<figure class="image">
								<?php

								// Set up alternate portrait image
								/*
								$picture_srcs = array();
								if ( ! empty( $item_custom_fields['slideshow-image-portrait'] ) ) {
									$picture_srcs[] = array(
										'media'		=> '(max-width: ' . $pilau_breakpoints->medium . 'px)',
										'srcset'	=> pilau_get_image_url( $item_custom_fields['slideshow-image-portrait_id'], 'full' )
									);
								}
								*/

								echo pilau_responsive_image(
									$item_custom_fields['carousel-image'],
									array( 'medium', 'large', 'home-hero-large' ),
									'home-hero-large',
									array(
										'(min-width:1000px) 62vw',
										'(min-width:800px) 72vw',
										'100vw'
									),
									null,
									array()
								);
								?>
							</figure>
						</a>
					</div>

				<?php } ?>

			<?php } ?>

			<?php


			?>

		</div>

	<?php }

}


/* Pilau Slideshow stuff
****************************************************************************************************/


/**
 * Filter to add custom slides
 *
 * @since	CLOSER 0.1
 */
add_filter( 'ps_custom_slides', 'closer_ps_custom_slides', 10, 2 );
function closer_ps_custom_slides( $slides, $slideshow ) {
	global $pilau_custom_fields;
	$slides = array();

	// Home page?
	if ( is_front_page() ) {

		for ( $i = 1; $i <= CLOSER_HOME_CAROUSEL_NUM_SLIDES; $i++ ) {

			// Is there a slide in this position?
			if ( isset( $pilau_custom_fields[ 'home-carousel-slide-' . $i ] ) && ctype_digit( $pilau_custom_fields[ 'home-carousel-slide-' . $i ] ) && function_exists( 'slt_cf_all_field_values' ) ) {

				// Get that post's custom fields
				$custom_fields = slt_cf_all_field_values( 'post', $pilau_custom_fields[ 'home-carousel-slide-' . $i ] );
				$image_alt = __( 'image' );

				// Text
				$text = '<div class="text">';

				// Heading and intro text
				$text .= '<figcaption>';
				if ( isset( $custom_fields['carousel-heading'] ) ) {
					$text .= '<h2 class="fw-light">' . $custom_fields['carousel-heading'] . '</h2>';
					$image_alt  = $custom_fields['carousel-heading'] . ' ' . $image_alt;
				}
				if ( isset( $custom_fields['carousel-text'] ) ) {
					$text .= '<p class="ff-serif fw-light">' . $custom_fields['carousel-text'] . '</p>';
				}
				$text .= '</figcaption>';

				// Post type
				$post_type_object = get_post_type_object( get_post_type( $pilau_custom_fields[ 'home-carousel-slide-' . $i ] ) );
				$text .= '<p class="post-type ff-serif">' . $post_type_object->labels->singular_name . '</p>';

				$text .= '</div>';

				// Image
				$image = '<div class="image">';
				if ( isset( $custom_fields['carousel-image'] ) ) {
					$image .= pilau_responsive_picture( $custom_fields['carousel-image'], '', array( 'home-hero-large', 'large', 'medium' ), $image_alt );
					//$image .= '<img src="' . pilau_get_image_url( $custom_fields['carousel-image'], 'large' ) . '" alt="' . $image_alt . '">';
				}
				$image .= '</div>';

				// Wrap up
				$slide_style = '';
				if ( isset( $custom_fields['carousel-colour'] ) ) {
					$slide_style = ' style="background-color: #' . $custom_fields['carousel-colour'] . '"';
				}
				$slide = '<figure' . $slide_style . '><a class="block-wrapper clearfix" href="' . get_permalink( $pilau_custom_fields[ 'home-carousel-slide-' . $i ] ) . '" title="' . __( 'Click to read more' ) . '">' . $text . $image . '</a></figure>';
				$slides[] = $slide;

			}

		}

	}

	return $slides;
}


/**
 * Filter slide classes
 *
 * @since	CLOSER 0.1
 */
//add_filter( 'ps_slide_classes', 'closer_ps_slide_classes', 10, 2 );
function closer_ps_slide_classes( $classes, $slide_id ) {
	global $closer_slide_classes;

	if ( isset( $closer_slide_classes[ $slide_id ] ) ) {
		$classes[] = $closer_slide_classes[ $slide_id ];
	}

	return $classes;
}


/**
 * Filter mobile breakpoint
 *
 * @since	CLOSER 0.1
 */
add_filter( 'ps_mobile_breakpoint', 'closer_ps_mobile_breakpoint' );
function closer_ps_mobile_breakpoint( $bp ) {
	return 800;
}