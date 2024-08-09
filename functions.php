<?php
/**
 *  functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package 
 */

add_action( 'after_setup_theme', 'mmc_setup' );
if ( ! function_exists( 'mmc_setup' ) ) :
	function mmc_setup() {
		load_theme_textdomain( 'mmc', get_template_directory() . '/languages' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array(
			'search-form',
			// 'comment-form',
			// 'comment-list',
			// 'gallery',
			'caption',
		) );

		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'custom-logo', array(
			'flex-width'  => true,
			'flex-height' => true,
		) );
		// add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );
	}
endif;

/* -- register scripts and styling -- */
add_action( 'wp_enqueue_scripts', 'mmc_enqueue_scripts' );
function mmc_enqueue_scripts() {
	wp_enqueue_style( 'theme-css', get_template_directory_uri() . '/dist/css/style.min.css' );
    wp_enqueue_style( 'slick-css', get_template_directory_uri() . '/dist/vendor/css/slick.min.css' );
    wp_enqueue_style( 'klaro-css', get_template_directory_uri() . '/dist/klaro/klaro.css' );
    wp_enqueue_style( 'animate-css', get_template_directory_uri() . '/dist/vendor/css/animate.min.css' );

	wp_enqueue_script('jquery', false, false, true);
	wp_register_script( 'slick', get_template_directory_uri() . '/dist/vendor/js/slick.min.js', array('jquery'), '1.0.0', true );
    wp_register_script( 'gsap', get_template_directory_uri() . '/dist/vendor/js/gsap.min.js', array('slick'), '1.0.0', true );
    wp_register_script( 'klaro-config', get_template_directory_uri() . '/dist/klaro/config.js', array('gsap'), '1.0.0', true );
    wp_register_script( 'klaro', get_template_directory_uri() . '/dist/klaro/klaro-no-css.js', array('klaro-config'), '1.0.0', true );
    wp_enqueue_script('maskedinpu', get_template_directory_uri() . '/dist/vendor/js/jquery.maskedinput.min.js', array('klaro'), '1.0.0', true);
    wp_register_script( 'custom-fast', get_template_directory_uri() . '/dist/js/custom-fast.min.js', array('maskedinpu'), '1.0.0', true );
	wp_register_script( 'theme-js', get_template_directory_uri() . '/dist/js/custom.min.js', array('custom-fast'), '1.0.0', true );
    wp_enqueue_script( 'ajax-js', get_template_directory_uri() . '/dist/js/ajax.min.js', array('jquery'), '1.0.0', true );
	wp_localize_script( 'theme-js', 'mmc_ajax_params', array(
		'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php',
	) );
	wp_enqueue_script( 'theme-js' );
}

/* -- register menus -- */
register_nav_menus( array(
	'main_menu' => 'Main Navigation',
	'sub_menu' => 'Sub Navigation',
    'service_menu' => 'About service',
    'cleaning_menu' => 'Dry cleaners',
) );


/* -- general -- */
include_once('includes/general/mobile-detect.php');
include_once('includes/general/general-edits.php');
include_once('includes/general/wordpress-cleanup.php');

/* -- theme -- */
include_once('includes/theme/template-helpers.php');
include_once('includes/theme/acf-helpers.php');

//get actual page id
function get_page_ID() {
//if on the blog page
	if ( is_home() && ! in_the_loop() ) {
	$ID = get_option('page_for_posts');
	} elseif ( is_post_type_archive()) {
		//reference a custom archive page based it's slug
		//eg. for a 'houses' custom post type, you would create a page called `houses` and store any archive front matter on this page
		$query = get_queried_object();
		$slug = $query->name;
		$pageobj = get_page_by_path($slug);
		$ID = $pageobj->ID;
	} else {
		$ID = get_the_ID();
	}
	return $ID;
}

function basic_cleaning_post() {
	$labels = array(
        'name' => 'Прибирання',
        'singular_name' => 'Запис',
        'menu_name' => 'Прибирання',
	);

	$args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-clipboard',
        'taxonomies' => array('basic_cleaning_category'),
        'supports' => array('title', 'thumbnail'),
	);

	register_post_type('basic_cleaning_post', $args);

	register_taxonomy('basic_cleaning_category', 'basic_cleaning_post', array(
        'label' => 'Категорії',
        'hierarchical' => true,
	));
}
add_action('init', 'basic_cleaning_post');

