<?php

/**
 * Template name: Explore the evidence
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
<div id="content" role="main">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
					<section class="page-title">
						<div class="inner">					
							<h1><?php the_title(); ?></h1>
							<?php the_content(); ?>
						</div>
					</section>
					<section class="filter-wrap">
						<div id="filters">
							<div class="inner">
							<?php /*
							closer_button_filters( array( 'theme', 'lifestage' ) );
							*/ ?>


							<form class="filter" action="" method="get">

								<?php closer_filter_select( 'theme', __( 'Theme' ), __( 'All themes' ) ); ?>

								<?php closer_filter_select( 'lifestage', __( 'Lifestage' ), __( 'All lifestages' ) ); ?>

								<div class="buttons">
									<input class="form-button button" type="submit" value="<?php _e( 'Apply' ); ?>">
									<?php closer_clear_filters_button(); ?>
								</div>

							</form>
						</div>
					</section>

					<section id="evidence" class="indent">
						<div class="inner">
						<?php

						// Get the evidence
						$closer_evidence_args = array(
							'post_type'			=> 'evidence',
							'posts_per_page'	=> -1,
							'orderby'			=> 'title',
							'order'				=> 'ASC'
						);
						$closer_evidence = new WP_Query( $closer_evidence_args );

						// Output
						if ( $closer_evidence->have_posts() ) {
							echo '<ul class="content-boxes explore-evidence">';
							closer_content_boxes( $closer_evidence );
							echo '</ul>';

						} else {

							echo '<p class="wrapper limit-width-narrow"><em>' . __( 'No evidence to display.' ) . '</em></p>';

						}

						// Reset query
						wp_reset_postdata();

						?>
						</div>
					</section>

				</article><!-- #post-<?php the_ID(); ?> -->

			<?php endwhile; endif; ?>

</div>
</div>

<?php get_footer(); ?>