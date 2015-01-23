<?php
/**
 Template name: Three Columns blog
 */
?>
<?php get_header(); ?>
<!--THREE COLUMNS BLOG-->
<div id="three-cols-page-<?php the_ID();?>" <?php post_class('three-cols-blog') ?>>
    <div class="ghostly-wrap">
			<div class="row">
				<? left_sidebar_view(); ?>
				<div class="col-sm-6 page-loop">
					<h3 class="entry-title text-center"><?php the_title(); ?></h3>
					<?php get_template_part('templates/tpl-posts-loop'); ?>
				</div>
				<? main_sidebar_view(); ?>
			</div>
    </div>
</div>
<?php get_footer(); ?>
