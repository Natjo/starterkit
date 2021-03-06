<?php
function is_login_page()
{
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}

function checkNonce($nonceContext)
{

    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : 0;
    if (!wp_verify_nonce($nonce, $nonceContext)) {
        exit(__('not authorized', 'domain'));
    }
}

function dateMonthInFr($date)
{
    $english_months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
    $french_months = array('Janv', 'Févr', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc');
    return str_replace($english_months, $french_months,  $date);
}


// Image url fonction id
function lsd_get_thumb($id, $size = 'medium')
{
    if ($id) {
        $img = wp_get_attachment_image_src($id, $size);
        $extension = substr($img[0], strrpos($img[0], '.') + 1);

        if ($extension == 'gif' || $extension == 'GIF') :
            $img = wp_get_attachment_image_src($id, 'full');
        endif;

        $imgUrl = is_array($img) ? reset($img) : "";

        return $imgUrl;
    }
}


// Image url function de mise en avant des articles
function lsd_get_featured($id, $size = 'medium')
{
    if ($id) {
        $img_id = get_post_thumbnail_id($id);
        $img = wp_get_attachment_image_src($img_id, $size);
        $extension = substr($img[0], strrpos($img[0], '.') + 1);

        if ($extension == 'gif' || $extension == 'GIF') :
            $img = wp_get_attachment_image_src($img_id, 'full');
        endif;

        $imgUrl = is_array($img) ? reset($img) : "";

        return $imgUrl;
    }
}

function youtube_id_from_url($url)
{
    $parts = parse_url($url);

    if (isset($parts['query'])) {
        parse_str($parts['query'], $qs);
        if (isset($qs['v'])) {
            return $qs['v'];
        } else if (isset($qs['vi'])) {
            return $qs['vi'];
        }
    }

    if (isset($parts['path'])) {
        $path = explode('/', trim($parts['path'], '/'));
        return $path[count($path) - 1];
    }

    return "";
}
