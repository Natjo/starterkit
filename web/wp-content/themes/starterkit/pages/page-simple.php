<?php
/*
Template Name: Page simple
*/

?>

<?php get_template_part('template-parts/general/block', 'header_nav'); ?>

<main id="main" role="main" tabindex="-1" class="page-simple">
    <?php
    views('hero-simple', array(
        'title' => get_the_title()
    ));
    ?>

    <section class="container rte">
        <?= get_the_content(); ?>
    </section>
</main>

<?php get_tpl();
