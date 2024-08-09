<?php

// acf options page
if (function_exists('acf_add_options_page')) {
	$parent = acf_add_options_page(array(
			'page_title'    => 'Theme General Settings',
			'menu_title'    => 'Чистота',
			'menu_slug'     => 'theme-general-settings',
			'icon_url'      => 'dashicons-admin-home',
			'redirect'      => true
	));

	acf_add_options_sub_page(array(
			'page_title'    => 'General settings',
			'menu_title'    => 'General',
			'parent_slug'   => $parent['menu_slug'],
	));

	$parent_bloks = acf_add_options_page(array(
		'page_title'    => 'Blocks Settings',
		'menu_title'    => 'Блоки',
		'menu_slug'     => 'blocks-settings',
		'icon_url'      => 'dashicons-table-row-after',
		'redirect'      => true
	));

	acf_add_options_sub_page(array(
			'page_title'    => 'General settings',
			'menu_title'    => 'General',
			'parent_slug'   => $parent_bloks['menu_slug'],
	));
}

global $global_options;

if ( function_exists('get_fields') ) {
	$global_options = get_fields('theme-general-settings');
}



//  create new category for ACF blocks
add_filter( 'block_categories', 'mmc_acf_block_categories', 10, 2 );
function mmc_acf_block_categories( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'acf-blocks',
				'title' => __( 'ACF blocks', 'mmc' ),
				'icon'  => 'vault',
			),
		)
	);
}


/* Register ACF blocks for gutenberg
* https://www.advancedcustomfields.com/resources/blocks/
* https://www.advancedcustomfields.com/resources/acf_register_block_type/
*/
if( function_exists('acf_register_block_type') ) {
	add_action('acf/init', 'mmc_register_acf_block_types');
}

function mmc_register_acf_block_types() {
	$theme_url = get_stylesheet_directory_uri();
	$thumb_url = $theme_url.'/assets/img/partials/';
	$assets_url = $theme_url.'/assets/public/partials/';

	$blocks = array(
		array(
			'name'						=> ($name = 'example'),
			'title'						=> __('Example', 'mmc'),
			'description'			=> __('This is sample block, please remove it upon further development.','mmc'),
			// 'enqueue_style' 	=> $assets_url.$name.'.css',
			// 'enqueue_script' 	=> $assets_url.$name.'.js',
			'category'				=> 'acf-blocks',
			'icon'						=> 'layout',
			'mode'						=> 'preview',
			'supports'				=> array( 'align' => false ),
			'render_callback' => 'mmc_render_block_thumbnail',
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'is_example' => true, 
						'thumb' => $thumb_url.$name.'.png'
					],
				]
			],
			'enqueue_assets' => function() use($name, $assets_url) {
				wp_enqueue_style( $name, $assets_url.$name.'.css', array(),'', false);
				wp_enqueue_script( $name, $assets_url.$name.'.js', array(),'',true);
			},
			// 'post_types'			=> array('post', 'page'),
		),
	);

	foreach ($blocks as $block) {
		acf_register_block($block);
	}
}


function mmc_render_block_thumbnail ($block, $content = '', $is_preview = false, $post_id = 0) {
	if ($is_preview && isset($block['data']['thumb'])) {
		$img = $block['data']['thumb'];
		echo '<img src="'.$img.'">';
	} else {
		$slug = str_replace('acf/', '', $block['name']);
		include( get_theme_file_path("partials/block-".$slug.".php") );
	}
}