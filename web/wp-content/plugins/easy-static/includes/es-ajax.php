<?php


//$url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'];
/*
function loadPage($url)
{
    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    );
    return file_get_contents($url, false, stream_context_create($arrContextOptions));
}
*/
/*

global $allPages;
$allPages = array();*/
/*
array_push($allPages, array(
  "folder" => "/404/"
));*//*
$args = array(
    'sort_order' => 'asc',
    'sort_column' => 'post_title',
    'hierarchical' => 1,
    'exclude' => '',
    'include' => '',
    'meta_key' => '',
    'meta_value' => '',
    'authors' => '',
    'child_of' => 0,
    'parent' => -1,
    'exclude_tree' => '',
    'number' => '',
    'offset' => 0,
    'post_type' => 'page',
    'post_status' => 'publish'
);

$pages = get_pages($args);

foreach ($pages as $page) {
    $page_template = get_post_meta($page->ID, '_wp_page_template', true);
    array_push($allPages, array(
        "folder" => "/" . $page->post_name . "/"
    ));
}*/
?>

<?php
/*
function cptPage($name, $slug)
{
    global $allPages;
    $args = array(
        'post_type' => $name,
        'posts_per_page' => -1,
        'order' => 'ID',
        'orderby' => 'title',
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
    );
    $queryArticles = new WP_Query($args);
    $inc = 0;
    if ($queryArticles->have_posts()) {
        while ($queryArticles->have_posts()) {
            $queryArticles->the_post();
            $rowId = get_the_ID();
            $inc++;
            array_push($allPages, array(
                "folder" => "/" . $slug . "/" . get_post_field('post_name', $rowId) . "/"
            ));

            array_push($allPages, array(
                "folder" => "/" . $slug . "/page/" . $inc . "/"
            ));
        }
        wp_reset_postdata();
    }
}
*/
?>

<?php
/*
$args = array(
    'public'   => true,
    '_builtin' => false,
);

$output = 'names'; // names or objects, note names is the default
$operator = 'and'; // 'and' or 'or'
$post_types = get_post_types($args, $output, $operator);

foreach ($post_types  as $post_type) {
    $post_type_object = get_post_type_object($post_type);
    $slug     = $post_type_object->rewrite['slug'];
    cptPage($post_type, $slug);
}


foreach ($allPages  as $page) {
    echo $page['folder'];

    $html = loadPage($url . $page['folder']);
    if ($page['folder'] === "/home/") {
        file_put_contents(WP_CONTENT_DIR . '/static/index.html', $html);
    } else {
        mkdir(WP_CONTENT_DIR . '/static' . $page['folder'], 0755, true);
        file_put_contents(WP_CONTENT_DIR . '/static' . $page['folder'] . 'index.html', $html);
    }
}
*/