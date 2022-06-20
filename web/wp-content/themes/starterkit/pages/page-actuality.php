<?php get_template_part('template-parts/general/block', 'header_nav'); ?>

<main id="main" role="main" tabindex="-1">
    <?php
    views('hero-simple', array(
        'title' => get_the_title()
    ));
    ?>

    <?php get_template_part('template-parts/general/block', 'views'); ?>
</main>

<?php get_tpl();
