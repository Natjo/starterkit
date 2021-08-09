<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 */

get_header();
get_header('nav');
?>
    <main id="main" class="main">

        <?php

        if ( have_posts() ) :

            /* Start the Loop */
            while ( have_posts() ) :
                the_post();

                get_template_part( 'template-parts/content', 'post' );

            endwhile;

        endif;
        ?>

    </main>

<?php
get_footer();
