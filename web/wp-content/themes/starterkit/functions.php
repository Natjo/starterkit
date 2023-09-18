<?php
define('THEME_DIR', get_template_directory() . '/' );
define('THEME_ASSETS', get_template_directory_uri() . '/assets/');
define('THEME_URL',get_template_directory_uri() . '/');
define('HOME_URL', get_home_url());
define('AJAX_URL', admin_url('admin-ajax.php'));

if (ENV_PROD) {
    define('GTAG_KEY', get_field('params_ga_code', 'option'));
} else {
    define('GTAG_KEY', 'AIzaSyCvSv4RSBSEL6zCfuA6XIsMMcQA0cxgBno');
}

if (!ENV_LOCAL) {
    define('ACF_LITE', true);
}

require_once(__DIR__ . '/inc/datatypes.php');
require_once(__DIR__ . '/inc/configuration.php');
require_once(__DIR__ . '/inc/configuration_security.php');
if (!ENV_LOCAL) {
    require_once(__DIR__ . '/inc/acf.php');
}
require_once(__DIR__ . '/inc/methods.php');
require_once(__DIR__ . '/inc/ajax-methods.php');
require_once(__DIR__ . '/inc/custom/search.php');
require_once(__DIR__ . '/inc/custom/form.php');
require_once(__DIR__ . '/inc/custom/walker.php');

// no compression
add_filter('jpeg_quality', function ($arg) {
    return -1;
});
add_filter('wp_editor_set_quality', function ($arg) {
    return -1;
});

/**
 * Views
 * 
 * Include views with js and css, and dependencies if contains import (modules)
 * Add app.js with dependencies if contains import (modules)
 * 
 */

$json = json_decode(file_get_contents(get_template_directory() . "/assets/views.json"), true);
$views = array();
$links = array();
function views($name, $args = null, $observe = true)
{
    global $json;
    global $views;
    global $links;

    // no doublon
    if (!array_key_exists($name, $views)) {
        // add js view in array if exist
        if (!empty($json[$name]["js"])) {
            $views[$name] = array(
                "js" => $json[$name]["js"],
                "observe" => $observe
            );
        }

        // add css file if exist
        if (!empty($json[$name]["css"])) {
            $file = $json[$name]["css"];
            array_push($links, $file);
        }
    }
    get_template_part('./assets/views/' . $name . '/' .  $name . '', '', $args);
}

function views_observe()
{
    global $views;
    $arr = array();
    foreach ($views as $key => $view) {
        if ($view["js"] && $view["observe"]) array_push($arr, $key);
    }
    return json_encode($arr);
}

/**
 * paramsData
 * 
 */
function paramsData()
{
    $dataToBePassed = array(
        'ajax_url' => AJAX_URL,
        'theme_url' => THEME_URL,
        'gtag_key' =>  GTAG_KEY,
        'rgpdNonce' => wp_create_nonce('rgpdNonce'),
    );
    echo json_encode($dataToBePassed);
}



/**
 * Console
 * 
 */
function console($value)
{
    echo "<pre>";
    print_r($value);
    echo "</pre>";
}

// Array of taxonomies terms in post 
function lsd_get_the_terms_name($ID, $taxonomy)
{
    $arr = array();
    $terms = get_the_terms($ID, $taxonomy);
    if ($terms) {
        foreach ($terms as $term) {
            array_push($arr, $term->name);
        }
    }
    return $arr;
}


// MAIL
add_action('phpmailer_init', 'my_phpmailer_configuration');
function my_phpmailer_configuration($phpmailer)
{
    $phpmailer->isSMTP();
    $phpmailer->Host = 'in-v3.mailjet.com';
    $phpmailer->SMTPAuth = true; // Indispensable pour forcer l'authentification
    $phpmailer->Port = 587;
    $phpmailer->Username = 'a16fb8f8858b28ba57a608c6a9452130';
    $phpmailer->Password = '7f570d9dedc28b17bedfdd473acc019c';
    $phpmailer->SMTPSecure = "tls"; // Sécurisation du serveur SMTP : ssl ou tls
    $phpmailer->From = "mail@lonsdale.fr"; // Adresse email d'envoi des mails
    $phpmailer->FromName = "Site - Valorplast"; // Nom affiché lors de l'envoi du mail
}


// Rewrite rules for news page
function news_rewrite_url()
{
    add_rewrite_tag('%paged%', '([^&]+)');

    add_rewrite_rule(
        'actualites/page/([^/]+)',
        'index.php?pagename=actualites&paged=$matches[1]',
        'top'
    );
}
add_action('init', 'news_rewrite_url');

// remove classes and ids of wp_list_pages()
function remove_page_class($wp_list_pages)
{
    $pattern = '/\<li class="page_item[^>]*>/';
    $replace_with = '<li>';
    return preg_replace($pattern, $replace_with, $wp_list_pages);
}
add_filter('wp_list_pages', 'remove_page_class');

/*
add_action( 'wp', 'onepage' );
function onepage()
{
    get_template_part('pages/page', 'homepage');
   exit;
}*/

// -------
//template
//--------
function get_tpl()
{
    global $links;
    include("inc/tpl.php");
}

ob_start();


