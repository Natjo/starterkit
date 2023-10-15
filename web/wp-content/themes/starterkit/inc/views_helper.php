<?php

class Strates
{
    public static function display()
    {
        $views = get_field('views', get_the_ID());
        foreach ($views as $view) {
            $name = $view['acf_fc_layout'];
            $function = str_replace("-", "_", $name);
            views($name, Strates::$function($view));
        }
    }

    public static function strate_wysiwyg($view)
    {
        $block_options = Blocks::block_options($view);

        $block_header = Blocks::block_header($view);

        $block_text = Blocks::block_text($view);

        return array_merge($block_text, $block_header, $block_options);
    }

    public static function strate_image($view)
    {
        $block_options = Blocks::block_options($view);

        $block_image = Blocks::block_image(array(
            "desktop" => new GetImage($view["block-image"]["image-desktop"], "image-desktop"),
            "mobile" => new GetImage($view["block-image"]["image-mobile"], "image-mobile"),
            //"tablet" => new GetImage($view["block-image"]["image-tablet"], "image-tablet"),
        ));

        return array_merge($block_image, $block_options);
    }

    public static function strate_image_text($view)
    {
        $block_options = Blocks::block_options($view);

        $block_options['reverse'] = $view['reverse'];

        $block_header = Blocks::block_header($view);

        $block_image = Blocks::block_image(array(
            "desktop" => new GetImage($view["block-image"]["image-desktop"], "image-desktop"),
            "mobile" => new GetImage($view["block-image"]["image-mobile"], "image-mobile"),
        ));

        $block_text = Blocks::block_text($view);

        return array_merge($block_text, $block_image, $block_header, $block_options);
    }

    public static function strate_push_articles($view)
    {
        $block_options = Blocks::block_options($view);

        $block_header = Blocks::block_header($view);

        $items = array();

        foreach ($view["items"] as $item) {
            array_push($items, Cards::card_article($item));
        }

        $args = array(
            "items" => $items,
            "cta" => $view["cta"]
        );

        return array_merge($block_header, $block_options, $args);
    }
}

class Cards
{
    public static function card_article($article)
    {
        $terms = lsd_get_the_terms_name($article->ID, 'categories');
        $args = array(
            "title" => get_the_title($article->ID),
            "date" => get_the_date("",$article->ID),
            "datetime" => get_the_date('Y-m-d',$article->ID),
            'tag' => !empty($terms) ? $terms[0] : null,
            'text' => get_field('card-news-desc', $article->ID),
            'images' => array(
                'desktop' => lsd_get_featured($article->ID, 'card-actu'),
                'width' => 440,
                'height' => 440
            )
        );

        return  $args;
    }
}

class Heros
{
    public static function hero_homepage()
    {
        $field = get_field('hero-homepage', get_the_ID());

        $image_desktop = new GetImage($field["image-desktop"], "image-desktop");

        $args = array(
            "title" => $field["title"],
            "images" =>  array(
                "desktop" => $image_desktop->url,
                "width" => $image_desktop->width,
                "height" => $image_desktop->height,
            ),
            "chapo" => $field["chapo"]

        );

        views('hero-homepage', $args);
    }
    public static function hero_simple()
    {
        $field = get_field('hero-simple', get_the_ID());

        $args = array(
            "title" => get_the_title(),
        );

        views('hero-simple', $args);
    }
}


class Blocks
{
    public static function block_options($block)
    {
        $args = array(
            'margin' => $block['block-options']['margin'],
            'background' => $block['block-options']['background'],
        );

        return  $args;
    }

    public static function block_header($block)
    {

        $args = array(
            'block-header' => [
                'title' => $block['block-header']['title'],
                'intro' =>  $block['block-header']['intro'],
            ]
        );

        return  $args;
    }

    public static function block_text($block)
    {

        $args = array(
            "block-text" => array(
                "text" =>  $block["block-text"]["text"],
            ),
        );

        return  $args;
    }

    public static function block_image($images)
    {
        $args = array(
            'block-image' => [
                'desktop' => $images['desktop']->url,
                'mobile' =>  !empty($images['mobile']) ? $images['mobile']->url : null,
                'tablet' => !empty($images['tablet']) ? $images['tablet']->url : null,
                "width" => $images['desktop']->width,
                "height" => $images['desktop']->height,
            ]
        );

        return  $args;
    }
}
