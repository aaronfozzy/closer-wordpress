<?php

/**
 * Theme footer
 *
 * @package	CLOSER-2022
 * @since	0.1
 */
//global $pilau_site_settings;

?>

<?php /*
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() . '/js/calc-polyfill.js'; ?>?v=0.0.1"></script>
<![endif]-->
*/ ?>

<?php wp_footer(); ?>

	<footer class="footer-main">
		<div class="inner-footer">
			<div class="footer-top">
				<div class="footer-left">
					<div class="logos">
						<a href="/"><img class="logo-closer" src="<?php echo get_stylesheet_directory_uri(); ?>/www/images/closer-logo.svg" alt="Closer"></a>
						<a href="http://www.esrc.ac.uk/" target="_blank"><img class="logo-esrc" src="<?php echo get_stylesheet_directory_uri(); ?>/www/images/esrc-logo.svg" alt="UKRI - Economic and Social Research Council"></a>
					</div>
					<nav class="nf_footer_menu footer-nav">
						<?php
						$menu_args = array(
							'theme_location' => 'footer-menu',
							'container'       => FALSE,
					        'container_id'    => FALSE,
					        'menu_class'      => 'footer_menu',
					        'menu_id'         => FALSE,
					        
						);
						//print_r(wp_nav_menu($menu_args));
							echo wp_nav_menu($menu_args);
							
						?>
					</nav>
					<!-- <div class="back-to-top">
						<a href="#main"><span class="icon icon-angle-up"></span> 
							<?php _e( 'Back to top' ); ?>
						</a>
					</div> -->
				</div>
				<div class="footer-col-2">
					<div class="footer-address">
						<?php the_field('footer_address', 'option'); ?>
					</div>
				</div>
				<div class="footer-col-2">
					<div class="footer-contact">
						<p><strong>TEL:</strong> <a href="tel:<?php the_field('tel', 'option'); ?>"><?php the_field('tel', 'option'); ?></a></p>
						<p><strong>EMAIL:</strong> <a href="mailto:<?php the_field('email', 'option'); ?>"><?php the_field('email', 'option'); ?></a></p>
					</div>
				</div>
			</div>
			<div class="footer-bottom">
				<div class="links">
					<ul>
					<?php if(get_field('footer_external_link_1_text', 'option')){ ?>
					<li class="actioned">
						<a href="<?php the_field('footer_external_link_1_url', 'option'); ?>" target="_blank" class="labelled discovery"><?php the_field('footer_external_link_1_text', 'option'); ?></a>
					</li>
					<?php } ?>
					<?php if(get_field('footer_external_link_2_text', 'option')){ ?>
					<li><a href="<?php the_field('footer_external_link_2_url', 'option'); ?>" target="_blank" class="labelled learning"><?php the_field('footer_external_link_2_text', 'option'); ?></a></li>
					<?php } ?>
					<?php if(get_field('footer_external_link_3_text', 'option')){ ?>
					<li><a href="<?php the_field('footer_external_link_3_url', 'option'); ?>" target="_blank" class="labelled covid"><?php the_field('footer_external_link_3_text', 'option'); ?></a></li>
					<?php } ?>
					<?php if(get_field('footer_external_link_4_text', 'option')){ ?>
					<li><a href="<?php the_field('footer_external_link_4_url', 'option'); ?>" target="_blank" class="labelled training"><?php the_field('footer_external_link_4_text', 'option'); ?></a></li>
					<?php } ?>
					</ul>
				</div>
				<p><strong><?php the_field('copy_text', 'option'); ?></strong></p>
			</div>
		</div>
	</footer>

</body>
</html>