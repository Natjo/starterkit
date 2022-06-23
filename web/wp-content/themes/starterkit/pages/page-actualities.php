<?php
/*
Template Name: Page Actualities
*/

?>

<?php get_template_part('template-parts/general/block', 'header_nav'); ?>

<main id="main" role="main" tabindex="-1" class="page-actualities">
    <?php
    views('hero-simple', array(
        'title' => get_the_title()
    ));
    ?>

    <?php views('strate-actualites'); ?>
</main>

<?php get_tpl();
