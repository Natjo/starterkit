<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 */

get_header();
get_header('nav');

$search_query = get_search_query();
?>
    <main id="main" class="main">

        <?php

        if ( have_posts() ) :

            while ( have_posts() ) :
                the_post();

                get_template_part( 'template-parts/content', 'search' );

            endwhile;

        endif;
        ?>

    </main>

<?php
get_footer();