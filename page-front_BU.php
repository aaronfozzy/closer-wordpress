<?php

/**
 * Front page
 *
 * @package	CLOSER-2022
 * @since	0.1
 */
global $pilau_custom_fields;

?>

<?php get_header(); ?>

<div id="content" role="main">


	<?php

	// The slideshow
	pilau_slideshow();

	?>


	<?php /* About */ ?>
	<section id="about" class="indent underline">
		<div class="wrapper limit-width-narrow">

			<div class="intro-text fw-light">
				<?php the_content(); ?>
			</div>

			<p><a class="button button-large" href="/about/"><?php _e( 'About' ); ?></a></p>

		</div>
	</section>


	<?php

	/* Latest */

	// Gather latest
	$closer_latest_ids = array();
	foreach ( array( 'news-1', 'news-2', 'event', 'evidence' ) as $closer_latest_suffix ) {
		if ( isset( $pilau_custom_fields[ 'latest-' . $closer_latest_suffix ] ) ) {
			$closer_latest_ids[] = $pilau_custom_fields[ 'latest-' . $closer_latest_suffix ];
		}
	}

	if ( $closer_latest_ids ) {	?>

		<section id="latest" class="indent underline two-cols-listing">
			<div class="wrapper limit-width">

				<h1><?php _e( 'Latest' ); ?></h1>

				<?php closer_content_items( $closer_latest_ids, 2, false, 'yes', true, true, 'any', true ); ?>

			</div>
		</section>

	<?php } ?>


	<?php /* Tweets */ ?>
	<section id="tweets" class="indent-small underline">
		<div class="wrapper limit-width">

			<h1 class="assistive-text"><?php _e( 'Twitter' ); ?></h1>

			<p class="twitter-follow img-rep"><a href="<?php echo pilau_twitter_follow_link(); ?>" title="<?php _e( 'Follow us on Twitter' ); ?>"><?php echo __( 'Follow' ) . ' @' . PILAU_TWITTER_SCREEN_NAME; ?></a></p>

			<div class="tweets-wrapper">
				<?php pilau_tweets(); ?>
			</div>

		</div>
	</section>


	<?php /* Explore the studies*/ ?>
	<section id="explore-studies" class="indent">
		<div class="wrapper limit-width">

			<h1><a href="<?php echo get_permalink( CLOSER_STUDIES_PAGE_ID ); ?>"><?php _e( 'Explore the studies' ); ?></a></h1>

			<?php

			// Timeline
			$closer_timeline = new Closer_Timeline( 'simple' );
			$closer_timeline->draw_timeline();

			?>

		</div>
	</section>


	<?php /* Explore the evidence */ ?>
	<section id="explore-evidence" class="indent">
		<div class="wrapper limit-width">

			<h1><a href="<?php echo get_permalink( CLOSER_EVIDENCE_PAGE_ID ); ?>"><?php _e( 'Explore the evidence' ); ?></a></h1>

			<?php

			echo '<div class="bc">';
			echo '<div class="bc-wrapper">';
			echo '<ul class="clearfix" data-items-visible="3">';
			closer_theme_blocks( 'main', 'evidence' );
			echo '</ul>';
			echo '</div>';
			echo '</div>';

			?>

		</div>
	</section>


</div>

<?php get_footer(); ?>