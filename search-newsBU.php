<?php
// done to avoid wp_reset_query() to have influence
$args = array(
	'post_type' => 'study',
	'posts_per_page' => 999
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
if (isset($_GET['s']) || isset($_GET['studyprod']) || isset($_GET['filt_theme']) || isset($_GET['filt_work']) || isset($_GET['start_date']) || isset($_GET['end_date'])) {
	global $wp_query;
	$validKeys = array(
		's',
		'studyprod',
		'filt_theme',
		'filt_work',
		'start_date',
		'end_date'
	);
	$data = clean_array($_GET, $validKeys);
	$modifications['post_type'] = 'news';
	
	if (isset($data['s'])) {
		$modifications['s'] = $data['s'];
	}
	$taxbits_news_theme = '';
	if (isset($data['filt_theme'])) {
		$taxbits_news_theme = array(
			'taxonomy' => 'news_theme',
			'field'    => 'slug',
			'terms'    => $data['filt_theme']
		);
	}
	$taxbits_news_work = '';
	if (isset($data['filt_work'])) {
		$taxbits_news_work = array(
			'taxonomy' => 'areas_of_work',
			'field'    => 'slug',
			'terms'    => $data['filt_work']
		);
	}
	$metabits_prod = '';
	if (isset($data['studyprod']) && !empty($data['studyprod'])) {
		$q = get_page_by_path( $data['studyprod'], OBJECT, 'study' );
		
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
	if (isset($data['studyprod']) && !empty($data['studyprod']) || isset($data['filt_theme']) && !empty($data['filt_theme']) || isset($data['filt_work']) && !empty($data['filt_work'])) {
		
		$modifications['meta_query'] = array(
			$metabits_prod
		);

		if(isset($data['filt_theme']) && !empty($data['filt_theme'])) {
			$modifications['tax_query'] = array(
				$taxbits_news_theme
			);
		}
		if(isset($data['filt_work']) && !empty($data['filt_work'])) {
			$modifications['tax_query'] = array(
				$taxbits_news_work
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
<link rel="canonical" href="<?php echo get_option('home'); ?>/news-opinion/news/">
<?php get_header();
?>

<div id="content" role="main">
	<div id="title-main" class="indent-small wrapper limit-width-narrow">
		<h1 class="large">News</h1>
	</div>
		<div id="filters" class="indent-small nf-filters">
			<div class='toggleHolder'>
				<div class="filter_toggler">
					Filter your search
				</div>
				<div class="filter_toggler close" style="display:none;">
					Hide filters
				</div>
			</div>
			<form class="news-blog-filter filter" action="" method="get">
				
				<input type="hidden" name="s" value="">
				<input type="hidden" name="post_type" value="news">
				<div class="filter_row">
					<label for="studyprod">Related Study</label>
					<select name="studyprod" id="studyprod">
						<option value="" class="label">All Studies</option>
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
				<div class="filter_row">
					<label for="filt_theme">Theme</label>
					<select name="filt_theme" id="filt_theme">
						<option value="" class="label">All Themes</option>
						<?php
							$news_themes = get_terms( array(
								'taxonomy' => 'news_theme',
								'hide_empty' => true
							) );
							foreach ($news_themes as $news_theme) {
								$selc = '';
								if ($data['filt_theme'] == $news_theme->slug) {
									$selc = ' selected';
								}
								echo '<option value="' . $news_theme->slug . '"' . $selc . '>' . $news_theme->name . '</option>';
							}
						?>
					</select>
				</div>

				<div class="filter_row">
					<label for="filt_work">CLOSER area of work</label>
					<select name="filt_work" id="filt_work">
						<option value="" class="label">All areas of work</option>
						<?php
							$blog_areas_of_works = get_terms( array(
								'taxonomy' => 'areas_of_work',
								'hide_empty' => true
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
					<label for="filt_date">Dates</label>
					<div class="from">
						<span>from</span>
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
								'post_type' => 'news'
							));
							?>
						<option value="" class="label">All Dates</option>
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
								'post_type' => 'news'
							));
							?>
							<option value="" class="label">All Dates</option>
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
						<input type="submit" name="" id="" value="Apply filters">
					</div>
					
				</div>
			</form>
		</div>
	<div class="promo_zone indent-small">
		<span class="left">
		<div class="icon">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/heart.png" alt="">
		</div>
		Love longitudinal? </span>
		<span class="right">
			<p><a href="<?php echo get_option('home'); ?>/sign-latest-news-closer/">Sign up </a> for email newsletters </p>
		
		</span>
	</div>
	<div id="posts" class="indent wrapper limit-width blog_news">
		 <?php if (have_posts()) { ?>
			<?php while (have_posts()) { the_post(); ?>
			<div class="post_container">
				<div class="post_date"><?php echo get_the_date( 'd F Y' ); ?></div>

				<div class="post_wrapper">
					<div class="post_image">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail('thumbnail'); ?>
						</a>
					</div>
					<div class="post_content">
						<div class="post_title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</div>
						<?php 
							$first_name = get_the_author_meta('first_name');
							$last_name = get_the_author_meta('last_name');
						?>
						<div class="post_by_line">by:  <span><?php echo $first_name . ' ' .$last_name; ?>  <!-- director of closer --></span></div>
						<div class="post_description">
							<?php the_excerpt(); ?>
						</div>
						<a href="<?php the_permalink(); ?>" class="post_more">Full story</a>
					</div>
				</div>
			</div>
			<?php }
		} else { ?>
		    <h2>Oops!  There seems to be no results for that combination, please try another.</h2>
		<?php } ?>

		<?php 
		if (  $wp_query->max_num_pages > 1 )
			echo '<div class="load_more_bt more_rec"><span class="icon icon-angle-circled-down"></span>Load more</div>'; // you can use <a> as well
		?>
	</div>
</div>
<?php get_footer(); ?>