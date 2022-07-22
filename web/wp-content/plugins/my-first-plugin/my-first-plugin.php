<?php
/*
Plugin Name: Mon premier plugin
Description: Mon premier plugin !
Author: Moi
*/

global $host;
global $hostfinal;

$host = "172.18.0.3";
$hostfinal = $_SERVER['SERVER_NAME'];
//$url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'];


// Include mfp-functions.php, use require_once to stop the script if mfp-functions.php is not found
require_once plugin_dir_path(__FILE__) . 'includes/mfp-functions.php';

require_once plugin_dir_path(__FILE__) . 'includes/mfp-admin-ajax.php';

