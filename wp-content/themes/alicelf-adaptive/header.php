<!doctype html>
<html <? language_attributes(); ?>>
<head>
	<meta charset="<? bloginfo( 'charset' ); ?>"/>
	<meta name="description" content="<? bloginfo( 'description' ); ?>"/>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
	<title><? wp_title() ?></title>
	<!--[if lt IE 9]>
	<script src="<? bloginfo('template_url'); ?>/ie/ie_fix_html5.js"></script>
	<![endif]-->
	<link rel="pingback" href="<? bloginfo( 'pingback_url' ); ?>"/>
	<? wp_head(); ?>
</head>

<body id="site-content" <? body_class(); ?>>
<?= al_map(); ?>
<div id="scroll-trigger-top"></div>
<div class="main-content-site">
	<div class="container">
		<header class="site-header">
			<div class="navigator-wrapper clearfix">
				<div class="ghostly-wrap">
					<? get_template_part( 'main-navigator' ); ?>
				</div>
			</div>
		</header>
