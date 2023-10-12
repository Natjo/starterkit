<?php

class Blocks_Helper
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
