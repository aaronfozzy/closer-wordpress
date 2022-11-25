<?php

/**
 * Study
 *
 * @package	CLOSER-2022
 * @since	0.1
 */
global $pilau_custom_fields;

?>

<?php get_header(); ?>

<div id="content" role="main">

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
		<section>
			<div class="inner">
				<div class="flex col-2-0">
					<div>
						<h1><?php the_title(); ?></h1>
					</div>
				</div>
				<div class="flex col-2-1">
					<div class="main-content">
						<?php the_content(); ?>
						<hr>
						<?php
							$rel1 = get_post_meta( $post->ID, '_slt_related-content-1', true ); 
							$rel2 = get_post_meta( $post->ID, '_slt_related-content-2', true ); 
							$rel3 = get_post_meta( $post->ID, '_slt_related-content-3', true ); 

							if ($rel1 || $rel2 || $rel3) {
								closer_related_content_section_detail();
							} else {
							?>
							<div class="related_block">
								<section id="related-content" class="indent underline heading-left jump-section">
									<div class="wrapper limit-width">
										<h1 class="heading">Related content</h1>
										<div class="body">
											<ul class="content-items clearfix cols-2">
											<?php 
												$args = array(
												'post_type'		=> array('news', 'blog'),
												'posts_per_page'	=> 3,
												'meta_query'		=> array(
													array(
														'key' => 'related_study',
														'value' => '"' . get_the_ID() . '"',
														'compare' => 'LIKE'
														)
													)
												);

												$wp_query = new WP_Query( $args );

												$post_count = 0;
												$last_count = 0;
												while( $wp_query->have_posts() ){ 
													$post_count++;
													if ($post_count == 1) {
														$col_class = "highlight";
													} else {
														$col_class = "col col-1";
													}

													?>

												<?php $wp_query->the_post(); 
												$post_type = get_post_type( get_the_ID() );
												?>

													<li class="item item-<?php echo $post_count; ?> <?php echo $col_class; ?> <?php echo ++$last_count%2 ? "last" : "" ?> clearfix post-type-news has-image has-themes has-post-type-themes">
														<div class="body clearfix">
															<figure class="image">
																<a class="image-wrapper no-icon" title="Click to read more" href="<?php the_permalink(); ?>">
																	<?php $header_image = wp_get_attachment_url( get_post_thumbnail_id($post->post_id) ); ?>
																	<img src="<?php echo $nf->image($header_image,250,130); ?>" alt="<?php the_title(); ?>">
																</a>
															</figure>
															<div class="text">
																<h2>
																<a class="image-wrapper" title="Click to read more" href="<?php the_permalink(); ?>" rel="permalink"><?php the_title(); ?></a>
																</h2>
																<p class="post-type-themes">
																	<span class="post-type"><?php echo $post_type; ?></span>
																</p>
																<div class="teaser"><?php the_excerpt(); ?></div>
															</div>
														</div>
													</li>
												<?php } ?>
											</ul>
										</div>
									</div>
								</section>
							</div>
						<?php } ?>
					    <?php if ( ! empty( $pilau_custom_fields['study-url'] ) ) { ?>
							<section class="grey boxed boxed-40">
								<h2>Visit the study website</h2>
					    		<div class="link-list">
					    			<ul>
					    				<li><a href="<?php echo esc_url( $pilau_custom_fields['study-url'] ); ?>"><?php echo preg_replace( '#^https?://#i', '', $pilau_custom_fields['study-url'] ); ?></a></li>
					    			</ul>
					    		</div>
						    </section>
						<?php }  ?>
						<?php if ( ! empty( $pilau_custom_fields['study-related-video'] ) ) { ?>
					    <h2>Related videos</h2>
					    <?php echo $pilau_custom_fields['study-related-video']; ?>
						<?php }  ?>
					</div>

					<aside>
					<?php if ( $pilau_custom_fields['study-alt-title'] ) { ?>
						<p class="alt-title"><?php echo $pilau_custom_fields['study-alt-title']; ?></p>
					<?php } ?>
					<div class="boxed boxed-med grey"><div class="logo-wrap">
						<?php echo get_the_post_thumbnail(); ?>
					</div></div>
					<?php if ( ! empty( $pilau_custom_fields['study-time-period'] ) ) { ?>
						<h3>Time period</h3>
						<p class="boxed boxed-med grey"><?php echo $pilau_custom_fields['study-time-period']; ?></p>
					<?php } ?>

					<?php if ( ! empty( $pilau_custom_fields['study-sample-size'] ) ) { ?>
						<h3>Original Sample size</h3>
						<p class="boxed boxed-med grey"><?php echo number_format( preg_replace( '/[^0-9]/', '', $pilau_custom_fields['study-sample-size'] ) ); ?><br><?php if ( ! empty( $pilau_custom_fields['study-sample-size-copy'] ) ) { ?>
						<?php echo $pilau_custom_fields['study-sample-size-copy']  ?></p>
						<?php } ?>
						</p>
					<?php } ?>
						
				<?php if ( ! empty( $pilau_custom_fields['study-team-member-1'] ) ) { ?>
						<h3>Director(s)</h3>
						<div class="boxed boxed-med grey team-member">
							<?php for ( $i = 1; $i <= CLOSER_STUDY_NUM_TEAM_MEMBERS; $i++ ) { ?>
								<?php if ( ! empty(  $pilau_custom_fields['study-team-member-' . $i] ) ) { ?>
									<?php $closer_tm_name = get_the_title( $pilau_custom_fields['study-team-member-' . $i] ); ?>
									<p><?php echo $closer_tm_name; ?></p>
									<img class="radius" src="<?php echo pilau_get_featured_image_url( $pilau_custom_fields['study-team-member-' . $i], 'post-thumbnail' ); ?>" alt="<?php echo $closer_tm_name; ?> photo">							
								<?php } ?>
							<?php } ?>

						</div>

				<?php } ?>


				<?php if ( ! empty( $pilau_custom_fields['study-funders'] ) ) { ?>
						<h3>Core funders</h3>
						<?php

						// Get the funders
						$closer_funders = new WP_Query( array(
							'post_type'			=> 'funder',
							'posts_per_page'	=> -1,
							'post__in'			=> $pilau_custom_fields['study-funders'],
							'orderby'			=> 'menu_order',
							'order'				=> 'ASC',
							//'pilau_multiply'	=> 3
						));

						if ( $closer_funders->have_posts() && function_exists( 'slt_cf_field_value' ) ) {

							echo '<div class="boxed boxed-med grey"><div class="logo-wrap">';

							while ( $closer_funders->have_posts() ) {
								$closer_funders->the_post();

								echo '<a class="no-icon" href="' . esc_url( slt_cf_field_value( 'funder-url' ) ) . '"><img src="' . pilau_get_featured_image_url( get_the_ID(), 'full' ) . '" alt="' . get_the_title() . ' logo"></a>';

							}

							echo '</div></div>';

						}

						// Reset query
						wp_reset_postdata();

						?>

				<?php } ?>


				<?php if ( ! empty( $pilau_custom_fields['study-host'] ) ) { ?>

						<h3>Host institution</h3>
						<div class="boxed boxed-med grey"><div class="logo-wrap">
							<a href="<?php echo esc_url( $pilau_custom_fields['study-host-url'] ); ?>"><img src="<?php echo pilau_get_image_url( $pilau_custom_fields['study-host-logo'], 'full' ); ?>" alt="<?php echo esc_attr( $pilau_custom_fields['study-host'] ) . ' logo'; ?>"></a>
						</div></div>

				<?php } ?>
					</aside>
				</div>
			</div>
		</section>
	</article>

</div>

<?php get_footer(); ?>