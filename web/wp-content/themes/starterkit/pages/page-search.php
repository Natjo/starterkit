<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 */
?>

<?php get_template_part('template-parts/general/block', 'header_nav'); ?>

<form id="search" method="post" action="/">
    <input type="text" name="s">
</form>

<main id="main" role="main" tabindex="-1">
    <?php views('strate-search'); ?>
</main>

<?php get_tpl();
