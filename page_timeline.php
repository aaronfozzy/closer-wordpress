<?php

/**
 * Template name: Timeline of studies
 *
 * @package	CLOSER-2022
 * @since	0.1
 */

/*
if ( WP_LOCAL_DEV ) {

	// Test population
	for ( $i = 1980; $i < 2000; $i++ ) {

		$sweep_id = wp_insert_post( array(
			'post_status'           => 'publish',
			'post_type'             => 'sweep',
			'post_title'			=> 'Sweep ' . $i
		));

		update_post_meta( $sweep_id, slt_cf_field_key( 'sweep-study' ), 166 );
		update_post_meta( $sweep_id, slt_cf_field_key( 'sweep-year' ), $i );
		update_post_meta( $sweep_id, slt_cf_field_key( 'sweep-age-range' ), '15-30' );
		update_post_meta( $sweep_id, slt_cf_field_key( 'sweep-description' ), 'Tempor placerat adipiscing elementum phasellus amet aenean, aenean nunc, pellentesque mus parturient? Mauris purus!' );
		update_post_meta( $sweep_id, slt_cf_field_key( 'sweep-url' ), '#' );

	}

}
*/
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

	<article role="article">

		<section class="page-title">
			<div class="inner">					
				<h1><?php the_title(); ?></h1>
			</div>
		</section>
		<section>
			<div class="inner">
				<div class="timeline">

						<?php

						$closer_timeline = new Closer_Timeline( 'simple' );
						$closer_timeline->draw_timeline();

						?>
				</div>
			</div>
		</section>
	</article>

</div>
</div>

<?php get_footer(); ?>