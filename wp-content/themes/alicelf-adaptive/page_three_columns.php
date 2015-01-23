<?php
/**
 Template name: Three Columns Page
 */
?>
<?php get_header(); ?>
<!--PAGE WITH 3 COLUMNS-->
<div id="three-cols-page-<?php the_ID();?>" <?php post_class('three-cols-page') ?>>
    <div class="ghostly-wrap">
			<div class="row">
				<? left_sidebar_view(); ?>
				<div class="col-sm-6 page-loop">
					<?php get_template_part('templates/tpl-page-loop'); ?>
				</div>
				<? main_sidebar_view(); ?>
			</div>
    </div>
</div>
<?php get_footer(); ?>
