<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package mmc
 */

get_header();
if ( have_posts() ) :

	get_template_part( 'template-parts/loop', 'archive' );


else :

	get_template_part( 'template-parts/content', 'none' );

endif;
get_sidebar();
get_footer();
