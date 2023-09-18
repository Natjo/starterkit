<?php
/*
Plugin Name: Easy static
Description: Generate static site
Version: 1
Author: Martin Jonathan
*/

global $wpdb;
global $host;
global $authentification;
//global $hostfinal;
//$hostfinal = $_SERVER['SERVER_NAME'];

$home_folder =  "home";
$url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'];

// add easy_static_host to options
$result_host = $wpdb->get_results("SELECT * FROM " . $table_prefix . "options WHERE option_name = 'easy_static_host'");
if (empty($result_host)) {
    $table_options = $wpdb->prefix . 'options';
    $data = array('option_name' => "easy_static_host", 'option_value' => $url);
    $format = array('%s', '%s');
    $wpdb->insert($table_options, $data, $format);
}

$host = $result_host[0]->option_value;


// add easy_static_active to options
$easy_static_active = $wpdb->get_results("SELECT * FROM " . $table_prefix . "options WHERE option_name = 'easy_static_active'");
if (empty($easy_static_active)) {
    $table_options = $wpdb->prefix . 'options';
    $data = array('option_name' => "easy_static_active", 'option_value' => false);
    $format = array('%s', '%d');
    $wpdb->insert($table_options, $data, $format);
}

// auth if htaccess
// TODO USER/Password in bdd
$authentification = array(
    'active' => isset($_SERVER['PHP_AUTH_USER']) ? true : false,
    'user' =>  "twiice",
    'password' => "Hahngaim7ooJoohe"
);


// add easy_static_slug to options
$easy_static_sluge = $wpdb->get_results("SELECT * FROM " . $table_prefix . "options WHERE option_name = 'easy_static_slug'");
if (empty($easy_static_sluge)) {
    $table_options = $wpdb->prefix . 'options';
    $data = array('option_name' => "easy_static_slug", 'option_value' => "/");
    $format = array('%s', '%s');
    $wpdb->insert($table_options, $data, $format);
}


/* A METTRE DANS web/index.php
// load static if exist or if no generate var available
if(empty($_GET['generate'])){
if (file_exists(__DIR__ . '/wp-content/easy-static/static/' . $_SERVER['REQUEST_URI'] . '/index.html')) {
echo file_get_contents(__DIR__ . '/wp-content/easy-static/static/' . $_SERVER['REQUEST_URI'] . '/index.html');
exit;   
}
} */
// Include mfp-functions.php, use require_once to stop the script if mfp-functions.php is not found
require_once plugin_dir_path(__FILE__) . 'includes/es-functions.php';

require_once plugin_dir_path(__FILE__) . 'includes/es-admin-ajax.php';


// Create page when saving page or post
add_action('save_post', 'wpdocs_notify_subscribers', 10, 3);

function wpdocs_notify_subscribers($post_id, $post, $update)
{
    global $easy_static_active;

    if ($easy_static_active[0]->option_value) {
        if ($post->post_type == "page" || $post->post_type == "post") {
            if ($post->static_active) {
                save($post);
            }
        }
    }
}