function city_post() {
	$labels = array(
        'name' => 'Місто',
        'singular_name' => 'Місто',
        'menu_name' => 'Місто',
	);

	$args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-location-alt',
        //'taxonomies' => array('city_category'),
        'supports' => array('title'),
	);

	register_post_type('city_post', $args);

	// register_taxonomy('city_category', 'basic_cleaning_post', array(
    //     'label' => 'Категорії',
    //     'hierarchical' => true,
	// ));
}

add_action('init', 'city_post');

// Add the size of the thumbnail
add_image_size('thumbnail-45x45', 45, 45, true);
add_image_size('thumbnail-150x150', 150, 150, true);
add_image_size('thumbnail-200x200', 200, 200, true);
add_image_size('thumbnail-300x300', 300, 300, true);
add_image_size('thumbnail-336x432', 336, 432, true);
add_image_size('thumbnail-500x233', 500, 233, true);
add_image_size('thumbnail-408x516', 408, 516, true);
add_image_size('thumbnail-451x225', 451, 225, true);
add_image_size('thumbnail-500x500', 500, 500, true);
add_image_size('thumbnail-585x350', 585, 350, true);
add_image_size('thumbnail-585x628', 585, 628, true);

// block-basic-cleaning.php
add_action('wp_ajax_load_category_posts', 'load_category_posts');
add_action('wp_ajax_nopriv_load_category_posts', 'load_category_posts');
add_action('load_category_posts', 'load_category_posts');

function load_category_posts() {
    $terms = get_sub_field('basic-cleaning__categories')[0];
    $category_id = (isset($_POST['category_id'])) ? $_POST['category_id'] : $terms->term_id;
    $post_id_ajax = (isset($_POST['post_id'])) ? $_POST['post_id'] : '';
    $post_count_ajax = (isset($_POST['post_count'])) ? $_POST['post_count'] : '2';
    $modifiedCount = ceil($post_count_ajax / 2);
    $current_language = pll_current_language();
    $image_ctg = get_field('basic-cleaning__ctg-image', 'basic_cleaning_category_' . $category_id);
    $template = (isset($_POST['template'])) ? $_POST['template'] : get_sub_field('basic-cleaning__template');

    $query_args = array(
        'post_type' => 'basic_cleaning_post',
        'tax_query' => array(
            array(
                'taxonomy' => 'basic_cleaning_category',
                'field' => 'term_id',
                'terms' => $category_id,
            ),
        ),
        'posts_per_page' => 10,
        'lang' => $current_language,
    );
    $count_post = 2;
    $query = new WP_Query($query_args);
    if ($query->have_posts()) { 
        ?>
        <div class="basic-cleaning__row">
            <?php
            while ($query->have_posts()) {
                $query->the_post();
                if(get_field("additional_$template", get_the_ID())) {
                    $count_post++;
                ?>
                    <div class="column" data-post-id="<?php echo esc_attr(get_the_ID()); ?>" data-post-template="<?php echo esc_attr($template); ?>" data-post-count="<?php echo esc_attr($count_post); ?>">
                        <p class="name">
                            <span class="img">
                                <?php   
                                    the_post_thumbnail('thumbnail-45x45');
                                ?>
                            </span>
                            <span class="title-span h5"><?php the_title(); ?></span>
                        </p>
                        <div class="absoulte-position">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_3774_19358)"><path d="M8.00016 5.33325V8.66659M7.99683 10.6666H8.00283M8.00016 14.6666C11.6668 14.6666 14.6668 11.6666 14.6668 7.99992C14.6668 4.33325 11.6668 1.33325 8.00016 1.33325C4.3335 1.33325 1.3335 4.33325 1.3335 7.99992C1.3335 11.6666 4.3335 14.6666 8.00016 14.6666Z" stroke="#2A2A2A" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_3774_19358"><rect width="16" height="16" fill="white"/></clipPath></defs></svg>
                        </div>
                    </div>
                <?php
                }
            } 
            wp_reset_postdata();
            if(wp_is_mobile()):
                echo '<style type="text/css">.basic-cleaning__column > .basic-cleaning__image { display: none;}</style>'; ?>
                <div class="basic-cleaning__image"></div>
            <?php endif; ?>
        </div>
    <?php } ?>
        <div class="basic-cleaning__image" style="grid-column: 1 / span 2; grid-row: <?php echo "$modifiedCount"?> / span 1;">
            <?php if ($post_id_ajax):
                $post = get_post($post_id_ajax);
                if ($post) {
                    echo '<div class="column__head">';
                    echo '<div class="column__body">';
                    echo get_the_post_thumbnail($post_id_ajax, 'thumbnail-45x45');
                    echo '<div class="column__title h5">' . apply_filters('the_title', $post->post_title) . '</div>';
                    if(wp_is_mobile()) {
                        echo '<div class="column__body-close"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="12" fill="#21A068"/><path d="M17 7L12 12M12 12L7 17M12 12L17 17M12 12L7 7" stroke="white" stroke-width="2" stroke-linecap="round"/></svg></div>';
                    }
                    echo '</div>';
                    echo '<p>'. get_field("additional_$template", $post_id_ajax) .'</p>';
                    echo '</div>';
                }
                $gallery_10 = get_field('additional_cleaning_gallery', $post_id_ajax); // Отримати галерею для конкретної публікації
                if ($gallery_10) {
                    $gl_item = (count($gallery_10) === 1) ? 'gallery_one' : 'gallery_two';
                    echo '<div class="gallery ' . $gl_item . '">';
                    foreach ($gallery_10 as $image) {
                        echo wp_get_attachment_image($image['ID'], 'full');
                    }
                    echo '</div>';
                }
                ?>
            <?php else: ?>
                <?= wp_get_attachment_image($image_ctg, 'thumbnail-500x500'); ?>
            <?php endif; ?>
        </div>
    <?php 
    if(isset($_POST['category_id']) || isset($_POST['post_id']) || isset($_POST['template']) || isset($_POST['post_count'])) {
        wp_die();
    }
}

