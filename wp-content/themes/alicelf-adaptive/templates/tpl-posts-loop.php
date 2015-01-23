<?php
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$args = array( 'posts_per_page' => 0, 'paged' => $paged );
$query = query_posts( $args );

if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<article>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			</div>
			<div class="row">
				<div class="panel-body">
					<?php if( has_post_thumbnail()): ?>
					<div class="col-sm-4">
						<a class="post-img" href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
					</div>
					<div class="col-sm-8">
						<?php else: ?>
						<div class="col-sm-12">
							<?php endif; ?>
							<small><?php the_time('F jS, Y'); ?></small>
							<div class="entry"><?php the_excerpt(); ?></div>
						</div>
					</div>
			</div>
		</div>
	</article>

<?php endwhile; else:  include( get_404_template() ); ?>
<?php endif; wp_reset_postdata(); ?>
<nav class="main-nav-pagenavi">
    <?php if ( function_exists( 'wp_pagenavi' ))
    {
        wp_pagenavi();
    } else { ?>
	    <nav>
		    <ul id="nav-below" class="pager clearfix">
			    <li class="nav-previous"><?php previous_post_link('&larr; %link'); ?></li>
			    <li class="nav-next"><?php next_post_link('%link &rarr;'); ?></li>
		    </ul>
	    </nav>
    <?php } ?>
</nav>
