<? get_header(); ?>
<!--ARCHIVE LOOP-->
    <div class="ghostly-wrap">
			<div class="row">
				<? left_sidebar_view(); ?>

				<div class="col-sm-6 archive-loop">
					<? if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

						<article class="row">
							<div class="col-sm-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4><a href="<? the_permalink(); ?>"><? the_title(); ?></a></h4>
									</div>
									<div class="panel-body">
										<small><? the_time('F jS, Y'); ?></small>
										<div class="entry"><? the_excerpt(); ?></div>
									</div>
								</div>
							</div>
						</article>

					<? endwhile; else: ?><p>Sorry, no posts matched your criteria.</p>
					<? endif; paged_navigation(); ?>
				</div>
				<? main_sidebar_view(); ?>
			</div>
    </div>
<? get_footer(); ?>