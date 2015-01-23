<?php
session_start();
/**
 * Walkers
 */
foreach ( glob( get_template_directory() . "/walkers/*.php" ) as $filename ) {
	require_once( $filename );
}
/**
 * shortcodes and maybe something else
 */
foreach ( glob( get_template_directory() . "/template_includes/*.php" ) as $filename ) {
	require_once( $filename );
}
/**
 * Theme customizer
 */
foreach ( glob( get_template_directory() . "/alicelf_theme_setup/Alice*" ) as $filename ) {
	require_once( $filename );
}

add_theme_support( 'post-thumbnails' );

function al_thumb( $class = null ) {
	//sun-gradient
	//blade-shine
	//Default: none effect
	if ( has_post_thumbnail() ):
		switch ( $class ) {
			case 'sun-gradient': ?>
				<figure class="col-sm-4 generated-figure <?= $class ?>">
					<a class="post-img" href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail(); ?>
						<figcaption>
							<h3><?= content_cutter( get_the_title(), 0, 3 ); ?></h3>
						</figcaption>
					</a>
				</figure>
				<? ;
				break;
			case 'blade-shine': ?>
				<figure class="col-sm-4 generated-figure <?= $class ?>">
					<a class="post-img" href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail(); ?>
						<figcaption>
							<p><?= content_cutter( get_the_title(), 0, 3 ); ?></p>

							<p><?= content_cutter( get_the_content(), 0, 10 ); ?></p>
						</figcaption>
					</a>
				</figure>
				<? ;
				break;
			default: ?>
				<figure class="col-sm-4 generated-figure">
					<a class="post-img" href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail(); ?>
					</a>
				</figure>
				<?;
		}
		?>
	<?php endif;
}

add_filter( 'widget_text', 'do_shortcode' );
add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link', 'audio' ) );

function alice_remove_post_type_support() {
	remove_post_type_support( 'post', 'post-formats' );
}

add_action( 'admin_head', 'alice_remove_post_type_support', 10 );
/**
 * @param $title
 *
 * @return string
 */
function title_addon( $title ) {
	( is_home() || is_front_page() ) ?
		$title = bloginfo( 'name' ) . " | " . get_bloginfo( 'description', 'display' ) : $title = the_title() . " | " . get_bloginfo( 'name' );
	if ( is_404() ) {
		$title = bloginfo( 'name' ) . ' | .404!';
	}

	return $title;
}

add_filter( 'wp_title', 'title_addon' );

register_sidebar( array(
		'name'          => 'Left sidebar',
		'id'            => 'left-widgeted-sidebar',
		'before_widget' => '<div id="%1$s" class="widget list-group %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<a class="wdg_title list-group-item active"><h4>',
		'after_title'   => '</h4></a>'
	)
);

register_sidebar( array(
		'name'          => 'Right sidebar',
		'id'            => 'base-widgeted-sidebar',
		'before_widget' => '<div id="%1$s" class="widget list-group %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<a class="wdg_title list-group-item active"><h4>',
		'after_title'   => '</h4></a>'
	)
);
/**
 * Register custom menus
 */
register_nav_menus( array(
	'primary'            => 'Primary Navigation',
	'footer_custom_menu' => 'Footer menu',
) );

function left_sidebar_view( $col_sm = 3 ) {
	if ( is_active_sidebar( 'left-widgeted-sidebar' ) ) { ?>
		<aside class="col-sm-<?= $col_sm ?> left-sidebar">
			<? dynamic_sidebar( 'left-widgeted-sidebar' ); ?>
		</aside>
	<? }
}

function main_sidebar_view( $col_sm = 3 ) {
	if ( is_active_sidebar( 'base-widgeted-sidebar' ) ) { ?>
		<aside class="col-sm-<?= $col_sm ?> main-sidebar">
			<? dynamic_sidebar( 'base-widgeted-sidebar' ); ?>
		</aside>
	<? }
}

/**
 * Register Site styles and Scripts
 */
