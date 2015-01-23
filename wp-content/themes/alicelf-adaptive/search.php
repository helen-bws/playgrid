<?php
/*
 *Template Name: Search Page
 */
get_header(); ?>
<? if ( have_posts() ) : ?>
	<div class="ghostly-wrap">
	<!--	Search Page -->
<? $search_count = 0;
	$search = new WP_Query("s=$s & showposts=-1");

	if($search->have_posts())
		while($search->have_posts()) { $search->the_post(); $search_count++; } ?>
	<div <? post_class('row') ?>>
		<div class="col-sm-8 search-loop">

			<h5 class="page-title"> Search result for: <?= get_search_query();?></h5>
			<p>Num of matched results: <?= $search_count; ?></p>
			<? while ( have_posts() ) : the_post(); ?>
				<article class="panel panel-primary">
					<div class="panel-heading">
						<h3><a href="<? the_permalink(); ?>"><? the_title(); ?></a></h3>
					</div>
					<div class="panel-body">
							<? al_thumb();
							$class_co_sm = is_active_sidebar( "base-widgeted-sidebar" ) ? 8 : 12; ?>
						<div class="col-sm-<?= $class_co_sm ?>">
							<small><? the_time('F jS, Y'); ?></small>
							<div class="entry"><? content_cutter(get_the_content(), 0, 5); ?>
								<a href="<? the_permalink() ?>"> ... Get more </a>
							</div>
						</div>
					</div>
				</article>
		</div>
		<? endwhile; main_sidebar_view(4); paged_navigation(); ?>
	</div>
</div>
<? else: include( get_404_template() ); endif; wp_reset_postdata(); get_footer(); ?>