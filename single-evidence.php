<?php

/**
 * Evidence summary
 *
 * @package	CLOSER-2022
 * @since	0.1
 */
global $pilau_custom_fields;

// Helper function to handle footnote refs in summary tabs
function closer_summary_tab_content( $content ) {
	global $closer_foonote_refs;
	$content = do_shortcode( $content );
	if ( $closer_foonote_refs ) {
		$content = str_replace( '<ol>', '<ol class="refs">', $content );
	}
	echo $content;
}

?>

<?php get_header(); ?>

<div id="content" role="main">

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">

		<!-- Page title -->

	    <section class="page-title">
	      <div class="inner">					
				<h1><?php the_title(); ?></h1>
				<p><?php echo $pilau_custom_fields['hero-text']; ?></p>			
				<div class="date-wrapper">
					<?php if ( $pilau_custom_fields['last-updated'] ) { ?>
						<?php $closer_date_parts = explode( '/', $pilau_custom_fields['last-updated'] ); ?>
						<p class="last-updated"><?php echo __( 'Last updated' ) . ' ' . date( 'F Y', mktime( 0, 0, 0, $closer_date_parts[0], 1, $closer_date_parts[1] ) ); ?></p>
					<?php } ?>
				</div>
			</div>
		</section>

		<!-- In this section links -->

	    <section class="in-this-section grey">
	    	<div class="inner has-aside-links">
	        <div class="left-col aside-links">
	        	<?php if ( ! empty( $pilau_custom_fields['hero-image'] ) ) { ?>
					<div class="image"><img src="<?php echo pilau_get_image_url( $pilau_custom_fields['hero-image'], 'hero-small' ); ?>" alt="<?php the_title(); ?> image">
			         	<div class="article-tags">
			            	<ul>
			              		<?php
								// Themes
								$closer_themes = pilau_objects_array_values( 'name', get_the_terms( get_the_ID(), 'theme' ) );
								if ( $closer_themes ) {
									echo '<li class="';
									if ( count( $closer_themes > 1 ) ) {
										echo 's';
									}
									echo '">' . implode( ', ', $closer_themes ) . '</li>' . "\n";
								}
							?>
			            	</ul>
			          	</div>
					</div>
				<?php } ?>
	        </div>
			</div>
		</section>

		<!-- Evidence summary accordion -->

		<?php if ( ! empty( $pilau_custom_fields['evidence-summary-key-findings'] ) ) { ?>

			<section id="evidence-summary" class="clearfix indent underline jump-section">

				<h1><?php _e( 'Key findings' ); ?></h1>

				<div class="key-findings post-content first-para-normal"><?php echo $pilau_custom_fields['evidence-summary-key-findings']; ?></div>

			</section>

		<?php } else { ?>

		<!-- Additional summary section -->

	    <section class="section-summary jump-section">
	      <div class="inner">
	        <div class="title">
	          <h2 class="jump-title"><?php _e( 'Evidence summary' ); ?></h2>
	          <div class="buttons">
	            <a class="button" href="<?php echo wp_get_attachment_url( $pilau_custom_fields['evidence-summary-pdf'] ); ?>" class="button button-large"><?php _e( 'Download summary PDF' ); ?></a>
	          </div>
	        </div>

					<?php
					// How many tabs?
					$closer_tabs = array();
					for ( $i = 1; $i <= 5; $i++ ) {
						if ( ! empty( $pilau_custom_fields['evidence-summary-heading-' . $i] ) ) {
							$closer_tabs[] = $pilau_custom_fields['evidence-summary-heading-' . $i];
						}
					}

					?>

					<div class="accordion-standard tabs hide-if-no-js hide-for-no-carousel tabs-<?php echo count( $closer_tabs ); ?>">
						<?php $i = 1; ?>
						<?php foreach ( $closer_tabs as $closer_tab ) { ?>
							<h3 id="tab-<?php echo $i; ?>"<?php if ( $i == 1 ) echo ' class="current"'; ?>><a href="#panel-<?php echo $i; ?>"><span class="icon icon-angle-circled-right"></span> <?php echo $closer_tab; ?></a></h3>
								<div class="clearfix panel<?php if ( $i == 1 ) echo ' current'; ?>" id="panel-<?php echo $i ?>">

									<h2><?php echo $closer_tabs[ $i - 1 ]; ?></h2>

									<div class="col-1 post-content first-para-normal">
										<?php closer_summary_tab_content( $pilau_custom_fields['evidence-summary-text-' . $i] ); ?>
									</div>

									<div class="col-2">

										<?php if ( $pilau_custom_fields['evidence-summary-quote-' . $i] ) { ?>

											<blockquote>&ldquo;<?php echo $pilau_custom_fields['evidence-summary-quote-' . $i]; ?>&rdquo;</blockquote>

										<?php } ?>

										<div class="post-content first-para-normal">
											<?php closer_summary_tab_content( $pilau_custom_fields['evidence-summary-text-' . $i . '-col-2'] ); ?>
										</div>

									</div>

								</div>
							<?php $i++; ?>
						<?php } ?>
					</div>
					<!-- End of inner -->
			</div>
		</section>

		<?php } ?>

		<?php if ( $pilau_custom_fields['briefing-paper'] ) { ?>

	    <section class="section-summary jump-section" id="briefing-paper">
	      	<div class="inner">
	        	<div class="title">
	          		<h2 class="jump-title"><?php _e( 'Briefing paper' ); ?></h2>
	          		<div class="buttons">
	            		<a href="<?php echo wp_get_attachment_url( $pilau_custom_fields['briefing-paper'] ); ?>" class="button button-large"><?php _e( 'Download briefing paper' ); ?></a>
	          		</div>
	        	</div>	
				<div class="body">

					<p class="large"><?php echo $pilau_custom_fields['briefing-paper-title']; ?></p>

					<?php if ( $pilau_custom_fields['briefing-paper-image'] ) { ?>
						<p><img class="wp-image" src="<?php echo pilau_get_image_url( $pilau_custom_fields['briefing-paper-image'], 'content-image' ); ?>" alt="<?php _e( 'Briefing paper image' ); ?>"></p>
					<?php } ?>

				</div>
	    	</div>
	    </section>

		<?php } ?>

		<!-- Reading list carousel -->

		<?php

		$closer_citations_count = 0;
		if ( function_exists( 'slt_cf_field_key' ) ) {

			// Get assigned citations
			$closer_citations = new WP_Query( array(
				'post_type'			=> 'citation',
				'posts_per_page'	=> -1,
				'meta_query'		=> array(
					array(
						'key'		=> slt_cf_field_key( 'citation-evidence' ),
						'value'		=> get_queried_object_id()
					)
				),
				'orderby'			=> 'title',
				'order'				=> 'ASC',
				//'pilau_multiply'	=> 5
			));
			$closer_citations_count = $closer_citations->found_posts;

		}

		?>
	    <section class="grey jump-section<?php if ( $closer_citations_count > 4 ) echo ' more-at-bottom'; ?>" id="reading-list">
	        <div class="inner">
	        	<div class="title">
	          		<h2 class="jump-title"><?php _e( 'Selected reading list' ); ?></h2>

	          		<?php if ( $pilau_custom_fields['selected-reading-pdf'] ) { ?>
	          		<div class="buttons">
	            		<a href="<?php echo wp_get_attachment_url( $pilau_custom_fields['selected-reading-pdf'] ); ?>" class="button"><?php _e( 'Download PDF' ); ?></a>
	          		</div>
					<?php } ?>
	        	</div>
	        	<?php if ( $pilau_custom_fields['selected-reading-intro'] ) { ?>
					<p class="large"><?php echo $pilau_custom_fields['selected-reading-intro']; ?></p>
				<?php } ?>
				<?php if ( $closer_citations->have_posts() ) { ?>
		        <div class="carousel-reading carousel-reading-init">
		          	<div class="carousel-prev swiper-button-prev"></div>
		          	<div class="carousel-next swiper-button-next"></div>
		          	<div class="swiper">
		            	<div class="swiper-wrapper citations maybe-more clearfix" data-maybe-more-rows="2">

							<?php $i = 1; ?>
							<?php while ( $closer_citations->have_posts() ) { ?>
								<?php
								$closer_citations->the_post();
								$closer_citation_custom_fields = array();
								if ( function_exists( 'slt_cf_all_field_values' ) ) {
									$closer_citation_custom_fields = slt_cf_all_field_values();
								}
								?>
					              <div class="reading-teaser swiper-slide citation maybe-more-row">
					                <div class="flex col-2">
					                  <div class="left-col">
					                    <h3><?php
											$citation_title = get_the_title();
											if ( in_array( $closer_citation_custom_fields['citation-format'], array( 'book' ) ) ) {
												$citation_title = '<i>' . $citation_title . '</i>';
											}
											echo $citation_title;
											?></h3>
					                  </div>
					                  <div class="right-col">
					                    <div class="author-details">
					                      <p><?php echo $closer_citation_custom_fields['citation-authors']; ?> (<?php echo $closer_citation_custom_fields['citation-date']; ?>)</p>
					                      <?php
												$closer_citation_source = $closer_citation_custom_fields['citation-publication-title'];
												if ( ! empty( $closer_citation_custom_fields['citation-url'] ) ) {
													$closer_citation_source = '<a href="' . esc_url( $closer_citation_custom_fields['citation-url'] ) . '">' . $closer_citation_source . '</a>';
												}
												if ( in_array( $closer_citation_custom_fields['citation-format'], array( 'chapter' ) ) ) {
													echo __( 'In' ) . ' ';
													if ( $closer_citation_custom_fields['citation-publication-editors'] ) {
														echo $closer_citation_custom_fields['citation-publication-editors'] . ' ';
														if ( strpos( $closer_citation_custom_fields['citation-publication-editors'], ',' ) !== false || strpos( $closer_citation_custom_fields['citation-publication-editors'], ' and ' ) !== false ) {
															echo '(eds.)';
														} else {
															echo '(ed.)';
														}
														echo ', ';
													}
													$closer_citation_source = '<i>' . $closer_citation_source . '</i>';
												}
												echo $closer_citation_source;
												if ( ! empty( $closer_citation_custom_fields['citation-publication-vol-issue'] ) ) {
													echo ', ' . $closer_citation_custom_fields['citation-publication-vol-issue'];
												}
												if ( ! empty( $closer_citation_custom_fields['citation-publication-pages'] ) ) {
													echo ', ' . $closer_citation_custom_fields['citation-publication-pages'];
												}
												?></p>
												<p><?php if ( in_array( $closer_citation_custom_fields['citation-format'], array( 'book', 'chapter', 'paper' ) ) && $closer_citation_custom_fields['citation-publisher'] ) { ?>
												<li class="publisher">
													<?php
													if ( $closer_citation_custom_fields['citation-place-of-publication'] ) {
														echo $closer_citation_custom_fields['citation-place-of-publication'] . ': ';
													}
													echo $closer_citation_custom_fields['citation-publisher'];
													?>
												</li>
											<?php } ?>
											<?php

									// Citation types
									$citation_types = pilau_objects_array_values( 'name', get_the_terms( get_the_ID(), 'citation_type' ) );
									if ( $citation_types ) {
										echo '<p class="themes"><span class="icon icon-tag';
										if ( count( $citation_types ) > 1 ) {
											echo 's';
										}
										echo '"></span> ' . implode( ', ', $citation_types ) . '</p>' . "\n";
									}

									?>
					                    </div>
					                  </div>
					                </div>
					              </div>

									<!-- <div<?php /* class="collapsible-text"*/ ?> data-label-open="<?php // _e( 'Expand citation' ); ?>" data-label-close="<?php // _e( 'Hide citation' ); ?>"> -->

								<?php $i++; ?>
							<?php } ?>

						</div>
					</div>
				</div>

				<?php }
				// Reset query
				wp_reset_postdata();
				?>
			</div>
		</section>

		<!-- Video podcast section???? -->

		<?php if ( ! empty( $pilau_custom_fields['videos-podcasts'] ) ) { ?>

			<section id="videos-podcasts" class="indent underline heading-left jump-section">
				<div class="wrapper limit-width">

					<h1 class="heading"><?php _e( 'Videos and podcasts' ); ?></h1>

					<div class="body">

						<?php

						$closer_videos_podcasts = explode( "\n", trim( $pilau_custom_fields['videos-podcasts'] ) );
						foreach ( $closer_videos_podcasts as $closer_videos_podcasts_item ) {
							$closer_video_podcast_parts = explode( '::', $closer_videos_podcasts_item );
							$closer_embed = wp_oembed_get( trim( $closer_video_podcast_parts[1] ) );
							if ( $closer_embed ) {
								echo '<h2>' . trim( $closer_video_podcast_parts[0] ) . '</h2>';
								echo '<div class="video-podcast">' . $closer_embed . '</div>';
							}
						}

						?>

					</div>

				</div>
			</section>

		<?php } ?>

		<!-- Relevant studies carousel -->

	    <section class="bordered" class="jump-section">
	      <div class="inner">
	        <h2 class="jump-title"><?php _e( 'Relevant studies' ); ?></h2>
	        <div class="carousel-relevant-studies carousel-relevant-studies-init">
	          <div class="carousel-prev swiper-button-prev"></div>
	          <div class="carousel-next swiper-button-next"></div>
	          <div class="swiper">
	            <div class="swiper-wrapper">
	            	<?php closer_list_studies_carousel( $pilau_custom_fields['evidence-studies'], true ); ?>
	            </div>
	          </div>
	        </div>
	      </div>
	    </section>
		
		<!-- Related content carousel -->


		<?php closer_related_content_section(); ?>

		<!-- External resources links -->

		<section id="external-resources" class="grey jump-section">
			<div class="inner">

				<h2 class="jump-title"><?php _e( 'External resources' ); ?></h2>
				
			        <div class="link-list">

					<ul>

						<?php

						if ( ! empty( $pilau_custom_fields['external-resources-featured-url'] ) && ! empty( $pilau_custom_fields['external-resources-featured-title'] ) ) {

							$classes = array( 'featured', 'clearfix' );
							if ( ! empty( $pilau_custom_fields['external-resources-featured-image'] ) ) {
								$classes[] = 'with-image';
							}

							echo '<li class="' . implode( ' ', $classes ) . '"><a href="' . esc_url( $pilau_custom_fields['external-resources-featured-url'] ) . '">' . $pilau_custom_fields['external-resources-featured-title'] . '</a></li>';

						}

						for ( $i = 1; $i <= 4; $i++ ) {

							if ( ! empty( $pilau_custom_fields['external-resources-url-' . $i] ) && ! empty( $pilau_custom_fields['external-resources-title-' . $i] ) ) {

								$classes = array();
								if ( ! ( $i % 2 ) ) {
									$classes[] = 'last';
								}

								echo '<li><a href="' . esc_url( $pilau_custom_fields['external-resources-url-' . $i] ) . '">' . $pilau_custom_fields['external-resources-title-' . $i] . '</a></li>';

							}

						}

						?>

					</ul>

				</div>

			</div>
		</section>

	     <!-- GRAPHS -->

		<?php if ( ! empty( $pilau_custom_fields['evidence-contextual-data'] ) ) { ?>

		<section id="related-contextual-data" class="indent underline heading-left jump-section">
			<div class="inner">

				<h2 class="jump-title"><?php _e( 'Relevant contextual data' ); ?></h2>

				<div class="graph-wrap">

					<a href="<?php echo get_the_permalink( $pilau_custom_fields['evidence-contextual-data'] ); ?>" class="block-wrapper">

						<?php $closer_cd_title = get_the_title( $pilau_custom_fields['evidence-contextual-data'] ); ?>

						<h3><?php echo $closer_cd_title; ?></h3>

						<img src="<?php echo pilau_get_featured_image_url( $pilau_custom_fields['evidence-contextual-data'], 'contextual-data' ); ?>" alt="<?php echo $closer_cd_title; ?> image">

					</a>

				</div>

			</div>
		</section>
		<?php } ?>

		<?php closer_related_evidence_section(); ?>

	</article>

</div>

<?php get_footer(); ?>