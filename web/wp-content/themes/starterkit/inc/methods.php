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
        if ("full" == $size) {
            return wp_get_original_image_url($id);
        }
        $img = wp_get_attachment_image_src($id, $size);
        $extension = substr($img[0], strrpos($img[0], '.') + 1);

        if ($extension == 'gif' || $extension == 'GIF') :
            $img = wp_get_attachment_image_src($id, 'full');
        endif;

        $imgUrl = is_array($img) ? reset($img) : "";
        $imgUrl =  wp_make_link_relative($imgUrl);
        return $imgUrl;
    }
}



// Image url function de mise en avant des articles
function lsd_get_featured($id, $size = 'medium')
{
    if ($id) {

        $img_id = get_post_thumbnail_id($id);

        if ("full" == $size) {
            $imgUrl = wp_get_original_image_url($img_id);
        } else {
            $img = wp_get_attachment_image_src($img_id, $size);
            $extension = substr($img[0], strrpos($img[0], '.') + 1);

            if ($extension == 'gif' || $extension == 'GIF') :
                $img = wp_get_attachment_image_src($img_id, 'full');
            endif;

            $imgUrl = is_array($img) ? reset($img) : "";
        }
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



/**
 * isWebp
 * for srcset picture, add ext .webp if not svg 
 */
function isWebp($img)
{
    if (!empty($img)) {
        return pathinfo($img)['extension'] != "svg" ? $img . ".webp" : $img;
    }
}

/**
 * Picture
 */
function picture($image, $class = "", $lazy = false, $breakpoints = [768, 1920])
{
    if (!empty($image)) {
        $sm = $breakpoints[0];
        $wd = $breakpoints[1];
        $lazy = !empty($lazy) ? ' loading="lazy"' : "";
        $class = !empty($class) ? ' class="' . $class . '"' : "";
        $alt = !empty($image["alt"]) ?  $image["alt"]  : "";

        $imgMobile = !empty($image['mobile']) ? $image['mobile'] : null;
        $imgTablet = !empty($image['tablet']) ? $image['tablet'] : null;
        $imgDesktop = $image['desktop'];

        echo '<picture' . $class . '>';
        if (!empty($imgMobile)) {
            echo '<source srcset="' . isWebp($imgMobile) . '" media="(max-width: ' . ($sm - 1) . 'px)" type="image/webp">';
            echo '<source srcset="' . $imgMobile . '" media="(max-width: ' . ($sm - 1) . 'px)" type="image/jpeg">';
        }

        echo '<source srcset="' . isWebp($imgDesktop) . '" media="(min-width: ' . (!empty($imgTablet) ? $wd : $sm) . 'px)" type="image/webp">';
        echo '<source srcset="' . $imgDesktop . '" media="(min-width: ' . (!empty($imgTablet) ? $wd : $sm)  . 'px)" type="image/jpeg">';

        if (!empty($imgTablet)) {
            echo ' <source srcset="' . isWebp($imgTablet) . '" media="(min-width: ' . $sm . 'px)" type="image/webp">';
            echo ' <source srcset="' . $imgTablet . '" media="(min-width: ' . $sm . 'px)" type="image/jpeg">';
        }

        echo '<img src="' . $imgDesktop . '" alt="' . $alt . '" width="' . $image['width'] . '" height="' . $image['height'] . '"' . $lazy . '>';
        echo '</picture>';
    }
}

/**
 * GetImage
 *
 */

class GetImage
{
    private $_image;
    private $_size;
    public $width = null;
    public $height = null;
    public $url = null;
    public $alt = null;
    public $id = null;

    function __construct($image, $size = null)
    {
        $this->_image = $image;
        $this->_size = $size;
        if ($image) {

            $width =  $image["width"];
            $height =  $image["height"];

            if (!empty($this->_image)) {
                $this->alt = $image["alt"];
                $this->id = $image["ID"];

                $sizes = $this->_image["sizes"];
                $img = $this->_image["url"];
                foreach ($sizes as $key => $value) {
                    if ($this->_size === $key) {
                        $img = $this->_image["sizes"][$this->_size];
                        $width =  $this->_image["sizes"][$this->_size . "-width"];
                        $height =  $this->_image["sizes"][$this->_size . "-height"];
                    }
                }
                $this->url = $img;

                $this->width =  $width;
                $this->height = $height;
            }
        }
    }
}


/**
 * SEO title and desc
 */
function lsd_seo()
{

    remove_action('wp_head', '_wp_render_title_tag', 1);

    $title = get_field('options-seo-title', 'options');
    $desc = get_field('options-seo-desc', 'options');


    if (get_field('options-seo-desc',  get_the_ID())) {
        $desc = get_field('options-seo-desc',  get_the_ID());
    } else {
        if (empty($desc)) {
            $desc = get_bloginfo('description');
        }
    }

    if (get_field('options-seo-title',  get_the_ID())) {
        $title =  get_field('options-seo-title',  get_the_ID());
        if (!is_front_page()) {
            $title =  $title . " | " . get_field('options-seo-title',  get_the_ID());
        }
    } else {
        if (empty($title)) {
            $title = get_bloginfo('name');
        }
        if (!is_front_page()) {
            $title =  $title . " | " . get_the_title();
        }
    }

    $markup = '<title>' . $title  . '</title>' . "\n";

    if (!empty($desc)) {
        $markup .= '<meta name="description" content="' . $desc . '">' . "\n";
    }

    return $markup;
}

/**
 * Create link
 *
 */
function setlink($link, $classes = "")
{
    $target = !empty($link["target"]) && $link["target"] != "" ? 'target="_blank"' : '';
    return '<a href="' . $link["url"] . '" class="' . $classes . '" ' . $target . '>' . $link["title"] . '</a>';
}

/**
 * Create link width picto
 *
 */
function setlinkIcon($link, $classes = "", $icon = "", $width = 13, $height = 17, $label = "")
{
    $target = !empty($link["target"]) ? 'target="_blank"' : '';
    return '<a ' . (!empty($label) ? ' aria-label="' . $label . '"' : "") . ' href="' . $link["url"] . '" class="' . $classes . '" ' . $target . '>' . icon($icon, $width, $height) . "<span>" . $link["title"] . "</span></a>";
}

/**
 * Icon
 */
function icon($name, $width, $height)
{
    $url = get_template_directory_uri() . '/assets/';
    return '<svg class="icon" width="' . $width . '" height="' . $height . '" aria-hidden="true" viewBox="0 0 ' . $width . ' ' . $height . '"><use xlink:href="' . $url . 'img/icons.svg#' . $name . '"></use></svg>';
}


/**
 * Options
 * block-options
 * 
 */

 function options($args){
    $margin = $args['margin']['has_margin'] ? ' ' . $args['margin']['value'] : '';
  
    if( !$args['margin']['has_margin'] && $args['background']['has_background'] ){
        $margin = $args['background']['padding'] ? ' nomargin ' . $args['background']['padding'] : '';
    }
    $background = $args['background']['color']  ? ' bg ' . $args['background']['color'] : '';

    $reverse = $args['reverse'] ? ' reverse' : '';
    
    return $reverse . $margin . $background;

 }