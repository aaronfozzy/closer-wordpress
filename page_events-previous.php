<?php

/**
 * Template name: Events previous
 *
 * @package	CLOSER-2022
 * @since	0.1
 */
global $closer_terms, $closer_filters;

?>

<?php get_header(); ?>

<?php

// Output colour class

$pickedcolour = $closer_filters['colours'] ? null : pilau_get_first_term( 'colours' );

if(!is_null($pickedcolour)){
	echo '<div class="' . $pickedcolour->slug . '">';
}else{
	echo '<div class="yellow">';
}
?>

<div id="content" role="main" class=" ' . $pickedcolour->slug . '">

	<article <?php post_class(); ?> role="article">
		<section class="page-title">
			<div class="inner">					
				<h1><?php the_title(); ?></h1>
			</div>
		</section>

		<section class="filter-wrap">
			<div id="filters">
				<div class="inner">

					<form class="filter" action="" method="get">

						<?php closer_filter_select( 'event_type', __( 'Type' ), __( 'All types' ) ); ?>

						<?php closer_filter_select( 'theme', __( 'Theme' ), __( 'All themes' ) ); ?>

						<div class="buttons">
							<input class="form-button button" type="submit" value="<?php _e( 'Apply' ); ?>">
							<?php closer_clear_filters_button(); ?>
						</div>

					</form>
				</div>
			</div>
		</section>

		
		<section>
			<div class="inner">
				<div class="flex col-2-1">
					<div class="main-content">
						<?php

						// Get events
						$closer_event_args = array(
							'post_type'				=> 'event',
							'posts_per_page'		=> -1,
							'slt_past_events'		=> true,
							'slt_reverse_events'	=> true
						);
						$closer_events = new WP_Query( $closer_event_args );

						if ( $closer_events->have_posts() ) { ?>

							<?php

							while ( $closer_events->have_posts() ) {
								$closer_events->the_post();

								$event_custom_fields = array();
								if ( function_exists( 'slt_cf_all_field_values' ) ) {
									$event_custom_fields = slt_cf_all_field_values();
								}
								$event_type = pilau_get_first_term( 'event_type' );

								?>
								<div class="teaser-event boxed boxed-40 flex">
									<div class="teaser-event-content">
										<?php if ( $event_type ) { ?>
											<p class="tabbed"><?php echo $event_type->name; ?></p>
										<?php } ?>
										<h3><?php the_title(); ?></h3>
										<p><?php echo pilau_teaser_text(); ?></p>
									</div>
									<div class="teaser-event-aside">
										<p class="icon calendar"><?php closer_event_date( $event_custom_fields ); ?></p>
										<a href="<?php echo get_permalink(); ?>" class="button">Read more</a>
									</div>

								</div>

								<?php

							}

							?>

							<?php

						} else {

							echo '<p class="indent wrapper limit-width-narrow">' . __( 'No events to display.' ) . '</p>';

						}

						// Reset query
						wp_reset_postdata();

						?>
					</div>
					<aside>
						<a href="/events-training" class="button">Upcoming events</a>
						<!-- <a href="https://training.closer.ac.uk/" class="button button-strong"><strong>CLOSER</strong> Training Hub</a> -->
					</aside>
				</div>
			</div>
		</section>

	</article>

</div>
</div>

<?php get_footer(); ?>