<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<div class="panel">
		<div class="panel-heading">
				<h3 class="entry-title"><?php the_title(); ?></h3>
		</div>

	    <div class="entry-content panel-body">
	        <?php the_content();  wp_link_pages();  edit_post_link(); the_tags(""," / "); ?>


	        <?php comments_template('/templates/tpl-comment.php'); ?>
	    </div>
	</div>
<?php endwhile; ?>