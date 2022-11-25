<?php

/**
 * Template name: Data resources landing
 *
 * @package	CLOSER-2022
 * @since	0.1
 */
global $pilau_custom_fields;

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
<div id="content" role="main">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
			<section class="page-title">
				<div class="inner">					
					<h1><?php the_title(); ?></h1>
					<?php the_content(); ?>
				</div>
			</section>

			<?php

			// Need to juggle classes for when link doesn't wrap video
			$link = (bool) $pilau_custom_fields['featured-url'];
			$download = (bool) ! $link && $pilau_custom_fields['featured-download'];
			$image = (bool) $pilau_custom_fields['featured-image'];
			$video = (bool) ! $image && $pilau_custom_fields['featured-video'];
			$classes_link = array( 'block-wrapper' );
			$classes_text = array();
			if ( $video ) {
				$classes_link[] = 'text';
			} else {
				$classes_text[] = 'text';
			}

			?>

			<section id="featured-content" class="wrapper limit-width">
				<div class="inner">
				<div>
					<?php if ( $link ) { ?><a class="<?php echo implode( ' ', $classes_link ); ?>" href="<?php echo esc_url( $pilau_custom_fields['featured-url'] ); ?>"><?php } ?>

					<div class="<?php echo implode( ' ', $classes_text ); ?>">

						<h1 class="tdh"><span class="hu"><?php echo $pilau_custom_fields['featured-heading']; ?></span></h1>

						<p><?php echo $pilau_custom_fields['featured-text']; ?></p>

						<?php if ( $download ) { ?>
							<p><a href="<?php echo wp_get_attachment_url( $pilau_custom_fields['featured-download'] ); ?>" class="button"><?php _e( 'Download' ); ?></a></p>
						<?php } ?>

					</div>

					<?php if ( $link && $video ) { ?></a><?php } ?>

					<figure class="image">
						<?php if ( $image ) { ?>
							<img src="<?php echo pilau_get_image_url( $pilau_custom_fields['featured-image'], 'hero-small' ); ?>" alt="<?php echo $pilau_custom_fields['featured-heading'] ?> image">
						<?php } else if ( $video ) { ?>
							<?php echo wp_oembed_get( $pilau_custom_fields['featured-video'] ); ?>
						<?php } ?>
					</figure>

					<?php if ( $link && $image ) { ?></a><?php } ?>
				</div>
				</div>
			</section>


			<?php if ( ! empty( $pilau_custom_fields['why-longitudinal-data-text'] ) ) { ?>
				<section id="why-longitudinal-data" class="data-page-promo indent underline heading-left">
					<div class="inner">
					<a href="<?php echo get_permalink( CLOSER_WHY_LONGITUDINAL_DATA_PAGE_ID ); ?>" class="block-wrapper">
						<div class="wrapper limit-width">

							<h1 class="heading tdh"><span class="hu"><?php echo get_the_title( CLOSER_WHY_LONGITUDINAL_DATA_PAGE_ID ); ?></span></h1>

							<div class="body">

								<p class="text post-content first-para-normal"><?php echo $pilau_custom_fields['why-longitudinal-data-text']; ?></p>

								<figure class="image">
									<img src="<?php echo pilau_get_image_url( $pilau_custom_fields['why-longitudinal-data-image'], 'content-box' ); ?>" alt="<?php _e( 'Longitudinal data image' ); ?>">
								</figure>

							</div>

						</div>
					</a>
					</div>
				</section>
			<?php } ?>


			<section id="explore-studies" class="indent heading-left">
				<div class="inner">
				<a href="<?php echo get_permalink( CLOSER_STUDIES_PAGE_ID ); ?>" class="block-wrapper">
				<div class="wrapper limit-width">

					<h1 class="heading tdh"><span class="hu"><?php echo get_the_title( CLOSER_STUDIES_PAGE_ID ); ?></span></h1>

					<div class="body">

						<div class="intro underline">

							<?php
							if ( function_exists( 'slt_cf_simple_formatting' ) ) {
								echo '<div class="post-content first-para-normal wrapper limit-width-narrow">' . slt_cf_simple_formatting( $pilau_custom_fields['explore-text'] ) . '</div>';
							}
							?>

						</div>

					</div>

				</div>
				</a>
				</div>
			</section>


			<section id="view-timeline" class="indent underline heading-left">
				<div class="inner">
				<a href="<?php echo get_permalink( CLOSER_TIMELINE_PAGE_ID ); ?>" class="block-wrapper">
				<div class="wrapper limit-width">

					<div class="body">

						<h1 class="tdh"><span class="hu"><?php _e( 'View our interactive timeline' ); ?></span></h1>

						<p class="post-content first-para-normal"><?php _e( 'Find out when each study started and collected data.' ); ?></p>

						<?php if ( $pilau_custom_fields['explore-timeline-image'] ) { ?>
							<figure class="image"><img src="<?php echo pilau_get_image_url( $pilau_custom_fields['explore-timeline-image'], 'large' ); ?>" alt="<?php _e( 'A preview of our studies timeline' ); ?>"></figure>
						<?php } ?>

					</div>

				</div>
				</a>
				</div>
			</section>


			<?php if ( ! empty( $pilau_custom_fields['how-access-data-text'] ) ) { ?>
				<section id="how-access-data" class="data-page-promo indent underline heading-left">
					<div class="inner"><a href="<?php echo get_permalink( CLOSER_HOW_ACCESS_DATA_PAGE_ID ); ?>" class="block-wrapper">
					<div class="wrapper limit-width">

						<h1 class="heading tdh"><span class="hu"><?php echo get_the_title( CLOSER_HOW_ACCESS_DATA_PAGE_ID ); ?></span></h1>

						<div class="body">

							<p class="text post-content first-para-normal"><?php echo $pilau_custom_fields['how-access-data-text']; ?></p>

							<figure class="image">
								<img src="<?php echo pilau_get_image_url( $pilau_custom_fields['how-access-data-image'], 'content-box' ); ?>" alt="<?php _e( 'How to access the data image' ); ?>">
							</figure>

						</div>

					</div>
				</a></div></section>
			<?php } ?>


			<section id="contextual-database" class="indent underline heading-left"><div class="inner"><a href="<?php echo get_permalink( CLOSER_CONTEXTUAL_DATABASE_PAGE_ID ); ?>" class="block-wrapper">
				<div class="wrapper limit-width">

					<h1 class="heading tdh"><span class="hu"><?php echo get_the_title( CLOSER_CONTEXTUAL_DATABASE_PAGE_ID ); ?></span></h1>

					<div class="body clearfix">

						<p class="text post-content first-para-normal"><?php echo $pilau_custom_fields['contextual-database-text']; ?></p>

						<figure class="image">
							<img class="rounded-corners" src="<?php echo pilau_get_image_url( $pilau_custom_fields['contextual-database-image'], 'medium' ); ?>" alt="<?php _e( 'Contextual database image' ); ?>">
						</figure>

					</div>

				</div>
			</a></div></section>

			<?php /*
			<section id="research-opportunities" class="indent underline heading-left"><a href="<?php echo get_permalink( CLOSER_RESEARCH_PAGE_ID ); ?>" class="block-wrapper">
				<div class="wrapper limit-width">

					<h1 class="heading tdh"><span class="hu"><?php echo get_the_title( CLOSER_RESEARCH_PAGE_ID ); ?></span></h1>

					<div class="body clearfix">

						<p class="text post-content first-para-normal"><?php echo $pilau_custom_fields['research-text']; ?></p>

					</div>

				</div>
			</a></section>
			*/ ?>

			<?php

			// Gather events
			$closer_events = array();
			for ( $i = 1; $i <= 2; $i++ ) {
				if ( isset( $pilau_custom_fields['featured-event-' . $i] ) ) {
					$closer_events[] = $pilau_custom_fields['featured-event-' . $i];
				}
			}

			if ( $closer_events ) { ?>

				<section id="featured-events" class="indent underline heading-left">
					<div class="inner">
					<div class="wrapper limit-width">

						<h1 class="heading"><?php _e( 'Featured events' ); ?></h1>

						<div class="body clearfix">

							<?php closer_content_items( $closer_events, 2, false, 'no', false, false, 'event', false, null, false, false, true ); ?>

						</div>

					</div>
				</div>
				</section>

			<?php } ?>


		</article><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; endif; ?>

</div>
</div>

<?php get_footer(); ?>