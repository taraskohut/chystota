<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package mmc
 */

get_header();
?>

<link rel="preload" as="image" href="<?= get_template_directory_uri(); ?>/dist/img/404.svg">
<section id="page-404" class="page-404" style="background: url('<?= get_template_directory_uri(); ?>/dist/img/404.svg') no-repeat center; background-size: cover;">
	<div class="page-404__container d-flex">
    <img class="page-404__img" src="<?= get_template_directory_uri(); ?>/dist/img/face-404.svg" alt="404-img" height="80" width="80">
    <h1 class="page-404__title">
      <?php echo pll__('Упс.. Сторінка не знайдена', 'Chistota'); ?>
    </h1>
    <p class="page-404__text">
      <?php echo pll__('На жаль, сторінки яку ви шукаєте не існує. Спробуйте повернутись на головну.', 'Chistota'); ?>
    </p>
    <a href="<?php echo home_url(); ?>" class="page-404__button button-box">
      <?php echo pll__('На головну', 'Chistota'); ?>
    </a>
	</div>
</section>

<?php
get_footer();
