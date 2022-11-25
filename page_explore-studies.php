<?php

/**
 * Template name: Studies landing
 *
 * @package	CLOSER-2022
 * @since	0.1
 */
global $closer_terms, $closer_filters;
$closer_tax_filters = array( 'dataset_feature', 'lifestage' );

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

	<?php // if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
		<section class="page-title">
			<div class="inner">
				<h1><?php the_title(); ?></h1>
				<div class="flex col-2-1">
					<div class="left-col">
						<?php the_content(); ?>
					</div>
					<aside>
						<a href="#section-studies" class="button button-strong">Our studies</a>
						<a href="/timeline" class="button button-strong">Timeline</a>
					</aside>
				</div>
			</div>
		</section>
			<?php /*
			<section id="filters" class="clearfix">

				<?php closer_button_filters( $closer_tax_filters ); ?>

			</section>
 			*/ ?>

		<section id="section-studies">
			<div class="inner">
				<div class="flex col-2">

					<?php

					wp_reset_query();

					// Get the studies
					$closer_studies_args = array(
						'post_type'			=> 'study',
						'posts_per_page'	=> -1,
						'orderby'			=> 'title',
						'order'				=> 'ASC',
						//'pilau_multiply'	=> 6
					);
					// if ( function_exists( 'slt_cf_field_key' ) ) {
					// 	$closer_studies_args['meta_key']    = slt_cf_field_key( 'study-time-period' );
					// 	$closer_studies_args['orderby']     = 'meta_value';
					// }
					// $closer_tax_query = array();
					// foreach ( $closer_filters as $taxonomy => $terms ) {
					// 	if ( in_array( $taxonomy, $closer_tax_filters ) && ! empty( $terms ) ) {
					// 		$closer_tax_query[] = array(
					// 			'taxonomy'		=> $taxonomy,
					// 			'terms'			=> $terms
					// 		);
					// 	}
					// }
					// if ( $closer_tax_query ) {
					// 	$closer_studies_args['tax_query'] = $closer_tax_query;
					// }
					$closer_studies = new WP_Query( $closer_studies_args );

					// Reposition study to be forced to bottom?
					if ( defined( 'CLOSER_BOTTOM_STUDY_ID' ) ) {
						foreach ( $closer_studies->posts as $closer_key => $closer_study ) {
							if ( $closer_study->ID == CLOSER_BOTTOM_STUDY_ID ) {
								unset( $closer_studies->posts[ $closer_key ] );
								$closer_studies->posts[] = $closer_study;
								break;
							}
						}
					}

					// Output
					if ( $closer_studies->have_posts() ) {

						closer_list_studies( $closer_studies->posts, false, CLOSER_BOTTOM_STUDY_ID );

					} else {

						echo '<p class="wrapper limit-width-narrow"><em>' . __( 'No matching studies to display.' ) . '</em></p>';

					}

					// Reset query
					wp_reset_postdata();

					?>

					</div>
				</div>

			</section>

		</article><!-- #post-<?php the_ID(); ?> -->

	<?php // endwhile; endif; ?>

</div>
</div>

<?php get_footer(); ?>