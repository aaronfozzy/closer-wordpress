<?php

/**
 * Template name: Legacy template
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

			<div class="inner content underline clearfix">
				<div class="wrapper limit-width">
					<div class="flex col-2-1">
						<div class="left-col">
							<?php the_content(); ?>
						</div>
						<aside>
							<a href="/research-fund-2/data-linkage/" class="button button-strong">Data linkage</a>
							<a href="/research-fund-2/data-harmonisation/" class="button button-strong">Data harmonisation</a>
							<a href="/research-fund-2/research-fund-3/" class="button button-strong">Other research we fund</a>
						</aside>
					</div>
				</div>
			</div>

			<?php

			// Child pages to list?
			$closer_child_pages = get_children( array(
				'post_parent'		=> get_the_ID(),
				'post_type'			=> 'page',
				'post_status'		=> 'publish',
				'orderby'			=> 'menu_order',
				'order'				=> 'ASC'
			));
			if ( $closer_child_pages ) {

				echo '<section>';
				echo '<div class="inner">';
				echo '<div class="communities-of-practice-wrapper">';
				echo '<h2>Related projects</h2>';
				echo '<div class="teasers flex col-3">';
				foreach ( $closer_child_pages as $closer_child_page ) {

					?>
						<div class="teaser grey">
							<h3><?php if (get_field('alternative_title', $closer_child_page->ID)):
								echo the_field('alternative_title', $closer_child_page->ID);
								else :
									echo get_the_title( $closer_child_page );
								endif ?></h3>
							<p>
								<?php $child_page_meta_desc = get_post_meta( $closer_child_page->ID, '_yoast_wpseo_metadesc', true ); 
								echo $child_page_meta_desc;
								?>
							</p>
							<a href="<?php echo get_permalink( $closer_child_page ); ?>" class="button button-strong">Read more</a>
						</div>
					<?php

				}
				echo '</div>';
				echo '</div>';
				echo '</div>';
				echo '</section>';
			}

			?>


		</article><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; endif; ?>

</div>
</div>

<?php get_footer(); ?>