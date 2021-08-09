<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */

get_header();
get_header('nav');
?>
    <main id="main" class="main">

        <?php

        if ( have_posts() ) :

            while ( have_posts() ) :
                the_post();

                get_template_part( 'template-parts/content', 'post' );

            endwhile;

        endif;
        ?>

    </main>

<?php
get_footer();
