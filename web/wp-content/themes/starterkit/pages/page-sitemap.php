<?php
/*
Template Name: Page Sitemap
*/

?>

<?php get_template_part('template-parts/general/block', 'header_nav'); ?>

<main id="main" role="main" tabindex="-1" class="page-sitemap">

    <?php Heros::hero_simple(); ?>

    <?php views('strate-sitemap'); ?>
</main>

<?php get_tpl();
