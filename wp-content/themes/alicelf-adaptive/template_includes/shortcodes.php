<?php

function custom_posts_shortcode( $atts ) {
	ob_start();

	$cat          = null;
	$type         = null;
	$limit        = null;
	$order        = null;
	$orderby      = null;
	$columns      = null;
	$ignoresticky = null;

	extract( shortcode_atts( array(
		'type'         => '',
		'order'        => 'DESC',
		'orderby'      => 'date',
		'limit'        => '-1',
		'exclude'      => '',
		'include'      => '',
		'columns'      => '',
		'ignoresticky' => '1',
		'cat'          => ''

	), $atts ) );

	global $wpdb, $post, $table_prefix;

	$excludearray = array();
	if ( ! empty( $exclude ) ) {
		$excludearray = explode( ',', $exclude );
	}
	$includearray = array();
	if ( ! empty( $include ) ) {
		$includearray = explode( ',', $include );
	}

	$default_columns = array( 1, 2, 3, 4, 6, 12 );
	$totcount        = 12;

	if ( ! in_array( $columns, $default_columns ) ) {
		$modulo = 4;
	} else {
		$modulo = 12 / $columns;
	}

	$paged = 1;
	if ( get_query_var( 'paged' ) ) {
		$paged = get_query_var( 'paged' );
	} elseif ( get_query_var( 'page' ) ) {
		$paged = get_query_var( 'page' );
	}

	$args = array(
		'post_type'           => $type,
		'order'               => $order,
		'orderby'             => $orderby,
		'post__not_in'        => $excludearray,
		// Exclude posts by id
		'post__in'            => $includearray,
		'posts_per_page'      => $limit,
		// Number of posts to display.
		'ignore_sticky_posts' => $ignoresticky,
		'paged'               => $paged,
		'cat'                 => $cat
	);
	// rewind
	$my_query = null;
	$my_query = new WP_Query( $args );

	if ( $my_query->have_posts() ) { ?>
		<div class="row posts-shortcode-<?= $type ?>">
			<?php while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
				<article id="post-<?php the_ID(); ?>" class="col-sm-<?= $modulo ?> shortcode-single-<?= $type ?>">
					<?php get_template_part( 'templates/tpl-shortcode-single' ); ?>
				</article>
			<?php endwhile; ?>
		</div>
	<?php } else {
		echo "no posts found on post type : " . $type;
	}
	return ob_get_clean();

}

/**
 * Simple Breadcrumbs. Call do_the_breadcrumb(); *(make sure wrap this <ol class="breadcrumb"></ol> if Bootstrap)
 */

function do_the_breadcrumb() {
	global $post;
	$posttype   = get_post_type( $post );
	$categories = get_the_category();
	$separator  = "";
	$output     = '';
	?>
	<ol class="breadcrumb">
		<? if ( is_home() ) { ?>
			<li class="active">Home: <?= bloginfo( 'name' ); ?></li>
		<? } else { ?>
			<li><a href="<?= site_url() ?>">Home</a></li>
			<? if ( is_single() ) { ?>
				<li><a href="<?= site_url() ?>?post_type=<?= $posttype ?>"><?= $posttype ?></a></li>
				<?php if ( $categories ) {
					foreach ( $categories as $category ) {
						$output .= '<li><a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">' . $category->cat_name . '</a></li>' . $separator;
					}
					echo trim( $output, $separator );
				} ?>
				<li class="active"><?= the_title() ?></li>
			<?
			}
			if ( is_page() ) {
				if ( is_page() && $post->post_parent > 0 ) {
					?>
					<?php foreach ( array_reverse( get_post_ancestors( $post ) ) as $ancestor ): ?>
						<li><a href="?p=<?= $ancestor ?>"><?= get_post( $ancestor )->post_title ?></a></li>
					<?php endforeach;
				} ?>
				<li class="active"><?= the_title() ?></li>
			<?
			}
			if ( is_category() ) {
				foreach ( $categories as $category ) {
					$output .= '<li><a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">' . $category->cat_name . '</a></li>' . $separator;
				}
				echo trim( $output, $separator );
			}
			if ( is_archive() ) {
				?>
				<li class="active">Archive</li>
			<?
			}
		} ?>
	</ol>
<?php
}

