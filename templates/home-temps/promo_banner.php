<div class="grid promo_banner_main">
	<div class="promo_left">
		<?php
		// check if the repeater field has rows of data
		if( have_rows('promo_banner') ):
		 	// loop through the rows of data
		    while ( have_rows('promo_banner') ) : the_row(); ?>

		    <?php 
		    $radio = get_sub_field('image_position');


		    if ($radio == 'custom') {
		    	$x_val = get_sub_field('image_x_value');
		    	$y_val = get_sub_field('image_y_value');

		    	$img_pos = $y_val  . ' ' . $x_val;
		    } else {
		    	$img_pos = $radio;
		    }
		    

				?>
		        <a href="<?php the_sub_field('link'); ?>" class="promo_row" style="background-image: url(<?php the_sub_field('image'); ?>);background-position: <?php echo $img_pos; ?>">
						<h2><?php the_sub_field('title'); ?></h2>
						<p><?php the_sub_field('intro'); ?></p>
					</a>
		    <?php endwhile;
		endif;

		?>
		
	</div>
	<div class="promo_right">
		<div class="twitter_header">
			<div class="left"><span>Tweets</span> by <a href=" https://twitter.com/CLOSER_UK" target="_blank">@CLOSER_UK</a></div>
			<div class="right"><a href=" https://twitter.com/CLOSER_UK" target="_blank">Follow us on Twitter</a></div>
		</div>
		<a id="container" class="twitter-timeline" 
		data-height="93%" 
		data-theme="light" 
		data-link-color="#f32f66" 
		data-chrome="noheader, nofooter"
		href="https://twitter.com/CLOSER_UK?ref_src=twsrc%5Etfw"> tweets by @CLOSER_UK</a> 
		<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
	</div>
</div>