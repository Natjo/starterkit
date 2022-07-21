<?php
/*
Plugin Name: Mon premier plugin
Description: Mon premier plugin !
Author: Moi
*/

global $url;
$url = 'https://172.18.0.3';
//$url = 'https://starterkit.code';
//$url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'];
$url = "https://newsletter.preprod.lonsdale.fr";

//$url = "localhost";


// Include mfp-functions.php, use require_once to stop the script if mfp-functions.php is not found
require_once plugin_dir_path(__FILE__) . 'includes/mfp-functions.php';

require_once plugin_dir_path(__FILE__) . 'includes/mfp-admin-ajax.php';