function footer_contacts() {
	global $alice;
	$output = null;
	$gl = $alice->globs();
	$gl['social_mail'];
	$output = "<ul id='footer-contact-list' class='list-inline list-unstyled'>";
	$output .="<li title='Email Us'><a href='".$gl['social_mail']."'><i class='fa fa-envelope'></i></a></li>";
//	$output .="<li title='Chat with us on Skype: ".$gl['social_skype']."'><i class='fa fa-skype'></i></li>";
	$output .="<li title='Visit the PlayGrid Google+ Community'><a target='_blank' href='".$gl['social_google_plus']."'><i class='fa fa-google-plus'></i></a></li>";
	$output .="<li title='LinkedIn'><a href='".$gl['social_linkedin']."'><i class='fa fa-linkedin'></i></a></li>";
	$output .="</ul>";

	return $output;
}

function social_list() {
	global $alice;
	$items = null;
	$result = null;
	foreach ( $alice->globs() as $k => $v) {
		if(strstr($k,'social')) {
			if(!empty($v)) {
				$fa = str_replace('social_','',$k);

				$items.="<li class='$k'><a href='$v'><i class='fa fa-".str_replace('_','-',$fa)."'></i></a></li>";
			}
		}
	}
	if($items) {
		return $result = "<ul class='social-list list-unstyled list-inline'>$items</ul>";
	}
}

function get_copy_echo() {
	global $alice;
	$result = null;
	foreach ( $alice->globs() as $k => $v) {
		if(strstr($k,'copyright')) {
			if(!empty($v))
			$result.="<i class='fa fa-copyright'></i> $v";
		}
	}
	if ($result)
		return $result;
}

function logo_link() {
	global $alice;
	$result = null;
	foreach ( $alice->globs() as $k => $v) {
		if(strstr($k,'logo')) {
			if(!empty($v))
				$result.="<div class='site-logo'><a href='".esc_url( home_url( '/' ) )."' title='".esc_attr( get_bloginfo( 'name', 'display' ) )."' class='$k' rel='home'><img src='".$v."' alt='".esc_attr( get_bloginfo( 'name', 'display' ) )."'></a></div>";
		}
	}
	if ($result)
		return $result;
}
function al_map(){
	global $alice;
	$map = null;
	$globhandler = $alice->globs();
	if($globhandler[ 'contact_map' ]){
		$map = '<div id="alice_map_canvas"></div>';
	}
	return $map;
}
// Template Todo: make fn for others fields
// Template Todo: figure out with google map

/**
 * Custom Post Types
 * [custom_posts type="recent_projects" order="asc" cat="46,71,52,64"]
 * [custom_posts type="recent_projects" limit="3" columns="2" cat="-46"]
 * [custom_posts type="testimonials" limit="3" columns="2" include="46,71,52,64"]
 * [custom_posts type="" columns="" limit="" include="" exclude="" order="" orderby="" ignoresticky=""]
 *
 * Breadcrumbs
 * [bcrumb]
 *
 * Social list
 * [social_list]
 *
 * Copyright
 * [get_copy]
 */

function release_alicelf_shortcodes() {
	add_shortcode( 'bcrumb', 'do_the_breadcrumb' );
	add_shortcode( 'custom_posts', 'custom_posts_shortcode' );
	add_shortcode( 'social_list', 'social_list' );
	add_shortcode( 'get_copy', 'get_copy_echo' );
	add_shortcode( 'do_logo_link', 'logo_link' );
	add_shortcode( 'show_map', 'al_map' );
}

add_action( 'init', 'release_alicelf_shortcodes' );