<?php
define('THEME_DIR', get_template_directory() . '/');
define('THEME_URL', get_template_directory_uri() . '/');
define('HOME_URL', get_home_url());
define('AJAX_URL', admin_url('admin-ajax.php'));
$test = get_fields('options');
define('OPTION_RGPD', $test);


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
require_once(__DIR__ . '/inc/tinymce.php');

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
        // add js view in array
        $views[$name] = array(
            "js" => $json[$name]["js"],
            "observe" => $observe
        );
       
        // add css file if exist
        if ($json[$name]["css"]) {
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
        //'options_rgpd' => OPTION_RGPD
    );
    echo json_encode($dataToBePassed);
}

/**
 * Picture
 * 
 */
function picture($args)
{
    get_template_part('template-parts/general/block', 'picture', array(
        'images' => $args['images'],
        'width' => isset($args['width']) ? ' width="' . $args['width'] . '"' : '',
        'height' => isset($args['height']) ? ' height="' . $args['height'] . '"' : '',
        'alt' => isset($args['alt']) ? $args['alt'] : NULL,
        'lazy' => isset($args['lazy']) ? ' loading="lazy"' : ''
    ));
}

/**
 * Icon
 * 
 */
function icon($name, $width, $height)
{
    return '<svg class="icon" width="' . $width . '" height="' . $height . '" aria-hidden="true" viewBox="0 0 20 20"><use xlink:href="' . THEME_URL . 'assets/img/icons.svg#' . $name . '"></use></svg>';
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


/*
 MAIL

add_action( 'phpmailer_init', 'my_phpmailer_configuration' );
function my_phpmailer_configuration( $phpmailer ) {
  $phpmailer->isSMTP();   
  $phpmailer->Host = 'xxxx';
  $phpmailer->SMTPAuth = true; // Indispensable pour forcer l'authentification
  $phpmailer->Port = 465;
  $phpmailer->Username = 'xxxx@xxxx.com';
  $phpmailer->Password = 'xxxx';
  $phpmailer->SMTPSecure = "ssl"; // Sécurisation du serveur SMTP : ssl ou tls
  $phpmailer->From = "xxxx@xxxx.com"; // Adresse email d'envoi des mails
  $phpmailer->FromName = "Site - Clos Cristal"; // Nom affiché lors de l'envoi du mail
}


$to = 'j.burt@lonsdale.fr';
$subject = 'The subject';
$body = 'The email body content';
$headers = array('Content-Type: text/html; charset=UTF-8'); 
wp_mail( $to, $subject, $body, $headers );

*/




// remove classes/ids items of Walker_Nav_Menu
add_filter('nav_menu_item_id', 'clear_nav_menu_item_id', 10, 3);
function clear_nav_menu_item_id($id, $item, $args)
{
    return "";
}

add_filter('nav_menu_css_class', 'clear_nav_menu_item_class', 10, 3);
function clear_nav_menu_item_class($classes, $item, $args)
{
    return array();
}

// template
function get_tpl(){
    global $links;
    include("inc/tpl.php");
}

ob_start();