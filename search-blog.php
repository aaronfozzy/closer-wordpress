<?php
// done to avoid wp_reset_query() to have influence
$args = array(
	'post_type' => 'study',
	'posts_per_page' => 999,
	'orderby'=> 'title',
	'order' => 'ASC'
);
$query = new WP_Query( $args );
$prodarr = array();
$prodarrc = 1;
if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post();
	$prodarr[$prodarrc]['title'] = get_the_title();
	$prodarr[$prodarrc]['slug'] = $post->post_name;
	$prodarrc++;
} }
wp_reset_query();
// filter
if (isset($_GET['s']) || isset($_GET['studyprod']) || isset($_GET['blog_theme']) || isset($_GET['filt_work']) || isset($_GET['start_date']) || isset($_GET['end_date'])) {
	global $wp_query;
	$validKeys = array(
		's',
		'studyprod',
		'blog_theme',
		'filt_work',
		'start_date',
		'end_date'
	);
	$data = clean_array($_GET, $validKeys);
	$modifications['post_type'] = 'blog';
	
	if (isset($data['s'])) {
		$modifications['s'] = $data['s'];
	}
	$taxbits_blog_theme = '';
	if (isset($data['blog_theme'])) {
		$taxbits_blog_theme = array(
			'taxonomy' => 'blog_theme',
			'field'    => 'slug',
			'terms'    => $data['blog_theme']
		);
	}
	$taxbits_blog_work = '';
	if (isset($data['filt_work'])) {
		$taxbits_blog_work = array(
			'taxonomy' => 'blog_areas_of_work',
			'field'    => 'slug',
			'terms'    => $data['filt_work']
		);
	}
	$metabits_prod = '';
	if (isset($data['studyprod']) && !empty($data['studyprod'])) {
		$q = get_page_by_path( $data['studyprod'], OBJECT, 'studyprod' );
		
		$metabits_prod = array(
			'key' => 'related_study',
			'value' => $q->ID,
			'compare' => 'LIKE'
		);
	}
	if(isset($data['start_date']) && !empty($data['start_date']) || isset($data['end_date']) && !empty($data['end_date'])) {

			$from_post_date = $data['start_date'];
			$to_post_date = $data['end_date'];
			
			$modifications['date_query'] = array(

				'after'     => date('M d, Y', $data['start_date']),
				'before'   => date('M d, Y', $data['end_date']),
				'inclusive' => true,
			);
		}
	if (isset($data['studyprod']) && !empty($data['studyprod']) || isset($data['blog_theme']) && !empty($data['blog_theme']) || isset($data['filt_work']) && !empty($data['filt_work'])) {
		
		$modifications['meta_query'] = array(
			$metabits_prod
		);

		if(isset($data['blog_theme']) && !empty($data['blog_theme'])) {
			$modifications['tax_query'] = array(
				$taxbits_blog_theme
			);
		}
		if(isset($data['filt_work']) && !empty($data['filt_work'])) {
			$modifications['tax_query'] = array(
				$taxbits_blog_work
			);
		}
	}
	$args = array_merge(
		$wp_query->query_vars,
		$modifications
	);
	query_posts( $args );
}
?>
<link rel="canonical" href="<?php echo get_option('home'); ?>/news-opinion/blog/">
<?php get_header(); ?>
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
				<h1>Blog</h1>
			</div>
		</section>
		<section class="filter-wrap">
			<div id="filters" class="indent-small green-darker nf-filters">
				<div class="inner">
					<form class="news-blog-filter filter flex jc-start" action="" method="get">
						<input type="hidden" name="s" value="">
						<input type="hidden" name="post_type" value="blog">
						<div class="filter_row">
							<label for="studyprod">Related study</label>
							<select name="studyprod" id="studyprod">
								<option value="" class="label">All studies</option>
								<?php
									foreach ($prodarr as $prods) {
										$selp = '';
										if ($data['studyprod'] == $prods['slug']) {
											$selp = ' selected';
										}
								?>
								<option value="<?php echo $prods['slug']; ?>"<?php echo $selp; ?>><?php echo $prods['title']; ?></option>
								<?php } ?>
							</select>
						</div>

						<?php closer_filter_select_new( 'blog_theme', __( 'Topic' ), __( 'All topics' ) ); ?>

						<div class="filter_row">
							<label for="filt_work">CLOSER area of work</label>
							<select name="filt_work" id="filt_work">
								<option value="" class="label">All areas of work</option>
								<?php
									$blog_areas_of_works = get_terms( array(
										'taxonomy' => 'blog_areas_of_work',
										'hide_empty' => true,
										'orderby'=> 'title',
										'order' => 'ASC'
									) );
									foreach ($blog_areas_of_works as $blog_areas_of_work) {
										$selc = '';
										if ($data['filt_work'] == $blog_areas_of_work->slug) {
											$selc = ' selected';
										}
										echo '<option value="' . $blog_areas_of_work->slug . '"' . $selc . '>' . $blog_areas_of_work->name . '</option>';
									}
								?>
							</select>
						</div>

						<div class="filter_row sub-filter">
							<div class="from">
								<span>From</span>
								<select name="start_date" id="filt_date" class="from">
								<?php
								$numposts = -1;
									$previous_year = $year = 0;
									$previous_month = $month = 0;
									$ul_open = false;
									$myposts = get_posts(array(
										'numberposts' => $numposts,
										'orderby' => 'post_date',
										'order' => 'DESC',
										'post_type' => 'blog'
									));
									?>
								<option value="" class="label">All dates</option>
								<?php foreach($myposts as $post) : ?>
								 
								<?php
								setup_postdata($post);
									$year = mysql2date('Y', $post->post_date);
									$month = mysql2date('n', $post->post_date);
									$full_date = strtotime($post->post_date);
								?>
								 
								<?php if ($year != $previous_year || $month != $previous_month) : ?>
								<?php if ($ul_open == true) : ?>
								<?php endif; ?>

								<?php 
								$selc = '';
								if ($data['start_date'] == $full_date) {
									$selc = ' selected';
								} ?>

								 
								<option value="<?php echo $full_date; ?>" class="label" <?php echo $selc; ?>><?php the_time('F Y'); ?></option>
								<?php $ul_open = true; ?>
								<?php endif; ?>
								<?php $previous_year = $year; $previous_month = $month; ?>
								<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="filter_row sub-filter">
							<div class="to">
								<span>To</span>
								<select name="end_date" id="filt_date" class="from">
								<?php
								$numposts = -1;
									$previous_year = $year = 0;
									$previous_month = $month = 0;
									$ul_open = false;
									$myposts = get_posts(array(
										'numberposts' => $numposts,
										'orderby' => 'post_date',
										'order' => 'DESC',
										'post_type' => 'blog'
									));
									?>
									<option value="" class="label">All dates</option>
									<?php foreach($myposts as $post) : ?>
									 
									<?php
									setup_postdata($post);
										$year = mysql2date('Y', $post->post_date);
										$month = mysql2date('n', $post->post_date);
										$full_date = strtotime($post->post_date);
									?>
									 
									<?php if ($year != $previous_year || $month != $previous_month) : ?>
									<?php if ($ul_open == true) : ?>
									<?php endif; ?>
									<?php 
										$selc = '';
										if ($data['end_date'] == $full_date) {
											$selc = ' selected';
										} ?>
									 
									<option value="<?php echo $full_date; ?>" class="label" <?php echo $selc; ?>><?php the_time('F Y'); ?></option>
									<?php $ul_open = true; ?>
									<?php endif; ?>
									<?php $previous_year = $year; $previous_month = $month; ?>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="filter_submit_cont">
							<div class="filter_submit">
								<input type="submit" class="button" name="" id="" value="Apply filters">
							</div>							
						</div>
					</form>
				</div>
			</div>
		</section>
		<section>
			<div class="inner">
				<div id="posts" class="indent wrapper limit-width blog_news">
					<div class="flex col-2">
						 <?php if (have_posts()) { ?>
							<?php while (have_posts()) { the_post(); ?>
								<?php 
									$post_id = get_the_ID();
									$area_terms = get_the_terms( $post_id, 'blog_areas_of_work' );
									$area_terms = $area_terms[0]->name;
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
						} else { ?>
						    <h2>Oops!  There seems to be no results for that combination, please try another.</h2>
						<?php } ?>
					</div>
				</div>
			</div>
		</section>
</div>
</div>
<?php get_footer(); ?>