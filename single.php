<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package mmc
 */

get_header();

get_template_part('template-parts/single/single-banner');
get_template_part('template-parts/single/single-content');
get_template_part('template-parts/items/item-breadcrumb');

get_footer();
