<?php

/* Contact details */
global $pilau_site_settings;

?>

<div class="contact-details">

	<address>
		<?php echo nl2br( trim( $pilau_site_settings['address'] ) ); ?>
	</address>

	<div class="tel-email-map">

		<ul>
			<li class="tel"><span class="label"><?php _e( 'Tel' ); ?></span> <?php echo $pilau_site_settings['tel']; ?></li>
			<li class="email"><span class="label"><?php _e( 'Email' ); ?></span> <?php echo pilau_obfuscate_email( $pilau_site_settings['email'], false ); ?></li>
		</ul>

		<p class="map-link"><a href="<?php echo pilau_google_map_link( str_replace( "\n", ' ', $pilau_site_settings['address'] ) ); ?>" target="_blank" class="no-icon"><span class="icon icon-link-ext-alt"></span> <?php _e( 'Map' ); ?></a></p>

	</div>

</div>
