<?php
/**
 * The Template for displaying single posts.
 */
get_header(); ?>
<!--SINGLE ARTICLE-->
<article id="article-<? the_ID(); ?>" class="ghostly-wrap">
		<div class="row">
			<? $class_co_sm = is_active_sidebar( "base-widgeted-sidebar" ) ? 8 : 12; ?>
			<div <? post_class("col-sm-$class_co_sm single-loop"); ?>>
				<? if ( have_posts() ): while ( have_posts() ) : the_post(); ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="entry-title"><? the_title(); ?></h3>
						</div>
						<div class="panel-body">
							<div class="entry-content"><? /*GenerateMeta::fetchAllMeta('recent_projects');*/ the_content(); ?></div>
							<div class="label label-default pull-right"> Author: <? the_author(); ?></div>
							<? edit_post_link(); al_tags_template(); ?>
							<nav>
								<ul id="nav-below" class="pager clearfix">
									<li class="nav-previous"><? previous_post_link('&larr; %link'); ?></li>
									<li class="nav-next"><? next_post_link('%link &rarr;'); ?></li>
								</ul>
							</nav>
							<? comments_template('/templates/tpl-comment.php'); ?>
						</div>
					</div>
				<? endwhile; else: echo "No post matched your criteria"; endif; ?>
			</div>
			<? main_sidebar_view(4) ?>
		</div>
</article>
<? get_footer(); ?>