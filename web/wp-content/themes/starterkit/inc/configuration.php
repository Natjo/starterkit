<?php

function theme_setup() {

    load_theme_textdomain('theme', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ),
    'post-thumbnails'
    );

    register_nav_menus(array(
        'menu-header' => 'Menu Principal',
        'menu-footer' => 'Menu Footer',
    ));

    show_admin_bar(false);

    add_image_size( 'thumbnail_example', 600, 800, array( 'center', 'center' ) );

}

// function remove_default_post_type_menu_bar( $wp_admin_bar ) {
//     $wp_admin_bar->remove_node( 'new-post' );
// }

// function remove_draft_widget() {
//     remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
// }

//REMOVE FILE TYPE
add_filter('style_loader_tag', 'remove_type_attr', 10, 2);
add_filter('script_loader_tag', 'remove_type_attr', 10, 2);

function remove_type_attr($tag, $handle) {
    return preg_replace("/type=['\"]text\/(javascript|css)['\"]/", '', $tag);
}

function remove_menus() {
    //remove_menu_page( 'edit.php' );
    remove_menu_page( 'edit-comments.php' );          //Comments
}
add_action( 'admin_menu', 'remove_menus' );

function acf_add_main_options() {
    if( function_exists('acf_add_options_page') ) {
        acf_add_options_page( 'Paramètres' );
    }
}

add_filter( 'wp_default_scripts', 'removeJqueryMigrate' );
function removeJqueryMigrate(&$scripts){
    if(!is_admin()){
        $scripts->remove('jquery');
        $scripts->add('jquery', false, array('jquery-core'), '1.4.1');
    }
}

add_action( 'after_setup_theme', 'theme_setup' );
// add_action( 'admin_bar_menu', 'remove_default_post_type_menu_bar', 999 );
// add_action( 'wp_dashboard_setup', 'remove_draft_widget', 999 );
add_action( 'wp_loaded', 'acf_add_main_options' );

//REMOVE : emoji 🗑
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');

// Remove wp-embed.min.js
function my_deregister_scripts(){
    wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'my_deregister_scripts' );

// Remove default css
function smartwp_remove_wp_block_library_css(){
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS
} 
add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100 );



// --------------------------
// Scripts et style
// --------------------------
add_action( 'init', 'scripts_site' );

function scripts_site(){
    if ( !is_admin() && !is_login_page() ) wp_deregister_script('jquery');
    if( !is_admin() || !is_user_logged_in() ){
        wp_enqueue_style( 'style_principal', get_template_directory_uri() . '/assets/styles.css', array(), filemtime(get_template_directory() . '/assets/styles.css' ) );
    }
}


// --------------------------------------------------------------------------------------
// Désactivation de l'éditeur wordpress sur les page (pour n'avoir que les champs ACF)
// --------------------------------------------------------------------------------------
add_action('init', 'init_remove_support',100);

function init_remove_support(){
    remove_post_type_support( "page", 'editor');
}
//Change body classes generated by Wordpress
// add_filter('body_class', 'add_body_classes');

// function add_body_classes($classes) {
//     $detect = array();

//     if (!preg_match('/opera|webtv/i', $_SERVER['HTTP_USER_AGENT']) && (preg_match('/msie\s(\d*)/i', $_SERVER['HTTP_USER_AGENT'], $version) || preg_match('/trident\/.*rv:(\d*)/i', $_SERVER['HTTP_USER_AGENT'], $version))|| preg_match('/Edge\/(\d*)/i', $_SERVER['HTTP_USER_AGENT'], $version)) {
//         $detect['browser'] = 'ie';

//         if(count($version) > 1) {
//             $detect['browser'] .= ' ie' . $version[1];
//         }
//     } else {
//         $detect['browser'] = '';
//     }

//     $classes[] = $detect['browser'];
//     return $classes;
// }
