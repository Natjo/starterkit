<?php
/**
 * Plugin Name: Lonsdale débug template part
 * Description: Plugin help to debug lsd_get_template_part
 * Version: 1.0.7
 * Author Lonsdale
 * Author: Lonsdale
 */

if(ENV_LOCAL){
    if(isset($_GET['debug']) && $_GET['debug'] == 1){
        define('LSD_DEBUG', true );
    }else{
        define('LSD_DEBUG', false );
    }
    add_filter( 'show_admin_bar', '__return_true' );
    add_action('admin_bar_menu', 'add_items');


    function add_items($admin_bar)
    {
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if(parse_url($actual_link, PHP_URL_QUERY)){
            $operator = "&";
        }else{
            $operator = "?";
        }

        if(isset($_GET['debug']) && $_GET['debug'] == 1){
            $paramsToRemove = 'debug=1';
            $urlLink = str_replace($paramsToRemove, 'debug=0', $actual_link);
            $labelLink = 'Désactiver le débug <span style="background-color: green;"></span>';
            define('LSD_DEBUG', true );
        }
        else if(isset($_GET['debug']) && $_GET['debug'] == 0){
            $paramsToRemove = 'debug=0';
            $urlLink = str_replace($paramsToRemove, 'debug=1', $actual_link);
            $labelLink = 'Activer le débug <span style="background-color: #888;"></span>';
            define('LSD_DEBUG', true );
        }else{
            $urlLink = $actual_link.$operator.'debug=1';
            $labelLink = 'Activer le débug <span style="background-color: #888;"></span>';
            define('LSD_DEBUG', false );

        }


        $admin_bar->add_menu( array(
            'id'    => 'lsd-debug',
            'parent' => 'top-secondary',
            'title' => $labelLink,
            'href'  => $urlLink,
            'meta'  => array(
                'title' => 'Lonsdale Débug',
            ),
        ) );
    }

    /* Change admin bar CSS */
    add_action('get_header', 'remove_marge_top');
    function remove_marge_top() {
        remove_action('wp_head', '_admin_bar_bump_cb');
    }
    function override_admin_bar_css() {
        if ( is_admin_bar_showing() && !is_admin()) { ?>
            <style type="text/css">
                #wpadminbar #wp-admin-bar-root-default,
                #wpadminbar #wp-admin-bar-my-account,
                #wpadminbar #wp-admin-bar-search{
                    display: none;
                }

                #wpadminbar{
                    background: transparent;
                    top: auto;
                    bottom: 0;
                }

                #wpadminbar #wp-admin-bar-lsd-debug a,
                #wpadminbar #wp-admin-bar-lsd-debug a strong{
                    font-size: 11px;
                }

                #wpadminbar #wp-admin-bar-lsd-debug{
                    background: rgba(0, 0, 0, .4);
                    font-size: 7px;
                }

                #wpadminbar #wp-admin-bar-lsd-debug a span{
                    display: inline-block;
                    width: 5px;
                    border-radius: 50%;
                    height: 5px;
                    margin-left: 3px;
                }

                #wpadminbar #wp-admin-bar-lsd-debug.hover a,
                #wpadminbar #wp-admin-bar-lsd-debug:hover a,
                #wpadminbar #wp-admin-bar-lsd-debug a:focus{
                    background: rgba(0, 0, 0, .6);
                    color: #eee !important;
                }
            </style>

            <?php
        }

    }
    add_action( 'wp_head', 'override_admin_bar_css' );

}else{
    define('LSD_DEBUG', false );
}
