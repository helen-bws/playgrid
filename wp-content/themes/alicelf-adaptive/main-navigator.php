<?php

if ( !has_nav_menu('primary') )
{
	$menu_id = wp_create_nav_menu("Default Alicelf Menu");

	wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-title' =>  __('Home'),
			'menu-item-classes' => 'home',
			'menu-item-url' => home_url( '/' ),
			'menu-item-status' => 'publish'));
	wp_nav_menu(array(
			'show_home'      => true,
			'menu_class'     => 'nav navbar-nav default-alicelf-navbar',
			'container'      => false,
			'walker'         => new AliceNavigator()
	));
} else {
?>

<nav class="navbar navbar-default alicelf-primary-navbar" role="navigation">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<? if ( logo_link() ) { echo logo_link(); } else { ?>
				<hgroup>
					<h1 class='site-title'>
						<a href='<?= esc_url( home_url( '/' ) ); ?>'
						   title='<?= esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'
						   rel='home'><? bloginfo( 'name' ); ?>
						</a>
					</h1>
					<h2 id="site-description" class='site-description'><? bloginfo( 'description' ); ?></h2>
				</hgroup>
			<? } ?>
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-ex1-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="main-navigation collapse navbar-collapse" id="navbar-ex1-collapse">
			<?php
			$args = array(
					'show_home'      => true,
					'menu_class'     => 'nav navbar-nav',
					'theme_location' => 'primary',
					'container'      => false,
					'walker'         => new AliceNavigator()
			);
			wp_nav_menu( $args );
			?>
		</div>
		<!-- /.navbar-collapse -->
	</div>
</nav>
<?php } ?>