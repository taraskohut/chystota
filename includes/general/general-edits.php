<?php


// change admin login page style
add_action( 'login_enqueue_scripts', 'my_login_logo_one' );
function my_login_logo_one() { 
	$logo_img = '';
	if( $custom_logo_id = get_theme_mod('custom_logo') ){
		$logo_img = wp_get_attachment_image_url( $custom_logo_id, 'full');
		echo '<style>body.login div#login h1 a {background-image: url('.$logo_img.'); background-size: contain; background-position: center; width: 200px; height: 100px; }</style>';
	} 
}
add_filter( 'login_headerurl', 'custom_loginlogo_url' );
function custom_loginlogo_url($url) {
	return get_home_url();
}

// remove toolbar items (examples)
// https://digwp.com/2016/06/remove-toolbar-items/
add_action('admin_bar_menu', 'shapeSpace_remove_toolbar_nodes', 999);
function shapeSpace_remove_toolbar_nodes($wp_admin_bar) {
	$wp_admin_bar->remove_node('wp-logo');
	$wp_admin_bar->remove_node('comments');
	$wp_admin_bar->remove_node('customize');
	$wp_admin_bar->remove_node('customize-background');
	$wp_admin_bar->remove_node('customize-header');
}