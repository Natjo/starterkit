<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 */

get_header();
get_header('nav');
?>
    <main id="main" class="main">
        <?php echo get_field('article-hero-title');?>
        <?php get_template_part( 'template-parts/general/block', 'views'); ?>
    </main>

<?php
get_footer();
