<?php

class Strates_Helper
{
    public static function strate_wysiwyg($view)
    {
        $block_header = Blocks_Helper::block_header($view);

        $block_text = Blocks_Helper::block_text($view);

        return array_merge($block_text, $block_header);
    }

    public static function strate_image($view)
    {
        $image = array(
            'images' => [
                'desktop' => $view['img-desktop']['sizes']['image-desktop'],
                'mobile' => $view['img-mobile']['sizes']['image-mobile'],
                'tablet' => $view['img-tablet']['sizes']['image-tablet'],
                'width' => $view['img-desktop']['sizes']['image-desktop-width'],
                'height' => $view['img-desktop']['sizes']['image-desktop-height'],
            ]
        );

        return array_merge($image);
    }

    public static function strate_image_text($view)
    {
        $block_options = Blocks_Helper::block_options($view);

        $block_options['reverse'] = $view['reverse'];

        $block_header = Blocks_Helper::block_header($view);

        $block_image = Blocks_Helper::block_image(array(
            "desktop" => new GetImage($view["block-image"]["image-desktop"], "image-desktop"),
            "mobile" => new GetImage($view["block-image"]["image-mobile"], "image-mobile"),
        ));

        $block_text = Blocks_Helper::block_text($view);

        return array_merge($block_text, $block_image, $block_header, $block_options);
    }
}
