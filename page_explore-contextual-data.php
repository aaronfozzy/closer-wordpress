<?php

/**
 * Template name: Contextual data landing
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

						<form class="filter" action="" method="get">

							<?php closer_filter_select( 'theme', __( 'Theme' ), __( 'All themes' ) ); ?>

							<div class="buttons">
								<input class="form-button button" type="submit" value="<?php _e( 'Apply' ); ?>">
								<?php closer_clear_filters_button(); ?>
							</div>

						</form>
					</div>
				</div>
			</section>

			<section id="evidence" class="indent">
				<div class="inner">
				<?php

				// Get the contextual data
				$closer_tax_query = array();
				if ( $closer_filters['theme'] ) {
					$closer_tax_query[] = array(
						'taxonomy'		=> 'theme',
						'terms'			=> $closer_filters['theme']
					);
				}
				$closer_data_args = array(
					'post_type'			=> 'contextual_data',
					'posts_per_page'	=> -1,
					'orderby'			=> 'title',
					'order'				=> 'ASC',
					'tax_query'			=> $closer_tax_query,
					//'pilau_multiply'	=> 10
				);
				$closer_data = new WP_Query( $closer_data_args );

				// Output
				if ( $closer_data->have_posts() ) {

					echo '<div class="content-boxes explore-contextual flex col-3">';
					//USE THIS FOR MASONRY LAYOUT
					//closer_content_boxes( $closer_data );
					closer_content_boxes_flex( $closer_data );
					echo '</div>';

				} else {

					echo '<p class="wrapper limit-width-narrow"><em>' . __( 'No data to display.' ) . '</em></p>';

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