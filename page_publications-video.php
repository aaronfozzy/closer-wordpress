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
				<?php /*
				closer_button_filters( array( 'theme', 'lifestage' ) );
				*/ ?>

				<form class="filter" action="" method="get">

					<?php closer_filter_select( 'publication_type', __( 'Type' ), __( 'All types' ) ); ?>

					<div class="buttons">
						<input class="form-button button" type="submit" value="<?php _e( 'Apply' ); ?>">
						<?php closer_clear_filters_button(); ?>
					</div>

				</form>
			</div>
		</section>
		<?php

		// Loop through the publication types
		$closer_something_output = false;

				// Get items of this type, with filters if necessary

				$closer_publications_args = array(
				'post_type' => 'publication',
				'posts_per_page' => -1, 
		     	);
				// Is there a featured item for this type?
				
				$closer_publications = new WP_Query( $closer_publications_args );
				$closer_total_posts = $closer_publications->found_posts + ( $closer_featured_resource_id ? 1 : 0 );

				if ( $closer_featured_resource || $closer_publications->have_posts() ) {
					$closer_something_output = true;

					?>

					<section id="pt-" class="publication-type">
						<div class="inner">
	
							<div class="body">

								<?php

								echo '<div class="grid">';
								closer_resource_boxes( $closer_publications);
								echo '</div>';

								?>
								
							</div>

						</div>
					</section>

				<?php } ?>

				<?php 
					if (  $closer_publications->max_num_pages > 1 )
					echo '<a class="load_more_bt more_rec button">Load more</a>'; // you can use <a> as well
				?>

				<?php

				// Reset query
				wp_reset_postdata();

				?>

		<?php if ( ! $closer_something_output ) { ?>
			<section class="indent heading-left underline">
				<div class="wrapper limit-width">
					<p class="body"><em><?php _e( 'Nothing found. Please try changing the filters.' ); ?></em></p>
				</div>
			</section>
		<?php } ?>

		 <!-- end of existing -->

	</article>

</div>
</div>

<?php get_footer(); ?>