function invoke_scripts() {

	$path = get_template_directory_uri() . '/bootstrap_sass/javascripts/bootstrap';
// name-script, src, script dependency, version, in_footer(true/false)
	wp_enqueue_script( 'jquery-theme-script', get_template_directory_uri() . '/js/jquery2.1.js', array(), false, true );

	wp_enqueue_style( 'bootstrap-base-styles', get_template_directory_uri() . '/bootstrap_sass/stylesheets/bootstrap.css' );
	wp_enqueue_style( 'template-base-styles', get_bloginfo( 'stylesheet_url' ) );

//	wp_enqueue_script( 'affix-script', $path . '/affix.js', array(), false, true );
	wp_enqueue_script( 'alert-script', $path . '/alert.js', array(), false, true );
//	wp_enqueue_script( 'button-script', $path . '/button.js', array(), false, true );
//	wp_enqueue_script( 'carousel-script', $path . '/carousel.js', array(), false, true );
	wp_enqueue_script( 'collapse-script', $path . '/collapse.js', array(), false, true );
//	wp_enqueue_script( 'dropdown-script', $path . '/dropdown.js', array(), false, true );
//	wp_enqueue_script( 'tab-script', $path . '/tab.js', array(), false, true );
	wp_enqueue_script( 'transition-script', $path . '/transition.js', array(), false, true );
//	wp_enqueue_script( 'scrollspy-script', $path . '/scrollspy.js', array(), false, true );
//	wp_enqueue_script( 'modal-script', $path . '/modal.js', array(), false, true );
	wp_enqueue_script( 'tooltip-script', $path . '/tooltip.js', array(), false, true );
	wp_enqueue_script( 'popover-script', $path . '/popover.js', array(), false, true );

	wp_enqueue_script( 'smooth-scroll', get_template_directory_uri() . '/js/smooth_scroll.js', array(), false, true );
	wp_enqueue_script( 'site-theme-script', get_template_directory_uri() . '/js/script.js', array(), false, true );
}

add_action( 'wp_enqueue_scripts', 'invoke_scripts' );

/**
 * @param $string
 * @param null $num_start
 * @param null $num_end
 *
 * @return string
 */
function content_cutter( $string, $num_start = null, $num_end = null ) {
	settype( $string, "string" );
	if ( is_int( $num_start ) && is_int( $num_end ) ) {
		$array_of_strings = explode( " ", $string );
		$sliced           = array_slice( $array_of_strings, $num_start, $num_end );
		$new_string       = implode( " ", $sliced );

		return $new_string;
	}

	return "num_start or num_end must be Integer";
}

/**
 * Add "Read More" custom text for Recent Projects (if use the_excerpt())
 */
function new_excerpt_more( $more ) {
	global $post;

	return ' ... <a href="' . get_permalink( $post->ID ) . '">Read more <i class="glyphicon glyphicon-arrow-right"></i></a>';
}

add_filter( 'excerpt_more', 'new_excerpt_more' );

/**
 * Get Navigation
 */
function paged_navigation() {
	if ( function_exists( 'wp_pagenavi' ) ) {
		wp_pagenavi();
	} else {
		echo "<div class='nav-previous'>" . previous_post_link( '&laquo; %link' ) . "</div><div class='nav-next'>" . next_post_link( '%link &raquo;' ) . "</div>";
	}
}

function al_tags_template() {
	if ( get_the_tags() ) { ?>
		<div class="alert alert-info tags-info">Tags:
			<? the_tags( "<i class='glyphicon glyphicon-tag'></i>",
				"<i class='glyphicon glyphicon-tag'></i>", "" ); ?>
		</div>
	<? }
}

/**
 * Restict duplicate images with different sizes
 *
 * @param $sizes
 */
function add_image_insert_override( $sizes ) {
	unset( $sizes[ 'thumbnail' ] );
	unset( $sizes[ 'medium' ] );
	unset( $sizes[ 'large' ] );
}

add_filter( 'intermediate_image_sizes_advanced', 'add_image_insert_override' );

add_filter( 'wp_revisions_to_keep', 'custom_revisions_number', 10, 2 );
function custom_revisions_number( $num, $post ) {
	$num = 3; // <-- change this accordingly.
	return $num;
}

/**
 * Custom Search form
 */
function al_search_form( $echo = true ) {
	do_action( 'pre_get_search_form' );

	$format = current_theme_supports( 'html5', 'search-form' ) ? 'html5' : 'xhtml';
	$format = apply_filters( 'search_form_format', $format );

	$search_form_template = locate_template( 'searchform.php' );
	if ( '' != $search_form_template ) {
		ob_start();
		require( $search_form_template );
		$form = ob_get_clean();
	} else {
		$form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
				<div class="input-group">
				<span class="input-group-addon"><label>' . _x( 'Search for:', 'label' ) . '</label></span>
				<input type="search" class="form-control" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder' ) . '" value="' . get_search_query() . '" name="s" title="' . esc_attr_x( 'Search for:', 'label' ) . '" />
				<span class="input-group-addon"><input type="submit" value="' . esc_attr_x( 'Search', 'submit button' ) . '" /></span>
			</div></form>';
	}
	$result = apply_filters( 'get_search_form', $form );

	if ( null === $result ) {
		$result = $form;
	}

	if ( $echo ) {
		echo $result;
	} else {
		return $result;
	}
}

/**
 * Creating custom posts by plugin
 */
