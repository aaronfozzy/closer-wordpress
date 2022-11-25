<?php

/**
 * News listing
 *
 * @package	CLOSER-2022
 * @since	0.1
 */
global $closer_terms, $closer_filters, $wp_query, $wpdb;

// Get IDs of studies associated with posts
$closer_studies_related_to_news_ids = array();
if ( function_exists( 'slt_cf_field_key' ) ) {
	$closer_studies_related_to_news_ids = $wpdb->get_col("
		SELECT	DISTINCT pm.meta_value
		FROM	$wpdb->postmeta pm
		WHERE	pm.meta_key	= '" . slt_cf_field_key( 'post-study' ) . "'
	");
}
//echo '<pre>'; print_r( $closer_studies_related_to_news_ids ); echo '</pre>'; exit;

// Get the studies
$closer_studies_related_to_news = get_posts( array(
	'post_type'			=> 'study',
	'orderby'			=> 'title',
	'order'				=> 'ASC',
	'posts_per_page'	=> -1,
	'post__in'			=> $closer_studies_related_to_news_ids
));
//echo '<pre>'; print_r( $closer_studies_related_to_news ); echo '</pre>'; exit;

// Make into options for filter
$closer_studies_related_to_news_options = array();
foreach ( $closer_studies_related_to_news as $closer_study_related_to_news_options ) {
	$closer_studies_related_to_news_options[ $closer_study_related_to_news_options->ID ] = get_the_title( $closer_study_related_to_news_options );
}

// Get dates
$closer_dates = $wpdb->get_col("
	SELECT		post_date
	FROM		$wpdb->posts
	WHERE		post_status		= 'publish'
	AND 		post_type		= 'post'
	ORDER BY	post_date DESC
");
//echo '<pre>'; print_r( $closer_dates ); echo '</pre>'; exit;

// Make into month / year options for filter
$closer_date_options = array();
foreach ( $closer_dates as $closer_date ) {
	$closer_date_options[ mysql2date( 'Y', $closer_date ) . mysql2date( 'm', $closer_date ) ] = mysql2date( 'F', $closer_date ) . ' ' . mysql2date( 'Y', $closer_date );
}
//echo '<pre>'; print_r( $closer_date_options ); echo '</pre>'; exit;

?>

<?php get_header(); ?>

<div id="content" role="main" class="yellow">

	<section class="page-title">
		<div class="inner">					
			<h1><?php the_title(); ?></h1>
		</div>
	</section>
	<section class="filter-wrap">
		<div id="filters">
			<div class="inner">
				<form class="filter clearfix" action="">
					<div>
						<div class="news-opinion inline-fields form-row">
							<label for="news-opinion-news"><input type="checkbox" value="1" id="news-opinion-news" name="news"<?php if ( $closer_filters['news-opinion'] == 'news' || $closer_filters['news-opinion'] == 'both' ) echo ' checked'; ?>> <?php _e( 'News' ); ?></label>
							<label for="news-opinion-opinion"><input type="checkbox" value="1" id="news-opinion-opinion" name="opinion"<?php if ( $closer_filters['news-opinion'] == 'opinion' || $closer_filters['news-opinion'] == 'both' ) echo ' checked'; ?>> <?php _e( 'Opinion' ); ?></label>
						</div>

						<?php closer_filter_select( $closer_studies_related_to_news_options, __( 'Related study' ), __( 'All studies' ), 'rel-study' ); ?>

						<?php closer_filter_select( 'theme', __( 'Theme' ), __( 'All themes' ) ); ?>

						<?php closer_filter_select( $closer_date_options, __( 'From' ), __( 'All dates' ), 'mf' ); ?>

						<?php closer_filter_select( $closer_date_options, __( 'To' ), __( 'All dates' ), 'mt' ); ?>

						<div class="buttons">
							<input class="form-button button" type="submit" value="<?php _e( 'Apply' ); ?>">
							<?php closer_clear_filters_button(); ?>
						</div>

					</div>

				</form>
			</div>
		</div>
</section>
	<div id="posts" class="indent wrapper limit-width">
		<div class="inner">
			<?php
			closer_search_items( $wp_query, 2, false, 'news-opinion', true, false, 'post', true, null, true, false, true, true );
			//echo '<pre>'; print_r( $wp_query ); echo '</pre>'; exit;
			?>
		</div>

	</div>

</div>

<?php get_footer(); ?>