// Polylang Pro
pll_register_string('chistota', 'ТОП', 'Chistota');
pll_register_string('chistota', 'Ми працюємо у', 'Chistota');
pll_register_string('chistota', 'Прийом замовлень:', 'Chistota');
pll_register_string('chistota', 'Виконання замовлень:', 'Chistota');
pll_register_string('chistota', 'Прибирання', 'Chistota');
pll_register_string('chistota', 'Хімчистка', 'Chistota');
pll_register_string('chistota', 'Про сервіс', 'Chistota');
pll_register_string('chistota', 'Про нас пишуть', 'Chistota');
pll_register_string('chistota', 'Фейсбук', 'Chistota');
pll_register_string('chistota', 'Телеграм', 'Chistota');
pll_register_string('chistota', 'Вайбер', 'Chistota');
pll_register_string('chistota', 'ТікТок', 'Chistota');
pll_register_string('chistota', 'Інстаграм', 'Chistota');
pll_register_string('chistota', 'Ютуб', 'Chistota');
pll_register_string('chistota', 'до/після', 'Chistota');
pll_register_string('chistota', 'з', 'Chistota');
pll_register_string('chistota', 'На основі', 'Chistota');
pll_register_string('chistota', 'відгуків', 'Chistota');
pll_register_string('chistota', 'Всі статті', 'Chistota');
pll_register_string('chistota', 'Мова статті:', 'Chistota');
pll_register_string('chistota', 'Назад до Блогу', 'Chistota');
pll_register_string('chistota', 'Дата публікації:', 'Chistota');
pll_register_string('chistota', 'хв', 'Chistota');
pll_register_string('chistota', 'Поділитися цією публікацією:', 'Chistota');
pll_register_string('chistota', 'Найпопулярніші статті', 'Chistota');
pll_register_string('chistota', 'Замовити прибирання', 'Chistota');
pll_register_string('chistota', 'Приєднуйся до нас в соціальних мережах', 'Chistota');
pll_register_string('chistota', 'Наш', 'Chistota');
pll_register_string('chistota', 'Всі відео', 'Chistota');
pll_register_string('chistota', 'Дивитися на YouTube', 'Chistota');
pll_register_string('chistota', 'Замовити', 'Chistota');
pll_register_string('chistota', 'Категорії', 'Chistota');
pll_register_string('chistota', 'Дякуємо!', 'Chistota');
pll_register_string('chistota', 'Ми зателефонуємо Вам впродовж декількох хвилин', 'Chistota');
pll_register_string('chistota', 'Дякуємо за ваше звернення!', 'Chistota');
pll_register_string('chistota', 'Оскільки зараз неробочий час, то ми солодко спимо.', 'Chistota');
pll_register_string('chistota', 'Звʼяжемось з вами', 'Chistota');
pll_register_string('chistota', 'ранку', 'Chistota');
pll_register_string('chistota', 'А поки підписуйтеся на наші соціальні мережі', 'Chistota');
pll_register_string('chistota', 'Час прийому замовлень:', 'Chistota');
pll_register_string('chistota', 'Телефон:', 'Chistota');
pll_register_string('chistota', 'Email:', 'Chistota');
pll_register_string('chistota', 'Адреса офісу:', 'Chistota');
pll_register_string('chistota', 'Юридична та поштова адреса:', 'Chistota');
pll_register_string('chistota', 'Детальніше', 'Chistota');
pll_register_string('chistota', 'Прості відповіді на складні питання:', 'Chistota');
pll_register_string('chistota', 'Упс.. Сторінка не знайдена', 'Chistota');
pll_register_string('chistota', 'На жаль, сторінки яку ви шукаєте не існує. Спробуйте повернутись на головну.', 'Chistota');
pll_register_string('chistota', 'На головну', 'Chistota');
pll_register_string('chistota', 'Замовити дзвінок', 'Chistota');
pll_register_string('chistota', 'Написати нам', 'Chistota');
pll_register_string('chistota', 'Подзвоніть мені', 'Chistota');
pll_register_string('chistota', 'Ми в соціальних мережах:', 'Chistota');
pll_register_string('chistota', 'Мова:', 'Chistota');
pll_register_string('chistota', 'Місто:', 'Chistota');
pll_register_string('chistota', 'Відповіді на запитання', 'Chistota');
pll_register_string('chistota', 'Час читання:', 'Chistota');
pll_register_string('chistota', 'Будь ласка, заповніть це поле', 'Chistota');

