<?php

/**
 * Template name: Contact us
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

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'underline' ); ?> role="article">

			<section class="page-title">
				<div class="inner">					
					<h1><?php the_title(); ?></h1>
					<?php the_content(); ?>
				</div>
			</section>

			<div class="post-content indent">
				<div class="wrapper limit-width-narrow">

					<?php the_content(); ?>

					<div class="contact-box">

						<?php include( 'inc/dsp_contact-details.php' ); ?>


					</div>

				</div>
			</div>

		</article><!-- #post-<?php //the_ID(); ?> -->

	<?php endwhile; endif; ?>

</div>
</div>

<?php get_footer(); ?>