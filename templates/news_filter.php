<section class="filter-wrap">
	<div id="filters">
		<div class="inner">
			<form class="news-blog-filter filter flex jc-start" action="<?php echo get_option('home'); ?>/news-opinion/news/" method="get">	
				<input type="hidden" name="s" value="">
				<input type="hidden" name="post_type" value="news">
				<div class="filter_row">
					<label for="studyprod">Related study</label>
						<select name="studyprod" id="studyprod">
							<option value="" class="label">All studies</option>
							<?php 
								$args = array(
									'post_type' => 'study',
									'posts_per_page' => 999,
									'orderby'=> 'title',
									'order' => 'ASC'
								);
								$query = new WP_Query( $args );
								if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post();
							 ?>
							<option value="<?php echo $post->post_name; ?>"><?php the_title(); ?></option>
							<?php } } wp_reset_query(); ?>
						</select>
					</div>

				<div class="filter_row">
					<label for="filt_theme">Topic</label>
					<select name="news_theme" id="filt_theme">
						<option value="" class="label">All topics</option>
						
						<?php 
							$news_themes = get_terms( array(
								'taxonomy' => 'news_theme',
								'hide_empty' => true,
								'orderby'=> 'title',
								'order' => 'ASC',
							) );
							foreach ($news_themes as $news_theme) {
								echo '<option value="'.$news_theme->slug.'">'.$news_theme->name.'</option>';
							} ?>
					</select>
				</div>
				<div class="filter_row">
					<label for="filt_work">CLOSER area of work</label>
					<select name="filt_work" id="filt_work">
						<option value="" class="label">All areas of work</option>
						
						<?php 
							$news_areas_of_works = get_terms( array(
								'taxonomy' => 'areas_of_work',
								'hide_empty' => true,
								'orderby'=> 'title',
								'order' => 'ASC',
							) );
							foreach ($news_areas_of_works as $news_areas_of_work) {
								echo '<option value="'.$news_areas_of_work->slug.'">'.$news_areas_of_work->name.'</option>';
							} ?>
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
								'post_type' => 'news'
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

						 
						<option value="<?php echo $full_date; ?>" class="label"><?php the_time('F Y'); ?></option>
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
								'post_type' => 'news'
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
							 
							<option value="<?php echo $full_date; ?>" class="label"><?php the_time('F Y'); ?></option>
							<?php $ul_open = true; ?>
							<?php endif; ?>
							<?php $previous_year = $year; $previous_month = $month; ?>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="filter_submit">
					<input type="submit" class="button" name="" id="" value="Apply filters">
				</div>

			</form>
		</div>
	</div>
</section>