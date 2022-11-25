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

			<header id="title-main">

				<div class="indent-small underline">
					<h1 class="wrapper limit-width-narrow">
						<?php the_title(); ?>
					</h1>
				</div>

				<div class="meta indent underline clearfix">
					<div class="wrapper limit-width">

						<p class="date"><?php pilau_post_date(); ?></p>

						<?php if ( $pilau_custom_fields['opinion'] ) { ?>
							<p class="byline"><?php _e( 'BY' ); ?>: <span class="name"><?php echo $pilau_custom_fields['author']; ?></span></p>
						<?php } ?>

						<?php pilau_sharethis(); ?>

					</div>
				</div>

			</header>

			<div class="content-main indent underline">
				<div class="wrapper">

					<div class="clearfix">

						<p class="news-opinion"><?php echo $pilau_custom_fields['opinion'] ? __( 'Opinion' ) : __( 'News' ); ?></p>

						<div class="post-content">
							<?php the_content(); ?>
						</div>

					</div>

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