// Add SVG to the list of allowed file types
function custom_upload_mimes($existing_mimes) {
    $existing_mimes['svg'] = 'image/svg+xml';
    return $existing_mimes;
}
add_filter('upload_mimes', 'custom_upload_mimes');


add_theme_support( 'admin-bar', array( 'callback' => '__return_false' ) );


// Events Filter 
function my_get_pagenum_link( $pagenum = 1, $escape = true, $base = null ) {
    global $wp_rewrite;

    $pagenum = (int) $pagenum;

    $request = $base ? remove_query_arg( 'paged', $base ) : remove_query_arg( 'paged' );
};

// function custom_pagination_link_classes($args) {
//     // Add your custom class to the 'a' tag
//     $args['mid_size'] = 1;
//     $args['prev_text'] = '&laquo;';
//     $args['next_text'] = '&raquo;';
//     $args['before_page_number'] = '<span class="page filter">';
//     $args['after_page_number'] = '</span>';
//     $args['before'] = '<span class="pagination-item">';
//     $args['after'] = '</span>';
//     return $args;
// }
// add_filter('paginate_links', 'custom_pagination_link_classes');

function filter_posts() {
    // var_dump($_POST);
    global $wp_query;
    $category_id = $_POST['category_id'];
    $page_num = (isset($_POST['page_num'])) ? $_POST['page_num'] : 1;

    $args = array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => wp_is_mobile() ? 5 : 9,
        'paged'          => $page_num,
        'order'          => 'DESC',
    );

    if( $category_id !== 'all') {
        $args['tax_query'] =  array(
            array (
                'taxonomy' => 'category',
                'field' => 'id',
                'terms' => $category_id,
            )
        );
    } 

    $wp_query = new WP_Query( $args );

    if($wp_query->have_posts()) : 
                        while($wp_query->have_posts()) : $wp_query->the_post(); 
                    $post_id = get_the_ID();
                    $permalink = get_permalink( $post_id );
                    $post_title = get_the_title();
                ?>
                    <?=// $post_title;
                    get_template_part('template-parts/items/item-post');
        endwhile;
        wp_reset_postdata();
    else :
        echo 'No posts found';
    endif;

    // Reset postdata
    wp_reset_postdata();

    $total_pages = $wp_query->max_num_pages;

    echo '<div class="pagination-wrap">';
    echo paginate_links(array(
        // 'base' => "#",
        'base' => home_url() . '/blog/page/%#%/',
        'total' => $total_pages,
        'current' => max(1, $page_num),
        'prev_next' => false,
        'type' => 'plain'
    ));
    echo '</div>';

    die();
}
add_action('wp_ajax_filter_posts', 'filter_posts');
add_action('wp_ajax_nopriv_filter_posts', 'filter_posts'); // For non-logged-in users

function chistota_mce_selesct($init_array) {
// Define the style_formats array
    $style_formats = array(
        // Each array child is a format with it's own settings
            array(
            'title' => 'Виділити текст',
            'inline' => 'mark',
            'classes' => 'mark'
        ),
        array(
            'title' => 'Підкреслити текст',
            'inline' => 'span',
            'classes' => 'highlight_text'
        ),
        array(
            'title' => 'Зображення на всю ширину',
            'inline' => 'span',
            'classes' => 'full-width-image'
        ),
    );
    // Insert the array, JSON ENCODED, into 'style_formats'
    $init_array['style_formats'] = json_encode($style_formats);
    return $init_array;
}

