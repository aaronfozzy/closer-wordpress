<?php
	get_header();
	
?>
<?php

// Output colour class

$pickedcolour = $closer_filters['colours'] ? null : pilau_get_first_term( 'colours' );

if(!is_null($pickedcolour)){
	echo '<div class="' . $pickedcolour->slug . '">';
}else{
	echo '<div class="blue">';
}
?>
<div id="content" role="main">
	<section class="page-title">
		<div class="inner">
			<h1>Blogs</h1>
		</div>
	</section>
	<?php require( dirname( __FILE__ ) . '/templates/blog_filter.php' ); ?>
	<section>
		<div class="inner">
			<div class="flex col-2">
				<?php if (have_posts()) { ?>
					<?php while (have_posts()) { the_post(); ?>
						<?php 
							$post_id = get_the_ID();
							$area_terms = get_the_terms( $post_id, 'blog_areas_of_work' );

							if (empty($area_terms)) {
			                    $area_terms = 'Blog';
			                }

			                if($area_terms !== 'Blog'){
				                $area_terms = $area_terms[0]->name;
				            }
						?>
						<div class="teaser-news radius-big boxed boxed-small with-label flex-mob content-stretch">							
							<?php echo '<div class="float-label float-label-left">' . $area_terms . '</div>'; ?>	
							<div class="float-label float-label-right icon calendar">
								<?php echo get_the_date( 'd F Y' ); ?>
							</div>
							<div class="image-wrap">
								<?php the_post_thumbnail('thumbnail'); ?>
							</div>
							<div class="teaser-content">
								<h3><?php the_title(); ?></h3>
								<?php the_excerpt(); ?>
								<a href="<?php the_permalink(); ?>" class="linked arrow">Read more</a>
							</div>
						</div>
					<?php }
				} ?>
				<?php 
				if (  $wp_query->max_num_pages > 1 )
					echo '<div class="button load_more_bt more_rec"><span class="icon icon-angle-circled-down"></span>Load more</div>'; // you can use <a> as well
				?>
	</div>
	
</div>
</div>
<?php get_footer(); ?>