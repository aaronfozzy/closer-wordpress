<?php

/**
 * Single post
 *
 * @package	CLOSER-2022
 * @since	0.1
 */
global $pilau_custom_fields;

?>

<?php get_header(); ?>

<div id="content" role="main">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">

			<section class="page-title">
				<div class="inner">
							
					<h1><?php the_title(); ?></h1>

					<div class="meta indent underline clearfix">
						<div class="wrapper limit-width">

							<span class="date strong">
							<?php
							if ( is_singular( 'news' ) ) {
							    echo "News | ";
							    echo pilau_post_date();
							} else if(is_singular( 'blog' )){
								echo "Blog | ";
							    echo pilau_post_date();
								echo " | ";
								the_field('the_author');
							} else {
								echo $pilau_custom_fields['opinion'] ? __( 'Opinion' ) : __( 'News' );
							} ?></span>

							<!-- <?php // pilau_sharethis(); ?> -->

						</div>
					</div>
				</div>
			</section>
			<div class="content-main indent underline">
				<div class="inner">	
					<?php if ( is_singular( 'news' ) ) { ?>
						<div class="main-content">
							<div class="clearfix">
								<div class="post-content">
									<?php the_content(); ?>
								</div>
							</div>
						</div>
					<?php } else if(is_singular( 'blog' )){ ?>						
						<div class="main-content">
							<div class="clearfix">
								<div class="post-content">
									<?php the_content(); ?>
								</div>
							</div>
						</div>					
					<?php } else { ?>
					    <div class="flex col-2-1 blue">
							<div class="main-content">
								<div class="clearfix">
									<div class="post-content">
										<?php the_content(); ?>
									</div>
								</div>
							</div>
							<aside>
								
							</aside>
						</div>
					<?php } ?>

					<?php

					if ( comments_open() ) {
						comments_template();
					}

					?>

				</div>
			</div>

		</article><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; endif; ?>

</div>

<?php get_footer(); ?>