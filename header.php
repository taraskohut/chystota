<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-template-parts
 *
 * @package mmc
 */

if (strrpos($_SERVER['REQUEST_URI'], '?') > 0) {
$string = explode('?', $_SERVER['REQUEST_URI'], 2);
$server1 = $string[0] . '?';
$server = strtolower($string[0]) . '?';
$utm = $string[1];
$new = 'https://' . $_SERVER['HTTP_HOST'] . $server . $utm;
if ($server1 != $server) {
		header('Location: ' . $new . '', true, 301);
		exit();
}
} else {
	if ($_SERVER['REQUEST_URI'] != strtolower($_SERVER['REQUEST_URI'])) {
			header('Location: https://' . $_SERVER['HTTP_HOST'] . strtolower($_SERVER['REQUEST_URI']), true, 301);
			exit();
	}
}

$fields = get_fields('option');
$langs_array = pll_the_languages(array('dropdown' => 1, 'hide_current' => 1, 'raw' => 1));
$langs_array_mobile = pll_the_languages(array('hide_current' => 0, 'raw' => 1));
$current_language = pll_current_language();
$parent_id = wp_get_post_parent_id(get_the_ID());
$child_pages = get_pages( array(
	'child_of' => get_the_ID()
) );
$front_page_id = get_option('page_on_front');
$settings__ad_text = 'settings__ad-text-' . $current_language;
if(($current_language !== 'ru') && isset($fields['settings__hide-language']) && $fields['settings__hide-language']) {
	unset($langs_array['ru']);
	unset($langs_array_mobile['ru']);
}

//Set cookie (City MOD)
global $selected_city, $city_objects, $main_menu, $sub_menu, $cleaning_menu;
$city_objects = array();
$default_city = $fields['settings_main-city'];

