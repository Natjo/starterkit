<?php

// load static if exist or if no dynamic var available

if(empty($_GET['dynamic'])){

    if (file_exists(__DIR__ . '/wp-content/static/' . $_SERVER['REQUEST_URI'] . '/index.html')) {    echo "***";
        echo file_get_contents(__DIR__ . '/wp-content/static/' . $_SERVER['REQUEST_URI'] . '/index.html');
        exit;
    }
}


/* 
$link = mysqli_connect(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASSWORD'), getenv('DB_NAME'));

// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}


$sql = "SELECT * FROM wp_static_options";
if ($result = mysqli_query($link, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            echo  $row['static_active'];
        }
        mysqli_free_result($result);
    } else {
        echo "No records matching your query were found.";
    }
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// Close connection
mysqli_close($link);
*/

/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
require __DIR__ . '/wp-blog-header.php';
