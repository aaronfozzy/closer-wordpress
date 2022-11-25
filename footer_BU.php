<?php

/**
 * Theme footer
 *
 * @package	CLOSER-2022
 * @since	0.1
 */
global $pilau_site_settings;

?>

<footer id="footer">

	<div id="footer-funders" class="indent">

		<h2 class="assistive-text"><?php _e( 'Funded by' ); ?></h2>

		<ul>
			<li id="funder-esrc" class="img-rep"><a href="http://www.esrc.ac.uk/" target="_blank" title="<?php _e( 'Visit the Economic and Social Research Council website' ); ?>"><?php _e( 'Economic and Social Research Council' ); ?></a></li>
			<li id="funder-mrc" class="img-rep"><a href="http://www.mrc.ac.uk/" target="_blank" title="<?php _e( 'Visit the Medical Research Council website' ); ?>"><?php _e( 'Medical Research Council' ); ?></a></li>
		</ul>

	</div>

	<div id="footer-main" role="contentinfo">

		<div class="contact-box">
			<div class="wrapper clearfix limit-width">

				<?php include( 'inc/dsp_contact-details.php' ); ?>

				<div class="contact-signup">

					<?php include( 'inc/dsp_enews-signup.php' ); ?>

				</div>

			</div>
		</div>

		<div class="wrapper clearfix">

			<ul class="footer-copyright">
				<li class="copyright">&copy; <?php echo date( 'Y' ); ?> CLOSER. <?php _e( 'All rights reserved.' ); ?></li>
				<li class="back-to-top"><a href="#main"><span class="icon icon-angle-up"></span> <?php _e( 'Back to top' ); ?></a></li>
			</ul>

		</div>

	</div>

</footer><!-- #footer -->

</div><!-- #main -->

<?php /*
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() . '/js/calc-polyfill.js'; ?>?v=0.0.1"></script>
<![endif]-->
*/ ?>

<?php wp_footer(); ?>

</body>
</html>