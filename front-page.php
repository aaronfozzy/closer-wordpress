<?php
/**
* Front page
*
	* @package	CLOSER-2022
	* @since	0.1
*/
global $pilau_custom_fields;
?>
<?php get_header(); ?>
<div id="content" role="main">
		<section class="hero bubbles">
			<div class="inner">
				<div class="flex flex-wide col-2 a-stretch">
					<!-- Homepage message -->
					<?php include "templates/home-temps/intro_banner.php"; ?>
					<!-- Homepage news teasers -->
					<div class="hero-news-teasers blue">
						<?php 
							$args = array(
								'post_type' => array('news', 'blog'),
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
										<div class="teaser-news radius-big boxed boxed-small with-label flex-mob">
											<div class="float-label float-label-left">
												<?php
													$post_type = get_post_type( $post->ID );
													echo $post_type;
												?>
											</div>
											<div class="float-label float-label-right icon calendar">
												<?php echo get_the_date( 'd F Y' ); ?>
											</div>
											<div class="image-wrap">
												<?php
												if(has_post_thumbnail()){
	                  								the_post_thumbnail();
	             								} ?>
											</div>
											<div class="teaser-content">
												<h3><?php if (get_field('alternative_title')):
													the_field('alternative_title');
													else :
														the_title();
													endif ?></h3>
												<?php the_excerpt(); ?>
												<a href="<?php the_permalink(); ?>" class="linked arrow">Read more</a>
											</div>
										</div>
									<?php } 
								wp_reset_postdata();
							}
						?>
						<div class="buttons jc-center">
							<a href="/news-opinion/news/" class="button button-strong">All News</a>
							<a href="/news-opinion/blog/" class="button button-strong">All Blogs</a>
						</div>
					</div>
				</div>
				<!-- <a href="#" class="skip-down"></a> -->
			</div>
		</section>

		<section>
			<div class="inner">
				<div class="intro">					
					<h2><?php the_field('column_title'); ?></h2>
				</div>
				<div class="teasers flex col-3 sh boxed boxed-40 grey">
					<?php
						$post_object = get_field('highlighted_article_1_title');
						if( $post_object ):
							$post = $post_object;
							setup_postdata( $post );
						?>
							<div class="teaser">
								<h3><?php the_field('highlighted_article_1_title'); ?></h3>
								<div class="right">
									<p><?php the_field('highlighted_article_1_intro'); ?></p>
									<?php 
										$link = get_field('highlighted_article_1_link');
										if( $link ): 
										    $link_url = $link['url'];
										    $link_title = $link['title'];
										    $link_target = $link['target'] ? $link['target'] : '_self';
									    ?>
									    <a class="button" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
									<?php endif; ?>
								</div>
							</div>
						<?php wp_reset_postdata();?>
						<?php endif; ?>
					<?php
						$post_object = get_field('highlighted_article_2_title');
						if( $post_object ):
							$post = $post_object;
							setup_postdata( $post );
						?>
							<div class="teaser">
								<h3><?php the_field('highlighted_article_2_title'); ?></h3>
								<div class="right">
									<p><?php the_field('highlighted_article_2_intro'); ?></p>
									<?php 
										$link = get_field('highlighted_article_2_link');
										if( $link ): 
										    $link_url = $link['url'];
										    $link_title = $link['title'];
										    $link_target = $link['target'] ? $link['target'] : '_self';
									    ?>
									    <a class="button" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
									<?php endif; ?>
								</div>
							</div>
						<?php wp_reset_postdata();?>
						<?php endif; ?>
					<?php
						$post_object = get_field('highlighted_article_3_title');
						if( $post_object ):
							$post = $post_object;
							setup_postdata( $post );
						?>
							<div class="teaser">
								<h3><?php the_field('highlighted_article_3_title'); ?></h3>
								<div class="right">
									<p><?php the_field('highlighted_article_3_intro'); ?></p>
									<?php 
										$link = get_field('highlighted_article_3_link');
										if( $link ): 
										    $link_url = $link['url'];
										    $link_title = $link['title'];
										    $link_target = $link['target'] ? $link['target'] : '_self';
									    ?>
									    <a class="button" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
									<?php endif; ?>
								</div>
							</div>
						<?php wp_reset_postdata();?>
						<?php endif; ?>
				</div>
			</div>
		</section>

		<section class="external-resource-links-wrapper grey purple">
			<div class="inner">
				<div class="intro">
					<h2><?php the_field('section_2_title'); ?></h2>
					<p><?php the_field('section_2_intro'); ?></p>
				</div>
				<div class="external-resource-links flex col-2">
						<?php 
							$args = array(
								'post_type' => array('closer_websites'),
								'posts_per_page' => '-1',
								'post_status' => 'publish',
								'order' => 'ASC',
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
				<div class="center">
					<a href="/our-resources" class="button button-strong">More resources</a>
				</div>
			</div>
		</section>
		<section class="communities-of-practice-wrapper bubbles pink">
			<div class="inner">
				<div class="intro">					
					<h2><?php the_field('section_3_title'); ?></h2>
					<p><?php the_field('section_3_intro'); ?></p>
				</div>
				<div class="teasers flex col-3 sh">
					<?php
						$post_object = get_field('communities_of_practice_1_title');
						if( $post_object ):
							$post = $post_object;
							setup_postdata( $post );
						?>
							<div class="teaser grey">
								<h3><?php the_field('communities_of_practice_1_title'); ?></h3>
								<div class="right">
									<p><?php the_field('communities_of_practice_1_intro'); ?></p>
									<a href="<?php the_field('communities_of_practice_1_link'); ?>" class="button">Read more</a>
								</div>
							</div>
						<?php wp_reset_postdata();?>
						<?php endif; ?>
					<?php
						$post_object = get_field('communities_of_practice_2_title');
						if( $post_object ):
							$post = $post_object;
							setup_postdata( $post );
						?>
							<div class="teaser grey">
								<h3><?php the_field('communities_of_practice_2_title'); ?></h3>
								<div class="right">
									<p><?php the_field('communities_of_practice_2_intro'); ?></p>
									<a href="<?php the_field('communities_of_practice_2_link'); ?>" class="button">Read more</a>
								</div>
							</div>
						<?php wp_reset_postdata();?>
						<?php endif; ?>
					<?php
						$post_object = get_field('communities_of_practice_3_title');
						if( $post_object ):
							$post = $post_object;
							setup_postdata( $post );
						?>
							<div class="teaser grey">
								<h3><?php the_field('communities_of_practice_3_title'); ?></h3>
								<div class="right">
									<p><?php the_field('communities_of_practice_3_intro'); ?></p>
									<a href="<?php the_field('communities_of_practice_3_link'); ?>" class="button">Read more</a>
								</div>
							</div>
						<?php wp_reset_postdata();?>
						<?php endif; ?>

				</div>
			</div>
		</section>
		<section class="grey green">
			<div class="inner inner-1000">
				<h2><?php the_field('section_title'); ?></h2>
				<?php
				$featured_post = get_field('select_event');
				if( $featured_post ): 

				$terms = get_the_terms( $featured_post->ID, 'event_type');
				
				foreach ($terms as $term) 
    			$term_names[] = $term->name;
				$term_name_str = implode("','",$term_names);

				$check_fields = get_post_custom($featured_post->ID);

				$event_date = $check_fields['_slt_event_date'][0];

				$date_string = strtotime($event_date);

				$format_date = date('d M Y', $date_string);
				?>

				<div class="teaser-event boxed boxed-40 flex">
					<div class="teaser-event-content">
						<p class="tabbed"><?php echo $term_name_str; ?></p>
						<h3><?php echo esc_html( $featured_post->post_title ); ?></h3>
					</div>
					<div class="teaser-event-aside">
						<p class="icon calendar"><?php echo $format_date; ?></p>
						<a href="<?php echo esc_html( $featured_post->guid ); ?>" class="button">Read more</a>
					</div>
				</div>
				
				<?php endif; ?>
				<div class="center">
					<a href="/events-training" class="button button-strong">All events</a>
				</div>

			</div>
		</section>
		<section class="content-4">
			<div class="inner">
				<div class="flex col-2 a-stretch">
					<div>
						<h2><?php the_field('left_col_title'); ?></h2>
						<div class="boxed boxed-40 grey">
							<div class="selected-articles">
								<?php if(get_field('selected_article_1_title')){ ?>
									<div class="article boxed boxed-med">
										<h3><?php the_field('selected_article_1_title'); ?></h3>
										<p><?php the_field('selected_article_1_intro'); ?></p>
										<div class="right">
										<?php 
											$link = get_field('selected_article_1_link');
											if( $link ): 
											    $link_url = $link['url'];
											    $link_target = $link['target'] ? $link['target'] : '_self';
											    ?>
											    <a class="button" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>">Read more</a>
											<?php endif; ?>
										</div>
									</div>
								<?php } ?>
								<?php if(get_field('selected_article_2_title')){ ?>
									<div class="article boxed boxed-med">
										<h3><?php the_field('selected_article_2_title'); ?></h3>
										<p><?php the_field('selected_article_2_intro'); ?></p>
										<div class="right">
										<?php 
											$link2 = get_field('selected_article_2_link');
											if( $link2 ): 
											    $link2_url = $link2['url'];
											    $link2_target = $link2['target'] ? $link2['target'] : '_self';
											    ?>
											    <a class="button" href="<?php echo esc_url( $link2_url ); ?>" target="<?php echo esc_attr( $link2_target ); ?>">Read more</a>
											<?php endif; ?>
										</div>
									</div>
								<?php } ?>
								<?php if(get_field('selected_article_3_title')){ ?>
									<div class="article boxed boxed-med">
										<h3><?php the_field('selected_article_3_title'); ?></h3>
										<p><?php the_field('selected_article_3_intro'); ?></p>
										<div class="right">
										<?php 
											$link3 = get_field('selected_article_3_link');
											if( $link3 ): 
											    $link3_url = $link3['url'];
											    $link3_target = $link3['target'] ? $link3['target'] : '_self';
											    ?>
											    <a class="button" href="<?php echo esc_url( $link3_url ); ?>" target="<?php echo esc_attr( $link3_target ); ?>">Read more</a>
											<?php endif; ?>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="tweets-wrap">
						<h2><?php the_field('right_col_title'); ?></h2>
						<div class="tweets-main">
							<a id="container" class="twitter-timeline" 
								data-theme="light" 
								data-link-color="#f32f66" 
								data-chrome="noheader, nofooter"
								href="https://twitter.com/CLOSER_UK?ref_src=twsrc%5Etfw"> tweets by @CLOSER_UK</a> 
							<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php include "inc/newsletter-signup.php"; ?>

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<?php endwhile; endif; ?>
		
</div>
<?php get_footer(); ?>