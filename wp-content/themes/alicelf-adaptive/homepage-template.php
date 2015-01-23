<?php
/**
 * Template name: Home Page
 */
?>
<? get_header(); ?>
<div id="page-<? the_ID(); ?>"  <? post_class( 'homepage-template-loop' ); ?>>
	<? if ( have_posts() ) {
		while ( have_posts() ) : the_post(); ?>
			<?php
			$feature_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) );
			$bgimg         = has_post_thumbnail() ? "url($feature_image[0])" : 'white';
			?>
			<div class="feature-home-container clearfix" style="background: <?= $bgimg ?>;">
				<div class="feature-wrap clearfix">
					<div class="feature-image-container">
						<h1 class="text-center"><?= $promo_line->renderMetadata() ?></h1>
						<h2 class="text-center"><strong><?= $promo_line_second->renderMetadata() ?></strong></h2>
						<p class="text-center bt-promo-link"><a class="btn" href="https://www.playgrid.com/register/"><?= $short_remark->renderMetadata() ?></a></p>
						<?
						global $image_second;
						if ( $image_second ) { $feaimg = $image_second->renderMetadata(); ?>
							<img class="img-responsive" id="homepage-console-image" src="<?= $feaimg[ 'url' ] ?>" alt="post_image"/>
						<? } ?>
					</div>
				</div>
			</div>
			<div id="mainhomepagecontent" class="clearfix"><? the_content(); ?></div>

		<? endwhile;
	} ?>
</div>
<? get_footer(); ?>
