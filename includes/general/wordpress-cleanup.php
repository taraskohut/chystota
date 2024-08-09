<?php


// remove wordpress meta generator
remove_action('wp_head', 'wp_generator');
// remove wordpress rsd link
remove_action ('wp_head', 'rsd_link');
// remove Windows Live Writer Manifest Link
remove_action( 'wp_head', 'wlwmanifest_link');
// remove WordPress Page/Post Shortlinks
remove_action( 'wp_head', 'wp_shortlink_wp_head');
// Disable REST API link tag
remove_action('wp_head', 'rest_output_link_wp_head', 10);
// Disable oEmbed Discovery Links
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
// Disable REST API link in HTTP headers
remove_action('template_redirect', 'rest_output_link_header', 11, 0);



/**
 * Disable feed
 */

// Redirect to the homepage all users trying to access feeds.
function disable_feeds() {
	wp_redirect( home_url() );
	die;
}
// Disable global RSS, RDF & Atom feeds.
add_action( 'do_feed',      'disable_feeds', -1 );
add_action( 'do_feed_rdf',  'disable_feeds', -1 );
add_action( 'do_feed_rss',  'disable_feeds', -1 );
add_action( 'do_feed_rss2', 'disable_feeds', -1 );
add_action( 'do_feed_atom', 'disable_feeds', -1 );

// Disable comment feeds.
add_action( 'do_feed_rss2_comments', 'disable_feeds', -1 );
add_action( 'do_feed_atom_comments', 'disable_feeds', -1 );

// Prevent feed links from being inserted in the <head> of the page.
add_action( 'feed_links_show_posts_feed',    '__return_false', -1 );
add_action( 'feed_links_show_comments_feed', '__return_false', -1 );
remove_action( 'wp_head', 'feed_links',       2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );

/**
 * Disable the emoji's
 */
add_action( 'init', 'disable_emojis' );
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
}

// Filter function used to remove the tinymce emoji plugin.
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

// Remove emoji CDN hostname from DNS prefetching hints.
function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' == $relation_type ) {
		/** This filter is documented in wp-includes/formatting.php */
		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
		$urls = array_diff( $urls, array( $emoji_svg_url ) );
	}
	return $urls;
}

//Remove JQuery migrate
add_action( 'wp_default_scripts', 'mmc_remove_jquery_migrate' );
function mmc_remove_jquery_migrate( $scripts ) {
	if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
		$script = $scripts->registered['jquery'];
		if ( $script->deps ) { // Check whether the script has any dependencies
			$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
		}
	}
}

// disable embeds
add_action( 'wp_footer', 'my_deregister_scripts' );
function my_deregister_scripts(){
	wp_dequeue_script( 'wp-embed' );
}

//Remove Gutenberg Block Library CSS from loading on the frontend
add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100 );
function smartwp_remove_wp_block_library_css(){
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'wc-block-style' ); 
} 
