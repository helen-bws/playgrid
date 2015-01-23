<?php get_header(); ?>

	<!--INDEX LOOP-->
	<div class="main-loop">
		<div class="ghostly-wrap">
			<div class="row">
				<? $class_co_sm = is_active_sidebar( "base-widgeted-sidebar" ) ? 8 : 12; ?>
				<div class="col-sm-<?= $class_co_sm ?> front-loop">
					<?php get_template_part( 'templates/tpl-index-loop' ); ?>
				</div>
				<? main_sidebar_view( 4 ); paged_navigation(); ?>
			</div>
		</div>
	</div>

<?php get_footer(); ?>