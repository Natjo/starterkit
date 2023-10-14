<?php

/**
 * - Template Name: Page Homepage
 */

?>

<?php get_template_part('template-parts/general/block', 'header_nav'); ?>

<main id="main" role="main" tabindex="-1" class="page-homepage">

    <?php Heros::hero_homepage(); ?>

    <?php Strates::display(); ?>

</main>

<?php get_tpl();
