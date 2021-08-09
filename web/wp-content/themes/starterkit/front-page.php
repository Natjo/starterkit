
<?php
/**
 * - Template Name: Page Home
 */
    ob_start();
?>

<main id="main" role="main">

<section>
<?php get_template_part( 'template-parts/general/block', 'views'); ?>

    <section>
        <header>
            <h2>Article récents</h2>
        </header>
        <ul>
            <?php
                $postslist = get_posts(array( 'numberposts' => 5, 'order'=> 'ASC', 'orderby' => 'title' )); 
            ?>
            <?php foreach ($postslist as $post) : setup_postdata($post); ?>
            <li>
                <h3><?= the_title(); ?></h3>
                <div class="desc">
                    <?php //echo get_field('article-summary', get_the_ID()); ?>
                </div>       
            </li>
            <?php endforeach; ?>
        </ul>
        <?php wp_reset_postdata(); ?>
    </section>
</section>
</main>

<?php
include("inc/tpl.php");