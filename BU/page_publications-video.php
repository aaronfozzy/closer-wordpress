<?php

/**
 * Template name: Publications and video landing
 *
 * @package	CLOSER-2022
 * @since	0.1
 */
global $closer_terms, $closer_filters, $pilau_custom_fields, $closer_video_embeds;

?>

<?php get_header(); ?>

<div id="content" role="main">

	<article <?php post_class(); ?> role="article">

		<section class="page-title">
			<div class="inner">					
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</div>
		</section>

		<section class="filter-wrap">
			<div id="filters">
				<div class="inner">
					<form class="filters" action="" method="get">

						<?php closer_filter_select( 'publication_type', __( 'Type' ), __( 'All types' ) ); ?>

						<?php closer_filter_select( 'theme', __( 'Theme' ), __( 'All themes' ) ); ?>

						<div class="buttons">
							<input class="form-button button" type="submit" value="<?php _e( 'Apply' ); ?>">
							<?php closer_clear_filters_button(); ?>
						</div>

					</form>
				</div>
			</div>
		</section>

		<?php if ( ! empty( $pilau_custom_fields['featured-resource'] ) && ! ( $closer_filters['theme'] || $closer_filters['publication_type'] ) ) { ?>

			<?php

			// Init featured item
			$closer_featured_type = pilau_get_first_term( 'publication_type', $pilau_custom_fields['featured-resource'] );
			$closer_featured_custom_fields = array();
			if ( function_exists( 'slt_cf_all_field_values' ) ) {
				$closer_featured_custom_fields = slt_cf_all_field_values( 'post', $pilau_custom_fields['featured-resource'] );
			}

			?>

			<section id="featured-resource" class="indent heading-left">
				<div class="wrapper limit-width">

					<h1 class="heading"><?php _e( 'Featured resource' ); ?></h1>

					<div class="body">

						<div class="text post-content first-para-normal">

							<p><b><?php echo $closer_featured_type->name; ?>:</b> <?php echo pilau_teaser_text( $pilau_custom_fields['featured-resource'], 60 ); ?></p>

							<?php if ( $closer_featured_type->slug != 'video' ) { ?>
								<p><a class="button" href="<?php echo closer_get_publication_file_url( $pilau_custom_fields['featured-resource'] ); ?>"><?php echo __( 'Download' ) . ' ' . strtolower( $closer_featured_type->name ); ?></a></p>
							<?php } ?>

						</div>

						<?php if ( has_post_thumbnail( $pilau_custom_fields['featured-resource'] ) ) { ?>
							<?php if ( $closer_featured_type->slug == 'video' ) { ?>
								<?php
								$closer_featured_permalink = $closer_featured_custom_fields['video-url'];
								$video_embed_id = closer_video_embed_init( $closer_featured_permalink, apply_filters( 'the_content', trim( get_post_field( 'post_content', $pilau_custom_fields['featured-resource'] ) ) ) );
								?>
								<figure class="image is-video">
									<a href="<?php echo esc_url( $closer_featured_permalink ); ?>" class="block-wrapper play-video no-icon" title="<?php _e( 'Click to watch this video' ); ?>" target="_blank" data-video-embed-id="<?php echo esc_attr( $video_embed_id ); ?>"><div class="image-wrapper"><img src="<?php echo pilau_get_featured_image_url( $pilau_custom_fields['featured-resource'], 'medium' ); ?>" alt="<?php echo get_the_title( $pilau_custom_fields['featured-resource'] ); ?> image"><span class="play"></span></div></a>
								</figure>
							<?php } else { ?>
								<figure class="image">
									<img class="rounded-corners" src="<?php echo pilau_get_featured_image_url( $pilau_custom_fields['featured-resource'], 'medium' ); ?>" alt="<?php echo get_the_title( $pilau_custom_fields['featured-resource'] ); ?> image">
								</figure>
							<?php } ?>
						<?php } ?>

					</div>

				</div>
			</section>

		<?php } ?>


		<?php

		// Loop through the publication types
		$closer_something_output = false;
		foreach ( $closer_terms as $closer_term ) { ?>

			<?php if ( $closer_term->taxonomy == 'publication_type' && ( ! $closer_filters[ 'publication_type' ] || in_array( $closer_term->term_id, $closer_filters[ 'publication_type' ] ) ) ) { ?>

				<?php

				// Get items of this type, with filters if necessary
				$closer_tax_query = array();
				$closer_tax_query[] = array(
					'taxonomy'		=> 'publication_type',
					'terms'			=> $closer_term->term_id
				);
				if ( $closer_filters['theme'] ) {
					$closer_tax_query[] = array(
						'taxonomy'		=> 'theme',
						'terms'			=> $closer_filters['theme']
					);
				}
				$closer_publications_args = array(
					'post_type'			=> 'publication',
					'posts_per_page'	=> -1,
					'orderby'			=> 'date',
					'order'				=> 'DESC',
					'tax_query'			=> $closer_tax_query,
					//'pilau_multiply'	=> 10,
				);
				// Is there a featured item for this type?
				$closer_featured_resource = null;
				$closer_featured_resource_id = ! empty( $pilau_custom_fields['featured-resource-' . $closer_term->slug] ) ? $pilau_custom_fields['featured-resource-' . $closer_term->slug] : null;
				if ( $closer_featured_resource_id ) {
					$closer_publications_args['post__not_in'] = array( $closer_featured_resource_id );
					$closer_featured_resource = get_post( $closer_featured_resource_id );
				}
				$closer_publications = new WP_Query( $closer_publications_args );
				$closer_total_posts = $closer_publications->found_posts + ( $closer_featured_resource_id ? 1 : 0 );

				if ( $closer_featured_resource || $closer_publications->have_posts() ) {
					$closer_something_output = true;

					?>

					<section id="pt-<?php echo $closer_term->slug; ?>" class="publication-type indent heading-left underline<?php if ( $closer_total_posts > 3 ) echo ' more-at-bottom'; ?>">
						<div class="wrapper limit-width">

							<div class="heading">

								<h1><?php echo $closer_term->name; ?>s</h1>

								<?php

								// Description?
								if ( $closer_term->description ) {
									echo '<p>' . $closer_term->description . '</p>';
								}

								?>

							</div>

							<div class="body">

								<?php

								echo '<ul class="content-boxes maybe-more" data-maybe-more-rows="1">';
								closer_content_boxes( $closer_publications, 3, $closer_featured_resource );
								echo '</ul>';

								?>

							</div>

						</div>
					</section>

				<?php } ?>

				<?php

				// Reset query
				wp_reset_postdata();

				?>

			<?php } ?>

		<?php } ?>

		<?php if ( ! $closer_something_output ) { ?>
			<section class="indent heading-left underline">
				<div class="wrapper limit-width">
					<p class="body"><em><?php _e( 'Nothing found. Please try changing the filters.' ); ?></em></p>
				</div>
			</section>
		<?php } ?>

	</article>

</div>

<?php get_footer(); ?>