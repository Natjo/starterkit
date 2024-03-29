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


    add_image_size('image-desktop', 1200, 400, true); 
    add_image_size('image-tablet', 768, 500, true);  
    add_image_size('image-mobile', 576, 768, true); 
    
    add_image_size('card-actu', 440, 440, true); 
}

// Remove unused format
function disable_unused_format($sizes)
{
    unset($sizes['large']);
    unset($sizes['2048x2048']);
    unset($sizes['1536x1536']);
    unset($sizes['medium_large']);
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'disable_unused_format');

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

/**
 * Remove wp files/scripts
 */
// Remove wp bmocl inline style
add_action('wp_enqueue_scripts', 'mywptheme_child_deregister_styles', 20);
function mywptheme_child_deregister_styles()
{
    wp_dequeue_style('classic-theme-styles');
}

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
}


// --------------------------------------------------------------------------------------
// Désactivation de l'éditeur wordpress sur les page (pour n'avoir que les champs ACF)
// --------------------------------------------------------------------------------------
add_action('init', 'init_remove_support',100);

function init_remove_support(){
    // If not in the admin, return.
    if ( ! is_admin() ) {
        return;
     }
  
     // Get the post ID on edit post with filter_input super global inspection.
     $current_post_id = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
     // Get the post ID on update post with filter_input super global inspection.
     $update_post_id = filter_input( INPUT_POST, 'post_ID', FILTER_SANITIZE_NUMBER_INT );
  
     // Check to see if the post ID is set, else return.
     if ( isset( $current_post_id ) ) {
        $post_id = absint( $current_post_id );
     } else if ( isset( $update_post_id ) ) {
        $post_id = absint( $update_post_id );
     } else {
        return;
     }
  
     // Don't do anything unless there is a post_id.
     if ( isset( $post_id ) ) {
        // Get the template of the current post.
        $template_file = get_post_meta( $post_id, '_wp_page_template', true );
        

        // Example of removing page editor for page-your-template.php template.
        if (  'default' !== $template_file ) {
            remove_post_type_support( 'page', 'editor' );
            // Other features can also be removed in addition to the editor. See: https://codex.wordpress.org/Function_Reference/remove_post_type_support.
        }
     }
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


/*  DISABLE GUTENBERG STYLE IN HEADER| WordPress 5.9 */
function wps_deregister_styles()
{
    wp_dequeue_style('global-styles');
}
add_action('wp_enqueue_scripts', 'wps_deregister_styles', 100);


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





// Tiny MCE Custom Formats
add_filter('mce_buttons_2', 'juiz_mce_buttons_2');

if (!function_exists('juiz_mce_buttons_2')) {
    function juiz_mce_buttons_2($buttons)
    {
        array_unshift($buttons, 'styleselect');

        return $buttons;
    }
}
add_filter('tiny_mce_before_init', 'juiz_mce_before_init');
if (!function_exists('juiz_mce_before_init')) {
    function juiz_mce_before_init($styles)
    {
        $style_formats = array(
            array(
                'title' => 'small',
                'inline' => 'small'
            ),
            array(
                'title' => 'intro',
                'selector' => 'p',
                'classes' => 'intro'
            ),
            array(
                'title' => 'image',
                'block' => 'div',
                'classes' => 'img',
                'wrapper' => true,
            )
        );
        $styles['style_formats'] = json_encode($style_formats);

        return $styles;
    }
}
if (!function_exists('juiz_init_editor_styles')) {
    add_action('after_setup_theme', 'juiz_init_editor_styles');
    function juiz_init_editor_styles()
    {
        add_editor_style('assets/styles.css');
    }
}

// add class rte to tiny mce body
function wpse_editor_styles_class($settings)
{
    $settings['body_class'] = 'rte';
    return $settings;
}
add_filter('tiny_mce_before_init', 'wpse_editor_styles_class');

// Set format
function remove_h1_from_heading($args)
{
    $args['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5';
    return $args;
}
add_filter('tiny_mce_before_init', 'remove_h1_from_heading');

// Color set
function my_mce4_options($init)
{

    $custom_colours = '
        "ff0000", "red",
        "00ffff", "Color 2 name",
        "00ffff", "Color 3 name",
        "00ffff", "Color 4 name",
        "FF0000", "Color 5 name",
        "FF99CC", "Color 6 name",
        "CCFFFF", "Color 7 name"
    ';

    // build colour grid default+custom colors
    $init['textcolor_map'] = '[' . $custom_colours . ']';

    // change the number of rows in the grid if the number of colors changes
    // 8 swatches per row
    $init['textcolor_rows'] = 1;

    return $init;
}
add_filter('tiny_mce_before_init', 'my_mce4_options');

// Removes buttons from the first row of the tiny mce editor
function jivedig_remove_tiny_mce_buttons_from_editor($buttons)
{

    $remove_buttons = array(
        'strikethrough',
        'hr', // horizontal line
        'wp_more', // read more link
        'spellchecker',
        'dfw', // distraction free writing mode
        'wp_adv', // kitchen sink toggle (if removed, kitchen sink will always display)
    );
    foreach ($buttons as $button_key => $button_value) {
        if (in_array($button_value, $remove_buttons)) {
            unset($buttons[$button_key]);
        }
    }
    return $buttons;
}
add_filter('mce_buttons', 'jivedig_remove_tiny_mce_buttons_from_editor');

// Removes buttons from the second row (kitchen sink) of the tiny mce editor
function jivedig_remove_tiny_mce_buttons_from_kitchen_sink($buttons)
{

    $remove_buttons = array(
        //'formatselect', // format dropdown menu for <p>, headings, etc
        //'underline',
        'alignjustify',
        //'forecolor', // text color
        //'pastetext', // paste as text
        'removeformat', // clear formatting
        'charmap', // special characters
        'outdent',
        'indent',
        //'undo',
        //'redo',
        'wp_help', // keyboard shortcuts
        'hr', // horizontal line
    );
    foreach ($buttons as $button_key => $button_value) {
        if (in_array($button_value, $remove_buttons)) {
            unset($buttons[$button_key]);
        }
    }
    return $buttons;
}
add_filter('mce_buttons_2', 'jivedig_remove_tiny_mce_buttons_from_kitchen_sink');

// Add sup and sub
function my_mce_buttons_2($buttons)
{
    /**
     * Add in a core button that's disabled by default
     */
    $buttons[] = 'superscript';
    $buttons[] = 'subscript';
    $buttons[] = 'underline';

    return $buttons;
}
add_filter('mce_buttons_2', 'my_mce_buttons_2');


// tiny mce Formatage avec les <p>
add_filter('tiny_mce_before_init', 'prevent_deleting_pTags');
function prevent_deleting_pTags($init)
{
    $init['wpautop'] = false;
    return $init;
}
