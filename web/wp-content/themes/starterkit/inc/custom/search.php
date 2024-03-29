<?php

// cards
function cards($name, $rowId,$article)
{
    if ($name === 'article') {
        return Cards::card_article($article);
    }
}

function getSearchCpt($filters)
{
    $posts_per_page = !empty($filters['posts_per_page']) ? $filters['posts_per_page'] : get_option('posts_per_page');
    $paged = !empty($filters['paged']) ? $filters['paged'] : 1;
    $args = array(
        'post_type' => 'news',
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        'orderby' => 'date',
        'order' => 'DESC',
        //'tax_query' => array($tax_query),
    );

    if (!empty($filters['s'])) {
        $args['s'] = $filters['s'];
    }

    $queryArticles = new WP_Query($args);

    $items = [];
    if ($queryArticles->have_posts()) {
        while ($queryArticles->have_posts()) {
            $queryArticles->the_post();
            $rowId = get_the_ID();
            $items[] = cards($filters['card'], $rowId,$queryArticles->post);
        }
        wp_reset_postdata();
    }

    $args['posts_per_page'] = -1;
    $queryArticleCount = new WP_Query($args);
    $totalPages = ceil($queryArticleCount->post_count / $posts_per_page);
    $totalPosts = $queryArticleCount->post_count;

    $pager = [
        'current_page' => $paged,
        'total_pages' => $totalPages,
        'total_posts' => $totalPosts
    ];

    return [
        'pager' => $pager,
        'items' => $items
    ];
}

/**
 * 
 * Pagination for search page / cpt archive or page
 * 
 * @param array $pager {
 *      @type number $current_page - page active
 *      @type number $total_pages - total pages
 * }
 * 
 * @param string $query set the slug url or query string 
 * 
 * 
 */

function pager($pager, $query = null)
{
    $page = $pager['current_page'];
    $total = $pager['total_pages'];
    $prev = $page - 1;
    $next = $page + 1;
    $arr = array();
    $offset = 3;
    $break = 4;

    if ($total > 1) {

        if ($total > $break + $offset + 1) {

            if ($page <= $break) {
                for ($i = 1; $i <= $offset + $break; $i++) {
                    if ($i <= $page + $offset && $i <= $total) {
                        array_push($arr, $i);
                    }
                }
            } else {
                array_push($arr, 1);
                array_push($arr, null);
            }

            if ($page > $break && $page <= $total - $break) {
                for ($i = $offset; $i < $total; $i++) {
                    if ($i <= $page + $offset  && $i >= $page - $offset) {
                        array_push($arr, $i);
                    }
                }
            }
            if ($page > $total - $break) {
                for ($i = $total - $break - $offset; $i <= $total; $i++) {
                    if ($i >= $page - $offset) {
                        array_push($arr, $i);
                    }
                }
            } else {
                array_push($arr, null);
                array_push($arr, $total);
            }
        } else {
            for ($i = 1; $i <= $total; $i++) {
                array_push($arr, $i);
            }
        }

        //
        echo '<div class="pager">';
        echo $prev >= 1 ? '<a rel="prev" href="' . $query . $prev . '" class="btn-1 prev">Prev</a>' : '<span class="disabled">Prev</span>';
        for ($i = 0; $i < count($arr); $i++) {
            $index = $arr[$i];
            $active = ($index === $page) ? ' class="active"' : '';
            echo ($index === null) ?  "<span>...</span>" :  '<a href="' . $query . $index . '"' . $active . '>' . $index . '</a>';
        }
        echo $next <= $total ? '<a rel="next" href="' . $query  . $next . '" class="btn-1 next">Next</a>' : '<span class="disabled">Next</span>';
        echo '</div>';
    }
}
