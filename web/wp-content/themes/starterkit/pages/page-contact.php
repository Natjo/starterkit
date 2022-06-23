<?php
/*
Template Name: Page Contact
*/

$pageId = get_the_ID();
?>

<?php get_template_part('template-parts/general/block', 'header_nav'); ?>

<main id="main" role="main" tabindex="-1" class="page-contact">

    <?php
    views('hero-simple', array(
        'title' => get_the_title()
    ));
    ?>

    <?php views('form-contact'); ?>

</main>

<?php get_tpl();
