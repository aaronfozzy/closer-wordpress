<?php

/**
 * Contextual data
 *
 * @package	CLOSER-2022
 * @since	0.1
 */
global $pilau_custom_fields;

?>

<?php get_header(); ?>

<div id="content" role="main" class="purple">

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">

		<section class="page-title">
			<div class="inner">					
				<h1><?php the_title(); ?></h1>
			</div>
		</section>

			<section id="data-description">
				<div class="inner">
				<?php if ( ! empty( $pilau_custom_fields['data-intro'] ) && function_exists( 'slt_cf_simple_formatting' ) ) { ?>
					<div id="data-intro" class="post-content">
						<?php echo slt_cf_simple_formatting( $pilau_custom_fields['data-intro'] ); ?>
					</div>
				<?php } ?>

				<?php if ( ! empty( $pilau_custom_fields['data-graph'] ) ) { ?>
					<div id="data-graph">
						<?php echo $pilau_custom_fields['data-graph']; ?>
					</div>
				<?php } ?>

				<div class="post-content first-para-normal">
					<?php the_content(); ?>
				</div>

				<?php

				// Get downloads assigned to this data
				$closer_downloads = null;
				if ( function_exists( 'slt_cf_field_key' ) ) {
					$closer_downloads = get_posts( array(
						'post_type'			=> 'attachment',
						'posts_per_page'	=> -1,
						'meta_query'		=> array(
							array(
								'key'			=> slt_cf_field_key( 'contextual_data' ),
								'value'			=> get_queried_object_id()
							)
						),
						'orderby'			=> 'title',
						'order'				=> 'ASC'
					));
				}

				if ( $closer_downloads ) { ?>

					<aside id="data-downloads">

						<h2><?php _e( 'Data on' ); ?> <?php the_title(); ?></h2>

						<ul class="downloads">
							<?php foreach ( $closer_downloads as $closer_download ) { ?>
								<li><a href="<?php echo wp_get_attachment_url( $closer_download->ID ); ?>"><?php echo get_the_title( $closer_download ); ?></a> (<?php echo strtoupper( pilau_simple_file_type( get_post_mime_type( $closer_download ) ) ); ?>)</li>
							<?php } ?>
						</ul>

					</aside>

				<?php } ?>

			<?php if ( $pilau_custom_fields['key-dates'] ) { ?>

				<aside id="key-dates" class="green">

					<h1><?php _e( 'Key dates' ); ?></h1>

					<?php

					// Explode and cycle through key dates
					$closer_key_date_lines = explode( "\n", $pilau_custom_fields['key-dates'] );
					$closer_new_key_date = true;
					$closer_item_line = 1;
					echo '<ul class="key-dates">' . "\n";
					for ( $i = 0; $i < count( $closer_key_date_lines ); $i++ ) {
						$line = trim( $closer_key_date_lines[ $i ] );

						if ( ! $line ) {
							// Empty line, ignore
							continue;
						}

						// Start new item?
						if ( $closer_new_key_date ) {
							echo '<li>';
						}

						// What kind of detail?
						if ( $closer_item_line == 1 ) {

							// Date as heading
							echo '<h2>' . $line . '</h2>' . "\n";

						} else if ( $closer_item_line == 2 ) {

							// Title line
							echo '<h3>' . $line . '</h3>' . "\n";

						} else if ( $line[0] == '"' ) {

							// Link
							$closer_target = '_self';
							if ( in_array( substr( $line, -4 ), array( '.pdf', '.doc' ) ) ) {
								$closer_target = '_blank';
							}
							echo '<p>' . preg_replace( '%(")(.*?)(").*?((?:http|https)(?::\/{2}[\\w]+)(?:[\/|\\.]?)(?:[^\\s"]*))%', '<a href="$4" target="' . $closer_target . '">$2</a>', $line ) . '</p>' . "\n";

						} else {

							// Just a line of text
							echo '<p>' . $line . '</p>' . "\n";

						}

						if ( isset( $closer_key_date_lines[ $i + 1 ] ) && trim( $closer_key_date_lines[ $i + 1 ] ) ) {
							// Still more to come for this item...
							$closer_new_key_date = false;
							$closer_item_line++;
						} else {
							// End item
							echo '</li>';
							$closer_new_key_date = true;
							$closer_item_line = 1;
						}

					}
					echo '</ul>' . "\n";

					?>

				</aside>

			<?php } ?>
				</div>
			</section>


		<?php if ( $pilau_custom_fields['related-contextual-data'] ) { ?>

			<section id="related-contextual-data" class="indent underline heading-left<?php if ( count( $pilau_custom_fields['related-contextual-data'] ) > 3 ) echo ' more-at-bottom'; ?>">
				<div class="inner limit-width">

					<h1 class="heading">Related contextual data</h1>

					<div class="body stretch">

						<?php closer_content_items( $pilau_custom_fields['related-contextual-data'], 3, false, 'no', false, false, 'contextual_data', false, 1, true, true, false, false, false ); ?>

					</div>

				</div>
			</section>

		<?php } ?>


	</article>

</div>

<?php get_footer(); ?>