$labels = array(
	'name'               => __( 'Recent Projects' ),
	'singular_name'      => __( 'Project' ),
	'add_new'            => __( 'Add new' ),
	'add_new_item'       => 'Add new recent project',
	'edit_item'          => 'Edit recent project',
	'new_item'           => 'New recent project',
	'view_item'          => 'View recent project',
	'search_items'       => 'Search recent project',
	'not_found'          => 'recent project not found',
	'not_found_in_trash' => 'Empty basket recent projects',
	'parent_item_colon'  => '',
	'menu_name'          => 'Projects'

);
$args   = array(
	'labels'             => $labels,
	'public'             => true,
	'publicly_queryable' => true,
	'show_ui'            => true,
	'show_in_menu'       => true,
	'query_var'          => true,
	'rewrite'            => array( 'slug' => 'recent_projects' ),
	'capability_type'    => 'post',
	'has_archive'        => true,
	'hierarchical'       => true,
	'menu_position'      => null,
	'taxonomies'         => array( 'category', 'post_tag' ),
	'supports'           => array(
		'title',
		'editor',
		'author',
		'thumbnail',
		'excerpt',
		'comments',
		'custom-fields',
		'page-attributes'
	)
);
if ( class_exists( 'GenerateCPosts' ) ) {
	$new_post = new GenerateCPosts( 'recent_projects', $labels, $args );
	$new_post->run();
//	$new_post->addContextualHelp('Some Contextual text');
//	$new_post->postFormatSupport();
}

$alice = AliceJwrap::getInstance();
// [0](_color = colorpicker, _upload = file) , html_type
// [1](int varchar text) , database_field_type
// underscore break word strict!
$args = array(
	array( 'logo_upload', 'varchar' ),
	array( 'favicon_upload', 'varchar' ),
	array( 'main_width', 'int' ),

	array( 'font_color', 'varchar' ),
	array( 'headers_color', 'varchar' ),

	array( 'company_copyright', 'text' ),
	array( 'contact_phone', 'varchar' ),
	array( 'contact_map', 'text' ),
	array( 'navbar_options', 'varchar' ),
	array( 'some_options', 'varchar' ),

	array( 'social_mail', 'varchar' ),
	array( 'social_skype', 'varchar' ),
	array( 'social_facebook', 'varchar' ),
	array( 'social_twitter', 'varchar' ),
	array( 'social_youtube', 'varchar' ),
	array( 'social_linkedin', 'varchar' ),
	array( 'social_instagram', 'varchar' ),
	array( 'social_pinterest', 'varchar' ),
	array( 'social_google_plus', 'varchar' ),
	array( 'social_vimeo', 'varchar' ),
	array( 'social_vk', 'varchar' ),
);
$alice->createTable( 'alice_cusomizer', $args );
$alice->run( 'Alice Theme Customization', 'Alice Theme', 'alicelf_customizer' );

$sections = array(
	'Main Panel'   => array(
		'logo_upload', 'favicon_upload', 'main_width',

		'navbar_options' => array(
			'checkbox',
			'Stick Navbar',
//			'Hide Navbar'
		),
//		'some_options' => array(
//			'select',
//			'Option one',
//			'Option two',
//		),
	),
	'Color Scheme' => array( 'font_color', 'headers_color' ),
	'Company Info' => array( 'contact_map', 'company_copyright', 'contact_phone' ),
	'Socials'      => array(
//		'social_facebook',
//		'social_twitter',
//		'social_youtube',
		'social_mail',
		'social_skype',
		'social_google_plus',
		'social_linkedin',
//		'social_vimeo',
//		'social_vk'
//		'social_pinterest',
//		'social_instagram',
	),
//	'Other Options' => array(
//		'another_some_options' => array(
//			'radio',
//			'Radio option one',
//			'Radio option two',
//			'Radio option three',
//		),
//	),
);
$alice->createSections( $sections );
//global $alice
//var_dump($alice->globs()['company_copyright']);

if (class_exists('GenerateMeta')) {
	//id , title, post_type
	$promo_line = new GenerateMeta( 'promo_line', 'Promo Line', 'page' );
	$promo_line->run( null, 'side', 'high' );
	$promo_line->saveMetadata();
//echo $promo_line->renderMetadata();

	$promo_line_second = new GenerateMeta( 'promo_line_second', 'Second Promo Line', 'page' );
	$promo_line_second->run( null, 'side', 'high' );
	$promo_line_second->saveMetadata();
//echo $promo_line_second->renderMetadata();

	$short_remark = new GenerateMeta( 'short_remark', 'Short remark', 'page' );
//input_type('text' by default), placement('normal' by default), priority('default' by default)
	$short_remark->run( null, 'side', 'high' );
	$short_remark->saveMetadata();
//echo $short_remark->renderMetadata();


// File
	$image_second = new GenerateMeta( 'second_featured_image', 'Second Image', 'page' );
	$image_second->run( 'file', 'side', 'high' );
//saveFileData() for Files
	$image_second->saveFileData();
//echo $image_second->renderMetadata()['url'];
}