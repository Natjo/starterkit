<?php

/**
 * - Template Name: Page Sitemap
 */
ob_start();

$items = array();
$parentPages = new WP_Query( array(
    'post_type'      => 'page',
    'posts_per_page' => -1,
    'post_parent'    => 0,
    'order'          => 'ASC',
    'orderby'        => 'name'
));
if ($parentPages->have_posts()) :
    while ($parentPages->have_posts()) : $parentPages->the_post();
        $childs = array();
        $item = array(
            'label' => get_the_title(),
            'url' => get_the_permalink()
        );
        $childPages = new WP_Query(array(
            'post_type'      => 'page',
            'posts_per_page' => -1,
            'post_parent'    => get_the_ID(),
            'order'          => 'ASC',
            'orderby'        => 'menu_order'
        ));
        if ($childPages->have_posts()) :
            while ($childPages->have_posts()) : $childPages->the_post();
                array_push($childs, array(
                    'label' => get_the_title(),
                    'url' => get_the_permalink(),
                ));
            endwhile;
        endif;
        $item['childs'] = $childs ;
        array_push($items, $item);
    endwhile;
    wp_reset_postdata();
endif;
?>

<main id="main" role="main">
    <section>
        <header>
            <h1>Sitemap</h1>
        </header>
        <?php views("sitemap", $items);?>
    </section>
</main>

<?php
include("inc/tpl.php");
