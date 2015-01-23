<?php
/*
Plugin Name: Alicelf Styles
Plugin URI: http://vzazerkalie.com/portf/
Description: Alicelf Admin Dashboard - Upload and Activate.
Author: Alicelf
Version: 0.1
Author URI: http://vzazerkalie.com/portf/
*/

/**
 * Admin/Frontend
 * Google Font Roboto
 * Font Awesome
 */
function load_open_roboto(){
	wp_register_style('RobotoGoogleFont', 'http://fonts.googleapis.com/css?family=Roboto:400italic,400,500italic,500');
	wp_enqueue_style( 'RobotoGoogleFont');
	wp_register_style('OpensansFonts', 'http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800');
	wp_enqueue_style( 'OpensansFonts');
	wp_enqueue_style( 'alice_font_avesome_admin', plugins_url( '/font-awesome/css/font-awesome.min.css', __FILE__ ));
}
add_action('wp_print_styles', 'load_open_roboto');
add_action('admin_head', 'load_open_roboto');

/**
 * Admin:
 * Font Awesome
 * Styles
 * Script
 */
function alicelf_admin_styles(){
	wp_enqueue_style( 'alice_font_avesome_admin', plugins_url( '/font-awesome/css/font-awesome.min.css', __FILE__ ));
	wp_enqueue_style( 'alice_style_admin', plugins_url( 'alicelf_admin_styles.css', __FILE__ ));
	wp_enqueue_script( 'alice_script_admin', plugins_url( '/script.js', __FILE__ ));
}
add_action( 'admin_head', 'alicelf_admin_styles' );

// add more buttons to the html editor
function appthemes_add_quicktags_alicelf(){
	if (wp_script_is('quicktags')){
		?>
		<script type="text/javascript">
			QTags.addButton( 'eg_php', 'Close php brackets', '[php]', '[/php]', 'q', 'Preformatted text tag', 111 );
		</script>
	<?php
	}
}
add_action( 'admin_print_footer_scripts', 'appthemes_add_quicktags_alicelf' );