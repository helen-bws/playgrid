<footer id="footer" class="navbar navbar-default">
	<div class="ghostly-wrap">
		<?php
		if ( has_nav_menu('footer_custom_menu') ) {
			$args = array(
					'show_home'      => true,
					'menu_class'     => 'nav navbar-nav',
					'theme_location' => 'footer_custom_menu',
					'container'      => false,
					'walker'         => new AliceNavigator()
			);
			wp_nav_menu( $args );
		}

		?>
	</div>
	<div class="ghostly-wrap">
		<div class="social_block"><?= footer_contacts() ?></div>
		<div class="copywright">
			<p><?= get_copy_echo() ?> | <a href="?p=65">Privacy</a></p>
		</div>
	</div>
	<a href="#scroll-trigger-top" id="footer-angle-arrow" class="hidden">
		<i class="glyphicon glyphicon-arrow-up"></i>
	</a>
</footer>
</div><!--END CONTAINER-->
</div><!--END MAIN-CONTENT-SITE-->
<?php wp_footer(); ?>
</body>
</html>