<?php
/*
* allow svg files
*/ 
add_filter('upload_mimes', 'cc_mime_types');
function cc_mime_types($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}

/*
* limit blog intro title
*/
function get_title( $count, $content ) {
	$title = get_the_title();
	if (!empty($content)) {
		$title = $content;
	}
	if (strlen($title)>$count){
		$title = strip_tags($title);
		$title = substr($title, 0, $count);
		$title = substr($title, 0, strripos($title, " "));
		$title = $title.'...';
	}
	return $title;
}

/*
* limit blog intro text
*/
function get_excerpt( $count, $content ) {
	if (!empty($content)) {
		$excerpt = $content;
	} else {
		$excerpt = get_the_excerpt();
		if (empty($excerpt)) {
			$excerpt = get_the_content();
		}
	}
	if (mb_strlen($excerpt) > $count) {
		$excerpt = strip_tags($excerpt);
		$excerpt = mb_substr($excerpt, 0, $count);
		$excerpt = mb_substr($excerpt, 0, strripos($excerpt, " "));
		$excerpt = $excerpt.'...';
	}
	return $excerpt;
}


/*
* Blog archive pagination
*/
function mmc_pagination_bar( $query_wp ) 
{
	$pages = $query_wp->max_num_pages;
	$big = 999999999; 
	if ($pages > 1)
	{
		$page_current = max(1, get_query_var('paged'));
		echo paginate_links(array(
			'base'			=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'		=> '?paged=%#%',
			'current'		=> $page_current,
			'total'			=> $pages,
			'type'			=> 'list',
			'prev_text'	=> '<',
			'next_text'	=> '>',
		));
	}
}


/*
* Add styles/classes to the "Styles" drop-down for TinyMCE Advanced
*	https://www.tiny.cloud/docs/configure/content-formatting/#formats
*	https://wordpress.stackexchange.com/questions/128931/tinymce-adding-css-to-format-dropdown
*/
add_filter( 'tiny_mce_before_init', 'fb_mce_before_init' );
function fb_mce_before_init( $settings ) {

	$style_formats = array(
		// array(
		// 	'title' => 'Title H1',
		// 	'selector' => '*',
		// 	'classes' => 'title-h1'
		// ),
	);
	$settings['style_formats'] = json_encode( $style_formats );
	return $settings;
}

// output website name
function the_site_name(){
	if (is_front_page()) :
		$html = sprintf( get_bloginfo( 'name' ).'<span class="part">'.get_bloginfo( 'description' ).'</span>'
	);
	else :
		$html = sprintf( '<a href="%1$s" rel="home" itemprop="url">'.get_bloginfo( 'name' ).'<span class="part">'.get_bloginfo( 'description' ).'</span>'.'</a>',
			esc_url( home_url( '/' ) )
		);
	endif;
	echo '<span class="site-title">'.$html.'</span>';
}

// disable cf7 autoformat
add_filter('wpcf7_autop_or_not', '__return_false');

function enqueue_styles_scripts() {
	// CSS
	wp_enqueue_style( 'mmc-style', get_template_directory_uri() . '/dist/css/style.min.css' );
	wp_enqueue_style( 'mmc-theme-style', get_stylesheet_uri() );
	// JS
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/dist/vendor/js/bootstrap.bundle.min.js', false, date('H:m:s'), true );
	wp_enqueue_script( 'mmc-script', get_template_directory_uri() . '/dist/js/custom.min.js', array('jquery'), date('H:m:s'), true );
	wp_localize_script('mmc-script', 'params', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' )
	) );
}