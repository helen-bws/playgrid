<?php
//Template Todo: Build Class and configure it
//Template Todo: add new types for fields and test it
//Template Todo: Make additional field creation page

add_action( 'admin_menu', 'alice_theme_subpage' );

function alice_theme_subpage() {
	add_submenu_page( 'alicelf-theme', 'create tables', 'Create table', 'edit_theme_options', 'create-table-slug', 'submenu_init' );
}

function submenu_init() {
	echo "This is submenu page";
}