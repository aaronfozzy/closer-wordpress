<?php

/**
 * ENews signup form
 *
 * @package	CfHE
 * @since	0.1
 */
global $closer_signup_form_count;
if ( ! isset( $closer_signup_form_count ) ){
	$closer_signup_form_count = 1;
}

?>

<!-- Begin MailChimp Signup Form -->
<div id="mc_embed_signup_<?php echo $closer_signup_form_count; ?>" class="mc_embed_signup">
	<h2><?php _e( 'eNews signup' ); ?></h2>
	<form action="//closer.us8.list-manage.com/subscribe/post?u=591bdf6a30b8a868e16e39491&amp;id=a33e129cf3" method="post" id="mc-embedded-subscribe-form-<?php echo $closer_signup_form_count; ?>" class="validate mc-signup-form clearfix" target="_blank" novalidate>
		<div class="field fname">
			<label for="mce-FNAME-<?php echo $closer_signup_form_count; ?>"><?php _e( 'First name' ); ?></label>
			<input type="text" value="" name="FNAME" class="input" id="mce-FNAME-<?php echo $closer_signup_form_count; ?>">
		</div>
		<div class="field lname">
			<label for="mce-LNAME-<?php echo $closer_signup_form_count; ?>"><?php _e( 'Last name' ); ?></label>
			<input type="text" value="" name="LNAME" class="input" id="mce-LNAME-<?php echo $closer_signup_form_count; ?>">
		</div>
		<div class="field email">
			<label for="mce-EMAIL-<?php echo $closer_signup_form_count; ?>"><?php _e( 'Email' ); ?></label>
			<input type="email" value="" name="EMAIL" class="required email input" id="mce-EMAIL-<?php echo $closer_signup_form_count; ?>">
		</div>
		<div class="buttons">
			<input type="submit" value="<?php _e( 'Sign up' ); ?>" name="subscribe" id="mc-embedded-subscribe-<?php echo $closer_signup_form_count; ?>" class="form-button button">
		</div>
		<div id="mce-responses" class="clear">
			<div class="response" id="mce-error-response-<?php echo $closer_signup_form_count; ?>" style="display:none"></div>
			<div class="response" id="mce-success-response-<?php echo $closer_signup_form_count; ?>" style="display:none"></div>
		</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
		<div style="position: absolute; left: -5000px;"><input type="text" name="b_591bdf6a30b8a868e16e39491_a33e129cf3" tabindex="-1" value=""></div>
	</form>
</div>
<!--End mc_embed_signup-->

<?php

$closer_signup_form_count++;