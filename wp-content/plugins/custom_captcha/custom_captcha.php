<?php
/*
Plugin Name: Custom Captcha
Plugin URI: http://vzazerkalie.com/portf/
Description: Captcha - Upload and Activate.
Author: Alicelf
Version: 0.1
Author URI: http://vzazerkalie.com/portf/
*/
add_action('wp_print_styles', 'secureimage_style');
function secureimage_style() {
	wp_enqueue_style('secure_image_few_styles', plugins_url('/captcha_style.css', __FILE__));
}

add_filter('comment_form_field_comment', 'captcha_init_incapsulator');

function captcha_init_incapsulator ($content) {
	echo $content;
	?>
	<div class="captha-holder">
		<img id="captcha" src="<?= plugin_dir_url(__FILE__).'securimage/securimage_show.php' ?>" alt="CAPTCHA Image" />
		<input type="text" name="captcha_code" size="10" maxlength="6" />
		<a class="btn btn-default" href="#" onclick="document.getElementById('captcha').src = '<?= plugin_dir_url(__FILE__).'securimage/securimage_show.php' ?>?sid=' + Math.random(); return false">Change Image</a>
	</div>
<?
}
add_filter( 'preprocess_comment', 'verify_comment_meta_data' );

function verify_comment_meta_data( $commentdata ) {
	include_once plugin_basename('/securimage/securimage.php', __FILE__);
	$securimage = new Securimage();
	if ( $securimage->check($_POST['captcha_code']) == false) {
		wp_die( __( 'Error: please fill correct the captcha.' ) );
//		return false;
	}
	return $commentdata;
}