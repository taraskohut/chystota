<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package mmc
 */

$current_language = pll_current_language();
?>

<?php get_header(); 
get_template_part('template-parts/blocks/block-banner');
?>

<div class="q__container">
            <div class="events-cat-filter">
                <div class="event-cat active" data-category="all"><span>All events</span></div>
                <?php 
                    $cat_arr = [];
                    if(have_posts()) : while(have_posts()) : the_post();
                    $post_id = get_the_ID();
                    $cat_args = array(
                        'orderby'       => 'term_id', 
                        'order'         => 'ASC',
                        'hide_empty'    => true, 
                    );
                    
                    $categories = get_terms('category', $cat_args);
                    foreach ($categories as $category) {
                        if (!in_array($category, $cat_arr)) {
                            $cat_arr[] = $category;
                            echo '<div class="event-cat" data-cat_id="' . $category->term_id . '"><span>' . $category->name . '</span></div>';
                        }
                    }
                endwhile; endif; 

                
                // var_dump($terms);
                ?>
            </div>
            <div class="archive-wrap">
                <?php
                    $custom_query_args = array(
                        'post_type' => 'post',
                        'post_status'    => 'publish',
                        'posts_per_page' => 9,
                        'order'          => 'ASC',
                        'numberposts'    => -1,
                    );
                    $current_page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
                    $custom_query_args['paged'] = $current_page; 
                    $next_event_query = new WP_Query( $custom_query_args );

                    // Pagination fix
                    $temp_query = $wp_query;
                    $wp_query   = NULL;
                    $wp_query   = $next_event_query;

                    // $cat_arr = []; 
                    if($next_event_query->have_posts()) : 
                        while($next_event_query->have_posts()) : $next_event_query->the_post(); 
                    $post_id = get_the_ID();
                    $permalink = get_permalink( $post_id );
                    $post_title = get_the_title();
                ?>
                    <?= $post_title; echo '<br>';
                        // get_template_part('template-parts/items/item-post');
                    ?>
                <?php endwhile; endif; ?>
                <?php 
                    // Reset postdata
                    wp_reset_postdata();

                    $total_pages = $next_event_query->max_num_pages;

                    // Custom query loop pagination
                    // previous_posts_link( 'Older Posts' );
                    // next_posts_link( 'Newer Posts', $total_pages );

                    echo '<div class="pagination-wrap">';
                    echo paginate_links(array(
                        'base' => str_replace(99999, '%#%', esc_url(get_pagenum_link(99999))),
                        'total' => $total_pages,
                        'current' => max(1, $current_page),
                        'prev_next' => false,
                        'type' => 'plain',
                    ));
                    echo '</div>';

                    // Reset main query object
                    $wp_query = NULL;
                    $wp_query = $temp_query;
                ?>
            </div>
            <?php //get_template_part ('templates/pagination'); ?>
        </div>



<?php get_footer(); ?>