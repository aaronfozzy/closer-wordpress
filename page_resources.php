<?php

/**
 * Template name: Resources
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
				<?php the_content(); ?>
			</div>
		</section>
		<section class="external-resource-links-wrapper grey purple">
			<div class="inner">
				<div class="intro">
					<?php $value1 = get_field( "section_2_title", 2 ); ?>
					<?php $value2 = get_field( "section_2_intro", 2 ); ?>
					<h2><?php echo $value1; ?></h2>
					<p><?php echo $value2; ?></p>
				</div>
				<div class="external-resource-links flex col-2">
											<?php 
							$args = array(
								'post_type' => array('closer_websites'),
								'posts_per_page' => '-1',
								'post_status' => 'publish'

							);
							$the_query = new WP_Query( $args );
							// The Loop
							if ( $the_query->have_posts() ) {
								while ( $the_query->have_posts() ) {
									$the_query->the_post();
									?>
									<div class="external-resource boxed border shadow">
										<?php 
										$image = get_field('closer_website_image');
										if( !empty( $image ) ): ?>
										    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
										<?php endif; ?>
										<?php the_content(); ?>										
										<a class="button" href="<?php the_field('closer_website_url'); ?>" target="_blank">Visit website</a>
									</div>
									<?php } 
								wp_reset_postdata();
							}
						?>
				</div>
			</div>
		</section>
		<section>
			<div class="inner">
			  	<div class="flex col-2 a-stretch">
			  		<?php if(get_field('block_1_title')){ ?>
			  		<div class="teaser border content-stretch">
		  				<h3><?php the_field('block_1_title'); ?></h3>
		  				<p><?php the_field('block_1_intro'); ?></p>
		  				<div class="center">
		  					<a href="<?php the_field('block_1_url'); ?>" class="button wide">Read more</a>
		  				</div>
		  			</div>
		  			<?php } ?>
			  		<?php if(get_field('block_2_title')){ ?>
			  		<div class="teaser border content-stretch">
		  				<h3><?php the_field('block_2_title'); ?></h3>
		  				<p><?php the_field('block_2_intro'); ?></p>
		  				<div class="center">
		  					<a href="<?php the_field('block_2_url'); ?>" class="button wide">Read more</a>
		  				</div>
		  			</div>
		  			<?php } ?>
			  		<?php if(get_field('block_3_title')){ ?>
			  		<div class="teaser border content-stretch">
		  				<h3><?php the_field('block_3_title'); ?></h3>
		  				<p><?php the_field('block_3_intro'); ?></p>
		  				<div class="center">
		  					<a href="<?php the_field('block_3_url'); ?>" class="button wide">Read more</a>
		  				</div>
		  			</div>	
		  			<?php } ?>
			  		<?php if(get_field('block_4_title')){ ?>
			  		<div class="teaser border content-stretch">
		  				<h3><?php the_field('block_4_title'); ?></h3>
		  				<p><?php the_field('block_4_intro'); ?></p>
		  				<div class="center">
		  					<a href="<?php the_field('block_4_url'); ?>" class="button wide">Read more</a>
		  				</div>
		  			</div>		
		  			<?php } ?>				  		
		     	</div>
	     	</div>
		</section>

		

		</article><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; endif; ?>

</div>
</div>

<?php get_footer(); ?>