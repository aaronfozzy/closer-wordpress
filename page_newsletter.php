<?php

/**
 * Template name: Newsletter signup page
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

			<section class="content underline clearfix">
				<div class="inner">
					<div class="flex col-2-1">
		        		<div class="left-col">
							<div class="newsletter">
								<div class="post-content-right">
									<div class="description"><?php the_field('template_description'); ?></div>
									<?php the_content(); ?>
								</div>
							</div>
						</div>						
						<aside>
						</aside>
					</div>
				</div>
			</section>
		</article>
	<?php endwhile;
	endif; ?>
</div>
</div>
<?php get_footer(); ?>