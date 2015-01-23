<?php
/**
 * The loop that displays a page.
 */
?>
<?php get_header(); ?>
<!--DEFAULT PAGE LOOP START-->
<div id="page-<?php the_ID(); ?>"  <?php post_class('default-page-loop ghostly-wrap'); ?>>

	<div class="row">
		<? $class_co_sm = is_active_sidebar( "base-widgeted-sidebar" ) ? 8 : 12; ?>
		<div class="col-sm-<?= $class_co_sm ?>">
				<?php get_template_part('templates/tpl-page-loop'); ?>
		</div>
		<?php main_sidebar_view(4) ?>
	</div>

</div>
<!--DEFAULT PAGE LOOP  END-->
<?php get_footer(); ?>
