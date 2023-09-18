<?php

/**
 * - Template Name: Page Homepage
 */

$pageId = get_the_ID();
?>

<?php get_template_part('template-parts/general/block', 'header_nav'); ?>

<main id="main" role="main" tabindex="-1" class="page-homepage">
    <?php
    views('hero-home', array(
        'title' => get_field('hero-homepage-title', $pageId)
    ));
    ?>
    <?php get_template_part('template-parts/general/block', 'views'); ?>

</main>

<?php get_tpl();