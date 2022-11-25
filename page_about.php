<?php

/**
 * Template name: About template
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
					<div class="left-col">
						<?php the_content(); ?>
					</div>
					<aside>
						<a href="/home/what-we-do/" class="button button-strong">About CLOSER</a>
						<a href="/explore-the-studies/" class="button button-strong">Our studies</a>
						<a href="/partners/" class="button button-strong">Our partners</a>
						<a href="/funders/" class="button button-strong">Our funders</a>
						<a href="/people/" class="button button-strong">Who we are</a>
						<a href="/our-newsletters/" class="button button-strong">Our newsletters</a>
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