<?php

/**
 * Template name: Evidence and impact landing
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
			</section>

			<?php
			$closer_featured_evidence = array();
			for ( $i = 1; $i <= 2; $i++ ) {
				if ( $pilau_custom_fields['featured-evidence-' . $i] ) {
					$closer_featured_evidence[] = $pilau_custom_fields['featured-evidence-' . $i];
				}
			}
			?>

			<?php if ( $closer_featured_evidence ) { ?>
				<section id="featured-evidence" class="indent underline heading-left">
					<div class="wrapper limit-width">

						<h1 class="heading"><?php _e( 'Featured evidence' ) ?></h1>

						<div class="body">

							<?php closer_content_items( $closer_featured_evidence, 2, false, 'no' ); ?>

						</div>

					</div>
				</section>
			<?php } ?>


			<section id="explore-evidence" class="indent underline heading-left"><a href="<?php echo get_permalink( CLOSER_EVIDENCE_PAGE_ID ); ?>" class="block-wrapper">
				<div class="wrapper limit-width">

					<h1 class="heading tdh"><span class="hu"><?php echo get_the_title( CLOSER_EVIDENCE_PAGE_ID ); ?></span></h1>

					<div class="body">

						<div class="intro">

							<?php
							if ( function_exists( 'slt_cf_simple_formatting' ) ) {
								echo '<div class="post-content first-para-normal wrapper limit-width-narrow">' . slt_cf_simple_formatting( $pilau_custom_fields['explore-text'] ) . '</div>';
							}
							?>

							<?php if ( $pilau_custom_fields['explore-evidence-image'] ) { ?>
								<figure class="image"><img src="<?php echo pilau_get_image_url( $pilau_custom_fields['explore-evidence-image'], 'large' ); ?>" alt="<?php _e( 'Explore the evidence' ); ?>"></figure>
							<?php } ?>

						</div>

					</div>

				</div>
			</a></section>


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
					<div class="wrapper limit-width">

						<h1 class="heading"><?php _e( 'Featured events' ); ?></h1>

						<div class="body clearfix">

							<?php closer_content_items( $closer_events, 2, false, 'no', false, false, 'event', false, null, false, false, true ); ?>

						</div>

					</div>
				</section>

			<?php } ?>


		</article><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; endif; ?>

</div>
</div>

<?php get_footer(); ?>