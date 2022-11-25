<?php

/**
 * Template name: Communities template
 *
 * @package	CLOSER-2022
 * @since	0.1
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

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
		<section class="page-title">
			<div class="inner">
				<h1><?php the_title(); ?></h1>
			</div>
		</section>
		<section>
			<div class="inner">
				<div class="underline">
					<div class="flex col-2-1">							
						<div class="post-content indent">
							<?php the_content(); ?>
						</div>
						<aside>
							<a href="/our-networks/closer-data-managers-network/" class="button button-strong">CLOSER Data Linkage Working Group</a>
							<a href="/our-networks/closer-data-linkage-working-group/" class="button button-strong">CLOSER Data Managers Network</a>
							<a href="/our-networks/closer-longitudinal-communications-network/" class="button button-strong">CLOSER Longitudinal Communications Network</a>
						</aside>
					</div>
				</div>
			</div>
		</section>
		</article><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; endif; ?>

</div>
</div>

<?php get_footer(); ?>