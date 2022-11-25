<div class="grid latest_zone">
	<div class="latest_zone_left">
		<?php
			$post_object = get_field('featured_post');
			if( $post_object ):
				// override $post
				$post = $post_object;
				setup_postdata( $post );

				$featured_ID = get_the_ID();
		?>
		
		<a href="<?php the_permalink(); ?>" class="zone_row featured">
			<div class="zone_img">
				<?php $url = wp_get_attachment_url( get_post_thumbnail_id($post->post_id) ); ?>
				<img src="<?php echo $nf->image($url,960,350); ?>" alt="<?php the_title(); ?>">
			</div>
			<div class="zone_desc">
				<div class="zone_title">
					<?php if (get_field('alternative_title')):
					the_field('alternative_title');
					else :
						the_title();
					endif ?>
					
				</div>
				<div class="zone_date"><?php echo get_the_date( 'd F Y' ); ?></div>
			</div>
		</a>
		<?php wp_reset_postdata();?>
	<?php endif; ?>

	<?php 
		$args = array(
			'post__not_in' => array($featured_ID),
			'post_type' => 'news',
			'posts_per_page' => '2',
			'order' => 'DESC',
			'post_status' => 'publish'

		);
		$the_query = new WP_Query( $args );
		// The Loop
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				?>
					<a href="<?php the_permalink(); ?>" class="zone_row">
						<div class="zone_desc">
							<div class="zone_title">
								<?php if (get_field('alternative_title')):
								the_field('alternative_title');
								else :
									the_title();
								endif ?>
								
							</div>
							<div class="zone_date"><?php echo get_the_date( 'd F Y' ); ?></div>
						</div>
					</a>
				<?php } 
			wp_reset_postdata();
		}
	?>
	<a class="link_more" href="<?php echo get_option('home'); ?>/news-opinion/news/">All News</a>
	</div>
	<div class="latest_zone_right">
		
	<!-- Featured Spot 1 -->
		<?php
			$post_object = get_field('featured_spot_1');
			if( $post_object ):
				$post = $post_object;
				setup_postdata( $post );

				if (get_post_type() == 'blog') {
					$featured_class = 'blog';
				} else if (get_post_type() == 'page') {
					$featured_class = 'page';
				}
			?>
			<?php 
			if (get_field('featured_spot_1_button_text', 2) ){
					$button_text = get_field('featured_spot_1_button_text', 2);
				} else if (empty(get_field('featured_spot_1_button_text', 2)) && get_post_type() == 'blog') {
					$button_text = "All blog";
				} else if (empty(get_field('featured_spot_1_button_text', 2)) && get_post_type() == 'event') {
					$button_text = "All events";
				}
				if (get_field('featured_spot_1_button_link', 2) ){
					$button_link = get_field('featured_spot_1_button_link', 2);
				} else if (empty(get_field('featured_spot_1_button_link', 2)) && get_post_type() == 'blog') {
					$button_link = "" .get_option('home'). "/news-opinion/blog/";
				} else if (empty(get_field('featured_spot_1_button_link', 2)) && get_post_type() == 'event') {
					$button_link = "" .get_option('home'). "/event/";
				}
			?>
		<div class="zone_right_row <?php echo $featured_class ?>">
			<a href="<?php the_permalink(); ?>" class="zone_row featured <?php echo $featured_class ?>">
				<div class="zone_img">
					<?php the_post_thumbnail('large'); ?>
				</div>
				<div class="zone_desc">
					<div class="zone_title">
						<?php if (get_field('alternative_title')):
							the_field('alternative_title');
						else :
							the_title();
						endif ?>
									
					</div>
					 
					<?php if (get_field('show_by_field', '2')): ?>
						<div class="zone_auth">By: <?php echo the_field('the_author'); ?></div>
					<?php endif ?>

				</div>
			</a>
			<a class="link_more" href="<?php echo $button_link; ?>">
				<?php echo $button_text; ?></a>
		</div>


		
		<?php wp_reset_postdata();?>
	<?php endif; ?>

	<!-- Featured Spot 1 -->
		<?php
			$post_object = get_field('featured_spot_2');
			if( $post_object ):
				$post = $post_object;
				setup_postdata( $post );

				if (get_post_type() == 'blog') {
					$featured_class = 'blog';
				} else if (get_post_type() == 'page') {
					$featured_class = 'page';
				}


		?>

		<?php if (get_post_type() == 'post' || get_post_type() == 'page'): 

		if (get_field('featured_spot_1_button_text', 2) ){
			$button_text = get_field('featured_spot_1_button_text', 2);
		} else if (empty(get_field('featured_spot_1_button_text', 2)) && get_post_type() == 'blog') {
			$button_text = "All blog";
		}
		if (get_field('featured_spot_1_button_link', 2) ){
			$button_link = get_field('featured_spot_1_button_link', 2);
		} else if (empty(get_field('featured_spot_1_button_link', 2)) && get_post_type() == 'blog') {
			$button_link = "" .get_option('home'). "/news-opinion/blog/";
		}

		?>
			
		
		<div class="zone_right_row <?php echo $featured_class ?>">
			<a href="<?php the_permalink(); ?>" class="zone_row featured <?php echo $featured_class ?>">
				<div class="zone_img">
					<?php the_post_thumbnail('large'); ?>
				</div>
				<div class="zone_desc">
					<div class="zone_title">
						<?php if (get_field('alternative_title')):
							the_field('alternative_title');
						else :
							the_title();
						endif ?>
									
					</div>
					<div class="zone_auth">By: <?php echo the_field('the_author'); ?></div>

				</div>
			</a>
			<a class="link_more" href="<?php echo $button_link; ?>"><?php echo $button_text; ?></a>
		</div>
		<?php endif ?>

	<?php if (get_post_type() == 'event'): ?>

	<?php 

	global $post;
	$meta = get_post_meta($post->ID);
	$event_organiser = get_post_meta( $post->ID, '_slt_event-organiser', true ); 

	$old_date = get_post_meta( $post->ID, '_slt_event_date', true ); 

	// returns Saturday, January 30 10 02:06:34
	$old_date_timestamp = strtotime($old_date);
	$new_date = date('Y-m-d H:i:s', $old_date_timestamp); 

	$day = date('d', $old_date_timestamp);
	$month = date('M', $old_date_timestamp);
	$year = date('Y', $old_date_timestamp); 
	//echo $new_date;


	$event_start_time = get_post_meta( $post->ID, '_slt_event-time-start', true ); 
	$event_end_time = get_post_meta( $post->ID, '_slt_event-time-end', true ); 
	$event_venue = get_post_meta( $post->ID, '_slt_event-venue', true ); 

	if (get_field('featured_spot_2_button_text', 2) ){
		$button_text = get_field('featured_spot_2_button_text', 2);
	} else if (empty(get_field('featured_spot_2_button_text', 2)) && get_post_type() == 'event') {
		$button_text = "All events";
	}
	if (get_field('featured_spot_2_button_link', 2) ){
		$button_link = get_field('featured_spot_2_button_link', 2);
	} else if (empty(get_field('featured_spot_2_button_link', 2)) && get_post_type() == 'event') {
		$button_link = "" .get_option('home'). "/event/";
	}

	 ?>

		<div class="zone_right_row events">
			<a href="<?php the_permalink(); ?>" class="zone_row">
				<div class="zone_desc">
					<div class="zone_title"><?php the_title(); ?></div>
					<p><?php echo $event_organiser; ?></p>
				</div>
				<div class="event_desc">
					<div class="event_date">
							<time datetime="<?php echo $new_date; ?>" class="date" itemprop="startDate" content="<?php echo $new_date; ?>">
							<span class="month"><?php echo $month; ?></span>
							<span class="day"><?php echo $day; ?></span>
							<span class="year"><?php echo $year; ?></span>
							</time>
					</div>
					<div class="event_meta">
						<div class="venue">
								<span>venue:  </span><?php echo $event_venue; ?>
						</div>
						<div class="time">
							<span>time:</span>  <?php echo $event_start_time; ?> - <?php echo $event_end_time; ?>
						</div>
					</div>
				</div>
		</a>
		<a class="link_more" href="<?php echo $button_link; ?>"><?php echo $button_text; ?></a>
	</div>
		<?php endif ?>
		
		<?php wp_reset_postdata();?>
	<?php endif; ?>
	</div>
</div>