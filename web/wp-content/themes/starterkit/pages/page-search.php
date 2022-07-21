<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 */
?>

<?php get_template_part('template-parts/general/block', 'header_nav'); ?>

<main id="main" role="main" tabindex="-1" class="page-search">
    <form id="search" method="get" action="/">
        <input type="text" name="s">
    </form>
    <?php views('strate-search'); ?>
</main>

<?php get_tpl();