if(!empty($default_city)) {
	$translated_post_id = pll_get_post($default_city, $current_language);
	$selected_city = $translated_post_id ? get_post($translated_post_id) : get_post($default_city);
	$default_city_translation = strtolower(get_field('post-city_translation', $default_city));
	$main_menu = 'main_menu';
	$sub_menu = 'sub_menu';
	$cleaning_menu = 'cleaning_menu';
	$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
	$cities = get_posts([
		'post_type' => 'city_post',
		'post_status' => 'publish',
		'numberposts' => -1
	]);

	foreach ($cities as $city) {
		if(str_contains($actual_link, $city->post_name)) {
			setcookie('user_city', $city->post_name, time() + 365 * 24 * 60 * 60, '/');
			$main_menu = 'main_menu_' . strtolower($city->post_name);
			$sub_menu = 'sub_menu_' . strtolower($city->post_name);
			$cleaning_menu = 'cleaning_menu_' . strtolower($city->post_name);
			$user_city = $city->post_name;
			break;
		} else {
			setcookie('user_city', $default_city_translation, time() + 365 * 24 * 60 * 60, '/');
			$user_city = $default_city_translation;
		}
	}

	$city_query = new WP_Query(array(
		'post_type'      => 'city_post',
		'posts_per_page' => -1,
		'meta_query'     => array(
			array(
				'key'     => 'post-city_translation',
				'value'   => $user_city,
				'compare' => '='
			)
		)
	));
	while ($city_query->have_posts()) {
		$city_query->the_post();
		$default_city = get_the_ID();
		$selected_city = get_post();
	}
	wp_reset_postdata();

	$args = array(
		'post_type' => 'city_post',
		'posts_per_page' => -1,
		'post__not_in'   => array($default_city),
	);
	$city_posts_query = new WP_Query($args);
	if ($city_posts_query->have_posts()) {
		while ($city_posts_query->have_posts()) {
			$city_posts_query->the_post();
			$post_city_translation = strtolower(get_field('post-city_translation'));
			$post_city_work = get_field('post-city_work');
			$city_url = '';
			$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
			$language_prefix = '/' . $current_language . '/';
			$cleaned_path = str_replace($language_prefix, '/', $path);
			if(str_contains($actual_link, $user_city)) {
				$city_url = str_replace($user_city, $post_city_translation, $actual_link);
				if($post_city_translation == $default_city_translation) {
					$city_url = str_replace($user_city.'/', '', $actual_link);
				}
			} else {
				$city_url = rtrim(pll_home_url(), '/') . '/' . $post_city_translation . $cleaned_path;
			}
			if (($city_url !== pll_home_url()) && !url_to_postid($city_url)) {
				$city_url = rtrim(pll_home_url(), '/') . '/' . $post_city_translation. '/';
			}
			$city_objects[] = array(
				'title'       => get_the_title(),
				'translation' => $post_city_translation,
				'work' => $post_city_work,
				'url' => $city_url,
			);
		}
		wp_reset_postdata();
	}
}
?>
						
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	
	<div id="page" class="wrapper">
		<header id="header" class="header">
			<div class="header__body">
				<div class="header__container flex--row first">
					<div class="header__navigation">
						<div class="header__logo">
							<?php if (function_exists('the_custom_logo')) {
								$custom_logo_id = get_theme_mod('custom_logo');
								$logo_svg = wp_get_attachment_url($custom_logo_id);
								if ($logo_svg && !is_front_page() && !is_page_template('templates/base-main.php')) {
									if (!empty($selected_city) && !empty($city_objects) && ($selected_city->post_name !== 'poland') && ($selected_city->post_name !== 'ukraine')) {
										echo '<a href="' . esc_url(pll_home_url() . $selected_city->post_name . '/') . '" rel="home">';
									} else {
										echo '<a href="' . esc_url(home_url('/')) . '" rel="home">';
									}
									echo file_get_contents($logo_svg);
									echo '</a>';
								} elseif ($logo_svg && (is_front_page() || is_page_template('templates/base-main.php'))) {
									echo file_get_contents($logo_svg);
								} else {
									bloginfo('name');
								}
							} else {
							bloginfo('name');
							} ?>
						</div>
						<nav class="header__nav">
							<?php
								if (has_nav_menu($main_menu)) {
									wp_nav_menu(array(
										'theme_location' => $main_menu,
										'menu_id'        => 'main_menu',
										'container'      => '',
									));
								}
							?>
						</nav>
					</div>
					<?php if(!wp_is_mobile()): ?>
						<div class="header__addition">
							<div class="wr-number-head">
								<a href="tel:<?= $fields['settings__phone']; ?>" class="header__tel change_number h5">
										<?= $fields['settings__phone']; ?>
								</a>
							</div>
							<?php if($fields['settings__under-number']): ?>
								<p class="header__under-number">
									<?= $fields[$settings__ad_text]; ?>
								</p>
							<?php else: ?>
								<div class="header__under-empty"></div>
							<?php endif; ?>
							<div class="header__panel">
								<div class="header__language" <?php if(empty($langs_array)){echo 'style="pointer-events: none;"';}?>>
									<div class="current-language <?= $current_language; ?>-language">
										<div class="h6">
											<?= $current_language; ?>
										</div>
										<img class="header__language-icon" src="<?php echo get_template_directory_uri() . '/dist/img/' . $current_language . '.svg'; ?>" alt="anguage-icon">
									</div>
									<div class="drop-block lang">
										<?php foreach ($langs_array as $lang) : ?>
											<a href="<?= $lang['url']; ?>" class="drop-block__link <?= $lang['slug']; ?>">
												<img class="header__language-icon" src="<?php echo get_template_directory_uri() . '/dist/img/' . $lang['slug'] . '.svg'; ?>" alt="anguage-icon">
												<?= $lang['slug']; ?>
											</a>
										<?php endforeach; ?>
									</div>
								</div>
								<?php if (!empty($selected_city) && !empty($city_objects)): ?>
									<div class="header__city">
										<?php
											echo '<button class="header__selected-city" data-city="' . esc_attr($selected_city->post_title) . '">
												<h6>' . esc_html($selected_city->post_title) . '</h6>
												<svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M6.5 8L10.5 12L14.5 8" stroke="#050607"/>
												</svg>
											</button>';
											echo '<div class="header__select-sity">';
											foreach ($city_objects as $city_object) {
												echo '<a class="city-button" href="' . esc_url($city_object['url']) . '">' . esc_html($city_object['title']) . '</a><br>';
											}									
											echo '</div>';
										?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php else: ?>
						<div class="header__addition mobile">
							<a href="tel:<?= $fields['settings__phone']; ?>" class="header__tel change_number">
								<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21.97 18.83C21.97 19.19 21.89 19.56 21.72 19.92C21.55 20.28 21.33 20.62 21.04 20.94C20.55 21.48 20.01 21.87 19.4 22.12C18.8 22.37 18.15 22.5 17.45 22.5C16.43 22.5 15.34 22.26 14.19 21.77C13.04 21.28 11.89 20.62 10.75 19.79C9.58811 18.9401 8.49169 18.0041 7.47 16.99C6.45877 15.972 5.5261 14.8789 4.68 13.72C3.86 12.58 3.2 11.44 2.72 10.31C2.24 9.17 2 8.08 2 7.04C2 6.36 2.12 5.71 2.36 5.11C2.6 4.5 2.98 3.94 3.51 3.44C4.15 2.81 4.85 2.5 5.59 2.5C5.87 2.5 6.15 2.56 6.4 2.68C6.66 2.8 6.89 2.98 7.07 3.24L9.39 6.51C9.57 6.76 9.7 6.99 9.79 7.21C9.88 7.42 9.93 7.63 9.93 7.82C9.93 8.06 9.86 8.3 9.72 8.53C9.59 8.76 9.4 9 9.16 9.24L8.4 10.03C8.29 10.14 8.24 10.27 8.24 10.43C8.24 10.51 8.25 10.58 8.27 10.66C8.3 10.74 8.33 10.8 8.35 10.86C8.53 11.19 8.84 11.62 9.28 12.14C9.73 12.66 10.21 13.19 10.73 13.72C11.27 14.25 11.79 14.74 12.32 15.19C12.84 15.63 13.27 15.93 13.61 16.11C13.66 16.13 13.72 16.16 13.79 16.19C13.87 16.22 13.95 16.23 14.04 16.23C14.21 16.23 14.34 16.17 14.45 16.06L15.21 15.31C15.46 15.06 15.7 14.87 15.93 14.75C16.16 14.61 16.39 14.54 16.64 14.54C16.83 14.54 17.03 14.58 17.25 14.67C17.47 14.76 17.7 14.89 17.95 15.06L21.26 17.41C21.52 17.59 21.7 17.8 21.81 18.05C21.91 18.3 21.97 18.55 21.97 18.83Z" stroke="#050607" stroke-width="1.5" stroke-miterlimit="10"/></svg>
							</a>
							<div class="header__menu-toggle">
								<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.5217 19.9565C12.5217 20.2333 12.6866 20.4987 12.9802 20.6944C13.2737 20.8901 13.6718 21 14.087 21H22.4348C22.8499 21 23.248 20.8901 23.5416 20.6944C23.8351 20.4987 24 20.2333 24 19.9565C24 19.6798 23.8351 19.4144 23.5416 19.2187C23.248 19.023 22.8499 18.913 22.4348 18.913H14.087C13.6718 18.913 13.2737 19.023 12.9802 19.2187C12.6866 19.4144 12.5217 19.6798 12.5217 19.9565ZM6.26087 13C6.26087 13.2767 6.42578 13.5422 6.71931 13.7379C7.01285 13.9335 7.41097 14.0435 7.82609 14.0435H22.4348C22.8499 14.0435 23.248 13.9335 23.5416 13.7379C23.8351 13.5422 24 13.2767 24 13C24 12.7233 23.8351 12.4578 23.5416 12.2621C23.248 12.0665 22.8499 11.9565 22.4348 11.9565H7.82609C7.41097 11.9565 7.01285 12.0665 6.71931 12.2621C6.42578 12.4578 6.26087 12.7233 6.26087 13ZM0 6.04348C0 6.32023 0.164907 6.58564 0.458442 6.78133C0.751978 6.97702 1.1501 7.08696 1.56522 7.08696H22.4348C22.8499 7.08696 23.248 6.97702 23.5416 6.78133C23.8351 6.58564 24 6.32023 24 6.04348C24 5.76673 23.8351 5.50132 23.5416 5.30563C23.248 5.10994 22.8499 5 22.4348 5H1.56522C1.1501 5 0.751978 5.10994 0.458442 5.30563C0.164907 5.50132 0 5.76673 0 6.04348Z" fill="#050607"/></svg>
							</div>
							<div class="header__menu-close" style="display: none;">
								<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_3500_49874)"><path d="M5.36396 19.364L18.0919 6.63604M18.0919 19.364L5.36396 6.63604" stroke="#050607" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_3500_49874"><rect width="24" height="24" fill="white" transform="translate(0 0.5)"/></clipPath></defs></svg>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php if(!is_single() && !is_page_template('templates/secondary-pages.php') && !is_page_template('templates/privacy-policy.php') && !is_404()): ?>
				<div class="header__submenu">
					<div class="header__container line flex--row">
						<?php
						if(is_front_page()) {
							$theme_location = $sub_menu;
						} elseif($parent_id === 0 && empty($child_pages)) {
							$theme_location = $sub_menu;
						} else {
							$theme_location = $cleaning_menu;
						}
						?>
						<?php if (has_nav_menu($theme_location)): ?>
							<nav class="header__subnav">
								<?php
									wp_nav_menu( array(
										'theme_location'	=> $theme_location,
										'menu_id'			=> 'sub_menu',
										'container'			=> '',
									) );
								?>
							</nav>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			<div class="header__progress">
				<div id="progress" class="header__progress-bar" style="width: 0%;"></div>
			</div>
			<div class="header-mobile">
				<div class="header-mobile__container">
					<?php if (has_nav_menu($sub_menu)): ?>
						<div class="header-mobile__title h4" data-menu="sub_menu-mobile">
							<?php echo pll__('Прибирання', 'chistota'); ?>
						</div>
						<nav class="header-mobile__subnav">
							<?php
								wp_nav_menu( array(
									'theme_location'	=> $sub_menu,
									'menu_id'			=> 'sub_menu-mobile',
									'container'			=> '',
								) );
							?>
						</nav>
					<?php endif; ?>
					<?php if (has_nav_menu($cleaning_menu)): ?>
						<div class="header-mobile__title h4" data-menu="cleaning_menu-mobile">
							<?php echo pll__('Хімчистка', 'chistota'); ?>
						</div>
						<nav class="header-mobile__subnav">
							<?php
								wp_nav_menu( array(
									'theme_location'	=> $cleaning_menu,
									'menu_id'			=> 'cleaning_menu-mobile',
									'container'			=> '',
								) );
							?>
						</nav>
					<?php endif; ?>
					<p class="header-mobile__subtitle line">
						<?php echo pll__('Ми в соціальних мережах:', 'chistota'); ?>
					</p>
					<div class="header-mobile__socials d-flex">
						<?php if($fields['settings__facebook']): ?>
							<a href="<?= $fields['settings__facebook']; ?>" target="_blank" class="header-mobile__social">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24 12C24 5.37258 18.6274 0 12 0C5.37258 0 0 5.37258 0 12C0 17.9895 4.3882 22.954 10.125 23.8542V15.4688H7.07812V12H10.125V9.35625C10.125 6.34875 11.9166 4.6875 14.6576 4.6875C15.9701 4.6875 17.3438 4.92188 17.3438 4.92188V7.875H15.8306C14.34 7.875 13.875 8.80008 13.875 9.75V12H17.2031L16.6711 15.4688H13.875V23.8542C19.6118 22.954 24 17.9895 24 12Z" fill="#1877F2"/><path d="M16.6711 15.4688L17.2031 12H13.875V9.75C13.875 8.80102 14.34 7.875 15.8306 7.875H17.3438V4.92188C17.3438 4.92188 15.9705 4.6875 14.6576 4.6875C11.9166 4.6875 10.125 6.34875 10.125 9.35625V12H7.07812V15.4688H10.125V23.8542C11.3674 24.0486 12.6326 24.0486 13.875 23.8542V15.4688H16.6711Z" fill="white"/></svg>
							</a>
						<?php endif; ?>
						<?php if($fields['settings__instagram']): ?>
							<a href="<?= $fields['settings__instagram']; ?>" target="_blank" class="header-mobile__social">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.0039 0C6.99403 0 5.52883 0.00516967 5.24402 0.0288024C4.2159 0.114287 3.57615 0.276208 2.87917 0.623314C2.34204 0.890106 1.91843 1.19936 1.50035 1.63288C0.738959 2.42347 0.277507 3.3961 0.110461 4.55226C0.0292458 5.11354 0.00561946 5.22801 0.000820359 8.09496C-0.00102545 9.05061 0.000820359 10.3083 0.000820359 11.9953C0.000820359 17.0025 0.00635778 18.4666 0.0303533 18.7509C0.113415 19.7516 0.270308 20.3812 0.602553 21.0699C1.23751 22.3882 2.45021 23.3778 3.87886 23.7471C4.37354 23.8745 4.9199 23.9446 5.6213 23.9778C5.91848 23.9908 8.94744 24 11.9783 24C15.0091 24 18.0399 23.9963 18.3297 23.9815C19.1418 23.9433 19.6134 23.88 20.1349 23.7452C21.5728 23.3741 22.7633 22.3993 23.4112 21.0625C23.737 20.3905 23.9022 19.7369 23.9769 18.7884C23.9932 18.5816 24 15.2847 24 11.9922C24 8.69907 23.9926 5.40821 23.9764 5.20142C23.9007 4.23765 23.7355 3.5896 23.3992 2.90461C23.1232 2.34389 22.8168 1.92515 22.372 1.49699C21.5781 0.738524 20.6072 0.276947 19.4503 0.11004C18.8897 0.0289871 18.778 0.00498504 15.9096 0H12.0039Z" fill="url(#paint0_radial_4337_26302)"/><path d="M12.0039 0C6.99403 0 5.52883 0.00516967 5.24402 0.0288024C4.2159 0.114287 3.57615 0.276208 2.87917 0.623314C2.34204 0.890106 1.91843 1.19936 1.50035 1.63288C0.738959 2.42347 0.277507 3.3961 0.110461 4.55226C0.0292458 5.11354 0.00561946 5.22801 0.000820359 8.09496C-0.00102545 9.05061 0.000820359 10.3083 0.000820359 11.9953C0.000820359 17.0025 0.00635778 18.4666 0.0303533 18.7509C0.113415 19.7516 0.270308 20.3812 0.602553 21.0699C1.23751 22.3882 2.45021 23.3778 3.87886 23.7471C4.37354 23.8745 4.9199 23.9446 5.6213 23.9778C5.91848 23.9908 8.94744 24 11.9783 24C15.0091 24 18.0399 23.9963 18.3297 23.9815C19.1418 23.9433 19.6134 23.88 20.1349 23.7452C21.5728 23.3741 22.7633 22.3993 23.4112 21.0625C23.737 20.3905 23.9022 19.7369 23.9769 18.7884C23.9932 18.5816 24 15.2847 24 11.9922C24 8.69907 23.9926 5.40821 23.9764 5.20142C23.9007 4.23765 23.7355 3.5896 23.3992 2.90461C23.1232 2.34389 22.8168 1.92515 22.372 1.49699C21.5781 0.738524 20.6072 0.276947 19.4503 0.11004C18.8897 0.0289871 18.778 0.00498504 15.9096 0H12.0039Z" fill="url(#paint1_radial_4337_26302)"/><path d="M11.9983 3.13867C9.59211 3.13867 9.29014 3.1492 8.34509 3.19222C7.40188 3.23542 6.75806 3.38479 6.19472 3.60394C5.612 3.8303 5.11769 4.13309 4.62523 4.62587C4.1324 5.11847 3.82969 5.61291 3.60265 6.19561C3.383 6.75929 3.23349 7.40346 3.19104 8.34656C3.14877 9.29187 3.1377 9.59411 3.1377 12.001C3.1377 14.4078 3.1484 14.7089 3.19122 15.6543C3.2346 16.5977 3.38393 17.2417 3.60284 17.8052C3.82932 18.3881 4.13203 18.8825 4.62468 19.3751C5.11695 19.8681 5.61126 20.1716 6.19361 20.398C6.75732 20.6171 7.40132 20.7665 8.34435 20.8097C9.2894 20.8527 9.59119 20.8632 11.9972 20.8632C14.4036 20.8632 14.7046 20.8527 15.6497 20.8097C16.5929 20.7665 17.2374 20.6171 17.8012 20.398C18.3837 20.1716 18.8773 19.8681 19.3695 19.3751C19.8624 18.8825 20.1651 18.3881 20.3921 17.8054C20.6099 17.2417 20.7594 16.5975 20.8037 15.6544C20.8462 14.7091 20.8573 14.4078 20.8573 12.001C20.8573 9.59411 20.8462 9.29205 20.8037 8.34674C20.7594 7.40328 20.6099 6.75929 20.3921 6.19579C20.1651 5.61291 19.8624 5.11847 19.3695 4.62587C18.8767 4.13291 18.3839 3.83012 17.8006 3.60394C17.2358 3.38479 16.5916 3.23542 15.6484 3.19222C14.7033 3.1492 14.4025 3.13867 11.9955 3.13867H11.9983ZM11.2035 4.73573C11.4394 4.73536 11.7026 4.73573 11.9983 4.73573C14.3639 4.73573 14.6443 4.74422 15.5784 4.78669C16.4423 4.8262 16.9111 4.97058 17.2234 5.09188C17.6369 5.25251 17.9317 5.44453 18.2416 5.75471C18.5517 6.06489 18.7436 6.3603 18.9046 6.77387C19.0258 7.0859 19.1704 7.55486 19.2097 8.41893C19.2521 9.35317 19.2614 9.63381 19.2614 11.9989C19.2614 14.3641 19.2521 14.6447 19.2097 15.5789C19.1702 16.443 19.0258 16.912 18.9046 17.224C18.744 17.6376 18.5517 17.932 18.2416 18.242C17.9315 18.5522 17.6371 18.7442 17.2234 18.9049C16.9115 19.0267 16.4423 19.1707 15.5784 19.2102C14.6445 19.2527 14.3639 19.2619 11.9983 19.2619C9.63254 19.2619 9.35216 19.2527 8.41818 19.2102C7.55434 19.1704 7.08551 19.026 6.77301 18.9047C6.35955 18.7441 6.06422 18.552 5.75413 18.2419C5.44403 17.9317 5.25207 17.637 5.09111 17.2232C4.96984 16.9112 4.82532 16.4423 4.786 15.5782C4.74355 14.644 4.73506 14.3633 4.73506 11.9967C4.73506 9.63011 4.74355 9.35095 4.786 8.41672C4.8255 7.55265 4.96984 7.08368 5.09111 6.77129C5.2517 6.35771 5.44403 6.0623 5.75413 5.75212C6.06422 5.44194 6.35955 5.24993 6.77301 5.08893C7.08532 4.96707 7.55434 4.82306 8.41818 4.78337C9.2355 4.74644 9.55224 4.73536 11.2035 4.73351V4.73573ZM16.7276 6.20724C16.1407 6.20724 15.6644 6.68303 15.6644 7.27034C15.6644 7.85747 16.1407 8.33382 16.7276 8.33382C17.3146 8.33382 17.7908 7.85747 17.7908 7.27034C17.7908 6.68322 17.3146 6.20687 16.7276 6.20687V6.20724ZM11.9983 7.44981C9.48561 7.44981 7.44839 9.48758 7.44839 12.001C7.44839 14.5143 9.48561 16.5512 11.9983 16.5512C14.511 16.5512 16.5475 14.5143 16.5475 12.001C16.5475 9.48758 14.511 7.44981 11.9983 7.44981ZM11.9983 9.04686C13.6293 9.04686 14.9516 10.3694 14.9516 12.001C14.9516 13.6324 13.6293 14.9551 11.9983 14.9551C10.3672 14.9551 9.04502 13.6324 9.04502 12.001C9.04502 10.3694 10.3672 9.04686 11.9983 9.04686Z" fill="white"/><defs><radialGradient id="paint0_radial_4337_26302" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(6.37503 25.8485) rotate(-90) scale(23.7858 22.1226)"><stop stop-color="#FFDD55"/><stop offset="0.1" stop-color="#FFDD55"/><stop offset="0.5" stop-color="#FF543E"/><stop offset="1" stop-color="#C837AB"/></radialGradient><radialGradient id="paint1_radial_4337_26302" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(-4.02013 1.72892) rotate(78.6806) scale(10.6323 43.827)"><stop stop-color="#3771C8"/><stop offset="0.128" stop-color="#3771C8"/><stop offset="1" stop-color="#6600FF" stop-opacity="0"/></radialGradient></defs></svg>
							</a>
						<?php endif; ?>
						<?php if($fields['settings__youtube']): ?>
							<a href="<?= $fields['settings__youtube']; ?>" target="_blank" class="header-mobile__social">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M23.5058 18.2048C23.9999 16.193 23.9999 12.021 23.9999 12.021C23.9999 12.021 24.0198 7.82833 23.5058 5.81658C23.2289 4.71544 22.4184 3.84689 21.3904 3.55057C19.5124 3 12.0001 3 12.0001 3C12.0001 3 4.48784 3 2.60977 3.52954C1.60144 3.82586 0.770976 4.71544 0.494351 5.81658C0 7.82833 0 12 0 12C0 12 0 16.193 0.494351 18.1834C0.771269 19.2846 1.58181 20.1531 2.60977 20.4494C4.50747 21 12.0001 21 12.0001 21C12.0001 21 19.5124 21 21.3904 20.4705C22.4184 20.1741 23.2289 19.3059 23.5058 18.2048ZM15.8553 12.0001L9.6084 15.8541V8.14605L15.8553 12.0001Z" fill="#F50000"/></svg>
							</a>
						<?php endif; ?>
						<?php if($fields['settings__tiktok']): ?>
							<a href="<?= $fields['settings__tiktok']; ?>" target="_blank" class="header-mobile__social">
								<svg width="21" height="24" viewBox="0 0 21 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.5634 8.66348C17.0956 9.77065 18.9727 10.4221 21 10.4221V6.47862C20.6163 6.4787 20.2336 6.43826 19.8583 6.35787V9.46194C17.8311 9.46194 15.9543 8.8105 14.4217 7.70341V15.7509C14.4217 19.7766 11.1932 23.0399 7.21097 23.0399C5.72509 23.0399 4.34403 22.5858 3.19678 21.8071C4.50618 23.1604 6.33223 24 8.35244 24C12.335 24 15.5636 20.7367 15.5636 16.7108V8.66348H15.5634ZM16.9718 4.685C16.1888 3.82023 15.6747 2.70268 15.5634 1.46718V0.959961H14.4815C14.7538 2.53028 15.6827 3.87187 16.9718 4.685ZM5.71557 18.7178C5.27807 18.138 5.04165 17.4286 5.04271 16.6993C5.04271 14.858 6.51938 13.3651 8.3412 13.3651C8.68073 13.3651 9.01822 13.4176 9.34178 13.5214V9.48978C8.96365 9.43739 8.58202 9.41515 8.20056 9.42331V12.5613C7.87675 12.4575 7.5391 12.4048 7.19949 12.4051C5.37767 12.4051 3.90107 13.8979 3.90107 15.7394C3.90107 17.0414 4.63917 18.1687 5.71557 18.7178Z" fill="#FF004F"/><path d="M14.4217 7.70333C15.9543 8.81042 17.8311 9.46186 19.8582 9.46186V6.35778C18.7267 6.11414 17.725 5.51641 16.9718 4.685C15.6826 3.87179 14.7538 2.5302 14.4815 0.959961H11.6395V16.7106C11.6331 18.5468 10.1589 20.0336 8.34101 20.0336C7.26975 20.0336 6.31803 19.5174 5.71529 18.7178C4.63898 18.1687 3.90088 17.0413 3.90088 15.7394C3.90088 13.8981 5.37748 12.4052 7.1993 12.4052C7.54835 12.4052 7.88478 12.4602 8.20037 12.5614V9.42339C4.28805 9.5051 1.1416 12.7365 1.1416 16.7107C1.1416 18.6946 1.92514 20.4931 3.19683 21.8071C4.34408 22.5858 5.72515 23.04 7.21103 23.04C11.1934 23.04 14.4218 19.7766 14.4218 15.7509V7.70333H14.4217Z" fill="black"/><path d="M19.8583 6.35781V5.51849C18.8379 5.52005 17.8376 5.23119 16.9718 4.68494C17.7382 5.53307 18.7473 6.11787 19.8583 6.35781ZM14.4815 0.959984C14.4555 0.809912 14.4356 0.658852 14.4217 0.507214V0H10.4977V15.7508C10.4915 17.5868 9.0174 19.0736 7.19933 19.0736C6.66557 19.0736 6.16162 18.9455 5.71532 18.7179C6.31806 19.5175 7.26978 20.0336 8.34104 20.0336C10.1588 20.0336 11.6332 18.5469 11.6395 16.7107V0.959984H14.4815ZM8.20056 9.42342V8.5299C7.87268 8.4846 7.54211 8.46187 7.21114 8.46203C3.22846 8.46195 0 11.7254 0 15.7508C0 18.2745 1.26884 20.4987 3.19694 21.807C1.92525 20.493 1.14171 18.6945 1.14171 16.7106C1.14171 12.7365 4.28808 9.50512 8.20056 9.42342Z" fill="#00F2EA"/></svg>
							</a>
						<?php endif; ?>
					</div>
					<p class="header-mobile__subtitle">
						<?php echo pll__('Мова:', 'chistota'); ?>
					</p>
					<div class="header-mobile__switch d-flex">
						<?php foreach ($langs_array_mobile as $lang) : ?>
							<a href="<?= $lang['url']; ?>" class="header-mobile__link <?= $lang['slug']; ?> <?php if($current_language == $lang['slug']){echo 'active';} ?>">
								<img class="header-mobile__language-icon" src="<?php echo get_template_directory_uri() . '/dist/img/' . $lang['slug'] . '.svg'; ?>" alt="anguage-icon" width="24" height="24">
								<?= $lang['slug']; ?>
							</a>
						<?php endforeach; ?>
					</div>
					<?php if (!empty($selected_city) && !empty($city_objects)): ?>
						<p class="header-mobile__subtitle">
							<?php echo pll__('Місто:', 'chistota'); ?>
						</p>
						<div class="header-mobile__select-sity">
							<?php 
								echo '<button class="city-button disabled" data-city="' . esc_attr($selected_city->post_title) . '">
								'. esc_html($selected_city->post_title) .'
								</button>';
								foreach ($city_objects as $city_object) {
									echo '<a class="city-button" href="' . esc_url($city_object['url']) . '">' . esc_html($city_object['title']) . '</a><br>';
								}									
							?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</header>


		<div id="content">