// Attach callback to 'tiny_mce_before_init' 
add_filter('tiny_mce_before_init', 'chistota_mce_selesct');

add_filter( 'paginate_links', function($link){
if(is_paged()){$link= str_replace('page/1/', '', $link);}
return $link;
} );

/**
 * ============
 * Poland theme
 * ============
 * WP Polylang how to set hreflang
 */
add_filter('pll_rel_hreflang_attributes', function($hreflangs) {
    $en_hreflang_link = $hreflangs['ru'];
	$ua_hreflang_link = $hreflangs['uk'];
	$pl_hreflang_link = $hreflangs['pl'];
    $hreflangs['ru-PL'] = $en_hreflang_link;
	$hreflangs['uk-PL'] = $ua_hreflang_link;
	$hreflangs['pl-PL'] = $pl_hreflang_link;
    unset($hreflangs['ru']);
	unset($hreflangs['uk']);
	unset($hreflangs['pl']);

    return $hreflangs;
}, 10, 1);
function custom_code_in_head() {
    ?>
<!-- Meta Pixel Code -->
<script type="text/javascript">
/* <![CDATA[ */
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '2322689124655789');
fbq('track', 'PageView');
/* ]]> */
</script>
<!-- End Meta Pixel Code -->

<!-- Google Tag Manager -->
<script type="text/javascript">
/* <![CDATA[ */
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TPTNLT6');
/* ]]> */
</script>
<!-- End Google Tag Manager -->
<!-- KeyCRM online chat widget begin -->
<script type="text/javascript">
(function(w,d,t,u,c){
  var s=d.createElement(t),j=d.getElementsByTagName(t)[0];s.src=u;s["async"]=true;s.defer=true;s.onload=()=>KeyCRM.render(c);j.parentNode.insertBefore(s,j)
})(window, document, "script","https://chat.key.live/bundles/widget.min.js",{token:"22fdf071-3708-4446-b329-ceebb097eb1a"});
</script>
<!-- KeyCRM online chat widget end -->
    <?php
}

add_action('wp_head', 'custom_code_in_head');

register_nav_menus( array(
	'main_menu_krakow' => 'Main Navigation Krakow',
	'sub_menu_krakow' => 'Sub Navigation Krakow',
    'cleaning_menu_krakow' => 'Dry cleaners Krakow',
	'main_menu_wroclaw' => 'Main Navigation Wroclaw',
	'sub_menu_wroclaw' => 'Sub Navigation Wroclaw',
    'cleaning_menu_wroclaw' => 'Dry cleaners Wroclaw',
) );

/* -- first visit -- */
function check_first_visit() {
    session_start();
    if (!isset($_SESSION['visited']) && !isset($_COOKIE['visited']) && pll_current_language() !== 'pl') {
        $_SESSION['visited'] = true;
        setcookie('visited', '1', time() + (10 * 365 * 24 * 60 * 60), '/');
    }
}
add_action('init', 'check_first_visit');

add_action( 'wpcf7_mail_sent', 'cf7_send_data_to_api' );
function cf7_send_data_to_api( $contact_form ) {
    $title = $contact_form->title;
    if ('Розрахувати вартість' == $title) {
        $submission = WPCF7_Submission::get_instance();
        $posted_data = $submission->get_posted_data();
        $data = array(
        'title' => '+48' . $posted_data['chystota-tel'],
        'pipeline_id'=> 2,
        'source_id' => 26,
        'manager_comment' => '',
        'manager_id' => '',
        'contact' => array(
            'full_name' => '',
            'email' => '',
            'phone' => '+48' . $posted_data['chystota-tel']
        ),
        'utm_source' => $posted_data['utm_source'],
        'utm_medium' => $posted_data['utm_medium'],
        'utm_campaign' => $posted_data['utm_campaign'],
        'utm_term' => $posted_data['utm_term'],
        'utm_content' => $posted_data['utm_content']
        );
        $data_string = json_encode($data);
        $token = 'M2E4YjMyZTliMjI1MDMzNmZmNjAyYzYzZGYxMGU3OTdmZDIwMDRhOQ';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://openapi.keycrm.app/v1/leads");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-type: application/json",
            "Accept: application/json",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            'Authorization: Bearer ' . $token
        ));
        $result = curl_exec($ch);
        curl_close($ch);
    }
}