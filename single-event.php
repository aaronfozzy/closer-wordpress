<?php

/**
 * Single event
 *
 * @package	CLOSER-2022
 * @since	0.1
 */
global $pilau_custom_fields, $closer_event_date, $closer_video_embeds;

// Event bits
$closer_event_type = pilau_get_first_term( 'event_type' );
$closer_past_event = $closer_event_date < mktime();
$classes = array();
if ( $closer_past_event ) {
	$classes[] = 'past';
}

?>

<?php get_header(); ?>

<div id="content" role="main event" class="green">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?> role="article" itemscope itemtype="http://schema.org/Event">

			<section class="page-title">
				<div class="inner">					
					<h1><?php the_title(); ?></h1>					
					<!-- <div class="date-wrapper"><?php // closer_event_date( $pilau_custom_fields ); ?></div> -->
				</div>
			</section>
			<div class="content-main indent underline">
				<div class="inner">
					<div class="clearfix">
						<div class="post-content event-post-content">
							<div class="flex col-2-1 blue">
								<div class="main-content">
									<?php if ( function_exists( 'slt_cf_simple_formatting' ) && $pilau_custom_fields['event-intro'] ) { ?>
										<div class="wrapper limit-width-narrow post-content post-content-intro boxed boxed-aside grey">
											<?php echo slt_cf_simple_formatting( $pilau_custom_fields['event-intro'] ); ?>
										</div>
									<?php } ?>
									<?php the_content(); ?>
								</div>
								<?php if ( ! $closer_past_event ) { ?>
									<aside>
										<div class="indent-small">
											<?php echo closer_event_booking_link(); ?>
										</div>						
										<h3 class="assistive-text">Event details</h3>
											<h3><?php _e( 'Event type' ); ?>:</h3>
											<p class="boxed boxed-aside grey"><?php echo $closer_event_type->name; ?></p>
											<h3><?php _e( 'Date' ); ?>:</h3>
											<p class="boxed boxed-aside grey"><?php
											if ( $closer_event_date ) {
												echo date( 'l d F Y', $closer_event_date );
											}
											?></p>
											<h3><?php _e( 'Time' ); ?>:</h3>
											<p class="boxed boxed-aside grey"><?php
											echo $pilau_custom_fields['event-time-start'] . ' - ' . $pilau_custom_fields['event-time-end'];
											?></p>
											<?php if ( $pilau_custom_fields['event-venue'] ) { ?>
													<h3><?php _e( 'Venue' ); ?>:</h3>
													<div class="boxed boxed-aside grey">
													<?php
													echo '<p itemprop="location">' . $pilau_custom_fields['event-venue'] . '</p>' . "\n";
													
													$showmap = $pilau_custom_fields['event-show-map'];
													if($showmap == 1){
													echo '<p class="map-link"><a title="' . __( 'View map' ) . '" target="_blank" href="https://maps.google.co.uk/?q=' . urlencode( $pilau_custom_fields['event-venue'] ) . '">' . __( 'Map' ) . '</a></p>' . "\n";
													}
													?>
													</div>
											<?php } ?>
										<?php if ( $pilau_custom_fields['event-speakers'] ) { ?>
										
											<h3><?php _e( 'Event organiser' ); ?>:</h3>
											<p class="boxed boxed-aside grey"><?php echo $pilau_custom_fields['event-organiser']; ?></p>

										<?php } ?>
											<h3><?php _e( 'Price' ); ?>:</h3>
											<p class="boxed boxed-aside grey"><?php
											if ( ! $pilau_custom_fields['event-price'] || $pilau_custom_fields['event-price'] == '0' ) {
												echo __( 'Free' );
											} else {
												if ( ! preg_match( '#[^0-9\.,]#', $pilau_custom_fields['event-price'] ) ) {
													echo '&pound;';
												}
												echo $pilau_custom_fields['event-price'];
											}
											?></p>
										<?php if ( $pilau_custom_fields['event-speakers'] ) { ?>
												<h3><?php _e( 'Speakers' ); ?>:</h3>
												<p class="boxed boxed-aside grey"><?php
												$event_speakers = array();
												foreach ( $pilau_custom_fields['event-speakers'] as $event_speaker_id ) {
													$event_speakers[] = get_the_title( $event_speaker_id );
												}
												echo implode( ', ', $event_speakers );
												?></p>
										<?php } ?>
										<?php if ( $pilau_custom_fields['event-level'] ) { ?>
												<h3><?php _e( 'Expertise level' ); ?>:</h3>
												<p class="boxed boxed-aside grey"><?php
													echo $pilau_custom_fields['event-level'];
													?></p>
										<?php } ?>


									</aside>
								<?php }else{ ?>
									<aside>
										<p class="boxed boxed-aside grey">
											This event has now passed
										</p>
									</aside>
								<?php } ?>



								<?php

								// Past event resources
								if ( $closer_past_event ) {

									// Get downloads assigned to this event
									$closer_downloads = null;
									if ( function_exists( 'slt_cf_field_key' ) ) {
										$closer_downloads = get_posts( array(
											'post_type'			=> 'attachment',
											'posts_per_page'	=> -1,
											'meta_query'		=> array(
												array(
													'key'			=> slt_cf_field_key( 'event' ),
													'value'			=> get_queried_object_id()
												)
											),
											'orderby'			=> 'title',
											'order'				=> 'ASC'
										));
									}

									if ( $closer_downloads || $pilau_custom_fields['event-videos'] ) { ?>

										<aside id="data-downloads">

											<h2><?php _e( 'Event resources' ); ?></h2>

											<ul class="downloads">
												<?php if ( $closer_downloads ) { ?>
													<?php foreach ( $closer_downloads as $closer_download ) { ?>
														<li><a href="<?php echo wp_get_attachment_url( $closer_download->ID ); ?>"><?php echo get_the_title( $closer_download ); ?></a> (<?php echo strtoupper( pilau_simple_file_type( get_post_mime_type( $closer_download ) ) ); ?>)</li>
													<?php } ?>
												<?php } ?>
												<?php if ( $pilau_custom_fields['event-videos'] ) { ?>
													<?php
													$closer_videos = explode( "\n", trim( $pilau_custom_fields['event-videos'] ) );
													?>
													<?php foreach ( $closer_videos as $closer_video ) { ?>
														<?php if ( trim( $closer_video ) ) { ?>
															<?php
															$closer_video_parts = explode( ' ', $closer_video );
															$closer_video_url = array_shift( $closer_video_parts );
															// Valid URL?
															if ( filter_var( $closer_video_url, FILTER_VALIDATE_URL ) !== false ) {
																$closer_video_embed_id = preg_replace( '/[^A-Za-z0-9]/', '', $closer_video_url );
																if ( ! array_key_exists( $closer_video_embed_id, $closer_video_embeds ) ) {
																	$closer_video_embeds[ $closer_video_embed_id ] = wp_oembed_get( $closer_video_url );
																}
																$closer_video_title = $closer_video_url;
																if ( count( $closer_video_parts ) ) {
																	$closer_video_title = implode( ' ', $closer_video_parts );
																}
																?>
																<li><a target="_blank" class="play-video no-icon" data-video-embed-id="<?php echo $closer_video_embed_id; ?>" href="<?php echo esc_url( $closer_video_url ); ?>" title="<?php _e( 'Click to watch this video' ); ?>"><?php echo $closer_video_title; ?></a> (<?php _e( 'Video' ); ?>)</li>
																<?php
															}
															?>
														<?php } ?>
													<?php } ?>
												<?php } ?>
											</ul>

										</aside>

									<?php } ?>

								<?php } ?>

						</div>
					</div>
				</div>
			</div>

		</article><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; endif; ?>

</div>

<?php get_footer(); ?>